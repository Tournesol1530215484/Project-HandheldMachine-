<?php
 global $_W, $_GPC;
 $Uuniacid=$_W['uniacid'];
$perms = pdo_fetch('select * from ' . tablename('sz_yi_perm_role') . ' where status1 = 1 and uniacid = '.$_W['uniacid']);
$dealmerch_perms = 'shop,shop.goods,shop.goods.view,shop.goods.add,shop.goods.edit,shop.goods.delete,order,order.view,order.view.status_1,order.view.status0,order.view.status1,order.view.status2,order.view.status3,order.view.status4,order.view.status5,order.view.status9,order.op,order.op.pay,order.op.send,order.op.sendcancel,order.op.finish,order.op.verify,order.op.fetch,order.op.close,order.op.refund,order.op.export,order.op.changeprice,exhelper,exhelper.print,exhelper.print.single,exhelper.print.more,exhelper.exptemp1,exhelper.exptemp1.view,exhelper.exptemp1.add,exhelper.exptemp1.edit,exhelper.exptemp1.delete,exhelper.exptemp1.setdefault,exhelper.exptemp2,exhelper.exptemp2.view,exhelper.exptemp2.add,exhelper.exptemp2.edit,exhelper.exptemp2.delete,exhelper.exptemp2.setdefault,exhelper.senduser,exhelper.senduser.view,exhelper.senduser.add,exhelper.senduser.edit,exhelper.senduser.delete,exhelper.senduser.setdefault,exhelper.short,exhelper.short.view,exhelper.short.save,exhelper.printset,exhelper.printset.view,exhelper.printset.save,exhelper.dosen,taobao,taobao.fetch,dealmerchmenu,dealmerchmenu.goods';
if(empty($perms)){
    $data = array('rolename' => '供应商', 'status' => 1, 'status1' => 1, 'perms' => $dealmerch_perms, 'deleted' => 0,'uniacid'=>$_W['uniacid']);
    pdo_insert('sz_yi_perm_role' , $data);
    $logid = pdo_insertid();
}else{
    $logid = $perms['id'];
}
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load() -> model('user');
if($operation == 'post'){           //如果method是post类型
    global $id;
    $id = $_GPC['id'];              //如果有传id
    if (empty($id)){
        ca('dealmerch.dealmerch.add');
    }else{
        ca('dealmerch.dealmerch.view|dealmerch.dealmerch.edit');
    }
    $su_info = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=:uniacid and id=:id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
    if (!empty($su_info)){
        if ($su_info['uid'] == $_W['uid']){
            message('无法修改自己的权限！', referer(), 'error');
        }
    }
    
    if ($_W['isajax'] && $_W['ispost']){
        $data = array(
            'uniacid' => $_W['uniacid'], 
            'username' => '1-'.trim($_GPC['username']),
            'merchsn' => date('Ymdhis').rand(11111,99999),
            'roleid' => $logid, 
            'perms' => is_array($_GPC['perms']) ? implode(',',$_GPC['perms']) : '',
            'merchname'=>$_GPC['merchname'],
            'lat'=>$_GPC['map']['lat'],
            'lng'=>$_GPC['map']['lng'],
            'mobile'=>$_GPC['mobile'],
            'display'=>$_GPC['display'],
            'BusinessLicensePic'=>serialize($_GPC['BusinessLicensePic']),
            'ImageDetailFile'=>serialize($_GPC['ImageDetailFile']),
            'address'=>$_GPC['address'],
            'typeid'=>$_GPC['typeid'],
            'merchsite'=>$_GPC['merchsite'],
            'mintype'=>$_GPC['mintype'],
            'worknumber'=>trim($_GPC['worknumber']),
            'operat'=>trim($_GPC['operat']),
            'operatmobile'=>trim($_GPC['operatmobile']),
            'licenseoverdue'=>strtotime($_GPC['licenseoverdue']),
            'businessLicenseNo'=>trim($_GPC['businessLicenseNo']),
            'details'=>$_GPC['details'],
            'special'=>$_GPC['special'],
            'contact'=>trim($_GPC['contact']),
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
                $pwd['roleid']=$data['roleid'];         //易货商家表不需要这5个字段
                $pwd['perms']=$data['perms'];
                $pwd['uid']=$data['uid'];
                $pwd['uniacid']=$data['uniacid'];
                unset($data['username']);
                unset($data['roleid']);
                unset($data['perms']);
                pdo_insert('sz_yi_dealmerch_user',$data);       //易货商家表
                unset($data);
                $data=$pwd;
                $data['dealmerchid']=pdo_insertid();                  //易货商家表ID
                $data['status']=1; 

                pdo_insert('sz_yi_perm_user', $data);
                $id = pdo_insertid();
                plog('perm.user.add', "添加操作员 ID: {$id} 用户名: {$data['username']} ");
            }
        }
        die(json_encode(array('result' => 1)));
    }
}elseif ($operation == 'delete'){ 
    ca('dealmerch.dealmerch.delete');
    $id = intval($_GPC['id']);
    $item = pdo_fetch('SELECT id,uid,username,openid FROM ' . tablename('sz_yi_perm_user') . " WHERE id = '$id' and uniacid='$Uuniacid'");
    if (empty($item)){
        message('抱歉，操作员不存在或是已经被删除！', $this -> createPluginWebUrl('dealmerch/dealmerch'), 'error');
    }

    pdo_delete('sz_yi_perm_user', array('id' => $id, 'uniacid' => $_W['uniacid']));  
    pdo_delete('sz_yi_dealmerch_user', array('uid' => $item['uid'], 'uniacid' => $_W['uniacid'])); 
    pdo_delete('sz_yi_af_supplier', array('openid' => $item['openid'], 'uniacid' => $_W['uniacid'],'dealmerchid'=>'1'));
    pdo_delete('users', array('uid' => $item['uid']));      
    plog('dealmerch.dealmerch.delete', "删除操作员 ID: {$id} 用户名: {$item['username']} "); 
    message('操作员删除成功！', $this -> createPluginWebUrl('dealmerch/dealmerch'), 'success'); 
}else if($operation == 'gettype'){ 

    $id=intval($_GPC['pid']);
    $param=array(
        ':pid'=>$id, 
        ':uniacid'=>$Uuniacid
    );
    $mintype=pdo_fetchall('select id , title from '.tablename('sz_yi_merch_type').' where status=1 and pid=:pid and uniacid =:uniacid',$param);
    $mintype?show_json(1,$mintype):show_json(2,'改分类暂无行业小类!');
}
$param=[':uniacid'=>$_W['uniacid']];
$type=pdo_fetchall('select `id`,`title` from hs_sz_yi_merch_type where pid=0 and uniacid =:uniacid',$param);
load() -> func('tpl');
include $this -> template('dealmerch_add');
