<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
	body{
		background: #fff;
	}
	.page_topbar{
	   background: #f00605;
	}
	.page_topbar a.back{
	    font-family:serif,"PingFang SC", Helvetica, "Helvetica Neue", "微软雅黑", Tahoma, Arial, sans-serif;
	    font-weight: bold;
	    padding-right: 15px;
	    color: #fff;
	}
	.page_topbar .title{
	    color: #fff;
	}
	.page_topbar .home {
	    position: absolute;
	    right: 15px;
	    top: 0;
	    color: #fff;
	    font-size: 14px;
	    text-decoration: none;
	}
	.adcate-container{
		-webkit-box-shadow:0 0 8px rgba(34,23,20,.5);
		box-shadow:0 0 8px rgba(34,23,20,.5);
	}
	.adcate-nav-box .adcate_nav{
		font-size: 14px;cursor: pointer;
	}
	.adcate-nav-box .adcate_nav.adcate_navon{
		color: #f00605;
		border-bottom: 1px solid #f00605;
	}
	.ad-container{
		margin-top: 10px;
	}
	.adcate-entry-box,
	.adcate-out-box{
		font-size: 24px;
		width: 30px;
		float: right;
		height: 40px;
    	line-height: 40px;
    	text-align: center;
	}
	.has-ad-box .ad-box{
		width: 100%;
		padding-top: -webkit-calc(1084 / 750 * 100%);
    	padding-top: -moz-calc(1084 / 750 * 100%);
    	padding-top: calc(1084 / 750 * 100%);
	}
	.no-ad-box{
		font-size: 12px;
		color: #666;
		text-align: center;
		padding-top: -webkit-calc(1084 / 750 * 100%);
    	padding-top: -moz-calc(1084 / 750 * 100%);
    	padding-top: calc(1084 / 750 * 100%);
    	position: relative;
	}
	.for-vertical-center{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%,-50%);
	}
	.no-ad-box .show-sincer{
		font-size: 14px;
	}
	.adcate-operate-box{
		height: 100%;
	    width: 100%;
	    background: #fff;
	    position: fixed;
	    left: 0;
	    top: -100%;
	    z-index: 999;
	    overflow-y: auto;
	    padding-bottom: 50px;
	}
	.adcate-list-operate-box{
		padding: 0 10px;
		-webkit-box-shadow:0 0 8px rgba(34,23,20,.5);
		box-shadow:0 0 8px rgba(34,23,20,.5);
		/*position: relative;*/
	}
	.adcate-list-operate-tag{
		font-size: 14px;
		float: left;
		height: 40px;
    	line-height: 40px;
	}
	.adcate-del-box{
		float: right;
		height: 40px;
		line-height: 40px;
		margin-right: 10px;
	}
	.adcate-del-btn{
		font-size: 14px;
		border: 1px solid #f00605;
		line-height: 1;
		border-radius: 5px;
		padding: 5px 15px;
		background: transparent;
	}
	.adcate-del-btn:focus{
		outline: none;
	}
	.adcate-list-ul{
		margin-top: 15px;
	}
	.adcate-list-ul .adcate_item{
		font-size: 14px;
		width: calc((100% - 60px) / 3);
		margin-left: 15px;
		float: left;
		padding: 10px;
		text-align: center;
		position: relative;
	}
	.adcate-list-ul .adcate_item .adcate_item-btn{
		background: transparent;
		width: 100%;
		height: 100%;
		border: 1px solid #999;
		border-radius: 5px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		padding-top: 5px;
		padding-bottom: 5px;
	}
	.adcate-list-ul .adcate_item .del-icon-box{
		display: none;
	    position: absolute;
	    right: 10px;
	    top: 0px;
	    color: #ccc;
	    font-weight: normal;
	    z-index: 11;
	    margin-top:0;
	    margin-left:0;
	    width:24px;
	    height:24px;
	}
	.adcate-list-ul .adcate_item .del-icon-box:before{
	    content:'';
	    position:absolute;
	    top:10px;
	    width:20px;
	    height:2px;
	    background-color: currentColor;
	    -webkit-transform:rotate(-45deg);
	    transform:rotate(-45deg);
	}
	.adcate-list-ul .adcate_item .del-icon-box:after{
	    content: '';
	    position: absolute;
	    top: 10px;
	    width: 20px;
	    height: 2px;
	    background-color: currentColor;
	    -webkit-transform: rotate(45deg);
	    transform: rotate(45deg);
	}
	.separate-box{
		font-size: 12px;
		color: #666;
		text-align: center;
		padding: 10px 0;
		position: relative;
	}
	.separate-box:before{
		content: "";
		display: block;
		width: calc((100% - 130px) / 2);
		height: 1px;
		background: #eee;
		position: absolute;
		left: 0;
		top: 50%;
		transform: translateY(-50%);
	}
	.separate-box:after{
		content: "";
		display: block;
		width: calc((100% - 130px) / 2);
		height: 1px;
		background: #eee;
		position: absolute;
		right: 0;
		top: 50%;
		transform: translateY(-50%);
	}
	.adcate-del-list-ul{
		margin-top: 0;
	}
	.envelope-link{
		color: #fff175;/*#fff;*/
		text-decoration: none;
	}
	.envelope-link:hover, .envelope-link:focus {
	    color: #fff175;/*#fff;*/
		text-decoration: none;
	}
</style>
<style type="text/css">
.diseaseMenu {width: calc(100% - 30px);float: left;height:80px;line-height:80px;background-color:#FFF;-moz-box-shadow:0 0 8px rgba(34,23,20,.5);overflow:hidden;}
.menuScroll1_lists {width:100%;height:80px;overflow:hidden;}
.menuScroll1_lists li {float:left;height:80px;line-height:80px;padding:0 10px;}
.menuScroll1_lists li a {display:block;width:100%;height:100%;font-size:30px;color:#2e3642;}
.menuScroll1_lists li a span {display:block;padding:0 10px;height:100%;border-bottom:6px solid #fff}
.menuScroll1_lists li.active a span {border-bottom:6px solid #d70a50;}
@media screen and (min-width:320px) and (max-width:900px) {
	.diseaseMenu {height:40px;line-height:40px;}
	.menuScroll1_lists {height:40px;}
	.menuScroll1_lists li {height:40px;line-height:40px;padding:0 20px;}
	.menuScroll1_lists li a {font-size:16px;}
	.menuScroll1_lists li a span {padding:0 5px;border-bottom:3px solid #fff}
	.menuScroll1_lists li.active a span {border-bottom:3px solid #d70a50}
}
</style>
<script src="//cdn.bootcss.com/iScroll/5.1.3/iscroll.js"></script>
<script src="//cdn.bootcss.com/jquery.touchswipe/1.5.1/jquery.touchswipe.min.js"></script> <!-- tag不起作用 -->
<!-- <script src="cdn.bootcss.com/jquery.touchswipe/1.6.17/jquery.touchSwipe.min.js"></script> -->
<!-- 分类查看广告 -->
<title>看广告 收红包</title>
<div id="container">
	<div class="page_topbar">
        <a href="javascript:;" class="back" onclick="history.go(-1)"><i class="fa fa-angle-left"></i></a>
        <div class="title">看广告&nbsp;&nbsp;<a href="<?php  echo $this->createMobileUrl('barter/bonus',array('op'=>'bonusIndex','type'=>'1'))?>" class="envelope-link">收红包</a></div>
        <a href="javascript:;" class="home">进入</a>
    </div>
    <div class="adcate-container clearfloat">
    	<div class="diseaseMenu menus relative" id="diseaseMenu">
			<div class="menuScroll1 menuScroll absolute adcate-nav-box" id="menuScroll">
				<!-- 动态标签获取没有删除的广告类型 -->
				<ul class="menuScroll1_lists clearfix">
					<?php  if(is_array($adcate)) { foreach($adcate as $k => $v) { ?>
						<!-- {<if $v['title'] == '推荐'>} -->
						<?php  if($k==0) { ?>
							<li class="adcate_nav adcate_navon" data-adcate='<?php  echo $v["id"];?>' ><?php  echo $v["title"];?></li>
						<?php  } else { ?> 		  
							<li class="adcate_nav" data-adcate='<?php  echo $v["id"];?>' ><?php  echo $v["title"];?></li>
						<?php  } ?>
					<?php  } } ?>

					<?php  if(false) { ?>
					<li class="adcate_nav adcate_navon" data-adcate='2'>附近</li>
					<li class="adcate_nav" data-adcate='3'>最新</li>
					<li class="adcate_nav" data-adcate='4'>生活服务111111111</li>
					<li class="adcate_nav" data-adcate='5'>餐饮美食qqqqqq</li>
					<li class="adcate_nav" data-adcate='6'>休闲娱乐</li>
					<li class="adcate_nav" data-adcate='7'>教育培训1111111111</li>
					<li class="adcate_nav" data-adcate='8'>旅游度假</li>
					<li class="adcate_nav" data-adcate='9'>家居服饰</li>
					<li class="adcate_nav" data-adcate='10'>交通运输1222222</li>
					<li class="adcate_nav" data-adcate='11'>高新科技</li>
					<li class="adcate_nav" data-adcate='12'>商务服务</li>
					<li class="adcate_nav" data-adcate='13'>广告宣传222222222</li>
					<li class="adcate_nav" data-adcate='14'>公益礼品</li>
					<li class="adcate_nav" data-adcate='15'>医保福利</li>
					<li class="adcate_nav" data-adcate='16'>电子电器</li>
					<li class="adcate_nav" data-adcate='17'>城建房产</li>
					<li class="adcate_nav" data-adcate='18'>农林牧渔222222</li>
					<li class="adcate_nav" data-adcate='19'>化工制造</li>
					<li class="adcate_nav" data-adcate='20'>机械制造</li>
					<li class="adcate_nav" data-adcate='21'>政府团队</li>
					<?php  } ?>
				</ul>
			</div>
		</div>
		<div class="adcate-entry-box">
			<i class="fa fa-angle-down ad-fold-icon" aria-hidden="true"></i>
		</div>
    </div>
    <div class="adcate-operate-box">
        <!-- <div class="page_topbar">
            <a href="javascript:;" class="back" onclick="history.go(-1)"><i class="fa fa-angle-left"></i></a>
            <div class="title">分类查看广告</div>
        </div> -->
        
    </div>
	<div id="ad-container" class="ad-container"></div>
</div>
<!-- 每一次点击向下箭头按钮(分类操作按钮) 模板动态获取 -->
<script id="tpl_adcate_operate" type="text/html">
	<div class='adcate-list-operate-box clearfloat'>
        <div class="adcate-list-operate-tag">广告分类</div>
        <div class="adcate-out-box">
			<i class="fa fa-angle-up ad-fold-icon" aria-hidden="true"></i>
		</div>
		<div class="adcate-del-box">
			<button type="button" class="adcate-del-btn">删除</button><!-- 点击删除按钮后,添加类名adcate-complete-btn,按钮文本变成"完成" -->
			<input type="hidden" class="adcate-del-input" name="adcate_del" value="">
		</div>
    </div>
    <!-- 没有删除的广告类型 在ul里面循环 带有删除按钮 前三个按钮(推荐 附近 最新)不可删除 操作面板-->
    <div class="adcate-list">	
    	<ul class="adcate-list-ul clearfloat" id="adcate-list-showed-ul">
			<%each enable as v%>
			<li class="adcate_item" data-adcate='<%v.id%>'>
				<button type="button" class="adcate_item-btn"><%v.title%></button>
				<%if v.title == '推荐' || v.title == '附近' || v.title == '最新'%>
				<%else%> 	 		 
					<span class="del-icon-box"></span>
				<%/if%>
			</li>
			<%/each%>
			<?php  if(false) { ?>
			<li class="adcate_item" data-adcate='2'>
				<button type="button" class="adcate_item-btn">附近</button>
				<!-- <span class="del-icon-box"></span> -->
			</li>
			<li class="adcate_item" data-adcate='3'>
				<button type="button" class="adcate_item-btn">最新</button>
				<!-- <span class="del-icon-box"></span> -->
			</li>
			<li class="adcate_item" data-adcate='4'>
				<button type="button" class="adcate_item-btn">生活服务</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='5'>
				<button type="button" class="adcate_item-btn">餐饮美食</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='6'>
				<button type="button" class="adcate_item-btn">休闲娱乐</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='7'>
				<button type="button" class="adcate_item-btn">教育培训</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='8'>
				<button type="button" class="adcate_item-btn">旅游度假</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='9'>
				<button type="button" class="adcate_item-btn">家居服饰</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='10'>
				<button type="button" class="adcate_item-btn">交通运输</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='11'>
				<button type="button" class="adcate_item-btn">高新科技</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='12'>
				<button type="button" class="adcate_item-btn">商务服务</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='13'>
				<button type="button" class="adcate_item-btn">广告宣传</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='14'>
				<button type="button" class="adcate_item-btn">公益礼品</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='15'>
				<button type="button" class="adcate_item-btn">医保福利</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='16'>
				<button type="button" class="adcate_item-btn">电子电器</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='17'>
				<button type="button" class="adcate_item-btn">城建房产</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='18'>
				<button type="button" class="adcate_item-btn">农林牧渔</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='19'>
				<button type="button" class="adcate_item-btn">化工制造</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='20'>
				<button type="button" class="adcate_item-btn">机械制造</button>
				<span class="del-icon-box"></span>
			</li>
			<li class="adcate_item" data-adcate='21'>
				<button type="button" class="adcate_item-btn">政府团队</button>
				<span class="del-icon-box"></span>
			</li>
			<?php  } ?>
		</ul>
		<div class="separate-box"><span>点击添加更多广告分类</span></div>
		<!-- 已删除的广告类型 在ul里面循环 没有带删除按钮-->
		<ul class="adcate-list-ul adcate-del-list-ul clearfloat">
			<%each destory as v%>
			<li class="adcate_item" data-adcate='<%v.id%>'>
				<button type="button" class="adcate_item-btn adcate-deled-item"><%v.title%></button>
			</li>
			<%/each%>
		</ul>
    </div>
</script>
<script id="tpl_has_ad" type="text/html">
	<div class="has-ad-box children-ad"><!-- background-size: cover; -->
		<div class="ad-box" data-link="<%ad.url%>" style="background: url('<%ad.thumb[0]%>') no-repeat center; background-size: contain;"></div>	  
	</div>
</script>
<script id="tpl_no_ad" type="text/html">
	<div class="no-ad-box children-ad" style="background: url('../addons/sz_yi/template/mobile/style1/static/images/no_ad.jpg') no-repeat center; background-size: contain;">
		<div class="for-vertical-center" style="display: none;">
			<div class="show-sincer">抱歉</div>
			<div class="no-ad-tips">本类别里暂时没有广告了</div>
			<div class="no-ad-tips">请选择其他分类呦~</div>
		</div>
	</div>
</script>
<script language="javascript">
	var current_adcate = 1;

	require(['tpl', 'core'], function (tpl, core) {
		$(function() {



			var myScroll2=null;
			function menuInit(){
				var _menuScroll = $(".menuScroll");
				var _menuScroll_size = _menuScroll.find("li").length;
				//var liWidth = 0;
				var liWidth1 = 0;
				$(".menuScroll li").each(function(){
					//liWidth =$(this).outerWidth()*_menuScroll_size;//这一种只适用于长度一样的li
					liWidth1 += $(this).outerWidth();//这一种适用于长度不一样的li
				});
				_menuScroll.css({width:liWidth1+1+'px'});
//                3.22
//				myScroll2=new IScroll(".menus",{
//					ventPassthrough: true,
//					scrollX: true,
//					scrollY: false,
//					preventDefault:false,
//					snap: "li"
//				});
			}
			menuInit();
			$(window).resize(function(){
				menuInit();
			});
			getAd(current_adcate);

			$('.adcate_nav').unbind('click').click(function () {
//                alert($(this).data('adcate'))
				if(current_adcate == $(this).data('adcate')){
					return false;
				}
				current_adcate = $(this).data('adcate');
				$('.adcate_nav').removeClass('adcate_navon');
            	$(this).addClass('adcate_navon');
				getAd(current_adcate);
			});	
			//弹出删除排序 或者 添加排序 版面
            $('.adcate-entry-box').click(function(){
                //异步请求 填充模板
            	core.json('barter/ad', {ac:'getcate'}, function (json) {
            	// core.json('barter/bonus', {adcate:adcate}, function (json) {
					//json.result.status == 1 代表获取成功
					if(json.status == 1){
						$(".adcate-operate-box").html(tpl("tpl_adcate_operate",json.result));
						bindCateEvents();
						$(".adcate-operate-box").animate({top:"45px"},100);
		                $('.adcate-out-box').unbind('click').click(function(){
		                    $(".adcate-operate-box").animate({top:"-100%"},100);
		                    //$('.menuScroll1_lists .adcate_nav').first().click();//务必确保第一个元素是不可删除的
		                });
					}else{
						core.tip.show('获取失败');
					}
				}, true);
            });
            function bindCateEvents(){
				$(".adcate-del-btn").unbind('click').click(function(){
	            	if($(this).hasClass("adcate-complete-btn")){
		            	$(".del-icon-box").fadeOut(200);
		            	//删除请求改变分类是否删除标志 只有点击完成按钮才会真的删除成功
		            	var adcate_del_val = $(".adcate-del-input").val();//用逗号连接的字符串数据格式
		            	if($(".adcate-del-input").isEmpty()){
		            		$('.adcate-complete-btn').removeClass("adcate-complete-btn").text("删除");
		            		$(".adcate-del-list-ul").fadeIn(200);
		            		return;
		            	}
		            	core.json('barter/ad', {ac:'doad',adcate:adcate_del_val}, function (json) {
                            console.log(json);
							//json.result.status == 1 代表删除成功
							if(json.status == 1){
								core.tip.show('删除成功');
								$(".adcate-del-input").val("");
								var adcate_del_val_arr = adcate_del_val.split(',');
								$(".menuScroll1_lists .adcate_nav").each(function(){
				                    var des_cate = $(this).data("adcate");
				                    var mark = $.inArray(des_cate.toString(),adcate_del_val_arr);
				                    if(mark != -1){
				                        $(this).remove();
				                    }
				                });
				                menuInit();//重新初始化
				                $('.menuScroll1_lists .adcate_nav').first().click();//务必确保第一个元素是不可删除的
							}else{
								core.tip.show('删除失败');
							}
						}, true);
						$('.adcate-complete-btn').removeClass("adcate-complete-btn").text("删除");
						$(".adcate-del-list-ul").fadeIn(200);
	            	}else{
	            		$(".del-icon-box").fadeIn(200);
	            		$(".adcate-del-btn").addClass("adcate-complete-btn").text("完成");
	            		$(".adcate-del-list-ul").fadeOut(200);
	            	}
	            });
	            $(".del-icon-box").unbind('click').click(function(){
	            	var adcate = $(this).parent().data("adcate");
	            	$(this).parent().remove();
	            	var adcate_del_val = $(".adcate-del-input").val();
	            	if($(".adcate-del-input").isEmpty()){
	            		$(".adcate-del-input").val(adcate);
	            	}else{
	            		$(".adcate-del-input").val(adcate_del_val + "," + adcate);
	            	}
	            	var adcate_text = $(this).parent().find(".adcate_item-btn").text();
	            	var addhtml = '<li class="adcate_item" data-adcate="' + adcate + '"><button type="button" class="adcate_item-btn adcate-deled-item">' + adcate_text + '</button></li>';
	            	$(".adcate-del-list-ul").append(addhtml);
	            	bindCateEvents();
	            	//console.log("adcate:" + adcate);
	            });
	            //$(".adcate-del-list-ul .adcate_item-btn").unbind('click').click(function(){
	            $(".adcate-deled-item").unbind('click').click(function(){
	            	var o =$(this);
	            	var data_adcate = $(this).parent().data("adcate");
	            	var adcate_text = $(this).text();
	            	core.json('barter/ad', {op:'display',ac:'doad',what:'add',adcate:data_adcate}, function (json) {
						//json.result.status == 1 代表添加成功
						if(json.status == 1){
							var addhtml = '<li class="adcate_item" data-adcate="' + data_adcate + '"><button type="button" class="adcate_item-btn">' + adcate_text + '</button><span class="del-icon-box"></span></li>';
							$("#adcate-list-showed-ul").append(addhtml);
	            			bindCateEvents();
	            			var navhtml = '<li class="adcate_nav" data-adcate="' + data_adcate + '">' + adcate_text + '</li>';
	            			$(".menuScroll1_lists").append(navhtml);
	            			menuInit();//重新初始化
	            			$('.menuScroll1_lists .adcate_nav').first().click();//务必确保第一个元素是不可删除的
	            			//带上新的元素重新绑定事件
	            			$('.adcate_nav').unbind('click').click(function () {
								current_adcate = $(this).data('adcate');
								$('.adcate_nav').removeClass('adcate_navon');
				            	$(this).addClass('adcate_navon');
								getAd(current_adcate);
							});
	            			o.parent().remove();
						}else{
							core.tip.show('添加失败');
						}
					}, true);
	            });
            }
            function getAd(current_adcate){
				current_adcate=current_adcate == 1 ? 2 : current_adcate;
				core.json('barter/ad', {ac:'getad',adcate:current_adcate}, function (json) {
				 	console.log(json);
				 	//json.result.status == 1 代表有广告,其他无
				 	if(json.status == 1){
				 		$("#ad-container").html(tpl('tpl_has_ad',json.result));//json.result.ad 包含广告链接和封面
				    	$(".page_topbar .home").unbind('click').click(function(){
							//这里复制上广告链接
							if ($('.children-ad').hasClass('has-ad-box')) {
								var thislink=$('.children-ad').find('.ad-box').data('link');
								location.href=thislink;
							}
						});
				 	}else{
				 		$("#ad-container").html(tpl('tpl_no_ad'));
				 	}
				    swipeLR();
				}, true);
			}
			function swipeLR(){
				$("#ad-container").swipe( {
				    //Generic swipe handler for all directions
				    swipeLeft:function(event, direction, distance, duration, fingerCount, fingerData) {
				    	myScroll2.next();
						$('.adcate_navon').next().click();
				    },
				    swipeRight:function(event, direction, distance, duration, fingerCount, fingerData) {
				    	myScroll2.prev();
						$('.adcate_navon').prev().click();
				    },
				    //此版本tag不起作用
				    click:function(event,target){
				    	if($("#ad-container .children-ad").hasClass("has-ad-box")){
							var link = $("#ad-container .ad-box").data("link");
				  			location.href = link;
				    	}
					}
				});
			}
		});
    });

</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>