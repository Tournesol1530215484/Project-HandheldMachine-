<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;

$dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and o.uniacid=:uniacid and isexchange = 1 and addressid = 0 ';
$params = array(':uniacid' => $_W['uniacid']);
if (!empty($_GPC['status'])){
    $condition .= ' and o.status =:status ';
    $params[':status']=$_GPC['status'];
}
if (p('supplier')){         //如果是供应商
    $condition.=' and o.supplier_uid = :uid ';
    $params[':uid'] = $_W['uid'];
}

$sql = 'select o.id,o.isverify,o.verified,o.status,o.ordersn,o.price,o.paytime,o.goodsprice, o.dispatchprice,o.createtime, o.paytype, a.title , a.mobile as exchangeAddrTel,m.mobile, m.realname from ' . tablename('sz_yi_order') . ' o  left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id left join ' . tablename('sz_yi_member') . ' m on o.openid = m.openid left join ' . tablename('sz_yi_exchange_address') . " a on a.id = o.storeid where 1 {$condition} ";
$sql .= ' ORDER BY o.id DESC ';
if (empty($_GPC['export'])){
    $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
}
$list = pdo_fetchall($sql, $params);
foreach ($list as & $row){
    $row['ordersn'] = $row['ordersn'] . ' ';
    $row['goods'] = pdo_fetchall('SELECT g.goodssn,g.title as goodsname ,g.thumb,og.price,og.total,og.realprice,g.title,og.optionname from ' . tablename('sz_yi_order_goods') . ' og' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid  ' . ' where og.uniacid = :uniacid and og.orderid=:orderid order by og.createtime  desc ', array(':uniacid' => $_W['uniacid'], ':orderid' => $row['id']));
    $totalmoney += $row['price'];
}
if (empty($totalmoney)){
    $totalmoney = 0;
}
unset($row);
$totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' o ' . ' left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id left join ' . tablename('sz_yi_member') . ' m on o.openid = m.openid ' . ' left join ' . tablename('sz_yi_exchange_address') . ' a on a.id = o.storeid ' . " where 1 {$condition}", $params);
$pager = pagination($total, $pindex, $psize);
if ($_GPC['export'] == 1){
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

    foreach ($list as $k => &$v){
        $v['createtime']=date('Y-m-d H:i:s',$v['createtime']);
        $v['paytime']=date('Y-m-d H:i:s',$v['paytime']);
    }
    m('excel') -> export($list, array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '买家会员名称', 'field' => 'realname', 'width' => 12), array('title' => '买家电话', 'field' => 'mobile', 'width' => 12), array('title' => '兑换点名称', 'field' => 'title', 'width' => 12), array('title' => '兑换点电话', 'field' => 'exchangeAddrTel', 'width' => 12), array('title' => '下单时间', 'field' => 'cretetime', 'width' => 24), array('title' => '总金额(易货码)', 'field' => 'price', 'width' => 12), array('title' => '交易日期', 'field' => 'paytime', 'width' => 24), array('title' => '付款方式', 'field' => 'paytype', 'width' => 24))));
}
load() -> func('tpl');
include $this -> template('dealmerch_local_list');
exit;
