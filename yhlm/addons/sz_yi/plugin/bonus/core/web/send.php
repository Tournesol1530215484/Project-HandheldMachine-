<?php
/**
 * 全球分红
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ApiCenter Inc. (http://api.chilang.com.cn)
 * @license    http://api.chilang.com.cn
 * @link       http://api.chilang.com.cn
 * @since      File available since Release v1.1
 * @Id         client.php 1079 2016-09-10 02:24:03Z chenhaisheng 
 */
global $_W, $_GPC;
ca('bonus.send.view');
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$set       = $this -> getSet();
$time      = time();
$pindex    = max(1, intval($_GPC['page']));
$psize     = 20;
$day_times = intval($set['settledays']) * 3600 * 24;
$daytime   = strtotime(date('Y-m-d 00:00:00'));
$sql       = 'select distinct cg.mid from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and ({$time} - o.finishtime > {$day_times})  ORDER BY o.finishtime DESC,o.status DESC";
$count = pdo_fetchall($sql);
$setshop = m('common') -> getSysset('shop');
if (empty($_GPC['export'])){
    $sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}
$p = p('commission') -> getSet();
$list = pdo_fetchall($sql);
$totalmoney = 0;
foreach ($list as $key => & $row){
    $member = $this -> model -> getInfo($row['mid'], array('ok', 'pay', 'myorder'));
    if($member['myordermoney'] < $set['consume_withdraw'] || empty($member)){
        unset($list[$key]);
    }else{
        if($member['commission_ok'] <= 0){
            unset($list[$key]);
        }else{
            if(!empty($member['bonuslevel'])){
                $row['realname'] = pdo_fetchcolumn('select levelname from ' . tablename('sz_yi_bonus_level') . ' where id=' . $member['bonuslevel']);
            }else{
                $row['realname'] = $set['levelname'];
            }
            $row['commission_ok'] = $member['commission_ok'];
            $row['commission_pay'] = $member['commission_pay'];
            $row['id'] = $member['id'];
            $row['avatar'] = $member['avatar'];
            $row['nickname'] = $member['nickname'];
            $row['realname'] = $member['realname'];
            $row['mobile'] = $member['mobile'];
            $totalmoney += $member['commission_ok'];
        }
    }
}
unset($row);
$total = count($list);
$send_bonus_sn = time();
$sendpay_error = 0;
$bonus_money = 0;
if (!empty($_POST)){
    $islog = false;
    if($total <= 0){
        message('发放人数为0，不能发放。', "", 'error');
    }
    foreach ($count as $key => $value){
        $member = $this -> model -> getInfo($value['mid'], array('ok', 'pay'));
        $send_money = $member['commission_ok'];
        $sendpay = 1;
        $islog = true;
        $level = $this -> model -> getlevel($member['openid']);
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
            $this -> model -> sendMessage($member['openid'], array('nickname' => $member['nickname'], 'levelname' => $level['levelname'], 'commission' => $send_money, 'type' => empty($set['paymethod']) ? '余额' : '微信钱包'), TM_BONUS_PAY);
        }
        $bonus_goods = array('status' => 3, 'applytime' => $time, 'checktime' => $time, 'paytime' => $time, 'invalidtime' => $time);
        pdo_update('sz_yi_bonus_goods', $bonus_goods, array('mid' => $member['id'], 'uniacid' => $_W['uniacid']));
    }
    if($islog){
        $log = array('uniacid' => $_W['uniacid'], 'money' => $totalmoney, 'status' => 1, 'ctime' => time(), 'paymethod' => $set['paymethod'], 'sendpay_error' => $sendpay_error, 'utime' => $daytime, 'send_bonus_sn' => $send_bonus_sn, 'total' => $total);
        pdo_insert('sz_yi_bonus', $log);
    }
    message('代理商分红发放成功', $this -> createPluginWebUrl('bonus/detail', array('sn' => $send_bonus_sn)), 'success');
}
include $this -> template('send');
