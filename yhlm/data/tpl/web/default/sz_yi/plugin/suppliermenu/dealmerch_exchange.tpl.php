<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealtabs', TEMPLATE_INCLUDEPATH)) : (include template('dealtabs', TEMPLATE_INCLUDEPATH));?>
<?php  if($op == 'display') { ?>
<!-- 查询 -->
<div class="panel panel-default" style="display:block;" id="list">
	<div class="panel-body">
    	<a class='btn btn-default' style="background-color: #1E95C9; color: #fff; border-radius: 6px;" href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_exchange',array('op'=>'add'))?>">添加兑换点</a>
    	<?php  if(false) { ?>
    	<a class='btn btn-default' style="background-color: #1E95C9; color: #fff; border-radius: 6px;" href="<?php  echo $this->createPluginWebUrl('suppliermenu/staff')?>">员工信息</a>
    	<?php  } ?> 		 
	</div>
   <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12"></div>
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:50px;'></th>
                    <th style='width:150px;'>兑换点名称</th>
                    <th style='width:150px;'>兑换点地址</th>
                    <th style='width:150px;'>联系电话</th>
                    <th style='width:150px;'>兑换日期</th>
                    <th style='width:150px;'>兑换时间</th>
                    <th style='width:150px;'>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($info)) { foreach($info as $row) { ?>
                <tr>
		            <td><input type="checkbox" name="id" value="<?php  echo $row['id'];?>"> </td>
		            <td><?php  echo $row['title'];?></td>
               		<td><?php  echo $row['address'];?></td> 
					<td><?php  echo $row['mobile'];?></td>
					<td><?php  echo $row['exchangeDate'];?></td>
					<td><?php  echo $row['exchangeTime'];?></td>
					<td><?php  if($row['status'] == 1 ) { ?><label class="label label-info">启用</label>  <?php  } else { ?> <label class="label label-danger">禁用 </label> <?php  } ?> </td>
					<td>
						<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_exchange',array('op'=>'edit','id'=>$row['id']))?>" class="btn btn-default" style="color: #fff; background-color: #8db98d;"> 编辑 </a>
						<a class="btn btn-danger" data-id="<?php  echo $row['id'];?>" onclick="return window.confirm('确定删除？');" href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_exchange', array('op' => 'delete', 'id'=>$row['id'])); ?>"> 删除 </a>
					</td>
                </tr>
            	<?php  } } ?>
            </tbody> 
    </table>
    <span class="btn btn-default" id="sel"> 全选 </span>
    <a href="jasscript:;" class="btn btn-danger" id="del"> 删除 </a>
    </div>
    <?php  echo $pager;?>
</div>
<?php  } ?>
<!-- 添加 -->
<?php  if($op == 'add' || $op == 'edit') { ?>
<form action="" method='post' class='form-horizontal' style="display:block;">
    <input type="hidden" name="id" value="<?php  echo $addrInfo['id'];?>">
    <input type="hidden" name="op" value="post">
    <input type="hidden" name="do" value="plugin">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="suppliermenu" />
    <input type="hidden" name="method" value="dealmerch_exchange" />
    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class="form-group notice">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
             
                    <div class="col-sm-4">
                        <!--<input type='hidden' id='noticeopenid' name='data[openid]' value="<?php  echo $dealmerchinfo['openid'];?>" />-->
              	
                    </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">兑换点名称</label>	
	                <div class="col-sm-9 col-xs-12">	
	                       <input type="text" name="title" class="form-control" value="<?php  echo $addrInfo['title'];?>" />
	                </div>	
	            </div>				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
			</div>	
            <div class="form-group">				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">兑换点地址</label>	
	                <div class="col-sm-9 col-xs-12">	
	                       	<div class="input-group" style="left: 15px;">
	                       		<input type="text" name="address" class="form-control" value="<?php  echo $addrInfo['address'];?>"/>
							</div>
	                </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group">				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	            	<label class="col-xs-12 col-sm-3 col-md-3 control-label">联系电话</label>	
	            	<div class="col-sm-9 col-xs-12">	
	                       <input type="text" name="mobile" class="form-control" value="<?php  echo $addrInfo['mobile'];?>" />
	            	</div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>

            <div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
						<label class="col-xs-12 col-sm-3 col-md-3 control-label">商家位置</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_form_field_coordinate('map',array('lng'=>$addrInfo['lng'],'lat'=>$addrInfo['lat']))?>
						</div>
					</div>
			</div>

            <div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">兑换日期</label>	
	                <div class="col-sm-9 col-xs-12 week">
						<label for="week1" style="user-select:none;">
							<input id="week1" type="checkbox" name="exchangeDate[]" <?php  if(in_array('1',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="1"/>周一
						</label>
						<label for="week2" style="user-select:none;">
							<input id="week2" type="checkbox" name="exchangeDate[]" <?php  if(in_array('2',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="2"/>周二
						</label>
						<label for="week3" style="user-select:none;">
							<input id="week3" type="checkbox" name="exchangeDate[]" <?php  if(in_array('3',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="3"/>周三
						</label>
						<label for="week4" style="user-select:none;">
							<input id="week4" type="checkbox" name="exchangeDate[]" <?php  if(in_array('4',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="4"/>周四
						</label>
						<label for="week5" style="user-select:none;">
							<input id="week5" type="checkbox" name="exchangeDate[]" <?php  if(in_array('5',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="5"/>周五
						</label>
						<label for="week6" style="user-select:none;">
							<input id="week6" type="checkbox" name="exchangeDate[]" <?php  if(in_array('6',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="6"/>周六
						</label>
						<label for="week7" style="user-select:none;"> 
							<input id="week7" type="checkbox" name="exchangeDate[]" <?php  if(in_array('7',$addrInfo['exchangeDate'])) { ?>checked<?php  } ?> value="7"/>周日
						</label>
	                </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>

    <!--  时间js插件  -->
            <div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">兑换时间</label>	
	                <div class="col-sm-9 col-xs-12">	
	                    <input type="text" name="exchangeTime" class="form-control" value="<?php  echo $addrInfo['exchangeTime'];?>"  />
	                    	<span class='help-block'>如:07:00 - 22:00</span>
	                </div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group"></div>

             <div class="form-group">				
             	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">状态</label>	
	                <div class="col-sm-9 col-xs-12">
						<label for="s1">
							<input id="s1" type="radio" name="status" <?php  if($addrInfo['status'] == 1 ) { ?>checked<?php  } ?> value="1" />启用  &nbsp;&nbsp;&nbsp;
						</label>
						<label for="s0"> 
							<input id="s0" type="radio" name="status" <?php  if($addrInfo['status'] == 0 ) { ?>checked<?php  } ?> value="0"/>禁用
						</label> 
	                </div> 				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group"></div>

            <div class="form-group"> 
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                           <button type="submit" value="提交" class="btn btn-primary col-lg-1"  id="onlyone">提交</button>
                           <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('dealmerch.dealmerch_exchange')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
            </div>
   		 </div>
   	</div>   
</form>
<?php  } ?>
<!-- </div> -->
<!-- </div> -->
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
