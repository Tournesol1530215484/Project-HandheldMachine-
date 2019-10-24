<?php

global $_W, $_GPC;
$openid = m('user')->getOpenid();
$op = empty($_GPC['op']) ? 'display':$_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('activity')->getMuser($openid); 			 	
$member=m('member')->getMember($openid);
$tid=$_GPC['tid']?:$member['id'];
if (!$muser) {
	// m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));	 		
} 	 	
if($op == 'display'){	 	 	
	$tmember=m('member')->getMember($tid);

	// if ($_GPC['debug']) {
	// 	$list=pdo_fetchall('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));
	// 	var_dump(count($list));
	// 	exit;	 		 
	// }

		$tuser=pdo_fetch('select * from '.tablename('sz_yi_visiting_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$tmember['openid']));
		if (!$tuser) {	 	 	 	 	 		 	 	 	 	 
			$tmuser=m('activity')->getMuser($tmember['openid']); 			 	
				 	 	 	 	 		 
			if ($tmuser) {	 	 		
				$oldData=array(
					'uniacid'=>$tmuser['uniacid'],
					'openid'=>$tmuser['openid'],
					'head'=>$tmuser['head'],
					'level'=>$tmuser['level'],
					'wechatCode'=>$tmuser['wechatCode'],
					'realname'=>$tmuser['realname'],
					'mobile'=>$tmuser['mobile'],
					'business'=>$tmuser['business'],
					'orgName'=>$tmuser['orgName'],	 	 	
					'contact'=>$tmuser['contact'],
					'orgMobile'=>$tmuser['orgMobile'],
					'orgDesc'=>$tmuser['orgDesc'],
					'withdrawRatio'=>$tmuser['withdrawRatio'],
					'province'=>$tmuser['provincea'],
					'city'=>$tmuser['city'],
					'area'=>$tmuser['area'],
					'address'=>$tmuser['address'],
					'job'=>$tmuser['job'],
					'browse'=>$tmuser['browse'],
					'supplier'=>$tmuser['supplier'],
					'need'=>$tmuser['need'],
					'worktype'=>$tmuser['worktype'],
					'public'=>$tmuser['public'],
					'banner'=>$tmuser['banner'],
					'title'=>$tmuser['title'],
					'ctime'=>time(),
				);

				pdo_insert('sz_yi_visiting_info',$oldData);
				$tuser=$oldData;
			}else{ 		 	 	 		  
				$tuser=array();
			}

	 	}		 	

	$banner=unserialize($tuser['banner']);
	foreach ($banner as $key => $value) {	 	 
		$banner[$key]=tomedia($value);	 	 	
	}	 	 	 		 	 	 	  	

	$msg=pdo_fetchall('select m.id tid,l.ctime,l.id,m.avatar,l.content,l.ctime,mu.realname from '.tablename('sz_yi_activity_leave_msg').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid left join '.tablename('sz_yi_visiting_info').' mu on mu.openid = m.openid where l.uniacid = :uniacid and l.openid = :openid order by l.id desc limit 0,5',array(':uniacid'=>$_W['uniacid'],':openid'=>$tuser['openid']));
	foreach ($msg as $key => $value) {	  	 	 
		$msg[$key]['link']=$this->createPluginMobileUrl('activity/card',array('tid'=>$value['tid']));
		$msg[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
 	}				 		 	  	 	 	 		 	 

}else if ($op == 'edit') {		 

	$tmember=m('member')->getMember($tid);
	$tinfo=m('activity')->getVisitingInfo($tmember['openid']);

	if ($ac == 'get') {	 	 	 
		$tinfo['wechatCode']=tomedia($tinfo['wechatCode']);
		$tinfo['head']=tomedia($tinfo['head']);
		show_json(1,array('list'=>$tinfo)); 	  		
	}else if ($ac == 'sub'){
		$data=array(
			'worktype'=>$_GPC['worktype'],
			'public'=>$_GPC['public'],
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile'],
			'job'=>$_GPC['job'],
			'orgName'=>$_GPC['orgName'],
			'province'=>$_GPC['province'],
			'city'=>$_GPC['city'],
			'area'=>$_GPC['district'],
			'address'=>$_GPC['address'],
			'title'=>$_GPC['signature'],
			'head'=>$_GPC['logo'],	 	 	 	 	 	 	
			'business'=>trim($_GPC['business']),	 
			'wechatCode'=>$_GPC['wechatCode'],
			'supplier'=>$_GPC['provide_res'],	 	 
			'need'=>$_GPC['needed_res']	 	 
        );

		if ($tinfo) {
        	$re=pdo_update('sz_yi_visiting_info',$data,array('openid'=>$tinfo['openid']));	 	
		}else{
			$data['uniacid']=$_W['uniacid'];
			$data['openid']=$openid;	 	 		
        	$re=pdo_insert('sz_yi_visiting_info',$data);	 
		}	 	 		
		if ($re) {	 	 	 	
			show_json(1,'修改成功!');	 	
		}
		show_json(0,'修改失败!');	 	
	}	 	 	 	 	 	

	include $this->template('editCard');		 	 	
	exit;
}else if($op == 'leave'){	 	
	$uid=intval($_GPC['uid']);
	$msg=trim($_GPC['msg']);	 

	$data=array(
		'uniacid'=>$_W['uniacid'],
		'muid'=>$uid,		//被评论者openid
		'openid'=>$openid,
		'ctime'=>time(),
		'content'=>$msg
	);

	pdo_insert('sz_yi_activity_leave_msg',$data);

	$re=pdo_insertid();	 	 	 

	if ($re) {
		$result=array(
			'id'=>$re,
			'content'=>$msg,
			'avatar'=>$member['avatar'],
			'realname'=>$muser['realname'],	 		  	  	 	 		
			'ctime'=>date('Y-m-d H:i:s')	 	 	 	 		
		);	 	 		 	 	 	 	 	 	 	 
		show_json(1,array('msg'=>'留言成功!','data'=>$result));
	}

	show_json(0,array('msg'=>'留言失败!')); 	 

}else if($op == 'browse'){
	$tmember=m('member')->getMember($tid);
	$tinfo=m('activity')->getVisitingInfo($tmember['openid']);
	$re=pdo_update('sz_yi_visiting_info',array('browse'=>$tinfo['browse']+1),array('id'=>$tinfo['id']));
	if ($re) {
		show_json(1);
	}	 	 
	show_json(0);
}else if($op == 'delete'){
	$msgid=intval($_GPC['msgid']);

	$re=pdo_delete('sz_yi_activity_leave_msg',array('id'=>$msgid));

	if($re){
		show_json(1,'删除成功!');
	}
	show_json(0,'删除失败!');
}else if($op == 'banner'){	 	 	 	 
	$data=array(	 	 	 	
		'banner'=>serialize($_GPC['logo'])	 	 
	);		  

	$re=pdo_update('sz_yi_visiting_info',$data,array('openid'=>$openid,'uniacid'=>$_W['uniacid']));		 
	if ($re) {
		show_json(1,'更新成功!');
	}	 	 	 		  	
	show_json(0,'更新失败!');
}else if ($op == 'cardlist'){	  	 

	$tid=$_GPC['tid'];
	$tmember=m('member')->getMember($tid);
	$tuser=m('activity')->getVisitingInfo($tmember['openid']);
	if (!$tuser) {
		m('tools')->tip('还没有创建名片无法生成海报');	 	
	}
	if ($ac == 'get') {
	
		$pindex=max(1,intval($_GPC['page']));
		$psize=10; 			 	 

		$sql='select * from '.tablename('sz_yi_poster').' where uniacid = :uniacid and type = :type ';
		$params=array(	 	 
			':uniacid'=>$_W['uniacid'],	 	 
			':type'=>8
		);	 

		$sql.=' limit '.($pindex -1) * $psize.' , '.$psize;	 	 
		$list=pdo_fetchall($sql,$params);	 	
		foreach ($list as $key => $value) {	 	
			$list[$key]['bg']=tomedia($value['bg']); 	 		 	 	
			$list[$key]['url']=$this->createPluginMobileUrl('activity/card',array('op'=>'poster','posterid'=>$value['id'],'tid'=>$tid)); 	 
		}	 	 	 	 	 	  
		$list=$list?$list:array();	 		 		 	
		show_json(1,array('list'=>$list)); 	 			 	 	 	 		 	  
	}	 	
	include $this->template('cardlist');
	exit;	
}else if($op == 'poster'){

	$posterid=intval($_GPC['posterid']);	 	 
	$minfo=m('member')->getMember($tid); 	 		  		 	  
    $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('activity/card',array('tid'=>$tid)));  
    exit(html_entity_decode('<img src="'.$pt.'" width="100%" height="auto" />'));
}else if($op == 'invite'){
	$posterid=intval($_GPC['posterid']);	 	 
	$minfo=m('member')->getMember($tid); 	 		  		 	  
    $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('activity/card',array('tid'=>$tid)));  	 
    exit(html_entity_decode('<img src="'.$pt.'" width="100%" height="auto" />'));
}	 		 	 	 	 	 	 	 
 		 	 	

include $this->template('visiting');		//个人页面
