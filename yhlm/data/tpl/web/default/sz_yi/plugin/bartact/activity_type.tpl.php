<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('acttabs', TEMPLATE_INCLUDEPATH)) : (include template('acttabs', TEMPLATE_INCLUDEPATH));?>
<?php  if($operation != 'post') { ?>
 
<?php  } ?>
 <script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>

<?php  if($operation == 'post') { ?>
<style type='text/css'>
    .tab-pane {padding:20px 0 20px 0;}
</style>
<div class="main rightlist">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
    	<input type="hidden" name="id" value="<?php  echo $item['id'];?>">
        <div class="panel panel-default">
            <div class="panel-body">
				<div class="tab-content" style="overflow:inherit">
					<div class="form-group">
					    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>分类名</label>
					    <div class="col-sm-9 col-xs-12">
					        <input type="text" name="title" id="title" class="form-control" value="<?php  echo $item['title'];?>" />
					    </div>
					</div>
					<div class="form-group">
					    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
					    <div class="col-sm-9 col-xs-12">
					        <input type="text" name="display" id="display" class="form-control" value="<?php  echo $item['display'];?>" />
					    </div>
					</div>
					<div class="form-group">
					    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>状态</label>
					    <div class="col-sm-9 col-xs-12">
					        <label for="isshow3" class="radio-inline"><input type="radio" name="status" value="1" id="isshow3" <?php  if(empty($item['status']) || $item['status'] == 1) { ?>checked="true"<?php  } ?> /> 显示</label>
            				<label for="isshow4" class="radio-inline"><input type="radio" name="status" value="0" id="isshow4"  <?php  if($item['status'] == 2) { ?>checked="true"<?php  } ?> /> 隐藏</label>
					    </div>
					</div>
					<?php  if(false) { ?>
					<div class="form-group">
					    <label class="col-xs-12 col-sm-3 col-md-2 control-label">logo</label>
					    <div class="col-sm-9 col-xs-12 detail-logo">
				             <?php if( ce('shop.goods' ,$item) ) { ?>
					        <?php  echo tpl_form_field_image('logo', $item['logo'])?>
					        <span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
					          <?php  } else { ?>
	                            <?php  if(!empty($item['logo'])) { ?>
	                            <a href='<?php  echo tomedia($item['logo'])?>' target='_blank'>
	                            <img src="<?php  echo tomedia($item['logo'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
	                             </a>
	                            <?php  } ?>
	                        <?php  } ?>
					    </div>
					</div>
					<?php  } ?>
                </div>
                <div class="form-group col-sm-12 mrleft40 border-t">
		        	<label class="col-xs-12 col-sm-3 col-md-2 control-label">&nbsp;</label>
					<input type="submit" name="submit" value="保存" class="btn btn-primary col-lg-1" onclick="return formcheck()" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
					<input type="button" name="back" onclick='history.back()' style='margin-left:10px;' value="返回列表" class="btn btn-default"/>
        		</div>
    		</div>
		</div>
	</form>
</div>
<?php  } else if($operation == 'display' || $op == 'demo') { ?>
<div class="main rightlist"> 	 	 
   
    <style>
        .label{cursor:pointer;}
    </style>
	<div class="panel panel-default">
        <div class="panel-body">
			<a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('bartact/activity_type',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加分类</a>
        </div>
	</div>
    <form action="" method="post">
		<div class="panel panel-default">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<thead class="navbar-inner">
						<tr>
							<th width="6%">ID</th>
							<th width="6%">分类名</th>
							<th width="6%">排序</th>
							<?php  if(false) { ?>
							<th width="15%">LOGO</th>
							<?php  } ?>
							<th width="14%">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php  if(is_array($type)) { foreach($type as $item) { ?>
						<tr> 	 	 	
							<td><?php  echo $item['id'];?></td>
							<td><?php  echo $item['title'];?></td>	 	
							<td><?php  echo $item['display'];?></td>
							<?php  if(false) { ?>
							<td><img style="height: auto;width:60px" src="<?php  echo tomedia($item['logo'])?>"  /></td>
							<?php  } ?>
							<td>
								<a href="<?php  echo $this->createPluginWebUrl('bartact/activity_type', array('id' => $item['id'], 'op' => 'post'))?>" class="btn btn-sm btn-default" title="编辑""><i class="fa fa-pencil"></i></a>
								<a href="<?php  echo $this->createPluginWebUrl('bartact/activity_type', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('确认删除此分类？');return false;" class="btn btn-default  btn-sm" title="删除"><i class="fa fa-times"></i></a>
							</td>
						</tr>
					<?php  } } ?>
					</tbody>
				</table>
				<?php  echo $pager;?>
			</div>
		</div>
	</form>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>