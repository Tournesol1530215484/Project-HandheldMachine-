<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('mattabs', TEMPLATE_INCLUDEPATH)) : (include template('mattabs', TEMPLATE_INCLUDEPATH));?>
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
	#memberavatar{
		margin-left: 27%;	
	}
</style>	 	 
<?php  if($_GPC['op'] == 'add') { ?> 	 		
<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" />
        <input type="hidden" name="editType" value="<?php  echo $_GPC['type'];?>">
        <input type="hidden" name="ac" value="<?php  echo $_GPC['ac'];?>">

        	
        <div class='panel panel-default' style="border-radius: 5px;">
            <div class='panel-heading' style="background: #eee;">评选基本信息</div>
            <div class='panel-body'>

	            <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>评选主题	</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if( ce('match.match' ,$su_info) ) { ?>
	                        <input type="text" name="data[title]" class="form-control" value="<?php  echo $match['title'];?>" autocomplete="off" />
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>所在城市	</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('match.match' ,$su_info) ) { ?>
								<?php  if(!empty($match['province'])) { ?>
									<?php  echo tpl_fans_form('reside',array('province' =>$match['province'],'city' =>$match['city'],'district'=>$match['area']));?> 		
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>评选类别</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('match.match' ,$su_info) ) { ?>	 	
								<select class="form-control" name="data[cate]">
								<?php  if(is_array($cate)) { foreach($cate as $item) { ?>
									<option <?php  if($item['id'] == $match['cate']) { ?>selected<?php  } ?> value="<?php  echo $item['id'];?>"><?php  echo $item['title'];?></option>
								<?php  } } ?>	 			 	 
								</select>
							<?php  } else { ?>
							<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<div class="form-group">	 
					<div class="col-md-10 col-sm-10 col-xs-12" style="margin-left:-8.5%;">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label">
							<span style='color:red'>*</span>评选详情
						</label>		 	 	 	 	 		 	 	  	 		 	 	 	 	 	 	
						<div class="col-sm-9 col-xs-12" >	 		 	  
							<?php if( ce('match.match' ,$su_info) ) { ?>	 
							<?php  echo tpl_ueditor('data[content]',$match['content']);?>
							<?php  } ?>	 	 	
						</div>
					</div>
				</div>

			</div>
		</div>
				

				
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">报名项目</div>
					<div class='panel-body diyform'> 	

						<!-- --> 		

							<!-- <div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label">
										<input type="" name="diy[<?php  echo $k;?>][title]" class="form-control diyinput" placeholder="请输入表单名 如:名称">	  
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
						<?php  if(is_array($match['field'])) { foreach($match['field'] as $k => $v) { ?>	
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
						<?php  } else if($k == 'name' || $k == 'mobile' || $k == 'slogan') { ?>
							
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
									<span class="btn col-lg-6 btn-default" data-type="1">名称</span>
									<span class="btn col-lg-6 btn-default" data-type="2">手机号码</span>
									<span class="btn col-lg-6 btn-default" data-type="3">口号</span>
									<span class="btn col-lg-6 btn-default" data-type="4">简介</span>
									<span class="btn col-lg-6 btn-default" data-type="5">身高</span>
									<span class="btn col-lg-6 btn-default" data-type="6">体重</span>
									<span class="btn col-lg-6 btn-default" data-type="7">三围</span>
									<span class="btn col-lg-6 btn-default" data-type="8">年龄</span>
									<span class="btn col-lg-6 btn-default" data-type="9">性别</span>
									<span class="btn col-lg-6 btn-default" data-type="10">专业</span>
									<span class="btn col-lg-6 btn-default" data-type="11">民族</span>
									<span class="btn col-lg-6 btn-default" data-type="12">身份证号</span>
									<span class="btn col-lg-6 btn-default" data-type="13">单位/学校</span>
									<span class="btn col-lg-6 btn-default" data-type="14">职业</span>
									<div class="btn col-lg-6 btn-default" data-type="15">自定义</div>	 	
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
									'diy[name]',
									'diy[mobile]',
									'diy[slogan]',
									'diy[desc]',
									'diy[height]',
									'diy[weight]',
									'diy[measurements]',
									'diy[age]',
									'diy[gender]',	  
									'diy[major]',
									'diy[national]',
									'diy[idcard]',
									'diy[unit]',
									'diy[job]'
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
								
									 	 
								if (type==1 || type ==2 || type ==3 || type == 4) {	 		  	
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
							$('span[data-type="1"],span[data-type="2"],span[data-type="3"],span[data-type="4"]').click();	 	 		 			 
							$('input[data-title="diy[name]"],input[data-title="diy[mobile]"]').click();	 	 		 			 
							<?php  } ?>		 	 	 		 	 	 	 
							$('span[data-type="1"],span[data-type="2"],span[data-type="3"],span[data-type="4"]').unbind('click');		 	  		

							// 自定义
							var diy=0;
							$('.diy-option div').click(function(){
								var o = $(this);	 		 	 		 	 	 	 	
								var type = 15;	 	 	 		  	 	
								var type =type+diy;	 	 	 	 				 	 		 	 	 			 	 		 	 	 
								field='diy[diy'+diy+']';		 	 		 	 	  		 		  	
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
			
				

			
				<div class='panel panel-default' style="border-radius: 5px;">
					<div class='panel-heading' style="background: #eee;">评选详情</div>
						<div class='panel-body'>

							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  封面图片</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('match.match' ,$su_info) ) { ?>
										<?php  echo tpl_form_field_multi_image('data[cover]',$match['thumbs']);?>
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
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>背景音乐</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('match.match' ,$su_info) ) { ?>
										<?php  echo tpl_form_field_audio('data[bgm]',$match['bgm']);?>
										<?php  } ?>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										</div>
									</div>
								</div>
							</div>



							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  投票规则</label>	 	 	
									<div class="col-sm-4 col-xs-4">
										<select class="form-control" name="data[votetype]">
											<option <?php  if($match['votetype'] == 1) { ?>selected<?php  } ?> value="1">每个微信号只能投一次</option>
											<option <?php  if($match['votetype'] == 2) { ?>selected<?php  } ?> value="2">每个微信号按天投票</option>
										</select>
									</div>	 		

									<label class="col-xs-12 col-sm-2 col-md-2 control-label">次数:</label>
									<div class="col-sm-2 col-xs-2">
										<select class="form-control" name="data[votenum]">
											<option <?php  if($match['votenum'] == 1) { ?>selected<?php  } ?> value="1">1</option>
											<option <?php  if($match['votenum'] == 2) { ?>selected<?php  } ?> value="2">2</option>
											<option <?php  if($match['votenum'] == 5) { ?>selected<?php  } ?> value="5">5</option>
											<option <?php  if($match['votenum'] == 10) { ?>selected<?php  } ?> value="10">10</option>
										</select>
									</div>	 	
								</div>
							</div>


							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  打赏投票比列	1元:</label>	 	 	
									<div class="col-sm-4 col-xs-4">

										<select class="form-control" name="data[rewardvote]">
											<option <?php  if(empty($match['rewardvote'])) { ?>selected<?php  } ?> value="0">0</option>
											<option <?php  if($match['rewardvote'] == 1) { ?>selected<?php  } ?> value="1">1</option>
											<option <?php  if($match['rewardvote'] == 5) { ?>selected<?php  } ?> value="5">5</option>
											<option <?php  if($match['rewardvote'] == 10) { ?>selected<?php  } ?> value="10">10</option>
										</select>	 		
									</div>
									<label class="col-xs-1 col-sm-1col-md-1 control-label">票</label>	 	 	

								</div>
							</div>	 		 		 	



							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  评选时间</label>
									<div class="col-sm-9 col-xs-12">
										<?php if( ce('match.match' ,$su_info) ) { ?>
											<?php  if($_GPC['type'] ==1) { ?>
											<?php  echo tpl_form_field_daterange('time',array('starttime'=>date('Y-m-d H:i:s',$match['stime']),'endtime'=>date('Y-m-d H:i:s',$match['etime'])),1)?>
											<?php  } else { ?>	 	 	
											<?php  echo tpl_form_field_daterange('time',array('starttime'=>'','endtime'=>''),1)?>
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
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  是否审核</label>
									<div class="col-sm-9 col-xs-12">
										<?php  if($_GPC['id']) { ?>
											<label class="radio-inline">
											  <input type="radio" name="data[isAudit]" <?php  if(empty($match['isAudit'])) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 不需要审核     
											</label> 
											<label class="radio-inline">
											  <input type="radio" name="data[isAudit]" <?php  if($match['isAudit'] == 1) { ?>checked<?php  } ?> id="inlineRadio1" value="1"> 需要审核     
											</label> 
										<?php  } else { ?>
											<label class="radio-inline">
											  <input type="radio" name="data[isAudit]" checked id="inlineRadio1" value="0"> 不需要审核     
											</label> 	 	
											<label class="radio-inline">
											  <input type="radio" name="data[isAudit]" id="inlineRadio1" value="1"> 需要审核     
											</label>
										<?php  } ?>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								</div>
							</div>


							<div class="form-group">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  联系方式	</label>	 	 	
									<div class="col-sm-9 col-xs-9">
										<textarea class="form-control" name="data[contact]"><?php  echo $match['contact'];?></textarea>										
									</div>

								</div>
							</div>


					</div>
				</div>
			



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
                         <?php if( ce('match.match' ,$su_info) ) { ?>
                            <input type="hidden" name="uid" value="<?php  echo $su_info['uid'];?>" />
                        <input type="button" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('match.match.add|match.match.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>	 		

<script language='javascript'>
 
   $(function(){
 	   	
 	   	setTimeout(function(){
			$('[name="reside[district]"]').hide();
		},1);

        $('#dataform').ajaxForm();


        $('[name="reside[city]"]').change(function(){
        	setTimeout(function(){
				$('[name="reside[district]"]').hide();
			},0);	 	 					 		 			
        });	 			  	 	
        $(':input[name=submit]').click(function(){
            if($(this).attr('submitting')=='1'){
                return;
            }

//           if ($(':input[name=username]').isEmpty()) {
//                Tip.focus($(':input[name=username]'), '请填写用户名!');
//                return;
//            }
//
//            <?php  if(empty($su_info)) { ?>
//              if ($(':input[name=password]').isEmpty()) {
//                Tip.focus($(':input[name=password]'), '请输入用户密码!');
//                return;
//            }
//            <?php  } ?>
            
            if ($('[name="data[title]"]').isEmpty()) {
               Tip.focus($('[name="data[title]"]'), '请输入评选主题!');
               return;
            }

			if ($('[name="reside[province]"]').isEmpty()) {
               Tip.focus($('[name="reside[province]"]'), '请选择省份!');
               return;
            }

            if ($('[name="reside[city]"]').isEmpty()) {
               Tip.focus($('[name="reside[city]"]'), '请选择城市!');
               return;
            }

            if ($('[name="data[content]"]').isEmpty()) {
               Tip.focus($('[name="data[content]"]'), '请输入评选详情!');
               return;
            } 		
            /*if ($('[name="reside[district]"]').isEmpty()) {
               Tip.focus($('[name="reside[district]"]'), '请选择地区!');
               return;
            }*/
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

            $(this).attr('submitting','1').removeClass('btn-primary');
            $('#dataform').ajaxSubmit(function(data){
                data = eval("(" +  data  +")");
                console.log(data);
                if(data.status != 1){
                      $(this).removeAttr('submitting').addClass('btn-primary');
                      alert(data.result);
                      return;
                }else{
                      alert(data.result);	 	  		  		 
                      location.href='<?php  echo $this->createPluginWebUrl("bartact/match")?>';
                }
            })
        })

   })
  
</script>

<?php  } else if($op =='display') { ?>
<style type="text/css">
	
	.noticemodal input{
		margin-bottom: 7.5px;
	}

	.noticemodal textarea{
		margin-bottom: 7.5px;
	}	 	 	
</style>
<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>一共 <span style="color:#f00"><?php  echo $totals;?></span> 个评选</div>
	    <div class='panel-body'> 		 
	    	
	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>	 	 	
	    				<th style="width:15%;">标题</th>
	    				<th style="width:15%;">封面图</th>
	    				<th style="width:70%;">操作</th>
	    			</tr>	 		  	 	 	 	 		 		
	    		</thead>	 	 
	    		<tbody>	 	
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>	 			 		 		 		 
		    			<tr> 		 				 	 	 	 	  	 		 	  	 	 	
		    				<td><?php  echo $item['title'];?></td>
		    				<td><img src="<?php  echo tomedia($item['cover'])?>" width="50px;"></td>	 	 
		    				<td> 			 	 			 						 	 	
		    					<a class="mywidth preview" href="javascript:void(0);" data-id="<?php  echo $item['id'];?>">预览</a> |	

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'add','type'=>1,'id'=>$item['id']))?>">修改</a>
		    					|
		    					<a class="mywidth" href="javascript:void(0);" onclick="del(<?php  echo $item['id'];?>)">删除</a>| 		 			
		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'signup','id'=>$item['id']))?>">查看报名信息</a>|

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'statistics','id'=>$item['id']))?>">评选汇总</a>|

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'update','id'=>$item['id']))?>">上传选手资料</a>|

		    					<a class="mywidth team" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">评选通知</a>

		    					<?php  if(0) { ?>
		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'netmap','ac'=>'treeview','id'=>$item['id']))?>">报名网络图</a> |


		    					<a class="mywidth sgPoster" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">签到海报</a>|

		    					<a class="mywidth change" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">评选变更</a> |

		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'add','id'=>$item['id'],'ac'=>'clone'))?>">复制评选</a>|

		    					<a class="mywidth sgnotice" href="javascript:void(0);" data-info="<?php  echo $item['info'];?>" data-id="<?php  echo $item['id'];?>">签到提醒人</a> |  	 	  	 	 		  	  		 		 	
		    					<a class="mywidth" target="_blank" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'live','id'=>$item['id']))?>">现场互动</a>|

		    					<a class="mywidth team" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">群发设置</a>
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

<div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>评选预览</h3></div>
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
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>评选变更提醒</h3></div>
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
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>发送评选通知提醒</h3></div> 
			<div class="modal-body noticemodal"> 	 	 	 			 		 		 	 		 	 
				<input type="hidden" name="satid">  	
				<input type="" class="form-control" name="notice[title]" placeholder="请输入通知标题" >
				<textarea rows="5" class="form-control" name="notice[content]" placeholder="通知内容(因微信消息字数限制，最多100个字)"></textarea>
				<input class="form-control" type="" name="notice[url]" placeholder="请输入通知url" >
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

		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'preview'))?>',data,function(data){
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
		sure = confirm('确认删除此评选吗?');
		if (!sure) {
			return false;
		}
		var data={
			id:id
		};
		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'delete'))?>',data,function(data){
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

		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'notice'))?>',data,function(data){
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
		location.href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'poster','what'=>1))?>&act_id="+actid+'&poster_tpl='+posterid; 	  	 		 		  	 	
	} 		 		 	 		 	 	

	$('.team').click(function(){
		$('[name="satid"]').val($(this).data('id'));
		$('#modal-module-menussss').modal('show');
	});

	var sending=false;
	function team(){

		var titile=$('[name="notice[titile]"]').val();
		var content=$('[name="notice[content]"]').val();
		var url=$('[name="notice[url]"]').val();
		var id=$('[name="satid"]').val();	 	
		if(sending){
			return false;	  	 
		}	 		
		sending = true; 			 			 		  	 	 
		var postData={	 		
			id:id,
			content:content,
			titile:titile,
			url:url
		};	 	 			 	 		 	 			 
		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'team'))?>',postData,function(data){
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
		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'getlist'))?>',data,function(data){
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

		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'sinotice','ac'=>'add'))?>',data,function(data){
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

		$.post('<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'sinotice','ac'=>'del'))?>',data,function(data){
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
		<a class="btn btn-success" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'netmap','ac'=>'treeview','id'=>$_GPC['id']))?>">切换到树状图</a>	 
	<?php  } else { ?>	 		 	 	 			 	 	 		 			
		<a class="btn btn-warning" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'netmap','ac'=>'netmap','id'=>$_GPC['id']))?>">切换到网络图</a>
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
	    				<th style="width:10%;">评选标题</th>
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
		    						<a class="label label-success" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'audit','id'=>$item['id'],'type'=>1,'check'=>1))?>">通过</a>
		    						<a class="label label-danger" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'audit','id'=>$item['id'],'type'=>1,'check'=>2))?>">驳回</a>
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
	<div class='panel panel-info' style="border-radius: 5px;">
    <div class='panel-heading'>报名信息 共有<span style="color:#f00"><?php  echo $totals;?></span>条记录</div>
	    <div class='panel-body'> 		 
	    	
	    	<form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
	            <input type="hidden" name="c" value="site" />
	            <input type="hidden" name="a" value="entry" />
	            <input type="hidden" name="m" value="sz_yi" />	 	
	            <input type="hidden" name="do" value="plugin" />
	            <input type="hidden" name="p" value="bartact" /> 	 
	            <input type="hidden" name="op" value="<?php  echo $_GPC['op'];?>" />
	            <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" />
	            <input type="hidden" name="method" value="match" />
	            <div class="form-group">				
	            	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">	 		 
		                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-3 control-label">编号</label>	
		                <div class="col-sm-8 col-lg-9 col-xs-12">	
		                    <input type="text" class="form-control"  name="sgno" value="<?php  echo $_GPC['sgno'];?>"/> 	
		                </div>	
		            </div>	 					 		 	 			 	 	 
	            	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-3 control-label">姓名/手机号</label>	
		                <div class="col-sm-8 col-lg-9 col-xs-12">	
		                    <input type="text" class="form-control"  name="keywords" value="<?php  echo $_GPC['keywords'];?>" placeholder="可搜索姓名/手机号"/> 		 	 	
		                </div>		 	  	
		            </div>	 	 		 	
	            	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	                       <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
	                       <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
	                       <button type="submit" name="export" value="1" class="btn btn-primary">导出报名信息</button>
	               </div> 	 	 	
	            <div class="form-group"></div>
	        </form>	 	 	

	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:5%;">编号</th>
	    				<?php  if(is_array($match['field'])) { foreach($match['field'] as $k => $v) { ?>	 		
		    				<th style="width:auto;"><?php  echo $v['title'];?></th>
	    				<?php  } } ?>	 	 	
	    				<th style="width:16%;">照片</th>
	    				<th style="width:10%;">报名时间</th>
	    				<th style="width:5%;">状态</th>	  	
	    				<th style="width:10%;">操作</th>
	    			</tr>	 	 			  	
	    		</thead>	 	 		 		 
	    		<tbody>	 			 
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr> 	 	  	 	 		 	 		  		 	
		    				<td><?php  echo $item['id'];?></td>
		    				<?php  if(is_array($match['field'])) { foreach($match['field'] as $k => $v) { ?>
		    				<?php  if($item[$k]['data']) { ?>	 		
		    					<td><?php  echo $item[$k]['data'];?></td>
		    				<?php  } else { ?>	 			 			
		    					<td>暂无</td>
		    				<?php  } ?> 
		    				<?php  } } ?>	 	 	 			 	 	 		 	 		 					 		 	
		    				<td>	 		
		    					<?php  if($item['thumbs']) { ?>
		    						<?php  if(is_array($item['thumbs'])) { foreach($item['thumbs'] as $v) { ?>
		    							<img width="50px" src="<?php  echo tomedia($v)?>">
		    						<?php  } } ?>
		    					<?php  } ?>	 					
		    				</td>
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
		    					<?php  if($item['paystatus'] != 2) { ?>
		    						<a class="label label-success" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'signup','ac'=>'audit','id'=>$item['id'],'type'=>1,'check'=>1))?>">通过</a>
		    						<a class="label label-danger" href="<?php  echo $this->createPluginWebUrl('bartact/match',array('op'=>'signup','ac'=>'audit','id'=>$item['id'],'type'=>1,'check'=>2))?>">驳回</a>
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

<?php  } else if($_GPC['op'] == 'statistics') { ?>	 	 

	<div class='panel panel-info' style="border-radius: 5px;">
    <div class='panel-heading panel-info'>评选统计</div>	 		
	    <div class='panel-body'> 		 	 	 
	    	<form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
	            <input type="hidden" name="c" value="site" />
	            <input type="hidden" name="a" value="entry" />
	            <input type="hidden" name="m" value="sz_yi" />	 	
	            <input type="hidden" name="do" value="plugin" />
	            <input type="hidden" name="p" value="bartact" /> 	 	 	
	            <input type="hidden" name="op" value="<?php  echo $_GPC['op'];?>" />
	            <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" />
	            <input type="hidden" name="method" value="match" />
	            <div class="form-group">				
	            	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-3 control-label">编号</label>	
		                <div class="col-sm-8 col-lg-9 col-xs-12">	
		                    <input type="text" class="form-control"  name="sgno" value="<?php  echo $_GPC['sgno'];?>"/> 	
		                </div>	
		            </div>	 				
	            	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
		                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-3 control-label">姓名/手机号</label>	
		                <div class="col-sm-8 col-lg-9 col-xs-12">	
		                    <input type="text" class="form-control"  name="keywords" value="<?php  echo $_GPC['keywords'];?>" placeholder="可搜索姓名/手机号"/> 	
		                </div>		 			 		 
		            </div>	 	 		 	
	            	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
	                       <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
	                       <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
	                       <button type="submit" name="export" value="1" class="btn btn-primary">导出汇总信息</button>
	               </div> 	 	 	
	            <div class="form-group"></div>
	        </form>	 		

<!-------------------------------------------------------------------------------------------->
	 	
    <div class='panel-heading panel-info'>共有<span style="color:#f00"><?php  if($totals) { ?><?php  echo $totals;?><?php  } else { ?>0<?php  } ?></span>条记录</div>

	    	<table class="table table-hover table-responsive"> 		
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th>排名</a></th>
	    				<th class="orderby" data-field="number"><a href="javascript:void(0);">编号</a></th>
	    				<th>姓名</th>
	    				<th>手机</th>
	    				<th class="orderby" data-field="vote"><a href="javascript:void(0);">投票数</a></th>
	    				<th class="orderby" data-field="reward"><a href="javascript:void(0);">打赏数</a></th>
    				</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr> 	 	  	 	 		 		 	 	
		    				<td><?php  echo $item['no'];?></td>	 		 		 	 	
		    				<td><?php  echo $item['sgno'];?></td>
		    				<td><?php  echo $item['name']['data'];?></td>
		    				<td><?php  echo $item['mobile']['data'];?></td>
		    				<td><?php  echo $item['vote'];?></td>
		    				<td><?php  echo $item['reward'];?></td>
		    			</tr>
	    			<?php  } } ?>	 	 		
	    			<tr> 	 	  	 	 	
		    				<td>合计</td>
		    				<td></td>
		    				<td></td>	 	 	
		    				<td></td>
		    				<td><?php  echo $vote;?></td>
		    				<td><?php  echo $reward;?></td>
		    			</tr>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>	 		
		</div>
	</div>	 		 
</div>
<script type="text/javascript">
	 
	$('.orderby').click(function(){
		var type = $(this).data('field');
		var url='<?php  echo $this->createPluginWebUrl('bartact/match',array('id'=>$_GPC['id'],'op'=>$_GPC['op'],'keywords'=>$_GPC['keywords'],'number'=>$_GPC['number']))?>';
		url+='&orderby='+type;
		location.href=url;	 	 					 			 	 	 			
		return false;		 	 	 	 				 	
	});
</script>
<?php  } else if($_GPC['op'] == 'refund') { ?>
	<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>共有<span style="color:#f00"><?php  if($totals) { ?><?php  echo $totals;?><?php  } else { ?>0<?php  } ?></span>条记录</div>
	    <div class='panel-body'> 		 	 	 
	    	
	    	<table class="table table-hover table-responsive"> 		
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:10%;">订单号</th>
	    				<th style="width:10%;">名称</th>
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
		    				<td><?php  echo $item['slogan']['data'];?></td>
		    				<td></td>
		    				<td><?php  echo $item['need']['data'];?></td>
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['money'];?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['sgtime'])?></td>
		    				<td>
		    					<?php  if($item['channel'] == 0) { ?>
		    					评选广场
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
<?php  } else if($_GPC['op'] == 'update') { ?>

	<div class="panel panel-info">
		<div class="panel-heading">
			<?php  if($_GPC['sgid']) { ?>
				修改选手资料
			<?php  } else { ?>
				上传选手资料
			<?php  } ?>
		</div>
	<div class="panel-body"> 		 
    <form id="updateform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" /> 
        <input type="hidden" name="sgid" value="<?php  echo $_GPC['sgid'];?>" /> 
        <input type="hidden" name="ac" value="post">	 		 		 	 	  	 	 		 	 		 	 
        <input type="hidden" name="op" value="<?php  echo $_GPC['op'];?>"> 	 	  	 	 		 	 		 	 

			<div class="form-group notice"> 		 
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>选择报名者</label>
                    <input type='hidden' id='noticeopenid' name='orther[openid]' value="<?php  echo $supplierinfo['openid'];?>" />
                    <div class='input-group'> 		  	
                        <input type="text" name="member" maxlength="30" style="margin-left:15px;" value="<?php  if(!empty($member)) { ?><?php  echo $member['nickname'];?>/<?php  echo $member['realname'];?>/<?php  echo $member['mobile'];?><?php  } ?>" id="member" class="form-control" readonly />
                        <div class='input-group-btn'>	 			
                            <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();" style="margin-left:15px;">选择报名者</button>
                            <button class="btn btn-danger" type="button" onclick="$('#noticeopenid').val('');$('#member').val('');$('#memberavatar').hide()">清除选择</button>
                        </div> 	 			 	
                    </div>
                    <span id="memberavatar" class='help-block' <?php  if(empty($member)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $member['avatar'];?>"/></span>
                    <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                        <div class="modal-dialog" style='width: 920px;'>
                            <div class="modal-content"> 	 	 	
                                <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择报名者</h3></div>
                                <div class="modal-body" >
                                    <div class="row">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice" placeholder="请输入昵称/姓名/手机号" />
                                            <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                        </div>
                                    </div>
                                    <div id="module-menus-notice" style="padding-top:5px;"></div>
                                </div>
                                <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                            </div>

                        </div>
                    </div>


                </div>              
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            </div>
	        </div>
	        <script language='javascript'>
	          	function search_members() {
	                if( $.trim($('#search-kwd-notice').val())==''){
	                    $('#search-kwd-notice').attr('placeholder','请输入关键词');
	                    <!-- Tip.focus('#search-kwd-notice','请输入关键词'); -->
	                    return;
	                }
		            $("#module-menus-notice").html("正在搜索....")
		            $.get('<?php  echo $this->createWebUrl('member/query')?>', {
		             	keyword: $.trim($('#search-kwd-notice').val())
		            }, function(dat){
		             	$('#module-menus-notice').html(dat);
		            });
	          	}
				function select_member(o) {
					$("#noticeopenid").val(o.openid);
					$("#memberavatar").show();
					$("#memberavatar").find('img').attr('src',o.avatar);
					$("#member").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
					$("#modal-module-menus-notice .close").click();
				}
	        </script>

			<?php  if(is_array($match['field'])) { foreach($match['field'] as $k => $v) { ?>	 	 	 	 		
			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  <?php  echo $v['title'];?></label>
					<div class="col-sm-9 col-xs-12">
						<input type="hidden" name="data[<?php  echo $k;?>][title]" value="<?php  echo $v['title'];?>">
						<input type="hidden" name="data[<?php  echo $k;?>][field]" value="<?php  echo $k;?>">
						<input type="" class="form-control" name="data[<?php  echo $k;?>][data]" value="">
					</div> 	 		 		 				 	 		 	 	 
				</div> 	 	 	 	
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div> 		 
			</div> 				 		  		
			<?php  } } ?>	 		 	 			 		 		

			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  选手图片</label>
					<div class="col-sm-9 col-xs-12">
						<input type="hidden" id="thumbtip">
						<?php if( ce('match.match' ,$sg) ) { ?>	 		
						<?php  echo tpl_form_field_multi_image('thumbs',$sg['thumbs']);?>
						<span class="help-block">最多上传三张图片 </span>
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
					<label class="col-xs-12 col-sm-3 col-md-3 control-label">图片描述</label>
					<div class="col-sm-9 col-xs-12">
						<textarea class="form-control" name="picdesc"></textarea>
					</div> 	 		 		 				 			
				</div> 	 	 	 		 			 					
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div> 		 
			</div> 	 

             <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                     <?php if( ce('match.match' ,$su_info) ) { ?>
                    <input type="hidden" name="uid" value="<?php  echo $su_info['uid'];?>" />
                    <input type="button" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <?php  } ?>
                   <input type="button" name="back" onclick='history.back()' <?php if(cv('match.match.add|match.match.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                </div>
            </div>


				</div>	 	 
        	</div> 		 
    </form>
</div>
</div>


<script language='javascript'>
 
   $(function(){
 	   		 				 

        $('#updateform').ajaxForm({type:'post'});	

        $(':input[name=submit]').click(function(){
            // if($(this).attr('submitting')=='1'){
            //     return;
            // }	 		


           	thumbNum=$('[name="thumbs[]"]').size();	 		 
           	
           	if (thumbNum == 0) {
           		alert('请至少需要上传一张图片');
                return;	 		
           	}	 	 		 	 		 		

           	if (thumbNum > 3) {
           		alert('最多选择三张图片!');
                return;	 		
           	}


          	if ($(':input[name=member]').isEmpty()) {
               Tip.focus($(':input[name=member]'), '请选择报名者!');
               return;
           	}



            <?php  if(is_array($match['field'])) { foreach($match['field'] as $k => $v) { ?>	 	 	 	 		
            	<?php  if($v['must'] == 1) { ?>
					if ($('[name="data[<?php  echo $k;?>][data]"]').isEmpty()) {
		               Tip.focus($('[name="data[<?php  echo $k;?>][data]"]'), '请输入<?php  echo $v['title'];?>!');
		               return;
		            }	
	            <?php  } ?>		 		 	 		 		
			<?php  } } ?>	 	 	
			 		 		

            $(this).attr('submitting','1').removeClass('btn-primary');
            $('#updateform').ajaxSubmit(function(data){
                data = eval("(" +  data  +")");
                console.log(data);	 				 	 		 		
                alert(data.result);	 			 		 
                if(data.status != 1){
                      $(this).removeAttr('submitting').addClass('btn-primary');
                      return;
                }else{
                      location.href='<?php  echo $this->createPluginWebUrl("bartact/match")?>';
                }
            })

            return false;		//ajaxSubmit 必须返回false 否则提交两次	 	 
        })	 		

   })
  
</script>


<?php  } ?>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>	 	