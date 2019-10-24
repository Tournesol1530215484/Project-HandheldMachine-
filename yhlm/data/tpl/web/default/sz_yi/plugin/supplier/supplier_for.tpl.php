<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<style>
    .preview{cursor:pointer}
</style>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="supplier" />
            <input type="hidden" name="method" value="supplier_for" />
            <div class="form-group">				<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">ID</label>	
	                <div class="col-sm-8 col-lg-9 col-xs-12">	
	                    <input type="text" class="form-control"  name="mid" value="<?php  echo $_GPC['mid'];?>"/> 	
	                </div>	
	            </div>
            	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-2 control-label">姓名</label>	
	                <div class="col-sm-8 col-lg-9 col-xs-12">	
	                    <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder="可搜索昵称/姓名/手机号"/> 	
	                </div>	
	            </div>
            	<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                   <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                   <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                   <?php if(cv('member.member.export')) { ?>   
                    <button type="submit" name="export1" value="1" class="btn btn-primary">导出 Excel</button>
                    <?php  } ?>
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
                    <th style='width:80px;'>会员ID</th>
                    <th style='width:80px;'>会员姓名</th>
                    <th style='width:100px;'>手机号码</th>
                    <th style='width:100px;'>微信</th>
                    <th style='width:120px;'>产品名称</th>
                    <th style='width:160px;'>证件照片</th>
					<th style='width:120px;'>类型</th>
                    <th style='width:120px;'>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td><?php  echo $row['id'];?></td>
                    <td><?php  echo $row['realname'];?></td>
                    <td><?php  echo $row['mobile'];?></td>
                    <td><?php  echo $row['weixin'];?></td>
                    <td><?php  echo $row['productname'];?></td>
                    <td>
                        正面：
                        <span class="preview"><img src="<?php  echo $row['imgs']['idimg1'];?>" alt="" height="50"></span>
                        反面：
                        <span class="preview"><img src="<?php  echo $row['imgs']['idimg2'];?>" alt="" height="50"></span>
                        营业执照:
                        <span class="preview"><img src="<?php  echo $row['imgs']['permit'];?>" alt="" height="50"></span>
                    </td>
					<td>
                        <?php  if($row['type'] == 1) { ?>供应商<?php  } ?>
                        <?php  if($row['type'] == 2) { ?>商家<?php  } ?>
                    </td>
                    <td>
                        <a class="btn btn-danger btn-sm" href="javascript:;" onclick="$('#modal-refund').find(':input[name=id]').val('<?php  echo $row['id'];?>')" data-toggle="modal" data-target="#modal-refund" data-original-title="" title="">驳回审核</a>
                        <!-- <a onclick="return confirm('您确定要驳回申请吗？');" class="label label-default " href="<?php  echo $this->createPluginWebUrl('supplier/supplier_for',array('op' => 'af_supplier', 'id' => $row['id'], 'status' => 0));?>">驳回审核</a> -->
						<a class="label label-default label-info" href="<?php  echo $this->createPluginWebUrl('supplier/supplier_for',array('op' => 'af_supplier', 'id' => $row['id'], 'status' => 2));?>">审核通过</a>
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
<!-- 驳回退款 -->
<div id="modal-refund" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="width:620px;margin:0px auto;">
    <form class="form-horizontal form" action="" method="post" enctype="multipart/form-data">
        <input type='hidden' name='id' value='' />
        <input type='hidden' name='p' value='supplier' />
        <input type='hidden' name='op' value='reject' />
        <div class="modal-dialog">
            <div class="modal-content"  >
                <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>驳回审核</h3></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">驳回原因</label>
                        <div class="col-md-8">
                            <textarea rows="3" class="form-control" name="rejectcontent"></textarea>
                        </div>
                    </div>
                    <div id="module-menus"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary span2" name="submit" value="<?php  echo $_W['token'];?>">确认</button><a href="#" class="btn" data-dismiss="modal" aria-hidden="true">关闭</a></div>
            </div>
        </div>·
    </form>
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
<script type="text/javascript">
    $(".preview").click(function(){
        var src = $(this).find("img").attr("src");
        // alert(src);
        $(".modal-body").find("img").attr("src",src);
        $('#modal').modal('toggle');
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>