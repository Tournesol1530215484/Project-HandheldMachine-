<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/navigation', TEMPLATE_INCLUDEPATH)) : (include template('common/navigation', TEMPLATE_INCLUDEPATH));?>

        <div class="fl wfs bcf7">
            <div class="regist-process-wrapper">
                <div class="regist-process-body fl wfs">
                    
                    <div class="regist-process-register-left fl">
                        <h2 class="title">用户注册</h2>
                        <form id="registerForm" action="user.php" method="post" name="formUser" onsubmit="return register2();">
                            <div>
                                <span class="title">手机号：</span>
                                <input class="form-control text" id="mobile" name="mobile"  type="text">
                                <p class="tips" id="username_notice"></p>
                            </div>
                            <div>
                                <span class="title">验证码：</span>
                                <input id="code" class="form-control text" type="text"  name="code">
                                <input type="button" class="yzma" id="btnSendCode" value="发送验证码">
                                <!-- <a href="#" class="yzma">60秒后重新发送</a> -->
                                <p class="tips" id="email_notice"></p>
                            </div>
                            
                            <div>
                                <span class="title">密码：</span>
                                <input class="form-control text" id="password"  name="password"  type="password">
                                <p class="tips" id="password_notice"></p>
                            </div>
                            <div>
                                <span class="title">确认密码：</span>
                                <input class="form-control text" id="cpassword" onblur="check_conform_password(this.value);" name="cpassword" type="password">
                                <p class="tips" id="conform_password_notice"></p>      
                            </div>
                            <!-- <div class="read-protocal">
                                <input id="protocal" name="agreement" type="checkbox" value="1" checked="checked" />
                                阅读并已同意<a href="http://help.ecmoban.com/article-6.html" target="_blank">《多级分销商城用户协议》</a>
                            </div> -->
                            <input type="hidden" name="act" value="act_register" >
                            <input type="hidden" name="back_act" value="" />
                            <input class="btn btn-danger register-now register" name="Submit" type="button"  value="立即注册">
                        </form>
                    </div>
                    
                   <div class="regist-process-register-right fr">
                       <h2 class="title">我已注册账号</h2>
                       <a class="btn btn-info login-now" href="<?php  echo $this->createMobileUrl('member')?>">立即登录</a>
                       <div class="scan" style="margin-top:25px">
			   <?php  if($this->yzShopSet['reglogo']) { ?>
				<img src="<?php  echo $_W['siteroot'] . "attachment/" . $this->yzShopSet['reglogo']?>" style="width:335px;height:230px;" title="<?php  echo $this->yzShopSet['pctitle']?>">
			    <?php  } else { ?>
				<img src="../addons/sz_yi/template/pc/default/static/images/logo.png" title="" alt="我是默认logo">
			    <?php  } ?>
                       </div>
                   </div>
                    
                </div>
            </div>
            
        </div>

<div class="blank"></div>
    <div class="regist-process-foot fl wfs">
        <p class="copyright"><?php  echo htmlspecialchars_decode($this->yzShopSet['pccopyright'])?></p>
    </div>

</div>
<script language="javascript">
    require(['tpl', 'core'], function(tpl, core) {
        //$('#container').html(tpl('member_info'));

            $('#mobile').blur(function(){
                if(!$('#mobile').isMobile()){
                    core.tip.show('请输入正确手机号码!');
                    return;
                }
                core.json('member/sendcode', {
                      'op'    : 'ismobile',
                      'mobile'  : $('#mobile').val(),
                       }, function(json) {
                        if(json.status==0){
                             core.tip.show(json.result);
                        }
                }, true, true);
            });
            
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
                       'mobile': $('#mobile').val()
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
                    },true,true);
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
                    $("#btnSendCode").val(curCount + "秒后重新验证码");
                }
            }


            $('.register').click(function() {
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
                       core.tip.show(json.result);
                       return;
                      }
                        core.json('member/register', {
                                'mobile': $('#mobile').val(),
                                'password': $('#password').val(),
                                'code': $('#code').val(),
                           }, function(json) {
                            if(json.status==1){
                                 core.tip.show('注册成功');
                                 location.href=json.result;
                            }
                            else{
                                core.tip.show(json.result);
                            }

                        },true, true);
                      
                    },true,true);
            });
    })
</script>
</body>
</html>
