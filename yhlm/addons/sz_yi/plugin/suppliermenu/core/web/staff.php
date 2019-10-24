<?php
	if (!defined('IN_IA')){

	    exit('Access Denied');

	} 
	global $_W, $_GPC;

	$op = empty($_GPC['op']) ? 'display' : trim($_GPC['op']);
	// var_dump($op); die;
	if ($op == 'display') {

		$where = ' and merchid=:uid ';

		$pindex = max(1, $_GPC['page']);

		$psize = 10;

		$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_staff').' where uniacid = :uniacid '.$where , array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

		$pager = pagination($total, $pindex, $psize);

		$sql = 'select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid '.$where.' order by id asc limit '.($pindex - 1)*$psize. ',' .$psize ;
		$info = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']) );

	}else if ( $op == 'edit'){
        $id=$_GPC['id'];
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':uid'=>$_W['uid'],
            ':id'=>$id
        );
        $addrInfo=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and merchid = :uid and id = :id',$params);
		$addrInfo['exchangeDate']=explode(',',$addrInfo['exchangeDate']);  
	} else if ($op == 'post') {
        $id = $_GPC['id'];

        $data = array(

            'name'=>$_GPC['name'],

            'idcard'=>$_GPC['idcard'],

            'merchid'=>$_W['uid'],
            
            'mobile'=>$_GPC['mobile'],

            'uniacid'=>$_W['uniacid'],

            'card_type'=>$_GPC['card_type'],

            'front'=>tomedia($_GPC['front']),
            
            'reverse'=>tomedia($_GPC['reverse'])
        ); 
        !$_GPC['name'] && message('员工名不能为空',referer(),'error');
        !$_GPC['idcard'] && message('证件号码不能为空',referer(),'error');
        !$_GPC['front'] && message('证件正面不能为空',referer(),'error');
        !$_GPC['reverse'] && message('证件反面不能为空',referer(),'error');

        if (empty($id)){
            pdo_insert('sz_yi_staff',$data);

            $insid = pdo_insertid();
            if (empty($insid)) {

                message('添加失败',$this->createPluginWebUrl('suppliermenu/staff'),'error');  exit;

            } else {
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
				plog('perm.user.add', "添加员工 ID: {$id} 用户名: {$data['name']} ");
                message('添加成功',$this->createPluginWebUrl('suppliermenu/staff'),'success');
            }
        }else{

            $updid=pdo_update('sz_yi_staff',$data,array('id'=>$id,'merchid'=>$_W['uid']));

            if (empty($updid)) {

                message('修改失败',$this->createPluginWebUrl('suppliermenu/staff'),'error');  exit;

            } else {
                message('修改成功',$this->createPluginWebUrl('suppliermenu/staff'),'success');  exit;

            }

        }


	} else if ($op == 'get') {
		
		$id = intval($_GPC['id']);
		
		$dealmerchinfo = pdo_fetch('select * from '.tablename('sz_yi_staff').' where id = :id limit 1', array(':id'=>$id,'merchid'=>$_W['uid']));

		// echo json_encode($dealmerchinfo);
		show_json(1, $dealmerchinfo);

	} else if ($op == 'delete') {

		$id = $_GPC['id'];

		if (empty($id)) {
			message('请选择数据', $this->createPluginWebUrl('suppliermenu/staff'));
		}
		
		$res = pdo_delete('sz_yi_staff', array('id'=>$id,'merchid'=>$_W['uid']));

		if (!$res) {
			message('删除失败', $this->createPluginWebUrl('suppliermenu/staff'), 'fail');
		} else {
			message('删除成功', $this->createPluginWebUrl('suppliermenu/staff'), 'success');
		}
	}
	load()->func('tpl');
	load()->model('mc');

	include $this->template('staff');

