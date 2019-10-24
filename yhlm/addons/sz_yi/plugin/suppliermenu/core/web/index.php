<?php

if (!defined('IN_IA')) {
    print ('Access Denied');
}
global $_W, $_GPC;
load()->func('tpl');
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
// if ($operation == 'display') {
// } elseif ($operation == 'store') {
// }

$uid = intval($_W['uid']);
$sql = 'select id from'.tablename('sz_yi_perm_user').'where uniacid=:uniacid and uid=:uid and type = 2';
$bool = pdo_fetch($sql, array(
	':uniacid' => $_W['uniacid'],
	':uid' => $_W['uid']
	));

// 商家数据
$sql = 'select * from'.tablename('sz_yi_store_data').'where uniacid=:uniacid and storeid=:uid';
$datum = pdo_fetch($sql, array(
	':uniacid' => $_W['uniacid'],
	':uid' => $_W['uid']
	));

$supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uid="' . $uid . '" and uniacid=' . $_W['uniacid']);
// 查询身份证图片
$supplierinfo['idimgs'] = pdo_fetch('select idimg1,idimg2 from'.tablename('sz_yi_supplier_idimages').'where uniacid=:uniacid and openid=:openid', array(':uniacid'=>$_W['uniacid'], ':openid'=>$supplierinfo['openid']));
// var_dump($supplierinfo);
if(!empty($supplierinfo['openid'])){
    $saler = m('member') -> getInfo($supplierinfo['openid']);
}
$totalmoney = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
$totalmoneyok = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.supplier_apply_status=1 and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
if ($op == 'detail') {
	$smemrch=p('bonus')->getMerch($_W['uid']);
	$mdata=$_GPC['data'];
	$mdata['provance']=$_GPC['birth']['province'];
	$mdata['city']=$_GPC['birth']['city'];
	$mdata['area']=$_GPC['birth']['district'];	 
	$sure=pdo_update('sz_yi_perm_user',$mdata,array('uid'=>$_W['uid'],'uniacid'=>$_W['uniacid']));
	if (!empty($sure)) {
		message('提交资料成功！', referer(), 'success');
	}else{
		message('修改资料失败！', referer(), 'error');
	} 				 		 
}
if(checksubmit('submit')){
	
	$data = array();
	$data['uniacid']     = $_W['uniacid'];
	$data['storeid']     = $_W['uid'];
	$data['storename']   = $_GPC['storename'];
	$data['tel']         = $_GPC['tel'];
	$data['description'] = $_GPC['Storeprofile'];
	$data['provance']    = $_GPC['provance'];
	$data['city']        = $_GPC['city'];
	$data['area']        = $_GPC['area'];
	$data['street']      = $_GPC['street'];
	$data['logo']        = $_GPC['logo'];
	$data['signboard']   = $_GPC['signboard'];	 	 	 
	if (empty($data['storename'])) {
		message('公司名称不能为空', '', 'error');
	}	 	 
	if (empty($data['tel'])) {
		message('门店电话不能为空', '', 'error');
	}
	if ($data['provance'] == '请选择省份' || $data['city'] == '请选择城市' || $data['area'] == '请选择区域') {
		message('门店地址不能为空', '', 'error');
	}
	if ($data['logo'] == '' || $data['signboard'] == '') {
		message('门店Logo或门店招牌不能为空', '', 'error');
	}
	if (empty($datum['id'])) {
		$result = pdo_insert('sz_yi_store_data', $data);
	} else {
		$result = pdo_update('sz_yi_store_data', $data, array('uniacid'=>$_W['uniacid'], 'storeid'=>$_W['uid']));
	}
	if (!empty($result)) {
		message('提交成功！', referer(), 'success');
	}
}




if(empty($_W['merchid'])){
    $url = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&do=shop&m=sz_yi&supplier={$_W['uid']}";
}else{
    $url = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&do=member&m=sz_yi&p=merch&op=detail&id={$_W['merchid']}&supplier={$_W['uid']}";
}
 
include $this->template('index');  


