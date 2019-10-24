<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$plugin_diyform = p('diyform');             
$pu=m('tools')->getinfo($_W['uid']);
$activityId=$_GPC['activityId'];
$muser=m('activity')->getMuser($_W['uid']);
m('activityba')->checkActivity($activityId);                

if($op == 'basic'){
    $id=intval($_GPC['id']);                 
    $act=m('activity')->getact($id);
}else if($op == 'live'){         

    if ($ac == 'sub') {
        $data=$_GPC['data'];                         
        $data['uniacid']=$_W['uniacid'];
        $data['actid']=$_GPC['activityId'];
        $data['ctime']=time();   
        pdo_insert('sz_yi_activity_livemsg',$data);
        $re=pdo_insertid();
        if ($re) {                       
            message('新增成功!',$this->createPluginWebUrl('activityba/control',array('op'=>'live')),'success');
        }
        message('新增失败!','','error');
    }else if($ac == 'delete'){

        $id=$_GPC['id'];
        $exists=pdo_fetch('select * from '.tablename('sz_yi_activity_livemsg').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

        if ($exists['actid'] != $activityId) {           
            message('没有这条消息','','error');
        }                                            
        pdo_delete('sz_yi_activity_livemsg',array('id'=>$id));
        message('删除成功!','','success');           
    }                               
    
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $sql='select * from '.tablename('sz_yi_activity_livemsg').' where uniacid = :uniacid and actid = :id';
    $params=array(
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$_GPC['activityId']
    );

    $list=pdo_fetchall($sql,$params);

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_livemsg').' where uniacid = :uniacid and actid = :id ',$params);
    $pager = pagination($totals, $pindex, $psize);                       

}else if($op == 'examine'){
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;
    $acid=$_GPC['activityId'];
    $act=m('activity')->getact($activityId);
    if ($ac == 'sub') {
        $id=$_GPC['id'];     
        $data=$_GPC['data'];     

        if ($id) {
            $re=pdo_update('sz_yi_activity_shake',$data,array('id'=>$id));  
            if ($re) {
                message('更新成功!',$this->createPluginWebUrl('activityba/control',array('op'=>'examine')),'success');
            }                       
            message('更新失败!',referer(),'error');
        }else{
            $data['uniacid']=$_W['uniacid'];            
            $data['actid']=$_GPC['activityId'];
            $data['ctime']=time();                               
            pdo_insert('sz_yi_activity_prize',$data);        
            $re=pdo_insertid(); 
            if ($re) {              
                message('新增成功!',$this->createPluginWebUrl('activityba/control',array('op'=>'examine')),'success');
            }
            message('新增失败!',referer(),'error');
        }                                                                           
    }else if($ac == 'set'){
        $type=$_GPC['type'];
        $sure=$_GPC['sure'];
        $data=array(
            $type=>$sure == 'on'?1:0
        );                         

        $re=pdo_update('sz_yi_activity',$data,array('id'=>$activityId));

        show_json(1);            
    }else if($ac == 'edit'){
        $type=$_GPC['type'];
        $id=$_GPC['id'];

        if ($type < 3) {
            $re=pdo_update('sz_yi_activity_msg',array('status'=>$type),array('id'=>$id,'uniacid'=>$_W['uniacid']));

        }else{      
            $re=pdo_delete('sz_yi_activity_msg',array('id'=>$id,'uniacid'=>$_W['uniacid']));
        }

        if ($re) {
            show_json(1,'操作成功!');
        }
        show_json(0,'操作失败!');
    }                               
        
    
    $status=$_GPC['status']?:1;
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $condition=' and am.uniacid = :uniacid and am.actid = :id ';

    if ($status == 1) {
        $condition.=' and am.status = 0 ';
    }else if($status == 2){
        $condition.=' and am.status = 1 ';
    }else if($status == 3){
        $condition.=' and am.status = 2 ';
    }           

    $sql='select am.*,m.nickname,m.avatar from '.tablename('sz_yi_activity_msg').' am left join '.tablename('sz_yi_member').' m on m.openid = am.openid where am.uniacid = :uniacid and am.actid = :id ';
    $sql.=$condition;
    $sql.=' order by am.id desc ';
    $sql.=' limit '.($pindex -1) * $psize.','.$psize;
    $params=array(  
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$acid,
    );
    $list=pdo_fetchall($sql,$params);

    foreach ($list as $key => $value) {
        $temp=unserialize($value['detail']);
        $list[$key]['content']=$temp['content'];
    }
                
    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_msg').' am left join '.tablename('sz_yi_member').' m on m.openid = am.openid where am.uniacid = :uniacid and am.actid = :id '.$condition,$params);
    $pager = pagination($totals, $pindex, $psize);


}else if($op == 'filte'){

    $acid=$_GPC['activityId'];
    $act=m('activity')->getact($activityId);
    if ($ac == 'sub') {
        $data=$_GPC['data'];     
        $data['uniacid']=$_W['uniacid'];
        $data['actid']=$_GPC['activityId'];
        $data['ctime']=time();                               
        pdo_insert('sz_yi_activity_filte',$data);        
        $re=pdo_insertid();                                                    
        if ($re) {                                                    
            message('新增成功!',$this->createPluginWebUrl('activityba/control',array('op'=>'filte')),'success');
        }                                                                                
        message('新增失败!','','error');             
    }else if($ac == 'delete'){          
        $id=$_GPC['id'];
        $exists=pdo_fetch('select * from '.tablename('sz_yi_activity_filte').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

        if ($exists['actid'] != $activityId) {           
            message('没有这条消息','','error');
        }                                            
        pdo_delete('sz_yi_activity_filte',array('id'=>$id));
        message('删除成功!','','success');             
    }                      
                                     
    $status=$_GPC['status']?:1;
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;       

    $condition=' and uniacid = :uniacid and actid = :id ';

    $params=array(   
        ':uniacid'=>$_W['uniacid'],                              
        ':id'=>$_GPC['activityId']
    );

    $sql='select * from '.tablename('sz_yi_activity_filte').' where 1 ';                                              
    $sql.=$condition;
    $sql.=' limit '.($pindex  -1) * $psize.','.$psize;                   

    $list=pdo_fetchall($sql,$params);

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_filte').'  where 1'.$condition,$params);
    $pager = pagination($totals, $pindex, $psize);

}
include $this -> template('control');        
