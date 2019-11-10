<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:86:"E:\phpstudy\PHPTutorial\WWW\currency\public/../application/admin\view\index\index.html";i:1573133419;s:75:"E:\phpstudy\PHPTutorial\WWW\currency\application\admin\view\Public\top.html";i:1572663696;s:76:"E:\phpstudy\PHPTutorial\WWW\currency\application\admin\view\Public\left.html";i:1573094587;}*/ ?>
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

    <!-- 时间筛选css -->
    <link rel="stylesheet" type="text/css" href="/static/admin/style/jedate.css" />


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
                    <li class="active">文章管理员</li>
                </ul>
            </div>
            <!-- /Page Breadcrumb -->

            <!-- Page Body -->
            <div class="page-body" >
                <!--查询操作结束-->
        <form  method="post" class="form-horizontal form" enctype="multipart/form-data" class="form-inline" id="search-form2" >

                <button type="button"  class="btn btn-sm btn-azure btn-addon" onClick="javascript:window.location.href = '<?php echo url('/admin/Index/addadmin'); ?>'" style=" display: inline-block; padding: 8px 9px;"> <i class="fa fa-plus"></i> 添加管理员</button>
                <span class="label label-sky graded" style="margin-left: 30px;padding: 11px 6px;">I D</span>
                <input type="id" class="form-control" name="id"  placeholder="输出用户ID" style="width: 150px; display: inline-block;" >

                <span class="label label-sky graded" style="margin-left: 30px;padding: 11px 6px;">姓名</span>
                <input type="name" class="form-control" name="realname" placeholder="输出用户姓名" style="width: 150px; display: inline-block;" >

                <span class="label label-sky graded" style="margin-left: 30px;padding: 11px 6px;">启用</span>
                
                <select name="status" class="form-control" style="width: 138px; display: inline-block;" >
                        <option value="" style="text-align: center;"></option>
                        <option value="1" style="text-align: center;">启用</option>
                        <option value="0" style="text-align: center;">禁用</option>                  
                </select>

               
                    <label >
                        <span class="label label-sky graded" style="margin-left: 30px;padding: 11px 6px;">开始时间</span>
                        <input type="" class="form-control" style="width: 150px; display: inline-block;" name="" id="startTime" value="" readonly="" />
                    </label>
                    <label>
                          <span class="label label-sky graded" style="margin-left: 30px;padding: 11px 6px;">结束时间</span>
                        <input type="" class="form-control" style="width: 150px; display: inline-block;" name="" id="endTime" value="" readonly />
                    </label>
                
                
                <a href="javascript:;" onclick="selectInfo()"  class="btn btn-blue" style="float: right;margin-right: 60px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">查 询</font></font></a>

                <a href="javascript:;" onclick="exportUser()" class="btn btn-blue" style="float: right;margin-right: 60px;" name="Excel" value="1"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">导 出Excel</font></font></a>

            </form>

                <div class="row" style="padding-top: 20px">

                     <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div class="widget">
                                <div class="">
                                    <div class="flip-scroll">
                                        <table class="table table-bordered table-hover">
                                            <thead class="">
                                            <tr>
                                                <th class="text-center" width="4%">ID</th>
                                                <th class="text-center">真实姓名</th>
                                                <th class="text-center">银行信息</th>
                                                <th class="text-center">手机</th>
                                                <th class="text-center">微信</th>
                                                <th class="text-center">是否启用</th>
                                                <th class="text-center" width="15%">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(is_array($Info) || $Info instanceof \think\Collection || $Info instanceof \think\Paginator): $i = 0; $__LIST__ = $Info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$InfoRes): $mod = ($i % 2 );++$i;?>
                                                
                                            <tr>
                                                <td align="center" ><?php echo $InfoRes['id']; ?></td>
                                                <td align="center"><?php echo $InfoRes['realname']; ?></td>
                                                <td align="center"><?php echo $InfoRes['bank']; ?></td>
                                                <td align="center"><?php echo $InfoRes['mobile']; ?></td>
                                                <td align="center"><?php echo $InfoRes['weixin']; ?></td>
                                                <td align="center">
                                                    <label>
                                                        <input class="checkbox-slider toggle colored-blue flag" <?php if($InfoRes['status']==1){echo 'checked="checked"';}?>   name="checled" value="<?php echo $InfoRes['status']; ?>"  type="checkbox" >
                                                        <span class="text"></span>
                                                    </label>
                                                </td>
                                                <td align="center">
                                                        
                                                       <a href="<?php echo url('/admin/Index/editadmin',array('id'=>$InfoRes['id'])); ?>"  class="btn btn-primary btn-sm shiny editinfo">
                                                        <i class="fa fa-edit "></i> 编辑
                                                    </a>

                                                    <a href="<?php echo url('/admin/Index/editadmin',array('id'=>$InfoRes['id'])); ?>" onClick="warning('确实要删除吗', '')" class="btn btn-danger btn-sm shiny" >
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

<script type="text/javascript">

    //是否启用
    $('.flag').on('click',function() {
        //获取当前状态
        var statu=$(this).val();
        var a = $(this).parent().parent().siblings().eq(0).addClass("add");
        var atid = $(this).parent().parent().siblings(".add").text();
        var fun="clickedit";
        var url="<?php echo url('/admin/Index/editadmin'); ?>";
           $.ajax({
               method:'POST',
               url:url,
               data:{
                   fun:fun,
                   statu:statu,
                   atid:atid
               },
               dataType:'JSON',
               success: function (result) {
                if(result.code==1){ //成功修改，改变value的值
                    var newvalue =result.data.atid
                    $(this).val(newvalue); 
                    console.log(result);
                }else{
                     console.log(result.msg)
                }

               },
               error:function(){

                   console.log('error');
               }

           })

    })

    //修改商品信息
    // $('.editinfo').on('click',function(){
    //      var a = $(this).parent().siblings().eq(0).addClass("add");
    //     var atid = $(this).parent().siblings(".add").text();
    //      var fun="editinfo";
    //     var url="<?php echo url('/admin/Index/edit'); ?>";
    //     $.ajax({
    //            method:'POST',
    //            url:url,
    //            data:{
    //                fun:fun,
    //                statu:statu,
    //                atid:atid
    //            },
    //            dataType:'JSON',
    //            success: function (result) {
    //             if(result.code==1){ //成功修改，改变value的值
    //                 // var newvalue =result.data.atid
    //                 // $(this).val(newvalue); 
    //             }else{
    //                  console.log(result.msg)
    //             }

    //            },
    //            error:function(){

    //                console.log('error');
    //            }

    //        })
        
    // })


    // 导出excel 
       function exportUser() {
        //action="<?php echo url('admin/index/Index'); ?>"
        // $('input[name="user_ids"]').val('');
         $('#search-form2').attr('action',"<?php echo url('admin/index/downExcel'); ?>")
        // var selected_ids = '';
        // $('.trSelected' , '#flexigrid').each(function(i){
        //     selected_ids += $(this).data('id')+',';
        // });
        // if(selected_ids != ''){
        //     $('input[name="user_ids"]').val(selected_ids.substring(0,selected_ids.length-1));
        // }
        $('#search-form2').submit();
    }

    //查询
    function selectInfo(){
         $('#search-form2').attr('action',"<?php echo url('admin/index/selectInfo'); ?>")
         $('input[name="user_ids"]').val('');
         $('#search-form2').submit();
    }


</script>

<!-- 时间筛选js -->
 <script src="/static/admin/style/jquery.min.js"></script>
 <script src="/static/admin/style/jedate.min.js"></script>
 <script src="/static/admin/style/coomselectjs.js"></script>


</body>
</html>