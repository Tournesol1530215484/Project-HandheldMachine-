<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
load()->model('user');
$perm_role = pdo_fetch('select * from ' . tablename('sz_yi_perm_role') . ' where status1 = 1 and uniacid = '.$_W['uniacid']);
$supplier_perms = 'shop,shop.goods,shop.goods.view,shop.goods.add,shop.goods.edit,shop.goods.delete,order,order.view,order.view.status_1,order.view.status0,order.view.status1,order.view.status2,order.view.status3,order.view.status4,order.view.status5,order.view.status9,order.op,order.op.pay,order.op.send,order.op.sendcancel,order.op.finish,order.op.verify,order.op.fetch,order.op.close,order.op.refund,order.op.export,order.op.changeprice,exhelper,exhelper.print,exhelper.print.single,exhelper.print.more,exhelper.exptemp1,exhelper.exptemp1.view,exhelper.exptemp1.add,exhelper.exptemp1.edit,exhelper.exptemp1.delete,exhelper.exptemp1.setdefault,exhelper.exptemp2,exhelper.exptemp2.view,exhelper.exptemp2.add,exhelper.exptemp2.edit,exhelper.exptemp2.delete,exhelper.exptemp2.setdefault,exhelper.senduser,exhelper.senduser.view,exhelper.senduser.add,exhelper.senduser.edit,exhelper.senduser.delete,exhelper.senduser.setdefault,exhelper.short,exhelper.short.view,exhelper.short.save,exhelper.printset,exhelper.printset.view,exhelper.printset.save,exhelper.dosen,taobao,taobao.fetch,suppliermenu,suppliermenu.goods';
if(empty($perm_role)){
    $data = array('rolename' => '供应商', 'status' => 1, 'status1' => 1, 'perms' => $supplier_perms, 'deleted' => 0,'uniacid'=>$_W['uniacid']);
    pdo_insert('sz_yi_perm_role' , $data);
    $logid = pdo_insertid();
}else{
    $logid = $perm_role['id'];
}

$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and uniacid=:uniacid and merch=0 and dealmerchid = 0 and member = 0 ';  
$params = array(':uniacid' => $_W['uniacid']);
if ($operation == 'af_supplier') {
	$status = $_GPC['status'];
	$id = $_GPC['id'];
	$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
	if (empty($openid)) {
		message('没有该条申请记录', $this->createPluginWebUrl('supplier/supplier_for'), 'error');
	} else {
		pdo_update('sz_yi_af_supplier', array('status' => $status), array('id' => $id, 'uniacid' => $_W['uniacid']));

		$this->model->sendSupplierInform($openid, $status);
		if ($status == 1) {
			$msg = '驳回申请成功';
		} else {
			$data = array();
			$msg = '审核通过成功';
			$supplier_usre = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
			$data['uid'] = user_register(array('username' => $supplier_usre['username'], 'password' => $supplier_usre['password']));
			$pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $data['uid']));
			// $perm_role = pdo_fetch ('select id,status from ' . tablename('sz_yi_perm_role') . ' where status1=1 and status=1 and uniacid = '.$_W['uniacid']);
			$data['password'] = $pwd['password'];
			$data['username'] = $supplier_usre['username'];
			$data['company'] = $supplier_usre['qq'];
			// $data['roleid']   = $perm_role['id']; 
			$data['roleid']   = $logid;
			$data['status']   = 1;
			$data['uniacid']  = $_W['uniacid'];
			$data['perms']    = '';
			$data['openid']   = $openid; 
			$data['provance']   = $supplier_usre['province'];
			$data['city']   = $supplier_usre['city'];
			$data['area']   = $supplier_usre['area'];
			// 1是供应商 2是商家
			$data['type'] = $supplier_usre['type'];

			pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $supplier_usre['uniacid'], 'role' => 'operator'));
			pdo_insert('sz_yi_perm_user', $data);
			// 商家审核通过后，添加店铺资料
			if ($data['type'] == 2) { 
				$storeData = array();
				$storeData['storeid'] = $data['uid'];
				$storeData['provance'] = $data['province'];
				$storeData['city'] = $data['city']; 
				$storeData['area'] = $data['area'];
				$msql = 'select nickname from'.tablename('sz_yi_member').' where uniacid = :uniacid and openid = :openid';
				$member = pdo_fetch($msql, array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
				$storeData['storename'] = $supplier_usre['qq'];
				$storeData['uniacid']   = $_W['uniacid'];
				$storeData['isopen']    = 1;
				pdo_insert('sz_yi_store_data', $storeData);
			}
		}
		message($msg, $this->createPluginWebUrl('supplier/supplier_for'), 'success');
	}
} elseif ($operation == 'reject') { // 驳回
	$id = $_GPC['id'];
	$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
	$result = pdo_update('sz_yi_af_supplier', array('account' => $_GPC['rejectcontent'], 'status' => 1), array('id' => $id, 'uniacid' => $_W['uniacid']));
	if (!empty($result)) {
		$this->model->sendSupplierInform($openid, 1);
		message('操作成功！', $this->createPluginWebUrl('supplier/supplier_for'), 'success');
	}
}



if (!empty($_GPC['mid'])) {
	$condition .= ' and id=:mid';
	$params[':mid'] = intval($_GPC['mid']);
}
if (!empty($_GPC['realname'])) {
	$_GPC['realname'] = trim($_GPC['realname']);
	$condition .= ' and realname like :realname';
	$params[':realname'] = "%{$_GPC['realname']}%";
}
$sql = 'select * from ' . tablename('sz_yi_af_supplier') . " where 1 and status=0 {$condition}";
if (empty($_GPC['export'])) {
	$sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}
$list = pdo_fetchall($sql, $params);
$i = 'select idimg1,idimg2,permit from'.tablename('sz_yi_supplier_idimages').'where uniacid=:uniacid and openid=:openid';
foreach ($list as $key => $value) {
	$list[$key]['imgs'] = pdo_fetch($i, array(':uniacid'=>$_W['uniacid'], ':openid'=>$value['openid']));
}
// echo '<pre>';
// print_r($list);
if ($_GPC['export1'] == '1') {
	plog('member.member.export', '导出会员数据');
	m('excel')->export($list, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '产品名称', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12), array('title' => '用户名', 'field' => 'productname', 'width' => 12), array('title' => '密码', 'field' => 'productname', 'width' => 12))));
}
$total = count($list);
$pager = pagination($total, $pindex, $psize);
load()->func('tpl');
include $this->template('supplier_for');