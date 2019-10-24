<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/finance/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/finance/tabs', TEMPLATE_INCLUDEPATH));?>

<?php  if($operation=='credit1') { ?>

<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
<div class="panel panel-default">
    
    <div class="panel-heading">
        积分充值
    </div>
    <div class="panel-body">
           <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝</label>
                <div class="col-sm-9 col-xs-12">
                    <img src='<?php  echo $profile['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                         <?php  echo $profile['nickname'];?>
                </div>
            </div>
           <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">OPENID</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $profile['openid'];?></div>
                </div>
            </div>
        
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员信息</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">姓名: <?php  echo $profile['realname'];?> / 手机号: <?php  echo $profile['mobile'];?></div>
            </div>
        </div>
        
         <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">当前积分</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static"><?php  echo $profile['credit1'];?></div>
            </div>
        </div>
           <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">充值积分</label>
            <div class="col-sm-9 col-xs-12">
                 <input type="text" name="num" class="form-control" value="" />
            </div>
           </div>
        
           <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
            <div class="col-sm-9 col-xs-12">
                    <input type="hidden" name="openid" value="<?php  echo $_GPC['openid'];?>" />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <input name="submit" type="submit" value="充 值" class="btn btn-primary span2" onclick="return confirm('确认充值？');return false;" >
            </div>
           </div>
  
        </div>
    </div>
 
</form>
</div>
<?php  } else if($operation=='credit2') { ?>

<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
<div class="panel panel-default">
    
    <div class="panel-heading">
        余额充值
    </div>
    <div class="panel-body">
         <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝</label>
                <div class="col-sm-9 col-xs-12">
                    <img src='<?php  echo $profile['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                         <?php  echo $profile['nickname'];?>
                </div>
            </div>
        <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">OPENID</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $profile['openid'];?></div>
                </div>
        </div>
        
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员信息</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">姓名: <?php  echo $profile['realname'];?> / 手机号: <?php  echo $profile['mobile'];?></div>
            </div>
        </div>
         <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">当前余额</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static"><?php  echo $profile['credit2'];?></div>
            </div>
        </div>
           <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">充值金额</label>
            <div class="col-sm-9 col-xs-12">
                 <input type="text" name="num" class="form-control" value="" />
            </div>
           </div>
        
           <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
            <div class="col-sm-9 col-xs-12">
                    <input type="hidden" name="openid" value="<?php  echo $_GPC['openid'];?>" />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <input name="submit" type="submit" value="充 值" class="btn btn-primary span2" onclick="return confirm('确认充值？');return false;">
            </div>
           </div>
  
        </div>
    </div>
 
</form>
</div>
<?php  } else if($operation=='credit3') { ?>

<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
    <div class="panel panel-default">

        <div class="panel-heading">
            易货码充值
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝</label>
                <div class="col-sm-9 col-xs-12">
                    <img src='<?php  echo $profile['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                    <?php  echo $profile['nickname'];?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">OPENID</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $profile['openid'];?></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员信息</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        姓名: <?php  echo $profile['realname'];?> / 手机号: <?php  echo $profile['mobile'];?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">当前易货码</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $profile['credit3'];?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">充值易货码</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text" name="num" class="form-control" value="" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <input type="hidden" name="openid" value="<?php  echo $_GPC['openid'];?>" />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <input name="submit" type="submit" value="充 值" class="btn btn-primary span2" onclick="return confirm('确认充值？');return false;" >
                </div>
            </div>

        </div>
    </div>

</form>
</div>

<?php  } else if($operation=='currency_credit3') { ?>

<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
    <div class="panel panel-default">

        <div class="panel-heading">
            易货额度充值
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝</label>
                <div class="col-sm-9 col-xs-12">
                    <img src='<?php  echo $profile['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                    <?php  echo $profile['nickname'];?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">OPENID</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $profile['openid'];?></div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">会员信息</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        姓名: <?php  echo $profile['realname'];?> / 手机号: <?php  echo $profile['mobile'];?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">当前易货额度</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static"><?php  echo $profile['currency_credit3'];?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">充值易货额度</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text" name="num" class="form-control" value="" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <input type="hidden" name="openid" value="<?php  echo $_GPC['openid'];?>" />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <input name="submit" type="submit" value="充 值" class="btn btn-primary span2" onclick="return confirm('确认充值？');return false;" >
                </div>
            </div>

        </div>
    </div>

</form>
</div>

<?php  } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>