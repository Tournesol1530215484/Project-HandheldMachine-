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

=============================================================================*/

if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Sz_DYi_Log

{
    //易货码收支记录
    public function putBarterCodeLog($openid = 0, $uid = 0,$type,$status,$assoctype,$currency=0,$ordersn,$note,$debugtime=0)
    //asssoctype关联订单  note备注 currency金额
    {
        global $_W , $_GPC;

        if ($currency > 0){
            $currency='+'.$currency;
        }
        $data=array(
            'uniacid'=>$_W['uniacid'],
            'openid'=>$openid,
            'type'=>$type,
            'uid'=>$uid,
            'currency'=>$currency,
            'status'=>$status,
            'assoctype'=>$assoctype,
            'dealtime' =>time(),
            'note' =>$note,
            'debugtime' =>$debugtime,
            'dealsn'=>$ordersn
        );
        return pdo_insert('sz_yi_barter_code_log',$data);

    }


    //易货额度收支记录
    //1 购买易货额度 2下架解冻 3人工冻结 4上架冻结 5购买会员赠送 6首次注册商家成功赠送 7定向易货退回 8广告资源置换所得 9购买获取 10平台赠送
    public function putBarterCurrencyLog($openid = 0, $uid = 0,$type=0,$currency=0,$ordersn,$note)

    {
        global $_W , $_GPC;

        if ($currency > 0){
            $currency='+'.$currency;
        }

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'openid' =>$openid,
            'uid' =>$uid,
            'type' =>$type,
            'currency' =>$currency,
            'dealtime' =>time(),
            'dealsn' =>$ordersn,
            'note' =>$note
        );

        return pdo_insert('sz_yi_barter_currency_log',$data);
    }



    public function putAdLog($log=array()){      //修改广告
        
        global $_W , $_GPC; 

        $adid=$log['ad_id'];
        $exists=pdo_fetchcolumn('select id from '.tablename('sz_yi_ad_for_log').' where uniacid = :uniacid and status = 0 and ad_id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$adid));

        if ($exists) {  //如存在未审核日志 将覆盖该日志
            
            $id=pdo_update('sz_yi_ad_for_log',$log,array('id'=>$exists)); 
        }else{
            pdo_insert('sz_yi_ad_for_log',$log);
            $id=pdo_insertid();
        }
        
        return $id?true:false;
    }


    public function putActLog($log=array()){      //修改广告
        
        global $_W , $_GPC; 

        $adid=$log['actid'];
        $exists=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_log').' where uniacid = :uniacid and status = 0 and actid = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$adid));  //活动id

        if ($exists) {  //如存在未审核日志 将覆盖该日志
            
            $id=pdo_update('sz_yi_activity_log',$log,array('id'=>$exists)); 
        }else{
            pdo_insert('sz_yi_activity_log',$log);
            $id=pdo_insertid();
        }
        
        return $id?true:false;
    }



    public function putAdBonusLog($openid,$adid,$level,$money,$type,$obid)
    {
        global $_W , $_GPC;

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'adid' =>$adid,
            'obid' =>$obid,
            'openid' =>$openid,
            'bonusType' =>$type, //  1现金 2易货码
            'level' =>$level,   // 上级等级 0用户自己 1 1级 2 2级 3区代 4市代 5省代 6经纪人 7总部
            'money' =>$money,   //分红
            'ctime' =>time()    
        );

        pdo_insert('sz_yi_ad_bonus_log',$data);
        return pdo_insertid(); 
    }


    public function putVipLog($openid,$customer,$level,$vip,$money,$ordersn,$remark='')
    {
        global $_W , $_GPC;

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'openid' =>$openid,
            'customer' =>$customer,
            'level' =>$level,   // 上级等级 1 上级 2 员工 3区代 4市代 5省代
            'vip' =>$vip, //  所充值年会员等级
            'money' =>$money,   //分红
            'ordersn' =>$ordersn,   
            'remark' =>$remark,   
            'ctime' =>time()    
        );

        pdo_insert('sz_yi_year_vip_log',$data);
        return pdo_insertid();       
    }                

    
    public function putAdWatchLog($openid,$adid,$money,$type,$status)
    {
        global $_W;
        $Version=pdo_fetchcolumn('select version from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$adid));
        $data=array(
            'uniacid'=>$_W['uniacid'],
            'adid' =>$adid,
            'openid' =>$openid,
            'status' =>$status,      
            'date' =>date('Ymd'),                                                                                
            'bonustype' =>$type, //  1现金 2易货码
            'money' =>$money,   //分红金额
            'version' =>$Version,    
            'ctime' =>time()    
        );
        m('sort')->calcdayad($openid,$money,$type);
        m('sort')->calcweekad($openid,$money,$type);
        m('sort')->calcmonthad($openid,$money,$type);
        m('sort')->calcquarterad($openid,$money,$type);
        m('sort')->calctotalad($openid,$money,$type);
        pdo_insert('sz_yi_obtain_bonus',$data);
        return pdo_insertid(); 
    }

    public function putActivityBonusLog($openid,$consumers,$atid,$money,$cate,$type,$level,$logid=0,$paytype)
    {

        global $_W;                         

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'consumers' =>$consumers,   //消费者
            'openid' =>$openid,         //分给谁
            'level' =>$level,          //等级 1 1级 2 间接(2级) 3区代 4市代 5省代 6 总部 
            'type' =>$type,          //  1活动 2文章
            'money' =>$money,   //分红金额          
            'logid' =>$logid,   //外键 
            'ctime' =>time(),
            'atid' =>$atid, 
            'paytype'=>$paytype,            
            'cate' =>$cate    //  1会员卡分成(提成) 2打赏分成
        );


        m('sort')->calcdayad($openid,$money,$type);
        m('sort')->calcweekad($openid,$money,$type);
        m('sort')->calcmonthad($openid,$money,$type);
        m('sort')->calcquarterad($openid,$money,$type);
        m('sort')->calctotalad($openid,$money,$type);
        
        pdo_insert('sz_yi_activity_bonus_log',$data);
        return pdo_insertid(); 
    }

    public function putSettopBonusLog($openid,$money,$level,$logid=0,$ordersn)
    {

        global $_W;                         

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'openid' =>$openid,         //分给谁
            'level' =>$level,          //等级 1 1级 2 间接(2级) 3区代 4市代 5省代 6 总部 
            'money' =>$money,   //分红金额          
            'logid' =>$logid,   //置顶表日志id
            'ordersn' =>$ordersn,
            'ctime' =>time(),
        );
        
        pdo_insert('sz_yi_settop_bonus_log',$data);
        return pdo_insertid(); 
    }

    public function putServiceFeeLog($mid,$money,$level,$ordersn,$type=0,$logid=0,$remark='')
    {

        global $_W;                         

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'orderid'=>$logid,
            'mid'=>$mid,
            'level'=>$level,
            'money'=>$money,
            'ctime'=>time(),
            'type'=>$type,
            'ordersn'=>$ordersn,
            'remark'=>$remark,
        );
        
        pdo_insert('sz_yi_barter_services_fee',$data);
        return pdo_insertid(); 
    }

    public function putOldServiceFeeLog($mid,$money,$level,$ordersn,$type=0,$logid=0,$remark='')
    {

        global $_W;                         

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'orderid'=>$logid,
            'mid'=>$mid,
            'level'=>$level,
            'money'=>$money,
            'ctime'=>time(),
            'type'=>$type,
            'ordersn'=>$ordersn,
            'remark'=>$remark,
        );
        
        pdo_insert('sz_yi_barter_services_fee_bak',$data);
        return pdo_insertid(); 
    } 

}

