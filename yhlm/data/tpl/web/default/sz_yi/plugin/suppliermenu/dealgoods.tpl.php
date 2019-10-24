<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if($operation != 'post') { ?>
<?php  } ?>
<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>
<?php  if($operation == 'post') { ?>
<style type='text/css'>
	.tab-pane {padding:20px 0 20px 0;}
</style>
<div class="main rightlist">
	<form action="" method="post" id="addGoods" class="form-horizontal form" enctype="multipart/form-data">
		<div class="">
			<!--             <div class="panel-heading">
                            <?php  if(empty($item['id'])) { ?>添加商品<?php  } else { ?>编辑商品<?php  } ?>
                        </div> -->
			<div class="">
				<div class="ulleft-nav">
					<!-- <?php if(cv('shop.goods.view')) { ?><li><a href="<?php  echo $this->createWebUrl('shop/goods')?>">商品管理</a></li><?php  } ?> -->
					<ul class="nav nav-arrow-next nav-tabs" id="myTab">
						<li class="active" ><a href="#tab_basic">基本信息</a></li>
						<li><a href="#tab_option">库存+价格</a></li> 
						<li><a href="#tab_des">商品描述</a></li>
						<li><a href="#tab_special">特色卖点</a></li>
						<li><a href="#afterSalesServices">售后服务</a></li>
						<li><a href="#tab_param">自定义属性</a></li>
						<li><a href="#tab_others">订单提醒</a></li>
					</ul>
				</div>
				<script>
                    $('#myTab a').click(function(){
                        var tempop=$(this).attr('href');
                        $('#myTab li').each(function(i,e){
                            $(e).removeClass('active');
						});
                        $(this).addClass('active');
                        var pane=$('.tab-content');
                        pane.find('div').each(function(i,e){
                            $(e).removeClass('active');
                        });
                        pane.find(tempop).addClass('active');
                    });
				</script>
				<!--<div class="good-tit">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-10 col-xs-12 text-right">
                        <input type="submit" name="submit" value="保存商品"
                        class="btn btn-primary" onclick="return formcheck()" />
                        <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.goods.add|shop.goods.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    </div>
                </div>-->
      				<div class="tab-content " style="overflow:inherit">
					<div class="tab-pane active" id="tab_basic" style="margin-top: -35px;"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/basic', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/basic', TEMPLATE_INCLUDEPATH));?></div>
					<div class="tab-pane" id="tab_des"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/des', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/des', TEMPLATE_INCLUDEPATH));?></div>
					<div class="tab-pane" id="tab_special"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/special', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/special', TEMPLATE_INCLUDEPATH));?></div>
					<div class="tab-pane" id="afterSalesServices"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/afterSalesServices', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/afterSalesServices', TEMPLATE_INCLUDEPATH));?></div>
					<div class="tab-pane" id="tab_param"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/param', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/param', TEMPLATE_INCLUDEPATH));?></div>
					<div class="tab-pane" id="tab_option"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/option', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/option', TEMPLATE_INCLUDEPATH));?></div>
					<div class="tab-pane" id="tab_others"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealgoods/others', TEMPLATE_INCLUDEPATH)) : (include template('dealgoods/others', TEMPLATE_INCLUDEPATH));?></div>
				</div>
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">审核日志</div>
						<div class='panel-body'>
							<table style="overflow: auto;width: 100%;" class="table table-hover table-bordered">
								<thead>  
									<tr> 
										<th class="col-sm-3" >申请日期</th>
										<th class="col-sm-3" >审核日期</th>
										<th class="col-sm-3" >操作</th>
										<th class="col-sm-3" >备注</th>
									</tr>   
								</thead>   
								<tbody>
								<?php  if(is_array($audit_log)) { foreach($audit_log as $key => $val) { ?>
									<tr> 
										<td class="col-sm-3"><?php  echo date('Y-m-d H:i:s',$val['sub_time'])?></td>
										<td class="col-sm-3">
											<?php  if(empty($val['audit_time'])) { ?>
												待审核
											<?php  } else { ?>
												<?php  echo date('Y-m-d H:i:s',$val['audit_time'])?>
											<?php  } ?>
										</td>
										<td class="col-sm-2">
											<?php  if($val['status'] == 0) { ?>
											审核中
											<?php  } else if($val['status'] == 1) { ?>
											审核通过
											<?php  } else if($val['status'] == 2 ) { ?>
											审核失败
											<?php  } ?>
										</td>
										<td class="col-sm-3"><?php  echo $val['note'];?></td>
									</tr>
								<?php  } } ?>
								</tbody>
							</table>
					</div>
				</div>
				<div class="form-group col-sm-12 mrleft40 border-t">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">&nbsp;</label>
						<input type="submit" name="submit" value="提交审核" class="btn btn-primary col-lg-1" onclick="return formcheck()" />
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
						<input type="button" data-sure="0" id="save" value="暂存草稿箱" style="margin-left: 10px;" class="btn btn-success col-lg-1" />
						<input type="button" name="back" onclick='history.back()' <?php if(cv('shop.goods.add|shop.goods.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
				</div>
			</div>
		</div>
	</div>
</div>
</form>
</div>

<script type="text/javascript">
	window.type = "<?php  echo $item['type'];?>";
	window.virtual = "<?php  echo $item['virtual'];?>";
    	

	$(function () {
		
		$('[name="noticetype[]"]').each(function(i,e){
			if ($(e).val() == 1) {
				console.log($(e));
				$(e).context.checked=true;
			} 
		});	 		 	 	 	 

		$('#save').click(function (){
		if ($(this).data('sure') == 1) {
			return false;
		}
		$(this).data('sure','1');
    	$('#addGoods').append('<input type="hidden" name="ischeck" value="3" />');

    	setTimeout(function(){
	    	$('[name="submit"]').click();
    	},250);
  
    });
		

		$(':radio[name=type]').click(function () {
			window.type = $("input[name='type']:checked").val();
			window.virtual = $("#virtual").val();
			if(window.type=='1'){
				$('#dispatch_info').show();
			} else {
				$('#dispatch_info').hide();
			}
			if (window.type == '3') {
				if ($('#virtual').val() == '0') {
					$('.choosetemp').show();
				}
			}
		})
	})
	var category = <?php  echo json_encode($children)?>;
	window.optionchanged = false;
	require(['bootstrap'], function () {
		$('#myTab a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		})
	});

	require(['util'], function (u) {
		$('#cp').each(function () {
			u.clip(this, $(this).text());
		});
	})

	function formcheck() {
        window.type = $("input[name='type']:checked").val();
        window.virtual = $("#virtual").val();

        if ($("#goodsname").isEmpty()) {
            $('#myTab a[href="#tab_basic"]').tab('show');
            Tip.focus("#goodsname", "请输入商品名称!");
            return false;
        }

        full = checkoption();
        if (!full) {
            return false;
        }
        if (optionchanged) {
            $('#myTab a[href="#tab_option"]').tab('show');
            alert('规格数据有变动，请重新点击 [刷新规格项目表] 按钮!');
            return false;
        }

		return true;
	}

	function checkoption() {

		var full = true;
		if ($("#hasoption").get(0).checked) {
			$(".spec_title").each(function (i) {
				if ($(this).isEmpty()) {
					$('#myTab a[href="#tab_option"]').tab('show');
					Tip.focus(".spec_title:eq(" + i + ")", "请输入规格名称!", "top");
					full = false;
					return false;
				}
			});
			$(".spec_item_title").each(function (i) {
				if ($(this).isEmpty()) {
					$('#myTab a[href="#tab_option"]').tab('show');
					Tip.focus(".spec_item_title:eq(" + i + ")", "请输入规格项名称!", "top");
					full = false;
					return false;
				}
			});
		}
		if (!full) {
			return false;
		}
		return full;
	}

</script>

<?php  } else if($operation == 'display') { ?>
<div class="main rightlist">
	<div class="panel panel-info">
		<div class="panel-heading">筛选</div>
		<div class="panel-body">
			<form action="./index.php" method="get" class="form-horizontal" role="form">
				<input type="hidden" name="c" value="site" />
				<input type="hidden" name="a" value="entry" />
				<input type="hidden" name="m" value="sz_yi" />
				<input type="hidden" name="do" value="plugin" />
				<input type="hidden" name="p"  value="suppliermenu" />
				<input type="hidden" name="method"  value="dealgoods" />
				<input type="hidden" name="op" value="display" />
				<div class="form-group"> 
					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class='input-group'>
							<div class='input-group-addon'>商品编号</div>
							<input class="form-control" name="goodssn" type="text" value="<?php  echo $_GPC['goodssn'];?>">
						</div>
					</div>

					<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
						<div class='input-group'>
							<div class='input-group-addon'>商品名称</div> 
							<input class="form-control" name="title" type="text" value="<?php  echo $_GPC['title'];?>">
						</div>
					</div>

					<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">  
						<div class='input-group'>  
							<div class='input-group-addon'>商品状态</div> 
							<select name="status" class='form-control'>  
								<option value="0" <?php  if(empty($_GPC['status'])) { ?> selected<?php  } ?>></option> 
								<option value="1" <?php  if($_GPC['status']== '1') { ?> selected<?php  } ?>>出售中</option>
								<option value="2" <?php  if($_GPC['goodsStatus'] == '2') { ?> selected<?php  } ?>>等待上架</option>
								<option value="3" <?php  if($_GPC['status'] == '3') { ?> selected<?php  } ?>>审核中</option>
								<option value="4" <?php  if($_GPC['status'] == '4') { ?> selected<?php  } ?>>审核失败</option>
								<option value="5" <?php  if($_GPC['status'] == '5') { ?> selected<?php  } ?>>已下架</option>
								<option value="6" <?php  if($_GPC['status'] == '6') { ?> selected<?php  } ?>>草稿箱</option>
							</select> 
						</div>
					</div>

					<div class="col-lg-1 col-md-6 col-sm-6 col-xs-12">
						<div class='input-group'>
							<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
						</div>
					</div> 

					<div class="col-lg-1 col-md-6 col-sm-6 col-xs-12">
						<div class='input-group'> 
							<button type="reset" class="btn btn-default"><i class="fa fa-remove"></i>清空</div></button>
						</div>
					</div>

				</div>
			</form>
		</div>
	</div>
	<style>
		.label{cursor:pointer; line-height: 2.5;}
		.panel .table td, .panel .table th{ text-align: center; }
	</style>
	<div class="panel panel-default" style="min-height: 68px !important;">
		<div class="panel-body">
			<a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealgoods',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加商品</a>
		</div>
	</div>
	<form action="" method="post">
		<div class="panel panel-default">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead class="navbar-inner">
					<tr> 
						<th width="auto">ID</th> 
						<th width="14%">商品图片</th>
						<th width="12%">商品名称</th> 
						<th width="12%">兑换方式</th>
						<th width="auto">库存总数量</th>
						<th width="12%">上架日期</th>
						<th width="12%">预计下架日期</th>
						<th width="12%">状态</th>
						<th width="14%">操作</th>
					</tr>   
					</thead>
					<tbody>
					<?php  if(is_array($list)) { foreach($list as $item) { ?>
					<tr>
						<td><?php  echo $item['id'];?></td>
						<td> 
							<img width="50px" height="50px" src="<?php  echo tomedia($item['thumb'])?>">
						</td>
						<td title="<?php  echo $item['title'];?>">
							<?php  echo $item['title'];?>
						</td>
						<td class='tdedit'>
							<?php  if($item['LocalFlag'] == 1) { ?>
								现场兑换&nbsp;&nbsp;
							<?php  } ?>
							<?php  if($item['PostFlag'] == 1) { ?>
								邮寄兑换
							<?php  } ?>
						</td> 
						<td class='tdedit'>
							<?php  echo $item['total'];?>
						</td>
						<td>
							<?php  if($item['shelves'] == 1) { ?>
								审核后上架
							<?php  } else if($item['shelves'] == 2) { ?>
								<?php  echo date('Y-m-d日 H:i:s',$item['startShelves'])?>
							<?php  } ?>
						</td>
						<td>
							售完下架
						</td>
						<td>
							<label data='<?php  echo $item['status'];?>' class='label  label-default <?php  if($item['status']==1) { ?>label-info<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'status')"<?php  } ?>><?php  if($item['status']==1) { ?>上架<?php  } else { ?>下架<?php  } ?></label>
								<?php  if($item['isCheck'] == 3 ) { ?>
									<label class='label  label-default label-success'>草稿箱</label>  
								<?php  } ?>
						</td>
						<td style="position:relative;" class="myown" id="something">
							<a href="javascript:;" data-url="<?php  echo $this->createPluginMobileUrl('dealmerch/detail', array('id' => $item['id']))?>"  title="复制连接" class="btn btn-default btn-sm js-clip" style="background-color: #ffb034;"><i class="fa fa-link" style="color: #fff;"></i></a>
							<?php if(cv('shop.goods.edit|shop.goods.view')) { ?>
							<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealgoods', array('id' => $item['id'], 'op' => 'post'))?>"class="btn btn-sm btn-default" title="<?php if(cv('shop.goods.edit')) { ?>编辑<?php  } else { ?>查看<?php  } ?>" style="background-color: #ff5858;"><i class="fa fa-pencil" style="color: #fff;"></i></a><?php  } ?>
					
						<!-- 调整库存 -->
							<a href="javascript:;" id="changestack" onclick="getoption(<?php  echo $item['id'];?>)" class="btn btn-sm btn-default" title="调整库存" style="background-color: #00ff7f;"><i class="fa fa-gears" style="color: #fff;"></i></a> 
							<div id="only" style="display: none; ">
									<div class="popup-wrapper" style="position: fixed; top:0; left:0; right:0; bottom:0; width:100%; text-align:center; height:100%; display: table; z-index: 1000;">
											<div class="popup-shade" style="display: table-cell; vertical-align: middle; background-color: rgba(0,0,0,0.5); ">
												<div class="content" style="width: 600px; height: 420px; margin: 0 auto;  background-color: #ffffff;">

												</div>	
											</div>
									</div>
							</div>
						<!-- 调整库存 -->
						
							<?php if(cv('shop.goods.delete')) { ?>
							<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealgoods', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('确认删除此商品？');return false;" class="btn btn-default  btn-sm" title="删除" style="background-color: #2d2d2d;"><i class="fa fa-times" style="color: #fff;"></i></a><?php  } ?>
						</td>
					</tr>
					<?php  } } ?>
					<tr>
						<td colspan='10'>
							<?php if(cv('shop.goods.add')) { ?>
							<a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealgoods',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加商品</a>
							<?php  } ?>
							<?php if(cv('shop.goods.edit')) { ?> 
							<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
							<?php  } ?>
						</td> 
					</tr>
					</tbody>
				</table>
				<?php  echo $pager;?>
			</div>
		</div>
	</form>
</div>
</div>

<div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 920px;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择兑换点</h3></div>
			<div class="modal-body">

			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>

	</div>
</div>

<script type="text/javascript">



    function getoption(id) {

        //获取数据
        $.ajax({
            type: 'get',
            url: "<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_stockchange')?>"+"&op=get&id="+id,
            dataType:'json',
            success: function(data) {
                if (data.status == 0){
                    alert(data.result);
                    return false;
                }

                var html = '<table class="table table-hover" style="width: 100%;">';
                html+='<tr><th style="width:25%">商品名</th><th style="width:25%">规格名</th><th style="width:25%">库存</th><th style="width:25%">操作</th></tr>';
                for(var i in data.result){
                    html+='<tr>';
                    html+='<td>'+data.result[i].goodsname+'</td>';
                    html+='<td>'+data.result[i].title+'</td>';
                    html+='<td>'+data.result[i].stock+'</td>';
                    html+='<td><button data-id="'+data.result[i].id+'" class="editstock btn btn-default">修改库存</button></td>';
                    html+='</tr>';
                }
                html+='</table>';
                $('#modal-module-menus').find('.modal-body').html(html);
                $('#modal-module-menus').modal('show');
            }
        });
    }


    $('#modal-module-menus').on('click','.editstock',function(){
        var num=prompt('请输入新库存');
        var optionid=$(this).data('id');
        if(num == 0 || num == null){
            return false;
        }

        $.post(
            "<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_stockchange',array('op'=>'delete'))?>",
            {num:num,optionid:optionid},
            function(data){
                alert(data.result);				 	//直接返回string
                $('#modal-module-menus').modal('hide');
            },'json');
    });

	$('.fa-remove').click(function () {		/*清空*/
		$('[name="goodssn"]').val('');
		$('[name="title"]').val('');
    });

	function fastChange(id, type, value) {
		$.ajax({
			url: "<?php  echo $this->createWebUrl('shop/goods')?>",
			type: "post",
			data: {op: 'change', id: id, type: type, value: value},
			cache: false,
			success: function () {

			}
		})
	}
	$(function () {
		$("form").keypress(function(e) {
			if (e.which == 13) {
				return false;
			}
		});

		$('.tdedit input').keydown(function (event) {
			if (event.keyCode == 13) {
				var group = $(this).closest('.input-group');
				var type = group.find('button').data('type');
				var goodsid = group.find('button').data('goodsid');
				var val = $.trim($(this).val());
				if(type=='title' && val==''){
					return;
				}
				group.prev().show().find('span').html(val);
				group.hide();
				fastChange(goodsid,type,val);
			}
		})
		$('.tdedit').mouseover(function () {
			$(this).find('.fa-pencil').show();
		}).mouseout(function () {
			$(this).find('.fa-pencil').hide();
		});
		$('.fa-edit-item').click(function () {
			var group = $(this).closest('span').hide().next();

			group.show().find('button').unbind('click').click(function () {
				var type = $(this).data('type');
				var goodsid = $(this).data('goodsid');
				var val = $.trim(group.find(':input').val());
				if(type=='title' && val==''){
					Tip.show(group.find(':input'), '请输入名称!');
					return;
				}
				group.prev().show().find('span').html(val);
				group.hide();
				fastChange(goodsid,type,val);
			});
		})
	})
	var category = <?php  echo json_encode($children)?>;
	function setProperty(obj, id, type) {
		$(obj).html($(obj).html() + "...");
		$.post("<?php  echo $this->createWebUrl('shop/goods')?>"
				, {'op': 'setgoodsproperty', id: id, type: type, data: obj.getAttribute("data")}
				, function (d) {
					$(obj).html($(obj).html().replace("...", ""));
					if (type == 'type') {
						$(obj).html(d.data == '1' ? '实体物品' : '虚拟物品');
					}
					if (type == 'status') {
						$(obj).html(d.data == '1' ? '上架' : '下架');
					}
					$(obj).attr("data", d.data);
					if (d.result == 1) {
						$(obj).toggleClass("label-info text-pinfo");
					}
				}
				, "json"
		);
	}


	// $(function () {
		// $('[data-toggle="popover"]').popover();
	// });
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>