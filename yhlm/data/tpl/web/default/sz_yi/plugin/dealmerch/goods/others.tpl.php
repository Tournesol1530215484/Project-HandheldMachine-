<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group notice">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">商家通知</label>
        <div class="col-sm-9 col-xs-12">
         <?php if( ce('dealmerch.goods.edit' ,$item) ) { ?>
            <input type='hidden' id='noticeopenid' name='noticeopenid' value="<?php  echo $item['noticeopenid'];?>" />
            <div class='input-group'>
                <input type="text" name="saler" maxlength="30" value="<?php  if(!empty($saler)) { ?><?php  echo $saler['nickname'];?>/<?php  echo $saler['realname'];?>/<?php  echo $saler['mobile'];?><?php  } ?>" id="saler" class="form-control" readonly />
                <div class='input-group-btn'>
                    <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();">选择通知人</button>
                    <button class="btn btn-danger" type="button" onclick="$('#noticeopenid').val('');$('#saler').val('');$('#saleravatar').hide()">清除选择</button>
                </div> 
            </div>
            <span id="saleravatar" class='help-block' <?php  if(empty($saler)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $saler['avatar'];?>"/></span>
            <span class="help-block">单品下单通知，可制定某个用户，通知商品下单备货通知,如果商品为同一商家，建议使用系统统一设置</span>
            <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                <div class="modal-dialog" style='width: 920px;'>
                    <div class="modal-content">
                        <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择通知人</h3></div>
                        <div class="modal-body" >
                            <div class="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice" placeholder="请输入粉丝昵称/姓名/手机号" />
                                    <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                </div>
                            </div>
                            <div id="module-menus-notice" style="padding-top:5px;"></div>
                        </div>
                        <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                    </div>

                </div>
            </div>
            <?php  } else { ?>
            <div class='form-control-static'>
                <?php  if(!empty($saler)) { ?><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $saler['avatar'];?>"/><?php  } else { ?>无<?php  } ?>
             </div>
            <?php  } ?>
        </div>
    </div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">通知方式</label>
        <div class="col-sm-9 col-xs-12">
            
        <?php if( ce('dealmerch.goods.edit' ,$item) ) { ?>
            <label class="checkbox-inline">
                <input type="checkbox" value="0" name='noticetype[]' <?php  if(in_array(0,$noticetype)) { ?>checked<?php  } ?> /> 下单通知
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name='noticetype[]' checked /> 付款通知
            </label>
             <label class="checkbox-inline">
                <input type="checkbox" value="2" name='noticetype[]' <?php  if(in_array(2,$noticetype)) { ?>checked<?php  } ?> /> 买家收货通知
            </label>
            <div class="help-block">通知商家方式</div>
         <?php  } else { ?>
           <div class='form-control-static'><?php  if(in_array(0,$noticetype)) { ?>下单通知;<?php  } ?><?php  if(in_array(1,$noticetype)) { ?>付款通知;<?php  } ?><?php  if(in_array(2,$noticetype)) { ?>买家收货通知;<?php  } ?></div>
         <?php  } ?>
        </div>
    </div>
</div>
<script language='javascript'>
         function search_members() {
             if( $.trim($('#search-kwd-notice').val())==''){
                 Tip.focus('#search-kwd-notice','请输入关键词');
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


<!-- 多用户提醒 -->
<?php  if(true) { ?> 
<div class="form-group notice">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">多用户提醒</label>
        <div class="col-sm-9 col-md-10 col-xs-12">             
         
            <?php if( ce('dealmerch.goods.edit' ,$item) ) { ?>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" name="openext">
                    开启 
                  </label>
                </div>

                <div class="form-group" style="display: none">
                    <input type="hidden" name="extnotice" value="<?php  echo $item['extnotice'];?>" >
                    <table class="table table-hover table-responsive" >
                        <thead>
                        <tr>
                            <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menussss').modal();">选择用户</button>
                            <th style="width: 20%">头像</th>        
                            <th style="width: 20%">用户名</th>        
                            <th style="width: 10%">联系电话</th>         
                            <th style="width: 5%">操作</th>
                        </tr>
                        </thead>
                        <tbody style="text-align:left;" class="exchange">
                            <?php  if(!empty($noticepeople)) { ?>
                                <?php  if(is_array($noticepeople)) { foreach($noticepeople as $k => $v) { ?>
                                    <tr data-id="<?php  echo $v['id'];?>">
                                        <td><img width="35px" height="35px" src="<?php  echo $v['avatar'];?>"></td>
                                        <td><?php  echo $v['nickname'];?>/<?php  echo $v['realname'];?></td>        
                                        <td><?php  echo $v['mobile'];?></td>
                                        <td><span type="button" class="fa fa-trash-o"></span></td>
                                    </tr>
                                <?php  } } ?>      
                            <?php  } ?>
                        </tbody>
                    </table>
                </div>
                <script>

                    $('[name="openext"]').click(function(){
                        $(this).parents('.checkbox').next().toggle(250);     
                    });     

                    var extnotice=<?php  echo json_encode(explode(',',$item['extnotice']))?>;
                    function myinarray(val,arr){            // 如果存在返回假 否则true
                        for(var i in arr){
                            if(arr[i]==val){
                                return false;
                            }
                        }
                        return true;
                    }

                    function select_user(obj){         //选择用户
                        var str='';              
                        if (myinarray(obj.id,extnotice)){
                            extnotice.push(obj.id);
                            str+='<tr data-id="'+obj.id+'">';
                            str+='<td><img width="35px" height="35px" src="'+obj.avatar+'"></td>';
                            str+='<td>'+obj.nickname+'</td>';
                            str+='<td>'+obj.mobile+'</td>';
                            str+='<td><span type="button" class="fa fa-trash-o"></span></td>';
                            str+='</tr>';            
                            $('[name="extnotice"]').val(extnotice.join(','));     //use ',' explode array
                            $('.exchange').append(str);         //列表
                        }else{
                            alert('该用户已存在');                         
                        }
                    }

                    $('.exchange').on('click','span',function(){
                        var parent=$(this).parents('tr');
                        var id=parent.data('id');
                        extnotice.splice($.inArray(id,extnotice),1);
                        $('[name="extnotice"]').val(extnotice.join(','));
                        parent.remove();
                    });

                    <?php  if($noticepeople) { ?>
                        $('[name="openext"]').click();
                    <?php  } ?>
                </script>

                <div id="modal-module-menussss" class="modal fade" tabindex="-1" aria-hidden="false" >
                    <div class="modal-dialog" style="width: 920px;">
                        <div class="modal-content">
                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择用户</h3></div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="keyword" value="" id="search-keyword" placeholder="请输入用户名称或联系电话">
                                        <span class="input-group-btn"><button type="button" class="btn btn-default" onclick="search_notice();">搜索</button></span>
                                    </div>       
                                </div>       
                                <div id="module-menusss" style="padding-top:5px;"></div>
                            </div>
                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                        </div>

                    </div>
                </div>

                <script>

                    function search_notice(){
                        $("#module-menusss").html("正在搜索....")
                        $.get("<?php  echo $this->createPluginWebUrl('dealmerch/store',array('op'=>'notice'))?>", {
                            keyword: $.trim($('#search-keyword').val())      
                        }, function(dat){                                              
                            $('#module-menusss').html(dat);                  
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
                        html+='<input type="hidden" value="' + o.id +'" name="extnotice[]">';
                        html+='<input type="text" value="' + o.nickname +'" readonly="" class="form-control">';
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
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        
    </div>
</div>

<?php  } ?>