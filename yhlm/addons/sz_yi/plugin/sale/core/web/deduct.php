<?php
//多级分销商城 QQ:1084070868
global $_W, $_GPC;

ca('sale.deduct.view');
$set = $this->getSet();
if (checksubmit('submit')) {
	ca('sale.deduct.save');
	$data = is_array($_GPC['data']) ? $_GPC['data'] : array();
	$set['creditdeduct'] = intval($data['creditdeduct']);
	$set['credit'] = 1;
	$set['money'] = round(floatval($data['money']), 2);
	$set['moneydeduct'] = intval($data['moneydeduct']);
	$set['dispatchnodeduct'] = intval($data['dispatchnodeduct']);
	$this->updateSet($set);
	plog('sale.deduct.save', '修改抵扣设置');
	message('抵扣设置成功!', referer(), 'success');
}
load()->func('tpl');
include $this->template('deduct');