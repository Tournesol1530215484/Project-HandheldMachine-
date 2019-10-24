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
    $id=$_GPC['mmid']?:$member['id'];
    $heMember=m('member')->getMember($id);
    $yesteday=strtotime('-1 day');
    $yesteday=date('Ymd',$yesteday);
        
    $tedaycount=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid and date = :date',array(':uniacid'=>$_W['uniacid'],':date'=>date('Ymd')));
    // 日访量统计      
    m('activity')->borwseStatis($heMember,$openid);         
    $tedayBorwse=m('activity')->getBStatis($heMember['id'],true);

    $yestdayPeople=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid and date = :date',array(':uniacid'=>$_W['uniacid'],':date'=>$yesteday));
    $tedayPeople=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid and date = :date',array(':uniacid'=>$_W['uniacid'],':date'=>date('Ymd')));       


    $max=pdo_fetchcolumn('select continuous from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid and openid = :openid and date = :date',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid'],':date'=>$yesteday));                             
    $max=$max?$max+1:0;

    $minfo['like']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_like').' where uniacid = :uniacid and atid = :id and type = 3',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    $minfo['browse']=pdo_fetchcolumn('select browse from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid']));  

    $minfo['reward']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_reward').' where uniacid = :uniacid and atid = :id and type = 3',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    $like=pdo_fetchall('select m.avatar from '.tablename('sz_yi_activity_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.atid = :id and al.type = 3 order by al.id desc limit 0,32 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 

    $comment=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ac.content from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 3 and ac.status = 1 order by ac.id desc limit 0,3 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    // if ($_GPC['debug']) {
    //     var_dump($comment);
    //     exit;
    // }
    $reward=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ar.money,ar.remark from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where ar.uniacid = :uniacid and ar.atid = :id and ar.type = 3 order by ar.id desc limit 0,3 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    if ($ac == 'signin') {
        m('tools')->signin($openid);    //签到
    }else if($ac == 'get'){   
        $pindex=max(1,intval($_GPC['page']));
        $psize=5;            
        if ($_GPC['status'] == 1) {
            $mysign=pdo_fetch('select s.*,m.avatar,m.nickname from '.tablename('sz_yi_activity_signin').' s  left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.openid = :openid and s.date = :date',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid'],':date'=>date('Ymd')));                              
            $mysign=$mysign?:array();
            if ($mysign) {           
                $mysign['ctime']=date('Y-m-d H:i:s',$mysign['ctime']);       
           }           
            $sql='select s.*,m.avatar,m.nickname from '.tablename('sz_yi_activity_signin').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.date = :date order by s.id asc ';
            $params=array(
                ':uniacid'=>$_W['uniacid'],
                ':date'=>date('Ymd')
            );
        }else if($_GPC['status'] == 2){
            $mysign=pdo_fetch('select openid,max(continuous) continuous,sum(score) score,sum(bonus) bonus from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid and openid = :openid group by openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$heMember['openid']));          

            if ($mysign) {
                $mysign['avatar']=$heMember['avatar'];       
                $mysign['nickname']=$heMember['nickname'];      
                $mysign['continuous'] +=1;       
            }

            $sql='select openid,max(continuous) continuous,sum(score) score,sum(bonus) bonus from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid group by openid order by continuous desc';   
            $params=array(                      
                ':uniacid'=>$_W['uniacid']                     
            );          
        }else if($_GPC['status'] == 3){
             $sql='select s.*,m.avatar,m.nickname from '.tablename('sz_yi_activity_signin').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.openid = :openid order by s.id desc ';    
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
                    $list[$key]['link']=$this->createPluginMobileUrl('activity/center',array('mmid'=>$tmember['id']));
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
}else if($op == 'reward'){ 
	$id=intval($_GPC['id']);
	$type=intval($_GPC['type']);
	
    if (!$id && $type == 3) {
        $id=$member['id'];
    }   

	empty($id) && m('tools')->tip('非法参数!');
	empty($type) && m('tools')->tip('非法参数!');
	// $type > 2 && m('tools')->tip('非法参数!');

	$at=m('activity')->getact($id,$type);
    if ($type == 3) {
        $at=array(
            'relOrg'=>$muser['orgName']
        );
    }
	if ($ac == 'sub') {
		$data=array(
			'uniacid'=>$_W['uniacid'],
			'openid'=>$openid,
            'type'=>$type,
			'paytype'=>2,
			'atid'=>$id, //member id 
			'money'=>floatval($_GPC['money']),
            'uniacid'=>$_W['uniacid'],
			'remark'=>$_GPC['message'],
            'ctime'=>time()
		);

		pdo_insert('sz_yi_activity_reward',$data);

		$id=pdo_insertid();
		if ($id) {
			p('commission')->calcReward($openid,1,$at['id'],$data['money'],$id);
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
                $sql='select s.* from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').'  a on a.id = s.actid  where s.uniacid = :uniacid and a.openid = :openid and s.deleted = 0 ';
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
                    $sql='select * from '.tablename('sz_yi_activity_signup').' where 1 ';             
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

            $sql='select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and deleted = 0 and paystatus = 2';         
                                     
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

            $re=pdo_update('sz_yi_activity_signup',array('status'=>$_GPC['isok'],'sgtime'=>time()),array('uniacid'=>$_W['uniacid'],'actid'=>$actid,'id'=>$sgid,'deleted'=>'0'));              
                
            if ($re) {      
                show_json(1,array('msg'=>'处理成功!'));     
            }                       
            show_json(0,array('msg'=>'处理失败!'));         
        }           

    }

	include $this->template('apply');	//	报名名单 和审核写一起
	exit;
}else if($op == 'poster'){ 

    $id=intval($_GPC['poster_tpl']);
    $tpl=pdo_fetch('select * from '.tablename('sz_yi_activity_poster').' where uniacid  = :uniacid and id = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
    $act_id=intval($_GPC['act_id']);
    

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
  
    // $pt=m('tools')->createPoster($openid,$type,$id);                  
    $pt=m('tools')->createShopImage($poster,$act_id);                      
    if (isMobile()) {
        echo html_entity_decode('<img src='.$pt.' width="100%" height="auto"  />');                             
    }else{               
        echo html_entity_decode('<img src='.$pt.' width="280px" height="auto" style="left:50%;position:absolute;margin-left:-140px;margin-top:7%;"/>');                             
    }           
    exit;                       
}else if($op == 'signin'){

    $atid=intval($_GPC['id']);

    $act=m('activity')->getSignUp($atid,$openid,' and deleted = 0 and status = 1 ');

    !$act && m('tools')->tip('你还没有报名或该报名审核没有通过');

    $no=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and signin <> 0',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));
    $no=$no?$no+1:1;
    $data=array(
        'sgtime'=>time(),
        'signin'=>$no
    );                   

    if ($act['signin'] > 0) {
        $sstr='重复打卡!';
    }else{
        $re=pdo_update('sz_yi_activity_signup',$data,array('uniacid'=>$_W['uniacid'],'id'=>$act['id'],'actid'=>$atid,'openid'=>$openid));                       
        $sstr='签到成功!';
    }

    $template = array(
            array(
                'title' => '签到状态', 
                'value' =>$sstr
            ),          
            array(
                'title' => '签到序号 ', 
                'value' => $no
            ),
            array(
                'title' => '签到时间 ', 
                'value' => date('Y-m-d H:i:s')
            ),
            array(
                'title' => '报名信息 ', 
                'value' =>'空'
            ),
            array(
                'title' => '报名项目 ', 
                'value' => $act['title']
            ),
            array(
                'title' => '订单编号 ', 
                'value' => time().$no
            ),
            array(
                'title' => '支付时间 ', 
                'value' => date('Y-m-d H:i:s',$act['paytime'])
            ),
        );                
    
    $msg = array(                
        'first' => array(
            'value' => "恭喜你，签到成功!",       
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
               
    $ret = m('message')->sendTplNotice($openid,'ZuWsPQdokCtfx0WVtcg2n0O6UNtf3cjKBGw5m0JBiWw',$msg, $url);

    // m('message')->sendCustomNotice($openid, $template);

    if ($re) {
        m('tools')->tip('签到成功!');
    }
    m('tools')->tip('签到失败!');

}else if($op == 'card'){

    if ($ac == 'getlist' && $_W['isajax']) {
        $type=$_GPC['status'] == 1?3:4;

        $pindex=max(1,intval($_GPC['page']));
        $psize=5;

        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':type'=>$type          
        );      
        $sql='select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = :type and enabled = 1 ';
        $sql.=' order by displayorder desc ';       
        $sql.=' limit '.($pindex -1) * $psize.' , '.$psize;
        $list=pdo_fetchall($sql,$params);
        if ($list) {         
            foreach ($list as $key => $value) {
                $list[$key]['thumb']=tomedia($value['thumb']);          
                $list[$key]['url']=$this->createPluginMobileUrl('activity/center',array('poster_tpl'=>$value['id'],'op'=>'createCard'));
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
    $tpl=pdo_fetch('select * from '.tablename('sz_yi_activity_poster').' where uniacid  = :uniacid and id = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
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

    pdo_insert('sz_yi_activity_share',$data);
    $lid=pdo_insertid();

    if ($type ==1 ) {
        $str='sz_yi_activity';          
    }else{
        $str='sz_yi_activity_article';
    }           
    if ($lid) {              
        $temp=m('activity')->getact($id,$type);                               
        pdo_update($str,array('forwarding'=>$temp['forwarding']+1),array('id'=>$temp['id'],'uniacid'=>$_W['uniacid']));
        show_json(1,'分享成功!');
    }

    show_json(0,'分享失败!');

}else if($op == 'deduct' && $_W['ispost']) {  
    $logid = intval($_GPC['logid']); 
    if (empty($logid)) {
        show_json(0, '充值出错, 请重试!');
    }

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
            $wechat            = m('common')->wechat_build($params, $options, 1);
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

    $activityTotals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));        
        
    $tmu=m('activity')->getMuser($openid);  
    $favorite=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_favorite').' where merchid = :uid and uniacid = :uniacid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$tmu['uid']));
    $favorite=$favorite?:0;  
    if ($tmu) {  
        $tpu=p('bonus')->getMerch($tmu['uid']);
        // var_dump($tpu['username']);
    }       
    $credit=m('member')->getCredit($openid,'credit2');   


include $this->template('center');      //个人中心
exit;

}else if($op == 'team'){
    $id=intval($_GPC['id']);
    $cate=trim($_GPC['cate']);

    if ($cate == 'act') {
        $act=m('activity')->getact($id);
        $str='活动';
    }else if($cate == 'art'){
        $act=m('activity')->getact($id,2);  
        $str='文章';
    }
    $muser=m('activity')->getMuser($openid);           
    $member=m('member')->getMember($openid);
    $url=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$id,'mid'=>$member['id']));            
    $oplist = pdo_fetchall('select openid from '.tablename('sz_yi_activity_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$muser['uid']));                                                                                                                                                  
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
            $extsql=' left join '.tablename('sz_yi_activity').' a on a.id = ac.atid ';
            $condition.=' and a.openid = :openid '; 
            $field.=' ,a.title ';              
        }else if($_GPC['status'] == 2){
            $extsql=' left join '.tablename('sz_yi_activity_article').' a on a.id = ac.atid ';
            $condition.=' and a.openid = :openid ';
            $field.=' ,a.title ';                               
        }else if($_GPC['status'] == 3){      
            $extsql='';                             
            $condition.=' and ac.openid = :openid ';                  
        }                    
            
        $sql='select ac.*,m.realname,m.nickname'.$field.' from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid '.$extsql.' where 1 ';                                     

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
        $re=pdo_delete('sz_yi_activity_comment',array('id'=>$id,'uniacid'=>$_W['uniacid']));
        if ($re) {
            show_json(1,'删除成功!');
        }        
        show_json(0,'删除失败!');
   }else if($ac == 'audit'){        
        $id=intval($_GPC['id']);                
        empty($id) && show_json(0,'非法参数!');
        $isok=intval($_GPC['isok']);                       
        empty($isok) && show_json(0,'非法参数!');           

        $re=pdo_update('sz_yi_activity_comment',array('status'=>$isok),array('id'=>$id,'uniacid'=>$_W['uniacid']));
        if ($re) {                   
            show_json(1,'审核成功!');                      
        }               
        show_json(0,'审核失败!');       
   }      
include $this->template('comment');      //个人打卡
exit;       
}else if($op == 'message'){

    if ($_W['isajax']) {
        if ($ac == 'sub') {
            $num=intval($_GPC['message_num']);
            $fee=floatval($num / 10); 
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
            $re=m('activity')->setMuser($data,$params);

            $log=array(
              'uniacid'=>$_W['uniacid'],
              'openid'=>$openid,
              'type'=>$type,     
              'channel'=>1,    
              'num'=>$num,      
              'fee'=>$fee,       
              'ctime'=>time()        
            );                                               
            pdo_insert('sz_yi_activity_recharge_msg_log',$log);
            if ($re) {           
                show_json(1,'充值短信成功');       
            }                
            show_json(0,'充值短信失败');                                   
        }
        
    }
    include $this->template('message');      //个人打卡
    exit; 
}            
include $this->template('signin');      //个人打卡