<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goodtabs', TEMPLATE_INCLUDEPATH)) : (include template('goodtabs', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal"  id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="op" value="demo" />
            <input type="hidden" name="p" value="dealmerch" />
            <input type="hidden" name="method" value="ad_for" />
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">广告编号</label>
                    <div class="col-sm-10 col-lg-10 col-xs-10">
                        <input type="" name="adsn" class="form-control">
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">广告名称</label>
                    <div class="col-sm-10 col-lg-10 col-xs-10">
                        <input type="" name="title" class="form-control">
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <button class="btn btn-default" ><i class="fa fa-search"></i> 搜索</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <?php  if('statistics.export.order') { ?>          
                    <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
                    <?php  } ?>
                </div>
            </div>
        </form>
    </div>
</div><div class="clearfix">

<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $totals;?>   </div>
    <div class="">
        <table class="table table-hover" style="overflow:visible;">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:8%">广告编号</th>
                    <th style="width:20%">广告名称</th>
                    <th style="width:15%">商家名称</th>
                    <th style="width:10%">联系人电话</th>
                    <th style="width:8%">核心记忆词</th>
                    <th style="width:8%;">创建时间</th>
                    <th style="width:20%">操作</th>            
                </tr>       
            </thead>     
            <tbody>          
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>         
                    <td><?php  echo $row['adsn'];?></td>      
                    <td><?php  echo $row['title'];?></td>
                    <td><?php  echo $row['username'];?></td>
                    <td><?php  echo $row['mobile'];?></td>
                    <td><?php  echo $row['core'];?></td>
                    <td><?php  echo date('Y-m-d H:i:s',$row['ctime'])?></td>
                    <td>  
						<a class="label label-default label-info" href="javascript:void(0);" data-id="<?php  echo $row['lid'];?>">审核通过</a>
                        <label data-id="<?php  echo $row['id'];?>" class="label label-control label-success showmodal">查看详情</label>          
                        <a class="label label-default label-danger" href="javascript:void(0);" data-id="<?php  echo $row['lid'];?>">驳回申请</a>
					</td> 
                </tr> 
                <?php  } } ?> 
            </tbody>
        </table>
           <?php  echo $pager;?>
    </div>
</div>
</div>
</div>
</div>


<!-- modal -->
<div class="modal fade" tabindex="-1" id="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">预览图片</h4>
            </div>
            <div class="modal-body">
                <img src="" alt="" width="100%">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <!-- <button type="button" class="btn btn-primary">保存</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="reject" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:30%"> 
        <div class="modal-content">
            <form method="post">  
                <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="reject" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">备注</h4>
                </div> 
                <div class="modal-body" style="padding:0px;"> 
                        <input type="hidden" name="logid" / >
                        <input type="hidden" name="op" value="check" / >
                        <input type="hidden" name="ischeck" value="" / >
                        <textarea name="note" class="form-control" placeholder="请输入备注" rows="10"></textarea> 
                </div>           
                <div class="modal-footer">                 
                    <button type="button reject" class="btn btn-primary col-sm-2 col-sm-offset-7">确认</button>
                    <button type="button" class="btn btn-default col-sm-2 col-sm-offset-1" data-dismiss="modal">关闭</button>
                </div>  
            </form>             
        </div>
    </div>
</div>

<script type="text/javascript"> 
    
    $(".reject").click(function(){
        $("#reject form").submit();
    }); 
    $(".label-danger,.label-info").click(function(){
        var id=$(this).data('id');
        $('[name="logid"]').val(id);
        if ($(this).hasClass('label-info')) {
            $('[name="ischeck"]').val(1);
        }else{
            $('[name="ischeck"]').val(2);
        }
        $('#reject').modal('toggle');  
    });               

    $(".preview").click(function(){
        var src = $(this).find("img").attr("src");
        // alert(src); 
        $("#modal").find("img").attr("src",src);
        $('#modal').modal('toggle');
    });
</script>
<div id="modal-module-menus"  class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>查看广告</h3></div>
            <div class="modal-body" >
                <div class="panel-body">
                    <h3 style="margin:0px;"><b>基本信息</b></h3>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">广告编号</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adsn"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">广告名称</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adtitle"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">广告描述</label>
                            <div class="col-sm-9 col-xs-12 col-md-9">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-12 control-label adesc"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>

                     <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">核心记忆词</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-12 control-label adcore"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>

                    
                    <?php  if(false) { ?>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">核心词拼音</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-12 control-label adspeel"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">广告标签</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adlabel"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>
                    <?php  } ?>


                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">广告主图</label>
                            <div class="col-sm-9 col-xs-12">
                                <img src="" alt="" class="adimg">
                            </div>
                        </div>
                        <br clear="both">
                    </div>


                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">轮播大图</label>
                            <div class="col-sm-9 col-xs-12">
                                <ul class="adbanner">
                                    <li>
                                        <img src="" alt="">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <br clear="both">
                    </div>

                    
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">链接</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-3 control-label adlink"></label>
                            </div>
                        </div>
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">视频链接</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-3 control-label volink"></label>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <h3 style="margin:0px;"><b>投放信息</b></h3>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">推荐商品</label>
                            <div class="col-sm-9 col-xs-12">
                                <table class="table table-hover table-bordered adgoods">
                                    <!-- <tr>
                                        <th style="width:auto;">商品编号</th>
                                        <th style="width:auto;">商品名称</th>
                                        <th style="width:auto;">类别</th>
                                        <th style="width:auto;">单价（元）</th>
                                    </tr> -->
                                    
                                </table>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">投放类型</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adput"></label>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">开始时间</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adstart"></label>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">有效期</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adtime"></label>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">投放人数</label>
                            <div class="col-sm-9 col-xs-12">
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-9 control-label adbonus"></label>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">投放区域</label>
                            <div class="col-sm-9 col-xs-12">
                                <table class="table table-hover table-bordered adarea">
                                    <!-- <tr>
                                        <th>序号</th>
                                        <th>地点名称  </th>
                                    </tr>
                                    <tr>
                                        
                                    </tr> -->

                                </table>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">投放目标</label>
                            <div class="col-sm-9 col-xs-12">               
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-12 col-md-12 control-label adgender">
                                    性别 : <span style="color:#f00;"></span>
                                </label>
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-12 col-md-12 control-label adearning">
                                    年收入 : <span style="color:#f00;"></span>
                                </label>
                                <label style="margin-top:8px;margin-left:-15px;" class="col-xs-12 col-sm-12 col-md-12 control-label adage">
                                    年龄段 : <span style="color:#f00;"></span>
                                </label>
                            </div>       
                        </div>              
                        <br clear="both">       
                    </div>

                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="margin-top:8px;" class="col-xs-12 col-sm-3 control-label">审核日志</label>
                            <div class="col-sm-9 col-xs-12">
                                <table class="table table-hover table-bordered adlog">
                                    <!-- <tr>
                                        <th>提交日期</th>
                                        <th>审核日期</th>
                                        <th>结果</th>
                                        <th>备注</th>
                                    </tr> -->
                                </table>
                            </div>
                        </div>      
                        <br clear="both">
                    </div>
                </div>
            </div>
            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
        </div>
    </div>
</div>
<script language="javascript">

    $('.showmodal').click(function(){
        var o  = $(this);
        var data={id:o.data('id'),uid:'<?php  echo $_W['uid'];?>'};
        $.post('<?php  echo $this->createPluginWebUrl('dealmerch/ad',array('op'=>'show'))?>',data,function(e){

            var log     = e.result.log;
            var ad      = e.result.ad;
            var product = e.result.product;
            $('.adsn').html(ad.adsn);
            $('.adtitle').html(ad.title);
            $('.adesc').html(ad.desc);  
            $('.adcore').html(ad.core); 
            $('.adspeel').html(ad.coreSpeel);   
            $('.adlabel').html(ad.adLabel); 
            $('.adlink').html(ad.link);
            $('.adput').html(ad.putInType); 
            $('.adstart').html(ad.stime);   
            $('.adtime').html(ad.calctime);
            $('.adbonus').html(ad.bonus);
            $('.adgender span').html(ad.gender);
            $('.adearning span').html(ad.earning);
            $('.adage span').html(ad.age);

            if (ad.type == 1) {
                $('.volink').parent().parent().parent().remove();
            }else if (ad.type == 2){
                $('.volink').parent().prev().html('外链链接');
                $('.volink').html(ad.outside);
            }else if(ad.type == 3){
                $('.volink').parent().prev().html('视频链接');
                $('.volink').html(ad.video);
            }
            
            var html='';
            for (var i = 0; i <= ad.thumb.length -1; i++) {
                if (i == 0) {
                    $('.adimg').attr('src',ad.thumb[i]).css('width','150px').css('height','150px').css('margin-left','12%');
                }
                html+='<li>';
                html+='<img width="150px;" height="150px" src="'+ad.thumb[i]+'" alet="">';
                html+='</li>';
            }
            $('.adbanner').html(html);

            var html='';
            html+='<tr>';
            html+='<th>提交日期</th>';
            html+='<th>审核日期</th>';
            html+='<th>结果</th>';
            html+='<th>备注</th>';
            html+='</tr>';
            for (var i = 0; i <= log.length -1; i++) {
                html+='<tr>';
                html+='<td>'+log[i].sub_time+'</td>';
                html+='<td>'+log[i].audit_time+'</td>';
                if (log[i].status == 0) {
                    html+='<td>待审核</td>';
                }else if (log[i].status == 1){
                    html+='<td>审核通过</td>';
                }else if(log[i].status == 2){
                    html+='<td>审核失败</td>';
                }
                html+='<td>'+log[i].note+'</td>';
                html+='</tr>';
            }
            $('.adlog').html(html);

            var html='';
            html+='<tr>';
            html+='<th style="width:auto;">商品编号</th>';
            html+='<th style="width:auto;">商品名称</th>';
            html+='<th style="width:auto;">类别</th>';
            html+='<th style="width:auto;">单价（元）</th>';
            html+='</tr>';
            if (product) {
                for (var i = 0; i <= product.length -1; i++) {
                    html+='<tr>';
                    html+='<td>'+product[i].id+'</td>';
                    html+='<td>'+product[i].title+'</td>';
                    html+='<td>'+product[i].ccate+'</td>';
                    html+='<td>'+product[i].marketprice+'</td>';
                    html+='</tr>';
                }
            }
            $('.adgoods').html(html);

            var html='';
            html+='<tr><th>序号</th><th>地点名称</th></tr>';
            html+='<tr>';
            html+='<td>1</td>';
            html+='<td>'+ad.area+'</td>';
            html+='</tr>';
            $('.adarea').html(html);
            $('#modal-module-menus').modal('show');
        },'json');
    });

    function send(btn){
        var modal =$('#modal-confirmsend');
        var itemid = $(btn).parent().find('.itemid').val();
            modal.find(':input[name=id]').val( itemid );
            var addressdata  = eval('(' +$(btn).parent().find('.addressdata').val()+')');
            modal.find('.realname').html(addressdata.realname);
            modal.find('.mobile').html(addressdata.mobile);
            modal.find('.address').html(addressdata.address);
    }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>