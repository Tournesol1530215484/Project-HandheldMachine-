<?php
	if (!defined('IN_IA')){

	    exit('Access Denied');

	} 
	global $_W, $_GPS;

	$op = empty($_GPC['op']) ? 'display' : trim($_GPC['op']);
	// var_dump($op); die;  
	if ($op == 'display') {
		
		$where = '';
		
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

		$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid '.$where , array(':uniacid'=>$_W['uniacid']));

		$pager = pagination($total, $pindex, $psize);

		$sql = 'select * from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid '.$where.' order by id asc limit '.($pindex - 1)*$psize. ',' .$psize ;

		$info = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid']) );

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

	} else if ($op == 'post') {

		$id = intval($_GPC['id']);
		
		$data = array(

			'title'=>$_GPC['title'],

			'address'=>$_GPC['address'],

			'mobile'=>$_GPC['mobile'],

			'uniacid'=>$_W['uniacid'],
			//经度
			'lng'=>$_GPC['map']['lng'],
			//纬度
			'lat'=>$_GPC['map']['lat'],

			'exchangeTime'=>$_GPC['exchangeTime'],

			'exchangeDate'=>implode(',', $_GPC['exchangeDate']),

			'status'=>intval($_GPC['status'])

		);
		// var_dump($data);die; exit; 

		if (!$id) {

			pdo_insert('sz_yi_exchange_address',$data);

			$insid = pdo_insertid();

			if (empty($insid)) {
				
				show_json(0,'添加失败');  exit; 
			
			} else {
				
				show_json(1, '添加成功'); exit; 
			
			}

		}	else {
			
			$res = pdo_update('sz_yi_exchange_address', $data, array('id'=>$id));
			// show_json($res);
			if (!empty($res)) {
				
				show_json(1,'修改成功'); exit; 
	 
			} else {
				
				show_json(0,'修改失败'); exit; 
			}
		}

	} else if ($op == 'get') {
		
		$id = intval($_GPC['id']);
		
		$dealmerchinfo = pdo_fetch('select * from '.tablename('sz_yi_exchange_address').' where id = :id limit 1', array(':id'=>$id));

		// echo json_encode($dealmerchinfo);
		show_json(1, $dealmerchinfo);

	} else if ($op == 'delete') {

		$id = $_GPC['id'];

		if (empty($id)) {
			message('请选择数据', $this->createPluginWebUrl('dealmerch/dealmerch_exchange'));
		}
		
		$res = pdo_delete('sz_yi_exchange_address', array('id'=>$id));

		if (!$res) {
			message('删除失败', $this->createPluginWebUrl('dealmerch/dealmerch_exchange'), 'fail');
		} else {
			message('删除成功', $this->createPluginWebUrl('dealmerch/dealmerch_exchange'), 'success');
		}

	}
	load()->func('tpl');

	load()->model('mc');

	include $this->template('dealmerch_exchange');

