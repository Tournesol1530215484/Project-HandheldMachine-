<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user') -> getOpenid();
$uniacid = $_W['uniacid'];
$set = m('common') -> getSysset(array('trade'));
// 余额提现手续费
$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
$myset = unserialize($setdata['sets']);


 $week = date('w');
// End 余额提现手续费       
if ($operation == 'display' && $_W['isajax']){
    $credit = m('member') -> getCredit($openid, 'credit2');
    $member = m('member') -> getMember($openid);
    $returnurl = urlencode($this -> createMobileUrl('member/withdraw'));
    $infourl = $this -> createMobileUrl('member/info', array('returnurl' => $returnurl));
    show_json(1, array('credit' => $credit, 'infourl' => $infourl, 'noinfo' => empty($member['realname'])));

}else if ($operation == 'submit' && $_W['ispost']){
    if($_GPC['type']==1){   //佣金提现


    }else{  //现金提现

        $money = floatval($_GPC['money']);
        $credit = m('member') -> getCredit($openid, 'credit2');
        if ($money < 0){
            show_json(0, '非法提现金额!');
        }
        if (empty($money)){
            show_json(0, '申请金额为空!');
        }
        if ($money > $credit){
            show_json(0, '提现金额过大!');
        }
        // if($week != 2  && $week!=4){
        // }             
        // 查询消费密码

        $member = m('member') -> getMember($openid);
        $withdraw_pwd = $member['withdraw_pwd'];
        // $withdraw_pwd || show_json(0, '请先设置消费密码！');
        // if(md5($_GPC['password']) != $withdraw_pwd){
        //     show_json(0, '消费密码错误!');
        // }
        
        m('member') -> setCredit($openid, 'credit2', - $money);
        // 余额提现手续费
        $poundage = $money * ($myset['bart']['withdraw'] / 100);
        $re_money = $money - $poundage;
        // End 余额提现手续费      
        $logno = m('common') -> createNO('member_log', 'logno', 'RW');
        $log = array('uniacid' => $uniacid, 'logno' => $logno, 'openid' => $openid, 'title' => '余额提现', 'type' => 1, 'createtime' => time(), 'status' => 0, 'money' => $money, 're_money' => $re_money, 'poundage' => $poundage);
        pdo_insert('sz_yi_member_log', $log);
        $logid = pdo_insertid();
        m('notice') -> sendMemberLogMessage($logid);
        show_json(1);

    }
    
}else if ($operation == 'info'){
	
	$info=m('member')->getMember($openid);
	if($_W['isajax']){
		$data=array(
			'bank'=>trim($_GPC['bank']),
			'bankname'=>trim($_GPC['bankname']),
			'bank'=>trim($_GPC['bank'])
		);
		$sure=pdo_update('sz_yi_member',$data,array('openid'=>$openid));
		$sure?show_json(1,'修改成功'):show_json(0,'修改失败');
	}
	include $this -> template('member/withdrawInfo');
	exit;
}
include $this -> template('member/withdraw');
