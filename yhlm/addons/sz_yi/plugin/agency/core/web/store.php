<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'query';

if ($operation == 'query') {                //查询兑换点
    $kwd                = trim($_GPC['keyword']);
    $uid                = trim($_GPC['uid']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];       //只能选择自己的兑换点
    
//  if (!empty($uid)) {
//  	var_dump($uid);
//      $condition .= " AND merch_uid = :uid "; 
//      $params[':uid'] =$uid; 	 		 	
//  }
    $condition   = " uniacid=:uniacid and status = 1 and merch_uid = {$_W['uid']} ";
	
    if (!empty($kwd)) {
        $condition .= " AND `title` LIKE :keyword "; 
        $condition .= " or `address` LIKE :keyword "; 
        $condition .= " or `mobile` LIKE :keyword ";
        $params[':keyword'] = "%{$kwd}%"; 
    }

    $ds = pdo_fetchall('SELECT id,title,address,mobile,exchangeDate,exchangeTime FROM ' . tablename('sz_yi_exchange_address') . " WHERE {$condition} order by id asc", $params);
    include $this->template('query_saler');
    exit;
}

load()->func('tpl'); 
include $this->template('store'); 
