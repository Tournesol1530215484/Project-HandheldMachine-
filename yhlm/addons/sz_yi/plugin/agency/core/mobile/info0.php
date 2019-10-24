<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$openid = m('user')->getOpenid();

if($op == 'edit'){
	if ($_W['isajax']) {
		$data = array();
		$data['realname']    = $_GPC['realname'];
		$data['mobile']      = $_GPC['mobile'];
		$data['banknumber']  = $_GPC['banknumber'];
		$data['accountbank'] = $_GPC['accountbank'];
		$data['accountname'] = $_GPC['accountname'];
		$result = pdo_update('sz_yi_perm_user', $data, array('openid' => $openid));
		if (!empty($result)) {
			show_json(1);
		} else {
			show_json(0, '修改失败！请重试');
		}
	}

	$sql = 'select username,realname,mobile,banknumber,accountbank,accountname from'.tablename('sz_yi_perm_user').'where openid = :openid';
	$info = pdo_fetch($sql, array(':openid' => $openid));
	if (empty($info)) {
		header('Location:'.$this->createPluginMobileUrl('suppliermenu/index'));
	}
} elseif ($op == 'editpwd') {

	// 查询uid
	if ($_W['isajax']) {
		// 验证为不能空
		$_GPC['oldpwd']  || show_json(0, '旧密码不能为空!');
		$_GPC['newpwd1'] || show_json(0, '新密码不能为空!');
		$_GPC['newpwd2'] || show_json(0, '新密码不能为空!');

		$sql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid';
		$info = pdo_fetch($sql, array(':openid' => $openid));

		$sql  = 'SELECT username, password, salt, groupid, starttime, endtime FROM ' . tablename('users') . ' WHERE `uid` = :uid';
		$user = pdo_fetch($sql, array(':uid' => $info['uid']));
		$user || show_json(0, '抱歉，请重新登录！');

		$password_old = user_hash($_GPC['oldpwd'], $user['salt']);

		$user['password'] != $password_old && show_json(0, '旧密码错误！');

		$_GPC['newpwd1'] != $_GPC['newpwd2'] && show_json(0, '两次密码不一致！');
		$members = array(
			'password' => user_hash($_GPC['newpwd1'], $user['salt'])
		);
		$result = pdo_update('users', $members, array('uid' => $info['uid']));
		if (empty($result)) {
			show_json(0, '网络不给力，请重试！');
		} else {
			show_json(1);
		}
	}
} elseif ($op == 'editstore') {
	$isql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid and uniacid = :uniacid limit 1';
	$uid = pdo_fetchcolumn($isql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));
	$sdsql = 'select * from '.tablename('sz_yi_store_data').' where storeid = :uid and uniacid = :uniacid';

	$storeData = pdo_fetch($sdsql, array(':uid' => $uid, ':uniacid' => $_W['uniacid']));
	// var_dump($storeData);
	if ($_W['isajax']) {
		$logo      = $_GPC['logo'];
		$signboard = $_GPC['signboard'];
		$description = $_GPC['description'];
		$storename = $_GPC['storename'];
		$sql = 'select uid from'.tablename('sz_yi_perm_user').' where openid = :openid';
		$storeid = pdo_fetchcolumn($sql, array(':openid' => $openid));
		$data = array('logo' => $logo, 'signboard' => $signboard, 'description' => $description, 'storename' => $storename);
		$res = pdo_update('sz_yi_store_data', $data, array('storeid' => $storeid));
		if (!empty($res)) {
			show_json(1, '保存成功！');
		} else {
			show_json(0, '没有任何改变！');
		}
	}
}
include $this->template('info');
