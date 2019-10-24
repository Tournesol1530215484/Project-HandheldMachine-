<?php



global $_W, $_GPC;



$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$popenid = m('user')->islogin();

$openid = m('user')->getOpenid();

$openid = $openid?$openid:$popenid;



$set=pdo_fetchcolumn('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));  //获取系统公众号设置

$set=unserialize($set);

$ratio=doubleval($set['bart']['ratio']);        //默认

$tmember=m('member')->getMember($openid);       //获取当前用户信息

if (intval($tmember['bonus_area']) > 0) {

    $bSet=p('bonus')->getSet();

    if (intval($tmember['bonus_area']) == 1) {

        $ratio=floatval($bSet['bart_province_disc']);   

    }else if (intval($tmember['bonus_area']) == 2) {

        $ratio=floatval($bSet['bart_city_disc']);

    }else if (intval($tmember['bonus_area']) == 3) {

        $ratio=floatval($bSet['bart_area_disc']);

    }else if (intval($tmember['bonus_area']) == 4) {

        $ratio=floatval($bSet['bart_shop_disc']);

    }

}

$decimal=floatval($ratio / 100);    //手续费         

$freeze_credit3=m('member')->getCredit($openid,'freeze_credit3');//冻结额度

if ($op == 'editpwd') {

    if ($_W['isajax']){ 

                    //手续费百分比 

        $freeze_credit3=doubleval($freeze_credit3);     //现有冻结额度

        $activation=doubleval($_GPC['activation']);     //总额度

        if ($freeze_credit3 < $activation) {

            show_json(0, '激活失败,冻结易货码不足激活数量,你当前冻结易货码'.$freeze_credit3);

        }  

        $deduct=$activation * $decimal;   //手续费 

        // $realCurrency=$activation-$deduct;  //  实得



        

        if (intval($_GPC['paytype']) == 4) {    //余额支付

            $credit2=m('member')->getCredit($openid,'credit2'); //扣除手续费

            doubleval($credit2) < doubleval($deduct) && show_json(0,'激活失败，手续费不足!');

            m('member')->setCredit($openid,'credit2',-doubleval($deduct));

            $paytype=2;                             

        }

         

        if (intval($_GPC['paytype']) == 5) {    //支付 

           	intval($_GPC['payed']) == 0 && show_json(0,'订单未支付!');

            m('member')->setCredit($openid,'credit2',-doubleval($deduct));    
            //会自动充值激活费用到余额 这里扣除激活费用  2018.12.14 15:01   

            $paytype=1;                         

        }                    



        if (!empty($paytype)) {			//余额或微信

        	m('member')->setCredit($openid,'freeze_credit3',-$activation);  //扣除

	        m('member')->setCredit($openid,'credit3',$activation);  //得到完整   

	        $ordersn='ac'.date('Ymdhi').uniqid();          

	        $profile=pdo_fetch('select openid,uid from '.tablename('sz_yi_member').'where openid=:uid and uniacid=:uniacid',array(':uid'=>$_GPC['openid'],':uniacid'=>$_W['uniacid']));



	        $data=array( 

	            'activacurrency'=>$activation,    //易货码激活总额

	            'type'          => 1, 

	            'activatime'    =>time(),

	            'activapay'     =>$deduct, //手续费

	            'paytype'       =>$paytype,   //支付方式

	            'ordersn'       =>$ordersn,    //订单号

	            'openid'        =>$openid,     //opendid

	            'uniacid'        =>$_W['uniacid']  //uniacid id

	        );  

	            // paytype 4 余额 5 微信

            //充值分红 

	        pdo_insert('sz_yi_currency_activation',$data);

            p('bonus')->calculateactive($openid,$deduct,pdo_insertid(),$ordersn);

	        m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],12,1,2,$activation,$ordersn,'激活所得易货码'); 

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

            $wechat            = m('common')->wechat_build($params, $options, 6);

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

        //'title' => $set['shop']['name'] . "会员充值",
        'title' =>  "换货服务费激活",

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



$iswechat=0;

if (is_weixin()) {

	$iswechat=1;

} 

include $this->template('activa');

// echo '正在开发，请稍后在试';