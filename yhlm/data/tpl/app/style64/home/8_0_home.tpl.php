<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('slide', TEMPLATE_INCLUDEPATH)) : (include template('slide', TEMPLATE_INCLUDEPATH));?>
<style>
body{
font:<?php  echo $_W['styles']['fontsize'];?> <?php  echo $_W['styles']['fontfamily'];?>;
color:<?php  echo $_W['styles']['fontcolor'];?>;
padding:0;
margin:0;
background-image:url('<?php  if(!empty($_W['styles']['indexbgimg'])) { ?><?php  echo $_W['styles']['indexbgimg'];?><?php  } ?>');
background-size:cover;
background-color:<?php  if(empty($_W['styles']['indexbgcolor'])) { ?>#E9EAEC<?php  } else { ?><?php  echo $_W['styles']['indexbgcolor'];?><?php  } ?>;
<?php  echo $_W['styles']['indexbgextra'];?>
}
a{color:<?php  echo $_W['styles']['linkcolor'];?>; text-decoration:none;}
<?php  echo $_W['styles']['css'];?>
.box{width:98.5%; margin:1.5% 0 1.5% 1.5%; overflow:hidden;}
.box .box-item1{float:left;text-align:center;display:block;text-decoration:none;outline:none;width:<?php  echo (100/4-2).'%';?>;height:75px;position:relative; color:#666;margin:0 0 5% 2%; padding:5px 0;}
.box .box-item1 i{display:inline-block;width:50px;height:50px;line-height:50px;font-size:25px;color:#fff;overflow: hidden; border:2px #FFF solid;}
.box .box-item1 span{color:<?php  echo $_W['styles']['fontnavcolor'];?>;display:block;font-size:14px; position:absolute; bottom:3.5%; width:100%;}

.box .box-item2{float:left;text-align:center;display:block;text-decoration:none;outline:none;width:<?php  echo (100/3-2).'%';?>;height:80px;position:relative; color:#666;background:#66c574;padding:0 0 5px 0; margin:0 2% 5% 0;}
.box .box-item2 i{display:inline-block;width:100%;height:100%;line-height:50px;font-size:35px;color:#FFF; overflow: hidden;}
.box .box-item2 span{color:<?php  echo $_W['styles']['fontnavcolor'];?>;display:block;font-size:14px; position:absolute; bottom:0; width:100%; background:#fff; border-bottom-left-radius:3px;border-bottom-right-radius:3px; -moz-border-radius-bottomleft:3px; -moz-border-radius-bottomright:3px; -webkit-border-bottom-left-radius:3px; -webkit-border-bottom-right-radius:3px;}
</style>
<div class="box">
	<?php  $num = 1;?>
	<?php  if(is_array($navs)) { foreach($navs as $nav) { ?>
	<?php  if($num == 1) $bg = '#AB6269';?>
	<?php  if($num == 2) $bg = '#AB916E';?>
	<?php  if($num == 3) $bg = '#647F90';?>
	<?php  if($num == 4) $bg = '#B08A77';?>
	<?php  if(($num <= 4)) { ?>
	<a href="<?php  echo $nav['url'];?>" class="box-item1 icon-rounded">
		<?php  if(!empty($nav['icon'])) { ?>
		<i style="background:url(<?php  echo $_W['attachurl'];?><?php  echo $nav['icon'];?>) no-repeat;background-size:cover;" class="img-circle"></i>
		<?php  } else { ?>
		<i class="<?php  echo $nav['css']['icon']['icon'];?> img-circle" style="background:<?php  echo $bg;?>; "></i>
		<?php  } ?>
		<span style="<?php  echo $nav['css']['name'];?>"><?php  echo $nav['name'];?></span>
	</a>
	<?php  } else { ?>
	<a href="<?php  echo $nav['url'];?>" class="box-item2 img-rounded">
		<?php  if(!empty($nav['icon'])) { ?>
		<i style="background:url(<?php  echo $_W['attachurl'];?><?php  echo $nav['icon'];?>) no-repeat;background-size:cover;" class=""></i>
		<?php  } else { ?>
		<i class="<?php  echo $nav['css']['icon']['icon'];?>" style="<?php  echo $nav['css']['icon']['style'];?>"></i>
		<?php  } ?>
		<span style="<?php  echo $nav['css']['name'];?>"><?php  echo $nav['name'];?></span>
	</a>
	<?php  } ?>
	<?php  $num++;?>
	<?php  } } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>

