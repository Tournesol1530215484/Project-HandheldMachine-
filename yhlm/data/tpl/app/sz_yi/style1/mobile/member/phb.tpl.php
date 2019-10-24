<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<title>积分排行榜</title>
	<link href="<?php  echo $_W['siteroot'];?>app/resource/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/addons/sz_yi/static/css/jfpx/integral.css">
	<script type='text/javascript' src='resource/js/lib/jquery-1.11.1.min.js'></script>

</head>
<body style="background-color:#FFC502;padding-top:0px; padding-bottom:0px;" class="body-gray my-memvers">

<!-- 积分排行 -->
<section style="background:#ff9900;margin-top:-17px;">
	<img src="/addons/sz_yi/static/images/integral.jpg" border="0" width="100%">
</section>
<div class="list-myorder" style="background:#ffffff;">
	<ul class="ul-product" style="color:#ffcc00;font-size:20px;">
		<?php  $key=1?>
		<?php  if(is_array($list)) { foreach($list as $member) { ?>
			<li>
            <span style="float:left;margin-right:10px;border-radius:3px;"><?php  echo $key;?></span>
            <?php  if($key==1||$key==2||$key==3) { ?>
            <span style="float:right;margin-left:10px;border-radius:3px;"><img style="width:30px;height:42px;" src="/addons/sz_yi/static/images/0<?php  echo $key;?>.jpg" style="border-width:0px;"></span>
            <?php  } ?>
				<span class="pic" onClick="newMsg('<?php  echo $member["openid"];?>');"><img src="<?php  echo tomedia($member['avatar']);?>" onerror="this.src='/addons/sz_yi/static/images/notoo.png'" style="border-radius:50px;"></span>
				<div class="text">
					<span class="pro-name">昵称：<?php  echo $member['nickname'];?></span>
						<div class="pro-pric" style="color:#ff9900;font-size:25px;"><span>积分：</span><?php  echo number_format($member['credit1'],0,'','')?></div>
				</div>
			</li>
		<?php  $key++?>
		<?php  } } ?>
	</ul>
</div>
<div align="center"><span style="color:#fff;font-size:16px; padding-bottom:60px;">赶紧加油获得更多积分上榜吧</span></div>
<?php  $show_footer=true?>
<?php  $footer_current='member'?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
