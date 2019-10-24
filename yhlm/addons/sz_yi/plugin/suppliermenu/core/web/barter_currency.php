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
$condition = ' and uniacid=:uniacid and openid = :openid ';
$params = array(':uniacid' => $_W['uniacid'],':openid' => $openid);
$member['currency_credit3']=m('member')->getCredit($openid,'currency_credit3');
$use=pdo_fetchcolumn('select sum(currency) from '.tablename('sz_yi_barter_currency_log').' where type=11 and uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
$member['use']=abs($use);

if (!empty($_GPC['datetime'])){
    $starttime = strtotime($_GPC['datetime']['start']);
    $endtime = strtotime($_GPC['datetime']['end']);
    if (!empty($_GPC['searchtime'])){
        $condition .= ' AND dealtime >= :starttime AND dealtime <= :endtime ';
        $params[':starttime'] = $starttime;
        $params[':endtime'] = $endtime;
    }
}

if (!empty($_GPC['type'])){
    $condition .= ' and type = :type';
    $params[':type'] = $_GPC['type'];
}

$sql = 'select * from '.tablename('sz_yi_barter_currency_log').'  where 1 '.$condition;
$sql .= ' ORDER BY dealtime DESC ';
//if (empty($_GPC['export'])){
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
//}

$list = pdo_fetchall($sql, $params);

foreach ($list as $k => &$v){
    $userinfo=pdo_fetch('select realname,username from '.tablename('sz_yi_perm_user').' where dealmerchid > 0 and uniacid = :uniacid and openid=:openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$v['openid']));
    $v['realname']=$userinfo['realname'];
    $v['username']=$userinfo['username'];
}
$totalcount = $total = pdo_fetchcolumn('select count(*) from '. tablename('sz_yi_barter_currency_log').'  where 1 '.$condition,$params);
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
    m('excel') -> export($list, array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '总金额', 'field' => 'price', 'width' => 12), array('title' => '商品金额', 'field' => 'goodsprice', 'width' => 12), array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12), array('title' => '付款方式', 'field' => 'paytype', 'width' => 12), array('title' => '会员名', 'field' => 'realname', 'width' => 12), array('title' => '收货人', 'field' => 'addressname', 'width' => 12), array('title' => '下单时间', 'field' => 'createtime', 'width' => 24))));
}

load() -> func('tpl');
include $this -> template('barter_currency');
exit;
