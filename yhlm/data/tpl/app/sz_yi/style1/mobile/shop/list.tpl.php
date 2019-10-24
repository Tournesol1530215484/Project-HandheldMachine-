<?php defined('IN_IA') or exit('Access Denied');?> <?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title><?php  if(empty($current_category)) { ?>全部商品<?php  } else { ?><?php  echo $current_category['name'];?><?php  } ?></title>
<style type="text/css">

    body {margin:0px; width:100%; height:100%; background:#efefef; color:#fff; }
    html {}
    .main {height:100%; width:100%; background:#fff; }
    .main .category {height:100%; width:60%; background:rgba(0,0,0,0.8);  position:fixed; left:-60%; top:0; z-index:9999;overflow-y: auto;}
    .main .category .title {height:44px; width:100%; background:rgba(0,0,0,0.2); line-height:44px; font-size:16px; color:#fff; text-align:center;}
    .main .category .all {height:auto; width:80%; padding:10px 10%; color:#fff;}
    .main .category .all p {height:auto; width:100%; font-size:16px; line-height:20px; padding:0px; margin:0px;}
   .main .category .all p.category_item { padding-top:10px;}
    .main .category .all p.child {height:auto; width:100%; font-size:16px; line-height:20px;  padding-left:10px;padding-top:10px;}
    .main .category .all p.third {height:auto; width:100%; font-size:16px; line-height:20px;  padding-left:20px;padding-top:10px;}
    .main .category .all span {height:auto; width:95%; margin-left:5%; margin-bottom:10px; font-size:16px; line-height:20px; padding:0px;}

    .main .page {width:100%; background:#eee; }
    .main .page .topbar {height:44px; width:100%; background:#f9f9f9; padding:8px 0;}
    .main .page .topbar .list1,    .main .page .topbar .list2 {height:28px; width:10%; float:left; margin-left:3%; line-height:28px; font-size:18px; text-align:left; color:#999;}
    .main .page .topbar .name {height:28px; width:54%;overflow: hidden; padding-left:10%; margin:auto; text-align:center; float:left; font-size:16px; line-height:28px; color:#666;}
    .main .page .topbar .sort {height:28px; width:10%; float:right; font-size:18px; line-height:28px; padding-top:5px; text-align:right; color:#999;}
    .main .page .topbar .search {height:28px; width:10%; float:right; margin-right:3%; font-size:18px; line-height:25px; text-align:right; color:#999;}

    .main .page .goods {height:auto; width:98%; padding:0 1%; margin-top:5px; float:left;}
    .main .page .goods .good {width: 50%;box-sizing: border-box;float: left;    padding: 8px 4px 4px 8px;}
    .main .page .goods .good img {width:100%; }
    .main .page .goods .good .nostock { position: absolute;bottom:20px;right:0px; width:30%;height:30%;z-index: 998;}
    .main .page .goods .good .nostock img { max-width: 50px}
	.goods-img {
    width: 100%;
    height: 0;
    padding-bottom: 100%;
    position: relative;
}
    .goods-cont {
    background-color: #fff;
    padding: 5px;
    box-sizing: border-box;
}

    .main .page .goods .good .name {height:42px; width:100%; font-size:14px; color:#333333; line-height:22px;overflow:hidden; padding-bottom:5px;}
    .main .page .goods .good .price {height:25px; width:100%; font-size:16px; color:#e43a3d}
    .main .page .goods .good .price span {height:23px; width:auto; padding:2px 6px; color:#cccccc; font-size:12px; border-radius:5px; text-decoration: line-through}
    .main .page .copyright {height:40px; width:100%; text-align:center; line-height:40px; font-size:12px; color:#999; margin-top:10px;float:left;}

    .main .page .sort_list {height:100px; width:90px; background:rgba(51,51,51,0.8); border-radius:5px; position:absolute; top:50px; right:5%; display:none;z-index:999}
    .main .page .sort_list .nav {height:25px; width:90px; line-height:25px; font-size:12px; color:#fff; text-align:center;}
    .main .page .sort_list .navon {color:#e43a3d;}

    #list_loading { width:94%;padding:10px;color:#666;text-align: center;float:right;}
    .list_no {height:100px; width:100%; margin:50px 0px 60px; color:#ccc; font-size:12px; text-align:center;}
    .list_no_menu {height:40px; width:50%; text-align:center;margin:auto;}
    .list_no_nav {height:38px; padding:5px;background:#eee; border:1px solid #d4d4d4; border-radius:5px; text-align:center; line-height:38px; color:#666;}

.category_group {background:#fff; border-bottom:1px solid #efefef;border-top:1px solid #efefef; height:37px;overflow: auto;  overflow-y: hidden;   list-style-type:none; ;white-space:nowrap;-webkit-overflow-scrolling:touch ; }
.category_group .container{ height:45px;overflow: auto; list-style-type:none;white-space:nowrap;-webkit-overflow-scrolling:touch ; }
.category_group  a {color:#f90;   height:45px; line-height:35px; text-decoration: none; color:#333; text-align: center; padding:10px;; }
.category_group  a.on  { color:#f90;font-weight:bold;background:#efefef;}

span.f12 {
    font-size: 10px;
}

#fenye{clear:both;margin:15px 0;height:35px; text-align:center; }
#fenye table{ width:100%}
#fenye img{ vertical-align:inherit}
#fenye a,#fenye span{text-decoration:none; font-size:14px;height:30px;display:inline-block; text-align:center; 
line-height:30px;color:#666;border: 1px solid #ccc;padding:0 10px; font-size:12px; margin:0 2px;background:#fff; border-radius:3px; }
#fenye a.curr{background-color: #EFEFEF; cursor:default;}
#fenye a:hover{color:#333; background-color: #EFEFEF; }
#fenye span em{ margin-left:5px;}
.head a{ text-decoration:none}

.pagination{display:inline-block;padding-left:0;margin:20px auto;border-radius:4px;text-align: center;width:100%;}
.pagination>li{display:inline-block;}
.pagination>li>a,.pagination>li>span{position:relative;float:left;padding:6px 12px;margin-left:-1px;line-height:1.42857143;color:#666;text-decoration:none;background-color:#fff;border:1px solid #ddd}
.pagination>li:first-child>a,.pagination>li:first-child>span{margin-left:0;border-top-left-radius:4px;border-bottom-left-radius:4px}
.pagination>li:last-child>a,.pagination>li:last-child>span{border-top-right-radius:4px;border-bottom-right-radius:4px}
.pagination>li>a:hover,.pagination>li>span:hover,.pagination>li>a:focus,.pagination>li>span:focus{color:#2a6496;background-color:#eee;border-color:#ddd}
.pagination>.active>a,.pagination>.active>span,.pagination>.active>a:hover,.pagination>.active>span:hover,.pagination>.active>a:focus,.pagination>.active>span:focus{z-index:2;cursor:default;background-color:#8C8C8C;}
.pagination>.disabled>span,.pagination>.disabled>span:hover,.pagination>.disabled>span:focus,.pagination>.disabled>a,.pagination>.disabled>a:hover,.pagination>.disabled>a:focus{color:#777;cursor:not-allowed;background-color:#fff;border-color:#ddd}
.pagination-lg>li>a,.pagination-lg>li>span{padding:10px 16px;font-size:18px}.pagination-lg>li:first-child>a,.pagination-lg>li:first-child>span{border-top-left-radius:6px;border-bottom-left-radius:6px}
.pagination-lg>li:last-child>a,.pagination-lg>li:last-child>span{border-top-right-radius:6px;border-bottom-right-radius:6px}.pagination-sm>li>a,.pagination-sm>li>span{padding:5px 10px;font-size:12px}
.pagination-sm>li:first-child>a,.pagination-sm>li:first-child>span{border-top-left-radius:3px;border-bottom-left-radius:3px}
.pagination-sm>li:last-child>a,.pagination-sm>li:last-child>span{border-top-right-radius:3px;border-bottom-right-radius:3px}
.pager{padding-left:0;margin:20px 0;text-align:center;list-style:none}.pager li{display:inline}.pager li>a,.pager li>span{display:inline-block;padding:5px 14px;background-color:#fff;border:1px solid #ddd;border-radius:15px}
.pager li>a:hover,.pager li>a:focus{text-decoration:none;background-color:#eee}
.pager .next>a,.pager .next>span{float:right}
.pager .previous>a,.pager .previous>span{float:left}
.pager .disabled>a,.pager .disabled>a:hover,.pager .disabled>a:focus,.pager .disabled>span{color:#777;cursor:not-allowed;background-color:#fff}


div.addinfo{height:18px;}

.label-waring {
    font-size: 12px;
    line-height: 14px;
    padding: 1px 5px;
    display: inline-block; 
}

.label-waring {
    background-color: #fd6801;
    color: #fff;
}.tint-gray {
    color: #ccc;
}.f12 {
    font-size: 12px;
    line-height: 14px;
}

/*分类*/

	.sortList{
			width: 100%;
			height: 30px;
			background: #fff;
			border: 1px solid #e7e7e7;
			border-left: none;
			border-right: none;
		
		}
		.sort-column{
			list-style: none;
			
			width: 100%;
			/* height: 100%; */
			display: flex;
			padding: 2% 0;
			margin: 0;
		}
		
		.sort-column li{
			flex: 1;
			line-height: 30px;
			border-right: 1px solid #e7e7e7;
			text-align: center;
			line-height:1;
			color:#666666;
			
		}
		.sort-column li:last-of-type{
			border-right:none ;
		}
		.active{
			color: #fd5454 !important;
		}
		/*筛选*/
		.sku {
			width: 100%;
			background-color: #fff;
			text-align: center;
			margin: 2% 0;
			display: none;
			color: #999999;
		}
		
		.sku_li {
			width: 100%;
			border-top: 1px solid #eeeeee;
			border-bottom: 1px solid #eeeeee;
			overflow: hidden;
		}
		
		.sku_li>li {
			width: 33.33%;
			padding: 4% 0;
			float: left;
		}
		
		.sku>span {
			width: 100%;
			padding: 2% 0;
			display: block;
		}
		
		.sku_class {
			width: 100%;
			height: 200px;
			border-top: 1px solid #eeeeee;
			border-bottom: 1px solid #eeeeee;
			padding:1% 0;
		}
		
		.sku_class_left {
			width: 40%;
			height: 100%;
			float: left;
			overflow: scroll;
			background-color: #fff;
		}
		
		.sku_class_left>li {
			padding-top: 10%;
		}
		.sku_class_left>li>a{
			color: #999;
		}
		
		.sku_class_right {
			width: 60%;
			height: 100%;
			float: left;
			background-color: #fff;
		}
		
		.sku_btn {
			width: 100%;
			background-color: #fff;
			overflow: hidden;
			padding: 2% 0;
			border-bottom: 1px solid #eeeeee
		}
		
		.sku_btn>span {
			padding: 0 3%;
		}
		
		.sku_btn>span:first-of-type {
			float: left;
		}
		
		.sku_btn>span:last-of-type {
			float: right;
		}
        .close_box.close{
            width: 44px;
            height: 44px;
            position:  absolute;
            top: 0px;
            right:5px;
            color: #fff;
            text-align: center;
            opacity:1;
        }
</style>

 
<div id='container'></div>

<script id='tpl_main' type='text/html'>
     <!--搜索-->
    <div class="search1">
                <div class="topbar1">
                    <div class='right'>
                        <button class="sub1"><i class="fa fa-search"></i></button>
                        <div class="home1">取消</div>
                    </div>
                    <div class='left_wrap'>
                        <div class='left'>
                            <input type="text" id='keywords' class="input1" placeholder='搜索: 输入商品关键词' value='<?php  echo $_GPC['keywords'];?>'/>
                        </div>
                    </div>
                </div>
                <div id='search_container' class='result1'>
        </div>
</div>
    <div class="main">
        <div id='category_container'></div>
        <div class='page'>
            <!--排序div-->
            <div class="sort_list">
                <div class="nav <?php  if($_GPC['order']=='') { ?>navon<?php  } ?> <?php  if($_GPC['order']=='sales') { ?>navon<?php  } ?>"  data-order='sales' data-by='desc'>销量从高到低</div>
                <div class="nav <?php  if($_GPC['order']=='marketprice' && $_GPC['by']=='asc') { ?>navon<?php  } ?>" data-order='marketprice' data-by='asc'>价格从低到高</div>
                <div class="nav <?php  if($_GPC['order']=='marketprice' && $_GPC['by']=='desc') { ?>navon<?php  } ?>"  data-order='marketprice' data-by='desc'>价格从高到低</div>
                <div class="nav <?php  if($_GPC['order']=='score') { ?>navon<?php  } ?>"  data-order='score' data-by='asc'>评价从高到低</div>
            </div>
            <!--topbar-->
            <div class="topbar">
                <?php  if(!empty($myshop['selectcategory']) || empty($myshop)) { ?>
                <div class="list1">
                  <i class="fa fa-list-ul"><br><span class="f12" style="position:relative; bottom:5px;">分类</span></i>
				  
                </div>
                <?php  } else { ?>
                <div class="list2" onclick='history.back()'>
                  <i class="fa fa-angle-left"></i>
                </div>
                
                <?php  } ?>
                <div class="name"><?php  if(empty($current_category)) { ?>全部商品<?php  } else { ?><?php  echo $current_category['name'];?><?php  } ?></div>
                <div class="search"><i class="fa fa-search"></i></div>
                <div class="sort"><i class="fa fa-sort-numeric-desc" style="float:right;"></i></div>
            </div>
           <!-- <div id="category_group"></div> -->
           <!-- 排序分类 -->
         	<div class="sortList">
				<ul class="sort-column">
					<li class="navSort <?php  if($_GPC['order']=='salesreal') { ?>active<?php  } ?>"  data-order="salesreal" data-by="desc">综合</li>
					<li class="navSort <?php  if($_GPC['order']=='sales') { ?>active<?php  } ?>" data-order="sales" data-by="desc">销量</li>
					<li class="navSort <?php  if($_GPC['order']=='marketprice') { ?>active<?php  } ?>" data-order="marketprice" data-by="desc">价格</li>
					<li class="screen">筛选</li>
				</ul>
			</div>

			<!-- 筛选 -->
            	<div class="sku">
                    <ul class="sku_li">
                        <li class='category_item' data-isnew='1' data-name='新上宝贝'><i class="fa fa-cart-plus"></i> 新上宝贝</li>
                        <li class='category_item' data-isrecommand='1'  data-name='推荐宝贝'><i class="fa fa-heart"></i> 推荐宝贝</li>
                        <li class='category_item' data-ishot='1'  data-name='热销宝贝'><i class="fa fa-fire"></i> 热销宝贝</li>
                        <li class='category_item' data-istime='1'  data-name='限时秒杀'><i class="fa fa-clock-o"></i> 限时秒杀</li>
                        <li class='category_item' data-isdiscount='1'  data-name='促销宝贝'><i class="fa fa-thumbs-up"></i> 促销宝贝</li>
                    </ul>
                    <span>选择分类</span>
                    <div class="sku_class">
                        <ul class="sku_class_left">

                        </ul>
                        <div class="sku_class_right"></div>
                    </div>

                <div class="sku_btn">
                    <span>取消筛选</span>
                    <span class="active">确认</span>
                </div>

		</div>

            
            <!--商品列表-->
            <div class="goods">
                <div id='goods_container'></div>
            </div>
            <div class="copyright">版权所有 © <?php  if(!empty($set['copyright'])) { ?><?php  echo $set['copyright'];?><?php  } else { ?><?php  echo $_W['account']['name'];?><?php  } ?></div>
        </div>
        
    </div>
</script>
 
<script id='tpl_search_list' type='text/html'>
     <ul>
     <%each list as value%>
        <li><i class="fa fa-angle-right"></i> <a href="<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%value.id%>"><%value.title%></a></li>
        <%/each%>
    </ul> 
</script>
<script id="tpl_category_group" type="text/html">
  
                <div class="category_group">
                    <div class='container'>
                    <%each category as c%><a href="javascript:;"
                                             level="<%c.level%>"
                                             name="<%c.name%>"
                       <%if c.level==1%>pcate="<%c.id%>"<%/if%>
                       <%if c.level==2%>ccate="<%c.id%>"<%/if%>
                       <%if c.level==3%>tcate="<%c.id%>"<%/if%>
                       <%if c.on %>class="on"<%/if%>
                       
                       ><%c.name%></a><%/each%>
     </div>
               </div>
 
 
 </script>
<script id='tpl_goods_list' type='text/html'>
  <form action="" method="get" id="goodsform">
    <input type="hidden" name="i" value="<?php  echo $_W['uniacid'];?>">
    <input type="hidden" name="c" value="<?php  echo $_GPC['c'];?>">
    <input type="hidden" name="do" value="<?php  echo $_GPC['do'];?>">
    <input type="hidden" name="m" value="<?php  echo $_GPC['m'];?>">
    <input type="hidden" name="p" value="<?php  echo $_GPC['p'];?>">
    <input type="hidden" name="keywords" value="<?php  echo $_GPC['keywords'];?>">
    <input type="hidden" id="isnew" name="isnew" value="<?php  echo $_GPC['isnew'];?>">
    <input type="hidden" id="ishot" name="ishot" value="<?php  echo $_GPC['ishot'];?>">
    <input type="hidden" id="isrecommand" name="isrecommand" value="<?php  echo $_GPC['isrecommand'];?>">
    <input type="hidden" id="isdiscount" name="isdiscount" value="<?php  echo $_GPC['isdiscount'];?>">
    <input type="hidden" id="istime" name="istime" value="<?php  echo $_GPC['istime'];?>">
    <input type="hidden" id="pcate" name="pcate" value="<?php  echo $_GPC['pcate'];?>">
    <input type="hidden" id="ccate" name="ccate" value="<?php  echo $_GPC['ccate'];?>">
    <input type="hidden" id="tcate" name="tcate" value="<?php  echo $_GPC['tcate'];?>">
    <input type="hidden" id="order" name="order" value="<?php  echo $_GPC['order'];?>">
    <input type="hidden" id="by" name="by" value="<?php  echo $_GPC['by'];?>">
  </form>
    <?php  if(is_array($goods)) { foreach($goods as $g) { ?>
     <?php  $discount=number_format($g['marketprice']*10/$g['productprice'],1)?>
    <div class="good" data-goodsid='<?php  echo $g['id'];?>' style="position:relative;">
        <?php  if($g['total']<=0) { ?><div class="nostock"><img src="../addons/sz_yi/template/mobile/default/static/images/salez.png"></div><?php  } ?>
		<div class="goods-img">
        <img src="<?php  echo $g['thumb'];?>">
		</div>
		<div class="goods-cont">
        <div class="name"><?php  echo $g['title'];?> </div>
        <div class="price">￥<?php  echo $g['marketprice'];?> <?php  if($g['productprice']>0 && $g['marketprice']!=$g['productprice']) { ?><span>￥<?php  echo $g['productprice'];?></span><?php  } ?></div>
        <div class="addinfo" style=" display:none;"><?php  if($discount&&$discount>0) { ?><span class="label-waring"><?php  echo $discount;?>折</span><?php  } ?>  <span class="f12 tint-gray">销量：<?php  echo $g['sales'];?></span></div>
		</div>
    </div>
    <?php  } } ?>

    <?php  echo $pager;?>
</script>
<script id='tpl_category_list' type='text/html'>
     <div class="category">
        <div class="title category_item" data-name='全部商品'><i class="fa fa-list-ul"></i> 全部分类
            <!-- <i class="fa fa-angle-left close" style="color: #fff; opacity: 1;font-size:26px; float:right; line-height:44px; margin-right:20px;"></i> -->
        </div>
        <div class="close_box close">
            <i class="fa fa-angle-left close" style="color: #fff; opacity: 1;font-size:26px; float:right; line-height:44px; margin-right:15px;"></i>
        </div>
        <div class="all">
              <p class='category_item' data-isnew='1' data-name='新上宝贝'><i class="fa fa-cart-plus"></i> 新上宝贝</p>
              <p class='category_item' data-isrecommand='1'  data-name='推荐宝贝'><i class="fa fa-heart"></i> 推荐宝贝</p>
              <p class='category_item' data-ishot='1'  data-name='热销宝贝'><i class="fa fa-fire"></i> 热销宝贝</p>
              <p class='category_item' data-istime='1'  data-name='限时秒杀'><i class="fa fa-clock-o"></i> 限时秒杀</p>
              <p class='category_item' data-isdiscount='1'  data-name='促销宝贝'><i class="fa fa-thumbs-up"></i> 促销宝贝</p>
              <%each category as parent%>
                <%if parent.id!='824'%>
                    <a href="<?php  echo $this->createMobileUrl('shop/list')?>&pcate=<%parent.id%>">
                        <p class='category_item' style=" color:#ffff00;"><i class="fa fa-angle-double-right"></i> <%parent.name%></p>
                    </a>
                    <%each parent.children as child%>
                      <a href="<?php  echo $this->createMobileUrl('shop/list')?>&pcate=<%parent.id%>&ccate=<%child.id%>">
                           <p class='child category_item' style=" color:#fff;"><i class="fa fa-angle-right"></i> <%child.name%></p>
                      </a>
                           <?php  if(intval($set['catlevel'])==3) { ?>
                               <%each child.children as third%>
                                   <p class='third category_item' data-tcate='<%third.id%>' data-name='<%third.name%>'><i class="fa fa-angle-right"></i> <%third.name%></p>
                               <%/each%>
                           <?php  } ?>
                    <%/each%>
                <%/if%>
              <%/each%>
        </div>
    </div>
</script>

<!--大分类-->
<script id='tpl_category' type='text/html'>

            <%each category as parent%>
                <%if parent.id!='824'%>
                <li>
                    <a href="<?php  echo $this->createMobileUrl('shop/list')?>&pcate=<%parent.id%>">
                        <p class='category_item_sort'><%parent.name%></p>
                    </a>
                </li>
                <%/if%>
            <%/each%>
</script>


<script id='tpl_empty' type='text/html'>
 <div class="list_no"><i class="fa fa-shopping-cart" style="font-size:100px;"></i><br><span style="line-height:18px; font-size:16px;">暂时没有相关商品</span><br>主人快去给我找点其他东西吧</div>
<div class="list_no_menu">
        <div class="list_no_nav" onclick="location.href='<?php  echo $this->createMobileUrl('shop/list')?>'">看看其他的</div>
 </div>
</script>
<script language='javascript'>
    var loaded = false;
    var stop = true;
    var category = null;
    var def_args = args  = {
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
           shopid:"<?php  echo $_GPC['shopid'];?>"
    };

    require(['tpl', 'core'], function (tpl, core) {


      function getGoods() {         //把PHP遍历出的产品放入dom中
          $('#goods_container').html(tpl('tpl_goods_list'));
          bindEvents();
      }

        /*
        function getGoods() {
             
            core.json('shop/list', args, function (json) {
           
             
                 
                $('#goods_container').html(tpl('tpl_goods_list',json.result));
                $('#category_group').html("");
                if(json.result.category && json.result.category.length>0){
                    var category = {category:json.result.category,parent:json.result.parent_category};
                    $('#category_group').html(tpl('tpl_category_group',category));    
                    $('#category_group a').unbind('click').click(function(){
                        if( $(this).attr('level')=='0'){
                          $('.topbar .name').html('全部商品');
                          document.title ='全部商品';
                        } else{
                            $('.topbar .name').html($(this).attr('name'));
                          document.title =$(this).attr('name');
                        }
                        args.page = 1;
                        args.pcate = $(this).attr('pcate') || 0;
                        args.ccate = $(this).attr('ccate') || 0;
                        args.tcate = $(this).attr('tcate') || 0;
                        loaded =false;
                        getGoods();
                    })
                }  
               
                if(json.result.current_category){
                     $('.topbar .name').html( json.result.current_category.name);
                     document.title = json.result.current_category.name;
                }
                
                if (json.result.goods.length <= 0) {
                    loaded = true;
                    $(window).scroll = null;
                    $('#goods_container').html(tpl('tpl_empty'));
                    return;
                }
                bindEvents();
                stop =true;
                bindMore();
                
            }, true);
        }*/
        function getGoodsMore() {
     
            core.json('shop/list', args, function (json) {
                var result = json.result;
                $('#goods_container').append(tpl('tpl_goods_list',result));
                bindEvents();
                $('#list_loading').remove();
                if (result.goods.length < result.pagesize) {
                        $('#goods_container').append('<div id="list_loading">已经加载全部商品</div>');
                        loaded = true;
                        $(window).scroll = null;
                        return;
                }
                stop=true;
                bindMore(); 
                
            });
        }

        function bindEvents() {
            $('.good img').each(function(){
               $(this).height($(this).width()); 
            });
            $('.good').unbind('click').click(function () {
                        location.href = core.getUrl('shop/detail', {id: $(this).data('goodsid'),my:'<?php  echo $_GPC['my'];?>'});
            });
        }
        
        function bindMore() {
     
            $(window).scroll(function () {
  
                if (loaded) {
                    return;
                }
                totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
                if ($(document).height() <= totalheight) {
                
                    if (stop == true) {
                        stop = false;
                        $('#goods_container').append('<div id="list_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载更多商品</div>');
                  
                        if(args.page=='' || args.page=='undefined'){
                            args.page = 1;
                        }
                        args.page++;
                        getGoodsMore();
                    }
                }
            });
        }
 
        function reset() {
            $('#form')[0].reset();
        }
        function bindCategoryEvents(){              //绑定分类事件
            
             $(".category .close").unbind('click').click(function(){
                        $(".category").animate({left:"-60%"},200);  //取消绑定单击事件
             });
             $(".category .category_item").unbind('click').click(function(){
                 var item = $(this);
                     $('#keywords').val(""); $('#search_container').html('');
                     $(".category").animate({left:"-60%"},200);
                      
                      $("#isnew").val(item.data('isnew'));
                      $("#ishot").val(item.data('ishot'));
                      $("[name='keywords']").val(item.data('keywords'));
                      $("#isrecommand").val(item.data('isrecommand'));
                      $("#isdiscount").val(item.data('isdiscount'));
                      $("#istime").val(item.data('istime'));
                      $("#ccate").val(item.data('ccate'));
                      $("#tcate").val(item.data('tcate'));          //最新最热推荐折扣限时
                      $("#pcate").val(item.data('pcate'));
                      $("#order").val('');
                      $("#by").val('');
                      $("#goodsform").submit();

                      /*
                      args  = {
                            page:1,
                            isnew: item.data('isnew'),
                            ishot:item.data('ishot'),
                            isrecommand:item.data('isrecommand'),
                            isdiscount:item.data('isdiscount'),
                            keywords:"",
                            istime: item.data('istime:'),
                            pcate: item.data('pcate'),
                            ccate: item.data('ccate'),
                            tcate: item.data('tcate'),
                            order:"",
                            by:"",
                            shopid:"<?php  echo $_GPC['shopid'];?>"
                     };
                     $('.topbar .name').html( item.data('name'));
                     document.title = item.data('name');
                     getGoods();
                     */
             });
             
        }

        function bindCategorySortEvents(){              //绑定分类事件

            $(".sku_li .category_item").unbind('click').click(function(){
                var item = $(this);
                $('#keywords').val(""); $('#search_container').html('');
                $(".category").animate({left:"-60%"},200);

                $("[name='keywords']").val(item.data('keywords'));
                $("#isnew").val(item.data('isnew'));
                $("#ishot").val(item.data('ishot'));
                $("#isrecommand").val(item.data('isrecommand'));
                $("#isdiscount").val(item.data('isdiscount'));
                $("#istime").val(item.data('istime'));
                $("#ccate").val(item.data('ccate'));
                $("#tcate").val(item.data('tcate'));          //最新最热推荐折扣限时
                $("#pcate").val(item.data('pcate'));
                $("#order").val('');
                $("#by").val('');
                $("#goodsform").submit();
            });
        }


        
        $('#container').html(tpl('tpl_main'));
        $('.sort').click(function () {          //排序js事件
                var display = $(".sort_list").css('display');
                if (display == 'none') {
                    $(".sort_list").fadeIn(200);
                } else {
                    $(".sort_list").fadeOut(100);
                }
        });

        $('.nav').click(function () {               //右上排序事件
                
                if ($(this).data('order') ==args.order && $(this).data('by') == args.by) {
                    return;
                }
                $('.nav').removeClass('navon');
                
                $(this).addClass('navon');

                      $("#order").val($(this).data('order'));
                      $("#by").val($(this).data('by'));
                      $("#goodsform").submit();

        });

        $('.navSort').click(function () {               //右上排序事件

            if ($(this).data('order') ==args.order && $(this).data('by') == args.by) {
                return;
            }

            $('.nav').removeClass('active');

            $(this).addClass('active');

            $("#order").val($(this).data('order'));
            $("#by").val($(this).data('by'));
            $("#goodsform").submit();
        });


        $('.list1').click(function(){
             $(".sort_list").fadeOut(100);
             if(category!=null){
                var value = $.trim($('#category_container').html());
                if(value.length == 0){
                    $('#category_container').append(tpl('tpl_category_list',category));
                }
                $(".category").animate({left:"0px"},200);
                bindCategoryEvents();
                return;
            }

            core.json('shop/util',{op:'category'}, function (json) {
                 category = json.result;                        //异步请求数据
                 console.log(category);
                 $('#category_container').append(tpl('tpl_category_list',category));    //在文档对象追加内容
                 $(".category").animate({left:"0px"},200);
                 bindCategoryEvents();
            }, true);
        });



        $('.screen').click(function(){  //下拉分类事件
            if(category!=null){
                var value = $.trim($('.sku_class_left').html());
                if(value.length == 0){
                    $('.sku_class_left').append(tpl('tpl_category',category));
                }
                return;
            }
            core.json('shop/util',{op:'category'}, function (json) {
                category = json.result;
                $('.sku_class_left').append(tpl('tpl_category',category));
                    bindCategorySortEvents();
            }, true);
        });

        $('.search').click(function(){
            
           $(".search1").animate({bottom:"0px"},100);
           $('#keywords').unbind('keyup').keyup(function(){
                    var keywords = $.trim( $(this).val());
                    if(keywords==''){
                        $('#search_container').html("");         
                        return;
                    }
                    core.json('shop/util',{op:'search',keywords:keywords }, function (json) {
                         var result = json.result;
                         if(result.list.length>0){
                            $('#search_container').html(tpl('tpl_search_list',result));    
                         }
                         else{
                            $('#search_container').html("");         
                         }

                      }, true);
            });

            $('.search1 .sub1').unbind('click').click(function(){
                var keywords = $.trim( $('#keywords').val());

                $('input[name="keywords"]').val(keywords);

                var temp123=$('input[name="keywords"]').val();

                $("#goodsform").submit();
//                args  = {
//                    page:1,
//                    isnew: args.isnew,
//                    ishot: args.ishot,
//                    isrecommand:args.isrecommand,
//                    isdiscount:args.isdiscount,
//                    keywords:keywords,
//                    istime: args.istime,
//                    pcate:args.pcate,
//                    ccate: args.ccate,
//                    tcate: args.tcate,
//                    order:args.order,
//                    by:args.by,
//                    shopid:args.shopid
//                 };
//
//                $(".sort_list").fadeOut(200);
//                $(".search1").animate({bottom:"-100%"},100);
//
//                getGoods();
            });


            $('.search1 .home1').unbind('click').click(function(){
                 $(".search1").animate({bottom:"-100%"},100);
            });
        });

   		/*分类*/
		$(".sort-column > li").click(function() {
			$(this).addClass('active').siblings().removeClass('active');
			if($(this).context.className == 'screen active') {
				$(".sku").show();
			} else {
				$(".sku").hide();
			}
		})
		/*取消筛选 确认*/
		$(".sku_btn span").click(function(){
			$(".sku").hide();
			
		})
//		$(".sku_li > li").click(function(){
//			$(this).addClass('active').siblings().removeClass('active');
//		})
     getGoods();
        

    });
    $(function(){
        $(".category .close").unbind('click').click(function(){
            $(".category").animate({left:"-60%"},200);  //取消绑定单击事件
        });
    });
</script>
<?php  $show_footer = true;?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
