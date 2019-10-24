<?php
global $_W, $_GPC;

$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$set=$this->getset();
$order_id=intval($_GPC['id']);
$param=[
    ':orderid'=>$order_id,
    ':uniacid'=>$_W['uniacid']
];

$order=pdo_fetch('select o.id ,o.address,o.carrier ,o.status , o.isverify , o.paytype , o.price , o.dispatchprice ,
 o.virtual ,o.dispatchtype, o.addressid , o.virtual_str,o.olddispatchprice , o.goodsprice, o.discountprice,o.discountprice,
 o.deductprice , o.deductcredit2,o.changeprice,o.changedispatchprice , o.couponprice ,o.ordersn,o.finishtime,
 o.expresssn ,o.iscomment,re.status as canrefund ,o.refundid from '.tablename('sz_yi_order').' as o left join '
.tablename('sz_yi_order_refund').' as re on re.id=o.refundid where o.id=:orderid and o.uniacid=:uniacid',$param);

$goods=pdo_fetchall('select og.goodsid,og.total,og.total,og.diyformfields,og.optionid,og.price,og.diyformfields,g.thumb,g.title from '
.tablename('sz_yi_order_goods').' as og left join '.tablename('sz_yi_goods').' as g on g.id = og.goodsid where og.orderid=:orderid and og.uniacid=:uniacid',$param);

foreach ($order as $key => &$value) {
    if ($key=='address'|| $key=='carrier'){
        $order[$key]=unserialize($order[$key]);
    }
}

$address=$order['address'];
$carrier=$order['carrier'];
include $this->template('order_detail');

