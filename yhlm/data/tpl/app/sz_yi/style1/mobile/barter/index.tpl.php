<?php defined('IN_IA') or exit('Access Denied');?>﻿<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
		<title><?php  echo $set['bart']['title'];?></title>
		<link href="../addons/sz_yi/template/mobile/style1/barter/css/mui.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/style1/barter/fa/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/style1/barter/css/index.css" />
		<script language="javascript" src="../addons/sz_yi/static/js/require.js"></script>
		<script language="javascript" src="../addons/sz_yi/static/js/app/config.js?v=2"></script>
		<script type="text/javascript" src="../addons/sz_yi/template/mobile/style1/barter/js/jquery.min.js"></script>
		<script type="text/javascript" src="../addons/sz_yi/template/mobile/style1/barter/js/mui.min.js"></script>
		<script src="../addons/sz_yi/template/mobile/style1/barter/js/index.js"></script>
	</head>
	<style type="text/css">
		.platform {
			width: 100%;
			height: 2.5rem;
			background-color: #fcfdf8;
			padding: 0 2%;
			border-top: 1px solid #ccc;
		}



		.platform-left {
			width: 20%;
			height: 100%;
			text-align: center;
			overflow: hidden;
			float: left;
		}

		.platform-left span {
			font-size: 13px;
			display: block;
			font-weight: 600;
		}

		.platform-right {
			width: 80%;
			height: 100%;
			font-size: 15px;
			font-weight: 500;
			float: right;
			line-height: 100%;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.platform-right span {
			width: 100%;
			height: 100%;
			line-height: 2.5rem;
		}

		.goods {
			width: 100%;
			padding: 0 1% 15%;
			overflow: hidden;
			background: #fff;
		}

		.goods_list {
			width: 50%;
			padding: 5% 2%;
			float: left;
			height: 212px;
			overflow: hidden;
		}

		.goods_list img {
			width: 100%;
			height: 8rem;
		}

		.goods_list_con {
			width: 100%;
			height: 1rem;
			overflow: hidden;
			font-size: 0.6rem;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.goods_list_con span:first-of-type {
			/*width: 18%;*/
			height: 100%;
			float: left;
			background-color: red;
			color: #fff;
			text-align: center;
			line-height: 1rem;
			margin-right: 5%;
		}

		.goods_list_con span:last-of-type {
			color: #000101;
		}

		.goods_list_pirce {
			width: 100%;
			padding-top: 3%;
			white-space: nowrap;
    		overflow: hidden;
    		text-overflow: ellipsis;
		}

		.goods_list_pirce_left {
			color: #d82b31;
			float: left;
			font-size: 12px;
		}
		.goods_list_pirce_unit {
			color: #9d9d9d;
		}
		.goods_list_pirce_riht {
			float: right;
			font-size: 12px;
			color: #9d9d9d;
		}
		/*拆红包得现金、看广告换产品 入口*/
		.clearfix:before,
		.clearfix:after {
		    content: "";
		    display: block;
		}

		.clearfix:after {
		    clear: both;
		}
		.ad-entry-box{
			padding: 0 3% 10px;
			background: #fff;
		}
		.ad-entry-box .entry-box{
			width: 40%;
			margin: 0 5%;
			float: left;
			line-height: 1;
		}
		.ad-entry-box .entry-box .entry-link{
			display: block;
			width: 100%;
			font-size: 0;
		}
		.ad-entry-box .entry-box .entry-link .entry-img{
			width: 100%;
		}
		.national-goods-link-box{
			display: block;
			width: 100%;
		}
		.national-goods-link-box .national-goods-link{
			display: block;
			width: 100%;
			text-align: center;
			font-size: 16px;
			color: red;
			padding: 5px;
		}
	</style>
	<body>
		<script language="javascript">
		    require(['core','tpl'],function(core,tpl){
		        core.init({
		            siteUrl: "<?php  echo $_W['siteroot'];?>",
		            baseUrl: "<?php  echo $this->createMobileUrl('ROUTES')?>"
		        });
		    })
		</script>
		<div class="content">
			<header>
				<div class="headert-left go-share-btn" onClick="share()">
					<a href="javascript:void(0);" class="fa fa-share-square-o"></a>
				</div>
				<div class="header-con">
					<form action="" method="get">
					<input type="hidden" name="i" value="8">
				    <input type="hidden" name="c" value="entry">
				    <input type="hidden" name="do" value="barter">
				    <input type="hidden" name="m" value="sz_yi">
				    <input type="hidden" name="p" value="list">
						<input type="text" name="keywords" id="" value="" placeholder="搜索商品" />
					</form>
					<span class="mui-icon mui-icon-search"></span>
				</div>
				<div class="header-right">
					<a href="<?php  echo $this->createMobileUrl('barter/message')?>" class="fa fa-envelope-o"></a>
				</div>
			</header>
			<main>
				<!-- 轮播图 -->
				<div class="mui-slider">
					<div class="mui-slider-group mui-slider-loop">
						<!--支持循环，需要重复图片节点 最大的元素-->
						<?php  $max=count($banner)-1?>
						<div class="mui-slider-item mui-slider-item-duplicate">
						<a href="<?php  echo $banner[$max]['link'];?>"><img src="<?php  echo tomedia($banner[$max]['thumb'])?>" /></a>
						</div>
						<?php  if(is_array($banner)) { foreach($banner as $key => $val) { ?>
							<div class="mui-slider-item">
								<a href="<?php  echo $val['link'];?>"><img src="<?php  echo tomedia($val['thumb'])?>" /></a>
							</div>
						<?php  } } ?>
						<!--支持循环，需要重复图片节点 最小的元素-->
						<div class="mui-slider-item mui-slider-item-duplicate">
							<a href="<?php  echo $banner[0]['link'];?>"><img src="<?php  echo tomedia($banner[0]['thumb'])?>" /></a>
						</div>
					</div>
					<!--
                    	轮播图圆点
                    -->
					<div class="mui-slider-indicator">
						<?php  if(is_array($banner)) { foreach($banner as $key => $val) { ?>
							<div class="mui-indicator <?php  if($key == 0) { ?> mui-active<?php  } ?>"></div>
						<?php  } } ?>
					</div>
				</div>
				<!-- 拆红包得现金、看广告 换产品 入口 --><!-- 看广告换产品 --><!-- 易活动 得现金 -->
				<!-- <div class="ad-entry-box clearfix">

					<div class="barter-entry entry-box">
						<a class="barter-link entry-link" href="<?php  echo $this->createMobileUrl('barter/ad')?>">
							<img class="entry-img" src="../addons/sz_yi/static/images/barter_entry.png">
						</a>
					</div>

					<div class="envelope-entry entry-box">
						<a class="envelope-link entry-link" href="<?php  echo $this->createMobileUrl('barter/bonus',array('op'=>'bonusIndex','type'=>'1'))?>">
							<img class="entry-img" src="../addons/sz_yi/static/images/envelope_entry.png">
						</a>
					</div>
				</div> -->
				<!-- 商品分类 -->
				<ul class="mui-table-view mui-grid-view mui-grid-9 mui-nav">
					<?php  if(is_array($menu)) { foreach($menu as $v) { ?>
					<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3 boder-no" style="padding:0px;">
						<a href="<?php  echo $v['url'];?>">
							<img src="<?php  echo tomedia($v['thumb'])?>" />
							<div class="mui-media-body"><?php  echo $v['title'];?></div>
						</a>
					</li>
					<?php  } } ?>
				</ul>

				<!-- 平台动态 -->
				<div class="platform">
					<div class="platform-left">
						<span>平台</span>
						<span style="color: #fa0902;">动态</span>
					</div>
					<div class="platform-right">
						<marquee scrollamount="3" width="100%">
							<span><?php  echo $set['bart']['state'];?></span>
						</marquee>
					</div>
				</div>

				<!-- 商品列表 -->
				<div class="goods" data-id="">
					<?php  if(is_array($list)) { foreach($list as $v) { ?>
						<div class="goods_list">
							<a href="<?php  echo $this->createMobileUrl('shop/detail',array('id'=>$v['id']))?>">
								<img src="<?php  echo $v['thumb'];?>" />
								<div class="goods_list_con">
									<span>
									<?php  if($v['PostFlag'] == 1) { ?>邮寄<?php  } ?>
									<?php  if($v['PostFlag'] == 1 && $v['LocalFlag'] == 1 ) { ?> & <?php  } ?>
									<?php  if($v['LocalFlag'] == 1) { ?>现场<?php  } ?>
									</span>
									<span><?php  echo $v['title'];?></span>
								</div>
								<div class="goods_list_pirce">
									<span class="goods_list_pirce_left">
										<?php  if($v['minprice'] != $v['maxprice']) { ?>
											<?php  echo $v['minprice'];?> - <?php  echo $v['maxprice'];?>
										<?php  } else { ?>
											<?php  echo $v['maxprice'];?>
										<?php  } ?>
										<span class="goods_list_pirce_unit">换货码</span>
									</span>
									<span class="goods_list_pirce_riht">已出售：<?php  echo $v['salesreal'];?>件</span>
								</div>
							</a>
						</div>
					<?php  } } ?>
					<div class="national-goods-link-box">
						<a class="national-goods-link" href="<?php  echo $this->createMobileUrl('barter/list')?>">进入全国产品
				        </a>
					</div>
				</div>
				<div id='share_cover'><img src='../addons/sz_yi/static/images/guide_share.png'/></div>
			</main>

			<!-- 导航 -->
			<!-- 这里如果是菜单当前页，那么给对应的nav-item color红色 style="color: #f00605;"-->
			<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/sidebar_barter', TEMPLATE_INCLUDEPATH)) : (include template('common/sidebar_barter', TEMPLATE_INCLUDEPATH));?>
			<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/nav_barter', TEMPLATE_INCLUDEPATH)) : (include template('common/nav_barter', TEMPLATE_INCLUDEPATH));?>
			<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('designer/menu', TEMPLATE_INCLUDEPATH)) : (include template('designer/menu', TEMPLATE_INCLUDEPATH));?>
		</div>
		<script type="text/javascript">
			function share(){
		        $('#share_cover').fadeIn(200).unbind('click').click(function(){
		            $(this).fadeOut(100);
		        });
		    }
		    // require(['core'], function(core) {
		    // 	$(".envelope-link").click(function(){
		    // 		core.tip.show('正式开发中...','middle');
		    // 	});
		    // 	$(".barter-link").click(function(){
		    // 		core.tip.show('正式开发中...','middle');
		    // 	});
		    // });
		</script>
		<script>
		     require(['http://res.wx.qq.com/open/js/jweixin-1.0.0.js'],function(wx){
		        window.shareData = <?php  echo json_encode($_W['shopshare'])?>;
				jssdkconfig = <?php  echo json_encode($_W['account']['jssdkconfig']);?> || { jsApiList:[] };

			    jssdkconfig.debug = false;
				jssdkconfig.jsApiList = ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','showOptionMenu','scanQRCode'];
				wx.config(jssdkconfig);
				wx.ready(function () {
					wx.showOptionMenu(window.shareData);
					wx.onMenuShareAppMessage(window.shareData);
					wx.onMenuShareTimeline(window.shareData);
					wx.onMenuShareQQ(window.shareData);
					wx.onMenuShareWeibo(window.shareData);
			    });
			});
		</script>
	</body>

</html>