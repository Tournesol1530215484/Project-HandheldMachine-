<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>                        
<div class="panel panel-info">
        <?php  if($op == 'display') { ?>
        <div class="panel-heading">易货菜单管理</div>           
        <div class="panel-body">
        <a class="btn btn-primary" href="<?php  echo $this->createPluginWebUrl('dealmerch/member',array('op'=>'post'))?>">添加会员等级</a>
            <table class="table table-hover" >           
                <tr style="; margin-left: 15px;">   
                    <th>会员头衔</th>
                    <th>金额</th>
                    <th>免换货服务费</th>
                    <th>操作</th>
                </tr>
                <?php  if(is_array($list)) { foreach($list as $k => $v) { ?>   
                    <tr>
                        <td><?php  echo $v['title'];?></td>
                        <td><?php  echo $v['cash']/1000?>千</td>
                        <td><?php  echo $v['currency']/10000?>万</td>
                        <td><a href="<?php  echo $this->createPluginWebUrl('dealmerch/member',array('op'=>'edit','id'=>$v['id']))?>">编辑</a></td>
                    </tr>
                <?php  } } ?>                 
            </table>
            <?php  echo $pager;?>
        </div>
    <?php  } else if($_GPC['op'] == 'log') { ?>
    <div class="panel-heading">购买会员日志</div>           
        <div class="panel-body">
            <table class="table table-hover" >           
                <tr style="; margin-left: 15px;">   
                    <th>头像/用户名</th>
                    <th>会员等级</th>
                    <th>支付方式</th>
                    <th>订单号</th>
                    <th>购买时间</th>
                </tr>           
                <?php  if(is_array($list)) { foreach($list as $k => $v) { ?>              
                    <tr>
                        <td><img src="<?php  echo $v['avatar'];?>" width="25px;" style="margin-right:3px;"><?php  echo $v['nickname'];?></td>
                        <td><?php  echo $v['title'];?></td>
                        <td>
                            <?php  if($v['paytype'] == 1) { ?>
                            余额
                            <?php  } else { ?>
                            微信
                            <?php  } ?>
                        </td>           
                        <td><?php  echo $v['ordersn'];?></td>
                        <td><?php  echo date('Y-m-d H:i:s',$v['ctime'])?></td>
                    </tr>
                <?php  } } ?>                 
            </table>                 
            <?php  echo $pager;?>
        </div>
    <?php  } else { ?>
        <div class="panel-heading">易货菜单编辑</div>
            <div class="panel-body">                        
                <form action="" method="post">                       
                    <input type="hidden" name="a" value="entry">
                    <input type="hidden" name="c" value="site">
                    <input type="hidden" name="op" value="post">
                    <input type="hidden" name="m" value="sz_yi">
                    <input type="hidden" name="do" value="plugin">
                    <input type="hidden" name="p" value="dealmerch">
                    <input type="hidden" name="method" value="member">
                    <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>">

                <div class="form-group" style="margin: 10px 0px">
                    <label class="col-sm-2 col-md-2 control-label"></label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">头衔名称</div>
                            <input type="text" name="title" id="" class="form-control" value="<?php  echo $item['title'];?>" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">金额</div>
                            <input type="text" name="cash" id="" class="form-control" value="<?php  echo $item['cash'];?>" />
                            <div class="input-group-addon">(千元)</div>
                        </div>   
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <div class="input-group-addon">赠送易货额度</div>
                            <input type="text" name="currency" id="" class="form-control" value="<?php  echo $item['currency'];?>" />
                            <div class="input-group-addon">(万元)</div>
                        </div>          
                    </div>
                    <br clear="both">
                </div>          

                <!-- <div class="form-group" style="margin: 10px 0px">
                    <label class="col-sm-3 col-md-2 control-label">金额</label>
                    <div class="col-sm-9 col-md-10"> 
                        <input type="text" name="cash" id="" class="form-control" value="<?php  echo $item['cash'];?>" />
                    </div>
                    <br clear="both">   
                </div> -->

                 <!-- <div class="form-group" style="margin: 10px 0px">       
                    <label class="col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-md-10"> 
                        <input type="text" name="currency" id="" class="form-control" value="<?php  echo $item['currency'];?>" />
                    </div>
                    <br clear="both">
                </div> -->



                <div class="form-group" style="margin: 10px 0px">
                    <label class="col-sm-3 col-md-2 control-label">是否启用</label>
                    <div class="col-sm-9 col-md-10">
                        <label for="ye">
                            <input id="ye" <?php  if($item['status'] == 1) { ?>checked<?php  } ?> type="radio" name="status" value="1">是
                        </label>
                        <label for="no">
                            <input id="no" <?php  if($item['status'] == 0) { ?>checked<?php  } ?> type="radio" name="status" value="0">否
                        </label>
                    </div>
                    <br clear="both">
                </div>
 

                <button type="submit" class="col-md-offset-3 btn btn-primary">提交表单</button>
                </form>
            </div>
    <?php  } ?>
</div>
 
</div> 
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>