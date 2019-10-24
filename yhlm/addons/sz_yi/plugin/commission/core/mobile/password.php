<?php
global $_W, $_GPC;
$openid = m('user')->getOpenid();
// show_json(-1, date('w'));
if ($_W['isajax']) {
	$sql = 'select withdraw_pwd from'.tablename('sz_yi_member').'where uniacid=:uniacid and openid=:openid';
	$pwd = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid'], ':openid'=>$openid));
	if ($_GPC['op'] == 'action') {
		if (!empty($pwd['withdraw_pwd'])) { // 已有密码
			$_GPC['oldpwd'] == '' && show_json(-1, '原密码不能为空！');
			$_GPC['pwd']    == '' && show_json(-1, '新密码不能为空！');
			$_GPC['rpwd']   != $_GPC['pwd'] && show_json(-1, '两次密码不一致！');
			if ($pwd['withdraw_pwd'] != md5($_GPC['oldpwd'])) {
				show_json(-1, '原密码输入错误！');
			}
			$result = pdo_update('sz_yi_member', array('withdraw_pwd'=>md5($_GPC['pwd'])), array('openid'=>$openid));
			show_json(1, $result);
		}else{ // 暂无密码
			$_GPC['pwd']    == '' && show_json(-1, '新密码不能为空！');
			$_GPC['rpwd']   != $_GPC['pwd'] && show_json(-1, '两次密码不一致！');
			$result = pdo_update('sz_yi_member', array('withdraw_pwd'=>md5($_GPC['pwd'])), array('openid'=>$openid));
			show_json(1, $result);
		}
	} elseif ($_GPC['op'] == 'compare') {
		if (empty($pwd['withdraw_pwd'])) { // 密码为空
			show_json(0);
		} elseif (md5($_GPC['pwd']) != $pwd['withdraw_pwd']) { // 密码错误
			show_json(-1);
		} elseif (date('w') == 1 || date('w') == 3 || date('w') == 5 || date('w') == 6 || date('w') == 0) { // 周二和周四才能申请提现
			show_json(2);
		} elseif (md5($_GPC['pwd']) == $pwd['withdraw_pwd']) { // 密码正确返回1
			show_json(1);
		}
	}elseif($_GPC['op']=='repel'){
			$result = pdo_update('sz_yi_member', array('repel'=>'1'), array('openid'=>$openid));
			show_json(1, $result);
			
		} else {
		// 用于前台判断
		show_json(1, $pwd);
	}
}
include $this->template('password');