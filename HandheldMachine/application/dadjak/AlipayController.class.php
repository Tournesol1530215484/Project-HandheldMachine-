<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/6 0006
 * Time: 上午 10:58
 */

namespace App\Controller;

use Think\Controller;
class AlipayController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //支付宝
    public function alipay2(){
        $mobile = $_SESSION['appUser'];
        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }
        $this->checkNecessaryParams("ins_type,type,rfid,rfid_area");

        vendor("alipay.AopSdk");
        include('/data/www/anju/Web/alipay/aop/AopClient.php');
        include('/data/www/anju/Web/alipay/aop/request/AlipayTradeAppPayRequest.php');
        $alipayconf = C('ALIPAY_CONF');
        $c = new \AopClient();
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $alipayconf['app_id'];//测试号
        $c->rsaPrivateKey = $alipayconf['private_key'] ;
        $c->format = "json";
        $c->charset= "UTF-8";
        $c->signType= "RSA2";
        $c->alipayrsaPublicKey = $alipayconf['public_key'];
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.open.public.template.message.industry.modify
        $request = new \AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $out_trade_no=$this->generate_password();
        
        $ins_type =$this->_post['ins_type'];   //保险年限
        $type     =$this->_post['type'];       //资产类型
        $rfid     =$this->_post['rfid'];
        $rfid_area=$this->_post['rfid_area'];
        if($type==1){
            if($ins_type==1){
                //电瓶车一年20
                $bodys="电瓶车盗抢险";
                $commodity_info="电瓶车盗抢险一年";
                $ins_type="一年";
                $type    ="电瓶车";
                $total_amount=20.00;
            }else if($ins_type==2){
                //电瓶车二年35
                $bodys="电瓶车盗抢险";
                $commodity_info="电瓶车盗抢险二年";
                $ins_type="二年";
                $type    ="电瓶车";
                $total_amount=35.00;
            }elseif($ins_type==3){
                //电瓶车三年40
                $bodys="电瓶车盗抢险";
                $commodity_info="电瓶车盗抢险三年";
                $ins_type="三年";
                $type    ="电瓶车";
                $total_amount=40.00;
            }else{
                //电瓶车12个月以下的体验用户
                $bodys="电瓶车盗抢险";
                $commodity_info="电瓶车盗抢险三年";
                $ins_type="三年";
                $type    ="电瓶车";
                $total_amount=120.00;
            };
        }else if($type==10){
            if($ins_type==1){
                //摩托车一年60
                $ins_type="一年";
                $type    ="摩托车";
                $total_amount=0.01;
                $bodys="摩托车盗抢险";
                $commodity_info="摩托车盗抢险一年";
            }else if($ins_type==2){
                //摩托车二年70
                $bodys="摩托车盗抢险";
                $commodity_info="摩托车盗抢险二年";
                $ins_type="二年";
                $type    ="摩托车";
                $total_amount=70.00;
            }else if($ins_type==3){
                //摩托车三年120
                $bodys="摩托车盗抢险";
                $commodity_info="摩托车盗抢险三年";
                $ins_type="三年";
                $type    ="摩托车";
                $total_amount=120.00;
            }else{
                //摩托车12个月以下的体验用户
                $bodys="摩托车盗抢险";
                $commodity_info="摩托车盗抢险二年";
                $ins_type="二年";
                $type    ="摩托车";
                $total_amount=140.00;
            };
        }
        $bizcontent = "{\"body\":\"$bodys\","
            . "\"subject\":\"$commodity_info\","           //订单描述
            . "\"out_trade_no\":\"$out_trade_no\","        //商户唯一订单号,随机生成
            . "\"timeout_express\":\"30m\","               //订单支付时间
            . "\"total_amount\":\"$total_amount\","        //金额
            . "\"product_code\":\"QUICK_MSECURITY_PAY\","
            . "\"ins_type\":\"$ins_type\","                //保险种类，1：一年，2；两年；3：三年
            . "\"type\":\"$type\","                        //资产类型--摩托车：10，电瓶车：1（目前只支持摩托车和电瓶车）
            . "\"rfid\":\"$rfid\","
            . "\"rfid_area\":\"$rfid_area\""
            . "}";

        $request->setNotifyUrl("http://anju.zwtapp.win:81/anju/Web/index.php/App/Base/notify");
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $c->sdkExecute($request);
         $this->log("返回给客户端的签名".$response);
        $data['out_trade_no']=$out_trade_no;
         $data['ins_type']    =$ins_type;
         $data['type']        =$type;
         $data['rfid']        =$rfid;
         $data['rfid_area']   =$rfid_area;
         $data['total_amount']   =$total_amount;
         $data['app_id']   =$alipayconf['app_id'];
         $id=M("alipay_records")->data($data)->add();
        if(empty($id)){
            $this->_resp['code']   = '-999';
            $this->_resp['result'] = '订单创建失败';
            $this->output();
        }
        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        $this->_resp['result'] = $response;//就是orderString 可以直接给客户端请求，无需再做处理。
        $this->output();
    }

    //支付宝更新黔西南地区
    public function alipay(){
        $mobile = $_SESSION['appUser'];
        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }
        $this->checkNecessaryParams("ins_type,type,rfid,rfid_area");

        vendor("alipay.AopSdk");
        include('/data/www/anju/Web/alipay/aop/AopClient.php');
        include('/data/www/anju/Web/alipay/aop/request/AlipayTradeAppPayRequest.php');
        $Arr_Qian=array(CITY_AREA_QXN01,CITY_AREA_QXN22,CITY_AREA_QXN23,CITY_AREA_QXN24,CITY_AREA_QXN25,CITY_AREA_QXN26,CITY_AREA_QXN27,CITY_AREA_QXN28,CITY_AREA_QXN29);
        //$Whererfid=session('where');
	$rfid_area=$this->_post['rfid_area'];
        if(in_array($rfid_area,$Arr_Qian)){
            $alipayconf = C('ALIPAY_CONF_QIAN');
        }else{
            $alipayconf = C('ALIPAY_CONF');
        }
        $c = new \AopClient();
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $alipayconf['app_id'];//测试号
        $c->rsaPrivateKey = $alipayconf['private_key'] ;
        $c->format = "json";
        $c->charset= "UTF-8";
        $c->signType= "RSA2";
        $c->alipayrsaPublicKey = $alipayconf['public_key'];
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.open.public.template.message.industry.modify
        $request = new \AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $out_trade_no=$this->generate_password();

        $ins_type =$this->_post['ins_type'];   //保险年限
        $type     =$this->_post['type'];       //资产类型
        $rfid     =$this->_post['rfid'];
        $rfid_area=$this->_post['rfid_area'];

       // $Whererfid=session('where');
//        if(in_array($rfid_area,$Arr_Qian)){
//            if($type==10){
//                if($ins_type==1){
//                    //摩托车一年40
//                    $ins_type="一年";
//                    $type    ="摩托车";
//                    $total_amount=0.01;
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险一年";
//                }else if($ins_type==2){
//                    //摩托车二年70
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险二年";
//                    $ins_type="二年";
//                    $type    ="摩托车";
//                    $total_amount=100;
//                }else if($ins_type==3){
//                    //摩托车三年90
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="摩托车";
//                    $total_amount=0.01;
//                }else{
//                    //摩托车12个月以下的体验用户
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="摩托车";
//                    $total_amount=140.00;
//                };
//            }
//        }else{
//            if($type==1){
//                if($ins_type==1){
//                    //电瓶车一年20
//                    $bodys="电瓶车盗抢险";
//                    $commodity_info="电瓶车盗抢险一年";
//                    $ins_type="一年";
//                    $type    ="电瓶车";
//                    $total_amount=0.01;
//                }else if($ins_type==2){
//                    //电瓶车二年35
//                    $bodys="电瓶车盗抢险";
//                    $commodity_info="电瓶车盗抢险二年";
//                    $ins_type="二年";
//                    $type    ="电瓶车";
//                    $total_amount=35.00;
//                }elseif($ins_type==3){
//                    //电瓶车三年40
//                    $bodys="电瓶车盗抢险";
//                    $commodity_info="电瓶车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="电瓶车";
//                    $total_amount=40.00;
//                }else{
//                    //电瓶车12个月以下的体验用户
//                    $bodys="电瓶车盗抢险";
//                    $commodity_info="电瓶车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="电瓶车";
//                    $total_amount=120.00;
//                };
//            }else if($type==10){
//                if($ins_type==1){
//                    //摩托车一年60
//                    $ins_type="一年";
//                    $type    ="摩托车";
//                    $total_amount=0.01;
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险一年";
//                }else if($ins_type==2){
//                    //摩托车二年70
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险二年";
//                    $ins_type="二年";
//                    $type    ="摩托车";
//                    $total_amount=70.00;
//                }else if($ins_type==3){
//                    //摩托车三年120
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="摩托车";
//                    $total_amount=0.01;
//                }else{
//                    //摩托车12个月以下的体验用户
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险二年";
//                    $ins_type="二年";
//                    $type    ="摩托车";
//                    $total_amount=140.00;
//                };
//            }
//        }

        if(in_array($rfid_area,$Arr_Qian)){ //黔西南地区
            if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_amount=0.01;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //摩托车二年100
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险二年";
                    $ins_type="二年";
                    $type    ="摩托车";
                    $total_amount=0.01;
                }else{
                    //摩托车12个月以下的体验用户
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=0.01;
                };
            }
        }else if($rfid_area=='442001'){   //中山市只支持摩托车
            if($type==10){              //摩托车
                if($ins_type==1){
                    //中山市摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_amount=60;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //中山市摩托车买二送一三年120
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=120;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                }else if($ins_type==3){
                    //中山市摩托车买二送一三年120
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=120;
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
                    $total_amount=30;
                }else if($ins_type==2){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_amount=60;
                }else if($ins_type==3){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_amount=60;
                }else{
                    //电瓶车12个月以下的体验用户三年120
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_amount=120;
                };
            }else if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_amount=60;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=120;
                }else if($ins_type==3){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=120;
                }else{
                    //摩托车12个月以下的体验用户三年140
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=140;
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
                    $total_amount=30;
                }
                else if($ins_type==2){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_amount=60;
                } else if($ins_type==3){
                    //电瓶车三年60
                    $bodys="电瓶车盗抢险";
                    $commodity_info="电瓶车盗抢险三年";
                    $ins_type="三年";
                    $type    ="电瓶车";
                    $total_amount=60;
                };
//                else{
//                    //电瓶车12个月以下的体验用户三年140
//                    $bodys="电瓶车盗抢险";
//                    $commodity_info="电瓶车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="电瓶车";
//                    $total_amount=0.01;
//                };
            }else if($type==10){
                if($ins_type==1){
                    //摩托车一年60
                    $ins_type="一年";
                    $type    ="摩托车";
                    $total_amount=60;
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险一年";
                }else if($ins_type==2){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=120;
                }else if($ins_type==3){
                    //摩托车二年120
                    $bodys="摩托车盗抢险";
                    $commodity_info="摩托车盗抢险三年";
                    $ins_type="三年";
                    $type    ="摩托车";
                    $total_amount=120;
                };
//                else if($ins_type==3){
//                    //摩托车三年90
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="摩托车";
//                    $total_amount=0.01;
//                }else{
//                    //摩托车12个月以下的体验用户
//                    $bodys="摩托车盗抢险";
//                    $commodity_info="摩托车盗抢险三年";
//                    $ins_type="三年";
//                    $type    ="摩托车";
//                    $total_amount=0.01;
//                };
            }
        }

        $bizcontent = "{\"body\":\"$bodys\","
            . "\"subject\":\"$commodity_info\","           //订单描述
            . "\"out_trade_no\":\"$out_trade_no\","        //商户唯一订单号,随机生成
            . "\"timeout_express\":\"30m\","               //订单支付时间
            . "\"total_amount\":\"$total_amount\","        //金额
            . "\"product_code\":\"QUICK_MSECURITY_PAY\","
            . "\"ins_type\":\"$ins_type\","                //保险种类，1：一年，2；两年；3：三年
            . "\"type\":\"$type\","                        //资产类型--摩托车：10，电瓶车：1（目前只支持摩托车和电瓶车）
            . "\"rfid\":\"$rfid\","
            . "\"rfid_area\":\"$rfid_area\""
            . "}";
	 if(in_array($rfid_area,$Arr_Qian)){
            $request->setNotifyUrl("http://anju-test.zwtapp.win:81/anju/Web/index.php/App/Base/notify2");
         }else{
            $request->setNotifyUrl("http://anju-test.zwtapp.win:81/anju/Web/index.php/App/Base/notify");
  	  }
//        $request->setNotifyUrl("http://anju.zwtapp.win:81/anju/Web/index.php/App/Base/notify");
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $c->sdkExecute($request);
        $this->log("返回给客户端的签名".$response);
        $data['out_trade_no']=$out_trade_no;
        $data['ins_type']    =$ins_type;
        $data['type']        =$type;
        $data['rfid']        =$rfid;
        $data['rfid_area']   =$rfid_area;
        $data['total_amount']   =$total_amount;
        $data['app_id']   =$alipayconf['app_id'];
        $id=M("alipay_records")->data($data)->add();
        if(empty($id)){
            $this->_resp['code']   = '-999';
            $this->_resp['result'] = '订单创建失败';
            $this->output();
        }
        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        $this->_resp['result'] = $response;//就是orderString 可以直接给客户端请求，无需再做处理。
        $this->output();
    }








    public  function  notify(){
        include('/data/www/anju/Web/alipay/aop/AopClient.php');
        $alipayconf = C('ALIPAY_CONF');
        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = $alipayconf['public_key'];
        //此处验签方式必须与下单时的签名方式一致
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
        $this->log("支付宝返回的订单信息".$flag);
        //验签通过后再实现业务逻辑，比如修改订单表中的支付状态。
        /**
         *  ①验签通过后核实如下参数out_trade_no、total_amount、seller_id
         *  ②修改订单表
         **/
        //打印success，应答支付宝。必须保证本界面无错误。只打印了success，否则支付宝将重复请求回调地址。
        echo 'success';
    }

  //随机生成的商户订单号
  public  function generate_password( $length = 15 ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password ='';
        for ( $i = 0; $i < $length; $i++ )
  {
    $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
  }
  return  $password;
  }

}
