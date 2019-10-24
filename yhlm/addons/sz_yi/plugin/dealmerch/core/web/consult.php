<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;


$op=empty($_GPC['op'])?'display':$_GPC['op'];

// if ($op =='show') {
//     $arr1=pdo_fetchall('select uid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and dealmerchid > 0',array(':uniacid'=>$_W['uniacid']));
//     $arr2=pdo_fetchall('select uid from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
//     $arra1=[];  
//     $arra2=[];  
//     foreach ($arr1 as $key => $value) {
//             $arra1[]=$value['uid'];
//     }
//     foreach ($arr2 as $key => $value) {
//             $arra2[]=$value['uid'];
//     } 
  
//     $temp=array_diff($arra2,$arra1);     
    // foreach ($temp as $key => $value) { 
    //         $sure=pdo_delete('sz_yi_dealmerch_user',array('uid'=>$value));
    //             echo $sure? $value.'删除成功!<br/>':$value.'删除失败<br/>';
    // }  
//     var_dump($temp);
//     exit;   
// }  

if ($op == 'display') {  
     
    $pindex = max(1, intval($_GPC['page'])); 
    $psize = 20; 
       
    $sql = 'select bc.merch_uid,bc.time,pu.username,pu.mobile as pmobile from ' . tablename('sz_yi_barter_consult').' bc left join '.tablename('sz_yi_perm_user').' pu on pu.uid=bc.merch_uid where bc.uniacid = :uniacid group by bc.merch_uid order by bc.time desc'; 

    //$sql = 'select bc.merch_uid,bc.time,pu.username,pu.mobile as pmobile from ' . tablename('sz_yi_liuyan').' bc left join '.tablename('sz_yi_perm_user').' pu on pu.uid=bc.merch_uid where bc.uniacid = :uniacid group by bc.merch_uid order by bc.time desc'; 

    //修改留言信息

    $sqls= 'select user.username,user.mobile as pmobile ,liu.superior_id,liu.lower_id from hs_sz_yi_liuyan liu left join hs_sz_yi_perm_user user on liu.superior_id=user.uid  where liu.weid=8 group by liu.superior_id order by time desc';
    $sqls.=' limit ' . ($pindex - 1) * $psize . ',' . $psize; 
    $lists=pdo_fetchall($sqls);
    
    foreach ($lists as $key => $value) {
        $tempinfo=pdo_fetchall('select user.username,liu.lower_id,liu.content from hs_sz_yi_liuyan liu left join hs_sz_yi_perm_user user on liu.lower_id=user.uid order by time desc ');
        $lists[$key]['content']=$tempinfo['content'];
        $lists[$key]['realname']=$tempinfo['realname'];
        $lists[$key]['nickname']=$tempinfo['nickname']; 
        $lists[$key]['mobile']=$tempinfo['mobile'];
    }



    $sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize; 
    $list = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'])); 

    //$lists = pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'])); 


    foreach ($list as $key => $value) { 
        $tempinfo=pdo_fetch('select bc.id , bc.openid ,bc.content,m.mobile,m.realname,m.nickname from ' .tablename('sz_yi_barter_consult').' bc left join '.tablename('sz_yi_member').' m on m.openid = bc.openid where bc.type = 2 and bc.uniacid = :uniacid and bc.merch_uid = :merch_uid order by bc.time desc',array(':uniacid'=>$_W['uniacid'],':merch_uid'=>$value['merch_uid'])); 
        $list[$key]['content']=$tempinfo['content'];
        $list[$key]['realname']=$tempinfo['realname'];
        $list[$key]['nickname']=$tempinfo['nickname']; 
        $list[$key]['mobile']=$tempinfo['mobile'];
    }

    $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_barter_consult').' bc left join '.tablename('sz_yi_perm_user').' pu on pu.uid = bc.merch_uid where bc.uniacid = :uniacid group by bc.merch_uid order by bc.time desc',array(':uniacid'=>$_W['uniacid']));  
    $pager = pagination($total, $pindex, $psize);

 
} else if ($op == 'show') {
    $uid= $_GPC['merch_uid'];
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20; 
    $condition = ' and bc.pid = 0  and bc.uniacid = :uniacid ';
    $params = array(':uniacid' => $_W['uniacid']); 
    if (!empty($_GPC['status'])){ 
        $condition .= ' and bc.status =:status '; 
        $params[':status']=$_GPC['status'];
    }
    if (p('supplier')){         //如果是供应商
        $condition.=' and bc.merch_uid = :uid ';
        $params[':uid'] = $uid;
    }
     
    $sql = 'select bc.id,bc.content,bc.time,bc.openid,bc.merch_uid,m.mobile,m.realname,m.nickname from ' . tablename('sz_yi_barter_consult') . ' bc  left join ' . tablename('sz_yi_member') . ' m on bc.openid = m.openid ' ." where 1 {$condition} "; 
    $sql .= ' ORDER BY bc.id ASC '; 

    if (empty($_GPC['export'])){
        $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    } 
    $list = pdo_fetchall($sql, $params);  

    foreach ($list as $key => $value) { 
        $list[$key]['content']=pdo_fetchcolumn('select content from '.tablename('sz_yi_barter_consult').' where uniacid  = :uniacid and openid = :openid and merch_uid = :merch_uid and type = 2 order by id desc',array(':uniacid'=>$_W['uniacid'],':openid'=>$value['openid'],':merch_uid'=>$value['merch_uid']));

        $arr=pdo_fetchall('select mobile,qq from '.tablename('sz_yi_perm_user'). ' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$list[$key]['openid']) );
        foreach ($arr as $k => $v) { 
            if (!empty($v['mobile'])) {
                $list[$key]['pmobile']=$v['mobile'];
            }
            if (!empty($v['qq'])) {
               $list[$key]['qq']=$v['qq'];     
            }
        }
    }
     
    $totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_barter_consult') . ' bc  left join ' . tablename('sz_yi_member') . ' m on bc.openid = m.openid ' ." where 1 {$condition} ", $params);

    $pager = pagination($total, $pindex, $psize);       
}
        //消息
if ($_W['isajax']) {
    $openid=$_GPC['openid'];
    $merch_uid=$_GPC['merch_uid']; 

    if ($op == 'message') {
        // $pindex = max(1, intval($_GPC['page']));
        $psize = 5; 
        
        $condition = ' and uniacid = :uniacid and openid=:openid and merch_uid= :merch_uid ';
        $params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid,':merch_uid'=>$merch_uid);
        $sql = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_barter_consult') . " where 1 {$condition}";
        $total = pdo_fetchcolumn($sql, $params);
        
        $pindex =empty($_GPC['page']) ? ceil($total/$psize) : intval($_GPC['page']);  //最大页数
        $list = array(); 
        if (!empty($total)) {
            $sql = 'SELECT id,content,`time`,`type` FROM ' . tablename('sz_yi_barter_consult') . ' where 1 ' . $condition . ' ORDER BY `id` ASC LIMIT ' . ($pindex -1)* $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);   
            foreach ($list as $key => $value) {
                $list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['time']);
            } 
        }  
        show_json(1, array('page'=>$pindex-1,'total' => $total, 'list' => $list, 'pagesize' => $psize));

    }else if ($op == 'send'){
        
        $exists=pdo_fetchcolumn('select max(id) from '.tablename('sz_yi_barter_consult').' where merch_uid = :merch_uid and openid = :openid and uniacid = :uniacid',array(':merch_uid'=>$merch_uid,':openid'=>$openid,':uniacid'=>$_W['uniacid']));
        
        $data = array(  
                'pid'=>0,
                'uniacid'=>$_W['uniacid'],
                'openid'=>$openid,
                'merch_uid'=>$merch_uid,
                'content'=>trim($_GPC['content']),
                'type'=>1,
                'time'=>time() 
            ); 

        $exists && $data['pid']=$exists;  
        pdo_insert('sz_yi_barter_consult',$data);
        $id=pdo_insertid();  
        $id?show_json(1,$id):show_json(0,'消息提交失败!'); 
    }
}

load() -> func('tpl'); 


//print_r($lists);
//print_r("this is test");

include $this -> template('consult');

exit;
