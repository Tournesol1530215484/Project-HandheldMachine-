<?php

//decode by QQ:270656184 http://www.yunlu99.com/

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$plugin_diyform = p('diyform');

$totals = array(); 



	// 修改开始
	$sp_goods = pdo_fetchall('select o.dispatchprice,og.orderid,g.title,o.goodsprice,o.createtime from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) left join " . tablename('sz_yi_goods') . " g on (og.goodsid=g.id) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$_W['uid']} and o.status=3 and og.supplier_apply_status=0");

		include $this->template('suppliermenu/canti');

		exit;



