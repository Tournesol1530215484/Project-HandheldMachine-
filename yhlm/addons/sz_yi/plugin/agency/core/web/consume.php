<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC;




$pv = p('virtual');
$diyform_plugin = p('diyform');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'change') {
	
} else if ($operation == 'post') {

    
} elseif ($operation == 'display') {

	$exists=check_agent($_W['uid']);

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;	 	 
 	$staff=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
 	//获取代理商uid
 	$agency=p('bonus')->getMerch($staff['merchid']);
 	$agency=m('member')->getMember($agency['openid']);

 	if ($exists) {
		$sql='select o.*,pu.username merchname from '.tablename('sz_yi_perm_user').' pu left join '.tablename('sz_yi_order').' o on o.openid = pu.openid where pu.uniacid =:uniacid and pu.belong_staffid = :id';
		$sql.=' limit '.($pindex - 1) * $psize.','.$psize;		 	 	  	 
		$params=array(':uniacid'=>$_W['uniacid'],':id'=>$staff['id']);

		$sqls = 'SELECT COUNT(*) FROM ' .tablename('sz_yi_perm_user'). ' pu left join '. tablename('sz_yi_order').' o on o.openid = pu.openid where pu.uniacid =:uniacid and pu.belong_staffid = :id  ';
		$total = pdo_fetchcolumn($sqls, $params); 	 	 	
		$list = pdo_fetchall($sql, $params);	 	 	
		$pager = pagination($total, $pindex, $psize); 	

	}else{
		if ($agency['bonus_area'] == 1) {
		 		$params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
		 	}else if ($agency['bonus_area'] == 2) {
		 		$params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
		 	}else if ($agency['bonus_area'] == 3) {
		 		$params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
		 	}
			
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$_GPC['keyword'] = trim($_GPC['keyword']);
				$condition .= ' AND o.title LIKE :title';
				$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
			}
			if (!empty($_GPC['category']['thirdid'])) {
				$condition .= ' AND o.tcate = :tcate';
				$params[':tcate'] = intval($_GPC['category']['thirdid']);
			}
			if (!empty($_GPC['category']['childid'])) {
				$condition .= ' AND o.ccate = :ccate';
				$params[':ccate'] = intval($_GPC['category']['childid']);
			}
			if (!empty($_GPC['category']['parentid'])) {
				$condition .= ' AND o.pcate = :pcate';
				$params[':pcate'] = intval($_GPC['category']['parentid']);
			}
			if ($_GPC['status'] != '') {
				$condition .= ' AND o.status = :status';
				$params[':status'] = intval($_GPC['status']);
			}	 


			$sql='select o.*,du.username merchname from '.tablename('sz_yi_perm_user').' du left join '.tablename('sz_yi_order').' o on du.openid=o.openid where 1 ';
			if ($agency['bonus_area'] == 1) {
		 		$condition.=' and du.provance = :province and du.uniacid = :uniacid  ';
		 	}else if ($agency['bonus_area'] == 2) {
		 		$condition.=' and du.provance = :province and du.city = :city and du.uniacid = :uniacid ';
		 	}else if ($agency['bonus_area'] == 3) {
		 		$condition.=' and du.provance = :province and du.city = :city and du.area = :district and du.uniacid = :uniacid ';
		 	}

			$sql.=$condition.' limit '.($pindex - 1) * $psize.','.$psize;
			// $sql = 'SELECT * FROM ' . tablename('sz_yi_order') . $condition . " ORDER BY `status` DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$sqls = 'SELECT COUNT(*) FROM ' .tablename('sz_yi_perm_user'). ' du left join '. tablename('sz_yi_order').' o on o.openid = du.openid where 1 ' . $condition;
			$total = pdo_fetchcolumn($sqls, $params); 	 	 	
			$list = pdo_fetchall($sql, $params);	 	 	 
			$pager = pagination($total, $pindex, $psize); 
	}
 		
} elseif ($operation == 'delete') {
 
	$id = intval($_GPC['id']);
	$row = pdo_fetch('SELECT id, title, thumb FROM ' . tablename('sz_yi_order') . ' WHERE id = :id', array(':id' => $id,'supplier_uid'=>$_W['uid']));
	if (empty($row)) {
		message('抱歉，商品不存在或是已经被删除！');
	}
	pdo_update('sz_yi_order', array('deleted' => 1), array('id' => $id));
	plog('shop.goods.delete', "删除商品 ID: {$id} 标题: {$row['title']} ");
	message('删除成功！', referer(), 'success');
} elseif ($operation == 'setgoodsproperty') {
 
	$id = intval($_GPC['id']);
	$type = $_GPC['type'];
	$data = intval($_GPC['data']);
	if (in_array($type, array('new', 'hot', 'recommand', 'discount', 'time', 'sendfree', 'nodiscount'))) {
		$data = ($data == 1 ? '0' : '1');
		pdo_update('sz_yi_order', array('is' . $type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		if ($type == 'new') {
			$typestr = '新品';
		} else if ($type == 'hot') {
			$typestr = '热卖';
		} else if ($type == 'recommand') {
			$typestr = '推荐';
		} else if ($type == 'discount') {
			$typestr = '促销';
		} else if ($type == 'time') {
			$typestr = '限时卖';
		} else if ($type == 'sendfree') {
			$typestr = '包邮';
		} else if ($type == 'nodiscount') {
			$typestr = '不参与折扣状态';
		}
		plog('shop.goods.edit', "修改商品{$typestr}状态   ID: {$id}");
		die(json_encode(array('result' => 1, 'data' => $data)));
	}
	if (in_array($type, array('status'))) {
		$data = ($data == 1 ? '0' : '1');
		pdo_update('sz_yi_order', array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		plog('shop.goods.edit', "修改商品上下架状态   ID: {$id}");
		die(json_encode(array('result' => 1, 'data' => $data)));
	}
	if (in_array($type, array('type'))) {
		$data = ($data == 1 ? '2' : '1');
		pdo_update('sz_yi_order', array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		plog('shop.goods.edit', "修改商品类型   ID: {$id}");
		die(json_encode(array('result' => 1, 'data' => $data)));
	}
	die(json_encode(array('result' => 0)));
}
load()->func('tpl'); 	 
include $this->template('consume');	 	 		 	 	 