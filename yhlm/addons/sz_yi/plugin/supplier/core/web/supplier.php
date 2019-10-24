<?php
global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'list' : $_GPC['op'];
ca('supplier.supplier');

if ($operation == 'display'){
 
    $roleid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid ='.$_W['uniacid']);
    $where = '';
    if(empty($_GPC['uid'])){
        $where .= 'and merchid=0 and uniacid=' . $_W['uniacid'] ;
    }else{
        $where .= ' and merchid=0 and uid="' . $_GPC['uid'] . '" and uniacid=' . $_W['uniacid'];
    }

    $list = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . ' where dealmerchid = 0 and merchid = 0 and muserid = 0  and roleid= \'' . $roleid . '\' ' . $where);

    $total = count($list); 
}else if ($operation == 'detail'){
    $uid = intval($_GPC['uid']);
    $supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uid="' . $uid . '" and uniacid=' . $_W['uniacid']);
    // 查询身份证图片
    $supplierinfo['idimgs'] = pdo_fetch('select idimg1,idimg2,permit from'.tablename('sz_yi_supplier_idimages').'where uniacid=:uniacid and openid=:openid', array(':uniacid'=>$_W['uniacid'], ':openid'=>$supplierinfo['openid']));
    $minfo=m('member')->getMember($supplierinfo['openid']);
    $supplierinfo['weixin']=$minfo['weixin'];
    $agent=m('member')->getMember($minfo['agentid']);
    $supplierinfo['agent']=$minfo['agentid'];
    if(!empty($supplierinfo['openid'])){
        $saler = m('member') -> getInfo($supplierinfo['openid']);
    }
    $totalmoney = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
    $totalmoneyok = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.supplier_apply_status=1 and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
    if(checksubmit('submit')){
        $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
        $info=array(
            'weixin'=>$_GPC['weixin'],
            'agentid'=>$_GPC['agentid']
        );
        pdo_update('sz_yi_member',$info,array('id'=>$minfo['id']));
        $data['provance'] = $_GPC['birth']['province'];
        $data['city']     = $_GPC['birth']['city'];
        $data['area']     = $_GPC['birth']['district'];
        pdo_update('sz_yi_perm_user', $data, array('uid' => $uid));
        message('保存成功!', $this -> createPluginWebUrl('supplier/supplier'), 'success');
    }
} elseif ($operation == 'list') {
    $roleid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid ='.$_W['uniacid']);

    $condition = 'WHERE p.merchid = 0 and p.uniacid = :uniacid and p.dealmerchid = 0 and p.merchid = 0 and p.roleid = :roleid and p.status = 1 and p.deleted = 0'; 
    $paras = array();
    $paras['uniacid'] = $_W['uniacid'];
    $paras['roleid']  = $roleid;
    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime = time();
    }
    if (!empty($_GPC['searchtime'])) {
        $starttime = strtotime($_GPC['time']['start']);
        $endtime = strtotime($_GPC['time']['end']);
        $condition .= ' AND p.create_time >= :starttime AND p.create_time <= :endtime ';
        $paras[':starttime'] = $starttime;
        $paras[':endtime'] = $endtime;
    }
    if (!empty($_GPC['realname'])) {
        $_GPC['realname'] = trim($_GPC['realname']);
        $condition .= ' and ( m.realname like :realname or m.nickname like :realname or m.mobile like :realname)';
        $paras[':realname'] = "%{$_GPC['realname']}%";
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize  = 15;

    $sql = 'SELECT m.nickname,m.realname,m.avatar,m.mobile,p.* FROM '.tablename('sz_yi_perm_user').' as p left join '.tablename('sz_yi_member').' as m on p.openid = m.openid '.$condition .' order by id desc limit '.($pindex - 1) * $psize.' , '.$psize;
    $sqlTotal = 'SELECT count(p.id) FROM '.tablename('sz_yi_perm_user').' as p left join '.tablename('sz_yi_member').' as m on p.openid = m.openid '.$condition;
    $total = pdo_fetchcolumn($sqlTotal, $paras);
    $pager = pagination($total, $pindex, $psize);
    $list = pdo_fetchall($sql, $paras);

    // echo '<pre>';
    // print_r($list);die;
}
load() -> func('tpl');
include $this -> template('supplier');
