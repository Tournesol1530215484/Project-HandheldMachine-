<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="">

<meta name="author" content="">

<title><?php  if(isset($title)) $_W['page']['title'] = $title?><?php  if(!empty($_W['page']['title'])) { ?><?php  echo $_W['page']['title'];?> - <?php  } ?><?php  if(empty($_W['page']['copyright']['sitename'])) { ?><?php  if(IMS_FAMILY != 'x') { ?><?php  echo $site['sitetitle'];?><?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['sitename'];?><?php  } ?></title>

<meta name="keywords" content="<?php  if(empty($_W['page']['copyright']['keywords'])) { ?><?php  if(IMS_FAMILY != 'x') { ?><?php  echo $site['sitetitle'];?>QQ：583489939<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['keywords'];?><?php  } ?>" />

<meta name="description" content="<?php  if(empty($_W['page']['copyright']['description'])) { ?><?php  if(IMS_FAMILY != 'x') { ?><?php  echo $site['sitetitle'];?>QQ：583489939<?php  } ?><?php  } else { ?><?php  echo $_W['page']['copyright']['description'];?><?php  } ?>" />

<link rel="shortcut icon" href="<?php  echo $_W['siteroot'];?><?php  echo $_W['config']['upload']['attachdir'];?>/<?php  if(!empty($_W['setting']['copyright']['icon'])) { ?><?php  echo $_W['setting']['copyright']['icon'];?><?php  } else { ?>images/global/wechat.jpg<?php  } ?>" />



<link rel="shortcut icon" href="http://www.biezao.com/attachment/images/global/wechat.jpg" />

<link href="./resource/css/bootstrap.min.css" rel="stylesheet">

<link href="./resource/css/font-awesome.min.css" rel="stylesheet">

<link href="./resource/css/typicons.min.css" rel="stylesheet">

<link href="./resource/css/login.css" rel="stylesheet">

<script type="text/javascript">

if(navigator.appName == 'Microsoft Internet Explorer'){

    if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {

        alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');

    }

}

window.sysinfo = {

<?php  if(!empty($_W['uniacid'])) { ?>

    'uniacid': '<?php  echo $_W['uniacid'];?>',

<?php  } ?>

<?php  if(!empty($_W['acid'])) { ?>

    'acid': '<?php  echo $_W['acid'];?>',

<?php  } ?>

<?php  if(!empty($_W['openid'])) { ?>

    'openid': '<?php  echo $_W['openid'];?>',

<?php  } ?>

<?php  if(!empty($_W['uid'])) { ?>

    'uid': '<?php  echo $_W['uid'];?>',

<?php  } ?>

    'siteroot': '<?php  echo $_W['siteroot'];?>',

    'siteurl': '<?php  echo $_W['siteurl'];?>',

    'attachurl': '<?php  echo $_W['attachurl'];?>',

    'attachurl_local': '<?php  echo $_W['attachurl_local'];?>',

<?php  if(defined('MODULE_URL')) { ?>

    'MODULE_URL': '<?php echo MODULE_URL;?>',

<?php  } ?>

    'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'}

};

</script>



</head>

<body data-spy="scroll" data-target=".navMenuCollapse">

<div class="header">

  <div class="warper clearfix wryh">

    <div class="logo "><a href="index.php"><img src="<?php  if($site['sitelogo']) { ?>../attachment/<?php  echo $site['sitelogo'];?><?php  } else { ?>./resource/images/logo.png<?php  } ?>" alt="" height="65px"/></a></div>

    <div class="header_text pull-left"></div>

  </div>

</div>

<div class="loginCon">

  <div class="warper clearfix">

    <div class="loginForm">

      <div class="loginFormTop text-center"> 

        <!--<a href="#tab-code" class="nav-code" onclick="document.getElementById('tab_type').value='shortcut';"></a>--> 

        <a href="#tab-password" class="nav-password on" onClick="document.getElementById('tab_type').value='password';"></a> </div>

      <div id="tab-password" class="tabcontent">

        <div class="loginForm-text text-left">账户登录</div>

        <!--<div class="loginForm-text-in text-left">密码错误，请重新输入密码</div>-->

        <form action="" method="post" role="form" onSubmit="return formcheck();" class="loginFormMiddle" >

          <div class="loginInput">

            <div class="input-icon"> <i class="icon icon-user"></i>

              <input name="username" type="text" id="inputName" placeholder="请输入用户名" value="admin">

            </div>







          </div>

          <div class="loginInput mt15">

            <div class="input-icon"> <i class="icon icon-lock"></i>

              <input name="password" type="password" id="inputPassword" placeholder="请输入用户密码" value="admin201659">

            </div>

          </div>

          <div class="btns clearfix text-right">

            <input type="hidden" value="true" name="rember" checked='true'>

            <a href="<?php  echo url('user/register');?>" class="loginForm-zc">免费注册</a>

            <input type="submit" name="submit" value="登 录" class="btn btn-blue " />

            <input type='hidden' id='tab_type' value='password'/>

            <input name="token" value="<?php  echo $_W['token'];?>" type="hidden" />

          </div>

        </form>

        <div class="loginForm-Bottom text-left mt20"> <span>提示：忘记密码请联系客服</span><br>

          <i class="icon icon-tel"></i> 客服热线：<?php  if($site['servicemobile']) { ?><?php  echo $site['servicemobile'];?><?php  } else { ?>020-888888<?php  } ?></div>

      </div>

      <div id="tab-code" class="tabcontent text-center" style="display:none">

        <div class="loginForm-text text-left">快速登录</div>

        <img class="codeImg" src="./resource/images/ewm.jpg" alt="">

        <p class="codetext"><span style="color: #3cc5f8;">O2O一站式电商平台</span><br>

          使用<span class="red">微信</span>扫描二维码登录</p>

      </div>

    </div>

    <div class="loginImg"> <img src="<?php  if($site['backgroup']) { ?>../attachment/<?php  echo $site['backgroup'];?><?php  } else { ?>./resource/images/loginrr.png<?php  } ?>" alt="" width="100%"> </div>

  </div>

</div>

<script>window.onload = function(){var oInput = document.getElementById("inputName");oInput.focus();}</script> 

<script src="./resource/js/lib/jquery-1.7.2.min.js"></script> 

<script src="./resource/js/lib/bootstrap.min.js"></script> 

<script>

$(function(){

	$('.loginFormTop a').click(function(e) {

		e.preventDefault();//取消事件默认动作

		$(e.target).addClass('on').siblings('.on').removeClass('on');

		$(".tabcontent").hide();

		$(this.hash).show();

	});

	/*弹出扫码后的第一次进入*/

	$('#codefirst').modal('show');

	/*弹出失败*/

	//$('#alert_fail').modal('show')

	/*弹出成功*/

	//$('#alert_success').modal('show')

})

function set_qrcode_content()

{

	$.post("./index.php?c=user&a=login&do=qrcode_relogin&", function(resp)

	{

		var qrcode_success = '<div class="loginForm-text text-left">快速登录</div>';

		qrcode_success += '<img class="codeImg" src="./app/qr.php?url=http%3A%2F%2Fwx176092.biezao.com%2Fapp%2Findex.php%3Fi%3D71%26j%3D%26c%3Duser%26a%3Dgetopenid%26login_code%3D" alt="">';

		qrcode_success += '<p class="codetext">O2O一站式电商平台<br>使用<span class="red">微信</span>扫描二维码登录</p>';

		document.getElementById('tab-code').innerHTML = qrcode_success;

	});

}

function GetRTime()

{

	window.setInterval(function()

	{

		if(document.getElementById('tab_type').value == 'shortcut')

		{

			$.post("./index.php?c=user&a=login&do=fresh_login&", function(resp)

			{

				if(resp == 'login_ok')

				{

					var qrcode_success = '<div class="loginForm-text text-left">快速登录</div>';

					qrcode_success += '<span class="phoneImg"></span>';

					qrcode_success += '<p class="codetext">扫码成功！<br> <big>请在手机端确认登陆</big></p>';

					qrcode_success += '<a href="javascript:;" class="red" onclick="set_qrcode_content()">返回二维码登陆</a>';

					document.getElementById('tab-code').innerHTML = qrcode_success;

				}

				else

				{

					if(resp != 'error')location = resp;

				}

			});

		}

	},1000);

}

GetRTime();

	</script>

<div class="footer-bottom clearfix mt40">

  <div class="warper"><?php  if($site['copyrights']) { ?><?php  echo $site['copyrights'];?><?php  } else { ?>Copyright ©2015-2016 All Rights Reserved. 广州海生网络科技有限公司 版本所有<?php  } ?><br><?php  if($site['registercode']) { ?><?php  echo $site['registercode'];?><?php  } else { ?>粤ICP备11035598号<?php  } ?></div>

</div>

</body>

</html>

