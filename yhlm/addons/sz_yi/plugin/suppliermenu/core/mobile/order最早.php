<?php

global $_W, $_GPC;

$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' limit 1 ");

$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('action','deal','display','order','delete','withdraw','withdrawlist') )?'display': $_GPC['op'];
$type = empty($_GPC['type']) || !in_array($_GPC['type'], array(0,1,2,3) )?0:$_GPC['type'];

if ($op == 'order' && $_W['isajax']) {

    $psize = 5 ;
    $page = empty($_GPC['page'])?0:$_GPC['page'];
    $conditions = '';

    switch ($type) {
    	case 1:
    		$conditions = ' and o.status = 0 ';
    		break;
        case 2:
            $conditions = ' and o.status = 1 ';
            break;
        case 3:
            $conditions = ' and o.status = 2 ';
            break;
    }

    $order = pdo_fetchall('select  o.id,o.ordersn,o.paytime,o.status,o.price, o.uniacid,o.paytype,o.goodsprice from '.tablename('sz_yi_order')." as o join ".tablename('sz_yi_order_goods')." as og on o.id = og.orderid and og.supplier_uid = $uid  where o.uniacid = '{$_W['uniacid']}'  {$conditions} group by o.id  order by o.createtime desc limit   ".($page*$psize)." , {$psize}" );

    foreach ($order as $key => &$value) {
        $value['goods'] = pdo_fetchall('select og.orderid , g.uniacid as uniacid , og.price, og.total ,og.realprice ,g.title, CONCAT('."'{$_W['attachurl']}'".',g.thumb) as thumb from '.tablename('sz_yi_order_goods').' as og left join '.tablename('sz_yi_goods')." as g on og.goodsid = g.id  where og.orderid = '{$value['id']}' and g.uniacid = '{$_W['uniacid']}' and og.supplier_uid = $uid  ");
        $value['total'] =  pdo_fetchcolumn('select sum(total)   from '.tablename('sz_yi_order_goods'). " where  orderid = '{$value['id']}' and  uniacid = '{$_W['uniacid']}' and supplier_uid = $uid limit 1");
        $value['url']= $this->createPluginMobileUrl('suppliermenu/order',array('op'=>'action','id'=>$value['id']));
    }

    show_json(1,array('order'=>$order,'status'=>count($order)<$psize?false:true  ));
}


if($op == 'delete' && $_W['isajax']){
    if(empty($_GPC['id'])) show_json(1,array('status'=>false));
    //if(!pdo_fetchcolumn('  select id from '.tablename('sz_yi_order')." where id = '{$_GPC['id']}' and status = 0 and supplier_uid = '$uid' and uniacid = '{$_W['uniacid']}' limit 1  ")) show_json(1,array('status'=>false));
    //pdo_update('sz_yi_order',array('status'=>-1),array('id'=>$_GPC['id']));
    show_json(1,array('status'=>true));

}

if ($op == 'withdraw') {

    // 查询提现权限 1是供应商 2是商家
    $type = pdo_fetchcolumn(' select type from  '.tablename('sz_yi_perm_user')." where openid = :openid limit 1 ", array(':openid' => $openid));
    $set = p('supplier')->getSet();
    if ($type == 1) {
        $authority = $set['power'];
    } elseif ($type == 2) {
        $authority = $set['storepower'];
    }

    // 计算可提现金额
    $costmoney = 0;
    $sp_goods = pdo_fetchall('select og.* from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$uid} and o.status=3 and og.supplier_apply_status=0");
    foreach ($sp_goods as $key => $value) {
        if ($value['goods_op_cost_price'] > 0) {
            $costmoney += $value['goods_op_cost_price'] * $value['total'];
        } elseif ($value['costprice'] > 0) { // 计算成本价
            $costmoney += $value['costprice'] * $value['total'];
        } else {
            $option = pdo_fetch('select * from ' . tablename('sz_yi_goods_option') . " where uniacid={$_W['uniacid']} and goodsid={$value['goodsid']} and id={$value['optionid']}");
            if ($option['costprice'] > 0) {
                $costmoney += $option['costprice'] * $value['total'];
            } else {
                $goods_info = pdo_fetch('select * from' . tablename('sz_yi_goods') . " where uniacid={$_W['uniacid']} and id={$value['goodsid']}");
                $costmoney += $goods_info['costprice'] * $value['total'];
            }
        }
    }


    // 提现申请
    $applytype = intval($_GPC['applytype']);
    if (!empty($applytype)) {

        $aurl = $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdraw'));
        if ($applytype == 2) { // 提现到微信
            if (!in_array('suppliermenu.wechat', $authority)) {
                exit('<script>alert("微信提现未开放！");location.href="'.$aurl.'"</script>');
            }
        } elseif ($applytype == 3) {
            if (!in_array('suppliermenu.balance', $authority)) {
                exit('<script>alert("余额提现未开放！");location.href="'.$aurl.'"</script>');
            }
        }

        $url = $this->createPluginMobileUrl('suppliermenu/index');
        if ($costmoney <= 0) {
            exit('<script>alert("没有可提现的金额!");location.href="'.$url.'"</script>');
        }

        $mygoodsid = pdo_fetchall('select id from ' . tablename('sz_yi_order_goods') . 'where supplier_uid=:supplier_uid and supplier_apply_status = 0', array(':supplier_uid' => $uid));
        if (empty($mygoodsid)) {
            exit('<script>alert("没有可提现的订单!");location.href="'.$url.'"</script>');
        }

        $applysn = m('common')->createNO('commission_apply', 'applyno', 'CA');
        $data = array('uid' => $uid, 'uniacid' => $_W['uniacid'], 'apply_money' => $costmoney, 'apply_time' => time(), 'status' => 0, 'type' => $applytype, 'applysn' => $applysn);
        if ($costmoney > 0) {
            $res = pdo_insert('sz_yi_supplier_apply', $data);
        }
        if (!empty($res)) {
            foreach ($mygoodsid as $ids) {
                $arr = array('supplier_apply_status' => 1);
                pdo_update('sz_yi_order_goods', $arr, array('id' => $ids['id']));
            }
        }
        $url2 = $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdrawlist'));
        exit('<script>alert("提现申请已提交，请耐心等待!");location.href="'.$url2.'"</script>');
    }
    include $this->template('withdraw');
    exit;
}
// 提现记录
if ($op == 'withdrawlist') {

    if ($_W['isajax']) {
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $sql = 'select * from'.tablename('sz_yi_supplier_apply').'where uid = :uid order by id desc';
        $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        $list = pdo_fetchall($sql, array(':uid' => $uid));
        foreach ($list as & $value) {
            $value['apply_time'] = date('Y/m/d H:i');
        }
        show_json(1, array('list' => $list, 'pagesize' => $psize));
    } else {
        include $this->template('withdrawlist');
        exit;
    }
}else if ($op == 'action'){
       
    $id = $orderid;
    $orderid=intval($_GPC['id']);
  

    $param=[
        ':oid'=>$orderid,
        ':openid'=>$openid
    ];

    $orderInfo=pdo_fetch('select o.id,o.ordersn,o.price,ma.realname,ma.mobile from '.tablename('sz_yi_order').' as o left join '.tablename("sz_yi_member_address").' as ma on o.openid=ma.openid where o.id=:oid and o.openid=:openid'
        ,$param);

    include $this->template('orderac');
    exit;
}else if($op=='deal'){
   $id = $_GPC['id'];
  
    $item = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
  
    $shopset = m('common')->getSysset('shop');
    if (empty($item)) {
        show_json(0,'抱歉，订单不存在!');
        //message('抱歉，订单不存在!', referer(), 'error');
       
    }
   

    function changeWechatSend($_var_8, $_var_9, $_var_10 = ''){
        global $_W;
        $_var_11 = pdo_fetch('SELECT plid, openid, tag FROM ' . tablename('core_paylog') . " WHERE tid = '{$_var_8}' AND status = 1 AND type = 'wechat'");
        if (!empty($_var_11['openid'])) {
            $_var_11['tag'] = iunserializer($_var_11['tag']);
            $_var_12 = $_var_11['tag']['acid'];
            load()->model('account');
            $_var_13 = account_fetch($_var_12);
            $_var_14 = uni_setting($_var_13['uniacid'], 'payment');
            if ($_var_14['payment']['wechat']['version'] == '2') {
                return true;
            }
            $_var_15 = array('appid' => $_var_13['key'], 'openid' => $_var_11['openid'], 'transid' => $_var_11['tag']['transaction_id'], 'out_trade_no' => $_var_11['plid'], 'deliver_timestamp' => TIMESTAMP, 'deliver_status' => $_var_9, 'deliver_msg' => $_var_10,);
            $_var_16 = $_var_15;
            $_var_16['appkey'] = $_var_14['payment']['wechat']['signkey'];
            ksort($_var_16);
            $_var_17 = '';
            foreach ($_var_16 as $_var_18 => $_var_19) {
                $_var_18 = strtolower($_var_18);
                $_var_17 .= "{$_var_18}={$_var_19}&";
            }
            $_var_15['app_signature'] = sha1(rtrim($_var_17, '&'));
            $_var_15['sign_method'] = 'sha1';
            $_var_13 = WeAccount::create($_var_12);
            $_var_20 = $_var_13->changeOrderStatus($_var_15);
            if (is_error($_var_20)) {
                message($_var_20['message']);
            }
        }
    }

    if (empty($item['addressid'])) {
       // message('无收货地址，无法发货！');
        show_json(0,'无收货地址，无法发货！');
    }

    if ($item['paytype'] != 3) {
        if ($item['status'] != 1) {
            //message('订单未付款，无法发货！');
            show_json(0,'订单未付款，无法发货！');
        }
    }
    if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
       // message();
        show_json(0,'请输入快递单号！');
    }
    if (!empty($item['transid'])) {
        changeWechatSend($item['ordersn'], 1);
    }
    pdo_update('sz_yi_order', array('status' => 2, 'express' => trim($_GPC['express']), 'expresscom' => trim($_GPC['expresscom']), 'expresssn' => trim($_GPC['expresssn']), 'sendtime' => time()), array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
    if (!empty($item['refundid'])) {
        $_var_22 = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id limit 1', array(':id' => $item['refundid']));
        if (!empty($_var_22)) {
            pdo_update('sz_yi_order_refund', array('status' => -1), array('id' => $item['refundid']));
            pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $item['id']));
        }
    }
    m('notice')->sendOrderMessage($item['id']);
  show_json(1);
}
include $this->template('order');
