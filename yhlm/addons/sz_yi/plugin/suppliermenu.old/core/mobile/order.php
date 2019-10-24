<?php
 
global $_W, $_GPC;

$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' limit 1 ");
 
$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('display','order','delete') )?'display': $_GPC['op'];
$type = empty($_GPC['type']) || !in_array($_GPC['type'], array(0,1,2,3) )?0:$_GPC['type'];

if ($op == 'order' && $_W['isajax']) {

    $psize = 5 ;
    $page = empty($_GPC['page'])?0:$_GPC['page']; 

    $conditions = '';

    switch ($type) {
    	case 1:
    		$conditions = ' and status = 0 ';
    		break;
        case 2:
            $conditions = ' and status = 1 ';
            break;	
        case 3:
            $conditions = ' and status = 2 ';
            break;    	
    	default:
    		# code...
    		break;
    }

     
     
    $order = pdo_fetchall('select  id,supplier_uid,ordersn,paytime,status,price, uniacid,paytype,goodsprice from '.tablename('sz_yi_order')." where uniacid = '{$_W['uniacid']}' and supplier_uid = '{$uid}' {$conditions} order by createtime desc limit   ".($page*$psize)." , {$psize}" );  
    foreach ($order as $key => &$value) {
        $value['goods'] = pdo_fetchall('select og.orderid , g.uniacid as uniacid , og.price, og.total ,og.realprice ,g.title, CONCAT('."'{$_W['attachurl']}'".',g.thumb) as thumb from '.tablename('sz_yi_order_goods').' as og left join '.tablename('sz_yi_goods')." as g on og.goodsid = g.id  where og.orderid = '{$value['id']}' and g.uniacid = '{$_W['uniacid']}'  ");
        $value['total'] =  pdo_fetchcolumn('select sum(total)   from '.tablename('sz_yi_order_goods'). " where  orderid = '{$value['id']}' and  uniacid = '{$_W['uniacid']}'  ");

    }

    show_json(1,array('order'=>$order,'status'=>count($order)<$psize?false:true  ));
}


if($op == 'delete' && $_W['isajax']){
    if(empty($_GPC['id'])) show_json(1,array('status'=>false));
    

    if(!pdo_fetchcolumn('  select id from '.tablename('sz_yi_order')." where id = '{$_GPC['id']}' and status = 0 and supplier_uid = '$uid' and uniacid = '{$_W['uniacid']}' limit 1  ")) show_json(1,array('status'=>false));


    pdo_update('sz_yi_order',array('status'=>-1),array('id'=>$_GPC['id']));
    show_json(1,array('status'=>true));

}

 include $this->template('order');
 