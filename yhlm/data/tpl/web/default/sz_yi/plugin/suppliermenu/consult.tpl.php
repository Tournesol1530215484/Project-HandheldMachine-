<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('dealtabs', TEMPLATE_INCLUDEPATH)) : (include template('dealtabs', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .message{
        padding:10px;
        height:250px;
        overflow:auto;
    }
    .message h3{
        color: #5bc0de;
        text-align: center;
        margin-top: 0px;
    }
    .message b{
        cursor: pointer;
    }
    .message p{ 
        height: auto;
        border-radius:5px; 
        border:#000 solid 1px;
        padding: 7px 3px;
        max-width: 75%;
        word-wrap:break-word;   
        word-break:break-all;
    }
    .he{
        background: rgba(225,225,225,0.5);
        text-align: left;
        float: left;
    }

    .you{
        background: rgba(0,0,0 ,0); 
        text-align: left; 
        float: right;
    }
</style> 
 
<div class="panel panel-info">
    <div class="panel-heading">共计 <span style="color:red; "><?php  echo $totalcount;?></span> 个用户咨询 </div>
    <div class="">
        <table class="table table-hover">
            <thead class="navbar-inner">
            <tr>
                <th style="width:auto;">用户账号</th>
                <th style="width:auto;">咨询内容</th>
                <th style="width:auto;">电话</th>
                <th style="width:auto;">QQ</th> 
                <th style="width:auto;">咨询时间</th>
                <th style="width:auto;">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $item) { ?>
            <tr style="background: #eee">
                <td><?php  echo $item['nickname'];?>/<?php  echo $item['realname'];?></td> 
                <td><?php  echo $item['content'];?></td>
                <td>
                    <?php  if(!empty($item['mobile'])) { ?>
                        <?php  echo $item['mobile'];?>
                    <?php  } else { ?>
                        <?php  echo $item['pmobile'];?>
                    <?php  } ?> 
                </td>
                <td><?php  echo $item['qq'];?></td>
                <td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
                <td> 
                    <button data-openid="<?php  echo $item['openid'];?>" class="btn showmsg btn-default" type="button">查看消息</button>
                </td>
            </tr>
            <?php  } } ?>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
</div>





                <div id="modal-module-menus" class="modal fade" tabindex="-1" aria-hidden="false" >
                    <div class="modal-dialog" style="width: 920px;">
                        <div class="modal-content">
                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>历史消息</h3></div>
                            <div class="modal-body"> 
                                <div class="row message" >
                                    <h3>
                                        <b class="addmore" data-load="0">加载更多</b>
                                    </h3>
                                    
                                </div> 
                                <div class="row">
                                    <div class="input-group col-lg-12">
                                        <input type="text" class="form-control" name="keyword" value="" id="search-kwd" placeholder="说点什么？" autocomplete="off" style="margin-left:4px;">   
                                        <span class="input-group-btn"></span> 
                                    </div>
                                </div>
                                <div id="module-menus" style="padding-top:5px;"></div>
                            </div>
                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                        </div>

                    </div>
                </div>
                                    <!-- <p class="he">这里客户消息</p> 
                                    <br clear="all"> 
                                    <p class="you">这里是商家消息</p> 
                                    <br clear="all">  -->
 
<script>

    

    var openid = null;
    var page = null;
    var o=$('.addmore');
 
    $(function(){

        $('#modal-module-menus').on('hidden.bs.modal', function (e) {
            $('.message').find('p').remove(); 
            $('.message').find('br').remove();  
        })

        $("#search-kwd").keydown(function (e) {
            if (e.keyCode == 13) {
               var content = $('#search-kwd').val(); 
                   $('#search-kwd').val(null);

                var html='<p class="you">'+content+'</p>';
                    html+='<br clear="all">';
                var data={
                    openid:openid,
                    op:"send",
                    content:content
                };

            $.post( 
                '<?php  echo $this->createPluginWebUrl("suppliermenu/consult")?>',
                data,
                function(data){   
                    if (data.status == 0) { 
                        alert(data.result); 
                    }else if (data.status == 1){
                        $(".message").append(html);
                    }
                },'json');         
            } 
        });

        $('.showmsg').click(function(){
            openid=$(this).data('openid');
            $.post( 
            '<?php  echo $this->createPluginWebUrl("suppliermenu/consult",array("op"=>"message"))?>',
               {openid:openid},
               function(json){  
                page=json.result.page;
                var html=''; 
                for(var i in json.result.list){
                    if (json.result.list[i].type == 1) {
                        html+='<p class="you">'+json.result.list[i].content+'</p>';
                        html+='<br clear="all">';
                    }else if (json.result.list[i].type == 2){
                        html+='<p class="he">'+json.result.list[i].content+'</p>';
                        html+='<br clear="all">';
                    }
                }
                    
                $(".message").append(html);
                $('#modal-module-menus').modal();

                
                if (json.result.page == 0) {
                    o.data('load','1');
                    o.html('已经加载全部消息');
                }else{  
                    o.data('load','0');
                    o.html('加载更多');
                    page=json.result.page;              
                }
            },'json'); 
        });
            

            $('.addmore').click(function(){
                
                var load=o.data('load');

                if (load == 1) {
                    return false;
                }   
                o.data('load','1'); 
                o.html('正在加载...');
                var data={
                    page:page,
                    op:'message',
                    openid:openid
                };
                
                $.post(
                    '<?php  echo $this->createPluginWebUrl("suppliermenu/consult")?>',
                    data, 
                    function(json){
                        o.html('加载更多');

                        var html=''; 
                        for(var i in json.result.list){
                            if (json.result.list[i].type == 1) {
                                html+='<p class="you">'+json.result.list[i].content+'</p>';
                                html+='<br clear="all">';
                            }else if (json.result.list[i].type == 2){
                                html+='<p class="he">'+json.result.list[i].content+'</p>';
                                html+='<br clear="all">';
                            }
                        } 
                             
                        $(".message h3").after(html); 
                        console.log(json.result.page == 0);
                        if (json.result.page == 0) {
                            o.html('已经加载全部消息');
                            o.data('load','1');
                        }else{  
                            o.data('load','0');
                            page=json.result.page;              
                        }
                    },
                'json');
            }); 

    }); 
</script>



<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?> 