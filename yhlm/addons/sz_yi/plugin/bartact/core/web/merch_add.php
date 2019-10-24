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
    // if (empty($id)){
    //     ca('dealmerch.dealmerch.add');
    // }else{
    //     ca('dealmerch.dealmerch.view|dealmerch.dealmerch.edit');
    // }
    $su_info = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid=:uniacid and id=:id', array(':uniacid' => $_W['uniacid'], ':id' => $id));
    if (!empty($su_info)){
        if ($su_info['uid'] == $_W['uid']){
            message('无法修改自己的权限！', referer(), 'error');
        }
    }
    
    if ($_W['isajax'] && $_W['ispost']){                      
        empty($_GPC['data']['openid']) && show_json(0,'请先选择会员!');       
        $openid=trim($_GPC['data']['openid']);      
        $exists=m('activity')->getMuser($openid); 
        $exists && show_json(0,'该会员已经成为活动商!');       
        $data = array(                         
            'uniacid' => $_W['uniacid'],                  
            'username' => '6-'.trim($_GPC['username']),            
            'roleid' => 50,                             
            'mobile'=>$_GPC['mobile'],                          
            'openid'=>$openid,                                        
            'orgName'=>$_GPC['orgName'],                           
            'province'=>trim($_GPC['reside']['province']),       
            'city'=>trim($_GPC['reside']['city']),      
            'area'=>trim($_GPC['reside']['district'])
        );      

        if (!empty($su_info['id'])){     
            $pwd = array();
            $result = pdo_fetch('select * from ' . tablename('users') . ' where uid = :uid' , array(':uid' => $su_info['uid']));
            $pwd['password'] = user_hash($_GPC['password'], $result['salt']);
            pdo_update('users', $pwd, array('uid' => $su_info['uid']));
            pdo_update('sz_yi_perm_user', $pwd, array('uid' => $su_info['uid'], 'uniacid' => $_W['uniacid']));
            plog('perm.user.edit', "编辑易活动商 ID: {$su_info['uid']} 用户名: {$data['username']} ");
        }else{   
            $result = pdo_fetch('select * from ' . tablename('users') . ' where username=\'' . $data['username'] . '\'');
            if (!empty($result)){       
                show_json(0,'此用户为系统存在用户，无法添加');
            }else{                  
                $data['uid'] = user_register(array('username' => $data['username'], 'password' => $_GPC['password']));
                $pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid' , array(':uid' => $data['uid']));
                pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $data['uniacid'], 'role' => 'operator'));
                $pwd['username']=$data['username'];
                $pwd['roleid']=$data['roleid'];         //活动商家表不需要这5个字段
                $pwd['perms']=$data['perms'];
                $pwd['uid']=$data['uid'];
                $pwd['uniacid']=$data['uniacid'];   
                unset($data['username']);       
                unset($data['roleid']);
                unset($data['perms']);
                pdo_insert('sz_yi_member_user',$data);       //活动商家表
                unset($data);
                $data=$pwd;
                $data['muserid']=pdo_insertid();                  //活动商家表ID
                $data['status']=1; 

                pdo_insert('sz_yi_perm_user', $data);
                $id = pdo_insertid();       
                plog('perm.user.add', "添加易活动商 ID: {$id} 用户名: {$data['username']} ");
            }                
        }                
        show_json(1,'添加易活动商成功!');                  
    }
}elseif ($operation == 'delete'){            
    // ca('dealmerch.dealmerch.delete');         
    $id = intval($_GPC['id']);
    $item = pdo_fetch('SELECT id,uid,username,openid FROM ' . tablename('sz_yi_perm_user') . " WHERE id = '$id' and uniacid='$Uuniacid'");  
    if (empty($item)){      
        message('抱歉，操作员不存在或是已经被删除！', $this -> createPluginWebUrl('bartact/merch'), 'error');
    }

    pdo_delete('sz_yi_perm_user', array('id' => $id, 'uniacid' => $_W['uniacid']));  
    pdo_delete('sz_yi_member_user', array('uid' => $item['uid'], 'uniacid' => $_W['uniacid'])); 
    pdo_delete('users', array('uid' => $item['uid']));                                        
    pdo_delete('sz_yi_af_supplier', array('openid' => $item['openid'], 'uniacid' => $_W['uniacid'],'member'=>'1'));
    plog('dealmerch.dealmerch.delete', "删除操作员 ID: {$id} 用户名: {$item['username']} "); 
    message('操作员删除成功！', $this -> createPluginWebUrl('bartact/merch'), 'success'); 
}else if($operation == 'gettype'){          

    $id=intval($_GPC['pid']);       
    $param=array(
        ':pid'=>$id, 
        ':uniacid'=>$Uuniacid
    );
    $mintype=pdo_fetchall('select id , title from '.tablename('sz_yi_merch_type').' where status=1 and pid=:pid and uniacid =:uniacid',$param);
    $mintype?show_json(1,$mintype):show_json(2,'改分类暂无行业小类!');
}else if ($operation == 'editpwd'){                 
    $uid=intval($_GPC['id']);       
    $info=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));           

    if($_W['isajax']){                         
        $data=$_GPC['data'];                         
        empty($_GPC['password']) && show_json(0,'密码不能为空');

        $_GPC['password'] != $_GPC['confirm'] && show_json(0,'两次密码不一致');
        $user=pdo_fetch('select * from '.tablename('users').' where uid = :uid',array(':uid'=>$uid));
        
        $cdata=array(               
            'password'=>$_GPC['password'],  
            'salt'=>$user['salt'],           
            'uid'=>$uid                                 
        );              

        $re=user_update($cdata);    
        
        if ($re) {  
            show_json(1,'修改成功!');    
        }
        show_json(0,'修改失败!');                           
    }
}
$param=[':uniacid'=>$_W['uniacid']];
$type=pdo_fetchall('select `id`,`title` from hs_sz_yi_merch_type where pid=0 and uniacid =:uniacid',$param);
load() -> func('tpl');       
include $this -> template('merch_add');     
