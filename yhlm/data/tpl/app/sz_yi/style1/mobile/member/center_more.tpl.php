<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!-- center -->
<title>个人中心</title>
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/style1/static/css/center.css">
<style type="text/css">
	.header{
		background: #282f31;
	}
	.header .user .user-info{
		width: calc(100% - 80px);
	}
	.user-recom{
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
	.tool-type-box{
		background: #fff;
	}
	.personal-info{
  		margin-top: 20px;
  		background: #fff;
  	}
  	/*覆盖center.css里面的样式*/
  	.personal-info .cart{
  		margin-top: 0px;
  		border-bottom: 0px;
  	}
  	.tool-type-tag{
  		padding: 10px 20px 0px;
  		border-bottom: 1px solid #ddd;
  	}
  	.tool-type-tag .tool-type-name{
  		float: left;
  		font-size: 18px;
  		color: #000;
  	}
  	.tool-type-tag .tool-type-more{
  		float: right;
  		font-size: 12px;
  		color: #999;
  		line-height: 27px;
  		position: relative;
  		padding-right: 20px;
  	}
  	.tool-type-tag .tool-type-more .more-link{
  		display: inline-block;
  		width: 100%;
  		height: 100%;
  	}
  	.tool-type-tag .tool-type-more .more-link,
  	.tool-type-tag .tool-type-more .more-link:visited{
		color: #999;
	}
  	.tool-type-tag .tool-type-more .more-icon{
  		font-size: 20px;	
  		margin-left: 10px;
  		display: inline-block;
  		position: absolute;
  		top: 50%;
  		transform: translateY(-50%);
  		margin-top: -2px;
  	}
  	.other-tool-box{
  		padding-bottom: 20px;
  	}
	.merchant .merchant-ul,
	.other-tool-box .other-ul{
		width: 100%;
	}
	.merchant .merchant-ul li,
	.other-tool-box .other-ul li{
		width: 25%;
		float: left;
		padding-bottom: 10px;
    	padding-top: 10px;
    	text-align: center;
	}
	.tool-type-box .item-link{
		color: #000;
	    font-size: 14px;
	    display: block;
	    width: 100%;
	    height: 100%;
	}
	.item-link .img1-box {
	    border-radius: 50%;
	    background: #10BDFF;
	    display: block;
	    width: 45px;
	    height:45px;
	    margin: 0 auto;
	    position: relative;
	    padding-left: 3px;
    	padding-right: 3px;
	}
	.item-link .img1-box .img1{
	    width: 80%;
	    height: 80%;
	    top: 6px;
	    position: absolute;
	    left: 4px;
	}
	.merchant .merchant-ul .item-link .img1-box{
		background: transparent;
	}
	.merchant .merchant-ul .item-link .img1-box .img1{
		width: 100%;
	    height: 100%;
	    top: 0px;
	    left: 0px;
	}
	.item-link .tool-name-box{
		display: block;
		white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
	}
	/*提高充值的z-index 就不用怕点击充值跳到余额那里去*/
	.z-take1{
		z-index: 100;
	}
	.other-tool-box .item-link .img1-box .img1{
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%);
	}
</style>
<div id="container"></div>
<script id="member_center" type="text/html">    
  <div class="header">
    <div class="user">
      <div class="user-left" onclick='location.href="<?php  echo $this->createMobileUrl('member/info')?>"'>
        <div class="user-head"><img src="<%member.avatar%>"/></div>
        <div class="user-info">
          <div class="user-name"><%member.nickname%></div>
          <div class="user-other user-member" <?php  if(!empty($set['shop']['levelurl'])) { ?>onclick='location.href="<?php  echo $set['shop']['levelurl'];?>"'<?php  } ?> ><%level.levelname%></div>
           <div class="user-other user-recom"><%if shop_set.shop.isreferrer%>推荐人：[<%referrer.realname%>]<%/if%> </div>
          <div class="user-other">ID：<%member.id%></div>
         
        </div>
      </div>
      <div class="user-right">
        <!-- <div> <i></i>余额：<a href="<?php  echo $this->createMobileUrl('member/transfer')?>" class="take">转账</a><?php  if(empty($set['trade']['closerecharge'])) { ?><a href="<?php  echo $this->createMobileUrl('member/recharge',array('openid'=>$openid))?>" class="take take1">充值</a><?php  } ?></div> -->
        <?php  if(empty($set['trade']['closerecharge'])) { ?><a href="<?php  echo $this->createMobileUrl('member/recharge',array('openid'=>$openid))?>" class="take take1 z-take1">充值</a><?php  } ?>
        <div style="position: relative;" onclick='location.href="<?php  echo $this->createMobileUrl('barter/cash')?>"'><i  style="float: left;">余额：</i><!-- <?php  if(empty($set['trade']['closerecharge'])) { ?><a href="<?php  echo $this->createMobileUrl('member/recharge',array('openid'=>$openid))?>" class="take take1">充值</a><?php  } ?> -->
        <!-- <div class="t_num hs-min" data-num="<%member.credit2%>"></div> -->
        <div class="timer" style="font-size:1rem" data-to="<%member.credit2%>" data-speed="1500"></div>
        </div>
        <div onclick='location.href="<?php  echo $this->createMobileUrl('barter/score')?>"' class="integral"><i style="float: left;">积分：</i><%if open_creditshop%><a href="<?php  echo $this->createPluginMobileUrl('creditshop')?>" class="take">兑换</a><%/if%>
        <!-- <div class="t_num" data-num="<%member.credit1%>"></div> -->
        <div class="timer" style="font-size:1rem" data-to="<%member.credit1%>" data-speed="1500"></div>
        </div>
        <!-- 换货码/币 -->
         <div class="integral" onclick='location.href="<?php  echo $this->createMobileUrl('barter/code')?>"'><i style="float: left;">换货码：</i>
        <!-- <div class="t_num" data-num="<%member.credit1%>"></div> -->
        <div class="timer" style="font-size:1rem" data-to="<%member.credit3%>" data-speed="1500"></div>
        </div>
        
      </div>
    </div>
    <ul class="hs-user clearfloat">
      <li> <a href="<?php  echo $this->createMobileUrl('order',array('status'=>0))?>"><i class="hs hs-daifukuan" style="font-size:30px"></i>
        <div style="margin-top: -5px;">待付款</div><%if order.status0>0%><span class="count"><%order.status0%></span><%/if%></a> </li>
      <li> <a href="<?php  echo $this->createMobileUrl('order',array('status'=>1))?>"><i class="hs hs-daifahuo" style="font-size:30px"></i>
        <div style="margin-top: -5px;">待发货</div><%if order.status0>1%><span class="count"><%order.status1%></span><%/if%></a> </li>
      <li> <a href="<?php  echo $this->createMobileUrl('order',array('status'=>2))?>"><i class="hs hs-icon3" style="font-size:30px"></i>
        <div style="margin-top: -5px;">待收货</div><%if order.status0>2%><span class="count"><%order.status2%></span><%/if%></a> </li>
      <li> <a href="<?php  echo $this->createMobileUrl('order',array('status'=>4))?>"><i class="hs hs-tuikuan" style="font-size:30px"></i>
        <div style="margin-top: -5px;">退款/售后</div><%if order.status0>4%><span class="count"><%order.status4%></span><%/if%></a> </li>
      <li class="hs-order"> <a href="<?php  echo $this->createMobileUrl('order')?>"><i class="hs hs-wodedingdan" style="font-size:30px"></i>
        <div style="margin-top: -5px;">我的订单</div></a> </li>
    </ul>
    <iframe id="vector" src="<?php  echo $this->createMobileUrl('member/vector',array('openid'=>$openid))?>"></iframe>
  </div>
  <div class="personal-info">
  	  <div class="tool-type-tag clearfloat">
	  	<div class="tool-type-name">个人信息</div>
	  	<div class="tool-type-more">
	  	  <a href="/app/index.php?i=8&c=entry&do=member&m=sz_yi" class="more-link">返回<span class="fa fa-angle-right more-icon"></span>
	  	  	</a>
	  	</div>
	  </div>
	  <ul class="cart hs-select clearfloat">

          <!--修改密码-->
          <li> <a href="/app/index.php?i=8&c=entry&p=forget&do=member&m=sz_yi"><div class="hs-item" style="background:#c1c554"><i class="fa fa-key"></i></div>修改密码 </a> </li>
          <!--消费密码-->
        <li> <a href="/app/index.php?i=8&c=entry&method=password&p=commission&m=sz_yi&do=plugin"><div class="hs-item" style="background:#c1c554"><i class="fa fa-key"></i></div>消费密码 </a> </li>
		<!--我的资料-->
	    <li> <a href="<?php  echo $this->createMobileUrl('member/info')?>"><div class="hs-item" style="background:#c1c554"><i class="hs hs-wodeziliao"></i></div>我的资料 </a> </li>
		<!--绑定手机-->
		<?php  if(!$member['isbindmobile'] && is_weixin()) { ?>    
		<li> <a href="<?php  echo $this->createMobileUrl('member/bindmobile')?>"><div class="hs-item" style="background:#c1c554"><i class="fa fa-mobile"></i></div>绑定手机 </a> </li>  
		<?php  } ?>

          <!--领优惠券-->
        <li> <a href="<?php  echo $this->createPluginMobileUrl('coupon')?>"><div class="hs-item" style="background:#e3378d"><i class="hs hs-fanli"></i></div>领优惠券 </a> </li>
          <!--我的购物车-->
	    <li> <a href="<?php  echo $this->createMobileUrl('shop/cart')?>"><div class="hs-item" style="background:#44b9c0"><i class="hs hs-cart"></i><span class="hs-count"><%counts.cartcount%></span></div>我的购物车</a></li>
		<!--我的收藏--> 
	    <li> <a href="<?php  echo $this->createMobileUrl('shop/favorite')?>"><div class="hs-item" style="background:#e23088"><i class="hs hs-jushoucang"></i><span class="hs-count"><%counts.favcount%></span></div>我的收藏</a></li>
		<!--我的足迹--> 
	    <li> <a href="<?php  echo $this->createMobileUrl('shop/history')?>"><div class="hs-item" style="background:#0680a9"><i class="hs hs-wodezuji"></i></div>我的足迹 </a> </li>
        <!--消费排行榜-->
        <li><a href="/app/index.php?i=8&c=entry&p=phb&do=member&m=sz_yi"><div class="hs-item"><i class="fa fa-clock-o"></i></div>消费排行榜</a></li>
          <!--收货地址-->
        <li><a href="<?php  echo $this->createMobileUrl('shop/address')?>"><div class="hs-item" style="background:#10BDFF;"><i class="hs hs-shouhuodizhi"></i></div>收货地址</a></li>

		<!--分销中心--> 
	    <?php  if($hascom) { ?>
	    <li> <a href="<?php  echo $this->createPluginMobileUrl('commission')?>"><div class="hs-item" style="background:#e84675"><i class="hs hs-fenxiao"></i></div><?php  echo $pset['texts']['center']?> </a> </li>
		<?php  } ?>


	  <?php  if(!is_weixin()) { ?>
	    <li> <a href="<?php  echo $this->createMobileUrl('member/logout')?>"><div class="hs-item" style="background:#e3378d"><i class="hs hs-fenxiaoshang"></i></div>退出 </a> </li>
	  <?php  } ?>
	  </ul>
  </div>
  


  
  <div class="copyright">版权所有 © <?php  if(empty($set['shop']['name'])) { ?><?php  echo $_W['account']['name'];?><?php  } else { ?><?php  echo $set['shop']['name'];?><?php  } ?> </div>
</script>
<!-- js --> 
<script type="text/javascript">
require(['tpl', 'core'], function(tpl, core) {  
	core.json('member/center',{},function(json){     
		$('#container').html(  tpl('member_center',json.result) );   
		var withdrawmoney = <?php echo empty($set['trade']['withdrawmoney'])?0:floatval($set['trade']['withdrawmoney'])?>;   
		$('#btnwithdraw').click(function(){       
			if(json.result.member.credit2<=0){        
				core.tip.show('无余额可提!');          
				return;           
			}             
			if(withdrawmoney>0 && json.result.member.credit2<withdrawmoney){    
				core.tip.show('满' +withdrawmoney + "元才能提现!" );        
				return;              
			}              
			location.href = core.getUrl('member/withdraw');       
		});
		//new add   
		$(function(){
			$.fn.countTo = function (options) {
				options = options || {};
				
				return $(this).each(function () {
					// set options for current element
					var settings = $.extend({}, $.fn.countTo.defaults, {
						from:            $(this).data('from'),
						to:              $(this).data('to'),
						speed:           $(this).data('speed'),
						refreshInterval: $(this).data('refresh-interval'),
						decimals:        $(this).data('decimals')
					}, options);
					
					// how many times to update the value, and how much to increment the value on each update
					var loops = Math.ceil(settings.speed / settings.refreshInterval),
						increment = (settings.to - settings.from) / loops;
					
					// references & variables that will change with each update
					var self = this,
						$self = $(this),
						loopCount = 0,
						value = settings.from,
						data = $self.data('countTo') || {};
					
					$self.data('countTo', data);
					
					// if an existing interval can be found, clear it first
					if (data.interval) {
						clearInterval(data.interval);
					}
					data.interval = setInterval(updateTimer, settings.refreshInterval);
					
					// initialize the element with the starting value
					render(value);
					
					function updateTimer() {
						value += increment;
						loopCount++;
						
						render(value);
						
						if (typeof(settings.onUpdate) == 'function') {
							settings.onUpdate.call(self, value);
						}
						
						if (loopCount >= loops) {
							// remove the interval
							$self.removeData('countTo');
							clearInterval(data.interval);
							value = settings.to;
							
							if (typeof(settings.onComplete) == 'function') {
								settings.onComplete.call(self, value);
							}
						}
					}
					
					function render(value) {
						// var formattedValue = settings.formatter.call(self, value, settings);
						var formattedValue = Number(value).toFixed(2);
						$self.html(formattedValue);
					}
				});
			};

			$.fn.countTo.defaults = {
				from: 0,               // the number the element should start at
				to: 0,                 // the number the element should end at
				speed: 1000,           // how long it should take to count between the target numbers
				refreshInterval: 100,  // how often the element should be updated
				decimals: 0,           // the number of decimal places to show
				formatter: formatter,  // handler for formatting the value before rendering
				onUpdate: null,        // callback method for every time the element is updated
				onComplete: null       // callback method for when the element finishes updating
			};

			function formatter(value, settings) {
				return value.toFixed(settings.decimals);
			}

			// custom formatting example
			$('.count-title').data('countToOptions', {
				formatter: function (value, options) {
				  return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
				}
			});

			// start all the timers
			$('.timer').each(count);  
			function count(options) {
				var $this = $(this);
				options = $.extend({}, options || {}, $this.data('countToOptions') || {});
				$this.countTo(options);
			}
		});
	},true);   
})
</script> 
<?php  $show_footer=true?>
<?php  $footer_current='member'?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>