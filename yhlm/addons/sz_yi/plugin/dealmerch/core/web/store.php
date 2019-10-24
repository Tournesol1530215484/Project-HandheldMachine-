<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'query';

if ($operation == 'query') {                //查询兑换点
    $kwd                = trim($_GPC['keyword']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];
    $condition          = " and uniacid=:uniacid and status = 1 ";
    if (!empty($kwd)) {
        $condition .= " AND `title` LIKE :keyword ";
        $condition .= " or `address` LIKE :keyword ";
        $condition .= " or `mobile` LIKE :keyword ";
        $params[':keyword'] = "%{$kwd}%";
    }
    $ds = pdo_fetchall('SELECT id,title,address,mobile,exchangeDate,exchangeTime FROM ' . tablename('sz_yi_exchange_address') . " WHERE 1 {$condition} order by id asc", $params);
    include $this->template('query_saler');
    exit;

}else if($operation == 'notice'){
    $kwd                = trim($_GPC['keyword']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];
    $condition          = " and uniacid=:uniacid ";
    if (!empty($kwd)) {
        $condition .= " AND `nickname` LIKE :keyword ";
        $condition .= " or `realname` LIKE :keyword ";
        $condition .= " or `mobile` LIKE :keyword ";
        $params[':keyword'] = "%{$kwd}%";
    }
    $ds = pdo_fetchall('SELECT id,avatar,nickname,realname,mobile FROM ' . tablename('sz_yi_member') . " WHERE 1 {$condition} order by id asc", $params);
    include $this->template('query_user');           
    exit;
}

load()->func('tpl');
include $this->template('store');
