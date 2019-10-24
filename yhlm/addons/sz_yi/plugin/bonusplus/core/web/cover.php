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

ca('bonusplus.cover');
$rule = pdo_fetch('select * from ' . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(
	':uniacid' => $_W['uniacid'],
	':module' => 'cover',
	':name' => '静态全返入口设置'
));
if (!empty($rule)) {
	$keyword = pdo_fetch('select * from ' . tablename('rule_keyword') . ' where uniacid=:uniacid and rid=:rid limit 1', array(
		':uniacid' => $_W['uniacid'],
		':rid' => $rule['id']
	));
	$cover   = pdo_fetch('select * from ' . tablename('cover_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(
		':uniacid' => $_W['uniacid'],
		':rid' => $rule['id']
	));
}
if (checksubmit('submit')) {
	$data = is_array($_GPC['cover']) ? $_GPC['cover'] : array();
	if (empty($data['keyword'])) {
		message('请输入关键词!');
	}
	if (!empty($rule)) {
		pdo_delete('rule', array(
			'id' => $rule['id'],
			'uniacid' => $_W['uniacid']
		));
		pdo_delete('rule_keyword', array(
			'rid' => $rule['id'],
			'uniacid' => $_W['uniacid']
		));
		pdo_delete('cover_reply', array(
			'rid' => $rule['id'],
			'uniacid' => $_W['uniacid']
		));
	}
	$rule_data = array(
		'uniacid' => $_W['uniacid'],
		'name' => '静态全返入口设置',
		'module' => 'cover',
		'displayorder' => 0,
		'status' => intval($data['status'])
	);
	pdo_insert('rule', $rule_data);
	$rid          = pdo_insertid();
	$keyword_data = array(
		'uniacid' => $_W['uniacid'],
		'rid' => $rid,
		'module' => 'cover',
		'content' => trim($data['keyword']),
		'type' => 1,
		'displayorder' => 0,
		'status' => intval($data['status'])
	);
	pdo_insert('rule_keyword', $keyword_data);
	$cover_data = array(
		'uniacid' => $_W['uniacid'],
		'rid' => $rid,
		'module' => $this->modulename,
		'title' => trim($data['title']),
		'description' => trim($data['desc']),
		'thumb' => $data['thumb'],
		'url' => $this->createPluginMobileUrl('bonusplus')
	);
	pdo_insert('cover_reply', $cover_data);
	plog('bonusplus.cover', '修改静态全返入口设置');
	message('静态全返入口设置成功!', referer(), 'success');
}
load()->func('tpl');
include $this->template('cover');
?>