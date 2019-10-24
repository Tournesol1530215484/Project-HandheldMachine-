<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>我的足迹</title>
<style type="text/css">
body {margin:0px; background:#f8f8f8; -moz-appearance:none;}
@font-face {font-family: "iconfont";
  src: url('../addons/sz_yi/template/mobile/style1/static/fonts/iconfont02.eot?t=1474179952'); /* IE9*/
  src: url('../addons/sz_yi/template/mobile/style1/static/fonts/iconfont02.eot?t=1474179952#iefix') format('embedded-opentype'), /* IE6-IE8 */
  url('../addons/sz_yi/template/mobile/style1/static/fonts/iconfont02.woff?t=1474179952') format('woff'), /* chrome, firefox */
  url('../addons/sz_yi/template/mobile/style1/static/fonts/iconfont02.ttf?t=1474179952') format('truetype'), /* chrome, firefox, opera, Safari, Android, iOS 4.2+*/
  url('../addons/sz_yi/template/mobile/style1/static/fonts/iconfont02.svg?t=1474179952#iconfont') format('svg'); /* iOS 4.1- */
}
@font-face {font-family: "iconfont";
  src: url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont1.eot?t=1474179952'); /* IE9*/
  src: url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont1.eot?t=1474179952#iefix') format('embedded-opentype'), /* IE6-IE8 */
  url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont1.woff?t=1474179952') format('woff'), /* chrome, firefox */
  url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont1.ttf?t=1474179952') format('truetype'), /* chrome, firefox, opera, Safari, Android, iOS 4.2+*/
  url('../addons/sz_yi/template/mobile/style1/shop/fonts/iconfont1.svg?t=1474179952#iconfont') format('svg'); /* iOS 4.1- */
}
@font-face {font-family: "iconfont";
  src: url('../addons/sz_yi/template/mobile/style1/member/fonts/iconfont.eot?t=1474945964'); /* IE9*/
  src: url('../addons/sz_yi/template/mobile/style1/member/fonts/iconfont.eot?t=1474945964#iefix') format('embedded-opentype'), /* IE6-IE8 */
  url('../addons/sz_yi/template/mobile/style1/member/fonts/iconfont.woff?t=1474945964') format('woff'), /* chrome, firefox */
  url('../addons/sz_yi/template/mobile/style1/member/fonts/iconfont.ttf?t=1474945964') format('truetype'), /* chrome, firefox, opera, Safari, Android, iOS 4.2+*/
  url('../addons/sz_yi/template/mobile/style1/member/fonts/iconfont.svg?t=1474945964#iconfont') format('svg'); /* iOS 4.1- */
}
.hs {
  font-family:"iconfont" !important;
  font-style:normal;
  -webkit-font-smoothing: antialiased;
  -webkit-text-stroke-width: 0.2px;
  -moz-osx-font-smoothing: grayscale;
}
.hs-xuan:before { content: "\e739"; }
.hs-wei:before { content: "\e651"; }
.hs-xuanzhong:before { content: "\d622"; }
.hs-guan:before { content: "\e933"; }

.history_no {height:40px; width:100%;  padding-top:180px; margin:50px 0px;}
.history_no_nav {height:38px; width:43%; background:#eee; margin:0px 3%; float:left; border:1px solid #d4d4d4; border-radius:5px; text-align:center; line-height:38px; color:#666;}
.history_top {height:44px;  background:#fff; padding:0px 3%; border-bottom:1px solid #e3e3e3;}
.history_top .title {height:44px; width:auto; float:left; font-size:16px; line-height:44px; color:#666;}
.history_top .nav {height:30px; width:auto; background:#fff; padding:0px 10px; border:1px solid #e3e3e3; border-radius:5px; float:right; line-height:30px; margin:6px 0px 0px 16px; color:#666; font-size:14px;}
.history_main {height:auto; background:#fff; border-bottom:1px solid #e3e3e3;margin-top: 10px}
.history_good {height:100px;  padding:10px 0px; border-bottom:1px solid #e3e3e3;}
.history_good .ico {height:20px; width:30px;  float:left ; font-size:24px;margin-top:25px;color:#666;z-index:2;position: relative;text-align:right;display: none}
.history_good .img {height:80px; width:80px; background:#f99; float:left;z-index:2;position: relative;margin-left:10px;}
.history_good .img img {height:100%; width:100%}
.history_good .info {height:80px; width:100%; float:left;margin-left:-120px;margin-right:-30px;position: relative;}
.history_good .info .inner {margin-left:130px;margin-right:30px;}
.history_good .info .inner .name {height:40px; width:100%; line-height:20px; color:#666; overflow:hidden; font-size:14px;}
.history_good .info .inner .other {height:30px; width:100%; margin-top:10px;}
.history_good .info .inner .other .price {height:30px; width:auto; float:left; line-height:30px; font-size:14px; color:#666; overflow:hidden; color:red;}
.history_good .info .inner .other .price span {color:#666;font-size:12px;text-decoration: line-through}
.history_good .right { float:right;width:30px;height:20px;margin-left:-30px; color:#666;font-size:18px;margin-top:25px;text-align:center;z-index:2;position: relative;}

.history_no {height:100px; width:100%; margin:50px 0px 60px; color:#ccc; font-size:12px; text-align:center;}
.history_no_menu {height:40px; width:100%;}
.history_no_nav {height:38px; width:43%; background:#eee; margin:0px 3%; float:left; border:1px solid #d4d4d4; border-radius:5px; text-align:center; line-height:38px; color:#666;}
 
#history_loading { padding:10px;color:#666;text-align: center;}
.removeD { position: fixed; bottom: 55px; left: 0; z-index: 1111; display: none; width: 100%; height: 45px; line-height: 45px; background: #d6d6d6; }
.removeD .all{margin-left: 10px;height: 45px;line-height: 45px;float: left;}
</style>
 
<div id='container'></div>
<script id='history_empty' type='text/html'>
     <div class="history_no"><i class="fa fa-history" style="font-size:100px;"></i><br><span style="line-height:18px; font-size:16px;">您没有浏览过任何商品</span><br>主人快去给我找点东西吧</div>
<div class="history_no_menu">
   <div class="history_no_nav" onclick="location.href='<?php  echo $this->createMobileUrl('member')?>'">个人中心</div>
        <div class="history_no_nav"  onclick="location.href='<?php  echo $this->getUrl()?>'">去逛逛</div>
 </div>
</script>
<script id="history_main" type="text/html">
      <div class="history_top">
        <div class="title" onclick='history.back()'><i class='fa fa-chevron-left'></i> 我的足迹(<%total%>)</div>
        <!-- <div class="nav" id="removehistory">删除</div> -->
        <div class="nav" onclick="complete($(this))">编辑</div>
    </div>
    <div class="removeD clearfloat">
        <div class="all ico" sel='0'>
            <i class="hs hs-wei" style="font-size: 20px;color: rgb(153, 153, 153);float:left"></i>
            <span style="float:left;margin-left:5px">全选</span>
        </div>
        <div style="float:right;margin-right:10px;color:red" id="removehistory">删除</div>
    </div>
    <div class="history_main"></div>
</script>
<script id='history_list' type='text/html'>
   
  
        <%each list as value%> 
        <div class="history_good" data-historyid="<%value.id%>" sel='0'>
            <div class="ico"><i class="hs hs-wei" ></i></div>
            <div class="img" onclick="location.href='<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%value.goodsid%>'"><img src="<%value.thumb%>"/></div>
            <div class="info" onclick="location.href='<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%value.goodsid%>'">
                <div class="inner">
                    <div class="name"><%value.title%></div>
                      <div class="other">
                             <div class="price">￥<%value.marketprice%> <span>￥<%value.productprice%><span></div>
                      </div>
                </div>
            </div>
            <div class="right remove">
                <!-- <i class="fa fa-times"></i> -->
                <i class="hs hs-guan"></i>
            </div>
        </div>
      <%/each%>
 
</script>
<script language='javascript'>
    var page= 1;
 
    function setSelect(obj, sel){
        if(sel=='1'){
             obj.find('i').addClass('hs-wei').removeClass('hs-xuanzhong').css('color', '#666');
        }
        else{
             obj.find('i').removeClass('hs-wei').addClass('hs-xuanzhong').css('color', '#00c1ff');
        }
        sel =sel==1?0:1;
        obj.parent().attr('sel',sel);
   
        calctotal();
    }
    //编辑
    function complete(obj){
      var _this = obj;
      if($(".history_good .ico").css('display')=="none"){
          $(".history_good .ico").show();
          _this.text("完成");
          $(".removeD").show();
      }else{
          $(".history_good .ico").hide();
          _this.text("编辑");
          $(".removeD").hide();
      }
    }
    function calctotal(){
           var total = 0;
        $(".history_good").each(function(){
            var $this = $(this);
            var sel = $this.attr('sel')=='1';
            if(sel){
                total++;
            }
        });
           if(total<=0){
                $("#removehistory").addClass('disabled');
            }
            else{
                $("#removehistory").removeClass('disabled');
            }

        return total;
    }
    
    require(['tpl', 'core'], function(tpl, core) {
       
     
        function bindEvents(){             
            $(".ico").unbind('click').click(function(){
                setSelect($(this),$(this).parent().attr('sel'))
            });
            $(".all").click(function(){
                var $icon = $(this).find('i');
                var sel = $(this).attr('sel');
                $(".ico").each(function(){
                    setSelect($(this),sel)
                });
                setSelect($icon,sel,true);
            });
            $('.remove').click(function(){
                var ids = [ $(this).closest('.history_good').data('historyid') ];
                removeHistory(ids); 
            });
        }

                     function removeHistory(ids){
                        if(ids.length<=0){
                                   core.tip.show('未选择商品');
                                   return;
                               }
                              core.tip.confirm('确认从我的足迹删除这些商品?',function(){
                                           $('.history_good').attr('del',0);
                                           core.json('shop/history',{'op':'remove',ids:ids},function(json){
                                                if(json.status==1)  {
                                                    for(var i in ids){
                                                        $('.history_good[data-historyid=' + ids[i]+ ']').attr('del',1).fadeOut(500,function(){
                                                            $('.history_good[data-historyid=' + ids[i]+ ']').remove();
                                                        })
                                                    }
                                                   if($('.history_good[del=0]').length<=0){
                                                        $('#container').html(  tpl('history_empty') );
                                                   }
                                                   else{
                                                       calctotal();    
                                                   }
                                                }
                                                else{
                                                    core.tip.show('删除失败');
                                                }
                                           },true,true);

                            });
                  }
                  
       
        core.json('shop/history',{},function(json){

               
                      
                    if(json.result.total<=0){
                        $('#container').html(  tpl('history_empty') );
                         return;
                     }
                     
                     $('#container').html(  tpl('history_main',json.result) );
              
                     $('#removehistory').click(function(){
                            var ids = [];
                            $('.history_good[sel=1]').each(function(){
                                ids.push($(this).data('historyid')) ;
                            });
                            removeHistory(ids);
                     });
                     
                     
                     $('.history_main').html(  tpl('history_list',json.result) );
                    bindEvents();
              
                    var loaded = false;
                      var stop=true; 
                      $(window).scroll(function(){ 
                          if(loaded){
                              return;
                          }
                            totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()); 
                            if($(document).height() <= totalheight){ 
                                
                                if(stop==true){ 
                                    stop=false; 
                                    $('.history_main').append('<div id="history_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载...</div>');
                                    page++;
                                    core.json('shop/history', {page:page}, function(morejson) {  
                                        stop = true;
                                        $('#history_loading').remove();
                                        $(".history_main").append(tpl('history_list', morejson.result));
                                        bindEvents();
                                        if (morejson.result.list.length <morejson.result.pagesize) {
                                          
                                            $('.history_main').append('<div id="history_loading">已经加载完全部足迹</div>');
                                            loaded = true;
                                            $(window).scroll = null;
                                            return;
                                        }
                                    },true); 
                                } 
                            } 
                        });
            
                  
              
                     
         },true);
   });
</script>
<?php  $show_footer = true?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
