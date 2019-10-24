<?php 
set_time_limit(18000) ;
ini_set('memory_limit', '128M');


 global $_W, $_GPC;
 $model = $this->model;
 $set = $model -> getSet();
 var_dump($set);
 

 

 if(empty($set['sendmethod']))return ;

	 
 
 

	 $time = time();
	 $day_times = intval($set['settledays']) * 3600 * 24;
	 $daytime = strtotime(date('Y-m-d 00:00:00'));
	 $sql = 'select distinct cg.mid from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and ({$time} - o.finishtime > {$day_times})  ORDER BY o.finishtime DESC,o.status DESC";
	 $count = pdo_fetchall($sql);

	 foreach ($count as $key => $value){

	    $member = $model -> getInfo($value['mid'], array('ok', 'pay'));
	    $send_money = $member['commission_ok'];
	    $sendpay = 1;
	    $islog = true;
	    $level = $model -> getlevel($member['openid']);
	    if(empty($set['paymethod'])){
	        m('member') -> setCredit($member['openid'], 'credit2', $send_money);
	    }else{
	        $logno = m('common') -> createNO('bonus_log', 'logno', 'RB');
	        $result = m('finance') -> pay($member['openid'], 1, $send_money * 100, $logno, '【' . $setshop['name'] . '】' . $level['levelname'] . '分红');
	        if (is_error($result)){
	            $sendpay = 0;
	            $sendpay_error = 1;
	        }
	    }
	    pdo_insert('sz_yi_bonus_log', array('openid' => $member['openid'], 'uid' => $member['uid'], 'money' => $send_money, 'uniacid' => $_W['uniacid'], 'paymethod' => $set['paymethod'], 'sendpay' => $sendpay, 'status' => 1, 'ctime' => time(), 'send_bonus_sn' => $send_bonus_sn));
	    if($sendpay == 1){
	       $model -> sendMessage($member['openid'], array('nickname' => $member['nickname'], 'levelname' => $level['levelname'], 'commission' => $send_money, 'type' => empty($set['paymethod']) ? '余额' : '微信钱包'), TM_BONUS_PAY);
	    }
	    $bonus_goods = array('status' => 3, 'applytime' => $time, 'checktime' => $time, 'paytime' => $time, 'invalidtime' => $time);
	    pdo_update('sz_yi_bonus_goods', $bonus_goods, array('mid' => $member['id'], 'uniacid' => $_W['uniacid']));
	 }

	 if($islog){
	    $log = array('uniacid' => $_W['uniacid'], 'money' => $totalmoney, 'status' => 1, 'ctime' => time(), 'paymethod' => $set['paymethod'], 'sendpay_error' => $sendpay_error, 'utime' => $daytime, 'send_bonus_sn' => $send_bonus_sn, 'total' => $total);
	    pdo_insert('sz_yi_bonus', $log);
	 }

 

 
    $time = time();
 
    $day_times = intval($set['settledays']) * 3600 * 24;
    $daytime = strtotime(date('Y-m-d 00:00:00'));
	if(empty($set['sendmonth'])){
	    $stattime = $daytime - 86400;
	    $endtime = $daytime - 1;
	}else if($set['sendmonth'] == 1){
	    $stattime = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
	    $endtime = mktime(0, 0, 0, date('m'), 1, date('Y')) - 1;
	}
	$sql = 'select m.* from ' . tablename('sz_yi_member') . ' m left join ' . tablename('sz_yi_bonus_level') . " l on m.bonuslevel=l.id and m.bonus_status=1 where 1 and l.premier=1 and m.uniacid={$_W['uniacid']}";
	$list = pdo_fetchall($sql);
	foreach ($list as $key => & $row){
	    $bonuspremier = $model->  premierInfo($row['openid'], array('ok', 'pay', 'myorder'));
	    if($bonuspremier['myordermoney'] < $set['consume_withdraw']){
	        unset($list[$key]);
	    }else{
	        $level = pdo_fetch('select id, levelname from ' . tablename('sz_yi_bonus_level') . ' where id=' . $row['bonuslevel']);
	        $row['levelname'] = $level['levelname'];
	        $row['commission_ok'] = $levelmoneys[$level['id']];
	        $row['commission_pay'] = number_format($bonuspremier['commission_pay'], 2);
	    }
	}
	unset($row);
	$send_bonus_sn = time();

    foreach ($list as $key => $value){
        $send_money = $value['commission_ok'];
        $sendpay = 1;
        if(empty($set['paymethod'])){
            m('member') -> setCredit($value['openid'], 'credit2', $send_money);
        }else{
            $logno = m('common') -> createNO('bonus_log', 'logno', 'RB');
            $result = m('finance') -> pay($value['openid'], 1, $send_money * 100, $logno, '【' . $setshop['name'] . '】' . $value['levelname'] . '分红');
            if (is_error($result)){
                $sendpay = 0;
                $sendpay_error = 1;
            }
        }
        pdo_insert('sz_yi_bonus_log', array('openid' => $value['openid'], 'uid' => $value['uid'], 'money' => $send_money, 'uniacid' => $_W['uniacid'], 'paymethod' => $set['paymethod'], 'sendpay' => $sendpay, 'isglobal' => 1, 'status' => 1, 'ctime' => time(), 'send_bonus_sn' => $send_bonus_sn));
        if($sendpay == 1){
           $model-> sendMessage($member['openid'], array('nickname' => $value['nickname'], 'levelname' => $value['levelname'], 'commission' => $send_money, 'type' => empty($set['paymethod']) ? '余额' : '微信钱包'), TM_BONUS_GLOPAL_PAY);
        }
    }
    $log = array('uniacid' => $_W['uniacid'], 'money' => $totalmoney, 'status' => 1, 'ctime' => time(), 'paymethod' => $set['paymethod'], 'sendpay_error' => $sendpay_error, 'isglobal' => 1, 'utime' => $daytime, 'send_bonus_sn' => $send_bonus_sn, 'total' => $total);
    pdo_insert('sz_yi_bonus', $log);

 
 
