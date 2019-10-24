<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>绑定手机</title>
<style type="text/css">
    body {margin:0px; background:#efefef; font-family:'微软雅黑'; -moz-appearance:none;}
    .info_main {margin-top:14px;}
    /*.info_main .line {margin:0 10px; height:40px; border-bottom:1px solid #e8e8e8; line-height:40px; color:#999;position: relative;}*/
    /*.info_main .line .title {height:40px; width:80px; line-height:40px; color:#444; float:left; font-size:16px;}*/
    /*.info_main .line .info { width:100%;float:right;margin-left:-80px; }*/
    /*.info_main .line .inner { margin-left:80px; }*/
    /*.info_main .line .inner input {height:40px; display:block; padding:0px; margin:0px; border:0px; float:left; font-size:16px;}*/
    .info_main .line { margin: 0 10px; height: 40px; line-height: 40px; color: #999; position: relative; margin-bottom: 10px; }
    .info_main .line input { height: 40px; display: block; padding: 0px; margin: 0px; border: 0px; float: left; border-radius: 5px; font-size: 16px; padding: 0 5px; }
    .info_main .line .inner .user_sex {line-height:40px;}
    .info_sub {height:44px; margin:14px 5px; background:#15a9ff; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .register {float:right;width:46%;height:44px; margin:14px 5px; background:#31cd00; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .nobindmobile {clear:both;height:44px;text-align:center; font-size:16px; line-height:44px; color:#15a9ff;}
    .select { border:1px solid #ccc;height:25px;}
    .hide {display: none;}
    #btnSendCode{float: right;color: #fff; background: #85d115; border-radius: 10px; width: 50%;}
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
        <div class="title">绑定手机</div>
    </div>
    <div style="margin: 10px;color: #999;line-height:20px"><i style="font-size: 20px;margin-right: 5px;float: left;" class="fa fa-mobile"></i>您还未绑定手机，请绑定</div>
    <div class="info_main">
        <div class="line"><!-- <div class="title">手机号码</div> -->
          <div class='info'>
            <div class='inner'><input type="text" style="width:100%" id='mobile' placeholder="请输入您的手机号码"  value="" /></div>
          </div>
        </div>
        <div class="line"><!-- <div class="title">验证码</div> -->
            <div class='info'>
                <div class='inner'>
                    <input type="text" id='code' style="width:45%" placeholder="请输入验证码"  value="" />
                    <input id="btnSendCode" type="button" value="发送验证码"/>
                </div>
            </div>
        </div>
        <div class='line'><input type="password" id='password' placeholder="请输入您的密码" style="display:none" value="" /></div>
        <div class='line'><input type="password" id='cpassword' placeholder="请确认密码" style="display:none" value="" /></div>
    </div>
    <div class="info_sub">绑定</div>
    <?php  if(empty($nobindmobile_hide)) { ?>
    <div class="nobindmobile">暂时不绑定，先去逛逛</div>
    <?php  } ?>
</script>
<script language="javascript">
    require(['tpl', 'core'], function(tpl, core) {
        $('#container').html(tpl('member_info'));
            $('.nobindmobile').click(function(){
                location.href = "<?php  echo $this->createMobileUrl('member/nobindmobile')?>";
            });
            
            var InterValObj; //timer变量，控制时间
            var count = 60; //间隔函数，1秒执行
            var curCount;//当前剩余秒数
            var hasmobile = false;
            $('#btnSendCode').click(function(){
                if(!$('#mobile').isMobile()){
                    core.tip.show('请输入正确手机号码!');
                    return;
                } 
                if($("#password").css('display')=="none"){
                    $("#password").show();
                }else{
                    $("#password").hide();
                }
                if($("#cpassword").css('display')=="none"){
                    $("#cpassword").show();
                }else{
                    $("#cpassword").hide();
                }
              　curCount = count;  　　
            　　//向后台发送处理数据 
                core.json('member/sendcode', {
                    'mobile': $('#mobile').val(),
                    'op' : "bindmobilecode"
                }, function(json) {
                  if(json.status==1){
                    //设置button效果，开始计时
                     $("#btnSendCode").attr("disabled", "true");
                     $("#btnSendCode").css({"background":"#ddd"});
                     $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
                     InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                  }else{
                      core.tip.show(json.result);
                  } 
                },true,true);
            });
            //timer处理函数
            function SetRemainTime() {
                if (curCount == 0) {                
                    window.clearInterval(InterValObj);//停止计时器
                    $("#btnSendCode").removeAttr("disabled");//启用按钮
                    $("#btnSendCode").css({"background":"#85d115"});
                    $("#btnSendCode").val("重新发送验证码");
                }
                else {
                    curCount--;
                    $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
                }
            }
            //检验验证码
            function checkcode()
            {
                 core.json('member/sendcode', {
                        'code': $('#code').val(),
                        'op':'checkcode'
                       }, function(json) {
   
                      if(json.status == 0)
                      {
                       core.tip.show(json.result);
                       return;
                      }
                    },true,true);
            }
            $('.info_sub').click(function() {
                  if(!$('#mobile').isMobile()){
                       core.tip.show('请输入正确手机号码!');
                       return;
                  }
                  if( $('#code').isEmpty()){
                       core.tip.show('请输验证码!');
                       return;
                  }
                  if( $('#password').isEmpty() && hasmobile ){
                       core.tip.show('请输入密码!');
                       return;
                  }
                  if( $('#cpassword').isEmpty() && hasmobile ){
                       core.tip.show('请再次输入密码!');
                       return;
                  }
                  if( $('#cpassword').val() != $('#password').val()){
                       core.tip.show('两次密码不一致!');
                       return;
                  }
                 core.json('member/sendcode', {
                        'code': $('#code').val(),
                        'op':'checkcode'
                       }, function(json) {
   
                      if(json.status == 0)
                      {
                       core.tip.show(json.result);
                       return;
                      }
                        core.json('member/bindmobile', {
                           'memberdata':{
                                'mobile': $('#mobile').val(),
                                'password': $('#password').val(),
                               } 
                           }, function(json) {
                            if(json.status==1){
                                 core.tip.show('绑定成功');
                                 //console.log(json.result.preurl);
                                 location.href="<?php  echo $this->createMobileUrl('shop')?>";
                            }
                            else{
                                core.tip.show('绑定失败!');
                            }
                        },true,true);                      
                    },true,true);
                    
                });
    })
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>