<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">邮费收支明细</div>
    <div class="panel-body">
                <label class="col-sm-3 col-md-3 control-label">邮费总额 <span class="credit3" style="color: #f00;"><?php  echo $postprice;?></span></label>
    </div>
</div>
<div class="panel panel-default">
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:20%">商品ID</th>
                    <th style="width:20%">商品名称</th>
                    <th style="width:20%">邮费</th>
                    <th style="width:20%">交易日期</th>
                    <!--<th style="width:20%">交易说明</th>-->
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($sp_goods)) { foreach($sp_goods as $row) { ?>
                <tr style="background: #eee">
                    <td><?php  echo $row['id'];?></td>
                    <td>
                        <?php  echo $row['optionname'];?>
                    </td>
                    <td><?php  echo $row['dispatchprice'];?></td>
                    <td><b><?php  echo date('Y-m-d H:i',$row['paytime'])?></b></td>
                    <!--<td><?php  echo $row['note'];?></td>-->
                </tr>
            <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>