<?php
global $_W, $_GPC;
//check_shop_auth('http://120.26.212.219/api.php', $this->pluginname);
load()->func('tpl');
//查询顶部图片
$list=pdo_fetch('select * from' .tablename('sz_yi_comeon_xitong').'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
//查询品牌
$liste=pdo_fetchall('select * from' . tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));

include $this->template('index');


