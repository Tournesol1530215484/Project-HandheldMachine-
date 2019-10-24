<?php
global $_W, $_GPC;
    //
$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$set=$this->getset();
$order_id=intval($_GPC['id']);
if (!empty($order_id)){
    $param=[
        ':orderid'=>$order_id,
        ':uniacid'=>$_W['uniacid']
    ];
    $order=pdo_fetch('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and id = :orderid',$param);
    $carrier=unserialize($order['carrier']);
    $address=pdo_fetch('select * from '.tablename('sz_yi_member_address').' where uniacid = :uniacid and id = :id',array(
        ':uniacid' => $_W['uniacid'],
        ':id'      => $order['addressid']
    ));
    $goods=pdo_fetch('select * from '.tablename('sz_yi_goods').' where uniacid = :uniacid and id = :goodsid',array(
        ':uniacid' => $_W['uniacid'],
        ":goodsid" => $order['goodsid']
    ));

}

var_dump($set);
var_dump(123);



