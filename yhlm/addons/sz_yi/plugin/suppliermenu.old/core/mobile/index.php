<?php
//多级分销商城 QQ:1084070868
global $_W, $_GPC;

$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('display','getinfo') )?'display': $_GPC['op'];
 
$openid = m('user')->getOpenid();

 


if($op == 'getinfo' && $_W['isajax']){

 $allprice = pdo_fetchcolumn('select  sum(price) from '.tablename('sz_yi_order')." where uniacid = '{$_W['uniacid']}' and supplier_uid = '{$_W['member']['uid']}' and status>1  limit 1 " ); //price

 $todayprice = pdo_fetchcolumn('select  sum(price) from '.tablename('sz_yi_order')." where uniacid = '{$_W['uniacid']}' and supplier_uid = '{$_W['member']['uid']}' and status>1 and  paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 " );

 $todaycount = pdo_fetchcolumn('select  count(1) from '.tablename('sz_yi_order')." where uniacid = '{$_W['uniacid']}' and supplier_uid = '{$_W['member']['uid']}' and status>1 and  paytime>=  unix_timestamp( CURDATE() )  and paytime <= unix_timestamp(now())  limit 1 ");


 $member = m('member')->getInfo($openid);

 show_json(1,array('allprice'=>$allprice?$allprice:0,'todayprice'=>$todayprice?$todayprice:0,'todaycount'=>$todaycount?$todaycount:0,'member'=>$member));


}




 include $this->template('index');
 