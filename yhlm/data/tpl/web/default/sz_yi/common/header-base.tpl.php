<?php defined('IN_IA') or exit('Access Denied');?><?php  !defined('RES_URL') && define('RES_URL','./themes/default/style/')?>
<!DOCTYPE html>
<html lang="en" class="app">
<head>  
<meta charset="utf-8">
<meta name="baidu-site-verification" content="MQtc5aDDm3" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="referrer" content="never">	 		 	 	 	 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php  if(isset($title)) $_W['page']['title'] = $title?><?php  if(!empty($_W['page']['title'])) { ?><?php  echo $_W['page']['title'];?> - <?php  } ?><?php  if(empty($_W['page']['copyright']['sitename'])) { ?>公众平台管理系统<?php  } else { ?><?php  echo $_W['page']['copyright']['sitename'];?><?php  } ?></title>
<meta name="keywords" content="<?php  if(empty($_W['page']['copyright']['keywords'])) { ?>微信,微信公众平台<?php  } else { ?><?php  echo $_W['page']['copyright']['keywords'];?><?php  } ?>" />
<meta name="description" content="<?php  if(empty($_W['page']['copyright']['description'])) { ?>微信公众平台管理系统，是国内最完善移动网站及移动互联网技术解决方案。<?php  } else { ?><?php  echo $_W['page']['copyright']['description'];?><?php  } ?>" />
<link rel="shortcut icon" href="<?php  echo $_W['siteroot'];?><?php  echo $_W['config']['upload']['attachdir'];?>/<?php  if($_W['setting']['copyright']['icon']) { ?><?php  echo $_W['setting']['copyright']['icon'];?><?php  } else { ?>images/global/wechat.jpg<?php  } ?>" />

<script src="./resource/js/app/util.js"></script>
<link rel="stylesheet" href="./resource/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo RES_URL;?>css/common.css" >
<link rel="stylesheet" href="./resource/css/font-awesome.min.css" type="text/css" />

<link rel="stylesheet" href="<?php echo RES_URL;?>css/animate.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo RES_URL;?>css/style.min.css" type="text/css" />

<script>var require = { urlArgs: 'v=<?php  echo date('YmdH');?>' };</script>
<script src="./resource/js/require.js"></script>
<script src="./resource/js/app/config.js"></script>	 	 
<script src="./resource/js/lib/jquery-1.11.1.min.js"></script>	 	 
<!--[if lt IE 9]>	 	 	 	 	 	 	 
<script src="./resource/js/html5shiv.min.js"></script>
<script src="./resource/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
if(navigator.appName == 'Microsoft Internet Explorer'){
	if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
		alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
	}
}
window.sysinfo = {
	<?php  if(!empty($_W['uniacid'])) { ?>'uniacid': '<?php  echo $_W['uniacid'];?>',<?php  } ?>
	<?php  if(!empty($_W['acid'])) { ?>'acid': '<?php  echo $_W['acid'];?>',<?php  } ?>
	<?php  if(!empty($_W['openid'])) { ?>'openid': '<?php  echo $_W['openid'];?>',<?php  } ?>
	<?php  if(!empty($_W['uid'])) { ?>'uid': '<?php  echo $_W['uid'];?>',<?php  } ?>
	'siteroot': '<?php  echo $_W['siteroot'];?>',
	'siteurl': '<?php  echo $_W['siteurl'];?>',
	'attachurl': '<?php  echo $_W['attachurl'];?>',
	<?php  if(defined('MODULE_URL')) { ?>'MODULE_URL': '<?php echo MODULE_URL;?>',<?php  } ?>
	'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'}
};
</script>
</head>
<body>