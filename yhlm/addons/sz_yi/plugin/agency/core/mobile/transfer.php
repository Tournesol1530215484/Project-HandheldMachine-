<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$openid = m('user')->getOpenid();
$userinfo['credit3']=m('member')->getCredit($openid,'credit3');
$userifno['freeze_credit3']=m('member')->getCredit($openid,'freeze_credit3');
if ( $op == 'getInfo'){
    $mobile=$_GPC['mid'];
    if (empty($mobile) || strlen($mobile) < 11){
        show_json(0,'非法参数!');  
    }  
    $info=pdo_fetch('select realname,openid from '.tablename("sz_yi_member").' where uniacid = :uniacid and mobile = :mobile',array(':uniacid'=>$_W['uniacid'],':mobile'=>$mobile)); 
    $info1=pdo_fetchcolumn('select username from '.tablename('sz_yi_perm_user').' where openid=:openid and dealmerchid > 0 and uniacid = :uniacid ',array(':openid'=>$info['openid'],':uniacid'=>$_W['uniacid']));     
  
    if (empty($info)){ 
        show_json(0,'查无此人,或该用户不是易货商家');    
    }
    show_json(1,$info['realname'].'/'.$info1);
}else if($op == 'btcodetrf'){  
    if ($_W['isajax']){ 
 
        $currency=m('member')->getCredit($openid,'credit3'); 
        if (doubleval($currency) < doubleval($_GPC['btcodenum'])){ 
            show_json(0,'易货码不足,转账失败！');
        }
        $reopenid=pdo_fetchcolumn('select openid from '.tablename("sz_yi_member").' where uniacid = :uniacid and mobile = :mobile',array(':uniacid'=>$_W['uniacid'],':mobile'=>$_GPC['mobile']));  
            
        $sponsorInfo=m('member')->getmember($openid); 
        $recipientInfo=m('member')->getmember($reopenid); 
        $ordersn='tr'.date('YmdHi').uniqid().rand(11111,99999);

        $data=array(
            'uniacid'       =>$_W['uniacid'],
            'sponsor_openid'=>$openid,
            'currency'      =>doubleval($_GPC['btcodenum']),
            'recipient_openid'=>$recipientInfo['openid'],
            'note'          =>$_GPC['btcodetrfnote'],
            'transfertime'  =>time(),
            'ordersn'=>$ordersn
        );
        //发起人
        m('member')->setCredit($openid,'credit3',-doubleval($_GPC['btcodenum']));
        //接收人
        m('member')->setCredit($recipientInfo['openid'],'credit3',doubleval($_GPC['btcodenum']));
        //赠出
        m('log')->putBarterCodeLog($sponsorInfo['openid'],$sponsorInfo['uid'],13,1,3,-doubleval($_GPC['btcodenum']),$ordersn,'赠出易货码');
        m('log')->putBarterCodeLog($recipientInfo['openid'],$recipientInfo['uid'],14,1,3,doubleval($_GPC['btcodenum']),$ordersn,'赠入易货码');
        pdo_insert('sz_yi_transfer_log',$data); 
        show_json(1,'转账成功！');

    }
}else if($op == 'histroy'){
    $page=intval($_GPC['page'])>0?intval($_GPC['page']):0;
    $psize=10;   
    // show_json(0,$openid);

    $sql='select * from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type in (13,14) order by id desc';
    
    $params=[    
        ':openid'=>$openid,   
        ':uniacid'=>$_W['uniacid']  
    ]; 
    $sql.=' limit '.$page*$psize.','.$psize;  
    $log=pdo_fetchall($sql,$params);  
    foreach ($log as $key => $value) {
        $log[$key]['dealtime']=date('Y-m-d H:i:s',$log[$key]['dealtime']);
    } 
    $res=[ 
        'log'=>$log,
        'thisnum'=>count($log),
        'psize'=>$psize 
    ];  
    show_json(1,$res);
} 

include $this->template('transfer');
