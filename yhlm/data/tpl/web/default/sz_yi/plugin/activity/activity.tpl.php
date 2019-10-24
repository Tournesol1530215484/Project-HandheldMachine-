<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" href="http://jhzh66.com/addons/sz_yi/static/js/dist/area/Area.xml?v=3"></script>
<style type='text/css'>
	.trhead td {  background:#efefef;text-align: center}
	.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
	.goods_info{position:relative;width:60px;}
	.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
	.goods_info:hover {z-index:1;position:absolute;width:auto;}
	.goods_info:hover img{width:320px; height:320px;}
	.mywidth {width: 80px;display: inline-block;text-overflow: ellipsis;}
	.poster {width:46%;height: 90%;float: left;list-style: none;}
	.poster img{width: 262px;height:440px;}
	.checked {border: 2px solid #f00;}
	.pad {padding:2px;}
	.diyinput {margin-top: -7.5px;}
	#menuContent ul{height: auto;}
	#menuContent li > span:nth-child(2){
		display: none;
	}
	#menuContent a span:nth-child(1){
		display: none;
	}
	#chart-container{overflow: auto;}
</style>
<?php  if($_GPC['op'] == 'add') { ?>
<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" />
        <input type="hidden" name="editType" value="<?php  echo $_GPC['type'];?>">
        <input type="hidden" name="ac" value="<?php  echo $_GPC['ac'];?>">
        <?php  if($_GPC['type'] ==1 || empty($_GPC['type'])) { ?>
        <div class='panel panel-default' style="border-radius: 5px;">
            <div class='panel-heading' style="background: #eee;">活动基本信息</div>
            <div class='panel-body'>

	            <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>活动主题</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if( ce('activity.activity' ,$su_info) ) { ?>
	                        <input type="text" name="data[title]" class="form-control" value="<?php  echo $activity['title'];?>" autocomplete="off" />
	                        <?php  } else { ?>
	                        <div class='form-control-static'>********</div>
	                        <?php  } ?>
	                    </div>
	                </div>
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                </div>
                </div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  活动时间</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.activity' ,$su_info) ) { ?>
								<?php  if($_GPC['type'] ==1) { ?>
								<?php  echo tpl_form_field_daterange('time',array('starttime'=>date('Y-m-d H:i:s',$activity['stime']),'endtime'=>date('Y-m-d H:i:s',$activity['etime'])),1)?>
								<?php  } else { ?>
								<?php  echo tpl_form_field_daterange('time',array('starttime'=>'','endtime'=>''),1)?>
								<?php  } ?>
							<div class="checkbox">
							  <label>
							    <input type="checkbox" name="data[afterTheStart]" value="1" <?php  if($activity['afterTheStart']) { ?>checked<?php  } ?>>
							    开始后不允许报名
							  </label>
							</div>
							<?php  } else { ?>
							<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>活动地点</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.activity' ,$su_info) ) { ?>
								<?php  if(!empty($activity['province'])) { ?>
									<?php  echo tpl_fans_form('reside',array('province' =>$activity['province'],'city' =>$activity['city'],'district'=>$activity['area']));?>
								<?php  } else { ?>
									<?php  echo tpl_fans_form('reside',array('province' =>'','city' =>'','district' =>''));?>
								<?php  } ?>
							<?php  } else { ?>
							<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  地址</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.activity' ,$su_info) ) { ?>
							<input type="text" name="data[address]" class="form-control" value="<?php  echo $activity['address'];?>" autocomplete="off" />
							<?php  } else { ?>
							<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>


				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  活动类别</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.activity' ,$su_info) ) { ?>
							<select class="form-control" name="data[cate]">
								<?php  if(is_array($cate)) { foreach($cate as $k => $v) { ?>
								<option <?php  if($activity['cate'] == $v['id']) { ?>selected<?php  } ?> value="<?php  echo $v['id'];?>"><?php  echo $v['title'];?></option>
								<?php  } } ?>
							</select>
							<?php  } else { ?>
							<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
				</div>


				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  活动摘要</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.activity' ,$su_info) ) { ?>
								<input type="text" name="data[desc]" class="form-control"  value="<?php  echo $activity['desc'];?>">
							<?php  } else { ?>
								<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  活动费用</label>
						<div class="col-sm-9 col-xs-12">
							<?php  if($_GPC['id']) { ?>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return hidewindow();" <?php  if($activity['cost'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 免费
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return showwindow();" <?php  if($activity['cost'] == 1) { ?>checked<?php  } ?> id="inlineRadio2" value="1"> 在线支付
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return hidewindow();" <?php  if($activity['cost'] == 3) { ?>checked<?php  } ?> id="inlineRadio4" value="3"> 现场收费
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return hidewindow();" <?php  if($activity['cost'] == 2) { ?>checked<?php  } ?> id="inlineRadio3" value="2"> AA制
								</label>
							<?php  } else { ?>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return hidewindow();" checked id="inlineRadio1" value="0"> 免费
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return showwindow();" id="inlineRadio2" value="1"> 在线支付
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return hidewindow();" id="inlineRadio4" value="3"> 现场收费
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[cost]" onclick="return hidewindow();" id="inlineRadio3" value="2"> AA制
								</label>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>


				<div class="form-group payitemWindow" style="display: none;">
					<div class="col-lg-6 col-md-10 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>费用项目</label>
						<div class="col-sm-9 col-xs-12">
							<table class="table table-hover table-responsive">
								<thead class="navbar-inner">
									<tr>
										<th>收费项目</th>
										<th width="30%;">人数限制（-1为不限制）</th>
										<th width="20%;">价格（元）</th>
										<th width="15%;">操作</th>
									</tr>
								</thead>
								<tbody class="payitem">
									<?php  if(is_array($activity['payitem'])) { foreach($activity['payitem'] as $k => $v) { ?>
										<tr>
											<td><input class="form-control" type="" name="payitem[<?php  echo $k;?>][title]" placeholder="项目名称" value="<?php  echo $v['title'];?>"></td>
											<td><input class="form-control" type="" name="payitem[<?php  echo $k;?>][limit]" placeholder="人数限制" value="<?php  echo $v['limit'];?>"></td>
											<td><input class="form-control" type="" name="payitem[<?php  echo $k;?>][money]" placeholder="价格" value="<?php  echo $v['money'];?>"></td>
											<td>
												<!-- <span class="fa fa-gear" style="float: left;"></span> -->
												<span class="fa fa-trash" data-id="$v['id']"><input type="hidden" value="<?php  echo $v['id'];?>" name="payitem[<?php  echo $k;?>][id]"></span>
											</td>
										</tr>
									<?php  } } ?>
								</tbody>
							</table>
							<div class="col-sm-9 col-xs-12">
							<div class="btn btn-primary" onclick="return additem();">添加项目</div>
								<br>
								<br>
								<label class="radio-inline">
								  <input type="radio" name="data[paytype]" <?php  if($activity['paytype'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 现场收费
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[paytype]" <?php  if($activity['paytype'] == 1) { ?>checked<?php  } ?> id="inlineRadio2" value="1"> 在线支付
								</label>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<script type="text/javascript">
					var itemnum=0;

					function showwindow(){
						if (itemnum == 0) {
							additem();
						}

						$('.payitemWindow').show();

					};

					function hidewindow(){
						$('.payitemWindow').hide();
					};

					<?php  if($activity['cost'] == 1) { ?>
						$('.payitemWindow').show();
						itemnum=<?php  echo count($activity['payitem'])?>;
					<?php  } ?>

					$('table ').on('click','.fa-trash',function(){
						var re=confirm('确定删除该项目吗?');
						re && $(this).parent().parent().remove();
					});

					function additem(){
						var html='';
						html+='<tr><input type="hidden" name="payitem['+itemnum+'][id]" placeholder="项目名称">';
						html+='<td><input class="form-control" type="" name="payitem['+itemnum+'][title]" placeholder="项目名称"></td>';
						html+='<td><input class="form-control" type="" name="payitem['+itemnum+'][limit]" placeholder="人数限制" value="-1"></td>';
						html+='<td><input class="form-control" type="" name="payitem['+itemnum+'][money]" placeholder="价格" value="0"></td>';
						html+='<td>';
						// html+='<span class="fa fa-gear" style="float:left;"></span>';
						html+='<span class="fa fa-trash"data-id=""></span>';
						html+='</td>';
						html+='</tr>';

						$('.payitem').append(html);
						itemnum++;

					}
				</script>


			</div>
		</div>

				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">发布人信息</div>
					<div class='panel-body'>

						<div class="form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  发布机构</label>
								<div class="col-sm-9 col-xs-12">
									<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<input type="text" name="relOrg" value="<?php  echo $info['orgName'];?>" disabled class="form-control">
									<?php  } else { ?>
										<div class='form-control-static'>********</div>
									<?php  } ?>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  联系人</label>
								<div class="col-sm-9 col-xs-12">
									<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<input type="text" name="contactOrg" value="<?php  echo $info['realname'];?>" disabled class="form-control">
									<?php  } else { ?>
										<div class='form-control-static'>********</div>
									<?php  } ?>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  联系电话</label>
								<div class="col-sm-9 col-xs-12">
									<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<input type="text" name="mobileOrg" value="<?php  echo $info['mobile'];?>" disabled class="form-control">
									<?php  } else { ?>
										<div class='form-control-static'>********</div>
									<?php  } ?>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>

						<?php  if(false) { ?>
						<div class="form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  机构简介</label>
								<div class="col-sm-9 col-xs-12">
									<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<textarea class="form-control" name="descOrg" disabled><?php  echo $info['orgDesc'];?></textarea>
									<?php  } else { ?>
										<div class='form-control-static'>********</div>
									<?php  } ?>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<?php  } ?>
					</div>
				</div>
				<?php  } ?>

				<?php  if($_GPC['type'] != 2) { ?>
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">报名项目</div>
					<div class='panel-body diyform'>

						<!-- -->

							<!-- <div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label">
										<input type="" name="diy[<?php  echo $k;?>][title]" class="form-control diyinput" placeholder="请输入表单名 如:姓名">
									</label>
									<div class="col-sm-9 col-xs-12">
										<input type="" class="form-control" name="diy[<?php  echo $k;?>][field]" placeholder="请输入字段名:name">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;">
									<input type="checkbox" class="" name="must">
										必填
									</label>
									<i class="fa fa-remove"></i>
								</div>
							</div>  -->
						<?php  if(true) { ?>
						<?php  if(is_array($activity['field'])) { foreach($activity['field'] as $k => $v) { ?>
						<?php  if(is_numeric($k)) { ?>
							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><?php  echo $v['title'];?></label>
									<div class="col-sm-9 col-xs-12">
										<input type="" class="form-control divipt" value="<?php  echo $v['title'];?>" name="" placeholder="请输入字段名 如:昵称">
										<input type="hidden" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][title]" value="<?php  echo $v['title'];?>">
										<input type="hidden" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][num]" value="<?php  echo $v['num'];?>">
										<input type="hidden" class="must" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][must]" value="<?php  echo $v['must'];?>">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;">
										<input type="checkbox" <?php  if($v['must'] ==1) { ?>checked<?php  } ?> data-title="diy[<?php  echo $k;?>]" class="" name="must"> 必填
									</label>
									<i class="fa fa-remove"></i>
								</div>
							</div>
						<?php  } else if($k == 'realname' || $k == 'mobile' || $k == 'unit') { ?>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><?php  echo $v['title'];?></label>
									<div class="col-sm-9 col-xs-12">
										<input type="" class="form-control" name="">
								  		<input type="hidden" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][title]" value="<?php  echo $v['title'];?>">
								  		<input type="hidden" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][num]" value="<?php  echo $v['num'];?>">
								  		<input type="hidden" class="must" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][must]" value="<?php  echo $v['must'];?>">
								  	</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;">
										<input type="checkbox" <?php  if($v['must'] ==1) { ?>checked<?php  } ?> data-title="diy[<?php  echo $k;?>]" class="" name="must"> 必填
									</label>
									<i class="fa"></i>
								</div>
							</div>
						<?php  } else { ?>
							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label">'+o.html()+'</label>
									<div class="col-sm-9 col-xs-12">
										<input type="" class="form-control" name="">
										<input type="hidden" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][title]" value="<?php  echo $v['title'];?>">
								  		<input type="hidden" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][num]" value="<?php  echo $v['num'];?>">
								  		<input type="hidden" class="must" data-type="<?php  echo $v['num'];?>" name="diy[<?php  echo $k;?>][must]" value="<?php  echo $v['must'];?>">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;">
										<input type="checkbox" <?php  if($v['must'] ==1) { ?>checked<?php  } ?> data-title="diy[<?php  echo $k;?>]" class="" name="must"> 必填
									</label>
									<i class="fa fa-remove"></i>
								</div>
							</div>
						<?php  } ?>
						<?php  } } ?>
						<?php  } ?>

					</div>

					<div class='panel-body'>

						<div class="form-group">
								<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
								<div class="col-sm-9 col-xs-12 diy-option" style="border:1px solid #ccc;padding-left:4%;padding-bottom:5%;">
									<h1 class="text-center">常用项目</h1>
									<span class="btn col-lg-6 btn-default" data-type="1">姓名</span>
									<span class="btn col-lg-6 btn-default" data-type="16">公司产品</span>
									<span class="btn col-lg-6 btn-default" data-type="2">手机号码</span>
									<span class="btn col-lg-6 btn-default" data-type="17">我有什么资源</span>
									<span class="btn col-lg-6 btn-default" data-type="3">单位</span>
									<span class="btn col-lg-6 btn-default" data-type="18">我要什么资源</span>
									<span class="btn col-lg-6 btn-default" data-type="4">微信号</span>
									<span class="btn col-lg-6 btn-default" data-type="5">年龄</span>
									<span class="btn col-lg-6 btn-default" data-type="6">行业</span>
									<span class="btn col-lg-6 btn-default" data-type="7">性别</span>
									<span class="btn col-lg-6 btn-default" data-type="8">职位</span>
									<span class="btn col-lg-6 btn-default" data-type="9">学历</span>
									<span class="btn col-lg-6 btn-default" data-type="10">兴趣爱好</span>
									<span class="btn col-lg-6 btn-default" data-type="11">住址</span>
									<span class="btn col-lg-6 btn-default" data-type="12">血型</span>
									<span class="btn col-lg-6 btn-default" data-type="13">月收入</span>
									<span class="btn col-lg-6 btn-default" data-type="14">报名摘要</span>
									<span class="btn col-lg-6 btn-default" data-type="15">婚姻状况</span>
									<div class="btn col-lg-6 btn-default" data-type="19">自定义</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
						<script type="text/javascript">


							$('body').on('click','[name="must"]',function(){
								var type = $(this).data('type');
								var title = $(this).data('title');
								if ($(this).context.checked) {
									$(this).parent().parent().prev().find('.must').val(1);
								}else{
									$(this).parent().parent().prev().find('.must').val(0);
								}
							});


							$('body').on('click','.fa-remove',function(){
								$(this).parent().parent().remove();
							});

							$('.diy-option span').click(function(){
								var o = $(this);
								var type = o.data('type');
								var exists=false;

								var option=[
									null,
									'diy[realname]',
									'diy[mobile]',
									'diy[unit]',
									'diy[wechat]',
									'diy[age]',
									'diy[industry]',
									'diy[gender]',
									'diy[job]',
									'diy[edubg]',
									'diy[interest]',
									'diy[addr]',
									'diy[blood]',
									'diy[income]',
									'diy[signUpDesc]',
									'diy[marriage]',
									'diy[goods]',
									'diy[supplier]',
									'diy[need]'
								];

								$('.diyform input[type="hidden"]').each(function(i,e){
									if (type == $(e).data('type')) {
										exists=true;
									}
								});

								if (exists) {
									alert('已经有该表单，不可重复添加');
									return;
								}

								var header='<div class="form-group"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="col-xs-12 col-sm-3 col-md-3 control-label">'+o.html()+'</label><div class="col-sm-9 col-xs-12"><input type="" class="form-control" name="">';


								if (type==1 || type ==2 || type ==3) {
									var footer='</div></div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;"><input type="checkbox" data-title="'+option[type]+'" class="" name="must"> 必填</label><i class="fa"></i></div></div>';
								}else{
									var footer='</div></div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;"><input type="checkbox" data-title="'+option[type]+'" class="" name="must"> 必填</label><i class="fa fa-remove"></i></div></div>';
								}

								var html='';
								html+='<input type="hidden" data-type="'+type+'" name="'+option[type]+'[title]" value="'+o.html()+'">';
								html+='<input type="hidden" data-type="'+type+'" name="'+option[type]+'[num]" value="'+type+'">';
								html+='<input type="hidden" class="must" data-type="'+type+'" name="'+option[type]+'[must]" value="0">';

								var real=header+html+footer;

								$('.diyform').append(real);
							});
							<?php  if(empty($_GPC['id'])) { ?>
							$('span[data-type="1"],span[data-type="2"],span[data-type="3"]').click();
							$('input[data-title="diy[realname]"],input[data-title="diy[mobile]"]').click();
							<?php  } ?>
							$('span[data-type="1"],span[data-type="2"],span[data-type="3"]').unbind('click');

							// 自定义
							var diy=0;
							$('.diy-option div').click(function(){
								var o = $(this);
								var type = 19;
								var type =type+diy;
								field='diy['+diy+']';

								var header='<div class="form-group"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="col-xs-12 col-sm-3 col-md-3 control-label">'+''+'</label><div class="col-sm-9 col-xs-12"><input type="" class="form-control divipt" name="" placeholder="请输入字段名 如:昵称">';
								var footer='</div></div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label class="col-xs-12 col-sm-2 col-md-2 control-label" style="margin-left: -75px;"><input type="checkbox" data-title="'+field+'" class="" name="must"> 必填</label><i class="fa fa-remove"></i></div></div>';

								var html='';
								html+='<input type="hidden" data-type="'+type+'" name="'+field+'[title]" value="'+o.html()+'">';
								html+='<input type="hidden" data-type="'+type+'" name="'+field+'[num]" value="'+type+'">';
								html+='<input type="hidden" data-type="'+type+'" name="'+field+'[must]" value="0" class="must">';

								var real=header+html+footer;
								diy++;
								$('.diyform').append(real);
							});

							$('body').on('keyup','.divipt',function(){
								var o = $(this);
								val=o.val();
								o.next('input').val(val);
								o.parent().prev().text(val);
							});

						</script>
					</div>
				</div>
				<?php  } ?>


				<?php  if($_GPC['type'] == 1 || empty($_GPC['type'])) { ?>
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;"></div>
						<div class='panel-body'>
							<?php  if(false) { ?>
							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  邀约模式</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[joinModel]" <?php  if(empty($activity['joinModel'])) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 报名模式
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[joinModel]" <?php  if($activity['joinModel'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 邀约模式
											</label>
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[joinModel]" checked  id="inlineRadio1" value="0"> 报名模式
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[joinModel]"  id="inlineRadio1" value="1"> 邀约模式
											</label>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  是否隐藏名单	</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[hideList]" <?php  if($activity['hideList'] == 0) { ?>checked<?php  } ?> checked id="inlineRadio1" value="0"> 否
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[hideList]" <?php  if($activity['hideList'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 是
											</label>
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[hideList]" checked id="inlineRadio1" value="0"> 否
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[hideList]" id="inlineRadio1" value="1"> 是
											</label>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  是否公开</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[public]" <?php  if($activity['public'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 公开
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[public]" <?php  if($activity['public'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 不公开
											</label>
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[public]" checked id="inlineRadio1" value="0"> 公开
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[public]" id="inlineRadio1" value="1"> 不公开
											</label>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>
							<?php  } ?>
							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  团队分销模式</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[teamModel]" <?php  if($activity['teamModel'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 否
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[teamModel]" <?php  if($activity['teamModel'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 是
											</label>
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[teamModel]" checked id="inlineRadio1" value="0"> 否
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[teamModel]"  id="inlineRadio1" value="1"> 是
											</label>
										<?php  } ?>
									</div>
										<div class="col-md-2 col-lg-5 font-set col-lg-offset-3 teamModelSet" style="margin-top:15px;<?php  if(empty($activity['teamModel'])) { ?>display: none;<?php  } ?>">
								          <div class="input-group">
								            <div class="input-group-addon">1级转介绍比例&nbsp;&nbsp;&nbsp;&nbsp;</div>
								            <input type="number" name="orther[agent1]" class="form-control" value="">
								            <div class="input-group-addon">%&nbsp;</div>
								          </div>
								          <div class="input-group">
								            <div class="input-group-addon">2级转介绍比例&nbsp;&nbsp;&nbsp;&nbsp;</div>
								            <input type="number" name="orther[agent2]" class="form-control" value="">
								            <div class="input-group-addon">%&nbsp;</div>
								          </div>
								        </div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  是否审核</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[isAudit]" <?php  if(empty($activity['isAudit'])) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 不需要审核
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[isAudit]" <?php  if($activity['isAudit'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 需要审核
											</label>
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[isAudit]" checked id="inlineRadio1" value="0"> 不需要审核
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[isAudit]" id="inlineRadio1" value="1"> 需要审核
											</label>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>




							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  是否允许退票	</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[refund]" <?php  if($activity['refund'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 不允许
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[refund]" <?php  if($activity['refund'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 允许
											</label>
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="orther[refund]" id="inlineRadio1" value="0"> 不允许
											</label>
											<label class="radio-inline">
											  <input type="radio" name="orther[refund]" checked id="inlineRadio1" value="1"> 允许
											</label>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>




							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  活动图标</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<?php  echo tpl_form_field_image('orther[icon]',$activity['icon']);?>
										<span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
										<?php  } else { ?>
											<img src="">
										<?php  } ?>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  微信二维码</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<?php  echo tpl_form_field_image('orther[wechatCode]',$activity['wechatCode']);?>
										<span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
										<?php  } else { ?>
											<img src="">
										<?php  } ?>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										</div>
									</div>
								</div>
							</div>

					</div>
				</div>
				<?php  } ?>

				<?php  if($_GPC['type'] == 2 || empty($_GPC['type'])) { ?>
				<div class='panel panel-default' style="border-radius: 5px;">
    		        <div class='panel-heading' style="background: #eee;">活动详情</div>
	        		    <div class='panel-body'>

	        		    	<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>背景音乐</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<?php  echo tpl_form_field_audio('data[bgm]',$activity['bgm']);?>
										<?php  } ?>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										</div>
									</div>
								</div>
							</div>

	        		    	<div class="form-group">
								<div class="col-md-10 col-sm-10 col-xs-12" style="margin-left:-8.5%;">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label">
										<span style='color:red'>*</span>活动详情
									</label>
									<div class="col-sm-9 col-xs-12" >
										<?php if( ce('activity.activity' ,$su_info) ) { ?>
										<?php  echo tpl_ueditor('data[content]',$activity['content']);?>
										<?php  } ?>
									</div>
								</div>
							</div>

            			</div>
					</div>
				</div>
				<?php  } ?>




				<!-- 审核表 -->
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">审核日志</div>
						<div class='panel-body'>
							<table style="overflow: auto;width: 100%;" class="table table-hover table-bordered">
								<thead>
									<tr>
										<th class="col-sm-3" >申请日期</th>
										<th class="col-sm-3" >审核日期</th>
										<th class="col-sm-3" >操作</th>
										<th class="col-sm-3" >备注</th>
									</tr>
								</thead>
								<tbody>
								<?php  if(is_array($audit_log)) { foreach($audit_log as $key => $val) { ?>
									<tr>
										<td class="col-sm-3"><?php  echo date('Y-m-d H:i:s',$val['sub_time'])?></td>
										<td class="col-sm-3">
											<?php  if(empty($val['audit_time'])) { ?>
												待审核
											<?php  } else { ?>
												<?php  echo date('Y-m-d H:i:s',$val['audit_time'])?>
											<?php  } ?>
										</td>
										<td class="col-sm-2">
											<?php  if($val['status'] == 0) { ?>
											审核中
											<?php  } else if($val['status'] == 1) { ?>
											审核通过
											<?php  } else if($val['status'] == 2 ) { ?>
											审核失败
											<?php  } ?>
										</td>
										<td class="col-sm-3"><?php  echo $val['note'];?></td>
									</tr>
								<?php  } } ?>
								</tbody>
							</table>
					</div>
				</div>



                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if( ce('activity.activity' ,$su_info) ) { ?>
                            <input type="hidden" name="uid" value="<?php  echo $su_info['uid'];?>" />
                        <input type="button" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('activity.activity.add|activity.activity.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>
<?php  } else if($op =='display') { ?>

<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>我发布的一共 <span style="color:#f00"><?php  echo $totals;?></span> 件</div>
	    <div class='panel-body'>

	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:auto;">标题</th>
	    				<th style="width:auto;">机构名称</th>
	    				<th style="width:auto;">浏览数</th>
	    				<th style="width:auto;">转发数</th>
	    				<th style="width:auto;">报名数</th>
	    				<th style="width:auto;">发布时间</th>
	    				<th style="width:auto;">开始时间</th>
	    				<th style="width:auto;">结束时间</th>
	    				<th style="width:19%;">操作</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr>
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['relOrg'];?></td>
		    				<td><?php  echo $item['browse'];?></td>
		    				<td><?php  echo $item['forwarding'];?></td>
		    				<td><?php  echo $item['signup'];?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['ctime'])?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['stime'])?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['etime'])?></td>
		    				<td>
		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'add','type'=>1,'id'=>$item['id']))?>">修改标题</a>|

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'add','type'=>2,'id'=>$item['id']))?>"> 修改正文</a>|

		    					<a class="mywidth preview" href="javascript:void(0);" data-id="<?php  echo $item['id'];?>">预览</a><br/>

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'signup','id'=>$item['id']))?>">报名名单</a>|

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'signin','id'=>$item['id']))?>">签到名单</a>|

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'netmap','ac'=>'treeview','id'=>$item['id']))?>">报名网络图</a><br/>

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'refund','id'=>$item['id']))?>">退款信息</a>|

		    					<a class="mywidth sgPoster" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">签到海报</a>|

		    					<a class="mywidth change" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">活动变更</a><br/>

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'add','id'=>$item['id'],'ac'=>'clone'))?>">复制活动</a>|

		    					<a class="mywidth" href="javascript:void(0);" onclick="del(<?php  echo $item['id'];?>)">删除</a>|
		    					<a class="mywidth sgnotice" href="javascript:void(0);" data-info="<?php  echo $item['info'];?>" data-id="<?php  echo $item['id'];?>">签到提醒人</a><br/>
		    					<a class="mywidth" target="_blank" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'live','id'=>$item['id']))?>">现场互动</a>|

		    					<a class="mywidth team" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">群发设置</a>
		    				</td>
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>
		</div>
	</div>
</div>

<div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>活动预览</h3></div>
			<div class="modal-body">
				<input type="hidden" name="atid">
				<input type="text" name="mobile" placeholder="请输入手机号" style="display:inline-block;width:82%;" class="form-control">
				<div class="btn btn-primary" onclick="send();" style="float: right;width: 18%;">发送</div>
			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>


<div id="modal-module-menuss" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>活动变更提醒</h3></div>
			<div class="modal-body">
				<input type="hidden" name="atids">
				<textarea class="form-control" name="content" rows="10" placeholder="请输入变更内容"></textarea>
				<div class="btn btn-primary col-lg-4 " onclick="return notice();" style="margin-top: 15px;" >发送提醒</div>
			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>


<div id="modal-module-menusss" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 650px;margin-top:2%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h1>选择签到海报</h1></div>
			<div class="modal-body">
				<ul>
					<?php  if(is_array($sgPoster)) { foreach($sgPoster as $index2 => $item) { ?>
					<li class="poster pad" data-id="<?php  echo $item['id'];?>" <?php  if($index2==0) { ?>style="margin-right:15px;"<?php  } ?>>
						<div style="width: 100%;height: 100%;">
							<img src="<?php  echo tomedia($item['thumb'])?>">
						</div>
					</li>
					<?php  } } ?>
					<br clear="both">
					<input type="hidden" name="posterid">
					<input type="hidden" name="actid">
				</ul>
				<div class="btn btn-primary col-lg-12 " onclick="return creatPoster();" style="margin-top: 15px;" >确定</div>
			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>


<div id="modal-module-menussss" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>群发消息</h3></div>
			<div class="modal-body">
				<input type="hidden" name="satid">
				<div class="form-group">点击发送后将开始群发消息</div>
				<div class="btn btn-primary" onclick="return team();" style="float: right;width: 18%;">发送</div>
			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>


<div id="modal-module-notice-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>签到提醒人</h3></div>
			<div class="modal-body">
				<input type="hidden" name="sgacid">
				<input type="text" name="sgmobile" placeholder="请输入手机号" style="display:inline-block;width:82%;" class="form-control">
				<div class="btn btn-primary" onclick="return sgnotice();" style="float: right;width: 18%;">设置</div>
				<table class="table">
					<thead class="table-hover">
						<tr>
							<th>手机号码</th>
							<th>操作</th>
						</tr>
					</thead>

					<tbody class="table-responsive sgtable">

					</tbody>

				</table>
			</div>
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>


<!-- ### -->

<script type="text/javascript">

	function send(){
		var data={
			mobile:$('[name="mobile"]').val(),
			atid:$('[name="atid"]').val()
		};

		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'preview'))?>',data,function(data){
			alert(data.result);
			if (data.status == 1) {
				$('[name="atid"]').val(null);
				$('[name="mobile"]').val(null);
			}else{
				$('[name="mobile"]').val(null);
			}
			$('#modal-module-menus').modal('hide');
		},'json');
	}

	function del(id){
		var data={
			id:id
		};
		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'delete'))?>',data,function(data){
			alert(data.result);
			if (data.status == 1) {
				location.reload();
			}
		},'json');
	}

	$('.preview').click(function(){
		$('[name="atid"]').val($(this).data('id'));
		$('#modal-module-menus').modal('show');
	});

	$('.preview').click(function(){
		$('[name="atid"]').val($(this).data('id'));
		$('#modal-module-menus').modal('show');
	});




	$('.change').click(function(){
		$('[name="atids"]').val($(this).data('id'));
		$('#modal-module-menuss').modal('show');
	});

	function notice(){
		var data={
			content:$('[name="content"]').val(),
			atid:$('[name="atids"]').val()
		};

		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'notice'))?>',data,function(data){
			alert(data.result);
			if (data.status == 1) {
				$('[name="atids"]').val(null);
				$('[name="content"]').val(null);
			}else{
				$('[name="content"]').val(null);
			}
			$('#modal-module-menuss').modal('hide');
		},'json');
	}


	$('.sgPoster').click(function(){
		$('[name="actid"]').val($(this).data('id'));
		$('#modal-module-menusss').modal('show');
	});


	$('.poster').click(function(){
		$('.poster').each(function(i,e){
			if ($(e).hasClass('pad')) {
			}else{
				$(e).removeClass('checked').addClass('pad');
			}
		});
		$(this).removeClass('pad').addClass('checked');
		$('[name="posterid"]').val($(this).data('id'));
	});


	function creatPoster(){
		var	posterid=$('[name="posterid"]').val();
		var actid=$('[name="actid"]').val();

		if (posterid=='') {
			alert('请选择海报');
			return false;
		}
		location.href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'poster','what'=>1))?>&act_id="+actid+'&poster_tpl='+posterid;
	}

	$('.team').click(function(){
		$('[name="satid"]').val($(this).data('id'));
		$('#modal-module-menussss').modal('show');
	});

	var sending=false;
	function team(){

		if(sending){
			return false;
		}
		sending = true;

		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'team','id'=>$_GPC['id']))?>',function(data){
			alert(data.result);
			if (data.status == 1) {
				$('#modal-module-menussss').modal('hide');
				setTimeout(function(){
					location.reload();
				},250);
				sending=false;
			}
		},'json');
	}

	$('.sgnotice').click(function(){
		$('[name="sgacid"]').val($(this).data('id'));
		var data={
			id:$(this).data('id')
		};
		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'getlist'))?>',data,function(data){
	 		$('.sgtable').html(data.result);
			$('#modal-module-notice-menus').modal('show');
		},'json')

	});

	var setmobile= false;
	function sgnotice(){

		if(setmobile){
			return false;
		}
		setmobile = true;

		data={
			id:$('[name="sgacid"]').val(),
			mobile:$('[name="sgmobile"]').val()
		};

		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'sinotice','ac'=>'add'))?>',data,function(data){
			alert(data.result);
			if (data.status == 1) {
				$('#modal-module-notice-menus').modal('hide');
				setTimeout(function(){
					location.reload();
				},250);
				return false;
			}
				setmobile=false;
		},'json');
	}

	$('body').on('click','.sgdel',function(){
		var sure=confirm('确认删除此提醒人号码吗?');

		if (!sure) {
			return false;
		}

		var data={
			id:$(this).data('id'),
			mobile:$(this).data('mobile')
		};

		$.post('<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'sinotice','ac'=>'del'))?>',data,function(data){
			alert(data.result);
			if (data.status == 1) {
				$('#modal-module-notice-menus').modal('hide');
				setTimeout(function(){
					location.reload();
				},250);

			}

		},'json');
	});
</script>

<?php  } else if($_GPC['op'] == 'netmap') { ?>

<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading text-center' style="font-size: 24px;">
    		<?php  echo $act['title'];?>
    	<span style="color:#f00;font-size: 24px;">
    		<?php  if($_GPC['ac'] == 'netmap') { ?>
				报名网络图
			<?php  } else { ?>
				报名树状图
			<?php  } ?>
    	</span>
    </div>
	<?php  if($_GPC['ac'] == 'netmap') { ?>
		<a class="btn btn-success" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'netmap','ac'=>'treeview','id'=>$_GPC['id']))?>">切换到树状图</a>
	<?php  } else { ?>
		<a class="btn btn-warning" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'netmap','ac'=>'netmap','id'=>$_GPC['id']))?>">切换到网络图</a>
	<?php  } ?>
	    <div class='panel-body'>


	    		<?php  if($_GPC['ac'] == 'netmap') { ?>
	    			<div id="chart-container"></div>
	    		<?php  } else { ?>
		    		<div id="menuContent" class="menuContent" style="width:95%;z-index:10;">
			        	<ul id="treeDemo" class="ztree" style="margin-top:0; width:100%;">

			        	</ul>
	    			</div>
    			<?php  } ?>
		</div>
	</div>
</div>

<?php  if($_GPC['ac'] == 'netmap') { ?>
	<link rel="stylesheet" type="text/css" href="http://jhzh66.com/addons/sz_yi/static/css/jquery.orgchart.css">
	<script type="text/javascript" src="http://jhzh66.com/addons/sz_yi/static/js/jorg/html2canvas.js"></script>
	<script type="text/javascript" src="http://jhzh66.com/addons/sz_yi/static/js/jorg/jquery.orgchart.js"></script>
	<script type="text/javascript">
		// sample of core source code
		// var datascource = {
		// 	'name'		: 'hsxx',
		// 	'title'		: '',
		// 	'relationship': { 'children_num': 3 },
		//     'children'	: [
		//     { 'name'    : 'Bo Miao',
		//       'title' : '',
		//       'relationship': {
		//     	'children_num': 0, 'parent_num': 0,'sibling_num': 0}
		// 	},
		//     { 'name': 'Su Miao', 'title': '',
		//       'relationship': { 'children_num': 1, 'parent_num': 0,'sibling_num': 0 },
		//       'children': [
		//         { 'name': 'Tie Hua', 'title': 'senior engineer',
		//           'relationship': { 'children_num': 0, 'parent_num': 0,'sibling_num': 0 }},
		//         { 'name': 'Hei Hei', 'title': '',
		//           'relationship': { 'children_num': 0, 'parent_num': 0,'sibling_num': 0 }}
		//       ]
		//     },
		//     { 'name': 'Yu Jie', 'title': '',
		//       'relationship': { 'children_num': 0, 'parent_num': 0,'sibling_num': 0 }}
		//   ]
		// };

		 var datascource  = <?php  echo json_encode($org)?>;
		 console.log(datascource);
		$('#chart-container').orgchart({
		  'data' : datascource,
		  'depth': 2,
		  'nodeTitle': 'name',
		  'nodeContent': 'title'
		});
	</script>


<?php  } else { ?>
<link rel="stylesheet" type="text/css" href="http://jhzh66.com/addons/sz_yi/static/css/myztree.css">
<script type="text/javascript" src="http://jhzh66.com/addons/sz_yi/static/js/jquery.ztree.all-3.5.js"></script>
<script type="text/javascript">
	var setting2 = {
    check: {
        enable: true,
        chkStyle: "radio",
        radioType: "all"
    },
    view: {
        dblClickExpand: false
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        onClick: onClickNode,
        onCheck: onCheck
    }
};

// var zNodes = [
//     { id: 1, pId: 0, name: "父节点1", open: false },
//     { id: 11, pId: 1, name: "父节点11" },
//     { id: 111, pId: 11, name: "叶子节点111" },
//     { id: 112, pId: 11, name: "叶子节点112" },
//     { id: 113, pId: 11, name: "叶子节点113" },
//     { id: 114, pId: 11, name: "叶子节点114" },
//     { id: 12, pId: 1, name: "父节点12" },
//     { id: 121, pId: 12, name: "叶子节点121" },
//     { id: 122, pId: 12, name: "叶子节点122" },
//     { id: 123, pId: 12, name: "叶子节点123" },
//     { id: 124, pId: 12, name: "叶子节点124" },
//     { id: 13, pId: 2, name: "父节点13", isParent: true },
//     { id: 2, pId: 0, name: "父节点2",isParent: true },
//     { id: 21, pId: 2, name: "父节点21", open: false },
//     { id: 211, pId: 21, name: "叶子节点211" },
//     { id: 212, pId: 21, name: "叶子节点212" },
//     { id: 213, pId: 21, name: "叶子节点213" },
//     { id: 214, pId: 21, name: "叶子节点214" },
//     { id: 22, pId: 2, name: "父节点22" },
//     { id: 221, pId: 22, name: "叶子节点221" },
//     { id: 222, pId: 22, name: "叶子节点222" },
//     { id: 223, pId: 22, name: "叶子节点223" },
//     { id: 224, pId: 22, name: "叶子节点224" },
//     { id: 23, pId: 2, name: "父节点23" },
//     { id: 231, pId: 23, name: "叶子节点231" },
//     { id: 232, pId: 23, name: "叶子节点232" },
//     { id: 233, pId: 23, name: "叶子节点233" },
//     { id: 234, pId: 23, name: "叶子节点234" },
//     { id: 3, pId: 0, name: "父节点3", isParent: true }
// ];

var zNodes = <?php  echo json_encode($tree);?>;

//初始化
$(document).ready(function () {
    $.fn.zTree.init($("#treeDemo"), setting2, zNodes);
});

//显示菜单
function showMenu() {
    $("#menuContent").css({ left: "15px", top: "34px" }).slideDown("fast");

    $("body").bind("mousedown", onBodyDown);
}
//隐藏菜单
function hideMenu() {
    // $("#menuContent").fadeOut("fast");
    // $("body").unbind("mousedown", onBodyDown);
}
function onBodyDown(event) {
    if (!(event.target.id == "menuBtn" || event.target.id == "menuContent" || event.target.id == "txt_ztree" || $(event.target).parents("#menuContent").length > 0)) {
        hideMenu();
    }
}

//节点点击事件
function onClickNode(e, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    zTree.checkNode(treeNode, !treeNode.checked, null, true);
    return false;
}

//节点选择事件
function onCheck(e, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
    nodes = zTree.getCheckedNodes(true),
    v = "";
    var parentid = "";
    var parentlevel = "";
    for (var i = 0, l = nodes.length; i < l; i++) {
        v += nodes[i].name + ",";
        parentid += nodes[i].id + ",";
        parentlevel += nodes[i].menu_level + ",";
    }
    if (v.length > 0) {
        v = v.substring(0, v.length - 1);
        parentid = parentid.substring(0, parentid.length - 1);
        parentlevel = parentlevel.substring(0, parentlevel.length - 1);
    }
    else {
        return;
    }

    hideMenu();
}
</script>
<?php  } ?>

<?php  } else if($_GPC['op'] == 'comment') { ?>


<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>共有<span style="color:#f00"><?php  echo $totals;?></span>条评论</div>
	    <div class='panel-body'>

	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:10%;">活动标题</th>
	    				<th style="width:10%;">评论者</th>
	    				<th style="width:auto;">内容</th>
	    				<th style="width:10%;">发布时间</th>
	    				<th style="width:10%;">当前状态</th>
	    				<th style="width:7%;">操作</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr>
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['nickname'];?></td>
		    				<td><?php  echo $item['content'];?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['ctime'])?></td>
		    				<td>
		    					<?php  if($item['status'] == 0) { ?>
		    					未审核
		    					<?php  } else if($item['status'] == 1) { ?>
		    					通过
		    					<?php  } else if($item['status'] == 2) { ?>
		    					驳回
		    					<?php  } ?>
		    				</td>
		    				<td>
		    					<?php  if($item['status'] == 0) { ?>
		    						<a class="label label-success" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'audit','id'=>$item['id'],'type'=>1,'check'=>1))?>">通过</a>
		    						<a class="label label-danger" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'audit','id'=>$item['id'],'type'=>1,'check'=>2))?>">驳回</a>
		    					<?php  } ?>
		    				</td>
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>
		</div>
	</div>
</div>


<?php  } else if($_GPC['op'] == 'signup') { ?>
	<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>共有<span style="color:#f00"><?php  echo $totals;?></span>条记录</div>
	    <div class='panel-body'>

	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:5%;">姓名</th>
	    				<th style="width:10%;">联系方式</th>
	    				<th style="width:auto;">单位</th>
	    				<th style="width:auto;">公司产品</th>
	    				<th style="width:auto;">我要换什么产品/资源</th>
	    				<th style="width:10%;">报名项目</th>
	    				<th style="width:4%;">报名费</th>
	    				<th style="width:5%;">审核</th>
	    				<th style="width:4%;">支付</th>
	    				<th style="width:10%;">报名时间</th>
	    				<th style="width:5%;">状态</th>
	    				<th style="width:4%;">签到</th>
	    				<th style="width:7%;">报名渠道</th>
	    				<th style="width:7%;">操作</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr>
		    				<td><?php  echo $item['realname']['data'];?></td>
		    				<td><?php  echo $item['mobile']['data'];?></td>
		    				<td><?php  echo $item['unit']['data'];?></td>
		    				<td><?php  echo $item['goods']['data'];?></td>
		    				<td><?php  echo $item['need']['data'];?></td>
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['money'];?></td>
		    				<td>
		    					<?php  if($item['status'] == 0) { ?>
		    					未审核
		    					<?php  } else if($item['status'] == 1) { ?>
		    					通过
		    					<?php  } else if($item['status'] == 2) { ?>
		    					驳回
		    					<?php  } ?>
		    				</td>
		    				<td>
		    					<?php  if($item['paystatus'] > 0) { ?>
									<?php  echo $item['money'];?>
		    					<?php  } else { ?>
		    						0.00
		    					<?php  } ?>
		    				</td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['ctime'])?></td>
		    				<td>
		    					<?php  if($item['paystatus'] == 0) { ?>
		    						未付款
		    					<?php  } else if($item['paystatus'] == 1) { ?>
		    						已付款
		    					<?php  } else if($item['paystatus'] == 2) { ?>
		    						已退款
		    					<?php  } ?>
		    				</td>
		    				<td>
		    					<?php  if($item['signin'] > 0) { ?>
		    						已签
		    					<?php  } else { ?>
		    						未签
		    					<?php  } ?>
		    				</td>
		    				<td>
		    					<?php  if($item['channel'] == 0) { ?>
		    						活动广场
		    					<?php  } else { ?>
		    						转发
		    					<?php  } ?>
		    				</td>
		    				<td>
		    					<?php  if($item['paystatus'] != 2) { ?>
		    						<a class="label label-success" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'signup','ac'=>'audit','id'=>$item['id'],'type'=>1,'check'=>1))?>">通过</a>
		    						<a class="label label-danger" href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'signup','ac'=>'audit','id'=>$item['id'],'type'=>1,'check'=>2))?>">驳回</a>
		    					<?php  } ?>
		    				</td>
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>
		</div>
	</div>
</div>

<?php  } else if($_GPC['op'] == 'signin') { ?>

	<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>共有<span style="color:#f00"><?php  if($totals) { ?><?php  echo $totals;?><?php  } else { ?>0<?php  } ?></span>条记录</div>
	    <div class='panel-body'>

	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:10%;">签到序号</th>
	    				<th style="width:10%;">姓名</th>
	    				<th style="width:10%;">联系方式</th>
	    				<th style="width:auto;">单位</th>
	    				<th style="width:10%;">公司产品</th>
	    				<th style="width:10%;">我要换什么产品/资源</th>
	    				<th style="width:10%;">报名项目</th>
	    				<th style="width:10%;">报名费</th>
	    				<th style="width:10%;">签到时间</th>
	    				<th style="width:10%;">报名渠道</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr>
		    				<td><?php  echo $item['signin'];?>/<?php  echo $item['signin'];?></td>
		    				<td><?php  echo $item['realname']['data'];?></td>
		    				<td><?php  echo $item['mobile']['data'];?></td>
		    				<td><?php  echo $item['unit']['data'];?></td>
		    				<td></td>
		    				<td></td>
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['money'];?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['sgtime'])?></td>
		    				<td>
		    					<?php  if($item['channel'] == 0) { ?>
		    					手机
		    					<?php  } else { ?>
		    					其他
		    					<?php  } ?>
		    				</td>

		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>
		</div>
	</div>
</div>
<?php  } else if($_GPC['op'] == 'refund') { ?>
	<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>共有<span style="color:#f00"><?php  if($totals) { ?><?php  echo $totals;?><?php  } else { ?>0<?php  } ?></span>条记录</div>
	    <div class='panel-body'>

	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:10%;">订单号</th>
	    				<th style="width:10%;">姓名</th>
	    				<th style="width:10%;">联系方式</th>
	    				<th style="width:auto;">单位</th>
	    				<th style="width:10%;">公司产品</th>
	    				<th style="width:10%;">我要换什么产品/资源</th>
	    				<th style="width:10%;">报名项目</th>
	    				<th style="width:10%;">报名费</th>
	    				<th style="width:10%;">签到时间</th>
	    				<th style="width:10%;">报名渠道</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index1 => $item) { ?>
		    			<tr>
		    				<td><?php  echo $item['id'];?></td>
		    				<td><?php  echo $item['realname']['data'];?></td>
		    				<td><?php  echo $item['mobile']['data'];?></td>
		    				<td><?php  echo $item['unit']['data'];?></td>
		    				<td></td>
		    				<td><?php  echo $item['need']['data'];?></td>
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['money'];?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['sgtime'])?></td>
		    				<td>
		    					<?php  if($item['channel'] == 0) { ?>
		    					活动广场
		    					<?php  } else { ?>
		    					转发
		    					<?php  } ?>
		    				</td>
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>
		</div>
	</div>
</div>
<?php  } else if($_GPC['op'] == 'live') { ?>

	<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" />
        <input type="hidden" name="op" value="add">
        <input type="hidden" name="ac" value="<?php  echo $_GPC['op'];?>">

				<?php  if($_GPC['type'] == 1 || empty($_GPC['type'])) { ?>
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;"></div>
						<div class='panel-body'>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  活动主题</label>
									<div class="col-sm-9 col-xs-12">
										<input type="" class="form-control" name="data[theme]" value="<?php  echo $act['theme'];?>">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>


							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  大屏幕生效时间	</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('activity.activity' ,$su_info) ) { ?>
											<?php  echo tpl_form_field_daterange('data', array('stime'=>date('Y-m-d H:i',$act['screen_stime']),'etime'=>date('Y-m-d H:i',$act['screen_etime'])),1)?>
											</label>
										<?php  } else { ?>
											<div class='form-control-static'>********</div>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  是否报名才能参加	</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('activity.activity' ,$su_info) ) { ?>
											<label class="radio-inline">
											  <input type="radio" name="data[afterTheSignup]" <?php  