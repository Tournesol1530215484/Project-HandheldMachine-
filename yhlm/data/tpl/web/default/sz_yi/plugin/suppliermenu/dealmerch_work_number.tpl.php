<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealtabs', TEMPLATE_INCLUDEPATH)) : (include template('dealtabs', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <div class="panel-heading">
        <?php  if($op == 'display') { ?>
            开户工号管理

        <?php  } else { ?>
            开户工号

        <?php  } ?>
    </div>
    <div class="panel-body">
<?php  if(false) { ?>
<form method='post' class='form-horizontal' style="display:block;">
    <input type="hidden" name="id" value="<?php  echo $addrInfo['id'];?>">
    <input type="hidden" name="ac" value="submit">
    <input type="hidden" name="do" value="plugin">              
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" /> 
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="suppliermenu" />           
    <input type="hidden" name="method" value="dealmerch_work_number" />
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
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">名称</label>   
                    <div class="col-sm-9 col-xs-12">    
                           <input type="text" name="name" class="form-control" value="<?php  echo $addrInfo['name'];?>" />
                    </div>  
                </div>              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>  

                
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">登陆密码</label>   
                    <div class="col-sm-9 col-xs-12">    
                           <input type="text" name="password" class="form-control" value="" />
                    </div>  
                </div>              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>

            <div class="form-group">                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">联系电话</label>   
                    <div class="col-sm-9 col-xs-12">    
                                <input type="text" name="mobile" class="form-control" value="<?php  echo $addrInfo['mobile'];?>"/>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>

            <?php  if(false) { ?>
            <div class="form-group">                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">身份证号码</label>  
                    <div class="col-sm-9 col-xs-12">    
                           <input type="text" name="idcard" class="form-control" value="<?php  echo $addrInfo['idcard'];?>" />
                    </div>              
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>

            <div class="form-group">                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">证件类型</label>   
                    <div class="col-sm-9 col-xs-12">    
                        <select name="card_type" class="form-control">
                                <option value="1">身份证</option>
                                <option value="2">军官证</option>
                                <option value="3">护照</option>
                                <option value="4">港澳居民来往内地通行证</option>
                                <option value="5">台湾居民来往大陆通行证</option>
                        </select>
                    </div>              
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>

            <div class="form-group">                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">证件正面</label>   
                    <div class="col-sm-9 col-xs-12">    
                           <?php  echo tpl_form_field_image('front');?>
                    </div>              
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>

            <div class="form-group">                
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">证件反面</label>   
                    <div class="col-sm-9 col-xs-12">    
                           <?php  echo tpl_form_field_image('reverse');?>
                    </div>              
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <?php  } ?>

            <div class="form-group"> 
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                           <button type="submit" value="提交" class="btn btn-primary col-lg-1"  id="onlyone">提交</button>
                           <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    </div>
            </div>
         </div>
    </div>   
</form>
<?php  } else if($op == 'display') { ?>

        <table >
            <tr style="; margin-left: 15px;">
                <td style="display: block ; margin-bottom: 15px;">所属易货渠道 : <span><?php  echo $agmember['province'];?><?php  echo $agmember['city'];?><?php  echo $agmember['district'];?></span></td>
                <td style="display: block; margin-bottom: 15px;">所属易货运营商/经纪人 : <span><?php  if(empty($agent['name'])) { ?>无<?php  } else { ?><?php  echo $agent['name'];?><?php  } ?></span></td>
                <td style="display: block; margin-bottom: 15px;">运营商/经纪人工号 : <span><?php  echo $info['name'];?>/<?php  echo $info['worknumber'];?></span></td>
                <td style="display: block; margin-bottom: 15px;">运营商/经纪人电话 : <span><?php  echo $info['name'];?>/<?php  echo $info['mobile'];?></span></td>
                <td style="display: block; margin-bottom: 15px;">易货开拓工号 : <span><?php  echo $info['worknumber'];?></span></td>
                <td style="display: block; margin-bottom: 15px;">开拓日期 : <span><?php  echo date('Y-m-d H:i:s',$info['ctime'])?></span></td>
                <td style="display: block; margin-bottom: 15px;">易货服务工号 : <span></span></td>
                <td style="display: block; margin-bottom: 15px;">服务开始时间 : <span></span></td>
            </tr>
        </table>
    
<script>
    $('[name="uid"]').change(function () {
        var uid=$(this).val();
        $.post(
            '<?php  echo $this->createPluginWebUrl("dealmerch/dealmerch_work_number",array("op"=>"getinfo"))?>',
            {uid:uid},
            function (data) {
                $('tr td:nth-child(1) span').html(data.result.city);
                $('tr td:nth-child(2) span').html(data.result.operat);
                $('tr td:nth-child(4) span').html(data.result.operatmobile);

            },'json');
    });
</script>
<?php  } ?>
</div>
</div>

</div>
</div>          
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>