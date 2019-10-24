<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>排行榜</title><!-- 这里要动态获取标题，内置标签变量？ -->
<link rel="stylesheet" href="../addons/sz_yi/template/mobile/default/member/merch/css/dropload.css">
<script src="../addons/sz_yi/template/mobile/default/member/merch/js/dropload.min.js"></script>
<style type="text/css">
    html{
        font-size: 10px;
    }
    body{
        background: #fff;
    }
    #big_body{width:100%;margin:0px; float: left;}
    .customer_top {height: 44px; width: 100%; background: #f00605;  border-bottom: 1px solid #ccc;}
    .back{
        height: 100%;
        line-height: 44px;
        width: 44px;
        margin-left: 10px;
        float: left;
        font-size: 16px;
        color: #fff;
        position: relative;
    }
    /*返回大于号按钮样式*/
    .back:after {
      content: "";
      display: block;
      clear: both;
      width: 10px;
      height: 10px;
      border-left: 2px solid rgb(255,255,255);
      border-bottom: 2px solid rgb(255,255,255);
      position: absolute;
      left: 10%;
      top: 50%;
      margin-top: -2px;
      -moz-transform: rotate(45deg) translateY(-50%);
      -ms-transform: rotate(45deg) translateY(-50%);
      -o-transform: rotate(45deg) translateY(-50%);
      -webkit-transform: rotate(45deg) translateY(-50%);
      transform: rotate(45deg) translateY(-50%);
    }
    .customer_top .title1{height: 100%;line-height: 44px;display: inline-block;width: calc(100% - 60px);text-align: center;color:#fff;font-size: 1.6rem;}
    .back{width: 30px; height: 100%;font-size: 22px;border-radius: 50%;float: left;line-height: 44px; font-family:serif,"PingFang SC", Helvetica, "Helvetica Neue", "微软雅黑", Tahoma, Arial, sans-serif;font-weight: bold;}
    /*flex居中*/
    .flex-center {
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }

    .sort-box{
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .no-sort{
        text-align: center;
        color: #999;
    }
    .sort-box .sort-ul .sort-item{
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    .sort-box .sort-ul .goods-link,
    .sort-box .sort-ul .shop-link{
        display: block;
        text-decoration: none;
        color: #000;
    }
    .sort-box .sort-ul .goods-link:visited,
    .sort-box .sort-ul .shop-link:visited,
    .sort-box .sort-ul .goods-link:active,
    .sort-box .sort-ul .shop-link:active{
        color: #000;
    }
    .sort-box .sort-ul .sort-item .floatl{
        float: left;
        margin-right: 10px;
        position: relative;
    }
    .sort-box .sort-ul .sort-item .floatl:after{
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        height: 0;
        width: 0;
        border-top: 16px solid #BDC0BA;
        border-left: 16px solid #BDC0BA;
        border-right: 16px solid transparent;
        border-bottom: 16px solid transparent;
        z-index: 2;
    }
    .sort-box .sort-ul .sort-item:nth-child(1) .floatl:after{ 
        border-top: 16px solid #ffb11b;
        border-left: 16px solid #ffb11b;
        border-right: 16px solid transparent;
        border-bottom: 16px solid transparent;
    }
    .sort-box .sort-ul .sort-item:nth-child(2) .floatl:after{
        border-top: 16px solid #e98b2a;
        border-left: 16px solid #e98b2a;
        border-right: 16px solid transparent;
        border-bottom: 16px solid transparent;
    }
    .sort-box .sort-ul .sort-item:nth-child(3) .floatl:after{
        border-top: 16px solid #E9A368;
        border-left: 16px solid #E9A368;
        border-right: 16px solid transparent;
        border-bottom: 16px solid transparent;
    }
    .sort-box .sort-ul .sort-item .floatl .pic-box{
        width: 100px;
        height: 100px;
        position: relative;
    }
    .sort-box .sort-ul .sort-item .floatl .pic-box .sort-num{
        position: absolute;
        width: 20px;
        height: 20px;
        top: 0;
        left: 5px;
        color: #fff;
        z-index: 3;
        font-size: 1.2rem;
    }
    .sort-box .sort-ul .sort-item:nth-child(1) .floatl .pic-box .sort-num,
    .sort-box .sort-ul .sort-item:nth-child(2) .floatl .pic-box .sort-num,
    .sort-box .sort-ul .sort-item:nth-child(3) .floatl .pic-box .sort-num{
        font-size: 1.4rem;
    }
    .sort-box .sort-ul .sort-item .floatr{
        float: right;
        width: calc(100% - 110px);
    }
    .sort-box .sort-ul .goods-tag,
    .sort-box .sort-ul .shop-tag{
        margin-bottom: 5px;
    }
    .sort-box .sort-ul .goods-tag .goods-link{
        font-size: 1.4rem;
    }
    .sort-box .sort-ul .shop-tag .shop-link{
        font-size: 1.2rem;
        color: #333;
    }
    .sort-box .sort-ul .sales-tag,
    .sort-box .sort-ul .value-tag{
        font-size: 1.2rem;
        color: #999;
    }
    .sales-value-box .sales-tag{
        float: left;
    }
    .sales-value-box .value-tag{
        float: right;
    }
    .font-color{
        color: #f00605;
    }
    /*时间导航条件*/
    .time-nav-container{ 
        width: 100%;
        max-width: 720px;
        border-bottom: 1px solid #ccc;
    }
    .time-nav-container .nav-box{
        list-style-type: none;
        width: 100%;
        height: 100%;
    }
    .time-nav-container .nav-box .nav-item-link{
        display: block;
        float: left;
        width: 25%;
        height: 100%;
        text-align: center;
        font-size: 14px;
        color: #000;
        padding-top: 10px;
        padding-bottom: 10px;
        text-decoration: none;
    }
    .time-nav-container .nav-box .nav-item-link.nav-on{
        border-bottom: 1px solid #f00605;
    }
    .time-nav-container .nav-box .nav-item.nav-name{
        width: 100%;
        height: 100%;
    }
    
</style>

<div id="big_body">
    <div class="customer_top">
        <div class="back" onclick='history.back()'></div>
        <div class="title1">换货商品销量排行榜</div>
    </div>
    <div class="nav-container time-nav-container">
        <ul class="nav-box clearfloat">
            <a class="nav-item-link time-1 nav-on" href="javascript:void(0);" data-time="1">
                <li class="nav-item nav-name">
                   1天
                </li>
            </a>
            <a class="nav-item-link time-7" href="javascript:void(0);" data-time="7">
                <li class="nav-item nav-name">
                   7天
                </li>
            </a>
            <a class="nav-item-link time-30" href="javascript:void(0);" data-time="30">
                <li class="nav-item nav-name">
                   30天
                </li>
            </a>
            <a class="nav-item-link time-90" href="javascript:void(0);" data-time="90">
                <li class="nav-item nav-name">
                    90天
                </li>
            </a>
        </ul>
    </div>
    <div class="sort-box">
        <ul class="sort-ul">      
            <!-- 这里循环排行记录 或者 排行记录暂无数据为空 两种选择-->
            <!-- 这里循环排行记录 或者 排行记录暂无数据为空end 后要删 只是看样式 -->
        </ul> 
    </div>
</div>
<!-- 两种模板 排行记录 或者 排行记录暂无数据为空 -->
<script id="tpl_sort" type="text/html">
    <%each list as g%>
        <li class="sort-item clearfloat flex-center">
            <a href="<%g.goodsurl%>" class="goods-link floatl"><!-- 商品图片(背景图形式) -->
                <div class="pic-box" style="background: url('<%g.thumb%>') no-repeat center; background-size: cover;">
                    <div class="sort-num"><%g.num%></div> <!-- 排行序号 -->
                </div>
            </a>
            <div class="content-box floatr">
                <div class="goods-tag">
                    <a href="<%g.goodsurl%>" class="goods-link"><!-- 商品名称 -->
                    <%g.title%>
                    </a> 
                </div>
                <div class="shop-tag">  
                    <a href="<%g.merchurl%>" class="shop-link"><!-- 店家名称 --> 
                    <!-- <%g.merchname%> -->
                    </a> 
                </div>
                <div class="sales-value-box"> 
                    <div class="sales-tag">易出: <span class="font-color"><%g.total%></span> 件</div><!-- 销售数量 -->
                    <div class="value-tag">价值: <span class="font-color">￥<%g.price%></span></div><!-- 销售价值 -->
                </div>
                
            </div>
        </li>
    <%/each%>
</script>
<script id="tpl_null" type="text/html">
    <div class="no-sort">暂无排行</div>
</script>
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
        var page = 1; 
        var current_time = 1; 
        $(".nav-item-link").click(function(){
            if($(this).hasClass('nav-on')){
                return;
            }
            page = 1;
            current_time = $(this).data("time");
            //console.log(page + " " + current_time);
            $(this).addClass('nav-on').siblings().removeClass("nav-on");
            $('.dropload-down').remove();
            getSort(current_time);
        });
        function bindDropLoad(){
            $('#big_body').dropload({ 
                scrollArea : window,  
                loadDownFn : function(me){
                    if(page<0) {me.noData();return ;} 
                        core.json('barter/index', {op:'sort',page:page,time:current_time}, function(json) {
                            //json.result.total 总是获取这一类型消息的全部条数，即使是分页下拉获取更多数据
                            /*if(json.result.count == 0){ 
                                $(".sort-box .sort-ul").append(tpl('tpl_null',json.result));
                                me.noData();
                                return;
                            }  */
                            if(json.result.count != json.result.pageNum || json.status == 0){ 
                                page=-1;   
                                me.lock(); 
                                me.noData();    
                            }else{ 
                                page++;
                            } 
                            json.status !=0 && $(".sort-box .sort-ul").append(tpl('tpl_sort',json.result) );
                            me.resetload(); 
                    }, true);
                    //console.log("drop:"+page);
                }
            });
        }
        function getSort(time){
            core.json('barter/index', {op:'sort',page:page,time:time}, function (json) {
                if(json.status == 0){ 
                    $(".sort-box .sort-ul").html(tpl('tpl_null'));
                    page=-1;
                    return;
                }
                $(".sort-box .sort-ul").html(tpl('tpl_sort',json.result));
                page++;
                bindDropLoad();
            }, true);
        }
        getSort(1);
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>