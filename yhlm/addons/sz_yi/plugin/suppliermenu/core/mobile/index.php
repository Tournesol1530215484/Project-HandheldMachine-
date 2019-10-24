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



}else if($role == 3){     // 本地 or 本地+全国 type 0 or 2

    $condition.=' and merchid > 0  ';

    $type = 2;



}else if ($role == 5){      //换货

    $condition.=' and dealmerchid > 0 ';

    $type = 3;



}





if ($_GPC['merch'] == 5) {

    $info=p('bonus')->getMerch($openid,'deal');

}else if ($_GPC['merch'] == 3) {

    $info=p('bonus')->getMerch($openid,'merch');

}else if ($_GPC['merch'] == 2) {

    $info=p('bonus')->getMerch($openid,'common');

}





$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and  openid = '{$openid}' {$condition} limit 1 ");

//print_r($uid);exit;

$username = pdo_fetchcolumn(' select username from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and  openid = '{$openid}' {$condition} limit 1 ");

$thismerch=p('bonus')->getMerch($openid,'deal');

$title=pdo_fetch('select bm.*,du.level from '.tablename('sz_yi_dealmerch_user').' du left join '.tablename('sz_yi_bart_member').' bm on bm.id = du.level where du.uniacid = :uniacid and du.uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$thismerch['uid']));

if (!$uid) {

    m('tools')->tips('你还不是换货商家请先成为换货商家!',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>5)));

}

// $title=pdo_fetchcolumn(' select username from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and  openid = '{$openid}' {$condition} limit 1 ");



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

// print_r($uid);exit;

if($op == 'getinfo' && $_W['isajax']){



    $allprice = pdo_fetchcolumn('select  sum(og.price) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status >= 1  limit 1 " );





    $sp_goods = pdo_fetchall('select og.*,o.dispatchprice from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$uid} and og.supplier_apply_status=0");

    $postprice=0;

    foreach ($sp_goods as $key => $value) {

        if ($value['dispatchprice'] > 0) {

            $postprice+=$value['dispatchprice'];

        }

    }



    // $allprice = pdo_fetchcolumn('select  sum(og.price*og.total) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status >= 1  limit 1 " );



    $todayprice = pdo_fetchcolumn('select  sum(og.price) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status >= 1 and  o.paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );



    // $todayprice = pdo_fetchcolumn('select  sum(og.price*og.total) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status >= 1 and  o.paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );



    $todaycount =   pdo_fetchcolumn('select  count(*) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status >= 1 and  o.paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );



    // $todaycount =   pdo_fetchcolumn('select  sum(og.total) from '.tablename('sz_yi_order_goods')." as og left join ".tablename('sz_yi_order')." as o on og.orderid = o.id  where og.uniacid = '{$_W['uniacid']}' and og.supplier_uid = '{$uid}' and o.status >= 1 and  o.paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );



    $member = m('member')->getInfo($openid);

    $member['credit3']=m('member')->getCredit($openid,'credit3');

    $member['freeze_credit3']=m('member')->getCredit($openid,'freeze_credit3');

    $member['currency_credit3']=m('member')->getCredit($openid,'currency_credit3');

    $pageInfo = array(

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/merchinfo', array('op' => 'merched','id' => $merchid)),

            // 'color' => '#EF6BA5',

            'name'  => '修改资料',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/xiugaizil.png',

        ),

        // array(

        // 	'url'   => $this->createPluginMobileUrl('suppliermenu/merchinfo'),

        // 	'color' => '#FF6363',

        // 	'name'  => '商家信息',

        // 	'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',

        // ) ,

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/info',array('op'=>'editpwd','merch' =>$role)),

            // 'color' => '#10BDFF',

            'name'  => '修改密码',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/xiugaimim.png',

        ) ,

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/info', array('op' => 'editstore','merch' =>$role)),

            // 'color' => '#FF6363',

            'name'  => '店铺装修',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/dpzx.png',

        ) ,

        array(

            'url'   => $this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>$role ,'storeid' => $uid)),

            // 'color' => '#EF6BA5',

            'name'  => '我的店铺',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/wddp.png',

        ) ,

        array(

            'url'   => $this->createMobileUrl('member/withdraw', array('op' => 'info')),

            // 'color' => '#Ff9933',

            'name'  => '提现资料',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/txzl.png',

        ),

        array(

            'url'   => $this->createMobileUrl('member/points', array('op' => 'exchange')),

            // 'color' => '#18C684',

            'name'  => '兑换点管理',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/dhdgl.png',

        ),

        array(

            'url'   => $this->createMobileUrl('member/points', array('op' => 'admin')),

            // 'color' => '#F78C39',

            'name'  => '兑换管理员',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/dhgly.png',

        ),

        // array(

        //     'url'   => $this->createPluginMobileUrl('suppliermenu/ad', array('merchtype' => '3')),

        //     'color' => '#F78C39',

        //     'name'  => '广告管理',

        //     'img'   => MODULE_URL.'plugin/suppliermenu/res/5.png',

        // )

        array(

            'url'   => $this->createMobileUrl('member/consult'),

            // 'color' => '#08E7DE',

            'name'  => '客户咨询',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/khzx.png',

        )

    );



    $product=array(

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/dealgoods',array('op'=>'post','merch'=>$role)),

            // 'color' => '#18C684',

            'name'  => '发布宝贝',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/fbbb.png',

        ) ,

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/goods',array('merch' =>$role)),

            // 'color' => '#DE39EF',

            'name'  => '宝贝管理',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/bbgl.png',

        ) ,

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/order',array('merch' =>$role)),

            // 'color' => '#F78C39',

            'name'  => '订单管理',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/ddgl.png',

        ) ,

        // array(

        //     'url'   => $this->createMobileUrl('member/consult'),

        //     // 'color' => '#08E7DE',

        //     'name'  => '广告管理',

        //     'img'   => MODULE_URL.'plugin/suppliermenu/res/13.png',

        // )

         array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/ad', array('merchtype' => '3')),

            // 'color' => '#F78C39',

            'name'  => '广告管理',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/gggl.png',

        )

    );

    $account=array(

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdraw','merch' =>$role)),

            // 'color' => '#08E7DE',

            'name'  => '邮费提现',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/yftx.png',

        ) ,

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdrawlist','merch' =>$role)),

            // 'color' => '#F93',

            'name'  => '提现记录',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/txjl.png',



        ) ,

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/transfer', array('op' => 'editpwd','merch'=>$role)),

            // 'color' => '#FF6363',

            'name'  => '换货码转账',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/hhmzz.png',

        ),

        array(

            'url'   => $this->createPluginMobileUrl('suppliermenu/activa', array('op' => 'editpwd','merch'=>$role)),

            // 'color' => '#FF6363',

            'name'  => '换货码激活',

            'img'   => MODULE_URL.'plugin/suppliermenu/res/hhmjh.png',

        )

    );

    //   array(

    // 'url'   => $this->createPluginMobileUrl('suppliermenu/goods',array('op'=>'post','merch'=>$role)),

    // 'color' => '#18C684',

    // 'name'  => '发布宝贝',

    // 'img'   => MODULE_URL.'plugin/suppliermenu/res/10.png',

    //   ) ,

    //   array(

    // 'url'   => $this->createPluginMobileUrl('suppliermenu/goods',array('merch' =>$role)),

    // 'color' => '#DE39EF',

    // 'name'  => '宝贝管理',

    // 'img'   => MODULE_URL.'plugin/suppliermenu/res/8.png',

    //   ) ,

    //   array(

    // 'url'   => $this->createPluginMobileUrl('suppliermenu/order',array('merch' =>$role)),

    // 'color' => '#F78C39',

    // 'name'  => '订单管理',

    // 'img'   => MODULE_URL.'plugin/suppliermenu/res/12.png',

    //   ) ,

    //   array(

    // 'url'   => $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdraw','merch' =>$role)),

    // 'color' => '#08E7DE',

    // 'name'  => '供应商提现',

    // 'img'   => MODULE_URL.'plugin/suppliermenu/res/13.png',

    //   ) ,

    //   array(

    // 'url'   => $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdrawlist')),

    // 'color' => '#F93',

    // 'name'  => '提现记录',

    // 'img'   => MODULE_URL.'plugin/suppliermenu/res/4.0.png',

    //   ) ,

    // );

    // 2为商家 多有以下权限

    // $pageInfo[] = array(

    // 	'url'   => $this->createPluginMobileUrl('suppliermenu/info', array('op' => 'editstore','merch' =>$role)),

    // 	'color' => '#FF6363',

    // 	'name'  => '店铺装修',

    // 	'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',

    // );

    // if ($role == 3){

    //     $merchid=pdo_fetchcolumn(' select merchid from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' {$condition} limit 1 ");

    //           //拿到本地商家id

    //     $pageInfo[] = array(

    //               'url'   => $this->createMobileUrl('member/merch', array('op' => 'detail','id' => $merchid,'merch' =>$role)),

    //               'color' => '#EF6BA5',

    //               'name'  => '我的店铺',

    //               'img'   => MODULE_URL.'plugin/suppliermenu/res/11.png',

    //           );

    //       }else{

    //           $pageInfo[] = array(

    //               'url'   => $this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>$role ,'storeid' => $uid)),

    //               'color' => '#EF6BA5',

    //               'name'  => '我的店铺',

    //               'img'   => MODULE_URL.'plugin/suppliermenu/res/11.png',

    //           );

    //       }



    // }



//        unset($pageInfo[1]);

    $dealmerchid=pdo_fetchcolumn(' select dealmerchid from  '.tablename('sz_yi_perm_user')." where uniacid = '{$_W['uniacid']}' and openid = '{$openid}' {$condition} limit 1 ");

//

    // unset($pageInfo[1]);

    // unset($pageInfo[2]);

    // unset($pageInfo[4]);

    // unset($pageInfo[5]);



    // $pageInfo[1]=array(

    //     'url'   => $this->createPluginMobileUrl('suppliermenu/dealgoods',array('op'=>'post','merch'=>$role)),

    //     'color' => '#18C684',

    //     'name'  => '发布宝贝',

    //     'img'   => MODULE_URL.'plugin/suppliermenu/res/10.png',

    // );



    //  $pageInfo[] = array(

    //      'url'   => $this->createPluginMobileUrl('suppliermenu/info', array('op' => 'editstore','merch'=>$role)),

    //      'color' => '#FF6363',

    //      'name'  => '店铺装修',

    //      'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',

    //  );

    //  $pageInfo[] = array(

    //      'url'   => $this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>$role ,'storeid' => $uid)),

    //      'color' => '#EF6BA5',

    //      'name'  => '我的店铺',

    //      'img'   => MODULE_URL.'plugin/suppliermenu/res/11.png',

    //  );

    //  $pageInfo[] = array(

    //      'url'   => $this->createPluginMobileUrl('suppliermenu/transfer', array('op' => 'editpwd','merch'=>$role)),

    //      'color' => '#FF6363',

    //      'name'  => '换货码转账',

    //      'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',

    //  );

    //  $pageInfo[] = array(

    //      'url'   => $this->createPluginMobileUrl('suppliermenu/activa', array('op' => 'editpwd','merch'=>$role)),

    //      'color' => '#FF6363',

    //      'name'  => '换货码激活',

    //      'img'   => MODULE_URL.'plugin/suppliermenu/res/9.png',

    //  );



    show_json(1, array(

        'allprice'  	 => $allprice   ? $allprice   : 0,

        'postprice'  	 => $postprice   ? $postprice   : 0,

        'todayprice'	 => $todayprice ? $todayprice : 0,

        'todaycount'	 => $todaycount ? $todaycount : 0,

        'member'    	 => $member,

        'type'      	 => $type,

        'pageInfo'   	 => $pageInfo,

        'productInfo'    => $product,

        'accountInfo'   => $account,

        'uid'			 => $uid

    ));

}



include $this->template('index');

