<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">易货码收支查询</div>
    <div class="panel-body">
        <form action="">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="sz_yi">
            <input type="hidden" name="p" value="dealmerch">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="method" value="barter_code_log">
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                <label class="col-md-3 label-control">交易编号：</label>
                <div class="from-group col-md-9">
                    <input class="form-control col-sm-3 col-md-10" type="text" name="dealsn" value="<?php  echo $_GPC['dealsn'];?>" placeholder="请输入交易编号">
                </div>
            </div>
            <div class="col-lg-8  col-md-6 col-sm-6 col-xs-12">
                <div class="col-sm-2">
                    <label class="radio-inline"><input type="radio" name="searchtime" value="0" <?php  if(empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>不搜索</label>
                    <label class="radio-inline"><input type="radio" name="searchtime" value="1" <?php  if(!empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>搜索</label>
                </div>
                <div class="col-sm-7 col-lg-6 col-xs-12">
                    <?php  echo tpl_form_field_daterange('datetime', array('starttime'=>date('Y-m-d H:i',$starttime),'endtime'=>date('Y-m-d H:i',$endtime)), true)?>
                </div>
                <button class="btn btn-default"><i class="fa fa-search"></i> 查询</button>
                <button class="btn btn-default" type="reset"><i class="fa fa-search"></i> 清空</button>
                <?php  if('statistics.export.order') { ?>
                <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
                <?php  } ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                <label class="col-md-1 label-control">交易类型：</label>
                <div class="col-md-10">
                    <label for="all">
                        <input type="checkbox" id="all"> 全选
                    </label>
                    <label for="r1">
                        <input type="checkbox" class="check" id="r1" name="r1" value="1" <?php  if($_GPC["r1"]) { ?>checked<?php  } ?>> 购物支出
                    </label>
                    <?php  if(false) { ?>
                    <label for="r2">
                        <input type="checkbox" class="check" id="r2" name="r2" value="1" <?php  if($_GPC["r2"]) { ?>checked<?php  } ?>> 金币兑换(支出)
                    </label>
                    <label for="r3">
                        <input type="checkbox" class="check" id="r3" name="r3" value="1" <?php  if($_GPC["r3"]) { ?>checked<?php  } ?>> 销售收入返还
                    </label>
                    <label for="r4">
                        <input type="checkbox" class="check" id="r4" name="r4" value="1" <?php  if($_GPC["r4"]) { ?>checked<?php  } ?>> 佣金支出
                    </label>
                    <label for="r5">
                        <input type="checkbox" class="check" id="r5" name="r5" value="1" <?php  if($_GPC["r5"]) { ?>checked<?php  } ?>> 易货码支出
                    </label>
                    <?php  } ?>
                    <label for="r6">
                        <input type="checkbox" class="check" id="r6" name="r6" value="1" <?php  if($_GPC["r6"]) { ?>checked<?php  } ?>> 激活易货码(支出)
                    </label>
                    <label for="r7">
                        <input type="checkbox" class="check" id="r7" name="r7" value="1" <?php  if($_GPC["r7"]) { ?>checked<?php  } ?>> 赠出
                    </label>
                    <label for="r8">
                        <input type="checkbox" class="check" id="r8" name="r8" value="1" <?php  if($_GPC["r8"]) { ?>checked<?php  } ?>> 人工充值(支出)
                    </label>
                    <?php  if(false) { ?>
                    <label for="r9">
                        <input type="checkbox" class="check" id="r9" name="r9" value="1" <?php  if($_GPC["r9"]) { ?>checked<?php  } ?>> 人工扣除(支出)
                    </label>
                    <label for="r10">
                        <input type="checkbox" class="check" id="r10" name="r10" value="1" <?php  if($_GPC["r10"]) { ?>checked<?php  } ?>> 发布现金商品(支出)
                    </label>
                    <?php  } ?>
                    <label for="r11">
                        <input type="checkbox" class="check" id="r11" name="r11" value="1" <?php  if($_GPC["r11"]) { ?>checked<?php  } ?>> 人工冻结
                    </label>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
                <label class="col-md-1 label-control"></label>
                <div class="col-md-10" style="margin-left: 45px;">
                    <label for="r12">
                        <input type="checkbox" class="check" id="r12" name="r12" value="1" <?php  if($_GPC["r12"]) { ?>checked<?php  } ?>> 销售收入
                    </label>
                    <?php  if(false) { ?>
                    <label for="r13">
                        <input type="checkbox" class="check" id="r13" name="r13" value="1" <?php  if($_GPC["r13"]) { ?>checked<?php  } ?>> 金币兑换(收入)
                    </label>
                    <label for="r14">
                        <input type="checkbox" class="check" id="r14" name="r14" value="1" <?php  if($_GPC["r14"]) { ?>checked<?php  } ?>> 购物消费返还
                    </label>
                    <label for="r15">
                        <input type="checkbox" class="check" id="r15" name="r15" value="1" <?php  if($_GPC["r15"]) { ?>checked<?php  } ?>> 佣金收入
                    </label>
                    <label for="r16">
                        <input type="checkbox" class="check" id="r16" name="r16" value="1" <?php  if($_GPC["r16"]) { ?>checked<?php  } ?>> 易货码收入
                    </label>
                    <?php  } ?>
                    <label for="r17">
                        <input type="checkbox" class="check" id="r17" name="r17" value="1" <?php  if($_GPC["r17"]) { ?>checked<?php  } ?>> 激活易货码(收入)
                    </label>
                    <label for="r18">
                        <input type="checkbox" class="check" id="r18" name="r18" value="1" <?php  if($_GPC["r18"]) { ?>checked<?php  } ?>> 赠入
                    </label>
                    <label for="r19">
                        <input type="checkbox" class="check" id="r19" name="r19" value="1" <?php  if($_GPC["r19"]) { ?>checked<?php  } ?>> 人工充值(收入)
                    </label>
                    
                    <?php  if(false) { ?>
                    <label for="r20">
                        <input type="checkbox" class="check" id="r20" name="r20" value="1" <?php  if($_GPC["r20"]) { ?>checked<?php  } ?>> 人工扣除(收入)
                    </label>
                    <label for="r21">
                        <input type="checkbox" class="check" id="r21" name="r21" value="1" <?php  if($_GPC["r21"]) { ?>checked<?php  } ?>> 发布现金商品(收入)
                    </label>
                    <?php  } ?>
                </div>       
            </div>

        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:12.5%">ID</th>
                    <th style="width:12.5%">交易编号</th>
                    <th style="width:12.5%">交易类型</th>
                    <th style="width:12.5%">交易额度</th>
                    <th style="width:12.5%">交易状态</th>
                    <th style="width:12.5%">关联类型</th>
                    <th style="width:12.5%">交易时间</th>
                    <th style="width:12.5%">交易说明</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr style="background: #eee">
                    <td><?php  echo $row['id'];?></td>
                    <td><?php  echo $row['dealsn'];?></td>
                    <td>
                        <b>
                            <?php  if($row['type'] == 1) { ?>
                                购物支出
                            <?php  } else if($row['type'] == 2) { ?>
                                销售收入
                            <?php  } else if($row['type'] == 3) { ?>
                                金币兑换(支出)
                            <?php  } else if($row['type'] == 4) { ?>
                                金币兑换(收入)
                            <?php  } else if($row['type'] == 5) { ?>
                                销售收入返还
                            <?php  } else if($row['type'] == 6) { ?>
                                购物消费返还
                            <?php  } else if($row['type'] == 7) { ?>
                                佣金支出
                            <?php  } else if($row['type'] == 8) { ?>
                                佣金收入
                            <?php  } else if($row['type'] == 9) { ?>
                                易货码支出
                            <?php  } else if($row['type'] == 10) { ?>
                                易货码收入
                            <?php  } else if($row['type'] == 11) { ?>
                                激活易货码(支出)
                            <?php  } else if($row['type'] == 12) { ?>
                                激活易货码(收入)
                            <?php  } else if($row['type'] == 13) { ?>
                                赠出
                            <?php  } else if($row['type'] == 14) { ?>
                                赠入
                            <?php  } else if($row['type'] == 15) { ?>
                                人工充值(支出)
                            <?php  } else if($row['type'] == 16) { ?>
                                人工充值(收入)
                            <?php  } else if($row['type'] == 17) { ?>
                               人工扣除(支出)
                            <?php  } else if($row['type'] == 18) { ?>
                               人工扣除(收入)
                            <?php  } else if($row['type'] == 19) { ?>
                                发布现金商品(支出)
                            <?php  } else if($row['type'] == 20) { ?>
                                发布现金商品(收入)
                            <?php  } else if($row['type'] == 21) { ?>
                                人工冻结
                            <?php  } ?>
                        </b>
                    </td>
                    <td>
                        <?php  if($row['type'] % 2 == 0) { ?>
                            <b style="color: #0f0"><?php  echo $row['currency'];?></b></td>
                        <?php  } else { ?>
                            <b style="color: #f00"><?php  echo $row['currency'];?></b></td>
                        <?php  } ?>
                    <td>
                        <b>
                            <?php  if($row['status'] == 1) { ?>
                                正常
                            <?php  } else { ?>
                                失效
                            <?php  } ?>
                        </b>
                    </td>
                    <td>
                        <b>
                            <?php  if($row['assoctype'] == 1) { ?>
                                易货订单
                            <?php  } else if($row['assoctype'] == 2) { ?>
                                激活易货码
                            <?php  } else if($row['assoctype'] == 3) { ?>
                                易货码收支
                            <?php  } ?>
                        </b>
                    </td>
                    <td><?php  echo date('Y-m-d H:i',$row['dealtime'])?></td>
                    <td><b><?php  echo $row['note'];?></b></td>
                </tr>
            <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
</div>
<script>
    $('#all').click(function () {
        sure=$(this).is(':checked');
        console.log(sure);
        $('.check').each(function (index,ele) {
                ele.checked=sure;
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>