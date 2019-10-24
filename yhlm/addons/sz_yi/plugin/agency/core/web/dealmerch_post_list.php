<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W, $_GPC; 


$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
  
if ($op == 'display') {
    $condition = " and o.uniacid = :uniacid and o.isexchange = 1 ";
    $params = array(':uniacid' => $_W['uniacid'] );
    //状态
    if (p('supplier')){         //如果是供应商
        $condition.=' and o.supplier_uid = :uid ';
        $params[':uid'] = $_W['uid'];
    }
    $statuscondition='';
    if (!empty($_GPC['status'] || $_GPC['status'] == '0' )) {
        $statuscondition .= ' and o.status = '.$_GPC["status"];
    }

    if (!empty($_GPC['refund'])){
        $statuscondition.= ' and o.refundid <> 0 ';
    }
    if (!empty($_GPC['datetime'])){
       $starttime = strtotime($_GPC['datetime']['start']);
       $endtime = strtotime($_GPC['datetime']['end']);
       if (!empty($_GPC['searchtime'])){
           $condition .= ' AND o.createtime >= :starttime AND o.createtime <= :endtime ';
           $params[':starttime'] = $starttime; 
           $params[':endtime'] = $endtime;
       }
    }
    //订单号
    if (!empty($_GPC['ordersn'])){
       $condition .= ' and o.ordersn like :ordersn';
       $params[':ordersn'] = "%{$_GPC['ordersn']}%";
    }
    //商品名称
    if (!empty($_GPC['goodsname'])){
       $condition .= ' and g.title like :goodsname';
       $params[':goodsname'] = "%{$_GPC['goodsname']}%";
    }
    //商品编号
    if (!empty($_GPC['goodssn'])){
       $condition .= ' and g.goodssn like :goodssn';
       $params[':goodssn'] = "%{$_GPC['goodssn']}%";
    }
    //买家信息 ......
    if (!empty($_GPC['buyerinfo'])) {
        if (preg_match("/^[1-9]\d*$/", $_GPC['buyerinfo'] ) ) {
            $condition .= ' and ma.mobile like :buyerinfo ';
            $params[':buyerinfo'] = "%{$_GPC['buyerinfo']}%";
        } else {
            $condition .= ' and ma.realname like :buyerinfo ';
            $params[':buyerinfo'] = "%{$_GPC['buyerinfo']}%";
        }
    }

    $pindex = max(1, intval($_GPC['page']));

    $psize = 20;

    $sql = "select o.id ,o.isverify, o.ordersn, o.virtual, o.isvirtual, o.carrier, o.uniacid, o.goodsprice, o.isexchange, o.uniacid, o.status, o.openid, o.price, o.addressid, o.expresscom, o.expresssn, o.dispatchtype, o.paytype, o.paytime, o.createtime, o.sendtime, ma.id as maid, ma.openid, ma.province, ma.city, ma.area, ma.address, ma.isdefault, ma.realname, ma.mobile, g.goodssn, g.title as goodsname , g.thumb, og.price, og.total, og.realprice, g.title,og.optionname from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid where 1 {$condition} ";
    $sql .= $statuscondition;
    $sql .= " order by o.id desc  limit ".($pindex - 1) * $psize .','.$psize;
    $list = pdo_fetchall($sql, $params);
    // echo '<pre>'; var_dump($list);die; 

    foreach ($list as & $row) {
        $row['ordersn'] = $row['ordersn'] . ' ';
        // $row['goods'] = pdo_fetchall('SELECT g.goodssn,g.title as goodsname ,g.thumb,og.price,og.total,og.realprice,g.title,og.optionname from ' . tablename('sz_yi_order_goods') . ' og' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id = og.goodsid  ' . ' where og.uniacid = :uniacid and og.orderid=:orderid order by og.createtime  desc ', array(':uniacid' => $_W['uniacid'], ':orderid' => $row['id']));
        $totalmoney += $row['price'];
        if ($row['dispatchtype'] == 1 || !empty($row['isverify']) || !empty($row['virtual']) || !empty($row['isvirtual'])) {
            // $carrier = iunserializer($row['carrier']);
                $row['addressdata']['realname'] = $row['realname'];
                $row['addressdata']['mobile'] = $row['mobile'];
                $row['addressdata']['address'] = $row['province'].$row['city'].$row['area'].$row['address'];
        } else {
            $address = iunserializer($row['address']);
            $isarray = is_array($address);
            $row['addressdata']['address'] = $row['province'].$row['city'].$row['area'].$row['address'];
            $row['addressdata'] = array('realname' => $row['realname'], 'mobile' => $row['mobile'], 'address' => $row['address']);
        }
    }

    if (empty($totalmoney)) {
        $totalmoney = 0;
    }
    unset($row);

    $totals = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition}", $params);
    // var_dump($condition, $params, $total);die;
    //状态查询
    $thistotals = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} {$statuscondition} ", $params);

    $sql0 = "select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.status = 0";
    // var_dump($sql0);die; 
    // var_dump($total['status0']);die; 
    $total['status0'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.status = 0", $params);
  
    $total['status1'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.status = 1", $params);
   
    $total['status_1'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.deleted = 1", $params);

    $total['status2'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.status = 2", $params);

    $total['status3'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.refundid <> 0 ", $params);

    $total['status4'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid  where 1 {$condition} and o.status = -1 ", $params);

    $pager = pagination($totals, $pindex, $psize);

    //导出
    if ($_GPC['export'] == 1) {
        $a = plog('statistics.export.order', '导出订单统计');
        $list[] = array('data' => '订单总计', 'count' => $totalcount);
        $list[] = array('data' => '金额总计', 'count' => $totalmoney);
        foreach ($list as & $row){
            if ($row['paytype'] == 1) {
                $row['paytype'] = '余额支付';
            }else if ($row['paytype'] == 11) {
                $row['paytype'] = '后台付款';
            }else if ($row['paytype'] == 21) {
                $row['paytype'] = '微信支付';
            }else if ($row['paytype'] == 22) {
                $row['paytype'] = '支付宝支付';
            }else if ($row['paytype'] == 23) {
                $row['paytype'] = '银联支付';
            }else if ($row['paytype'] == 3) {
                $row['paytype'] = '货到付款';
            }
            switch ($row['status']) {
                case 1:   $row['status']='待付款'; break;
                case 2:   $row['status']='待发货'; break;
                case 3:   $row['status']='待收货'; break;
                case 4:   $row['status']='待退货退款'; break;
                case 5:   $row['status']='已退款'; break;
                case 6:   $row['status']='已退货'; break;
                case 7:   $row['status']='完成'; break;
                case 8:   $row['status']='关闭'; break;
            }

            $row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
        }
        unset($row);
        foreach ($list as $k => &$v){
            $v['cretetime']=date('Y-m-d H:i:s',$v['cretetime']);
            $v['paytime']=date('Y-m-d H:i:s',$v['paytime']);
            $v['price']=number_format($v['price'] , 2);
            $v['address'] = $v['province'].$v['city'].$v['area'].$v['address'];
        }
        $arr =  array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' =>  
                    array(
                        array('title' => '订货日期', 'field' => 'cretetime', 'width' => 12), 
                        array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), 
                        array('title' => '收货人', 'field' => 'realname', 'width' => 12), 
                        array('title' => '联系电话', 'field' => 'mobile', 'width' => 12), 
                        array('title' => '送货地址', 'field' => 'address', 'width' => 12), 
                        array('title' => '订单金额', 'field' => 'price', 'width' => 12), 
                        array('title' => '已付款金额', 'field' => 'price', 'width' => 12), 
                        array('title' => '付款时间', 'field' => 'paytime', 'width' => 12), 
                        array('title' => '快递公司', 'field' => 'expresscom', 'width' => 12), 
                        array('title' => '快递单号', 'field' => 'expresssn', 'width' => 12), 
                        array('title' => '订单状态', 'field' => 'status', 'width' => 12)
                    )
                );
        m('excel') -> export($list, $arr);
    } 

} else if ($op == 'delete') { 
    $id = intval($_GPC['id']);
    $orders = pdo_fetch("select id,status from ".tablename('sz_yi_order')." where id = {$id}");
    //订单状态为已发货至退货退款未成功之间不可删除
    if ($orders['status'] >= 2 && $orders['status'] < 7) {
        message('订单未完成，不能关闭！');
    } 
    pdo_update('sz_yi_order', array('deleted' => 1), array('id' => $id, 'uniacid' => $_W['uniacid']));
    plog('order.op.delete', "订单删除 ID: {$id}");
    show_json( 1, '已删除订单'.$id);

} else if ($op == 'detail') {
    $id = intval($_GPC['id']);
    $sql = "select o.id as oid , o.ordersn, o.uniacid, o.goodsprice, o.isexchange, o.uniacid, o.status, o.openid, o.price, o.addressid, o.expresscom, o.expresssn, o.dispatchtype, o.paytype, o.paytime, o.createtime, o.sendtime, ma.id , ma.openid, ma.province, ma.city, ma.area, ma.address, ma.isdefault, ma.realname, ma.mobile, g.goodssn, g.title as goodsname , g.thumb, og.price, og.total, og.realprice, g.title,og.optionname from " . tablename('sz_yi_member_address'). " ma left join " . tablename('sz_yi_order') . " o on ma.id = o.addressid left join ". tablename('sz_yi_order_goods') ." og on o.id = og.orderid left join " . tablename('sz_yi_goods') . " g on g.id = og.goodsid where o.id = :id and o.uniacid = :uniacid";
    $info = pdo_fetch($sql, array(':id'=>$id, ':uniacid'=>$_W['uniacid']));
    show_json(1, $info);

} else if ($op == 'deal') {

    $id = intval($_GPC['id']);

    $item = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
    if (empty($item)) {
        message('抱歉，订单不存在!', referer(), 'error');
    }
    //退款
    if (!empty($item['refundid'])) {
        ca('order.view.status4');
    } else {
        if ($item['status'] == -1) {
            ca('order.view.status_1');
        } else {
            ca('order.view.status' . $item['status']);
        }
    }

    if ($_GPC['to'] == 'confirmpay') {
        //确认支付    
    } else if ($_GPC['to'] == 'confirmsend1') {
        //
        if ($item['status'] == 0) {
            message('订单未付款, 无法确认收货');
        }

    } else if ($_GPC['to'] == 'finish') {
        //结束订单
        // ca('order.op.finish');
        pdo_update('sz_yi_order', array('status' => 3, 'finishtime' => time()), array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
    }
}

load() -> func('tpl');
include $this -> template('dealmerch_post_list');
