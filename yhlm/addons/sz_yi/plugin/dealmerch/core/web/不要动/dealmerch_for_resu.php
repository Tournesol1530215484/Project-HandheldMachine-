<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
ca('dealmerch.dealmerch_for_resu');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and s.uniacid=:uniacid and s.dealmerchid = 1 and pu.dealmerchid > 0 ';
$params = array(':uniacid' => $_W['uniacid']);
if (!empty($_GPC['mid'])){
    $condition .= ' and s.id=:mid';
    $params[':mid'] = intval($_GPC['mid']);
}
if (!empty($_GPC['realname'])){
    $_GPC['realname'] = trim($_GPC['realname']); 
    $condition .= ' and s.realname like :realname or s.mobile like :realname ';
    $params[':realname'] = "%{$_GPC['realname']}%";
}
if (!empty($_GPC['addres'])){
    $_GPC['addres'] = trim($_GPC['addres']); 
    $condition .= ' and s.province like :addres or s.city like :addres or s.district like :addres';
    $params[':addres'] = "%{$_GPC['addres']}%";
}

if (!empty($_GPC['status'])){
    if ($_GPC['status'] == 1){
        $condition .= ' and s.status = 1';
    }
    if ($_GPC['status'] == 2){ 
        $condition .= ' and s.status = 2';
    } 
}else{
    $condition .= ' and s.status > 0'; 
}  
$sql = 'select s.*,pu.uid as realid from ' . tablename('sz_yi_af_supplier') . " s left join ".tablename('sz_yi_perm_user')." pu on pu.openid = s.openid where 1 {$condition}";
$sql.=' order by pu.uid desc ';          
if (empty($_GPC['export'])){
    $sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}
$list = pdo_fetchall($sql, $params);
foreach ($list as $key => $value) {
    $tmem=m('member')->getMember($value['openid']);
    if ($tmem['agentid']) {
        $ta=m('member')->getMember($tmem['agentid']);
    }
    if ($ta) {
        $list[$key]['agentname']=$ta['nickname'];
    }else{
        $list[$key]['agentname']='无';
    }
}
    //进行一个数据导出
    if ($_GPC['export1'] == '1'){
        $sql = 'select s.*,pu.uid as realid from ' . tablename('sz_yi_af_supplier') . " s left join ".tablename('sz_yi_perm_user')." pu on pu.openid = s.openid where 1 {$condition}";
        $sql.=' order by pu.uid desc ';          
        $lists = pdo_fetchall($sql, $params);

        plog('member.member.export', '导出会员数据');
        m('excel') -> export($lists, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '微信', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12),array('title' => '公司名称', 'field' => 'qq', 'width' => 12),array('title' => '省', 'field' => 'province', 'width' => 12),array('title' => '市', 'field' => 'city', 'width' => 12),array('title' => '区', 'field' => 'district', 'width' => 12))));
    }
 
$total =pdo_fetchcolumn('select COUNT(*) from ' . tablename('sz_yi_af_supplier') . " s left join ".tablename('sz_yi_perm_user')." pu on pu.openid = s.openid where 1 {$condition}",$params);
$pager = pagination($total, $pindex, $psize); 
load() -> func('tpl'); 
include $this -> template('dealmerch_for_resu');
