<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/order/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/order/tabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade.js"></script>
<style type="text/css">
    .main .form-horizontal .form-group{margin-bottom:0;}
    .main .form-horizontal .modal .form-group{margin-bottom:15px;}
    #modal-confirmsend .control-label{margin-top:0;}
    .ad2 {display: none;}
    .border_bg{ border: 1px #ccc solid;border-radius: 4px; margin-bottom: 10px; background-color: #fff;}
    .border_bg .panel-heading{ background-color: #e8ecef; color: #000; }
</style>
<div class="main">
    <form class="form-horizontal form" action="" method="post">
        <?php  if($item['transid']) { ?><div  class="alert alert-error"><i class="fa fa-lightbulb"></i> 此为微信支付订单，必须要提交发货状态！</div><?php  } ?>
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
        <input type="hidden" name="dispatchid" value="<?php  echo $dispatch['id'];?>" />
        <div class="panel panel-default">
        	<div class="border_bg">
            	<div class="panel-heading" style="font-weight: bold;">订单信息</div>
	            <div class="panel-body table-responsive">
	            	<style type="text/css">
	            		.table_hover th{ text-align: center; color: #fff;height: 30px!important; line-height: 30px!important; }
	            	</style>
	                <table class="table table-hover table_hover" style="text-align: center;">
	                    <thead class="navbar-inner">
	                        <tr style="background-color: #1e95c9;">
	                        	<th style="width:12%;">粉丝</th>
	                            <th style="width:14%;">会员信息</th>
	                            <?php  if($item['transid']) { ?>
	                            <th style="width:12%;">微信交易号</th>
	                            <?php  } ?>
	                            <th style="width:16%;">订单编号</th>
	                            <th style="width:12%;">订单金额</th>
	                            <?php  if(!empty($coupon)) { ?>
	                            <th style="width:12%;">使用优惠券</th>
	                            <?php  } ?>
	                            <th style="width:10%;">配送方式</th>
	                            <th style="width:10%;">付款方式</th>
	                        </tr>
	                    </thead>
	                    <tr><td><img src='<?php  echo $member['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' /><br><p style="margin-top: 10px;"><?php  echo $member['nickname'];?></p></div></td>
	                        <td style="text-align: left; padding-left: 30px;">ID: <?php  echo $member['id'];?><br>姓名: <?php  echo $member['realname'];?><br>手机号: <?php  echo $member['mobile'];?><br>微信号: <?php  echo $member['weixin'];?></td>
	                        <?php  if($item['transid']) { ?>
	                        <td><?php  echo $item['transid'];?></td>
	                        <?php  } ?>
	                        <td><?php  echo $item['ordersn'];?></td>
	                        <td style="    padding-left: 0px;"><table cellspacing="0" cellpadding="0">
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>商品小计：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>￥<?php  echo number_format( $item['goodsprice'] ,2)?></td>
									</tr>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>运费：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>￥<?php  echo number_format( $item['olddispatchprice'],2)?></td>
									</tr>
									<?php  if($item['discountprice']>0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>会员折扣：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>-￥<?php  echo number_format( $item['discountprice'],2)?></td>
									</tr>
									<?php  } ?>
		
									<?php  if($item['deductprice']>0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>积分抵扣：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>-￥<?php  echo number_format( $item['deductprice'],2)?></td>
									</tr>
									<?php  } ?>
									<?php  if($item['deductcredit2']>0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>余额抵扣：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>-￥<?php  echo number_format( $item['deductcredit2'],2)?></td>
									</tr> 
									<?php  } ?>
									<?php  if($item['deductenough']>0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>满额立减：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>-￥<?php  echo number_format( $item['deductenough'],2)?></td>
									</tr>
									<?php  } ?>
									<?php  if($item['couponprice']>0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>优惠券优惠：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'>-￥<?php  echo number_format( $item['couponprice'],2)?></td>
									</tr>
									<?php  } ?>
									<?php  if(intval($item['changeprice'])!=0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>卖家改价：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'><span style="<?php  if(0<$item['changeprice']) { ?>color:green<?php  } else { ?>color:red<?php  } ?>"><?php  if(0<$item['changeprice']) { ?>+<?php  } else { ?>-<?php  } ?>￥<?php  echo number_format(abs($item['changeprice']),2)?></span></td>
									</tr><?php  } ?>
									<?php  if(intval($item['changedispatchprice'])!=0) { ?>
									<tr><td  style='border:none;text-align:right;padding-right: 5px;'>卖家改运费：</td>
										<td  style='border:none;text-align:right;padding-left: 0px;'><span style="<?php  if(0<$item['changedispatchprice']) { ?>color:green<?php  } else { ?>color:red<?php  } ?>"><?php  if(0<$item['changedispatchprice']) { ?>+<?php  } else { ?>-<?php  } ?>￥<?php  echo abs($item['changedispatchprice'])?></span></td>
									</tr><?php  } ?> 
		
									<tr><td style='border:none;text-align:right;padding-right: 5px;'>应收款：</td>
										<td  style='border:none;text-align:right;color:green;padding-left: 0px;'>￥<?php  echo number_format($item['price'],2)?></td>
									</tr>
											<?php if(cv('order.op.changeprice')) { ?>
											<?php  if(empty($item['statusvalue'])) { ?>
									<tr>
										<td style='border:none;text-align:right;padding-right: 5px;'></td>
										<td  style='border:none;text-align:right;color:green;padding-left: 0px;'><a href="javascript:;" class="btn btn-link " onclick="changePrice('<?php  echo $item['id'];?>')">修改价格</a></td>
									</tr>
									<?php  } ?><?php  } ?>
								</table>
	                        </td>
	                        <?php  if(!empty($coupon)) { ?>
	                        <td><p class="form-control-static">
									<a href="<?php  echo $this->createPluginWebUrl('coupon/coupon',array('op'=>'post','id'=>$coupon['id']))?>" target='_blank'>
									[<?php  echo $coupon['id'];?>]<?php  echo $coupon['couponname'];?></a> - 
								   <?php  if($coupon['backtype']==0) { ?>立减 <?php  echo $coupon['deduct'];?> 元
								  <?php  } else if($coupon['backtype']==1) { ?>打 <?php  echo $coupon['discount'];?> 折
								  <?php  } else if($coupon['backtype']==2) { ?>
								  <?php  if($coupon['backmoney']>0) { ?>返 <?php  echo $coupon['backmoney'];?> 余额;<?php  } ?>
								  <?php  if($coupon['backcredit']>0) { ?>返 <?php  echo $coupon['backcredit'];?> 积分;<?php  } ?>
								  <?php  if($coupon['backredpack']>0) { ?>返 <?php  echo $coupon['backredpack'];?> 红包;<?php  } ?>
								  <b>返利方式: </b>
								    <?php  if($item['backwhen']==0) { ?>交易完成后（过退款期限）
								  <?php  } else if($item['backwhen']==1) { ?>订单完成后（收货后）
								  <?php  } else { ?>订单付款后<?php  } ?>
								  <b>返利情况: </b> <?php  if(empty($coupon['back'])) { ?>
								  <span class='label label-default'>未返利</span>
								  <?php  } else { ?><span class='label label-danger'>已返利 <?php  echo data('Y-m-d H:i',$coupon['backtime'])?></span>
								  <?php  } ?>
								  <?php  } ?>
								</p></td>
							<?php  } ?>
	                        <td><p class="form-control-static">
									<?php  if(empty($item['addressid'])) { ?>
		                                <?php  if($item['isverify']==1) { ?>线下核销
		                                <?php  } else if($item['isvirtual']==1) { ?>虚拟物品
		                                <?php  } else if(!empty($item['virtual'])) { ?>虚拟物品(卡密)自动发货<!--virtual-->
		                                <?php  } else if($item['dispatchtype']==1) { ?>自提
		                                <?php  } ?>
		                            <?php  } else { ?>
		                                <?php  if(empty($dispatchtype)) { ?>快递<?php  } ?>
		                            <?php  } ?></p></td>
	                        <td><p class="form-control-static">
		                            <?php  if($item['paytype'] == 0) { ?>未支付<?php  } ?>
		                            <?php  if($item['paytype'] == 1) { ?>余额支付<?php  } ?>
		                            <?php  if($item['paytype'] == 11) { ?>后台付款<?php  } ?>
		                            <?php  if($item['paytype'] == 21) { ?>微信支付<?php  } ?>
		                            <?php  if($item['paytype'] == 22) { ?>支付宝支付<?php  } ?>
		                            <?php  if($item['paytype'] == 23) { ?>银联支付<?php  } ?>
		                            <?php  if($item['paytype'] == 3) { ?>货到付款<?php  } ?>
		                        </p></td>
	                    </tr>
	                    <thead class="navbar-inner">
	                        <tr style="background-color: #1e95c9;">
	                        	<th style="width:12%;">订单状态</th>
	                        	<?php  if(!empty($refund) && $refund['status']==1) { ?>
	                        	<th style="width:12%;">退款时间</th>
	                        	<?php  } ?>
	                            <th style="width:12%;">下单日期</th>
	                            <?php  if($item['status']>=1) { ?>
	                            <th style="width:12%;">付款时间</th>
	                            <?php  } ?>
	                            <?php  if($item['status']>=2 && !empty($item['addressid']) ) { ?>
	                            <th style="width:12%;">发货信息</th>
	                            <?php  } ?>
	                            <?php  if($item['status']>=2 && !empty($item['virtual']) ) { ?>
	                            <th style="width:12%;">发货信息</th>
	                            <?php  } ?>
	                            
	                            <?php  if($item['status']>=3) { ?>
		                			<?php  if($item['isverify']==1) { ?>
	                            <th style="width:12%;">核销信息</th>
	                            <?php  } else { ?>
	                            <th style="width:12%;">完成时间</th>
	                            	<?php  } ?>
	                            <?php  } ?>
	                            <th style="width:12%;position: relative;">备注<button type='submit' name='saveremark' class='btn btn-default' style="position: absolute; top: 5px; right: 5px;">保存备注</button></th>
	                        </tr>
	                    </thead>
	                    <tr><td><p class="form-control-static">
		                            <?php  if($item['status'] == 0) { ?><span class="label label-info">待付款</span><?php  } ?>
		                            <?php  if($item['status'] == 1) { ?><span class="label label-info">待发货</span><?php  } ?>
		                            <?php  if($item['status'] == 2) { ?><span class="label label-info">待收货</span><?php  } ?>
		                            <?php  if($item['status'] == 3) { ?><span class="label label-success">已完成</span><?php  } ?>
		                            <?php  if($item['status'] == -1) { ?>
		                              <?php  if(!empty($refund) && $refund['status']==1) { ?>
		                                <span class="label label-default">已退款</span> <?php  if(!empty($refund['refundtime'])) { ?>退款时间: <?php  echo date('Y-m-d H:i:s',$refund['refundtime'])?><?php  } ?>
		                                <?php  } else { ?>
		                              <span class="label label-default">已关闭</span>
		                              <?php  } ?>
		                            <?php  } ?>
		                        </p></td>
		                    <?php  if(!empty($refund) && $refund['status']==1) { ?>
	                        <td><?php  echo date('Y-m-d H:i:s',$item['refundtime'])?></td>
	                        <?php  } ?>
	                        <td><?php  echo date('Y-m-d H:i:s', $item['createtime'])?></td>
	                        <?php  if($item['status']>=1) { ?>
	                        <td><?php  echo date('Y-m-d H:i:s', $item['paytime'])?></td>
	                        <?php  } ?>
	                        <?php  if($item['status']>=2 && !empty($item['addressid']) ) { ?>
	                        <td><p class="form-control-static">快递公司: <?php  echo $item['expresscom'];?>  <br/>快递单号: <?php  echo $item['expresssn'];?> <br/>发货时间: <?php  echo date('Y-m-d H:i:s', $item['sendtime'])?><br/>
	
										   <button type='button' class='btn btn-default' onclick='express_find(this,"<?php  echo $item['id'];?>")' >查看物流</button>
	
							</p></td>
	                        <?php  } ?>
	                        <?php  if($item['status']>=2 && !empty($item['virtual']) ) { ?>
	                        <td><?php  echo str_replace("\n","<br/>", $item['virtual_str'])?></td>
	                        <?php  } ?>
	                        <?php  if($item['status']>=3) { ?>
		                			<?php  if($item['isverify']==1) { ?>
	                        <td><p class="form-control-static">
		                        	消费码: <?php  echo $item['verifycode'];?><br/>核销时间: <?php  echo date('Y-m-d H:i:s', $item['finishtime'])?><br/>
									<?php  if(!empty($saler)) { ?>核销人:  <?php  echo $saler['nickname'];?>( <?php  echo $saler['salername'];?> )<br/><?php  } ?>
	               					<?php  if(!empty($store)) { ?>核销门店: <?php  echo $store['storename'];?><br/><?php  } ?>
		                        </p></td>
	                        <?php  } else { ?>
	                        <td><?php  echo date('Y-m-d H:i:s', $item['finishtime'])?></td>
	                        	<?php  } ?>
	                        <?php  } ?>
	                        <td><textarea style="height:60px;" class="form-control" name="remark" cols="70"><?php  echo $item['remark'];?></textarea></td>
	                    </tr>
	                </table>
	            </div>
	        </div>
        </div>
    </form>

        <?php  if(p('commission') && count($agents)>0) { ?>
        <div class="panel panel-default">
        	<div class="border_bg">
            	<div class="panel-heading">分销商信息</div>
	            <div class="panel-body">
	                <?php  if(!empty($agents['0'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">一级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[0]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[0]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[0]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[0]['id'];?> <b>姓名:</b> <?php  echo $agents[0]['realname'];?>  <b>手机号:</b> <?php  echo $agents[0]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission1;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>
	                <?php  if(!empty($agents['1'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">二级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[1]['id']))?>" target='_blank'>
									<img src='<?php  echo $agents[1]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[1]['nickname'];?> 
										 </a>
								<b>ID:</b> <?php  echo $agents[1]['id'];?> <b>姓名:</b> <?php  echo $agents[1]['realname'];?>  <b>手机号:</b> <?php  echo $agents[1]['mobile'];?>
								<b>佣金:</b> <?php  echo $commission2;?> 元
							</p>
	                    </div>
	                </div>
	                <?php  } ?>
	                <?php  if(!empty($agents['2'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">三级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
															<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[2]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[2]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" />  <?php  echo $agents[2]['nickname'];?> 
									 </a>
								<b>ID:</b> <?php  echo $agents[2]['id'];?> <b>姓名:</b> <?php  echo $agents[2]['realname'];?> <b>手机号:</b> <?php  echo $agents[2]['mobile'];?>
									<b>佣金:</b> <?php  echo $commission3;?> 元						
									 
									 </p>
	                    </div>
	                </div>
	                <?php  } ?>
	                <?php  if(!empty($agents['3'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">四级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[3]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[3]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[3]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[3]['id'];?> <b>姓名:</b> <?php  echo $agents[3]['realname'];?>  <b>手机号:</b> <?php  echo $agents[3]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission4;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['4'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">五级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[4]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[4]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[4]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[4]['id'];?> <b>姓名:</b> <?php  echo $agents[4]['realname'];?>  <b>手机号:</b> <?php  echo $agents[4]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission5;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['5'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">六级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[5]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[5]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[5]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[5]['id'];?> <b>姓名:</b> <?php  echo $agents[5]['realname'];?>  <b>手机号:</b> <?php  echo $agents[5]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission6;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['6'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">七级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[6]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[6]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[6]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[6]['id'];?> <b>姓名:</b> <?php  echo $agents[6]['realname'];?>  <b>手机号:</b> <?php  echo $agents[6]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission7;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['7'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">八级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[7]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[7]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[3]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[7]['id'];?> <b>姓名:</b> <?php  echo $agents[7]['realname'];?>  <b>手机号:</b> <?php  echo $agents[7]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission8;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['8'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">九级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[8]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[8]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[8]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[8]['id'];?> <b>姓名:</b> <?php  echo $agents[8]['realname'];?>  <b>手机号:</b> <?php  echo $agents[8]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission9;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['9'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">十级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[9]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[9]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[9]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[9]['id'];?> <b>姓名:</b> <?php  echo $agents[9]['realname'];?>  <b>手机号:</b> <?php  echo $agents[9]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission10;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['10'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">十一级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[10]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[10]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[10]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[10]['id'];?> <b>姓名:</b> <?php  echo $agents[10]['realname'];?>  <b>手机号:</b> <?php  echo $agents[10]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission11;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['11'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">十二级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[11]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[11]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[11]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[11]['id'];?> <b>姓名:</b> <?php  echo $agents[11]['realname'];?>  <b>手机号:</b> <?php  echo $agents[11]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission12;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['12'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">十三级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[12]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[12]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[12]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[12]['id'];?> <b>姓名:</b> <?php  echo $agents[12]['realname'];?>  <b>手机号:</b> <?php  echo $agents[12]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission13;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['13'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">十四级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[13]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[13]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[13]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[13]['id'];?> <b>姓名:</b> <?php  echo $agents[13]['realname'];?>  <b>手机号:</b> <?php  echo $agents[13]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission14;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>	
	                <?php  if(!empty($agents['14'])) { ?>
	                <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">十五级分销商 :</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'detail','id'=>$agents[14]['id']))?>" target='_blank'>
								<img src='<?php  echo $agents[14]['avatar'];?>' style="width:30px;height:30px;padding:1px;border:1px solid #ccc" /> <?php  echo $agents[14]['nickname'];?> 
									  </a>
									 <b>ID:</b> <?php  echo $agents[14]['id'];?> <b>姓名:</b> <?php  echo $agents[14]['realname'];?>  <b>手机号:</b> <?php  echo $agents[14]['mobile'];?> 
									
								<b>佣金:</b> <?php  echo $commission15;?> 元
									 </p>
								
	                    </div>
	                </div>
	                <?php  } ?>					
					<?php if(cv('commission.changecommission')) { ?>
					  <div class="form-group">
	                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
	                    <div class="col-sm-9 col-xs-12">
	                        <p class="form-control-static">
								<a href='javascript:;' class='btn btn-default' onclick="commission_change('<?php  echo $item['id'];?>')">修改佣金</a>
							</p>
	                    </div>
	                </div>
								
								<?php  } ?>
	            </div>
	        </div>
        </div>
        <?php  } ?>
        <?php  if(!empty($item['addressid'])) { ?>
          <div class="panel panel-default">
            <div class="panel-heading">收件人信息</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static ad1"><?php  echo $user['realname'];?></p>
                        <p class="form-control-static ad2"><input type="text" name="realname" id="realname" value="<?php  echo $user['realname'];?>" class="form-control" style="width:130px;display:inline;"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static ad1"><?php  echo $user['mobile'];?></p>
                        <p class="form-control-static ad2"><input type="text" name="mobile" id="mobile" value="<?php  echo $user['mobile'];?>" class="form-control" style="width:130px;display:inline;"></p>
                    </div>
                </div>
                <div class="form-group">

                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">地址 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static ad1" id="d_address"><?php  echo $user['address'];?>
                        </p>

                        <?php if(cv('order.op.changeaddress')) { ?>
                        <p class="form-control-static ad2" id="e_address">
                            <select id="sel-provance" onChange="selectCity();" class="select form-control" style="width:130px;display:inline;">
                                <option value="" selected="true">省/直辖市</option>
                            </select>
                            <select id="sel-city" onChange="selectcounty(0)" class="select form-control" style="width:135px;display:inline;">
                                <option value="" selected="true">请选择</option>
                            </select>
                            <select id="sel-area" class="select form-control" style="width:130px;display:inline;">
                                <option value="" selected="true">请选择</option>
                            </select>
                            <input type="text" name="address_info" id="address_info" class="form-control changeprice_orderprice" style="width:300px;display:inline;margin-top:4px" value="<?php  echo $address_info?>">
                        </p>

                        <button type='button' name='editaddress' id='editaddress' class='btn btn-default ad1'>编辑信息</button>
                        <button type='button' name='saveaddress' id='saveaddress' class='btn btn-default ad2'>保存信息</button>
                        <button type='button' name='backaddress' id='backaddress' class='btn btn-default ad2'>返回</button>
                        <?php  } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php  } else if($item['isverify']==1 || !empty($item['virtual']) ||!empty($item['isvirtual'])) { ?>
		<?php  if($show==1) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">联系人</div>
            <div class="panel-body">
                   <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系人姓名 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php  echo $user['carrier_realname'];?> </p>
                    </div>
                </div>
               <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系人手机 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php  echo $user['carrier_mobile'];?>  </p>
                    </div>
                </div>
            </div>
        </div>
		<?php  } ?> 
        <?php  } else { ?>
          <div class="panel panel-default">
            <div class="panel-heading">自提信息</div>
            <div class="panel-body">
                   <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">自提人姓名 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php  echo $user['carrier_realname'];?> /  <?php  echo $user['carrier_mobile'];?></p>
                    </div>
                </div>
               <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">自提地点 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php  echo $user['address'];?> (联系人： <?php  echo $user['realname'];?> / <?php  echo $user['mobile'];?> ) </p>
                    </div>
                </div>
            </div>
        </div>
        <?php  } ?>
      

    <?php  if($diyform_flag == 1) { ?>
	
	<?php  if(!empty($order_data)) { ?>
   <div class='panel-heading'>订单统一表单信息</div>
    <div class='panel-body'>
        <!--<span>diyform</span>-->
    
       <?php  $datas = $order_data?>
        <?php  if(is_array($order_fields)) { foreach($order_fields as $key => $value) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  echo $value['tp_name']?></label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">

                    <?php  if($value['data_type'] == 0 || $value['data_type'] == 1 || $value['data_type'] == 2 || $value['data_type'] == 6) { ?>
                    <?php  echo str_replace("\n","<br/>",$datas[$key])?>

                    <?php  } else if($value['data_type'] == 3) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <?php  echo $v1?>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 5) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 7) { ?>
                    <?php  echo $datas[$key]?>

                    <?php  } else if($value['data_type'] == 8) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <?php  echo $v1?>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 9) { ?>
                    <?php echo $datas[$key]['province']!='请选择省份'?$datas[$key]['province']:''?>-<?php echo $datas[$key]['city']!='请选择城市'?$datas[$key]['city']:''?>
                    <?php  } ?>
                </div>

            </div>
        </div>

        <?php  } } ?>
     
    </div>
	<?php  } ?>
	  <?php  if(count($goods)==1 &&  !empty($goods[0]['diyformdata'])) { ?>
    <div class='panel-heading'>其他信息</div>
    <div class='panel-body'>
        <!--<span>diyform</span>-->
      
		<?php  $datas = $goods[0]['diyformdata']?>
        <?php  if(is_array($goods[0]['diyformfields'])) { foreach($goods[0]['diyformfields'] as $key => $value) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  echo $value['tp_name']?></label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">

                    <?php  if($value['data_type'] == 0 || $value['data_type'] == 1 || $value['data_type'] == 2 || $value['data_type'] == 6) { ?>
                    <?php  echo str_replace("\n","<br/>",$datas[$key])?>

                    <?php  } else if($value['data_type'] == 3) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <?php  echo $v1?>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 5) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 7) { ?>
                    <?php  echo $datas[$key]?>

                    <?php  } else if($value['data_type'] == 8) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <?php  echo $v1?>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 9) { ?>
                    <?php echo $datas[$key]['province']!='请选择省份'?$datas[$key]['province']:''?>-<?php echo $datas[$key]['city']!='请选择城市'?$datas[$key]['city']:''?>
                    <?php  } ?>
                </div>

            </div>
        </div>

        <?php  } } ?>
    
    </div>    <?php  } ?>
    <?php  } ?>
        <?php  if(!empty($refund)) { ?>

        <div class="panel panel-default">
            <div class="panel-heading">退款申请</div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">退款原因 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php  echo $refund['reason'];?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">退款说明 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <p class="form-control-static"><?php echo empty($refund['content'])?'无':$refund['content']?></p>
                    </div>
                </div>
                <?php  if($refund['status']==1) { ?>
                  <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">退款时间 :</label>
                    <div class="col-sm-9 col-xs-12">
                        <div class="form-control-static"><?php  echo date('Y-m-d H:i:s',$item['refundtime'])?></div>
                    </div>
                </div>
                <?php  } ?>
                
                <?php if(cv('order.op.refund')) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  if($refund['status']==0) { ?>
                        <a class="btn btn-danger btn-sm" href="javascript:;" onclick="$('#modal-refund').find(':input[name=id]').val('<?php  echo $item['id'];?>')" data-toggle="modal" data-target="#modal-refund">处理退款申请</a>
                        <?php  } else if($refund['status']==-1) { ?>
                        <span class='label label-default'>已拒绝</span>
                        <?php  } else if($refund['status']==1) { ?>
                        <span class='label label-danger'>已退款</span>
                        <?php  } ?>
                    </div>
                </div> 
                <?php  } ?>
            </div>
        </div>
        <?php  } ?>
        <div class="panel panel-default">
            <div class="panel-heading">商品信息</div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                        <tr><th style="width:4%;">ID</th>
                            <th style="width:10%;">商品标题</th>
                            <th style="width:12%;">商品规格</th>
                            <th style="width:10%;">商品编号</th>
                            <th style="width:10%;">商品条码</th>
                            <th style="width:16%;">现价/原价/成本价</th>
                            <th style="width:15%;">属性</th>
                            <th style="width:5%;">购买数量</th>
                            <th style="width:10%;color:red;">折扣前/折扣后</th>
                            <th style="width:8%;">操作</th>
                        </tr>
                    </thead>
                    <?php  if(is_array($item['goods'])) { foreach($item['goods'] as $goods) { ?>
                    <tr><td><?php  echo $goods['id'];?></td>
                        <td>
                            <?php  if($category[$goods['pcate']]['name']) { ?>
                            <span class="text-error">[<?php  echo $category[$goods['pcate']]['name'];?>] </span><?php  } ?><?php  if($children[$goods['pcate']][$goods['ccate']]['1']) { ?>
                            <span class="text-info">[<?php  echo $children[$goods['pcate']][$goods['ccate']]['1'];?>] </span>
                            <?php  } ?>
                            <?php  echo $goods['title'];?>
                        </td>
                        <td><span class="label label-info"><?php  echo $goods['optionname'];?></span></td>
                        <td><?php  echo $goods['goodssn'];?></td>
                        <td><?php  echo $goods['productsn'];?></td>
                        <td><?php  echo $goods['marketprice'];?>元 / <?php  echo $goods['productprice'];?>元 / <?php  echo $goods['costprice'];?>元</td>
                        <td><?php  if($goods['status']==1) { ?><span class="label label-success">上架</span><?php  } else { ?><span class="label label-error">下架</span><?php  } ?>&nbsp;<span class="label label-info"><?php  if($goods['type'] == 1) { ?>实体商品<?php  } else { ?>虚拟商品<?php  } ?></span></td>
                        <td><?php  echo $goods['total'];?></td>
                        <td style='color:red;font-weight:bold;'><?php  echo $goods['orderprice'];?>/<?php  echo $goods['realprice'];?>
						<?php  if(intval($goods['changeprice'])!=0) { ?>
						<br/>(改价<?php  if($goods['changeprice']>0) { ?>+<?php  } ?><?php  echo number_format(abs($goods['changeprice']),2)?>)
							<?php  } ?>
						</td>
                        <td>  
                            <a href="<?php  echo $this->createWebUrl('shop/goods', array('id' => $goods['id'], 'op' => 'post'))?>" class="btn btn-default btn-sm" title="编辑" style="background-color: #ff5858; color: #fff;"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                        </td>
                    </tr>
					<?php  if(count($item['goods'])>1 && $diyform_flag==1 && !empty($goods['diyformdata'])) { ?>
					<tr>
						<td colspan='10' style="background:#FCF8E3">
							 
							<a href='javascript:;' class='btn btn-default' hide="1" onclick="showDiyInfo(this)">查看用户信息</a>
							<div style='display:none'>
							  
		<?php  $datas = $goods['diyformdata']?>
        <?php  if(is_array($goods['diyformfields'])) { foreach($goods['diyformfields'] as $key => $value) { ?>
        <div class="form-group">
            <label class="col-xs-1 control-label"><?php  echo $value['tp_name']?></label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">

                    <?php  if($value['data_type'] == 0 || $value['data_type'] == 1 || $value['data_type'] == 2 || $value['data_type'] == 6) { ?>
                    <?php  echo str_replace("\n","<br/>",$datas[$key])?>

                    <?php  } else if($value['data_type'] == 3) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <?php  echo $v1?>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 5) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 7) { ?>
                    <?php  echo $datas[$key]?>

                    <?php  } else if($value['data_type'] == 8) { ?>
                    <?php  if(!empty($datas[$key])) { ?>
                    <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                    <?php  echo $v1?>
                    <?php  } } ?>
                    <?php  } ?>

                    <?php  } else if($value['data_type'] == 9) { ?>
                    <?php echo $datas[$key]['province']!='请选择省份'?$datas[$key]['province']:''?>-<?php echo $datas[$key]['city']!='请选择城市'?$datas[$key]['city']:''?>
                    <?php  } ?>
                </div>

            </div>
        </div>

        <?php  } } ?>
       
					</div>		
						</td>
					</tr>
					<?php  } ?>
                    <?php  } } ?>
                    <tr>
                        <td colspan="2">
                            
                           <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/order/ops', TEMPLATE_INCLUDEPATH)) : (include template('web/order/ops', TEMPLATE_INCLUDEPATH));?>
                           
                        </td>
                        <td colspan="8">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
</div>
        </div>
		<script language="javascript">
			function showDiyInfo(obj){
				var hide = $(obj).attr('hide');
				if(hide=='1'){
					$(obj).next().slideDown();
				}
				else{
					$(obj).next().slideUp();
				}
				$(obj).attr('hide',hide=='1'?'0':'1');
			}

            <?php if(cv('order.op.changeaddress')) { ?>
            cascdeInit("<?php echo isset($user['province'])?$user['province']:''?>","<?php echo isset($user['city'])?$user['city']:''?>","<?php echo isset($user['area'])?$user['area']:''?>");

            $('#editaddress').click(function() {
                show_address(1);
            });

            $('#backaddress').click(function() {
                show_address(0);
            });

            $('#saveaddress').click(function() {
                var url = "<?php  echo $this->createWebUrl('order/list',array('op'=>'saveaddress'))?>";
                var id =<?php  echo $id?>;
                var realname = $('#realname').val();
                var mobile = $('#mobile').val();
                var provance = $('#sel-provance').val();
                var city = $('#sel-city').val();
                var area = $('#sel-area').val();
                var address = $('#address_info').val();

                if(realname==''){
                    alert('请填写收件人姓名!');
                    return false;
                }

                if(mobile==''){
                    alert('请填写收件人手机!');
                    return false;
                }

                if(provance=='请选择省份'){
                    alert('请选择省份!');
                    return false;
                }

                if(address==''){
                    alert('请填写详细地址!');
                    return false;
                }

                $.ajax({
                    url: url,
                    dataType: "json",
                    data: {id:id,realname:realname,mobile:mobile,provance:provance,city:city,area:area,address:address},
                    success:function(json){
                        var result = json.result;
                        if(json.status==1){
                            location.reload();
                        } else {
                            alert(result);
                        }
                    }
                });
            });

            function show_address(flag) {
                if (flag == 1) {
                    $('.ad1').hide();
                    $('.ad2').show();
                } else {
                    $('.ad1').show();
                    $('.ad2').hide();
                }
            }
            <?php  } ?>

			</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/order/modals', TEMPLATE_INCLUDEPATH)) : (include template('web/order/modals', TEMPLATE_INCLUDEPATH));?>
  <?php  if(p('commission')) { ?>
		  
		   <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('commission/changecommission', TEMPLATE_INCLUDEPATH)) : (include template('commission/changecommission', TEMPLATE_INCLUDEPATH));?>
		  <?php  } ?>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>

