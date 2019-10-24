<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title><?php  echo $set['name'];?></title>
<style type="text/css">
	.follow_topbar{ display: none; }
    body {margin:0px; background:#eee; font-family:'微软雅黑'; -moz-appearance:none;}
    .top {overflow: hidden; position:relative;}
    .top .bgimg {height:auto; position:relative;}
    .top .bgimg img { width:100%; position: relative;}
    .top .list {position:absolute;right:10px;top:10px;height:35px; width:auto; line-height:50px; font-size:16px; text-align:right; color:#efefef; margin-right:10px;}
    .top .topbar {height:220px; width:100%; position:absolute; bottom:-30px; left:0px; z-index:2;}
    .top .topbar .logo {height:80px; width:80px; padding:4px; border:1px solid #fff; border-radius:45px; margin:auto;}
    .top .topbar .logo img {height:70px; width:70px; padding:4px; border:1px solid #fff; border-radius:70px; margin:auto;}
    .top .topbar .shop_name {height:30px; width:auto; background:rgba(0,0,0,0.1); padding:0px 15px; margin:10px auto; font-size:16px; color:#fff; line-height:30px; text-align:center; display:table; border-radius:15px;}
    .top .topbar .menu {height:45px; width:100%; background:rgba(0,0,0,0.3);}
    .top .topbar .menu .nav {height:40px; width:25%; padding-top:5px; float:left; text-align:center; font-size:12px; color:#fff;}
    .top .topbar .menu .navon {height:37px; border-bottom:3px solid #dd2322;}
    .top .topbar .menu .nav i {font-size:18px;}
    .title {height:40px; width:100%; margin-top: 10px; background:#fff; padding:0px 3%; font-size:16px; color:#666; line-height:40px;}
    .title1 {height:40px; margin-top: 10px; background:#fff; padding:0px 3%; font-size:16px; color:#666; line-height:40px;}
    .goods {height:auto; border-top: 1px #b5b5b5 solid; min-height:100px; width:100%; background:#fff; overflow:hidden;float:left;padding-bottom:40px;}
    .goods .good {overflow:hidden; width:50%; float:left; display: inline-table; padding: 2%}
    .goods .good .img {width:100%;overflow:hidden;}
    .goods .good .img img {width:100%;height:173px;}
    .goods .good .name {height: 48px;overflow-y: hidden; padding: 4px 6px; font-size:12px; line-height:20px; color:#666; }
    .goods .good .price { padding-left: 6px; height: 40px; color:#f03; font-size:14px;}
    .goods .good .price .dianjigoumai{font-size: 12px; margin-right: 10px;display: inline-block; width: 48px; height: 18px; border-radius: 2px; padding: 0px 4px; float: right; text-align: right; color: #fff;
    							background: #e14549 url(../addons/sz_yi/template/mobile/style1/shop/img/icon_gouwuche_1.png) no-repeat left center;background-size: 14px 14px; }
    .goods .good .price .div_span {color:#aaa; font-size:12px; /*text-decoration:line-through;*/display: block; width: 100%; height: 18px;}
    .goods .good .price .div_span .div_span_left{position:relative;float:left;width:70px;margin-right:-70px;}

	.box_1{ padding-right: 10px; margin-top: 4px;float:right;width:100%; height: 10px;}
	.box_1 .box_1_1{ height: 10px; margin-left:72px; background: #fff; border-radius: 5px; border: 1px #e14549 solid;overflow: hidden;}
	.box_1 .box_1_1 .box_2{ background: #e14549; height: 10px; border-bottom-left-radius: 2px; border-top-left-radius: 2px; }
    .copyright {height:40px; width:100%; text-align:center; line-height:30px; font-size:12px; color:#999; padding:10px 0 0; float: left;}
    /*.bottom_menu {height:50px; width:100%; background:#f90; position:fixed; bottom:0px; left:0px; z-index:1;}*/
    .banner {overflow:hidden;position:relative;height:auto;}
    .banner .main_image{width:100%;position:relative;top:0;left:0;}
	.banner .main_image ul{}
	.banner .main_image li{float:left;width: 100%;}
	.banner .main_image li img{display:block;width:100%; }
	.banner .main_image li a{display:block;width:100%;}
    div.flicking_con{position:absolute;bottom:10px;z-index:1;width:100%;height:12px;}
    div.flicking_con .inner { width:100%;height:9px;text-align:center;}
    div.flicking_con a{position:relative; width:10px;height:9px;background:url('../addons/sz_yi/template/mobile/default/static/images/dot.png') 0 0 no-repeat;display:inline-block;text-indent:-1000px}
    div.flicking_con a.on{background-position:0 -9px}
    .index_loading { width:94%;padding:10px;color:#666;text-align: center;float:left;}

    .class1 {background:#fff; border-top:1px solid #eee; border-bottom:1px solid #eee;overflow:hidden;margin-bottom:10px;}
	.class1 .class2 {height:85px; width:25%; float:left;}
	.class1 .class2:active {background:#f7f7f7;}
	.class1 .class2 .class3 {height:70px; width:80px; margin:auto;}
	.class1 .class2 .class3 .ico {height:40px; width:40px; margin:10px 15px 10px 15px; line-height:40px; text-align:center; color:#fff; font-size:18px;}
	.class1 .class2 .class3 .ico img { width:50px;height:50px;}
	.class1 .class2 .class3 .text {height:20px; width:80px; font-size:12px; color:#999; text-align:center; line-height:20px;overflow:hidden;}

	.top_menu{ width: 100%; height: 80px; background: #fff; padding: 10px 0px;}
	.top_menu ul{ width: 100%; height: 70px; clear: both;}
	.top_menu ul li{ width: 20%; height: 70px; float: left;}
	.top_menu ul li a{ display: block; width: 100%; height: 100%; text-decoration: none; color: #5d5d5d; }
	.top_menu ul li a i{ margin: 0px auto; width: 40px; height: 40px; display: block; }
	@media screen and (min-width: 320px) and (max-width: 359px) {
		.top_menu ul li a i{ width: 40px; height: 40px; }
	}
	@media screen and (min-width: 360px) and (max-width: 374px) {
		.top_menu{ height: 85px; }
		.top_menu ul li a i{ width: 45px; height: 45px; }
	}
	@media screen and (min-width: 375px) and (max-width: 413px) {
		.top_menu{ height: 90px; }
		.top_menu ul li a i{ width: 48px; height: 48px; }
	}
	@media screen and (min-width: 414px) and (max-width: 450px) {
		.top_menu{ height: 90px; }
		.top_menu ul li a i{ width: 50px; height: 50px; }
	}
	@media screen and (min-width: 768px) {
		.top_menu{ height: 110px; }
		.top_menu ul li a i{ width: 70px; height: 70px; }
	}
	.top_menu ul li a p{ font-size: 12px; margin: 5px auto; height: 20px; line-height: 20px; text-align: center; display: block; }
	ul#navlist{font-size:14px; padding-bottom: 13px;}
	ul#navlist li{ float: left; height: 40px; line-height: 40px; width: 20%; text-align: center;}
	ul#navlist li a{ text-decoration: none; display: block;width: 100%; height: 40px;color: #000000; }
	.activex{ }
	.activex a{ color: #ff1c1d!important; border-bottom: 1px #ff1c1d solid; }

    .top_search{ height: 40px; position: fixed; top: 0px; width: 100%; }
    .top_search a{ color: #fff; }
    .top_search p{ font-size: 12px;margin: -4px 0px 0px; text-align: center; }
	.top_search .logo1 a,.pull_down_menu a{ display: block; width: 100%; height: 100%;text-decoration:none;}
	.top_search .menu_right{ width: 100%; float:right;position: relative;z-index: 4}
    .top_search .menu_right .menu_right1{ margin-left:52px; height: 36px;}
    .top_search .menu_right .menu_right1 .menu_right2{ float:left; width:100%;}
    .search { margin-right:44px; height:30px; margin-top: 5px; line-height:30px; font-size:14px; text-indent: 10px; border-radius: 3px; color: #fff; background: rgba(233, 233, 233, 0.2);}

	@font-face {font-family: 'iconfont';
	    src: url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont.eot'); /* IE9*/
	    src: url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
	    url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont.woff') format('woff'), /* chrome、firefox */
	    url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
	    url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont.svg#iconfont') format('svg'); /* iOS 4.1- */
	}
	.iconfont{ font-family:"iconfont" !important;
		       font-size:16px;font-style:normal;
		       -webkit-font-smoothing: antialiased;
		       -webkit-text-stroke-width: 0.2px;
		       -moz-osx-font-smoothing: grayscale;
	}
	.bg{ background:#000;
		width: 100%;
		position: absolute;
		top: -2px;
		left: -20%;
    	width: 140%;
		height: 8px;
		-webkit-filter: blur(10px);
		   -moz-filter: blur(10px);
		    -ms-filter: blur(10px);
		        filter: blur(10px);
		filter: progid:DXImageTransform.Microsoft.Blur(PixelRadius=10, MakeShadow=false);
		z-index: 1
	}
</style>

<!--屏幕中间【点击显示】下拉菜单代码样式-->
<link href="../addons/sz_yi/template/mobile/style1/shop/hqs/hqs_otherinfo.css" rel="stylesheet">
<!--屏幕中间【点击显示】下拉菜单代码样式结束-->

<div id='list_contanier'></div>
<div id='container'></div>
<script id='tpl_index' type='text/html'>
    <%if advs.length>0%>
    <div class="banner">
        <%if advs.length>1%>
        <div class="flicking_con"><div class="inner">
            <%each advs as value index %>
            <a class="<%if index==0%>on<%/if%>" href="#@"><%index%></a>
            <%/each%>
            </div>
        </div>
        <%/if%>


        <div class="main_image c-demoslider" id='banner'>
            <ul data-type="slides">
                <%each advs as adv %>
                <li data-delay="5" data-src="5" data-trans3d="tr6,tr17,tr22,tr23,tr29,tr27,tr32,tr34,tr35,tr53,tr54,tr62,tr63,tr4,tr13,tr45" data-trans2d="tr3,tr8,tr12,tr19,tr22,tr25,tr27,tr29,tr31,tr34,tr35,tr38,tr39,tr41" <%if adv.link%>onclick="location.href='<%adv.link%>'"<%/if%>>
                	<img src="<%adv.thumb%>" data-src="<%adv.thumb%>"  data-thumb="<%adv.thumb%>"  /></li>
                <%/each%>
            </ul>
            <ul data-type="controls">
				<li data-type="captions"></li>
				<li data-type="link"></li>
				<li data-type="video"></li>
				<li data-type="slideinfo"></li>
				<li data-type="circletimer"></li>
				<li data-type="previous"></li>
				<li data-type="next"> </li>
				<li data-type="bartimer"></li>
				<li data-type="slidecontrol" data-thumb="true" data-thumbalign="up"></li>
			</ul>
        </div>

    </div>
    <%/if%>

   <div class="top_menu">
    	<ul><li><a href="javascript:void(0);"  onclick="location.href='<?php  echo $this->createMobileUrl("shop/list") ?>'"><i style="background: url(../addons/sz_yi/template/mobile/style1/shop/img/icon_11.png) no-repeat center center; background-size: 100% 100%; "></i><p>全部商品</p></a></li>
    		<li><a href="<?php  echo $this->createMobileUrl('order')?>"><i style="background: url(../addons/sz_yi/template/mobile/style1/shop/img/icon_12.png) no-repeat center center; background-size: 100% 100%; "></i><p>我的订单</p></a></li>
    		<li><a href="<?php  echo $this->footer['second']['url']?>"><i style="background: url(../addons/sz_yi/template/mobile/style1/shop/img/icon_13.png) no-repeat center center; background-size: 100% 100%; "></i><p>商品分类</p></a></li>
    		<li><a href="<?php  echo $this->createMobileUrl('shop/cart')?>"><i style="background: url(../addons/sz_yi/template/mobile/style1/shop/img/icon_14.png) no-repeat center center; background-size: 100% 100%; "></i><p>购物车</p></a></li>
    		<li><a href="<?php  echo $this->createMobileUrl('member')?>"><i style="background: url(../addons/sz_yi/template/mobile/style1/shop/img/icon_15.png) no-repeat center center; background-size: 100% 100%; "></i><p>个人中心</p></a></li>
    	</ul>
    </div>

    <%if category.length>0%>
    <div class="title">推荐分类</div>
    <div class="class1">
        <%each category as value%>
        <a href="<%value.url%>">
            <div class="class2">
                <div class="class3">
                    <div class="ico ico1"><img src='<%value.thumb%>' /></div>
                    <div class="text"><%value.name%></div>
                </div>
            </div>
        </a>
       <%/each%>
    </div>
    <%/if%>

    <div class="title1">
    	<ul id="navlist">
    		<li id="activex1" class="activex" onClick="tab(1)"><a href="javascript:void(0);">新品</a></li>
			<li id="activex2" onClick="tab(2)"><a href="javascript:void(0);">热卖</a></li>
			<li id="activex3" onClick="tab(3)"><a href="javascript:void(0);">推荐</a></li>
			<li id="activex4" onClick="tab(4)"><a href="javascript:void(0);">促销</a></li>
			<li id="activex5" onClick="tab(5)"><a href="javascript:void(0);">包邮</a></li>
		</ul>
    </div>
    <div class="goods">
        <div id='goods_container1' style=" overflow: auto; display:block;"></div>
        <div id='goods_container2' style="overflow: auto; display: none;"></div>
        <div id='goods_container3' style="overflow: auto; display: none;"></div>
        <div id='goods_container4' style="overflow: auto; display: none;"></div>
        <div id='goods_container5' style="overflow: auto; display: none;"></div>
    </div>
    <div class="copyright">版权所有 © <?php  if($this->yzShopSet['copyright']) { ?><?php  echo $this->yzShopSet['copyright']?><?php  } else { ?><?php  echo $_W['account']['name'];?><?php  } ?></div>
     <!--搜索-->
    <div class="search1">
        <div class="topbar1">
            <div class='right'>
                <button class="sub1"><i class="fa fa-search"></i></button>
                <div class="home1">取消</div>
            </div>
            <div class='left_wrap'>
                <div class='left'>
                    <input type="text" id='keywords' class="input1" placeholder='搜索: 输入商品关键词'/>
                </div>
            </div>
        </div>
        <div id='search_container' class='result1'>
        </div>
    </div>
</script>
<script id='tpl_goods_list' type='text/html'>
    <%each goods as g%>
    <div class="good" data-goodsid='<%g.id%>'>
        <div class='img'><img src="<%g.thumb%>"></div>
        <div class="name"><%g.title%></div>
        <div class="price">￥<%g.marketprice%><a herf="javascript:void(0);" class="dianjigoumai">购买</a>
        <br />
        <div class="div_span">
        	<div class="div_span_left">已售:<%g.sales%>件</div>
        	<div class="box_1">
        		<div class="box_1_1">
                	<div class="box_2" style=" width: <%g.ratio%>%;"></div>
                </div>
            </div>
        </div>
        <%if g.productprice>0 && g.marketprice!=g.productprice%>
	        <!--<span>￥<%g.productprice%></span>-->
        <%/if%>
	    </div>
    </div>
    <%/each%>
</script>
<script id='tpl_search_list' type='text/html'>
     <ul>
     <%each list as value%>
        <li><i class="fa fa-angle-right"></i> <a href="<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%value.id%>"><%value.title%></a></li>
        <%/each%>
    </ul>
</script>
<style type="text/css">
	.top_search .logo1 { width: 50px; height:36px; float: left; position:relative; padding: 2px 6px; border-radius: 18px; display: inline-block; margin-right:-52px;z-index: 5}
    .pull_down_menu{ width:30px; height: 36px; float: right; position:relative; margin: 1px 8px 0px -38px; border-radius: 17px;}
    .pull_down_menu1{ position: fixed;top:20%; right: 0px; border-radius: 50%; width:50px; height: 50px; margin: 6px 8px 0px -38px; background:#999; background-size: 100% 100%; box-shadow: 0 0 1px #4e4e4e;}
	.pull_down_menu1 a{ display: block; width: 100%; height: 100%; text-decoration: none; }
</style>
<div class="pull_down_menu1 otherinfo" id="rightFixed">
	<a href="javascript:void(0);" class="iconp" style="text-align:center;color:#eee">
		<i style="font-size:20px" class="hs hs-classify"></i>
		<span style="display:block;margin-top: -3px;">分类</span>
	</a>
	<div class="conmenu animated">
		<ul><li><a href="<?php  echo $this->footer['second']['url']?>">推荐分类</a></li>
			<li><a href="javascritp:void(0);" class="iconp1">男生会场</a>
				<div class="conmenu1 animated1">
	    			<ul><li><a href="javascritp:void(0);">内裤</a></li>
						<li><a href="javascritp:void(0);">裤子</a></li>
						<li><a href="javascritp:void(0);">衬衫</a></li>
						<li><a href="javascritp:void(0);">上衣</a></li>
						<li><a href="javascritp:void(0);">鞋子</a></li>
					</ul>
				</div>
			</li>
			<li><a href="javascritp:void(0);" class="iconp2">女生会场</a>
				<div class="conmenu2 animated2">
	    			<ul><li><a href="javascritp:void(0);">裤子</a></li>
						<li><a href="javascritp:void(0);">连衣裙</a></li>
						<li><a href="javascritp:void(0);">半身裙</a></li>
						<li><a href="javascritp:void(0);">上衣</a></li>
						<li><a href="javascritp:void(0);">内衣</a></li>
					</ul>
				</div>
			</li>
			<li><a href="javascritp:void(0);" class="iconp3">9.9特场</a>
				<div class="conmenu3 animated3">
	    			<ul>
	    				<li><a href="javascritp:void(0);">9.9特场</a></li>
					</ul>
				</div>
			</li>
			<li><a href="javascritp:void(0);" class="iconp4">饰品会场</a>
				<div class="conmenu4 animated4">
	    			<ul><li><a href="javascritp:void(0);">项链</a></li>
						<li><a href="javascritp:void(0);">头饰</a></li>
						<li><a href="javascritp:void(0);">帽子</a></li>
						<li><a href="javascritp:void(0);">首饰</a></li>
					</ul>
				</div>
			</li>
			<li><a href="javascritp:void(0);" class="iconp5">分销系统</a>
				<div class="conmenu5 animated5">
	    			<ul>
	    				<li><a href="javascritp:void(0);">智能分销</a></li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
</div>

	<!--改变头部搜索背景透明度与颜色-->
	<script type="text/javascript">
	    $(window).scroll(function(){
	    	var top = $(document).scrollTop() ;
	    	alpha = top/270;
	    	lpha = top/270 + 0.2;
	    	if(alpha>0.5){ alpha = 1; }
	    	if(top<=25){
	    		alpha1 = "fff";
	    	}else if (top>25) {
	    		alpha1 = "000";
	    	}
	    	if(top<=25){
	    		alpha2 = "fff";
	    	}else if (top>25) {
	    		alpha2 = "a4a3a3";
	    	}
	    	$('.top_search').css("background","rgba(255,255,255,"+ alpha + ")");
	    	$("#a_logo1").css("color","#"+ alpha1 );
	    	$('.search').css("background","rgba(233,233,233,"+ lpha + ")");
	    	$('.search').css("color","#"+ alpha2 );
	    	$("#a_pull_down_menu").css("color","#"+ alpha1 );
	    	//背影的增删
	    	if(top>250){
	    		$('.top_search > div:first-child').removeClass('bg');
	    	}else{
	    		$('.top_search > div:first-child').addClass('bg');
	    	}
	    });
	    $('body').on("touchmove",function(){
	    	var top = $(document).scrollTop() ;
	    	alpha = top/270;
	    	lpha = top/270 + 0.2;
	    	if(alpha>0.5){ alpha = 1; }
	    	if(top<=25){
	    		alpha1 = "fff";
	    	}else if (top>25) {
	    		alpha1 = "000";
	    	}
	    	if(top<=25){
	    		alpha2 = "fff";
	    	}else if (top>25) {
	    		alpha2 = "a4a3a3";
	    	}
	    	$('.top_search').css("background","rgba(255,255,255,"+ alpha + ")");
	    	$("#a_logo1").css("color","#"+ alpha1 );
	    	$('.search').css("background","rgba(233,233,233,"+ lpha + ")" );
	    	$('.search').css("color","#"+ alpha2 );
	    	$("#a_pull_down_menu").css("color","#"+ alpha1 );
	    	//背影的增删
	    	if(top>250){
	    		$('.top_search > div:first-child').removeClass('bg');
	    	}else{
	    		$('.top_search > div:first-child').addClass('bg');
	    	}
	    });
	</script>
	<div class="top_search">
    	<div class="bg"></div>
    	<div class="logo1">
    		<a href="javascript:void(0);" id="a_logo1">
	    		<i class="icon iconfont" style=" margin-left: 10px;">&#xe601;</i>
	    		<p>扫一扫</p></a>
    	</div>
    	<div class="menu_right">
    		<div class="menu_right1">
	    		<div class="menu_right2">
	    			<div class='search'>
	    				<i class="icon iconfont" style="font-size: 16px">&#xe602;</i> 输入商品名称</div></div>
	    		<div class="pull_down_menu">
	    			<a href="javascript:void(0);" id="a_pull_down_menu">
		    			<i class="icon iconfont xiaoxi" style=" margin-left: 7px;">&#xe600;</i>
		    			<p>消息</p></a>
	    		</div>
	    	</div>
    	</div>
    </div>
    <!--改变头部搜索背景透明度与颜色结束-->

<!--顶部搜索代码-->
<script src="../addons/sz_yi/template/mobile/style1/shop/hqs/js/skrollr.min.js"></script>
<script>
	// var s = skrollr.init();
</script>
<!--顶部搜索代码结束-->

<script language='javascript'>
    var page = new Array( );
    var loaded = new Array( );
    var stop = new Array( );
    var scrolling = new Array( );

    for(var $i=1;$i<=5;$i++){
    	page[$i]=1;
    	loaded[$i]=stop[$i]=scrolling[$i]=false;
    }

	function tab(n){
        $('#goods_container'+n).fadeIn();
        $('#goods_container'+n).siblings().hide();
        $('#activex'+n).addClass('activex');
        $('#activex'+n).siblings().removeClass('activex');
         window.getGoods (n);
    }

	//屏幕中间下拉菜单【左右移动】代码
	(function() {
        var special = jQuery.event.special,
            uid1 = 'D' + (+new Date()),
            uid2 = 'D' + (+new Date() + 1);
        special.scrollstart = {
            setup: function() {
                var timer,
                    handler = function(evt) {
                        var _self = this,
                            _args = arguments;
                        if (timer) {
                            clearTimeout(timer);
                        } else {
                            evt.type = 'scrollstart';
                            //jQuery.event.handle.apply(_self, _args);//   jQuery 1.9以下的版本用这个handle
                            jQuery.event.dispatch.apply(_self, _args);//   jQuery 1.9以上的版本用这个dispatch
                        }
                        timer = setTimeout(function() {
                            timer = null;
                        }, special.scrollstop.latency);
                    };
                $(this).bind('scroll', handler).data(uid1, handler);
            },
            teardown: function() {
                $(this).unbind('scroll', $(this).data(uid1));
            }
        };
        special.scrollstop = {
            latency: 1000,
            setup: function() {
                var timer,
                    handler = function(evt) {
                        var _self = this,
                            _args = arguments;
                        if (timer) {
                            clearTimeout(timer);
                        }
                        timer = setTimeout(function() {
                            timer = null;
                            evt.type = 'scrollstop';
                            //jQuery.event.handle.apply(_self, _args);
                            jQuery.event.dispatch.apply(_self, _args);
                        }, special.scrollstop.latency);
                    };
                $(this).bind('scroll', handler).data(uid2, handler);
            },
            teardown: function() {
                $(this).unbind('scroll', $(this).data(uid2));
            }
        };
    })();
    (function() {
	    $(window).bind('scrollstart', function() {
	        $("#rightFixed").animate({right:"-50px",opacity:'.4'});
	    });
	    $(window).bind('scrollstop', function(e) {
	        $("#rightFixed").animate({right:"-40px",opacity:'.7'},1000).stop().animate({right:"0",opacity:'1'},500);
	    });
    })();
	//屏幕中间下拉菜单【左右移动】代码结束

    require(['core', 'tpl'], function (core, tpl) {
        core.json('shop/index', {}, function (json) {
            var result = json.result;
            $('#container').html(tpl('tpl_index', result));
            $('.nav').click(function () {
                $('.nav').removeClass('navon');
                $(this).addClass('navon');
                var op = $(this).data('op');
                if (op == 'all') {
                    location.href = core.getUrl('shop/list');
                } else if (op == 'discount') {
                    location.href = core.getUrl('shop/list', {isdiscount: 1});
                } else if (op == 'notice') {
                    location.href = core.getUrl('shop/notice');
                }
            });

            if (result.advs.length > 0) {
            //   $('.banner').height($('.main_image').find('img').height());
                require(['jquery','jquery.touchslider','swipe'], function ($) {
                    new Swipe($('#banner')[0], {
						speed:300,
						auto:4000,
						callback: function(){

			            $(".flicking_con  .inner  a").removeClass("on").eq(this.index).addClass("on");
					}
				  });
                })
            }


            /*
            function getGoods(type) {

                core.json('shop/index', {'op': 'goods', type:type ,page: page}, function (gjson) {
                    var result = gjson.result;
                    if (result.status == 0) {
                        core.message('服务器内部错误', core.getUrl('shop'), 'error');
                        return; }
                    stop = true;
                    // $('.index_loading').remove();

                    $('#goods_container1,#goods_container2,#goods_container3,#goods_container4,#goods_container5').append(tpl('tpl_goods_list', result));
		    		$('.good img').each(function(){ $(this).height('');});
                    $('.good').unbind('click').click(function(){ location.href = core.getUrl('shop/detail',{id:$(this).data('goodsid') }); })
                    if (result.goods.length < result.pagesize && scrolling) {
                        $('#goods_container1,#goods_container2,#goods_container3,#goods_container4,#goods_container5').append('<div class="index_loading">已经加载全部商品</div>');
                        setTimeout(function(){ $('.index_loading').hide(); },1000);
                        loaded = true;
                        $(window).scroll = null;
                    	return; }
                    $(window).scroll(function () {
                        if (loaded) { return; }
                        totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
                        if ($(document).height() <= totalheight) {
                            if (stop == true) {
                                stop = false;scrolling=true;
                                $('#goods_container1,#goods_container2,#goods_container3,#goods_container4,#goods_container5').append('<div class="index_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载更多商品</div>');
                                page++;
                                getGoods('display');
                            }
                        }
                    });
                });
            }

            */

            window.getGoods = function(type) {

            	if(!type) type=1;

                core.json('shop/index', {'op': 'goods', type:type ,page: page[type]}, function (gjson) {
                    var result = gjson.result;
                    if (result.status == 0) {
                        core.message('服务器内部错误', core.getUrl('shop'), 'error');
                        return; }
                    stop[type] = true;
                    // $('.index_loading').remove();

                    $('#goods_container'+type).append(tpl('tpl_goods_list', result));
		    		$('.good img').each(function(){ $(this).height('');});
                    $('.good').unbind('click').click(function(){ location.href = core.getUrl('shop/detail',{id:$(this).data('goodsid') }); });
                    if (!result.goods||result.goods.length < result.pagesize && scrolling[type]) {
                        $('#goods_container'+type).append('<div class="index_loading">已经加载全部商品</div>');
                        setTimeout(function(){ $('.index_loading').hide(); },1000);
                        loaded[type] = true;
                        $(window).scroll = null;
                    	return;
                    }
                    $(window).unbind('scroll');
                    $(window).scroll(function () {
                        if (loaded[type]) { return; }
                        totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
                        if ($(document).height() <= totalheight) {
                            if (stop[type] == true) {
                                stop[type] = false;scrolling[type]=true;
                                $('#goods_container'+type).append('<div class="index_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载更多商品</div>');
                                page[type]++;
                                getGoods(type);
                            }
                        }
                    });
                });
            }

            $('.search').click(function(){
                $(".search1").animate({bottom:"0px"},100);
                $('#keywords').unbind('keyup').keyup(function(){
                	var keywords = $.trim( $(this).val());
                	if(keywords==''){
                    	$('#search_container').html("");
                    	return; }
                	core.json('shop/util',{op:'search',keywords:keywords }, function (json) {
                        var result = json.result;
                        if(result.list.length>0){ $('#search_container').html(tpl('tpl_search_list',result)); }
                        else{ $('#search_container').html(""); }
                     }, true);
            	});
            	$('.search1 .sub1').unbind('click').click(function(){
                    	var keywords = $.trim( $('#keywords').val());
                    	var url = core.getUrl('shop/list',{keywords:keywords});
                    	location.href=  url; });
            	$('.search1 .home1').unbind('click').click(function(){
                    $(".search1").animate({bottom:"-100%"},100); });
        	});
            getGoods(1);
        }, true);
    });
</script>

<!--屏幕中间【点击显示】下拉菜单代码-->
<script language="javascript" src="../addons/sz_yi/template/mobile/style1/shop/hqs/hqs_otherinfo.js"></script>
<!--屏幕中间【点击显示】下拉菜单代码结束-->

<?php  $show_footer=true;$footer_current ='first'?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>