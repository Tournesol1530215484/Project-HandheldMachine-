<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC; 

$cauth=check_agent($_W['uid']);
// $cauth && message('你没有权限',$this->createPluginWebUrl('agency/bill'),'warning');
//全返
if(p('return')) {$set_return = p('return') -> getSet();
}

$sets=pdo_fetch('select sets  from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid'])); 
$sets=unserialize($sets['sets']);   

$diyform_plugin = p('diyform');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'query') {

}
if ($operation == 'change') {

} else if ($operation == 'post') {
 
} elseif ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	if ($cauth) {	//员工
		$sql='select s.*,sl.status sstatus ,sl.type ,sl.ratio,sl.bonus,sl.ordersn,sl.ctime sctime,ca.openid,ca.activacurrency,du.uid merchsn,du.merchname from '.tablename('sz_yi_staff_log').' sl left join '.tablename('sz_yi_staff').' s on sl.mid=s.mid left join '.tablename('sz_yi_currency_activation').' ca on ca.ordersn = sl.ordersn left join '.tablename('sz_yi_perm_user').' pu on ca.openid = pu.openid left join '.tablename('sz_yi_dealmerch_user').' du on du.uid = pu.uid where s.uniacid = :uniacid and s.uid = :uid';
		$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']); 
	}else{//代理
		$sql='select s.*,sl.status sstatus ,sl.type ,sl.ratio,sl.bonus,sl.ordersn,sl.ctime sctime,ca.openid,ca.activacurrency,du.uid merchsn,du.merchname from '.tablename('sz_yi_staff_log').' sl left join '.tablename('sz_yi_staff').' s on sl.mid=s.mid left join '.tablename('sz_yi_currency_activation').' ca on ca.ordersn = sl.ordersn left join '.tablename('sz_yi_perm_user').' pu on ca.openid = pu.openid left join '.tablename('sz_yi_dealmerch_user').' du on du.uid = pu.uid where s.uniacid = :uniacid and s.merchid = :uid';
		$params = array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']); 

	}
	$condition ='';	 	 		 	 

	if (!empty($_GPC['serNum'])) {
		$_GPC['serNum'] = trim($_GPC['serNum']);
		$condition .= ' AND `s.worknumber` LIKE :serNum ';
		$params[':serNum'] = '%' . trim($_GPC['serNum']) . '%';
	}


	if (!empty($_GPC['serName'])) {
		$_GPC['serName'] = trim($_GPC['serName']);
		$condition .= ' AND `s.name` LIKE :serName ';
		$params[':serName'] = '%' . trim($_GPC['serName']) . '%';
	} 

	if (!empty($_GPC['merchNum'])) {
		$_GPC['merchNum'] = trim($_GPC['merchNum']);
		$condition .= ' AND `pu.uid` = :merchNum ';
		$params[':merchNum'] = trim($_GPC['merchNum']);
	} 

	if (!empty($_GPC['merchName'])) {
		$_GPC['merchName'] = trim($_GPC['merchName']);
		$condition .= ' AND `du.merchname` LIKE :merchName ';
		$params[':merchName'] = '%' . trim($_GPC['merchName']) . '%';
	}

	$sql.=' group by sl.ordersn limit '.intval($pindex -1) * $psize .','.$psize;
 	$staff=pdo_fetchall($sql,$params); 	
 	foreach ($staff as $key => $value) {
 		// $temp=p('bonus')->getMerch($value['openid'],'deal');
 		// $merchname=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid=:uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$temp['uid']));
 		// $staff[$key]['merchsn']=$merchname['uid']; 	 
 		// $staff[$key]['merchname']=$merchname['merchname'];
 		$temp=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid=:uniacid and uid=:uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
 		$staff[$key]['bsname']=$temp['name'];
 		$staff[$key]['bswn']=$temp['worknumber'];

 	}

	// if (!empty($_GPC['status'])) {   
	// 	if ($_GPC['status'] == 1) {	//出售中   
	// 		$condition.=' and status = 1 and isCheck = 1 '; 
	// 	}else if ($_GPC['status'] == 2){  //待上架    
	// 		$condition.=' and shelves = 2 and startshelves > '.time().' '; 
	// 	}else if ($_GPC['status'] == 3){	//审核中   
	// 		$condition.=' and isCheck = 0 '; 
	// 	}else if ($_GPC['status'] == 4){		//审核失败 
	// 		$condition.=' and isCheck = 2 '; 
	// 	}else if ($_GPC['status'] == 5){		//已下架 
	// 		$condition.=' and isCheck = 0 and isCheck = 1 '; 
	// 	}else if ($_GPC['status'] == 6){		//草稿 
	// 		$condition.=' and isCheck = 3 '; 
	// 	}  
	// } 



	// if (p('supplier')) {
	// 	$suproleid = pdo_fetchcolumn('select id from' . tablename('sz_yi_perm_role') . ' where status1 = 1 and uniacid ='.$_W['uniacid']);
	// 	$userroleid = pdo_fetchcolumn('select roleid from ' . tablename('sz_yi_perm_user') . ' where uid=:uid and uniacid=:uniacid', array(':uid' => $_W['uid'], ':uniacid' => $_W['uniacid']));
	// 	if ((!empty($userroleid)) && ($userroleid == $suproleid)) {
	// 		$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . ' and supplier_uid=' . $_W['uid'] . " ORDER BY `status` DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	// 		$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_goods') . $condition . ' and supplier_uid=' . $_W['uid'];
	// 		$total = pdo_fetchcolumn($sqls, $params);
	// 	} else {
	// 		$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . " ORDER BY `status` DESC, `displayorder` DESC,`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	// 		$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_goods') . $condition;
	// 		$total = pdo_fetchcolumn($sqls, $params);
	// 	}
	// } else {
	// 	$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . " ORDER BY `status` DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
	// 	$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_goods') . $condition;
	// 	$total = pdo_fetchcolumn($sqls, $params);
	// }

	// $list = pdo_fetchall($sql, $params);

	// $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'delete') {
	// $id = intval($_GPC['id']);
	// $row = pdo_fetch('SELECT id, title, thumb FROM ' . tablename('sz_yi_goods') . ' WHERE id = :id', array(':id' => $id));
	// if (empty($row)) {
	// 	message('抱歉，商品不存在或是已经被删除！');
	// }
	// pdo_update('sz_yi_goods', array('deleted' => 1), array('id' => $id));
	// plog('dealmerch.dealgoods.delete', "删除商品 ID: {$id} 标题: {$row['title']} ");
	// message('删除成功！', referer(), 'success');
} 
load()->func('tpl'); 
include $this->template('bonus_for_agency');