<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<!-- saved from url=(0077)http://wx.juwu168.com/app/index.php?i=35&c=entry&do=info&m=tj_business&id=648 -->
<html lang="en" class="am-touch js cssanimations">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<script src="../addons/sz_yi/template/mobile/default/member/merch/js/jquery.js"></script>
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
		<title><?php  echo $merch['merchaname'];?></title>

		<link href="../addons/sz_yi/template/mobile/default/member/merch/css/amazeui.min.css" rel="stylesheet">
		<script src="../addons/sz_yi/template/mobile/default/member/merch/js/amazeui.min.js"></script>
		<script src="../addons/sz_yi/template/mobile/default/member/merch/js/timeago.js"></script>
		<script src="../addons/sz_yi/template/mobile/default/member/merch/js/layer.js"></script>
		<link rel="stylesheet" href="../addons/sz_yi/template/mobile/default/member/merch/css/layer.css" id="layuicss-skinlayercss">
		<style>
			html,
			body,
			ul,
			li,
			ol,
			dl,
			dd,
			dt,
			p,
			h1,
			h2,
			h3,
			h4,
			h5,
			h6,
			form,
			fieldset,
			legend,
			img {
				margin: 0;
				padding: 0;
			}
			
			.am-btn img {
				position: fixed;
				bottom: 50px;
				right: 10px;
				z-index: 100;
				width: 65px;
				height: 65px;
			}
			
			.am-btn2 img {
				position: fixed;
				bottom: 120px;
				right: 16px;
				z-index: 100;
				border-radius: 55px;
				width: 55px;
				height: 55px;
			}
			
			.pay {
				float: right;
				background-color: #2bb8aa;
				color: white;
				font-size: 12px;
				padding: 2px 5px;
				border-radius: 4px;
				margin-right: 10px;
				margin-top: -22px;
			}
			
			.clear {
				clear: both
			}
			
			.phone {
				float: right;
				line-height: 25px;
				margin-top: 5px;
				padding: 0px 0px 0px 12px;
				margin-right: 10px;
				border-left: 1px solid #eee
			}
			
			.ppp img {
				width: 96%;
				height: auto;
			}
			
			.goods_list {
				width: 100%;
				background-color: #ffffff;
			}
			
			.goods_con {
				width: 100%;
				padding: 2% 3%;
				overflow: hidden;
				position: relative;
				font-size: 12px;
			}
			
			.goods_list_img {
				width: 100%;
			}
			
			.goods_list_img img {
				width: 70px;
				height: 70px;
				float: left;
			}
			
			.goods_list_con {
				float: left;
				padding-left: 3%;
			}
			
			.goods_list_con p {
				padding: 1% 0;
			}
			
			.goods_list_con p:first-of-type {
				font-size: 16px;
			}
			
			.goods_list_btn {
				float: right;
				width: 60px;
				height: 25px;
				text-align: center;
				line-height: 25px;
				color: #fff;
				background-color: #32b4a8;
				border-radius: 6px;
				position: absolute;
				bottom: 6%;
				right: 2%;
			}
		</style>
		<script type="text/javascript">
			$(function() {
				$('body').timeago();
			});
		</script>

	</head>

	<body style="margin: 0px;">
		<!--详情页顶部商家主图-->
		<div>
			<div><img src="<?php  echo tomedia($merch['img'])?>" width="100%" height="180px"></div>
			<div align="center" style="margin-top:-45px;"><img src="<?php  echo tomedia($merch['logo'])?>" width="90px" height="90px" style="border:3px solid #2bb8aa;border-radius:90px;"></div>
		</div>
		<!--详情页商家滚动公告-->
		<div style="border-bottom: 8px solid #eee;line-height:20px;color:#2bb8aa;font-size:14px;">
			<span>
		<marquee scrollamount="3" width="100%">
			<?php  echo $merch['title'];?>	</marquee>
	</span>
		</div>
		<!--商家标题区域-->
		<div style="border-bottom:1px solid #eee;">
			<div style="margin-left: 10px;margin-top:5px;"><?php  echo $merch['merchname'];?></div>
			<div style="margin-left: 10px;color:#999; font-size:12px;margin-bottom: 5px;">营业时间：<?php  echo $merch['hours'];?>&nbsp;&nbsp;&nbsp;<?php  echo $merch['browse'];?>人浏览&nbsp;人均：¥<?php  echo $merch['average'];?></div>

		</div>
		<!--商家地址，一键拨号-->
		<div style="line-height: 35px;border-bottom:10px solid #eee;font-size:14px;">
			<span style="margin-left:5px;"><img src="../addons/sz_yi/template/mobile/default/member/merch/img/dizhi.png" width="15px" height="15px"></span>
			<span onclick="dili()">	<?php  echo $merch['address'];?></span>
			<a href="tel:<?php  echo $merch['mobile'];?>"><span class="phone"><img src="../addons/sz_yi/template/mobile/default/member/merch/img/phone.png" width="20px" height="20px"></span></a>
		</div>
		<div style="border-top:10px solid #eee;">
			<div style="line-height: 30px;height: 30px;">
				<span style="font-size:14px;margin-left: 10px;">在售商品</span>
				<a href="<?php  echo $this->createMobileUrl('shop/index')?>"><span style="font-size:12px;float: right;margin-right:10px;color: #999;">更多&gt;</span></a>
			</div>
			<div class="clear"></div>
			<div style="border-bottom: 1px solid #eee"></div>
			<!-- 商品部分 -->
			<div class="goods_list">
				<?php  if(!empty($goods)) { ?>
					<?php  if(is_array($goods)) { foreach($goods as $k => $v) { ?>
						<a href="<?php  echo $this->createMobileUrl('shop/detail')?>&id=<?php  echo $v['id'];?>">
							<div class="goods_con">
								<div class="goods_list_img">
									<img src="<?php  echo $v['thumb'];?>" />
								</div>
								<div class="goods_list_con">
									<p style="color: #5d5d5d;"><?php  echo $v['title'];?></p>
									<p style="color: #46c2b7;">优惠价：￥<?php  echo $v['marketprice'];?> &nbsp;&nbsp;<span style="color: #989898;">门店价：￥<?php  echo $v['productprice'];?></span></p>
									<p style="color: #989898;">
										<span>已售：<?php  echo $v['sales'];?></span>
									</p>
								</div>
							</div>
						</a>
					<?php  } } ?>
				<?php  } else { ?>
					<span style="font-size:14px;margin-left: 10px;">该店家暂时没有商品</span>
				<?php  } ?>
			</div>
	
		</div>
		<div style="border-bottom: 15px solid #eee;"></div>
		<!--商家图文详情介绍-->
		<div class="" style="width:100%;padding-bottom: 20%;">

			<?php  echo $merch['details'];?>
		</div>
		<!-- <div style="border-bottom: 10px solid #eee;"></div> -->

		<!--用户评论区域-->
		<!--
<div id="content" style="margin-bottom:40px;">
	<div style="border-bottom: 1px solid #eee;line-height:35px;margin-left: 10px;">用户评价</div>
		<div style="border-bottom:1px solid #eee;">
		<div style="margin:10px;">
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/132.jpg" style="width:40px;height: 40px;border-radius:40px;">
		</div>
		<div style="margin-left: 60px;font-size:14px;margin-top: -45px;">
			<span>大足商城-小李18623515045</span>
			<span style="float:right;font-size:12px;color:#666;margin-right:10px;">2017-10-30,14:21:38</span>
		</div>
		<div class="xingxing" style="margin-left:60px;font-size:13px;line-height: 35px;">
			<span>评分</span>
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/xx5.png" width="90px">		</div>
		
		<div style="font-size:14px;margin-left:60px;margin-right:10px;">
			<span>bbb</span><br>
					</div>
	</div>
		<div style="border-bottom:1px solid #eee;">
		<div style="margin:10px;">
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/132(1).jpg" style="width:40px;height: 40px;border-radius:40px;">
		</div>
		<div style="margin-left: 60px;font-size:14px;margin-top: -45px;">
			<span>台亮——天睿电商</span>
			<span style="float:right;font-size:12px;color:#666;margin-right:10px;">2017-08-11,07:14:38</span>
		</div>
		<div class="xingxing" style="margin-left:60px;font-size:13px;line-height: 35px;">
			<span>评分</span>
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/xx5.png" width="90px">		</div>
		
		<div style="font-size:14px;margin-left:60px;margin-right:10px;">
			<span>好</span><br>
					</div>
	</div>
		<div style="border-bottom:1px solid #eee;">
		<div style="margin:10px;">
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/132(2)" style="width:40px;height: 40px;border-radius:40px;">
		</div>
		<div style="margin-left: 60px;font-size:14px;margin-top: -45px;">
			<span>同城乐享异业联盟</span>
			<span style="float:right;font-size:12px;color:#666;margin-right:10px;">2017-08-08,20:32:31</span>
		</div>
		<div class="xingxing" style="margin-left:60px;font-size:13px;line-height: 35px;">
			<span>评分</span>
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/xx5.png" width="90px">		</div>
		
		<div style="font-size:14px;margin-left:60px;margin-right:10px;">
			<span>大家好</span><br>
					</div>
	</div>
		<div style="border-bottom:1px solid #eee;">
		<div style="margin:10px;">
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/132(3).jpg" style="width:40px;height: 40px;border-radius:40px;">
		</div>
		<div style="margin-left: 60px;font-size:14px;margin-top: -45px;">
			<span>A黑马-软件开发-文创体验</span>
			<span style="float:right;font-size:12px;color:#666;margin-right:10px;">2017-07-30,01:39:58</span>
		</div>
		<div class="xingxing" style="margin-left:60px;font-size:13px;line-height: 35px;">
			<span>评分</span>
			<img src="../addons/sz_yi/template/mobile/default/member/merch/img/xx5.png" width="90px">		</div>
	
		<div style="font-size:14px;margin-left:60px;margin-right:10px;">
			<span>1111</span><br>
					</div>
	</div>
	</div>
 -->

		<div style="line-height:50px; position:fixed; bottom:-1px; width:100%;">
			<div style="float:left; text-align:center; width:15%;background-color:white;border-top:1px solid #eee;border-right:1px solid #eee" onclick="url()">首页</div>
			<div class="like" style="float:left; text-align:center; width:25%;background-color:white;border-top:1px solid #eee;border-right:1px solid #eee"><?php  if($merch['isfavorite']) { ?>取消收藏<?php  } else { ?>收藏<?php  } ?></div>
			<div style="float:right; background:#2bb8aa;color:white; width:60%; text-align:center;" onclick="dili()">一键导航</div>
		</div>
		<input type="text" id="sj_id" value="648" style="display:none">
		<!-- <div onclick="location.href=&#39;./index.php?i=35&amp;c=entry&amp;action=pinglun&amp;sj_id=648&amp;do=Info&amp;m=tj_business&#39;" class="am-btn" style="margin-top:-50px;">
	<img src="../addons/sz_yi/template/mobile/default/member/merch/img/publish-detail-icon.png">
</div> -->

		<div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
			<div class="am-modal-dialog">
				<div class="am-modal-hd">发表评论
					<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close="">×</a>
				</div>
				<div class="am-modal-bd">
					<form class="am-form" name="fabu" id="fabu" action="http://wx.juwu168.com/app/index.php?i=35&amp;c=entry&amp;action=fabu&amp;id=648&amp;do=Info&amp;m=tj_business" method="post">
						<div class="am-form-group">
							<textarea class="" rows="3" id="doc-ta-1" name="info"></textarea>
							<input type="text" name="sj_id" value="648" style="display:none">
						</div>
						<div style="float:right;margin-top:-5px;margin-bottom:3px;">
							<a onclick="tijiao()" class="am-btn am-btn-primary  am-btn-xs">发布</a>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script>

			$(function() {
				var $modal = $('#your-modal');

				$modal.siblings('.am-btn1').on('click', function(e) {
					var $target = $(e.target);
					if(($target).hasClass('js-modal-open')) {
						$modal.modal();
					} else if(($target).hasClass('js-modal-close')) {
						$modal.modal('close');
					} else {
						$modal.modal('toggle');
					}
				});
			});
		</script>

		<script>
			function dili() {
				var lat = <?php  echo $merch['lat'];?>;
				var lng = <?php  echo $merch['lng'];?>;
				var url = 'http://apis.map.qq.com/uri/v1/geocoder?coord=' + lat + ',' + lng + '&referer=WeChat';

				window.location.href = url;
			}

			function url() {

				var url = "<?php  echo $this->createMobileUrl('member/merch')?>";
				window.location.href = url;
			}
		</script>

		<script>
            $('.like').click(function(){
                var self = $(this);			//收藏
				$.post(
				    "<?php  echo $this->createMobileUrl('member/merch')?>",
					{op:'set',sid:'<?php  echo $uid;?>'},
					function (ret) {	    
                        if(ret.status==1){
                            if(ret.result.isfavorite){
                                self.html('取消收藏');
                            }
                            else{
                                self.html('收藏');
                            }
                        }
                    }
                    ,'json');
//                require(['core'],function(core){
//                    core.json('member/merch',{op:'set', sid:'<?php  echo $_GPC['id'];?>'},function(ret){
//                        if(ret.status==1){
//                            if(ret.result.isfavorite){
//                                self.html('取消收藏');
//                            }
//                            else{
//                                self.html('收藏');
//                            }
//                        }
//                        else{
//                            core.tip.show('操作失败');
//                        }
//                    });
//                },false,true);
            });
		</script>

		<script src="../addons/sz_yi/template/mobile/default/member/merch/js/index.js" type="text/javascript"></script>