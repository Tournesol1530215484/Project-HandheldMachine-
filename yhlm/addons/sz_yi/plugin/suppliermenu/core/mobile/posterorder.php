<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$popenid  = m('user')->islogin();

$openid = m('user')->getOpenid();

//$openid="oSI4Lj7B07Xf6MVG9o_zgDFIjnF4";

$openid = $openid?$openid:$popenid;

// if($op == 'display'){

// 	// header('Location:'.$this->createPluginMobileUrl('suppliermenu/poster'));
// 	$this->createPluginMobileUrl('suppliermenu/posterorder')
// }

//获取所有的邮费明细

    if ($_W['isajax']) {
    	$page = empty($_GPC['page'])?0:$_GPC['page'];

        $pindex = max(1, intval($_GPC['page']));

        $psize = 10;

        $uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' and type=3 limit 1 ");	//供应商id

        $sql = 'select og.*,o.* from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$uid} and o.isexchange =1  group by o.id  order by o.createtime desc";
       // $sql='select og.*,o.dispatchprice,o.createtime,o.order from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and  ";
        //$sql='select o.* from '.tablename('sz_yi_order')." as o join ".tablename('sz_yi_order_goods')." as og on o.id = og.orderid  where  o.uniacid = '{$_W['uniacid']}'   and o.supplier_uid = {$uid} and o.isexchange = 1 group by o.id  order by o.createtime desc";

        //$sql='select o.* from '.tablename('sz_yi_order')." as o join ".tablename('sz_yi_order_goods')." as og on o.id = og.orderid and og.supplier_uid = $uid  where o.uniacid = '{$_W['uniacid']}' and o.isexchange = 1 group by o.id  order by o.createtime desc" ;
        //$sql='select * from '.tablename('sz_yi_order')."as o where o.supplier_uid=$uid";

        $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

        $list = pdo_fetchall($sql);


        foreach ($list as & $value) {

            $value['createtime'] = date('Y/m/d H:i',$value['createtime']);

        }

        show_json(1, array('list' => $list, 'pagesize' => $psize,'uid'=>$uid,'openid'=>$openid));

    } else {

        include $this->template('posterorder');

        exit;

    }

