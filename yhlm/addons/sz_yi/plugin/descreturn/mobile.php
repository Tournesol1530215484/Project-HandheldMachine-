<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class DescreturnMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('descreturn');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	/**
	 * [此方法不需要登录就可以访问]
	 * @return null
	 * 系统定时访问，切不可手动访问！
	 * http://kh39.61why.com/app/index.php?i=2&c=entry&p=descreturn&method=api&m=sz_yi&do=plugin
	 */
	public function api()
	{
		$this->descreturn(); // 递减全返
		$this->promptly();   // 消费返
	}

	/**
	 * 执行递减全返的方法
	 * @return null
	 */
	public function descreturn()
	{

		global $_W;
		$sql = 'select * from'.tablename('sz_yi_descreturn_set').'where uniacid=:uniacid';
		$set = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid']));

		// 是否开启递减全返
		if (!$set['isopen']) {
			return false;
		}
		// 查询全返汇总表 进行全返
		$sql = 'select * from'.tablename('sz_yi_descreturn_list').'where uniacid=:uniacid';
		$return = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid']));

		if (empty($return)) {
			exit('暂无全返订单');
		}

		echo "descreturn <hr>";
		foreach ($return as $key => $value) {
			if (empty($value['openid'])) {
				echo '找不到收款人';
				continue;
			}
			// 关闭全返的订单
			if (!$value['status']) {
				echo '关闭全返的订单';
				continue;
			}
			// 全返停止了
			if ($value['surplus_price'] <= $set['stopcondition']) {
				echo "全返停止了 待全返金额少于{$set['stopcondition']}，就停止全返 <br>";
				continue;
			}
			// 本次需要全返的金额
			$presentM = $value['surplus_price'] * ($set['scale'] / 100);
			echo "{$value['openid']}=>总：{$value['need_price']}本次返的金额{$presentM}";
			// 剩余全返金额
			$surplus_price = $value['surplus_price'] - $presentM;
			echo "剩余全返金额{$surplus_price},<br>";

			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $value['openid'],
				'need_price' => $value['need_price'],
				'surplus_price' => $surplus_price,
				'this_price' => $presentM,
				'createtime' => time()
				);
			if ($presentM > 0) {
				$result = pdo_insert('sz_yi_descreturn_log', $data);
			}
			if (!empty($result)) {
				$update = array(
					'surplus_price' => $surplus_price
					);
				pdo_update('sz_yi_descreturn_list', $update, array('openid'=>$value['openid']));
				// 全返至积分
				m('member')->setCredit($value['openid'], 'credit1', $presentM, array($_W['uid'], '全返到积分'));
			}
		}
	}
	/**
	 * 执行消费返的方法
	 * 消费者购买供应商的商品，平台立即反订单金额的84%给供应商，剩余16%每天以0.05%返给供应商(余额返还)
	 * @return null
	 */
	public function promptly()
	{
		global $_W;
		// 消费返设置
		$sql = 'select scale from'.tablename('sz_yi_descreturn_set').'where uniacid=:uniacid';
		$set = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid']));
		if (empty($set)) {
			echo '没有设置消费返设置';
			return; // 没有设置消费返设置
		}
		$sql = 'select * from'.tablename('sz_yi_promptly_list').'where uniacid=:uniacid';
		$plist = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid']));
		if (empty($plist)) {
			echo '没有消费返订单';
			return; // 没有消费返订单
		}

		foreach ($plist as $key => $value) {
			if (empty($value['supplier_openid'])) {
				echo '找不到收款人';
				continue;
			}
			if ($value['surplus_price'] <= 0) {
				continue; // 钱返完了
			}
			$return = round($value['surplus_price'] * ($set['scale'] / 100), 2); // 剩余全返的金额一天天返还 如 万分之5
			echo '本次返',$return,'<br>';
			$surplus_price = round($value['surplus_price'] - $return, 2); // 剩余返回的金额
			echo '剩余返',$surplus_price,'<br>';
			m('member')->setCredit($value['supplier_openid'], 'credit1', $return, array($_W['uid']));
			// 插入记录表
			$data =  array(
				'uniacid'       => $_W['uniacid'],
				'openid'        => $value['supplier_openid'],
				'need_price'    => round($value['need_price'], 2),
				'surplus_price' => round($surplus_price, 2),
				'this_price'    => round($return, 2),
				'createtime'    => time(),
				);
			if ($data['this_price'] > 0) {
				pdo_insert('sz_yi_promptly_log', $data);
			}
			// 返完了更新sz_yi_promptly_list 表
			pdo_update('sz_yi_promptly_list', array('surplus_price'=>$surplus_price), array('id'=>$value['id']));
		}
	}

	/*public function promptly_back() // 备份
	{
		global $_W;
		// 消费返设置
		$sql = 'select scale from'.tablename('sz_yi_descreturn_set').'where uniacid=:uniacid';
		$set = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid']));
		if (empty($set)) {
			echo '没有设置消费返设置';
			return; // 没有设置消费返设置
		}
		$sql = 'select * from'.tablename('sz_yi_promptly_list').'where uniacid=:uniacid';
		$plist = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid']));
		if (empty($plist)) {
			echo '没有消费返订单';
			return; // 没有消费返订单
		}

		foreach ($plist as $key => $value) {
			$return = round($value['surplus_price'] * ($set['scale'] / 100), 2); // 剩余全返的金额一天天返还 如 万分之5
			echo '本次返',$return,'<br>';
			$surplus_price = round($value['surplus_price'] - $return, 2); // 剩余返回的金额
			echo '剩余返',$surplus_price,'<br>';
			m('member')->setCredit($value['openid'], 'credit1', $return, array($_W['uid']));
			// 插入记录表
			$data =  array(
				'uniacid' => $_W['uniacid'],
				'openid' => $value['openid'],
				'need_price' => round($value['need_price'], 2),
				'surplus_price' => round($surplus_price, 2),
				'this_price' => round($return, 2),
				'createtime' => time(),
				);
			pdo_insert('sz_yi_promptly_log', $data);
			// 返完了更新sz_yi_promptly_list 表
			pdo_update('sz_yi_promptly_list', array('surplus_price'=>$surplus_price), array('id'=>$value['id']));
		}
	}*/
}