<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('cardtabs', TEMPLATE_INCLUDEPATH)) : (include template('cardtabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" href="http://jhzh66.com/addons/sz_yi/static/js/dist/area/Area.xml?v=3"></script>
<style type='text/css'>
	.trhead td {  background:#efefef;text-align: center}
	.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
	.goods_info{position:relative;width:60px;}
	.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
	.goods_info:hover {z-index:1;position:absolute;width:auto;}
	.goods_info:hover img{width:320px; height:320px;}
	#modal-module-qrcode img{left:50%;margin-left:-130px;position: relative;}
</style>
<?php  if($_GPC['op'] == 'add') { ?>
<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $su_info['id'];?>" />
        <div class='panel panel-default' style="border-radius: 5px;">
            <div class='panel-heading' style="background: #eee;">账号信息</div>
            <div class='panel-body'>

        		<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>选择	</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.article' ,$su_info) ) { ?>
								<label class="radio-inline">
								  <input type="radio" name="data[worktype]" <?php  if($muser['worktype'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 职业者
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[worktype]" <?php  if($muser['worktype'] == 1) { ?>checked<?php  } ?> id="inlineRadio2" value="1"> 创业者
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>类型	</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('activity.article' ,$su_info) ) { ?>
								<label class="radio-inline">
								  <input type="radio" name="data[public]" <?php  if($muser['public'] == 0) { ?>checked<?php  } ?> id="inlineRadio1" value="0"> 公开
								</label>
								<label class="radio-inline">
								  <input type="radio" name="data[public]" <?php  if($muser['public'] == 1) { ?>checked<?php  } ?> id="inlineRadio2" value="1"> 私密
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
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>姓名</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('activity.activity')) { ?>
	                        <input type="text" name="data[realname]" class="form-control" value="<?php  echo $muser['realname'];?>" autocomplete="off" />
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  电话</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
							<input type="text" name="data[mobile]" class="form-control" value="<?php  echo $muser['mobile'];?>" autocomplete="off" />
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  职位</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
							<input type="text" name="data[job]" class="form-control" value="<?php  echo $muser['job'];?>" autocomplete="off" />
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  职位</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
							<input type="text" name="data[orgName]" class="form-control" value="<?php  echo $muser['orgName'];?>" autocomplete="off" />
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
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>地区</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('activity.activity')) { ?>
	                       	 <?php  echo tpl_fans_form('reside',array('province' =>$muser['province'],'city' =>$muser['city'],'district'=>$muser['area']));?>
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
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>地址</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('activity.activity')) { ?>
	                        <input type="text" name="data[address]" class="form-control" value="<?php  echo $muser['address'];?>" autocomplete="off" />
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
	                    <label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>签名</label>
	                    <div class="col-sm-9 col-xs-12">
	                        <?php if(cv('activity.activity')) { ?>
	                        <input type="text" name="data[title]" class="form-control" value="<?php  echo $muser['title'];?>" autocomplete="off" />
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 头像</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
							<?php  echo tpl_form_field_image('data[head]',$muser['head']);?>
							<span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
							<?php  } else { ?>
								<img src="<?php  echo tomedia($muser['head'])?>">
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
							<?php if(cv('activity.activity')) { ?>
							<?php  echo tpl_form_field_image('data[wechatCode]',$muser['wechatCode']);?>
							<span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
							<?php  } else { ?>
								<img src="<?php  echo tomedia($muser['wechatCode'])?>">
							<?php  } ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>公司业务</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
								 <textarea class="form-control" name="data[business]"><?php  echo $muser['business'];?></textarea>
							<?php  } else { ?>
								<div><?php  echo $muser['business'];?></div>
							<?php  } ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span> 我提供的资源</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
								 <textarea class="form-control" name="data[supplier]"><?php  echo $muser['supplier'];?></textarea>
							<?php  } else { ?>
								<div><?php  echo $muser['supplier'];?></div>
							<?php  } ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
					</div>
				</div>


				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  我需要的资源</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
								 <textarea class="form-control" name="data[need]"><?php  echo $muser['need'];?></textarea>
							<?php  } else { ?>
								<div><?php  echo $muser['need'];?></div>
							<?php  } ?>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							</div>
						</div>
					</div>
				</div>

				<?php  if(false) { ?>
				<div class="form-group">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  机构名称	</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
							<input type="text" name="data[orgName]" class="form-control" value="<?php  echo $muser['orgName'];?>" autocomplete="off" />
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
							<?php if(cv('activity.activity')) { ?>
							<input type="text" name="data[contact]" class="form-control" value="<?php  echo $muser['contact'];?>" autocomplete="off" />
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
							<?php if(cv('activity.activity')) { ?>
							<input type="text" name="data[orgMobile]" class="form-control" value="<?php  echo $muser['orgMobile'];?>" autocomplete="off" />
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  机构介绍	</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
							<textarea class="form-control" name="data[orgDesc]"><?php  echo $muser['orgDesc'];?></textarea>
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
						<label class="col-xs-12 col-sm-3 col-md-3 control-label"><span style='color:red'>*</span>  提现比例</label>
						<div class="col-sm-9 col-xs-12">
							<?php if(cv('activity.activity')) { ?>
								<input type="number" name="data[withdrawRatio]" class="form-control" value="<?php  echo $muser['withdrawRatio'];?>">
							<?php  } else { ?>
								<div class='form-control-static'>********</div>
							<?php  } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					</div>
				</div>

			</div>
		</div>
		<?php  } ?>
                <div class="form-group"></div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                    <?php if(cv('activity.activity')) { ?>
                        <input type="button" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('activity.activity.add|activity.activity.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
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
 	function del(id){
		var data={
			id:id
		};
		$.post('<?php  echo $this->createPluginWebUrl('activity/article',array('op'=>'delete'))?>',data,function(data){
			alert(data.result);
			if (data.status == 1) {
				location.reload();
			}
		},'json');
	}





		$(':input[name=submit]').click(function(){
            if($(this).attr('submitting')=='1'){
                return;
            }

          // if ($('[name="data[title]"]').isEmpty()) {
          //      Tip.focus($('[name="data[title]"]'), '请填写标题!');
          //      return;
          //  }

           //  if ($('[name="data[desc]"]').isEmpty()) {
           //     Tip.focus($('[name="data[desc]"]'), '请输入摘要!');
           //     return;
           // }

           // if ($('[name="data[content]"]').isEmpty()) {
           //     Tip.focus($('[name="data[bgm]"]'), '请输入填写内容!');
           //     return;
           //}
			// var data={
			// 	title:$('[name="data[title]"]').val(),
			// 	desc:$('[name="data[desc]"]').val(),
			// 	relOrg:$('[name="data[relOrg]"]').val(),
			// 	descOrg:$('[name="data[descOrg]"]').val(),
			// 	type:$('input[name="data[type]"]:checked').val(),
			// 	teamModel:$('input[name="data[teamModel]"]:checked').val(),
			// 	bgm:$('[name="data[bgm]"]').val(),
			// 	status:$('input[name="data[status]"]').val(),
			// 	token:$('input[name="token"]').val(),
			// 	id:$('input[name="id"]').val(),
			// 	content:$('[name="data[content]"]').val()
			// };

            $(this).attr('submitting','1').removeClass('btn-primary');

            var ajax={
            	type:'post',
            	dataType:'json',
            	<?php  if($_GPC['op'] == 'add') { ?>
            	url:"<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'add'))?>",
            	<?php  } else { ?>
            	url:"<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'banner','ac'=>'sub'))?>",
            	<?php  } ?>
				success:function(re){
					alert(re.result);
					if (re.status ==1) {
						location.reload();
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

<?php  } else if($op == 'banner' && $ac == '') { ?>

<form action="" method="post">
<div class="panel panel-default">
    <div class="panel-body table-responsive">
        <table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th width="50%;">略缩图</th>
                    <th width="50%;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $index => $row) { ?>
                <tr>
                    <td><img width="120px;" height="120px;" src="<?php  echo tomedia($row['thumb'])?>"></td>
                    <td>
                    	<a class="btn btn-primary" href="<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'banner','ac'=>'post','num'=>$index))?>">修改</a>
                    	<a class="btn btn-default" onclick="return confirm('确定删除改轮播图吗?')" href="<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'banner','ac'=>'delete','num'=>$index))?>">删除</a>
                    </td>
                </tr>
                <?php  } } ?>
                <tr>
                    <td colspan='6'>
                          <a class='btn btn-primary' href="<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'banner','ac'=>'post'))?>"><i class='fa fa-plus'></i> 添加轮播图</a>
                          <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
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

<?php  } else if($ac == 'post') { ?>

<div class="main rightlist">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit='return formcheck()'>
        <input type="hidden" name="num" value="<?php  echo $_GPC['num'];?>" />
        <input type="hidden" name="ac" value="sub" />
        <div class="panel panel-default">
            <div class="panel-heading">
                轮播图设置
            </div>
            <div class="panel-body">

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">轮播图图片</label>
                <div class="col-sm-9 col-xs-12"	>
                    <?php  echo tpl_form_field_image('thumb', $list[$_GPC['num']]['thumb'])?>
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
    /*function formcheck() {
        if ($('[name="thumb"]').isEmpty()) {
            Tip.focus('[name="thumb"]', "请上传轮播图!");
            return false;
        }
        return true;
    }*/
</script>
<?php  } ?>




