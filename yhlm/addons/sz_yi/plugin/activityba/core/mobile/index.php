<?php

global $_W, $_GPC;
$openid = m('user')->getOpenid();	 	 
$op = empty($_GPC['op']) ? 'display':$_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid); 			
$member=m('member')->getMember($openid);
if ($_GPC['debug']) {
	
}
// if (!$muser) {		 		 		 		 		 		 
	// m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));	 		
// }

if ($op == 'display') {
	$agent=m('member')->getMember($member['agentid']);
	$member['agent']=$agent?$agent['nickname']:'无';
}else if($op == 'edit'){  				 	 
	if (!$muser) {	  		 	 		 	 
		m('tools')->tips('你还没有注册易活动,请先注册!',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));
	} 		 		
	if ($_GPC['what'] == 'pwd') {
		$exists=pdo_fetchcolumn('select uid from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		if (!$exists) {	 
			!$_W['isajax'] && m('tools')->tip('你还没有注册,请先注册!');
			$_W['isajax'] && show_json(0,'你还没有注册,请先注册!');	 
		}
	}

	$tpu=p('bonus')->getMerch($muser['uid']);

	if ($ac == 'sub') {
		$what=trim($_GPC['what']);
		if ($what == 'info') {
 		 
			$data=array(
				'realname'=>trim($_GPC['realname']),
				'mobile'=>trim($_GPC['mobile']),
				'wechat'=>trim($_GPC['wechat']),
				'logo'=>trim($_GPC['logo']),	 	 
				'wechatCode'=>trim($_GPC['wechatCode']),
				'orgMobile'=>trim($_GPC['orgMobile']),
				'address'=>trim($_GPC['address']),
				'contact'=>trim($_GPC['contact']),	 	 
				'orgName'=>trim($_GPC['orgName']),	 	 	 	 	 	 	 	 	 		 		 	 
				'job'=>trim($_GPC['job']), 	 	
				'orgDesc'=>trim($_GPC['orgDesc'])
			);

			$re=pdo_update('sz_yi_member_user',$data,array('uniacid'=>$_W['uniacid'],'openid'=>$openid));
			
			if ($re) {
				show_json(1,'修改成功!');
			}
			show_json(0,'修改失败!');
		}else if($what == 'pwd'){
			$pwd=$_GPC['password'];
			$repwd=$_GPC['re_password'];
			$pwd != $repwd && show_json(0,'两次输入密码不一致!'); 					 				 	 	

			$user=pdo_fetch('select * from '.tablename('users').' where uid = :uid',array(':uid'=>$exists));
			$tuser=array(
				'salt'=>$user['salt'],
				'password'=>$pwd,
				'uid'=>$exists
			);
			// show_json(0,$tuser);	 	 	
			$re=user_update($tuser);
			if ($re) {
				show_json(1,'修改成功!');
			}	  
			show_json(0,'修改失败!');
		}
		
	}

	include $this->template('info');	//	修改
	exit;
}else if($op == 'rechagre'){

	if (!$muser) {	  		 	 		 	 
		m('tools')->tips('你还没有注册易活动,请先注册!',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));
	}	 	 	 	 	 	 				 	

	$type=intval($_GPC['type']);
 		 	 	 
	empty($type) && m('tools')->tip('非法参数!'); 	 	
	if ($type == 1) {
		$money=68;
		$str='初级会员';
	}else if($type == 2){
		$money=680;
		$str='中级会员'; 	 	
	}else if($type ==3){	  
		$money=6800;
		$str='高级会员';
	}
	$money=floatval($money); 		 
	$ordersn=m('common')->createNO('member_user', 'level', 'act');

	if ($ac == 'sub' && $_W['isajax']) {

		$data=array(
			'level'=>$type
		); 	 	 		 	 	 		 	 	
 	 	
 	 	$muser['level'] == $type && show_json(0,'购买'.$str.'失败,你已经是'.$str.'!');	 	

		$log=array( 			 	 	
			'uniacid'=>$_W['uniacid'], 	 		 	  	 	
			'openid'=>$openid,	 	 	 		 	  
			'fee'=>floatval($money), 	 		 	 	 		   	 	 	 
			'level'=>$type,	 	 
			'ordersn'=>$_GPC['ordersn'], 	 	 	 
			'paytype'=>$_GPC['paytype'], 		 	  	 	 
			'type'=>1,	 	 //充值方式  1	 	  		 	  	 	 
			'ctime'=>time(), 	 		 	 			 	  	 		 	  	  		
			'etime'=>strtotime('+1 year') 	 		 	 			 	  	 		 	  	  		
		);	 	

		if ($log['type'] != 1) { 	 	

			if ($log['paytype'] ==2) {
				$paytype='余额';	 	
				$credit='credit2';	 	 		 
			}else if($log['paytype'] ==3){		 	  	 
				$paytype='换货码';			 	 	 			 	 	 
				$credit='credit3';	 	 		 
			}	 	 	 	 	 	 

			$currency=m('member')->getCredit($openid,$credit);		  	 	 

			$currency < $money && show_json(0,$paytype.'不足,购买会员失败');

			m('member')->setCredit($openid,$credit,-$money);	 	 	   	 	 

		}	 	 
		 	 
		$re=pdo_update('sz_yi_member_user',$data,array('uniacid'=>$_W['uniacid'],'openid'=>$openid));
		pdo_insert('sz_yi_activity_recharge_log',$log); 		 	
		if ($re) {
			p('commission')->calcMemberCardBonus($openid,pdo_insertid(),$type,floatval($money)); 		 	 	 	 		 	 		 		
			show_json(1,'购买'.$str.'成功!');	  			 	 	
		}	 	 
		m('member')->setCredit($openid,$credit,$money);
		show_json(0,'购买'.$str.'失败!');	 	 			  
	}	 	 

	include $this->template('inrechargefo');
	exit;
}else if($op == 'checkmember'){ 
	if (is_weixin()) {
		m('member')->checkMember();
		show_json(1,'更新成功!');
	}else{
		show_json(0,'更新失败!请使用微信打开');
	}
	exit; 	 	 
}else if($op == 'browse'){

	$id=intval($_GPC['id']);
	$type=intval($_GPC['type']);

	if ($type ==3 && !$id) {
		$id=$member['id'];
	}

	empty($id) && show_json(0,'非法参数!');
	empty($type) && show_json(0,'非法参数!');

	$re=m('activity')->setBrowse($id,$type);

	if ($re) {
		show_json(1,'更新成功');
	} 	

	show_json(0,'更新失败');

}else if($op == 'like'){	//点赞 
	$id=$_GPC['id'];
	$type=$_GPC['type'];
	$str='sz_yi_activity';
	if ($type==2) {
		$str='sz_yi_activity_article';
	}else if($type == 3){
		$str='sz_yi_member_user';		//用户
		if (!$id) {
			$id=$member['id'];
		}
	}

	empty($id) && show_json(0,'非法参数!');
	empty($type) && show_json(0,'非法参数!');

	$data=array(
		'uniacid'=>$_W['uniacid'],
		'type'=>$_GPC['type'],
		'atid'=>$id,		//member id
		'openid'=>$openid,
		'ctime'=>time()
	);

	$time=m('time')->today();
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':openid'=>$openid,
		':id'=>$id, 		//mid	 	
		':type'=>$_GPC['type'],
		':stime'=>$time[0],
		':etime'=>$time[1],
	);

	$exists=pdo_fetch('select * from '.tablename('sz_yi_activity_like').' where uniacid = :uniacid and openid = :openid and atid = :id and ctime > :stime and ctime < :etime and type = :type',$params);

	if ($exists) {
		show_json(0,'你今天已经加油过了!');
	} 	   	 
	
	pdo_insert('sz_yi_activity_like',$data);
	$lid=pdo_insertid();
	
	if ($lid) { 		 	
		$member=m('member')->getMember($openid);	 
		$act=m('activity')->getact($id,$type);  	 	 	 		 	 		 	 	
		pdo_update($str,array('like'=>$act['like']+1),array('id'=>$act['id'],'uniacid'=>$_W['uniacid']));
		show_json(1,array('member'=>$member));	 	 	 		 	 	
	}	 	
 		 	 	
	show_json(0,'加油失败!');

}else if($op == 'comment'){
	$id=$_GPC['id'];
	$type=$_GPC['type'];	
		
	if (!$id && $type == 3) {
		$id=$member['id'];
	}	 	 	

	empty($id) && show_json(0,'非法参数');	
	empty($type) && show_json(0,'非法参数');	

	$data=array(
		'uniacid'=>$_W['uniacid'],
		'openid'=>$openid,
		'atid'=>$id,		//mid
		'type'=>$type,
		'ctime'=>time(),
		'content'=>trim($_GPC['comment'])
	);

	pdo_insert('sz_yi_activity_comment',$data);

	$id=pdo_insertid();
	$member=m('member')->getMember($openid);
	if ($id) {
		show_json(1,array('member'=>$member));
	} 	 	 
	show_json(0,'评论失败');
}else if ($op == 'favorite') {
	$id=$_GPC['id'];
	$type=$_GPC['type'];
	$info=m('activity')->getact($id,$type);
	$fropenid=$info['openid'];
	$re=m('tools')->favoriteMember($openid,$fropenid);	
	
	show_json(1,array('isfavorite'=>$re)); 	 		 	

}	 		 
include $this->template('index');		//个人页面
