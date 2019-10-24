<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC,$frames;

if ($_GPC['debug']) {
}

$setting = m('common')->getSetData();

$setting = unserialize($setting['plugins']);

$pv = p('virtual');
$diyform_plugin = p('diyform');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'change') {
	
} else if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;	
	$exists=check_agent($_W['uid']);
	$staff=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
	 	//获取代理商uid
	 	$agency=p('bonus')->getMerch($staff['merchid']);
	 	$agency=m('member')->getMember($agency['openid']);
	 	$condition = '';
		if (!empty($_GPC['title'])) {
			$_GPC['keyword'] = trim($_GPC['title']);
			$condition .= ' AND g.title LIKE :title';
			$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
		}

		if (!empty($_GPC['status'])) {
			if ($_GPC['status'] == 0) {
			}else if ($_GPC['status'] == 1) {	//saler
				$condition .= ' AND g.status = 1';
			}else if ($_GPC['status'] == 2) {
				$condition .= ' AND g.isCheck = 0';
			}else if ($_GPC['status'] == 3) {
				$condition .= ' AND g.isCheck = 0';
			}else if ($_GPC['status'] == 4) {
				$condition .= ' AND g.isCheck = 2';
			}else if ($_GPC['status'] == 5) {
				$condition .= ' AND g.status = 0';
			}else if ($_GPC['status'] == 6){
				$condition .= ' AND g.type = 8 and g.shelves = 3';
			}
		}

 	if ($exists) {	//如果是员工

 		$sql='select g.*,pu.username merchname from '.tablename('sz_yi_perm_user').' pu left join '.tablename('sz_yi_goods').' g on g.supplier_uid = pu.uid where pu.uniacid = :uniacid and pu.belong_staffid = :id';
 		$sql.=$condition.' limit '.($pindex - 1) * $psize.','.$psize;
		
		$sqls = 'SELECT COUNT(*) FROM ' .tablename('sz_yi_perm_user'). ' pu left join '. tablename('sz_yi_goods').' g on g.supplier_uid = pu.uid where 1 and pu.uniacid = :uniacid and pu.belong_staffid = :id';
		$total = pdo_fetchcolumn($sqls,array(':uniacid'=>$_W['uniacid'],':id'=>$staff['id'])); 	 	 	
		$list = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'],':id'=>$staff['id']));	
		$pager = pagination($total, $pindex, $psize);
	}else{
		

	 	if ($agency['bonus_area'] == 1) {
	 		$params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
	 	}else if ($agency['bonus_area'] == 2) {
	 		$params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
	 	}else if ($agency['bonus_area'] == 3) {
	 		$params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
	 	}
		
		
		if (!empty($_GPC['category']['thirdid'])) {
			$condition .= ' AND g.tcate = :tcate';
			$params[':tcate'] = intval($_GPC['category']['thirdid']);
		}
		if (!empty($_GPC['category']['childid'])) {
			$condition .= ' AND g.ccate = :ccate';
			$params[':ccate'] = intval($_GPC['category']['childid']);
		}
		if (!empty($_GPC['category']['parentid'])) {
			$condition .= ' AND g.pcate = :pcate';
			$params[':pcate'] = intval($_GPC['category']['parentid']);
		}
		if ($_GPC['status'] != '') {
			$condition .= ' AND g.status = :status';
			$params[':status'] = intval($_GPC['status']);
		}	 	
		$sql='select g.*,du.username merchname from '.tablename('sz_yi_perm_user').' du left join '.tablename('sz_yi_goods').' g on du.uid=g.supplier_uid where 1 ';
		if ($agency['bonus_area'] == 1) { 	 
	 		$condition.=' and du.provance = :province and du.uniacid = :uniacid and g.deleted = 0 ';
	 	}else if ($agency['bonus_area'] == 2) {
	 		$condition.=' and du.provance = :province and du.city = :city and du.uniacid = :uniacid and g.deleted = 0 ';
	 	}else if ($agency['bonus_area'] == 3) {
	 		$condition.=' and du.provance = :province and du.city = :city and du.area = :district and du.uniacid = :uniacid and g.deleted = 0 ';
	 	}
	 	
		$sql.=$condition.' limit '.($pindex - 1) * $psize.','.$psize;
		// $sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . " ORDER BY `status` DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$sqls = 'SELECT COUNT(*) FROM ' .tablename('sz_yi_perm_user'). ' du left join '. tablename('sz_yi_goods').' g on g.supplier_uid = du.uid where 1 ' . $condition; 
		$total = pdo_fetchcolumn($sqls, $params); 	 	 	
		$list = pdo_fetchall($sql, $params);	
		$pager = pagination($total, $pindex, $psize);
	}
	 	 
	$pager = pagination($total, $pindex, $psize); 	
} elseif ($operation == 'delete') {
 
	
} elseif ($operation == 'setgoodsproperty') {
 
}
load()->func('tpl');
include $this->template('dealgoods');	  	 	 		 	 	 