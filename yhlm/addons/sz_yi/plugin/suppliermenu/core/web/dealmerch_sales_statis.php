<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and uniacid=:uniacid and shelves > 0 and supplier_uid = :uid ';
$params = array(':uniacid' => $_W['uniacid'],':uid' => $_W['uid']);
$salescondition=' o.refundid = 0 and status <> -1 and status <> 0  and o.uniacid = :uniacid ';
$salesparams=array(':uniacid'=>$_W['uniacid']);
if (!empty($_GPC['goodssn'])){

    $condition .= ' and goodssn like :goodssn ';
    $params[':goodssn'] = "%{$_GPC['goodssn']}%";
}
if (!empty($_GPC['goodstitle'])){
    $condition .=' and title like :goodstitle ';
    $params[':goodstitle']= "%{$_GPC['goodstitle']}%";
}

if (!empty($_GPC['datetime'])){
    $starttime = strtotime($_GPC['datetime']['start']);
    $endtime = strtotime($_GPC['datetime']['end']);
    if (!empty($_GPC['searchtime'])){
        $salescondition .= ' AND o.paytime >= :starttime AND o.paytime <= :endtime ';
        $salesparams[':starttime'] = $starttime;
        $salesparams[':endtime'] = $endtime;
    }
}

$sql = 'select id,goodssn,title from '.tablename('sz_yi_goods').' where 1 '.$condition;
$sql .= ' ORDER BY id ASC ';
if (empty($_GPC['export'])){
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
}
$list = pdo_fetchall($sql, $params);
foreach ($list as & $row){      //查询规格
    $opcondition=' go.uniacid = :uniacid and go.goodsid=:goodsid ';
    $opparams=array(':uniacid' => $_W['uniacid'],':goodsid'=>$row['id']);
    if (!empty($_GPC['optitle'])){
        $opcondition.=' and go.title like :optitle';
        $opparams[':optitle']="%{$_GPC['optitle']}%";
    }

    $row['options'] = pdo_fetchall('SELECT go.id,go.title as title,go.marketprice,gsi.thumb as thumb from '.tablename('sz_yi_goods_option').' go left join '.tablename('sz_yi_goods_spec_item').' gsi on gsi.id=go.specs where '.$opcondition.' order by go.goodsid asc ', $opparams);
    $row['sales']=0;
    $row['salesincome']=0;
    foreach($row['options'] as $k => &$v){
        $optionSales=pdo_fetch('select count(og.total) as total from '.tablename('sz_yi_order_goods').' og left join '.tablename('sz_yi_order')." o on o.id = og.orderid where {$salescondition} and og.optionid={$v['id']} ",$salesparams);
        //查询销售总数 总销售价格 = 数量*单价
        $v['sales']=$optionSales['total'];
        $v['salesincome']=$optionSales['total'] * $v['marketprice'];
        $row['sales']+=$optionSales['total'];
        $row['salesincome']+=floatval($v['salesincome']);
    }
}
if (empty($totalmoney)){
    $totalmoney = 0;
}
unset($row);

//$totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_goods') . ' o ' . ' left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id left join ' . tablename('sz_yi_member') . ' m on o.openid = m.openid ' . ' left join ' . tablename('sz_yi_exchange_address') . ' a on a.id = o.storeid ' . " where 1 {$condition}", $params);
$totalcount = $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_goods').' where 1 '.$condition,$params);
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
    m('excel') -> export($list, array('title' => '销售统计-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '商品编号', 'field' => 'goodssn', 'width' => 24), array('title' => '商品名称', 'field' => 'title', 'width' => 12), array('title' => '销售总数量', 'field' => 'sales', 'width' => 12), array('title' => '销售收入(易货码)', 'field' => 'salesincome', 'width' => 12))));
}

load() -> func('tpl');
include $this -> template('dealmerch_sales_statis');
exit;
