<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">按时间查询销售数量和销售金额</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal"  id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="dealmerch" />
            <input type="hidden" name="method" value="dealmerch_sales_statis" />
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">
                    <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">商品编号</label>
                    <div class="col-sm-8 col-lg-2 col-xs-12">
                        <input type="text" class="form-control" name="goodssn" value="<?php  echo $_GPC['goodssn'];?>">
                    </div>
                    <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">商品名称</label>
                    <div class="col-sm-8 col-lg-2 col-xs-12">
                        <input type="text" class="form-control" name="goodstitle" value="<?php  echo $_GPC['goodstitle'];?>">
                    </div>
                    <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">规格型号</label>
                    <div class="col-sm-8 col-lg-2 col-xs-12">
                        <input type="text" class="form-control" name="optitle" value="<?php  echo $_GPC['optitle'];?>">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-2 col-md-3 col-lg-3 control-label">订单时间</label>
                    <div class="col-sm-2">
                        <label class="radio-inline"><input type="radio" name="searchtime" value="0" <?php  if(empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>不搜索</label>
                        <label class="radio-inline"><input type="radio" name="searchtime" value="1" <?php  if(!empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>搜索</label>
                    </div>
                    <div class="col-sm-7 col-lg-7 col-xs-12">
                        <?php  echo tpl_form_field_daterange('datetime', array('starttime'=>date('Y-m-d H:i',$starttime),'endtime'=>date('Y-m-d H:i',$endtime)), true)?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <button class="btn btn-default" ><i class="fa fa-search"></i> 搜索</button>
                    <button class="btn btn-default" type="reset"><i class="fa fa-trash-o"></i> 清空</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <?php  if('statistics.export.order') { ?>
                    <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
                    <?php  } ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">共计 <span style="color:red; "><?php  echo $totalcount;?></span> 条记录 </div>
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">
                <tr><th style="width:20%">序号</th>
                    <th style="width:20%">商品编号</th>
                    <th style="width:20%">商品名称</th>
                    <th style="width:20%">销售总数量</th>
                    <th style="width:20%">销售收入(易货码)</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $index => $row) { ?>
                <tr style="background: #eee">
                    <td><?php  echo $index+1?></td>
                    <td><?php  echo $row['goodssn'];?></td>
                    <td><?php  echo $row['title'];?></td>
                    <td><?php  echo $row['sales'];?></td>
                    <td><?php  echo $row['salesincome'];?></td>
                </tr>
                <tr>
                <td colspan="15">
           <?php  if(is_array($row['options'])) { foreach($row['options'] as $g) { ?>
            <table style="width:auto;float:left;margin:10px 10px 0 10px;" title="<?php  echo $g['title'];?>">
                <tr>
                    <td style="width:auto;"><img src="<?php  echo tomedia($g['thumb'])?>" style="width: 50px; height: 50px;border:1px solid #ccc;padding:1px;"></td>
                    <td style="width:auto">
                        规格型号:<?php  echo $g['title'];?> <br/>
                        销售数量: <?php  echo $g['sales'];?><br/>
                        销售收入: <strong><?php  echo $g['salesincome'];?></strong>
                    </td>
                </tr>
            </table>
           <?php  } } ?>
         
                </td></tr>
            <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>