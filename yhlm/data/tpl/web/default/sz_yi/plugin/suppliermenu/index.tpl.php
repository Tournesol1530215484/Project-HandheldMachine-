<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if($bool) { ?>
<script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade.js"></script>
<div class="panel panel-info">
    <div class="panel-heading">商家资料</div>
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="">
        <div class="panel panel-default" style="background-color: transparent; border: none;">
            <div class="border_bg">
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style="color:red">*</span> 公司名称</label>
                            <div class="col-sm-9 col-xs-12">
                                <input type="text" name="storename" class="form-control" value="<?php  echo $datum['storename'];?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style="color:red">*</span>门店地址</label>
                            <div class="col-sm-9 col-xs-12">
                                <div class="form-group">
                                    <select id="sel-provance" name="provance" class="" onchange="selectCity();" class="select">
                                        <option value="<?php  echo $datum['provance'];?>" selected="true">所在省份</option>
                                    </select>
                                    <select id="sel-city" name="city" class="" onchange="selectcounty()" class="select">
                                        <option value="<?php  echo $datum['city'];?>" selected="true">所在城市</option>
                                    </select>
                                    <select id="sel-area" name="area" class="" class="select">
                                        <option value="<?php  echo $datum['area'];?>" selected="true">所在地区</option>
                                    </select>
                                    <div  style=' display:flex;margin-top:10px;'>
                                        <span style="margin-top:5px;">详细地址:</span><input type="text" name="street" class="form-control" value="<?php  echo $datum['street'];?>"  style=' width:60%'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style="color:red">*</span>门店电话</label>
                            <div class="col-sm-9 col-xs-12">
                                <input type="text" name="tel" class="form-control" value="<?php  echo $datum['tel'];?>">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style="color:red">*</span>门店Logo</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php  echo tpl_form_field_image('logo', $datum['logo'])?>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label">门店简介</label>
                            <div class="col-sm-9 col-xs-12">
                                <textarea name="Storeprofile" id="" cols="30" class="form-control" rows="3"><?php  echo $datum['description'];?></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style="color:red">*</span>门店招牌</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php  echo tpl_form_field_image('signboard', $datum['signboard'])?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1">
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
                            <input type="button" name="back" onclick="history.back()" style="margin-left:10px;" value="返回列表" class="btn btn-default">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    cascdeInit('<?php  echo $datum["provance"];?>', '<?php  echo $datum["city"];?>', '<?php  echo $datum["area"];?>');
</script>
<?php  } ?>
<div class="panel panel-info">
    <div class="panel-heading">您的资料</div>
    <div class="panel-body">
        <form action="./index.php" method="post" class="form-horizontal" role="form">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="suppliermenu" />
            <input type="hidden" name="method" value="index" />
            <input type="hidden" name="op" value="detail" />
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">真实姓名</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="data[realname]" class="form-control" value="<?php  echo $supplierinfo['realname'];?>" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">手机号码</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $supplierinfo['mobile'];?>" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">金额</label>
                    <div class="col-sm-9 col-xs-12">
                        <span class='help-block'>累计金额：<span style='color:red'><?php  if(!empty($totalmoney)) { ?><?php  echo $totalmoney;?><?php  } else { ?>0<?php  } ?>元</span> 已结算金额：<span style='color:red'><?php  if(!empty($totalmoneyok)) { ?><?php  echo $totalmoneyok;?><?php  } else { ?>0<?php  } ?>元</span></span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">银行卡号</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="data[banknumber]" class="form-control" value="<?php  echo $supplierinfo['banknumber'];?>" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">开户名</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="data[accountname]" class="form-control" value="<?php  echo $supplierinfo['accountname'];?>" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">身份证信息</label>
                    <div class="col-sm-9 col-xs-12">
                        <p>身份证正面：<img src="<?php  echo $supplierinfo['idimgs']['idimg1'];?>" alt="" width="150"> </p>
                        <br>
                        <p>身份证反面：<img src="<?php  echo $supplierinfo['idimgs']['idimg2'];?>" alt="" width="150"></p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">开户银行</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="data[accountbank]" class="form-control" value="<?php  echo $supplierinfo['accountbank'];?>" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">QQ号</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="data[qq]" class="form-control" value="<?php  echo $supplierinfo['qq'];?>" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    QQ用于客服聊天 (注: 若要开启此功能，QQ需要开启'允许临时聊天'功能)
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">所在区域</label>
                    <div class="col-sm-9 col-xs-12">
                    <?php  echo tpl_form_field_district('birth', array('province' => $supplierinfo['provance'],'city' => $supplierinfo['city'],'district' => $supplierinfo['area']));?>
                        <?php  if(0) { ?>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <select class="form-control tpl-province" id="s-provance"  onchange="selCity();" name="birth[provance]">
                                <option value="" selected="true">所在省份</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <select class="form-control tpl-city" id="s-city" onchange="selcounty()" name="birth[city]">
                                <option value="" selected="true">所在城市</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <select class="form-control tpl-district" id="s-area" onchange="" name="birth[district]">
                                <option value="" selected="true">所在地区</option>
                            </select>
                        </div>
                        <?php  } ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    区域分佣 按这个地址发放
                </div>
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                </div>
            </div>


        </form>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">直接连接 直接进入的URL</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form">
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">直接URL</label>
                <div class="col-sm-8 col-lg-9">
                     <input type="text" class="form-control" readonly="readonly" name="url_show" value="<?php  echo $url;?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">二维码</label>
                <div class="col-sm-8 col-lg-9" id="qrcode-block">


                </div>
            </div>

        </form>
    </div>
</div>

<!-- <script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade_c.js"></script> -->
<script type="text/javascript">
$(function() {
    // cascdeInit("<?php  echo $supplierinfo['provance'];?>", "<?php  echo $supplierinfo['city'];?>", "<?php  echo $supplierinfo['area'];?>");
    require(['jquery.qrcode'], function(){
        $('#qrcode-block').html('').qrcode({
            render: 'canvas',
            width: 150,
            height: 150,
            text: '<?php  echo $url;?>'
        });
    })
})
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
