<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('adtabs', TEMPLATE_INCLUDEPATH)) : (include template('adtabs', TEMPLATE_INCLUDEPATH));?>
<style type='text/css'>
	.trhead td {  background:#efefef;text-align: center}
	.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
	.goods_info{position:relative;width:60px;}
	.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
	.goods_info:hover {z-index:1;position:absolute;width:auto;}
	.goods_info:hover img{width:320px; height:320px;}
	.exchange img{
		width: 5em;
		height: auto;
	}
</style>
<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="method" value="ad" />
        <input type="hidden" name="p" value="suppliermenu" />
        <input type="hidden" name="do" value="plugin" />
        <input type="hidden" name="m" value="sz_yi" />
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>">
        <input type="hidden" name="op" value="post" />
        <input type="hidden" name="ac" value="submit" />
        <div class='panel panel-default' style="border-radius: 5px;">
            <div class='panel-heading' style="background: #eee;">广告信息<label data-id="<?php  echo $member['default_ad_model'];?>" class="label changemodel label-success">(
        	<?php  if($member['default_ad_model'] == 1) { ?>
         		基础广告
         	<?php  } else if($member['default_ad_model'] == 2) { ?>
         		外链广告
         	<?php  } else if($member['default_ad_model'] == 3) { ?>
         		纯视频广告
         	<?php  } ?>
			)</label></div>
            <div class='panel-body'>
            	<?php  if($member['default_ad_model'] == 1 || $member['default_ad_model'] == 2 || $member['default_ad_model'] == 3) { ?>
				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告名称</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" placeholder="最多可填写25个字" name="title" class="form-control" value="<?php  echo $su_info['title'];?>" autocomplete="off" />
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>核心记忆词</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="core" class="form-control" value="<?php  echo $su_info['core'];?>" placeholder="最多可填写5个字" autocomplete="off" />
						</div>
					</div>
				</div>
				<?php  if(false) { ?>
				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>核心词拼音</label>
						<div class="col-sm-2 col-xs-12">
							<input type="text" name="coreSpeel[]" class="form-control" value="<?php  echo $su_info['coreSpeel'];?>" autocomplete="off" />
						</div>
						<div class="col-sm-2 col-xs-12">
							<input type="text" name="coreSpeel[]" class="form-control" value="<?php  echo $su_info['coreSpeel'];?>" autocomplete="off" />
						</div>
						<div class="col-sm-2 col-xs-12">
							<input type="text" name="coreSpeel[]" class="form-control" value="<?php  echo $su_info['coreSpeel'];?>" autocomplete="off" />
						</div>
						<div class="col-sm-2 col-xs-12">
							<input type="text" name="coreSpeel[]" class="form-control" value="<?php  echo $su_info['coreSpeel'];?>" autocomplete="off" />
						</div>
						<div class="col-sm-1 col-xs-12">
							<input type="text" name="coreSpeel[]" class="form-control" value="<?php  echo $su_info['coreSpeel'];?>" autocomplete="off" />
						</div>
					</div>
				</div>
				<?php  } ?>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告分类</label>
						<div class="col-sm-9 col-xs-12">
							<select class="form-control" name="cate">
								<?php  if(is_array($cate)) { foreach($cate as $k => $v) { ?>
								<option value="<?php  echo $v['id'];?>">   <?php  echo $v['title'];?></option>
								<?php  } } ?>
							</select>
							<!-- <input type="url" name="link"  value="<?php  echo $su_info['link'];?>" placeholder="请输入广告链接" autocomplete="off" /> -->
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告大图</label>
						<div class="col-sm-9 col-xs-12">
							<?php if( ce('shop.goods' ,$su_info) ) { ?>
							<?php  echo tpl_form_field_multi_image('thumb',$su_info['thumb'])?>
							<span class="help-block">建议尺寸: 750:1084 （一张）</span>
							<?php  } else { ?>

							<a href='<?php  echo tomedia($img)?>' target='_blank'>
								<img src="<?php  echo tomedia($img)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
							</a>

							<?php  } ?>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告链接</label>
						<div class="col-sm-9 col-xs-12">
							<input type="url" name="link" class="form-control" value="<?php  echo $su_info['link'];?>" placeholder="请输入广告链接" autocomplete="off" />
						</div>
					</div>
				</div>
                <div class="form-group">
                    <div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
                        <label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>轮播图</label>
                        <div class="col-sm-9 col-xs-12">
                            <?php if( ce('shop.goods' ,$su_info) ) { ?>
                            <?php  echo tpl_form_field_multi_image('thumbs',$su_info['thumbs'])?>
                            <span class="help-block">建议尺寸: 640:360（限3张） </span>
                            <?php  } else { ?>
                            <?php  if(is_array($su_info['thumbs'])) { foreach($su_info['thumbs'] as $p) { ?>
                            <a href='<?php  echo tomedia($p)?>' target='_blank'>
                                <img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
                            </a>
                            <?php  } } ?>
                            <?php  } ?>
                        </div>
                    </div>
                </div>

                <!--<div class="form-group">-->
                    <!--<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">-->
                        <!--<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告链接</label>-->
                        <!--<div class="col-sm-9 col-xs-12">-->
                            <!--<input type="url" name="links" class="form-control" value="<?php  echo $su_info['link'];?>" placeholder="请输入广告链接" autocomplete="off" />-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->

				<?php  if($member['default_ad_model'] == 2 ) { ?>
					<div class="form-group">
						<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
							<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>外部链接</label>
							<div class="col-sm-9 col-xs-12">
								<input type="url" name="outside" class="form-control" value="<?php  echo $su_info['outside'];?>" placeholder="请输入外部链接" autocomplete="off" />
							</div>
						</div>
					</div>
				<?php  } else if($member['default_ad_model'] == 3 ) { ?>
					<div class="form-group">
						<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
							<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>视频链接</label>
							<div class="col-sm-9 col-xs-12">
								<input type="url" name="video" class="form-control" value="<?php  echo $su_info['video'];?>" placeholder="请输入视频链接" autocomplete="off" />
							</div>
						</div>
					</div>
				<?php  } ?>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>联系电话</label>
						<div class="col-sm-9 col-xs-12">
							<input type="text" name="mobile" class="form-control" value="<?php  echo $su_info['mobile'];?>" autocomplete="off" placeholder="请输入联系号码"/>
						</div>
					</div>
				</div>

				<?php  if(false) { ?>
				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告标签</label>
						<div class="col-sm-3 col-xs-12">
							<input type="text" name="adLabel[]" placeholder="最多可填写5个字" class="form-control" value="<?php  echo $su_info['adLabel'];?>" autocomplete="off" />
						</div>
						<div class="col-sm-3 col-xs-12">
							<input type="text" name="adLabel[]" placeholder="最多可填写5个字" class="form-control" value="<?php  echo $su_info['adLabel'];?>" autocomplete="off" />
						</div>
						<div class="col-sm-3 col-xs-12">
							<input type="text" name="adLabel[]" placeholder="最多可填写5个字" class="form-control" value="<?php  echo $su_info['adLabel'];?>" autocomplete="off" />
						</div>
					</div>
				</div>
				<?php  } ?>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>广告描述</label>
						<div class="col-sm-9 col-xs-12">
							<?php  echo tpl_ueditor('desc',$su_info['desc']);?>
						</div>
					</div>
				</div>
			</div>
		</div>


        <div class='panel panel-default' style="border-radius: 5px;">
			<div class="panel-heading" style="background: #eee;">投放设置</div>
			<div class=	'panel-body'>

				<div class="form-group">
					<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
						<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>投放类型</label>
						<div class="col-sm-9 col-xs-12">
							<select name="putInType" class="form-control">
								<option <?php  if($su_info['putInType'] == 1) { ?>selected<?php  } ?> value="1">现金红包广告</option>
								<option <?php  if($su_info['putInType'] == 2) { ?>selected<?php  } ?> value="2">换货码广告</option>
							</select>
						</div>
					</div>
				</div>

			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>推荐商家</label>
					<div class="col-sm-9 col-xs-12">
						<?php  if(is_array($merchall)) { foreach($merchall as $k => $v) { ?>
						<label class="radio-inline">
						  <input type="radio" <?php  if($v['uid'] == $su_info['recuid']) { ?>checked<?php  } ?> name="uid" value="<?php  echo $v['uid'];?>">

						  <?php  echo $v['username'];?>

						  [
						  <?php  if($v['dealmerchid'] > 0) { ?>
						  	换货商家
						  <?php  } else if($v['merchid'] > 0) { ?>
						  	本地商家
						  <?php  } else { ?>
						  	全国商家
						  <?php  } ?>
						  ]
						</label>
						<?php  } } ?>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>推荐商品</label>
					<div class="col-sm-9 col-xs-12">

						<div class="form-group addLocalFlag">
	                    <input type="hidden" name="goodsids" value="<?php  echo $item['goodsids'];?>" >
	                    <table class="table table-hover table-responsive" >
	                        <thead>
	                        <tr>
	                            [<button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus').modal();">推荐商品</button>]
	                            <th style="width: 35%">图片</th>
	                            <th style="width: 20%">商品名称</th>
	                            <th style="width: 10%">价格</th>
	                            <th style="width: 5%">操作</th>
	                        </tr>
	                        </thead>
	                        <tbody style="text-align:left;" class="exchange">
	                            <?php  if(!empty($stores)) { ?>
	                                <?php  if(is_array($stores)) { foreach($stores as $k => $v) { ?>
	                                    <tr data-id="<?php  echo $v['id'];?>">
	                                        <td><img src="<?php  echo tomedia($v['thumb'])?>"></td>
	                                        <td><?php  echo $v['title'];?></td>
	                                        <td><?php  echo $v['marketprice'];?></td>
	                                        <td><span type="button" class="fa fa-trash-o"></span></td>
	                                    </tr>
	                                <?php  } } ?>
	                            <?php  } ?>
	                        </tbody>
	                    </table>
	                </div>
					</div>
				</div>
			</div>


			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>投放目标</label>
					<div class="col-sm-9 col-xs-12">
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox1" name="setarea" value="option1">投放区域
						</label>
						<div class="form-group select-addr" style="display:none;margin-bottom: -10px">
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
								<div class="col-sm-9 col-xs-12">
									<label class="checkbox-inline" style="margin-bottom:15px;">
									  <input type="checkbox" id="national" name="national" value="1"> 全国投放
									</label>
									<?php  echo tpl_form_field_district('address', array('province' => $su_info['province'],'city' => $su_info['city'],'district' => $su_info['area']));?>
								</div>
							</div>
						</div>
						<br>

						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox2" name="setgender" value="option2">性别
						</label>
						<div class="form-group select-gender" style="display:none;margin-bottom: -10px">
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
								<div class="col-sm-9 col-xs-12">
									<label class="radio-inline">
										<input type="radio" <?php  if($su_info['gender'] == 1) { ?>checked<?php  } ?> name="gender" value="1"> 男
									</label>
									<label class="radio-inline">
										<input type="radio" <?php  if($su_info['gender'] == 2) { ?>checked<?php  } ?> name="gender" value="2"> 女
									</label>
									<label class="radio-inline">
										<input type="radio" <?php  if(empty($su_info['gender'])) { ?>checked<?php  } ?> name="gender" value="0"> 不限制
									</label>
								</div>
							</div>
						</div>
						<br>

						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" name="setimum" value="option3"> 年收入
						</label>
						<div class="form-group select-receive" style="display:none;margin-bottom: -10px">
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
								<div class="col-sm-9 col-xs-12">
									<div class="input-group">
								      <span class="input-group-addon">最低</span>
								      <input type="text" value="<?php  echo $su_info['minimum'];?>" class="form-control" name="minimum" aria-describedby="inputGroupSuccess3Status">
								      <span class="input-group-addon">最高</span>
								      <input type="text" value="<?php  echo $su_info['maximum'];?>" class="form-control" name="maximum"  aria-describedby="inputGroupSuccess3Status">
								    </div>
								</div>
							</div>
						</div>
						<br>

						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox4" name="setage" value="option4"> 年龄段
						</label>
						<div class="form-group select-year" style="display:none;margin-bottom: -10px">
							<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
								<div class="col-sm-9 col-xs-12">
									<div class="input-group">
								      <span class="input-group-addon">最少</span>
								      <input type="text" class="form-control" name="minage" value="<?php  echo $su_info['minage'];?>" aria-describedby="inputGroupSuccess3Status">
								      <span class="input-group-addon">最高</span>
								      <input type="text" class="form-control" name="maxage" value="<?php  echo $su_info['maxage'];?>"  aria-describedby="inputGroupSuccess3Status">
								    </div>
								</div>
							</div>
						</div>

						<br>

					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'></span></label>
					<div class="col-sm-9 col-xs-12">
						<label class="col-xs-12 col-sm-12 col-md-12 control-label">
							<span style='color:red;float:left;margin-left:-15px;' class="adcash"></span>
							<span style='color:red;float:left;margin-left:35px;' class="adnum"></span>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>红包个数</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="bonus" placeholder="最低500起投,每条消耗0.3(余额/换货码),请确认余额充足" class="form-control" value="<?php  echo $su_info['bonus'];?>" autocomplete="off" />
					</div>
				</div>
			</div>


			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>最多领取</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="usermax" placeholder="用户最多领取个数" class="form-control" value="<?php  echo $su_info['usermax'];?>" autocomplete="off" />
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>每天个数</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="daymax" placeholder="用户每天最多领取个数" class="form-control" value="<?php  echo $su_info['daymax'];?>" autocomplete="off" />
					</div>
				</div>
			</div>

			<?php  if(false) { ?>
			  <div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>派发金额</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" name="money" placeholder="最低500起投,每条消耗0.3(余额/换货码),请确认余额充足" class="form-control" value="<?php  echo $su_info['money'];?>" autocomplete="off" />
					</div>
				</div>
			</div>
			<?php  } ?>

			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>开始日期</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo _tpl_form_field_date('stime',$su_info['stime'], true)?>
					</div>
				</div>
			</div>


			<div class="form-group">
				<div class="col-lg-10 col-md-6 col-sm-6 col-xs-12">
					<label class="col-xs-12 col-sm-3 col-md-1 control-label"><span style='color:red'>*</span>结束日期</label>
					<div class="col-sm-9 col-xs-12">
						 <?php  echo _tpl_form_field_date('etime',$su_info['etime'],true)?>
					</div>
				</div>
			</div>

        	</div>
		</div>
			<div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-1 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <input type="submit" value="提交审核" class="btn btn-primary col-lg-offset-2 col-lg-2" />
                    <div class="btn btn-success col-lg-offset-1 col-lg-2 draft">保存草稿</div>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <!-- <span style="display:block" class="col-lg-12">(信息通过审核候才有效)</span> -->
                </div>
            </div>
	</div>



		<script>


			$('[name="core"]').blur(function(){
				var length=5;
				var o = $(this);
				if (o.val().length > length) {
					alert('核心记忆最多为五个字!');
					return;
				}
			});

			$('[name="putInType"]').change(function(){
				var type=$(this).val();
				$.post('<?php  echo $this->createPluginWebUrl("suppliermenu/ad",array("op"=>"getinfo"))?>',{type:type},function(e){
					var t1=type==1?'剩余的余额 : ':'剩余的换货码 : ';
						t1+=e.result.currency;
						t2='可投放的数量 : '+e.result.number;
						$('.adcash').html(t1);
						$('.adnum').html(t2);
				},'json');
			});
			$('[name="putInType"]').val(1).change();
			$('[name="bonus"]').blur(function(){
				var num=$(this).val();
				if (parseInt(num) < 500) {
					alert('红包个数最少要500个');
					$(this).val('');
				}
			});

			$('[name="usermax"]').blur(function(){
				var usermax=$(this).val();
				var daymax=$('[name="daymax"]').val();
				var bonus=$('[name="bonus"]').val();
                console.log(bonus);
                console.log(usermax);
				if (parseInt(usermax) > parseInt(bonus)) {
					alert('用户最多领取个数不能超过总投放个数');
					$(this).val('');
				}
				if (parseInt(daymax) > parseInt(usermax)) {
					alert('每天领取个数不能超过最多领取个数');
				}
			});

			$('[name="daymax"]').blur(function(){
				var daymax=$(this).val();
				var usermax=$('[name="usermax"]').val();
				console.log('每天领取次数'+daymax);
				console.log('最多领取个数'+usermax);
				if (parseInt(daymax) > parseInt(usermax)) {
					alert('每天领取个数不能超过最多领取个数');
					$(this).val('');
				}
			});

			$('#inlineCheckbox1').click(function(){
				var o = $(this);
				if (o.context.checked) {
					$('.select-addr').show();
				}else{
					$('.select-addr').hide();
				}
			});

			$('#national').click(function(){
				var o = $(this);
				if (o.context.checked) {
					$('.tpl-district-container').hide();
				}else{
					$('.tpl-district-container').show();
				}
			});


			$('#inlineCheckbox2').click(function(){
				var o = $(this);
				if (o.context.checked) {
					$('.select-gender').show();
				}else{
					$('.select-gender').hide();
				}
			});

			$('#inlineCheckbox3').click(function(){
				var o = $(this);
				if (o.context.checked) {
					$('.select-receive').show();
				}else{
					$('.select-receive').hide();
				}
			});

			$('#inlineCheckbox4').click(function(){
				var o = $(this);
				if (o.context.checked) {
					$('.select-year').show();
				}else{
					$('.select-year').hide();
				}
			});



			//推荐商品
                	var maxgoods=3;
                    var goodsids=<?php  echo json_encode(explode(',',$item['goodsids']))?>;
                    function myinarray(val,arr){            //自己的inarray 如果存在返回假 否则true
                        for(var i in arr){
                            if(arr[i]==val){
                                return false;
                            }
                        }
                        return true;
                    }

                    function select_saler(obj){         //选择商品
                        var str='';
                        if (myinarray(obj.id,goodsids)){
                        	if (goodsids.length >= maxgoods+1) {
                        		alert('最多选择'+maxgoods+'个商品');
                        		return;
                        	}
                            goodsids.push(obj.id);
                            str+='<tr data-id="'+obj.id+'">';
                            str+='<td><img style="width:80px;height:80px;" src="'+obj.thumb+'" alt=""></td>';
                            str+='<td>'+obj.title+'</td>';
                            str+='<td>'+obj.marketprice+'</td>';
                            str+='<td><span type="button" class="fa fa-trash-o"></span></td>';
                            str+='</tr>';
                            $('[name="goodsids"]').val(goodsids.join(','));     //use ',' explode array
                            $('.exchange').append(str);         //列表
                        }else{
                            alert('商品已存在');
                        }
                    }

                    <?php  if($su_info) { ?>		//修改的时候赋值js数组
                    	<?php  if(is_array($stores)) { foreach($stores as $row) { ?>
                            goodsids.push(<?php  echo $row['id'];?>);
                    	<?php  } } ?>
                            $('[name="goodsids"]').val('<?php  echo $su_info['goodsid'];?>');
                    <?php  } ?>

                    $('.exchange').on('click','span',function(){
                        var parent=$(this).parents('tr');
                        var id=parent.data('id');
                        goodsids.splice($.inArray(id,goodsids),1);
                        $('[name="goodsids"]').val(goodsids.join(','));
                        parent.remove();
                    });

                </script>

                <div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
                    <div class="modal-dialog" style="width: 920px;">
                        <div class="modal-content">
                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择商品</h3></div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="请输入商品名">
                                        <span class="input-group-btn"><button type="button" class="btn btn-default" onclick="search_stores();">搜索</button></span>
                                    </div>
                                </div>
                                <div id="module-menus" style="padding-top:5px;"></div>
                            </div>
                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                        </div>

                    </div>
                </div>
                <script>
                    function search_stores() {
                        $("#module-menus").html("正在搜索....")
                        $.post("<?php  echo $this->createPluginWebUrl('suppliermenu/store',array('op'=>'getProduct'))?>", {
                            keyword: $.trim($('#search-kwd').val())
                        }, function(dat){
                            $('#module-menus').html(dat);
                        });
                    }
                    function remove_store(obj){
                        var storeid = $(obj).closest('.multi-audio-item').attr('storeid');
                        $('.multi-audio-item[storeid="' + storeid +'"]').remove();
                    }
                    function select_store(o) {
                        if($('.multi-audio-item[storeid="' + o.id +'"]').length>0){
                            return;
                        }
                        var html ='<div style="height: 40px; position:relative; float: left; margin-right: 18px;" class="multi-audio-item" storeid="' + o.id +'">';
                        html+='<div class="input-group">';
                        html+='<input type="hidden" value="' + o.id +'" name="goodsids[]">';
                        html+='<input type="text" value="' + o.storename +'" readonly="" class="form-control">';
                        html+='<div class="input-group-btn"><button type="button" onclick="remove_store(this)" class="btn btn-default"><i class="fa fa-remove"></i></button></div>';
                        html+='</div></div>';
                        $('#stores').append(html);
                    }
                </script>

				<?php  } else if($member['default_ad_model'] == 2) { ?>

				<?php  } else if($member['default_ad_model'] == 2) { ?>

				<?php  } ?>

				<?php  if(false) { ?>
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
									<span class="help-block">建议尺寸: 750:1084 </span>
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
										<span class="help-block">建议尺寸: 750:1084 </span>
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
				<?php  } ?>

            </div>

        </div>
    </form>
</div>

</div>
<script language='javascript'>

   $(function(){

   	<?php  if($su_info['national'] || $su_info['province']) { ?>
		$('[name="setarea"]').click();

		<?php  if($su_info['national']) { ?>
			$('[name="national"]').click();
		<?php  } ?>
   	<?php  } ?>

   	<?php  if($su_info['gender']) { ?>
		$('[name="setgender"]').click();
   	<?php  } ?>

	<?php  if($su_info['minimum'] || $su_info['maximum']) { ?>
		$('[name="setimum"]').click();
   	<?php  } ?>


   	<?php  if($su_info['minage'] || $su_info['maxage']) { ?>
		$('[name="setage"]').click();
   	<?php  } ?>


   	$('.changemodel').click(function(){
   		var o = $(this);
   		var data={
   			id:o.data('id')
   		};
   		$.post('<?php  echo $this->createPluginWebUrl('suppliermenu/ad',array('op'=>'set'))?>',data,function(data){

   			if (data.status == 1) {
   				alert('切换模式成功!');
   				location.reload();
   				return;
   			}
   			alert('切换模式失败!');
   		},'json');

   	});


     	$('.draft').click(function(){
     		var html='<input type="hidden" value="3" name="status"/>';
     		console.log($(this).parents('form'));
     		$(this).parents('form').append(html).submit();
     	});
        // $('#dataform').ajaxForm();

        // $(':input[name=submit]').click(function(){
        //     if($(this).attr('submitting')=='1'){
        //         return;
        //     }
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
			// console.log('this');
            // $(this).attr('submitting','1').removeClass('btn-primary');
            // $('#dataform').ajaxSubmit(function(data){
            //     data = eval("(" +  data  +")");
            //     if(data.result!=1){
            //           $(this).removeAttr('submitting').addClass('btn-primary');
            //           Tip.select($(':input[name=username]'), data.message );
            //           return;
            //     }
            //     location.href= "<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_add')?>";
            // })
        // })
   })

</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>