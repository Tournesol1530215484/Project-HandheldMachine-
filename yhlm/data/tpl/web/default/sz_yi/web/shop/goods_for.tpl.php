<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/shop/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/shop/tabs', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="shop" />
            <input type="hidden" name="p" value="goods_for" />
            <div class="form-group">
            	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">产品名称</label>
	                <div class="col-sm-8 col-lg-9 col-xs-12">
	                    <input type="text" class="form-control"  name="title" value="<?php  echo $_GPC['title'];?>" placeholder="可搜索产品名称"/>
	                </div>	
	            </div>
            	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                   <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                   <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
               </div>

            <div class="form-group">
            </div>
        </form>
    </div>
</div><div class="clearfix">

<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?>   </div>
    <div class="">
        <table class="table table-hover" style="overflow:visible;">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:80px;'>ID</th>
                    <th style='width:80px;'>产品主图</th>
                    <th style='width:120px;'>商品名称</th>
                    <th style='width:120px;'>现价</th>
                    <th style='width:120px;'>原价</th>
                    <th style='width:120px;'>产品图片</th>
                    <?php  if(false) { ?>
                        <th style='width:120px;'>上架日期</th>
                        <th style='width:120px;'>预计下架日期</th>
                    <?php  } ?>
                    <th style='width:180px;'>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td><?php  echo $row['id'];?></td>
                    <td>
                        <span class="preview">
                            <img src="<?php  echo tomedia($row['thumb'])?>" alt="" height="50">
                        </span>
                    </td>
                    <td><?php  echo $row['title'];?></td>
                    <td>
                        <?php  echo $row['marketprice'];?> 元
                    </td>
                    <td>
                        <?php  echo $row['productprice'];?> 元
                    </td>
                    <td>
                        <?php  if(is_array($row['thumb_url'])) { foreach($row['thumb_url'] as $key => $val) { ?>
                        <span class="preview"><img src="<?php  echo tomedia($val)?>" alt="" height="50"></span>
                        <?php  } } ?>
                    </td>
                    <td>  
						<a class="label label-default label-info" href="<?php  echo $this->createWebUrl('shop/goods_for',array('op' => 'check', 'logid' => $row['lid'],'ischeck'=>1));?>">审核通过</a>        
                        <a class="label label-default label-success" href="<?php  echo $this->createWebUrl('shop/goods',array('op'=>'post','id'=>$row['id'],'read'=>'on'))?>">查看详情</a>         
                        <a class="label label-default label-danger" href="#" data-id="<?php  echo $row['lid'];?>">驳回申请</a>
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
                    <h4 class="modal-title" id="myModalLabel">驳回请求</h4>
                </div> 
                <div class="modal-body" style="padding:0px;"> 
                        <input type="hidden" name="logid" / >
                        <input type="hidden" name="op" value="check" / >
                        <input type="hidden" name="ischeck" value="2" / >
                        <textarea name="note" class="form-control" placeholder="请输入原因" rows="10"></textarea> 
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
    $(".label-danger").click(function(){
        var id=$(this).data('id');
        $('[name="logid"]').val(id);
        $('#reject').modal('toggle');  
    }); 

    $(".preview").click(function(){
        var src = $(this).find("img").attr("src");
        // alert(src); 
        $("#modal").find("img").attr("src",src);
        $('#modal').modal('toggle');
    });
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>