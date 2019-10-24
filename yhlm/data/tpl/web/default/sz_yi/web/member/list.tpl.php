<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/member/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/member/tabs', TEMPLATE_INCLUDEPATH));?>
<?php  if($operation=='display') { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="member" />
            <div class="form-group">
            	<div class="col-lg-2 col-sm-6 col-xs-12">
          			<div class='input-group'>
		                <div class="input-group-addon">ID</div>
		                <input type="text" class="form-control"  name="mid" value="<?php  echo $_GPC['mid'];?>"/>
			    	</div>
          		</div>
	      
        		<div class="col-lg-3 col-sm-6 col-xs-12">
          			<div class='input-group'>
		                <div class="input-group-addon">会员信息</div>
		                <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder="可搜索昵称/姓名/手机号"/>
			    	</div>
          		</div>
	      
        		<div class="col-lg-2 col-sm-6 col-xs-12">
          			<div class='input-group'>
		                <div class="input-group-addon">是否关注</div>
		                    <select name='followed' class='form-control'>
		                        <option value=''></option>
		                        <option value='0' <?php  if($_GPC['followed']=='0') { ?>selected<?php  } ?>>未关注</option>
		                        <option value='1' <?php  if($_GPC['followed']=='1') { ?>selected<?php  } ?>>已关注</option>
		                        <option value='2' <?php  if($_GPC['followed']=='2') { ?>selected<?php  } ?>>取消关注</option></select>
			    	</div>
          		</div>
	      
        		<div class="col-lg-2 col-sm-6 col-xs-12">
           			<div class='input-group'>
		                <div class="input-group-addon">会员等级</div>
		                <select name='level' class='form-control'>
		                    <option value=''></option>
		                    <?php  if(is_array($levels)) { foreach($levels as $level) { ?>
		                    <option value='<?php  echo $level['id'];?>' <?php  if($_GPC['level']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
		                    <?php  } } ?>
		                </select>
			    	</div>
          		</div>
	      
        		<div class="col-lg-2 col-sm-6 col-xs-12">
          			<div class='input-group'>
		                <div class="input-group-addon">会员分组</div>
	                    <select name='groupid' class='form-control'>
	                        <option value=''></option>
	                        <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
	                        <option value='<?php  echo $group['id'];?>' <?php  if($_GPC['groupid']==$group['id']) { ?>selected<?php  } ?>><?php  echo $group['groupname'];?></option>
	                        <?php  } } ?>
	                    </select>
					</div>
		        </div>
            </div>
			
			<div class="form-group">
		        <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12" style="height: 34px;">
		        	<div class='input-group'>
			            <div class='input-group-addon' style="padding: 8px 12px 9px 12px; border-left: 1px #ccc solid; border-right: 1px #ccc solid; border-radius: 2px;">注册时间
			            	<label class='radio-inline' style='margin-top:-7px;'><input type='radio' value='0' name='searchtime' <?php  if($_GPC['searchtime']=='0') { ?>checked<?php  } ?> checked>不搜索</label>
			            	<label class='radio-inline' style='margin-top:-7px;'><input type='radio' value='1' name='searchtime' <?php  if($_GPC['searchtime']=='1') { ?>checked<?php  } ?>>搜索</label>
			            </div>
		        	</div>
		        </div>
		
		        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
		        	<div class='input-group'>
			            <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d  H:i', $endtime)),true);?>
			        </div>
		        </div>

		        <div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
		        	<div class='input-group'>
		                <div class="input-group-addon">黑名单</div>
	                    <select name='isblack' class='form-control'>
	                        <option value=''></option>
	                        <option value='0' <?php  if($_GPC['isblack']=='0') { ?>selected<?php  } ?>>否</option>
	                        <option value='1' <?php  if($_GPC['isblack']=='1') { ?>selected<?php  } ?>>是</option>
	                    </select>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
					<div class='input-group'>
	                	<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
	                	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
	                	<?php if(cv('member.member.export')) { ?>   
	                    <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
	                    <?php  } ?>
					</div>
		        </div>
            </div>
        </form>
    </div>
</div><div class="clearfix">

<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?>   </div>
    <div class="table-responsive">
        <table class="table table-hover" style="overflow:visible;">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:8%;'>会员ID</th>
					<?php  if($opencommission) { ?>
						<th style='width:12%;'>推荐人</th>	
					<?php  } ?>
                    <th style='width:11%;'>粉丝</th>
                    <th style='width:8%;'>会员姓名</th>
                    <th style='width:12%;'>手机号码</th>
                    <th style='width:12%;'>会员等级/分组</th>
                    <th style='width:13%;'>注册时间</th>
                    <th style='width:8%;'>积分</th>
                    <th style='width:8%;'>余额</th>
                    <th style='width:8%;'>成交订单</th>
                    <th style='width:8%;'>成交金额</th> 
                    <th style='width:11%'>关注</th>
                    <th style='width:10%'>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td>   <?php  echo $row['id'];?></td>
		  <?php  if($opencommission) { ?>
		    <td  <?php  if(!empty($row['agentid'])) { ?>title='ID: <?php  echo $row['agentid'];?>'<?php  } ?>>
				<?php  if(empty($row['agentid'])) { ?>
				  <?php  if($row['isagent']==1) { ?>
				      <label class='label label-primary'>总店</label>
				      <?php  } else { ?>
				       <label class='label label-default'>暂无</label>
				      <?php  } ?>
				<?php  } else { ?>
				
                    	<?php  if(!empty($row['agentavatar'])) { ?>
                        <img src='<?php  echo $row['agentavatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                       <?php  } ?>
                       <?php  if(empty($row['agentnickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['agentnickname'];?><?php  } ?>
					   <?php  } ?>
                        
                    </td>
		  <?php  } ?>
		  
                    <td><a href="<?php  echo $this->createWebUrl('member',array('op'=>'detail','id' => $row['id']));?>" title="会员详情" style="color: #376fd5;">
                    	<?php  if(!empty($row['avatar'])) { ?>
                         <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                       <?php  } ?>
                       <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?></a>
                        
                    </td>
                    <td><?php  echo $row['realname'];?></td>
                    <td><?php  echo $row['mobile'];?></td>
                    <td><?php  if(empty($row['levelname'])) { ?>普通会员<?php  } else { ?><?php  echo $row['levelname'];?><?php  } ?>
                        <br/><?php  if(empty($row['groupname'])) { ?>无分组<?php  } else { ?><?php  echo $row['groupname'];?><?php  } ?></td>
      
                    <td><?php  echo date('Y-m-d H:i',$row['createtime'])?></td>
                    <td><?php  echo $row['credit1'];?></td>
                    <td><?php  echo $row['credit2'];?></td>
                    <td><?php  echo $row['ordercount'];?></td>
                    <td><?php  echo floatval($row['ordermoney'])?></td>
                    <td> 
						   <?php  if($row['isblack']==1) { ?>
                    <span class="label label-default" style='color:#fff;background:black'>黑名单</span>
					<?php  } else { ?>
						<?php  if(empty($row['followed'])) { ?>
                        <?php  if(empty($row['uid'])) { ?>
                        <label class='label label-default'>未关注</label>
                        <?php  } else { ?>
                        <label class='label label-warning'>取消关注</label>
                        <?php  } ?>
                        <?php  } else { ?>
                    <label class='label label-success'>已关注</label>    
                    <?php  } ?><?php  } ?>
					
					</td>
             
                            <td  style="overflow:visible;">
                        
                        <div class="btn-group btn-group-sm" >
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
                                <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 9999'>
                               
                        <?php if(cv('member.member.view|member.member.edit')) { ?><li><a href="<?php  echo $this->createWebUrl('member',array('op'=>'detail','id' => $row['id']));?>" title="会员详情"><i class='fa fa-edit'></i> 会员详情</a></li><?php  } ?>
                        <?php if(cv('order')) { ?><li><a  href="<?php  echo $this->createWebUrl('order', array('op' => 'display','member'=>$row['nickname']))?>" title='会员订单'><i class='fa fa-list'></i> 会员订单</a></li><?php  } ?>
                        <?php if(cv('finance.recharge.credit1')) { ?><li><a href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit1','id'=>$row['id']))?>" title='充值积分'><i class='fa fa-credit-card'></i> 充值积分</a></li><?php  } ?>
                        <?php if(cv('finance.recharge.credit2')) { ?><li><a href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit2','id'=>$row['id']))?>" title='充值余额'><i class='fa fa-money'></i> 充值余额 </a></li><?php  } ?>
                        <?php if(cv('finance.recharge.credit3')) { ?><li><a href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit3','id'=>$row['id']))?>" title='充值易货码'><i class='fa fa-yen'></i> 充值易货码 </a></li><?php  } ?> 
		    <?php if(cv('member.member.black')) { ?>
                            <?php  if($row['isblack']==1) { ?>  
                            <li><a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'setblack','id' => $row['id'],'black'=>0));?>" title='取消黑名单'><i class='fa fa-minus-square'></i> 取消黑名单</a></li> 
                            <?php  } else { ?>
                            <li><a href="<?php  echo $this->createWebUrl('member/list',array('op'=>'setblack','id' => $row['id'],'black'=>1));?>" title='设置黑名单'><i class='fa fa-minus-circle'></i> 设置黑名单</a></li>
                            <?php  } ?>
                        <?php  } ?>
                        <?php if(cv('member.member.delete')) { ?><li><a  href="<?php  echo $this->createWebUrl('member',array('op'=>'delete','id' => $row['id']));?>" title='删除会员' onclick="return confirm('确定要删除该会员吗？');"><i class='fa fa-remove'></i> 删除会员</a></li><?php  } ?>
                                </ul>
                            </div>

               
                    </td>
                   
                    </td>
                </tr>
                <?php  } } ?>
            </tbody>
        </table>
           <?php  echo $pager;?>
    </div>
</div>
</div>
<?php  } else if($operation=='detail') { ?>
<form <?php  if('member.member.edit') { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="id" value="<?php  echo $member['id'];?>">
    <input type="hidden" name="op" value="detail">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="do" value="member" />
    <style type="text/css">
    	table thead tr{ background-color: #1e95c9; color: #fff;}
    	table thead tr th{ text-align: center; }
    </style>
    <div class='panel panel-default'>
        <div class='panel-heading'>会员详细信息</div>
        <div class='panel-body'>
            <div class="table-responsive">
                <table  class="table table-bordered">
                	<thead class="navbar-inner">
	                    <tr><th style="width:12%;">粉丝</th>
	                        <th style="width:16%;">OPENID</th>
	                        <th>会员等级</th>
	                        <th>会员分组</th>
	                        <th>真实姓名</th>
	                        <th>手机号码</th>
	                        <th>微信号</th>
	                        <th>积分</th>
	                    </tr>
                    </thead>
                    <tr><td><img style='width:100px;height:100px;padding:1px;border:1px solid #ccc' src='<?php  echo $member['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                        <p><?php  echo $member['nickname'];?></p></td>
                        <td><div class="form-control-static" style="width: 100%; word-wrap:break-word;"><?php  echo $member['openid'];?></div></td>
                        <td><?php if(cv('member.member.edit')) { ?>

                      <select name='data[level]' class='form-control'>

                        <option value=''><?php echo empty($shop['levelname'])?'普通会员':$shop['levelname']?></option>

                        <?php  if(is_array($levels)) { foreach($levels as $level) { ?>

                        <option value='<?php  echo $level['id'];?>' <?php  if($member['level']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>

                        <?php  } } ?>

                    </select>

                    <?php  } else { ?>

                    <div class='form-control-static'>

                        <?php  if(empty($member['level'])) { ?>

                        <?php echo empty($shop['levelname'])?'普通会员':$shop['levelname']?>

                        <?php  } else { ?>

                        <?php  echo pdo_fetchcolumn('select levelname from '.tablename('sz_yi_member_level').' where id=:id limit 1',array(':id'=>$member['level']))?>

                        <?php  } ?>

                    </div>

                    <?php  } ?></td>
                        <td><div class="">
                                <?php if(cv('member.member.edit')) { ?>
                                <select name='data[groupid]' class='form-control'>
                                    <option value=''>无分组</option>
                                    <?php  if(is_array($groups)) { foreach($groups as $group) { ?>
                                    <option value='<?php  echo $group['id'];?>' <?php  if($member['groupid']==$group['id']) { ?>selected<?php  } ?>><?php  echo $group['groupname'];?></option>
                                    <?php  } } ?>
                                </select>
                                <?php  } else { ?>
                                <div class='form-control-static'>
                                    <?php  if(empty($member['groupid'])) { ?>
                                    无分组
                                    <?php  } else { ?>
                                    <?php  echo pdo_fetchcolumn('select groupname from '.tablename('sz_yi_member_group').' where id=:id limit 1',array(':id'=>$member['groupid']))?>
                                    <?php  } ?>
                                </div>
                                <?php  } ?>
                            </div></td>
                        <td><div class="">
                                <?php if(cv('member.member.edit')) { ?>
                                <input type="text" name="data[realname]" class="form-control" value="<?php  echo $member['realname'];?>"  />
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $member['realname'];?></div>
                                <?php  } ?>
                            </div></td>
                        <td><div class="">
                                <?php if(cv('member.member.edit')) { ?>
                                <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $member['mobile'];?>"  />
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $member['mobile'];?></div>
                                <?php  } ?>
                            </div></td>
                        <td><div class="">
                                <?php if(cv('member.member.edit')) { ?>
                                <input type="text" name="data[weixin]" class="form-control" value="<?php  echo $member['weixin'];?>"  />
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $member['weixin'];?></div>
                                <?php  } ?>
                            </div></td>
                        <td><div class="">
                            <?php if(cv('finance.recharge.credit1')) { ?>
                            <div class='input-group'>
                                <div class=' input-group-addon'  style='width:200px;text-align: left;'><?php  echo $member['credit1'];?></div>
                                <div class='input-group-btn'>
                                    <a class='btn btn-primary' href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit1','id'=>$member['id']))?>">充值</a>
                                </div>
                            </div>
                            <?php  } else { ?>
                            <div class='form-control-static'><?php  echo $member['credit1'];?></div>
                            <?php  } ?>

                        </div></td>
                    </tr>
                    <thead class="navbar-inner">
	                    <tr>
                            <th>登录密码</th>
                            <th>余额(非易货码)</th>
	                        <th>成交订单数</th>
	                        <th>成交金额</th>
	                        <th>注册时间</th>
	                        <th>关注状态</th>
	                        <th>黑名单</th>
	                        <th>备注</th>
	                    </tr>
	                </thead>
                    <tr>
                    <td><div class="">
                                <?php if(cv('member.member.edit')) { ?>
                                <input type="text" name="password" class="form-control" value=""  /> 留空则不修改密码
                                <?php  } else { ?>
                                <div class='form-control-static'></div>
                                <?php  } ?>
                            </div></td>
                            <td><div class="">
                            <?php if(cv('finance.recharge.credit2')) { ?>
                            <div class='input-group'>
                                <div class=' input-group-addon' style='width:200px;text-align: left;'><?php  echo $member['credit2'];?></div>

                                <div class='input-group-btn'><a class='btn btn-primary' href="<?php  echo $this->createWebUrl('finance/recharge', array('op'=>'credit2','id'=>$member['id']))?>">充值</a>
                                </div>

                            </div>
                            <?php  } else { ?>
                            <div class='form-control-static'><?php  echo $member['credit2'];?></div>
                            <?php  } ?>
                        </div></td>
                        <td>
                            <div class='form-control-static'><?php  echo $member['self_ordercount'];?></div>
                        </td>
                        <td>
                            <div class='form-control-static'><?php  echo $member['self_ordermoney'];?> 元</div>
                        </td>
                        <td>
                            <div class='form-control-static'><?php  echo date('Y-m-d H:i:s', $member['createtime']);?></div>
                        </td>
                        <td><div class='form-control-static'>
                                <?php  $followed = m('user')->followed($member['openid'])?>
                                <?php  if(!$followed) { ?>
                                <?php  if(empty($member['uid'])) { ?>
                                <label class='label label-default'>未关注</label>
                                <?php  } else { ?>
                                <label class='label label-warning'>取消关注</label>
                                <?php  } ?>
                                <?php  } else { ?>
                                <label class='label label-success'>已关注</label>
                                <?php  } ?>

                            </div>
                        </td>
                        <td><div class="">
                            <?php if(cv('member.member.edit')) { ?>
                            <label class="radio-inline"><input type="radio" name="data[isblack]" value="1" <?php  if($member['isblack']==1) { ?>checked<?php  } ?>>是</label>
                            <label class="radio-inline" ><input type="radio" name="data[isblack]" value="0" <?php  if($member['isblack']==0) { ?>checked<?php  } ?>>否</label>
                            <span class="help-block">设置黑名单后，此会员无法访问商城</span>
                            <?php  } else { ?>
                            <input type='hidden' name='data[isblack]' value='<?php  echo $member['isblack'];?>' />
                            <div class='form-control-static'><?php  if($member['isblack']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                            <?php  } ?>

                        </div></td>
                        <td><div class="">
                            <?php if(cv('member.member.edit')) { ?>
                            <textarea name="data[content]" class='form-control'><?php  echo $member['content'];?></textarea>
                            <?php  } else { ?>
                            <div class='form-control-static'><?php  echo $member['content'];?></div>
                            <?php  } ?>
                        </div></td>
                        
                        </tr>
	                </thead>
                </table>
            </div>
        </div>

		
        <?php  if($diyform_flag == 1) { ?>
        <div class='panel-heading'>
            自定义表单信息
        </div>
        <div class='panel-body'>
            <!--<span>diyform</span>-->

            <?php  $datas = iunserializer($member['diymemberdata'])?>
            <?php  if(is_array($fields)) { foreach($fields as $key => $value) { ?>
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
                        <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a>
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
	<?php  if($member['isagent']==1 && $member['status']==1) { ?>
	<?php  } else { ?>
        <?php  if($hascommission && cv('commission.agent.changeagent')) { ?>
        <div class='panel-heading'>
            设置分销商 <small>注意: 分销商设置后，无法再此进行修改，如果要修改，请联系系统管理员</small>
        </div>
           <div class='panel-body'>
<div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">上级分销商</label>
                    <div class="col-sm-4">
                       <input type="hidden" value="<?php  echo $member['agentid'];?>" id='agentid' name='adata[agentid]' class="form-control"  />
                        
                      <?php if(cv('commission.agent.edit')) { ?>
                        <div class='input-group'>
                            <input type="text" name="parentagent" maxlength="30" value="<?php  if(!empty($parentagent)) { ?><?php  echo $parentagent['nickname'];?>/<?php  echo $parentagent['realname'];?>/<?php  echo $parentagent['mobile'];?><?php  } ?>" id="parentagent" class="form-control" readonly />
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();">选择上级分销商</button>
                                <button class="btn btn-danger" type="button" onclick="$('#agentid').val('');$('#parentagent').val('');$('#parentagentavatar').hide()">清除选择</button>
                            </div> 
                        </div>
                        <span id="parentagentavatar" class='help-block' <?php  if(empty($parentagent)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $parentagent['avatar'];?>"/></span>
                         
                        <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择上级分销商</h3></div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice" placeholder="请输入分销商昵称/姓名/手机号" />
                                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                            </div>
                                        </div>
                                        <div id="module-menus-notice" style="padding-top:5px;"></div>
                                    </div>
                                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                </div>

                            </div>
                        </div>
                        <span class="help-block">修改后， 只有关系链改变, 以往的订单佣金都不会改变,新的订单才按新关系计算佣金 ,请谨慎选择</span>
                        <?php  } else { ?>
                        <div class='form-control-static'>
                            <?php  if(!empty($parentagent)) { ?><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $parentagent['avatar'];?>"/><?php  } else { ?>无<?php  } ?>
                         </div>
                        <?php  } ?>
                        
                    </div>
                </div>
            
			     <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否固定上级</label>
                <div class="col-sm-9 col-xs-12">
                     <?php if(cv('commission.agent.check')) { ?>
                    <label class="radio-inline"><input type="radio" name="adata[fixagentid]" value="1" <?php  if($member['fixagentid']==1) { ?>checked<?php  } ?>>是</label>
                    <label class="radio-inline" ><input type="radio" name="adata[fixagentid]" value="0" <?php  if($member['fixagentid']==0) { ?>checked<?php  } ?>>否</label>
                    <span class="help-block">固定上级后，任何条件也无法改变其上级，如果不选择上级分销商，且固定上级，则上级永远为总店（是分销商）或无上线（非分销商）</span>
                    <?php  } else { ?>
                      <input type='hidden' name='adata[fixagentid]' value='<?php  echo $member['fixagentid'];?>' />
                      <div class='form-control-static'><?php  if($member['fixagentid']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                    
                </div>
            </div>
			   
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分销商等级</label>
               <div class="col-sm-9 col-xs-12">
                         <?php if(cv('commission.agent.edit')) { ?>
                    <select name='adata[agentlevel]' class='form-control'>
                        <option value='0'><?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?></option>
                         <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                        <option value='<?php  echo $level['id'];?>' <?php  if($member['agentlevel']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
                        <?php  } } ?>
                    </select>
                         <?php  } else { ?>
                             <input type="hidden" name="adata[agentlevel]" class="form-control" value="<?php  echo $member['agentlevel'];?>"  />
                             
                              <?php  if(empty($member['agentlevel'])) { ?>
                            <?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?>
                                <?php  } else { ?>
                                <?php  echo pdo_fetchcolumn('select levelname from '.tablename('sz_yi_commission_level').' where id=:id limit 1',array(':id'=>$member['agentlevel']))?>
                                <?php  } ?>
                         <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">累计佣金</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'> <?php  echo $member['commission_total'];?></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">已打款佣金</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'> <?php  echo $member['commission_pay'];?></div>
                </div>
            </div>
			   <?php  if($member['agenttime']!='1970-01-01 08:00') { ?>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">成为分销商时间</label>
                <div class="col-sm-9 col-xs-12">
                    <div class='form-control-static'><?php  echo $member['agenttime'];?></div> 
                </div>
            </div>
			   <?php  } ?>
           <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">分销商权限</label>
                <div class="col-sm-9 col-xs-12">
                     <?php if(cv('commission.agent.check')) { ?>
                    <label class="radio-inline"><input type="radio" name="adata[isagent]" value="1" <?php  if($member['isagent']==1) { ?>checked<?php  } ?>>是</label>
                    <label class="radio-inline" ><input type="radio" name="adata[isagent]" value="0" <?php  if($member['isagent']==0) { ?>checked<?php  } ?>>否</label>
                    <?php  } else { ?>
                      <input type='hidden' name='adata[isagent]' value='<?php  echo $member['isagent'];?>' />
                      <div class='form-control-static'><?php  if($member['isagent']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                    
                </div>
            </div>
       
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">审核通过</label>
                <div class="col-sm-9 col-xs-12">
                     <?php if(cv('commission.agent.check')) { ?>
                    <label class="radio-inline"><input type="radio" name="adata[status]" value="1" <?php  if($member['status']==1) { ?>checked<?php  } ?>>是</label>
                    <label class="radio-inline" ><input type="radio" name="adata[status]" value="0" <?php  if($member['status']==0) { ?>checked<?php  } ?>>否</label>
                    <input type='hidden' name='oldstatus' value="<?php  echo $member['status'];?>" />
                       <?php  } else { ?>
                      <input type='hidden' name='adata[status]' value='<?php  echo $member['status'];?>' />
                      <div class='form-control-static'><?php  if($member['status']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>

             <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">强制不自动升级</label>
                <div class="col-sm-9 col-xs-12">
                      <?php if(cv('commission.agent.edit')) { ?>
                    <label class="radio-inline" ><input type="radio" name="adata[agentnotupgrade]" value="0" <?php  if($member['agentnotupgrade']==0) { ?>checked<?php  } ?>>允许自动升级</label>
                    <label class="radio-inline"><input type="radio" name="adata[agentnotupgrade]" value="1" <?php  if($member['agentnotupgrade']==1) { ?>checked<?php  } ?>>强制不自动升级</label>
                    <span class="help-block">如果强制不自动升级，满足任何条件，此分销商的级别也不会改变</span>
                    <?php  } else { ?>
                         <input type="hidden" name="adata[agentnotupgrade]" class="form-control" value="<?php  echo $member['agentnotupgrade'];?>"  />
                      <div class='form-control-static'><?php  if($member['agentnotupgrade']==1) { ?>强制不自动升级<?php  } else { ?>允许自动升级<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>
        
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">自选商品</label>
                <div class="col-sm-9 col-xs-12">
                      <?php if(cv('commission.agent.edit')) { ?>
                    <label class="radio-inline" ><input type="radio" name="adata[agentselectgoods]" value="0" <?php  if($member['agentselectgoods']==0) { ?>checked<?php  } ?>>系统设置</label>
                    <label class="radio-inline"><input type="radio" name="adata[agentselectgoods]" value="1" <?php  if($member['agentselectgoods']==1) { ?>checked<?php  } ?>>强制禁止</label>
                    <label class="radio-inline"><input type="radio" name="adata[agentselectgoods]" value="2" <?php  if($member['agentselectgoods']==2) { ?>checked<?php  } ?>>强制开启</label>
                    <span class="help-block">系统设置： 跟随系统设置，系统关闭自选则为禁止，系统开启自选则为允许</span>
                    <span class="help-block">强制禁止： 无论系统自选商品是否关闭或开启，此分销商永不能自选商品</span>
                    <span class="help-block">强制允许： 无论系统自选商品是否关闭或开启，此分销商永可以自选商品</span>
                    <?php  } else { ?>
                      <input type="hidden" name="adata[agentselectgoods]" class="form-control" value="<?php  echo $member['agentselectgoods'];?>"  />
                      <div class='form-control-static'><?php  if($member['agentnotselectgoods']==1) { ?>
                          强制禁止 
                          <?php  } else if($member['agentselectgoods']==2) { ?>
                          强制允许
                          <?php  } else { ?>
                          <?php  if($plugin_com_set['select_goods']==1) { ?>系统允许<?php  } else { ?>系统禁止<?php  } ?>
                          <?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>
        </div>
        <?php  } ?>
		        <?php  } ?>
                <?php if(cv('member.member.edit')) { ?>
                                重置提现密码<input type="text" name="withdraw_pwd" class="form-control" value=""  /> 留空则不修改密码
                                <?php  } else { ?>
                                <div class='form-control-static'></div>
                <?php  } ?>
        <div class='panel-body'>
          <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <?php if(cv('member.member.edit')) { ?>
                  <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
	<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                  <?php  } ?>
                <input type="button" class="btn btn-default" name="submit" onclick="history.go(-1)" value="返回列表" <?php if(cv('member.member.edit')) { ?>style='margin-left:10px;'<?php  } ?> />
                </div>
            </div>
         </div>

    </div>   
	
</form>
<?php  } ?>
</div>
</div>
<script language='javascript'>
    
         function search_members() {
             if( $.trim($('#search-kwd-notice').val())==''){
                 Tip.focus('#search-kwd-notice','请输入关键词');
                 return;
             }
		$("#module-menus-notice").html("正在搜索....")
		$.get('<?php  echo $this->createPluginWebUrl('commission/agent')?>', {
			keyword: $.trim($('#search-kwd-notice').val()),'op':'query',selfid:"<?php  echo $id;?>"
		}, function(dat){
			$('#module-menus-notice').html(dat);
		});
	}
	function select_member(o) {
		$("#agentid").val(o.id);
                  $("#parentagentavatar").show();
                  $("#parentagentavatar").find('img').attr('src',o.avatar);
		$("#parentagent").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
		$("#modal-module-menus-notice .close").click();
	}
        
    </script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>