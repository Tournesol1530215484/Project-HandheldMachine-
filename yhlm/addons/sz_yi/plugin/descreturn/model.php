<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

if (!class_exists('DescreturnModel')) {
	class DescreturnModel extends PluginModel
	{
		/**
		 * 下单时调用
		 * @param  int $orderid 订单ID
		 * @return null
		 */
		public function insertDescReturnOrder($orderid='')
		{
			if (empty($orderid)) {
				return;
			}
			global $_W;
			// 查询该订单的开启全返的所有商品
			$sql = 'select og.goodsid,og.price,o.openid from'.tablename('sz_yi_order_goods').' as og,'.tablename('sz_yi_goods').' as g,'.tablename('sz_yi_order').' as o where og.uniacid=:uniacid and og.orderid=:orderid and og.goodsid=g.id and g.isdescreturn=1 and o.id=og.orderid';
			$goodsList = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid'], ':orderid'=>$orderid));
			if (empty($goodsList)) {
				// 暂无全返商品;
				return;
			}
			$data = array();
			// print_r($goodsList);exit;
			// 入库sz_yi_descreturn_order全返表
			// 查询对应等级的返钱比例
			// $sql = 'select l.descscale from'.tablename('sz_yi_member').' as m left join '.tablename('sz_yi_member_level').' as l on m.level=l.id where m.uniacid=:uniacid and m.openid=:openid';
			foreach ($goodsList as $value) {
				// 查询会员等级，等级不同 返的钱不同 level
				// $level = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $value['openid']));
				// $value['price']     *= ($level['descscale'] / 100); // 实际要返的 (暂时不做)
				$value['uniacid']    = $_W['uniacid'];
				$value['createtime'] = time();
				// print_r($value);exit;
				pdo_insert('sz_yi_descreturn_order', $value); // 入库

				// 入库全返金额汇总表
				$sql = 'select openid,sum(need_price) as need_price,sum(surplus_price) as surplus_price from'.tablename('sz_yi_descreturn_list').'where uniacid=:uniacid and openid=:openid';
				$descreturn_list = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':openid'=>$value['openid']));

				// 汇总表里有数据就update 没有就insert
				if ($descreturn_list['openid']) {
					// 更新
					$data = array(
					    'need_price'    => $value['price'] + $descreturn_list['need_price'],
					    'surplus_price' => $value['price'] + $descreturn_list['surplus_price'],
					);
					$result = pdo_update('sz_yi_descreturn_list', $data, array('openid' => $value['openid']));
				} else {
					// 插入
					$data['uniacid']       = $_W['uniacid'];
					$data['need_price']    = $value['price'];
					$data['surplus_price'] = $value['price'];
					$data['openid']        = $value['openid'];
					$data['createtime']    = time();
					pdo_insert('sz_yi_descreturn_list', $data);
				}
			}
		}

		/**
		 * 消费者购买供应商的商品，如:平台立即反订单金额的84%给供应商，剩余16%每天返给供应商
		 * @param  int $orderid 订单ID
		 * @return null
		 */
		public function promptly($orderid='')
		{
			if (empty($orderid)) {
				return false;
			}
			global $_W;
			// 查询消费返设置
			$sql = 'select * from'.tablename('sz_yi_descreturn_set').'where uniacid=:uniacid';
			$set = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));

			// 查询购买人
			$sql = 'select openid from'.tablename('sz_yi_order').'where uniacid=:uniacid and id=:orderid';
			$os = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':orderid' => $orderid));
			$purchaser = $os['openid'];
			// 1.查询订单商品属于哪个供应商
			$sql = 'select supplier_uid,price from'.tablename('sz_yi_order_goods').'where uniacid=:uniacid and orderid=:orderid';
			$ogs = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':orderid' => $orderid));
			$supplier_uid = $ogs['supplier_uid'];
			if (empty($supplier_uid)) {
				return; // 这个商品不属于某个供应商就停止
			}
			// 2.立即把84%的订单金额返给供应商（余额）
			$sql = 'select openid from'.tablename('sz_yi_perm_user').'where uniacid=:uniacid and uid=:uid';
			$openids = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':uid' => $supplier_uid));
			$openid = $openids['openid'];
			if (empty($openid)) {
				return; // 找不到供应商的openid
			}
			$supplier_return = round($ogs['price'] * ($set['sellerscale'] / 100), 2); // 如：84% 返给供应商 16% 入库 sz_yi_promptly_list
			// $c16 = round($ogs['price'] - $supplier_return, 2);
			$c16 = round($ogs['price'] * ($set['userscale'] / 100), 2);

			$data = array(
				'uniacid'         => $_W['uniacid'],
				'openid'          => $purchaser, // 购买人的openid
				'money'           => $ogs['price'], // 订单总额
				'supplier_openid' => $openid , // 供应商的openid
				'supplier_return' => $supplier_return , // 返给供应商84%的钱
				'need_price'      => $c16, // 需要返还总额
				'surplus_price'   => $c16, // 剩余的价格
				'createtime'      => time(),
				'oid'      => $orderid,
				);
			pdo_insert('sz_yi_promptly_list', $data);

			m('member')->setCredit($openid, 'credit2', $supplier_return, array($_W['uid'])); // 把84%的订单金额返给供应商（余额）
		}





		/**
		 * 消费者购买供应商的商品，返订单金额的佣金比例到供应商openid的上层用户的余额
		 * @param  int $orderid 订单ID
		 * @return null
		 */
		public function moneyratio($orderid='')
		{	

			if (empty($orderid)) {
				return false;
			}
			global $_W;

			$isexchange=pdo_fetchcolumn('select isexchange from '.tablename('sz_yi_order').' where id = :id',array(':id'=>$orderid));
			if (!empty($isexchange)) {	//易货订单不参与供应商返现
				return;
			}			 	 		 
			// 1.查询订单商品属于哪个供应商
			$sql = 'select supplier_uid,price from'.tablename('sz_yi_order_goods').'where uniacid=:uniacid and orderid=:orderid';
			$ogs = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':orderid' => $orderid));
			$supplier_uid = $ogs['supplier_uid'];

			if (empty($supplier_uid)) {
				return; // 这个商品不属于某个供应商就停止
			}

			//查出制造商的openID
			$sql = 'select openid,username from'.tablename('sz_yi_perm_user').'where uniacid=:uniacid and uid=:uid';
			$openid = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':uid'=>$supplier_uid)); 
			$openidd = $openid['openid'];

			//查出openID的上家的member id
			$sql = 'select agentid from'.tablename('sz_yi_member').'where uniacid=:uniacid and openid=:openid';
			$agentid = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':openid'=>$openidd));
			$agentid = $agentid['agentid'];


			//查出openID的上家的member openid
			$sql = 'select openid from'.tablename('sz_yi_member').'where uniacid=:uniacid and id=:id';
			$openids = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':id'=>$agentid));
			$openids = $openids['openid'];



			//判断该供应商有没有上级
			if($agentid == 0){
				return; // 这个供应商如果没有上级就停止
			}else{
				
				//计算供应商订单给上级的佣金
				$set = p('supplier') -> getSet();
				$ratiomoney = $ogs['price'] * $set['moneyratio'] / 100;
				
				m('member')->setCredit($openids, 'credit2', $ratiomoney, array($_W['uid'],'供应商返现金额'.'('.$openid['username'].')')); 
			}

			
	

		}
	}
}
