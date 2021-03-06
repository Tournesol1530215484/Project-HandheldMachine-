<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>消息中心</title>
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/style1/barter/css/iconfontzy.css">
<style type="text/css">
    html{
        font-size: 10px;
    }
    #big_body{width:100%;margin:0px; float: left;}
  	.customer_top {height: 44px; width: 100%; background: #f00605;  border-bottom: 1px solid #ccc;}
  	.back{
        height: 100%;
        line-height: 44px;
        width: 44px;
        margin-left: 10px;
        float: left;
        font-size: 16px;
        color: #fff;
        position: relative;
    }
    /*返回大于号按钮样式*/
    .back:after {
      content: "";
      display: block;
      clear: both;
      width: 10px;
      height: 10px;
      border-left: 2px solid rgb(255,255,255);
      border-bottom: 2px solid rgb(255,255,255);
      position: absolute;
      left: 10%;
      top: 50%;
      margin-top: -2px;
      -moz-transform: rotate(45deg) translateY(-50%);
      -ms-transform: rotate(45deg) translateY(-50%);
      -o-transform: rotate(45deg) translateY(-50%);
      -webkit-transform: rotate(45deg) translateY(-50%);
      transform: rotate(45deg) translateY(-50%);
    }
  	.customer_top .title1{height: 100%;line-height: 44px;display: inline-block;width: calc(100% - 60px);text-align: center;color:#fff;font-size: 1.6rem;}
  	.back{width: 30px; height: 100%;font-size: 22px;border-radius: 50%;float: left;line-height: 44px; font-family:serif,"PingFang SC", Helvetica, "Helvetica Neue", "微软雅黑", Tahoma, Arial, sans-serif;font-weight: bold;}
    #list-type-box{width:100%; background: #fff;}
    #list-type-box .list-type{float: left; width: 100%; background: #fff;border-bottom: 2px solid #dddede;;}
    #list-type-box .list-type li{float: left; width: 33.33%; text-align: center; color: #787878;height: 35px;}
    #list-type-box .list-type li>span{display: block;width: 80px;height: 100%;line-height: 35px;margin: 0 auto;}
    #list-type-box .list-type li.action>span{color: #FF3E3E;border-bottom: 2px solid #FF3E3E;}
    .tab_con{
        height: auto;
        width: 100%;
        padding: 10px 0;
        overflow: hidden;
    }
    .tab_con .con{
        height: auto;
        display: none;
        color: #333;
        word-break: break-all;
        margin-bottom: 80px; 
    }
    .detailed-msg-type{
        height: auto;
        width: 100%;
        background: #fff;
        border-bottom: 1px solid #ddd;
    }
    .list1 {
        height: 50px;
        width: 94%;
        background: #fff;
        margin: 0px 3%;
        border-bottom: 1px solid #eee;
        line-height: 50px;
        color: #666;
    }
    .detailed-msg-type .go-link{
        display: block;
        color: #969696;
        text-decoration: none;
    }
    .detailed-msg-type .list1 i {
        font-size: 20px;
        margin-right: 10px;
        line-height:50px;
    }
    .detailed-msg-type .list1 .red-color{
        color: #FF3E3E;
    }
 }
</style>

<div id="big_body">
    <div class="customer_top">
  		<div class="back" onclick='history.back()'></div>
  		<div class="title1">消息中心</div>
	</div>
    <div id="list-type-box">
        <ul class="list-type">
            <li id="type-tag1" class="type-tag action" onclick="tab(1)">
				<span>用户消息</span>
			</li>
            <li id="type-tag2" class="type-tag" onclick="tab(2)">
                <span>商家消息</span>
            </li>
            <li id="type-tag3" class="type-tag" onclick="tab(3)">
				<span>账户消息</span>
			</li>
        </ul>
    </div>
    <div class="tab_con">
        <div class="con" id="con_1" style='display:block'>
            <ul class="detailed-msg-type"> 
                <!--客服消息-->
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'consult'))?>" class="go-link"><i class="zy icon-liaotianduihua-xianxing red-color"></i>客服消息<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'goods'))?>" class="go-link"><i class="zy icon-commodity red-color"></i>商品管理消息<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>
            </ul>
        </div>
        <div class="con" id="con_2">
            <ul class="detailed-msg-type"> 
                <!--商品管理消息-->
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'goods'))?>" class="go-link"><i class="zy icon-commodity red-color"></i>商品管理消息<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>
                <!--好友消息-->
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'friend'))?>" class="go-link"><i class="zy icon-interactive red-color"></i>好友消息<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>     
                <!--邮寄订单-->
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'post'))?>" class="go-link"><i class="zy icon-jijianfasong-xianxing red-color"></i>邮寄订单<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>  
                <!--现场订单-->
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'local'))?>" class="go-link"><i class="zy icon-ditu red-color"></i>现场订单<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>  
            </ul>
        </div>
        <div class="con" id="con_3" >
            <ul class="detailed-msg-type"> 
                <!--客服消息-->
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'consult'))?>" class="go-link"><i class="zy icon-liaotianduihua-xianxing red-color"></i>客服消息<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>
                <li class="list1"><a href="<?php  echo $this->createMobileUrl('barter/message',arraY('op'=>'goods'))?>" class="go-link"><i class="zy icon-commodity red-color"></i>商品管理消息<i class="fa fa-angle-right" style="font-size:26px; float: right;"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    function tab(n){
        $('#con_'+n).fadeIn();
        $('#con_'+n).siblings().hide();
        $('#type-tag'+n).addClass('action');
        $('#type-tag'+n).siblings().removeClass('action');

    }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
