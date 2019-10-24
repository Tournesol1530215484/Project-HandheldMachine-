<?php 
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {  
    $storeids=pdo_fetchall('select id from '.tablename("sz_yi_exchange_address").' where uniacid = :uniacid and merch_uid = :merch_uid ',array(":uniacid"=>$_W['uniacid'],':merch_uid'=>$_W['uid']));
    $storeid=[];
    foreach ($storeids as $key => $value) {
        $storeid[]=$value['id'];
    }
    
     
    $storestr=implode(',',$storeid);
    if (empty($storestr)) {
        $storestr='0';
    }  
    $list = pdo_fetchall("SELECT s.*,m.nickname,m.avatar,m.mobile,m.realname,store.storename FROM " . tablename('sz_yi_saler') . "  s " . " left join " . tablename('sz_yi_member') . " m on s.openid=m.openid and m.uniacid = s.uniacid " . " left join " . tablename('sz_yi_store') . " store on store.id=s.storeid " . " WHERE s.uniacid = '{$_W['uniacid']}' and s.isexchange = 1 and s.id in ({$storestr}) ORDER BY id asc"); 
} elseif ($operation == 'post') {

    $id = intval($_GPC['id']); 
    
    $item = pdo_fetch("SELECT * FROM " . tablename('sz_yi_saler') . " WHERE id =:id and uniacid=:uniacid limit 1", array(  
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    if (!empty($item)) {
        $saler = m('member')->getMember($item['openid']); 
        $store = pdo_fetch("SELECT * FROM " . tablename('sz_yi_exchange_address') . " WHERE id =:id and uniacid=:uniacid limit 1", array( 
            ':uniacid' => $_W['uniacid'],
            ':id' => $item['storeid']
        ));
    }

    if ($_GPC['ac']=='my') {

        $data = array(
            'uniacid' => $_W['uniacid'], 
            'storeid' => intval($_GPC['storeid']),
            'openid' => trim($_GPC['openid']), 
            'status' => intval($_GPC['status']),
            'salername' => trim($_GPC['store']),
            'isexchange' => 1,
            'mobile'    => trim($_GPC['mobile'])
        );
        $m    = m('member')->getMember($data['openid']);
        if (!empty($id)) {
            pdo_update('sz_yi_saler', $data, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            plog('verify.saler.edit', "编辑核销员 ID: {$id} <br/>核销员信息: ID: {$m['id']} / {$m['openid']}/{$m['nickname']}/{$m['realname']}/{$m['mobile']} ");
        } else {
            $scount = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('sz_yi_saler') . ' WHERE openid =:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $data['openid']));
            if ($scount > 0) {
                message('此会员已经成为核销员，没法重复添加', '', 'error');
            }
            pdo_insert('sz_yi_saler', $data);
            $id = pdo_insertid();
            plog('verify.saler.add', "添加核销员 ID: {$id}  <br/>核销员信息: ID: {$m['id']} / {$m['openid']}/{$m['nickname']}/{$m['realname']}/{$m['mobile']} ");
        }
        message('更新核销员成功！', $this->createPluginWebUrl('suppliermenu/dealmerch_adminset', array(
            'op' => 'display'
        )), 'success');
    }
    
} elseif ($operation == 'delete') {
    ca('verify.saler.delete');
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,openid FROM " . tablename('sz_yi_saler') . " WHERE id = '$id'");
    if (empty($item)) {
        message('抱歉，核销员不存在或是已经被删除！', $this->createPluginWebUrl('suppliermenu/dealmerch_adminset', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_saler', array(
        'id' => $id,
        'uniacid' => $_W['uniacid']
    ));
    $m = m('member')->getMember($item['openid']);
    plog('verify.saler.delete', "删除核销员 ID: {$id}  <br/>核销员信息: ID: {$m['id']} / {$m['openid']}/{$m['nickname']}/{$m['realname']}/{$m['mobile']} ");
    message('核销员删除成功！', $this->createPluginWebUrl('suppliermenu/dealmerch_adminset', array(
        'op' => 'display' 
    )), 'success');
} elseif ($operation == 'query') {
    $kwd                = trim($_GPC['keyword']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];
    $condition          = " and s.uniacid=:uniacid";
    if (!empty($kwd)) {
        $condition .= " AND ( m.nickname LIKE :keyword or m.realname LIKE :keyword or m.mobile LIKE :keyword or store.storename like :keyword )";
        $params[':keyword'] = "%{$kwd}%";
    }
    $ds = pdo_fetchall("SELECT s.*,m.nickname,m.avatar,m.mobile,m.realname,store.storename FROM " . tablename('sz_yi_saler') . "  s " . " left join " . tablename('sz_yi_member') . " m on s.openid=m.openid " . " left join " . tablename('sz_yi_store') . " store on store.id=s.storeid " . " WHERE 1 {$condition} ORDER BY id asc", $params);
    include $this->template('query_saler');
    exit;
}
load()->func('tpl');
include $this->template('dealmerch_adminset');
