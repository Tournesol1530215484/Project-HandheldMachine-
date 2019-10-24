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

	$sg=pdo_fetchcolumn('select data from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid order by id desc',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
	$data=unserialize($sg);
	
	$accountinfo=m('match')->getAccountInfo($openid);
	$agent=m('member')->getMember($member['agentid']);
	$member['agent']=$agent?$agent['nickname']:'无';

}else if($op == 'edit'){  	


	$accountinfo=m('match')->getAccountInfo($openid);

	if (!$accountinfo) {			//防止分表信息丢失
		$actmerch=m('tools')->getMuser($openid); 	

		if ($actmerch) {
			 	 	 	 		
			$data=array(
				'openid'=>$openid,
				'uniacid'=>$_W['uniacid'],
				'realname'=>trim($actmerch['realname']),
				'mobile'=>trim($actmerch['mobile']),
				'wechat'=>trim($actmerch['wechat']),
				'logo'=>trim($actmerch['logo']),	 	 
				'wechatCode'=>trim($actmerch['wechatCode']),
				'orgMobile'=>trim($actmerch['orgMobile']),
				'address'=>trim($actmerch['address']),
				'contact'=>trim($actmerch['contact']),	 	 
				'orgName'=>trim($actmerch['orgName']),	 	 	 	 	 	 	 	 	 		 		 	 
				'job'=>trim($actmerch['job']), 	 	
				'ctime'=>time(),	 	 	 	 		 	 	 	  	 	
				'orgDesc'=>trim($actmerch['orgDesc'])
			);
			pdo_insert('sz_yi_match_account_info',$data);
			$accountinfo=pdo_fetch('select * from '.tablename('sz_yi_match_account_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		}
				
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
			
			$re=pdo_update('sz_yi_match_account_info',$data,array('uniacid'=>$_W['uniacid'],'openid'=>$openid));
			
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
	}else{
		m('tools')->tip('没有这个等级!');
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
		pdo_insert('sz_yi_match_recharge_log',$log); 		 	
		if ($re) {
			p('commission')->calcMemberCardBonus($openid,pdo_insertid(),$type,floatval($money)); 		 	 	 	 		 	 		 		
			show_json(1,'购买'.$str.'成功!');	  			 	 	
		}	 	 
		m('member')->setCredit($openid,$credit,$money);
		show_json(0,'购买'.$str.'失败!');	 	 			  
	}	 	 

	include $this->template('inrechargefo');
	exit;
}else if($op == 'settop'){

	$id=$_GPC['id'];
	$type=$_GPC['type'];

	$act=m('match')->getact($id,$type);
	$ordersn=m('common')->createNO('match', 'is_top', 'st');
	$obj=$type==1 ? '活动':'文章';	 	 
	$table=$type==1 ? 'sz_yi_match':'sz_yi_match_article';	 	 
	!$act && m('tools')->tip('没有该'.$obj);

	if ($_W['isajax']) {
		
		$act['openid'] != $openid && show_json(0,'没有该'.$obj);
		
		if ($ac == 'check') {
			
			show_json(1,'正确！');

		}else if($ac == 'sub'){	 	 	 	
			
			
			$toptype=$_GPC['toptype'];
			$paytype=$_GPC['paytype'];

			switch ($toptype) {
				case '1':
					$str='刷新';
					$fee=10;
					break;

				case '2':
					show_json(0,'置顶-优先开发中');
					break;

				case '3':
					$str='置顶';
					$fee=500;
					break;

				case '4':
					$str='王顶';
					$fee=1000;
					break;
				
				default:
					exit;
					break;
			}


			if ($paytype == 2) {
				$cstr='余额不足!';
				$credit='credit2';
			}else if($paytype == 3){
				$credit='credit3';
				$cstr='换货码不足!';
			}

			$currency=m('member')->getCredit($openid,$credit);
			$fee > $currency && show_json(0,$cstr);

			$log=array(
				'uniacid'=>$_W['uniacid'],
				'openid'=>$openid,
				'type'=>$type,
				'actid'=>$id,	 	 
				'toptype'=>$toptype,
				'fee'=>$fee,
				'ordersn'=>$_GPC['ordersn'],
				'paytype'=>$paytype,
				'ctime'=>time(),
			);
			
			$data=array(
				'is_top'=>$toptype,
				'toptime'=>time(),
			);	 		 		 	
		 	 	 	 	 	 	 	 

			$re=pdo_update($table,$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));

			if ($re) {	 	 	 	 
				m('member')->setCredit($openid,$credit,-$fee);		//扣除
				pdo_insert('sz_yi_match_settop_log',$log);		//记录日志
				p('commission')->calcSetopBonus($openid,$_GPC['ordersn'],pdo_insertid(),$fee);	//发放置顶分成
				show_json(1,'置顶成功！');
			}else{
				show_json(1,'置顶失败！');
			}

		}else if($ac == 'clear'){
			$re=pdo_update($table,array('is_top'=>0),array('id'=>$id,'uniacid'=>$_W['uniacid']));	
			if ($re) {
				show_json(1,'取消置顶成功!');
			}	
			show_json(1,'取消置顶失败!');
		}
	}
	

	include $this->template('settop');
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

	$re=m('match')->setBrowse($id,$type);

	if ($re) {
		show_json(1,'更新成功');
	} 	

	show_json(0,'更新失败');

}else if($op == 'like'){	//点赞 
	$id=$_GPC['id'];
	$type=$_GPC['type'];
	$str='sz_yi_match';
	if ($type==2) {
		$str='sz_yi_match_article';
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

	$exists=pdo_fetch('select * from '.tablename('sz_yi_match_like').' where uniacid = :uniacid and openid = :openid and atid = :id and ctime > :stime and ctime < :etime and type = :type',$params);

	if ($exists) {
		show_json(0,'你今天已经加油过了!');
	} 	   	 
	
	pdo_insert('sz_yi_match_like',$data);
	$lid=pdo_insertid();
	
	if ($lid) { 		 	
		$member=m('member')->getMember($openid);	 
		$act=m('match')->getact($id,$type);  	 	 	 		 	 		 	 	
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

	pdo_insert('sz_yi_match_comment',$data);

	$id=pdo_insertid();
	$member=m('member')->getMember($openid);
	if ($id) {
		show_json(1,array('member'=>$member));
	} 	 	 
	show_json(0,'评论失败');
}else if ($op == 'favorite') {
	$id=$_GPC['id'];
	$type=$_GPC['type'];
	$info=m('match')->getact($id,$type);
	$fropenid=$info['openid'];
	$re=m('tools')->favoriteMember($openid,$fropenid);	
	
	show_json(1,array('isfavorite'=>$re)); 	 		 	

}	 		 
include $this->template('index');		//个人页面
