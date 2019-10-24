<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/static/css1/one.css">
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/static/css1/style.css">
<link rel="stylesheet" href="../addons/sz_yi/plugin/bartact/template/mobile/default/css/style.css">
<style>
    .goods-list .right-side{
        height: 527px;
    }
    .goods-list .right-side li p:nth-of-type(1){
        height: 35px;
    }
    .goods-list .left-side .ul-1, .goods-list .left-side .ul-2{
        line-height: 32px;
    }
    .goods-list .left-side{
        height: 550px;
    }
    .weizhi{
      padding: 10px 0;
      border-bottom: 1px solid #ebebeb;
      margin-bottom: 20px;
    }
</style>
<div class="yh_header" id="yh_header">
	<div class="ye_header_top">
		<p>
			欢迎光临易货联盟商城！
			<strong class="phone red">热线:400-616-1519</strong>
			<span class="cart r-align">
				<a href="#"><img src="../addons/sz_yi/static/img/cart.png">购物车<font class="red">0</font>商品</a>
			</span>
			<span class=" r-align">
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&eid=194&wxref=mp.weixin.qq.com#wechat_redirect" class="phone1">手机版</a>
			</span>
			<span class="behind  r-align">
				<strong><a href="http://jhzh66.com/web/index.php?c=site&a=entry&method=dealgoods&p=suppliermenu&do=plugin&m=sz_yi" class="red">商家后台</a></strong>
			</span>
			<span class="r-align">
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=register&do=member&m=sz_yi" class="regiest">注册</a>
				<font class="line">|</font>
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=login&do=member&m=sz_yi" class="login">登录</a>
			</span>

		</p>

	</div>
</div>
<div class="main">
	<!-- 头部搜索框 -->
	<div class="main_top flex">
		<!-- logo -->
		<div class="logo flex1 flex">
			<img src="../addons/sz_yi/static/images/logo.png">
			<div class="fonts">
				<h4><strong>易货联盟<!-- <font class="four" >抱团营销</font> -->	</strong></h4>
				<font style="font-size: 15px">WWW.JHZH66.COM</font>
			</div>
		</div>
		<!-- 搜索 -->
		<div class="search_box flex2">
			<div class="search flex">
				<input class="form-control" type="text" name="" placeholder="现代时尚 LED吸顶灯 创意玫瑰花水晶灯 客厅卧室书房">
				<button type="submit" class="search_btn">搜索</button>
			</div>
			<p class="lists">	
				<span class="">换车 换房</span>
				<span class="">换采购产品 换广告</span>
				<span class="">换本地吃、喝、玩、乐</span>
			</p>
		</div>
		<!-- 二维码 -->
		<div class="erweima flex1">
			<img src="../addons/sz_yi/static/images/1pae.jpg">
		</div>
  </div>
  <!-- 内容 -->
  <div class=" clearfix">
    <p class="foot_detail_p">
        <span><a href="http://jhzh66.com/web/index.php?c=site&a=entry&method=one&p=bartact&do=plugin&m=sz_yi#">首页</a></span>
        <span>&gt;</span>
        <span><a href="javascript:;" class="foot_pCurrent">新闻</a></span>
    </p>
<!--左边 开始-->
<div class="foot_detail_l">
  <div class="foot_leftTop">&nbsp;新闻</div>
    <div class="foot_leftCon">
      <ul>
        <!--<li><a href="#" class="foot_leftCurrent">优惠券说明</a></li>-->
          <!--<li><a href="#" class="foot_leftCurrent">会员介绍</a></li>-->
          <!--<li><a href="#" class="foot_leftCurrent">金币说明</a></li>-->
          <!--<li><a href="#" class="foot_leftCurrent">商场社区</a></li>-->
          <!--<li><a href="#" class="foot_leftCurrent">账户安全</a></li>-->

      </ul>
    </div>
</div>
<!--左边 结束-->
<!--右边 结束-->
<div class="foot_detail_r">
  <div class="foot_rightTop"><?php  echo $article['title'];?></div>
    <div class="foot_rightCon">
        <?php  echo $article['content'];?>

<p>&nbsp;</p>

<p>&nbsp;</p>
    </div>
</div>
<!--右边 结束-->
</div>
<!-- footer -->
<div class="main-footer1">
  <div class="five-box">
    <ul class="five flex">
      <li class="flex1">
        <img src="../addons/sz_yi/static/img/7.png">
        7天退换
      </li>
      <li class="flex1">
        <img src="../addons/sz_yi/static/img/5b8i.png">
        专业维修
      </li>
      <li class="flex1">
        <img src="../addons/sz_yi/static/img/a7am.png">
        延保服务
      </li>
      <li class="flex1">
        <img src="../addons/sz_yi/static/img/0xw7.png">
        回收服务
      </li>
      <li class="flex1">
        <img src="../addons/sz_yi/static/img/q7nv.png">
        服务中心
      </li>
    </ul>
  </div>

    <div class="five2 flex">
        <ul class="flex1">
            <h3>易货易物常识</h3>
            <!--<li><a href="<?php  echo $this->createPluginWebUrl('bartact/send',array('op'=>'display','title'=>'易货易物常识'));?>">易货易物常识</a></li>-->
            <li><a href="<?php  echo $this->createMobileUrl('member/send',array('op'=>'display','title'=>'易货易物概念'));?>">易货易物概念</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/send',array('op'=>'display','title'=>'易货易物现状'));?>">易货易物现状</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/send',array('op'=>'display','title'=>'易货易物优势'));?>">易货易物优势</a></li>
        </ul>
        <ul class="flex1">
            <h3>新手指南</h3>
            <li><a href="<?php  echo $this->createMobileUrl('member/ready',array('op'=>'display','title'=>'商家申请'));?>">商家申请</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/ready',array('op'=>'display','title'=>'发布产品'));?>">发布产品</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/ready',array('op'=>'display','title'=>'换货码激活'));?>">换货码激活</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/ready',array('op'=>'display','title'=>'收费标准'));?>">收费标准</a></li>
        </ul>
        <ul class="flex1">
            <h3>售后服务</h3>
            <li><a href="<?php  echo $this->createMobileUrl('member/sale',array('op'=>'display','title'=>'查看订单'));?>">查看订单</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/sale',array('op'=>'display','title'=>'邮费提现'));?>">邮费提现</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/sale',array('op'=>'display','title'=>'申请退款'));?>">申请退款</a></li>
        </ul>
        <ul class="flex1">
            <h3>会员专区</h3>
            <li><a href="<?php  echo $this->createMobileUrl('member/vip',array('op'=>'display','title'=>'会员介绍'));?>">会员介绍</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/vip',array('op'=>'display','title'=>'会员申请'));?>">会员申请</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/vip',array('op'=>'display','title'=>'会员福利'));?>">会员福利</a></li>
        </ul>
        <ul class="flex1">
            <h3>常见问题</h3>
            <li><a href="<?php  echo $this->createMobileUrl('member/shopping',array('op'=>'display','title'=>'购物流程'));?>">购物流程</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/shopping',array('op'=>'display','title'=>'易物常见问题'));?>">易物常见问题</a></li>
            <li><a href="<?php  echo $this->createMobileUrl('member/shopping',array('op'=>'display','title'=>'联系我们'));?>">联系我们</a></li>
        </ul>
    </div>
</div>
<div class="footer2">
  <div class="footer-content">
      <span>增值电信业务经营许可证： 鄂ICP备14005665号-10 </span>     
      <span>网络文化经营许可证：鄂ICP备14005665号-10</span>     
      <span>
        互联网医疗保健信息服务 审核同意书 鄂ICP备14005665号-10 
      </span>
      <span>2012-0005© 2003-2016 xxxxx.COM 版权所有</span>
    <ul class="flex imgs">
      <li class="flex1"><img src="../addons/sz_yi/static/img/zj05_ltxd.png"></li>
      <li class="flex1"><img src="../addons/sz_yi/static/img/zj07_ur05.png"></li>
      <li class="flex1"><img src="../addons/sz_yi/static/img/zj09_zjyh.png"></li>
      <li class="flex1"><img src="../addons/sz_yi/static/img/zj11_7ba2.png"></li>
    </ul>
  </div>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>