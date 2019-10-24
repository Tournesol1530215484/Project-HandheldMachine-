<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

//af_supplier images
global $_W, $_GPC;

/*if ($_GPC['debug']) {
    $list=pdo_fetchall('select * from '.tablename('sz_yi_currency_activation').' where uniacid = :uniacid and paytype = 4',array(':uniacid'=>$_W['uniacid']));
    foreach ($list as $key => $value) {
        if ($value['activapay'] > 0) {
            m('member')->setCredit($value['openid'],'credit3',$value['activapay']);
            pdo_update('sz_yi_currency_activation',array('activapay'=>0),array('id'=>$value['id']));
        }
    }       //debug
    exit;
}*/
$op=!empty($_GPC['op'])?$_GPC['op']:'';
$dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and ca.uniacid= :uniacid ';
$params = array(':uniacid' => $_W['uniacid']); 
$set=pdo_fetchcolumn('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
$set=unserialize($set);
$ratio=doubleval($set['bart']['ratio']);        //其他地方需要手续费
$ratio=0.00;                    //总后台激活不需要手续费
//if (empty($starttime) || empty($endtime)){
//    $starttime = strtotime('-1 month');
//    $endtime = time();
//}

//if (!empty($_GPC['ordersn'])){
//    $condition .= ' and o.ordersn like :ordersn';
//    $params[':ordersn'] = "%{$_GPC['ordersn']}%";
//}
//if (!empty($_GPC['supplier_uid'])){
//    $condition .= ' and og.supplier_uid = :supplier_uid';
//    $params[':supplier_uid'] = "{$_GPC['supplier_uid']}";
//}else{
//    $condition .= ' and og.supplier_uid > 0';
//}
//if (!empty($_GPC['uid'])){
//    $condition.=' and ca.uid=:uid ';
//    $params[':uid']=$_GPC['uid'];
//}



                              

if ($op == 'getInfo'){
    if (!empty($_GPC['openid'])){
        $info['credit3']=m('member')->getCredit($_GPC['openid'],'credit3');
        $info['freeze_credit3']=m('member')->getCredit($_GPC['openid'],'freeze_credit3');
        $info['currency_credit3']=m('member')->getCredit($_GPC['openid'],'currency_credit3');
        show_json(1,$info);
    }
        show_json(0,'非法参数');
}
if ($op == 'direct'){

    // 这里计算会员分成开始

    if ($_W['isajax']) {                                    
        if ($ac == 'getlist') {     
            $logid=intval($_GPC['logid']);                      

            // $sql='select bl.money,bl.ctime,bl.level,m.realname,m.nickname from '.tablename('sz_yi_activity_bonus_log').' bl left join '.tablename('sz_yi_member').' m on m.openid = bl.openid where bl.uniacid = :uniacid and bl.cate = 2 and bl.logid = :logid ';                                      
            // $params=array(              
            //     ':uniacid'=>$_W['uniacid'],                                    
            //     ':logid'=>$logid,           
            // );    
            // $list=pdo_fetchall($sql,$params);     //获取所有的代理商信息        
            
            // $rule=array(
            //     '商家',                
            //     '一级',
            //     '二级',
            //     '区代',        
            //     '市代',
            //     '省代',
            //     '总部'
            // );

            // if ($list) {
            //     foreach ($list as $key => $value) {  
            //         $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            //         $list[$key]['rule']=$rule[intval($value['level'])];
                    
            //     }
            //     show_json(1,$logid);
            // }   
            $res=$this->getParentAgents($logid);     
            show_json(1,$res);
        }
    }


    // 计算结束


    $openid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uid = '.$_GPC['openid']);
    if ($openid) {
        $_GPC['openid']=$openid;
    }       

    $profile=pdo_fetch('select openid,uid from '.tablename('sz_yi_member').'where openid=:uid and uniacid=:uniacid',array(':uid'=>$_GPC['openid'],':uniacid'=>$_W['uniacid']));
    $ordersn='ac'.date('Ymdhi').uniqid();
    $data=array(
        'uniacid'=>$_W['uniacid'],
        'activacurrency'=>doubleval($_GPC['freeze_currency']),
        'type'=>1,          //1易货额度激活
        'activatime'=>time(),
        'ordersn'=>$ordersn,
        'paytype'=>4,         //直接
        'openid'=>$profile['openid']
    ); 

    if ($profile){

        $currency=m('member')->getCredit($_GPC['openid'],'freeze_credit3');
        $currency_credit3=m('member')->getCredit($_GPC['openid'],'currency_credit3');   //易货额度
        if (doubleval($currency)<doubleval($_GPC['freeze_currency'])){
            show_json(0,'冻结易货码不足');
        }
        $deduct=doubleval($_GPC['freeze_currency']) * ($ratio / 100);   //百分比        
        $data['activapay']=$deduct;     
        $realCurrency=doubleval($_GPC['freeze_currency'])-$deduct;      
        m('member')->setCredit($_GPC['openid'],'credit3',$realCurrency, array($profile['uid'], '后台会员激活'.doubleval($_GPC['freeze_currency']).'易货码'.'激活扣除'.$deduct)); //激活易货码
        m('member')->setCredit($_GPC['openid'],'freeze_credit3',-doubleval($_GPC['freeze_currency']), array($profile['uid'], '后台会员解冻'.doubleval($_GPC['freeze_currency']).'易货码')); //激活易货码
        pdo_insert('sz_yi_currency_activation',$data);
        m('log')->putBarterCodeLog($_GPC['openid'],$profile['uid'],12,1,2,$realCurrency,$ordersn,'激活所得易货码');
        m('log')->putBarterCodeLog($_GPC['openid'],$profile['uid'],11,1,2,-$deduct,$ordersn,'激活易货码手续费');
        m('log')->putBarterCurrencyLog($_GPC['openid'],$profile['uid'],2,-doubleval($_GPC['freeze_currency']),$ordersn,'易货码激活');
        show_json(1,'激活成功');
    }
    show_json(0,'非法参数');
}


if (!empty($_GPC['openid'])){
   $condition .= ' and ca.openid = :openid';                 
   $params[':openid'] = "{$_GPC['openid']}";
   $selmerch=p('bonus')->getmerch($_GPC['supplier_uid']);       
   $info['credit3']=m('member')->getCredit($_GPC['openid'],'credit3');
     $info['freeze_credit3']=m('member')->getCredit($_GPC['openid'],'freeze_credit3');
    $info['currency_credit3']=m('member')->getCredit($_GPC['openid'],'currency_credit3'); 
}        


if (!empty($_GPC['time'])){
   $starttime = strtotime($_GPC['time']['start']);
   $endtime = strtotime($_GPC['time']['end']);
   if (!empty($_GPC['searchtime'])){
       $condition .= ' AND ca.activatime >= :starttime AND ca.activatime <= :endtime ';
       $params[':starttime'] = $starttime;
       $params[':endtime'] = $endtime;
   }            
}

                    
$sql = 'select m.realname,m.nickname,ca.id,ca.type,ca.activacurrency,ca.activapay,ca.activatime,ca.paytype from '.tablename('sz_yi_currency_activation').' ca left join '.tablename('sz_yi_member').' m on ca.openid=m.openid where 1 ';
$sql.=$condition;
$sql .= ' ORDER BY ca.activatime DESC ';
$sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$list = pdo_fetchall($sql,$params);         
        
$totalcount = $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_currency_activation').' ca left join '.tablename('sz_yi_member').' m on ca.openid=m.openid where 1 '.$condition,$params);

$pager = pagination($total, $pindex, $psize);
if ($_GPC['export'] == 1){
    ca('statistics.export.order');
    plog('statistics.export.order', '导出订单统计');
    $list[] = array('data' => '订单总计', 'count' => $totalcount);
    $list[] = array('data' => '金额总计', 'count' => $totalmoney);
    foreach ($list as & $row){
        if ($row['paytype'] == 1){
            $row['paytype'] = '余额支付';
        }else if ($row['paytype'] == 11){
            $row['paytype'] = '后台付款';
        }else if ($row['paytype'] == 21){
            $row['paytype'] = '微信支付';
        }else if ($row['paytype'] == 22){
            $row['paytype'] = '支付宝支付';
        }else if ($row['paytype'] == 23){
            $row['paytype'] = '银联支付';
        }else if ($row['paytype'] == 3){
            $row['paytype'] = '货到付款';
        }
        $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
    }
    unset($row);
    m('excel') -> export($list, array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '总金额', 'field' => 'price', 'width' => 12), array('title' => '商品金额', 'field' => 'goodsprice', 'width' => 12), array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12), array('title' => '付款方式', 'field' => 'paytype', 'width' => 12), array('title' => '会员名', 'field' => 'realname', 'width' => 12), array('title' => '收货人', 'field' => 'addressname', 'width' => 12), array('title' => '下单时间', 'field' => 'createtime', 'width' => 24))));
}


if ($op == 'reward'){

    if ($_W['isajax']) {                                    
        if ($ac == 'getlist') {     
            $logid=intval($_GPC['logid']);                      

            $sql='select bl.money,bl.ctime,bl.level,m.realname,m.nickname from '.tablename('sz_yi_activity_bonus_log').' bl left join '.tablename('sz_yi_member').' m on m.openid = bl.openid where bl.uniacid = :uniacid and bl.cate = 2 and bl.logid = :logid ';                                      
            $params=array(              
                ':uniacid'=>$_W['uniacid'],                                    
                ':logid'=>$logid,           
            );    
            $list=pdo_fetchall($sql,$params);     //获取所有的代理商信息        
            
            $rule=array(
                '商家',                
                '一级',
                '二级',
                '区代',        
                '市代',
                '省代',
                '总部'
            );

            if ($list) {
                foreach ($list as $key => $value) {  
                    $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
                    $list[$key]['rule']=$rule[intval($value['level'])];
                    
                }
                show_json(1,$logid);
            }        
            show_json(0,array(222222222));
        }
    }

    // $pindex = max(1, intval($_GPC['page']));     
    // $psize = 20;         
    // $roleid =   50;
    // $condition = ' ';         

    // $condition .= ' and ar.uniacid = ' . $_W['uniacid'];
                 
    // $sql='select ar.*,m.nickname,m.realname from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where 1 ';
    // $sql.=$condition;                                        

    // $sql .= ' order by ar.id desc limit ' . ($pindex - 1) * $psize . ',' . $psize;                                                       
    // $list = pdo_fetchall($sql);                                      

    // foreach ($list as $key => $value) {                        
    //     if ($value['type'] <= 2) {   
    //         $list[$key]['info']=m('activity')->getact($value['atid'],$value['type']);
    //     }else{                                             
    //         $list[$key]['info']=m('member')->getMember($value['atid']);
    //     }                    
    // }                                                              
                                                                                               
    // $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid  where 1 ' . $condition);                               
    // $pager = pagination($total, $pindex, $psize);
}

// $member=pdo_fetchall('select pu.openid,pu.uid as hsuid,m.uid as muid,pu.username,m.realname,m.nickname from '.tablename('sz_yi_perm_user').' pu left join '.tablename('sz_yi_member').' m on m.openid=pu.openid where pu.dealmerchid > 0 and pu.status = 1 and pu.uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));

load() -> func('tpl');
include $this -> template('dealmerch_activation');
exit;
