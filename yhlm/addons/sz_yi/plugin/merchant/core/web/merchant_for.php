<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
 ca('merchant.merchant_for');
load()->model('user');
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and uniacid=:uniacid and merch=1';
$params = array(':uniacid' => $_W['uniacid']);
if ($operation == 'af_merchant') {
	$status = $_GPC['status'];
	$id = $_GPC['id'];
	$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");

    if (empty($openid)) {
		message('没有该条申请记录', $this->createPluginWebUrl('merchant/merchant_for'), 'error');
	} else {
		pdo_update('sz_yi_af_supplier', array('status' => $status), array('id' => $id, 'uniacid' => $_W['uniacid']));
		
	     	$this->model->sendmerchantInform($openid, $status);
		if ($status == 1) {
			$msg = '驳回申请成功';
		} else {
			$data = array();
			$msg = '审核通过成功';
			
			$merchant_usre = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
			$data['uid'] = user_register(array('username' => $merchant_usre['username'], 'password' => $merchant_usre['password']));
			
			$pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $data['uid']));
			
			$perm_role = pdo_fetch ('select id,status from ' . tablename('sz_yi_perm_role') . ' where status1=1 and status=1 and uniacid = '.$_W['uniacid']);
			$data['password'] = $pwd['password'];
			$data['username'] = $merchant_usre['username'];
			$data['provance'] = $merchant_usre['province'];
			$data['company'] = $merchant_usre['qq'];
			$data['city'] = $merchant_usre['city'];
			$data['area'] = $merchant_usre['district'];
			$data['roleid'] = $perm_role['id'];
			$data['status'] = 1;
			$data['uniacid'] = $_W['uniacid'];
			$data['perms'] = '';
			$data['openid'] = $openid;  

			pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $merchant_usre['uniacid'], 'role' => 'operator'));
			 $arr=array(
			     'uniacid'=>$_W['uniacid'],
			     'uid'=>$data['uid'], 
			     'merchname'=>$merchant_usre['qq'], 
			     'province'=>$data['provance'],
			     'city'=>$data['city'],
			     'district'=>$data['area'],  
			     'status'=>1  
			); 
			pdo_insert('sz_yi_merch_user',$arr);
			$data['merchid']=pdo_insertid(); 
			pdo_insert('sz_yi_perm_user', $data); 
		}
		message($msg, $this->createPluginWebUrl('merchant/merchant_for'), 'success');
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

$i = 'select idimg1,idimg2,permit from '.tablename('sz_yi_supplier_idimages').' where uniacid=:uniacid and openid=:openid';
foreach($list as $key => $value) {
    $list[$key]['imgs'] = pdo_fetch($i, array(':uniacid'=>$_W['uniacid'], ':openid'=>$value['openid']));
}
if ($_GPC['export1'] == '1') {
	plog('member.member.export', '导出会员数据');
	m('excel')->export($list, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '产品名称', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12), array('title' => '用户名', 'field' => 'productname', 'width' => 12), array('title' => '密码', 'field' => 'productname', 'width' => 12))));
}
$total = count($list);
$pager = pagination($total, $pindex, $psize);
load()->func('tpl');
include $this->template('merchant_for');