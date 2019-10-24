<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;

$op= !empty($_GPC['op'])?$_GPC['op']:'display';
if ($op == 'display'){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $condition=' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list=pdo_fetchall('select * from '.tablename('sz_yi_barter_menu').' where uniacid = :uniacid '.$condition,array(':uniacid'=>$_W['uniacid']));
    $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_barter_menu').' where uniacid ='.$_W['uniacid']);
    $pager = pagination($total, $pindex, $psize);
}else if ($_W['ispost'] && $op == 'post'){
     $data=array(
        'uniacid'=>$_W['uniacid'],
        'thumb' => $_GPC['thumb'],
        'url' => $_GPC['url'],
        'title' => $_GPC['title'],
        'isdisplay' => $_GPC['isdisplay'],
        'displayorder' => $_GPC['displayorder'],
    );
    if (intval($_GPC['id'])){
        $sure=pdo_update('sz_yi_barter_menu',$data,array('id'=>$_GPC['id']));
    }else{
        $sure=pdo_insert('sz_yi_barter_menu',$data);
    }
    $sure?message('修改成功!',$this->createPluginWebUrl('dealmerch/setmenu'),'success'):message('修改失败!',$this->createPluginWebUrl('dealmerch/setmenu'),'error');
    exit;
}else if ($op == 'edit'){
    $id=intval($_GPC['id']);
    $item=pdo_fetch('select * from '.tablename('sz_yi_barter_menu').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
}

load() -> func('tpl');
include $this -> template('setmenu');
exit;
 