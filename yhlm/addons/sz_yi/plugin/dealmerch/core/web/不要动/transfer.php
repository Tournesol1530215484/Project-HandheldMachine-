<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;

$op = empty($_GPC['op']) ?'dispaly':trim($_GPC['op']);

$pindex = max(1, intval($_GPC['page']));
$psize = 15;

$condition=' uniacid=:uniacid ';
$params=array(
    ':uniacid'=>$_W['uniacid']
);
$sets=p('dealmerch')->getSysSet();

if (!empty($_GPC['datetime'])){         //如果时间不为空 判断是否按时间搜索
    $starttime=strtotime($_GPC['datetime']['start']);
    $endtime=strtotime($_GPC['datetime']['end']);
    if ($_GPC['searchtime'] == '1'){
        $condition.=' and transfertime > :starttime and transfertime < :endtime ';
        $params[':starttime']=$starttime;
        $params[':endtime']=$endtime;
    }
}

if ($op== 'getInfo' ){
    $memberInfo=m('member')->getmember(intval($_GPC['uid']));
    if (empty($memberInfo)){
        show_json(0,'没有该用户');
    }else{
        if ($_GPC['who'] == 'sponsor'){
            $memberInfo['credit3']=m('member')->getCredit($memberInfo['openid'],'credit3');
            $memberInfo['credit2']=m('member')->getCredit($memberInfo['openid'],'credit2');
        }
        show_json(1,$memberInfo);
    }
}else if ($op == 'transfer' && checksubmit()){
    if ($_W['ispost']){

        $sponsorInfo=m('member')->getmember($_GPC['sponsor_uid']);
        $recipientInfo=m('member')->getmember($_GPC['recipient_uid']);
        $sponsorInfo['credit3']=m('member')->getCredit($sponsorInfo['openid'],'credit3');   //获取真实credit3
        $sponsorInfo['credit2']=m('member')->getCredit($sponsorInfo['openid'],'credit2');   //获取真实credit3
        $recipientInfo['credit3']=m('member')->getCredit($recipientInfo['openid'],'credit3');
        $ratio=floatval($sets['bart']['ratio']);
        $ordersn='tr'.date('YmdHi').uniqid().rand(11111,99999);

        if ($ratio) {
            $fee= (floatval($ratio / 100))  * floatval($_GPC['currency']);
            
            if ($sponsorInfo['credit2'] < $fee ) {
                message('发起方余额不足，扣除手续费失败',$this->createPluginWebUrl('dealmerch/transfer'),'error');
            }
            m('member')->setCredit($sponsorInfo['openid'], 'credit2',-$fee, array($sponsorInfo['uid'], '总后台扣除转账易货码手续费')); //易货码充值
            if ($sets['bart']['trbonus'] == 1) {
                p('bonus')->calculateactive($sponsorInfo['openid'],$fee,$ordersn);  //计算易货分红
            }
        }           
        if (doubleval($sponsorInfo['credit3'])<doubleval($_GPC['currency'])){
            message('易货码不足，转账失败',$this->createPluginWebUrl('dealmerch/transfer'),'error');
        }
        $data=array(
            'uniacid'=>$_W['uniacid'],
            'sponsor_openid'=>$sponsorInfo['openid'],
            'currency'=>doubleval($_GPC['currency']),
            'recipient_openid'=>$recipientInfo['openid'],
            'note' => trim($_GPC['note']),
            'deduct'    =>$fee,
            'ordersn'=>$ordersn
        );
        m('member')->setCredit($sponsorInfo['openid'], 'credit3',-$_GPC['currency'], array($sponsorInfo['uid'], '后台会员减去转账易货码')); //易货码充值
        m('member')->setCredit($recipientInfo['openid'], 'credit3',+$_GPC['currency'], array($recipientInfo['uid'], '后台会员增加转账易货码')); //易货码充值
        pdo_insert('sz_yi_transfer_log',$data);
        //赠出
        m('log')->putBarterCodeLog($sponsorInfo['openid'],$sponsorInfo['uid'],13,1,3,-doubleval($_GPC['currency']),$ordersn,'赠出易货码');
        m('log')->putBarterCodeLog($recipientInfo['openid'],$recipientInfo['uid'],14,1,3,doubleval($_GPC['currency']),$ordersn,'赠入易货码');
        plog('dealmerch.save.transfer', '转账');
        message('转账成功!', $this -> createPluginWebUrl('dealmerch/transfer'), 'success');
    }

}

$condition.='order by id desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

$list=pdo_fetchall('select id,sponsor_openid,currency,recipient_openid,note,transfertime from '.tablename('sz_yi_transfer_log').' where'.$condition,$params);

foreach($list as $k =>&$v){
//    print_r($v['recipient_openid']);exit;
//    print_r($v);exit;
    $tempname=pdo_fetch('select realname,nickname from '.tablename('sz_yi_member').' where openid="'.$v['sponsor_openid'].'"');

    $v['sponsor_name']=!empty($tempname['realname'])?$tempname['realname']:$tempname['nickname'];
    $tempname=pdo_fetch('select realname,nickname from '.tablename('sz_yi_member').' where openid="'.$v['recipient_openid'].'"');
    $v['recipient_name']=!empty($tempname['realname'])?$tempname['realname']:$tempname['nickname'];
}

$totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_transfer_log').' where '.$condition,$params);
$pager = pagination($total, $pindex, $psize);

load() -> func('tpl');
include $this -> template('transfer');
