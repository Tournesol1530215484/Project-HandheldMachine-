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
        $set['shop']['merchname'] = trim($_GPC['merchname']);   
        $set['shop']['title'] = trim($_GPC['title']);
        $set['shop']['thumbs'] = $_GPC['thumbs'];
        $set['shop']['protocol']=$_GPC['protocol']; 
        $set['shop']['adlink']=$_GPC['adlink']; 
        plog('merchant.save.merchant', '修改系统设置-商家设置');
    }
    $data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set));    //
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
$max=0;
if (!empty($set['shop']['protocol'])){
    foreach ($set['shop']['protocol'] as $k => $v) {
        $v['content']=html_entity_decode($v['content']);        //如果为空不会进来max=0 如果只有一个数组max=2
        $max=$k;
    }
    $max++;
}

include $this -> template('set');
