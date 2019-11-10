<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"E:\phpstudy\PHPTutorial\WWW\currency\public/../application/admin\view\index\editadmin.html";i:1573317036;s:75:"E:\phpstudy\PHPTutorial\WWW\currency\application\admin\view\Public\top.html";i:1572663696;s:76:"E:\phpstudy\PHPTutorial\WWW\currency\application\admin\view\Public\left.html";i:1573094587;}*/ ?>
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
  <title></title>

    <meta name="description" content="Dashboard">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--Basic Styles-->
    <link href="/static/admin/style/bootstrap.css" rel="stylesheet">
    <link href="/static/admin/style/font-awesome.css" rel="stylesheet">
    <link href="/static/admin/style/weather-icons.css" rel="stylesheet">
    <!-- <link rel="icon" href="/currency/public/favicon.ico" type="image/x-icon" /> -->

    <!--Beyond styles-->
    <link id="beyond-link" href="/static/admin/style/beyond.css" rel="stylesheet" type="text/css">
    <link href="/static/admin/style/demo.css" rel="stylesheet">
    <link href="/static/admin/style/typicons.css" rel="stylesheet">
    <link href="/static/admin/style/animate.css" rel="stylesheet">

    <!-- 图片上传css -->
    
    <script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>
    <script src="http://www.jq22.com/jquery/jquery-migrate-1.2.1.min.js"></script>
    <link href="/static/admin/style/IMGUP.css" rel="stylesheet" />

    <!-- 图片上传 -->
    <style>
    .upload-thumb {
        display: block !important;
        float: left;
        width: 147px !important;
        height: 147px !important;
        position: relative;
    }

    .upload-thumb img {
        width: 100%;
        height: 100%;
    }

    .img-box, .sku-img-box {
        overflow: hidden;
    }

    .off-box, .sku-off-box {
        position: absolute;
        width: 18px;
        height: 18px;
        right: 0;
        top: 0;
        line-height: 18px;
        background-color: #FFF;
        cursor: pointer;
        text-align: center;
    }

    .black-bg {
        position: absolute;
        right: 0;
        top: 0;
        left: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.3);
    }

    .hide {
        display: none;
    }

    .img-error {
        color: red;
        height: 25px;
        line-height: 25px;
        display: none;
    }

    .hint {
        font-size: 12px;
        line-height: 16px;
        color: #BBB;
        margin-top: 10px;
    }

    .ncsc-goods-default-pic .goodspic-uplaod .handle {
        height: 30px;
        margin: 10px 0;
    }

    .ncsc-upload-btn, .upload-btn {
        vertical-align: top;
        width: 80px;
        height: 30px;
        margin: 10px 5px 0 0;
        display: block;
        position: relative;
        z-index: 1;
    }

    .ncsc-upload-btn {
        display: inline-block;
        margin: 0 5px 0;
        vertical-align: middle;
    }

    .ncsc-upload-btn span, .upload-btn span {
        width: 80px;
        height: 30px;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 2;
        cursor: pointer;
    }

    .ncsc-upload-btn .input-file, .upload-btn .input-file {
        width: 80px;
        height: 30px;
        padding: 0;
        margin: 0;
        border: none 0;
        opacity: 0;
        filter: alpha(opacity=0);
        cursor: pointer;
    }

    .ncsc-upload-btn p, .upload-btn p {
        font-size: 12px;
        line-height: 20px;
        background-color: #F5F5F5;
        text-align: center;
        color: #666;
        width: 78px;
        height: 20px;
        padding: 4px 0;
        border: solid 1px;
        border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC;
        position: absolute;
        left: 0;
        top: 0;
        cursor: pointer;
        z-index: 1;
    }

    select, input[type="file"] {
        height: 30px;
        line-height: 30px;
    }

    .base {
        width: 80%;
        background-color: #fff;
        border-radius: 20px;
        margin: auto;
    }

</style>


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
                    <li>
                        <a href="">文章管理</a>
                    </li>
                    <li class="active">添加文章</li>
                </ul>
            </div>
            <!-- /Page Breadcrumb -->

            <!-- Page Body -->
            <div class="page-body">

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="widget">
                            <div class="widget-header bordered-bottom bordered-blue">
                                <span class="widget-caption">新增文章</span>
                            </div>
                            <div class="widget-body">
                                <div id="horizontal-form">
                                    <form class="form-horizontal" role="form" action="<?php echo url('/admin/Index/editadmin'); ?>" method="post" enctype="multipart/form-data" >
                                        <input type="hidden" name="id" value="">
                                        <input type="hidden" name="fun" value="editinfo">
                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">文章标题</label>
                                            <div class="col-sm-6">
                                                <input class="form-control"   placeholder="" name="article_title" value="" required="" type="text">
                                            </div>
                                            <p class="help-block col-sm-4 red">* 必填</p>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">文章作者</label>
                                            <div class="col-sm-6">
                                                <input class="form-control"   placeholder="" name="article_author" value="" required="" type="text">
                                            </div>
                                            <p class="help-block col-sm-4 red">* 必填</p>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">关键词</label>
                                            <div class="col-sm-6">
                                                <input class="form-control"   placeholder="" name="article_keywords" value="" required="" type="text">
                                            </div>
                                            <p class="help-block col-sm-4 red">* 必填</p>
                                        </div>

                                     

                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">文章logo</label>
                                             <!-- 图片上传开始 -->

                                             <div class="base">
                                                <div id="goods_picture_box" class="controls" style="background-color:#FFF;border: 1px solid #E9E9E9;">
                                                    <div class="ncsc-goods-default-pic">
                                                        <div class="goodspic-uplaod" style="padding: 15px;">
                                                            <div class='img-box' style="min-height:160px;">
                                                                <div class="upload-thumb" id="default_uploadimg">
                                                                    <img src="/static/admin/style/album/default_goods_image_240.gif">
                                                                </div>
                                                            </div>
                                                            <div class="clear"></div>
                                                            <div class="handle">
                                                                <div class="ncsc-upload-btn">
                                                                    <a href="javascript:void(0);">
                                                                        <span>
                                                                            <input style="cursor:pointer;font-size:0;" type="file" id="fileupload" hidefocus="true" class="input-file" name="file_upload" multiple="multiple"/>
                                                                        </span>
                                                                        <p>图片上传</p>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="album_id" value="1"/>

                                              <!-- 图片上传结束 -->

                                        </div>

                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">前台显示</label>
                                            <div class="col-sm-6">
                                                <div class="radio">
                                                    <label>
                                                        <input  name="satus" value="1" type="radio"><span class="text">显示</span>
                                                    </label>
                                                    <label>
                                                        <input class="inverted"  name="satus" value="0" type="radio"><span class="text">不显示</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">审核状态</label>
                                            <div class="col-sm-6">
                                                <div class="radio">
                                                    <label>
                                                        <input   name="article_type" value="0" type="radio"><span class="text">未通过</span>
                                                    </label>
                                                    <label>
                                                        <input  name="article_type" value="1" type="radio"><span class="text">待审核</span>
                                                    </label>
                                                    <label>
                                                        <input   name="article_type" value="2" type="radio"><span class="text">通过</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">资源类型</label>
                                            <div class="col-sm-6">
                                                <div class="radio">
                                                    <label>
                                                        <input  name="resource_type" value="0" type="radio"><span class="text">ed2k资源</span>
                                                    </label>
                                                    <label>
                                                        <input name="resource_type" value="1" type="radio"><span class="text">磁力资源</span>
                                                    </label>
                                                    <label>
                                                        <input  name="resource_type" value="2" type="radio"><span class="text">网盘资源</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">文章描述</label>
                                            <div class="col-sm-6">
                                                <textarea style="resize:none; width:523px;height: 115px" name="article_description" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label   class="col-sm-2 control-label no-padding-right">文章内容</label>
                                            <div class="col-sm-6">
                                                <textarea id="content" name="article_content" ></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-default">保存信息</button>
                                            </div>
                                        </div>
                                    </form>
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



<!--引入百度编辑器-->
<script src="/static/admin/ueditor/ueditor.config.js"></script>
<script src="/static/admin/ueditor/ueditor.all.min.js"></script>
<script src="/static/admin/ueditor/lang/zh-cn/zh-cn.js"></script>

<!--实例化百度编辑器-->
<script type="text/javascript">
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    UE.getEditor('content',{initialFrameWidth:1100,initialFrameHeight:800});
</script>

<link href="/static/admin/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css" rel="stylesheet" type="text/css" />  

<script type="text/javascript" src="/static/admin/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>  

<script type="text/javascript">      

SyntaxHighlighter.all();       

</script>


<!-- 图片上传 -->
<script src="/static/admin/style/IMGUP.js"></script>

<!-- 图片上传新 -->
<script src="/static/admin/style/js/jquery-1.8.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/style/js/drag-arrange.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/admin/style/js/ajax_file_upload.js" type="text/javascript"></script>
<script type="text/javascript" src="/static/admin/style/js/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="/static/admin/style/js/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript">

    var dataAlbum;
    var UPLOADGOODS = 'UPLOAD_GOODS';//存放商品图片
    $(function () {
        //给图片更换位置事件
        $('.draggable-element').arrangeable();

        var album_id = $("#album_id").val();
        dataAlbum = {
            "album_id": album_id,
            "type": "1,2,3,4",
            'file_path': UPLOADGOODS
        };
        // ajax 上传图片
        var upload_num = 0; // 上传图片成功数量
        $('#fileupload').fileupload({
            url: "admin/Base/upload",
            dataType: 'json',
            //formData:dataAlbum,
            add: function (e, data) {
                $("#goods_picture_box .img-error").hide();
                $("#goods_picture_box #default_uploadimg").remove();
                //显示上传图片框
                var html = "";
                $.each(data.files, function (index, file) {
                    html += '<div class="upload-thumb draggable-element"  nstype="' + file.name + '">';
                    html += '<img nstype="goods_image" src="/static/admin/stylealbum/uoload_ing.gif">';
                    html += '<input type="hidden"  class="upload_img_id" nstype="goods_image" value="">';
                    html += '<div class="black-bg hide">';
                    html += '<div class="off-box">&times;</div>';
                    html += '</div>';
                    html += '</div>';
                });
                $(html).appendTo('#goods_picture_box .img-box');
                //模块可拖动事件
                $('#goods_picture_box .draggable-element').arrangeable();
                data.submit();
            },
            done: function (e, data) {
                var param = data.result;
                $this = $('#goods_picture_box div[nstype="' + param.origin_file_name + '"]');
                if (param.state > 0) {
                    $this.removeAttr("nstype");
                    $this.children("img").attr("src", param.file_name);
                    $this.children("input[type='hidden']").val(param.file_id);
                } else {
                    $this.remove();
                    if ($("#goods_picture_box .img-box .upload-thumb").length == 0) {
                        var img_html = '<div class="upload-thumb" id="default_uploadimg">';
                        img_html += '<img src="/static/admin/style/album/default_goods_image_240.gif">';
                        img_html += '</div>';
                        $("#goods_picture_box .img-box").append(img_html);
                    }
                    $("#goods_picture_box .img-error").html("请检查您的上传参数配置或上传的文件是否有误").show();
                }
            }
        })

        //图片幕布出现
        $(".draggable-element").live('mouseenter', function () {
            $(this).children(".black-bg").show();
        });
        //图片幕布消失
        $(".draggable-element").live('mouseleave', function () {
            $(this).children(".black-bg").hide();
        });

        //删除页面图片元素
        $(".off-box").live('click', function () {
            if ($(".img-box .upload-thumb").length == 1) {
                var html = "";
                html += '<div class="upload-thumb" id="default_uploadimg">';
                html += '<img nstype="goods_image" src="/static/admin/style/album/default_goods_image_240.gif">';
                html += '<input type="hidden" name="image_path" id="image_path" nstype="goods_image" value="">';
                html += '</div>';
                $(html).appendTo('.img-box');
            }
            $(this).parent().parent().remove();
        });


    });
    //备用
    function img_upload() {

    }
    //图片上传方法
    //此方法备用，后续在使用中需要进行相应的修改，不能直接使用。
    //有的一个页面有可能有两个上传图片地方，所以添加此方法。届时在
    //<input style="cursor:pointer;font-size:0;" type="file" id="fileupload" hidefocus="true" class="input-file" name="file_upload" multiple="multiple"/>
    //增加一个onclick事件就可以了：onclick="file_upload(this);"
    function file_upload(obj) {
        var spec_id = $(obj).attr("spec_id");
        var spec_value_id = $(obj).attr("spec_value_id");
        $('.sku-draggable-element' + spec_id + '-' + spec_value_id).arrangeable();
        $(obj).fileupload({
            url: "admin/Base/upload",
            dataType: 'json',
            formData: dataAlbum,
            add: function (e, data) {
                var box_obj = $(this).parent().parent().parent().parent().parent().parent().parent().parent();
                var spec_id = box_obj.attr("spec_id");
                var spec_value_id = box_obj.attr("spec_value_id");
                box_obj.find(".img-error").hide();
                box_obj.find("#sku_default_uploadimg").remove();
                //显示上传图片框
                var html = "";
                $.each(data.files, function (index, file) {
                    html += '<div class="upload-thumb sku-draggable-element' + spec_id + '-' + spec_value_id + ' sku-draggable-element"  nstype="' + file.name + '">';
                    html += '<img nstype="goods_image" src="/static/admin/style/album/uoload_ing.gif">';
                    html += '<input type="hidden"  class="sku_upload_img_id" nstype="goods_image" spec_id="" spec_value_id="" value="">';
                    html += '<div class="black-bg hide">';
                    html += '<div class="sku-off-box">&times;</div>';
                    html += '</div>';
                    html += '</div>';
                });

                box_obj.find('.sku-img-box').append(html);
                //模块可拖动事件
                $('.sku-draggable-element' + spec_id + '-' + spec_value_id).arrangeable();
                data.submit();
            },
            done: function (e, data) {
                var box_obj = $(this).parent().parent().parent().parent().parent().parent().parent().parent();
                var spec_id = box_obj.attr("spec_id");
                var spec_value_id = box_obj.attr("spec_value_id");
                var param = data.result;
                $this = box_obj.find('div[nstype="' + param.origin_file_name + '"]');
                if (param.state > 0) {
                    $this.removeAttr("nstype");
                    $this.children("img").attr("src", __IMG(param.file_name));
                    $this.children("input[type='hidden']").val(param.file_id);
                    $this.children("input[type='hidden']").attr("spec_id", spec_id);
                    $this.children("input[type='hidden']").attr("spec_value_id", spec_value_id);
                    //将上传的规格图片记录
                    for (var i = 0; i < $sku_goods_picture.length; i++) {
                        if ($sku_goods_picture[i].spec_id == spec_id && $sku_goods_picture[i].spec_value_id == spec_value_id) {
                            $sku_goods_picture[i]["sku_picture_query"].push({
                                "pic_id": param.file_id,
                                "pic_cover_mid": param.file_name
                            });
                        }
                    }
                } else {
                    $this.remove();
                    if (box_obj.find(".upload-thumb").length == 0) {
                        var img_html = '<div class="upload-thumb" id="default_uploadimg">';
                        img_html += '<img src="/static/admin/style/album/default_goods_image_240.gif">';
                        img_html += '</div>';
                        box_obj.find(".sku-img-box").append(img_html);
                    }
                    box_obj.find(".img-error").html("请检查您的上传参数配置或上传的文件是否有误").show();
                }
            }
        })
    }
</script>
</body>
</html>