<?php
	if (!defined('IN_IA')){

	    exit('Access Denied');

	} 
	global $_W, $_GPC;

	$cauth=check_agent($_W['uid']);
	$cauth && message('你没有权限',$this->createPluginWebUrl('agency/bonus_for_agency'),'warning');
	$op = empty($_GPC['op']) ? 'display' : trim($_GPC['op']);
	if ($op == 'display') {

		$where = ' and merchid=:uid ';

		$pindex = max(1, $_GPC['page']);

		$psize = 10;

		$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_staff').' where uniacid = :uniacid '.$where , array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

		$pager = pagination($total, $pindex, $psize);

		$sql = 'select s.*,u.status enable from '.tablename('sz_yi_staff').' s left join '.tablename('users').' u on u.uid=s.uid where s.uniacid = :uniacid '.$where.' order by s.id asc limit '.($pindex - 1)*$psize. ',' .$psize ;
		$info = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']) );

	}else if ( $op == 'edit'){
        $id=$_GPC['id'];
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':uid'=>$_W['uid'],
            ':id'=>$id
        );
        $addrInfo=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and merchid = :uid and id = :id',$params);
        $member=m('member')->getMember($addrInfo['mid']);
	} else if ($op == 'post') {
        $id = $_GPC['id'];

        $data = array(

            'name'=>$_GPC['name'],

            'idcard'=>$_GPC['idcard'],

            'merchid'=>$_W['uid'],
            
            'mobile'=>$_GPC['mobile'],

            'mid'=>$_GPC['mid'],

            'uniacid'=>$_W['uniacid'],

            'card_type'=>$_GPC['card_type'],

            'front'=>tomedia($_GPC['front']),
            
            'ctime'=>time(),

            'reverse'=>tomedia($_GPC['reverse'])
        ); 		  
        
        !$_GPC['name'] && message('员工名不能为空',referer(),'error');
        !$_GPC['idcard'] && message('证件号码不能为空',referer(),'error');
        !$_GPC['mid'] && message('请选择会员',referer(),'error');

        $exists=p('agency')->getMStaff($_GPC['mid']);
        $exists && message('所选择会员已成为员工或代理商',$this->createPluginWebUrl('agency/staff'),'error');

        if (empty($id)){
        !$_GPC['password'] && message('密码不能为空',referer(),'error');
            pdo_insert('sz_yi_staff',$data);

            $insid = pdo_insertid();
            if (empty($insid)) {

                message('添加失败',$this->createPluginWebUrl('agency/staff'),'error');

            } else {
				unset($data);
				$data=array();
				$work=array();
            	// $work['worknumber']='YC0'.rand(10000,99999).$insid;

            	$work['worknumber']='C';
                for ($i=0; $i <  6-strlen($insid); $i++) { 
					$work['worknumber'].='0';
                }
                $work['worknumber'].=$insid;

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
				plog('perm.user.add', "添加员工 ID: {$id} 用户名: {$data['username']} ");
                message('添加成功',$this->createPluginWebUrl('agency/staff'),'success');
            }
        }else{
        	if (!empty($_GPC['password'])) {
        		$info=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        		$salt=pdo_fetchcolumn('select salt from '.tablename('users').' where uid = :uid ',array(':uid'=>$info['uid'])); 	 	
        		user_update(array('username' =>$info['worknumber'], 'password' => $_GPC['password'],'salt'=>$salt,'uid'=>$info['uid']));
				
				$pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $info['uid']));
				pdo_update('sz_yi_perm_user',array('password'=>$pwd['password']),array('uid'=>$info['uid']));
        	}

            $updid=pdo_update('sz_yi_staff',$data,array('id'=>$id,'merchid'=>$_W['uid']));

            if (empty($updid)) {

                message('修改失败',$this->createPluginWebUrl('agency/staff'),'error');  exit;

            } else {
                message('修改成功',$this->createPluginWebUrl('agency/staff'),'success');  exit;

            }

        }


	} else if ($op == 'change') {
		

		$uid = intval($_GPC['uid']);
		$status = intval($_GPC['status']);

		$sure=pdo_update('users',array('status'=>$status),array("uid"=>$uid));
		if ($status == 2) {
		
			pdo_update('sz_yi_staff',array('status'=>1),array("uid"=>$uid));
		}else{
			pdo_update('sz_yi_staff',array('status'=>0),array("uid"=>$uid));
		}

		if ($sure) {	 		 
			show_json(1);
		}else{
			show_json(0,'更新失败');
		}

		//------------------------------------------------------------------------------------------------

		// $dealmerchinfo = pdo_fetch('select * from '.tablename('sz_yi_staff').' where id = :id limit 1', array(':id'=>$id,'merchid'=>$_W['uid']));

		// // echo json_encode($dealmerchinfo);

	} else if ($op == 'delete') {

		$id = $_GPC['uid'];

		if (empty($id)) {
			message('非法参数', $this->createPluginWebUrl('agency/staff'));
		}
			 	 	 
		$res1 = pdo_delete('sz_yi_staff', array('uid'=>$_GPC['uid'],'uniacid'=>$_W['uniacid']));

		$res2 = pdo_delete('users', array('uid'=>$_GPC['uid']));

		$res3 = pdo_delete('sz_yi_perm_user', array('uid'=>$_GPC['uid'],'uniacid'=>$_W['uniacid']));

		if (!$res1 && !$res2 && !$res3) {  	  	 	 	 
			message('删除失败', $this->createPluginWebUrl('agency/staff'), 'fail');
		} else {
			message('删除成功', $this->createPluginWebUrl('agency/staff'), 'success');
		}
	}else if ($op == 'clear'){
		$spall=pdo_fetchall('select uid from '.tablename('sz_yi_perm_user').' where uniacid =:uniacid and staffid > 0',array(':uniacid'=>$_W['uniacid']));
		$sf=pdo_fetchall('select * from '.tablename('sz_yi_staff').' where uniacid =:uniacid ',array(':uniacid'=>$_W['uniacid']));
		$sfid=array();
		foreach ($sf as $key => $value) {
			$sfid[]=$value['uid'];
		}
		foreach ($spall as $key => $value) {
			if (in_array($value['uid'],$sfid)) {
				unset($spall[$key]);
			}
		}
		if (empty($spall)) {
		echo '没有冗余数据!';exit;
			
		}	 	 
		foreach ($spall as $key => $value) { 	 
			pdo_delete('sz_yi_perm_user',array('uid'=>$value['uid'],'uniacid'=>$_W['uniacid']));
			pdo_delete('users',array('uid'=>$value['uid']));
		}
		echo '冗余清除完毕!';exit;
	}
	load()->func('tpl');
	load()->model('mc');

	include $this->template('staff');

