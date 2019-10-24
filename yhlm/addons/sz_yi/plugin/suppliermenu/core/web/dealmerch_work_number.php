<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W, $_GPC;

$op=empty($_GPC['op'])?'display':$_GPC['op'];
    $uid=intval($_W['uid']);
    $merch=p('bonus')->getMerch($uid);
    $member=m('member')->getMember($merch['openid']);
    $agency=pdo_fetchcolumn('select id from '.tablename('sz_yi_staff').' where uniacid = :uniacid and merchid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
    //成为代理商后初次访问
    if ($member['bonus_area'] > 0 && empty($agency)) {
		$op='create';

		if ($_GPC['ac'] == 'submit') {
			// var_dump($_GPC);exit;	 	 
			$data = array(
				'name'=>$_GPC['name'],

	            'merchid'=>$_W['uid'],
	            
	            'mobile'=>$_GPC['mobile'],
	            
	            'isagent'=>1, 			//boos	 

	            'uniacid'=>$_W['uniacid'],

	            'ctime'=>time()
	        ); 

			!$_GPC['name'] && message('员工名不能为空',referer(),'error');
       		!$_GPC['password'] && message('密码不能为空',referer(),'error');
       		!$_GPC['mobile'] && message('手机号不能为空',referer(),'error');

			pdo_insert('sz_yi_staff',$data);
    	    $insid = pdo_insertid();
    	    
			unset($data);
			$data=array();
			$work=array();
        	$work['worknumber']='YC0'.rand(10000,99999).$insid;
        	$tempuid= user_register(array('username' =>$work['worknumber'], 'password' => $_GPC['password']));
			$pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $tempuid));
			$work['uid']=$tempuid;
            pdo_update('sz_yi_staff',$work,array('id'=>$insid));
			pdo_insert('uni_account_users', array('uid' => $tempuid, 'uniacid' => $_W['uniacid'], 'role' => 'operator'));
			$data['staffid']  = $insid; 
			$data['password'] = $pwd['password'];
			$data['username'] = $work['worknumber'];
			$data['roleid'] = 47;
			$data['status'] = 1;
			$data['uid']	= $tempuid; 	
			$data['uniacid'] = $_W['uniacid'];
			pdo_insert('sz_yi_perm_user', $data);
			$id = pdo_insertid();
			plog('perm.user.add', "易货商家添加代理商工号 ID: {$id} 用户名: {$data['username']} ");
            message('添加成功',$this->createPluginWebUrl('suppliermenu/dealmerch_work_number'),'success');
		}

		
    }
    $info=pdo_fetch('select merchid,uid,name,worknumber,mobile,ctime from '.tablename('sz_yi_staff').' where uniacid =:uniacid and merchid= :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));

    $agent=pdo_fetch('select merchid,uid,name,worknumber,mobile,ctime from '.tablename('sz_yi_staff').' where uniacid =:uniacid and uid= :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$info['merchid']));

    $agmerch=p('bonus')->getMerch($agent['merchid']);
    $agmember=m('member')->getMember($agmerch['openid']);
    if (!$agmember) {
    	$agmember=$member;
    }
load() -> func('tpl'); 	
include $this -> template('dealmerch_work_number');
exit;
