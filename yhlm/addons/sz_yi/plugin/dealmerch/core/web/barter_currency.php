<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

//af_supplier images
global $_W, $_GPC;
ca('dealmerch.barter_currency');
$op=!empty($_GPC['op'])?$_GPC['op']:'display';
if ($op == 'display') {

    $dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $condition = ' and uniacid=:uniacid ';
    $params = array(':uniacid' => $_W['uniacid']);

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
}else if($op == 'query'){

    if ($_W['isajax']) {
        $uid=intval($_GPC['uid']);
        !$uid && show_json(0,'非法参数!');
        $tmerch=p('bonus')->getMerch($uid);
        $re['cash']=m('member')->getCredit($tmerch['openid'],'credit2');
        $re['code']=m('member')->getCredit($tmerch['openid'],'credit3');
        $re['currency']=m('member')->getCredit($tmerch['openid'],'currency_credit3');
        show_json(1,$re);        
    }               
}  else if($op == 'detailnum'){

    if ($_W['isajax']) {
       
          $data=$_POST;
            $logid=$data['logid'];  //获取订单编号ac2019061810335d084d5eaabfa
           // $sql='select * from'.tablename("sz_yi_barter_log")."where logno=".$logid;
           // $ordersn=pdo_fetch($sql,$params); 
            //$ordersn=pdo_fetch("select * from hs_sz_yi_barter_log where logno = '$logid'");
            $ordersn=pdo_fetch('select * from '.tablename('sz_yi_currency_barter_log').' where logno = :logno ',array(':logno'=>$logid));

            if ($ordersn) {
                //返回订单信息 换货码激活详情
                 $ordersn['number'] = ($ordersn['number']==null)?'无':$ordersn['number'];
                 $ordersn['why'] = ($ordersn['why']==null)?'无':$ordersn['why'];
                 $ordersn['createtime']=date('Y-m-d H:i:s',$ordersn['createtime']);
                show_json(1,$ordersn);
             }
             show_json(0);    
    }               
}       

load() -> func('tpl');
include $this -> template('barter_currency');
exit;
