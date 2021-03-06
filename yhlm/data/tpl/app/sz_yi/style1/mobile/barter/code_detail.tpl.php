<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>
<?php  if($_GPC['ac']=='put') { ?>
好友转入 
<?php  } else if($_GPC['ac']=='get') { ?>
好友转出
<?php  } else if($_GPC['ac']=='saler') { ?>
销售获得
<?php  } else if($_GPC['ac']=='use') { ?>
换货消耗的
<?php  } else if($_GPC['ac']=='refund') { ?>
退单退款记录(商家)
<?php  } else if($_GPC['ac']=='refunduse') { ?>
退单退款记录
<?php  } ?>
</title><!-- 这里要动态获取标题，内置标签变量？ -->
<link rel="stylesheet" href="../addons/sz_yi/template/mobile/default/member/merch/css/dropload.css">
<script src="../addons/sz_yi/template/mobile/default/member/merch/js/dropload.min.js"></script>
<style type="text/css">
    html{
        font-size: 10px;
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
    .record-box{
        margin-top: 15px;
        margin-bottom: 20px;
    }
    .record-box .record-item{
        background: #fff;
        padding: 10px 3%;
        margin-top: 10px;  
        font-size: 1.4rem;
    }
    .record-box .record-item>span{
        display: inline-block;
        height: 100%;
    }
    
    .record-box .record-item .time{
        color: #9c9c9c;
        width: 100%;
        padding-bottom: 3px;
    }
    .record-box .record-item .action{
        width: 40%;
        float: right;
        color: #f3403f;
    }
    .record-box .record-item .num{
        width: 60%;  
        float: left; 
    }
    .no-record{
        text-align: center;
        color: #9c9c9c;
        margin-top: 100px;
    }      
</style>

<div id="big_body">
    <div class="customer_top">
  		<div class="back" onclick='history.back()'></div>
  		<div class="title1">
        <?php  if($_GPC['ac']=='put') { ?>
        好友转入 
        <?php  } else if($_GPC['ac']=='get') { ?>
        好友转出
        <?php  } else if($_GPC['ac']=='saler') { ?>
        销售获得
        <?php  } else if($_GPC['ac']=='use') { ?>
        换货消耗的
        <?php  } else if($_GPC['ac']=='refund') { ?>
        退单退款记录(商家)
        <?php  } else if($_GPC['ac']=='refunduse') { ?>
        退单退款记录
        <?php  } ?>            
        </div><!-- 这里要动态获取标题，内置标签变量？ 同上面的title标签-->
	</div>
    
    <div class="record-box">
        <ul class="record-ul">      
            <!-- 这里循环转账记录 或者 转账记录为空-->
           <!--  <li class="record-item clearfloat">
                <span class="time">2018-07-05 16:09:23</span>
                <span class="action">转入</span>
                <span class="num">+2018</span>
            </li>
            <li class="record-item clearfloat">
                <span class="time">2018-07-05 16:06:38</span>
                <span class="action">转出</span>
                <span class="num">-1000</span>
            </li>  -->
            <!-- <div class="no-record">暂无转账记录哦~</div> -->
            <!-- 这里循环转账记录 或者 转账记录为空end 后要删 只是看样式 -->
        </ul> 
    </div>
</div>
<script id="tpl_msg" type="text/html">
    <%if ac == 'put'%>
    <%each list as g%>
        <li class="record-item clearfloat">
            <span class="time"><%g.time%></span>  
            <span class="action">转入<%g.currency%></span>
            <span class="num">转入方 : <%g.realname%>/<%g.nickname%> </span>
            <br clear="all" />                 
        </li>
    <%/each%>  
    <%else if ac =='get'%>
     <%each list as g%>
        <li class="record-item clearfloat">
            <span class="time"><%g.time%></span>  
            <span class="action">转出<%g.currency%></span>
            <span class="num">转出方 : <%g.realname%>/<%g.nickname%> </span>
            <br clear="all" />               
        </li>
    <%/each%>
    <%else if ac =='saler'%> 
     <%each list as g%>
        <li class="record-item clearfloat"> 
            <span class="time"><%g.dealtime%></span>   
            <span class="action" style="width:100%;"><%g.note%><%g.currency%></span> 
            <span class="num" style="width:100%;">交易单号 : <%g.dealsn%></span> 
            <br clear="all" />                 
        </li> 
    <%/each%>  
    <%else if ac =='use'%>
     <%each list as g%> 
        <li class="record-item clearfloat">
            <span class="time"><%g.dealtime%></span>  
            <span class="num" style="width:100%;"><%g.note%><%g.currency%></span>
            <span class="action" style="width:100%;">交易单号 : <%g.dealsn%></span>
            <br clear="all" />                  
        </li> 
    <%/each%>
    <%else if ac =='refund'%>
     <%each list as g%> 
        <li class="record-item clearfloat">  
            <span class="num" style="width:100%;">购买人 :<%g.nickname%> 价格 :<%g.price%></span>
            <span class="time">退款时间 : <%g.refundtime%></span>   
            <span class="action" style="width:100%;">交易单号 : <%g.ordersn%></span>
            <br clear="all" />                  
        </li> 
    <%/each%>
    <%else if ac =='refunduse'%>
     <%each list as g%> 
        <li class="record-item clearfloat">  
            <span class="num" style="width:100%;">购买人 :<%g.nickname%> 价格 :<%g.goodsprice%>换货码 + <%g.dispatchprice%> 邮费</span>
            <span class="time">退款时间 : <%g.refundtime%></span>                
            <span class="action" style="width:100%;">交易单号 : <%g.ordersn%></span>
            <br clear="all" />                  
        </li> 
    <%/each%>
    <%/if%> 
</script>    
<script id="tpl_null" type="text/html">
    <div class="no-record">暂无转账记录哦~</div>
</script>
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
        var page = 1; 
        $('#big_body').dropload({   
            scrollArea : window, 
            loadDownFn : function(me){ 
                if(page<0) {me.noData();return ;}  
                core.json('barter/code', {op:'detail',ac:'<?php  echo $_GPC['ac'];?>',page:page}, function(json) {
                        //json.result.total 总是获取这一类型消息的全部条数，即使是分页下拉获取更多数据
                                                
                        if(json.result.count != json.result.pageNum){  
                            page=-1;    
                            me.lock();  
                            me.noData();   
                        }else{ 
                            page++;
                        } 
                        json.result.count !=0 && $(".record-box .record-ul").append(tpl('tpl_msg',json.result) );
                        me.resetload();  
                }, true);
            }
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>