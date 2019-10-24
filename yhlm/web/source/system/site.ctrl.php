<?php 
/**
 * 站点设置
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
 
defined('IN_IA') or exit('Access Denied');

if(empty($_W['isfounder'])) {
	message('您没有权限', '', 'error');
}

$dos = array('copyright');
$do = in_array($do, $dos) ? $do : 'copyright';
$settings = $_W['setting']['copyright'];
if(empty($settings) || !is_array($settings)) {
	$settings = array();
}

if ($do == 'copyright') {
	$_W['page']['title'] = '站点信息设置 - 系统管理';
	if (checksubmit('submit')) {
		$data = array(
			'status' => $_GPC['status'],
			'reason' => $_GPC['reason'],
			'sitetitle' => $_GPC['sitetitle'],
			'sitelogo' => $_GPC['sitelogo'],
			'backgroup' => $_GPC['backgroup'],
			'backgroupcolor' => $_GPC['backgroupcolor'],
			'servicemobile' => $_GPC['servicemobile'],
			'registercode' => $_GPC['registercode'],
			'copyrights' => $_GPC['copyrights'],
			
		);
		setting_save($data, 'copyright');
		message('更新设置成功！', url('system/site'));
	}
}

template('system/site');