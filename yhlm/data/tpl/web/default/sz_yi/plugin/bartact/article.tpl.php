<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('arttabs', TEMPLATE_INCLUDEPATH)) : (include template('arttabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" href="http://jhzh66.com/addons/sz_yi/static/js/dist/area/Area.xml?v=3"></script>
<style type='text/css'>
	.trhead td {  background:#efefef;text-align: center}
	.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
	.goods_info{position:relative;width:60px;}
	.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
	.goods_info:hover {z-index:1;position:absolute;width:auto;}
	.goods_info:hover img{width:320px; height:320px;} 		
	#modal-module-qrcode img{left:50%;margin-left:-130px;position: relative;}
	.have-margin-top{margin-top: 7.5px;}	 	   
</style>
<?php  if($_GPC['op'] == 'post') { ?> 	 		 
<div class="main rightlist"> 		
    <form id="dataform" action="" method="post" class="form1-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>" /> 
        <input type="hidden" name="ac" value="<?php  echo $_GPC['ac'];?>" />	
        <div class='panel panel-default' style="border-radius: 5px;">
            <div class='panel-heading' style="background: #eee;">活动基本信息</div>
            <div class='panel-body'>

	            <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>标题</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if( ce('activity.article' ,$su_info) ) { ?>
	                        <input type="text" name="data[title]" class="form-control" value="<?php  echo $article['title'];?>" autocomplete="off" />
	                        <?php  } else { ?>
	                        <div class='form-control-static'>********</div>
	                        <?php  } ?>
	                    </div>
	                </div>
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                </div>
	                <br clear="both">
                </div>

                <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>摘要</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if( ce('activity.article' ,$su_info) ) { ?>
	                        <input type="text" name="data[desc]" class="form-control" value="<?php  echo $article['desc'];?>" autocomplete="off" />
	                        <?php  } else { ?>
	                        <div class='form-control-static'>********</div>
	                        <?php  } ?>
	                    </div>
	                </div>
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                </div>
	                 <br clear="both">
                </div>

                <div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>活动地点</label>
						<div class="col-sm-9 col-xs-12">  		
							<?php if( ce('activity.activity' ,$su_info) ) { ?>
							<?php  echo tpl_fans_form('reside',array('province' =>$article['province'],'city' =>$article['city'],'district' =>$article['area']));?>
							<?php  } else { ?>	 	
							<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
					<br clear="both">
				</div>

                <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>发布机构</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if( ce('activity.article' ,$su_info) ) { ?>
	                        <input type="text" name="data[relOrg]" disabled class="form-control" value="<?php  echo $muser['orgName'];?>" autocomplete="off" />
	                        <?php  } else { ?>
	                        <div class='form-control-static'>********</div>
	                        <?php  } ?>
	                    </div>
	                </div>
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                </div>
	                <br clear="both">
                </div>

                 <div class="form-group">
                	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>机构介绍</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if( ce('activity.article' ,$su_info) ) { ?>
	                        <textarea class="form-control" disabled name="data[descOrg]"><?php  echo $muser['orgDesc'];?></textarea>
	                        <?php  } else { ?> 		
	                        <div class='form-control-static'>********</div>
	                        <?php  } ?>
	                    </div>
	                </div>
	                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                </div>
	                <br clear="both">
                </div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>文章类别	</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.article' ,$su_info) ) { ?>
								<label class="radio-inline">
								  <input type="radio" name="data[type]" <?php  if($article['type'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 综合     
								</label> 		 		 
								<label class="radio-inline">
								  <input type="radio" name="data[type]" <?php  if($article['type'] == 1) { ?>checked<?php  } ?> id="inlineRadio2" value="1"> 营销     
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[type]" <?php  if($article['type'] == 2) { ?>checked<?php  } ?> id="inlineRadio3" value="2"> 活动报道 
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[type]" <?php  if($article['type'] == 3) { ?>checked<?php  } ?> id="inlineRadio4" value="3"> 媒体报道
								</label>
							<?php  } else { ?>
								<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
					<br clear="both">
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>团队模式</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.article' ,$su_info) ) { ?>
								<label class="radio-inline">
								  <input type="radio" name="data[teamModel]" <?php  if($article['teamModel'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 否     
								</label> 		 
								<label class="radio-inline">
								  <input type="radio" name="data[teamModel]" <?php  if($article['teamModel'] == 1) { ?>checked<?php  } ?> id="inlineRadio2" value="1"> 是     
								</label>
							<?php  } else { ?>
								<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
					<br clear="both">
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 背景音乐</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_form_field_audio('data[bgm]',$article['bgm']);?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
					<br clear="both">
				</div>


				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 内容</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_ueditor('data[content]',$article['content']);?>
						</div> 		
					</div> 		
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

				<input type="hidden" name="status" value="0">

			</div>
			<br clear="both">
		</div>
                <div class="form-group"></div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if( ce('activity.article' ,$su_info) ) { ?>
	                        <input type="hidden" name="uid" value="<?php  echo $su_info['uid'];?>" />
	                        <div class="btn btn-success col-lg-1 col-lg-offset-1 draft">保存草稿</div>
	                        <input type="button" name="submit" value="提交" class="btn btn-primary col-lg-1 col-lg-offset-1" />
	                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('activity.article.add|activity.article.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default col-lg-offset-1" />
                    </div>
                </div>
            </div>

        </div>

		<div class="form-group col-sm-12">
         	
        </div>
    </form>
</div>

<?php  } else if($op == 'display') { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="bartact" />
            <input type="hidden" name="method" value="article" />
            <input type="hidden" name="op" value="display" />
            <div class="form-group row">	 	 	 	 
                <div class="col-xs-12 col-sm-4 col-lg-2 sx">
                    <div class="input-group">
                        <div class="input-group-addon">文章标题</div>
                        <input type="text" class="form-control"  name="title" value="<?php  echo $_GPC['title'];?>"/>
                    </div>	 	 	 	 	 
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-2 sx">
                    <div class="input-group">
                        <div class="input-group-addon">活动商信息</div>
                        <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder='可搜索昵称/名称/手机号'/>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-2 sx">
                    <div class="input-group">
                        <div class="input-group-addon">推广</div>
                        <select name='is_top' class='form-control'>	 	
                            <option value=''></option>	  	 		 	 	
                            <option value='4' <?php  if($_GPC['is_top']=='4') { ?>selected<?php  } ?>>王顶</option>
                            <option value='3' <?php  if($_GPC['is_top']=='3') { ?>selected<?php  } ?>>置顶</option>
                            <option value='2' <?php  if($_GPC['is_top']=='2') { ?>selected<?php  } ?>>优先</option>
                            <option value='1' <?php  if($_GPC['is_top']=='1') { ?>selected<?php  } ?>>刷新</option>
                        </select>	 	
                    </div>	 	 	 	 		 	 	 		 	 	 	 	 		 	 
                </div>	 	
                <div class="col-xs-12 col-sm-4 col-lg-2 sx" >
                    <div class="input-group">	 	 
                        <div class="input-group-addon">	 	 
                        等级
                    	</div>	 	 		 	 	 		 	 
                        <select name='level' class='form-control'> 		 	  	 		 	 	 
                            <option value=""></option>	 				 		  		 	 
                            <option value='1' <?php  if($_GPC['level']==1) { ?>selected<?php  } ?>>免费用户</option>
                            <option value='2' <?php  if($_GPC['level']==2) { ?>selected<?php  } ?>>初级会员</option>
                            <option value='3' <?php  if($_GPC['level']==3) { ?>selected<?php  } ?>>中级会员</option>
                            <option value='4' <?php  if($_GPC['level']==4) { ?>selected<?php  } ?>>高级会员</option>
                        </select>	 		 	 	 			 	 				 			  	 	  	
                    </div>	 		 			 		 
                </div>	 	
                <div class="col-xs-12 col-sm-4 col-lg-4 sx">
                    <div class="input-group">
                        <div class="input-group-addon">区域选择</div>
                        <?php  echo tpl_fans_form('reside',array('province' => $_GPC['reside']['province'],'city' => $_GPC['reside']['city'],'district' => $_GPC['reside']['district']));?>
                    </div>
                </div>	 	 
                <div class="col-xs-12 col-sm-6 col-lg-2 sx have-margin-top">
                    <div class="input-group">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php if(cv('bonus.agent.export')) { ?>
                        <!-- <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button> -->
                        <?php  } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>我发布的一共 <span style="color:#f00"><?php  echo $totals;?></span> 篇</div>
	    <div class='panel-body'> 		 
	    	
	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner">
	    			<tr>
	    				<th style="width:auto;">标题</th>	 
	    				<th style="width:auto;">机构</th>	 	
	    				<th style="width:auto;">地区</th>	 
	    				<th style="width:5%;">推广</th>		 
	    				<th style="width:5%;">浏览数</th>	  
	    				<th style="width:5%;">点赞数</th>
	    				<th style="width:5%;">分享数</th>
	    				<th style="width:10%;">创建时间</th>
	    				<th style="width:auto;">操作</th>	 	
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr> 	 	  	 	 	
		    				<td><?php  echo $item['title'];?></td>
		    				<td><?php  echo $item['orgName'];?></td>
		    				<td>	  
		    					<?php  echo $item['province'];?><?php  echo $item['city'];?><?php  echo $item['area'];?>
		    				</td>
		    				<td>	 	 	 	 	 	 
		    					<?php  if($item['is_top'] == 1) { ?>
		    						优先
		    					<?php  } else if($item['is_top'] == 2) { ?>
		    						王顶
		    					<?php  } else if($item['is_top'] == 3) { ?>
		    						置顶
		    					<?php  } else if($item['is_top'] == 4) { ?>
		    						刷新
		    					<?php  } else { ?>
		    						无
		    					<?php  } ?>
		    				</td>
		    				<td><?php  echo $item['browse'];?></td>
		    				<td><?php  echo $item['like'];?></td>
		    				<td><?php  echo $item['forwarding'];?></td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['ctime'])?></td>	 	 
		    				<td> 	 	 	
		    					<a class="mywidth preview" href="javascript:void(0);" data-id="<?php  echo $item['id'];?>">预览</a> 
		    					
		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'post','id'=>$item['id']))?>">修改</a>|
 					 				 	 				 
		    					<a class="mywidth" href="javascript:void(0);" onclick="del(<?php  echo $item['id'];?>)">删除</a>| 		 			
		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'post','id'=>$item['id'],'ac'=>'clone'))?>">复制文章</a>|<br/>
		    					 		
		    					<a class="mywidth team" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">群发设置</a>
		    							 	 	
		    					<a class="mywidth qrcode" data-src="<?php  echo $item['qrcode'];?>" href="javascript:void(0);"> 二维码</a>|

		    					<a class="mywidth toPromote" data-id="<?php  echo $item['id'];?>" href="javascript:void(0);">推广</a>

		    				</td> 					 	 	 			 	 	 			 		
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>
	    	<?php  echo $pager;?>
		</div>	 	 
	</div>
</div>
</div>

<div id="modal-module-to-promote" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>活动推广</h3></div>
			<div class="modal-body">
				<input type="hidden" name="setatid">		 		 	 		 	
				<select name="topromote" class="form-control" style="margin-bottom: 15px;">
					<option>请选择推广分类</option>
					<option value="1">刷新</option>	 			  	  	
					<option value="2">优先</option>	 	
					<option value="3">置顶</option>	 
					<option value="4">王顶</option>	 	 	
				</select>	 	 
				<font class="text-info"></font>
				<div class="btn btn-primary" onclick="set();" style="float: right;width: 18%;">设置</div>	 	
			</div> 	 	 		 	 	 	 			 	 	 	 	 	 	 
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>	 	
	</div>	 	
</div>
<script type="text/javascript">
	$('.toPromote').click(function(){
		$('[name="setatid"]').val($(this).data('id'));	 	 	 	 	 	
		$('#modal-module-to-promote').modal('show');	 	
	});
	$('select[name="topromote"]').change(function(){
		var str='';
		if ($(this).val() == 2) {	 	
			str='优先，会员发布的信息永远排前面';
		}else if ($(this).val() == 4) {
			str='王顶，永远在地区排第一位';
		}else if ($(this).val() == 3) {	 	 		 	 	 	 	 	 	 	
			str='置顶，排名在2-5位';
		}else if ($(this).val() == 1) {	 	 	 			 	 	
			str='刷新，刷一次等新发布活动，靠在免费活动之前';
		}	 	 
			$(this).next('font').text(str);		  	 
	});
	var seted=false;
	function set(){
		if (seted) {
			return;
		}
		seted = true;
		var data={
			id:$('[name="setatid"]').val(),
			type:2,	 	 	 	
			is_top:$('[name="topromote"').val()
		};

		var url='<?php  echo $this->createPluginWebUrl('bartact/activity',array('ac'=>'settop'))?>';
		$.post(url,data,function(data){	 
			alert(data.result);	 	 
			if (data.status == 1) {	 
				location.reload();	  
				return;
			}
			seted=false;			
		},'json');
	}
</script>



<?php  } else if($op=='draft') { ?>

	<div class='panel panel-default' style="border-radius: 5px;">
    <div class='panel-heading'>草稿箱一共 <span style="color:#f00"><?php  echo $totals;?></span>篇</div>
	    <div class='panel-body'> 	 	  			 
	    	
	    	<table class="table table-hover table-responsive">
	    		<thead class="navbar-inner"> 	
	    			<tr>
	    				<th style="width:auto;">标题</th>
	    				<th style="width:auto;">文章类型</th>
	    				<th style="width:auto;">所属分类</th>
	    				<th style="width:auto;">创建时间</th>
	    				<th style="width:auto;">操作</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    			<?php  if(is_array($list)) { foreach($list as $index => $item) { ?>
		    			<tr> 	 	  	 	 	
		    				<td><?php  echo $item['title'];?></td>
		    				<td>原创</td>
		    				<td>
		    					<?php  if($item['cate'] == 0) { ?>
		    						综合
		    					<?php  } else if($item['cate'] == 1) { ?>
		    						营销  
		    					<?php  } else if($item['cate'] == 2) { ?>
		    						 活动报道
		    					<?php  } else if($item['cate'] == 3) { ?>
		    						媒体报道
		    					<?php  } ?>
		    				</td>
		    				<td><?php  echo date('Y-m-d H:i:s',$item['ctime'])?></td>
		    				<td> 		
		    					<a class="mywidth release" href="javascript:void(0);" data-id="<?php  echo $item['id'];?>">发布</a> 

		    					<a class="mywidth preview" href="javascript:void(0);" data-id="<?php  echo $item['id'];?>">预览</a> 
		    					
		    					<a class="mywidth" href="<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'post','id'=>$item['id']))?>">修改</a>|

		    					<a class="mywidth" href="javascript:void(0);" onclick="del(<?php  echo $item['id'];?>)">删除</a>|
		    				</td>
		    			</tr>
	    			<?php  } } ?>
	    		</tbody>
	    	</table>

		</div>
	</div>
</div>

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
		    					<a class="label label-success" href="<?php  echo $this->createPluginWebUrl('bartact/activity',array('op'=>'audit','id'=>$item['id'],'type'=>2,'check'=>1))?>">通过</a>
		    					<a class="label label-danger" href="<?php  echo $this->createPluginWebUrl('bartact/activity',array('op'=>'audit','id'=>$item['id'],'type'=>2,'check'=>2))?>">驳回</a>
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

<?php  } ?>
	

</div>
<script language='javascript'>
 	function del(id){
			var data={
				id:id
			};
			$.post('<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'delete'))?>',data,function(data){
				alert(data.result); 		 	
				if (data.status == 1) {
					location.reload(); 	 	
				} 
			},'json');
		}

    

     	

     	$('.draft').click(function(){
     		$('[name="status"]').val(2);
     		$(':input[name=submit]').click();
     	});


        $(':input[name=submit]').click(function(){
            if($(this).attr('submitting')=='1'){
                return;
            }

            if ($('[name="data[title]"]').isEmpty()) {
               Tip.focus($('[name="data[title]"]'), '请填写标题!');
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

            if ($('[name="reside[district]"]').isEmpty()) {
               Tip.focus($('[name="reside[district]"]'), '请选择地区!');
               return;
            }

            if ($('[name="data[desc]"]').isEmpty()) {
               Tip.focus($('[name="data[desc]"]'), '请输入摘要!');
               return;
            }

           // if ($('[name="data[content]"]').isEmpty()) {
           //     Tip.focus($('[name="data[bgm]"]'), '请输入填写内容!');
           //     return;
           // }


			var data={
				title:$('[name="data[title]"]').val(),
				desc:$('[name="data[desc]"]').val(),
				relOrg:$('[name="data[relOrg]"]').val(),
				descOrg:$('[name="data[descOrg]"]').val(),
				type:$('input[name="data[type]"]:checked').val(),
				teamModel:$('input[name="data[teamModel]"]:checked').val(),
				bgm:$('[name="data[bgm]"]').val(), 	 	 	
				status:$('input[name="data[status]"]').val(), 	 	 	
				token:$('input[name="token"]').val(), 	 	 	
				id:$('input[name="id"]').val(), 	 	 	
				content:$('[name="data[content]"]').val()
			};

            $(this).attr('submitting','1').removeClass('btn-primary');

            var ajax={
            	type:'post',
            	dataType:'json', 	 	 	 	 	 	 	 	 	 
            	url:"<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'post'))?>",
				success:function(re){
					alert(re.result.msg);
					if (re.status ==1) { 		 	
						location.href=re.result.url;
					}
					return; 	 	  	 	 
				},
				error:function(re){
                    $(this).removeAttr('submitting').addClass('btn-primary');
                    alert('提交失败!');
                    return;
				} 			 
            }; 	 	
            $('#dataform').ajaxSubmit(ajax);
			$('#dataform').resetForm();            

            return false; 
        })
  
</script>

<div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>文章预览</h3></div>
			<div class="modal-body">
				<input type="hidden" name="atid">
				<input type="text" name="mobile" placeholder="请输入手机号" style="display:inline-block;width:82%;" class="form-control">
				<div class="btn btn-primary" onclick="return send();" style="float: right;width: 18%;">发送</div>	 	
			</div> 	 	 	
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div>

<div id="modal-module-teamSend" class="modal fade" tabindex="-1" aria-hidden="false" >
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

<div id="modal-module-qrcode" class="modal fade" tabindex="-1" aria-hidden="false" >
	<div class="modal-dialog" style="width: 450px;margin-top:12%;">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>二维码</h3></div>
			<div class="modal-body"> 	 	
				<img src="http://jhzh66.com/attachment/images/8/2018/09/zQttlmbi68t19FentflBmlNVGVV1QV.png">
			</div>
			<h1 class="text-center">点击鼠标右键，图片另存为可保存到本地</1>	 	 	 	 	 		 		 
			<div class="modal-footer"><a href="#" class="btn btn-default" style="display: none;" data-dismiss="modal" aria-hidden="true">关闭</a></div>
		</div>
	</div>
</div> 


<script type="text/javascript">
		
		$('.preview').click(function(){
			$('[name="atid"]').val($(this).data('id'));
			$('#modal-module-menus').modal('show');
		}); 	 	


	$('.preview').click(function(){
		$('[name="atid"]').val($(this).data('id'));
		$('#modal-module-menus').modal('show');
	});

	function send(){
		var data={
			mobile:$('[name="mobile"]').val(),
			atid:$('[name="atid"]').val()
		};

		$.post('<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'preview'))?>',data,function(data){
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

	$('.team').click(function(){
		$('[name="satid"]').val($(this).data('id'));
		$('#modal-module-teamSend').modal('show');
	});

	var sending=false;
	function team(){

		if(sending){
			return false;
		}
		sending = true; 		 
 		
		$.post('<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'team','id'=>$_GPC['id']))?>',function(data){
			alert(data.result); 	 	
			if (data.status == 1) { 		 
				$('#modal-module-teamSend').modal('hide'); 		
				setTimeout(function(){ 	 			 
					location.reload(); 	 	 
				},250);
				sending=false;
			}
		},'json');
	}
	
	$('.qrcode').click(function(){ 	 
		$('#modal-module-qrcode img').attr('src',$(this).data('src'));
		$('#modal-module-qrcode').modal('show');
	});

	$('.release').click(function(){
		var sure=confirm('确定发布该文章吗?');

		if (!sure) {
			return false;
		} 	 	

		var data={
			id:$(this).data('id') 	
		};
		$.post('<?php  echo $this->createPluginWebUrl('bartact/article',array('op'=>'release'))?>',data,function(data){
			alert(data.result); 	 	
			if (data.status == 1) { 		 
				setTimeout(function(){ 	 			 
					location.reload(); 	 	 
				},250);
				sending=false;
			}	 		
		},'json');
	});	 	 

</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>	 	 