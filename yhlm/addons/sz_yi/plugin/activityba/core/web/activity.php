<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$plugin_diyform = p('diyform');             
$pu=m('tools')->getinfo($_W['uid']);
$muser=m('activity')->getMuser($_W['uid']);
$activityId=$_GPC['activityId'];
$tempact=m('activityba')->checkActivity($activityId);
if($op == 'basic'){
    $id=intval($_GPC['activityId']);

    if ($_W['isajax']) {         
        $data=array(
            'screen_stime'=>strtotime($_GPC['data']['start']),
            'screen_etime'=>strtotime($_GPC['data']['end']),
            'afterTheSignup'=>$_GPC['data']['afterTheSignup'],
            'theme'=>$_GPC['data']['theme'],                            
            'companyLogo'=>$_GPC['data']['companyLogo'],                
            'background'=>$_GPC['data']['background'],
        );                                             

        $re=pdo_update('sz_yi_activity',$data,array('uniacid'=>$_W['uniacid'],'id'=>$activityId));

        if ($re) {          
            show_json(1,'修改成功!');
        }
        show_json(0,'修改失败!');
    }                                       
    $act=m('activity')->getact($id);
}else if($op == 'prize'){

    $id=$_GPC['id'];
    if ($ac == 'post') {
        if ($id) {
            $item=pdo_fetch('select * from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
            if ($item['actid'] != $activityId) {
                message('没有该条记录!','','error');
            }
        }
    }else if ($ac == 'sub') {
        
        $data=$_GPC['data'];

        if ($id) {  
            $re=pdo_update('sz_yi_activity_prize',$data,array('id'=>$id));  
            if ($re) {
                message('更新成功!',$this->createPluginWebUrl('activityba/activity',array('op'=>'prize')),'success');
            }
            message('更新失败!',referer(),'error');
        }else{
        	$data['uniacid']=$_W['uniacid'];
            $data['actid']=$_GPC['activityId'];
            $data['ctime']=time();                      
            pdo_insert('sz_yi_activity_prize',$data);
            $re=pdo_insertid();
            if ($re) {
                message('新增成功!',$this->createPluginWebUrl('activityba/activity',array('op'=>'prize')),'success');
            }
            message('新增失败!',referer(),'error');
        }                                                                           
    }else if ($ac == 'delete'){
        $item=pdo_fetch('select * from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        if ($item['actid'] != $activityId) {
            message('没有该条记录!','','error');
        }

        $re=pdo_delete('sz_yi_activity_prize',array('id'=>$id));
        message('删除成功',$this->createPluginWebUrl('activityba/activity',array('op'=>'prize')),'success');
    }                   
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $sql='select * from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and actid = :id';
    $params=array(
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$_GPC['activityId']
    );

    $list=pdo_fetchall($sql,$params);

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and actid = :id ',$params);
    $pager = pagination($totals, $pindex, $psize);          

}else if($op == 'shake'){

        $id=$_GPC['id'];     
    if ($ac == 'sub') {
        $data=$_GPC['data'];     

        if ($id) {
            $re=pdo_update('sz_yi_activity_shake',$data,array('id'=>$id));  
            if ($re) {
                message('更新成功!',$this->createPluginWebUrl('activityba/activity',array('op'=>'shake')),'success');
            }
            message('更新失败!',referer(),'error');
        }else{
        	$data['uniacid']=$_W['uniacid'];
            $data['actid']=$_GPC['activityId'];
            $data['ctime']=time();                               
            pdo_insert('sz_yi_activity_shake',$data);        
            $re=pdo_insertid();         
            if ($re) {
                message('新增成功!',$this->createPluginWebUrl('activityba/activity',array('op'=>'shake')),'success');
            }
            message('新增失败!',referer(),'error');
        }                                                                           
    }else if($ac  == 'post'){
        if ($id) {
            $item=pdo_fetch('select * from '.tablename('sz_yi_activity_shake').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
            if ($item['actid'] != $activityId) {
                message('没有该条记录!','','error');
            }
        }
    }else if($ac == 'delete'){
        $item=pdo_fetch('select * from '.tablename('sz_yi_activity_shake').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        if ($item['actid'] != $activityId) {
            message('没有该条记录!','','error');
        }

        $re=pdo_delete('sz_yi_activity_shake',array('id'=>$id));
        message('删除成功',$this->createPluginWebUrl('activityba/activity',array('op'=>'shake')),'success');
    }                   
    
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $sql='select * from '.tablename('sz_yi_activity_shake').' where uniacid = :uniacid and actid = :id ';
    
    $params=array(
        ':uniacid'=>$_W['uniacid'],                          
        ':id'=>$activityId
    );

    $list=pdo_fetchall($sql,$params);

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_shake').' where uniacid = :uniacid and actid = :id ',$params);
    $pager = pagination($totals, $pindex, $psize);

}
include $this -> template('activity');
