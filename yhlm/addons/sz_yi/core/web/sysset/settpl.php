<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$tpl = trim($_GPC['tpl']);
load()->func('tpl');

if ($tpl == 'setmenu') {
    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

    $set = unserialize($setdata['sets']);

    $k=$_GPC[k].$_GPC[k];

    $spec = array(
        "id" => random(32),
        "type" => $_GPC['type']
    );

    include $this->template('web/sysset/tpl/setmenu');
}
