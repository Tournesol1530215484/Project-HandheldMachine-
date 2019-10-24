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

ca('bonusplus.detail');
$operation     = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$params        = array(
	':uniacid' => $_W['uniacid']
);
$daytime       = strtotime(date('Y-m-d', time()));
$sn            = $_GPC['sn'];
$params[':sn'] = $sn;
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 20;
	$logs   = pdo_fetchall('select * from ' . tablename('sz_yi_bonusplus_log') . ' where uniacid=:uniacid and send_bonusplus_sn =:sn limit ' . ($pindex - 1) * $psize . ',' . $psize, $params);
	$total  = pdo_fetchcolumn('select count(id) from ' . tablename('sz_yi_bonusplus_log') . ' where uniacid=:uniacid and send_bonusplus_sn =:sn', $params);
	$pluginbonusplus = p("bonusplus");
	$bonusplus_start = 0;
	$bonusplus_set = $pluginbonusplus->getSet();
	$zhengdian = $bonusplus_set["senddaytime"];
	if(empty($zhengdian))
	{
		$zhengdian = 10;
	}
	// if($bonusplus_set['sendmethod'] == 0)
	// {
		// $zhengdian = 0;
	// }
	
	foreach ($logs as $key => &$value) {
		$member             = m('member')->getInfo($value['uid']);
		$value['avatar']    = $member['avatar'];
		$value['mobile']    = $member['mobile'];
		$value['realname']  = $member['realname'];
		$value['nickname']  = $member['nickname'];
		$value['credit2']   = $member['credit2'];
		$value['credit1']   = $member['credit1'];
		$value['member_id'] = $member['id'];
		if(empty($value['sendpaytime']))
		{
			$curhour = intval(date('H', $value['paytime']));
			if($curhour >= intval($zhengdian))
			{
				$value['plansendpaytime'] = strtotime(date("Y-m-d ".$zhengdian.":00:00",$value['paytime'])) + 3600 * 24; 
			}
			else
			{
				$value['plansendpaytime'] = strtotime(date("Y-m-d ".$zhengdian.":00:00",$value['paytime'])); 
			}
		}
	}
	$pager = pagination($total, $pindex, $psize);
} else if ($operation == 'afresh') {
	$logs          = pdo_fetchall('select * from ' . tablename('sz_yi_bonusplus_log') . ' where uniacid=:uniacid and send_bonusplus_sn =:sn', $params);
	$sendpay_error = 0;
	foreach ($logs as $key => $value) {
		$sendpay = 1;
		$logno   = m('common')->createNO('bonusplus_log', 'logno', 'RB');
		$result  = m('finance')->pay($value['openid'], 1, $value['money'] * 100, $logno, '平台分红');
		if (is_error($result)) {
			$sendpay       = 0;
			$sendpay_error = 1;
		}
		pdo_update('sz_yi_bonusplus_log', array(
			'sendpay' => $sendpay
		), array(
			'openid' => $value['openid'],
			'uniacid' => $_W['uniacid']
		));
		if ($sendpay == 1) {
			m('member')->setCredit($value['openid'], 'credit1', $value['integral']);
			$this->model->send_bonusplus_message($value['openid'], $value['money'], $value['return_money'], $value['integral'], $this->createMobileUrl('member'));
		}
	}
	pdo_update('sz_yi_bonusplus', array(
		'sendpay_error' => $sendpay_error
	), array(
		'send_bonusplus_sn' => $sn,
		'uniacid' => $_W['uniacid']
	));
	message('分红重新发放成功', $this->createPluginWebUrl('bonusplus/detail', array(
		'sn' => $sn
	)), 'success');
} 
else if ($operation == 'list') {
	$totalmoney = pdo_fetchcolumn('select sum(money) as totalmoney from ' . tablename('sz_yi_bonusplus') . ' where uniacid=:uniacid', array(
		':uniacid' => $_W['uniacid']
	));
	$pindex     = max(1, intval($_GPC['page']));
	$psize      = 20;
	$list       = pdo_fetchall('select * from ' . tablename('sz_yi_bonusplus') . " where uniacid={$_W['uniacid']} order by id desc limit " . ($pindex - 1) * $psize . ',' . $psize);
}
include $this->template('detail');
?>