<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta name="Generator" content="ECJIA 1.5" />
<meta charset="UTF-8">
<meta name="baidu-site-verification" content="MQtc5aDDm3" />
<link rel="shortcut icon" href="favicon.ico" />

<!-- PC_CSS_JS调用 -->

    <link href="../addons/sz_yi/static/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../addons/sz_yi/template/pc/default/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="../addons/sz_yi/template/pc/default/static/css/index.css">
    <script src="../addons/sz_yi/template/pc/default/static/js/jquery.min.js"></script>
    <script src="../addons/sz_yi/template/pc/default/static/js/bootstrap.min.js"></script>
    <script type='text/javascript' src='../addons/sz_yi/template/pc/default/static/js/jquery.lazyload.js'></script>
    <link href="../addons/sz_yi/template/pc/default/static/css/css.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/pc/default/static/css/stylpce.css">
    <link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/pc/default/static/css/style.css">
    <link rel="stylesheet" href="../addons/sz_yi/template/pc/default/static/css/member-center.css">
    <link rel="stylesheet" href="../addons/sz_yi/template/pc/default/static/css/fontello.css">
    <link href="../addons/sz_yi/template/pc/default/static/css/suggest.css" rel="stylesheet" type="text/css" />
<!-- 原mobile_css_js调用 -->

    <script language="javascript" src="../addons/sz_yi/static/js/require.js"></script>
    <script language="javascript" src="../addons/sz_yi/static/js/app/config.js?v=2"></script>
    <script language="javascript" src="../addons/sz_yi/static/js/dist/jquery-1.11.1.min.js"></script>
    <script language="javascript" src="../addons/sz_yi/static/js/dist/jquery.gcjs.js"></script>


<script language="javascript">
    require(['core','tpl'],function(core,tpl){
        core.init({
            siteUrl: "<?php  echo $_W['siteroot'];?>",
            baseUrl: "<?php  echo $this->createMobileUrl('ROUTES')?>"
        });
       
    })
</script>

<?php  if(is_array($this->header)) { ?>
<div class="follow_topbar"><div class="headimg"><img src="<?php  echo $this->header['icon']?>"></div><div class="info"><div class="i"><?php  echo $this->header['text']?></div><div class="i">关注公众号，享专属服务</div></div><div class="sub" onclick="location.href='<?php  echo $this->header['url']?>'">立即关注</div></div>
<div style='height:44px;'>&nbsp;</div>
<?php  } ?>