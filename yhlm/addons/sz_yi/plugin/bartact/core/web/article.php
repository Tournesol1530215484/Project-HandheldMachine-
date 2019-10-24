<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac =$_GPC['ac'];

$plugin_diyform = p('diyform');
$totals = array();
// $muser=m('activity')->getMuser($_W['uid']);

if ($op == 'display') {     
    ca('bartact.article');   
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and aa.uniacid = :uniacid and aa.status = 1';       
    

    if ($_GPC['title']) {
        $condition .= ' and aa.title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
    
    if (!empty($_GPC['realname'])){
        $_GPC['realname'] = trim($_GPC['realname']);                         
        $condition .= ' and ( mu.realname like :realname or mu.mobile like :realname)';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }

    if ($_GPC['level']) {                                            
        $condition .= ' and mu.level = :level';                  
        $params[':level'] = intval($_GPC['level']) - 1;        
    }    

    
    if($_GPC['is_top'] != ''){              
        if($_GPC['is_top'] == 1){                        
            $condition .= ' and aa.is_top=1';
        }else if($_GPC['is_top'] == 2){
            $condition .= ' and aa.is_top=2';
        }else if($_GPC['is_top'] == 3){
            $condition .= ' and aa.is_top=3';
        }else if($_GPC['is_top'] == 4){
            $condition .= ' and aa.is_top=4';                          
        }
    }       

    if($_GPC['reside']['province'] != ""){      
        $condition .= ' and aa.province=\'' . $_GPC['reside']['province'] . '\'';
    }
    if($_GPC['reside']['city'] != ""){       
        $condition .= 'and aa.city=\'' . $_GPC['reside']['city'] . '\'';      
    }       
    if($_GPC['reside']['district'] != ""){                    
        $condition .= 'and aa.district=\'' . $_GPC['reside']['district'] . '\'';
    }        

    // if ($_GPC['parentid'] == '0'){
    //     $condition .= ' and mu.agentid=0';
    // }else if (!empty($_GPC['parentname'])){
    //     $_GPC['parentname'] = trim($_GPC['parentname']);
    //     $condition .= ' and ( p.mobile like :parentname or p.nickname like :parentname or p.realname like :parentname)';
    //     $params[':parentname'] = "%{$_GPC['parentname']}%";
    // }
    // if ($_GPC['followed'] != ''){
    //     if ($_GPC['followed'] == 2){
    //         $condition .= ' and f.follow=0 and mu.uid<>0';
    //     }else{
    //         $condition .= ' and f.follow=' . intval($_GPC['followed']);
    //     }
    // }
    
    
    // if (empty($starttime) || empty($endtime)){
    //     $starttime = strtotime('-1 month');
    //     $endtime = time();
    // }
    // if (!empty($_GPC['time'])){
    //     $starttime = strtotime($_GPC['time']['start']);
    //     $endtime = strtotime($_GPC['time']['end']);
    //     if ($_GPC['searchtime'] == '1'){
    //         $condition .= ' AND mu.agenttime >= :starttime AND mu.agenttime <= :endtime ';
    //         $params[':starttime'] = $starttime;
    //         $params[':endtime'] = $endtime;
    //     }
    // }
    // if (!empty($_GPC['agentlevel'])){
    //     $condition .= ' and mu.bonuslevel=' . intval($_GPC['agentlevel']);
    // }
    // if ($_GPC['status'] != ''){
    //     $condition .= ' and mu.status=' . intval($_GPC['status']);
    // }
    // if ($_GPC['agentblack'] != ''){
    //     $condition .= ' and mu.agentblack=' . intval($_GPC['agentblack']);
    // }


    $sql='select aa.*, mu.orgName from '.tablename('sz_yi_activity_article').' aa left join '.tablename('sz_yi_member_user').' mu on mu.openid = aa.openid where 1 '.$condition;
    $sql.=' order by aa.id desc ';          
                             
    $totals = pdo_fetchcolumn("select count(*) from ".tablename('sz_yi_activity_article')." aa left join ".tablename('sz_yi_member_user')." mu on mu.openid = aa.openid where 1  {$condition} ", $params);
    
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;                      
    $list = pdo_fetchall($sql, $params);
    foreach ($list as $key => $value) {               
        if ($value['stime'] > time()) {
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();            
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
       $list[$key]['qrcode']=m('tools')->createQrcode($this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$value['id'])));      
    }
    $pager = pagination($totals, $pindex, $psize);
}else if ($op == 'post'){
    $id=$_GPC['id'];         
    $article=m('activity')->getact($id,2);
    $muser=m('activity')->getMuser($article['openid']);     
    if ($_W['isajax']) {
        
        $data=$_GPC['data'];        
        $data['province']=$_GPC['reside']['province'];      
        $data['city']=$_GPC['reside']['city'];      
        $data['area']=$_GPC['reside']['district'];   
        if (empty($id)) {              
            $data['uid']=$muser['uid'];      
            $data['uniacid']=$_W['uniacid'];         
            $data['openid']=$muser['openid'];                                      
            pdo_insert('sz_yi_activity_article',$data);
            $id=pdo_insertid();                       

            if ($id) {                             
                show_json(1,array('msg'=>'添加成功!','url'=>$this->createPLuginWebUrl('activity/article')));
            }else{
                show_json(0,array('msg'=>'添加失败!','url'=>$this->createPLuginWebUrl('activity/article')));
            }       

        }else{
            if ($ac == 'clone') {
                $data['uid']=$muser['uid'];         
                $data['uniacid']=$_W['uniacid'];
                $data['openid']=$muser['openid'];                        
                pdo_insert('sz_yi_activity_article',$data);
                $re=pdo_insertid();                                
            }else{                                  
                $re=pdo_update('sz_yi_activity_article',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
            }       
                        
            if ($re) {
                show_json(1,array('msg'=>'更新成功!','url'=>$this->createPLuginWebUrl('activity/article')));
            }else{      
                show_json(0,array('msg'=>'更新失败!','url'=>$this->createPLuginWebUrl('activity/article')));
            }

        }

    }
}else if($op == 'preview'){
    $id=intval($_GPC['atid']);           
    $mobile=trim($_GPC['mobile']);       

    if (empty($id) || empty($mobile)) {
        show_json(0,'非法参数!');                    
    }

    $member=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and mobile  = :mobile ',array(':uniacid'=>$_W['uniacid'],':mobile'=>$mobile));
    $act=m('activity')->getact($id,2);              
    !$member && show_json(0,'找不到该手机所属会员');
    $template = array(
            array(
                'title' => '客服提醒通知', 
                'value' =>'文章预览'
            ),          
            array(
                'title' => '客户名称 ', 
                'value' =>$member['nickname']    
            ),
            array(
                'title' => '客户类型 ', 
                'value' =>''
            ),
            array(
                'title' => '提醒内容 ', 
                'value' =>$act['title']
            ),
            array(
                'title' => '通知时间', 
                'value' =>date('Y-m-d H:i:s')
            ),
            array(
                'title' => '摘要 ', 
                'value' =>$act['desc']
            ),
        );    
                                     
    $url=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$id));            
    $re=m('message')->sendCustomNotice($member['openid'], $template,$url);   

    if ($re['errcode'] == 0) {                          
        show_json(1,'发送成功！');                       
    }else{       
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }           
}else if ($op == 'delete'){
    $id=intval($_GPC['id']);
    $article=m('activity')->getact($id,2);      

    $re=pdo_delete('sz_yi_activity_article',array('id'=>$id,'uniacid'=>$_W['uniacid']));
    if ($re) {                                  
        show_json(1,'删除成功！');                              
    }else{               
        show_json(0,'删除失败!');
    }              
}else if($op == 'team'){
    $id=intval($_GPC['id']); 
    $act=m('activity')->getact($id);
                    
    $template = array(
            array(
                'title' => '客服提醒通知', 
                'value' =>''
            ),          
            array(
                'title' => '客户名称 ', 
                'value' =>$member['nickname']
            ),
            array(
                'title' => '客户类型 ', 
                'value' =>''
            ),
            array(
                'title' => '提醒内容 ', 
                'value' =>'现场易货交流会:产品交流会，各大企业都可以带上自己的产品，现场进行卖货，换货，资源互推，招商加盟'
            ),
            array(
                'title' => '通知时间', 
                'value' =>date('Y-m-d H:i:s')
            ),
            array(
                'title' => '摘要 ', 
                'value' =>''
            ),
        );    
                                     
    $url=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$id));   
    $oplist = pdo_fetchall('select openid from '.tablename('sz_yi_member').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid'])); 
    foreach ($oplist as $key => $value) {
        
    }        
    $re=m('message')->sendCustomNotice($member['openid'], $template,$url); 
    if ($re['errcode'] == 0) {           
        show_json(1,'发送成功！');            
    }else{       
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }

}else if($op == 'release'){
    $id=intval($_GPC['id']);
    $re=pdo_update('sz_yi_activity_article',array('status'=>1),array('id'=>$id,'uniacid'=>$_W['uniacid']));
    if ($re) {
        show_json(1,'发布成功!');
    }       
    show_json(1,'发布失败!');
}

include $this -> template('article');    