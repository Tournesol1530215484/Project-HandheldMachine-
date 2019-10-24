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
    $id = $_GPC['id'];                //如果有传id
//    if (empty($id)){
    $openid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where dealmerchid > 0 and uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

//        ca('suppliermenu.dealmerch.add');
//    }else{
//        ca('suppliermenu.dealmerch.view|suppliermenu.dealmerch.edit');
//    }
    $su_info = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=:uniacid and id=:id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
//    if (!empty($su_info)){
//        if ($su_info['uid'] == $_W['uid']){
//            message('无法修改自己的权限！', referer(), 'error');
//        }
//    }

    if ($_W['isajax'] && $_W['ispost']){

        //存在未审核的数据  uid在审核时作为条件
        $data = array(
            'uniacid' => $_W['uniacid'],
            'merchname'=>$_GPC['merchname'],
            'uid'     =>$_W['uid'],
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

        $log=array(
            'uniacid'=>$_W['uniacid'], 
            'uid'=>$_W['uid'],
            'openid'=>$openid,
            'sub_time'=>time(),
            'status' => 0               //0审核中 1成功 2失败
        );

        //如果存在未审核的信息 将覆盖该信息 status = 0 
        $virtual=pdo_fetch('select id,virtualid from '.tablename('sz_yi_virtual_log').' where uniacid = :uniacid and uid = :uid and status = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
        if (!empty($virtual)){
            pdo_update('sz_yi_virtual_dealmerch_user',$data,array('id'=>$virtual['virtualid']));
            pdo_update('sz_yi_virtual_log',$log,array('id'=>$virtual['id']));
        }else{
            //virtual_dealmerch_user表中只会出现一条该用户未审核的记录
            $id=pdo_fetchcolumn('select id from '.tablename('sz_yi_virtual_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));
            if ($id){ 
                pdo_update('sz_yi_virtual_dealmerch_user',$data,array('id'=>$id)); 
            }else{ 
                pdo_insert('sz_yi_virtual_dealmerch_user',$data); 
                $id=pdo_insertid(); 
            }
            $log['virtualid']=$id;
            pdo_insert('sz_yi_virtual_log',$log);
        }

//        pdo_update('sz_yi_dealmerch_user',$data,array('id'=>$_GPC['id'],"uniacid"=>$_W['uniacid']));
        die(json_encode(array('result' => 1)));
    }
}elseif ($operation == 'delete'){
    ca('suppliermenu.dealmerch.delete');
    $id = intval($_GPC['id']);
    $item = pdo_fetch('SELECT id,uid,username FROM ' . tablename('sz_yi_perm_user') . " WHERE id = '$id' and uniacid='$Uuniacid'");
    if (empty($item)){
        message('抱歉，操作员不存在或是已经被删除！', $this -> createPluginWebUrl('suppliermenu/dealmerch_add'), 'error');
    }
    pdo_delete('sz_yi_perm_user', array('id' => $id, 'uniacid' => $_W['uniacid']));
    pdo_delete('users', array('uid' => $item['uid']));
    plog('dealmerch.dealmerch.delete', "删除操作员 ID: {$id} 用户名: {$item['username']} ");
    message('操作员删除成功！', $this -> createPluginWebUrl('suppliermenu/dealmerch_add'), 'success');
}else if($operation == 'gettype'){

    $id=intval($_GPC['pid']);
    $param=array(
        ':pid'=>$id,
        ':uniacid'=>$Uuniacid
    );
    $mintype=pdo_fetchall('select id , title from '.tablename('sz_yi_merch_type').' where status=1 and pid=:pid and uniacid =:uniacid',$param);
    $mintype?show_json(1,$mintype):show_json(2,'改分类暂无行业小类!');
}
$nowLog=pdo_fetchcolumn('select virtualid from '.tablename('sz_yi_virtual_log').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

    $su_info = pdo_fetch('select * from ' . tablename('sz_yi_virtual_dealmerch_user') . ' where uniacid=:uniacid and id=:id order by id desc', array(':uniacid' => $_W['uniacid'], ':id' => $nowLog));

// $su_info = pdo_fetch('select * from ' . tablename('sz_yi_dealmerch_user') . ' where uniacid=:uniacid and uid=:id', array(':uniacid' => $_W['uniacid'], ':id' => $_W['uid']));
$su_info['ImageDetailFile']=unserialize($su_info['ImageDetailFile']);
$su_info['BusinessLicensePic']=unserialize($su_info['BusinessLicensePic']);
$param=[':uniacid'=>$_W['uniacid']];
$audit_log=pdo_fetchall('select * from '.tablename('sz_yi_virtual_log').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));



$type=pdo_fetchall('select `id`,`title` from hs_sz_yi_merch_type where pid=0 and uniacid =:uniacid',$param);
load() -> func('tpl');
include $this -> template('dealmerch_add');
