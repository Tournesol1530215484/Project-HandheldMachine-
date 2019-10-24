<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

//af_supplier images
global $_W, $_GPC;
$openid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid= :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
$op=!empty($_GPC['op'])?$_GPC['op']:'display';
if ($op == 'doadd') {
    $id=$_GPC['id'];
   empty($_GPC['title']) && message('模板名不能为空!',$this->createPluginWebUrl('suppliermenu/ad_demo'),'error');
   empty($_GPC['thumbs']) && message('模板图片不能为空!',$this->createPluginWebUrl('suppliermenu/ad_demo'),'error');
    $data=array(
        'uniacid'=>$_W['uniacid'],
        'title'=>$_GPC['title'],
        'type'=>$_GPC['type'],
        'status'=>$_GPC['status'],
        'video'=>$_GPC['video'],
        'ctime'=>time(),
        'thumb'=>serialize($_GPC['thumbs']),
        'module'=>count($_GPC['thumbs'])
    );
    if (empty($id)) {
        pdo_insert('sz_yi_set_ad_model',$data);
        $id=pdo_insertid();
        $id?message('添加模板成功!',$this->createPluginWebUrl('suppliermenu/ad_demo'),'success'):message('添加模板失败!',$this->createPluginWebUrl('suppliermenu/ad_demo'),'error');
    }else{
       $sure=pdo_update('sz_yi_set_ad_model',$data,array('id'=>$id));
        $sure?message('修改模板成功!',$this->createPluginWebUrl('suppliermenu/ad_demo'),'success'):message('修改模板失败!',$this->createPluginWebUrl('suppliermenu/ad_demo'),'error'); 
    }
}else if ($op == 'display'){

    $sql = 'select * from '.tablename('sz_yi_set_ad_model');
    $list = pdo_fetchall($sql);
    $merch=p('bonus')->getMerch($_W['uid']);
    $member=m('member')->getMember($merch['openid']);
    if ($list) {
        foreach ($list as $key => $value) {
            $list[$key]['thumb']=unserialize($list[$key]['thumb']);
           foreach ($list[$key]['thumb'] as $key1 => $value1) {
                $list[$key]['thumb'][$key1]=tomedia($list[$key]['thumb'][$key1]);
           } 
        }
    }
    $totalcount = $total = pdo_fetchcolumn('select count(*) from '. tablename('sz_yi_set_ad_model'));
}else if ($op == 'set'){
    $id=$_GPC['id'];
    $merch=p('bonus')->getMerch($_W['uid']);            
    $sure=pdo_update('sz_yi_member',array('default_ad_model'=>$id),array('openid'=>$merch['openid']));
    if ($sure) {
        show_json(1);
    }else{
        show_json(0);
    }
}else if ($op == 'adddemo'){
    $id=$_GPC['id'];
    if ($_GPC['id']) {
        $item=pdo_fetch('select * from '.tablename('sz_yi_set_ad_model').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        if ($item) {
            $item['thumb']=unserialize($item['thumb']);
        }
    }
}
load() -> func('tpl');
include $this -> template('ad_demo');
exit;           
