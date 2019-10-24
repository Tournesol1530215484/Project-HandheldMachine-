<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">易货码激活管理</div>
    <div class="panel-body">
        <form action="" class="row">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="sz_yi">
            <input type="hidden" name="p" value="dealmerch">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="method" value="dealmerch_activation">
            <div class="form-group">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class='input-group'> 
                        <div class='input-group-addon'>商家</div>
                        <div class='input-group'>                                        
                            <input type="text" name="saler" disabled="true" maxlength="30" value="<?php  if(!empty($selmerch)) { ?><?php  echo $selmerch['username'];?>/<?php  echo $selmerch['realname'];?>/<?php  echo $selmerch['mobile'];?><?php  } ?>" id="saler" class="form-control" readonly />             
                            <div class='input-group-btn'>                           
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus1').modal();">选择商家</button>
                                <input type="hidden" name="supplier_uid" id="supplier" value="<?php  if(!empty($selmerch)) { ?><?php  echo $selmerch['uid'];?><?php  } ?>">
                                <input type="hidden" name="openid" id="openid" value="<?php  echo $selmerch['openid'];?>">
                            </div>                                                                                        
                        </div>                                                                

                        <!-- modal start-->
                            <div id="modal-module-menus1"  class="modal fade" tabindex="-1">
                                <div class="modal-dialog" style='width: 920px;'>
                                    <div class="modal-content">
                                        <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择商家</h3></div>
                                        <div class="modal-body" >
                                            <div class="row">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="请输入用户名/姓名/手机号" />
                                                    <span class='input-group-btn'><button type="button" class="btn btn-default mysearch">搜索</button></span>
                                                </div>                                     
                                            </div>                                             
                                            <div id="module-menus" style="padding-top:5px;"></div>
                                        </div>               
                                        <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                    </div>
                                </div>       
                            </div>                  
                            <script language='javascript'>
                                $('.mysearch').click(function(){
                                    if( $.trim($('#search-kwd').val())==''){
                                             Tip.focus('#search-kwd','请输入关键词');
                                             return;
                                    }
                                    $("#module-menus").html("正在搜索....")
                                    $.get('<?php  echo $this->createWebUrl('member/selmerch')?>', {
                                        keyword: $.trim($('#search-kwd').val()),
                                        type:'goods'                
                                    }, function(dat){
                                        $('#module-menus').html(dat);
                                    });                             
                                });                     

                                function select_member1(o) {         
                                    $("#supplier").val(o.uid);                                                             
                                    $("#openid").val(o.openid);                                                             
                                    $("#saler").val( o.username+ "/" + o.realname + "/" + o.mobile );
                                    $(".close").click();                                                 
                                }
                               </script>                        

                        <!-- modal end -->
                    </div>
                </div>   

                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="margin-top:7.5px;">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">激活时间</label>
                    <div class="col-sm-3">
                            <label class='radio-inline'><input type='radio' value='0' name='searchtime' <?php  if($_GPC['searchtime']=='0') { ?>checked<?php  } ?>>不搜索</label>
                            <label class='radio-inline'><input type='radio' value='1' name='searchtime' <?php  if($_GPC['searchtime']=='1') { ?>checked<?php  } ?>>搜索</label>
                    </div>
                    <div class="col-sm-7 col-lg-7 col-xs-12" style="margin-top: -7.5px;">
                        <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d  H:i', $endtime)),true);?>
                    </div>          
                </div>              

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <button class="btn btn-default"><i class="fa fa-search"></i> 查询</button>
                    <span class="btn btn-default" onclick="popwin = $('#modal-module-menus').modal();" >
                        <i class="fa fa-thumbs-up"></i> 激活易货码 
                    </span>
                    <span class="btn btn-default" onclick="popwin = $('#modal-module-menus').modal();" >
                        <i class="fa fa-angellist"></i> 获取易货权利 
                    </span>
                </div>          
                <br clear="both"> 
            </div>

            <div class="form-group" style="margin-top: 7.5px;">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-sm-3 col-md-3 control-label">可用易货码 <span class="credit3" style="color: #f00;"><?php  echo $info['credit3'];?></span></label>
                    <label class="col-sm-3 col-md-3 control-label">未激活易货码 <span class="freeze_credit3" style="color: #f00;"><?php  echo $info['freeze_credit3'];?></span></label>
                    <label class="col-sm-3 col-md-3 control-label">可用易货额度 <span style="color: #f00;"><?php  echo $info['currency_credit3'];?></span>
                    </label>
                </div>
                <br clear="both"> 
            </div>

        </form>
    </div>          
</div>
            
<div class="panel panel-default">
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">     
                <tr>
                    <th style="width:25%">用户名</th>
                    <th style="width:25%">操作类型</th>
                    <th style="width:25%">激活额度</th>
                    <th style="width:25%">激活支付</th>
                    <th style="width:25%">支付方式</th>
                    <th style="width:25%">支付渠道</th>
                    <th style="width:25%">激活时间</th>
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
                            后台操作     
                        <?php  } ?>                   
                    </td>    
                    <td>                                            
                        <?php  if($row['paytype'] == 1) { ?>
                            微信     
                        <?php  } else { ?>
                            余额         
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
                        <label for="houtai">
                            <input type="radio" name="paytype" id="houtai" value="3">后台操作
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
                            <label for="comson" class="control-label" style="display:none;">
                                <input type="radio" name="paychannel" id="comson" value="3">总部
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
            $('#comson').parent().hide();
            $('#zhifubao').parent().show();
            $('#weixin').parent().show();
            $('.online').show();
            $('.downline').hide();
        }else if(who == 2){
            $('.downline').show();
            $('.online').hide();
        }else{ 
            $('#comson').parent().show();
            $('#zhifubao').parent().hide();
            $('#weixin').parent().hide();
            $('.online').show();
            $('.downline').hide();
        }
    });

    $('[name="activacurrency"]').blur(function () {
        var currency=$(this).val();
        if (currency>=1000 && currency!= ''){
            $('.havecurrency').html(Math.floor(currency*0.05)+' 元 ');
        }else{
            alert('每次最少激活易货码数量不低于1000');
            $(this).val(null);
        }
    });
    /*$('[name="supplier_uid"]').change(function () {
        var openid=$(this).val();
        $.post(
            '<?php  echo $this->createPluginWebUrl("dealmerch/dealmerch_activation",array("op"=>"getInfo"))?>',
            {openid:openid},
            function (data) {
                if(data.status == 1){
                    $('.freeze_credit3').html(data.result.freeze_credit3);
                    $('.credit3').html(data.result.credit3);
                }else{
                    alert(data.result);
                }
            },'json');
    });*/
    $('.direct').click(function () {
        var currency=$('[name="activacurrency"]').val();
        if (currency<1000){
            return false;
        }
        var uid=$('[name="supplier_uid"]').val();
        $.post(
            '<?php  echo $this->createPluginWebUrl("dealmerch/dealmerch_activation",array("op"=>"direct"))?>',
            {freeze_currency:currency,openid:uid},
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