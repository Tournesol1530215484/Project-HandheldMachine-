<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and uniacid = :uniacid ';
    
    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_activity').' where 1 '.$condition;
    $sql.=' order by id desc ';
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity'). " where 1 {$condition} ", $params);
    
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $list = pdo_fetchall($sql, $params);
    foreach ($list as $key => $value) {          
        if ($value['stime'] > time()) {
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    }
    $pager = pagination($totals, $pindex, $psize);
}else if ($op == 'post'){
    $id=intval($_GPC['id']);
}
include $this -> template('activity');