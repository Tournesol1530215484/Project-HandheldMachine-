<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealtabs', TEMPLATE_INCLUDEPATH)) : (include template('dealtabs', TEMPLATE_INCLUDEPATH));?>	 	 
<style type='text/css'>
	.trhead td {  background:#efefef;text-align: center}
	.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
	.goods_info{position:relative;width:60px;}
	.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
	.goods_info:hover {z-index:1;position:absolute;width:auto;}
	.goods_info:hover img{width:320px; height:320px;}
</style>
<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="op" value="post" />
        <div class='panel panel-default' style="border-radius: 5px;">
            <div class='panel-heading' style="background: #eee;">商家基本信息</div>
            <div class='panel-body'>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  商家名称</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="merchname" class="form-control" value="<?php  echo $su_info['merchname'];?>" autocomplete="off" />
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  联系人</label> 
						<div class="col-sm-4 col-xs-4">
							<input type="text" name="contact" class="form-control" value="<?php  echo $su_info['contact'];?>" autocomplete="off" />
						</div>


						<label class="col-xs-1 col-sm-1 col-md-1 control-label"><span style='color:red'>*</span>  联系电话</label> 
						<div class="col-sm-4 col-xs-4">
							<input type="text" name="mobile" class="form-control" value="<?php  echo $su_info['mobile'];?>" autocomplete="off" />

						</div>
					</div>
				</div>



				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label">所在地区</label>
						<div class="input-group" style="left: 15px;">
							<div class="input-group-addon" >区域选择</div>
							<?php  echo tpl_fans_form('reside',array('province' =>$su_info['province'],'city' =>$su_info['city'],'district' =>$su_info['district']));?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  详细地址</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="address" class="form-control" value="<?php  echo $su_info['address'];?>" autocomplete="off" />
						</div>
					</div>

				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  商家网址</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="merchsite" class="form-control" value="<?php  echo $su_info['merchsite'];?>" autocomplete="off" />

						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label">精准定位</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_form_field_coordinate('map',array('lng'=>$su_info['lng'],'lat'=>$su_info['lat']))?>

						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  行业大类</label>
						<div class="col-sm-9 col-xs-12">
							<select class="form-control" name="typeid">
								<?php  if(is_array($type)) { foreach($type as $k => $v) { ?>
								<option <?php  if($su_info['typeid'] == $v['id']) { ?>selected<?php  } ?> value="<?php  echo $v['id'];?>"><?php  echo $v['title'];?></option>
								<?php  } } ?>
							</select>

						</div>
					</div>
					<script>
						$('[name="typeid"]').change(function(){
							var temp=$(this).val();
							$.post(
								"<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_add',array('op'=>'gettype'))?>",
								{pid:temp},
								function (data) {
								    var op='';
									if(data.status==1){
									    for(var i  in data.result){
									        if(<?php  echo $su_info['mintype'];?> == data.result[i].id){
                                                op+='<option selected value="'+data.result[i].id+'">'+data.result[i].title+'</option>';
											}else{
                                                op+='<option value="'+data.result[i].id+'">'+data.result[i].title+'</option>';
											}
										}
									}else{
                                        op+='<option value="">'+data.result+'</option>';
									}
 									$('[name="mintype"]').html(op);
                                }
                            ,'json');
						});
                        $('[name="typeid"]').change();
					</script>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  商家简介</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_ueditor('details',$su_info['details']);?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  特色优势</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_ueditor('special',$su_info['special']);?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  所属运营商</label>
						<div class="col-sm-9 col-xs-12">
							<input class="form-control" name="operat" type="text" value="<?php  echo $su_info['operat'];?>" placeholder="请输入所属运营商">
						</div>
					</div>
				</div> 

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  运营商电话</label>
						<div class="col-sm-9 col-xs-12">
							<input class="form-control" name="operatmobile" value="<?php  echo $su_info['operatmobile'];?>" type="text" placeholder="请输入运营商电话">
						</div>
					</div>

				</div>
			</div>
		</div>


				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">基本资质</div>
					<div class='panel-body'>

						<div class="form-group">
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  企业证照</label>
								<div class="col-sm-9 col-xs-12">
									<?php if( ce('shop.goods' ,$su_info) ) { ?>
									<?php  echo tpl_form_field_multi_image('BusinessLicensePic',$su_info['BusinessLicensePic'])?>
									<span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
									<?php  } else { ?>
									<?php  if(is_array($su_info['BusinessLicensePic'])) { foreach($su_info['BusinessLicensePic'] as $p) { ?>
									<a href='<?php  echo tomedia($p)?>' target='_blank'>
										<img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
									</a>
									<?php  } } ?>
									<?php  } ?>

								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-2 control-label">执照到期时间</label>
								<?php if( ce('shop.goods' ,$su_info) ) { ?>
								<div class="col-sm-4 col-xs-6">
									<?php echo sz_tpl_form_field_date('licenseoverdue', !empty($su_info['licenseoverdue']) ? date('Y-m-d H:i',$su_info['licenseoverdue']) : date('Y-m-d H:i'), 1)?>
								</div>
								<?php  } else { ?>
								<div class="col-sm-6 col-xs-6">
									<div class='form-control-static'>
										<?php  if($su_info['istime']==1) { ?>
										<?php  echo date('Y-m-d H:i',$su_info['licenseoverdue'])?>
										<?php  } ?>
									</div>
								</div>
								<?php  } ?>

							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>  营业执照编号</label>
								<div class="col-sm-9 col-xs-12">
									<input type="text" name="businessLicenseNo" class="form-control" value="<?php  echo $su_info['businessLicenseNo'];?>" />
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
								<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>  经营场所照片</label>
								<div class="col-sm-9 col-xs-12">
									<?php if( ce('shop.goods' ,$su_info) ) { ?>
										<?php  echo tpl_form_field_multi_image('ImageDetailFile',$su_info['ImageDetailFile'])?>
										<span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
									<?php  } else { ?>
										<?php  if(is_array($su_info['ImageDetailFile'])) { foreach($su_info['ImageDetailFile'] as $p) { ?>
											<a href='<?php  echo tomedia($p)?>' target='_blank'>
												<img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
											</a>
										<?php  } } ?>
									<?php  } ?>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">审核日志</div>
						<div class='panel-body'>
							<table style="overflow: inherit;width: 100%;" class="table table-hover table-bordered">
								<thead>
									<tr class="primary">
										<th class="col-sm-3" style="width: 25%;">申请日期</th>
										<th class="col-sm-3" style="width: 25%;">审核日期</th>
										<th class="col-sm-2" style="width: 25%;">操作</th>
										<th class="col-sm-3" style="width: 25%;">备注</th>
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

                <div class="form-group"></div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-1 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
						<input type="hidden" name="uid" value="<?php  echo $su_info['uid'];?>" />  
                        <input type="button" name="submit" value="提交审核" class="btn btn-primary col-lg-2" />  
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />    
                        <span style="display:block" class="col-lg-12">(信息通过审核候才有效)</span>
                    </div>   
                </div>
            </div>

        </div>

		<div class="form-group col-sm-12">
         
        </div>
    </form>
</div>

</div>
<script language='javascript'>
 
   $(function(){
     
        $('#dataform').ajaxForm();

        $(':input[name=submit]').click(function(){
            if($(this).attr('submitting')=='1'){
                return;
            }
//           if ($(':input[name=username]').isEmpty()) {
//                Ti0p.focus($(':input[name=username]'), '请填写用户名!');
//                return;
//            }
//
//            <?php  if(empty($su_info)) { ?>
//              if ($(':input[name=password]').isEmpty()) {
//                Tip.focus($(':input[name=password]'), '请输入用户密码!');
//                return;
//            }
//            <?php  } ?>
//
//			<?php  if(empty($id)) { ?>
//            if ($(':input[name=merchname]').isEmpty()) {
//                Tip.focus($(':input[name=merchname]'), '请输入商家名!');
//                return;
//            }
//            if ($(':input[name=typeid]').isEmpty()) {
//                Tip.focus($(':input[name=typeid]'), '请输入行业大类!');
//                return;
//            }
//            if ($(':input[name=mobile]').isEmpty()) {
//                Tip.focus($(':input[name=mobile]'), '请输入联系电话!');
//                return;
//            }
//            if ($(':input[name=address]').isEmpty()) {
//                Tip.focus($(':input[name=address]'), '请输入详细地址!');
//            }
//			<?php  } ?>
			console.log('this');
            $(this).attr('submitting','1').removeClass('btn-primary');
            $('#dataform').ajaxSubmit(function(data){
                data = eval("(" +  data  +")");
                if(data.result!=1){
                      $(this).removeAttr('submitting').addClass('btn-primary');
                      Tip.select($(':input[name=username]'), data.message );
                      return;
                }
                location.href= "<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_add')?>";
            })
        })
   })
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>