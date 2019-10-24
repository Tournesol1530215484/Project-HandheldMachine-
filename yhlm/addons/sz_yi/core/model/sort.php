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

class Sz_DYi_Sort{
    	


    //分销
    function calcday($openid=null,$bonus=null){
        global $_W;                      
        $time=m('time')->today();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_commission_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_commission_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'day'=>$bonus,
                'utime'=>time()
            );                     
            pdo_insert('sz_yi_commission_sort_log',$data);                  
        }

    }

    function calcweek($openid=null,$bonus=null){
        global $_W;                      
        $time=m('time')->week();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_commission_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_commission_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'week'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_commission_sort_log',$data);
        }

    }

    function calcmonth($openid=null,$bonus=null){
        global $_W;                      
        $time=m('time')->month();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_commission_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_commission_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'month'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_commission_sort_log',$data);
        }

    }


    function calcquarter($openid=null,$bonus=null){
        global $_W;                      
        $time=m('time')->thisQuarter();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_commission_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_commission_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'quarter'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_commission_sort_log',$data);
        }

    }


    function calctotal($openid=null,$bonus=null){
        global $_W;                      
        $exists=pdo_fetch('select * from '.tablename('sz_yi_commission_sort_log').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果没有不存在记录 （第一次计算）
                $data=array(
                    'total'=>$exists['total']+$bonus,
                    'utime'=>time()
                );
            pdo_update('sz_yi_commission_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'total'=>$bonus,
                'utime'=>time()
            );
            pdo_insert('sz_yi_commission_sort_log',$data);
        }

    }



    // -------------------------------------------------广告红包------------------------------------------------------//

    // type : 1 现金
    // type : 2 换货码          
  
    function calcdayad($openid=null,$bonus=null,$type=1){
        global $_W;                      
        $time=m('time')->today();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = '.$type.' and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_ad_bonus_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'day'=>$bonus,
                'type'=>$type,
                'utime'=>time()
            );                     
            pdo_insert('sz_yi_ad_bonus_sort_log',$data);                  
        }

    }

    function calcweekad($openid=null,$bonus=null,$type=1){
        global $_W;                      
        $time=m('time')->week();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = '.$type.' and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_ad_bonus_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'week'=>$bonus,
                'type'=>$type,
                'utime'=>time()
            );
            pdo_insert('sz_yi_ad_bonus_sort_log',$data);
        }

    }

    function calcmonthad($openid=null,$bonus=null,$type=1){
        global $_W;                      
        $time=m('time')->month();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = '.$type.' and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_ad_bonus_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'month'=>$bonus,
                'type'=>$type,
                'utime'=>time()
            );
            pdo_insert('sz_yi_ad_bonus_sort_log',$data);
        }

    }


    function calcquarterad($openid=null,$bonus=null,$type=1){
        global $_W;                      
        $time=m('time')->thisQuarter();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = '.$type.' and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
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
            pdo_update('sz_yi_ad_bonus_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'quarter'=>$bonus,
                'type'=>$type,
                'utime'=>time()
            );
            pdo_insert('sz_yi_ad_bonus_sort_log',$data);
        }

    }


    function calctotalad($openid=null,$bonus=null,$type=1){
        global $_W;                      
        $time=m('time')->thisQuarter();
        $exists=pdo_fetch('select * from '.tablename('sz_yi_ad_bonus_sort_log').' where uniacid = :uniacid and type = '.$type.' and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($exists) {      //如果没有不存在记录 （第一次计算）

                $data=array(
                    'total'=>$exists['total']+$bonus,
                    'utime'=>time()
                );
                                                      
            pdo_update('sz_yi_ad_bonus_sort_log',$data,array('id'=>$exists['id']));
        }else{
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid' =>$openid,
                'total'=>$bonus,
                'type'=>$type,
                'utime'=>time()
            );
            pdo_insert('sz_yi_ad_bonus_sort_log',$data);
        }

    }

}

