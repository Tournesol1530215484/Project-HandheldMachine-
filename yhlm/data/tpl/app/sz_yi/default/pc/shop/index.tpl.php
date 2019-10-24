<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title><?php  echo $this->yzShopSet['pctitle']?></title>
<meta content='<?php  echo $this->yzShopSet['pckeywords']?>' name='Keywords'>
<meta content='<?php  echo $this->yzShopSet['pcdesc']?>' name='Description'>



<link rel="stylesheet" type="text/css" href="../addons/sz_yi/static/css1/one.css">
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/static/css1/style.css">
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
		[class^="icon-"]:before, [class*=" icon-"]:before{
			font-family:inherit;
		}
    .main_top .logo{
        top:0;
    }
</style>
<div class="yh_header" id="yh_header">
	<div class="ye_header_top">
		<p>
			欢迎光临易货联盟商城！
			<strong class="phone red">热线:400-616-1519</strong>
			<span class="cart r-align">
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=cart&do=shop&m=sz_yi"><img src="../addons/sz_yi/static/img/cart.png">购物车<font class="red">0</font>商品</a>
			</span>
			<span class=" r-align">
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&eid=194&wxref=mp.weixin.qq.com#wechat_redirect" class="phone1">手机版</a>
			</span>
			<span class="behind  r-align">

				<strong><a href="http://jhzh66.com/web/index.php?c=user&a=login&" class="red">商家后台</a></strong>
			</span>
			<span class="r-align">
                  <?php  if($_COOKIE[__cookie_sz_yi_userid_.$_W['uniacid']]) { ?>
                	<a href="<?php  echo $this->createMobileUrl('order')?>">个人中心</a>
                   <?php  } else { ?>
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=register&do=member&m=sz_yi" class="regiest">注册</a>
				<font class="line">|</font>
				<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=login&do=member&m=sz_yi" class="login">登录</a>
			       <?php  } ?>
            </span>

		</p>

	</div>
</div>
<div class="main">
	<!-- 头部搜索框 -->
	<div class="main_top flex" style="width: 100%;">
		<!-- logo -->
		<div class="logo flex1 flex">
			<img  style="height: 63px;width: 206px;position: relative;left: 28px;" src="<?php  echo tomedia($set['shop']['pclogo'])?>">
			<!--<div class="fonts">-->
				<!--<h4><strong>易货联盟&lt;!&ndash; <font class="four" >抱团营销</font> &ndash;&gt;	</strong></h4>-->
				<!--<font style="font-size: 15px">WWW.JHZH66.COM</font>-->
			<!--</div>-->
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
	<!-- 商品分类标签 -->
	<div class="goods_kind ">
		<ul class="flex frist">
			<li class=" activeA"><a href="http://jhzh66.com/web/index.php?c=site&a=entry&method=one&p=bartact&do=plugin&m=sz_yi#">易货商品分类</a></li>
            <?php  if(is_array($set['shop']['hmenu_name'])) { foreach($set['shop']['hmenu_name'] as $k => $v) { ?>
            <li class="flex1 index"><a href="<?php  echo $set['shop']['hmenu_url'][$k];?>" style="font-size: 16px;font-weight: 600;color:#ff4200;"><?php  echo $v['name'];?></a>
                <ul id="introduce">
                    <?php  if(is_array($v['vlist'])) { foreach($v['vlist'] as $k => $val) { ?>
                    <li><a href="<?php  echo $val['url'];?>"> <?php  echo $val['name'];?></a></li>
                    <?php  } } ?>
                </ul>
            </li>
            <?php  } } ?>
            <!--<li class="flex1 index active"><a href="http://jhzh66.com/web/index.php?c=site&a=entry&method=one&p=bartact&do=plugin&m=sz_yi#">首页</a></li>-->
			<!--<li class="flex1 introduce"><a href="http://yh.jhzh66.com/page131">公司简介</a>-->
				<!--&lt;!&ndash; 二级菜单 父级class为子级菜单id&ndash;&gt;-->
				<!--<ul id="introduce">-->
					<!--<li><a href="http://yh.jhzh66.com/page131">公司简介</a></li>-->
					<!--<li><a href="http://yh.jhzh66.com/page131">企业文化</a></li>-->
					<!--<li><a href="http://yh.jhzh66.com/page131">团队风采</a> </li>-->
				<!--</ul>-->
			<!--</li>-->
			<!--<li class="flex1 goods"><a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=list&do=shop&m=sz_yi">易货商品</a></li>-->
			<!--<li class="flex1 activity"><a href="http://jhzh66.com/app/index.php?i=8&c=entry&method=activity&p=activity&m=sz_yi&mid=1175&do=plugin">各地活动</a></li>-->
			<!--<li class="flex1 mark"><a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=list&do=shop&m=sz_yi">购物商城</a></li>-->
			<!--<li class="flex1 news"><a href="http://yh.jhzh66.com/page134">新闻动态</a>-->
				<!--<ul id="news">-->
					<!--<li><a href="#">公司动态</a></li>-->
					<!--<li><a href="#">易货新品上架</a></li>-->
					<!--<li><a href="#">易货行业新闻</a> </li>-->
				<!--</ul>-->
			<!--</li>-->
			<!--<li class="flex1 check-in"><a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=article&aid=14&m=sz_yi&do=plugin">商家入驻</a></li>-->
			<!--<li class="flex1 join-in"><a href="http://yh.jhzh66.com/page152">加盟合作</a></li>-->
			<!--<li class="flex1 forum"><a href="https://www.weizan.cn/wx/close">换货论坛</a></li>-->
		</ul>
		<!-- 左侧菜单 -->
		<div class="second">
			<ul class="all">
                <?php  if(is_array($catelist)) { foreach($catelist as $key => $v) { ?>
				<li class="dianqi<?php  echo $key;?>"><a href="#"><?php  echo $v['name'];?></a></li>
				<?php  } } ?>
                <!--<li class="dianqi2"><a href="#">家用电器/电吹风</a></li>-->
				<!--<li><a href="#">美妆/化妆/asa</a> </li>-->
			</ul>
            <?php  if(is_array($catelists)) { foreach($catelists as $k => $v) { ?>
            <ul id="dianqi<?php  echo $k;?>" class="third">
                <?php  if(is_array($v['catelist'])) { foreach($v['catelist'] as $c) { ?>
                <li><a href="#"><?php  echo $c['name'];?></a></li>
                <?php  } } ?>
            </ul>
            <?php  } } ?>
			<!-- 展示 左侧二级菜单 class对应id-->
			<!--<ul id="dianqi" class="third">-->
				<!--<li><a href="#">家用电器</a></li>-->
				<!--<li><a href="#">家用电器/电吹风</a></li>-->
				<!--<li><a href="#">家用电器</a></li>-->
				<!--<li><a href="#">家用电器/电吹风</a></li>-->
				<!--<li><a href="#">家用电器</a></li>-->
				<!--<li><a href="#">家用电器/电吹风</a></li>-->
				<!--<li><a href="#">家用电器</a></li>-->
				<!--<li><a href="#">家用电器/电吹风</a></li>-->
			<!--</ul>-->
			<!--<ul id="dianqi2" class="third">-->
				<!--<li><a href="#">家用电器2</a></li>-->
				<!--<li><a href="#">家用电器/电吹风2</a></li>-->
				<!--<li><a href="#">家用电器2</a></li>-->
				<!--<li><a href="#">家用电器/电吹风2</a></li>-->
				<!--<li><a href="#">家用电器2</a></li>-->
				<!--<li><a href="#">家用电器/电吹风2</a></li>-->
				<!--<li><a href="#">家用电器2</a></li>-->
				<!--<li><a href="#">家用电器/电吹风2</a></li>-->
			<!--</ul>-->
		</div>
	</div>

	<!-- 中间 -->
	<div class="banner ">
		<!-- 轮播图 -->
		<div class="slideBox ">
			<div class="hd">
				<ul>
                    <?php  if(is_array($advlist)) { foreach($advlist as $v) { ?>
                    <li class=""></li>
                    <?php  } } ?>
                </ul>
			</div>
			<div class="bd">
				<ul>
					<?php  if(is_array($advlist)) { foreach($advlist as $v) { ?>
					<li><img src="<?php  echo tomedia($v['thumb_pc'])?>"></li>
                    <?php  } } ?>
                </ul>
			</div>
		</div>
		<!--右侧边栏  -->
		<div class="info">
			<div class="info-header">
				<img src="<?php  echo tomedia($set['shop']['pclogo'])?>">
				<p>Hi~欢迎来到易货联盟</p>
				<p><a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=login&do=member&m=sz_yi">登录</a>
                    &nbsp<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=register&do=member&m=sz_yi">注册</a></p>
				<div class="buttons">
					<span class="fuli"><a href="#">新人福利</a></span>
					<span class="plus"><a href="www.baidu.com">Plus会员</a></span>
				</div>
			</div>
			<div class="fast-news">
				<h5>新闻快报<span class="more"><a href="#">更多></a></span>	</h5>
				<ul>
					<li><span class="gonggao">公告</span>瑞士钟表品牌宝齐莱...</li>
					<li><span class="hot">活动</span>优选好货</li>
					<li><span class="gonggao">热门</span>C1驾照即将升级，...</li>
					<li><span class="gonggao">推荐</span>小米9透明版：12G...</li>
				</ul>
			</div>

			<div class="iconfonts-img">
				<ul>
                    <?php  if(is_array($rlist)) { foreach($rlist as $v) { ?>
                    <a href="<?php  echo $v['url'];?>">
                        <li>
                            <!--<span><img src="../addons/sz_yi/static/img/huafei.png"></span>-->
                            <span><img src="<?php  echo tomedia($v['img'])?>"></span>
                            <p><?php  echo $v['title'];?></p>
                        </li>
                    </a>
                    <?php  } } ?>
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/huafei.png"></span>-->
						<!--<p>话费</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/jipiao.png"></span>-->
						<!--<p>机票</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/jiudian.png"></span>-->
						<!--<p>酒店</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/youxi.png"></span>-->
						<!--<p>游戏</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/qiye.png"></span>-->
						<!--<p>企业购</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/jiayou.png"></span>-->
						<!--<p>加油卡</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/dianying.png"></span>-->
						<!--<p>电影票</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/huoche.png"></span>-->
						<!--<p>火车票</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/zhongchou.png"></span>-->
						<!--<p>众筹</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/licai.png"></span>-->
						<!--<p>理财</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/lipin.png"></span>-->
						<!--<p>礼品卡</p>-->
					<!--</li>-->
					<!--<li>-->
						<!--<span><img src="../addons/sz_yi/static/img/baitiao.png"></span>-->
						<!--<p>白条</p>-->
					<!--</li>-->
				</ul>
			</div>
		</div>
	</div>

	<!-- 1F商品 -->
    <?php  if(is_array($catelists)) { foreach($catelists as $k => $v) { ?>
	<div class="F1 goods-list" style="padding: 0;">
		<h1><?php  echo $v['rank'];?>F <?php  echo $v['name'];?><span><a href="#">更多</a></span></h1>

		<div class="left-side">
			<div class="left-side-top">
				<h2>精品推荐</h2>
				<p class="intro"><?php  echo $v['goods']['title'];?></p>
                <a href="<?php  echo $this->createMobileUrl('shop/detail',array('op'=>'display','id' => $v['goods']['id']));?>">
				<img src="<?php  echo tomedia($v['goods']['thumb'])?>">
                </a>
			</div>
			<ul class="ul-1">
                <?php  if(is_array($v['catelist'])) { foreach($v['catelist'] as $c) { ?>
				<li class="flex">
					<span class="flex1"><?php  echo $c['name'];?></span>
                    <span class="flex1"></span>
				</li>
                <?php  } } ?>
				<!--<li class="flex">-->
					<!--<span class="flex1">服装厂</span>-->

				<!--</li>-->
				<!--<li class="flex">-->
					<!--<span class="flex1">服装厂</span>-->
                    <!--<span class="flex1">服装厂</span>-->
				<!--</li>-->
			</ul>


		</div>
		<ul class="right-side">
            <?php  if(is_array($v['goodslist'])) { foreach($v['goodslist'] as $row) { ?>
			<li>
                <a href="<?php  echo $this->createMobileUrl('shop/detail',array('op'=>'display','id' => $row['id']));?>">
				<img src="<?php  echo tomedia($row['thumb'])?>">
				<p><?php  echo $row['title'];?></p>
				<p class="grey">市场价：<font style="color:#777">	<del>￥<?php  echo $row['productprice'];?></del></font></p>
				<p>易货价：<span class="yihuo-price">￥<?php  echo $row['marketprice'];?></span></p>
                </a>
			</li>
            <?php  } } ?>
		</ul>
	</div>
    <?php  } } ?>
	<!-- 新闻 -->
	<div class="news2 flex">
		<ul class="flex1">
			<h2><a href="#">公司易货新闻</a></h2>
            <?php  if(is_array($articlelist2)) { foreach($articlelist2 as $v) { ?>
			<li><a href="<?php  echo $this->createMobileUrl('member/news',array('id'=>$v['id']));?>"><i class="icon icon-bofang"></i><?php  echo $v['title'];?></a></li>
		    <?php  } } ?>
		</ul>
		<ul class="flex1">
			<h2><a href="#">行业易货动态</a></h2>
            <?php  if(is_array($articlelist3)) { foreach($articlelist3 as $v) { ?>
            <li><a href="<?php  echo $this->createMobileUrl('member/news',array('id'=>$v['id']));?>"><i class="icon icon-bofang"></i><?php  echo $v['title'];?></a></li>
            <?php  } } ?>
		</ul>
        <ul class="flex1">
            <h2><a href="#">易货新品上架</a></h2>
            <?php  if(is_array($articlelist4)) { foreach($articlelist4 as $v) { ?>
            <li><a href="<?php  echo $this->createMobileUrl('member/news',array('id'=>$v['id']));?>"><i class="icon icon-bofang"></i><?php  echo $v['title'];?></a></li>
            <?php  } } ?>
        </ul>
	</div>


</div>
<!-- 右侧固定 -->
<div class="right-fix">
	<ul>
        <?php  if(is_array($listlogo)) { foreach($listlogo as $v) { ?>
        <!--<a href="<?php  echo $v['url'];?>"><li class="fkf-1"><img src="<?php  echo tomedia($v['img'])?>"></li></a>-->
        <?php  } } ?>
		<a href="http://jhzh66.com/app/index.php?i=8&c=entry&p=cart&do=shop&m=sz_yi"><li class="fkf-1"><img src="../addons/sz_yi/static/img/fkf-1.png"></li></a>
		<a href="#"><li class="fkf-2"><img src="../addons/sz_yi/static/img/fkf-2.png"></li></a>
		<li class="fkf-3 kefu"><img src="../addons/sz_yi/static/img/fkf-3.png">
				<div class="kefu left-fkf">
					<div class="fkf-top flex">
						<img src="../addons/sz_yi/static/img/fkf-3.png">
						<div class="fkf-text">
							<h2>易货在线客服</h2>
							<p>服务时间：8:00-18：00</p>
						</div>

					</div>
					<div class="fkf-text2">
						选择下列客服进行沟通:
						<p><a href="#"><?php  echo $set['shop']['phone'];?></a></p>
						<p>没有客服信息</p>
					</div>
				</div>
		</li>
		<li class="fkf-4" class="phone"> <img src="../addons/sz_yi/static/img/fkf-4.png">
			<div class="phone left-fkf">
					<div class="fkf-top flex">
						<img src="../addons/sz_yi/static/img/fkf-4.png">
						<div class="fkf-text">
							<h2><?php  echo $set['shop']['phone'];?></h2>
							<p>7*24小时服务</p>
						</div>
					</div>
					<div class="fkf-top flex">
						<img src="../addons/sz_yi/static/img/fkf-4.png">
						<div class="fkf-text">
							<h2><?php  echo $set['shop']['phone'];?></h2>
							<p>7*24小时服务</p>
						</div>
					</div>
				</div>
		</li>
		<a href="#"><li class="fkf-5"><img src="../addons/sz_yi/static/img/fkf-5.png"></li></a>
		<a href="#"><li class="fkf-6"><img src="../addons/sz_yi/static/img/fkf-6.png"></li></a>
		<li class="fkf-7 erweima"><img src="../addons/sz_yi/static/img/fkf-7.png">
			<div class="erweima left-fkf">
					<img src="../addons/sz_yi/static/img/qrcode_124.jpg">
					<p>扫一扫</p>
		</li>
		<a href="#"><li class="fkf-8"><img src="../addons/sz_yi/static/img/fkf-8.png"></li></a>


	</ul>
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
<!--<a href="https://m.kuaidi100.com/" target="_blank">快递查询</a>-->
	<!--<div class="footer2">-->
		<!--<div class="footer-content">-->
				<!--<span>增值电信业务经营许可证： 鄂ICP备14005665号-10 </span>-->
				<!--<span>网络文化经营许可证：鄂ICP备14005665号-10</span>-->
				<!--<span>-->
					<!--互联网医疗保健信息服务 审核同意书 鄂ICP备14005665号-10-->
				<!--</span>-->
				<!--<span>2012-0005© 2003-2016 xxxxx.COM 版权所有</span>-->
			<!--<ul class="flex imgs">-->
				<!--<li class="flex1"><img src="../addons/sz_yi/static/img/zj05_ltxd.png"></li>-->
				<!--<li class="flex1"><img src="../addons/sz_yi/static/img/zj07_ur05.png"></li>-->
				<!--<li class="flex1"><img src="../addons/sz_yi/static/img/zj09_zjyh.png"></li>-->
				<!--<li class="flex1"><img src="../addons/sz_yi/static/img/zj11_7ba2.png"></li>-->
			<!--</ul>-->
		<!--</div>-->
	<!--</div>-->
<script type="text/javascript" src="../addons/sz_yi/static/js1/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript">
	// 轮播
		$(".slideBox").slide({mainCell:".bd ul",effect:"leftLoop",autoPlay:true,trigger:"click",mouseOverStop:false});

	// 鼠标移动展示二级菜单
		$('.frist li').hover(function(){
			$(this).find('ul').show()
		},function(){
			$(this).find('ul').hide()
		});



	//显示左侧边栏
		// $('.activeA').hover(function(){
		// 	$('.second .all').show();
		// },function(){
		// 	$('.second .all').hide();
		// });
		// $('.second .all').hover(function(){
		// 	$('.second .all').show();
		// },function(){

		// });
	// 点击分类菜单外区域隐藏侧边栏
		// $(document).click(function(){
		// 	$('.second .all').fadeOut();
		// })
		// $('.second').click(function(e){
		// 	e.stopPropagation();
		// })
	// 显示左侧边栏详细分类
		$('.second .all li').hover(function(){
			var id = $(this).prop('class');
			$('#'+id+'').show();
		},function(){
			var id = $(this).prop('class');
			$('#'+id+'').hide();
		});
		$('.second .third').hover(function(){
			$(this).show()
		},function(){
			$(this).hide()
		});
	//固定右侧栏
	// 返回顶部
		$('html').removeClass();
		$('.right-fix li').hover(function(){
			$(this).find('div').show()
		},function(){
			$(this).find('div').hide()
		});
		$('.fkf-8').click(function(){
			$('html, body').animate({scrollTop: 0}, 500);
			console.log($('body').scrollTop(),$('.main').scrollTop(),$('.scrollable').scrollTop())
		})
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>