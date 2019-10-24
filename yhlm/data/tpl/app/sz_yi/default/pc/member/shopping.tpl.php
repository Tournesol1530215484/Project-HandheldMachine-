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
        <span><a href="javascript:;" class="foot_pCurrent">购物流程</a></span>
    </p>
<!--左边 开始-->
<div class="foot_detail_l">
  <div class="foot_leftTop">购物帮助</div>
    <div class="foot_leftCon">
      <ul>
        <li><a href="<?php  echo $this->createPluginWebUrl('bartact/shopping',array('op'=>'display','title'=>"货到付款"));?>" class="foot_leftCurrent">购物流程</a></li>
          <li><a href="<?php  echo $this->createPluginWebUrl('bartact/shopping',array('op'=>'display','title'=>"易物常见问题"));?>" class="foot_leftCurrent">易物常见问题</a></li>
          <li><a href="<?php  echo $this->createPluginWebUrl('bartact/shopping',array('op'=>'display','title'=>"货到付款"));?>" class="foot_leftCurrent">联系我们</a></li>
      </ul>
    </div>
</div>
<!--左边 结束-->
<!--右边 结束-->
<div class="foot_detail_r">
  <div class="foot_rightTop"><?php  echo $but['title'];?></div>
    <div class="foot_rightCon">
        <?php  if($but['type']==1) { ?>
        <?php  if(is_array($list)) { foreach($list as $v) { ?>

        <p><strong><?php  echo $v['wen'];?></strong></p>

        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo $v['da'];?></p>
        <?php  } } ?>
        <?php  } ?>

        <?php  if($but['type']==2) { ?>
        <?php  if(is_array($list)) { foreach($list as $v) { ?>
        <?php  if(!empty($v['wen'])) { ?>
        <p><strong><?php  echo $v['wen'];?></strong></p>
        <?php  } ?>
        <?php  if(!empty($v['pic'])) { ?>
        <p><strong><img alt="" src="<?php  echo tomedia($v['pic'])?>" style="height:510px; width:850px"></strong></p>
        <?php  } ?>
        <?php  } } ?>
        <?php  } ?>
      <!--<p><strong>进入注册页面，填写您希望使用的用户名、密码、手机号码等信息，获取验证码完成注册。</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.79797600 1471585981." style="height:510px; width:850px"></strong></p>-->

      <!--<p><strong>第二步 &nbsp;绑定企业开通店铺（个人易货可忽略）</strong></p>-->

      <!--<p><strong>在首页右上方点击“绑定企业”按钮。</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.79459000 1471586015." style="height:545px; width:850px"></strong></p>-->

      <!--<p><strong>进入企业用户注册页面，设置您的企业名称，企业所在地等信息。</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.22412500 1471586039." style="height:510px; width:850px"></strong></p>-->

      <!--<p><strong>上传您的营业执照，法人身份证等企业相关资质。</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.37220100 1471586061." style="height:510px; width:850px"></strong></p>-->

      <!--<p><strong>按照信息提示认真填写，确认无误后提交等待审核！</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.82400900 1471586083." style="height:510px; width:850px"></strong></p>-->

      <!--<p><strong>第三步 &nbsp;寻找商品</strong></p>-->

      <!--<p><strong>在网页上方输入框内输入自己想要换购的商品名称，如“酒水”，点击</strong><strong>“</strong><strong>搜索</strong><strong>”</strong><strong>进入如图：（可以搜商品、需求、企业）</strong></p>-->

      <!--<p><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/content1533890134.png" style="height:915px; width:1919px"></p>-->

      <!--<p><strong>选择您需要的商品，确认数量，完善收货地址，确认提交订单，等待收货。</strong></p>-->

      <!--<p><strong>可到个人中心</strong><strong>-</strong><strong>我的订单查看。</strong></p>-->

      <!--<p>&nbsp;</p>-->

      <!--<p><strong>第四步、发布商品：进入企业中心进行需求管理。</strong></p>-->

      <!--<p><strong>可以发布企业自有的商品以及需求的商品信息，易货平台提供一对一客服服务。</strong></p>-->

      <!--<p><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.91947800 1471587010." style="height:506px; width:850px"></p>-->

      <!--<p><strong>企业通过发布现有</strong><strong>的库存货物</strong><strong>易出后</strong><strong>获得提货权</strong><strong>，在易货商城</strong><strong>内用提货权换取其他商品。</strong><strong>也可以现金充值后转换提货权，1元=1提货权。</strong></p>-->

      <!--<p><strong>进入个人中心“我的资产”管理资产。</strong></p>-->

      <!--<p><strong>资产分为提货权余额和现金余额；</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/content1533890149.png" style="height:912px; width:1920px"></strong></p>-->

      <!--<p><strong>提货权可以通过现金充值转换得到（支持支付宝与微信支付）</strong></p>-->

      <!--<p><strong><img alt="" src="http://www.51ehw.com/uploads/B/uploads/fck/0.20669800 1471587339." style="height:489px; width:850px"></strong></p>-->

      <!--<p>如有任何疑问请致电客服：400-0029-777</p>-->
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