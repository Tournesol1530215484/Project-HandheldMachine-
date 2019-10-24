<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="panel panel-info">
    <?php  if($op == 'display') { ?>
    <div class="panel-heading">广告模板管理</div>
    <div class="panel-body">
    <style type="text/css">
    .show-modal{
        width:350px;
        height:auto;
        margin-left: 29%;
    }

    .show-modal img{
       margin-bottom:5px;
    }
    .set-defatult{
        transition:all 1s;
    }
    </style>
        <?php  if(false) { ?>
        <form action="">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="sz_yi">
            <input type="hidden" name="p" value="suppliermenu">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="method" value="barter_currency">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <select name="type" class="form-control col-sm-3 col-md-3" style="margin-bottom: 15px">
                        <option <?php  if($_GPC['type']==0) { ?>selected<?php  } ?> value="0">全部</option>
                        <option <?php  if($_GPC['type']==1) { ?>selected<?php  } ?> value="1">购买易货额度</option>
                        <option <?php  if($_GPC['type']==2) { ?>selected<?php  } ?> value="2">下架解冻</option>
                        <option <?php  if($_GPC['type']==3) { ?>selected<?php  } ?> value="3">人工冻结</option>
                        <option <?php  if($_GPC['type']==4) { ?>selected<?php  } ?> value="4">上架冻结</option>
                        <option <?php  if($_GPC['type']==5) { ?>selected<?php  } ?> value="5">购买会员赠送</option>
                        <option <?php  if($_GPC['type']==6) { ?>selected<?php  } ?> value="6">首次注册商家成功赠送</option>
                        <option <?php  if($_GPC['type']==7) { ?>selected<?php  } ?> value="7">定向易货退回</option>
                        <option <?php  if($_GPC['type']==8) { ?>selected<?php  } ?> value="8">广告资源置换所得</option>
                        <option <?php  if($_GPC['type']==9) { ?>selected<?php  } ?> value="9">购买获取</option>
                        <option <?php  if($_GPC['type']==10) { ?>selected<?php  } ?> value="10">平台赠送</option> 	 		 	 	
                        <option <?php  if($_GPC['type']==11) { ?>selected<?php  } ?> value="11">销售收入易货码自动激活</option>
                </select>
                <label class="col-sm-3 col-md-3 control-label">可用易货额度 <span class="credit3" style="color: #f00;"><?php  echo $member['currency_credit3'];?></span></label>
                <label class="col-sm-3 col-md-3 control-label">已消耗易货额度 <span class="freeze_credit3" style="color: #f00;"><?php  echo $member['use'];?></span></label>
                <label class="col-sm-3 col-md-3 control-label">上架冻结易货额度 <span style="color: #f00;"></span></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="col-sm-2">
                    <label class="radio-inline"><input type="radio" name="searchtime" value="0" <?php  if(empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>不搜索</label>
                    <label class="radio-inline"><input type="radio" name="searchtime" value="1" <?php  if(!empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>搜索</label>
                </div>
                <div class="col-sm-7 col-lg-7 col-xs-12">
                    <?php  echo tpl_form_field_daterange('datetime', array('starttime'=>date('Y-m-d H:i',$starttime),'endtime'=>date('Y-m-d H:i',$endtime)), true)?>
                </div>
                <button class="btn btn-default"><i class="fa fa-search"></i> 查询</button>
            </div>
        </form>
        <?php  } ?>
    </div>
</div>
<div class="panel panel-default">
    <div class="">
	<table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style="width:14%">模板名称</th>
                    <th style="width:14%">模板类型</th>
                    <th style="width:14%">模块数</th>
                    <th style="width:14%">模板状态</th>
                    <th style="width:14%">是否纯视频 </th>
                    <th style="width:14%">创建时间 </th>
                    <th style="width:14%">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $k => $v) { ?>
                <tr style="background: #eee">
                    <td><?php  echo $v['title'];?></td>
                    <td>
                        <?php  if($v['type'] == 1) { ?>
                            系统
                        <?php  } else { ?>
                            用户
                        <?php  } ?>
                    </td>
                    <td><?php  echo $v['module'];?></td>
                    <td>
                    <?php  if($v['status'] == 1) { ?>
                        正常
                    <?php  } else { ?>
                        禁用
                    <?php  } ?>
                    </td>
                    <td>
                    <?php  if($v['video'] == 1) { ?>
                        是
                    <?php  } else { ?>
                        否
                    <?php  } ?>
                    </td>
                    <td><?php  echo date('Y-m-d H:i:s',$v['ctime'])?></td>
                    <td>
                        <label class="label label-info" onclick='return preview(<?php  echo json_encode($v["thumb"])?>);'>预览</label>
                        <?php  if(intval($member['default_ad_model'])==intval($v['id'])) { ?>                        
                            <label data-id="<?php  echo $v['id'];?>" data-m="<?php  echo $member['default_ad_model'];?>" class="label label-danger set-defatult">默认</label>
                        <?php  } else { ?>                                              
                            <label data-id="<?php  echo $v['id'];?>" data-m="<?php  echo $member['default_ad_model'];?>" class="label label-success set-defatult">设为默认</label>
                        <?php  } ?>
                        <?php  if(false) { ?>       
                        <a href="<?php  echo $this->createPluginWebUrl('suppliermenu/ad_demo',array('op'=>'adddemo','id'=>$v['id']))?>" class="label label-primary">修改</a>
                        <?php  } ?>       
                    </td>
                </tr>
                <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>


<!-- modal -->
<div class="modal fade" tabindex="-1" id="modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:45%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">预览模板</h4>
            </div>
            <div class="modal-body">
                <div class="show-modal" style="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $('.set-defatult').click(function(){
        var o = $(this);
        var id = o.data('id');
        $.post('<?php  echo $this->createPluginWebUrl("suppliermenu/ad_demo",array('op'=>'set'))?>',{id:id},function(e){
            if (e.status == 1) {
                $('.set-defatult').each(function(i,e){
                    if ($(e).hasClass('label-danger')) {
                        $(e).removeClass('label-danger').addClass('label-success').html('设为默认');
                    }        
                    o.removeClass('label-success').addClass('label-danger').html('默认');     
                });
            }else{
                alert('error');
            }
        },'json');
    });

    function preview(json){
        var html = '';
        for (var i = 0; i < json.length; i++) {
            html+='<img src="'+json[i]+'" alt="" width="100%">';
        }
        $('.show-modal').html(html);
        $('#modal').modal('toggle');
    }

</script>


<?php  } else if($_GPC['op'] == 'adddemo') { ?>
<div class="panel-heading">广告模板管理</div>

<div class='panel-body' style="margin-top: 20px;">
        <form action="">
            <input type="hidden" name="c" value="site">
            <input type="hidden" name="a" value="entry">
            <input type="hidden" name="m" value="sz_yi">
            <input type="hidden" name="p" value="suppliermenu">
            <input type="hidden" name="do" value="plugin">
            <input type="hidden" name="op" value="doadd">
            <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>">
            <input type="hidden" name="method" value="ad_demo">
        <div class="form-group">
            <label class="col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>模板名称</label>
            <div class="col-sm-6 col-md-7">
                <input type="text" name="title" id="title" class="form-control" value="<?php  echo $item['title'];?>" />
            </div>
            <br clear='both'>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label class="col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>模板类型</label>
            <div class="col-sm-6 col-md-7">
                <select name="type" class="form-control">
                    <option <?php  if($item['type'] == 1) { ?>selected<?php  } ?> value="1">系统</option>
                    <option  <?php  if($item['type'] == 2) { ?>selected<?php  } ?> value="2">用户</option>
                </select>
            </div>
            <br clear='both'>
        </div>


        <div class="form-group">
            <label class="col-sm-3 col-md-2 control-label">模版图片</label>
            <div class="col-sm-6 col-md-7">
                <?php  if(empty($piclist)) { ?>         
                <?php  echo tpl_form_field_multi_image('thumbs',$item['thumb'])?>
                <span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
                <?php  } else { ?>
                <?php  if(is_array($item['thumb'])) { foreach($item['thumb'] as $p) { ?>
                <a href='<?php  echo tomedia($p)?>' target='_blank'>
                    <img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
                </a>
                <?php  } } ?>
                <?php  } ?>
            </div>      
            <br clear='both'>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>模板状态</label>
            <div class="col-sm-6 col-md-7">         
                 <select name="status" class="form-control">
                    <option <?php  if($item['status'] == 1) { ?>selected<?php  } ?> value="1">启用</option>
                    <option <?php  if($item['status'] == 2) { ?>selected<?php  } ?> value="2">禁用</option>
                </select>
            </div>
            <br clear='both'>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>是否纯视频</label>
            <div class="col-sm-6 col-md-7"> 
                <select name="video" class="form-control">
                    <option <?php  if($item['video'] == 1) { ?>selected<?php  } ?> value="1">是</option>                  
                    <option <?php  if($item['video'] == 2) { ?>selected<?php  } ?> value="2" selected>否</option>
                </select>
            </div>
            <br clear='both'>
        </div>


       <!--  <div class="form-group " style="display:none;">
            <label class="col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>视频链接</label>
            <div class="col-sm-6 col-md-7">
                <input type="text" name="link" id="link" class="form-control" value="<?php  echo $item['link'];?>" />
            </div>
            <br clear='both'>
        </div> -->


        <input type="submit" class="btn btn-primary col-sm-2 col-sm-offset-4" name="" value='提交'>
    </form>
</div>
<script type="text/javascript">

    // $('[name="video"]').change(function(){
    //     var o=$(this);
    //     if (o.val() == 1) {
    //         o.parents('.form-group').next('div').show();
    //     }else{
    //         o.parents('.form-group').next('div').hide();
    //     }
    // });

</script>
<?php  } ?>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>