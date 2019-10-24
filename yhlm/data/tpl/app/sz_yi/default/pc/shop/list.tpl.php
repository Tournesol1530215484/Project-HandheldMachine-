<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title><?php  if(empty($current_category)) { ?>全部商品<?php  } else { ?><?php  echo $current_category['name'];?><?php  } ?></title>

<!--导航调用-->
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/navigation', TEMPLATE_INCLUDEPATH)) : (include template('common/navigation', TEMPLATE_INCLUDEPATH));?>
<div style="width:100%;background:#fff">
<div class="w m0a">
    <div class="xqleft">
        
        <div class="left-shop">
            
        </div>
        <script id='tpl_category_list' type='text/html'>
            <div id="sp-category" class="mc" clstag="shangpin|keycount|product|pop-04">
                <div class="jOneLevel">
                    <span>商品分类</span>
                </div>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="category_item" href="<?php  echo $this->createMobileUrl('shop/list')?>">全部商品</a>
                    </div>
                    <!--<div class="jThreeLevel">
                        <ul>
                            <li><a  href="#">2016夏装男款</a></li>
                            <li><a  href="#">2016夏装男款</a></li>
                        </ul>
                    </div>-->
                </div>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="category_item" href="javascript:void(0)"  data-isnew='1' data-name='新上宝贝'>新上宝贝</a>
                    </div>
                    <div class="jThreeLevel">
                    </div>
                </div>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="category_item" href="javascript:void(0)" data-isrecommand='1' data-name='推荐宝贝'>推荐宝贝</a>
                    </div>
                    <div class="jThreeLevel">
                    </div>
                </div>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="category_item" href="javascript:void(0)" data-ishot='1' data-name='热销宝贝'>热销宝贝</a>
                    </div>
                    <div class="jThreeLevel">
                    </div>
                </div>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="category_item" href="javascript:void(0)" data-istime='1' data-name='限时秒杀'>限时秒杀</a>
                    </div>
                    <div class="jThreeLevel">
                    </div>
                </div>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="category_item" href="javascript:void(0)" data-isdiscount='1' data-name='促销宝贝'>促销宝贝</a>
                    </div>
                    <div class="jThreeLevel">
                    </div>
                </div>
                <%each category as parent%>
                <div class="jOneLevelarea">
                    <div class="jTwoLevel">
                        <span class="jIconArrow"></span>
                        <a class="child category_item" href="<?php  echo $this->createMobileUrl('shop/list')?>&pcate=<%parent.id%>"><%parent.name%></a>
                    </div>
                    <div class="jThreeLevel">
                    </div>
                </div>
                <%/each%>
            </div>
            
           <!--<div class="m m2 related-buy">
                <div class="mt">
                    <h2>店铺热销</h2>
                </div>
                <div class="mc">
                    <ul>
                        <li class="fore1">
                        <div class="p-imgbox">
                            <a href="#" target="_blank">
                            <img height="160" src="images/56c6b8d4N34647293.jpg" width="160">
                            </a></div>
                        <div class="p-name">
                            <a href="#" target="_blank">红豆（Hodo）男装 2016春季新款男士商务休闲纯色V领棉质长袖T恤</a></div>
                        <div class="p-info p-bfc">
                            <div class="p-count fl"><s>1</s><b>热销11690件</b></div>
                            <div class="p-price fr"><strong class="J-p-1005947680">￥109.00</strong></div>
                        </div>
                        </li>
                        <li>
                        <div class="p-imgbox">
                            <a href="#" target="_blank">
                            <img height="160" src="images/56c6b8d4N34647293.jpg" width="160">
                            </a></div>
                        <div class="p-name">
                            <a href="#" target="_blank">红豆（Hodo）男装 2016春季新款男士商务休闲纯色V领棉质长袖T恤</a></div>
                        <div class="p-info p-bfc">
                            <div class="p-count fl"><s>2</s><b>热销11690件</b></div>
                            <div class="p-price fr"><strong class="J-p-1005947680">￥109.00</strong></div>
                        </div>
                        </li>
                        <li>
                        <div class="p-imgbox">
                            <a href="#" target="_blank">
                            <img height="160" src="images/56c6b8d4N34647293.jpg" width="160">
                            </a></div>
                        <div class="p-name">
                            <a href="#" target="_blank">红豆（Hodo）男装 2016春季新款男士商务休闲纯色V领棉质长袖T恤</a></div>
                        <div class="p-info p-bfc">
                            <div class="p-count fl"><s>3</s><b>热销11690件</b></div>
                            <div class="p-price fr"><strong class="J-p-1005947680">￥109.00</strong></div>
                        </div>
                        </li>
                        <li>
                        <div class="p-imgbox">
                            <a href="#" target="_blank">
                            <img height="160" src="images/56c6b8d4N34647293.jpg" width="160">
                            </a></div>
                        <div class="p-name">
                            <a href="#" target="_blank">红豆（Hodo）男装 2016春季新款男士商务休闲纯色V领棉质长袖T恤</a></div>
                        <div class="p-info p-bfc">
                            <div class="p-count fl"><s>4</s><b>热销11690件</b></div>
                            <div class="p-price fr"><strong class="J-p-1005947680">￥109.00</strong></div>
                        </div>
                        </li>
                        <li>
                        <div class="p-imgbox">
                            <a href="#" target="_blank">
                            <img height="160" src="images/56c6b8d4N34647293.jpg" width="160">
                            </a></div>
                        <div class="p-name">
                            <a href="#" target="_blank">红豆（Hodo）男装 2016春季新款男士商务休闲纯色V领棉质长袖T恤</a></div>
                        <div class="p-info p-bfc">
                            <div class="p-count fl"><s>5</s><b>热销11690件</b></div>
                            <div class="p-price fr"><strong class="J-p-1005947680">￥109.00</strong></div>
                        </div>
                        </li>
                    </ul>
                </div>
            </div>-->
            </script>
        <div class="detail-info">
            <div class="filter cls">
            </div>
            
        </div>
        <script id='tpl_main' type='text/html'> 
            <div class="fl rankitem">
                    <!--<span class="fl label ml20">默认排序</span> -->
                    <span class="orderCell" data-order='sales' data-by='desc'>
                        <a class="item itemDefault" href="javascript:void(0)"><span>销量从高到低</span></a>
                    </span>
                    <span class="orderCell" data-order='marketprice' data-by='asc'>
                        <a class="item itemDefault" href="javascript:void(0)"><span>价格从低到高</span></a>
                    </span>
                    <span class="orderCell" data-order='marketprice' data-by='desc'>
                        <a class="item itemDefault" href="javascript:void(0)"><span>价格从高到低</span></a>
                    </span>
                    <span class="orderCell" data-order='score' data-by='asc'>
                        <a class="item itemDefault" href="javascript:void(0)"><span>评价从高到低</span></a>
                    </span>
                </div>
            </script>
            <script id='tpl_goods_list' type='text/html'>
            <div class="allsortgood">
                <form action="" method="get" id="goodsform">
                    <input type="hidden" name="i" value="<?php  echo $_W['uniacid'];?>">
                    <input type="hidden" name="c" value="<?php  echo $_GPC['c'];?>">
                    <input type="hidden" name="do" value="<?php  echo $_GPC['do'];?>">
                    <input type="hidden" name="m" value="<?php  echo $_GPC['m'];?>">
                    <input type="hidden" name="p" value="<?php  echo $_GPC['p'];?>">


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
                <ul>
                    <?php  if(is_array($goods)) { foreach($goods as $g) { ?>
                    <li>
                        <div class="bor-sort">
                            <a class="img-pos" href="<?php  echo $this->createMobileUrl('shop/detail', array('my' => $_GPC['my'], 'id' => $g['id']))?>">
                            <img src="<?php  echo $g['thumb'];?>"></a>
                            <a class="sort-tit" href="#"><?php  echo $g['title'];?></a>
                            <p><em>￥<?php  echo $g['marketprice'];?></em><i>总销量<b><?php  echo $g['sales'];?></b></i>
                             <?php  if($g['productprice']>0 && $g['marketprice']!=$g['productprice']) { ?><span>￥<?php  echo $g['productprice'];?></span><?php  } ?>
                            <?php  if($discount&&$discount>0) { ?><span class="label-waring"><?php  echo $discount;?>折</span><?php  } ?>
                            </p>
                        </div>
                    </li>
                    <?php  } } ?>
                    
                </ul>
            </div>
            <div class="catepro_pageNav pageNav tc">
                <?php  echo $pager;?>
                <!--<a class="pre preDisable" href="#"><span>上一页</span></a>
                <a class="curr" href="#"><span>1</span></a>
                <a href="#"><span>2</span></a>
                <em>...</em> 
                <a href="#"><span>8</span></a>
                <a class="next " href="#"><span>下一页</span></a> </div>-->
            </script>
    </div>
</div>
</div></div>
<script id='tpl_search_list' type='text/html'>
    <ul>
    <%each list as value%>
        <li><i class="fa fa-angle-right"></i> <a href="<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%value.id%>"><%value.title%></a></li>
    <%/each%>
    </ul> 
</script>

<script id='tpl_empty' type='text/html'>
    <div class="list_no">
        <i class="fa fa-shopping-cart" style="font-size:100px;"></i>
        <br>
        <span style="line-height:18px; font-size:16px;">暂时没有相关商品</span>
        <br>主人快去给我找点其他东西吧
    </div>
    <div class="list_no_menu">
        <div class="list_no_nav" onclick="location.href='<?php  echo $this->createMobileUrl('shop/list')?>'">看看其他的</div>
    </div>
</script>
<script id="tpl_category_group" type="text/html">
  
    <div class="category_group">
        <div class='container'>
            <%each category as c%>
                <a href="javascript:;"
                level="<%c.level%>"
                name="<%c.name%>"
                <%if c.level==1%>pcate="<%c.id%>"<%/if%>
                <%if c.level==2%>ccate="<%c.id%>"<%/if%>
                <%if c.level==3%>tcate="<%c.id%>"<%/if%>
                <%if c.on %>class="on"<%/if%>>

                    <%c.name%>
                </a>
            <%/each%>
        </div>
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
           shopid:"<?php  echo $_GPC['shopid'];?>",
           key:$("#keyword").val()
    };

    require(['tpl', 'core'], function (tpl, core) {
      function getGoods() {
          $(".orderCell[data-order='<?php  echo $_GPC['order'];?>'][data-by='<?php  echo $_GPC['by'];?>']").addClass('orderActive');
          $('.detail-info').append(tpl('tpl_goods_list'));
          bindEvents();
      }


        /*
        function getGoods() {
             
            core.json('shop/list', args, function (json) {
           
             
                 
                $('.content_main').html(tpl('tpl_goods_list',json.result));
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
                $('.detail-info').append(tpl('tpl_goods_list',result));
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

        /*function bindEvents() {
            $('.good img').each(function(){
               $(this).height($(this).width()); 
            });
            $('.good').unbind('click').click(function () {
                        location.href = core.getUrl('shop/detail', {id: $(this).data('goodsid'),my:'<?php  echo $_GPC['my'];?>'});
            });
        }*/
        
        /*function bindMore() {
     
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
        }*/
 
        function reset() {
            $('#form')[0].reset();
        }
        function bindCategoryEvents(){
            
             $(".category .close").unbind('click').click(function(){
                        $(".category").animate({left:"-60%"},200);
             });
             $(".category_item").unbind('click').click(function(){
                 var item = $(this);
                     $('#keywords').val(""); $('#search_container').html('');
                     $(".category").animate({left:"-60%"},200);
                      
                      $("#isnew").val(item.data('isnew'));
                      $("#ishot").val(item.data('ishot'));
                      $("#isrecommand").val(item.data('isrecommand'));
                      $("#isdiscount").val(item.data('isdiscount'));
                      $("#istime").val(item.data('istime'));
                      $("#ccate").val(item.data('ccate'));
                      $("#tcate").val(item.data('tcate'));
                      $("#pcate").val(item.data('pcate'));
                      $("#order").val('');
                      $("#by").val('');
                      $("#goodsform").submit();
                      /*args  = {
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
                     getGoods();*/
             });
             
        }
        
        $('.filter').html(tpl('tpl_main'));
        $('.sort').click(function () {
                var display = $(".sort_list").css('display');
                if (display == 'none') {
                    $(".sort_list").fadeIn(200);
                } else {
                    $(".sort_list").fadeOut(100);
                }

        });

        

        $('.orderCell').click(function () {

              
                
                if ($(this).data('order') ==args.order && $(this).data('by') == args.by) {
                    return;
                }
             $('.orderCell').removeClass('orderActive');
                
              $(this).addClass('orderActive');
              $("#order").val($(this).data('order'));
              $("#by").val($(this).data('by'));
              $("#goodsform").submit();

                   /*args  = {
                            page:1,
                            isnew: args.isnew,
                            ishot: args.ishot,
                            isrecommand:args.isrecommand,
                            isdiscount:args.isdiscount,
                            keywords:args.keywords,
                            istime: args.istime,
                            pcate:args.pcate,
                            ccate: args.ccate,
                            tcate: args.tcate,
                            order:$(this).data('order'),
                            by:$(this).data('by'),
                            shopid:args.shopid
                     };
               
                $(".sort_list").fadeOut(200);
                getGoods();*/
        });
         $(".sort_list").fadeOut(100);
         if(category!=null){
              $(".category").animate({left:"0px"},200);
              bindCategoryEvents();
              return;
         }
         
         core.json('shop/util',{op:'category'}, function (json) {
             category = json.result;
             $('.left-shop').html(tpl('tpl_category_list',category));
             $(".category").animate({left:"0px"},200);
             bindCategoryEvents();
          }, true);
     getGoods();
        

    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>