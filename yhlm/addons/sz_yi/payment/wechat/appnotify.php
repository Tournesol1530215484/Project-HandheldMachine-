<?php
//微信支付要返回的失败信息
$errorStr = "<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
//微信支付要返回的成功信息
$successStr = "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
define('IN_MOBILE', true);
$file_in = file_get_contents("php://input"); // 接收post数据
$xml = simplexml_load_string($file_in); // 转换post数据为simplexml对象
require '../../../../framework/bootstrap.inc.php';
require '../../../../addons/sz_yi/defines.php';
require '../../../../addons/sz_yi/core/inc/functions.php';
require '../../../../addons/sz_yi/core/inc/plugin/plugin_model.php';
//判断订单状态，实际操作中位更改订单状态

if ($xml->return_code == "SUCCESS") {
    $sql='select * from '.tablename('sz_yi_app_log').' where ordersn=:ordersn';
    $log=pdo_fetch($sql,array(':ordersn'=>$xml->out_trade_no));
        if(empty($log)){
            $sql="select * from ".tablename('sz_yi_order')." where  ordersn=:ordersn";
            $order=pdo_fetch($sql,array(':ordersn'=>$xml->out_trade_no));
            $arr=array();
            if(!empty($order)){
                $arr['uniacid']=$order['uniacid'];
            }
            $arr['ordersn']=$xml->out_trade_no;
            $arr['appid']=$xml->appid;
            $arr['mch_id']=$xml->mch_id;
            $arr['openid']=$xml->openid;
            $arr['total_fee']=$xml->total_fee*0.01;
            $arr['wechatsn']=$xml->transaction_id;
            $arr['paytime']=strtotime($xml->time_end);
            $arr['addtime']=time();            
            pdo_insert('sz_yi_app_log',$arr);
        }
       
        //返回订单成功信息
        echo $successStr;
        echo 'SUCCESS';
        return $successStr;
    
} else {

    //返回订单失败信息
    echo $errorStr;
    return $errorStr;
  
}