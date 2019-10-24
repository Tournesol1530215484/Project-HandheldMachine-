<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$plugin_diyform = p('diyform');             
$pu=m('tools')->getinfo($_W['uid']);
$muser=m('activity')->getMuser($_W['uid']);  
$activityId=$_GPC['activityId'];             
m('activityba')->checkActivity($activityId);                
                         
if($op == 'basic'){                 
    $id=intval($_GPC['id']);                 
    $act=m('activity')->getact($id);
}else if($op == 'winning'){
    
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    /*$sql='select ap.type,ap.title,m.nickname,m.avatar,m.mobile from '.tablename('sz_yi_activity_luckguys').' al left join '.tablename('sz_yi_member').'m on m.openid = al.openid left join '.tablename('sz_yi_activity_prize').' ap on ap.id = al.prizeid where al.uniacid = :uniacid and sg.actid = :id and al.status > 0 ';
                                    
    $params=array(                      
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$_GPC['activityId']
    );

    $list=pdo_fetchall($sql,$params);*/


    $sql='select al.id,al.ctime,al.code,ap.type,ap.title,sg.data from '.tablename('sz_yi_activity_luckguys').' al left join '.tablename('sz_yi_activity_signup').' sg on sg.openid = al.openid left join '.tablename('sz_yi_activity_prize').' ap on ap.id = al.prizeid where al.uniacid = :uniacid and sg.actid = :id and al.status > 0 ';
                                                               
    $params=array(                                              
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$_GPC['activityId']       
    );
    $list=pdo_fetchall($sql,$params);
            
    foreach ($list as $key => $value) {         
        $temp=unserialize($value['data']);            
        $list[$key]['realname']=$temp['realname']['data'];
        $list[$key]['mobile']=$temp['mobile']['data'];          
        $list[$key]['code']=strval($value['code']);                 
        $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
        unset($list[$key]['data']); 
    }


    if ($_W['isajax']) {
        if ($ac == 'clear') {           
            $re=pdo_update('sz_yi_activity_luckguys',array('status'=>0),array('actid'=>$activityId,'uniacid'=>$_W['uniacid']));
            if ($re) {
                show_json(1,'清空成功！');
            }           
            show_json(0,'清空失败！');
        }                                       
    }    

    if($ac == 'excel'){

        m('excel')->export($list, array(                          
            'title' => $act['title'].'中奖数据',                    
            'columns' => array(                                           
                array('title' => 'id',      'field' => 'id', 'width'           => 12), 
                array('title' => '奖品类型', 'field' => 'type', 'width'     => 12), 
                array('title' => '奖品名称', 'field' => 'title', 'width'       => 12), 
                array('title' => '中奖人',   'field' => 'realname', 'width'       => 12), 
                array('title' => '报名手机号','field' => 'mobile', 'width'       => 12), 
                array('title' => '中奖时间',  'field' => 'ctime', 'width' => 24), 
                array('title' => '兑换码',    'field' => 'code', 'width' => 12)
            )                                                           
        ));                                 
    }
    
    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_luckguys').' where uniacid = :uniacid and actid = :id and status > 0 ',$params);
    $pager = pagination($totals, $pindex, $psize);

}else if($op == 'shake'){           

    if ($ac == 'sub') {
        $id=$_GPC['id'];     
        $data=$_GPC['data'];     

        if ($id) {
            $re=pdo_update('sz_yi_activity_shake',$data,array('id'=>$id));  
            if ($re) {
                message('更新成功!',$this->createPluginWebUrl('activityba/activity',array('op'=>'prize')),'success');
            }
            message('更新失败!',referer(),'error');
        }else{
            $data['actid']=$_GPC['activityId'];
            $data['ctime']=time();                               
            pdo_insert('sz_yi_activity_prize',$data);        
            $re=pdo_insertid();
            if ($re) {
                message('新增成功!',$this->createPluginWebUrl('activityba/activity',array('op'=>'prize')),'success');
            }
            message('新增失败!',referer(),'error');
        }                                                                           
    }                   
    
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $sql='select * from '.tablename('sz_yi_activity_shake').' where uniacid = :uniacid and actid = :id';
    $params=array(
        ':uniacid'=>$_W['uniacid'],                          
        ':id'=>$_GPC['activityId']
    );

    // $list=pdo_fetchall($sql,$params);

    // $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_shake').' where uniacid = :uniacid and actid = :id ',$params);
    // $pager = pagination($totals, $pindex, $psize);

}
include $this -> template('interact');
