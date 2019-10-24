<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$plugin_diyform = p('diyform');             
$muser=m('match')->getMuser($_W['uid']);
$totals = array();
if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':openid'=>$muser['openid']);
    $condition=' and uniacid = :uniacid and openid = :openid ';
     	
    if ($_GPC['adsn']) {                 
        $condition .= ' and adsn like :adsn';                
        $params[':adsn'] = "%{$_GPC['adsn']}%";                                   
    }       
    
    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_match').' where 1 '.$condition;
    $sql.=' order by id desc ';
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_match'). " where 1 {$condition} ", $params);
    
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $list = pdo_fetchall($sql, $params);
    foreach ($list as $key => $value) {
    	$html='<tr>';
    	$list[$key]['noticeList']=unserialize($value['noticeList']);
        $list[$key]['signup']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id'])); 	 	
    	$temparr=m('match')->trArrayKey($list[$key]['noticeList']);     
    	$list[$key]['info']=json_encode($temparr);	 	 	         
        if ($value['stime'] > time()) {                  
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    }
    $pager = pagination($totals, $pindex, $psize);

    $sgPoster=pdo_fetchall('select * from '.tablename('sz_yi_match_poster').' where uniacid = :uniacid and type = 1 and enabled = 1 order by displayorder desc limit 0,2',array(':uniacid'=>$_W['uniacid']));            
    
}else if ($op == 'add'){
    $id=intval($_GPC['id']);
    $info=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
    $match=m('match')->getact($id,1);
    $match['thumbs']=unserialize($match['thumbs']);

    // $audit_log=pdo_fetchall('select * from '.tablename('sz_yi_match_log').' where uniacid = :uniacid and actid = :id order by id desc ',array(':id'=>$id,':uniacid'=>$_W['uniacid']));

    // if ($match && $match['openid'] != $muser['openid']) {
    //     message('没有这条比赛!','','error');
    // }                         
    $match['field']=unserialize($match['field']);
    
    $cate=pdo_fetchall('select * from '.tablename('sz_yi_match_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));
    if ($_W['isajax']) {  
        $data=$_GPC['data'];        
        $diy=$_GPC['diy'];                                   
        $data['openid']=$muser['openid'];                       
        $data['uniacid']=$_W['uniacid'];                                
        $data['uid']=$_W['uid'];
        foreach ($data['cover'] as $key => $value) {
            $thumbs[]=$value;                   
        }                    
        $data['cover']=$data['cover'][0];                   
        $data['thumbs']=serialize($thumbs);  //包括首图也在                                        
        $data['field']=serialize($diy);                    
        $data['stime']=strtotime($_GPC['time']['start']);
        $data['etime']=strtotime($_GPC['time']['end']);
        $data['votestime']=$data['stime'];
        $data['voteetime']=$data['etime'];

        $data['province']=$_GPC['reside']['province'];
        $data['city']=$_GPC['reside']['city'];
        $data['area']=$_GPC['reside']['district'];                      

        $data['status']=1;               

        // $logid=pdo_fetchcolumn('select id from '.tablename('sz_yi_match_log').' where uniacid = :uniacid and actid = :actid and status = 0',array(':uniacid'=>$_W['uniacid'],':actid'=>$id)); 
                 
        // $goodslog=[ 
        //     'uniacid'=>$_W['uniacid'],  
        //     'uid'=>$_W['uid'],
        //     'actid'=>$_GPC['id'], 
        //     'sub_time'=>time(),
        //     'status'=>0 
        // ]; 



        if (empty($id)) {

            $data['ctime']=time();
			$author=m('match')->getMuser($_W['uid']);
			$muser=m('member')->getMember($author['openid']);
        	pdo_insert('sz_yi_match',$data);
        	$id=pdo_insertid();
      
            // $goodslog['actid']=$id;    
            // pdo_insert('sz_yi_match_log',$goodslog);
            
            if ($id) {
            	show_json(1,'添加成功');
        	}                      
        	show_json(0,'添加失败');
        }else{

            if ($_GPC['ac'] == 'clone') {
                $data['ctime']=time();
                pdo_insert('sz_yi_match',$data);
                $id=pdo_insertid();     

                $goodslog['actid']=$id;    
                pdo_insert('sz_yi_match_log',$goodslog); //记录日志

                if ($id) {         
                    show_json(1,'复制成功!!!');       
                }       
                    show_json(0,'添加失败');
            }else{
                
                $re=pdo_update('sz_yi_match',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));      
 	 		 	
                // if ($logid) { 
                //     pdo_update('sz_yi_match_log',$goodslog,array('id'=>$logid));
                // }else{  
                //     pdo_insert('sz_yi_match_log',$goodslog);
                // }	 	 	 


                if ($re) {                                
                    show_json(1,array('修改成功!'));       
                }                                                                                                 
                    show_json(0,array('修改失败!'));       
                                                
            }
        	
        }
    }

}else if($op == 'comment'){
    $muser=m('match')->getMuser($_W['uid']);
    !$muser && message('你还没有注册!请先注册!','','error');
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $condition='';
    $sql='select c.*,a.title,m.nickname from '.tablename('sz_yi_match_comment').' c left join '.tablename('sz_yi_match').' a on a.id = c.atid left join '.tablename('sz_yi_member').' m on m.openid = c.openid where c.type = 1 and c.uniacid = :uniacid and a.openid = :openid';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $params=array(      
        ':uniacid'=>$_W['uniacid'],
        ':openid'=>$muser['openid']
    );      

    $list=pdo_fetchall($sql,$params);

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_comment').' c left join '.tablename('sz_yi_match').' a on a.id = c.atid left join '.tablename('sz_yi_member').' m on m.openid = c.openid where c.type = 1 and c.uniacid = :uniacid and a.openid = :openid',$params);

    $pager = pagination($totals, $pindex, $psize);

}else if($op == 'signup'){
    $id = intval($_GPC['id']);
    $muser=m('match')->getMuser($_W['uid']);                                
    $match=m('activity')->getact($id,5);
    $match['field']=unserialize($match['field']);   

    !$muser && message('你还没有注册!请先注册!','','error');

    if ($ac == 'audit') {
        $id=intval($_GPC['id']); 
        $data=array(
            'status'=>$_GPC['check']
        );
        
        $re=pdo_update('sz_yi_match_signup',$data,array('uniacid'=>$_W['uniacid'],'id'=>$id));
                    
        if ($re) {   
            message('审核成功!','','success');
        }
        message('审核失败!','','error');
    }
    $id=$_GPC['id'];
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $condition='';       
    $sql='select s.*,a.title from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;              
    $params=array(                      
        ':uniacid'=>$_W['uniacid'],               
        ':id'=>$id     
    );      
        
    $list=pdo_fetchall($sql,$params);                                   

    foreach ($list as $key => $value) {
        $tinfo=unserialize($value['data']);
        $list[$key]=array_merge($value,$tinfo);
    }                       

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id',$params);
    $pager = pagination($totals, $pindex, $psize);


}else if($op == 'statistics'){
    
    // var_dump($field);
    // exit;
    // {php $field='a:4:{s:4:"name";a:3:{s:5:"title";s:6:"名称";s:3:"num";s:1:"1";s:4:"must";s:1:"1";}s:6:"mobile";a:3:{s:5:"title";s:12:"手机号码";s:3:"num";s:1:"2";s:4:"must";s:1:"1";}s:6:"slogan";a:3:{s:5:"title";s:6:"口号";s:3:"num";s:1:"3";s:4:"must";s:1:"0";}s:4:"desc";a:3:{s:5:"title";s:6:"简介";s:3:"num";s:1:"4";s:4:"must";s:1:"0";}}';}
    // {php $field=unserialize($field);}
    $muser=m('match')->getMuser($_W['uid']);
    !$muser && message('你还没有注册!请先注册!','','error');

    $id=$_GPC['id'];
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;

    $condition='';       
    $sql='select s.*,a.title from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.signin > 0';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;              
    $params=array(                      
        ':uniacid'=>$_W['uniacid'],               
        ':id'=>$id     
    );      
        
    $list=pdo_fetchall($sql,$params);                                   

    foreach ($list as $key => $value) {
        $tinfo=unserialize($value['data']);
        $list[$key]=array_merge($value,$tinfo);
    }                       

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.signin > 0',$params);

    $pager = pagination($totals, $pindex, $psize);      

}else if($op == 'refund'){
    $muser=m('match')->getMuser($_W['uid']);
    !$muser && message('你还没有注册!请先注册!','','error');
    $id=$_GPC['id'];
    $pindex=max(1,intval($_GPC['page']));
    $psize=20;
 		 	 		 	 	 	 
    $condition='';       
    $sql='select s.*,a.title from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.paystatus = 2';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;              
    $params=array(                      
        ':uniacid'=>$_W['uniacid'],               
        ':id'=>$id     
    );      
        
    $list=pdo_fetchall($sql,$params);                                   

    foreach ($list as $key => $value) {
        $tinfo=unserialize($value['data']);
        $list[$key]=array_merge($value,$tinfo);
    }                       	 	 	

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.paystatus = 2',$params);

    $pager = pagination($totals, $pindex, $psize);      

}else if($op == 'delete'){               
    $id=intval($_GPC['id']); 
                        
    $act=m('match')->getact($id);
    $mu=m('match')->getMuser($_W['uid']);
    if (empty($act) || $mu['openid'] != $act['openid']) {
        show_json(0,'没有这条比赛');
    }

    $re=pdo_delete('sz_yi_match',array('id'=>$id,'uniacid'=>$_W['uniacid']));
    
    if ($re) {
        show_json(1,'删除成功');
    }

    show_json(1,'删除失败');

}else if($op == 'netmap'){       
    $id=intval($_GPC['id']);                                   
    $act=m('match')->getact($id);         
    $muser=m('match')->getMuser($act['openid']); 

    if ($ac == 'netmap') {
        $tree=pdo_fetchall('select id,pid pId,data from '.tablename('sz_yi_match_signup').' s where uniacid = :uniacid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        foreach ($tree as $key => $value) {     
            $tdate=unserialize($value['data']);
            unset($tree[$key]['data']);          
            $tree[$key]['name']=$tdate['realname']['data'].'('.m('match')->countChild($tree,$value['id']).')';                 
            $tree[$key]['title']='';
        }                                                                            

        $tree=m('match')->reSort($tree);                                  
        $tinfo=array(        
            'children_num'=>m('match')->countChild($tree,0),     
            'parent_num'=>0,       
            'sibling_num'=>0              
        );
        $org=array(      
            'name'=>$muser['orgName'],
            'title'=>'',                 
            'relationship'=>$tinfo,
            'children'=>$tree
        );              
    }else{      //树状

        $tree=pdo_fetchall('select id,pid pId,channel,data from '.tablename('sz_yi_match_signup').' s where uniacid = :uniacid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        
        $tcount=m('match')->countChild($tree,0);

        $tarr=array(
            'id'=>0,
            'pId'=>0,                 
            'open'=>true,
            'name'=>$muser['orgName'].'('.$tcount.')',
            'isParent'=>$tcount >0 ?true:false
        );                                        

        foreach ($tree as $key => $value) {         
            $tdata=unserialize($value['data']);                     
            unset($tree[$key]['data']);                          
            $count=m('match')->countChild($tree,$value['id']);
            $tree[$key]['open']=false;       
            $tree[$key]['isParent']=$count >0 ?true:false;   
            if ($value['channel'] == 0 && $value['pid'] == 0) {                              
                $tree[$key]['name']=$tdata['realname']['data'].'(比赛广场'.$count.')';
            }else{                                                                  
                $tree[$key]['name']=$tdata['realname']['data'].'(转发'.$count.')';               
            }           
        }                        
        array_push($tree,$tarr);        
    }
}else if($op == 'team'){
    $id=intval($_GPC['id']); 
    $act=m('match')->getact($id);
    $muser=m('match')->getMuser($_W['uid']);      
    $muser['msgnum'] <= 0 && show_json(0,'你的短信数量不足,无法群发');                 
    
    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id));   
    $oplist = pdo_fetchall('select openid from '.tablename('sz_yi_match_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$muser['uid']));                                                                                                 
    $url=$this->createPluginMobileUrl('match/match');                                              
    // $oplist=pdo_fetchall('select * from '.tablename('sz_yi_match_').);                 


    $msg = array(                
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(         
            'title' => '您关注的机构发布新比赛了 ',
            'value' => $act['title'],
            "color" => "#4a5077"
        ),                              
        'keyword2' => array(
            'title' => '消息类型',
            'value' =>  '比赛订阅',                                             
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
            'value' =>  '比赛群发',                                             
            "color" => "#4a5077"
        ),
        'keyword2' => array(         
            'title' => '提醒内容 ',
            'value' => $act['title'].'比赛已经通过'.$_W['setting']['copyright']['sitetitle'].'发送给你的'.count($oplist).'位粉丝',
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

}else if($op == 'audit'){    
    /*$id=intval($_GPC['id']); 
    $data=array(
        'status'=>$_GPC['check']
    );                       
    
    $re=pdo_update('sz_yi_match_comment',$data,array('uniacid'=>$_W['uniacid'],'id'=>$id,'type'=>$_GPC['type']));
    
    if ($re) {           
        message('审核成功!',$this->createPluginWebUrl('match/signin',array('op'=>'comment')),'success');
    }       
    message('审核失败!',$this->createPluginWebUrl('match/signin',array('op'=>'comment')),'error');*/
}else if($op == 'preview'){
    $id=intval($_GPC['atid']);
    $mobile=trim($_GPC['mobile']);

    if (empty($id) || empty($mobile)) {
        show_json(0,'非法参数!');                    
    }

    $member=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and mobile  = :mobile ',array(':uniacid'=>$_W['uniacid'],':mobile'=>$mobile));
    $act=m('match')->getact($id); 		
    !$member && show_json(0,'找不到该手机所属会员');
    // $muser['msgnum'] <= 0 && show_json(0,'你的短信数量不足,无法发送');              

    // $template = array(
    //         array(
    //             'title' => '客服提醒通知', 
    //             'value' =>'比赛预览'
    //         ),          
    //         array(
    //             'title' => '客户名称 ', 
    //             'value' =>$member['nickname']
    //         ),
    //         array(
    //             'title' => '通知时间', 
    //             'value' =>date('Y-m-d H:i:s')
    //         ),
    //         array( 		
    //             'title' => '摘要 ', 
    //             'value' =>$act['desc']
    //         ),
    //     );

        $msg = array(                
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(         
            'title' => '比赛预览 ',
            'value' => $act['title'],
            "color" => "#4a5077"
        ),                              
        'keyword2' => array(
            'title' => '客户名称',
            'value' => $member['nickname'],                                             
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

                                     
    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id));            
    $re=m('message')->sendTplNotice($member['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);                            

    if ($re['errcode'] == 0) {
        // pdo_update('sz_yi_member_user',array('msgnum'=>$muser['msgnum']-1),array('id'=>$muser['id']));               
        show_json(1,'发送成功！');                                                           
    }else{       
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }           
}else if($op == 'notice'){
    $id=intval($_GPC['atid']);
    $content=trim($_GPC['content']);
    $muser['msgnum'] <= 0 &&  show_json(0,'你的短信数量不足,无法发送');

    if (empty($id) || empty($content)) {
        show_json(0,'非法参数!');                    
    }

    $match=m('match')->getact($id);

    $msg = array(                
        'first' => array(
            'value' => "比赛变更提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(            
            'title' => '比赛标题 ',
            'value' => $match['title'],
            "color" => "#4a5077"
        ),                              
        'keyword2' => array(
            'title' => '变更内容',
            'value' => $content,                                             
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

    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id));

    $list=pdo_fetchall('select openid from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and status = 1 and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));           
    foreach ($list as $key => $value) {                              
        // $re=m('message')->sendCustomNotice($value['openid'], $template,$url); 
        $re=m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);                            
    }            
    if ($re['errcode'] == 0) {
        pdo_update('sz_yi_member_user',array('msgnum'=>$muser['msgnum']-1),array('id'=>$muser['id']));               
        show_json(1,'发送成功！');            
    }else{               
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }           
}else if($op == 'sinotice'){
    /*$id=intval($_GPC['id']);
    $mobile=trim($_GPC['mobile']);
    empty($id) && show_json(0,'非法参数');

    $act=m('match')->getact($id);
    empty($act) && show_json(0,'没有这条比赛!');
    $noticeList=unserialize($act['noticeList']);

    if ($ac == 'add') {
        foreach ($noticeList as $key => $value) {
            if ($value == $mobile) {
                show_json(0,'该提醒手机号码已经存在');
            }
        }
        $checkM=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and mobile = :mobile',array(':uniacid'=>$_W['uniacid'],':mobile'=>$mobile));
        empty($checkM) && show_json(0,'没有该手机号码!');  

        $checkMu=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$checkM['openid']));       
        empty($checkMu) && show_json(0,'该手机号码未注册易比赛');  

        $noticeList[]=$mobile;      
        $data=array(     
            'noticeList'=>serialize($noticeList)
        );
        $release=m('member')->getMember($act['openid']);
        $re=pdo_update('sz_yi_match',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
        $str='添加';

    }else if($ac == 'del'){
        foreach ($noticeList as $key => $value) {
            if ($value == $mobile) {
                unset($noticeList[$key]);
            }
        }
        $data=array(
            'noticeList'=>serialize($noticeList)
        );
        $re=pdo_update('sz_yi_match',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
        $str='删除';
    }

    if ($re) {
        show_json(1,$str.'成功!');
    }       

    show_json(0,$str.'失败!');*/
}else if($op == 'getlist'){
    /*$id=intval($_GPC['id']);
    $act=m('match')->getact($id);
    $act['noticeList']=unserialize($act['noticeList']);
    $html='<tr>';   
    foreach ($act['noticeList'] as $key => $value) {
        $html.='<td>'.$value.'</td><td><label data-mobile="'.$value.'" data-id="'.$id.'" class="sgdel label label-danger">删除</label></td>';         
    }       
    $html.='</tr>';
    show_json(1,$html);*/  
}else if($op == 'poster'){
    /*$id=$_GPC['act_id'];
    $minfo=m('member')->getMember($openid); 

    if ($_GPC['what'] == 1) {
        $posterid=6;                                    
        $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('match/center',array('op'=>'signin','id'=>$id,'mid'=>$minfo['id'])));                             
    }else {                      
        $posterid=9;                                                            
        $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id,'mid'=>$minfo['id'])));                      
    }                                                                           
    exit(html_entity_decode('<img src="'.$pt.'" width="320px" height="auto" style="position: absolute;left: 50%;margin-left: -160px;top: 20%;" />'));*/
}else if($op == 'update'){
    $id=intval($_GPC['id']);         
    $match=m('activity')->getact($id,5);
    $match['field']=unserialize($match['field']);
    
    $sgid=intval($_GPC['sgid']);

    if ($ac == 'post') {

        $master=$_GPC['orther'];
        $master['uniacid']=$_W['uniacid'];
        $master['actid']=$id;
        $master['ctime']=time();


        $data=$_GPC['data'];        
        $data['uniacid']=$_W['uniacid'];
        $data['data']=serialize($data);
        foreach ($_GPC['thumbs'] as $key => $value) {
            $thumbs[]=$value;                                                   
        }                                                      	 	 	                                                       
        $data['thumbs']=serialize($thumbs); 	 				 		

        if ($sgid) {
            $re=pdo_update('sz_yi_match_signup',$master,array('id'=>$sgid,'uniacid'=>$_W['uniacid']));

            pdo_update('sz_yi_match_signup_data',$data,array('sgid'=>$sgid,'uniacid'=>$_W['uniacid']));
        
        }else{
            pdo_insert('sz_yi_match_signup',$master);
            $re=pdo_insertid();
            $data['sgid']=$re;                          
            pdo_insert('sz_yi_match_signup_data',$data);
        }
    
        if ($re) {
            show_json(1,'更新成功！');        
        }                                    
        show_json(1,'更新失败！');        
    }                                                                      
}                                   
     
include $this -> template('match');