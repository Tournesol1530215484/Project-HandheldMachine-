<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC;


$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'giveup') {
	empty($_GPC['uid']) && message('非法参数',referer(),'error');
	$pu=array(
		'belong_staffid'=>0
	);
	pdo_update('sz_yi_perm_user',$pu,array('uid'=>$_GPC['uid']));

	message('放弃成功!',$this->createPluginWebUrl('agency/merch'),'success');
} else if ($operation == 'claim') {
	empty($_GPC['uid']) && message('非法参数',referer(),'error');
	$staffid=pdo_fetchcolumn('select id from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
	$pu=array(
		'belong_staffid'=>$staffid
	);
	pdo_update('sz_yi_perm_user',$pu,array('uid'=>$_GPC['uid']));

	message('认领成功!',$this->createPluginWebUrl('agency/merch'),'success');
} elseif ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$exists=check_agent($_W['uid']);
	if ($exists) {			//如果存在就是员工
 		$staff=$exists;
		$belong_staffid=pdo_fetchcolumn('select id from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
 	}else{
 		$staff=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
 	}	 	 
 	//获取代理商uid
 	$agency=p('bonus')->getMerch($staff['merchid']);

 	$agency=m('member')->getMember($agency['openid']);
 	if ($agency['bonus_area'] == 1) {
 		$params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
 	}else if ($agency['bonus_area'] == 2) {
 		$params=array(':province'=>$agency['bonus_province'],':city'=>$agency['bonus_city'],':uniacid'=>$_W['uniacid']);
 	}else if ($agency['bonus_area'] == 3) {
 		$params=array(':province'=>$agency['bonus_province'],':city'=>$agency['bonus_city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
 	} 	 	 		 	  	 	
	
	$condition = ' and pu.uniacid = :uniacid  ';


/*	if (!empty($_GPC['keyword'])) {
		$_GPC['keyword'] = trim($_GPC['keyword']);
		$condition .= ' AND g.title LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
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
	}	*/ 	

	$sql='select pu.*,m.mobile mmobile from '.tablename('sz_yi_perm_user').' pu left join '.tablename('sz_yi_member').' m  on  pu.openid = m.openid where 1 ';	 	
	if ($agency['bonus_area'] == 1) {
 		$condition.=' and pu.provance = :province ';
 	}else if ($agency['bonus_area'] == 2) {
 		$condition.=' and pu.provance = :province and pu.city = :city ';
 	}else if ($agency['bonus_area'] == 3) {
 		$condition.=' and pu.provance = :province and pu.city = :city and pu.area = :district ';
 	}

	$sql.=$condition.' order by uid desc limit '.($pindex - 1) * $psize.','.$psize;
	// $sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . " ORDER BY `status` DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	$sqls = 'SELECT COUNT(*) FROM ' .tablename('sz_yi_perm_user'). ' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where 1 ' . $condition;
	$total = pdo_fetchcolumn($sqls, $params); 	 	 	
	$list = pdo_fetchall($sql, $params);	 
	$pager = pagination($total, $pindex, $psize); 	
} elseif ($operation == 'delete') {
 	
} elseif ($operation == 'setgoodsproperty') {
 	
}
load()->func('tpl');
include $this->template('merch');	  	 	 		 	 	 