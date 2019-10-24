<?php
global $_W, $_GPC;
//check_shop_auth('http://120.26.212.219/api.php', $this->pluginname);
load()->func('tpl');
$liste=pdo_fetchall('select * from' . tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
$paihang = pdo_fetch("SELECT * FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
include $this->template('ph/paihang');


