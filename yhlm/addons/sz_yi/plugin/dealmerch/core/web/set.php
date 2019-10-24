<?php
if (!defined('IN_IA')){
    exit('Access Denied');
} 
global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'dispaly' : trim($_GPC['op']);

$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
 
$set = unserialize($setdata['sets']);


    $list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_adv') . " WHERE uniacid = '{$_W['uniacid']}' and isbart = 1 and enabled = 0 ORDER BY displayorder DESC");

if (checksubmit()){

    if ($_W['ispost']){ 
        $set['bart']['account'] = trim($_GPC['data']['openid']);   
        $set['bart']['title'] = trim($_GPC['title']);   
        $set['bart']['state'] = trim($_GPC['state']);  
        $set['bart']['share'] = trim($_GPC['share']); 
        $set['bart']['thumb'] = trim($_GPC['thumb']); 
        $set['bart']['get'] = trim($_GPC['get']); 
        $set['bart']['ratio'] = doubleval($_GPC['ratio']);  
        $set['bart']['tratio'] = doubleval($_GPC['tratio']);
        $set['bart']['trbonus'] = intval($_GPC['trbonus']);  
        $set['bart']['protocol']=$_GPC['protocol']; 
        $set['bart']['withdraw'] = doubleval($_GPC['withdraw']);    
        plog('dealmerch.save.dealmerch', '修改系统设置-商家设置');    
    } 
    $data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set));  
    pdo_update('sz_yi_sysset', $data, array('uniacid' => $_W['uniacid']));  
    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'])); 

    m('cache') -> set('merchset', $setdata);
    message('保存成功!', $this -> createPluginWebUrl('dealmerch/set'), 'success'); 
}
$saler=m('member')->getMember($set['bart']['account']);
$max=0;
if (!empty($set['bart']['protocol'])){ 
    foreach ($set['bart']['protocol'] as $k => $v) { 
        $v['content']=html_entity_decode($v['content']);        //如果为空不会进来max=0 如果只有一个数组max=2
        $max=$k;
    }
    $max++; 
} 

load() -> func('tpl');
include $this -> template('set');
