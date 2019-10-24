<?php defined('IN_IA') or exit('Access Denied');?><?php  define('IN_MESSAGE', true)?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
.app,.app body {width:100%;	height:100%;overflow:auto;}
.gw-container{margin: 0 auto; max-width: 720px; min-height: 564px;}
.alert-info { background-color: #F7F7F7;border-color: #F7F7F7;color: #31708f;}
</style>
<div class="gw-container">
	<div class="container-fluid"> 
		<?php  if(defined('IN_MESSAGE')) { ?>
		<div style='padding-top:60px;'>
			<div class="jumbotron clearfix alert alert-<?php  echo $label;?>">
				<div class="row">
					<div class="col-sm-4 col-md-3 col-lg-2" style='text-align:right'>
						<i style="font-size:10em" class="fa fa-5x fa-<?php  if($label=='success') { ?>check-circle<?php  } ?><?php  if($label=='danger') { ?>times-circle<?php  } ?><?php  if($label=='info') { ?>info-circle<?php  } ?><?php  if($label=='warning') { ?>exclamation-triangle<?php  } ?>"></i>
					</div>
					<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
						<?php  if(is_array($msg)) { ?>
							<h2>MYSQL 错误：</h2>
							<p><?php  echo cutstr($msg['sql'], 300, 1);?></p>
							<p><b><?php  echo $msg['error']['0'];?> <?php  echo $msg['error']['1'];?>：</b><?php  echo $msg['error']['2'];?></p>
						<?php  } else { ?>
						<h2><?php  echo $caption;?></h2>
						<p><?php  echo $msg;?></p>
						<?php  } ?>
						<?php  if($redirect) { ?>
						<p><a href="<?php  echo $redirect;?>" class="alert-link">如果你的浏览器没有自动跳转，请点击此链接</a></p>
						<script type="text/javascript">
							setTimeout(function () {
								location.href = "<?php  echo $redirect;?>";
							}, 2000);
						</script>
						<?php  } else { ?>
						<p>[<a href="javascript:history.go(-1);" class="alert-link">点击这里返回上一页</a>] &nbsp; [<a href="./?refresh" class="alert-link">首页</a>]</p>
						<?php  } ?>
					</div>
				</div>
			</div>
		<?php  } else { ?>
		<div class="well" style="padding-top:60px;overflow:auto;">
		<?php  } ?>
<script>
function res(){
   require(['jquery'],function($){
	var h = document.documentElement.clientHeight;
	$(".gw-container").css('min-height',h);
});
}
window.onresize = res;
res();
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer-gw', TEMPLATE_INCLUDEPATH)) : (include template('common/footer-gw', TEMPLATE_INCLUDEPATH));?>
