<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>客户咨询</title>
<link rel="stylesheet" href="../addons/sz_yi/template/mobile/default/member/merch/css/dropload.css">
<script src="../addons/sz_yi/template/mobile/default/member/merch/js/dropload.min.js"></script>
<style type="text/css">
    html{
        font-size: 10px;
    }
    #big_body{width:100%;margin:0px; float: left;}
  	.customer_top {height: 44px; width: 100%; background: #009BF8;  border-bottom: 1px solid #ccc;}
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
    .msg-list{
        margin-top: 15px;
        margin-bottom: 20px;
    }
    .msg-list .msg-box{
        background: #fff;
        padding: 10px 3% 20px;
        margin-top: 10px;  
        font-size: 1.2rem;  
    }
    .msg-list .msg-box .msg-time{
        padding-top: 3px;
        padding-bottom: 3px;
        color: #9c9c9c;
    }
    .msg-list .msg-box .msg-title{
        padding-top: 3px;
        padding-bottom: 3px;
        color: #009BF8;
    }
    .no-msg{
        text-align: center;
        color: #9c9c9c;
        margin-top: 100px;
    }      
</style>

<div id="big_body">
    <div class="customer_top">
  		<div class="back" onclick='history.back()'></div>
  		<div class="title1">客户咨询</div>
	</div> 
    <div class="msg-list">
        <!-- 这里循环消息记录 -->
        <!--<div class="msg-box" onclick="javascript:void(0);">
            <div class="msg-time">2018-06-11 16:09</div>
            <div class="msg-title">张银清～易货联盟～产品策划师/张银清</div>
            <div class="msg-content">无非相遇别离、无非是找到自己的位置，并安然地待下去。除此之外，别无其他。值得庆幸的是，我们依然能够剔除掉表面的玩意，内敛而踏实地活着。在灿烂的夜晚里，依然能够笑着流出了泪。</div>
        </div>
        <div class="msg-box" onclick="javascript:void(0);">
            <div class="msg-time">2018-06-11 16:09</div>
            <div class="msg-title">张银清～易货联盟～产品策划师/张银清</div>
            <div class="msg-content">无非相遇别离、无非是找到自己的位置，并安然地待下去。除此之外，别无其他。值得庆幸的是，我们依然能够剔除掉表面的玩意，内敛而踏实地活着。在灿烂的夜晚里，依然能够笑着流出了泪。</div>
        </div>
        <div class="no-msg">暂无咨询~</div>-->
        <!-- 循环end 后要删 -->
    </div>
</div>
<script id="tpl_msg" type="text/html">
    <%each list as g%> 	
        <div class="msg-box" onclick="location.href='<%g.url%>'">
            <div class="msg-time"><%g.time%></div> <!-- 咨询时间 -->
            <div class="msg-title"><%g.nickname%></div> <!-- 用户名 -->
            <div class="msg-content"><%g.content%></div> <!-- 消息 -->
        </div>   
    <%/each%>  
</script>  	 	 		
<script id="tpl_null" type="text/html">
    <div class="no-msg">暂无咨询~</div>
</script> 
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
        var page = 1; 
        $('#big_body').dropload({
            scrollArea : window,
            loadDownFn : function(me){ 
                if(page<0) {me.noData();return ;} 
                core.json('member/consult', {op:'message',page:page}, function(json) {
//                json.status代表什么的? 这里给一个变量判断总的咨询数量为空，要不就去掉暂无咨询模板的附加
//                  // json.status == 0 不是预想标志效果，所以下面代码注释   
                    // if(json.status == 0) {
                    //    $(".msg-list").append(tpl('tpl_null'));
                    //    core.tip.show("暂无咨询");
                    // }  
                    if(json.result.count != json.result.pageNum || json.status == 0){ 
                        page=-1;  
                        me.lock(); 
                        me.noData();  
                    }else{ 
                        page++;
                    }  
                    json.result.count!=0 && json.status != 0 && $(".msg-list").append(tpl('tpl_msg',json.result));
                    me.resetload(); 
                }, true);
            }
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
