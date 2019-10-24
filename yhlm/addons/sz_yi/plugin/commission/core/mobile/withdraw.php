<?php
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$member = m("member")->getMember($openid);
if ($_W['isajax']) {
	$member = $this->model->getInfo($openid, array('total', 'ok', 'ordercount0', 'apply', 'check', 'lock', 'pay', 'myorder'));
	// file_put_contents(dirname(__FILE__).'/aaa',json_encode($member));
	$cansettle = $member['commission_ok'] > 0 && $member['commission_ok'] >= floatval($this->set['withdraw']) &&  $member['myoedermoney'] >= floatval($this->set['consume_withdraw']);
	$member['commission_ok']    = number_format($member['commission_ok'], 2);
	$member['commission_total'] = number_format($member['commission_total'], 2);
	$member['commission_check'] = number_format($member['commission_check'], 2);
	$member['commission_apply'] = number_format($member['commission_apply'], 2);
	$member['commission_lock']  = number_format($member['commission_lock'], 2);
	$member['commission_pay']   = number_format($member['commission_pay'], 2);


	$member['commission_check'] = pdo_fetchcolumn('select sum(commission) from '.tablename('sz_yi_commission_apply')." where  uniacid = '{$_W['uniacid']}' and status = 1 and  mid = '{$member['id']}' limit 1 ");
	// 查询成功提现佣金
	$sql = 'select sum(commission) as c from'.tablename('sz_yi_commission_apply').'where uniacid=:uniacid and mid=:mid and status=3';
	$commission_success = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':mid' => $member['id']));
	// var_dump($commission_success);exit;
	$member['commission_success'] = $commission_success;
	show_json(1, array('cansettle' => $cansettle, 'settlemoney' => number_format(floatval($this->set['withdraw']), 2), 'member' => $member, 'set' => $this->set));
}
include $this->template('withdraw');
