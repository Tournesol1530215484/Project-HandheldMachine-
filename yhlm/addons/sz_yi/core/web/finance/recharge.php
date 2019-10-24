<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$op      = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
$id      = intval($_GPC['id']);
$profile = m('member')->getMember($id);

if ($op == 'credit1') {

	ca('finance.recharge.credit1');
	if ($_W['ispost']) {
		m('member')->setCredit($profile['openid'], 'credit1', $_GPC['num'], array($_W['uid'], '后台会员充值积分'));//余额积分
		plog('finance.recharge.credit1', "积分充值 充值积分: {$_GPC['num']} <br/>会员信息: ID: {$profile['id']} /  {$profile['openid']}/{$profile['nickname']}/{$profile['realname']}/{$profile['mobile']}");


		$msg = array(
		    'first' => array(
		        'value' => "后台会员充值积分！",
		        "color" => "#4a5077"
		    ),
		    'keyword1' => array(
		        'title' => '积分充值',
		        'value' => "后台会员充值积分:" .$_GPC['num'] . "积分!",
		        "color" => "#4a5077"
		    ),
		    'remark' => array(
		        'value' => "\r\n我们已为您充值积分，请您登录个人中心查看。",
		        "color" => "#4a5077"
		    )
		);

		$detailurl  = $this->createMobileUrl('member');
		m('message')->sendCustomNotice($profile['openid'], $msg, $detailurl);
		message('充值成功!', referer(), 'success');
	}

	$profile['credit1'] = m('member')->getCredit($profile['openid'], 'credit1');



} elseif ($op == 'credit2') {
	
	ca('finance.recharge.credit2');
	if ($_W['ispost']) {
		
		m('member')->setCredit($profile['openid'], 'credit2', $_GPC['num'], array($_W['uid'], '后台会员充值余额')); //余额充值
		$set = m('common')->getSysset('shop');
		$logno = m('common')->createNO('member_log', 'logno', 'RC');
		
		$data = array('openid' => $profile['openid'], 'logno' => $logno, 'uniacid' => $_W['uniacid'], 'type' => '0', 'createtime' => TIMESTAMP, 'status' => '1', 'title' => $set['name'] . '会员充值', 'money' => $_GPC['num'], 'rechargetype' => 'system',);
		pdo_insert('sz_yi_member_log', $data);
		$logid = pdo_insertid();
		m('member')->setRechargeCredit($openid, $log['money']);
		if (p('sale')) {
			$data['id'] = $logid;
			p('sale')->setRechargeActivity($data);
		}
		if (floatval($_GPC['num']) > 0) {
			m('notice')->sendMemberLogMessage($logid);
		}
		plog('finance.recharge.credit2', "余额充值 充值金额: {$_GPC['num']} <br/>会员信息:  ID: {$profile['id']} / {$profile['openid']}/{$profile['nickname']}/{$profile['realname']}/{$profile['mobile']}");
		message('充值成功!', referer(), 'success');
	}
	
	$set = m('common')->getSysset(); //商城信息
	$profile['credit2'] = m('member')->getCredit($profile['openid'], 'credit2'); //查询当前余额
	
} else if ($op == 'credit3') {

    ca('finance.recharge.credit3');
    if ($_W['ispost']) {

        m('member')->setCredit($profile['openid'], 'credit3', $_GPC['num'], array($_W['uid'], '后台会员充值易货码')); //余额充值
        $set = m('common')->getSysset('shop');
        $logno = m('common')->createNO('member_log', 'logno', 'RC');

        $data = array('openid' => $profile['openid'], 'logno' => $logno, 'uniacid' => $_W['uniacid'], 'type' => '0', 'createtime' => TIMESTAMP, 'status' => '1', 'title' => $set['name'] . '会员充值', 'money' => $_GPC['num'], 'rechargetype' => 'system','why'=>$_GPC['why'],'number'=>$_GPC['number'],);
        pdo_insert('sz_yi_barter_log', $data);
        $logid = pdo_insertid();
        m('member')->setRechargeCredit($openid, $log['money']);
        if (p('sale')) {
            $data['id'] = $logid;
            p('sale')->setRechargeActivity($data);
        }
        if (floatval($_GPC['num']) > 0) {
            m('notice')->sendMemberLogMessage($logid);
        }
        $userinfo=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where openid = :openid and uniacid = :uniacid and dealmerchid = 1 ',array(':openid'=>$profile['openid'],':uniacid'=>$_W['uniacid']));
        m('log')->putBarterCodeLog($profile['openid'],$userinfo['uid'],16,1,4,doubleval($_GPC['num']),$logno,'充值易货码');
        plog('finance.recharge.credit3', "易货码充值 充值易货码: {$_GPC['num']} <br/>会员信息:  ID: {$profile['id']} / {$profile['openid']}/{$profile['nickname']}/{$profile['realname']}/{$profile['mobile']}");
        message('充值成功!', referer(), 'success');
    }
    $set = m('common')->getSysset(); //商城信息
    $profile['credit3'] = m('member')->getCredit($profile['openid'], 'credit3'); //查询当前余额

    $isbarter=pdo_fetch('select id from '.tablename('sz_yi_perm_user').' where openid=:openid and uniacid=:uniacid and dealmerchid > 0' ,array(
        ':openid' => $profile['openid'],
        ':uniacid' => $_W['uniacid']
    ));

}else if ($op == 'currency_credit3') {

    ca('finance.recharge.currency_credit3');
    if ($_W['ispost']) {

        m('member')->setCredit($profile['openid'], 'currency_credit3', $_GPC['num'], array($_W['uid'], '后台会员充值易货码')); //余额充值
        $set = m('common')->getSysset('shop');
        $logno = m('common')->createNO('member_log', 'logno', 'RC');

        $data = array('openid' => $profile['openid'], 'logno' => $logno, 'uniacid' => $_W['uniacid'], 'type' => '0', 'createtime' => TIMESTAMP, 'status' => '1', 'title' => $set['name'] . '会员充值', 'money' => $_GPC['num'], 'rechargetype' => 'system','number'=>$_GPC['number'],'why'=>$_GPC['why'],);
        pdo_insert('sz_yi_currency_barter_log', $data);
        $logid = pdo_insertid();
        m('member')->setRechargeCredit($openid, $log['money']);
        if (p('sale')) {
            $data['id'] = $logid;
            p('sale')->setRechargeActivity($data);
        }
        if (floatval($_GPC['num']) > 0) {
            m('notice')->sendMemberLogMessage($logid);
        }
        $userinfo=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where openid = :openid and uniacid = :uniacid and dealmerchid = 1 ',array(':openid'=>$profile['openid'],':uniacid'=>$_W['uniacid']));
        m('log')->putBarterCurrencyLog($profile['openid'],$userinfo['uid'],9,doubleval($_GPC['num']),$logno,'充值易货额度');
        plog('finance.recharge.currency_credit3', "易货码充值 充值易货码: {$_GPC['num']} <br/>会员信息:  ID: {$profile['id']} / {$profile['openid']}/{$profile['nickname']}/{$profile['realname']}/{$profile['mobile']}");
        message('充值成功!', referer(), 'success');
    }
    $set = m('common')->getSysset(); //商城信息
    $profile['currency_credit3'] = m('member')->getCredit($profile['openid'], 'currency_credit3'); //查询当前余额
    
    $isbarter=pdo_fetch('select id from '.tablename('sz_yi_perm_user').' where openid=:openid and uniacid=:uniacid and dealmerchid > 0' ,array(
        ':openid' => $profile['openid'],
        ':uniacid' => $_W['uniacid']
    ));

}
//日志记录
function writelog($str,$title='Error')
{
	$open=fopen($title.".txt","a" );
	fwrite($open,$str);
	fclose($open);
}

load()->func('tpl');
include $this->template('web/finance/recharge');
