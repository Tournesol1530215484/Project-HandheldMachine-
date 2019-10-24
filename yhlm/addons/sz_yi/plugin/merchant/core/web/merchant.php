<?php
 global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
ca('merchant.merchant'); 
if ($operation == 'display'){
    $roleid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid ='.$_W['uniacid']);
    $where = ' where p.merchid > 0 and p.roleid = :roleid and p.uniacid= :uniacid ';
    $paras=array(':roleid' => $roleid,
        ':uniacid' => $_W['uniacid']
    );
    if(!empty($_GPC['uid'])){
        $where .= ' and p.uid = :uid ';
        $paras[':uid'] = $_GPC['uid'];
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize  = 15;
    $sql = 'SELECT m.nickname,m.realname,m.avatar,m.mobile,p.* FROM '.tablename('sz_yi_perm_user').' as p left join '.tablename('sz_yi_member').' as m on p.openid = m.openid '.$where .' order by id desc limit '.($pindex - 1) * $psize.' , '.$psize;
    $sqlTotal = 'SELECT count(p.id) FROM '.tablename('sz_yi_perm_user').' as p left join '.tablename('sz_yi_member').' as m on p.openid = m.openid '.$where;
    $total = pdo_fetchcolumn($sqlTotal, $paras);
    $pager = pagination($total, $pindex, $psize);
    $list = pdo_fetchall($sql, $paras);
}else if ($operation == 'detail'){
    // $uid = intval($_GPC['uid']);
    // $supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uid="' . $uid . '" and uniacid=' . $_W['uniacid']);
    // if(!empty($supplierinfo['openid'])){
    //     $saler = m('member') -> getInfo($supplierinfo['openid']);
    // }
    // $totalmoney = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
    // $totalmoneyok = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.supplier_apply_status=1 and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));

    // if(checksubmit('submit')){
    //     $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
    //     pdo_update('sz_yi_perm_user', $data, array('uid' => $uid));
    //     message('保存成功!', $this -> createPluginWebUrl('merchant/merchant'), 'success');
    // }
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
        message('保存成功!', $this -> createPluginWebUrl('merchant/merchant'), 'success');
    }
}
load() -> func('tpl');
include $this -> template('merchant');
