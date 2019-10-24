<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<style type='text/css'>
</style>
<?php  if($operation=='display') { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="supplier" />
            <input type="hidden" name="method" value="store" />
            <input type="hidden" name="op" value="display" />
            <div class="form-group">
            	<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">店铺名称</label>
	                <div class="col-sm-4">
	                    <input type="text" class="form-control" name="storename" value="<?php  echo $_GPC['storename'];?>" placeholder='店铺名称' /> 
	                </div>
	                <div class="col-sm-4">
	                	<div class="input-group">
	                	    <div class="input-group-addon" style="padding: 8px 12px 9px 12px; border-left: 1px #ccc solid; border-right: 1px #ccc solid; border-radius: 2px;">店铺状态
	                	    	<label class="radio-inline" style="margin-top:-7px;"><input type="radio" value="-1" <?php  if($_GPC['isopen'] == -1) { ?> checked="" <?php  } ?> name="isopen">关闭</label>
	                	        <label class="radio-inline" style="margin-top:-7px;"><input type="radio" value="1" <?php  if($_GPC['isopen'] == 1) { ?> checked="" <?php  } ?> name="isopen">开启</label>
	                	    </div>
	                	</div>
	                    <!-- <input type="radio" class="form-control" name="isopen" value="0" />  -->
	                    <!-- <input type="radio" class="form-control" name="isopen" value="1" />  -->
	                </div>
	            </div>
				<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                    <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                </div>
           </div>
        </form>
    </div>
    <div class="panel-body">
        <p>商家列表入口</p>
        <p><?php  echo $this->createPluginMobileUrl('supplier/store')?></p>
        <hr>
        <p id="qrcode"></p>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
                <tr>
                	<th width="120">店铺ID</th>
                    <th width="180">店铺名</th>
                    <th width="90">店铺Logo</th>
                    <th width="160">电话</th>
                    <th width="480">门店介绍</th>
                    <th width="160">门店入口</th>
                    <th width="">地址</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($stores)) { foreach($stores as $row) { ?>
	                <tr>
	                	<td><?php  echo $row['id'];?></td>
                        <td><?php  echo $row['storename'];?></td>
	                    <td>
                            <?php  if(!empty($row['logo'])) { ?>
                                <img src="../attachment/<?php  echo $row['logo'];?>" width="100%">
                            <?php  } else { ?>
                                暂无
                            <?php  } ?>
                        </td>
	                    <td><?php  echo $row['tel'];?></td>
	                    <td><?php  echo $row['description'];?></td>
	                    <td><a target="_blank" href="<?php  echo $this->createPluginMobileUrl('supplier/store', array('storeid' => $row['storeid'], 'op' => 'skip'));?>" title="<?php  echo $this->createPluginMobileUrl('supplier/store', array('storeid' => $row['storeid'], 'op' => 'skip'));?>">点击进入</a></td>
	                    <td><?php  echo $row['provance'];?><?php  echo $row['city'];?><?php  echo $row['area'];?><?php  echo $row['street'];?></td>
	                    <td>
	                    	<?php  if($row['isopen'] == 1) { ?><label class="label label-success"><a style="color:white;" href="<?php  echo $this->createPluginWebUrl('supplier/store', array('storeid' => $row['storeid'], 'op' => 'switch', 'status' => 0));?>" onclick="return confirm('您确定要关闭？？');">已开启，点击关闭店铺</a></label><?php  } ?>
	                    	<?php  if($row['isopen'] == 0) { ?><label class="label label-info"><a style="color:white;" href="<?php  echo $this->createPluginWebUrl('supplier/store', array('storeid' => $row['storeid'], 'op' => 'switch', 'status' => 1));?>" onclick="return confirm('您确定要开启？？');">已关闭，点击开启店铺</a></label><?php  } ?>
	                    </td>
	                </tr>
                <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
<script type="text/javascript">
$(function() {
    require(['jquery.qrcode'], function(){
        $('#qrcode').qrcode({
            render: 'canvas',
            width: 150,
            height: 150,
            text: "<?php  echo $this->createPluginMobileUrl('supplier/store')?>"
        });
    })
})

</script>
<?php  } else if($operation=='detail') { ?>

<?php  } ?>
</div> 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>