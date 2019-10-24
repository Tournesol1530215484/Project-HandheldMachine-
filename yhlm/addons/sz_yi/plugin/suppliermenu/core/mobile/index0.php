<?php
//多级分销商城 QQ:1084070868
global $_W, $_GPC;

$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('display','getinfo') )?'display': $_GPC['op'];

$popenid        = m('user')->islogin();
$openid = m('user')->getOpenid();
$openid = $openid?$openid:$popenid;

$role=intval($_GPC['merch']);
$condition='';
$type = 0;
if ($role == 2){    //普通商家 全国

    $condition.=' and merchid = 0 and dealmerchid = 0 ';
    $type = 2;
}else if($role == 3){     // 本地 or 本地+全国
    $condition.=' and merchid > 0  ';
    $type = 2;
}else if ($role == 5){      //易货

    $condition.=' and dealmerchid > 0 ';
    $type = 3;

}

$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and  openid = '{$openid}' {$condition} limit 1 ");
$username = pdo_fetchcolumn(' select username from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and  openid = '{$openid}' {$condition} limit 1 ");


//$merchInfo = pdo_fetch(' select * from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' '{$condition}' limit 1 ");
// type 1是供应商 2是商家
//if ($merchInfo[''])
$set = p('suppliermenu')->getSet();

// 判断是否有访问权限
//if ($type == 1) {
//	if (!in_array('suppliermenu.visit', $set['power'])) {
//		// echo "您无权限访问！";
//		include $this->template('alert');
//		exit;
//	}
//} elseif ($type == 2) {
//	if (!in_array('suppliermenu.visit', $set['storepower'])) {
//		// echo "您无权限访问！";
//		include $this->template('alert');
//		exit;
//	}
//}

if($op == 'getinfo' && $_W['isajax']){

	$allprice = pdo_fetchcolumn('select  sum(og.price*og.total) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status>1  limit 1 " ); //price
	$todayprice = pdo_fetchcolumn('select  sum(og.price*og.total) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status>1 and  o.paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );
	$todaycount =   pdo_fetchcolumn('select  sum(og.total) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status>1 and  o.paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );
	$member = m('member')->getInfo($openid);
	$pageInfo = array(
	    array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/info',array('op'=>'editpwd')),
			'color' => '#10BDFF',
			'name'  => '修改密码',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/7.png',
	    ) ,
	    array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/goods',array('op'=>'post','merch'=>$role)),
			'color' => '#18C684',
			'name'  => '发布宝贝',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/10.png',
	    ) ,
	    array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/goods',array('merch' =>$role)),
			'color' => '#DE39EF',
			'name'  => '宝贝管理',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/8.png',
	    ) ,
	    array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/order',array('merch' =>$role)),
			'color' => '#F78C39',
			'name'  => '订单管理',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/12.png',
	    ) ,
	    array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdraw')),
			'color' => '#08E7DE',
			'name'  => '供应商提现',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/13.png',
	    ) ,
	    array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdrawlist')),
			'color' => '#F93',
			'name'  => '提现记录',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/4.0.png',
	    ) ,
	);
	// 2为商家 多有以下权限
	if ($type == 2) {
		$pageInfo[] = array(
			'url'   => $this->createPluginMobileUrl('suppliermenu/info', array('op' => 'editstore')),
			'color' => '#FF6363',
			'name'  => '店铺装修',
			'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',
		);
		if ($role == 3){
		    $merchid=pdo_fetchcolumn(' select merchid from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' {$condition} limit 1 ");
            //拿到本地商家id
		    $pageInfo[] = array(
                'url'   => $this->createMobileUrl('member/merch', array('op' => 'detail','id' => $merchid)),
                'color' => '#EF6BA5',
                'name'  => '我的店铺',
                'img'   => MODULE_URL.'plugin/suppliermenu/res/11.png',
            );
        }else{
            $pageInfo[] = array(
                'url'   => $this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>$role ,'storeid' => $uid)),
                'color' => '#EF6BA5',
                'name'  => '我的店铺',
                'img'   => MODULE_URL.'plugin/suppliermenu/res/11.png',
            );
        }

	}

    if ($type == 3){
//        unset($pageInfo[1]);
        $pageInfo[1]=array(
            'url'   => $this->createPluginMobileUrl('suppliermenu/dealgoods',array('op'=>'post','merch'=>$role)),
            'color' => '#18C684',
            'name'  => '发布宝贝',
            'img'   => MODULE_URL.'plugin/suppliermenu/res/10.png',
        );
        unset($pageInfo[4]);
        unset($pageInfo[5]);
        $pageInfo[] = array(
            'url'   => $this->createPluginMobileUrl('suppliermenu/info', array('op' => 'editstore')),
            'color' => '#FF6363',
            'name'  => '店铺装修',
            'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',
        );
	}


	show_json(1, array(
		'allprice'   => $allprice   ? $allprice   : 0,
		'todayprice' => $todayprice ? $todayprice : 0,
		'todaycount' => $todaycount ? $todaycount : 0,
		'member'     => $member,
		'type'       => $type,
		'pageInfo'   => $pageInfo,
	));
}

include $this->template('index');
