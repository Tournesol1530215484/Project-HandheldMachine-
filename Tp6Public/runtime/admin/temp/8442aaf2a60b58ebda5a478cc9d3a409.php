<?php /*a:5:{s:69:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\admin\Adminlist.html";i:1589420380;s:67:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\header.html";i:1586589054;s:65:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\left.html";i:1589420850;s:66:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\right.html";i:1586589152;s:67:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\footer.html";i:1586589186;}*/ ?>
<!-- 顶部开始 -->
<!--头部开始-->
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台登录-X-admin2.2</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/xadmin.css">
    <link rel="staticfile" href="/static/common/iconinfo/incofont.css">
    <!-- <link rel="stylesheet" href="./css/theme5.css"> -->
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>
</head>
<body class="index">
<div class="container">
    <div class="logo">
        <a href="">基于X-admin</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>
    <ul class="layui-nav left fast-add" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">+新增</a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.open('最大化','http://www.baidu.com','','',true)">
                        <i class="iconfont">&#xe6a2;</i>弹出最大化</a></dd>
                <dd>
                    <a onclick="xadmin.open('弹出自动宽高','http://www.baidu.com')">
                        <i class="iconfont">&#xe6a8;</i>弹出自动宽高</a></dd>
                <dd>
                    <a onclick="xadmin.open('弹出指定宽高','http://www.baidu.com',500,300)">
                        <i class="iconfont">&#xe6a8;</i>弹出指定宽高</a></dd>
                <dd>
                    <a onclick="xadmin.add_tab('在tab打开','member-list.html')">
                        <i class="iconfont">&#xe6b8;</i>在tab打开</a></dd>
                <dd>
                    <a onclick="xadmin.add_tab('在tab打开刷新','member-del.html',true)">
                        <i class="iconfont">&#xe6b8;</i>在tab打开刷新</a></dd>
            </dl>
        </li>
    </ul>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">admin</a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.open('个人信息','http://www.baidu.com')">个人信息</a></dd>
                <dd>
                    <a onclick="xadmin.open('切换帐号','http://www.baidu.com')">切换帐号</a></dd>
                <dd>
                    <a href="./login.html">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item to-index">
            <a href="/">前台首页</a></li>
    </ul>
</div>

<!--头部结束-->
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<!--左侧开始-->

<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="会员管理">&#xe6b8;</i>
                    <cite>会员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?php echo url('index/Statistics'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>统计页面</cite></a>
                    </li>
                    <li>

                        <a href="<?php echo url('index/MemberStatic'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>会员列表(静态表格)</cite></a>
                    </li>
<!--                    <li>-->
<!--                        <a href="<?php echo url('index/MemberDynamic'); ?>">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>会员列表(动态表格)</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="<?php echo url('index/MemberDel'); ?>">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>会员删除</cite></a>-->
<!--                    </li>-->
                    <li>
                        <a href="javascript:;">
                            <i class="iconfont">&#xe70b;</i>
                            <cite>会员管理</cite>
                            <i class="iconfont nav_right">&#xe697;</i></a>
                        <ul class="sub-menu">

<!--                            <li>-->
<!--                                <a href="<?php echo url('index/MemberList'); ?>">-->
<!--                                    <i class="iconfont">&#xe6a7;</i>-->
<!--                                    <cite>等级管理</cite></a>-->
<!--                            </li>-->
                        </ul>
                    </li>
                </ul>
            </li>
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="订单管理">&#xe723;</i>-->
<!--                    <cite>订单管理</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('订单列表','order-list.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>订单列表</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('订单列表1','order-list1.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>订单列表1</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="分类管理">&#xe723;</i>-->
<!--                    <cite>分类管理</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('多级分类','cate.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>多级分类</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="城市联动">&#xe723;</i>-->
<!--                    <cite>城市联动</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('三级地区联动','city.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>三级地区联动</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="管理员管理">&#xe726;</i>
                    <cite>管理员管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i></a>
                <ul class="sub-menu">
                    <li>
<!--                        <a onclick="xadmin.add_tab('管理员列表','Adminlist.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>管理员列表</cite></a>-->
                        <a href="<?php echo url('Admin/Adminlist'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>管理员列表</cite></a>
                    </li>

                    <li>
                        <a href="<?php echo url('Admin/Adminrole'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>角色管理</cite></a>
                    </li>
                    <li>
                        <a href="<?php echo url('Admin/Admincate'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限分类</cite></a>
                    </li>
                    <li>
                        <a href="<?php echo url('Admin/Adminrule'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>权限管理</cite></a>
                    </li>
                </ul>
            </li>
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="系统统计">&#xe6ce;</i>-->
<!--                    <cite>系统统计</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('拆线图','echarts1.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>拆线图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('拆线图','echarts2.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>拆线图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('地图','echarts3.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>地图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('饼图','echarts4.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>饼图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('雷达图','echarts5.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>雷达图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('k线图','echarts6.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>k线图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('热力图','echarts7.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>热力图</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('仪表图','echarts8.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>仪表图</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="图标字体">&#xe6b4;</i>-->
<!--                    <cite>图标字体</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('图标对应字体','unicode.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>图标对应字体</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="其它页面">&#xe6b4;</i>-->
<!--                    <cite>其它页面</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a href="login.html" target="_blank">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>登录页面</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('错误页面','error.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>错误页面</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('示例页面','demo.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>示例页面</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('更新日志','log.html')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>更新日志</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="iconfont left-nav-li" lay-tips="第三方组件">&#xe6b4;</i>-->
<!--                    <cite>layui第三方组件</cite>-->
<!--                    <i class="iconfont nav_right">&#xe697;</i></a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('滑块验证','https://fly.layui.com/extend/sliderVerify/')" target="">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>滑块验证</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('富文本编辑器','https://fly.layui.com/extend/layedit/')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>富文本编辑器</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('eleTree 树组件','https://fly.layui.com/extend/eleTree/')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>eleTree 树组件</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('图片截取','https://fly.layui.com/extend/croppers/')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>图片截取</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('formSelects 4.x 多选框','https://fly.layui.com/extend/formSelects/')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>formSelects 4.x 多选框</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('Magnifier 放大镜','https://fly.layui.com/extend/Magnifier/')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>Magnifier 放大镜</cite></a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a onclick="xadmin.add_tab('notice 通知控件','https://fly.layui.com/extend/notice/')">-->
<!--                            <i class="iconfont">&#xe6a7;</i>-->
<!--                            <cite>notice 通知控件</cite></a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
        </ul>
    </div>
</div>
<!--左侧结束-->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>我的桌面</li></ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd></dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
<!-- 右侧主体结束 -->
<!--中间模板开始-->
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="/static/admin/css/font.css">
        <link rel="stylesheet" href="/static/admin/css/xadmin.css">
        <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
          <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5" action="Adminlist">
                                <input name="action" value="searchinfo" hidden>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                                </div>
                            </form>
                        </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                            <button class="layui-btn" onclick="add_info(this)"><i class="layui-icon"></i>添加</button>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>
<!--                                    <input type="checkbox" name=""  lay-skin="primary">-->
                                      <input type="checkbox" lay-filter="checkall" name="" lay-skin="primary">

                                  </th>
                                  <th>ID</th>
                                  <th>登录名</th>
<!--                                  <th>手机</th>-->
                                  <th>邮箱</th>
                                  <th>角色</th>
                                  <th>加入时间</th>
                                  <th>状态</th>
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              <?php if(is_array($Admin) || $Admin instanceof \think\Collection || $Admin instanceof \think\Paginator): $i = 0; $__LIST__ = $Admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$AdminRes): $mod = ($i % 2 );++$i;?>
                                <tr>
                                  <td>
<!--                                    <input type="checkbox" name=""  lay-skin="primary">-->
                                      <input type="checkbox" name="id" value="<?php echo htmlentities($AdminRes['id']); ?>"   lay-skin="primary">
                                  </td>
                                  <td><?php echo htmlentities($AdminRes['id']); ?></td>
                                  <td><?php echo htmlentities($AdminRes['username']); ?></td>
                                  <td><?php echo htmlentities($AdminRes['email']); ?></td>
                                  <td><?php echo htmlentities($AdminRes['title']); ?></td>
                                  <td><?php echo htmlentities($AdminRes['regTime']); ?></td>
                                  <td class="td-status">
                                      <?php if(($AdminRes['status']==1)): ?>
                                      <span class="layui-btn layui-btn-normal layui-btn-mini">
                                    <a onclick="member_stop2(this,'<?php echo htmlentities($AdminRes['id']); ?>','<?php echo htmlentities($AdminRes['status']); ?>')" href="javascript:;"  title="已启用">已启用</a>
                                        </span>
                                      <?php else: ?>
                                      <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-danger">
                                    <a onclick="member_stop2(this,'<?php echo htmlentities($AdminRes['id']); ?>','<?php echo htmlentities($AdminRes['status']); ?>')" href="javascript:;"  title="以停用">以停用</a>
                                        </span>
                                      <?php endif; ?>
                                  <td class="td-manage">
<!--                                    <a onclick="member_stop(this,'10001')" href="javascript:;"  title="启用">-->
<!--                                      <i class="layui-icon">&#xe601;</i>-->
<!--                                    </a>-->
                                    <a title="编辑"  onclick="role_info_edit(this,'<?php echo htmlentities($AdminRes['id']); ?>')"  href="javascript:;">
                                      <i class="layui-icon">&#xe642;</i>
                                    </a>
                                    <a title="删除" onclick="member_del(this,'<?php echo htmlentities($AdminRes['id']); ?>')" href="javascript:;">
                                      <i class="layui-icon">&#xe640;</i>
                                    </a>
                                  </td>
                                </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                              </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                <div>
                                    <?php echo $Admins; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </body>
    <script>

        /*用户-停用*/
        function member_stop2(obj,id,status){

            layer.confirm('确认要停(启)用户吗？',function(index){
                $.ajax({
                    url:"<?php echo url('Admin/Adminlist'); ?>",
                    data:{'id':id,'status':status,'action':'upstatus'},
                    type:"Post",
                    dataType:"json",
                    success:function(data){
                        var status=status==1?0:1;
                        if(data.msg=='ok'){

                            if($(obj).attr('title')=='已启用'){
                                $(obj).attr('title','已停用')
                                // $(obj).find('i').html('&#xe62f;');
                                var html ='<a onclick="member_stop(this,'+id+','+0+')" href="javascript:;"  title="以停用">以停用</a>'
                                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-danger').html(html);
                                layer.msg('已停用!',{icon: 5,time:1000});

                            }else{
                                $(obj).attr('title','已启用');
                                var html=' <a onclick="member_stop(this,'+id+','+1+')" href="javascript:;"  title="已启用">已启用</a>'
                                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-danger').html(html);
                                layer.msg('已启用!',{icon: 6,time:1000});
                            }
                        }else{
                            layer.msg('错误!',{icon: 3,time:1000});
                        }

                    },
                    error:function(data){
                        layer.msg('错误!',{icon: 5,time:1000});
                        //  $.messager.alert('错误',data.msg);
                    }
                });

            });
        }

        /*添加信息*/
        function add_info(obj){
            //console.log(id);
            console.log(888)
            var index=layer.open({
                type:2,
                title:'添加管理员',
                shadeClose: false,
                shade: 0.3,
                maxmin: true,
                moveOut : true,
                zindex: 198611140,
                area: ['40%', '60%'],
                //content:"<?php echo url('Admin/MemberAdd'); ?>"+'?id='+id
                content:"<?php echo url('Admin/Adminlist'); ?>"+'?action='+'adminadd'
            });
            // layer.open(index)
        }


        /*编辑*/
        function role_info_edit(obj,id) {
            var index=layer.open({
                type:2,
                title:'修改角色',
                shadeClose: false,
                shade: 0.3,
                maxmin: true,
                moveOut : true,
                zindex: 198611140,
                area: ['40%', '60%'],
                content:"<?php echo url('Admin/Adminlist'); ?>"+'?action='+'admin_edit'+'&&id='+id,
            });
        }


      layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var form = layui.form;

          // 监听全选
          form.on('checkbox(checkall)', function(data){
              if(data.elem.checked){
                  $('tbody input').prop('checked',true);
              }else{
                  $('tbody input').prop('checked',false);
              }
              form.render('checkbox');
          });

        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

       /*用户-停用*/
      function member_stop(obj,id){
          layer.confirm('确认要停用吗？',function(index){

              if($(obj).attr('title')=='启用'){

                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});

              }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
              }
              
          });
      }

      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.ajax({
                  url: "<?php echo url('Admin/Adminlist'); ?>",
                  data: {'id':id,'action':'admin_del'},
                  type:'POST',
                  dataType:'json',
                  success:function (data) {
                      if(data.msg=='ok'){
                          $(obj).parents("tr").remove();
                          layer.msg('已删除!',{icon:1,time:1000});
                      }else{
                          layer.msg('删除失败!',{icon:2,time:1000});
                      }
                  },
                  error:function (data) {
                      layer.msg(data.msg,{icon:3,time:1000});
                  }
              })

              $(obj).parents("tr").remove();
              layer.msg('已删除!',{icon:1,time:1000});
          });
      }

        //全选反选  进行删除
        function delAll (argument) {
            var ids = [];
            // 获取选中的id
            $('tbody input').each(function(index, el) {
                if($(this).prop('checked')){
                    ids.push($(this).val())
                }
            });
            layer.confirm('确认要删除吗？'+ids.toString(),function(index){
                //捉到所有被选中的，发异步进行删除
                $.ajax({
                    url: "<?php echo url('Admin/Adminlist'); ?>",
                    data: {'ids':ids,'action':'del_all'},
                    type: 'POST',
                    dataType: 'json',
                    success:function (data) {
                        if(data.msg=='ok'){
                            console.log(data);
                            layer.msg('删除成功', {icon: 1});
                            $(".layui-form-checked").not('.header').parents('tr').remove();
                        }else{
                            console.log(data);
                            layer.msg('删除失败', {icon: 2});
                        }
                    },
                    error:function (data) {
                        console.log(data);
                        layer.msg(data.msg,{icon:3,time:100000})
                    }
                });

                // layer.msg('删除成功', {icon: 1});
                // $(".layui-form-checked").not('.header').parents('tr').remove();

            });
        }




      // function delAll (argument) {
      //
      //   var data = tableCheck.getData();
      //
      //   layer.confirm('确认要删除吗？'+data,function(index){
      //       //捉到所有被选中的，发异步进行删除
      //       layer.msg('删除成功', {icon: 1});
      //       $(".layui-form-checked").not('.header').parents('tr').remove();
      //   });
      // }
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
</html>
<!-- 右侧主体开始 -->
</div>
</div>
<div id="tab_show"></div>
</div>
</div>


<div class="page-content-bg"></div>
<style id="theme_style"></style>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- <script>//百度统计可去掉
var _hmt = _hmt || []; (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();</script> -->
</body>

</html>
<!-- 右侧主体结束 -->