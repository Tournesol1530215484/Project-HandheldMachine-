<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<style type='text/css'>
.trhead td {  background:#efefef;text-align: center}
.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
.goods_info{position:relative;width:60px;}
.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
.goods_info:hover {z-index:1;position:absolute;width:auto;}
.goods_info:hover img{width:320px; height:320px;}
</style>
<?php  if($operation=='display') { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="supplier" />
            <input type="hidden" name="method" value="supplier_apply" />
            <input type="hidden" name="op" value="display" />
            <div class="form-group">				<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">会员信息</label>
	                <div class="col-sm-8 col-lg-9 col-xs-12">
	                    <input type="text" class="form-control"  name="uid" value="<?php  echo $_GPC['uid'];?>" placeholder='搜索供货商ID'/> 
	                </div>
	            </div>
            	<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">提现单号</label>
	                <div class="col-sm-8 col-lg-9 col-xs-12">
	                    <input type="text" class="form-control"  name="applysn" value="<?php  echo $_GPC['applysn'];?>"/> 
	                </div>
	            </div>
				<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
                    <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
               </div>
            </div>    
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
                <tr>
                    <th style='width:8%;'>供应商ID</th>
                    <th style='width:12%;'>头像/用户名</th>
                    <th style='width:15%;'>提现单号</th>
                    <th style='width:5%;'>开户名</th>
                    <th style='width:10%;'>电话</th>
                    <th style='width:10%;'>开户银行</th>
                    <th style='width:15%;'>银行卡号</th>
                    <th style='width:6%;'>提现金额</th>
                    <th style='width:12%;'>申请时间</th>
                    <th style='width:8%;'>打款</th>
                    <th style='width:10%;'>状态</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <?php  if(!empty($row['uid'])) { ?>
                        <tr>
                            <td><?php  echo $row['uid'];?></td>
                            <td><img src="<?php  echo $row['avatar'];?>" width="30px" height="30px"><?php  echo $row['nickname'];?></td>
                            <td><?php  echo $row['applysn'];?></td>                       
                            <td><?php  echo $row['accountname'];?></td>
                            <td><?php  echo $row['telephone'];?></td>
                            <td><?php  echo $row['accountbank'];?></td>
                            <td><?php  echo $row['banknumber'];?></td>
                            <td><?php  echo $row['apply_money'];?></td>
                            <td><?php  echo date('Y-m-d H:i:s',$row['apply_time']);?></td>
                            <td>
                                <?php  if($row['type'] == 1) { ?>
                                银行卡
                                <?php  } else if($row['type'] == 2) { ?>
                                微信钱包
                                <?php  } else if($row['type'] == 3) { ?>
                                平台余额
                                <?php  } ?>
                            </td>
                            <td  style="overflow:visible;">
                                <div class="form-group">
                                    <div class="col-sm-9 col-xs-9">          
                                    <label class="label label-danger" style="margin-right: 7.5px;"> 
                                        <a onclick="return confirm('您确定要拒绝？');" href="<?php  echo $this->createPluginWebUrl('supplier/supplier_apply/detail',array('id' => $row['id'],'status'=>2));?>" style="color:#fff;">拒绝</a>
                                    </label>
                                             
                                    <label class="label label-success"> 
                                        <a onclick="return confirm('您确定要打款？');" href="<?php  echo $this->createPluginWebUrl('supplier/supplier_apply/detail',array('id' => $row['id']));?>" style="color:#fff;">点击打款</a>
                                    </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php  } ?>
                <?php  } } ?>
            </tbody>
        </table>
    </div>
</div>
<?php  } ?>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>