<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">易货额度收支查询</div>
    <div class="panel-body">
        <form action="">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="sz_yi">
            <input type="hidden" name="p" value="suppliermenu">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="method" value="barter_currency">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <select name="type" class="form-control col-sm-3 col-md-3" style="margin-bottom: 15px">
                        <option <?php  if($_GPC['type']==0) { ?>selected<?php  } ?> value="0">全部</option>
                        <option <?php  if($_GPC['type']==1) { ?>selected<?php  } ?> value="1">购买易货额度</option>
                        <option <?php  if($_GPC['type']==2) { ?>selected<?php  } ?> value="2">下架解冻</option>
                        <option <?php  if($_GPC['type']==3) { ?>selected<?php  } ?> value="3">人工冻结</option>
                        <!-- <option <?php  if($_GPC['type']==4) { ?>selected<?php  } ?> value="4">上架冻结</option> -->
                        <option <?php  if($_GPC['type']==5) { ?>selected<?php  } ?> value="5">购买会员赠送</option>
                        <!-- <option <?php  if($_GPC['type']==6) { ?>selected<?php  } ?> value="6">首次注册商家成功赠送</option>
                        <option <?php  if($_GPC['type']==7) { ?>selected<?php  } ?> value="7">定向易货退回</option>
                        <option <?php  if($_GPC['type']==8) { ?>selected<?php  } ?> value="8">广告资源置换所得</option> -->
                        <option <?php  if($_GPC['type']==9) { ?>selected<?php  } ?> value="9">购买获取</option>
                        <option <?php  if($_GPC['type']==10) { ?>selected<?php  } ?> value="10">平台赠送</option> 	 		 	 	
                        <option <?php  if($_GPC['type']==11) { ?>selected<?php  } ?> value="11">销售收入易货码自动激活</option>
                </select>
                <label class="col-sm-3 col-md-3 control-label">可用易货额度 <span class="credit3" style="color: #f00;"><?php  echo $member['currency_credit3'];?></span></label>
                <label class="col-sm-3 col-md-3 control-label">已消耗易货额度 <span class="freeze_credit3" style="color: #f00;"><?php  echo $member['use'];?></span></label>
                <label class="col-sm-3 col-md-3 control-label">上架冻结易货额度 <span style="color: #f00;"></span></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-sm-2">
                    <label class="radio-inline"><input type="radio" name="searchtime" value="0" <?php  if(empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>不搜索</label>
                    <label class="radio-inline"><input type="radio" name="searchtime" value="1" <?php  if(!empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>搜索</label>
                </div>
                <div class="col-sm-7 col-lg-7 col-xs-12">
                    <?php  echo tpl_form_field_daterange('datetime', array('starttime'=>date('Y-m-d H:i',$starttime),'endtime'=>date('Y-m-d H:i',$endtime)), true)?>
                </div>
                <button class="btn btn-default"><i class="fa fa-search"></i> 查询</button>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:20%">用户名</th>
                    <th style="width:20%">交易类型</th>
                    <th style="width:20%">交易额度</th>
                    <th style="width:20%">交易日期</th>
                    <th style="width:20%">交易说明</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr style="background: #eee">
                    <td><?php  echo $row['username'];?>/<?php  echo $row['realname'];?></td>
                    <td>
                       <?php  if($row['type'] == 1) { ?>
                			购买易货额度
            			<?php  } else if($row['type']  == 2) { ?>
        				下架解冻
        				<?php  } else if($row['type']  == 3) { ?>
        				人工冻结
        				<?php  } else if($row['type']  == 4) { ?>
        				上架解冻
        				<?php  } else if($row['type']  == 5) { ?>
        				购买会员赠送
        				<?php  } else if($row['type']  == 6) { ?>
        				首次注册商家成功赠送
        				<?php  } else if($row['type']  == 7) { ?>
        				定向易货退回
        				<?php  } else if($row['type']  == 8) { ?>
        				广告资源置换所得
        				<?php  } else if($row['type']  == 9) { ?>
        				购买获取
        				<?php  } else if($row['type']  == 10) { ?>
        				平台赠送
        				<?php  } else if($row['type']  == 11) { ?>
            			商家冻结易货码激活
                        <?php  } ?>
                    </td>
                    <td><?php  echo $row['currency'];?></td>
                    <td><b><?php  echo date('Y-m-d H:i',$row['dealtime'])?></b></td>
                    <td><?php  echo $row['note'];?></td>
                </tr>
            <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>