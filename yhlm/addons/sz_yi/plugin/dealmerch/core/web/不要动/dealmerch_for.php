<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
 ca('dealmerch.dealmerch_for');
load()->model('user');
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;


$condition = ' and uniacid=:uniacid and dealmerchid > 0 ';
$params = array(':uniacid' => $_W['uniacid']);
if ($operation == 'af_dealmerch') {
	$status = $_GPC['status'];
	$id = $_GPC['id'];

	$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");

    if (empty($openid)) {
		message('没有该条申请记录', $this->createPluginWebUrl('dealmerch/dealmerch_for'), 'error');
	} else {
		$time=time();//添加审核通过时间
		pdo_update('sz_yi_af_supplier', array('status' => $status,'ctime'=>$time), array('id' => $id, 'uniacid' => $_W['uniacid']));
		
		$this->model->senddealmerchInform($openid, $status);
		if ($status == 1) {
			$msg = '驳回申请成功';
		} else {
			$data = array();
			$msg = '审核通过成功';
			$dealmerch_usre = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
			$data['uid'] = user_register(array('username' => $dealmerch_usre['username'], 'password' => $dealmerch_usre['password']));
			$pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $data['uid']));
			$perm_role = pdo_fetch ('select id,status from ' . tablename('sz_yi_perm_role') . ' where status1=1 and status=1 and uniacid = '.$_W['uniacid']);
			$data['password'] = $pwd['password'];
			$data['username'] = $dealmerch_usre['username'];
			$data['realname'] = $dealmerch_usre['realname'];
			$data['mobile'] = $dealmerch_usre['mobile'];
			$data['company'] = $dealmerch_usre['qq'];
			$data['roleid'] = $perm_role['id'];
			$data['status'] = 1; 
			$data['uniacid'] = $_W['uniacid'];
			$data['perms'] = '';
			$data['provance']=$dealmerch_usre['province']; 
			$data['city']	=$dealmerch_usre['city'];
			$data['area']=$dealmerch_usre['district'];
			$data['openid'] = $openid; 
			$data['type'] = 3;

			pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $dealmerch_usre['uniacid'], 'role' => 'operator'));
            $arr=array(
                'uniacid'=>$_W['uniacid'],
                'uid'=>$data['uid'],
                'merchname'=>$dealmerch_usre['qq'],
                'province'	=> $dealmerch_usre['province'],
                'city'	=> $dealmerch_usre['city'],
                'mobile'	=> $dealmerch_usre['mobile'],
                'contact'	=> $dealmerch_usre['contact'],
                'district'	=> $dealmerch_usre['district'],
                'merchsn'=>date('YmdHis').rand(11111,99999)
            );  	 				
            pdo_insert('sz_yi_dealmerch_user',$arr);
            $data['dealmerchid']=pdo_insertid();     //易货商家 通过审核后的标记
            pdo_insert('sz_yi_perm_user', $data);
            //赠送成为商家易货额度
            $sets=pdo_fetchcolumn('select `sets` from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
            $bart=unserialize($sets)['bart'];
            if(floatval($bart['get']) > 0){
            	m('member')->setCredit($openid,'currency_credit3',floatval($bart['get']));
            	m('log')->putBarterCurrencyLog($openid,$data['uid'],10,floatval($bart['get']),'','平台赠送');
            }
		}
		//message($msg, $this->createPluginWebUrl('dealmerch/dealmerch_for_resu'), 'success');
		message($msg, $this->createPluginWebUrl('dealmerch/dealmerch_for'), 'success');
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
$sql = 'select * from ' . tablename('sz_yi_af_supplier') . " where 1 and status=0 {$condition} ";
$sql.=' order by id desc ';   

if (empty($_GPC['export'])) {
	$sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}
$list = pdo_fetchall($sql, $params);

$templateopenid=array();
foreach ($list as $key => $value) {
	$openids=$value['openid'];
	$templateopenid=pdo_fetch("select agentid FROM hs_sz_yi_member  WHERE openid ='$openids'");
	$agentid=$templateopenid['agentid'];
	$tuijian=pdo_fetch("select realname trealname FROM hs_sz_yi_member where id = $agentid");
	if(!empty($tuijian['trealname'])){
		$list[$key]['trealname']=$tuijian['trealname'];
	}else{
		$list[$key]['trealname']="无";
	}

}

if ($_GPC['export1'] == '1') {
	plog('member.member.export', '导出会员数据');
	m('excel')->export($list, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '产品名称', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12), array('title' => '用户名', 'field' => 'productname', 'width' => 12), array('title' => '密码', 'field' => 'productname', 'width' => 12))));
}
 
$total =pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_af_supplier') . " where 1 and status=0 {$condition}",$params); 
$pager = pagination($total, $pindex, $psize);
load()->func('tpl');
include $this->template('dealmerch_for');