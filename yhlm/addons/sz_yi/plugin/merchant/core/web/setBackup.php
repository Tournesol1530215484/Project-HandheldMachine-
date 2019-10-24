<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'dispaly' : trim($_GPC['op']);

$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
$set = unserialize($setdata['sets']);
if (checksubmit()){
    if ($_W['ispost']){
        $set['shop']['title'] = trim($_GPC['title']);
        $set['shop']['thumbs'] = $_GPC['thumbs'];
        plog('merchant.save.merchant', '修改系统设置-商家设置');
    }
    $data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set));
    if (empty($setdata)){
        pdo_insert('sz_yi_sysset', $data);
    }else{
        pdo_update('sz_yi_sysset', $data, array('uniacid' => $_W['uniacid']));
    }
    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    m('cache') -> set('merchset', $setdata);
    message('保存成功!', $this -> createPluginWebUrl('merchant/set'), 'success');
}
load() -> func('tpl');
include $this -> template('set');
