<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>积分</title>
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
    #list-type-box{width:100%; background: #fff;}
    #list-type-box .list-type{float: left; width: 100%; background: #fff;border-bottom: 2px solid #dddede;;}
    #list-type-box .list-type li{float: left; width: 33.33%; text-align: center; color: #787878;height: 35px;}
    #list-type-box .list-type li>span{display: block;width: 80px;height: 100%;line-height: 35px;margin: 0 auto;}
    #list-type-box .list-type li.action>span{color: #FF3737;border-bottom: 2px solid #FF3E3E;}
    .tab_con{
        height: auto;
        width: 100%;
        padding: 5px 0 10px 0;
        overflow: hidden;
    }
    .tab_con .con{
        height: auto;
        display: none;
        color: #333;
        word-break: break-all;
        margin-bottom: 80px; 
    }
    .record-box{
        margin-bottom: 20px;
    }
    .record-box .record-item{
        background: #fff;
        padding: 5px 3%;
        margin-top: 10px;  
        font-size: 1.2rem;  
    }
    .record-box .record-item>span{
        display: inline-block;
        height: 100%;
    }
    
    .record-box .record-item .time{
        color: #9c9c9c;
        width: 100%;
        padding-bottom: 2px;
    }
    .record-box .record-item .descript{
        width: 70%;
        float: left;
        margin-right: 5%;
    }
    .record-box .record-item .num{
        color: #f3403f;
        width: 25%;
        float: right;
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
  		<div class="title1">积分</div>
	</div>
    <div id="list-type-box">
        <ul class="list-type">
            <li id="type-tag1" class="type-tag action" onclick="tab(1)" type="1">
                <span>全部</span>
            </li>
            <li id="type-tag2" class="type-tag" onclick="tab(2)" type="2">
                <span>赚取</span>
            </li>
            <li id="type-tag3" class="type-tag" onclick="tab(3)" type="3">
                <span>消费</span>
            </li>
        </ul>
    </div>
    <div class="tab_con">
        <div class="con record-box active" id="con_1" style='display:block'>
            <ul class="record-ul"> 
                <!-- 这里循环该类型积分记录 或者 该类型积分记录为空-->
                <!--<li class="record-item clearfloat">
                    <span class="time">2018-07-05 16:09:23</span>
                    <span class="descript">描述</span>
                    <span class="num">+2018</span>
                </li>
                <li class="record-item clearfloat">
                    <span class="time">2018-07-05 16:06:38</span>
                    <span class="descript">会员注册赠送积分</span>
                    <span class="num">-1000</span>
                </li> -->
                <div class="no-record">暂无记录哦~</div>
                <!-- 这里循环该类型积分记录 或者 该类型积分记录为空end 后要删 只是看样式 -->
            </ul>
        </div>
        <div class="con record-box" id="con_2">
            <ul class="record-ul"> 
                <!-- 这里循环该类型积分记录 或者 该类型积分记录为空-->
                <!--<li class="record-item clearfloat">
                    <span class="time">2018-07-05 16:06:38</span>
                    <span class="descript">会员注册赠送积分</span>
                    <span class="num">-1000</span>
                </li> -->
                <div class="no-record">暂无记录哦~</div>
                <!-- 这里循环该类型积分记录 或者 该类型积分记录为空end 后要删 只是看样式 -->
            </ul>
        </div>
        <div class="con record-box" id="con_3" >
            <ul class="record-ul"> 
                <!-- 这里循环该类型积分记录 或者 该类型积分记录为空-->
                <!--<li class="record-item clearfloat">
                    <span class="time">2018-07-05 16:09:23</span>
                    <span class="descript">描述</span>
                    <span class="num">+2018</span>
                </li>
                <li class="record-item clearfloat">
                    <span class="time">2018-07-05 16:06:38</span>
                    <span class="descript">会员注册赠送积分</span>
                    <span class="num">-1000</span>
                </li> -->
                <div class="no-record">暂无记录哦~</div>
                <!-- 这里循环该类型积分记录 或者 该类型积分记录为空end 后要删 只是看样式 -->
            </ul>
        </div>
    </div>
</div>
<script id="tpl_msg" type="text/html">
    <%each messgae as g%>
        <li class="record-item clearfloat">
            <span class="time">2018-07-05 16:09:23</span>
            <span class="descript">描述</span>
            <span class="num">+2018</span>
        </li>
    <%/each%>
</script>
<script id="tpl_null" type="text/html">
    <div class="no-record">暂无转账记录哦~</div>
</script>
<script type="text/javascript">
    function tab(n){
        $('#con_'+n).fadeIn().addClass('active');;
        $('#con_'+n).siblings().hide().removeClass('active');
        $('#type-tag'+n).addClass('action');
        $('#type-tag'+n).siblings().removeClass('action');
    }
</script>
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
        var page1 = 0;//为了标记不同类型积分的页码
        var page2 = 0;
        var page3 = 0;
        $('#big_body').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                //这里获取选择的积分标签类型
                var $type = $(".type-tag.action").attr("type");
                if(page<0) {me.noData();return ;}
                core.pjson('barter/score', {op:'get',merch:<?php  echo $_GPC['merch'];?>,page:page,type:$type}, function(json) {
                        //json.result.total 总是获取这一类型消息的全部条数，即使是分页下拉获取更多数据
                        if(json.result.total == 0){
                            $("record-box.action .record-ul").append(tpl('tpl_null',json.result));
                            me.noData();
                            return;
                        }
                        
                        if(json.result.status==true){
                            if($type == 1){
                                page1++;
                            }else if($type == 2){
                                page2++;
                            }else{
                                page3++;
                            } 
                        }else{ 
                            if($type == 1){
                                page1= -1;
                            }else if($type == 2){
                                page2 = -1;
                            }else{
                                page3 = -1;
                            }
                            me.lock();
                            me.noData();  
                        }
                        $("record-box.action .record-ul").append(tpl('tpl_msg',json.result));
                        me.resetload(); 
                }, true);
            }
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
