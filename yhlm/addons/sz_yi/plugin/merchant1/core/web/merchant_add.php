<?php
 global $_W, $_GPC;
 $Uuniacid=$_W['uniacid'];
$perms = pdo_fetch('select * from ' . tablename('sz_yi_perm_role') . ' where status1 = 1 and uniacid = '.$_W['uniacid']);
$merchant_perms = 'shop,shop.goods,shop.goods.view,shop.goods.add,shop.goods.edit,shop.goods.delete,order,order.view,order.view.status_1,order.view.status0,order.view.status1,order.view.status2,order.view.status3,order.view.status4,order.view.status5,order.view.status9,order.op,order.op.pay,order.op.send,order.op.sendcancel,order.op.finish,order.op.verify,order.op.fetch,order.op.close,order.op.refund,order.op.export,order.op.changeprice,exhelper,exhelper.print,exhelper.print.single,exhelper.print.more,exhelper.exptemp1,exhelper.exptemp1.view,exhelper.exptemp1.add,exhelper.exptemp1.edit,exhelper.exptemp1.delete,exhelper.exptemp1.setdefault,exhelper.exptemp2,exhelper.exptemp2.view,exhelper.exptemp2.add,exhelper.exptemp2.edit,exhelper.exptemp2.delete,exhelper.exptemp2.setdefault,exhelper.senduser,exhelper.senduser.view,exhelper.senduser.add,exhelper.senduser.edit,exhelper.senduser.delete,exhelper.senduser.setdefault,exhelper.short,exhelper.short.view,exhelper.short.save,exhelper.printset,exhelper.printset.view,exhelper.printset.save,exhelper.dosen,taobao,taobao.fetch,merchantmenu,merchantmenu.goods';
if(empty($perms)){
    $data = array('rolename' => '供应商', 'status' => 1, 'status1' => 1, 'perms' => $merchant_perms, 'deleted' => 0,'uniacid'=>$_W['uniacid']);
    pdo_insert('sz_yi_perm_role' , $data);
    $logid = pdo_insertid();
}else{
    $logid = $perms['id'];
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load() -> model('user');
if($operation == 'post'){
    $data = array(
        'uniacid' => $_W['uniacid'],
        'username' => $_W['uniacid'].'_'.trim($_GPC['username']),
        'roleid' => $logid,
        'status' => 1,
        'perms' => is_array($_GPC['perms']) ? implode(',',$_GPC['perms']) : '',
        'merchname'=>$_GPC['merchname'],
        'lat'=>$_GPC['map']['lat'],
        'lng'=>$_GPC['map']['lng'],
        'mobile'=>$_GPC['mobile'],
        'display'=>$_GPC['display'],
        'img'=>$_GPC['img'],
        'address'=>$_GPC['address'],
        'logo'=>$_GPC['logo'],
        'title'=>$_GPC['title'],
        'average'=>$_GPC['average'],
        'typeid'=>$_GPC['typeid'],
        'details'=>$_GPC['contnet'],
        'browse'=>$_GPC['browse'],
        'hours'=>$_GPC['hours'],
            'province'=>trim($_GPC['reside']['province']),
            'city'=>trim($_GPC['reside']['city']),
            'district'=>trim($_GPC['reside']['district']),
    );

if (!empty($su_info['id'])){
    $pwd = array();
    $result = pdo_fetch('select * from ' . tablename('users') . ' where uid=:uid' , array(':uid' => $su_info['uid']));
    $pwd['password'] = user_hash($_GPC['password'], $result['salt']);
    pdo_update('users', $pwd, array('uid' => $su_info['uid']));
    pdo_update('sz_yi_perm_user', $pwd, array('uid' => $su_info['uid'], 'uniacid' => $_W['uniacid']));
    plog('perm.user.edit', "编辑操作员 ID: {$su_info['uid']} 用户名: {$data['username']} ");
}else{
    $result = pdo_fetch('select * from ' . tablename('users') . ' where username=\'' . $data['username'] . '\'');
    if (!empty($result)){
        die(json_encode(array('result' => 0, 'message' => '此用户为系统存在用户，无法添加')));
    }else{
        $data['uid'] = user_register(array('username' => $data['username'], 'password' => $_GPC['password']));
        $pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid' , array(':uid' => $data['uid']));
        pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $data['uniacid'], 'role' => 'operator'));
        $pwd['username']=$data['username'];
        $pwd['roleid']=$data['roleid'];         //商家表不需要这5个字段
        $pwd['perms']=$data['perms'];
        $pwd['uid']=$data['uid'];
        $pwd['uniacid']=$data['uniacid'];
        unset($data['username']);
        unset($data['roleid']);
        unset($data['perms']);
        pdo_insert('sz_yi_merch_user',$data);       //商家表
        unset($data);
        $data=$pwd;
        $data['merchid']=pdo_insertid();                  //商家表ID
        $data['status']=1;

        pdo_insert('sz_yi_perm_user', $data);
        $id = pdo_insertid();
        plog('perm.user.add', "添加操作员 ID: {$id} 用户名: {$data['username']} ");
    }
}
die(json_encode(array('result' => 1)));
}elseif ($operation == 'delete'){
    ca('merchant.merchant.delete');
    $id = intval($_GPC['id']);
    $item = pdo_fetch('SELECT id,uid,username FROM ' . tablename('sz_yi_perm_user') . " WHERE id = '$id' and uniacid='$Uuniacid'");
    if (empty($item)){
        message('抱歉，操作员不存在或是已经被删除！', $this -> createPluginWebUrl('merchant/merchant'), 'error');
    }
    pdo_delete('sz_yi_perm_user', array('id' => $id, 'uniacid' => $_W['uniacid']));
    pdo_delete('users', array('uid' => $item['uid']));
    plog('merchant.merchant.delete', "删除操作员 ID: {$id} 用户名: {$item['username']} ");
    message('操作员删除成功！', $this -> createPluginWebUrl('merchant/merchant'), 'success');
}
$param=[':uniacid'=>$_W['uniacid']];
$type=pdo_fetchall('select `id`,`title` from hs_sz_yi_merch_type where uniacid =:uniacid',$param);
load() -> func('tpl');
include $this -> template('merchant_add');
