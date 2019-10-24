<?php defined('IN_IA') or exit('Access Denied');?>﻿<style type="text/css">
    .border_bg{ border: 1px #ccc solid;border-radius: 4px; margin-bottom: 10px; background-color: #fff;}
    .border_bg .panel-heading{ background-color: #e8ecef; color: #000; }
    .border_bg .form-control>div{margin-bottom: 10px;}
    span.help-block>b{color:red;}
</style>
<div class="border_bg">
    <!--<div class='panel-heading'>基本设置</div>-->
    <div class='panel-body'>
        <!--<div class="form-group">-->
            <!--<label class="col-sm-3 col-md-2 control-label">排序</label>-->
            <!--<div class="col-sm-9 col-md-8">-->
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <!--<input type="text" name="displayorder" id="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />-->
                    <!--<span class='help-block'>数字越大，排名越靠前,如果为空，<b>默认排序方式为创建时间</b></span>-->
                    <?php  } else { ?>
                    <!--<div class='form-control-static'><?php  echo $item['displayorder'];?></div>-->
                    <?php  } ?>
                <!--</div>-->
        <!--</div>-->
        <?php  if(p('supplier')) { ?>
        <?php  if($perm_role == 0) { ?>
        <div class="form-group">
                <label class="col-sm-3 col-md-2 control-label">供货商</label>
                <div class="col-sm-9 col-md-8">
                    <select name='supplier_uid' class='form-control'>
                        <option value="">请选择供货商</option>
                        <?php  if(is_array($result)) { foreach($result as $row) { ?>
                        <option value="<?php  echo $row['uid'];?>" <?php  if($item['supplier_uid']==$row['uid']) { ?>selected="selected"<?php  } ?>><?php  echo $row['realname'];?>/<?php  echo $row['username'];?></option>
                        <?php  } } ?>
                    </select>
                    <span class='help-block'>选择供货商</span>
                </div>
        </div>
        <?php  } else { ?>
        <input type="hidden" name="supplier_uid" value="<?php  echo $_W['uid'];?>">
        <?php  } ?>
        <?php  } ?>
    </div>
</div>
<div class="border_bg" style="margin-top: 20px;">
    <div class='panel-heading'>商品设置</div>
    <div class='panel-body'>
        <!--<div class="form-group">-->
            <!--<label class="col-sm-3 col-md-2 control-label">商品编号</label>-->
            <!--<div class="col-sm-9 col-md-8">-->
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <!--<input type="text" name="goodssn" id="goodssn" class="form-control" value="<?php  echo $item['goodssn'];?>" />-->
                <?php  } else { ?>
                <!--<div class='form-control-static'><?php  echo $item['goodssn'];?></div>-->
                <?php  } ?>
            <!--</div>-->
        <!--</div>-->

        <!--<div class="form-group">-->
            <!--<label class="col-sm-3 col-md-2 control-label">商品单位</label>-->
            <!--<div class="col-sm-9 col-md-8">-->
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <!--<input type="text" name="unit" id="unit" class="form-control" value="<?php  echo $item['unit'];?>" />-->
                <!--<span class="help-block">如: 个/件/包</span>-->
                <?php  } else { ?>
                <!--<div class='form-control-static'><?php  echo $item['unit'];?></div>-->
                <?php  } ?>
            <!--</div>-->
        <!--</div>-->

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>商品名称</label>
            <div class="col-sm-9 col-md-8">
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <input type="text" name="goodsname" id="goodsname" class="form-control" value="<?php  echo $item['title'];?>" />
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['title'];?></div>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group ">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>发货方式</label>
            <div class="col-sm-9 col-md-8">
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <label for="cbxLocaleFlag">现场交易
                    <input type="checkbox" id="cbxLocaleFlag" name="LocalFlag" value="1">
                </label>
                <label for="cbxPostFlag">快递发货
                    <?php  if($item['PostFlag']== '1') { ?>
                    <input type="checkbox" id="cbxPostFlag" checked name="PostFlag" value="1">
                    <?php  } else { ?>
                    <input type="checkbox" id="cbxPostFlag" name="PostFlag" value="1">
                    <?php  } ?>
                </label>
                <label for="cbxLocaleFlag">包邮
                    <input type="checkbox" id="cbxLocaleFlag"   <?php  if($item['send'] == 1) { ?>checked<?php  } ?> name="send" value="1">
                </label>
                <div class="form-group addLocalFlag" style="display: none">
                    <input type="hidden" name="storeids" value="<?php  echo $item['storeids'];?>" >
                    <table class="table table-hover table-responsive" >
                        <thead>
                        <tr>
                            [<button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus').modal();">选择兑换点</button>]
                            <th style="width: 20%">兑换点名称</th>
                            <th style="width: 35%">兑换点地址</th>
                            <th style="width: 10%">联系电话</th>
                            <th style="width: 20%">兑换日期</th>
                            <th style="width: 10%">兑换时间</th>
                            <th style="width: 5%">操作</th>
                        </tr>
                        </thead>
                        <tbody style="text-align:left;" class="exchange">
                            <?php  if(!empty($stores)) { ?>
                                <?php  if(is_array($stores)) { foreach($stores as $k => $v) { ?>
                                    <tr data-id="<?php  echo $v['id'];?>">
                                        <td><?php  echo $v['title'];?></td>
                                        <td><?php  echo $v['address'];?></td>
                                        <td><?php  echo $v['mobile'];?></td>
                                        <td><?php  echo $v['exchangeDate'];?></td>
                                        <td><?php  echo $v['exchangeTime'];?></td>
                                        <td><span type="button" class="fa fa-trash-o"></span></td>
                                    </tr>
                                <?php  } } ?>
                            <?php  } ?>
                        </tbody>
                    </table>
                </div>
                <script>
                    var storeids=<?php  echo json_encode(explode(',',$item['storeids']))?>;
                    function myinarray(val,arr){            //自己的inarray 如果存在返回假 否则true
                        for(var i in arr){
                            if(arr[i]==val){
                                return false;
                            }
                        }
                        return true;
                    }

                    function select_saler(obj){         //选择兑换点
                        var str='';
                        if (myinarray(obj.id,storeids)){
                            storeids.push(obj.id);
                            str+='<tr data-id="'+obj.id+'">';
                            str+='<td>'+obj.title+'</td>';
                            str+='<td>'+obj.address+'</td>';
                            str+='<td>'+obj.mobile+'</td>';
                            str+='<td>'+obj.exchangeDate+'</td>';
                            str+='<td>'+obj.exchangeTime+'</td>';
                            str+='<td><span type="button" class="fa fa-trash-o"></span></td>';
                            str+='</tr>';
                            $('[name="storeids"]').val(storeids.join(','));     //use ',' explode array
                            $('.exchange').append(str);         //列表
                        }else{
                            alert('兑换点已存在');
                        }
                    }

                    $('.exchange').on('click','span',function(){
                        var parent=$(this).parents('tr');
                        var id=parent.data('id');
                        storeids.splice($.inArray(id,storeids),1);
                        $('[name="storeids"]').val(storeids.join(','));
                        parent.remove();
                    });

                </script>

                <div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
                    <div class="modal-dialog" style="width: 920px;">
                        <div class="modal-content">
                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择兑换点</h3></div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="请输入兑换点名称或联系电话">
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
                        $.post("<?php  echo $this->createPluginWebUrl('suppliermenu/store',array('op'=>'query'))?>", {
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
                        html+='<input type="hidden" value="' + o.id +'" name="storeids[]">';
                        html+='<input type="text" value="' + o.storename +'" readonly="" class="form-control">';
                        html+='<div class="input-group-btn"><button type="button" onclick="remove_store(this)" class="btn btn-default"><i class="fa fa-remove"></i></button></div>';
                        html+='</div></div>';
                        $('#stores').append(html);
                    }

                </script>

                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['title'];?></div>
                <?php  } ?>
            </div>
        </div>
        <script>
            var ischecked=true;

            $('#cbxLocaleFlag').click(function () {
                if (ischecked){
                    ischecked=false;
                    $('.addLocalFlag').show();
                }else{
                    ischecked=true;
                    $('.addLocalFlag').hide();
                }
            });
            <?php  if($item['LocalFlag']== '1') { ?>
            $('#cbxLocaleFlag').click();
            <?php  } ?>
        </script>

        <div class="form-group">
            <label class="col-sm-3 col-md-2 control-label">标题图片</label>
            <div class="col-sm-9 col-md-8">
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <?php  echo tpl_form_field_multi_image('headPic',$headPics)?>
                <span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
                <?php  } else { ?>
                <?php  if(is_array($headPics)) { foreach($headPics as $p) { ?>
                <a href='<?php  echo tomedia($p)?>' target='_blank'>
                    <img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
                </a>
                <?php  } } ?>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 col-md-2 control-label">介绍图片</label>
            <div class="col-sm-9 col-md-8">
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <?php  echo tpl_form_field_multi_image('thumbs',$piclist)?>
                <span class="help-block">建议尺寸: 640 * 640 ，或正方型图片 </span>
                <?php  } else { ?>
                <?php  if(is_array($piclist)) { foreach($piclist as $p) { ?>
                <a href='<?php  echo tomedia($p)?>' target='_blank'>
                    <img src="<?php  echo tomedia($p)?>" style='height:100px;border:1px solid #ccc;padding:1px;float:left;margin-right:5px;' />
                </a>
                <?php  } } ?>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>商品分类</label>
            <div class="col-sm-9 col-md-4">
                <?php  if($shopset['catlevel']==3) { ?>
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <?php  echo tpl_form_field_category_level3('category', $parent, $children, $item['pcate'], $item['ccate'], $item['tcate'])?>
                <?php  } else { ?>
                <div class='form-control-static'>
                    <?php  echo pdo_fetchcolumn('select name from '.tablename('sz_yi_category').' where id=:id limit 1',array(':id'=>$item['pcate']))?> -
                    <?php  echo pdo_fetchcolumn('select name from '.tablename('sz_yi_category').' where id=:id limit 1',array(':id'=>$item['ccate']))?> -
                    <?php  echo pdo_fetchcolumn('select name from '.tablename('sz_yi_category').' where id=:id limit 1',array(':id'=>$item['tcate']))?>
                </div>
                <?php  } ?>

                <?php  } else { ?>
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <?php  echo tpl_form_field_category_level2('category', $parent, $children, $item['pcate'], $item['ccate'])?>
                <?php  } else { ?>
                <div class='form-control-static'>
                    <?php  echo pdo_fetchcolumn('select name from '.tablename('sz_yi_category').' where id=:id limit 1',array(':id'=>$item['pcate']))?> -
                    <?php  echo pdo_fetchcolumn('select name from '.tablename('sz_yi_category').' where id=:id limit 1',array(':id'=>$item['ccate']))?>
                </div>
                <?php  } ?>
                <?php  } ?>
            </div>
            <div class="col-sm-9 col-md-5">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">其他分类</label>
                <div class="col-sm-6 col-xs-12">

                    <?php if( ce('shop.goods' ,$item) ) { ?>

                    <select id="cates" name='cates[]' class="form-control" multiple="" >
                        <?php  if(intval($shopset['catlevel'])==3) { ?>
                        <?php  if(is_array($category)) { foreach($category as $p) { ?>
                        <?php  if(empty($p['parentid'])) { ?>
                        <?php  if(is_array($children[$p['id']])) { foreach($children[$p['id']] as $c) { ?>
                        <?php  if(is_array($children[$c['id']])) { foreach($children[$c['id']] as $t) { ?>
                        <option value="<?php  echo $t['id'];?>" <?php  if(is_array($cates) && in_array($t['id'],$cates)) { ?>selected<?php  } ?> ><?php  echo $p['name'];?>-<?php  echo $c['name'];?>-<?php  echo $t['name'];?></option>
                        <?php  } } ?>
                        <?php  } } ?>
                        <?php  } ?>
                        <?php  } } ?>
                        <?php  } else { ?>
                        <?php  if(is_array($category)) { foreach($category as $p) { ?>
                        <?php  if(empty($p['parentid'])) { ?>
                        <?php  if(is_array($children[$p['id']])) { foreach($children[$p['id']] as $c) { ?>
                        <option value="<?php  echo $c['id'];?>" <?php  if(is_array($cates) && in_array($c['id'],$cates)) { ?>selected<?php  } ?> ><?php  echo $p['name'];?>-<?php  echo $c['name'];?></option>
                        <?php  } } ?>
                        <?php  } ?>
                        <?php  } } ?>
                        <?php  } ?>
                    </select>

                    <?php  } else { ?>

                    <div class='form-control-static'>
                        <?php  if(intval($shopset['catlevel'])==3) { ?>
                        <?php  if(is_array($category)) { foreach($category as $p) { ?>
                        <?php  if(empty($p['parentid'])) { ?>
                        <?php  if(is_array($children[$p['id']])) { foreach($children[$p['id']] as $c) { ?>
                        <?php  if(is_array($children[$c['id']])) { foreach($children[$c['id']] as $t) { ?>
                        <?php  if(is_array($cates) && in_array($t['id'],$cates)) { ?><?php  echo $p['name'];?>-<?php  echo $c['name'];?>-<?php  echo $t['name'];?>; <?php  } ?>
                        <?php  } } ?>
                        <?php  } } ?>
                        <?php  } ?>
                        <?php  } } ?>
                        <?php  } else { ?>
                        <?php  if(is_array($category)) { foreach($category as $p) { ?>
                        <?php  if(empty($p['parentid'])) { ?>
                        <?php  if(is_array($children[$p['id']])) { foreach($children[$p['id']] as $c) { ?>
                        <?php  if(is_array($cates) && in_array($c['id'],$cates)) { ?><?php  echo $p['name'];?>-<?php  echo $c['name'];?>; <?php  } ?>
                        <?php  } } ?>
                        <?php  } ?>
                        <?php  } } ?>
                        <?php  } ?>
                    </div>
                    <?php  } ?>

                </div>
            </div>
        </div>

        <!--<div class="form-group">-->
            <!--<label class="col-sm-3 col-md-2 control-label">重量</label>-->
            <!--<div class="col-sm-9 col-md-3">-->
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <!--<div class="input-group">-->
                    <!--<input type="text" name="weight" id="weight" class="form-control" value="<?php  echo $item['weight'];?>" />-->
                    <!--<span class="input-group-addon">克</span>-->
                <!--</div>-->
                <?php  } else { ?>
                <!--<div class='form-control-static'><?php  echo $item['weight'];?> 克</div>-->
                <?php  } ?>
            <!--</div>-->
            <!--<div class='help-block'>商品重量设置空或0，则为包邮，<b>如启用多规格，多规格内也需进行设置</b></div>-->
        <!--</div>-->
        <!--<div class="form-group">-->
            <!--<label class="col-sm-3 col-md-2 control-label">库存</label>-->
            <!--<div class="col-sm-9 col-md-3">-->
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <!--<div class="input-group">-->
                    <!--<input type="text" name="total" id="total" class="form-control" value="<?php  echo $item['total'];?>" />-->
                    <!--<span class="input-group-addon">件</span>-->
                <!--</div>-->
                <?php  } else { ?>
                <!--<div class='form-control-static'><?php  echo $item['total'];?> 件</div>-->
                <?php  } ?>
            <!--</div>-->
            <!--<span class="help-block">商品的剩余数量,<b>如启用多规格<?php  if(p('virtual')) { ?>或为虚拟卡密产品<?php  } ?>，则此处设置无效，请移至“商品规格”<?php  if(p('virtual')) { ?>或“虚拟物品插件”<?php  } ?>中设置</b> </span>-->
        <!--</div>-->
        <!--<div class="form-group">-->
            <!--<label class="col-sm-3 col-md-2 control-label">减库存方式</label>-->
            <!--<div class="col-sm-9 col-md-8">-->
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <!--<label for="totalcnf1" class="radio-inline"><input type="radio" name="totalcnf" value="0" id="totalcnf1" <?php  if(empty($item) || $item['totalcnf'] == 0) { ?>checked="true"<?php  } ?> /> 拍下减库存</label>-->
                <!--&nbsp;&nbsp;&nbsp;-->
                <!--<label for="totalcnf2" class="radio-inline"><input type="radio" name="totalcnf" value="1" id="totalcnf2"  <?php  if(!empty($item) && $item['totalcnf'] == 1) { ?>checked="true"<?php  } ?> /> 付款减库存</label>-->
                <!--&nbsp;&nbsp;&nbsp;-->
                <!--<label for="totalcnf3" class="radio-inline"><input type="radio" name="totalcnf" value="2" id="totalcnf3"  <?php  if(!empty($item) && $item['totalcnf'] == 2) { ?>checked="true"<?php  } ?> /> 永不减库存</label>-->
                <?php  } else { ?>

                <!--<div class='form-control-static'>-->
                    <?php  if(empty($item) || $item['totalcnf'] == 0) { ?>拍下减库存<?php  } ?>
                    <?php  if(!empty($item) && $item['totalcnf'] == 1) { ?>付款减库存<?php  } ?>
                    <?php  if(!empty($item) && $item['totalcnf'] == 2) { ?>永不减库存<?php  } ?>
                <!--</div>-->

                <?php  } ?>
            <!--</div>-->
        <!--</div>-->
        <div class="form-group" <?php  if(($item['type'] == 2 || $item['type'] == 3)) { ?>style="display: none;"<?php  } ?> style="margin-top: 10px;">

        <div class="form-group">
            <label class="col-sm-3 col-md-2 control-label">运费设置</label>
            <div class="col-md-10 col-lg-5">
                <?php if( ce('shop.goods' ,$item) ) { ?>
                <label class="radio-inline" style="float: left;"><input type="radio" name="dispatchtype" value="1" <?php  if($item['dispatchtype'] == 1) { ?>checked="true"<?php  } ?>  /> 统一邮费</label>
                <div class="input-group form-group" style="width: 180px; float: left;">
                    <input type="text" name="dispatchprice" style="margin:0 10px;" id="dispatchprice" class="form-control" value="<?php  echo $item['dispatchprice'];?>" />
                    <span class="input-group-addon">元</span>
                </div>

                <label class="radio-inline" style="float: left;margin-left: 10px;"><input type="radio" name="dispatchtype" value="0" <?php  if(empty($item['dispatchtype'])) { ?>checked="true"<?php  } ?>   /> 运费模板</label>
                <div style="width: auto; float: left; margin-left: 10px;"  id="type_dispatch">
                    <select class="form-control tpl-category-parent" id="dispatchid" name="dispatchid">
                        <option value="0">选择模板</option>
                        <?php  if(is_array($dispatch_data)) { foreach($dispatch_data as $dispatch_item) { ?>
                        <option value="<?php  echo $dispatch_item['id'];?>" <?php  if($item['dispatchid'] == $dispatch_item['id']) { ?>selected="true"<?php  } ?>><?php  echo $dispatch_item['dispatchname'];?></option>
                        <?php  } } ?>
                    </select>
                </div>

                <?php  } else { ?>
                <div class='form-control-static' style="margin-left: 10px;"><?php  if(empty($item['dispatchtype'])) { ?>运费模板 <?php  if($item['dispatchid'] == 0) { ?>默认模板<?php  } else { ?><?php  if(is_array($dispatch_data)) { foreach($dispatch_data as $dispatch_item) { ?><?php  if($item['dispatchid'] == $dispatch_item['id']) { ?><?php  echo $dispatch_item['dispatchname'];?><?php  } ?><?php  } } ?><?php  } ?><?php  } else { ?>统一邮费<?php  } ?></div>
                <?php  } ?>
            </div>

        </div>

        <link href="../addons/sz_yi/static/js/dist/select2/select2.css" rel="stylesheet">
        <link href="../addons/sz_yi/static/js/dist/select2/select2-bootstrap.css" rel="stylesheet">
        <script language="javascript" src="../addons/sz_yi/static/js/dist/select2/select2.min.js"></script>
        <script language="javascript" src="../addons/sz_yi/static/js/dist/select2/select2_locale_zh-CN.js"></script>

        <script language="javascript">
            $(function(){
                $('[name="category[parentid]"]').val('1061').change();
                $('#cates').select2({
                    search:true,
                    placeholder: "请选择其他商品分类",
                    allowClear: true
                });
            })
        </script>

    </div>
</div>
</div>
<div class="border_bg" style="margin-top: 20px;">
    <div class='panel-heading'>功能设置</div>
    <div class='panel-body'>

        <div class="form-group">
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">用户最多购买量</label>

                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <div class="input-group">
                        <input type="text" name="usermaxbuy" class="form-control" value="<?php  echo $item['usermaxbuy'];?>" />
                        <span class="input-group-addon">件</span>
                    </div>
                    <span class="help-block">用户购买过的此商品数量限制</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['usermaxbuy'];?> 件</div>
                    <?php  } ?>

                </div>
            </div>

            <div class="col-lg-5 col-md-12">
                <label class="col-xs-12 col-sm-4 col-md-3 control-label">单次最多购买量</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <div class="input-group">
                        <input type="text" name="maxbuy" id="maxbuy" class="form-control" value="<?php  echo $item['maxbuy'];?>" />
                        <span class="input-group-addon">件</span>
                    </div>
                    <span class="help-block">用户单次购买此商品数量限制</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['maxbuy'];?> 件</div>
                    <?php  } ?>
                </div>
            </div>

        </div>

        <!-- <div class="form-group">
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">用户最多购买量</label>

                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <div class="input-group">
                        <input type="text" name="usermaxbuy" class="form-control" value="<?php  echo $item['usermaxbuy'];?>" />
                        <span class="input-group-addon">件</span>
                    </div>
                    <span class="help-block">用户购买过的此商品数量限制</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['usermaxbuy'];?> 件</div>
                    <?php  } ?>

                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-12 col-sm-4 col-md-3 control-label">赠送积分</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <div class="input-group">
                        <input type="text" name="credit" id="credit" class="form-control" value="<?php  echo $item['credit'];?>" />
                        <span class="input-group-addon">分</span>
                    </div>
                    <p class="help-block">会员购物赠送的积分, 如果不填写或填写0，则默认为不赠送积分，如果带%则为按成交价格的比例计算积分</p>
                    <p class="help-block">如: 购买2件，设置10 积分, 不管成交价格是多少， 则购买后获得20积分</p>
                    <p class="help-block">如: 购买2件，设置10%积分, 成交价格2 * 200= 400， 则购买后获得 40 积分（400*10%）</p>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['credit'];?> 分</div>
                    <?php  } ?>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-12 col-sm-4 col-md-3 control-label">已出售数</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <div class="input-group">
                        <input type="text" name="sales" id="sales" class="form-control" value="<?php  echo $item['sales'];?>" />
                        <span class="input-group-addon">件</span>
                    </div>
                    <span class="help-block">物品虚拟出售数，会员下单此数据就增加, 无论是否支付</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['sales'];?> 件</div>
                    <?php  } ?>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-12 col-sm-4 col-md-3 control-label">单次最多购买量</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <div class="input-group">
                        <input type="text" name="maxbuy" id="maxbuy" class="form-control" value="<?php  echo $item['maxbuy'];?>" />
                        <span class="input-group-addon">件</span>
                    </div>
                    <span class="help-block">用户单次购买此商品数量限制</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['maxbuy'];?> 件</div>
                    <?php  } ?>
                </div>
            </div>
            <?php  if($com_set['upgrade_by_good'] && !empty($com_set['level'])) { ?>
            <div class="col-lg-5 col-md-12" >
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">分销商</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <select name='commission_level_id' class='form-control'>
                        <option value="0">请选择分销商等级</option>
                        <?php  if(is_array($commissionLevels)) { foreach($commissionLevels as $level) { ?>
                        <option value="<?php  echo $level['id'];?>" <?php  if($item['commission_level_id'] == $level['id']) { ?>selected="selected"<?php  } ?>><?php  echo $level['levelname'];?></option>
                        <?php  } } ?>
                    </select>
                    <span class='help-block'>购买此商品成为指定的分销商等级</span>
                </div>
            </div>
            <?php  } ?>
        </div> -->
        <!-- 全返开关 begin -->
        <!-- From:LuckStar.D    Date:2016/04/27   Content:加入全返开关,  ims_sz_yi_goods 加入isreturn字段-->

        <div class="form-group">
            <?php  if($set_return['isqueue']) { ?>
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">是否全返</label>
                <div class="col-xs-7 col-sm-8 col-md-9">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <label class="radio-inline"><input type="radio" name="isreturn" value="1" <?php  if($item['isreturn'] == 1) { ?>checked="true"<?php  } ?>  /> 是</label>
                    <label class="radio-inline"><input type="radio" name="isreturn" value="0" <?php  if($item['isreturn'] == 0) { ?>checked="true"<?php  } ?>  /> 否</label>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  if($item['isreturn']) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>
			<?php  } ?>
           <!-- <div class="col-lg-5 col-md-12" >
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">是否排列全返</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <label class="radio-inline"><input type="radio" name="isreturnqueue" value="1" <?php  if($item['isreturnqueue'] == 1) { ?>checked="true"<?php  } ?>  /> 是</label>
                    <label class="radio-inline"><input type="radio" name="isreturnqueue" value="0" <?php  if($item['isreturnqueue'] == 0) { ?>checked="true"<?php  } ?>  /> 否</label>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  if($item['isreturn']) { ?>是<?php  } else { ?>否<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>-->
			<?php  if($set_returnmatter['returnmatter']) { ?>
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">是否购物返</label>
                <div class="col-xs-7 col-sm-8 col-md-9">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
					<label class="radio-inline"><input type="radio" name="returnmatter" value="1" <?php  if($item['returnmatter'] == 1) { ?>checked="true"<?php  } ?>  /> 是</label>
					<label class="radio-inline"><input type="radio" name="returnmatter" value="0" <?php  if($item['returnmatter'] == 0) { ?>checked="true"<?php  } ?>  /> 否</label>
					   <?php  } else { ?>
					   <div class='form-control-static'><?php  if($item['isreturn']) { ?>是<?php  } else { ?>否<?php  } ?></div>
					 <?php  } ?>
                </div>
            </div>

            <?php  } ?>


            <div class="col-lg-5 col-md-12 ">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">上架方式</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <?php  if($item['shelves'] == 1) { ?>
                    <label for="isshow1" class="radio-inline"><input type="radio" checked name="shelves" value="1" id="isshow1" /> 审核后上架</label>
                    <?php  } else { ?>
                    <label for="isshow1" class="radio-inline"><input type="radio" checked name="shelves" value="1" id="isshow1" /> 审核后上架</label>
                    <?php  } ?>&nbsp;&nbsp;&nbsp;
                    <label for="isshow2" class="radio-inline">
                        <?php  if($item['shelves'] == '2') { ?>
                        <input type="radio" name="shelves" checked value="2" id="isshow2" /> 指定日期上架
                        <?php  } else { ?>
                        <input type="radio" name="shelves" value="2" id="isshow2" /> 指定日期上架
                        <?php  } ?>
                        <div style="display: inline-block;">
                            <?php echo sz_tpl_form_field_date('startShelves', !empty($item['startShelves']) ? date('Y-m-d H:i',$item['startShelves']) : date('Y-m-d H:i'), 1)?>
                        </div>
                    </label>
                    <span class="help-block"></span>

                    <?php  } else { ?>
                    <div class='form-control-static'><?php  if(empty($item['status'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                    <?php  } ?>

                </div>
            </div>
            <div class="col-lg-5 col-md-12 ">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">下架方式</label>
                <div class="col-xs-7 col-sm-8 col-md-8">
                    <?php if( ce('shop.goods' ,$item) ) { ?>
                    <label for="isshow1" class="radio-inline"><input checked type="radio" name="xiajia" value="3" id="isshow1" <?php  if($item['status'] == 1) { ?>checked="true"<?php  } ?> /> 售完下架</label>
                    <span class="help-block"></span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  if(empty($item['status'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                    <?php  } ?>

                </div>
            </div>

    </div>
    <div class="form-group">
            <div class="col-lg-5 col-md-12">
                <label class="col-xs-5 col-sm-4 col-md-3 control-label">商家承诺:</label>
                <div class="col-xs-7 col-sm-8 col-md-9">
                    <input type="checkbox" name="protocol" value="1">
                    <label>同意<a id="protocol" style="color:#00B2EE">《<?php  echo $sets['bart']['protocol']['0']['title'];?>》</a></label>
                </div>

            </div>
</div>
        </div>
</div>
<script type="text/javascript">
    $('#protocol').click(function(){
        $('#protocol-modal').modal('toggle');
    });
</script>
<div id="protocol-modal" class="modal fade" tabindex="-1" aria-hidden="false" >
                    <div class="modal-dialog" style="width: 920px;">
                        <div class="modal-content">
                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3><?php  echo $sets['bart']['protocol']['0']['title'];?></h3></div>
                            <div class="modal-body">
                                <?php  echo html_entity_decode($sets['bart']['protocol']['0']['content'])?>
                            </div>
                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                        </div>

                    </div>
                </div>
