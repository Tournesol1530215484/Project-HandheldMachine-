<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

//af_supplier images
global $_W, $_GPC;
$openid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid= :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
$op=!empty($_GPC['op'])?$_GPC['op']:'';
if ($op == 'debug') {
    $params=array(
        ':uniacid'=>$_W['uniacid'],
        ':uid'=>658
    ); 
    $order=pdo_fetchall('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid and status = 3',$params);
    $codelog=pdo_fetchall('select * from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
    foreach ($order as $key => $value) {
        foreach ($codelog as $k => $v) {
            if ($value['ordersn'] == $v['dealsn']) {
                unset($order[$key]);            
            }
        }
    }
    print_r($order);
    exit;
}
$dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and uniacid=:uniacid and openid = :openid';
$params = array(':uniacid' => $_W['uniacid'],':openid' => $openid);
if (!empty($_GPC['datetime'])){
    $starttime = strtotime($_GPC['datetime']['start']);
    $endtime = strtotime($_GPC['datetime']['end']);
    if (!empty($_GPC['searchtime'])){
        $condition .= ' AND dealtime >= :starttime AND dealtime <= :endtime ';
        $params[':starttime'] = $starttime;
        $params[':endtime'] = $endtime;
    }
}

if (!empty($_GPC['dealsn'])){
        $condition .= ' AND dealsn like :dealsn ';
        $params[':dealsn'] = "%{$_GPC['dealsn']}%";
}


$str='';
//上排
if ($_GPC['r1'] == 1){
    $str.=',1 ';
}
if ($_GPC['r2'] == 1){
    $str.=', 3 ';
}
if ($_GPC['r3'] == 1){
    $str.=', 5 ';
}
if ($_GPC['r4'] == 1){
    $str.=', 7 ';
}
if ($_GPC['r5'] == 1){
    $str.=', 9 ';
}
if ($_GPC['r6'] == 1){
    $str.=', 11 ';
}
if ($_GPC['r7'] == 1){
    $str.=', 13 ';
}
if ($_GPC['r8'] == 1){
    $str.=', 15 ';
}
if ($_GPC['r9'] == 1){
    $str.=', 17 ';
}
if ($_GPC['r10'] == 1){
    $str.=', 19 ';
}
if ($_GPC['r11'] == 1){
    $str.=', 21 ';
}

//下排
if ($_GPC['r12'] == 1){
    $str.=', 2 ';
}
if ($_GPC['r13'] == 1){
    $str.=', 4 ';
}
if ($_GPC['r14'] == 1){
    $str.=', 6 ';
}
if ($_GPC['r15'] == 1){
    $str.=', 8 ';
}
if ($_GPC['r16'] == 1){
    $str.=', 10 ';
}
if ($_GPC['r17'] == 1){
    $str.=', 12 ';
}
if ($_GPC['r18'] == 1){
    $str.=', 14 ';
}
if ($_GPC['r19'] == 1){
    $str.=', 16 ';
}
if ($_GPC['r20'] == 1){
    $str.=', 18 ';
}
if ($_GPC['r21'] == 1){
    $str.=', 20 ';
}
if ($str){
    $condition.=' and type in ( '.trim($str,',').' ) ';
}

$sql = 'select * from '.tablename('sz_yi_barter_code_log').'  where 1 '.$condition;
$sql .= ' ORDER BY dealtime DESC ';
//if (empty($_GPC['export'])){
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
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

$totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_barter_code_log') .' where 1 '.$condition,$params);

$pager = pagination($total, $pindex, $psize);

if ($_GPC['export'] == 1){
    $sqls = 'select * from '.tablename('sz_yi_barter_code_log').'  where 1 '.$condition;
    $sqls .= ' ORDER BY dealtime DESC ';
    $lists = pdo_fetchall($sqls, $params);

    plog('statistics.export.order', '导出订单统计');
    foreach ($lists as & $row){


        if ($row['status'] == 0){
            $row['status'] = '失效';
        }else if ($row['status'] == 1){
            $row['status'] = '正常';
        }

        if ($row['assoctype'] == 1){
            $row['assoctype'] = '易货订单';
        }else if ($row['assoctype'] == 2){
            $row['assoctype'] = '激活易货码';
        }else if ($row['assoctype'] == 3){
            $row['assoctype'] = '易货码收支';
        }else if ($row['assoctype'] == 4){
            $row['assoctype'] = '充值';
        }

        $row['dealtime']=date('Y-m-d H:i:s',$row['dealtime']);

        if ($row['type'] == 1){
            $row['type'] = '购物支出';
        }else if ($row['type'] == 2){
            $row['type'] = '购物收入';
        }else if ($row['type'] == 3){
            $row['type'] = '金币兑换(支出)';
        }else if ($row['type'] == 4){
            $row['type'] = '金币兑换(收入)';
        }else if ($row['type'] == 5){
            $row['type'] = '销售收入返还';
        }else if ($row['type'] == 6){
            $row['type'] = '购物消费返还';
        }else if ($row['type'] == 7){
            $row['type'] = '佣金支出';
        }else if ($row['type'] == 8){
            $row['type'] = '佣金收入';
        }else if ($row['type'] == 9){
            $row['type'] = '易货码支出';
        }else if ($row['type'] == 10){
            $row['type'] = '易货码收入';
        }else if ($row['type'] == 11){
            $row['type'] = '激活易货码(支出)';
        }else if ($row['type'] == 12){
            $row['type'] = '激活易货码(收入)';
        }else if ($row['type'] == 13){
            $row['type'] = '赠出';
        }else if ($row['type'] == 14){
            $row['type'] = '赠入';
        }else if ($row['type'] == 15){
            $row['type'] = '人工充值(支出)';
        }else if ($row['type'] == 16){
            $row['type'] = '人工充值(收入)';
        }else if ($row['type'] == 17){
            $row['type'] = '人工扣除(支出)';
        }else if ($row['type'] == 18){
            $row['type'] = '人工扣除(收入)';
        }else if ($row['type'] == 19){
            $row['type'] = '发布现金商品(支出)';
        }else if ($row['type'] == 20){
            $row['type'] = '发布现金商品(收入)';
        }else if ($row['type'] == 21){
            $row['type'] = '人工冻结';
        }

    }
    unset($row);
    m('excel') -> export($lists, array('title' => '换货码收支查询-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '交易编号', 'field' => 'dealsn', 'width' => 24), array('title' => '交易类型', 'field' => 'type', 'width' => 12), array('title' => '换货码交易', 'field' => 'currency', 'width' => 12), array('title' => '交易状态', 'field' => 'status', 'width' => 12), array('title' => '关联类型', 'field' => 'assoctype', 'width' => 12), array('title' => '交易时间', 'field' => 'dealtime', 'width' => 24), array('title' => '交易说明', 'field' => 'note', 'width' => 24))));
}
load() -> func('tpl');
include $this -> template('barter_code_log');
exit;
