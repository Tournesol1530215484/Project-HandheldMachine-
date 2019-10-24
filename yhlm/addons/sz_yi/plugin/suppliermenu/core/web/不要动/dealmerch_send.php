<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

//af_supplier images
global $_W, $_GPC;
$openid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid= :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
$op=!empty($_GPC['op'])?$_GPC['op']:'';
$dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$condition.=' and dealmerchid > 0 ';
$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and  openid = '{$openid}' {$condition} limit 1 ");

$sp_goods = pdo_fetchall('select og.*,o.paytime,o.dispatchprice from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$uid} and og.supplier_apply_status=0 order by og.id desc");

$postprice=0;
foreach ($sp_goods as $key => $value) {
    if ($value['dispatchprice'] > 0) {
        $postprice+=$value['dispatchprice'];
    }
}
//$totalcount=$total =pdo_fetchcolumn('select count(og.id) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$uid} and og.supplier_apply_status=0");
//
//$pager = pagination($total, $pindex, $psize);

load() -> func('tpl');
include $this -> template('dealmerch_send');
exit;
