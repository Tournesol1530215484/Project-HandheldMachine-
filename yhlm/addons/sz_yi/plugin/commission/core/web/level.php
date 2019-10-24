<?php
/**
 * 分销商等级
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */


//ALTER TABLE `ims_sz_yi_commission_level` ADD `authority` VARCHAR( 255 ) NOT NULL COMMENT '分销权限' AFTER `weight` 
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$set = $this->getSet();

/* print_r("<pre>");
print_r($set); */
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_commission_level') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY commission1 asc");
$leveltype = intval($set['leveltype']);
//Author:Y.yang Date:2016-04-08 Content:成为分销商条件（购买条件）
$goods = false;
if (!empty($set['become_goodsid'])) {
	$goods = pdo_fetch('select id,title from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1 ', array(
		':id' => $set['become_goodsid'],
		':uniacid' => $_W['uniacid']
	));
}
// END

/**
 * 分销商等级列表
 */
if ($operation == 'display') {
    ca('commission.level.view');
    $list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_commission_level') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY commission1 asc");
	foreach($list as $key => $value ){
		//$level = pdo_fetch("SELECT id,levelname FROM " . tablename('sz_yi_member_level') . " WHERE uniacid = '{$_W['uniacid']}' AND commission_level_id =". $value['id']);
		//$list[$key]['member_level'] = $level;
		$value['authority'] = unserialize($value['authority']);
		if( $value['authority']['is_withdraw'] == 1 ){   $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '佣金提现' : ',佣金提现'; }
		if( $value['authority']['is_qrcode'] == 1 ){     $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '推广二维码' : ',推广二维码'; } 
		if( $value['authority']['is_optional'] == 1 ){   $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '自选商品' : ',自选商品'; }
		if( $value['authority']['is_shop'] == 0 ){       $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '我的小店' : ',我的小店'; }
		if( $value['authority']['show_goods'] == 1 ){    $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '订单商品详情' : ',订单商品详情'; }
		if( $value['authority']['show_customer'] == 1 ){ $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '订单购买者详情' : ',订单购买者详情'; }
		if( $value['authority']['is_remind'] == 1 ){     $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '消息提醒' : ',消息提醒'; }
		if( $value['authority']['is_message'] == 1 ){    $list[$key]['authority_item'] .= empty($list[$key]['authority_item'])? '开启留言' : ',开启留言'; }
		if( empty($list[$key]['authority_item'])){
			$list[$key]['authority_item'] = '无';
		}
	}

/**
 * 添加/编辑分销商等级
 */
} elseif ($operation == 'defaultPost') { 
	//默认分销等级
	if (checksubmit('submit')) {
		$data = is_array($_GPC['setdata']) ? array_merge($set, $_GPC['setdata']) : array();
		$this->updateSet($data);
		m('cache')->set('template_' . $this->pluginname, $data['style']);
		plog('commission.set', '修改基本设置');
		message('设置保存成功!', referer(), 'success');
	}
 
} elseif ($operation == 'post') {
	//新增分销等级
	$id = intval($_GPC['id']);
	//分销等级
	$level = pdo_fetch("SELECT * FROM " . tablename('sz_yi_commission_level') . " WHERE id = '$id'");
	$updatelevel = @json_decode($level['updatelevel'], true);
	$level['authority'] = unserialize($level['authority']);
	if (empty($id)) {
		ca('commission.level.add');
		$level['authority']['is_withdraw'] = 2;
		$level['authority']['is_qrcode'] = 2;
		$level['authority']['is_optional'] = 2;
		$level['authority']['show_goods'] = 2;
		$level['authority']['show_customer'] = 2;
		$level['authority']['is_remind'] = 2;
		$level['authority']['is_message'] = 2;
	} else {
		ca('commission.level.view|commission.level.edit');
		//会员等级
		$member_level = pdo_fetchAll("SELECT id,levelname FROM " . tablename('sz_yi_member_level') . " WHERE uniacid = '{$_W['uniacid']}' AND commission_level_id =". $level['id']);
	}

	//提交保存
	if (checksubmit('submit')) {
		if (empty($_GPC['levelname'])) {
			message('抱歉，请输入分类名称！');
		}
		if( $_GPC['authority']['is_shop'] ){
			$_GPC['authority']['is_shop'] = 0;
		}else{
			$_GPC['authority']['is_shop'] = 1;
			$_GPC['authority']['is_optional'] = 0;
		}	
		
		$data = array(
			'uniacid' => $_W['uniacid'], 
			'levelname' => $_GPC['levelname'], 
			'commission1' => $_GPC['commission1'], 
			'commission2' => $_GPC['commission2'], 
			'commission3' => $_GPC['commission3'], 
			'commission4' => $_GPC['commission4'], 
			'commission5' => $_GPC['commission5'], 
			'commission6' => $_GPC['commission6'], 
			'commission7' => $_GPC['commission7'], 
			'commission8' => $_GPC['commission8'], 
			'commission9' => $_GPC['commission9'], 
			'commission10' => $_GPC['commission10'], 
			'commission11' => $_GPC['commission11'], 
			'commission12' => $_GPC['commission12'],
			'commission13' => $_GPC['commission13'], 
			'commission14' => $_GPC['commission14'], 
			'commission15' => $_GPC['commission15'], 			
			'commissionmoney' => $_GPC['commissionmoney'], 
			'ordermoney' => $_GPC['ordermoney'], 
			'ordercount' => intval($_GPC['ordercount']), 
			'downcount' => intval($_GPC['downcount']),
			'tiaojian' => intval($_GPC['tiaojian']), 
			'updatelevel' => json_encode($_GPC['updatelevel']), 
			'weight' => intval($_GPC['weight']),
			'autoupdate' => $_GPC['autoupdate'],
			'authority' => serialize($_GPC['authority']),
			'spread_qrcode' => $_GPC['authority']['is_qrcode']
		);		
		
		if (!empty($id)) {
			$data['updatemember'] = intval($_GPC['updatemember']);
			pdo_update('sz_yi_commission_level', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
			plog('commission.level.edit', "修改分销商等级 ID: {$id}");
		} else {
			pdo_insert('sz_yi_commission_level', $data);
			$id = pdo_insertid();
			plog('commission.level.add', "添加分销商等级 ID: {$id}");
		}
		saveMemberlevel($id, $data);//新增默认会员等级2016.09.08
		message('更新等级成功！', $this->createPluginWebUrl('commission/level', array('op' => 'display')), 'success');
	}
	
/**
 * 删除分销商等级
 *
 * @param  $ 
 * @param  $ 
 * @return array
 */	
} elseif ($operation == 'delete') {
	ca('commission.level.delete');
	$id = intval($_GPC['id']);
	$level = pdo_fetch("SELECT id,levelname FROM " . tablename('sz_yi_commission_level') . " WHERE id = '$id'");
	if (empty($level)) {
		message('抱歉，等级不存在或是已经被删除！', $this->createPluginWebUrl('commission/level', array('op' => 'display')), 'error');
	}
	pdo_delete('sz_yi_commission_level', array('id' => $id, 'uniacid' => $_W['uniacid']));	
	plog('commission.level.delete', "删除分销商等级 ID: {$id} 等级名称: {$level['levelname']}");
	//2016.09.08新增删除会员等级
	$member_level    = pdo_fetch("SELECT id,levelname FROM " . tablename('sz_yi_member_level') . " WHERE commission_level_id = '$id'");
	$member_level_id = $member_level['id'];
	pdo_delete('sz_yi_member_level', array( 'id' => $member_level_id, 'uniacid' => $_W['uniacid']));
    plog('member.level.delete', "删除会员等级 ID: {$member_level_id} 等级名称: {$member_level['levelname']}");
	//end
	message('等级删除成功！', $this->createPluginWebUrl('commission/level', array('op' => 'display')), 'success');
}
load()->func('tpl');
include $this->template('level');

/**
 * 新增/编辑对应会员级别信息
 *
 * @param  $id 分销级别ID
 * @param  $data 分销级别数据
 * @return array
 */
function saveMemberlevel($id, $data){
	global $_W, $_GPC;
	$arrayData = array(
		'commission_level_id' => $id,//分销等级ID
		'uniacid'    => $_W['uniacid'],
		'level'      => $data['weight'],//等级权重，数字越大越高级
		'levelname'  => $data['levelname'],//等级名称
		'ordercount' => 0,//订单数量
		'ordermoney' => 0,//升级条件
		'discount'   => 0//折扣
	);
	$level = pdo_fetch("SELECT id,levelname FROM " . tablename('sz_yi_member_level') . " WHERE commission_level_id = '$id'");
	if (!empty($level)) {
		pdo_update('sz_yi_member_level', $arrayData, array('id' => $id, 'uniacid' => $_W['uniacid']));
		plog('member.level.edit', "修改会员等级 ID: {$id}");
	} else {
		pdo_insert('sz_yi_member_level', $arrayData);
		$memberid = pdo_insertid();
		pdo_update('sz_yi_commission_level', array('updatemember' => $memberid), array('id' => $id, 'uniacid' => $_W['uniacid']));
		plog('member.level.add', "添加会员等级 ID: {$id}");
	}
	return;
}
