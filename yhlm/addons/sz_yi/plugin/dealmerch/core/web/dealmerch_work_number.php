<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W, $_GPC;
ca('dealmerch.dealmerch_work_number');

$op=empty($_GPC['op'])?'display':$_GPC['op'];
$where = ' and pu.dealmerchid > 0 ';
if ($op == 'getinfo'){
    $uid=intval($_GPC['uid']);
    $info=pdo_fetch('select city,operat,operatmobile from '.tablename('sz_yi_dealmerch_user').' where uniacid =:uniacid and uid= :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
    show_json(1,$info);
}
$roleid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid ='.$_W['uniacid']);

$sql = 'select pu.*,m.id as mid from ' . tablename('sz_yi_perm_user') . ' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where pu.roleid= \'' . $roleid . '\' ' . $where ;
    $sql .= ' order by pu.uid desc';  
    $list = pdo_fetchall($sql);

$merch = pdo_fetchall($sql);

foreach ($merch as $k => &$v){
    $tempinfo=pdo_fetch('select realname,username from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$v['uid']));
    $v['realname']=$tempinfo['realname'];
    $v['username']=$tempinfo['username'];
}

unset($row);

load() -> func('tpl');
include $this -> template('dealmerch_work_number');
exit;
