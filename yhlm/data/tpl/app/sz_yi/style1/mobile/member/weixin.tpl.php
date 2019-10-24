<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>微信登录</title>

<style type="text/css">
    body {margin:0px; background:#efefef; font-family:'微软雅黑'; -moz-appearance:none;}
    .info_main {height:auto;  background:#fff; margin-top:14px; border-bottom:1px solid #e8e8e8; border-top:1px solid #e8e8e8;}
    .info_main .line {margin:0 10px; height:40px; border-bottom:1px solid #e8e8e8; line-height:40px; color:#999;position: relative;}
    .info_main .line .title {height:40px; width:80px; line-height:40px; color:#444; float:left; font-size:16px;}
    .info_main .line .info { width:100%;float:right;margin-left:-80px; }
    .info_main .line .inner { margin-left:80px; }
    .info_main .line .inner input {height:40px; display:block; padding:0px; margin:0px; border:0px; float:left; font-size:16px;}
    .info_main .line .inner .user_sex {line-height:40px;}
    .register {height:44px; margin:14px 5px; background:#31cd00; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .info_sub {height:44px; margin:14px 5px; background:#31cd00; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .nobindmobile {clear:both;height:44px; margin:14px 5px; background:#ccc; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
    .select { border:1px solid #ccc;height:25px;}
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
    <div class="title">微信登录</div>
</div>
    <!-- <div class="info_main">
        <div class="line"><div class="title">手机号码</div><div class='info'><div class='inner'><input type="text" id='mobile' placeholder="请输入您的手机号码"  value="" /></div></div></div>
        <div class="line"><div class="title">验证码</div><div class='info'><div class='inner'><input type="text" id='code' placeholder="请输入验证码"  value="" /><input id="btnSendCode" type="button" style="position: absolute;right: 0;top: 0;" value="发送验证码"  /></div></div></div>

        <div class="page_topbar">
            <div class="title">设置新密码</div>
        </div>

        <div class="line"><div class="title">设置密码</div><div class='info'><div class='inner'><input type="password" id='password' placeholder="请输入您的密码"  value="" /></div></div></div>
        <div class="line"><div class="title">确认密码</div><div class='info'><div class='inner'><input type="password" id='cpassword' placeholder="请确认密码"  value="" /></div></div></div>
        
    </div> -->
    <p>这里是测试微信登录</p>
    <div class="info_sub">保存</div>
</script>

<script language="javascript">
    require(['tpl', 'core'], function(tpl, core) {
        $('#container').html(tpl('member_info'));

            var InterValObj; //timer变量，控制时间
            var count = 60; //间隔函数，1秒执行
            var curCount;//当前剩余秒数

            $('#btnSendCode').click(function(){
                if(!$('#mobile').isMobile()){
                    core.tip.show('请输入正确手机号码!');
                    return;
                }
              　curCount = count;
            　　
                 core.json('member/sendcode', {
                       'mobile': $('#mobile').val(),
                       'op':'forgetcode'
                       }, function(json) {
                        if(json.status==1){
                             //设置button效果，开始计时
                             $("#btnSendCode").attr("disabled", "true");
                             $("#btnSendCode").val(curCount + "秒后重新获取验证码");
                             InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                        　　  //向后台发送处理数据 
                        }else{
                            core.tip.show(json.result);
                        }
                    },true);
            });

            //timer处理函数
            function SetRemainTime() {
                if (curCount == 0) {                
                    window.clearInterval(InterValObj);//停止计时器
                    $("#btnSendCode").removeAttr("disabled");//启用按钮
                    $("#btnSendCode").val("重新发送验证码");
                }
                else {
                    curCount--;
                    $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
                }
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



                  if( $('#password').isEmpty()){
                       core.tip.show('请输入密码!');
                       return;
                  }
                  if( $('#cpassword').isEmpty()){
                       core.tip.show('请再次输入密码!');
                       return;
                  }
                  if( $('#cpassword').val() != $('#password').val()){
                       core.tip.show('两次密码不一致!');
                       return;
                  }
                  //检验验证码
                    core.json('member/sendcode', {
                        'code': $('#code').val(),
                        'op':'checkcode'
                       }, function(json) {
   
                      if(json.status == 0)
                      {
                       core.tip.show(json.result.msg);
                       return;
                      }
                        core.json('member/forget', {
                           'memberdata':{
                                'mobile': $('#mobile').val(),
                                'password': $('#password').val()
                               } 
                           }, function(json) {
                            if(json.status==1){
                                 core.tip.show('修改成功');
                                 //console.log(json.result.preurl);
                                 location.href=json.result.preurl;
                            }
                            else{
                                core.tip.show('修改失败!');
                            }

                        },true,true);

                    },true,true);      

                });
    })
</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
