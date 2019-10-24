<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div id="mainlist">
    <div class="panel panel-info">
        <div class="panel-heading">按时间查询销售数量和销售金额</div>
        <div class="panel-body">
            <form action="" method="get" class="form-horizontal"  id="form1">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="method" value="dealmerch_stockchange" />
                <input type="hidden" name="p" value="suppliermenu" />
                <input type="hidden" name="do" value="plugin" />
                <input type="hidden" name="m" value="sz_yi" />
                <div class="form-group">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">
                        <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">商品编号</label>
                        <div class="col-sm-6 col-lg-2 col-xs-12">
                            <input type="text" class="form-control" name="goodssn" value="<?php  echo $_GPC['goodssn'];?>">
                        </div>
                        <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">商品名称</label>
                        <div class="col-sm6 col-lg-2 col-xs-12">
                            <input type="text" class="form-control" name="goodsname" value="<?php  echo $_GPC['goodsname'];?>">
                        </div>
                        <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">规格型号</label>
                        <div class="col-sm-6 col-lg-2 col-xs-12">
                            <input type="text" class="form-control" name="optitle" value="<?php  echo $_GPC['optitle'];?>" placeholder="请输入规格型号">
                        </div>
                        <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">操作类型</label>
                        <div class="col-sm-6 col-lg-2 col-xs-12">
                            <select name="type">
                                    <option value="0" <?php  if($_GPC['type']== 0) { ?>selected<?php  } ?>>全部</option>
                                    <option value="2" <?php  if($_GPC['type']== 2) { ?>selected<?php  } ?>>库存调整</option>
                                    <option value="3" <?php  if($_GPC['type']== 3) { ?>selected<?php  } ?>>用户付款</option>
                                    <option value="4" <?php  if($_GPC['type']== 4) { ?>selected<?php  } ?>>取消交易</option>
                                    <option value="5" <?php  if($_GPC['type']== 5) { ?>selected<?php  } ?>>商品下架</option>
                                    <option value="6" <?php  if($_GPC['type']== 6) { ?>selected<?php  } ?>>定向易货冻结</option>
                                    <option value="7" <?php  if($_GPC['type']== 7) { ?>selected<?php  } ?>>定向易货取消冻结</option>
                                    <option value="8" <?php  if($_GPC['type']== 8) { ?>selected<?php  } ?>>定向易货出库</option>
                            </select>
                        </div>
                          <label class="col-xs-12 col-sm-2 col-md-3 col-lg-1 control-label">交易日期</label>
                            <div class="col-sm-8 col-lg-2 col-xs-12">
                                <?php  echo tpl_form_field_daterange('datetime', array('starttime'=>date('Y-m-d',$starttime), 'endtime'=>date('Y-m-d',$endtime)), true)?>
                                <!-- <input type="text" class="form-control" name="datetime" value=""> -->
                                <label class="radio-inline"><input type="radio" name="searchtime" value="0" <?php  if(empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>不搜索</label>
                                <label class="radio-inline"><input type="radio" name="searchtime" value="1" <?php  if(!empty($_GPC['searchtime'])) { ?>checked<?php  } ?>>搜索</label>
                            </div> 
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <button class="btn btn-default" id="search"><i class="fa fa-search"></i> 搜索</button>
                        <button class="btn btn-default" type="reset" id="reset"><i class="fa fa-trash-o"></i> 清空</button>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <!-- <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel panel-default">
        <!-- <div class="panel-heading">共计 <span style="color:red; "><?php  echo $totalcount;?></span> 条记录 </div> -->
        <div class="">
            <table class="table table-hover">
                <thead class="navbar-inner">
                    <tr>
                        <th style="width:30%">商品id</th>
                        <th style="width:30%">商品编号</th>
                        <th style="width:30%">商品名称</th>
                        <th style="width:30%">数量</th>
                        <!-- <th style="w3dth:20%">商品类型</th> -->
                        <th style="width:30%">交易日期</th>
                        <th style="width:30%">规格型号</th>
                        <th style="width:30%">操作类型</th>
                        <!-- <th style="width:20%">操作</th> -->
                    </tr>
                </thead>
                <tbody>
                <?php  if(is_array($info)) { foreach($info as $row) { ?>
                    <tr style="background: #eee">
                        <td><?php  echo $row['goodsid'];?></td>
                        <td><?php  echo $row['goodssn'];?></td>
                        <td><?php  echo $row['goodsname'];?></td>
                        <td><?php  echo $row['stock'];?></td>
                        <td><?php  echo date('Y-m-d H:i:s',$row['dealtime'])?></td>
                        <td><?php  echo $row['optiontitle'];?></td>
                        <td>
                            <?php  if($row['type']==2) { ?>库存调整
                            <?php  } else if($row['type']==3) { ?>用户付款
                            <?php  } else if($row['type']==4) { ?>取消交易
                            <?php  } else if($row['type']==5) { ?>商品下架
                            <?php  } else if($row['type']==6) { ?>定向易货冻结
                            <?php  } else if($row['type']==7) { ?>定向易货取消冻结
                            <?php  } else if($row['type']==8) { ?>定向易货出库
                            <?php  } else { ?>全部
                            <?php  } ?>
                        </td>
                        <!-- <td> <span href="" data-id="<?php  echo $row['id'];?>">编辑</span> </td> -->
                    </tr>
                    <!-- <tr><td colspan="15">
                            <?php  if(is_array($row['optionid'])) { foreach($row['optionid'] as $g) { ?>
                            <table style="width:200px;float:left;margin:10px 10px 0 10px;" title="<?php  echo $g['title'];?>">
                                <tr>
                                    <td style="width:60px;"><img src="<?php  echo tomedia($g['thumb'])?>" style="width: 50px; height:0px;border:1px solid #ccc;padding:1px;"></td>
                                    <td style="width: 20%">
                                        规格型号:<?php  echo $g['title'];?> <br/>
                                        销售数量: <?php  echo $g['sales'];?><br/>
                                        销售收入: <strong><?php  echo $g['salesincome'];?></strong>
                                    </td>
                                </tr>
                            </table>
                            <?php  } } ?>
                        </td>
                    </tr> -->
                <?php  } } ?>
            </table>
            <?php  echo $pager;?>   

        </div>
    </div>
</div>

<!-- 修改 -->
<form action="" method='post' class='form-horizontal' style="display:none;" id="moredata">
    <input type="hidden" name="id" value="<?php  echo $info['id'];?>">
    <input type="hidden" name="optionid" value="<?php  echo $info['optionid'];?>">
    <input type="hidden" name="optiontitle" value="<?php  echo $info['optiontitle'];?>">
    <input type="hidden" name="goodsid2" value="<?php  echo $info['goodsid'];?>">
    <input type="hidden" name="op" value="post">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="dealmerch" />
    <input type="hidden" name="status" value="edit" />
    <input type="hidden" name="method" value="dealmerch_stockchange" />
    <div class='panel panel-default'>
        <div class='panel-heading' id="tips">修改库存</div>
        <div class='panel-body'>
            <div class="form-group notice">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="col-sm-4">

                        <input type='hidden' id='noticeopenid' name='data[openid]' value="<?php  echo $info['openid'];?>" />
                
                    </div>              </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">商品编号</label>  
                    <div class="col-sm-9 col-xs-12">    
                           <input type="text" name="goodssn" class="form-control"  />
                    </div>  
                </div>              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>  
            <div class="form-group">                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">商品名称</label>  
                    <div class="col-sm-9 col-xs-12">    
                        <div class="input-group" style="left: 15px;">
                            <input type="text" name="goodsname" class="form-control" />
                        </div>
                    </div>              
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">操作类型</label>   
                    <div class="col-sm-9 col-xs-12 week">
                        <input type="radio" id="type0" name="type" value="0"/>全部
                        <input type="radio" id="type1" name="type" value="1"/>初始上架
                        <input type="radio" id="type2" name="type" value="2"/>库存调整
                        <input type="radio" id="type3" name="type" value="3"/>用户付款
                        <input type="radio" id="type4" name="type" value="4"/>取消交易
                        <input type="radio" id="type5" name="type" value="5"/>商品下架
                        <input type="radio" id="type6" name="type" value="6"/>定向易货冻结
                        <input type="radio" id="type7" name="type" value="7"/>定向易货取消冻结
                        <input type="radio" id="type8" name="type" value="8"/>定向易货出库
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group">                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">库存</label>   
                    <div class="col-sm-9 col-xs-12">    
                        <input type="text" name="stock" class="form-control" />
                            <span class='help-block'> /件/个 </span>
                    </div>              </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group"></div>
            <div class="form-group">               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <label class="col-xs-12 col-sm-3 col-md-3 control-label">交易日期</label> 
                    <div class="col-sm-9 col-xs-12">    
                        <?php echo sz_tpl_form_field_date('startShelves', !empty($item['timeend']) ? date('Y-m-d',$item['timeend']) : date('Y-m-d'), 1)?>
                        <!-- <input type="text" name="dealtime" class="form-control" value="<?php  echo $info['dealtime']?>"  /> -->
                    </div>              </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                </div>
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                           <div type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  id="onlyone">提交</div>
                           <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('dealmerch.dealmerch_exchange')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
            </div>
         </div>
    </div>   
</form>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript">
    //编辑
    $('tbody td span').click(function() {
        $('#mainlist').hide();
        $('#moredata').show();
        $('#tips').html('修改商品库存');
        var num = $(this).data('id');
        var obj = $("input[name='type']").val();
        //获取数据
        $.ajax({
            type: "get",
            dataType: "json",
            data: {id: num },
            url: "<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_stockchange', array('op'=>'get'))?>"+"&status=checklog",
            success: function (msg) {
                if (msg.status == 1) {
                    $("input[name='goodssn']").attr({'placeholder': msg.result.goodssn, 'value':  msg.result.goodssn});
                    $("input[name='goodsname']").attr({'placeholder': msg.result.goodsname, 'value': msg.result.goodsname});
                    $("input[name='stock']").attr({'placeholder' : msg.result.stock, 'value': msg.result.stock});
                    $("input[name='dealtime']").attr({'placeholder': msg.result.dealtime, 'value': msg.result.dealtime});
                    $("input[name='goodsid2']").attr({'placeholder': msg.result.goodsid, 'value': msg.result.goodsid});
                    $("input[name='id']").attr('value', msg.result.id);
                    $("input[name='optionid']").attr('value', msg.result.optionid);
                    $("input[name='optiontitle']").attr('value', msg.result.optiontitle);
                    // alert($("[name='type'] :eq(6)").val());
                    if (msg.result.type == 0) {$("#type0").attr("checked", true); }
                    else if (msg.result.type == 1) { $("#type1").attr('checked',true); }
                    else if (msg.result.type == 2) { $("#type2").attr('checked',true); }
                    else if (msg.result.type == 3) { $("#type3").attr('checked',true); }
                    else if (msg.result.type == 4) { $("#type4").attr('checked',true); }
                    else if (msg.result.type == 5) { $("#type5").attr('checked',true); }
                    else if (msg.result.type == 6) { $("#type6").attr('checked',true); }
                    else if (msg.result.type == 7) { $("#type7").attr('checked',true); }
                    else if (msg.result.type == 8) { $("#type8").attr('checked',true); }
                } 
            }
        });
            // alert($("[name='type'] :checked").val());
        $('#onlyone').click(function () {
            transport(num);
        });
    });
    
    //提交
    function transport(num) {
        if (num != undefined ) {
            //执行修改
            // var dates = $('#moredata').serialize();
            var dates = '&goodssn='+$("[name='goodssn']").val()+'&goodsname='+$("[name='goodsname']").val()+'&stock='+$("[name='stock']").val()+'&type='+$("[name='type'] :checked").val()+ '&optionid='+$("[name='optionid']").val()+"&goodsid2="+$("[name='goodsid2']").val()+" &status=edit";
            // console.log(dates);
            $.ajax({
                type: "post",
                data: {op: "post", id: num, data: dates},
                dataType: "json",
                url: "<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_stockchange', array('op'=>'post'))?>&status=edit&id="+num+"&goodsid2="+$("[name='goodsid2']").val()+"data:"+dates ,
                success: function (msg) {
                    if (msg.type != 'success') {
                        alert('修改失败');
                    } else {
                        alert('修改成功');
                        window.location.reload();
                    }
                }
            });
        }
    }
    //全选
    $("#sel").click(function() {
        $("input[name='id']").prop('checked',true);
    });
    //搜索
    $('#search').click(function() {
        //时间搜索不精准
        $('#form1').submit();
    });
    //删除多条
    $("#del").click(function(ids) {
        var arr=[] ;
        $('input[name="id"]:checked').each(function() {
            arr.push( $(this).val());
            console.log(arr);
        });
        if (window.confirm("确定删除所选记录？") == true) {
            $.ajax({
                type: "get",
                dataType: 'json',
                url: "<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_stockchange',array('op'=>'delete'))?>"+'&id='+arr,
                success: function(msg){
                    if (msg.message == '删除成功') {
                        alert(msg.message);
                        window.location.reload();
                    } else {
                        alert(msg.message);
                    }
                }
            });
        }
    });
   
</script>