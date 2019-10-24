<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac =$_GPC['ac'];

$plugin_diyform = p('diyform');
$totals = array();
$muser=m('activity')->getMuser($_W['uid']);

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':openid'=>$muser['openid']);
    $condition=' and uniacid = :uniacid and openid = :openid and status = 1';       
        
    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_activity_article').' where 1 '.$condition;
    $sql.=' order by id desc ';
    
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity_article'). " where 1 {$condition} ", $params);
    
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

    $info=m('tools')->getAccountInfo($article['openid']);        

    if ( $article && $article['openid'] != $muser['openid']) {
        message('没有这篇文章!','','error');
    }                                    

    if ($_W['isajax']) {
        
        $data=$_GPC['data'];        
        $data['province']=$_GPC['reside']['province'];      
        $data['city']=$_GPC['reside']['city'];      
        $data['area']=$_GPC['reside']['district'];   
        if (empty($id)) {              
            $data['uid']=$_W['uid'];
            $data['uniacid']=$_W['uniacid'];
            $data['openid']=$muser['openid'];
            $data['ctime']=time();

            // $oldart=m('activity')->getact($id,2);
            if ($muser['level'] == 3) {
                // if ($oldart['is_top'] <= 2) {
                    $data['is_top']=2;
                    $data['toptime']=time();
                // }
            }

            pdo_insert('sz_yi_activity_article',$data);
            $id=pdo_insertid();                       

            if ($id) {                             
                show_json(1,array('msg'=>'添加成功!','url'=>$this->createPLuginWebUrl('activity/article')));
            }else{
                show_json(0,array('msg'=>'添加失败!','url'=>$this->createPLuginWebUrl('activity/article')));
            }       

        }else{
            if ($ac == 'clone') {
                $data['uid']=$_W['uid'];
                $data['uniacid']=$_W['uniacid'];
                $data['openid']=$muser['openid'];
                $data['ctime']=time();
                // $oldart=m('activity')->getact($id,2);
                if ($muser['level'] == 3) {
                    // if ($oldart['is_top'] <= 2) {
                        $data['is_top']=2;
                        $data['toptime']=time();
                    // }
                }                        
                pdo_insert('sz_yi_activity_article',$data);
                $re=pdo_insertid();                                
            }else{
                $oldart=m('activity')->getact($id,2);
                if ($muser['level'] == 3) {
                    if ($oldart['is_top'] <= 2) {
                        $data['is_top']=2;
                        $data['toptime']=time();
                    }
                }                                  
                $re=pdo_update('sz_yi_activity_article',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
            }       
                        
            if ($re) {
                show_json(1,array('msg'=>'更新成功!','url'=>$this->createPLuginWebUrl('activity/article')));
            }else{      
                show_json(0,array('msg'=>'更新失败!','url'=>$this->createPLuginWebUrl('activity/article')));
            }

        }

    }
}else if($op == 'draft'){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':openid'=>$muser['openid']);
    $condition=' and uniacid = :uniacid and openid = :openid and status = 2 ';
    
    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_activity_article').' where 1 '.$condition;
    $sql.=' order by id desc ';
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity_article'). " where 1 {$condition} ", $params);
    
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
}else if($op == 'comment'){
    $muser=m('activity')->getMuser($_W['uid']);
    !$muser && message('你还没有注册!请先注册!',referer(),'error');
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $condition='';
    $sql='select c.*,a.title,m.nickname from '.tablename('sz_yi_activity_comment').' c left join '.tablename('sz_yi_activity_article').' a on a.id = c.atid left join '.tablename('sz_yi_member').' m on m.openid = c.openid where c.type = 2 and c.uniacid = :uniacid and a.openid = :openid';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $params=array(      
        ':uniacid'=>$_W['uniacid'],
        ':openid'=>$muser['openid']
    );      

    $list=pdo_fetchall($sql,$params);

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_comment').' c left join '.tablename('sz_yi_activity_article').' a on a.id = c.atid left join '.tablename('sz_yi_member').' m on m.openid = c.openid where c.type = 2 and c.uniacid = :uniacid and a.openid = :openid',$params);

    $pager = pagination($totals, $pindex, $psize);
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
    $article['uid'] != $_W['uid'] && show_json(0,'没有该篇文章');

    $re=pdo_delete('sz_yi_activity_article',array('id'=>$id,'uniacid'=>$_W['uniacid']));
    if ($re) {                                  
        show_json(1,'删除成功！');                              
    }else{               
        show_json(0,'删除失败!');
    }              
}else if($op == 'team'){
    $id=intval($_GPC['id']); 
    $act=m('activity')->getact($id,2);
    $muser=m('activity')->getMuser($_W['uid']);      
    // $muser['msgnum'] <= 0 && show_json(0,'你的短信数量不足,无法群发');                 
        
    $url=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$id));   
    $oplist = pdo_fetchall('select openid from '.tablename('sz_yi_activity_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$muser['uid']));                                                                                                 
    $url=$this->createPluginMobileUrl('activity/article');                                              
    // $oplist=pdo_fetchall('select * from '.tablename('sz_yi_activity_').);                 


    $msg = array(                
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(         
            'title' => '您关注的机构发布新文章了 ',
            'value' => $act['title'],
            "color" => "#4a5077"
        ),                              
        'keyword2' => array(
            'title' => '消息类型',
            'value' =>  '文章订阅',                                             
            "color" => "#4a5077"
        ),
        'keyword3' => array(            
            'title' => '通知时间',    
            'value' =>  date('Y-m-d H:i:s'),         
            "color" => "#4a5077"             
        ),      
        'remark' => array(           
            'value' => "\r".$act['desc'],
            "color" => "#4a5077"
        )
    );
         

    $ntc = array(                
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(
            'title' => '消息类型',
            'value' =>  '文章群发',                                             
            "color" => "#4a5077"
        ),
        'keyword2' => array(         
            'title' => '提醒内容 ',
            'value' => $act['title'].'文章已经通过'.$_W['setting']['copyright']['sitetitle'].'发送给你的'.count($oplist).'位粉丝',
            "color" => "#4a5077"
        ),                                                  
        'keyword3' => array(            
            'title' => '通知时间',    
            'value' =>  date('Y-m-d H:i:s'),         
            "color" => "#4a5077"             
        ),      
        'remark' => array(                           
            'value' => "\r".$act['desc'],
            "color" => "#4a5077"
        )       
    );
                                                     
    foreach ($oplist as $key => $value) {          
        $ret = m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);
    }                                           
       m('message')->sendTplNotice($muser['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$ntc, $url);

    if ($ret['errcode'] == 0) {
        pdo_update('sz_yi_member_user',array('msgnum'=>$muser['msgnum']-1),array('id'=>$muser['id']));               
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