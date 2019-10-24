<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?> 
<script src="../addons/sz_yi/static/js/dist/bootstrap.min.js" type="text/javascript"></script>
<style type="text/css">
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
    .border_bg{ border: 1px #ccc solid;border-radius: 4px; margin-bottom: 10px; background-color: #fff;}
    .border_bg .panel-heading{ background-color: #e8ecef; color: #000; }
    .control-label, label, .help-block{
        font-size: 12px;
        color: #717171;
    }
    .help-block {
        color: #a3a3a3;
        margin: 0;
        padding-top: 5px;
    }
    .status-box .show-apperance{
        -webkit-appearance: radio;
        display: inline-block;
        height: 14px;
    }
    .vertical-box{
        line-height: 25px;
    }
    .post-btn{
        margin-left: 15px;
    }
    .admin-list-form .admin-list-container{
        background: #fff;
    }
    .admin-box .admin-item{
        font-size: 12px;
        width: 50%;
        float: left;
        padding: 10px;
        border-bottom: 1px dashed #ddd; 
    }
    .admin-box .admin-item:nth-child(odd){
        border-right: 1px dashed #ddd;
    }
    .admin-item-tag{
        margin-bottom: 10px;
    }
    .search-members-result,
    .search-store-result{
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .search-members-result .member-item{
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 14px;
        border-bottom: 1px dashed #ddd;
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
    }
    .store .info .info1 .inner {
        margin-left: 30px;
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
    .store .info .info1 .inner .tel {
        width: 100%;
        font-size: 13px;
        color: #999;
        padding-top: 3px;
        padding-bottom: 3px;
        overflow: hidden;
    }
</style>
<div class="customer_top">
    <div class="back" onclick="history.back()"></div>
    <div class="title1">兑换管理员</div>
</div>
<?php  if($_GPC['op']=='detail') { ?><!-- $operation == 'post' --> <!-- 用if else内置标签控制 兑换管理员列表 和 兑换管理员编辑 的显示 -->
<div class="main">
    <form  method="post" class="form-horizontal form" enctype="multipart/form-data">
        <input type="hidden" name="i" value="<?php  echo $_GPC['i'];?>" />
        <input type="hidden" name="c" value="entry" />
        <input type="hidden" name="p" value="points" />
        <input type="hidden" name="m" value="sz_yi" />
        <input type="hidden" name="op" value="detail" />
        <input type="hidden" name="do" value="member" />
        <input type="hidden" name="ac" value="dosave" />
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
        <div class='panel panel-default' style="background-color: transparent; border: none;">
            <div class="border_bg">
                <div class='panel-heading'>管理员设置</div>
                <div class='panel-body'> 
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 选择管理员</label>
                            <div class="col-sm-9 col-xs-12">
                                <input type='hidden' id='openid' name='openid' value="<?php  echo $item['openid'];?>" />
                                <div class='input-group'>
                                    <input type="text" name="saler" maxlength="30" value="<?php  if(!empty($saler)) { ?><?php  echo $saler['nickname'];?>/<?php  echo $saler['realname'];?>/<?php  echo $saler['mobile'];?><?php  } ?>" id="saler" class="form-control" readonly />
                                    <div class='input-group-btn'>
                                        <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus').modal();">选择管理员</button>
                                    </div>
                                </div>
                                <?php  if(!empty($saler)) { ?><!--img 的src $saler['avatar'] -->
                                <span class='help-block'><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $saler['avatar'];?>"/></span>
                                <?php  } ?>
                                <div id="modal-module-menus"  class="modal fade" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择管理员</h3></div>
                                            <div class="modal-body" >
                                                <div class="row">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="请输入粉丝昵称/姓名/手机号" />
                                                        <span class='input-group-btn'><button type="button" class="btn btn-default search-members-btn">搜索</button></span>
                                                    </div>
                                                </div>
                                                <div id="module-menus" style="padding-top:5px;"></div>
                                            </div>
                                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 管理员姓名</label>
                            <div class="col-sm-9 col-xs-12">
                                <input type="text" name="salername" class="form-control" value="<?php  echo $item['salername'];?>" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 联系电话</label>
                            <div class="col-sm-9 col-xs-12"> 
                                <input type="text" name="mobile" class="form-control" value="<?php  echo $item['mobile'];?>" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        </div>
                    </div>      
                     <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label">所属兑换点</label>
                            <div class="col-sm-9 col-xs-12">
                                <input type='hidden' id='storeid' name='storeid' value="<?php  echo $store['id'];?>" />
                                <div class='input-group'>
                                    <input type="text" name="store" maxlength="30" value="<?php  echo $store['title'];?>" id="store" class="form-control" readonly /><!--不知道是 storename 还是title-->
                                    <div class='input-group-btn'>
                                        <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus1').modal();">选择兑换点</button>
                                        <button class="btn btn-danger" type="button" onclick="$('#storeid').val('');$('#store').val('');">清除选择</button>
                                    </div>
                                </div>
                                <span class="help-block">如果不选择兑换点，则此管理员为全局管理员，所有兑换点的均可核销</span>
                                <div id="modal-module-menus1"  class="modal fade" tabindex="-1"> 
                                    <div class="modal-dialog"><!-- style='width: 920px;' -->
                                        <div class="modal-content">
                                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择兑换点</h3></div>
                                            <div class="modal-body" >
                                                <div class="row">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="keyword" value="" id="search-kwd1" placeholder="请输入兑换点名称" />
                                                        <span class='input-group-btn'><button type="button" class="btn btn-default search-stores-btn">搜索</button></span><!--  onclick="search_stores();" -->
                                                    </div>
                                                </div>
                                                <div id="module-menus1" style="padding-top:5px;"></div>
                                            </div>
                                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                        </div>
        
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        </div>
                    </div>
                    
                    <div class="form-group status-box">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label">状态</label>
                            <div class="col-sm-9 col-xs-12">
                                <label class='radio-inline vertical-box'>
                                    <input class="show-apperance" type='radio' name='status' value='1' <?php  if($item['status']==1) { ?>checked<?php  } ?> /> <span>启用</span>
                                </label>
                                <label class='radio-inline vertical-box'>
                                    <input class="show-apperance" type='radio' name='status' value='0' <?php  if($item['status']==0) { ?>checked<?php  } ?> /> <span>禁用</span>
                                </label>
                                     
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        </div>
                    </div>
                    
                    <div class="form-group"></div>
                    <div class="form-group"> 
                        <label class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1 post-btn"/>
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                            <input type="button" name="back" onclick="history.back()" style="margin-left:10px;" value="返回列表" class="btn btn-default" data-original-title="" title="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script language='javascript'>
    require(['tpl', 'core'], function(tpl, core) {
        $('form').submit(function () {
            if ($(':input[name=saler]').isEmpty()) {
                core.tip.show('请选择管理员!');
                return false;
            }
            if ($(':input[name=salername]').isEmpty()) {
                core.tip.show('请输入管理员姓名!');
                return false; 
            }
            if ($(':input[name=mobile]').isEmpty()) {
                core.tip.show('请输入管理员电话!');
                return false; 
            }
            if ($(':input[name=store]').isEmpty()) { 
                core.tip.show('请输入选择兑换点');
                return false; 
            } 
            return true;
       });
    });
</script>
<script type="text/html" id="search-members-tpl">
    <div class="search-members-result">
        <ul class="search-members-list">
        	<%each list as vo%>
            	<li class="member-item" onclick='select_member({openid:"<%vo.openid%>",realname:"<%vo.realname%>",nickname:"<%vo.nickname%>",mobile:"<%vo.mobile%>"})'><%vo.realname%>/<%vo.nickname%>/<%vo.mobile%></li>
        	<%/each%>
    </div>
        </ul>
</script>
<script type="text/html" id="search-store-tpl">
    <div class="search-store-result">
        <!-- 循环开始 -->
    	<%each list as vo%>
        <div class="store" onclick='select_store({id:"<%vo.id%>",title:"<%vo.title%>",address:"<%vo.address%>",mobile:"<%vo.mobile%>"})'>
            <div class="info clearfloat">1
                <div class="ico"><i class="fa fa-building-o"></i></div>
                <div class="info1">
                    <div class="inner">
                        <div class="store-name"><%vo.title%></div> 兑换点名称 
                        <div class="address">地址: <%vo.address%></div> 兑换点地址 
                        <div class="tel">电话: <%vo.mobile%></div> 联系电话 
                    </div>
                </div>
            </div>
        </div>
    	<%/each%>
    </div>
</script>
<script language='javascript'>
    require(['tpl', 'core'], function(tpl, core) {
        $(".search-members-btn").click(function(){
            search_members();
        });
        $(".search-stores-btn").click(function(){
            search_stores();
        });
        function search_members() {
            if( $.trim($('#search-kwd').val())==''){
                core.tip.show('请输入关键词');
                return;
            }
            $("#module-menus").html("正在搜索....")

//          $.get('<?php  echo $this->createWebUrl('member/query')?>', {
//              keyword: $.trim($('#search-kwd').val())
//          }, function(dat){
//              $('#module-menus').html(dat);
//              $('#module-menus').html(tpl('search-members-tpl'));
//          });
            core.json('member/points', {op:'search',keyword: $.trim($('#search-kwd').val())}, function(json) {
            	console.log(json);
                if(json.status){
                	console.log(json.result);
                    $('#module-menus').html(tpl('search-members-tpl',json.result));
                }else{
                    core.tip.show('暂无符合管理员');
                }
           }, true);
        }
        function search_stores() {
            if( $.trim($('#search-kwd1').val())==''){
                core.tip.show('请输入关键词');
                return;
            }
            $("#module-menus1").html("正在搜索....")   
//          $.get('<?php  echo $this->createPluginWebUrl('suppliermenu/store',array('op'=>'query'));?>', {
//              keyword: $.trim($('#search-kwd1').val())  
//          }, function(dat){
//              $('#module-menus1').html(dat); 
//              $('#module-menus1').html(tpl('search-store-tpl'));
//          });
            core.json('member/points', {op:'query',keyword: $.trim($('#search-kwd1').val())}, function(json) {
                if(json.status){
                    $('#module-menus1').html(tpl('search-store-tpl',json.result));
                }else{
                    core.tip.show('暂无符合兑换点');
                }
           }, true);
        }
        
    });
    //o为一个对象 包含member的信息
    function select_member(o) {
    	console.log(o);
        $("#openid").val(o.openid);
        $("#saler").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
        $(".close").click();
    }
    function select_store(o) {
        $("#storeid").val(o.id);
        $("#store").val( o.title);
        $(".close").click();
    }
    </script>
    
<?php  } else if($_GPC['op']=='admin') { ?>
	<!-- elseif $operation == 'display'-->
	<div class='panel panel-default admin-list-container'>
	    <div class='panel-heading'>管理员管理</div>
	    <div class='admin-container'>
	        <ul class="admin-box clearfloat">
	            <!-- 只是看样式s -->
	            <!--<li class="admin-item">
	                <div class="admin-item-tag">管理员: MAGNOLIA</div>
	                <div class="admin-item-tag">姓名: MAGNOLIA</div>
	                <div class="admin-item-tag">所属兑换点: 全局兑换点</div>
	                <div class="admin-item-tag">状态: <?php  if(false) { ?> 
	                    <span class='label label-success'>启用</span> 
	                    <?php  } else { ?> 
	                    <span class='label label-danger'>禁用</span>
	                    <?php  } ?> 
	                </div>
	                <div class="admin-item-tag">操作: 
	                    <a class='btn btn-default' href="javascript:void(0);"><i class='fa fa-edit'></i></a>
	                    <a class='btn btn-default' href="javascript:void(0);" onclick="return confirm('确认删除此管理员吗？');
	                      return false;"><i class='fa fa-remove'></i></a> 
	                </div>
	            </li>
	            <li class="admin-item">
	                <div class="admin-item-tag">管理员: MAGNOLIA</div>
	                <div class="admin-item-tag">姓名: MAGNOLIA</div>
	                <div class="admin-item-tag">所属兑换点: 全局兑换点</div>
	                <div class="admin-item-tag">状态: 
	                    <span class='label label-danger'>禁用</span>
	                </div>
	                <div class="admin-item-tag">操作: 
	                    <a class='btn btn-default' href="javascript:void(0);"><i class='fa fa-edit'></i></a>
	                    <a class='btn btn-default' href="javascript:void(0);" onclick="return confirm('确认删除此管理员吗？');
	                      return false;"><i class='fa fa-remove'></i></a> 
	                </div>
	            </li>
	            <li class="admin-item">
	                <div class="admin-item-tag">管理员: MAGNOLIA</div>
	                <div class="admin-item-tag">姓名: MAGNOLIA</div>
	                <div class="admin-item-tag">所属兑换点: 全局兑换点</div>
	                <div class="admin-item-tag">状态: 
	                    <span class='label label-danger'>禁用</span>
	                </div>
	                <div class="admin-item-tag">操作: 
	                    <a class='btn btn-default' href="javascript:void(0);"><i class='fa fa-edit'></i></a>
	                    <a class='btn btn-default' href="" onclick="return confirm('确认删除此管理员吗？');
	                      return false;"><i class='fa fa-remove'></i></a> 
	                </div>
	            </li>-->
	            <!-- 只是看样式e -->
	
	            <?php  if(is_array($list)) { foreach($list as $row) { ?>
	            <li class="admin-item">
	                <div class="admin-item-tag">管理员: <?php  echo $row['realname'];?></div>
	                <div class="admin-item-tag">姓名: <?php  echo $row['salername'];?></div>
	                <div class="admin-item-tag">所属兑换点: <?php  if(empty($row['storename'])) { ?>全局兑换点<?php  } else { ?><?php  echo $row['storename'];?><?php  } ?></div>
	                <div class="admin-item-tag">状态: <?php  if($row['status']==1) { ?> 
	                    <span class='label label-success'>启用</span> 
	                    <?php  } else { ?> 
	                    <span class='label label-danger'>禁用</span>
	                    <?php  } ?> 
	                </div>
	                <div class="admin-item-tag">操作: 
	                    <a class='btn btn-default' href="<?php  echo $this->createMobileUrl('member/points',array('op'=>'detail','id'=>$row['id']))?>"><i class='fa fa-edit'></i></a><!-- 给href赋上编辑链接 -->
	                    <a class='btn btn-default' href="<?php  echo $this->createMobileUrl('member/points',array('op'=>'del','type'=>'2','id'=>$row['id']))?>" onclick="return confirm('确认删除此管理员吗？');"><i class='fa fa-remove'></i></a> <!-- 给href赋上删除链接 -->
	                </div>
	            </li>
	            <?php  } } ?>
	        </ul>
	    </div>
	    <div class='panel-footer'>
	        <a class='btn btn-primary' href="<?php  echo $this->createMobileUrl('member/points',array('op'=>'detail'))?>"><i class="fa fa-plus"></i> 添加新管理员</a>
	    </div>
	</div>
<?php  } ?>



<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
