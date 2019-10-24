<?php

if (!defined('IN_IA')) {
	print ('Access Denied');
}

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';






load()->func('tpl');
include $this->template('web/shop/traffic');


