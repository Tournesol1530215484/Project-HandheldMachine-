<?php defined('IN_IA') or exit('Access Denied');?><body>
<div class="wfs head_top">
    <div class="cover-page-wrapper">
        <ul class="fl top_l">
            <li class="fore"><a href="<?php  echo $this->createMobileUrl('shop/index')?>">欢迎来到<?php  echo $this->yzShopSet['name']?>！</a></li>
        </ul>
        <ul class="fr top_r">
            <?php  if($this->yzShopSet['phone']) { ?>
            <li class="fore">客服电话：<?php  echo $this->yzShopSet['phone']?></li>
            <?php  } ?>
        <?php  if($_COOKIE[__cookie_sz_yi_userid_.$_W['uniacid']]) { ?>
            <li class="fore"><a href="<?php  echo $this->createMobileUrl('order')?>">个人中心</a></li>
        <?php  } else { ?>
            <li class="fore"><a href="<?php  echo $this->createMobileUrl('member')?>">登陆</a></li>
            <li class="spacer"></li>
            <li class="fore"><a href="<?php  echo $this->createMobileUrl('member/register')?>">注册</a></li>
        <?php  } ?>

            <li class="fore cart" id="ECS_CARTINFO">
                <a href="<?php  echo $this->createMobileUrl('shop/cart')?>" title="查看购物车"><i class="fontello-icon-basket"></i><span>购物车</span><strong><b>0</b></strong></a>
             </li>
        </ul>
    </div>
</div>

<div id="head-fix" class="head fl wfs nav-wrap">
<div class="cover-page-wrapper head_logo_nav">
<!-- logo -->
    <div class="logo fl">
        <a href="<?php  echo $this->createMobileUrl('shop/index')?>">
        <?php  if($this->yzShopSet['pclogo']) { ?>
            <img src="<?php  echo $_W['siteroot'] . "attachment/" . $this->yzShopSet['pclogo']?>" style="width:270px;height:60px;" title="<?php  echo $this->yzShopSet['pctitle']?>">
        <?php  } else { ?>
            <img src="../addons/sz_yi/template/pc/default/static/images/logo.png" title="" alt="我是默认logo">
        <?php  } ?>
        </a>
    </div>
    <?php  if($this->yzShopSet['hmenu_name']) { ?>
          <ul class="nav_head fl">
            <?php  if(is_array($this->yzShopSet['hmenu_name'])) { foreach($this->yzShopSet['hmenu_name'] as $k => $v) { ?>
            <li>
                <a href="<?php  echo $this->yzShopSet['hmenu_url'][$k]?>"><?php  echo $v;?></a>
            </li>
            <?php  } } ?>
        </ul>
    <?php  } else { ?>
        <ul class="nav_head fl">
            <li>
                <a href="<?php  echo $this->createMobileUrl('shop/index')?>">首页</a>
            </li>
            <li>
                <a href="<?php  echo $this->createMobileUrl('shop/list', array('order' => 'sales', 'by' => 'desc'))?>">全部商品</a>
            </li>
            <li>
                <a href="<?php  echo $this->createMobileUrl('shop/notice')?>">店铺公告</a>
            </li>
            <?php  if($this->footer['commission']) { ?>
                <li <?php  if($footer_current=='commission') { ?>class='active'<?php  } ?>>
                    <a href="<?php  echo $this->footer['commission']['url']?>">
                        <span><?php  echo $this->footer['commission']['text']?></span>
                    </a>
                </li>
            <?php  } ?>
            <li>
                <a href="<?php  echo $this->createMobileUrl('order')?>">会员中心</a>
            </li>
        </ul>
    <?php  } ?>

    <!--<form id="searchForm" name="searchForm" method="get" action="" onSubmit="return checkSearchForm()" class="search-product fl">-->
	<div class="search-product fl">
        <input name="k_template" type="hidden" class="k_value" value="0">
        <i class="fontello-icon-search fr search"></i>
        <input autocomplete="off" name="keywords" type="text" id="keyword" placeholder="输入关键字搜索" value="" class="form-control search-box fr"/>
        <div class="suggestions_box" id="suggestions" style="display:none;border-bottom: 1px solid #cdcdcd;">
            <div class="suggestions_list" id="auto_suggestions_list"></div>
        </div>
        </div>
    <!--</form>-->
</div>
</div>
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
	$('#keyword').keyup(function(){
	    if(event.keyCode == 13){
		$('.search').click();
		return;
	    }
	});

        $('.search').click(function(){
            var args  = {
                   page:"<?php  echo $_GPC['page'];?>",
                   isnew: "<?php  echo $_GPC['isnew'];?>",
                   ishot: "<?php  echo $_GPC['ishot'];?>",
                   isrecommand:"<?php  echo $_GPC['isrecommand'];?>",
                   isdiscount:"<?php  echo $_GPC['isdiscount'];?>",
                   keywords:"<?php  echo $_GPC['keywords'];?>",
                   istime:"<?php  echo $_GPC['istime'];?>",
                   pcate:"<?php  echo $_GPC['pcate'];?>",
                   ccate:"<?php  echo $_GPC['ccate'];?>",
                   tcate:"<?php  echo $_GPC['tcate'];?>",
                   order:"<?php  echo $_GPC['order'];?>",
                   by:"<?php  echo $_GPC['by'];?>",
                   shopid:"<?php  echo $_GPC['shopid'];?>",
                   keywords:$("#keyword").val()
            };
            location.href=core.getUrl('shop/list', args);
        });
    })
</script>
