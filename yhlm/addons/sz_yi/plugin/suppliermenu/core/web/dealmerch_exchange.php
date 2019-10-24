<?php
	if (!defined('IN_IA')){

	    exit('Access Denied');

	} 
	global $_W, $_GPC;

	$op = empty($_GPC['op']) ? 'display' : trim($_GPC['op']);
	// var_dump($op); die;
	if ($op == 'display') {

		$where = ' and merch_uid=:uid ';

		if ($_GPC['status'] != '所有' ) {
			$where = ' and status = '.intval($_GPC['status']);
		} else {
			$where = '';
		}

		if (!empty($_GPC['titormob'])) {
			$where = ' and (title in '.$_GPC['titormob'].' or mobile = '.$_GPC['titormob'].')';
		} else {
			$where = '';
		}

		$pindex = max(1, $_GPC['page']);

		$psize = 10;

		$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid '.$where , array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

		$pager = pagination($total, $pindex, $psize);

		$sql = 'select * from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid '.$where.' and merch_uid = :uid order by id asc limit '.($pindex - 1)*$psize. ',' .$psize ;
		$info = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']) );
		foreach ($info as $k => $v) {
			
			$info[$k]['exchangeDate'] = explode(',', $v['exchangeDate']);
			
			foreach ($info[$k]['exchangeDate'] as $key => $d) {
				
				switch($d) {
					case '1': $info[$k]['exchangeDate'][$key] = '周一'; continue; 
					case '2': $info[$k]['exchangeDate'][$key] = '周二'; continue; 
					case '3': $info[$k]['exchangeDate'][$key] = '周三'; continue; 
					case '4': $info[$k]['exchangeDate'][$key] = '周四'; continue; 
					case '5': $info[$k]['exchangeDate'][$key] = '周五'; continue; 
					case '6': $info[$k]['exchangeDate'][$key] = '周六'; continue; 
					case '7': $info[$k]['exchangeDate'][$key] = '周日'; continue; 
 				}
			}
 			
 			$info[$k]['exchangeDate'] = implode(',' , $info[$k]['exchangeDate']);

		}
		// echo '<pre>'; var_dump($info);die; exit; 
 
	}else if ( $op == 'edit'){
        $id=$_GPC['id'];
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':uid'=>$_W['uid'],
            ':id'=>$id
        );
        $addrInfo=pdo_fetch('select * from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid and merch_uid = :uid and id = :id',$params);
		$addrInfo['exchangeDate']=explode(',',$addrInfo['exchangeDate']);  
	} else if ($op == 'post') {
        $id = $_GPC['id'];
        //add 

        $data = array(

            'title'=>$_GPC['title'],

            'address'=>$_GPC['address'],

            'mobile'=>$_GPC['mobile'],

            'merch_uid'=>$_W['uid'],

            'uniacid'=>$_W['uniacid'],
            //经度
            'lng'=>$_GPC['map']['lng'],
            //纬度 
            'lat'=>$_GPC['map']['lat'],

            'exchangeTime'=>$_GPC['exchangeTime'],

            'exchangeDate'=>implode(',', $_GPC['exchangeDate']),

            'status'=>intval($_GPC['status'])
        ); 

        if (empty($id)){
            pdo_insert('sz_yi_exchange_address',$data);

            $insid = pdo_insertid();
            if (empty($insid)) {

                message('添加失败',$this->createPluginWebUrl('suppliermenu/dealmerch_exchange'),'error');  exit;

            } else {
                message('添加成功',$this->createPluginWebUrl('suppliermenu/dealmerch_exchange'),'success');  exit;

            }
            //edit
        }else{

            $updid=pdo_update('sz_yi_exchange_address',$data,array('id'=>$id,'merch_uid'=>$_W['uid']));

            if (empty($updid)) {

                message('修改失败',$this->createPluginWebUrl('suppliermenu/dealmerch_exchange'),'error');  exit;

            } else {
                message('修改成功',$this->createPluginWebUrl('suppliermenu/dealmerch_exchange'),'success');  exit;

            }

        }


	} else if ($op == 'get') {
		
		$id = intval($_GPC['id']);
		
		$dealmerchinfo = pdo_fetch('select * from '.tablename('sz_yi_exchange_address').' where id = :id limit 1', array(':id'=>$id,'merch_uid'=>$_W['uid']));

		// echo json_encode($dealmerchinfo);
		show_json(1, $dealmerchinfo);

	} else if ($op == 'delete') {

		$id = $_GPC['id'];

		if (empty($id)) {
			message('请选择数据', $this->createPluginWebUrl('suppliermenu/dealmerch_exchange'));
		}
		
		$res = pdo_delete('sz_yi_exchange_address', array('id'=>$id,'merch_uid'=>$_W['uid']));

		if (!$res) {
			message('删除失败', $this->createPluginWebUrl('suppliermenu/dealmerch_exchange'), 'fail');
		} else {
			message('删除成功', $this->createPluginWebUrl('suppliermenu/dealmerch_exchange'), 'success');
		}

	}
	$tempopenid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
	$tempinfo=m('member')->getMember($tempopenid);
	$agency=empty($tempinfo['bonus_area'])?false:true;
	load()->func('tpl');
	load()->model('mc');

	include $this->template('dealmerch_exchange');

exit;