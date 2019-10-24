<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .border_bg{ border: 1px #ccc solid;border-radius: 4px; margin-bottom: 10px; background-color: #fff;}
    .border_bg .panel-heading{ background-color: #e8ecef; color: #000; }
</style>
<script type="text/javascript">
    function validate() {
     
        if ($(':checkbox[name="user[upload]"]:checked').val() == '1') {
            if ($.trim($(':text[name="user[access_key]"]').val()) == '') {
            Tip.focus(':text[name="user[access_key]"]', '请输入七牛 ACCESS_KEY !', 'top');
                    return false;
            }
            if ($.trim($(':text[name="user[secret_key]"]').val()) == '') {
            Tip.focus(':text[name="user[secret_key]"]', '请输入七牛 SECRET_KEY !', 'top');
                    return false;
            }
            if ($.trim($(':text[name="user[bucket]"]').val()) == '') {
            Tip.focus(':text[name="user[bucket]"]', '请输入七牛空间名称 !', 'top');
                    return false;
            }
            if ($.trim($(':text[name="user[url]"]').val()) == '') {
            Tip.focus(':text[name="user[url]"]', '请输入七牛空间链接  !', 'top');
                    return false;
            }
        }
        <?php  if($_W['isfounder']) { ?>
            if ($(':radio[name="admin[upload]"]:checked').val() == '1') {
                if ($.trim($(':text[name="admin[access_key]"]').val()) == '') {
                Tip.focus(':text[name="admin[access_key]"]', '请输入七牛 ACCESS_KEY !', 'top');
                        return false;
                }
                if ($.trim($(':text[name="admin[secret_key]"]').val()) == '') {
                Tip.focus(':text[name="admin[secret_key]"]', '请输入七牛 SECRET_KEY !', 'top');
                        return false;
                }
                if ($.trim($(':text[name="admin[bucket]"]').val()) == '') {
                Tip.focus(':text[name="admin[bucket]"]', '请输入七牛空间名称 !', 'top');
                        return false;
                }
                   if ($.trim($(':text[name="admin[url]"]').val()) == '') {
                        Tip.focus(':text[name="admin[url]"]', '请输入七牛空间链接 !', 'top');
                        return false;
                }
            }
        <?php  } ?>
                return true;
    }
    $(function () {
        $(':radio[name="user[upload]"]').click(function () {
            if ($(this).val()=='0') {
                  $('.qiniu-params').hide();
                } else {
                  $('.qiniu-params').show();
             }
        });

        $(':radio[name="admin[upload]"]').click(function () {
           if ($(this).val()=='0') {
                    $('.qiniu-params-admin').hide();
                } else {
 
                    $('.qiniu-params-admin').show();
                }
        });

    });
</script>
<div class="ulleft-nav">
	<ul class="nav nav-tabs">
	    <li class="active"><a href="javascript:;">七牛存储设置</a></li>
	</ul>
</div>
<form method="post" class="form-horizontal form"  onsubmit="return validate();">
    <div class="panel panel-default" style="background-color: transparent; border: none;">
    	<div class="border_bg">
        	<div class="panel-heading">存储设置</div>
	        <div class="panel-body">
	            <div class="form-group">
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">系统存储设置</label>
		                <div class="col-sm-9">
		                    <div class='form-control-static'>
		                        <?php  if($set['admin']['allow']==1) { ?>
		                            <?php  if($set['user']['upload']==1) { ?>七牛<?php  } else { ?>本地<?php  } ?>
		                            <?php  } else { ?>
		                            <?php  if($set['admin']['upload']==1) { ?>七牛<?php  } else { ?>本地<?php  } ?>
		                        <?php  } ?>
		                    </div>
		                </div>
		            </div>					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>
	
	            <?php  if($set['admin']['allow']==1) { ?>
	            <div class="form-group">
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">七牛存储</label>
		                <div class="col-sm-9">
		                    <label class="radio-inline"><input type="radio" name="user[upload]" value="1" <?php  if($set['user']['upload']==1) { ?> checked="checked"<?php  } ?>/>开启</label>
		                     <label class="radio-inline"><input type="radio" name="user[upload]" value="0" <?php  if(empty($set['user']['upload'])) { ?> checked="checked"<?php  } ?>/>关闭</label>
		                    <span class="help-block">开启七牛存储，不使用系统默认的存储方式, <a href="http://www.qiniu.com/" target="_blank">七牛存储</a></span>
		                </div>
	            	</div>	
		            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" <?php  if(empty($set['user']['upload'])) { ?>style="display:none"<?php  } ?> >
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">ACCESS_KEY</label>
		                <div class="col-sm-9">
		                	<input type="text" name="user[access_key]" class="form-control" value="<?php  echo $set['user']['access_key'];?>" autocomplete="off">
		                </div>
		            </div>				</div>
	            <div class="form-group qiniu-params" <?php  if(empty($set['user']['upload'])) { ?>style="display:none"<?php  } ?> >
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">SECRET_KEY</label>
		                <div class="col-sm-9">
		                	<input type="text" name="user[secret_key]" class="form-control" value="<?php  echo $set['user']['secret_key'];?>" autocomplete="off"/>
		                </div>
		            </div>	
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" <?php  if(empty($set['user']['upload'])) { ?>style="display:none"<?php  } ?> >
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">BUCKET(空间名称)</label>
		                <div class="col-sm-9">
		                	<input type="text" name="user[bucket]" class="form-control" value="<?php  echo $set['user']['bucket'];?>" autocomplete="off"/>
		                </div>					</div>
	            </div>	
	            <div class="form-group qiniu-params" <?php  if(empty($set['user']['upload'])) { ?>style="display:none"<?php  } ?> >
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">URL(空间链接)</label>
		                <div class="col-sm-9">
		                	<input type="text" name="user[url]" class="form-control" value="<?php  echo $set['user']['url'];?>" autocomplete="off"/>
		                </div>
		            </div>	
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
		                <div class="col-sm-9"> 
		                    <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
		                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		                </div>
		            </div>
	            </div>	
	            <?php  } ?>	
	        </div>
	    </div>
    </div>

    <?php  if($_W['isfounder']) { ?>
    <div class="panel panel-default" style="background-color: transparent; border: none;">
    	<div class="border_bg">
        	<div class="panel-heading">管理员存储设置</div>
	        <div class="panel-body">
	            <div class="form-group">
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">允许客户自定义七牛路径</label>
		                <div class="col-sm-9">
		                    <label class="radio-inline"><input type="radio" name="admin[allow]" value="0" <?php  if(empty($set['admin']['allow'])) { ?> checked="checked"<?php  } ?>/> 不允许</label>
		                    <label class="radio-inline"><input type="radio" name="admin[allow]" value="1" <?php  if($set['admin']['allow']) { ?> checked="checked"<?php  } ?>/> 允许</label>
		                    <span class="help-block">是否允许客户自定义七牛空间</span>
		                </div>
		            </div>	
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">默认存储设置</label>
		                <div class="col-sm-9">
		                    <label class="radio-inline"><input type="radio" name="admin[upload]" value="0" <?php  if(empty($set['admin']['upload'])) { ?> checked="checked"<?php  } ?>/> 本地存储</label>
		                    <label class="radio-inline"><input type="radio" name="admin[upload]" value="1" <?php  if($set['admin']['upload']) { ?> checked="checked"<?php  } ?>/> 七牛存储</label>
		                    <span class="help-block">默认宝贝图片存储方式</span>
		                </div>
		            </div>
	            </div>	
	            <div class="form-group qiniu-params-admin" <?php  if(empty($set['admin']['upload'])) { ?>style="display:none"<?php  } ?> >
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">ACCESS_KEY</label>
		                <div class="col-sm-9">
		                	<input type="text" name="admin[access_key]" class="form-control" value="<?php  echo $set['admin']['access_key'];?>" autocomplete="off">
		                </div>
		            </div>	
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" <?php  if(empty($set['admin']['upload'])) { ?>style="display:none"<?php  } ?> >
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">SECRET_KEY</label>
		                <div class="col-sm-9">
		                	<input type="text" name="admin[secret_key]" class="form-control" value="<?php  echo $set['admin']['secret_key'];?>" autocomplete="off"/>
		                </div>					</div>
	            </div>	
	            <div class="form-group qiniu-params-admin" <?php  if(empty($set['admin']['upload'])) { ?>style="display:none"<?php  } ?> >
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-3 col-md-3 control-label">BUCKET(空间名称)</label>
		                <div class="col-sm-9">
		                	<input type="text" name="admin[bucket]" class="form-control" value="<?php  echo $set['admin']['bucket'];?>" autocomplete="off"/>
		                </div>
	            	</div>	
	            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" <?php  if(empty($set['admin']['upload'])) { ?>style="display:none"<?php  } ?> >
		                <label class="col-xs-12 col-sm-3 col-md-2 control-label">URL(空间链接)</label>
		                <div class="col-sm-9">
		                	<input type="text" name="admin[url]" class="form-control" value="<?php  echo $set['admin']['url'];?>" autocomplete="off"/>
		                </div>
		            </div>
	            </div> 
	        </div>	
	        <div class="form-group">
	            <label class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
	            <div class="col-sm-9">
	                <input type="submit" name="submit_admin" value="提交" class="btn btn-primary col-lg-1" />
	                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
	            </div>
	        </div>
	    </div>
    </div>
    <?php  } ?></form>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>