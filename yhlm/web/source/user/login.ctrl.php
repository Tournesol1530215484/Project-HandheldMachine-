<?php
/**
 * 登录
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

defined('IN_IA') or exit('Access Denied');
define('IN_GW', true);

if(checksubmit()) {
	_login($_GPC['referer']);
}
$setting = $_W['setting'];
$site = $_W['setting']['copyright'];

template('user/login');
function _login($forward = '') {
	global $_GPC, $_W;

	load()->model('user');
	$member = array();
	$username = trim($_GPC['username']);
	pdo_query('DELETE FROM'.tablename('users_failed_login'). ' WHERE lastupdate < :timestamp', array(':timestamp' => TIMESTAMP-300));
	$failed = pdo_get('users_failed_login', array('username' => $username, 'ip' => CLIENT_IP));
	if ($failed['count'] >= 5) {
		message('输入密码错误次数超过5次，请在5分钟后再登录',referer(), 'info');
	}
	if (!empty($_W['setting']['copyright']['verifycode'])) {
		$verify = trim($_GPC['verify']);
		if(empty($verify)) {
			message('请输入验证码');
		}
		$result = checkcaptcha($verify);
		if (empty($result)) {
			message('输入验证码错误');
		}
	}
	if(empty($username)) {
		message('请输入要登录的用户名');
	}
	$member['username'] = $username;
	$member['password'] = $_GPC['password'];
	if(empty($member['password'])) {
		message('请输入密码');
	}
	$record = user_single($member);
	// var_dump($member);exit;
	if(!empty($record)) {
		if($record['status'] == 1) {
			message('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！');
		}
		$founders = explode(',', $_W['config']['setting']['founder']);
		$_W['isfounder'] = in_array($record['uid'], $founders);
		if (!empty($_W['siteclose']) && empty($_W['isfounder'])) {
			message('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
		}
		$cookie = array();
		$cookie['uid'] = $record['uid'];
		$cookie['lastvisit'] = $record['lastvisit'];
		$cookie['lastip'] = $record['lastip'];
		$cookie['hash'] = md5($record['password'] . $record['salt']);
		$session = base64_encode(json_encode($cookie));
		isetcookie('__session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
		$status = array();
		$status['uid'] = $record['uid'];
		$status['lastvisit'] = TIMESTAMP;
		$status['lastip'] = CLIENT_IP;
		user_update($status);
				if($record['type'] == ACCOUNT_OPERATE_CLERK) {
			header('Location:' . url('account/switch', array('uniacid' => $record['uniacid'])));
			die;
		}
		if(empty($forward)) {
			$forward = $_GPC['forward'];
		}
		if(empty($forward)) {
			$forward = './index.php?c=account&a=display';
		}
		if ($record['uid'] != $_GPC['__uid']) {
			isetcookie('__uniacid', '', -7 * 86400);
			isetcookie('__uid', '', -7 * 86400);
		}


		//后面加的，不是超级管理员的用户，登录成功后跳转到可以操作的第一个公众号里

		$founders = explode(',', $_W['config']['setting']['founder']);

		if(!in_array($record['uid'], $founders)){

		  $sql = "SELECT * FROM ".tablename('uni_account')." as a LEFT JOIN ".tablename('account')." as b ON a.default_acid = b.acid LEFT JOIN ".tablename('uni_account_users')." as c ON a.uniacid = c.uniacid WHERE a.default_acid <> 0 AND c.uid = {$record['uid']} AND b.isdeleted <> 1 ORDER BY c.`rank` DESC, a.`uniacid` DESC LIMIT 1";

		  $uni = pdo_fetch($sql);

		 // echo "<pre>";
		 // print_r($uni);
		 // exit;	  
		  if(!empty($uni)){
			  isetcookie('__uniacid',$uni['uniacid'], 7 * 86400);
	          isetcookie('__uid', $record['uid'], 7 * 86400);
	          // $agencys=pdo_fetchcolumn('SELECT id from '.tablename('sz_yi_staff').' where uid = :uid',array(':uid'=>$uni['uid']));
	          // if ($agencys) {
	          // 	header('location: ' . url('home/welcome/agency'));	          
	          // }else{
	          	header('location: ' . url('home/welcome/platform'));
	          // }
			  exit;
		  }
 
		}
	

		pdo_delete('users_failed_login', array('id' => $failed['id']));
		message("欢迎回来，{$record['username']}。", $forward);
	} else {
		if (empty($failed)) {
			pdo_insert('users_failed_login', array('ip' => CLIENT_IP, 'username' => $username, 'count' => '1', 'lastupdate' => TIMESTAMP));
		} else {
			pdo_update('users_failed_login', array('count' => $failed['count'] + 1, 'lastupdate' => TIMESTAMP), array('id' => $failed['id']));
		}
		message('登录失败，请检查您输入的用户名和密码！');
	}
}

