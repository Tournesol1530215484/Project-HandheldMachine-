<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;

/*微信jssdk接口*/
/*require_once IA_ROOT . '/addons/sz_yi/wxjs/jssdk.php';
$appid       = $_W['account']['key'];
$appsecret   = $_W['account']['secret'];
$jssdk       = new JSSDK( $appid, $appsecret );
$signPackage = $jssdk->GetSignPackage();*/

if ($_GPC['op'] == 'show') { 
	$a=m('user')->oauth_info(); 
	print_r($a);
	exit;
}
$openid = m('user')->getOpenid();

$roleid = pdo_fetchcolumn('select id from '.tablename('sz_yi_perm_role')." where rolename = '供应商' and uniacid = '{$_W['uniacid']}' and status = 1 and status1 = 1 limit 1 ");

$supplier = pdo_fetchcolumn('select id  from '.tablename('sz_yi_perm_user')." where openid = '{$openid}' and roleid = '{$roleid}' limit 1  ");

$dd  = p('return') ->returnmoney();

$set = m('common')->getSysset(array('trade'));
$shop_set = m('common')->getSysset(array('shop'));
$member = m('member')->getMember($openid);
$member['nickname'] = empty($member['nickname']) ? $member['mobile'] : $member['nickname'];
$uniacid = $_W['uniacid'];
$trade['withdraw'] = $set['trade']['withdraw'];
$trade['closerecharge'] = $set['trade']['closerecharge'];
$hascom = false;
$shopset = array();
$supplier_switch = false;
if (p('supplier')) {
	$supplier_set = p('supplier')->getSet();
	if (!empty($supplier_set['switch'])) {
		$supplier_switch = true;
	}
}
$shopset['supplier_switch'] = $supplier_switch;
$plugc = p('commission');

 
if ($plugc) {
	$pset = $plugc->getSet();
	if (!empty($pset['level'])) {
		if ($member['isagent'] == 1 && $member['status'] == 1) {
			$hascom = true;
		}
	}
}
$shopset['commission_text'] = $pset['texts']['center'];
$shopset['hascom'] = $hascom;
$hascoupon = false;
$hascouponcenter = false;
$plugin_coupon = p('coupon');
if ($plugin_coupon) {
	$pcset = $plugin_coupon->getSet();
	if (empty($pcset['closemember'])) {
		$hascoupon = true;
		$hascouponcenter = true;
	}
}
$shopset['hascoupon'] = $hascoupon;
$shopset['hascouponcenter'] = $hascouponcenter;
$pluginbonus = p('bonus');
$bonus_start = false;
$bonus_text = "";
if (!empty($pluginbonus)) {
	$bonus_set = $pluginbonus->getSet();
	if (!empty($bonus_set['start'])) {
		$bonus_start = true;
		$bonus_text = $bonus_set['detail_text'] ? $bonus_set['detail_text'] : '分红明细';
	}
}
$shopset['article'] = pdo_fetch("select * from " . tablename('sz_yi_article_sys') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
$shopset['bonus_start'] = $bonus_start;
$shopset['bonus_text'] = $bonus_text;
$shopset['is_weixin'] = is_weixin();
$level = array('levelname' => empty($this->yzShopSet['levelname']) ? '普通会员' : $this->yzShopSet['levelname']);
$orderparams = array(':uniacid' => $_W['uniacid'], ':openid' => $openid);
$order = array('status0' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status=0  and uniacid=:uniacid limit 1', $orderparams), 'status1' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status=1 and refundid=0 and uniacid=:uniacid limit 1', $orderparams), 'status2' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status=2 and refundid=0 and uniacid=:uniacid limit 1', $orderparams), 'status4' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and refundid<>0 and uniacid=:uniacid limit 1', $orderparams),);
$counts = array('cartcount' => pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_member_cart') . ' where uniacid=:uniacid and openid=:openid and deleted=0 ', array(':uniacid' => $uniacid, ':openid' => $openid)), 'favcount' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and openid=:openid and deleted=0 ', array(':uniacid' => $uniacid, ':openid' => $openid)));
	if ($plugin_coupon) {
		$time = time();
		$sql = 'select count(*) from ' . tablename('sz_yi_coupon_data') . ' d';
		$sql .= ' left join ' . tablename('sz_yi_coupon') . ' c on d.couponid = c.id';
		$sql .= ' where d.openid=:openid and d.uniacid=:uniacid and  d.used=0 ';
		$sql .= " and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<={$time} && c.timeend>={$time})) order by d.gettime desc";
		$counts['couponcount'] = pdo_fetchcolumn($sql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));
	}
		 
if ($_W['isajax']) {				 	 	 	  
	$level = array('levelname' => empty($this->yzShopSet['levelname']) ? '普通会员' : $this->yzShopSet['levelname']);
	if (!empty($member['level'])) {
		$level = m('member')->getLevel($openid);
	}
	$orderparams = array(':uniacid' => $_W['uniacid'], ':openid' => $openid);
	$order = array('status0' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status=0  and uniacid=:uniacid limit 1', $orderparams), 'status1' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status=1 and refundid=0 and uniacid=:uniacid limit 1', $orderparams), 'status2' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status=2 and refundid=0 and uniacid=:uniacid limit 1', $orderparams), 'status4' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and refundid<>0 and uniacid=:uniacid limit 1', $orderparams),);
	if (mb_strlen($member['nickname'], 'utf-8') > 6) {
		$member['nickname'] = mb_substr($member['nickname'], 0, 6, 'utf-8');
	}
	$referrer = array();
	if ($shop_set['shop']['isreferrer']) {
		if ($member['agentid'] > 0) {
			$referrer = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where uniacid=' . $_W['uniacid'] . ' and id = \'' . $member['agentid'] . '\' ');
			$referrer['realname'] = mb_substr($referrer['realname'], 0, 6, 'utf-8');
		} else {
			$referrer['realname'] = '总店';
		}
	}
	$open_creditshop = false;
	$creditshop = p('creditshop');
	if ($creditshop) {
		$creditshop_set = $creditshop->getSet();
		if (!empty($creditshop_set['centeropen'])) {
			$open_creditshop = true;
		}
	}
	$counts = array('cartcount' => pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_member_cart') . ' where uniacid=:uniacid and openid=:openid and deleted=0 ', array(':uniacid' => $uniacid, ':openid' => $openid)), 'favcount' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and openid=:openid and deleted=0 ', array(':uniacid' => $uniacid, ':openid' => $openid)));
	if ($plugin_coupon) {
		$time = time();
		$sql = 'select count(*) from ' . tablename('sz_yi_coupon_data') . ' d';
		$sql .= ' left join ' . tablename('sz_yi_coupon') . ' c on d.couponid = c.id';
		$sql .= ' where d.openid=:openid and d.uniacid=:uniacid and  d.used=0 ';
		$sql .= " and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timestart<={$time} && c.timeend>={$time})) order by d.gettime desc";
		$counts['couponcount'] = pdo_fetchcolumn($sql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));
	}
	// 查询已消费金额
	$sql = 'select sum(price) as price from'.tablename('sz_yi_order').'where uniacid=:uniacid and openid=:openid and status = 3';
	$x = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

	$sql = 'select surplus_price from'.tablename('sz_yi_descreturn_log').'where uniacid=:uniacid and openid=:openid ORDER BY id desc';
	$surplus_price = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $openid));

	$member['credit3']=m('member')->getCredit($openid,'credit3');
	$member['credit2']=m('member')->getCredit($openid,'credit2');
    $member['credit1']=m('member')->getCredit($openid,'credit1');
    show_json(1, array('member' => $member, 'referrer' => $referrer, 'shop_set' => $shop_set, 'order' => $order, 'level' => $level, 'open_creditshop' => $open_creditshop, 'counts' => $counts, 'shopset' => $shopset, 'trade' => $trade, 'x' => $x['price'], 'surplus_price' => $surplus_price['surplus_price']));
}
$aa = pdo_fetch("select * from " . tablename('sz_yi_member_style').' where style = 1 and uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
$style = $aa['styleid']; 
$member['style'] = $style; 
$bb = pdo_fetch("select credit7 from " . tablename('mc_members')."where uid = '{$member['uid']}'");
$member['credit7'] = $bb['credit7'];


if($style == 0){

	include $this->template('member/center');
}else if($style == 1){
	include $this->template('member/center1');
}else if($style == 2){
	include $this->template('member/center2');
}else if($style == 3){
	include $this->template('member/center3');
}
