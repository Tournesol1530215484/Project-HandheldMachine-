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

class Sz_DYi_Fans{


    function test(){
        var_dump('bate');
        exit;
    }


    function calcFansDay($openid=null,$bonus=1){
        global $_W;                      
        $time=m('time')->today();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_fans_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果没有不存在记录 （第一次计算）
            if ($exists['utime'] >= $time[0] && $exists['utime'] <= $time[1]) {     //如果更新时间在今天则修改 否则替换提成字段
                $data=array(
                    'day'=>$exists['day']+$bonus,
                    'utime'=>time()
                );
            }else{          
                $data=array(
                    'day'=>$bonus,            
                    'utime'=>time()
                );                   
            }                                            
            pdo_update('sz_yi_fans_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'day'=>$bonus,
                'utime'=>time()
            );                     
            pdo_insert('sz_yi_fans_sort_log',$data);                  
        }

    }

    function calcFansWeek($openid=null,$bonus=1){
        global $_W;                      
        $time=m('time')->week();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_fans_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果没有不存在记录 （第一次计算）
            if ($exists['utime'] >= $time[0] && $exists['utime'] <= $time[1]) {     //如果更新时间在今天则修改 否则替换提成字段
                $data=array(
                    'week'=>$exists['week']+$bonus,
                    'utime'=>time()
                );
            }else{          
                $data=array(
                    'week'=>$bonus,
                    'utime'=>time()
                );       
            }                                            
            pdo_update('sz_yi_fans_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'week'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_fans_sort_log',$data);
        }

    }

    function calcFansMonth($openid=null,$bonus=1){
        global $_W;                      
        $time=m('time')->month();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_fans_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果没有不存在记录 （第一次计算）
            if ($exists['utime'] >= $time[0] && $exists['utime'] <= $time[1]) {     //如果更新时间在今天则修改 否则替换提成字段
                $data=array(
                    'month'=>$exists['month']+$bonus,
                    'utime'=>time()
                );
            }else{          
                $data=array(
                    'month'=>$bonus,
                    'utime'=>time()
                );       
            }                                            
            pdo_update('sz_yi_fans_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'month'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_fans_sort_log',$data);
        }

    }


    function calcFansQuarter($openid=null,$bonus=1){
        global $_W;                      
        $time=m('time')->thisQuarter();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_fans_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果没有不存在记录 （第一次计算）
            if ($exists['utime'] >= $time[0] && $exists['utime'] <= $time[1]) {     //如果更新时间在今天则修改 否则替换提成字段
                $data=array(
                    'quarter'=>$exists['quarter']+$bonus,
                    'utime'=>time()
                );
            }else{          
                $data=array(
                    'quarter'=>$bonus,
                    'utime'=>time()
                );       
            }                                            
            pdo_update('sz_yi_fans_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'quarter'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_fans_sort_log',$data);
        }

    }


    function calcFansTotal($openid=null,$bonus=1,$auto=false){
        global $_W;                      

        if ($auto) {
            $agent1=m('member')->getMember($openid);
            $agent2=m('member')->getMember($member['agentid']);
            $agent3=m('member')->getMember($agent2['agentid']);

            if ($agent1['agentid'] && $agent2) {        //上上级增加粉丝
                $this->calcFansDay($agent2['openid']);
                $this->calcFansWeek($agent2['openid']);
                $this->calcFansMonth($agent2['openid']);
                $this->calcFansQuarter($agent2['openid']);
                $this->calcFansTotal($agent2['openid']);
            }
                             
            if ($agent2['agentid'] && $agent3) {     //上上上级增加粉丝
                $this->calcFansDay($agent3['openid']);
                $this->calcFansWeek($agent3['openid']);
                $this->calcFansMonth($agent3['openid']);
                $this->calcFansQuarter($agent3['openid']);
                $this->calcFansTotal($agent3['openid']);
            }

        }               
        $exists=pdo_fetch('select * from '.tablename('sz_yi_fans_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果存在记录 （第一次计算）
                $data=array(
                    'total'=>$exists['total']+$bonus,
                    'utime'=>time()
                );
            pdo_update('sz_yi_fans_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'total'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_fans_sort_log',$data);
        }

    }

    function plusData(){
        global $_W;

        $time=array(0,time());

        $allMember=pdo_fetchall('select openid from '.tablename('sz_yi_member').' where uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid'])); 
        $num=0;
                 
        foreach ($allMember as $key => $value) {                                                                  
            $member_bonus = p('commission')->getmyInfo($value['openid'],$time[0],$time[1]);
            if (intval($member_bonus['agentcount'])) {
                $data=array(
                    'uniacid'=>$_W['uniacid'],
                    'openid'=>$value['openid'],
                    'total'=>intval($member_bonus['agentcount']),
                    'utime'=>time()
                );
                $num++;
                pdo_insert('sz_yi_fans_sort_log',$data);
            }
            
        }                   
        var_dump($num);
    }

}

