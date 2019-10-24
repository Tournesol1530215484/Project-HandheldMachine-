<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/sysset/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/sysset/tabs', TEMPLATE_INCLUDEPATH));?>
<div class="main rightlist">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" >
        <input type='hidden' name='setid' value="<?php  echo $set['id'];?>" />
        <input type='hidden' name='op' value="shop" />
        <div class="panel panel-default">
            <div class='panel-body'>  
                <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商城名称</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('sysset.save.shop')) { ?>
	                        <input type="text" name="shop[name]" class="form-control" value="<?php  echo $set['shop']['name'];?>" />
	                        <?php  } else { ?>
	                        <input type="hidden" name="shop[name]" value="<?php  echo $set['shop']['name'];?>"/>
	                        <div class='form-control-static'><?php  echo $set['shop']['name'];?></div>
	                        <?php  } ?>
	                    </div>
	                </div>
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">美恰客服</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('sysset.save.cservice')) { ?>
	                        <input type="text" name="shop[cservice]" class="form-control" value="<?php  echo $set['shop']['cservice'];?>" />
	                        <span class='help-block'>请到 <a href='https://meiqia.com/' target='_blank'>美恰</a> 获取聊天连接地址<br>如:https://eco-api.meiqia.com/dist/standalone.html?eid=9669
	                        <?php  } else { ?>
	                        <input type="hidden" name="shop[cservice]" value="<?php  echo $set['shop']['cservice'];?>"/>
	                        <div class='form-control-static'><?php  echo $set['shop']['cservice'];?></div>
	                        <?php  } ?>
	                    </div>
	                </div>
                </div>
                
                <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商城LOGO</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('sysset.save.shop')) { ?>
	                        <?php  echo tpl_form_field_image('shop[logo]', $set['shop']['logo'])?>
	                        <span class='help-block'>正方型图片</span>
	                        <?php  } else { ?>
	                        <input type="hidden" name="shop[logo]" value="<?php  echo $set['shop']['logo'];?>"/>
	                        <?php  if(!empty($set['shop']['logo'])) { ?>
	                        <a href='<?php  echo tomedia($set['shop']['logo'])?>' target='_blank'>
	                           <img src="<?php  echo tomedia($set['shop']['logo'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
	                        </a>
	                        <?php  } ?>
	                        <?php  } ?>
	                    </div>
	                </div>
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">店招</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('sysset.save.shop')) { ?>
	                        <?php  echo tpl_form_field_image('shop[img]', $set['shop']['img'])?>
	                        <span class='help-block'>商城首页店招，建议尺寸640*450</span>
	                        <?php  } else { ?>
	                        <input type="hidden" name="shop[img]" value="<?php  echo $set['shop']['img'];?>"/>
	                        <?php  if(!empty($set['shop']['img'])) { ?>
	                        <a href='<?php  echo tomedia($set['shop']['img'])?>' target='_blank'>
	                           <img src="<?php  echo tomedia($set['shop']['img'])?>" style='width:200px;border:1px solid #ccc;padding:1px' />
	                        </a>
	                        <?php  } ?>
	                        <?php  } ?>
	                    </div>
	                </div>
                </div>

                <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">商城海报</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('sysset.save.shop')) { ?>
	                        <?php  echo tpl_form_field_image('shop[signimg]', $set['shop']['signimg'])?>
	                        <span class='help-block'>推广海报，建议尺寸640*640</span>
	                        <?php  } else { ?>
	                        <input type="hidden" name="shop[signimg]" value="<?php  echo $set['shop']['signimg'];?>"/>
	                        <?php  if(!empty($set['shop']['signimg'])) { ?>
	                        <a href='<?php  echo tomedia($set['shop']['signimg'])?>' target='_blank'>
	                           <img src="<?php  echo tomedia($set['shop']['signimg'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
	                        </a>
	                        <?php  } ?>
	                        <?php  } ?>
	                    </div>
	                </div>
                
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">全局统计代码</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('sysset.save.shop')) { ?>
							<textarea name="shop[diycode]" class="form-control richtext" cols="70" rows="5"><?php  echo $set['shop']['diycode'];?></textarea>
	                        <?php  } else { ?>
	                        <textarea name="shop[diycode]" class="form-control richtext" cols="70" style="display:none"  rows="5"><?php  echo $set['shop']['diycode'];?></textarea>
	                        <div class='form-control-static'><?php  echo $set['shop']['diycode'];?></div>
	                        <?php  } ?>
	                    </div>
	                </div>
	            </div>

                <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">版权信息</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <input type="text" name="shop[copyright]" class="form-control" value="<?php  echo $set['shop']['copyright'];?>" />
	                        <span class='help-block'>版权所有 © 后面文字字样</span>
	                    </div>
	                </div>
                </div>
                <div class="form-group"></div>
            	<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if(cv('sysset.save.shop')) { ?>
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                     </div>
            	</div>   
            </div>
        </div>     
    </form>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>