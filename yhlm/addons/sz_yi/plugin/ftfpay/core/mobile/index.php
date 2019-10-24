<?php
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';


if ($operation == 'qrcode') {

	$openid = m('user')->getOpenid();
	$member   = m("member")->getMember($openid);
	if (empty($member)) {
		echo '<script>alert("收款人不存在！");location.href="'.$this->createMobileUrl('member').'";</script>';
	}
	include $this->template('qrcode');

} else if ($operation == 'getqrcode') {
	// 生成二维码
	$this->model->createCode();

} else if ($operation == 'pay') {

	$payeeopenid = $_GPC['payeeopenid'];
	$member   = m("member")->getMember($payeeopenid);
	if (empty($member)) {
		echo '<script>alert("收款人不存在！");location.href="'.$this->createMobileUrl('member').'";</script>';
	}
	include $this->template('pay');

} else if ($operation == 'confirm' && $_W['isajax']) {

	$money = floatval($_GPC['money']);

	empty($money) && show_json(-1, '转帐金额不能为空');

	$money < 0 && show_json(-1, '转帐金额不能小于0');

	// 收款人的 openid
	$openid = m('user')->getOpenid();
	// 查询消费密码
    $memberthis = m('member') -> getMember($openid);
    $withdraw_pwd = $memberthis['withdraw_pwd'];
    $withdraw_pwd || show_json(0, '请先设置消费密码！');
    if(md5($_GPC['password']) != $withdraw_pwd){
        show_json(0, '消费密码错误!');
    }
	$payeeopenid = $_GPC['payeeopenid'];
	$member = m("member")->getMember($payeeopenid);

	empty($member) && show_json(-1, '收款人不存在！');
	$thisOpenid = m('user')->getOpenid();
	$thisBalance = m('member')->getCredit($thisOpenid, 'credit2');//余额


	$thisBalance < $money && show_json(-1, '您的余额不足！');
	// 充值
	m('member')->setCredit($payeeopenid, 'credit2', $money, array($_W['uid'], '面对面付款-收入'));//余额积分

	// 扣除
	m('member')->setCredit($thisOpenid, 'credit2', -$money, array($_W['uid'], '面对面付款-支出'));//余额积分
	show_json(1, 'ok');
}