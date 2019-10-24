<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if($operation != 'post') { ?>
 
<?php  } ?>
 <script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>

<?php  if($operation == 'post') { ?>


<style type='text/css'>
    .tab-pane {padding:20px 0 20px 0;}
</style>
<div class="main rightlist">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <div class="panel panel-default">
<!--             <div class="panel-heading">
                <?php  if(empty($item['id'])) { ?>添加商品<?php  } else { ?>编辑商品<?php  } ?>
            </div> -->
            <div class="panel-body">
            	
            	<div class="ulleft-nav">
                    <!-- <?php if(cv('shop.goods.view')) { ?><li><a href="<?php  echo $this->createWebUrl('shop/goods')?>">商品管理</a></li><?php  } ?> -->
                <ul class="nav nav-arrow-next nav-tabs" id="myTab">
                    <li class="active" ><a href="#tab_basic">基本信息</a></li>
                    <li><a href="#tab_des">商品描述</a></li>
                    <li><a href="#tab_param">自定义属性</a></li>
                    <li><a href="#tab_option">商品规格</a></li>
                 	<?php  if(in_array('suppliermenu.discount', $authority)) { ?><li><a href="#tab_discount">会员权限及折扣设置</a></li><?php  } ?>
				<?php  if(0) { ?>	<li><a href="#tab_share">分享及关注设置</a></li><?php  } ?>
                <li><a href="#tab_others">订单提醒</a></li>

                    <?php  if(p('verify')) { ?>
						<?php  if(in_array('suppliermenu.verify', $authority)) { ?><li><a href="#tab_verify">线下核销设置</a></li><?php  } ?>
                    <?php  } ?>


                    <?php  if(in_array('suppliermenu.distribution', $authority)) { ?>
	                    <?php  if(!empty($com_set['level'])) { ?>
	                    <li><a href="#tab_sell">分销设置</a></li>
	                    <?php  } ?>
                    <?php  } ?>

                    <?php  if(0) { ?>

                    <?php  if(p('sale')) { ?>
					<li><a href="#tab_sale">营销设置</a></li>
                    <?php  } ?>

                    <?php  } ?>

                    <?php  if(0) { ?>
                    <li><a href="#tab_detaildiy">详情页店铺信息设置</a></li>
                    <?php  } ?>

                    <?php  if(0) { ?>
                    <?php  if(p('diyform')) { ?>
					<li><a href="#tab_diyform">自定义表单</a></li>
                    <?php  } ?>
                    <?php  } ?>
                </ul> 
                </div>


                
                <div class="good-tit">
				    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
				    <div class="col-sm-10 col-xs-12 text-right">
						<input type="submit" name="submit" value="保存商品"
	                    class="btn btn-primary" onclick="return formcheck()" />
						<input type="button" name="back" onclick='history.back()' <?php if(cv('shop.goods.add|shop.goods.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
						<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				    </div>
				</div>
 

                <div class="tab-content" style="overflow:inherit">
                    <div class="tab-pane  active" id="tab_basic"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/basic', TEMPLATE_INCLUDEPATH)) : (include template('goods/basic', TEMPLATE_INCLUDEPATH));?></div>
                    <div class="tab-pane" id="tab_des"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/des', TEMPLATE_INCLUDEPATH)) : (include template('goods/des', TEMPLATE_INCLUDEPATH));?></div>
                    <div class="tab-pane" id="tab_param"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/param', TEMPLATE_INCLUDEPATH)) : (include template('goods/param', TEMPLATE_INCLUDEPATH));?></div>
                    <div class="tab-pane" id="tab_option"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/option', TEMPLATE_INCLUDEPATH)) : (include template('goods/option', TEMPLATE_INCLUDEPATH));?></div>
                	<?php  if(in_array('suppliermenu.discount', $authority)) { ?><div class="tab-pane" id="tab_discount"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/discount', TEMPLATE_INCLUDEPATH)) : (include template('goods/discount', TEMPLATE_INCLUDEPATH));?></div><?php  } ?>
                  <?php  if(0) { ?>  <div class="tab-pane" id="tab_share"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/share', TEMPLATE_INCLUDEPATH)) : (include template('goods/share', TEMPLATE_INCLUDEPATH));?></div> <?php  } ?>
                  <div class="tab-pane" id="tab_others"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/others', TEMPLATE_INCLUDEPATH)) : (include template('goods/others', TEMPLATE_INCLUDEPATH));?></div>

                    <div class="tab-pane" id="tab_detaildiy"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/detaildiy', TEMPLATE_INCLUDEPATH)) : (include template('goods/detaildiy', TEMPLATE_INCLUDEPATH));?></div>
 
                    <?php  if(p('verify')) { ?>
                    	<?php  if(in_array('suppliermenu.verify', $authority)) { ?><div class="tab-pane" id="tab_verify"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/verify', TEMPLATE_INCLUDEPATH)) : (include template('goods/verify', TEMPLATE_INCLUDEPATH));?></div><?php  } ?>
                    <?php  } ?>


                    <?php  if(p('commission') && !empty($com_set['level'])) { ?>
                    	<?php  if(in_array('suppliermenu.distribution', $authority)) { ?><div class="tab-pane" id="tab_sell"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/commission', TEMPLATE_INCLUDEPATH)) : (include template('goods/commission', TEMPLATE_INCLUDEPATH));?></div><?php  } ?>
                    <?php  } ?> 


                    <?php  if(p('sale')) { ?>
                    <div class="tab-pane" id="tab_sale"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/sale', TEMPLATE_INCLUDEPATH)) : (include template('goods/sale', TEMPLATE_INCLUDEPATH));?></div>
                    <?php  } ?>
 

                    <?php  if(p('diyform')) { ?>
                    <div class="tab-pane" id="tab_diyform"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/diyform', TEMPLATE_INCLUDEPATH)) : (include template('goods/diyform', TEMPLATE_INCLUDEPATH));?></div>
                    <?php  } ?>

                </div>

 
		        <div class="form-group col-sm-12 mrleft40 border-t">
		        	<label class="col-xs-12 col-sm-3 col-md-2 control-label">&nbsp;</label>
			 
					<input type="submit" name="submit" value="保存商品" class="btn btn-primary col-lg-1" onclick="return formcheck()" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			 
					<input type="button" name="back" onclick='history.back()'  style='margin-left:10px;'  value="返回列表" class="btn btn-default" />

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
				$(e).context.checked=true;
			}
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
		<?php  if($shopset['catlevel'] == 3) { ?>
		if ($("#category_third").val() == '0') {
		$('#myTab a[href="#tab_basic"]').tab('show');
				Tip.focus("#category_third", "请选择完整商品分类!");
				return false;
		}
		<?php  } else { ?>
		if ($("#category_child").val() == '0') {
			$('#myTab a[href="#tab_basic"]').tab('show');
			Tip.focus("#category_child", "请选择完整商品分类!");
			return false;
		}

		<?php  } ?>

		<?php  if(empty($id)) { ?>
		if ($.trim($(':input[name="thumb"]').val()) == '') {
		$('#myTab a[href="#tab_basic"]').tab('show');
				Tip.focus(':input[name="thumb"]', '请上传缩略图.');
				return false;
		}
		<?php  } ?>
				var full = true;
		if (window.type == '3') {

			if (window.virtual != '0') {  //如果单规格，不能有规格

				if ($('#hasoption').get(0).checked) {

					$('#myTab a[href="#tab_option"]').tab('show');
					util.message('您的商品类型为：虚拟物品(卡密)的单规格形式，需要关闭商品规格！');
					return false;
				}
			}
			else {

				var has = false;
				$('.spec_item_virtual').each(function () {
					has = true;
					if ($(this).val() == '' || $(this).val() == '0') {
						$('#myTab a[href="#tab_option"]').tab('show');
						Tip.focus($(this).next(), '请选择虚拟物品模板!');
						full = false;
						return false;
					}
				});
				if (!has) {
					$('#myTab a[href="#tab_option"]').tab('show');
					util.message('您的商品类型为：虚拟物品(卡密)的多规格形式，请添加规格！');
					return false;
				}
			}
		}
		if (!full) {
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
                <input type="hidden" name="method" value="goods">
                <input type="hidden" name="op" value="display" />
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">关键字</label>
                    <div class="col-xs-12 col-sm-8 col-lg-9">
                        <input class="form-control" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">状态</label>
                    <div class="col-xs-12 col-sm-8 col-lg-9">
                        <select name="status" class='form-control'>
							<option value="" <?php  if($_GPC['status'] == '') { ?> selected<?php  } ?>></option>
                            <option value="1" <?php  if($_GPC['status']== '1') { ?> selected<?php  } ?>>上架</option>
                            <option value="0" <?php  if($_GPC['status'] == '0') { ?> selected<?php  } ?>>下架</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">分类</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php  if(intval($shopset['catlevel'])==3) { ?>
						<?php  echo tpl_form_field_category_level3('category', $parent, $children, $params[':pcate'], $params[':ccate'], $params[':tcate'])?>
						<?php  } else { ?>
						<?php  echo tpl_form_field_category_level2('category', $parent, $children, $params[':pcate'], $params[':ccate'])?>
						<?php  } ?>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-lg-2">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                    </div>
                </div>

                <div class="form-group">
                </div>
            </form>
        </div>
    </div>
    <style>
        .label{cursor:pointer;}
    </style>
	<div class="panel panel-default"> 
        <div class="panel-body">
        	<script type="text/javascript">
        		<?php  if($_W['isdealmerch'] == 2) { ?>
        			window.location.href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealgoods')?>";
        		<?php  } ?>    
        	</script>  
			<a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('suppliermenu/goods',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加商品</a>
        </div>
	</div>
    <form action="" method="post">
		<div class="panel panel-default">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<thead class="navbar-inner">
						<tr>
							<th width="6%">ID</th>
							<th width="6%">排序</th>
							<th width="6%">商品</th>
							<th width="32%">&nbsp;</th>
<!-- 							<th width="26%">属性</th> -->
							<th width="10%">价格</th>
							<th width="10%">库存</th>
							<th width="9%">销量</th>
							<th width="9%">状态</th>
							<th width="14%">操作</th>
						</tr>
					</thead>
					<tbody>
						<?php  if(is_array($list)) { foreach($list as $item) { ?>
						<tr>

							<td><?php  echo $item['id'];?></td>
							<td>
								<?php if(cv('shop.goods.edit')) { ?>
								<input type="text" class="form-control" name="displayorder[<?php  echo $item['id'];?>]" value="<?php  echo $item['displayorder'];?>">
								<?php  } else { ?>
								<?php  echo $item['displayorder'];?> 
								<?php  } ?>
							</td>
							<td title="<?php  echo $item['title'];?>">
								<img src="<?php  echo tomedia($item['thumb'])?>" style="width:40px;height:40px;padding:1px;border:1px solid #ccc;"  />
							</td>
							<td title="<?php  echo $item['title'];?>" class='tdedit'>
								<?php  if(!empty($category[$item['pcate']])) { ?>
								<span class="text-danger">[<?php  echo $category[$item['pcate']]['name'];?>]</span>
								<?php  } ?>
								<?php  if(!empty($category[$item['ccate']])) { ?>
								<span class="text-info">[<?php  echo $category[$item['ccate']]['name'];?>]</span>
								<?php  } ?>
								<?php  if(!empty($category[$item['tcate']]) && intval($shopset['catlevel'])==3) { ?>
								<span class="text-info">[<?php  echo $category[$item['tcate']]['name'];?>]</span>
								<?php  } ?>
								<br/>
								<?php if(cv('shop.goods.edit')) { ?>

								<span class=' fa-edit-item' style='cursor:pointer'><i class='fa fa-pencil' style="display:none"></i> <span class="title"><?php  echo $item['title'];?></span></span>
								<div class="input-group goodstitle" style="display:none" data-goodsid="<?php  echo $item['id'];?>">
									<input type='text' class='form-control' value="<?php  echo $item['title'];?>" />
									<div class="input-group-btn">
										<button type="button" class="btn btn-default" data-goodsid='<?php  echo $item['id'];?>' data-type="title"><i class="fa fa-check"></i></button>
									</div>
								</div>
								<?php  } else { ?>
								<?php  echo $item['title'];?>
								<?php  } ?>
							</td>
							<td class='tdedit'>
								<?php  if($item['hasoption']==1) { ?>
								<?php if(cv('shop.goods.edit')) { ?>
								<span class='tip' title='多规格不支持快速修改'><?php  echo $item['marketprice'];?></span>
								<?php  } else { ?>
								<?php  echo $item['marketprice'];?>
								<?php  } ?>
								<?php  } else { ?>
								<?php if(cv('shop.goods.edit')) { ?>

								<span class=' fa-edit-item' style='cursor:pointer'><i class='fa fa-pencil' style="display:none"></i> <span class="title"><?php  echo $item['marketprice'];?></span> </span>
								<div class="input-group" style="display:none" data-goodsid="<?php  echo $item['id'];?>">
									<input type='text' class='form-control' value="<?php  echo $item['marketprice'];?>"   />
									<div class="input-group-btn">
										<button type="button" class="btn btn-default" data-goodsid='<?php  echo $item['id'];?>' data-type="marketprice"><i class="fa fa-check"></i></button>
									</div>
								</div>
								<?php  } else { ?>
								<?php  echo $item['marketprice'];?>
								<?php  } ?><?php  } ?>

							</td>

							<td class='tdedit'>
								<?php  if($item['hasoption']==1) { ?>
								<?php if(cv('shop.goods.edit')) { ?>
								<span class='tip' title='多规格不支持快速修改'><?php  echo $item['total'];?></span>
								<?php  } else { ?>
								<?php  echo $item['total'];?>
								<?php  } ?>
								<?php  } else { ?>
								<?php if(cv('shop.goods.edit')) { ?>

								<span class=' fa-edit-item' style='cursor:pointer'><i class='fa fa-pencil' style="display:none"></i> <span class="title"><?php  echo $item['total'];?></span> </span>
								<div class="input-group" style="display:none" data-goodsid="<?php  echo $item['id'];?>">
									<input type='text' class='form-control' value="<?php  echo $item['total'];?>"   />
									<div class="input-group-btn">
										<button type="button" class="btn btn-default" data-goodsid='<?php  echo $item['id'];?>' data-type="total"><i class="fa fa-check"></i></button>
									</div>
								</div>
								<?php  } else { ?>
								<?php  echo $item['total'];?>
								<?php  } ?><?php  } ?>

							</td>
							<td><?php  echo $item['salesreal'];?></td>
							<td>

                                <?php  if(p('supplier')) { ?>
                                <?php  if($_W['isfounder'] == 1) { ?>
                                    <label data='<?php  echo $item['status'];?>' class='label  label-default <?php  if($item['status']==1) { ?>label-info<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'status')"<?php  } ?>><?php  if($item['status']==1) { ?>上架<?php  } else { ?>下架<?php  } ?></label>
                                <?php  } else { ?>
                                    <?php  $roleid = pdo_fetchcolumn('select id from' . tablename('sz_yi_perm_role') . ' where uniacid = '.$_W['uniacid'].' and status1=1')?>
                                    <?php  $user = pdo_fetch('select roleid,setting from' . tablename('sz_yi_perm_user') . ' where uid=' . $_W['uid']); $setting = json_decode($user['setting'],true); ?>
                                    <?php  if($roleid == $user['roleid']&&empty($setting['shelves'])) { ?>
                                        <label data='<?php  echo $item['status'];?>' class='label  label-default <?php  if($item['status']==1) { ?>label-info<?php  } ?>' >
                                    <?php  } else { ?>
                                        <label data='<?php  echo $item['status'];?>' class='label  label-default <?php  if($item['status']==1) { ?>label-info<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'status')"<?php  } ?>>
                                    <?php  } ?>
                                    <?php  if($item['status']==1) { ?>上架<?php  } else { ?>下架<?php  } ?>
                                    </label>
                                <?php  } ?>
                            <?php  } else { ?>
                                <label data='<?php  echo $item['status'];?>' class='label  label-default <?php  if($item['status']==1) { ?>label-info<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'status')"<?php  } ?>><?php  if($item['status']==1) { ?>上架<?php  } else { ?>下架<?php  } ?></label>
                            <?php  } ?>

							</td>
							<td style="position:relative;">
								<a href="javascript:;" data-url="<?php  echo $this->createMobileUrl('shop/detail', array('id' => $item['id']))?>"  title="复制连接" class="btn btn-default btn-sm js-clip"><i class="fa fa-link"></i></a>
								<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/goods', array('id' => $item['id'], 'op' => 'post'))?>" class="btn btn-sm btn-default" title="编辑""><i class="fa fa-pencil"></i></a>
								<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/goods', array('id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('确认删除此商品？');return false;" class="btn btn-default  btn-sm" title="删除"><i class="fa fa-times"></i></a>
							</td>
						</tr>
						<tr>
						<td  colspan="10" style="text-align: right;padding: 6px 0;border-top:none;">
						<label data='<?php  echo $item['isnew'];?>' class='label label-default text-default <?php  if($item['isnew']==1) { ?>label-info text-pinfo<?php  } else { ?><?php  } ?>'   <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'new')"<?php  } ?>>新品</label>-

						<label data='<?php  echo $item['ishot'];?>' class='label label-default text-default <?php  if($item['ishot']==1) { ?>label-info text-pinfo<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'hot')"<?php  } ?>>热卖</label>-

						<label data='<?php  echo $item['isrecommand'];?>' class='label label-default text-default <?php  if($item['isrecommand']==1) { ?>label-info text-pinfo<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'recommand')"<?php  } ?>>推荐</label>-

						<label data='<?php  echo $item['isdiscount'];?>' class='label label-default text-default <?php  if($item['isdiscount']==1) { ?>label-info text-pinfo<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'discount')"<?php  } ?>>促销</label>-

						<label data='<?php  echo $item['issendfree'];?>' class='label label-default text-default <?php  if($item['issendfree']==1) { ?>label-info text-pinfo<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'sendfree')"<?php  } ?>>包邮</label>-

						<label data='<?php  echo $item['istime'];?>' class='label label-default text-default <?php  if($item['istime']==1) { ?>label-info text-pinfo<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'time')"<?php  } ?>>限时卖</label>-

						<label data='<?php  echo $item['isnodiscount'];?>' class='label label-default text-default <?php  if($item['isnodiscount']==1) { ?>label-info text-pinfo<?php  } ?>' <?php if(cv('shop.goods.edit')) { ?>onclick="setProperty(this,<?php  echo $item['id'];?>,'nodiscount')"<?php  } ?>>不参与折扣</label>

						</td>
						</tr>
						<?php  } } ?>
						<tr>
							<td colspan='10'>
								<a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('suppliermenu/goods',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加商品</a>
								 
								<input name="submit" type="submit" class="btn btn-default" value="提交排序">
								<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
								 
							</td>
						</tr>

						</tr>
					</tbody>
				</table>
				<?php  echo $pager;?>
			</div>
		</div>
	</form>
</div>
</div>
<script type="text/javascript">
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

$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
