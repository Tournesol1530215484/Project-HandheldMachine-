<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20 0020
 * Time: 上午 10:28
 */

namespace App\Controller;

use Think\Controller;
class WxpayController extends  BaseController{
    public function _initialize()
    {
        parent::_initialize();
    }

    /*
   配置参数
   */

    //下单
    public function getPrePayOrder3(){
    $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
    $onoce_str = $this->createNoncestr();
    $wxpayconf = C('config');

    $notify_url = $wxpayconf["notify_url"];

    /**
     * ins_type:保险年限，1代表一年，2代表二年，3代表三年，4代表有效期6个月的用户直接购买三年
     * type：1代表电动车，10代表摩托车。
     */
    $ins_type =$this->_post['ins_type'];   //保险年限
    $type     =$this->_post['type'];       //资产类型
    $rfid     =$this->_post['rfid'];
    $rfid_area=$this->_post['rfid_area'];
    if($type==1){
        if($ins_type==1){
            //电瓶车一年20
            $bodys="电瓶车盗抢险一年";
            $commodity_info="电瓶车盗抢险一年";
            $ins_type="一年";
            $type    ="电瓶车";
            $total_fee=1;//单位为分
        }else if($ins_type==2) {
            //摩托车三年120
            $bodys = "电瓶车盗抢险两年";
            $commodity_info = "电瓶车盗抢险两年";
            $ins_type = "二年";
            $type = "电瓶车";
            $total_fee =1;
        }else if($ins_type==3){
            //电瓶车三年40
            $bodys="电瓶车盗抢险三年";
            $commodity_info="电瓶车盗抢险三年";
            $ins_type="三年";
            $type    ="电瓶车";
            $total_fee=1;
        }else if($ins_type==4){
            //有效期6个月的电瓶车三年120
            $bodys="电瓶车盗抢险三年";
            $commodity_info="电瓶车盗抢险三年";
            $ins_type="三年";
            $type    ="电瓶车";
            $total_fee=1;
        };
    }else if($type==10){
        if($ins_type==1){
            //摩托车一年60
            $ins_type="一年";
            $type    ="摩托车";
            $total_fee=1;
            $bodys="摩托车盗抢险一年";
            $commodity_info="摩托车盗抢险一年";
        }else if($ins_type==2) {
            //摩托车三年120
            $bodys = "摩托车盗抢险两年";
            $commodity_info = "摩托车盗抢险两年";
            $ins_type = "两年";
            $type = "摩托车";
            $total_fee =1;
        }
        else if($ins_type==3) {
            //摩托车三年120
            $bodys = "摩托车盗抢险三年";
            $commodity_info = "摩托车盗抢险三年";
            $ins_type = "三年";
            $type = "摩托车";
            $total_fee =1;
        }else if($ins_type==4){
            //摩托车两年140
            $bodys="摩托车盗抢险二年";
            $commodity_info="摩托车盗抢险二年";
            $ins_type="二年";
            $type    ="摩托车";
            $total_fee=1;
        };
    }

    $data["appid"] = $wxpayconf["appid"];   //appid
    $data["body"] = $bodys;                 //商品名称
    $data["detail"] = $commodity_info;      //商品详情
    $data["mch_id"] = $wxpayconf['mch_id']; // 商户id
    $data["nonce_str"] = $onoce_str;        //订单商号，随机生成
    $data["notify_url"] = $notify_url;      //回吊地址
    $data["out_trade_no"] = $onoce_str;     //商户订单号，同一商户下唯一
    //$data["spbill_create_ip"] = $this->get_client_ip();  //当前服务器的ip
    $data["total_fee"] =$total_fee;             //商品金额
    $data["trade_type"] ="APP";
    $sign = $this->getSign($data);
    $data["sign"] = $sign;

    $data_info["nonce_str"]=$onoce_str;                 //订单商号，随机生成
    $data_info["out_trade_no"]=$onoce_str;              //商户订单号，同一商户下唯一
    $data_info["ins_type"]=$ins_type;                   //保险年限
    $data_info["type"]=$type;                           //资产类型
    $data_info["rfid"]=$rfid;
    $data_info["rfid_area"]=$rfid_area;
    $data_info["gmt_create"]=date('Y-m-d H:i:s',time());
    $id=M("weipay_records")->data($data_info)->add();
    if(empty($id)){
        $this->_resp['code']   = '-999';
        $this->_resp['result'] = '订单创建失败';
        $this->output();
    }
    $xml = $this->arrayToXml($data);
    $response = $this->postXmlCurl($xml, $url);

    //将微信返回的结果xml转成数组
    $response = $this->xmlToArray($response);

    if($response === false){

        echo 'FALSE';
        exit;      // 如果解析的结果为false，终止程序
    }

    if ($response['return_code'] == 'FAIL') {
        echo $response->return_msg;            // 如果微信返回错误码为FAIL，则代表请求失败，返回失败信息；
    } else {
        //如果上一次请求成功，那么我们将返回的数据重新拼装，进行第二次签名
        $resignData = array(
            'appid'    =>    $response['appid'],
            'partnerid'    =>    $response['mch_id'],
            'prepayid'    =>    $response['prepay_id'],
            'noncestr'    =>    $response['nonce_str'],
            'timestamp'    =>    time(),
            'package'    =>    'Sign=WXPay'
        );
        //二次签名；
        $sign = $this->getSign($resignData);
        $this->log('二次签名sign:'.$sign);
    }
    $this->log("第一次验签结果".$response);
    $this->_resp['result'] =array(
        'appId'        =>    $response['appid'],
        'partnerId'    =>    $response['mch_id'],
        'prepayId'     =>    $response['prepay_id'],
        'nonceStr'     =>    $response['nonce_str'],
        'timeStamp'    =>    time(),
        'packageValue' =>    'Sign=WXPay',
        'sign'      => $sign
    );
    $this->log("第二次验签结果".$this->_resp['result']);
    $this->output();
}


    public function getPrePayOrder(){
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $onoce_str = $this->createNoncestr();
        $wxpayconf = C('config');

        $notify_url = $wxpayconf["notify_url"];

        /**
         * ins_type:保险年限，1代表一年，2代表二年，3代表三年，4代表有效期6个月的用户直接购买三年
         * type：1代表电动车，10代表摩托车。
         */
        $ins_type =$this->_post['ins_type'];   //保险年限
        $type     =$this->_post['type'];       //资产类型
        $rfid     =$this->_post['rfid'];
        $rfid_area=$this->_post['rfid_area'];
        $Arr_Qian=array(CITY_AREA_QXN01,CITY_AREA_QXN22,CITY_AREA_QXN23,CITY_AREA_QXN24,CITY_AREA_QXN25,CITY_AREA_QXN26,CITY_AREA_QXN27,CITY_AREA_QXN28,CITY_AREA_QXN29);

        if(in_array($rfid_area,$Arr_Qian)){ //黔西南地区
            if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_fee=60;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //摩托车二年100
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险二年";
                    $ins_type="二年";
                    $type    ="摩托车";
                    $total_fee=100;
                }else{
                    //摩托车12个月以下的体验用户
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=140;
                };
            }
        }else if($rfid_area=='442001'){   //中山市只支持摩托车
            if($type==10){              //摩托车
                if($ins_type==1){
                    //中山市摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_fee=60;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //中山市摩托车买二送一三年120
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=120;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                }else if($ins_type==3){
                    //中山市摩托车买二送一三年120
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=120;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                };
            }
        }else if($rfid_area=='441823'||$rfid_area=='441801'){    //清远市 441823（连山）、441801（清城区）
            if($type==1){
                if($ins_type==1){
                    //电瓶车一年30
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险一年";
                    $ins_type="一年";
                    $type    ="电瓶车";
                    $total_fee=30;
                }else if($ins_type==2){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=60;
                }else if($ins_type==3){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=60;
                }else{
                    //电瓶车12个月以下的体验用户三年120
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=120;
                };
            }else if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_fee=60;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=120;
                }else if($ins_type==3){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=120;
                }else{
                    //摩托车12个月以下的体验用户三年140
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=140;
                };
            }
        }else{      //云浮市云城区，肇庆市广宁县，咸宁市咸安区、开发区、通山县、赤壁市
            if($type==1){
                if($ins_type==1){
                    //电瓶车一年30
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险一年";
                    $ins_type="一年";
                    $type    ="电瓶车";
                    $total_fee=1;
                }
                else if($ins_type==2){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=1;
                } else if($ins_type==3){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=1;
                };
            }else if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_fee=1;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=1;
                }else if($ins_type==3){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_fee=1;
                };
            }
        }



//        if($type==1){
//            if($ins_type==1){
//                //电瓶车一年20
//                $bodys="电瓶车盗抢险一年";
//                $commodity_info="电瓶车盗抢险一年";
//                $ins_type="一年";
//                $type    ="电瓶车";
//                $total_fee=1;//单位为分
//            }else if($ins_type==3){
//                //电瓶车三年40
//                $bodys="电瓶车盗抢险三年";
//                $commodity_info="电瓶车盗抢险三年";
//                $ins_type="三年";
//                $type    ="电瓶车";
//                $total_fee=1;
//            }else if($ins_type==4){
//                //有效期6个月的电瓶车三年120
//                $bodys="电瓶车盗抢险三年";
//                $commodity_info="电瓶车盗抢险三年";
//                $ins_type="三年";
//                $type    ="电瓶车";
//                $total_fee=1;
//            };
//        }else if($type==10){
//            if($ins_type==1){
//                //摩托车一年60
//                $ins_type="一年";
//                $type    ="摩托车";
//                $total_fee=1;
//                $bodys="摩托车盗抢险一年";
//                $commodity_info="摩托车盗抢险一年";
//            }else if($ins_type==3) {
//                //摩托车三年120
//                $bodys = "摩托车盗抢险三年";
//                $commodity_info = "摩托车盗抢险三年";
//                $ins_type = "三年";
//                $type = "摩托车";
//                $total_fee =1;
//            }else if($ins_type==4){
//                //摩托车两年140
//                $bodys="摩托车盗抢险二年";
//                $commodity_info="摩托车盗抢险二年";
//                $ins_type="二年";
//                $type    ="摩托车";
//                $total_fee=1;
//            };
//        }

        $data["appid"] = $wxpayconf["appid"];   //appid
        $data["body"] = $bodys;                 //商品名称
        $data["detail"] = $commodity_info;      //商品详情
        $data["mch_id"] = $wxpayconf['mch_id']; // 商户id
        $data["nonce_str"] = $onoce_str;        //订单商号，随机生成
        $data["notify_url"] = $notify_url;      //回吊地址
        $data["out_trade_no"] = $onoce_str;     //商户订单号，同一商户下唯一
        //$data["spbill_create_ip"] = $this->get_client_ip();  //当前服务器的ip
        $data["total_fee"] =$total_fee;             //商品金额
        $data["trade_type"] ="APP";
        $sign = $this->getSign($data);
        $data["sign"] = $sign;

        $data_info["nonce_str"]=$onoce_str;                 //订单商号，随机生成
        $data_info["out_trade_no"]=$onoce_str;              //商户订单号，同一商户下唯一
        $data_info["ins_type"]=$ins_type;                   //保险年限
        $data_info["type"]=$type;                           //资产类型
        $data_info["rfid"]=$rfid;
        $data_info["rfid_area"]=$rfid_area;
        $data_info["gmt_create"]=date('Y-m-d H:i:s',time());
        $id=M("weipay_records")->data($data_info)->add();
        if(empty($id)){
            $this->_resp['code']   = '-999';
            $this->_resp['result'] = '订单创建失败';
            $this->output();
        }
        $xml = $this->arrayToXml($data);
        $response = $this->postXmlCurl($xml, $url);

        //将微信返回的结果xml转成数组
        $response = $this->xmlToArray($response);

        if($response === false){

            echo 'FALSE';
            exit;      // 如果解析的结果为false，终止程序
        }

        if ($response['return_code'] == 'FAIL') {
            echo $response->return_msg;            // 如果微信返回错误码为FAIL，则代表请求失败，返回失败信息；
        } else {
            //如果上一次请求成功，那么我们将返回的数据重新拼装，进行第二次签名
            $resignData = array(
                'appid'    =>    $response['appid'],
                'partnerid'    =>    $response['mch_id'],
                'prepayid'    =>    $response['prepay_id'],
                'noncestr'    =>    $response['nonce_str'],
                'timestamp'    =>    time(),
                'package'    =>    'Sign=WXPay'
            );
            //二次签名；
            $sign = $this->getSign($resignData);
            $this->log('二次签名sign:'.$sign);
        }
        $this->log("第一次验签结果".$response);
        $this->_resp['result'] =array(
            'appId'        =>    $response['appid'],
            'partnerId'    =>    $response['mch_id'],
            'prepayId'     =>    $response['prepay_id'],
            'nonceStr'     =>    $response['nonce_str'],
            'timeStamp'    =>    time(),
            'packageValue' =>    'Sign=WXPay',
            'sign'      => $sign
        );
        $this->log("第二次验签结果".$this->_resp['result']);
        $this->output();
    }


    public function getPrePayOrder2(){
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $onoce_str = $this->createNoncestr();
        $wxpayconf = C('config');

        $notify_url = $wxpayconf["notify_url"];

        /**
         * ins_type:保险年限，1代表一年，2代表二年，3代表三年，4代表有效期6个月的用户直接购买三年
         * type：1代表电动车，10代表摩托车。
         */
        $ins_type =$this->_post['ins_type'];   //保险年限
        $type     =$this->_post['type'];       //资产类型
        $rfid     =$this->_post['rfid'];
        $rfid_area=$this->_post['rfid_area'];
        $Arr_Qian=array(CITY_AREA_QXN01,CITY_AREA_QXN22,CITY_AREA_QXN23,CITY_AREA_QXN24,CITY_AREA_QXN25,CITY_AREA_QXN26,CITY_AREA_QXN27,CITY_AREA_QXN28,CITY_AREA_QXN29);
        if(in_array($rfid_area,$Arr_Qian)){
                if($type==10){
                    if($ins_type==1){
                        //摩托车一年60
                        $ins_type="一年";
                        $type    ="摩托车";
                        $total_fee=10;
                        $bodys="摩托车盗抢险一年";
                        $commodity_info="摩托车盗抢险一年";
                    }else if($ins_type==2) {
                        //摩托车三年120
                        $bodys = "摩托车盗抢险两年";
                        $commodity_info = "摩托车盗抢险两年";
                        $ins_type = "两年";
                        $type = "摩托车";
                        $total_fee =10;
                    }else if($ins_type==4){
                        //摩托车两年140
                        $bodys="摩托车盗抢险二年";
                        $commodity_info="摩托车盗抢险二年";
                        $ins_type="二年";
                        $type    ="摩托车";
                        $total_fee=10;
                    };
                }
        }else{
            if($type==1){
                if($ins_type==1){
                    //电瓶车一年20
                    $bodys="电瓶车盗抢险一年";
                    $commodity_info="电瓶车盗抢险一年";
                    $ins_type="一年";
                    $type    ="电瓶车";
                    $total_fee=1;//单位为分
                }else if($ins_type==3){
                    //电瓶车三年40
                    $bodys="电瓶车盗抢险三年";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=1;
                }else if($ins_type==4){
                    //有效期6个月的电瓶车三年120
                    $bodys="电瓶车盗抢险三年";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_fee=1;
                };
            }else if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_fee=1;
                    $bodys="摩托车盗抢险一年";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==3) {
                    //摩托车三年120
                    $bodys = "摩托车盗抢险三年";
                    $commodity_info = "摩托车盗抢险三年";
                    $ins_type = "三年";
                    $type = "摩托车";
                    $total_fee =1;
                }else if($ins_type==4){
                    //摩托车两年140
                    $bodys="摩托车盗抢险二年";
                    $commodity_info="摩托车盗抢险二年";
                    $ins_type="二年";
                    $type    ="摩托车";
                    $total_fee=1;
                };
            }
        }

        $data["appid"] = $wxpayconf["appid"];   //appid
        $data["body"] = $bodys;                 //商品名称
        $data["detail"] = $commodity_info;      //商品详情
        $data["mch_id"] = $wxpayconf['mch_id']; // 商户id
        $data["nonce_str"] = $onoce_str;        //订单商号，随机生成
        $data["notify_url"] = $notify_url;      //回吊地址
        $data["out_trade_no"] = $onoce_str;     //商户订单号，同一商户下唯一
        //$data["spbill_create_ip"] = $this->get_client_ip();  //当前服务器的ip
        $data["total_fee"] =$total_fee;             //商品金额
        $data["trade_type"] ="APP";
        $sign = $this->getSign($data);
        $data["sign"] = $sign;

        $data_info["nonce_str"]=$onoce_str;                 //订单商号，随机生成
        $data_info["out_trade_no"]=$onoce_str;              //商户订单号，同一商户下唯一
        $data_info["ins_type"]=$ins_type;                   //保险年限
        $data_info["type"]=$type;                           //资产类型
        $data_info["rfid"]=$rfid;
        $data_info["rfid_area"]=$rfid_area;
        $data_info["gmt_create"]=date('Y-m-d H:i:s',time());
        $id=M("weipay_records")->data($data_info)->add();
        if(empty($id)){
            $this->_resp['code']   = '-999';
            $this->_resp['result'] = '订单创建失败';
            $this->output();
        }
        $xml = $this->arrayToXml($data);
        $response = $this->postXmlCurl($xml, $url);

        //将微信返回的结果xml转成数组
        $response = $this->xmlToArray($response);

        if($response === false){

            echo 'FALSE';
            exit;      // 如果解析的结果为false，终止程序
        }

        if ($response['return_code'] == 'FAIL') {
            echo $response->return_msg;            // 如果微信返回错误码为FAIL，则代表请求失败，返回失败信息；
        } else {
            //如果上一次请求成功，那么我们将返回的数据重新拼装，进行第二次签名
            $resignData = array(
                'appid'    =>    $response['appid'],
                'partnerid'    =>    $response['mch_id'],
                'prepayid'    =>    $response['prepay_id'],
                'noncestr'    =>    $response['nonce_str'],
                'timestamp'    =>    time(),
                'package'    =>    'Sign=WXPay'
            );
            //二次签名；
            $sign = $this->getSign($resignData);
            $this->log('二次签名sign:'.$sign);
        }
        $this->log("第一次验签结果".$response);
        $this->_resp['result'] =array(
            'appId'        =>    $response['appid'],
            'partnerId'    =>    $response['mch_id'],
            'prepayId'     =>    $response['prepay_id'],
            'nonceStr'     =>    $response['nonce_str'],
            'timeStamp'    =>    time(),
            'packageValue' =>    'Sign=WXPay',
            'sign'      => $sign
        );
        $this->log("第二次验签结果".$this->_resp['result']);
        $this->output();
    }




    /*生成签名*/
    public function getSign($Obj){
        $wxpayconf = C('config');
        foreach ($Obj as $k => $v){
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$wxpayconf['api_key'];
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     *  作用：产生随机字符串，不长于32位
     */
    public function createNoncestr( $length = 32 ){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }


    //数组转xml
    public function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val){
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     *  作用：将xml转为array
     */
    public function xmlToArray($xml){
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }


    /**
     *  作用：以post方式提交xml到对应的接口url
     */
    public function postXmlCurl($xml,$url,$second=30){
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果

        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            curl_close($ch);
            return false;
        }
    }

    /*
   获取当前服务器的IP
   */
    public function get_client_ip(){
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    /**
     *  作用：格式化参数，签名过程需要使用
     */
    public function formatBizQueryParaMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v){
            if($urlencode){
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar='';
        if (strlen($buff) > 0){
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
}
