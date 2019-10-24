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
        $set['bart']['ad']['price']    = floatval($_GPC['ad']['price']);   
        $set['bart']['ad']['num']      = intval($_GPC['num']);  
        $set['bart']['ad']['me']       = sprintf('%.3f',$_GPC['ad']['me']); 
        $set['bart']['ad']['one']      = sprintf('%.3f',$_GPC['ad']['one']); 
        $set['bart']['ad']['two']      = sprintf('%.3f',$_GPC['ad']['two']); 
        $set['bart']['ad']['area']     = sprintf('%.3f',$_GPC['ad']['area']);  
        $set['bart']['ad']['city']     = sprintf('%.3f',$_GPC['ad']['city']);
        $set['bart']['ad']['province'] = sprintf('%.3f',$_GPC['ad']['province']);  
        $set['bart']['ad']['midd']     = sprintf('%.3f',$_GPC['ad']['midd']); 
        $set['bart']['ad']['account']  = sprintf('%.3f',$_GPC['ad']['account']);  
        
        plog('dealmerch.save.dealmerch', '修改系统设置-广告设置');    
    } 
    $data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set));  
    pdo_update('sz_yi_sysset', $data, array('uniacid' => $_W['uniacid']));  
    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'])); 

    m('cache') -> set('merchset', $setdata);
    message('保存成功!', $this -> createPluginWebUrl('dealmerch/ad_set'), 'success'); 
} 
include $this -> template('ad_set');
