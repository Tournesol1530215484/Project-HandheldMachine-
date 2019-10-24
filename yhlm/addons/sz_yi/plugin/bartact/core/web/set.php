<?php
 global $_W, $_GPC;
global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'dispaly' : trim($_GPC['op']);
ca('bartact.set');
$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
/*if ($_GPC['debug']) {
    $list=pdo_fetchall('select * from '.tablename('sz_yi_activity_reward').' where uniacid = :uniacid limit 0,110',array(':uniacid'=>$_W['uniacid']));
    $a=0;
    foreach ($list as $key => $value) {
        if ($value['money'] > 0) {
            if ($value['id'] != 88) {
                // m('member')->setCredit($value['openid'],'credit2',-$value['money']);
                $a++;
            }
        }
    }
                    
    exit; 
}*/

$set = unserialize($setdata['sets']);  
if (checksubmit()){ 
    if ($_W['ispost']){
        $bartact['audit'] = intval($_GPC['bartact']['audit']);    //审核
        $bartact['minscore'] = floatval($_GPC['bartact']['minscore']);      //打卡获得积分 
        $bartact['maxscore'] = floatval($_GPC['bartact']['maxscore']);   
        $bartact['card_agent1'] = floatval($_GPC['bartact']['card_agent1']);   
        $bartact['card_agent2'] = floatval($_GPC['bartact']['card_agent2']);   
        $bartact['card_commission1'] = floatval($_GPC['bartact']['card_commission1']);   
        $bartact['card_commission2'] = floatval($_GPC['bartact']['card_commission2']);   
        $bartact['card_commission3'] = floatval($_GPC['bartact']['card_commission3']);   
        $bartact['card_boos'] = floatval($_GPC['bartact']['card_boos']);   
        $bartact['reward_author'] = floatval($_GPC['bartact']['reward_author']);   
        $bartact['reward_agent1'] = floatval($_GPC['bartact']['reward_agent1']);   
        $bartact['reward_agent2'] = floatval($_GPC['bartact']['reward_agent2']);   
        $bartact['reward_commission1'] = floatval($_GPC['bartact']['reward_commission1']);   
        $bartact['reward_commission2'] = floatval($_GPC['bartact']['reward_commission2']);   
        $bartact['reward_commission3'] = floatval($_GPC['bartact']['reward_commission3']);   
        $bartact['reward_boos'] = floatval($_GPC['bartact']['reward_boos']);   
        $bartact['msg'] = floatval($_GPC['bartact']['msg']);   
        $bartact['signin'] = intval($_GPC['bartact']['signin']);  

        $bartact['level1'] = floatval($_GPC['bartact']['level1']);   
        $bartact['level2'] = floatval($_GPC['bartact']['level2']);   
        $bartact['level3'] = floatval($_GPC['bartact']['level3']);   
        $set['bartact']=$bartact; 
        plog('bartact.save.set', '修改系统设置-易活动设置');
    }  
    $data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set)); 
    if (empty($setdata)){
        pdo_insert('sz_yi_sysset', $data);       
    }else{                           
        pdo_update('sz_yi_sysset', $data, array('uniacid' => $_W['uniacid']));
    }                                                
    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    m('cache') -> set('bartactset', $setdata);
    message('保存成功!', $this -> createPluginWebUrl('bartact/set'), 'success');
}                                
$set=$set['bartact'];    
$styles = array();
$dir    = IA_ROOT . "/addons/sz_yi/plugin/" . $this->pluginname . "/template/mobile/";
if ($handle = opendir($dir)) {
    while (($file = readdir($handle)) !== false) {
        if ($file != ".." && $file != ".") {
            if (is_dir($dir . "/" . $file)) {
                $styles[] = $file;
            }
        }
    }
    closedir($handle);
}

load()->func('tpl');
include $this->template('set');
