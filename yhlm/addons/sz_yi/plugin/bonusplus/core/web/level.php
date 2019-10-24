<?php
/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$set       = $this->getSet();
$leveltype = $set['leveltype'];

if ($operation == 'display') {
	ca('bonusplus.level.view');
	$list = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_bonusplus_level') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY level asc");
	
	//var_dump($prodlist);exit;
} elseif ($operation == 'post') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		ca('bonusplus.level.add');
	} else {
		ca('bonusplus.level.view|bonusplus.level.edit');
	}
	$pindex = max(1, intval($_GPC["page"]));
	$psize = 10;
	$level = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_bonusplus_level') . " WHERE id = '$id'");
	$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id asc";
	// $sql .= " limit " . ($pindex - 1) * $psize . "," . $psize;
	$prodlist = pdo_fetchall($sql);
	
	// $total = pdo_fetchcolumn("select count(id) from" . tablename("sz_yi_goods") . " where uniacid =" . $_W["uniacid"]);
	// $pager = pagination($total, $pindex, $psize);
	if (checksubmit('submit')) {
		if (empty($_GPC['levelname'])) {
			message('抱歉，请输入名称！');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'level' => $_GPC['level'],
			'levelname' => $_GPC['levelname'],
			'agent_money' => floatval($_GPC['agent_money']),
			'commissionmoney' => $_GPC['commissionmoney'],
			'ordermoney' => $_GPC['ordermoney'],
			'ordercount' => intval($_GPC['ordercount']),
			'downcount' => intval($_GPC['downcount']),
			'downcountlevel1' => intval($_GPC['downcountlevel1']),
			'content' => intval($_GPC['content']),
			'premier' => intval($_GPC['premier']),
			'pcommission' => floatval($_GPC['pcommission']),
			'options_first' => json_encode($_GPC['options_first']),
			'options_second' => json_encode($_GPC['options_second']),
			'options_three' => json_encode($_GPC['options_three'])
		);
		if (!empty($id)) {
			pdo_update('sz_yi_bonusplus_level', $data, array(
				'id' => $id,
				'uniacid' => $_W['uniacid']
			));
			plog('bonusplus.level.edit', "修改分销商等级 ID: {$id}");
		} else {
			pdo_insert('sz_yi_bonusplus_level', $data);
			$id = pdo_insertid();
			plog('bonusplus.level.add', "添加分销商等级 ID: {$id}");
		}
		message('更新等级成功！', $this->createPluginWebUrl('bonusplus/level', array(
			'op' => 'display'
		)), 'success');
	}
} elseif ($operation == 'delete') {
	ca('bonusplus.level.delete');
	$id    = intval($_GPC['id']);
	$level = pdo_fetch('SELECT id,levelname FROM ' . tablename('sz_yi_bonusplus_level') . " WHERE id = '$id'");
	if (empty($level)) {
		message('抱歉，等级不存在或是已经被删除！', $this->createPluginWebUrl('bonusplus/level', array(
			'op' => 'display'
		)), 'error');
	}
	pdo_delete('sz_yi_bonusplus_level', array(
		'id' => $id,
		'uniacid' => $_W['uniacid']
	));
	plog('bonusplus.level.delete', "删除分销商等级 ID: {$id} 等级名称: {$level['levelname']}");
	message('等级删除成功！', $this->createPluginWebUrl('bonusplus/level', array(
		'op' => 'display'
	)), 'success');
}
load()->func('tpl');
include $this->template('level');
?>