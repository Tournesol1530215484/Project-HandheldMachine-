<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type='text/css'>
	.trhead td {  background:#efefef;text-align: center}
	.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
	.goods_info{position:relative;width:60px;}
	.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
	.goods_info:hover {z-index:1;position:absolute;width:auto;}
	.goods_info:hover img{width:320px; height:320px;}
</style>
<?php  if($op == 'display') { ?>
<div class="panel panel-info">
		<div class="panel-heading">筛选</div>
		<div class="panel-body">
			<form action="./index.php" method="get" class="form-horizontal" role="form">
				<input type="hidden" name="c" value="site">
				<input type="hidden" name="a" value="entry">
				<input type="hidden" name="m" value="sz_yi">
				<input type="hidden" name="do" value="plugin">
				<input type="hidden" name="p" value="activity">
				<input type="hidden" name="method" value="member">

				<div class="form-group">
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class="input-group">
							<div class="input-group-addon">姓名</div>
							<input class="form-control" name="realname" type="text" value="<?php  echo $_GPC['realname'];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class="input-group">
							<div class="input-group-addon"> 手机</div>
							<input class="form-control" name="mobile" type="text" value="<?php  echo $_GPC['mobile'];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class="input-group">
							<div class="input-group-addon">单位</div>
							<input class="form-control" name="orgName" type="text" value="<?php  echo $_GPC['orgName'];?>">
						</div>
					</div>

					<div class="col-lg-1 col-md-6 col-sm-6 col-xs-12">
						<div class="input-group">
							<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
						</div>
					</div>

					<div class="col-lg-1 col-md-6 col-sm-6 col-xs-12">
						<div class="input-group">
							<div class="btn btn-default"><i class="fa fa-remove"></i>清空</div></div>
						</div>
					</div>

				</form></div>

		</div>

<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'><span style="color:#f00"></span></div>
	    <div class='panel-body'>
	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:auto;">姓名</th>
	    				<th style="width:auto;">手机</th>
	    				<th style="width:auto;">会员</th>
	    				<th style="width:auto;">到期时间</th>
	    				<th style="width:auto;">单位</th>
	    				<th style="width:auto;">频次</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr>
		    				<td><?php  echo $item['realname']['data'];?></td>
		    				<td><?php  echo $item['mobile']['data'];?></td>
		    				<td>
		    					<?php  if($item['level'] ==1) { ?>
		    						会员
		    					<?php  } else if($item['level'] == 2) { ?>
									商家
		    					<?php  } else if($item['level'] == 3) { ?>
		    						战略
		    					<?php  } else { ?>
		    						无
		    					<?php  } ?>
		    				</td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['etime'])?></td>
		    				<td><?php  echo $item['unit']['data'];?></td>
		    				<td><?php  echo $item['num'];?></td>
	    					<td  style="overflow:visible;">
                                <div class="btn-group btn-group-sm">
                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作
                                    	<span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
                                        <li>
                                        	<a href="javascript:void(0);" class="setlevel" data-id="<?php  echo $item['id'];?>">设置会员</a>
                                        </li>
                                    </ul>
                                </div>
		    				</td>
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
		</div>
	</div>
</div>

<?php  } ?>
<div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 750px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>设置等级</h3></div>
			<div class="modal-body col-lg-10 col-md-10 col-sm-10 col-xs-12 row" style="padding: 15px;">
				<input type="hidden" name="sgupid">

				<div class="form-group">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>会员等级</label>
						<div class="col-sm-9 col-xs-12">
							<select class="form-control" name="level" style="margin-bottom: 15px;">
							 	<option>请选择会员等级</option>
							 	<option value="1">会员</option>
							 	<option value="2">商家</option>
							 	<option value="3">战略</option>
							 </select>
						</div>
					</div>
				</div>

				 <div class="form-group" style="display: none;">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>有效期</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo _tpl_form_field_date('etime','', $withtime = true)?>
						</div>
					</div>
				</div>

				<div class="btn btn-primary" onclick="save();" style="float: right;width: 18%;margin-top: 15px">确定</div>
			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>

</div>
<script type="text/javascript">
	$('.setlevel').click(function(){
		$('[name="sgupid"]').val($(this).data('id'));
		$('#modal-module-menus').modal('show');
	});
	$('select[name="level"]').change(function(){
		var o = $(this);
		if (o.val() == 1 || o.val() == 2 || o.val() == 3) {
			o.parent().parent().parent().next('div').show();
		}else{
			o.parent().parent().parent().next('div').hide();
		}
	});
	var sended=false;
	function save(){

		if (sended) {
			return;
		}
		sended=true;
		var data={
			id:$('[name="sgupid"]').val(),
			level:$('[name="level"]').val(),
			etime:$('[name="etime"]').val()
		};
		var url='<?php  echo $this->createPluginWebUrl('activity/member',array('op'=>'sub'))?>';
		$.post(url,data,function(data){
			alert(data.result);
			if (data.status ==1) {
				location.reload();
				return;
			}
			sended=false;
		},'json');
	}
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>