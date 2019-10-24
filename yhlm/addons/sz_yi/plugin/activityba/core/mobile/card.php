<?php

global $_W, $_GPC;
$openid = m('user')->getOpenid();
$op = empty($_GPC['op']) ? 'display':$_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid); 			 	
$member=m('member')->getMember($openid);
$tid=$_GPC['tid']?:$member['id'];
if (!$muser) {
	m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));	 		
} 	 	
if($op == 'display'){	 	 	
	$tmember=m('member')->getMember($tid);		 		 	
	$tuser=m('tools')->getMuser($tmember['openid']);	
	$banner=unserialize($tuser['banner']);
	foreach ($banner as $key => $value) {	 	 
		$banner[$key]=tomedia($value);	 	 	
	}	 	 	 		 	 	 	  	

	$msg=pdo_fetchall('select m.id tid,l.ctime,l.id,m.avatar,l.content,l.ctime,mu.realname from '.tablename('sz_yi_activity_leave_msg').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid left join '.tablename('sz_yi_member_user').' mu on mu.openid = m.openid where l.uniacid = :uniacid and l.muid = :uid order by l.id desc limit 0,5',array(':uniacid'=>$_W['uniacid'],':uid'=>$tuser['uid']));
	foreach ($msg as $key => $value) {
		$msg[$key]['link']=$this->createPluginMobileUrl('activity/card',array('tid'=>$value['tid']));
		$msg[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
 	}				 		 	  	 	 	 		 	 

}else if ($op == 'edit') {		 

	$tmember=m('member')->getMember($tid);
	$tinfo=m('tools')->getMuser($tmember['openid']);

	if ($ac == 'get') {
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
			'logo'=>$_GPC['logo'],	 	 	 	
			'business'=>trim($_GPC['business']),	 
			'wechatCode'=>$_GPC['wechatCode'],
			'supplier'=>$_GPC['provide_res'],	 	 
			'need'=>$_GPC['needed_res']	 	 
        );

        $re=pdo_update('sz_yi_member_user',$data,array('openid'=>$tinfo['openid']));	 	
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
		'muid'=>$uid,
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
	$tinfo=m('tools')->getMuser($tmember['openid']);
	$re=pdo_update('sz_yi_member_user',array('browse'=>$tinfo['browse']+1),array('id'=>$tinfo['id']));
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
	$re=pdo_update('sz_yi_member_user',$data,array('openid'=>$openid,'uniacid'=>$_W['uniacid']));		 
	if ($re) {
		show_json(1,'更新成功!');
	}	 	 	 		  	
	show_json(0,'更新失败!');
}else if ($op == 'cardlist'){	  	 

	$tid=$_GPC['tid'];
	$tmember=m('member')->getMember($tid);
	$tuser=m('tools')->getMuser($tmember['openid']);
	if (!$tuser) {
		m('tools')->tip('还不是活动商家无法生成海报');	 	
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
}	 	
 		 	 	

include $this->template('visiting');		//个人页面
