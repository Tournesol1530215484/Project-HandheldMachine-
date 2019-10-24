<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header-base', TEMPLATE_INCLUDEPATH)) : (include template('common/header-base', TEMPLATE_INCLUDEPATH));?>
<style>
    .navbar-nav li {
        display: table-cell;
        max-width: 110px;
        opacity: 0.6;
        width: 100%;
    }
</style>
<section class="vbox hidden-bsection">
  <header class="bg-dark dk header navbar navbar-fixed-top-xs ">
	<div class="navbar-header aside-md" style="width:190px"> 
		<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav"><i class="fa fa-bars"></i> </a> 
		<div class="nav-box">
			<nav class="navbarv2 text-center clearfix">
				<div class="account-area">
					<div class="dropdown">
						<a data-toggle="dropdown" class="btn btn-link dropdown-toggle  navbarv2-left" href="javascript:;" style="color:#FFF">
							<i class="fa fa-home"></i> <?php  echo $_W['account']['name'];?><b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="javascript:;">
                                    <i class="fa fa-wechat"></i> <?php  echo $_W['account']['name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <i title="管理其它公众号" onclick="window.parent.location='<?php  echo url('account/display');?>'" class="fa fa-cog fa-spin"></i>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php  if($_W['role'] != 'operator') { ?>
                                    <i title="编辑当前账号资料" onclick="window.parent.mainFrame.location='<?php  echo url('account/post', array('uniacid' => $_W['uniacid']));?>'" class="fa fa-pencil"></i>
                                    <?php  } ?>
                                </a>
                            </li>
                    	</ul>
                    </div>
                </div>
            </nav>
        </div>            
        <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> <i class="fa fa-cog"></i></a> 
	</div>      
	<?php  if(defined('IN_SOLUTION')) { ?>
	<ul class="nav navbar-nav hidden-xs">
        <?php  global $solution,$solutions;?>
        <?php  if($_W['role'] != 'operator') { ?>
        <li><a href="<?php  echo url('home/welcome/ext');?>"><i class="fa fa-reply-all"></i>返回公众号功能管理</a></li>
        <?php  } ?>
        <?php  if(is_array($solutions)) { foreach($solutions as $row) { ?>
        <li <?php  if($row['name'] == $solution['name']) { ?> class="dker"<?php  } ?>><a href="<?php  echo url('home/welcome/solution', array('m' => $row['name']));?>"><i class="fa fa-cog"></i><?php  echo $row['title'];?></a></li>
        <?php  } } ?>
        <?php  if($_W['isfounder']) { ?><?php  } ?>
    </ul>
    <?php  } else { ?>
    <ul class="nav navbar-nav hidden-xs">
   
    	<?php  if(!empty($_W['uniacid']) && !$top_nav) { ?>
            <li <?php  if($_GPC['a']=='welcome') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/welcome');?>"><i class="fa fa-sitemap"></i>系统管理</a></li>
            <li><a href="<?php  echo url('account/display');?>"><i class="fa fa-cog"></i>公众号管理</a></li>
            <!--<li><a href="<?php  echo url('utility/emulator');?>"><i class="fa fa-angle-right"></i> <span>模拟测试</span></a></li>-->
            <li <?php  if($_GPC['a']=='updatecache') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/updatecache');?>"><i class="fa fa-cog"></i>更新缓存</a></li>
            <li style="max-width:220px; text-align:left"><a href="<?php  echo url('home/welcome/platform');?>"><i class="fa fa-share"></i>管理公众号（<?php  echo $_W['account']['name'];?>）</a></li>

            <li><a href="<?php  echo url('user/logout');?>"><i class="fa fa-cog"></i>退出系统</a></li>
        <?php  } ?>
                                
        <!--<li><a href="./?refresh"><i class="fa fa-reply-all"></i> 返回系统</a></li>-->
        <?php  global $top_nav;?>
        <?php  if(is_array($top_nav)) { foreach($top_nav as $nav) { ?>
fggfgfhgfh
        <?php  if(!empty($_W['isfounder']) || empty($_W['setting']['permurls']['sections']) || in_array($nav['name'], $_W['setting']['permurls']['sections'])) { ?><li<?php  if(FRAME == $nav['name']) { ?> class="active"<?php  } ?>><a href="<?php  echo url('home/welcome/' . $nav['name']);?>"><i class="<?php  echo $nav['append_title'];?>"></i><?php  echo $nav['title'];?></a></li><?php  } ?>
        trhtrhtrhtr
        <?php  } } ?>
        <?php  if($_W['isfounder']) { ?><?php  } ?>
    </ul>
    <?php  } ?> 
    <ul class="navbar-right">
        <li class="dropdown topbar-notice">
        	<a type="button" data-toggle="dropdown">
        		<i class="fa fa-bell"></i>
        		<span class="badge" id="notice-total">0</span>
        	</a>
        	<div class="dropdown-menu" aria-labelledby="dLabel">
        		<div class="topbar-notice-panel">
        			<div class="topbar-notice-arrow"></div>
        			<div class="topbar-notice-head">系统公告</div>
        			<div class="topbar-notice-body">
            			<ul id="notice-container"></ul>
        			</div>
        		</div>
        	</div>
        </li>
<!--    <li class="dropdown">
        	<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="display:block; max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; "><i class="fa fa-group"></i><?php  echo $_W['account']['name'];?> <b class="caret"></b></a>
        	<ul class="dropdown-menu">
        		<?php  if($_W['role'] != 'operator') { ?>
        		<li><a href="<?php  echo url('account/post', array('uniacid' => $_W['uniacid']));?>" target="_blank"><i class="fa fa-weixin fa-fw"></i> 编辑当前账号资料</a></li>
        		<?php  } ?>
        		<li><a href="<?php  echo url('account/display');?>" target="_blank"><i class="fa fa-cogs fa-fw"></i> 管理其它公众号</a></li>
        		<li><a href="<?php  echo url('utility/emulator');?>" target="_blank"><i class="fa fa-mobile fa-fw"></i> 模拟测试</a></li>
        	</ul>
        </li>-->
        <li class="dropdown topbar-notice2">
        	<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php  echo $_W['user']['username'];?> (<?php  if($_W['role'] == 'founder') { ?>系统管理员<?php  } else if($_W['role'] == 'manager') { ?>公众号管理员<?php  } else { ?>公众号操作员<?php  } ?>) <b class="caret"></b></a>
        	<ul class="dropdown-menu">
                <?php  if(!empty($_W['uniacid']) && !$top_nav) { ?>
                <li><a href="<?php  echo url('home/welcome/platform');?>" target="mainFrame"><i class="fa fa-share fa-fw"></i>继续管理公众号（<?php  echo $_W['account']['name'];?>）</a></li>
                <?php  } ?>
        		<li><a href="<?php  echo url('user/profile/profile');?>" target="mainFrame"><i class="fa fa-weixin fa-fw"></i> 我的账号</a></li>
                <li><a href="<?php  echo url('account/display');?>" target="mainFrame"><i class="fa fa-comments fa-fw"></i>公众号管理</a></li>
       			<?php  if($_W['role'] != 'operator') { ?>
        		<li class="divider"></li>
        		<li><a href="<?php  echo url('system/welcome');?>" target="mainFrame"><i class="fa fa-sitemap fa-fw"></i> 系统管理</a></li>
                <!--<li><a href="<?php  echo url('system/updatecache');?>" target="_blank"><i class="fa fa-refresh fa-fw"></i> 更新缓存</a></li>-->
                <li><a href="<?php  echo url('utility/emulator');?>" target="mainFrame"><i class="fa fa-mobile fa-fw"></i> 模拟测试</a></li>
        		<li><a href="<?php  echo url('system/updatecache');?>" target="mainFrame"><i class="fa fa-refresh fa-fw"></i> 更新缓存</a></li>
        		<li class="divider"></li>
        		<?php  } ?>
        		<li><a href="<?php  echo url('user/logout');?>"><i class="fa fa-sign-out fa-fw"></i> 退出系统</a></li>
        	</ul>
        </li>
    </ul>
	<div class="msgbox"></div>
</header>

<?php  
$modules_shcuts = uni_modules();
$settings_shortcuts = uni_setting($_W['uniacid'], array('shortcuts'));
$shorts_shcuts = $settings_shortcuts['shortcuts'];
if(!is_array($shorts_shcuts)) {
    $shorts_shcuts = array();
}
$shortcut_scs_shcuts = array();
foreach($shorts_shcuts as $shortcut_sc) {
    $module_sc = $modules_shcuts[$shortcut_sc['name']];
    if(!empty($module_sc)) {
        $shortcut_sc['title'] = $module_sc['title'];
        if(file_exists('../addons/' . $module_sc['name'] . '/icon.jpg')) {
            $shortcut_sc['image'] = '../addons/' . $module_sc['name'] . '/icon.jpg';
        } else {
            $shortcut_sc['image'] = '../web/resource/images/nopic-small.jpg';
        }
        $shortcut_scs_shcuts[] = $shortcut_sc;
    }
}
unset($shortcut_sc);
?>
<?php $frames = empty($frames) ? $GLOBALS['frames'] : $frames; _calc_current_frames($frames);?>
<style>
.bg-ordinary {background-color: #; color: #;}
.bg-hover {background-color: #ffc333; color: #fff8e5;}
</style>
<section>
	<section class="hbox stretch"> <!-- .aside -->
        <aside class="bg-light lter aside-mu hidden-print b-r" id="nav">
          <section class="vbox"> 
            <!--<header class="quick bg-primary lter text-center clearfix">
                <div class="btn-group">
                    <a class="btn btn-sm btn-dark btn-icon" title="添加快捷方式" href="./index.php?c=profile&a=module&"><i class="fa fa-plus"></i></a>
                    <div class="btn-group hidden-nav-xs">
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"> 快捷方式 <span class="caret"></span> </button>
                        <ul class="dropdown-menu text-left">
                            <li><a href="./index.php?c=platform&a=reply&m=userapi"><i class="fa fa-sitemap"></i><span>自定义接口</span></a></li>
                                             
                         </ul>
                    </div>
                 </div>
            </header>-->
            <section class="w-f scrollable" style="top:0px">
              <div style="position: relative; overflow: hidden; width: auto; height: 437px;" class="slimScrollDiv">
                <div style="overflow: hidden; width: auto; height: 437px;" class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333"> 
                  <!-- nav -->
                  <nav class="nav-primary hidden-xs">
                    <ul class="nav">
                      <li class="nav-0"> <a href="#layout" class=""> <i class="fa fa-columns icon"> <b class="<?php  if($controller == 'account') { ?>bg-hover<?php  } else { ?>bg-ordinary<?php  } ?>"></b></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>公众号</span> </a>
                        <ul class="nav lt" style="display:<?php  if($controller == 'account' || $controller == '') { ?>block<?php  } else { ?>none<?php  } ?>;">
                          <li class="<?php  if($action == 'display') { ?>active<?php  } ?>"><a href="<?php  echo url('account/display');?>"><i class="fa fa-angle-right"></i><span>公众号列表</span></a></li>
                          <li class="<?php  if($action == 'groups') { ?>active<?php  } ?>"><a href="<?php  echo url('account/groups');?>"><i class="fa fa-angle-right"></i><span>公众号服务套餐</span></a> </li>
                        </ul>
                      </li> 
                      <li class="nav-1"> <a href="#layout" class=""> <i class="fa fa-columns icon"> <b class="<?php  if($controller == 'extension') { ?>bg-hover<?php  } else { ?>bg-ordinary<?php  } ?>"></b></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>扩展管理</span> </a>
                        <ul class="nav lt" style="display:<?php  if($controller == 'extension') { ?>block<?php  } else { ?>none<?php  } ?>;">
                          <li class="<?php  if($action == 'module') { ?>active<?php  } ?>"><a href="<?php  echo url('extension/module');?>"><i class="fa fa-angle-right"></i><span>模块管理</span></a></li>
                          <li class="<?php  if($action == 'service') { ?>active<?php  } ?>"><a href="<?php  echo url('extension/service/display');?>"><i class="fa fa-angle-right"></i><span>常用服务</span></a> </li>
                          <li class="<?php  if($action == 'theme') { ?>active<?php  } ?>"><a href="<?php  echo url('extension/theme');?>"><i class="fa fa-angle-right"></i><span>微站风格</span></a> </li>
                          <li class="<?php  if($action == 'theme') { ?>active<?php  } ?>"><a href="<?php  echo url('extension/theme/web');?>"><i class="fa fa-angle-right"></i><span>后台皮肤</span></a> </li>
                          <li class="<?php  if($action == 'menu') { ?>active<?php  } ?>"><a href="<?php  echo url('extension/menu');?>"><i class="fa fa-angle-right"></i><span>系统菜单</span></a> </li>
                          <li class="<?php  if($action == 'platform') { ?>active<?php  } ?>"><a href="<?php  echo url('extension/platform');?>"><i class="fa fa-angle-right"></i><span>微信平台</span></a> </li>
                        </ul>
                      </li>
                      <li class="nav-2"> <a href="#layout" class=""> <i class="fa fa-columns icon"> <b class="<?php  if($controller == 'article') { ?>bg-hover<?php  } else { ?>bg-ordinary<?php  } ?>"></b></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>文章/公告</span> </a>
                        <ul class="nav lt" style="display:<?php  if($controller == 'article') { ?>block<?php  } else { ?>none<?php  } ?>;">
                          <li class="<?php  if($action == 'news') { ?>active<?php  } ?>"><a href="<?php  echo url('article/news');?>"><i class="fa fa-angle-right"></i><span>文章管理</span></a></li>
                          <li class="<?php  if($action == 'notice') { ?>active<?php  } ?>"><a href="<?php  echo url('article/notice');?>"><i class="fa fa-angle-right"></i><span>公告管理</span></a> </li>
                        </ul>
                      </li>  

                      <li class="nav-3"> <a href="#layout" class=""> <i class="fa fa-columns icon"> <b class="<?php  if($controller == 'user') { ?>bg-hover<?php  } else { ?>bg-ordinary<?php  } ?>"></b></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>用户管理</span> </a>
                       <ul class="nav lt" style="display:<?php  if($controller == 'user') { ?>block<?php  } else { ?>none<?php  } ?>;">
                          <li class="<?php  if($action == 'profile') { ?>active<?php  } ?>"><a href="<?php  echo url('user/profile');?>"><i class="fa fa-angle-right"></i><span>我的账户</span></a></li>
                          <li class="<?php  if($action == 'display') { ?>active<?php  } ?>"><a href="<?php  echo url('user/display');?>"><i class="fa fa-angle-right"></i><span>用户管理</span></a> </li>
                          <li class="<?php  if($action == 'group') { ?>active<?php  } ?>"><a href="<?php  echo url('user/group');?>"><i class="fa fa-angle-right"></i><span>用户组管理</span></a> </li>
                          <li class="<?php  if($action == 'registerset') { ?>active<?php  } ?>"><a href="<?php  echo url('user/registerset');?>"><i class="fa fa-angle-right"></i><span>用户设置</span></a> </li>
                          <li class="<?php  if($action == 'logout') { ?>active<?php  } ?>"><a href="<?php  echo url('user/logout');?>"><i class="fa fa-angle-right"></i> <span>退出系统</span></a></li>
                        </ul>
                      </li>
                      <li class="nav-4"> <a href="#layout" class=""> <i class="fa fa-columns icon"> <b class="<?php  if($controller == 'system') { ?>bg-hover<?php  } else { ?>bg-ordinary<?php  } ?>"></b></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>系统管理</span> </a>
                        <ul class="nav lt" style="display:<?php  if($controller == 'system') { ?>block<?php  } else { ?>none<?php  } ?>;">
                          <li class="<?php  if($action == 'site') { ?>active<?php  } ?>"><a href="<?php  echo url('system/site');?>"><i class="fa fa-angle-right"></i><span>站点设置</span></a></li>
                          <li class="<?php  if($action == 'attachment') { ?>active<?php  } ?>"><a href="<?php  echo url('system/attachment');?>"><i class="fa fa-angle-right"></i><span>附件设置</span></a> </li>
                          <li class="<?php  if($action == 'common') { ?>active<?php  } ?>"><a href="<?php  echo url('system/common');?>"><i class="fa fa-angle-right"></i><span>其他设置</span></a> </li>
                          <li class="<?php  if($action == 'database') { ?>active<?php  } ?>"><a href="<?php  echo url('system/database');?>"><i class="fa fa-angle-right"></i><span>数据库</span></a> </li>
                          <li class="<?php  if($action == 'tools') { ?>active<?php  } ?>"><a href="<?php  echo url('system/tools');?>"><i class="fa fa-angle-right"></i><span>工具管理</span></a> </li>
                          <li class="<?php  if($action == 'logs') { ?>active<?php  } ?>"><a href="<?php  echo url('system/logs');?>"><i class="fa fa-angle-right"></i><span>查看日志</span></a> </li>
                          <li class="<?php  if($action == 'optimize') { ?>active<?php  } ?>"><a href="<?php  echo url('system/optimize');?>"><i class="fa fa-angle-right"></i><span>性能优化</span></a> </li>
                          <li class="<?php  if($action == 'updatecache') { ?>active<?php  } ?>"><a href="<?php  echo url('system/updatecache');?>"><i class="fa fa-angle-right"></i><span>更新缓存</span></a> </li>
                        </ul>
                      </li>
                      <script language="javascript">
                        $(".nav-0").addClass('active');
                      </script>
                    </ul>
                  </nav>
					<script language="javascript">
                    function append(e,url){
                    alert(url);
                        if(url!=''){
                            location.href= url;
                            e.stopPropagation();    
                        }
                    }
                    </script> 
                  <!-- / nav --> 
                </div>
                <div style="background: rgb(51, 51, 51) none repeat scroll 0% 0%; width: 5px; position: absolute; top: -382px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 0px; height: 437px;" class="slimScrollBar"></div>
                <div style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 0px;" class="slimScrollRail"></div>
              </div>
            </section>
            <footer class="footer lt hidden-xs b-t b-light"> <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-default btn-icon "> <i class="fa fa-angle-left text"></i> <i class="fa fa-angle-right text-active"></i> </a>
              <div class="copyright hidden-nav-xs"> &copy; </div>
            </footer>
          </section>
        </aside>
	  <script src="<?php echo RES_URL;?>js/common.js"></script>
      <?php  if(empty($_COOKIE['check_setmeal']) && !empty($_W['account']['endtime']) && ($_W['account']['endtime'] - TIMESTAMP < (6*86400))) { ?>
          <div class="upgrade-tips" id="setmeal-tips">
              <a href="<?php  echo url('user/edit', array('uid' => $_W['account']['uid']));?>" target="_blank">
                  您的服务有效期限：<?php  echo date('Y-m-d', $_W['account']['starttime']);?> ~ <?php  echo date('Y-m-d', $_W['account']['endtime']);?>.
                  <?php  if($_W['account']['endtime'] < TIMESTAMP) { ?>
                  目前已到期，请联系管理员续费
                  <?php  } else { ?>
                  将在<?php  echo ($_W['account']['endtime'] - strtotime(date('Y-m-d')))/86400?>天后到期，请及时付费
                  <?php  } ?>
              </a><span class="tips-close" style="background:#d03e14;" onclick="check_setmeal_hide();"><i class="fa fa-times-circle"></i></span>
          </div>
      <script>
              function check_setmeal_hide() {
                  util.cookie.set('check_setmeal', 1, 1800);
                  $('#setmeal-tips').hide();
                  return false;
              }
      </script>
      <?php  } ?>
     <?php  if(defined('IN_MESSAGE')) { ?>
     <section class='vbox'>
 		<div class="container-fluid" style="margin-top:20px;">
		 
		<div class="jumbotron clearfix alert alert-<?php  echo $label;?>">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-lg-2" style='text-align: right'>
					<i style='font-size:10em' class="fa fa-5x fa-<?php  if($label=='success') { ?>check-circle<?php  } ?><?php  if($label=='danger') { ?>times-circle<?php  } ?><?php  if($label=='info') { ?>info-circle<?php  } ?><?php  if($label=='warning') { ?>exclamation-triangle<?php  } ?>"></i>
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
					<p><a href="<?php  echo $redirect;?>">如果你的浏览器没有自动跳转，请点击此链接</a></p>
					<script type="text/javascript">
						setTimeout(function () {
							location.href = "<?php  echo $redirect;?>";
						}, 3000);
					</script>
					<?php  } else { ?>
					<p>[<a href="javascript:history.go(-1);">点击这里返回上一页</a>] &nbsp; [<a href="./?refresh">首页</a>]</p>
					<?php  } ?>
			</div>
		</div>
	</div>
</section>

<!-- /.aside -->
<section>
	<section class="vbox" >
		<section class="scrollable padder" style="padding-top:10px;">
<?php  } ?>
<style>
gw-container .page-header { -moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color -moz-use-text-color -moz-use-text-color #333; border-image: none; border-style: none none none solid; border-width: medium medium medium 0.3em;  padding-left: 1em;}
</style>
<div class="gw-container" style="margin:1em"> 