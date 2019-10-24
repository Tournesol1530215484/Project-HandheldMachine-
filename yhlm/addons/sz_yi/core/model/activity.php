<?php

/*=============================================================================

#     FileName: goods.php

#         Desc: ��Ʒ��

#       Author: Yunzhong - http://www.yunzshop.com

#        Email: 1084070868@qq.com

#     HomePage: http://www.yunzshop.com

#      Version: 0.0.1

#   LastChange: 2016-02-05 02:32:56

#      History:

================================================================`=============*/

if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Sz_DYi_Activity{
             
    function getSet(){          
        global $_W ;
        $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    
        $set = unserialize($setdata['sets']);
        return $set;
    }

    function getVisitingInfo($openid){
        global $_W;
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':openid'=>$openid
        );
        $info=pdo_fetch('select * from '.tablename('sz_yi_visiting_info').' where uniacid = :uniacid and openid = :openid ',$params);
        return $info;
    }


    function setVisitingInfo($data,$openid){
        global $_W;      

        $exists=$this->getVisitingInfo($openid);
        if ($exists) {
            $params=array(
                'openid'=>$openid,
                'uniacid'=>$_W['uniacid'],
            );          
            
            $re=pdo_update('sz_yi_visiting_info',$data,$params);
        }else{
            $data['uniacid']=$_W['uniacid'];
            $data['openid']=$openid;
            $re=pdo_insert('sz_yi_visiting_info',$data);
        }

        return $re?:false;

    }

    function getAccountInfo($openid){            
        global $_W;

        $accountinfo=pdo_fetch('select * from '.tablename('sz_yi_activity_account_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        return $accountinfo;
    }

    function getMuser($uid){
        global $_W;

        $params=array(
            ':uniacid'=>$_W['uniacid']
        );
        $sql='select * from '.tablename('sz_yi_member_user').' where uniacid  = :uniacid ';

        if (is_numeric($uid)) 
        {
            $params[':uid'] = $uid ; 
            $sql.=' and uid = :uid ';
        }else
        {
            $params[':openid'] = $uid ; 
            $sql.=' and openid = :openid ';
        }
        $info=pdo_fetch($sql,$params);
        return $info?:array();      
    }

    function getPayitem($id,$do=true){
        global $_W;      
        if ($do) {
            $py=pdo_fetch('select * from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and atid = :id and enabled  = 1 and ismobile = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        }else{
            $py=pdo_fetchall('select * from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and atid = :id and enabled  = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));        
        }                
        return $py;      
    }
    
    function reSort($data, $parent_id = 0) {     
        $return = array();//不能用static    
        foreach($data as $v) {                                       
            if($v['pId'] == $parent_id) {
                $relationship=array(           
                    'children_num'=>$this->countChild($data,$v['id']),
                    'parent_num'=>1,  
                    'sibling_num'=>0     
                );                                      
                $v['children']=array();                  
                $v['relationship']=$relationship;
                foreach($data as $subv) {               
                    if($subv['pId'] == $v['id']) {
                        $v['children']= $this->reSort($data, $v['id']);         
                        break;       
                    }                
                }                        
                $return[] = $v;                              
            }                                  
        }
        return $return;      
    }


    function setMuser($data,$params)
    {
        global $_W;
        $re=pdo_update('sz_yi_member_user',$data,$params);
        return $re?true:false;
    }

    function getinfo($uid){
        global $_W;

        $info=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));

        return $info;        
    }

    function getact($atid,$type=1){
        global $_W;         
        if ($type==1) {
            $info=pdo_fetch('select * from '.tablename('
                ').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }else if($type == 2){
            $info=pdo_fetch('select * from '.tablename('sz_yi_activity_article').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }else if($type == 4){
            $info=pdo_fetch('select * from '.tablename('sz_yi_match_picture').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }else if($type == 11){
            $info=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }                           
        return $info?:array();       
    }

    function countChild($arr=array(),$id){
        $num=0;
        foreach ($arr as $key => $value) {
            if ($value['pId'] == $id) {      
                $num+=1;         
            }
        }

        return $num;
    }

    function setBrowse($atid,$type=1){
        global $_W;
        $at=$this->getact($atid,$type);

        if (!$at) {
            return false;
        }

        if ($type==1) {

            $info=pdo_update('sz_yi_activity',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid'])); 

        }else if($type == 2){

            $info=pdo_update('sz_yi_activity_article',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid']));

        }else if($type == 3){
            
            $info=pdo_update('sz_yi_member_user',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid']));

        }else if($type == 4){
            
            $info=pdo_update('sz_yi_match_picture',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid']));

        }else if($type == 11){
            
            $info=pdo_update('sz_yi_match',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid']));

        }
                    
        return $info?true:false;


    }

    function getSignUp($actid,$openid,$condition=''){
        global $_W;

        $params=array(
            ':uniacid'=>$_W['uniacid'],         
            ':openid'=>$openid,         
            ':id'=>$actid                    
        );                                   
        $sql='select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and actid = :id ';
        $sql.=$condition;
        
        $re=pdo_fetch($sql,$params);

        $re=$re?:array();
        return $re;
    }

    function auditSuppleir($status,$id){
        global $_W;

        $openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
        if (empty($openid)) {       
            m('tools')->tip('没有该条申请记录');
        } else {                
            pdo_update('sz_yi_af_supplier', array('status' => $status), array('id' => $id, 'uniacid' => $_W['uniacid']));
            if ($status == 1) {
                $msg = '驳回申请成功';
            } else {
                $data = array();
                $msg = '审核通过成功';
                $af_user = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
                $data['uid'] = user_register(array('username' => $af_user['username'], 'password' => $af_user['password']));
                $pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $data['uid']));
                // $perm_role = pdo_fetch ('select id,status from ' . tablename('sz_yi_perm_role') . ' where status1=1 and status=1 and uniacid = '.$_W['uniacid']); 50 
                $data['password'] = $pwd['password'];
                $data['username'] = $af_user['username'];
                $data['company'] = $af_user['qq'];
                $data['roleid'] = 50;
                $data['status'] = 1; 
                $data['uniacid'] = $_W['uniacid'];
                $data['perms'] = '';
                $data['provance']=$af_user['province']; 
                $data['city']   =$af_user['city'];
                $data['area']=$af_user['district'];
                $data['openid'] = $openid; 
                
                pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $af_user['uniacid'], 'role' => 'operator'));
                $arr=array(
                    'uniacid'=>$af_user['uniacid'],
                    'uid'=>$data['uid'],
                    'openid'=>$af_user['openid'],
                    'mobile'=>$af_user['mobile'],
                    'realname'=>$af_user['realname'],
                    'orgName'   => $af_user['qq'],
                    'province'=> $af_user['province'],
                    'city'   => $af_user['city'],
                    'area'   => $af_user['district']   
                );  
                pdo_insert('sz_yi_member_user',$arr);
                $data['muserid']=pdo_insertid();     //易货商家 通过审核后的标记
                pdo_insert('sz_yi_perm_user', $data);
            }
                m('tools')->tip($msg);       
        }
    }

    function getAuthor($atid){
        global $_W;
        $act=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));
        $m=m('member')->getMember($act['openid']);
        return $m;
    }

    function trArrayKey($arr=array()){
        $temp=array();
        foreach ($arr as $key => $value) {
            $temp['m'.$key]=$value;
        }
        return $temp;
    }

    public function sendMsg($openid = '', $oldlevel = null, $level = null)
    {
        global $_W, $_GPC;
        $member     = m('member')->getMember($openid);
        $usernotice = unserialize($member['noticeset']);
        if (!is_array($usernotice)) {
            $usernotice = array();
        }
        $shop      = m('common')->getSysset('shop');
        $tm        = m('common')->getSysset('notice');
        $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
        if (strexists($detailurl, '/addons/sz_yi/')) {
            $detailurl = str_replace("/addons/sz_yi/", '/', $detailurl);
        }
        if (strexists($detailurl, '/core/mobile/order/')) {
            $detailurl = str_replace("/core/mobile/order/", '/', $detailurl);
        }
        if (!$level) {
            $level = m('member')->getLevel($openid);
        }
        $defaultlevelname = empty($shop['levelname']) ? '普通会员' : $shop['levelname'];
        $msg              = array(
            'first' => array(
                'value' => "亲爱的" . $member['nickname'] . ', 恭喜您成功升级！',
                "color" => "#4a5077"
            ),
            'keyword1' => array(
                'title' => '任务名称',
                'value' => '会员升级',
                "color" => "#4a5077"
            ),
            'keyword2' => array(
                'title' => '通知类型',
                'value' => '您会员等级从 ' . $defaultlevelname . ' 升级为 ' . $level['levelname'] . ', 特此通知!',
                "color" => "#4a5077"
            ),
            'remark' => array(
                'value' => "\r\n您即可享有" . $level['levelname'] . '的专属优惠及服务！',
                "color" => "#4a5077"
            )
        );
        if (!empty($tm['upgrade']) && empty($usernotice['upgrade'])) {
            m('message')->sendTplNotice($openid, $tm['upgrade'], $msg, $detailurl);
        } else if (empty($usernotice['upgrade'])) {
            m('message')->sendCustomNotice($openid, $msg, $detailurl);
        }
    }

    function borwseStatis($heMember,$openid){
        global $_W;                                            
        if ($heMember['openid']!=$openid) {
            $tre=$this->getBStatis($heMember['id']);
            if ($tre) {
                pdo_update('sz_yi_activity_browse_statis',array('statis'=>$tre['statis']+1),array('id'=>$tre['id'],'uniacid'=>$_W['uniacid']));                              

            }else{
                $browsestatis=array(
                    'uniacid'=>$_W['uniacid'],
                    'mid'=>$heMember['id'],
                    'date'=>date('Ymd'),         
                    'statis'=>1      
                );                       
                pdo_insert('sz_yi_activity_browse_statis',$browsestatis); 
            }
        }
    }

    function getBStatis($mid,$statis=false){
        global $_W;              
        $params=array(                      
            ':uniacid'=>$_W['uniacid'],
            ':mid'=>$mid,         
            ':date'=>date('Ymd')                 
        );
        $exists=pdo_fetch('select * from '.tablename('sz_yi_activity_browse_statis').' where uniacid = :uniacid and mid = :mid and date = :date',$params);
        if ($exists) {                                                      
            return $statis?$exists['statis']:$exists;        
        }                          
        return false;
    } 

    public function gettotal($openid,$set=null,$stime=null,$etime=null){
        
        global $_W;
        $member = p('commission')->getInfo($openid, array());
        $agentLevel = p('commission') ->getLevel($member['openid']);
        $level = intval($set['level']);
        $orders     = array();
        $level1     = $member['level1'];
        $level2     = $member['level2'];
        $level3     = $member['level3'];
        if ($level >= 1) {
            // $level1_memberids = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and agentid=:agentid', array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']), 'id');
            $level1_orders = pdo_fetchall('select commission1,o.id,o.createtime,o.price,og.commissions from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . " where o.uniacid=:uniacid and o.agentid=:agentid {$condition} and og.status1>=0 and o.status>0 and  og.nocommission=0 and o.createtime >= :stime and o.createtime <= :etime ", array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id'],':stime'=>$stime,':etime'=>$etime));
            foreach ($level1_orders as $o) {
                if (empty($o['id'])) {
                    continue;
                }                
                $commissions = iunserializer($o['commissions']);
                $commission = iunserializer($o['commission1']);
                
                if (empty($commissions)) {
                    $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
                } else {
                    $commission_ok = isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
                }
                foreach ($orders as &$or) {
                    if ($or['id'] == $o['id'] && $or['level'] == 1) {
                        $or['commission'] += $commission_ok;
                        break;
                    }
                }
                unset($or);
            
                $commissioncount += $commission_ok;
            }
            
        }
        if ($level >= 2) {
            if ($level1 > 0) {
                $level2_orders = pdo_fetchall('select commission2 ,o.id,o.createtime,o.price,og.commissions   from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . " where o.uniacid=:uniacid and o.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")  {$condition}  and og.status2>=0 and o.status>0 and og.nocommission=0 and o.createtime >= :stime and o.createtime <= :etime ", array(':uniacid' => $_W['uniacid'],':stime'=>$stime,':etime'=>$etime));
                foreach ($level2_orders as $o) {
                    if (empty($o['id'])) {
                        continue;
                    }
                    $commissions = iunserializer($o['commissions']);
                    $commission = iunserializer($o['commission2']);
                    if (empty($commissions)) {
                        $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
                    } else {
                        $commission_ok = isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
                    }
                    foreach ($orders as &$or) {
                        if ($or['id'] == $o['id'] && $or['level'] == 2) {
                            $or['commission'] += $commission_ok;
                            break;
                        }
                    }
                    unset($or);
                 
                    $commissioncount += $commission_ok;
                }
            }
        }
        
        if ($level >= 3) {
            
            if ($level2 > 0) {
                $level3_orders = pdo_fetchall('select commission3 ,o.id,o.createtime,o.price,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where o.uniacid=:uniacid and o.agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ")  {$condition} and og.status3>=0 and o.status>0 and og.nocommission=0 and o.createtime >= :stime and o.createtime <= :etime ", array(':uniacid' => $_W['uniacid'],':stime'=>$stime,':etime'=>$etime));
                
                foreach ($level3_orders as $o) {
                    if (empty($o['id'])) {
                        continue;
                    }
                    $commissions = iunserializer($o['commissions']);
                    $commission = iunserializer($o['commission3']);
                    if (empty($commissions)) {
                        $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
                    } else {
                        $commission_ok = isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
                    }
                    foreach ($orders as &$or) {
                        if ($or['id'] == $o['id'] && $or['level'] == 3) {
                            $or['commission'] += $commission_ok;
                            break;
                        }
                    }
                    unset($or);
                               
                    $commissioncount += $commission_ok;
                }
            }   
        }
        usort($orders, 'sortByCreateTime');
        $commissioncount = number_format($commissioncount, 2);
        return $commissioncount;
    
    }


    // 报名者openid 报名数据                                   
    public function calcTeamModelBonus($openid,&$data){
        global $_W;
        global $_GPC;
        $tact=m('activity')->getact($data['actid']);
        $member=m('member')->getMember($openid);            
        if ($member['agentid']) {
            $agent=m('member')->getMember($member['agentid']);
            $params=array(
                ':uniacid'=>$_W['uniacid'],
                ':id'=>$data['actid']                           
            );
            //如果报名之前该用户上级曾经转发过 上级与上上级将可以获得对应分红
            if ($agent) {
                $params[':openid']=$agent['openid'];        // 1级
                $fw1=pdo_fetch('select * from '.tablename('sz_yi_activity_share').' where uniacid = :uniacid and openid = :openid and type = 1 and actid = :id',$params);
                // $tb=pdo_fetch('select * from '.tablename('sz_yi_activity_team_bonus').' where uniacid = :uniacid and mid = :mid and openid = :openid and level = 1 ',array(':uniacid'=>$_W['uniacid'],':mid'=>$agent['id']));
                if (!empty($fw1)) {     
                    $bonus1=array(
                        'uniacid'=>$_W['uniacid'],      
                        'mid'=>$agent['id'],        
                        'openid'=>$openid,       
                        'money'=>floatval($data['money']) * intval($tact['agent1'] / 100),       
                        'level'=>1,      
                        'actid'=>$data['actid'],        
                        'ctime'=>time()
                    );

                    pdo_insert('sz_yi_activity_team_bonus',$bonus1);
                    $data['money']-=$bonus1['money'];
                    $data['agent1']=1;           
                }                       
            }        

            if ($agent['agentid']) {                    // 2级
                $agent2=m('member')->getMember($member['agentid']);
                if ($agent2) {              
                    $params[':openid']=$agent2['openid'];
                    $fw2=pdo_fetch('select * from '.tablename('sz_yi_activity_share').' where uniacid = :uniacid and openid = :openid and type = 1 and actid = :id ',$params);
                    // $tb=pdo_fetch('select * from '.tablename('sz_yi_activity_team_bonus').' where uniacid = :uniacid and mid = :mid and openid = :openid and level = 1 ',array(':uniacid'=>$_W['uniacid'],':mid'=>$agent['id']));
                    if (!empty($fw2)) {                 
                        $bonus2=array( 
                            'uniacid'=>$_W['uniacid'],      
                            'mid'=>$agent2['id'],       
                            'openid'=>$openid,       
                            'money'=>floatval($data['money']) * intval($tact['agent2'] / 100),       
                            'level'=>2,                      
                            'actid'=>$data['actid'],               
                            'ctime'=>time()  
                        );              

                        pdo_insert('sz_yi_activity_team_bonus',$bonus2);     
                        $data['money']-=$bonus2['money'];
                        $data['agent2']=1;
                    }       
                }        
            }                   
        }
    }


    public function auditActivity($lid,$ischeck =1,$note=''){
        global $_W;     

        $lid=intval($lid); 
        $ischeck=intval($ischeck);
            
        if ($lid>0){        
            $log=[
                'status'=>$ischeck,
                'note'  => trim($note),
                'audit_time'=>time()
            ];

            $data=array(
                'status'=>$ischeck
            );
                 
            $id=pdo_fetchcolumn('select actid from '.tablename('sz_yi_activity_log').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$lid));
            $log['actid']=$id;
            if ($ischeck == 1) { 
              
                $sure=pdo_update('sz_yi_activity',$data,array('id'=>$id)); 
                m('log')->putActLog($log);  //记录日志
                // $sure?message('审核成功!','','success'):message('审核失败!','','error');  
                // return 'success';           
            }else if ($ischeck == 2){        
                $sure=pdo_update('sz_yi_activity',$data,array('id'=>$id)); 
                m('log')->putActLog($log);  //记录日志
                // return 'fail';        
                // $sure?message('驳回成功!','','success'):message('驳回失败!','','error');
            }
                                         
        }

    }

    public function test(){
        var_dump('test');
        exit;
    }


}

