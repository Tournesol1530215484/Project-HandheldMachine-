<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:91:"E:\phpstudy\PHPTutorial\WWW\currency\public/../application/admin\view\Article\CateList.html";i:1573096107;s:75:"E:\phpstudy\PHPTutorial\WWW\currency\application\admin\view\Public\top.html";i:1572663696;s:76:"E:\phpstudy\PHPTutorial\WWW\currency\application\admin\view\Public\left.html";i:1573094587;}*/ ?>
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <title><?php echo $AllConf['sitename']; ?></title>

    <meta name="description" content="Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--Basic Styles-->
    <link href="/static/admin/style/bootstrap.css" rel="stylesheet">
    <link href="/static/admin/style/font-awesome.css" rel="stylesheet">
    <link href="/static/admin/style/weather-icons.css" rel="stylesheet">
    <link rel="icon" href="/currency/public/favicon.ico" type="image/x-icon" />

    <!--Beyond styles-->
    <link id="beyond-link" href="/static/admin/style/beyond.css" rel="stylesheet" type="text/css">
    <link href="/static/admin/style/demo.css" rel="stylesheet">
    <link href="/static/admin/style/typicons.css" rel="stylesheet">
    <link href="/static/admin/style/animate.css" rel="stylesheet">


</head>
<body>
<!-- 头部 -->
<div class="navbar">
    <div class="navbar-inner">
        <div class="navbar-container">
            <!-- Navbar Barnd -->
            <div class="navbar-header pull-left">

                <a href="#" class="navbar-brand">
                    <small>
                        <img src="/static/admin/images/logo.png" style="width:200px; height:60px " alt="">
                    </small>
                </a>
            </div>
            <!-- /Navbar Barnd -->
            <!-- Sidebar Collapse -->
            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="collapse-icon fa fa-bars"></i>
            </div>
            <!-- /Sidebar Collapse -->
            <!-- Account Area and Settings -->
            <div class="navbar-header pull-right">
                <div class="navbar-account">
                    <ul class="account-area">

                        <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" title="Help" href="#">
                                    <i class="icon fa fa-envelope"></i>
                                    <span class="badge">8</span>
                                </a>
                                <!--Messages Dropdown-->
                                <ul class="pull-right dropdown-menu dropdown-arrow dropdown-messages">
                                    <li>
                                        <a href="#">
                                            <img src="/static/admin/images/001.jpg" class="message-avatar" alt="Divyia Austin">
                                            <div class="message">
                                                <span class="message-sender">
                                                    Creazy
                                                </span>
                                                <span class="message-time">
                                                    43分钟之前
                                                </span>
                                                <span class="message-subject">
                                                    分销完成
                                                </span>
                                                <span class="message-body">
                                                    3点之前完成所有的信息，并发邮箱提醒...
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="/static/admin/images/002.jpg" class="message-avatar" alt="Microsoft Bing">
                                            <div class="message">
                                                <span class="message-sender">
                                                    阿茶
                                                </span>
                                                <span class="message-time">
                                                    11月1日 13:22:30
                                                </span>
                                                <span class="message-subject">
                                                    胡说不胡说
                                                </span>
                                                <span class="message-body">
                                                   胡说不胡说胡说不胡说胡说不胡说胡说不胡说胡说不胡说
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="/static/admin/images/003.jpg" class="message-avatar" alt="Divyia Austin">
                                            <div class="message">
                                                <span class="message-sender">
                                                    谁抢了我的枫叶ID
                                                </span>
                                                <span class="message-time">
                                                   10月10日 12:22:22
                                                </span>
                                                <span class="message-subject">
                                                    瞎说不瞎说
                                                </span>
                                                <span class="message-body">
                                                    瞎说不瞎说瞎说不瞎说瞎说不瞎说瞎说不瞎说瞎说不瞎说瞎说不瞎说
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="dropdown-footer">
                                        <a href="#">
                                            查看所以的消息
                                        </a>
                                        <button class="btn btn-xs btn-default shiny darkorange icon-only pull-right"><i class="fa fa-check"></i></button>
                                    </li>
                                </ul>
                                <!--/Messages Dropdown-->
                            </li>
                            <li>
                                <a class="dropdown-toggle" data-toggle="dropdown" title="Tasks" href="#">
                                    <i class="icon fa fa-tasks"></i>
                                    <span class="badge">18</span>
                                </a>
                                <!--Tasks Dropdown-->
                                <ul class="pull-right dropdown-menu dropdown-tasks dropdown-arrow ">
                                    <li class="dropdown-header bordered-darkorange">
                                        <i class="fa fa-tasks"></i>
                                        18 个任务正在进行
                                    </li>

                                    <li>
                                        <a href="#">
                                            <div class="clearfix">
                                                <span class="pull-left">会员模块</span>
                                                <span class="pull-right">65%</span>
                                            </div>

                                            <div class="progress progress-xs">
                                                <div style="width:65%" class="progress-bar"></div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <div class="clearfix">
                                                <span class="pull-left">分销模块</span>
                                                <span class="pull-right">35%</span>
                                            </div>

                                            <div class="progress progress-xs">
                                                <div style="width:35%" class="progress-bar progress-bar-success"></div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <div class="clearfix">
                                                <span class="pull-left">积分模块</span>
                                                <span class="pull-right">75%</span>
                                            </div>

                                            <div class="progress progress-xs">
                                                <div style="width:75%" class="progress-bar progress-bar-darkorange"></div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <div class="clearfix">
                                                <span class="pull-left">商品模块</span>
                                                <span class="pull-right">10%</span>
                                            </div>

                                            <div class="progress progress-xs">
                                                <div style="width:10%" class="progress-bar progress-bar-warning"></div>
                                            </div>
                                        </a>
                                    </li>

                                    <li class="dropdown-footer">
                                        <a href="#">
                                            查看所有任务进度
                                        </a>
                                        <button class="btn btn-xs btn-default shiny darkorange icon-only pull-right"><i class="fa fa-check"></i></button>
                                    </li>
                                </ul>
                                <!--/Tasks Dropdown-->
                            </li>
                        <li>
                            <a class="login-area dropdown-toggle" data-toggle="dropdown">
                                <div class="avatar" title="View your public profile">
                                    <img src="/static/admin/images/adam-jansen.jpg">
                                </div>
                                <section>
                                    <h2><span class="profile"><span><?php echo \think\Request::instance()->session('username'); ?></span></span></h2>
                                </section>
                            </a>
                            <!--Login Area Dropdown-->
                            <ul class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                                <li class="username"><a>David Stevenson</a></li>
                                <li class="dropdown-footer">
                                    <a href="<?php echo url('login/login'); ?>">
                                        退出登录
                                    </a>
                                </li>
                                <li class="dropdown-footer">
                                    <a href="<?php echo url('edit',array('id'=>\think\Request::instance()->session('id'))); ?>">
                                        修改密码
                                    </a>
                                </li>
                            </ul>
                            <!--/Login Area Dropdown-->
                        </li>
                        <!-- /Account Area -->
                        <!--Note: notice that setting div must start right after account area list.
                            no space must be between these elements-->
                        <!-- Settings -->
                    </ul>
                </div>
            </div>
            <!-- /Account Area and Settings -->
        </div>
    </div>
</div>

<!-- /头部 -->

<div class="main-container container-fluid">
    <div class="page-container">
        <!-- Page Sidebar -->
        <div class="page-sidebar" id="sidebar">
    <!-- Page Sidebar Header-->
    <div class="sidebar-header-wrapper">
        <input class="searchinput" type="text">
        <i class="searchicon fa fa-search"></i>
        <div class="searchhelper">Search Reports, Charts, Emails or Notifications</div>
    </div>
    <!-- /Page Sidebar Header -->
    <!-- Sidebar Menu -->
    <ul class="nav sidebar-menu">
        <!--Dashboard-->
        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-globe"></i>
                <span class="menu-text">文章新闻模块</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Article/Articlelist'); ?>">
                        <span class="menu-text">文章列表</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Article/Articlecat'); ?>">
                        <span class="menu-text">文章分类</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

         <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa  fa-windows"></i>
                <span class="menu-text">系统管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li class="open">
                    <a class="menu-dropdown" href="<?php echo url('admin/Conf/ConfList'); ?>"><span class="menu-text">配置项</span><i class="menu-expand"></i></a>
                </li>

                <li class="open">
                    <a class="menu-dropdown" href="<?php echo url('admin/Conf/Clist'); ?>"><span class="menu-text">配置项值</span><i class="menu-expand"></i></a>
                </li>

                <li class="open">
                    <a class="menu-dropdown" href="<?php echo url('admin/Conf/ClearCache'); ?>"><span class="menu-text">清除缓存</span><i class="menu-expand"></i></a>
                </li>

            </ul>
        </li>


          <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-vimeo-square"></i>
                <span class="menu-text">权限类</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Admin/lisGroup'); ?>">
                        <span class="menu-text">角色列表</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Admin/lisRule'); ?>">
                        <span class="menu-text">权限列表</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Admin/lisAdmin'); ?>">
                        <span class="menu-text">管理员管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

         <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-globe"></i>
                <span class="menu-text">充值类</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">支付宝充值</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">微信充值</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-globe"></i>
                <span class="menu-text">短信邮件类</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">短信</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">邮件</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>


        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-globe"></i>
                <span class="menu-text">订单类</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">订单列表</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">订单统计</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>






           <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-vimeo-square"></i>
                <span class="menu-text">会员类</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Member/MemberList'); ?>">
                        <span class="menu-text">会员列表</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>


        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa  fa-calendar"></i>
                <span class="menu-text">商品管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Goods/GoodsList'); ?>">
                        <span class="menu-text">商品列表</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Goods/GoodsAdd'); ?>">
                        <span class="menu-text">添加商品</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('brand/BrandList'); ?>">
                        <span class="menu-text">商品品牌</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Category/CateList'); ?>">
                        <span class="menu-text">商品分类</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('CategoryWord/CateWordList'); ?>">
                        <span class="menu-text">商品类型关联词</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                 <li>
                    <a href="<?php echo url('CategoryBrand/CateBrandList'); ?>">
                        <span class="menu-text">商品类型品牌</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Goodstype/TypeList'); ?>">
                        <span class="menu-text">商品类型</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="menu-text">商品回收站</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>


         <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-vimeo-square"></i>
                <span class="menu-text">对象存储类</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Member/MemberList'); ?>">
                        <span class="menu-text">七牛云oss</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Member/MemberList'); ?>">
                        <span class="menu-text">阿里云oss</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                 <li>
                    <a href="<?php echo url('Member/MemberList'); ?>">
                        <span class="menu-text">腾讯云oss</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <!-- <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-globe"></i>
                <span class="menu-text">导航管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Nav/NavList'); ?>">
                        <span class="menu-text">导航管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

         <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-globe"></i>
                <span class="menu-text">广告管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Ad/AdList'); ?>">
                        <span class="menu-text">广告管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa    fa-laptop"></i>
                <span class="menu-text">推荐位管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Recommend/RecList'); ?>">
                        <span class="menu-text">推荐位管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-bullhorn"></i>
                <span class="menu-text">促销管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">
                        <span class="menu-text">促销管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-building-o"></i>
                <span class="menu-text">订单管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">
                        <span class="menu-text">订单管理管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-vimeo-square"></i>
                <span class="menu-text">会员管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Member/MemberList'); ?>">
                        <span class="menu-text">会员管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>


        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-comment-o"></i>
                <span class="menu-text">短信管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">
                        <span class="menu-text">短信管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa  fa-folder-o"></i>
                <span class="menu-text">文章模块</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="<?php echo url('Cate/CateList'); ?>">
                        <span class="menu-text">栏目管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
                <li>
                    <a href="<?php echo url('Article/ArticleList'); ?>">
                        <span class="menu-text">文章管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa   fa-bitbucket"></i>
                <span class="menu-text">数据库管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">
                        <span class="menu-text">数据库管理</span>
                        <i class="menu-expand"></i>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-dropdown">
                <i class="menu-icon fa  fa-windows"></i>
                <span class="menu-text">系统管理</span>
                <i class="menu-expand"></i>
            </a>
            <ul class="submenu">
                <li class="open">
                    <a class="menu-dropdown" href="<?php echo url('admin/Conf/ConfList'); ?>"><span class="menu-text">配置项</span><i class="menu-expand"></i></a>
                </li>

                <li class="open">
                    <a class="menu-dropdown" href="<?php echo url('admin/Conf/Clist'); ?>"><span class="menu-text">配置项值</span><i class="menu-expand"></i></a>
                </li>

                <li class="open">
                    <a class="menu-dropdown" href="<?php echo url('admin/Conf/ClearCache'); ?>"><span class="menu-text">清除缓存</span><i class="menu-expand"></i></a>
                </li>

            </ul>
        </li> -->



    </ul>
    <!-- /Sidebar Menu -->
</div>
        <!-- /Page Sidebar -->
        <!-- Page Content -->
        <div class="page-content">
            <!-- Page Breadcrumb -->
            <div class="page-breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="#">系统</a>
                    </li>
                    <li class="active">文章栏目</li>
                </ul>
            </div>
            <!-- /Page Breadcrumb -->

            <!-- Page Body -->
            <div class="page-body">

                <button type="button" tooltip="添加栏目" class="btn btn-sm btn-azure btn-addon" onClick="javascript:window.location.href = 'CateAdd'"> <i class="fa fa-plus"></i> Add
                </button>
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="widget">
                            <div class="widget-body">
                                <div class="flip-scroll">
                                    <table class="table table-bordered table-hover">
                                        <thead class="">
                                        <tr>
                                            <th class="text-center" width="4%">ID</th>
                                            <th class="text-center">栏目名称</th>
                                            <th class="text-center">栏目类型</th>
                                            <th class="text-center">关 键 字</th>
                                            <th class="text-center">导航显示</th>
                                            <th class="text-center">排序</th>
                                            <th class="text-center" width="15%">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(is_array($Cate) || $Cate instanceof \think\Collection || $Cate instanceof \think\Paginator): $i = 0; $__LIST__ = $Cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$CateRes): $mod = ($i % 2 );++$i;?>
                                        <tr>
                                            <td align="center" name="cateid"><?php echo $CateRes['cat_id']; ?></td>
                                            <td align="left"> <?php if($CateRes['lever'] != 0): endif; ?><?php echo str_repeat('-',$CateRes['lever']*6)?><?php echo $CateRes['cat_name']; ?></td>
                                            <td align="center">
                                                <?php if($CateRes['cat_type'] == 1): ?>
                                                    系统分类
                                                <?php elseif($CateRes['cat_type'] == 2): ?>
                                                    帮助分类
                                                <?php elseif($CateRes['cat_type'] == 3): ?>
                                                    网店帮助
                                                <?php elseif($CateRes['cat_type'] == 4): ?>
                                                    网店信息
                                                <?php else: ?>
                                                    普通分类
                                                <?php endif; ?>
                                            </td>
                                            <td align="center"><?php echo $CateRes['keywords']; ?></td>
                                            <td align="center">
                                                <?php if($CateRes['show_in_nav'] == 0): ?>
                                                <img src="/static/admin/images/no.png" style="width: 25px" height="25px">
                                                <?php else: ?>
                                                <img src="/static/admin/images/yes.png " style="width: 25px" height="25px">
                                                <?php endif; ?>
                                            </td>
                                            <td align="center"><input type="text" value="<?php echo $CateRes['sort_order']; ?>"  name="sort_order" style="width: 30px;height: 30px;text-align: center" ></td>
                                            
                                            <td align="center">
                                                <a href="<?php echo url('CateEdit',array('cat_id'=>$CateRes['cat_id'])); ?>" class="btn btn-primary btn-sm shiny">
                                                    <i class="fa fa-edit"></i> 编辑
                                                </a>

                                                <a href="#" onClick="warning('确实要删除吗', '<?php echo url('CateDel',array('cat_id'=>$CateRes['cat_id'])); ?>')"
                                                   <?php if(in_array(($CateRes['cat_id']), explode(',',"1,2,3,4"))): ?>
                                                        disabled="disabled"
                                                   <?php endif; ?>
                                                   class="btn btn-danger btn-sm shiny" >
                                                    <i class="fa fa-trash-o"></i> 删除
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                        
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- /Page Body -->
        </div>
        <!-- /Page Content -->
    </div>
</div>

<!--Basic Scripts-->
<script src="/static/admin/style/jquery_002.js"></script>
<script src="/static/admin/style/bootstrap.js"></script>
<script src="/static/admin/style/jquery.js"></script>
<!--Beyond Scripts-->
<script src="/static/admin/style/beyond.js"></script>

<!--排序-->
<script style="text/javascript">
    $(function (){
        $('#pai').click(function(){
            $(['name=cateid']).each(function(){
                alert($(this).val());
            });

//            var url="<?php echo url('/admin/Cate/Catesort'); ?>";
//            $.ajax({
//                method:'POST',
//                url:url,
//                data:{
//                    ids:ids,
//                    sort:sort
//                },
//                dataType:'JSON',
//                success: function (msg) {
//                    console.log(111);
//                },
//                error:function(){
//                    console.log('error');
//                }
//
//            })
        });
    })

</script>






</body></html>