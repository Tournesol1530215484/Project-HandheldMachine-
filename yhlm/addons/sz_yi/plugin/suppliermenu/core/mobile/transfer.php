<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$popenid        = m('user')->islogin();
$openid = m('user')->getOpenid();
$openid = $openid?$openid:$popenid;

$userinfo['credit3']=m('member')->getCredit($openid,'credit3');                                
$userinfo['currency_credit3']=m('member')->getCredit($openid,'currency_credit3');               
$userifno['freeze_credit3']=m('member')->getCredit($openid,'freeze_credit3');                 
$sets=p('dealmerch')->getSysSet();
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
        
        $ratio=floatval($sets['bart']['tratio']);
        $currency=m('member')->getCredit($openid,'credit3');
        $credit2=m('member')->getCredit($openid,'credit2'); 
        $ordersn='tr'.date('YmdHi').uniqid().rand(11111,99999);

        if (doubleval($currency) < doubleval($_GPC['btcodenum'])){ 
            show_json(0,'易货码不足,转账失败！');
        }

        $reopenid=pdo_fetchcolumn('select openid from '.tablename("sz_yi_member").' where uniacid = :uniacid and mobile = :mobile',array(':uniacid'=>$_W['uniacid'],':mobile'=>$_GPC['mobile']));  
            
        $sponsorInfo=m('member')->getmember($openid); 
        $sponsorInfo['currency_credit3']=m('member')->getCredit($openid,'currency_credit3');
        if (floatval($sponsorInfo['currency_credit3']) < floatval($_GPC['btcodenum'])) {
            show_json(0,'易货额度不足，转账失败,请买额度');        
        }                               
        m('member')->setCredit($openid,'currency_credit3',-floatval($_GPC['btcodenum']));


        $recipientInfo=m('member')->getmember($reopenid); 

        $data=array(
            'uniacid'         =>$_W['uniacid'],
            'sponsor_openid'  =>$openid,
            'currency'        =>doubleval($_GPC['btcodenum']),
            'recipient_openid'=>$recipientInfo['openid'],
            'note'            =>$_GPC['btcodetrfnote'],
            'transfertime'    =>time(),
            'deduct'          =>$fee,
            'ordersn'         =>$ordersn
        );
        //发起人
        m('member')->setCredit($openid,'credit3',-doubleval($_GPC['btcodenum']));
        //接收人
        m('member')->setCredit($recipientInfo['openid'],'credit3',doubleval($_GPC['btcodenum']));
        //赠出
        m('log')->putBarterCodeLog($sponsorInfo['openid'],$sponsorInfo['uid'],13,1,3,-doubleval($_GPC['btcodenum']),$ordersn,'赠出易货码');
        m('log')->putBarterCodeLog($recipientInfo['openid'],$recipientInfo['uid'],14,1,3,doubleval($_GPC['btcodenum']),$ordersn,'赠入易货码');
        pdo_insert('sz_yi_transfer_log',$data); 
        // if ($ratio) {
        //     $fee= (floatval($ratio / 100))  * floatval($_GPC['btcodenum']);
            
        //     if ($credit2 < $fee ) {
        //         show_json(0,'发起方余额不足，扣除手续费失败');
        //     }
            
        //     m('member')->setCredit($openid, 'credit2',-$fee); //易货码充值
        //     if ($sets['bart']['trbonus'] == 1) {         
        //         p('bonus')->calculateactive($openid,$fee,pdo_insertid(),$ordersn);  //计算易货分红
        //     }
        // }
        show_json(1,'转账成功！');

    }
}else if($op == 'histroy'){
    $page=intval($_GPC['page'])>0?intval($_GPC['page']):0;
    $psize=10;   
    // show_json(0,$openid);

    $sql='select tl.*,m.nickname snname,m.realname srname,dm.nickname rnname,dm.realname rrname from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on m.openid=tl.sponsor_openid left join '.tablename('sz_yi_member').' dm on dm.openid = tl.recipient_openid where tl.uniacid = :uniacid and tl.sponsor_openid = :openid or tl.recipient_openid = :openid order by tl.id desc';
    
    $params=[    
        ':openid'=>$openid,   
        ':uniacid'=>$_W['uniacid']  
    ]; 
    $sql.=' limit '.$page*$psize.','.$psize;  
    $log=pdo_fetchall($sql,$params);  
    foreach ($log as $key => $value) {
        $log[$key]['dealtime']=date('Y-m-d H:i:s',$log[$key]['transfertime']);
    } 
    $res=[ 
        'log'=>$log,
        'thisnum'=>count($log),
        'psize'=>$psize 
    ];  
    show_json(1,$res);
} 

include $this->template('transfer');
