<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
 // ca('bartact.dealmerch_for');
load()->model('user');
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;

$condition = ' and uniacid=:uniacid and member > 0 ';
$params = array(':uniacid' => $_W['uniacid']); 				 
if ($operation == 'af_member') {
	$status = $_GPC['status'];
	$id = $_GPC['id'];
	$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
	if (empty($openid)) {
		message('没有该条申请记录', $this->createPluginWebUrl('bartact/member_for'), 'error');
	} else {	 		 	
		pdo_update('sz_yi_af_supplier', array('status' => $status), array('id' => $id, 'uniacid' => $_W['uniacid']));
		// $this->model->senddealmerchInform($openid, $status);
		if ($status == 1) {
			$msg = '驳回申请成功';
		} else {
			$data = array();
			$msg = '审核通过成功';
			$af_user = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
			$data['uid'] = user_register(array('username' => $af_user['username'], 'password' => $af_user['password']));
			$pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $data['uid']));
			$perm_role = pdo_fetch ('select id,status from ' . tablename('sz_yi_perm_role') . ' where status1=1 and status=1 and uniacid = '.$_W['uniacid']);
			$data['password'] = $pwd['password'];
			$data['username'] = $af_user['username'];
			$data['company'] = $af_user['qq'];
			$data['roleid'] = $perm_role['id'];
			$data['status'] = 1; 
			$data['uniacid'] = $_W['uniacid'];
			$data['perms'] = '';
			$data['provance']=$af_user['province']; 
			$data['city']	=$af_user['city'];
			$data['area']=$af_user['district'];
			$data['openid'] = $openid; 
			
			pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $af_user['uniacid'], 'role' => 'operator'));
            $arr=array(
                'uniacid'=>$af_user['uniacid'],
                'uid'=>$data['uid'],
                'openid'=>$af_user['openid'],
                'mobile'=>$af_user['mobile'],
                'realname'=>$af_user['realname'],
                'orgName'	=> $af_user['qq'],
            );  
            pdo_insert('sz_yi_member_user',$arr);
            $data['muserid']=pdo_insertid();     //易货商家 通过审核后的标记
            pdo_insert('sz_yi_perm_user', $data);
            //赠送成为商家易货额度
            // $sets=pdo_fetchcolumn('select `sets` from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
            // $bart=unserialize($sets)['bart'];
            // if(floatval($bart['get']) > 0){
            // 	m('member')->setCredit($openid,'currency_credit3',floatval($bart['get']));
            // 	m('log')->putBarterCurrencyLog($openid,$data['uid'],10,floatval($bart['get']),'','平台赠送');
            // }
		}
		message($msg, $this->createPluginWebUrl('bartact/member_for_resu'), 'success');
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
$sql.=' order by id desc ';   

if (empty($_GPC['export'])) {
	$sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}
$list = pdo_fetchall($sql, $params);
if ($_GPC['export1'] == '1') {
	plog('member.member.export', '导出会员数据');
	m('excel')->export($list, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '产品名称', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12), array('title' => '用户名', 'field' => 'productname', 'width' => 12), array('title' => '密码', 'field' => 'productname', 'width' => 12))));
}
 
$total =pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_af_supplier') . " where 1 and status=0 {$condition}",$params); 
$pager = pagination($total, $pindex, $psize);
load()->func('tpl');
include $this->template('member_for');