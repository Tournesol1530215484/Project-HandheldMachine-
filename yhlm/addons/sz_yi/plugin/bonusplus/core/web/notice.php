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

ca('commission.notice');
$set = $this->getSet();
if (checksubmit('submit')) {
	$set['tm'] = is_array($_GPC['tm']) ? $_GPC['tm'] : array();
	$this->updateSet($set);
	plog('commission.notice', '修改通知设置');
	message('设置保存成功!', referer(), 'success');
}
load()->func('tpl');
include $this->template('notice');
?>