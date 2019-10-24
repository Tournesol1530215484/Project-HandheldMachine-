<?php
 global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
$ac=$_GPC['ac'];     

if ($op == 'list'){            
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;         
    $roleid =   50;
    $condition = ' and uniacid = :uniacid and level > 0 ';                   
    $params=array(
        ':uniacid'=>$_W['uniacid']            
    );
    if ($_GPC['uid']) {                     
        $condition.=' and uid = :uid ';      
        $params['uid']=$_GPC['uid'];             
    }            

    $sql='select openid,level,uid,realname from '.tablename('sz_yi_member_user').' where 1 ';                                            
    $sql.=$condition;               
    $sql.=' group by openid ';                                                                                             
    $sql .= ' order by id desc limit ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql,$params);
    $tsql='select ctime,etime,paytype from '.tablename('sz_yi_activity_recharge_log').' where uniacid = :uniacid and openid = :openid order by id desc limit 0 ,1 ';
    foreach ($list as $key => $value) {  
        $tmember=m('member')->getMember($value['openid']);
        $tlog=pdo_fetch($tsql,array(':uniacid'=>$_W['uniacid'],':openid'=>$value['openid'])); 
        $list[$key]['ctime']=$tlog['ctime'];
        $list[$key]['etime']=$tlog['etime'];         
        $list[$key]['paytype']=$tlog['paytype'];
        if ($tmember['agentid']) {       
            $agent=m('member')->getMember($tmember['agentid']);
            $list[$key]['agent']=$agent;           
            if ($agent['agentid']) {         
                $list[$key]['agent2']=m('member')->getMember($agent['agentid']);
            }                   
        }                    
    }                       
    $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member_user').' where 1 '.$condition,$params);
    $pager = pagination($total, $pindex, $psize);                                                                                                            

}else if ($op == 'recharge'){
    
    if ($_W['isajax']) {                                    
        if ($ac == 'getlist') {     
            $logid=intval($_GPC['logid']);                      

            $sql='select bl.money,bl.ctime,bl.level,m.realname,m.nickname from '.tablename('sz_yi_activity_bonus_log').' bl left join '.tablename('sz_yi_member').' m on m.openid = bl.openid where bl.uniacid = :uniacid and bl.cate = 1 and bl.type = 0 and bl.logid = :logid ';              
            $params=array(      
                ':uniacid'=>$_W['uniacid'],      
                ':logid'=>$logid,
            );    
            $list=pdo_fetchall($sql,$params);        
            
            $rule=array(
                '自己',        
                '一级',
                '二级',
                '区代',        
                '市代',
                '省代',
                '总部'
            );

            if ($list) {
                foreach ($list as $key => $value) {  
                    $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
                    $list[$key]['rule']=$rule[intval($value['level'])];
                    
                }
                show_json(1,$list);
            }        
            show_json(0,array());
        }
    }                    
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;         
    $roleid =   50;
    $condition = ' ';         

    $condition .= ' and pu.uniacid = ' . $_W['uniacid'];
    if ($_GPC['uid']) {             
        $condition .= ' and mu.uid = ' . $_GPC['uid'];              
    }                                                    
    $sql = 'select pu.*, mu.realname,mu.mobile,mu.uid,p.username,m.province,m.city,m.area,m.agentid from ' . tablename('sz_yi_activity_recharge_log') . ' pu left join '.tablename('sz_yi_member_user').' mu on mu.openid = pu.openid left join '.tablename('sz_yi_perm_user').' p on p.uid = mu.uid left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where 1 '.$condition ;                                                                                                                                                                                                                                     
    $sql .= ' order by pu.id desc limit ' . ($pindex - 1) * $psize . ',' . $psize;                                                    
    $list = pdo_fetchall($sql);
    foreach ($list as $key => $value) {
        if (!empty($value['agentid'])) {
            $tagent=m('member')->getMember($value['agentid']);
            $list[$key]['agent']=$tagent;       
            if ($tagent['agentid']) {               
                $list[$key]['agent2']=m('member')->getMember($tagent['agentid']);
            }
        }
    }                        
    $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_recharge_log').' pu left join '.tablename('sz_yi_member_user').' mu on mu.openid = pu.openid where 1  ' . $condition);                      
    $pager = pagination($total, $pindex, $psize);                                                                              

}else if ($op == 'reward'){


    if ($_W['isajax']) {                                    
        if ($ac == 'getlist') {     
            $logid=intval($_GPC['logid']);                      

            $sql='select bl.money,bl.ctime,bl.level,m.realname,m.nickname from '.tablename('sz_yi_activity_bonus_log').' bl left join '.tablename('sz_yi_member').' m on m.openid = bl.openid where bl.uniacid = :uniacid and bl.cate = 2 and bl.logid = :logid ';                                      
            $params=array(              
                ':uniacid'=>$_W['uniacid'],                                    
                ':logid'=>$logid,           
            );    
            $list=pdo_fetchall($sql,$params);     //获取所有的代理商信息        
            
            $rule=array(
                '商家',                
                '一级',
                '二级',
                '区代',        
                '市代',
                '省代',
                '总部'
            );

            if ($list) {
                foreach ($list as $key => $value) {  
                    $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
                    $list[$key]['rule']=$rule[intval($value['level'])];
                    
                }
                show_json(1,$list);
            }        
            show_json(0,array());
        }
    }

    $pindex = max(1, intval($_GPC['page']));     
    $psize = 20;         
    $roleid =   50;
    $condition = ' ';         

    $condition .= ' and ar.uniacid = ' . $_W['uniacid'];
                 
    $sql='select ar.*,m.nickname,m.realname from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where 1 ';
    $sql.=$condition;                                        

    $sql .= ' order by ar.id desc limit ' . ($pindex - 1) * $psize . ',' . $psize;                                                       
    $list = pdo_fetchall($sql);                                      

    foreach ($list as $key => $value) {                        
        if ($value['type'] <= 2) {   
            $list[$key]['info']=m('activity')->getact($value['atid'],$value['type']);
        }else{                                             
            $list[$key]['info']=m('member')->getMember($value['atid']);
        }                    
    }                                                              
                                                                                               
    $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid  where 1 ' . $condition);                               
    $pager = pagination($total, $pindex, $psize);
}else if ($op == 'business'){   //新添加

    $pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and s.uniacid=:uniacid and s.member = 1 and pu.muserid > 0 ';
$params = array(':uniacid' => $_W['uniacid']);
if (!empty($_GPC['mid'])){
    $condition .= ' and s.id=:mid';
    $params[':mid'] = intval($_GPC['mid']);
}
if (!empty($_GPC['realname'])){
    $_GPC['realname'] = trim($_GPC['realname']); 
    $condition .= ' and s.realname like :realname';
    $params[':realname'] = "%{$_GPC['realname']}%";
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
if ($_GPC['export1'] == '1'){
    plog('member.member.export', '导出会员数据');
    m('excel') -> export($list, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '产品名称', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12))));
}
 
$total =pdo_fetchcolumn('select COUNT(*) from ' . tablename('sz_yi_af_supplier') . " s left join ".tablename('sz_yi_perm_user')." pu on pu.openid = s.openid where 1 {$condition}",$params);
$pager = pagination($total, $pindex, $psize); 



    
}else if ($op == 'detail'){
    $uid = intval($_GPC['uid']);
    $supplierinfo=m('activity')->getMuser($uid);
    $member=m('member')->getMember($supplierinfo['openid']);         		 		 
    if(checksubmit('submit')){
        $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
        unset($data['openid']); //不可修改	 	
        $data['province'] = $_GPC['reside']['province'];
        $data['city']     = $_GPC['reside']['city']; 	 
        $data['area']     = $_GPC['reside']['district'];	  	
        pdo_update('sz_yi_member_user', $data, array('uid' => $uid));		 	  
        message('保存成功!', $this -> createPluginWebUrl('bartact/merch'), 'success');
    }
}
load() -> func('tpl');                            
include $this -> template('member');     
