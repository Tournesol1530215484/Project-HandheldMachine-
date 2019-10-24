<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user') -> getOpenid();
$ordersn=$_GPC['outtradeno'];

$uniacid=$_W['uniacid'];
$sql="select * from ".tablename('sz_yi_app_log')." where uniacid=:uniacid and ordersn=:ordersn";
$log=pdo_fetch($sql,array(':uniacid'=>$uniacid,':ordersn'=>$ordersn)); 

if(empty($log)){
    message('未查到此数据，请联系管理员!', $this->createMobileUrl('order/list'), 'error');
}
$order = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where ordersn=:ordersn and uniacid=:uniacid and openid=:openid limit 1', array(':ordersn' => $ordersn, ':uniacid' => $uniacid, ':openid' => $openid));
$orderid=$order['id'];

if (empty($order)){
    message('订单未找到!', $this->createMobileUrl('order/list'), 'error');
}
pdo_update('sz_yi_order', array('paytype' => 30), array('id' => $order['id']));
$ret = array();
$ret['result'] = 'success';
$ret['type'] = 'app';
$ret['from'] = 'return';
$ret['tid'] = $log['ordersn'];
$ret['user'] = $log['openid'];
$ret['fee'] = $log['total_fee'];
$ret['weid'] = $log['weid'];
$ret['uniacid'] = $log['uniacid'];

$pay_result = $this -> payResult($ret);
 $url = $this -> createMobileUrl('order/detail', array('id' => $order['id']));
    die("<script>top.window.location.href='{$url}'</script>");