<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$uniacid = $_W['uniacid'];
if (empty($member['referralsn'])) {
	$referralsn = m('common')->createNO('member', 'referralsn', 'SH');
	$data = array('referralsn' => $referralsn);
	pdo_update('sz_yi_member', $data, array('id' => $member['id'], 'uniacid' => $_W['uniacid']));
	$member['referralsn'] = $referralsn;
}
include $this->template('member/referral');