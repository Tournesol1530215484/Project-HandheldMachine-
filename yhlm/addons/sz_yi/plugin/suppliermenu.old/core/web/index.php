<?php

if (!defined('IN_IA')) {
    print ('Access Denied');
}
global $_W, $_GPC;
load()->func('tpl');


$uid = intval($_W['uid']);
// var_dump($uid);
$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uid="' . $uid . '" and uniacid=' . $_W['uniacid']);
// 查询身份证图片
$supplierinfo['idimgs'] = pdo_fetch('select idimg1,idimg2 from'.tablename('sz_yi_supplier_idimages').'where uniacid=:uniacid and openid=:openid', array(':uniacid'=>$_W['uniacid'], ':openid'=>$supplierinfo['openid']));
// var_dump($supplierinfo);
if(!empty($supplierinfo['openid'])){
    $saler = m('member') -> getInfo($supplierinfo['openid']);
}
$totalmoney = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
$totalmoneyok = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.supplier_apply_status=1 and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));

if(checksubmit('submit')){
    $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
    $data['provance'] = $_GPC['birth']['province'];
    $data['city']     = $_GPC['birth']['city'];
    $data['area']     = $_GPC['birth']['district'];
    // print_r($data);exit;
    pdo_update('sz_yi_perm_user', $data, array('uid' => $uid));
    message('保存成功!', referer(), 'success');
}

$url = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&do=shop&m=sz_yi&supplier={$_W['uid']}";

include $this->template('index');


