<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>看广告/推广奖励</title><!--  看广告/推广奖励 换货码两种title 需要动态获取 对应入口-->
<style type="text/css">
    html{
        font-size: 10px;
    }
    body{
        background: #efeff4;
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
    /*自己或者粉丝获取类型导航条件*/
    .status-nav-container1{ 
        width: 100%;
        max-width: 720px;
        border-bottom: 1px solid #ccc;
        background: #fff;
    }
    .status-nav-container1 .nav-box{
        list-style-type: none;
        width: 100%;
        height: 100%;
    }
    .status-nav-container1 .nav-box .nav-item-link{
        display: block;
        float: left;
        width: 50%;
        height: 100%;
        text-align: center;
        font-size: 14px;
        color: #666;
        padding-top: 10px;
        padding-bottom: 10px;
        text-decoration: none;
    }
    .status-nav-container1 .nav-box .nav-item-link.nav-on{
        border-bottom: 1px solid #f00605;
        color: #f00605;
    }
    .status-nav-container1 .nav-box .nav-item.nav-name{
        width: 100%;
        height: 100%;
    }
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

    .ad-container{
        background: #fff;
        padding: 10px;
        margin-top: 10px;
    }
    .ad-container .ad-go-link{
        color: #000;
        text-decoration: none;
    }
    .ad-container .pic-box{
        margin-right: 10px;
        width: 80px;
        padding-top: 80px;
    }
    .ad-container .content-box{
        width: calc(100% - 190px);
    }
    .ad-container .content-box .ad-tag{
        font-size: 14px;
    }
    .ad-container .content-box .bonusad-time{
        font-size: 10px;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .ad-container .get-how-much{
        width: 90px;
        margin-left: 10px;
        color: #999;
    }
    .ad-container .content-box .line-clamp2{
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ad-container .get-how-much{
        text-align: center;
    }
    .about-envelope{
        padding: 5px 10px;
        margin-top: 10px;
        font-size: 12px;
    }
    .list-box{
        background: #fff;
        margin-top: 10px;
    }
    .no-list {
        padding: 50px 10px;
        text-align: center;
        color: #999;
    }
    .list-box .list-ul .list-item{
        border-bottom: 1px solid #eee;
        padding: 5px 0;
    }
    .list-box .list-ul .my-list-item{
        color: #f00605;
    }
    .list-box .list-ul .list-item .record-box1,
    .list-box .list-ul .list-item .record-box2{
        width: 50%;
        font-size: 12px;
    }
    .list-box .list-ul .list-item .record-box2{
        text-align: right;
    }
    .list-box .list-ul .list-item .record-box1 .user-tag{
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .list-box .list-ul .list-item .record-box1 .time-tag{
        font-size: 10px;
    }
    .list-box .list-ul .list-item .record-box2 .show-tag{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #list_loading{ 
        width: 94%;
        padding: 10px;
        color: #666;
        text-align: center;
    }
</style>
<div class="big_body">
    <div class="page_topbar">
        <a href="javascript:;" class="back" onclick="history.back()"><i class="fa fa-angle-left"></i></a>
        <div class="title">看广告/推广奖励</div> 
        <!-- 看广告/推广奖励 两种title 需要动态获取-->
    </div>
    <div class="nav-container1 status-nav-container1">
        <ul class="nav-box clearfloat">
            <a class="nav-item-link <?php  if($_GPC['status']=='1' || empty($_GPC['status'])) { ?>nav-on<?php  } ?>" href="javascript:void(0);" data-status="1">
                <li class="nav-item nav-name">
                   看广告 <!-- 看广告 status为1 或者默认 -->
                </li>
            </a>
            <a class="nav-item-link <?php  if($_GPC['status']=='2') { ?>nav-on<?php  } ?>" href="javascript:void(0);" data-status="2">
                <li class="nav-item nav-name">
                   推广奖励 <!-- 推广奖励status为2-->
                </li>
            </a>
        </ul>
    </div>
    <div class="list-box">
        <ul class="list-ul ad-container">      
            <!-- 下拉加载数据容器 -->
        </ul> 
    </div>
    <div style="width: 100%; height: 30px;background: #fff;"></div>
</div>
<!-- 两种模板 用户拆红包看广告获取现金换货码记录 或者 记录暂无数据为空 -->
<script id="tpl_list" type="text/html">
    <%each list as g%>
        <li class="list-item">
            <div class="flex-center">
                <div class="pic-container">
                    <div class="pic-box" style="background: url('<%g.thumb[0]%>') no-repeat center; background-size: contain;"></div>
                </div>
                <div class="content-box">
                    <div class="ad-tag line-clamp2">
                        <%g.title%>
                    </div>
                    <div class="bonusad-time line-clamp1"><!-- 拆红包时间 -->
                       <%g.ctime%>
                    </div>
                </div>
                <div class="get-how-much"><!-- 换货码红包价值,根据红包类型选择显示 此处为换货码 -->
                    +<%g.money%>换货码 
                </div>
            </div>
        </li>
    <%/each%>
</script>
<script id="tpl_null" type="text/html">
    <div class="no-list">暂无记录</div>
</script>
<script type="text/javascript">
    //到时候换掉请求链接
    var page = 1; 
    require(['tpl', 'core'], function(tpl, core) {
        getList(page);
        function getList(page){
            core.json('barter/code', {page:page,op:'ad',status: '<?php  echo $_GPC['status'];?>'}, function(json) {
                $('.nav-item-link').click(function() {       
                    var status = $(this).data('status'); 
                    location.href = core.getUrl('barter/code', {op:'ad',status: status});
                }); 
                if (json.result.list.length <= 0) {
                    $(".list-box .list-ul").html(tpl('tpl_null'));
                    return;
                }
                $(".list-box .list-ul").html(tpl('tpl_list',json.result));
                var loaded = false;
                var stop=true; 
                //不用点击加载实现的话,pagesize要足够大，否则会出现问题,建议pagesize:10,反正就是要超过一个手机屏高
                $(window).scroll(function(){ 
                    if(loaded){
                        return;
                    }
                    totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()); 
                    if($(document).height() <= totalheight){
                        if(stop==true){ 
                            stop=false; 
                            $('.list-box .list-ul').append('<div id="list_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载</div>');
                            page++;
                            core.json('barter/code', {page:page,op:'ad',status: '<?php  echo $_GPC['status'];?>'}, function(morejson) {  
                                stop = true;
                                $('#list_loading').remove();
                                $(".list-box .list-ul").append(tpl('tpl_list', morejson.result));
                                if (morejson.result.list.length <morejson.result.pagesize) {
                                    $('.list-box .list-ul').append('<div id="list_loading">已经加载全部</div>');
                                    loaded = true;
                                    return;
                                }
                            },true); 
                        } 
                    } 
                });
            }, true);
        }
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>