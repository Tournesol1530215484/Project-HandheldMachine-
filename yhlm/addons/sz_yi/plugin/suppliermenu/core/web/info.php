<?php
global $_W, $_GPC;

$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$openid=m('user')->isLogin();
if ($operation == 'display') {
	$sql = 'select * from'.tablename('sz_yi_perm_user').'where uniacid = :uniacid and uid = :uid';
	$info = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':uid' => $_W['uid']));
	if (checksubmit('submit')) {
		$result = pdo_update('sz_yi_perm_user', $_GPC['data'], array('uid' => $_W['uid']));
		if (!empty($result)) {
		    message('操作成功!', referer(), 'success');
		}
	}
} elseif ($operation == 'editpwd') {
	if (checksubmit('submit')) {
		$sql  = 'SELECT username, password, salt, groupid, starttime, endtime FROM ' . tablename('users') . ' WHERE `uid` = :uid';
		$user = pdo_fetch($sql, array(':uid' => $_W['uid']));

		$password_old = user_hash($_GPC['oldpwd'], $user['salt']);
		if ($user['password'] != $password_old) {
		    message('旧密码错误！', '', 'error');
		}
		if ($_GPC['newpwd1'] == $_GPC['newpwd2']) {
			$members = array(
				'password' => user_hash($_GPC['newpwd1'], $user['salt'])
			);
			$result = pdo_update('users', $members, array('uid' => $_W['uid']));
			if (!empty($result)) {
				message('操作成功!', referer(), 'success');
			} else {
				message('网络不给力，请重试!', '', 'error');
			}
		} else {
			message('两次密码不一致!', '', 'error');
		}
	}
}
	 	 	

include $this -> template('info');
