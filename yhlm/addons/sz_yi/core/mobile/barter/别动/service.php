<?php

if (!defined('IN_IA')){

    exit('Access Denied');

}
global $_W,$_GPC;

$openid=m('user')->getOpenid(); 
$popenid=m('user')->islogin();
$openid = $openid?$openid:$popenid;
$set=pdo_fetch('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
$set=unserialize($set['sets']);
$ratio=floatval($set['bart']['withdraw'] / 100); 	  	 	 	
$op=empty($_GPC['op'])?'display':$_GPC['op'];
$member = m('member') -> getMember($openid);

if ($_W['isajax']) {
	if ($op == 'get') {

        switch ($member['bonus_area']) {
            case '1':
                $condition1.=' and v.level = 5 order by v.id desc';
                $condition.=' and v.level = 5 ';
                $mcon=' and level = 5 ';
                break;
            case '2':
                $condition1.=' and v.level = 4 order by v.id desc';
                $condition.=' and v.level = 4 ';
                $mcon=' and level = 4 ';

                break;
            case '3':
                $condition1.=' and v.level = 3 order by v.id desc';
                $condition.=' and v.level = 3 ';
                $mcon=' and level = 3 ';

                break;
            default:
                break;
        }
        $mbonus=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_year_vip_log').' where uniacid = :uniacid and openid = :openid '.$mcon,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        $svs=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_barter_services_fee').' where uniacid = :uniacid and mid = :mid '.$mcon,array(':uniacid'=>$_W['uniacid'],':mid'=>$member['id']));
        $title="换货服务费提现";
        $already=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_member_log').' where uniacid = :uniacid and openid = :openid and type=1 and title=:title and status<=1 ',array(':title'=>$title,':uniacid'=>$_W['uniacid'],':openid'=>$member['openid']));
        // print_r($already);exit;
        $info['already']= $already;

        $total=$mbonus+$svs; 
        $info['total']['service']=$total-$already;
        $info['credit2']=$info['total']['service'];
        $temp=$info['credit2'];


        // $info['credit2']=m('member')->getCredit($openid,'credit2');
		$info['total']['recharge']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_member_log').' where type = 0 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		// $uids=pdo_fetchall('select uid from '.tablename('sz_yi_perm_user').' where openid = :openid and uniacid = :uniacid',array(':openid'=>$openid,':uniacid'=>$_W['uniacid']));
		// if (!empty($uids)) {
		// 	foreach ($uids as $key => $value) {
		// 			$temp[]=$value['uid'];
		// 	}
		// }else{
		// 	$temp[]=0;
		// }

		// $uidStr=implode(',', $temp);  
		$info['total']['withdraw']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_member_log').' where type = 1 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));   
		$info['total']['shop']=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where isexchange = 0 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));  
 		$info['total']['refunduse']=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where uniacid = :uniacid and openid = :openid and refundtime != 0 and isexchange = 0 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		       

        $info['total']['goods']=0;      //货款 
        $info['total']['other']=0;      //其他 
        $info['openid']=$openid;
        // $fans=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 1',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $me=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and bonustype = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        // show_json(0,'123');

        
		$info['total']['fans']=floatval($fans)+floatval($me);	//粉丝购物奖励 

		$set = m('common')->getSysset(array(
        'shop',
        'pay',
        'trade'
    ));
    if (!empty($set['trade']['closerecharge'])) {
        show_json(-1, '系统未开启账户充值!');
    }
    pdo_delete('sz_yi_member_log', array(
        'openid' => $openid,
        'status' => 0,
        'type' => 0,
        'uniacid' => $_W['uniacid']
    ));
    $logno = m('common')->createNO('member_log', 'logno', 'RC');
    $log   = array(
        'uniacid' => $_W['uniacid'],
        'logno' => $logno,
        'title' => $set['shop']['name'] . "会员充值",
        'openid' => $openid,
        'type' => 0,
        'createtime' => time(),
        'status' => 0
    );
    pdo_insert('sz_yi_member_log', $log);
    $logid  = pdo_insertid();
    $credit = m('member')->getCredit($openid, 'credit2');
    $wechat = array(
        'success' => false
    );
    if (is_weixin()) {
        if (isset($set['pay']) && $set['pay']['weixin'] == 1) {
            load()->model('payment');
            $setting = uni_setting($_W['uniacid'], array(
                'payment'
            ));
            if (is_array($setting['payment']['wechat']) && $setting['payment']['wechat']['switch']) {
                $wechat['success'] = true;
            }
        }
    }
    $alipay = array(
        'success' => false
    );
    if (isset($set['pay']['alipay']) && $set['pay']['alipay'] == 1) {
        load()->model('payment');
        $setting = uni_setting($_W['uniacid'], array(
            'payment'
        ));
        if (is_array($setting['payment']['alipay']) && $setting['payment']['alipay']['switch']) {
            $alipay['success'] = true;
        }
    }

    $pluginy = p('yunpay');
    $yunpay = array(
        'success' => false
    );
    if ($pluginy) {
        $yunpayinfo = $pluginy->getYunpay();

        if (isset($yunpayinfo) && @$yunpayinfo['switch']) {
            $yunpay['success'] = true;
        }
    }

    show_json(1, array(
        'set' => $set,
        'logid' => $logid,
        'isweixin' => is_weixin(),
        'wechat' => $wechat,
        'alipay' => $alipay,
        'credit' => $credit,
        'yunpay' => $yunpay,
        'info'=>$info
    ));


		// show_json(1,array('info'=>$info));	
	} 
}


include $this -> template('barter/service');
