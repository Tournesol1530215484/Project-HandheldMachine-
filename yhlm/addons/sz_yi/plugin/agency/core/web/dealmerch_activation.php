<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$op=!empty($_GPC['op'])?$_GPC['op']:'';
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$openid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid' => $_W['uid']));
$condition = ' and ca.uniacid=:uniacid and ca.openid = :openid ';
$params = array(
    ':uniacid' => $_W['uniacid'],
    ':openid' => $openid
);

//if (empty($starttime) || empty($endtime)){
//    $starttime = strtotime('-1 month');
//    $endtime = time();
//}
//if (!empty($_GPC['datetime'])){
//    $starttime = strtotime($_GPC['datetime']['start']);
//    $endtime = strtotime($_GPC['datetime']['end']);
//    if (!empty($_GPC['searchtime'])){
//        $condition .= ' AND o.createtime >= :starttime AND o.createtime <= :endtime ';
//        $params[':starttime'] = $starttime;
//        $params[':endtime'] = $endtime;
//    }
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
//if (!empty($_W['uid'])){
//    $condition.=' and ca.uid=:uid ';
//    $params[':uid']=$_W['uid'];
//}
//if ($op == 'getInfo'){
//    if (!empty($_W['uid'])){
//        $info=pdo_fetch('select credit3,freeze_credit3 from '.tablename('sz_yi_member').' where uniacid=:uniacid and uid=:uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
//        show_json(1,$info);
//    }
//        show_json(0,'非法参数');
//}

if ($op == 'direct'){
    $set=pdo_fetchcolumn('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
    $set=unserialize($set);
    $ratio=doubleval($set['bart']['ratio']);
    $ordersn='ac'.date('Ymdhi').uniqid();
    $data=array(
        'uniacid'=>$_W['uniacid'],
        'activacurrency'=>doubleval($_GPC['freeze_currency']),
        'type'=>1,          //1易货额度激活
        'activatime'=>time(),  
        'ordersn'=>$ordersn, 
        'paytype'=>4,             //直接
        'openid'=>$openid 
    );  
    $profile=pdo_fetch('select openid,uid from '.tablename('sz_yi_member').'where openid =:openid and uniacid=:uniacid',array(':openid'=>$openid,':uniacid'=>$_W['uniacid'])); 
    if ($profile){ 
        $currency=m('member')->getCredit($profile['openid'],'freeze_credit3'); 
        if (doubleval($currency)<doubleval($_GPC['freeze_currency'])){ 
            show_json(0,'冻结易货码不足'); 
        }
        $deduct=doubleval($_GPC['freeze_currency']) * ($ratio / 100);  
        $data['activapay']=$deduct;  
        $realCurrency=doubleval($_GPC['freeze_currency'])-$deduct;// 实得($realCurrency)=激活数量($__GPC['freeze_currency'])-手续费(($deduct))
        m('member')->setCredit($profile['openid'],'credit3',$realCurrency, array($profile['uid'], '商家后台会员激活'.doubleval($_GPC['freeze_currency']).'易货码'.'激活扣除'.$deduct)); //激活易货码
        m('member')->setCredit($profile['openid'],'freeze_credit3',-doubleval($_GPC['freeze_currency']), array($profile['uid'], '商家后台会员解冻'.doubleval($_GPC['freeze_currency']).'易货码')); //激活易货码
        pdo_insert('sz_yi_currency_activation',$data);
        m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],12,1,2,$realCurrency,$ordersn,'激活所得易货码');
        show_json(1,'激活成功');
        m('log')->putBarterCodeLog($profile['openid'],$profile['uid'],11,1,2,-$deduct,$ordersn,'激活易货码手续费');
    }
    show_json(0,'非法参数');
} 
 
$sql = 'select ca.id,ca.paytype,ca.type,ca.activacurrency,ca.activapay,ca.activatime,m.realname,m.nickname from ' . tablename('sz_yi_currency_activation') ." ca left join ".tablename('sz_yi_member')." m on m.openid=ca.openid  where 1 {$condition} ";
$sql .= ' ORDER BY ca.activatime DESC '; 
//if (empty($_GPC['export'])){
//    $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
//}
$list = pdo_fetchall($sql, $params);
//foreach ($list as & $row){
//    $row['ordersn'] = $row['ordersn'] . ' ';
//    $row['goods'] = pdo_fetchall('SELECT g.thumb,og.price,og.total,og.realprice,g.title,og.optionname from ' . tablename('sz_yi_order_goods') . ' og' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid  ' . ' where og.uniacid = :uniacid and og.orderid=:orderid order by og.createtime  desc ', array(':uniacid' => $_W['uniacid'], ':orderid' => $row['id']));
//    $totalmoney += $row['price'];
//}
//if (empty($totalmoney)){
//    $totalmoney = 0;
//}
//unset($row);
 
$totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_currency_activation') . " ca left join ".tablename('sz_yi_member')."  m on m.openid = ca.openid where 1 {$condition}", $params);
$pager = pagination($total, $pindex, $psize); 
// if ($_GPC['export'] == 1){
// //    ca('statistics.export.order');
//     plog('statistics.export.order', '导出订单统计');
//     $list[] = array('data' => '订单总计', 'count' => $totalcount);
//     $list[] = array('data' => '金额总计', 'count' => $totalmoney);
//     foreach ($list as & $row){
//         if ($row['paytype'] == 1){
//             $row['paytype'] = '余额支付';
//         }else if ($row['paytype'] == 11){
//             $row['paytype'] = '后台付款';
//         }else if ($row['paytype'] == 21){
//             $row['paytype'] = '微信支付';
//         }else if ($row['paytype'] == 22){
//             $row['paytype'] = '支付宝支付';
//         }else if ($row['paytype'] == 23){
//             $row['paytype'] = '银联支付';
//         }else if ($row['paytype'] == 3){
//             $row['paytype'] = '货到付款';
//         }
//         $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
//     }
//     unset($row);
//     m('excel') -> export($list, array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '总金额', 'field' => 'price', 'width' => 12), array('title' => '商品金额', 'field' => 'goodsprice', 'width' => 12), array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12), array('title' => '付款方式', 'field' => 'paytype', 'width' => 12), array('title' => '会员名', 'field' => 'realname', 'width' => 12), array('title' => '收货人', 'field' => 'addressname', 'width' => 12), array('title' => '下单时间', 'field' => 'createtime', 'width' => 24))));

// }
$member['credit3']=m('member')->getCredit($openid,'credit3');
$member['freeze_credit3']=m('member')->getCredit($openid,'freeze_credit3');
$member['currency_credit3']=m('member')->getCredit($openid,'currency_credit3');
load() -> func('tpl');
include $this -> template('dealmerch_activation');
exit;
