<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

 global $_W, $_GPC;

load() -> model('user');

$su_info = pdo_fetch('select * from ' . tablename('sz_yi_virtual_dealmerch_user') . ' where uniacid=:uniacid and uid=:id order by id desc', array(':uniacid' => $_W['uniacid'], ':id' => $_GPC['uid']));
$su_info['ImageDetailFile']=unserialize($su_info['ImageDetailFile']);
$su_info['BusinessLicensePic']=unserialize($su_info['BusinessLicensePic']);
$param=[':uniacid'=>$_W['uniacid']];
$audit_log=pdo_fetchall('select * from '.tablename('sz_yi_virtual_log').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_GPC['uid']));
$type=pdo_fetchall('select `id`,`title` from hs_sz_yi_merch_type where pid=0 and uniacid =:uniacid',$param);
load() -> func('tpl');
include $this -> template('showmerchinfo');
