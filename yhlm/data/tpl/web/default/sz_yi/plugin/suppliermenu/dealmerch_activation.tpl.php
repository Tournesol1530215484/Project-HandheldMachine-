<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">易货码激活管理</div>
    <div class="panel-body">
        <form action="">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="sz_yi">
            <input type="hidden" name="p" value="suppliermenu">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="method" value="dealmerch_activation">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="col-sm-3 col-md-3 control-label">可用易货码 <span class="credit3" style="color: #f00;"><?php  echo $member['credit3'];?></span></label>
                <label class="col-sm-3 col-md-3 control-label">未激活易货码 <span class="freeze_credit3" style="color: #f00;"><?php  echo $member['freeze_credit3'];?></span></label>
                <label class="col-sm-3 col-md-3 control-label">可用易货额度 <span style="color:#f00;"><?php  echo $member['currency_credit3'];?></span></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <button class="btn btn-default"><i class="fa fa-search"></i> 查询</button>
                <button class="btn btn-default" onclick="popwin = $('#modal-module-menus').modal();" type="reset"><i class="fa fa-thumbs-up"></i> 激活易货码 </button>

                <!--<button class="btn btn-default" onclick="popwin = $('#modal-module-menus').modal();"  type="reset"><i class="fa fa-angellist"></i> 获取易货权利 </button>-->

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
                    <th style="width:20%">操作类型</th>
                    <th style="width:20%">激活额度</th>
                    <th style="width:20%">激活支付</th>
                    <th style="width:20%">支付方式</th>
                    <th style="width:20%">激活时间</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr style="background: #eee">
                    <td><?php  echo $row['nickname'];?>/<?php  echo $row['realname'];?></td>
                    <td>
                      <?php  if($row['type'] == '1') { ?>
                            易货额度激活
                        <?php  } ?>
                    </td>
                    <td><?php  echo $row['activacurrency'];?></td>
                    <td><b><?php  echo $row['activapay'];?></b></td>
                    <td> 
                        <?php  if($row['paytype'] == 1) { ?>
                            微信
                        <?php  } else if($row['paytype'] == 2) { ?>
                            余额
                        <?php  } else if($row['paytype'] == 3) { ?>
                            线下
                        <?php  } else if($row['paytype'] == 4) { ?>
                            易货码
                        <?php  } ?> 
                    </td> 
                    <td><?php  echo date('Y-m-d H:i',$row['activatime'])?></td>
                </tr>
            <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
</div>

<div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
    <div class="modal-dialog" style="width: 920px;">
        <div class="modal-content">
            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>激活易货码数量</h3></div>
            <div class="modal-body">
                <div class="form-group" style="float: left;width: 100%;">
                    <label class="col-sm-2 col-md-2 control-label" style="margin-top:8px;">激活易货码数量</label>
                    <div class="col-sm-9 col-md-9">
                        <input type="number" name="activacurrency" class="form-control" value="">
                        <span class="control-label" style="color: #999;font-size:12px;">
                            现金激活易货码，需要支付的现金按照易货码数量的5%收取，每次最少激活易货码数量不低于1000
                        </span>
                    </div>
                </div>

                <div class="form-group" style="float: left;width: 100%;">
                    <label class="col-sm-2 col-md-2 control-label" style="margin-top:6px;">支付方式</label>
                    <div class="col-sm-9 col-md-9">
                        <label for="zaixian">
                            <input type="radio" checked name="paytype" id="zaixian" value="1">在线支付
                        </label>
                        <label for="xianxia">
                            <input type="radio" name="paytype" id="xianxia" value="2">线下支付
                        </label>
                    </div>
                </div>

                <div class="form-group" style="float: left;width: 100%;">
                    <label class="col-sm-2 col-md-2 control-label" style="margin-top:6px;">需要支付</label>
                    <div class="col-sm-9 col-md-9">
                        <label class="havecurrency"></label>
                    </div>
                </div>

                <div class="online">
                    <div class="form-group" style="float: left;width: 100%;">
                        <label class="col-sm-2 col-md-2 control-label" style="margin-top:4px;">支付渠道</label>
                        <div class="col-sm-9 col-md-9">
                            <label for="zhifubao" class="control-label">
                                <input type="radio" name="paychannel" checked id="zhifubao" value="1">支付宝
                            </label>
                            <label for="weixin" class="control-label">
                                <input type="radio" name="paychannel" id="weixin" value="2">微信
                            </label>
                        </div>
                    </div>

                </div>

                <div class="downline" style="display: none;">
                    <div class="form-group" style="float: left;width: 100%;">
                        <label class="col-sm-2 col-md-2 control-label" style="margin-top:4px;">支付渠道</label>
                        <div class="col-sm-9 col-md-9">
                            <select name="selpaymodel" class="form-control">
                                <option value="1">快线pos机支付</option>
                                <option value="2">银行转账</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="float: left;width: 100%;">
                        <label class="col-sm-2 col-md-2 control-label" style="margin-top:8px;">付款人</label>
                        <div class="col-sm-9 col-md-9">
                            <input type="text" name="payname" class="form-control" value="">
                        </div>
                    </div>

                    <div class="form-group" style="float: left;width: 100%;">
                        <label class="col-sm-2 col-md-2 control-label" style="margin-top:8px;">付款时间</label>
                        <div class="col-sm-9 col-md-9">
                            <?php echo sz_tpl_form_field_date('paytime', !empty($item['paytime']) ? date('Y-m-d H:i',$item['paytime']) : date('Y-m-d H:i'), 1)?>
                        </div>
                    </div>

                    <div class="form-group" style="float: left;width: 100%;">
                        <label class="col-sm-2 col-md-2 control-label" style="margin-top:8px;">流水单号</label>
                        <div class="col-sm-9 col-md-9">
                            <input type="text" name="serialNumber" class="form-control" value="">
                        </div>
                    </div>

                </div>
                    <!--<div class="form-group col-sm-12">-->
                        <!---->
                        <!--&lt;!&ndash;<input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="请输入兑换点名称或联系电话">&ndash;&gt;-->
                        <!--&lt;!&ndash;<span class="input-group-btn"><button type="button" class="btn btn-default" onclick="search_stores();">搜索</button></span>&ndash;&gt;-->
                    <!--</div>-->
                <div id="module-menus" style="padding-top:5px;"></div>
            </div>


            <div class="modal-footer">
                <a class="btn btn-default direct">直接激活</a>
                <a href="#" class="btn btn-default " data-dismiss="modal" aria-hidden="true">确定</a>
                <a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a>
            </div>
        </div>

    </div>
</div>
<script>

    $('[name="paytype"]').click(function () {
        var who=$(this).val();
        if (who==1){
            $('.online').show();
            $('.downline').hide();
        }else{
            $('.downline').show();
            $('.online').hide();
        }
    });

    $('[name="activacurrency"]').blur(function () {
        var currency=$(this).val();
        if (currency>=1000 && currency!= ''){        
            $('.havecurrency').html(Math.floor(currency * <?php  echo $ratio;?>)+' 元 ');
        }else{
            alert('每次最少激活易货码数量不低于1000');
            $(this).val(null);
        }
    });

//    $('[name="uid"]').change(function () {
//        var uid=$(this).val();
//        $.post(
//            '<?php  echo $this->createPluginWebUrl("dealmerch/dealmerch_activation",array("op"=>"getInfo"))?>',
//            {uid:uid},
//            function (data) {
//                if(data.status == 1){
//                    $('.freeze_credit3').html(data.result.freeze_credit3);
//                    $('.credit3').html(data.result.credit3);
//                }else{
//                    alert(data.result);
//                }
//            },'json');
//    });
    $('.direct').click(function () {
        var currency=$('[name="activacurrency"]').val();
        if (currency<1000){
            return false;
        }
        $.post(
            '<?php  echo $this->createPluginWebUrl("suppliermenu/dealmerch_activation",array("op"=>"direct"))?>',
            {freeze_currency:currency},
            function (data) {
                $('#modal-module-menus').modal('hide');
                if (data.status == 1){
                    alert(data.result);
                }else{
                    alert(data.result);
                }
            },'json');
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>