<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$popenid        = m('user')->islogin();
$openid = m('user')->getOpenid();
$openid = $openid?$openid:$popenid;

$set=pdo_fetchcolumn('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
$set=unserialize($set);
$ratio=doubleval($set['bart']['ratio']);        //默认
$level=pdo_fetchall('select * from '.tablename('sz_yi_bart_member').' where uniacid = :uniacid and status = 1',array(':uniacid'=>$_W['uniacid']));
$tmember=m('member')->getMember($openid);

if ($_GPC['debug']) {                                        
    $re=m('demo')->num2chr('467458');                                        
    print_r($re);                        
    // $re=m('demo')->ch2num($re);                                                                                
    exit;                                    
}
if (intval($tmember['bonus_area']) > 0) {
    $bSet=p('bonus')->getSet();
    if (intval($tmember['bonus_area']) == 1) {
        $ratio=floatval($bSet['bart_province_disc']);

        
    }else if (intval($tmember['bonus_area']) == 2) {
        $ratio=floatval($bSet['bart_city_disc']);

    }else if (intval($tmember['bonus_area']) == 3) {
        $ratio=floatval($bSet['bart_area_disc']);
    }
}
$decimal=floatval($ratio / 100);  

// $credit2=m('member')->getCredit($openid,'credit2');
if ($op=='getlevel') {

}
if ($op == 'pay') {
    if ($_W['isajax']){ 
        $level=p('suppliermenu')->getMemberLevel(intval($_GPC['level']));
                    //手续费百分比 
        // $freeze_credit3=doubleval($freeze_credit3);     //现有
        // $activation=doubleval($_GPC['activation']);     //总
        // if ($freeze_credit3 < $activation) {
        //     show_json(0, '激活失败,冻结易货码不足激活数量,你当前冻结易货码'.$freeze_credit3);
        // }  
        // $deduct=$activation * $decimal;   //手续费 
        // $realCurrency=$activation-$deduct;  //  实得
        if (intval($_GPC['paytype']) == 4) {    //余额支付

            $credit2=m('member')->getCredit($openid,'credit2');
            floatval($credit2) < floatval($level['cash']) && show_json(0,'购买失败，余额不足!');
            m('member')->setCredit($openid,'credit2',-floatval($level['cash']));
            $paytype=2; 
        }

             
        if (intval($_GPC['paytype']) == 5) {    //余额支付 
           	intval($_GPC['payed']) == 0 && show_json(0,'订单未支付!');
            $paytype=1;
        } 

        if (!empty($paytype)) {         //余额或微信
            // m('member')->setCredit($openid,'freeze_credit3',-$activation);  //扣除
            // m('member')->setCredit($openid,'credit3',$activation);  //得到完整 

            m('member')->setCredit($openid,'currency_credit3',floatval($level['currency'])); //赠送额度
            $merch=p('bonus')->getMerch($openid,'deal');
            $mdata=array(
                'level'=>$level['id'],
                'rctime'=>time()
            );
            pdo_update('sz_yi_dealmerch_user',$mdata,array('uid'=>$merch['uid'],'uniacid'=>$_W['uniacid']));
	        $ordersn='rm'.date('Ymdhi').uniqid();          

	        $data=array( 
	            'status'        => 1, 
	            'level'         =>$level['id'],
	            'paytype'       =>$paytype,
	            'ordersn'       =>$ordersn,
	            'openid'        =>$openid,
	            'uniacid'       =>$_W['uniacid'],
                'ctime'         =>time(),
	        );          
	        // paytype 4 余额 5 微信
            //购买会员分红               
            p('bonus')->calculateVipBonus($openid,$level['cash'],$level['id'],$ordersn);
	        pdo_insert('sz_yi_recharge_member',$data);     
            m('log')->putBarterCurrencyLog($profile['openid'],$merch['uid'],5,$level['currency'],$ordersn,'购买年会员赠送易货额度'); 
            
	        // m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],12,1,2,$activation,$ordersn,'激活所得易货码'); 
	        // $paytype == 2 && m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],11,1,2,-$deduct,$ordersn,'激活易货码手续费');
	        show_json(1,'购买会员成功!');
        }
        
        show_json(0,'非法参数');
 
    }
} else if($op == 'deduct' && $_W['ispost']) {  
    $logid = intval($_GPC['logid']); 
    if (empty($logid)) {
        show_json(0, '充值出错, 请重试!');
    }

    $id = floatval($_GPC['id']);
    if (empty($id)) {
        show_json(0, '请选择等级!');
    }
    $level=p('suppliermenu')->getMemberLevel($id);
    // $freeze_credit3=doubleval($freeze_credit3);     //现有
    // $activation=doubleval($_GPC['actnum']);     //总
    // if ($freeze_credit3 < $activation) {
        // show_json(0, '激活失败,冻结易货码不足激活数量,你当前冻结易货码'.$freeze_credit3);
    // }

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
    $level=p('suppliermenu')->getMemberLevel($id);
	if($log['money'] <= 0){
        pdo_update('sz_yi_member_log', array('money' => floatval($level['cash']), 'couponid' => $couponid,'do'=>'rechargeMember'), array('id' => $log['id']));
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
        $params['fee']   = floatval($level['cash']);
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

    $logno = m('common')->createNO('member_log', 'logno', 'RM');
    $log   = array(
        'uniacid' => $_W['uniacid'],
        'logno' => $logno,
        'title' => $set['shop']['name'] . "购买年会员",
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
}

// else if ($operation == 'complete' && $_W['ispost']) {
//     $logid = intval($_GPC['logid']);
//     $log   = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_member_log') . ' WHERE `id`=:id and `uniacid`=:uniacid limit 1', array(
//         ':uniacid' => $uniacid,
//         ':id' => $logid
//     ));
//     if (!empty($log) && empty($log['status'])) {
//         $payquery = m('finance')->isWeixinPay($log['logno']);
//         if (!is_error($payquery)) {
//             pdo_update('sz_yi_member_log', array(
//                 'status' => 1,
//                 'rechargetype' => $_GPC['type']
//             ), array(
//                 'id' => $logid
//             ));
//             m('member')->setCredit($openid, 'credit2', $log['money']);
//             m('member')->setRechargeCredit($openid, $log['money']);
//             if (p('sale')) {
//                 p('sale')->setRechargeActivity($log);
//             }
//             m('notice')->sendMemberLogMessage($logid);
//         }
//     } 
//     show_json(1);
// }
$iswechat=0;
if (is_weixin()) {
	$iswechat=1;
} 
include $this->template('recharge');
// echo '正在开发，请稍后在试';