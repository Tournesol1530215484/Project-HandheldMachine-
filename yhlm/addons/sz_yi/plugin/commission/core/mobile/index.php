<?php

//decode by QQ:270656184 http://www.yunlu99.com/



global $_W, $_GPC;

$openid = m('user')->getOpenid();

$pluginbonus = p("bonus");
$bonus = 0;

$level = $this->model->getLevel($openid);



$this->model->upgradeLevelByOrder($openid);



if(!empty($pluginbonus)){

	$bonus_set = $pluginbonus->getSet();

	if(!empty($bonus_set['start'])){

		//分红

		if($bonus_set['bonushow'] == 1){

			$bonus = 1;

			$member_bonus = p('bonus')->getInfo($openid, array('total', 'ordercount', 'ok'));

			$bonus_cansettle = $member_bonus['commission_ok'] > 0 && $member_bonus['commission_ok'] >= floatval($bonus['withdraw']);

			//$bonus_commission_ok = $member['commission_ok'];

			$member_bonus['nickname'] = empty($member_bonus['nickname']) ? $member_bonus['mobile'] : $member_bonus['nickname'];

			$member_bonus['agentcount'] = number_format($member_bonus['agentcount'], 0);

			$member_bonus['ordercount0'] = number_format($member_bonus['ordercount'], 0);

			// $member_bonus['commission_ok'] = number_format($member_bonus['commission_ok'], 2);

			$member_bonus['commission_pay'] = number_format($member_bonus['commission_pay'], 2);

			// $member_bonus['commission_total'] = number_format($member_bonus['commission_total'], 2);

			$member_bonus['customercount'] = count(p('bonus')->getChildAgents($member_bonus['id']));

			$level = p('bonus')->getLevel($openid);

		}

	}

}



if ($_W['isajax']) {

	//$member = $this->model->getInfo($openid, array('total', 'ordercount0', 'ok', 'myorder','apply'));
	$member = $this->model->getInfo($openid, array('total', 'ok', 'ordercount0', 'apply', 'check', 'lock', 'pay', 'myorder'));
	file_put_contents(dirname(__FILE__).'/bbbbb',json_encode($member));
	
	$cansettle = $member['commission_ok'] > 0 && $member['commission_ok'] >= floatval($this->set['withdraw']);

	$mycansettle = $member['commission_ok'] > 0 && $member['myoedermoney'] >= floatval($this->set['consume_withdraw']);

	//add 2016-06-20

	$mymonth = $member['commission_ok'] > 0 && $member['month_consume_withdraw'] >=  floatval($this->set['month_consume_withdraw']);

	$mytotal = $member['commission_ok'] > 0 && $member['total_consume_withdraw'] >=  floatval($this->set['total_consume_withdraw']);

	$month_consume_withdraw = floatval($member['month_consume_withdraw']);

	$total_consume_withdraw = floatval($member['total_consume_withdraw']);

	//add end

	$commission_ok = $member['commission_ok'];

	

    $member['nickname'] = empty($member['nickname']) ? $member['mobile'] : $member['nickname'];

	$member['agentcount'] = number_format($member['agentcount'], 0);

	$member['ordercount0'] = number_format($member['ordercount0'], 0);
	
	$member['commission_apply'] = number_format($member['commission_apply'], 2);

	// $member['commission_ok'] = number_format($member['commission_ok'], 2);

	$member['commission_pay'] = number_format($member['commission_pay'], 2);

	// $member['commission_total'] = number_format($member['commission_total'], 2);

	$member['customercount'] = pdo_fetchcolumn('select count(id) from ' . tablename('sz_yi_member') . ' where agentid=:agentid and ((isagent=1 and status=0) or isagent=0) and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']));

	if (mb_strlen($member['nickname'], 'utf-8') > 6) {

		$member['nickname'] = mb_substr($member['nickname'], 0, 6, 'utf-8');

	}



	//是否开启佣金提现2016.8.25 add

	$extract_commission = m('commission')->getAuthority('is_withdraw', $this->set['extract_commission'] );

	$this->set['extract_commission'] = $extract_commission;

	//end 

	//是否生成小店二维码2016.8.25 add

	$spread_qrcode = m('commission')->getAuthority('is_qrcode', $this->set['spread_qrcode'] );

	$this->set['spread_qrcode'] = $spread_qrcode;

	//end 

	

	//是否关闭"我的小店"功能 data2016.08.25 

	$closemyshop = m('commission')->getAuthority('is_shop', $set['closemyshop'] );

	$this->set['closemyshop'] = $closemyshop;

	//end 

	

	// 2016.08.25 add by chs start

	//是否允许分销商自己的小店选择自己推广的产品	

	$openselect = false;

	if( false != $member['agentlevel'] ){//设置分销等级

		$commission_level = pdo_fetch("SELECT authority FROM " . tablename('sz_yi_commission_level') . " WHERE id = ". $member['agentlevel']);

		$authority = unserialize($commission_level['authority']);

		if( $authority['is_optional'] == 1 ){

			$openselect = true;

		}

	}else{//默认分销等级		

		if ( $this->set['select_goods'] == 1 ) {

			//单独禁用某个分销商的自选权限

			if (empty($member['agentselectgoods']) || $member['agentselectgoods'] == 2) {

				$openselect = true;

			}

		} else {

			if ($member['agentselectgoods'] == 2) {

				$openselect = true;

			}

		}

	}

	$this->set['openselect'] = $openselect;

	//end

	

	show_json(1, array(

		'commission_ok' => $commission_ok, 

		'member' => $member, 

		'level' => $level, 

		'cansettle' => $cansettle, 

		'mycansettle' => $mycansettle, 

		//是否允许提现add 2016.08.25

		'extract_commission' => $extract_commission, 

		'spread_qrcode' => $spread_qrcode, 

		//end

		//add 2016-06-20

		'month_consume_withdraw' => $month_consume_withdraw,

		'total_consume_withdraw' => $month_consume_withdraw,

		'month_money' => floatval($this->set['month_consume_withdraw']), 

		'total_money' => floatval($this->set['total_consume_withdraw']), 

		//add end

		'settlemoney' => floatval($this->set['withdraw']), 

		'mysettlemoney' => floatval($this->set['consume_withdraw']),

		'set' => $this->set,

	));

}

//$member = $member = $this->model->getInfo($openid, array('total', 'ordercount0', 'ok', 'myorder', 'apply'));
$member = $this->model->getInfo($openid, array('total', 'ok', 'ordercount0', 'apply', 'check', 'lock', 'pay', 'myorder'));

$user_data = array('credit7' => "$member[commission_total]");

pdo_update('mc_members', $user_data, array('uid' => "$member[uid]"));

if($_GPC[style] == 1){

	include $this->template('index1');

}else if($_GPC[style] == 2){

	include $this->template('index2');

}else{

	include $this->template('index');

}







/*

global $_W, $_GPC;

$openid = m('user')->getOpenid();

$pluginbonus = p('bonus');

$bonus = 0;

$level = $this->model->getLevel($openid);

if (!empty($pluginbonus)) {

	$bonus_set = $pluginbonus->getSet();

	if (!empty($bonus_set['start'])) {

		if ($bonus_set['bonushow'] == 1) {

			$bonus = 1;

			$member_bonus = p('bonus')->getInfo($openid, array('total', 'ordercount', 'ok'));

			$bonus_cansettle = $member_bonus['commission_ok'] > 0 && $member_bonus['commission_ok'] >= floatval($bonus['withdraw']);

			$member_bonus['nickname'] = empty($member_bonus['nickname']) ? $member_bonus['mobile'] : $member_bonus['nickname'];

			$member_bonus['ordercount0'] = number_format($member_bonus['ordercount'], 0);

			$member_bonus['commission_ok'] = number_format($member_bonus['commission_ok'], 2);

			$member_bonus['commission_pay'] = number_format($member_bonus['commission_pay'], 2);

			$member_bonus['commission_total'] = number_format($member_bonus['commission_total'], 2);

			$member_bonus['customercount'] = intval($member_bonus['agentcount']);

			$level = p('bonus')->getLevel($openid);

		}

	}

}

if ($_W['isajax']) {

	$member = $this->model->getInfo($openid, array('total', 'ordercount0', 'ok', 'myorder'));

	$cansettle = $member['commission_ok'] > 0 && $member['commission_ok'] >= floatval($this->set['withdraw']);

	$mycansettle = $member['commission_ok'] > 0 && $member['myoedermoney'] >= floatval($this->set['consume_withdraw']);

	$commission_ok = $member['commission_ok'];

	$member['nickname'] = empty($member['nickname']) ? $member['mobile'] : $member['nickname'];

	$member['agentcount'] = number_format($member['agentcount'], 0);

	$member['ordercount0'] = number_format($member['ordercount0'], 0);

	$member['ordermoney0'] = number_format($member['ordermoney0'], 2);

	$member['commission_ok'] = number_format($member['commission_ok'], 2);

	$member['commission_pay'] = number_format($member['commission_pay'], 2);

	$member['commission_total'] = number_format($member['commission_total'], 2);

	$member['customercount'] = pdo_fetchcolumn('select count(id) from ' . tablename('sz_yi_member') . ' where agentid=:agentid and ((isagent=1 and status=0) or isagent=0) and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']));

	if (mb_strlen($member['nickname'], 'utf-8') > 6) {

		$member['nickname'] = mb_substr($member['nickname'], 0, 6, 'utf-8');

	}

	$openselect = false;

	if ($this->set['select_goods'] == '1') {

		if (empty($member['agentselectgoods']) || $member['agentselectgoods'] == 2) {

			$openselect = true;

		}

	} else {

		if ($member['agentselectgoods'] == 2) {

			$openselect = true;

		}

	}

	$this->set['openselect'] = $openselect;

	show_json(1, array('commission_ok' => $commission_ok, 'member' => $member, 'level' => $level, 'cansettle' => $cansettle, 'mycansettle' => $mycansettle, 'settlemoney' => number_format(floatval($this->set['withdraw']), 2), 'mysettlemoney' => number_format(floatval($this->set['consume_withdraw']), 2), 'set' => $this->set,));

}

include $this->template('index');





*/