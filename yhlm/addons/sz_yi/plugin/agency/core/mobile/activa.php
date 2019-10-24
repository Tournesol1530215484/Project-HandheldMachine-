<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$openid = m('user')->getOpenid();
$set=pdo_fetchcolumn('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
$set=unserialize($set);
$ratio=doubleval($set['bart']['ratio']);
$decimal=($ratio / 100);
$freeze_credit3=m('member')->getCredit($openid,'freeze_credit3');
if ($op == 'editpwd') {
    if ($_W['isajax']){ 
                    //手续费百分比 
        $freeze_credit3=doubleval($freeze_credit3);     //现有
        $activation=doubleval($_GPC['activation']);     //总
        if ($freeze_credit3 < $activation) {
            show_json(0, '激活失败,冻结易货码不足激活数量,你当前冻结易货码'.$freeze_credit3);
        }  

        $deduct=$activation * $decimal;   //手续费 
        $realCurrency=$activation-$deduct;  //  实得

        if (intval($_GPC['paytype']) == 4) {    //余额支付
            $credit2=m('member')->getCredit($openid,'credit2');
            doubleval($credit2) < doubleval($deduct) && show_json(0,'激活失败，手续费不足!');
            m('member')->setCredit($openid,'credit2',-doubleval($deduct));
            $paytype=2; 
        }
         
        if (intval($_GPC['paytype']) == 5) {    //余额支付 
           	intval($_GPC['payed']) == 0 && show_json(0,'订单未支付!');
            $paytype=1;
        } 

        if (!empty($paytype)) {			//余额或微信
        	m('member')->setCredit($openid,'freeze_credit3',-$activation);  //扣除
	        m('member')->setCredit($openid,'credit3',$activation);  //得到完整   
	        $ordersn='ac'.date('Ymdhi').uniqid();          
	        $profile=pdo_fetch('select openid,uid from '.tablename('sz_yi_member').'where openid=:uid and uniacid=:uniacid',array(':uid'=>$_GPC['openid'],':uniacid'=>$_W['uniacid']));

	        $data=array( 
	            'activacurrency'=>$activation, 
	            'type'          => 1, 
	            'activatime'    =>time(),
	            'activapay'     =>$deduct,
	            'paytype'       =>$paytype,
	            'ordersn'       =>$ordersn,
	            'openid'        =>$openid,
	            'uniacid'        =>$_W['uniacid']
	        );  
	            // paytype 4 余额 5 微信
            //充值分红
            p('bonus')->calculateactive($openid,$deduct,$ordersn);
	        pdo_insert('sz_yi_currency_activation',$data);
	        m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],12,1,2,$activation,$ordersn,'激活所得易货码'); 
	        $paytype == 2 && m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],11,1,2,-$deduct,$ordersn,'激活易货码手续费');
	        show_json(1,'激活成功!');
        }
        
        show_json(0,'非法参数');
 
    }
} else if($op == 'deduct' && $_W['ispost']) {  
    $logid = intval($_GPC['logid']); 
    if (empty($logid)) {
        show_json(0, '充值出错, 请重试!');
    }

    $money = floatval($_GPC['money']);
    if (empty($money)) {
        show_json(0, '请填写充值金额!');
    }

    $freeze_credit3=doubleval($freeze_credit3);     //现有
    $activation=doubleval($_GPC['actnum']);     //总
    if ($freeze_credit3 < $activation) {
        show_json(0, '激活失败,冻结易货码不足激活数量,你当前冻结易货码'.$freeze_credit3);
    }

    $type = $_GPC['type'];
    if (!in_array($type, array('weixin'))) {
        show_json(0, '未找到支付方式');
    }

    $log = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_member_log') . ' WHERE `id`=:id and `uniacid`=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $logid 
    )); 

    if (empty($log)) {
        show_json(0, '充值出错, 请重试!'); 
    }

    /*修复支付问题*/
    $couponid = intval($_GPC['couponid']);
	if($log['money'] <= 0){
        pdo_update('sz_yi_member_log', array('money' => $money, 'couponid' => $couponid), array('id' => $log['id']));
    }

    $set = m('common')->getSysset(array(
        'shop',
        'pay'
    ));
    if ($type == 'weixin') {
        if (!is_weixin()) {
            show_json(0, '非微信环境!');
        }
        if (empty($set['pay']['weixin'])) {
            show_json(0, '未开启微信支付!');
        }
        $wechat          = array(
            'success' => false
        );
        $params          = array();
        $params['tid']   = $log['logno'];
        $params['user']  = $openid;
        $params['fee']   = $money;
        $params['title'] = $log['title'];
        load()->model('payment');
        $setting = uni_setting($_W['uniacid'], array(
            'payment'
        )); 
        if (is_array($setting['payment'])) {
            $options           = $setting['payment']['wechat'];
            $options['appid']  = $_W['account']['key']; 
            $options['secret'] = $_W['account']['secret'];
            $wechat            = m('common')->wechat_build($params, $options, 1);
            $wechat['success'] = false;
            if (!is_error($wechat)) {
                $wechat['success'] = true; 
            } else { 
                show_json(0, $wechat['message']);
            }
        }
        if (!$wechat['success']) {
            show_json(0, '微信支付参数错误!');
        }
        show_json(1, array(
            'wechat' => $wechat
        ));
    }
} else if ($op == 'display' && $_W['isajax']) {
    $set = m('common')->getSysset(array(
        'shop',
        'pay',
        'trade'
    ));
    
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
    
    show_json(1, array(
        'set' => $set,
        'logid' => $logid,
        'isweixin' => is_weixin(),
        'wechat' => $wechat,
    ));
}else if ($operation == 'complete' && $_W['ispost']) {
    $logid = intval($_GPC['logid']);
    $log   = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_member_log') . ' WHERE `id`=:id and `uniacid`=:uniacid limit 1', array(
        ':uniacid' => $uniacid,
        ':id' => $logid
    ));
    if (!empty($log) && empty($log['status'])) {
        $payquery = m('finance')->isWeixinPay($log['logno']);
        if (!is_error($payquery)) {
            pdo_update('sz_yi_member_log', array(
                'status' => 1,
                'rechargetype' => $_GPC['type']
            ), array(
                'id' => $logid
            ));
            m('member')->setCredit($openid, 'credit2', $log['money']);
            m('member')->setRechargeCredit($openid, $log['money']);
            if (p('sale')) {
                p('sale')->setRechargeActivity($log);
            }
            m('notice')->sendMemberLogMessage($logid);
        }
    } 
    show_json(1);
}
$iswechat=0;
if (is_weixin()) {
	$iswechat=1;
} 
include $this->template('activa');
// echo '正在开发，请稍后在试';