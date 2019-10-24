<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade.js"></script>
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
            <input type="hidden" name="method" value="supplier" />
            <input type="hidden" name="op" value="" />
            <div class="form-group">				<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">会员信息</label>
	                <div class="col-sm-8 col-lg-9 col-xs-12">
	                    <input type="text" class="form-control"  name="uid" value="<?php  echo $_GPC['uid'];?>" placeholder='搜索供货商ID'/>
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
	<div class="panel-body">
    	<a class='btn btn-default' style="background-color: #1E95C9; color: #fff; border-radius: 6px;" href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'post'))?>">添加新供应商</a>
	</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
                <tr><th style='width:150px;'>供应商ID</th>
                    <th style='width:150px;'>用户名</th>
                    <th style='width:150px;'>姓名</th>
                    <th style='width:150px;'>手机号码</th>
                    <th style='width:150px;'>类型</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                	<?php  if(!empty($row['uid'])) { ?>
		                <tr><td><?php  echo $row['uid'];?></td>
		                    <td><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier/detail',array('uid' => $row['uid']));?>" title='供应商信息' style="color: #0060DF;"><?php  echo $row['username'];?></a></td>
		                    <td><?php  echo $row['realname'];?></td>
		                    <td><?php  echo $row['mobile'];?></td>
		                    <td>
		                        <?php  if($row['type'] == 1) { ?>供应商<?php  } ?>
		                        <?php  if($row['type'] == 2) { ?>商家<?php  } ?>
		                    </td>
		                    <td  style="overflow:visible;">
                                <div class="btn-group btn-group-sm">
                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
                                        <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
                                            <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier/detail',array('uid' => $row['uid']));?>" title='供应商信息'><i class='fa fa-user'></i> 供应商信息</a></li>
                                            <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'post', 'id' => $row['id']))?>" title='修改密码'><i class='fa fa-edit'></i> 修改密码</a></li>
                                            <li><a  href="<?php  echo $this->createPluginWebUrl('supplier/supplier_list',array('supplier_uid' => $row['uid']));?>" title='我的订单'><i class='fa fa-list'></i> 我的订单</a></li>
                                            <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'delete', 'id' => $row['id']))?>" title="删除" onclick="return confirm('确定要删除该供应商吗？');"><i class='fa fa-remove'></i> &nbsp;删除供应商</a></li>
                                        </ul>
                                </div>
		                    </td>
		                </tr>
		            <?php  } ?>
                <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
<?php  } else if($operation=='detail') { ?>

<form <?php if(cv('supplier.supplier.edit|supplier.supplier.check')) { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="id" value="<?php  echo $supplierinfo['uid'];?>">
    <input type="hidden" name="op" value="detail">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="supplier" />
    <input type="hidden" name="method" value="supplier" />
    <input type="hidden" name="op" value="detail" />
    <div class='panel panel-default'>
        <div class='panel-heading'>供应商详细信息</div>
        <div class='panel-body'>
            <div class="form-group notice">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">微信角色</label>
                    <div class="col-sm-4">
                        <input type='hidden' id='noticeopenid' name='data[openid]' value="<?php  echo $supplierinfo['openid'];?>" />
                        <div class='input-group'>
                            <input type="text" name="saler" maxlength="30" value="<?php  if(!empty($saler)) { ?><?php  echo $saler['nickname'];?>/<?php  echo $saler['realname'];?>/<?php  echo $saler['mobile'];?><?php  } ?>" id="saler" class="form-control" readonly />
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();">选择角色</button>
                                <button class="btn btn-danger" type="button" onclick="$('#noticeopenid').val('');$('#saler').val('');$('#saleravatar').hide()">清除选择</button>
                            </div>
                        </div>
                        <span id="saleravatar" class='help-block' <?php  if(empty($saler)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $saler['avatar'];?>"/></span>
                        <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择角色</h3></div>
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

                    </div>				</div>
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
			                                $("#saleravatar").show();
			                                 $("#saleravatar").find('img').attr('src',o.avatar);
			    $("#saler").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
			    $("#modal-module-menus-notice .close").click();
			  }
			</script>

			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">会员ID</label>
	                <div class="col-sm-9 col-xs-12">
	                    <div class='form-control-static'><?php  echo $supplierinfo['uid'];?></div>
	                </div>
	            </div>
	            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
			</div>


			<div class="form-group">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">真实姓名</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="data[realname]" class="form-control" value="<?php  echo $supplierinfo['realname'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="data[realname]" class="form-control" value="<?php  echo $supplierinfo['realname'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['realname'];?></div>
	                    <?php  } ?>
	                </div>
	            </div>
	            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
			</div>
            <div class="form-group">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">手机号码</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $supplierinfo['mobile'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="data[mobile]" class="form-control" value="<?php  echo $supplierinfo['mobile'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['mobile'];?></div>
	                    <?php  } ?>
	                </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>

            <div class="form-group">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">微信</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="weixin" class="form-control" value="<?php  echo $supplierinfo['weixin'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="weixin" class="form-control" value="<?php  echo $supplierinfo['weixin'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['weixin'];?></div>
	                    <?php  } ?>
	                </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>



            <div class="form-group notice">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">推荐人</label>
                    <div class="col-sm-4">
                        <input type='hidden' id='noticeopenid1' name='agentid' value="<?php  echo $agent['id'];?>" />
                        <div class='input-group'>
                            <input type="text" name="agent" maxlength="30" value="<?php  if(!empty($agent)) { ?><?php  echo $agent['nickname'];?>/<?php  echo $agent['realname'];?>/<?php  echo $agent['mobile'];?><?php  } ?>" id="saler1" class="form-control" readonly />
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice1').modal();">选择角色</button>
                                <button class="btn btn-danger" type="button" onclick="$('#noticeopenid1').val('');$('#saler1').val('');$('#saleravatar1').hide()">清除选择</button>
                            </div>
                        </div>
                        <span id="saleravatar1" class='help-block' <?php  if(empty($agent)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $agent['avatar'];?>"/></span>
                        <div id="modal-module-menus-notice1"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择角色</h3></div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice1" placeholder="请输入昵称/姓名/手机号" />
                                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members1();">搜索</button></span>
                                            </div>
                                        </div>
                                        <div id="module-menus-notice1" style="padding-top:5px;"></div>
                                    </div>
                                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                </div>

                            </div>
                        </div>

                    </div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
			<script language='javascript'>
			  function search_members1() {
			             if( $.trim($('#search-kwd-notice1').val())==''){
							$('#search-kwd-notice1').attr('placeholder','请输入关键词');
			                 <!-- Tip.focus('#search-kwd-notice1','请输入关键词'); -->
			                 return;
			             }
			    $("#module-menus-notice1").html("正在搜索....")
			    $.get('<?php  echo $this->createWebUrl('member/query')?>', {
			      keyword: $.trim($('#search-kwd-notice1').val()),agent:'agent'
			    }, function(dat){
			      $('#module-menus-notice1').html(dat);
			    });
			  }
			  function select_member1(o) {
			    $("#noticeopenid1").val(o.id);
			                                $("#saleravatar1").show();
			                                 $("#saleravatar1").find('img').attr('src',o.avatar);
			    $("#saler1").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
			    $("#modal-module-menus-notice1 .close").click();
			  }
			</script>


            <div class="form-group">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">公司名称</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="data[company]" class="form-control" value="<?php  echo $supplierinfo['company'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="data[company]" class="form-control" value="<?php  echo $supplierinfo['company'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['company'];?></div>
	                    <?php  } ?>
	                </div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>

            <div class="form-group">				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	            	<label class="col-xs-12 col-sm-3 col-md-3 control-label">金额</label>
	            	<div class="col-sm-9 col-xs-12">
	            	<span class='help-block'>累计金额：<span style='color:red'><?php  if(!empty($totalmoney)) { ?><?php  echo $totalmoney;?><?php  } else { ?>0<?php  } ?>元</span> 已结算金额：<span style='color:red'><?php  if(!empty($totalmoneyok)) { ?><?php  echo $totalmoneyok;?><?php  } else { ?>0<?php  } ?>元</span></span>
	            	</div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group">				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">银行卡号</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="data[banknumber]" class="form-control" value="<?php  echo $supplierinfo['banknumber'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="data[banknumber]" class="form-control" value="<?php  echo $supplierinfo['banknumber'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['banknumber'];?></div>
	                    <?php  } ?>
	                </div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group">				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">开户名</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="data[accountname]" class="form-control" value="<?php  echo $supplierinfo['accountname'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="data[accountname]" class="form-control" value="<?php  echo $supplierinfo['accountname'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['accountname'];?></div>
	                    <?php  } ?>
	                </div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group">
			    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			        <label class="col-xs-12 col-sm-3 col-md-3 control-label">身份证信息</label>
			        <div class="col-sm-9 col-xs-12">
			            <p>身份证正面：<img src="<?php  echo $supplierinfo['idimgs']['idimg1'];?>" alt="" width="280"></p>
			            <p>身份证反面：<img src="<?php  echo $supplierinfo['idimgs']['idimg2'];?>" alt="" width="280"></p>
			            <p>营业执照：<img src="<?php  echo $supplierinfo['idimgs']['permit'];?>" alt="" width="280"></p>
			        </div>
			    </div>
			    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			    </div>
			</div>

            <div class="form-group">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">开户银行</label>
	                <div class="col-sm-9 col-xs-12">
	                       <?php if(cv('supplier.supplier.edit')) { ?>
	                    <input type="text" name="data[accountbank]" class="form-control" value="<?php  echo $supplierinfo['accountbank'];?>"  />
	                       <?php  } else { ?>
	                       <input type="hidden" name="data[accountbank]" class="form-control" value="<?php  echo $supplierinfo['accountbank'];?>"  />
	                    <div class='form-control-static'><?php  echo $supplierinfo['accountbank'];?></div>
	                    <?php  } ?>
	                </div>				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				</div>
            </div>
            <div class="form-group">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">QQ号</label>
	                <div class="col-sm-9 col-xs-12">
	                    <input type="text" name="data[qq]" class="form-control" value="<?php  echo $supplierinfo['qq'];?>"  />
	                </div>
	                </div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					QQ用于客服聊天 (注: 若要开启此功能，QQ需要开启'允许临时聊天'功能)
					<!-- <a target=blank href='http://wpa.qq.com/msgrd?v=3&uin=913746590&site=qq&menu=yes'><img src="http://wpa.qq.com/pa?p=1:913746590:2" border="0" align="middle" ></a>
					<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=3466957810&site=qq&menu=yes"><img src="http://wpa.qq.com/pa?p=1:913746590:1" border="0" align="middle" ></a> -->
				</div>
            </div>
            <div class="form-group">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	                <label class="col-xs-12 col-sm-3 col-md-3 control-label">所在区域</label>
	                <div class="col-sm-9 col-xs-12">
	                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
	                    <select class="form-control tpl-province" id="sel-provance" onchange="selectCity();" name="birth[province]">
	                        <option value="" selected="true">所在省份</option>
	                    </select>
	                </div>
	                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
	                    <select class="form-control tpl-city" id="sel-city" onchange="selectcounty()" name="birth[city]"><option value="" selected="true">所在城市</option></select>
	                </div>
	                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
	                    <select class="form-control tpl-district" id="sel-area" name="birth[district]"><option value="" selected="true">所在地区</option></select>
	                </div>
	            </div></div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					区域分佣 按这个地址发放
				</div>
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if(cv('supplier.supplier.edit|supplier.supplier.check')) { ?>
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('supplier.supplier.edit|supplier.supplier.check')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
            </div>
   		 </div>
   	</div>
</form>
</div>
<?php  } else if($operation = 'list') { ?>
<div class="panel panel-default">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="supplier" />
            <input type="hidden" name="method" value="supplier" />
            <input type="hidden" name="op" value="list" />
            <div class="row" style="padding: 0 10px;">
                <!-- <div class="myform col-lg-4">
                    <div class="input-group">
                        <label class="input-group-addon">
                            <label class="checkbox-inline" style="margin-top:-7px;"><input type="checkbox" value="1" name="searchtime">按时间</label>
                      </label>
                        <div class="">
                            <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d  H:i', $endtime)),true);?>
                        </div>
                    </div>
                </div> -->
                <div class="myform col-lg-4">
                    <div class="input-group">
                        <label class="input-group-addon">会员信息</label>
                        <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder="可搜索昵称/姓名/手机号"/>
                    </div>
                </div>
                <div class="myform col-lg-2">
                    <div class="input-group">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <a class='btn btn-default' style="background-color: #1E95C9; color: #fff; border-radius: 6px;" href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'post'))?>">添加新商家</a>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:150px;'>商家ID</th>
                    <th style='width:150px;'>类型</th>
                    <th style='width:150px;'>用户名</th>
                    <th style='width:250px;'>头像/昵称</th>
                    <th style='width:150px;'>姓名</th>
                    <th style='width:150px;'>手机号码</th>
                    <!-- <th style='width:250px;'>时间</th> -->
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?> <?php  if(!empty($row['uid'])) { ?>
                <tr>
                    <td><?php  echo $row['uid'];?></td>
                    <td>
                        <?php  if($row['type'] == 1) { ?>供应商<?php  } ?>
                        <?php  if($row['type'] == 2) { ?>商家<?php  } ?>
                    </td>
                    <td><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier/detail',array('uid' => $row['uid']));?>" title='商家信息' style="color: #0060DF;"><?php  echo $row['username'];?></a></td>
                    <td>
                        <?php  if($row['avatar']) { ?><img src="<?php  echo $row['avatar'];?>" alt="" height="30"><?php  } ?>
                        <span><?php echo $row['realname'] ? $row['realname'] : $row['nickname']?></span>
                    </td>
                    <td><?php  echo $row['realname'];?></td>
                    <td><?php  echo $row['mobile'];?></td>
                    <!-- <td><?php echo $row['create_time'] ? date('Y/m/d H:i:s', $row['create_time']) : '-';?></td> -->
                    <td style="overflow:visible;">
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
                                <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier/detail',array('uid' => $row['uid']));?>" title='供应商信息'><i class='fa fa-user'></i> 商家信息</a></li>
                                <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'post', 'id' => $row['id']))?>" title='修改商家级别'><i class='fa fa-edit'></i> 修改商家级别/密码</a></li>
                                <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_list',array('supplier_uid' => $row['uid']));?>" title='我的订单'><i class='fa fa-list'></i> 我的订单</a></li>
                                <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'delete', 'id' => $row['id']))?>" title="删除" onclick="return confirm('确定要删除该商家吗？');"><i class='fa fa-remove'></i> &nbsp;删除商家</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php  } ?> <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
<?php  } ?>
</div>
<script>
    cascdeInit("<?php  echo $supplierinfo['provance'];?>", "<?php  echo $supplierinfo['city'];?>",'<?php  echo $supplierinfo['area'];?>');
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>