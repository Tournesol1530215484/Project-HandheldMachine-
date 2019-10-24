<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC; 


$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'query') {
  
}
if ($operation == 'change') {
	
} else if ($operation == 'display') {
	// if (!empty($_GPC['title'])) { 
	// 	$_GPC['title'] = trim($_GPC['title']);
	// 	$condition .= ' AND `title` LIKE :title ';
	// 	$params[':title'] = '%' . trim($_GPC['title']) . '%';
	// }

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$agencyinfo=p('agency')->getStaff($_W['uid']);
	$temp1=p('bonus')->getMerch($agencyinfo['merchid']);
	$temp=m('member')->getMember($temp1['openid']);
	$sql='select bg.*,o.ordersn from '.tablename('sz_yi_bonus_goods').' bg left join '.tablename('sz_yi_order').' o on o.id = bg.orderid where 1 ';
	$condition = ' and bg.uniacid = :uniacid AND bg.mid = :mid ';
	$sql.=$condition;
	$params = array(':uniacid' => $_W['uniacid'], ':mid' =>$temp['id']); 
	
	$sql.=' LIMIT ' . ($pindex - 1) * $psize . ','. $psize;
	
	$list = pdo_fetchall($sql,$params);
	// if (!empty($_GPC['goodssn'])) {
	// 	$condition .= ' AND `goodssn` = :goodssn ';
	// 	$params[':goodssn'] = intval($_GPC['goodssn']); 
	// } 

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

} elseif ($operation == 'setgoodsproperty') {

}

load()->func('tpl'); 
include $this->template('bill');