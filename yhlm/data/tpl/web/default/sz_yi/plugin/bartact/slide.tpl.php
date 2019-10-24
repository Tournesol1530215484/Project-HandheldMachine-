<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('advtabs', TEMPLATE_INCLUDEPATH)) : (include template('advtabs', TEMPLATE_INCLUDEPATH));?>

<?php  if($operation == 'display') { ?>

<form action="" method="post">
    <input type="hidden" name="op" value="sort">
<div class="panel panel-default">            
    <div class="panel-body table-responsive">
        <table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style='width:90px'>显示顺序</th>					
                    <th>标题</th>
                    <th>略缩图</th>
                    <th>位置</th>
                    <th>链接</th>
                    <th>状态</th>
                    <th >操作</th>
                </tr>
            </thead>
            <tbody>  
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td><?php  echo $row['id'];?></td>
                    <td> 
                        <?php if(cv('shop.adv.edit')) { ?>
                           <input type="text" class="form-control" name="displayorder[<?php  echo $row['id'];?>]" value="<?php  echo $row['displayorder'];?>">
                        <?php  } else { ?>
                           <?php  echo $row['displayorder'];?>
                        <?php  } ?>       
                    </td>    
                    
                    <td><?php  echo $row['advname'];?></td>                     
                    <td><img width="120px;" height="auto" src="<?php  echo tomedia($row['thumb'])?>"></td>   
                    <td>
                        <?php  if($row['place']== 0) { ?>
                            首页
                        <?php  } else { ?>
                            底部
                        <?php  } ?>       
                    </td>   
                    <td><?php  echo $row['link'];?></td>  
                       <td>
                                    <?php  if($row['status']==1) { ?>
                                    <span class='label label-success'>显示</span>
                                    <?php  } else { ?>
                                    <span class='label label-danger'>隐藏</span>
                                    <?php  } ?>
                                </td>
                    <td style="text-align:left;">
                        <?php if(cv('shop.adv.view|shop.adv.edit')) { ?>                        <a href="<?php  echo $this->createPluginWebUrl('bartact/slide', array('op' => 'post', 'id' => $row['id']))?>" class="btn btn-default btn-sm"  title="<?php if(cv('shop.adv.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>" style="background-color: #ffb034; color:#fff;"><i class="fa fa-edit"></i></a><?php  } ?>
                        <?php if(cv('shop.adv.delete')) { ?><a href="<?php  echo $this->createPluginWebUrl('bartact/slide', array('op' => 'delete', 'id' => $row['id']))?>"class="btn btn-default btn-sm" onclick="return confirm('确认删除此幻灯片?')" title="删除" style="background-color: #000; color:#fff;"><i class="fa fa-times"></i></a><?php  } ?>
                    </td>
                </tr>
                <?php  } } ?> 
                <tr>
                    <td colspan='6'>
                        <?php if(cv('shop.adv.add')) { ?>
                          <a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('bartact/slide',array('op'=>'post'))?>"><i class='fa fa-plus'></i> 添加幻灯片</a>
                          <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <?php  } ?>
                        <?php if(cv('shop.adv.edit')) { ?>
                          <input name="submit" type="submit" class="btn btn-default" value="提交排序">
                        <?php  } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</form>
</div>
<script>
    require(['bootstrap'], function ($) {
        $('.btn').hover(function () {
            $(this).tooltip('show');
        }, function () {
            $(this).tooltip('hide');
        });
    });
</script>

<?php  } else if($operation == 'post') { ?>

<div class="main rightlist">
    <form <?php if( ce('shop.adv' ,$item) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
        <div class="panel panel-default">
            <div class="panel-heading">
                幻灯片设置       
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                    <div class="col-sm-9 col-xs-12">
                                <input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
                                <span class='help-block'>数字越大，排名越靠前</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>幻灯片标题</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" id='advname' name="advname" class="form-control" value="<?php  echo $item['advname'];?>" /> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">幻灯片图片</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  echo tpl_form_field_image('thumb', $item['thumb'])?>
                        <span class='help-block'>建议尺寸:640 * 350 , 请将所有幻灯片图片尺寸保持一致</span>
                    </div>
                </div>
               
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">幻灯片连接</label>
                    <div class="col-sm-9 col-xs-12">
                        <input type="text" name="link" class="form-control" value="<?php  echo $item['link'];?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">显示位置</label>
                    <div class="col-sm-9 col-xs-12">      
                        <label class='radio-inline'>
                            <input type='radio' name='place' value=0' <?php  if($item['place']==0) { ?>checked<?php  } ?> /> 首页
                        </label>                 
                        <label class='radio-inline'>                                 
                            <input type='radio' name='place' value=1' <?php  if($item['place']==1) { ?>checked<?php  } ?> /> 底部
                        </label>                
                    </div>
                </div> 


                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
                    <div class="col-sm-9 col-xs-12">
                      
                        <label class='radio-inline'>
                            <input type='radio' name='status' value=1' <?php  if($item['status']==1) { ?>checked<?php  } ?> /> 是
                        </label>
                        <label class='radio-inline'> 
                            <input type='radio' name='status' value=0' <?php  if($item['status']==0) { ?>checked<?php  } ?> /> 否
                        </label>
                    </div>
                </div>
                
                    <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.adv.add|shop.adv.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
            </div>
                
                
            </div>
        </div>
         
    </form>
</div>
</div>
<script language='javascript'>
    function formcheck() {
        if ($("#advname").isEmpty()) {
            Tip.focus("advname", "请填写幻灯片名称!");
            return false;
        }
        return true;
    }
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>