<?php

global $_W, $_GPC;
$openid = m('user')->getOpenid();
$op = empty($_GPC['op']) ? 'display': $_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid);
$member=m('member')->getMember($openid);
if (!$muser) {
    // m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));           
}                        
if ($op == 'display') {  
    if ($_GPC['debug']) {
        $set=m('tools')->getset()['bartact'];
        var_dump($set);
        exit;
    }
    $id=$_GPC['mmid']?:$member['id'];
    $heMember=m('member')->getMember($id);
    $yesteday=strtotime('-1 day');
    $yesteday=date('Ymd',$yesteday);
        
    $tedaycount=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid and date = :date',array(':uniacid'=>$_W['uniacid'],':date'=>date('Ymd')));

    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));      

    // 日访量统计      
    m('match')->borwseStatis($heMember,$openid);         
    // $tedayBorwse=m('match')->getBStatis($heMember['id'],true);
    $tedayBorwse=rand(1,300000);
    $yestdayPeople=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid and date = :date',array(':uniacid'=>$_W['uniacid'],':date'=>$yesteday));
    $tedayPeople=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid and date = :date',array(':uniacid'=>$_W['uniacid'],':date'=>date('Ymd')));       


    $max=pdo_fetchcolumn('select continuous from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid and openid = :openid and date = :date',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid'],':date'=>date('Ymd')));      
    $max=$max?:0;

    $minfo['like']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_like').' where uniacid = :uniacid and atid = :id and type = 3',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    $minfo['browse']=pdo_fetchcolumn('select browse from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid']));  

    $minfo['reward']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_reward').' where uniacid = :uniacid and atid = :id and type = 3',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    $like=pdo_fetchall('select m.avatar from '.tablename('sz_yi_match_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.atid = :id and al.type = 3 order by al.id desc limit 0,32 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 

    $comment=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ac.content from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 3 and ac.status = 1 order by ac.id desc limit 0,3 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    // if ($_GPC['debug']) {
    //     var_dump($comment);
    //     exit;
    // }
    $reward=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ar.money,ar.remark from '.tablename('sz_yi_match_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where ar.uniacid = :uniacid and ar.atid = :id and ar.type = 3 order by ar.id desc limit 0,3 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    if ($ac == 'signin') {
        $sets=m('tools')->getset();
        $bartact=$sets['bartact'];
        if (empty($bartact['signin'])) {
            show_json(0,'打卡暂未开启!!');
        }                        
        m('tools')->signin($openid);    //签到
    }else if($ac == 'get'){   
        $pindex=max(1,intval($_GPC['page']));
        $psize=5;            
        if ($_GPC['status'] == 1) {
            $mysign=pdo_fetch('select s.*,m.avatar,m.nickname from '.tablename('sz_yi_match_signin').' s  left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.openid = :openid and s.date = :date',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid'],':date'=>date('Ymd')));                              
            $mysign=$mysign?:array();
            if ($mysign) {           
                $mysign['ctime']=date('Y-m-d H:i:s',$mysign['ctime']);       
           }           
            $sql='select s.*,m.avatar,m.nickname from '.tablename('sz_yi_match_signin').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.date = :date order by s.id asc ';
            $params=array(
                ':uniacid'=>$_W['uniacid'],
                ':date'=>date('Ymd')
            );
        }else if($_GPC['status'] == 2){
            $mysign=pdo_fetch('select openid,max(continuous) continuous,sum(score) score,sum(bonus) bonus from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid and openid = :openid group by openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid']));          

            if ($mysign) {
                $mysign['avatar']=$heMember['avatar'];       
                $mysign['nickname']=$heMember['nickname'];      
                $mysign['continuous'] +=1;       
            }

            $sql='select openid,max(continuous) continuous,sum(score) score,sum(bonus) bonus from '.tablename('sz_yi_match_signin').' where uniacid = :uniacid group by openid order by continuous desc';   
            $params=array(                      
                ':uniacid'=>$_W['uniacid']                     
            );          
        }else if($_GPC['status'] == 3){
             $sql='select s.*,m.avatar,m.nickname from '.tablename('sz_yi_match_signin').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.openid = :openid order by s.id desc ';    
            $params=array(                              
                ':uniacid'=>$_W['uniacid'],      
                ':openid'=>$heMember['openid']      
            );               
        }    
        $sql.=' limit '.($pindex - 1) * $psize .','.$psize;
        $list=pdo_fetchall($sql,$params);
        if ($list) {
            foreach ($list as $key => $value) {             
                $list[$key]['no']=$pindex * $psize -  $psize + 1 + $key;
                if ($_GPC['status'] == 2) {
                    $tmember=m('member')->getMember($value['openid']);       
                    $list[$key]['avatar']=$tmember['avatar'];                                
                    $list[$key]['link']=$this->createPluginMobileUrl('match/center',array('mmid'=>$tmember['id']));
                    $list[$key]['continuous'] +=1;       
                }               
                if ($_GPC['status'] == 3) {
                    $list[$key]['ctime']=date('m-d H:i:s',$value['ctime']);
                }else{
                   $list[$key]['ctime']=date('H:i:s',$value['ctime']); 
                }
                
            }           
            if ($_GPC['status'] == 1 || $_GPC['status'] == 2 && $pindex == 1) {
                show_json(1,array('list'=>$list,'mysign'=>$mysign,'pagesize'=>$psize));
            }
            show_json(1,array('list'=>$list,'pagesize'=>$psize));
        }
        show_json(1,array('list'=>array(),'pagesize'=>$psize));
    }
}else if($op == 'report'){          //举报

    $report_type=pdo_fetchall('select * from '.tablename('sz_yi_report_type').' where uniacid = :uniacid and status = 1 order by display desc ',array(':uniacid'=>$_W['uniacid']));
        if ($_W['isajax']) {
            $type=intval($_GPC['type']);            
            $id=intval($_GPC['id']);
            $act=m('activity')->getact($id,$type);
                            
            switch ($type) {
                case '1':
                    $obj='活动';
                    break;
                case '2':
                    $obj='文章';
                    break;
                case '3':
                    $obj='用户';
                    break;
                case '4':
                    $obj='图片';
                    break;
                case '5':
                    $obj='比赛';
                    break;
                                        
                default:
                    # code...
                    break;
            }
            $log=array(                         
                'uniacid'=>$_W['uniacid'],
                'openid'=>$openid,
                'atid'   =>$id,                                              
                'uid'    =>$act['uid'],
                'objtype'=>$type,         
                'type'   =>$_GPC['report_type'],
                'remark' =>trim($_GPC['content']),
                'ctime'  =>time()
            );        
                                         
            $params=array(                                
                ':uniacid'=>$_W['uniacid'],
                ':openid'=>$openid,
                ':stime'=>$stime,
                ':atid'=>$id,
                ':etime'=>$etime
            );      

            $repid=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_report_log'). ' where uniacid = :uniacid and openid = :oepnid and atid = :atid and ctime > :stime and ctime < :etime',$params);            
            if ($repid) {
                show_json(0,'举报失败，你今天已经举报过该'.$obj);   
            }

            pdo_insert('sz_yi_activity_report_log',$log);                
            if (pdo_insertid()) {
                show_json(1,'举报成功!');
            }else{
                show_json(0,'举报失败!');
            }

    }           

    include $this->template('report'); 
    exit;    
}else if($op == 'reward'){

    $dashangstatus=pdo_fetchcolumn("select status from  ".tablename('sz_yi_plugin')." where    identity  ='dashang'");if($dashangstatus != 1){ 	m('tools')->tip('打赏功能暂时关闭');	}

    $id=intval($_GPC['id']);
	$sgid =intval($_GPC['sgid']);
	$type=intval($_GPC['type']);

    empty($id) && m('tools')->tip('非法参数!');
	empty($type) && m('tools')->tip('非法参数!');
	// $type > 2 && m('tools')->tip('非法参数!');

    $at=m('activity')->getact($id,5);

    $muser=m('activity')->getMuser($at['openid']);         
    $player=m('match')->getMatchPlayer($id,$sgid);

                            
    

	if ($ac == 'sub') {        


        pdo_update('sz_yi_match_signup',array('reward'=>$player['reward']+1),array('id'=>$player['id'],'uniacid'=>$_W['uniacid']));

        if ($at['ctime'] < time() && $at['etime'] > time()) {                            
            $money=floor($_GPC['money']);
            $votenum=intval($at['rewardvote']) * $money;    //一元打赏投票数
            if ($votenum) {
                pdo_update('sz_yi_match_signup',array('vote'=>$player['vote']+$votenum),array('id'=>$player['id'],'uniacid'=>$_W['uniacid']));
                $votedata=array(                                
                    'uniacid'=>$_W['uniacid'],
                    'atid'=>$id,        //match id
                    'sgid'=>$sgid,      //member id
                    'openid'=>$openid,                      
                    'money'=>floatval($_GPC['money']),
                    'number'=>$votenum,              
                    'type'=>2,  //1投票 2 打赏
                    'ctime'=>time()                                 
                );                                                                      
                pdo_insert('sz_yi_match_vote',$data);
            }                       
        } 

		$data=array(          
			'uniacid'=>$_W['uniacid'],
			'openid'=>$openid,
            'type'=>$type,
			'paytype'=>2,
            'atid'=>$id, //member id 
			'sgid'=>$sgid, //member id 
			'money'=>floatval($_GPC['money']),
            'uniacid'=>$_W['uniacid'],
			'remark'=>$_GPC['message'],
            'ctime'=>time()
		);                            

		pdo_insert('sz_yi_match_reward',$data);
                                    
		$id=pdo_insertid();                                                   
		if ($id) {                                        //type 6 比赛
			p('commission')->calcPlayerReward($openid,6,$sgid,$data['money'],$id);
            m('member')->setCredit($openid,'credit2',-$data['money']);                     
            show_json(1,'打赏成功!');                       
		}
		show_json(0,'打赏失败');               

	}      
                 
	include $this->template('reward');	//	打赏
	exit;
}else if($op == 'apply'){ 

    if (!$muser) {
        m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));           
    }
    if ($_W['isajax']) {
        if ($ac == 'get') {
            $id=intval($_GPC['id']);
            $pindex=max(1,intval($_GPC['page']));
            $psize=5;

            $params=array(
                ':uniacid'=>$_W['uniacid']
            );
            if (empty($id)) {
                $params[':openid']=$openid;
                $sql='select s.* from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').'  a on a.id = s.actid  where s.uniacid = :uniacid and a.openid = :openid and s.deleted = 0 ';
                $sql.=' order by s.id desc ';
            }else{
                $type=$_GPC['status'];
                $condition=' and uniacid = :uniacid and actid = :id and deleted = 0  ';
                if ($type == 1) {                            
                    $condition.=' and openid = :openid';
                    $params[':openid']=$openid;
                }else if ($type == 2) {       
                    $condition.=' and fmid = :mid';
                    $tmember=m('member')->getMember($openid);
                    $params[':mid']=$tmember['id'];              
                }                   
                    $params[':id']=$id;                            
                    $sql='select * from '.tablename('sz_yi_match_signup').' where 1 ';             
                    $sql.=$condition;                                  
                    $sql.=' order by id desc ';  
            }                            

            $sql.=' limit '.($pindex -1) * $psize.' , '.$psize;
            $list=pdo_fetchall($sql,$params);       
            if ($list) {         
                foreach ($list as $key => $value) {
                    $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
                    $list[$key]['info']=unserialize($value['data']);
                    $list[$key]['data']=json_encode(array('info'=>unserialize($value['data'])));
                }
                show_json(1,array('list'=>$list));
            }
            $list=array();       
            show_json(0,array('list'=>$list));
        }else if($ac == 'getRefund'){
            $id=intval($_GPC['id']);
            $pindex=max(1,intval($_GPC['page']));
            $psize=5;

            $params=array(
                ':uniacid'=>$_W['uniacid'],
                ':id'=>$id
            );

            $sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0 and paystatus = 2';         
                                     
            $sql.=' order by id desc ';
            $sql.=' limit '.($pindex -1) * $psize.' , '.$psize;
            $list=pdo_fetchall($sql,$params);        
            if ($list) {
                foreach ($list as $key => $value) {
                    $list[$key]['refundtime']=date('Y-m-d H:i:s',$value['refundtime']);
                    $list[$key]['info']=unserialize($value['data']);
                    $list[$key]['data']=json_encode(array('info'=>unserialize($value['data'])));
                }
                show_json(1,array('list'=>$list));
            }
            $list=array();
            show_json(0,array('list'=>$list));
        }


        if ($ac == 'audit') {
            
            $actid=intval($_GPC['atid']);
            $sgid=intval($_GPC['userid']);

            if (empty($actid) || empty($sgid) ) {
                show_json(0,array('msg'=>'非法参数!'));         
            }

            $re=pdo_update('sz_yi_match_signup',array('status'=>$_GPC['isok'],'sgtime'=>time()),array('uniacid'=>$_W['uniacid'],'actid'=>$actid,'id'=>$sgid,'deleted'=>'0'));              
                
            if ($re) {      
                show_json(1,array('msg'=>'处理成功!'));     
            }                       
            show_json(0,array('msg'=>'处理失败!'));         
        }           
    }

	include $this->template('apply');	//	报名名单 和审核写一起
	exit;
}else if($op == 'poster'){
    $id=$_GPC['act_id'];                                               
    $minfo=m('member')->getMember($openid);         
    if ($_GPC['what'] == 1) {
        $posterid=6;                                    
        $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('match/center',array('op'=>'signin','id'=>$id,'mid'=>$minfo['id'])));                             
    }else {                      
        $posterid=9;                                                                                                        
        $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id,'mid'=>$minfo['id'])));                      
    }                                                                           
    exit(html_entity_decode('<img src="'.$pt.'" width="100%" height="auto" />'));
}else if($op == 'signin'){                          

    $atid=intval($_GPC['id']);

    $act=m('match')->getSignUp($atid,$openid,' and deleted = 0 and status = 1 ');
        
        // http://jhzh66.com/app/index.php?i=8&c=entry&method=match&p=match&op=apply&id=260&m=sz_yi&do=plugin
    !$act && m('tools')->tips('你还没有报名或该报名审核没有通过',$this->createPluginMobileUrl('match/match',array('op'=>'apply','id'=>$atid)));

    $no=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and signin <> 0',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));  
    $no=$no?$no+1:1;
    $data=array(
        'sgtime'=>time(),
        'signin'=>$no
    );                   

    $match=m('match')->getact($act['actid']);          

    if (intval($act['signin'] ) > 0) {                       
        $thisno=$act['signin'];             
        $sstr='签到失败,重复签到!';          
        $sgtime=$act['sgtime'];                  
    }else{
        $re=pdo_update('sz_yi_match_signup',$data,array('uniacid'=>$_W['uniacid'],'id'=>$act['id'],'actid'=>$atid,'openid'=>$openid));                       
        $sstr='恭喜你，签到成功!';
        $thisno=$no;             
        $sgtime=time();
    }

    $actitem=pdo_fetch('select * from '.tablename('sz_yi_match_payitem').' where uniacid = :uniacid  and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$act['item']));

    $msg = array(                
        'first' => array(
            'value' => $sstr,       
            "color" => "#4a5077"        
        ),
        'keyword1' => array(
            'value' =>  $match['title'],                                             
            "color" => "#4a5077"                                 
        ),           
        'keyword2' => array(                         
            'value' =>date('Y年m月d日 H:i',$match['stime']),      
            "color" => "#4a5077"                                         
        ),
        'keyword3' => array(            
            'value' =>$actitem['title'],                    
            "color" => "#4a5077"             
        ),                                                           
        'keyword4' => array(            
            'value' =>'你是第'.$thisno.'个签到的人',                    
            "color" => "#4a5077"             
        ),
        'keyword5' => array(            
            'value' =>date('Y年m月d日 H:i',$sgtime),                    
            "color" => "#4a5077"             
        ),      
        'remark' => array(                           
            'value' => "\r".$act['desc'],                   
            "color" => "#4a5077"            
        )
    );                                       

    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$_GPC['actid']));
    $re = m('message')->sendTplNotice($openid,'d873CYt1NI68xWB2on9JepLcVJLzGCxPxlsoou3LWOg',$msg, $url);

        m('tools')->tips($sstr,'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MjM5OTg5OTIwOA==&scene=110#wechat_redirect');                                                  
        exit;                            

}else if($op == 'join'){                          

    $atid=intval($_GPC['id']);

    $match=m('match')->getact($atid);          

    $act=m('match')->getSignUp($atid,$openid,' and deleted = 0 and status = 1 ');
    
    $match['stime'] > time() || $match['etime'] < time() && m('tools')->tip('活动未开始或已结束,无法参加！');

    $returnurl=$this->createPluginMobileUrl('match/center',array('op'=>'join','id'=>$atid));
    if ($match['afterTheSignup']) {                                                                                 
        // http://jhzh66.com/app/index.php?i=8&c=entry&method=match&p=match&op=apply&id=260&m=sz_yi&do=plugin
        !$act && m('tools')->tips('你还没有报名，正在跳转到报名页面',$this->createPluginMobileUrl('match/match',array('op'=>'apply','id'=>$atid,'returnurl'=>$returnurl)));           
    }                       

    $no=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and signin <> 0',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));  
    $no=$no?$no+1:1;
    $data=array(
        'sgtime'=>time(),
        'signin'=>$no
    );                   

    if (intval($act['signin'] ) > 0) {                       
        $thisno=$act['signin'];             
        $sstr='签到失败,重复签到!';          
        $sgtime=$act['sgtime'];                  
    }else{
        $re=pdo_update('sz_yi_match_signup',$data,array('uniacid'=>$_W['uniacid'],'id'=>$act['id'],'actid'=>$atid,'openid'=>$openid));                       
        $sstr='恭喜你，签到成功!';
        $thisno=$no;             
        $sgtime=time();
    }

    $actitem=pdo_fetch('select * from '.tablename('sz_yi_match_payitem').' where uniacid = :uniacid  and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$act['item']));

    $msg = array(                
        'first' => array(
            'value' => $sstr,       
            "color" => "#4a5077"        
        ),
        'keyword1' => array(
            'value' =>  $match['title'],                                             
            "color" => "#4a5077"                                 
        ),           
        'keyword2' => array(                         
            'value' =>date('Y年m月d日 H:i',$match['stime']),      
            "color" => "#4a5077"                                         
        ),           
        'keyword3' => array(            
            'value' =>$actitem['title'],                    
            "color" => "#4a5077"             
        ),                                                           
        'keyword4' => array(            
            'value' =>'你是第'.$thisno.'个签到的人',                    
            "color" => "#4a5077"             
        ),
        'keyword5' => array(            
            'value' =>date('Y年m月d日 H:i',$sgtime),                    
            "color" => "#4a5077"             
        ),      
        'remark' => array(                           
            'value' => "\r".$act['desc'],                   
            "color" => "#4a5077"            
        )
    );

    


    //参加活动
    $joinlog=array(
        'uniacid'=>$_W['uniacid'],
        'openid'=>$openid,
        'actid'=>$atid,
        'status'=>1,
        'uptime'=>time(),
    );                                       
                             
    $exists=pdo_fetch('select * from '.tablename('sz_yi_match_join_status').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    if ($exists) {                                        
        //已经参加过 修改
        $re=pdo_update('sz_yi_match_join_status',$joinlog,array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
    }else{          
        pdo_insert('sz_yi_match_join_status',$joinlog);      //账号首次参加                                            
    }          

    //end
    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$_GPC['actid']));
    $re = m('message')->sendTplNotice($openid,'d873CYt1NI68xWB2on9JepLcVJLzGCxPxlsoou3LWOg',$msg, $url);
    if (is_weixin()) {
        m('tools')->tips($sstr,'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MjM5OTg5OTIwOA==&scene=110#wechat_redirect');                                                  
    }else{
        m('tools')->tips($sstr,$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$atid)));                                                 
    }

}else if($op == 'card'){

    if ($ac == 'getlist' && $_W['isajax']) {
        $type=$_GPC['status'] == 1?3:4;

        $pindex=max(1,intval($_GPC['page']));
        $psize=5;

        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':type'=>$type          
        );      
        $sql='select * from '.tablename('sz_yi_match_poster').' where uniacid = :uniacid and type = :type and enabled = 1 ';
        $sql.=' order by displayorder desc ';       
        $sql.=' limit '.($pindex -1) * $psize.' , '.$psize;
        $list=pdo_fetchall($sql,$params);
        if ($list) {         
            foreach ($list as $key => $value) {
                $list[$key]['thumb']=tomedia($value['thumb']);          
                $list[$key]['url']=$this->createPluginMobileUrl('match/center',array('poster_tpl'=>$value['id'],'op'=>'createCard'));
            }           
            show_json(1,array('list'=>$list,'pagesize'=>$psize));
        }       
        $list=array();      
        show_json(0,array('list'=>$list,'pagesize'=>$psize));

    }

    include $this->template('card');  //退款
    exit;
}else if($op == 'createCard'){

    $id=intval($_GPC['poster_tpl']);
    $tpl=pdo_fetch('select * from '.tablename('sz_yi_match_poster').' where uniacid  = :uniacid and id = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
    // $act_id=intval($_GPC['act_id']);        

    if (!$tpl) {         
        show_json(0,'找不到该模板');       
    }
    if ($tpl['type']==1) {       
        $type=6;
    }else{
        $type=7;
    }

    $poster = pdo_fetch('select * from ' . tablename('sz_yi_poster') . ' where uniacid=:uniacid and type=:type and isdefault=1 limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':type' => $type        
    ));     
    $poster['bg']=$tpl['thumb'];        

    
    $pt=m('tools')->createCardImage($poster);   
    // if (isMobile()) {                                                            
        echo html_entity_decode('<img src='.$pt.' width="100%" height="auto" />');                             
    // }else{                                                                
        // echo html_entity_decode('<img src='.$pt.' width="280px" height="auto" />');                             
    // }         
    exit;        

}else if($op == 'refund'){

    include $this->template('refund');  //退款
    exit;
}else if($op == 'invite'){

    include $this->template('invite');  //会动邀约
    exit;   
}else if($op == 'share'){
    $id=intval($_GPC['atid']);
    $type=intval($_GPC['type']);

    if (!$id || !$type) {
        exit;
    }

    $data=array(
        'uniacid'=>$_W['uniacid'],
        'openid'=>$openid,
        'actid'=>$id,
        'type'=>$type,
        'ctime'=>time()
    );          

    pdo_insert('sz_yi_match_share',$data);
    $lid=pdo_insertid();

    if ($type ==1 ) {
        $str='sz_yi_match';          
    }else{
        $str='sz_yi_match_article';
    }           
    if ($lid) {              
        $temp=m('match')->getact($id,$type);                               
        pdo_update($str,array('forwarding'=>$temp['forwarding']+1),array('id'=>$temp['id'],'uniacid'=>$_W['uniacid']));
        show_json(1,'分享成功!');
    }

    show_json(0,'分享失败!');

}else if($op == 'deduct' && $_W['ispost']) {  
    $logid = intval($_GPC['logid']); 
    if (empty($logid)) {                        
        show_json(0, '充值出错, 请重试!');
    }

    $buildtype=intval($_GPC['buildtype'])?:1;
    $money = floatval($_GPC['money']);
    if (empty($money)) {            
        show_json(0, '请填写充值金额!');
    }

    $type = $_GPC['type'];
    if (!in_array($type, array('weixin'))) {
        show_json(0, '未找到支付方式');
    }

    $log = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_member_log') . ' WHERE `id`=:id and `uniacid`=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $logid 
    )); 

    if (empty($log)) {                              
        show_json(0, '充值出错, 请重试!'); 
    }

    /*修复支付问题*/
    $couponid = intval($_GPC['couponid']);
    if($log['money'] <= 0){
        pdo_update('sz_yi_member_log', array('money' => $money, 'couponid' => $couponid), array('id' => $log['id']));
    }

    $set = m('common')->getSysset(array(
        'shop',
        'pay'
    ));
    if ($type == 'weixin') {
        if (!is_weixin()) {
            show_json(0, '非微信环境!');
        }
        if (empty($set['pay']['weixin'])) {
            show_json(0, '未开启微信支付!');
        }
        $wechat          = array(
            'success' => false
        );
        $params          = array();
        $params['tid']   = $log['logno'];
        $params['user']  = $openid;
        $params['fee']   = $money;
        $params['title'] = $log['title'];
        load()->model('payment');
        $setting = uni_setting($_W['uniacid'], array(
            'payment'
        )); 
        if (is_array($setting['payment'])) {
            $options           = $setting['payment']['wechat'];
            $options['appid']  = $_W['account']['key']; 
            $options['secret'] = $_W['account']['secret'];
            $wechat            = m('common')->wechat_build($params, $options,$buildtype);
            $wechat['success'] = false;
            if (!is_error($wechat)) {                           
                $wechat['success'] = true; 
            } else { 
                show_json(0, $wechat['message']);
            }
        }
        if (!$wechat['success']) {
            show_json(0, '微信支付参数错误!');
        }
        show_json(1, array(
            'wechat' => $wechat
        ));
    }
} else if ($op == 'getinfo' && $_W['isajax']) {
    $set = m('common')->getSysset(array(
        'shop',
        'pay',
        'trade'
    ));
    
    pdo_delete('sz_yi_member_log', array(
        'openid' => $openid,
        'status' => 0,
        'type' => 0,
        'uniacid' => $_W['uniacid']
    ));

    $logno = m('common')->createNO('member_log', 'logno', 'RC');
    $log   = array(
        'uniacid' => $_W['uniacid'],
        'logno' => $logno,
        'title' => $set['shop']['name'] . "会员充值",
        'openid' => $openid,
        'type' => 0,
        'createtime' => time(),
        'status' => 0
    );
    pdo_insert('sz_yi_member_log', $log);
    $logid  = pdo_insertid();
    $wechat = array(
        'success' => false
    );

    if (is_weixin()) {
        if (isset($set['pay']) && $set['pay']['weixin'] == 1) {
            load()->model('payment');
            $setting = uni_setting($_W['uniacid'], array(
                'payment'
            ));
            if (is_array($setting['payment']['wechat']) && $setting['payment']['wechat']['switch']) {
                $wechat['success'] = true;
            }
        }
    }    
    
    show_json(1, array(
        'set' => $set,
        'logid' => $logid,
        'isweixin' => is_weixin(),
        'wechat' => $wechat,
    ));
}else if($op == 'center'){

    // if (!$muser) {                     
    //     m('tools')->tips('你还没有注册易活动,请先注册!',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));
    // }

    $matchTotals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));        
        
    $tmu=m('match')->getMuser($openid);  
    $favorite=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_favorite').' where merchid = :uid and uniacid = :uniacid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$tmu['uid']));
    $favorite=$favorite?:0;  
    if ($tmu) {  
        $tpu=p('bonus')->getMerch($tmu['uid']);
    }       
    $credit=m('member')->getCredit($openid,'credit2');   


include $this->template('center');      //个人中心
exit;

}else if($op == 'team'){
    $id=intval($_GPC['id']);
    $cate=trim($_GPC['cate']);

    if ($cate == 'act') {
        $act=m('match')->getact($id);
        $str='活动';
    }else if($cate == 'art'){
        $act=m('match')->getact($id,2);  
        $str='文章';
    }
    $muser=m('match')->getMuser($openid);           
    $member=m('member')->getMember($openid);
    $muser['msgnum'] <= 0 && show_json(0,'你的短信数量不足,无法发送');
    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id,'mid'=>$member['id']));            
    $oplist = pdo_fetchall('select openid from '.tablename('sz_yi_match_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$muser['uid']));                                                                                                                                                  
    $msg = array(                
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(         
            'title' => $str.'名称 ',
            'value' => $act['title'],
            "color" => "#4a5077"
        ),                              
        'keyword2' => array(
            'title' => '消息类型',               
            'value' =>  $str,                                             
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
            'value' =>  $str.'群发',                                             
            "color" => "#4a5077"        
        ),
        'keyword2' => array(                         
            'title' => '提醒内容 ',
            'value' => $act['title'].$str.'已经通过'.$_W['setting']['copyright']['sitetitle'].'发送给你的'.count($oplist).'位粉丝',
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
    // d873CYt1NI68xWB2on9JepLcVJLzGCxPxlsoou3LWOg                        
        $ret = m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);
    }                                                         
       m('message')->sendTplNotice($muser['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$ntc, $url);                            
    if ($ret['errcode'] == 0) {                      
        pdo_update('sz_yi_member_user',array('msgnum'=>$muser['msgnum']-1),array('id'=>$muser['id']));
        show_json(1,'群发成功！');                   
    }else{       
        show_json(0,'群发失败!错误代码'.$re['errcode'].':'.$re['errmsg']);       
    }

}else if($op == 'comment'){
    
    if ($_W['isajax'] && $ac == 'get') {       
        $pindex=max(1,intval($_GPC['page']));
        $psize=5;

        $condition=' and ac.uniacid = :uniacid ';
        $params=array(
            ':uniacid'=>$_W['uniacid'],              
            ':openid'=>$openid              
        );   

        if ($_GPC['status']) {
            $condition.=' and ac.type = :type ';                
            $params[':type']=$_GPC['status'];        
        }               

        $field='';
        if ($_GPC['status'] == 1) {
            $extsql=' left join '.tablename('sz_yi_match').' a on a.id = ac.atid ';
            $condition.=' and a.openid = :openid '; 
            $field.=' ,a.title ';              
        }else if($_GPC['status'] == 2){
            $extsql=' left join '.tablename('sz_yi_match_article').' a on a.id = ac.atid ';
            $condition.=' and a.openid = :openid ';
            $field.=' ,a.title ';                               
        }else if($_GPC['status'] == 3){      
            $extsql='';                             
            $condition.=' and ac.openid = :openid ';                  
        }                    
            
        $sql='select ac.*,m.realname,m.nickname'.$field.' from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid '.$extsql.' where 1 ';                                     

        $sql.=$condition;                        
        $sql.=' order by ac.id desc ';               
        $sql.=' limit '.($pindex - 1) * $psize.','.$psize;               
        // show_json(0,array('sql'=>$sql,'params'=>$params));       
        $list=pdo_fetchall($sql,$params);                                           
        if ($list) {                
            foreach ($list as $key => $value) {
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            }
            show_json(1,array('list'=>$list,'pagesize'=>$psize));
        }                 
        show_json(0,array('list'=>array(),'pagesize'=>$psize));     

   }else if($ac == 'del'){
        $id=intval($_GPC['id']);
        empty($id) && show_json(0,'非法参数!');      
        $re=pdo_delete('sz_yi_match_comment',array('id'=>$id,'uniacid'=>$_W['uniacid']));
        if ($re) {
            show_json(1,'删除成功!');
        }        
        show_json(0,'删除失败!');
   }else if($ac == 'audit'){        
        $id=intval($_GPC['id']);                
        empty($id) && show_json(0,'非法参数!');
        $isok=intval($_GPC['isok']);                       
        empty($isok) && show_json(0,'非法参数!');           

        $re=pdo_update('sz_yi_match_comment',array('status'=>$isok),array('id'=>$id,'uniacid'=>$_W['uniacid']));
        if ($re) {                   
            show_json(1,'审核成功!');                      
        }               
        show_json(0,'审核失败!');       
   }      
include $this->template('comment');      //个人打卡
exit;       
}else if($op == 'message'){     //短信充值

    $set=m('tools')->getset();
    $fee=number_format($set['bartact']['msg'],2);
    if ($_W['isajax']) {
        if ($ac == 'sub') {
            $num=intval($_GPC['message_num']);
            $fee=floatval($num * $fee); 
            $type=intval($_GPC['paytype']); 

            if ($type ==2) {
                $credit=m('member')->getCredit($openid,'credit2');
                $fee > $credit && show_json(0,'余额不足,充值短信失败');
                m('member')->setCredit($openid,'credit2',-$fee);
            }
           
            $data=array(                                 
              'msgnum'=>$num+$muser['msgnum']
            );                                       
            $params=array(
              'uniacid'=>$_W['uniacid'],              
              'openid'=>$openid            
            );                                   
            $re=m('match')->setMuser($data,$params);
            
            $log=array(
              'uniacid'=>$_W['uniacid'],
              'openid'=>$openid,
              'type'=>$type,     
              'channel'=>1,    
              'num'=>$num,      
              'fee'=>$fee,       
              'ctime'=>time()        
            );                                               
            pdo_insert('sz_yi_match_recharge_msg_log',$log);
            if ($re) {           
                show_json(1,'充值短信成功');       
            }                
            show_json(0,'充值短信失败');                                   
        }
        
    }
    include $this->template('message');      //个人打卡
    exit; 
}else if($op == 'sort'){

    if ($_W['isajax']) {               
        if ($ac == 'get') {      

            $status=$_GPC['status'];
            $time=$_GPC['time'];
            if ($time == 1) {
                $time=m('time')->today();   //今天开始结束
                $order='order by day desc ';
                $str='day';
            }else if($time == 2) {
                $time=m('time')->week();    //本周开始结束
                $order='order by week desc ';
                $str='week';
            }else if($time == 3) {
                $time=m('time')->month();   //本月开始结束
                $order='order by month desc ';
                $str='month';
            }else if($time == 4) {      
                $time=m('time')->thisQuarter(); //本季度开始结束
                $order='order by quarter desc ';
                $str='quarter';
            }     

            $pindex=max(1,intval($_GPC['page']));
            $psize=30;	 	     
            $params=array(   
                ':uniacid'=>$_W['uniacid'],  
                ':stime'=>$time[0],      
                ':etime'=>$time[1]
            );
            if ($status == 1) { 
                // $openid='oSI4LjzGDzCztrhCP10C7-4flpE4';
                // $member_bonus = p('commission')->getInfo($openid, array());

                // show_json(0,$member_bonus);
                // $sql='select count(*) num,atid mid from '.tablename('sz_yi_match_like').' where uniacid = :uniacid and type = 3 and ctime > :stime and ctime < :etime group by atid order by num desc ';
                // $sql.=' limit '.($pindex  -1) * $psize.','.$psize;
                // $list=pdo_fetchall($sql,$params);
                // $no=($pindex -1) * $psize;
                // if ($list) {
                //     foreach ($list as $key => $value) {
                //         $tminfo=m('member')->getMember($value['mid']);
                //         $list[$key]['nickname']=$tminfo['nickname'];
                //         $list[$key]['ctime']=date('Y-m-d H:i:s',$tminfo['createtime']);
                //         $list[$key]['avatar']=$tminfo['avatar'];     
                //         $list[$key]['no']=$no+=1;
                //     }                         
                //     show_json(1,array('list'=>$list,'pagesize'=>$psize));
                // }

                $allMember=pdo_fetchall('select openid,avatar,id,nickname from '.tablename('sz_yi_member').' where uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid'])); 

                foreach ($allMember as $key => $value) {                                                                  
                    $member_bonus = p('commission')->getmyInfo($value['openid'],$time[0],$time[1]);
                    $allMember[$key]['fans']=$member_bonus['agentcount'];
                }             

                $date = array_column($allMember, 'fans');        
                array_multisort($date,SORT_DESC,$allMember);         
                $list=array_slice($allMember,($pindex-1)*$psize,$psize);

                $no=($pindex -1) * $psize;
                if ($list) {
                    foreach ($list as $key => $value) {
                        $list[$key]['no']=$no+=1;
                    }                         
                }
                
                show_json(1,array('list'=>$list,'pagesize'=>$psize));
            }else if($status == 2) {            
                $sql='select sum(price) price,openid from '.tablename('sz_yi_order').' where uniacid = :uniacid and status > 0 and isexchange = 1 and createtime > :stime and createtime < :etime group by openid order by price desc ';        
                $sql.=' limit '.($pindex  -1) * $psize.','.$psize;
                $list=pdo_fetchall($sql,$params);        
                if ($list) {
                    $no=($pindex -1) * $psize;                                    
                    foreach ($list as $key => $value) {
                        $tminfo=m('member')->getMember($value['openid']);
                        $list[$key]['nickname']=$tminfo['nickname'];
                        $list[$key]['ctime']=date('Y-m-d H:i:s',$tminfo['createtime']);
                        $list[$key]['avatar']=$tminfo['avatar'];
                        $list[$key]['no']=$no+=1;     
                    }                                    
                    show_json(1,array('list'=>$list,'pagesize'=>$psize));
                }                    
            }else if($status == 3) {            
                // $sql='select sum(money) money,openid ,ctime from (select money,openid,uniacid,ctime from '.tablename('sz_yi_obtain_bonus').' union select money,openid,uniacid,ctime from '.tablename('sz_yi_ad_bonus_log').') as t where uniacid = :uniacid and bonustype = 1  and ctime > :stime and ctime < :etime group by openid order by money desc ';        
                // $sql.=' limit '.($pindex  -1) * $psize.','.$psize;                                                                 
                // $list=pdo_fetchall($sql,$params);                                    
                // if ($list) {
                //     $no=($pindex -1) * $psize;                                                                   
                //     foreach ($list as $key => $value) {                      
                //         $tminfo=m('member')->getMember($value['openid']);        
                //         $list[$key]['nickname']=$tminfo['nickname'];     
                //         $list[$key]['ctime']=date('Y-m-d H:i:s',$tminfo['createtime']);
                //         $list[$key]['avatar']=$tminfo['avatar'];
                //         $list[$key]['no']=$no+=1;                         
                //     }                                
                //     show_json(1,array('list'=>$list,'pagesize'=>$psize));
                // }
                $sql='select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = 1 and utime >= :stime and utime <= :etime ';  
                $sql.=$order;          
                $sql.=' limit '. ($pindex  -1) * $psize.','.$psize;    
                $list=pdo_fetchall($sql,$params);                
                if ($list) {                                                             
                    $no=($pindex -1) * $psize;                                                                   
                    foreach ($list as $key => $value) {                      
                        $tminfo=m('member')->getMember($value['openid']);        
                        $list[$key]['nickname']=$tminfo['nickname'];     
                        $list[$key]['ctime']=date('Y-m-d H:i:s',$value['utime']);
                        $list[$key]['avatar']=$tminfo['avatar'];     
                        $list[$key]['bonus']=$value[$str];     
                        $list[$key]['no']=$no+=1;                                                                
                    }                                                 
                    show_json(1,array('list'=>$list,'pagesize'=>$psize));
                }
            }else if($status == 4) {
                $sql='select * from '.tablename('sz_yi_commission_sort_log').' where uniacid = :uniacid and utime >= :stime and utime <= :etime ';  
                $sql.=$order;          
                $sql.=' limit '. ($pindex  -1) * $psize.','.$psize;    
                $list=pdo_fetchall($sql,$params);                
                if ($list) {                                                             
                    $no=($pindex -1) * $psize;                                                                   
                    foreach ($list as $key => $value) {                      
                        $tminfo=m('member')->getMember($value['openid']);        
                        $list[$key]['nickname']=$tminfo['nickname'];     
                        $list[$key]['ctime']=date('Y-m-d H:i:s',$value['utime']);
                        $list[$key]['avatar']=$tminfo['avatar'];     
                        $list[$key]['bonus']=$value[$str];     
                        $list[$key]['no']=$no+=1;                                                                
                    }                                                 
                    show_json(1,array('list'=>$list,'pagesize'=>$psize));
                }
            }else if($status == 5) {            
                $sql='select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = 2 and utime >= :stime and utime <= :etime ';  
                $sql.=$order;          
                $sql.=' limit '. ($pindex  -1) * $psize.','.$psize;    
                $list=pdo_fetchall($sql,$params);                
                if ($list) {                                                             
                    $no=($pindex -1) * $psize;                                                                   
                    foreach ($list as $key => $value) {                      
                        $tminfo=m('member')->getMember($value['openid']);        
                        $list[$key]['nickname']=$tminfo['nickname'];     
                        $list[$key]['ctime']=date('Y-m-d H:i:s',$value['utime']);
                        $list[$key]['avatar']=$tminfo['avatar'];     
                        $list[$key]['bonus']=$value[$str];     
                        $list[$key]['no']=$no+=1;                                                                
                    }                                                 
                    show_json(1,array('list'=>$list,'pagesize'=>$psize));
                }
            }                                                                                                       

            show_json(0,array('list'=>array(),'pagesize'=>$psize));     
        }
    }
    if ($ac == 'list') {
          
        include $this->template('sortList');      //个人打卡
        exit;        
    }
    include $this->template('sort');      //个人打卡
    exit;                
}            
include $this->template('signin');      //个人打卡