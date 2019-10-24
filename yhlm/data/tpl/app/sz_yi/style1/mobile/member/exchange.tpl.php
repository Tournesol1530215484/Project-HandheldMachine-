<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>兑换点管理</title>
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
  	.customer_top {
        height: 44px;
        width: 100%;
        background: #009BF8;
    }
    .back {
        width: 30px;
        height: 100%;
        font-size: 22px;
        border-radius: 50%;
        float: left;
        line-height: 44px;
        font-family: serif,"PingFang SC", Helvetica, "Helvetica Neue", "微软雅黑", Tahoma, Arial, sans-serif;
        font-weight: bold;
        margin-left: 10px;
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
    .customer_top .title1{
        height: 100%;
        line-height: 44px;
        display: inline-block;
        width: calc(100% - 60px);
        text-align: center;
        color:#fff;
        font-size: 1.6rem;
    }
    
    .operation-tag{
        font-size: 16px;
        padding: 5px 10px 5px 15px;
        background: #eee;
    }
    .points-info-box{
        padding: 10px;
        font-size: 15px;
    }
    .points-info-box input{
        outline: none;
    }
    .points-info-box .info-item{
        line-height: 30px;
        padding: 3px;
    }
    .points-info-box .float-l{
        float: left;
        width: 90px;
    }
    .points-info-box .float-r{
        float: right;
        width: calc(100% - 90px);
    }
    .points-info-box .float-r .show-r-appearance{
        -webkit-appearance: radio;
    }
    .points-info-box .float-r .show-c-appearance{
        -webkit-appearance: checkbox;
    }
    .points-info-box .float-r label{
        font-weight: normal;
        margin-right: 10px;
    }
    .points-info-box .float-r .whole-input{
        width: 100%;
        border: 1px solid #ddd;
        background: #fff;
        padding-left: 5px;
    }
    .points-info-box .float-r .location{
        width: 31%;
        border: 1px solid #ddd;
        background: #fff;
    }
    .points-info-box .float-r .location.btn{
        padding: 5px;
        background: #009BF8;
        color: #fff;
    }
    .help-block.tips{
        font-size: 12px;
        margin-top: 0;
        margin-bottom: 0;
    }
    .post-btn{
        color: #fff;
        background: #009BF8;
        margin-left: 10px;
    }
    /*兑换点样式*/
    .add-edit-box{
        margin-bottom: 15px;
    }
    .store {
        background: #fff;
        padding: 5px;
        border-bottom: 1px dashed #eaeaea;
    }
    .store .info .ico {
        float: left;
        height: 50px;
        width: 30px;
        line-height: 30px;
        font-size: 16px;
        text-align: center;
        color: #666;
    }
    .store .info .info1 {
        width: 100%;
        float: left;
        margin-left: -30px;
        margin-right: -60px;
        position: relative;
    }
    .store .info .info1 .inner {
        margin-left: 30px;
        margin-right: 60px;
        overflow: hidden;
    }
    .store .info .info1 .inner .store-name{
        width: 100%;
        font-size: 14px;
        color: #333;
        line-height: 25px;
        overflow: hidden;
    }
    .store .info .info1 .inner .address {
        width: 100%;
        font-size: 13px;
        color: #999;
        overflow: hidden;
        padding-top: 3px;
        padding-bottom: 3px;
    }
    .store .info .info1 .inner .tel,
    .store .info .info1 .inner .exchangedate,
    .store .info .info1 .inner .exchangetime,
    .store .info .info1 .inner .exchangestatus {
        width: 100%;
        font-size: 13px;
        color: #999;
        padding-top: 3px;
        padding-bottom: 3px;
        overflow: hidden;
    }
    .store .info .ico2 {
        height: 50px;
        width: 30px;
        padding-top: 15px;
        float: right;
        font-size: 24px;
        text-align: center;
        color: #ccc;
    }
    .store .info .ico3 {
        height: 50px;
        width: 30px;
        padding-top: 15px;
        float: right;
        font-size: 24px;
        text-align: center;
        color: #ccc;
    }
    .edit-del-tag-box{
        width: 90px;
        height: 40px;
        position: absolute;
        bottom: 0px;
        right: 0px;
    }

</style>

<div id="big_body">
	
    <div class="customer_top">
  		<div class="back" onclick='history.back()'></div>
  		<div class="title1">兑换点管理</div>
	</div> 
    <div class="points-list-container">
        <div class='panel-footer add-footer' style="display: none;">
            <!-- a标签href 给新增跳转链接 -->
            <a class='btn btn-primary' href="<?php  echo $this->createMobileUrl('member/points',array('op'=>'exchangeEdit'))?>"><i class="fa fa-plus"></i> 添加新兑换点</a>
        </div>
    </div>
    
</div>

<script type="text/html" id="tpl-loading">
    <div id="loading" style="position: absolute; top:0px; left:0px; width:100%;padding-top:200px; text-align: center; height:100%; ">
         <img   src="<?php echo MODULE_URL.'plugin/suppliermenu/res/loading.gif?'.time();?>"/>
    </div>
</script> 
<!-- 用内置变量标志分分界线 -->
<!-- if $op == 'display'} -->
<!-- 查询 兑换点列表界面start-->
<?php  if($_GPC['op'] == 'exchange') { ?>
<script id="tpl_points-list" type="text/html">
    <!-- 循环开始 -->
    <%each list as vv%>
    <div class="store">
        <div class="info clearfloat">
            <div class="ico"><i class="fa fa-building-o"></i></div>
            <div class="info1">
                <div class="inner">
                    <div class="store-name"><%vv.title%></div><!-- 兑换点名称 -->
                    <div class="address">地址: <%vv.address%></div><!-- 兑换点地址 -->
                    <div class="tel">电话: <%vv.mobile%></div><!-- 联系电话 -->
                    <div class="exchangedate">兑换日期: <%vv.exchangeDate%></div><!-- 兑换日期 -->
                    <div class="exchangetime">兑换时间: <%vv.exchangeTime%></div><!-- 兑换时间 -->
                    <div class="exchangestatus">状态: <%if vv.status == 0%>禁用<%else%>启用<%/if%></div><!-- 状态 -->
                </div>
                <div class="edit-del-tag-box">
                    <!-- 编辑按钮 到时给a标签href赋上编辑跳转链接 -->
                    <a class="btn btn-default" href="<?php  echo $this->createMobileUrl('member/points',array('op'=>'exchangeEdit'))?>&id=<%vv.id%>"><i class="fa fa-edit"></i></a>
                    <!-- 删除按钮 到时给a标签href赋上删除跳转链接 -->
                    <a class="btn btn-default" href="<?php  echo $this->createMobileUrl('member/points',array('op'=>'del','type'=>'1'))?>&id=<%vv.id%>" onclick="return confirm('确认删除此兑换点吗？');
                      "><i class="fa fa-remove"></i></a> 
                </div>
            </div>
            <a href="http://api.map.baidu.com/marker?location=<%vv.lat%>,<%vv.lng%>&amp;title=&amp;name=&amp;content=<%vv.address%>&amp;output=html">
                <div class="ico2">
                    <i class="fa fa-map-marker"></i>
                </div>
            </a><!-- 商家位置 -->
            <!-- 联系电话隐藏 -->
            <a href="tel:" style="display: 
            none"><div class="ico3"><i class="fa fa-phone"></i></div></a>
        </div>
    </div>
    <!-- 循环结束 下面的同结构到时候删掉只是为了看样式 -->
    <%/each%>
</script>
<!-- 兑换点列表相关 -->
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
        $(".add-footer").css("display","block");
        var page = 1; 
//      $(".points-list-container").append(tpl('tpl_points-list'));
        $('#big_body').dropload({
            scrollArea : window,
            loadDownFn : function(me){ 
                if(page<0) {me.noData();return ;} 
                core.json('member/points', {op:'<?php  echo $_GPC['op'];?>',page:page}, function(json) {
                	json.result.count!=0 && json.status != 0 && $(".points-list-container").append(tpl('tpl_points-list',json.result) );
                    if(json.result.count != json.result.pageNum || json.status == 0){ 
                        page=-1;  
                        me.lock(); 
                        me.noData();  
                    }else{ 
                        page++;
                    }  
                    me.resetload(); 
                }, true);
            }
        });
    });
</script>
<!-- /if} -->
<!-- 查询 兑换点列表界面end-->
<?php  } ?>
<!-- 添加 -->
<!-- if $op == 'add' || $op == 'edit'} -->
<?php  if($_GPC['op']=='exchangeEdit') { ?>
<!-- 兑换点编辑相关start -->
<script type="text/html" id="tpl-add-edit">
    <div class="operation-tag">兑换点编辑</div>
    <div class="add-edit-box">
        <form method="post" id="showDataForm">
            <!-- 下面这一串type="hidden"的input 我不太清楚有什么 也弄过来了 -->
            <input type="hidden" name="id" value="<?php  echo $addrInfo['id'];?>">
            <input type="hidden" name="op" value="post">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="p" value="suppliermenu" />
            <input type="hidden" name="method" value="dealmerch_exchange" />
            <div class="points-info-box">
                <ul>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">兑换点名称 </div>
                        <div class="float-r info-value">
                            <input class="whole-input" type="text" name="title" value="<?php  echo $addrInfo['title'];?>" placeholder="兑换点名称">
                        </div>
                    </li>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">兑换点地址 </div>
                        <div class="float-r info-value">
                            <input class="whole-input" type="text" name="address" value="<?php  echo $addrInfo['address'];?>" placeholder="兑换点地址">
                        </div>
                    </li>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">联系电话 </div>
                        <div class="float-r info-value">
                            <input class="whole-input" type="text" name="mobile" value="<?php  echo $addrInfo['mobile'];?>" placeholder="联系电话">
                        </div>
                    </li>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">商家位置 </div>
                        <div class="float-r info-value">
                            <input class="location" type="text" name="lng" value="<?php  echo $addrInfo['lng'];?>" placeholder="地理经度" autocomplete="off" class="input-lng">
                            <input class="location" type="text" name="lat" value="<?php  echo $addrInfo['lat'];?>" placeholder="地理纬度" class="input-lat">
                            <button onclick="showCoordinate(this);" class="btn btn-default location" type="button">选择坐标</button>
                        </div>
                    </li>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">兑换日期 </div>
                        <div class="float-r info-value">
                            <label for="week1" style="user-select:none;">
                            <input class="show-c-appearance" id="week1" type="checkbox" name="exchangeDate[]" <?php  if(in_array('1',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="1"/>周一
                            </label>
                            <label for="week2" style="user-select:none;">
                                <input class="show-c-appearance" id="week2" type="checkbox" name="exchangeDate[]" <?php  if(in_array('2',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="2"/>周二
                            </label>
                            <label for="week3" style="user-select:none;">
                                <input class="show-c-appearance" id="week3" type="checkbox" name="exchangeDate[]" <?php  if(in_array('3',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="3"/>周三
                            </label>
                            <label for="week4" style="user-select:none;">
                                <input class="show-c-appearance" id="week4" type="checkbox" name="exchangeDate[]" <?php  if(in_array('4',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="4"/>周四
                            </label>
                            <label for="week5" style="user-select:none;">
                                <input class="show-c-appearance" id="week5" type="checkbox" name="exchangeDate[]" <?php  if(in_array('5',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="5"/>周五
                            </label>
                            <label for="week6" style="user-select:none;">
                                <input class="show-c-appearance" id="week6" type="checkbox" name="exchangeDate[]" <?php  if(in_array('6',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="6"/>周六
                            </label>
                            <label for="week7" style="user-select:none;"> 
                                <input class="show-c-appearance" id="week7" type="checkbox" name="exchangeDate[]" <?php  if(in_array('7',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="7"/>周日
                            </label>
                        </div>
                    </li>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">兑换时间 </div>
                        <div class="float-r info-value">
                            <input class="whole-input" value="<?php  echo $addrInfo['exchangeTime'];?>" type="text" name="exchangeTime" value="" placeholder="兑换时间">
                            <span class='help-block tips'>如:07:00 - 22:00</span>
                        </div>
                    </li>
                    <li class="info-item clearfloat">
                        <div class="float-l info-tag">是否启用 </div>
                        <div class="float-r info-value">
                            <label for="s1">
                            <input class="show-r-appearance" id="s1" type="radio" name="status" <?php  if($addrInfo['status'] == 1 ) { ?>checked<?php  } ?> value="1" />启用  &nbsp;&nbsp;&nbsp;
                            </label>
                            <label for="s0"> 
                                <input class="show-r-appearance" id="s0" type="radio" name="status" <?php  if($addrInfo['status'] == 0 ) { ?>checked<?php  } ?> value="0"/>禁用
                            </label> 
                        </div>
                    </li>
                </ul>
            </div>
            <div class="footer">
                <input type="button" class="btn post-btn"  value="提交"/>
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <input type="button" onclick='history.back()' value="返回列表" class="btn btn-default"/>
            </div>
        </form>  
    </div>
</script> 

<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {
        //core.pjson('suppliermenu/merchinfo', {op:'showinfo'}, function(json) {
        $('#big_body').append(tpl('tpl-add-edit'));
        $('.post-btn').click(function(){  
            var title = $('input[name="title"]').val();   
            var address = $('input[name="address"]').val(); 
            var mobile = $('input[name="mobile"]').val(); 
            var lng = $('input[name="lng"]').val(); 
            var lat = $('input[name="lat"]').val();
            //兑换日期是一个数组
            var exchangeDate = new Array();
            $("input[name='exchangeDate[]']:checked").each(function(){
                exchangeDate.push($(this).val());
            });
            var exchangeTime = $('input[name="exchangeTime"]').val(); 
            var status = $('input[name="status"]:checked').val(); 

            if($.trim(title).length == 0){ 
                core.tip.show("请输入兑换点名称");  
                return false;  
            } 
            if($.trim(address).length == 0){ 
                core.tip.show("请输入兑换点地址");  
                return false;  
            } 
            if($.trim(mobile).length == 0){ 
                core.tip.show("请输入联系电话");  
                return false;  
            } 
            if($.trim(lng).length == 0){ 
                core.tip.show("请选择商家位置");  
                return false;  
            } 
            if($.trim(lat).length == 0){ 
                core.tip.show("请选择商家位置");  
                return false;  
            } 
            if(exchangeDate.length == 0){ 
                core.tip.show("请选择兑换日期");  
                return false;  
            } 
            if($.trim(exchangeTime).length == 0){ 
                core.tip.show("请输入兑换时间");  
                return false;  
            } 
            if($.trim(status).length == 0){ 
                core.tip.show("请选择状态");  
                return false;  
            } 
            var data = { 
            	<?php  if($_GPC['id']) { ?>'id':"<?php  echo $_GPC['id'];?>",<?php  } ?>
                'op':'exchangeEdit', 
                'ac':'exdosave',
                'title'  : title, 
                'address' : address,
                'mobile' : mobile,
                'lng' : lng,
                'lat' : lat,
                'exchangeDate' : exchangeDate,
                'exchangeTime' : exchangeTime,
                'status': status
            };  

            core.json('member/points', data, function(json) {
                if (json.status == 1) {
                    core.tip.show('操作成功！');
                    window.location.href=json.result;
                } else if (json.status == 0) {
                    core.tip.show(json.result);
                }
            }, true,true);
        });

        //}, true,true);
    });
    //获取精准定位
    function showCoordinate(elm) {
        require(["util"], function(util){
            var val = {};
            val.lng = parseFloat($(elm).prev().prev().val());
            val.lat = parseFloat($(elm).prev().val());
            util.map(val, function(r){
                $(elm).prev().prev().val(r.lng);
                $(elm).prev().val(r.lat);
            });

        });
    }
</script>
<!-- 兑换点编辑相关end -->
<!-- {/if -->
<?php  } ?>



<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
