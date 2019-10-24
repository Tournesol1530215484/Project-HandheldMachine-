<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!-- 我的资料 -->
<title>我的资料</title>
<style type="text/css">
body {margin:0px; background:#efefef; font-family:'微软雅黑'; -moz-appearance:none;}
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
.info_main {height:auto;  background:#fff; margin-top:14px; border-bottom:1px solid #e8e8e8; border-top:1px solid #e8e8e8;}
.info_main .line {margin:0 10px; height:40px; border-bottom:1px solid #e8e8e8; line-height:40px; color:#999;}
.info_main .line .title {height:40px; width:90px; line-height:40px; color:#444; float:left; font-size:16px;padding-left:25px }
.info_main .line .info { width: 100%; float: right; margin-left: -100px; height: 39px; overflow: hidden; }
.info_main .line .inner { margin-left:100px; }
.info_main .line input {height:39px; width:100%;display:block; padding:0px; margin:0px; border:0px; float:left; font-size:16px;}
.info_main .line .inner .user_sex {line-height:40px;}
.info_sub {height:44px; margin:14px 5px; background:#15a9ff; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
.info_main{margin-top: 0}
.select { border: 1px solid #ccc; height: 30px; margin-top: 6px; line-height: 20px; background: #5c5c5c; color: #fff; padding: 0 5px; border-radius: 3px; }
.page_topbar{background:#fff;}
.home{position: absolute;right: 15px;top: 0;color: #000}
</style>
<script src="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.core-2.5.2.js" type="text/javascript"></script>
<script src="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.core-2.5.2-zh.js" type="text/javascript"></script>
<link href="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.core-2.5.2.css" rel="stylesheet" type="text/css" />
<link href="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.animation-2.5.2.css" rel="stylesheet" type="text/css" />
<script src="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.datetime-2.5.1.js" type="text/javascript"></script>
<script src="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.datetime-2.5.1-zh.js" type="text/javascript"></script>
<script src="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.android-ics-2.5.2.js" type="text/javascript"></script>
<link href="../addons/sz_yi/static/js/dist/mobiscroll/mobiscroll.android-ics-2.5.2.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade.js"></script>
<div id="container"></div>
<script id="member_info" type="text/html">
  <div class="page_topbar">
      <a href="javascript:;" class="back" onclick="history.back()"><i class="fa fa-angle-left"></i></a>
      <div class="title">我的资料</div>
      <a href="javascript:;" class="home"><i class="iconfont shouye"></i></a>
  </div>
  <div style="margin:10px;color:#999">账号设置</div>
    <div class="info_main">
        <div class="line"><div class="title">姓名</div><div class='info'><div class='inner'><input type="text" id='realname' placeholder="请输入您的姓名"  value="<%realname%>" /></div></div></div>
 
        <%if mobile%>
        <div class="line">
            <div class="title">手机号码</div>
            <div class='info'>
                <div class='inner'><%mobile%></div>
            </div>
        </div>
        <input type="hidden" id='mobile' value="<%mobile%>" />
        <%else%>            
        <div class="line">
            <div class="title">手机号码</div>
            <div class='info'>
                <div class='inner'>
                    <input type="text" id='mobile' placeholder="请输入您的手机号码" value="<%mobile%>" />
                </div>
            </div>
        </div>
        
        <%/if%>

        <div class="line"><div class="title">微信号</div><div class='info'><div class='inner'><input type="text"  id='weixin' placeholder="请输入微信号" value="<%weixin%>"/></div></div></div>
        <div class="line">
            <div class="title">性别</div>
            <div class='info'>
              <div class='inner'>
                <span class="gender" data-val="1"><i class="hs <%if gender=='1'%>hs-xuanzhong<%else%>hs-wei<%/if%>" <%if gender=='1'%>style="color:#15a9ff;"<%/if%>></i> 男</span>&nbsp;&nbsp;
                <span class="gender" data-val="2"><i class="hs <%if gender=='2'%>hs-xuanzhong<%else%>hs-wei<%/if%>" <%if gender=='2'%>style="color:#15a9ff;"<%/if%>></i> 女
                <input type="hidden" id="gender" value="<%gender%>" />
              </div>
            </div>
        </div>
      
        <div class="line">
            <div class="title">所在城市</div><div class='info'><div class='inner'>
            <select id="sel-provance" onChange="selectCity();" class="select">
                <option value="" selected="true">省/直辖市</option>
            </select>
            <select id="sel-city" onChange="selectcounty()" class="select">
                <option value="" selected="true">请选择</option>
            </select>
            <select id="sel-area" class="select">
                <option value="" selected="true">请选择</option>
            </select></div></div>
        </div>
        <div class="line"  style="border:0px;"><div class="title">生日</div><div class='info'><div class='inner'><input type="text" id="birthday" placeholder="点击选择日期" readonly value='<%birthday%>'/></div></div></div>
        
    </div><div class="info_sub">确认修改</div>
  <div class="copyright">版权所有 ©<?php  if(empty($set['shop']['name'])) { ?><?php  echo $_W['account']['name'];?><?php  } else { ?><?php  echo $set['shop']['name'];?><?php  } ?></div>
</script>
<script language="javascript">
    require(['tpl', 'core'], function(tpl, core) {
        core.json('member/info',{},function(json){
            if (json.result.member) {
 
                var data = json.result.member;
//                console.log(json.result.member);

                $('#container').html(tpl('member_info', data));
                var currYear = (new Date()).getFullYear();
                var opt = {};
                opt.date = {preset: 'date'};
                opt.datetime = {preset: 'datetime'};
                opt.time = {preset: 'time'};
                opt.default = {
                    theme: 'android-ics light', 
                    display: 'modal',
                    mode: 'scroller',
                    lang: 'zh',
                    startYear: currYear - 100,
                    endYear: currYear 
                };
                $("#birthday").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
                cascdeInit(data.province,data.city,data.area);
//                var city = $('#sel-provance');
//                var city2 = city.find('option');
//                for(let i=0;i<city2.length;i++){
//                    var cityOption = city2.eq(i).html();
//                    console.log(cityOption)
//                    if(json.result.member.bonus_province == city2.eq(i).html()){
//                        city2.eq(i).prop('selected',true)
//                    }
//                }
//                console.log(city2)
                $('.gender').click(function() {
                    var $this = $(this);
                    var val = $this.data('val');
                    $('.gender').find('i').css('color', '#999').removeClass('hs-xuanzhong').addClass('hs-wei');
                    $(this).find('i').removeClass('hs-wei').addClass('hs-xuanzhong').css('color', '#15a9ff');
                    $('#gender').val(val);
                })
                $('.info_sub').click(function() {
                    if($(this).attr('saving')=='1')
                    {
                        return;
                    }
                   
                       if( $('#realname').isEmpty()){
                           core.tip.show('请输入姓名!');
                           return;
                       }
                       if(!$('#mobile').isMobile()){
                           core.tip.show('请输入正确手机号码!');
                           return;
                       }
                       
                      if( $('#weixin').isEmpty()){
                           core.tip.show('请输入微信号!');
                           return;
                       }
                  
                   $(this).html('正在处理...').attr('saving',1);
                   var birthday = $('#birthday').val().split('-');
                    core.json('member/info', {
                       'memberdata':{
                            'realname': $('#realname').val(),
                            'mobile': $('#mobile').val(),
                            'weixin': $('#weixin').val(),
                            'gender': $('#gender').val(),
                            'birthyear': $('#birthday').val().length>0?birthday[0]:0,
                            'birthmonth': $('#birthday').val().length>0?birthday[1]:0,
                            'birthday': $('#birthday').val().length>0?birthday[2]:0,
                            'province': $('#sel-provance').val(),
                            'city': $('#sel-city').val(),
                            'area': $('#sel-area').val()
                       }, 'mcdata':{
                            'realname': $('#realname').val(),
                            'mobile': $('#mobile').val(),
                            'gender': $('#gender').val(),
                            'birthyear': $('#birthday').val().length>0?birthday[0]:0,
                            'birthmonth': $('#birthday').val().length>0?birthday[1]:0,
                            'birthday': $('#birthday').val().length>0?birthday[2]:0,
                            'resideprovince': $('#sel-provance').val(),
                            'residecity': $('#sel-city').val()
                       }
                    }, function(json) {
                       
                        if(json.status==1){
                             core.tip.show('保存成功');
                             <?php  if(!empty($_GPC['returnurl'])) { ?>
                                 location.href="<?php  echo urldecode($_GPC['returnurl'])?>";
                             <?php  } else { ?>
                                 location.href="<?php  echo $this->createMobileUrl('member')?>";
                             <?php  } ?>
                        }
                        else{
                            $('.info_sub').html('确认修改').removeAttr('saving');
                            core.tip.show('保存失败!');
                        }
                    },true,true);
                })
            }
        });

    })
</script>
<?php  $show_footer=true?>
<?php  $footer_current='member'?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>