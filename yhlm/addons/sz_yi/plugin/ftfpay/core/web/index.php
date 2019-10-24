<?php
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->func('tpl');
if ($operation == 'display') {
	// echo "999";
} elseif ($operation == 'post') {
	// 
}
include $this->template('index');
