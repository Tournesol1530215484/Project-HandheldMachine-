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

class Sz_DYi_Activityba{

    //判断活动是否该商家
    function checkActivity($id){        //活动id
        global $_W;
        global $_GPC;

        $muser=m('activity')->getMuser($_W['uid']);
        $activity=m('activity')->getact($id );

        if ($muser['openid'] != $activity['openid']) {
            if ($_W['isajax']) {
                show_json(0,'找不到该活动!');
            }else{       
                message('找不到该活动','','error');
            }
        }           
    }


    //创建互动页面签到二维码
    public function createQrcode($url = '')
    {           
        global $_W,$_GPC;
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activityba/' . $_W['uniacid'];
        if (!is_dir($_var_100)) {   
            load()->func('file');                         
            mkdirs($_var_100);      
        }

        $_var_102 = 'activityba_' . md5($url) . '.png';                       
        $_var_103 = $_var_100 . '/' . $_var_102;                                           
        if (!is_file($_var_103)) {                    
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($url, $_var_103, QR_ECLEVEL_H, 4);           
        }           
        return $_W['siteroot'] . 'addons/sz_yi/data/activityba/' . $_W['uniacid'] . '/' . $_var_102;
    }


    public function getPrize($actid){
        global $_W;

        $sql='select * from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and actid = :actid and num > 0';
        $sql.=' order by displayorder asc ';                        
        $params=array(                          
            ':uniacid'=>$_W['uniacid'],
            ':actid'=>$actid,
        );

        $prize=pdo_fetch($sql,$params);

        return $prize?:false;
    }
    

}

