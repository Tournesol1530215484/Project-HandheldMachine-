<?php /*a:5:{s:70:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\index\Statistics.html";i:1586589516;s:67:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\header.html";i:1586589054;s:65:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\left.html";i:1589420850;s:66:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\right.html";i:1586589152;s:67:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\common\footer.html";i:1586589186;}*/ ?>
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

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">

        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一周新增用户</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main1" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一周PV/UV量</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main2" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">用户来源</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main3" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">硬盘使用量</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main4" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.bootcss.com/echarts/4.2.1-rc1/echarts.min.js"></script>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main1'));

    // 指定图表的配置项和数据
    var option = {
        grid: {
            top: '5%',
            right: '1%',
            left: '1%',
            bottom: '10%',
            containLabel: true
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            type: 'category',
            data: ['周一','周二','周三','周四','周五','周六','周日']
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            name:'用户量',
            data: [820, 932, 901, 934, 1290, 1330, 1320],
            type: 'line',
            smooth: true
        }]
    };


    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main2'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        grid: {
            top: '5%',
            right: '2%',
            left: '1%',
            bottom: '10%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : ['周一','周二','周三','周四','周五','周六','周日']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'PV',
                type:'line',
                areaStyle: {normal: {}},
                data:[120, 132, 101, 134, 90, 230, 210],
                smooth: true
            },
            {
                name:'UV',
                type:'line',
                areaStyle: {normal: {}},
                data:[45, 182, 191, 234, 290, 330, 310],
                smooth: true,

            }
        ]
    };


    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);


    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main3'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
        },
        series : [
            {
                name: '访问来源',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {value:335, name:'直接访问'},
                    {value:310, name:'邮件营销'},
                    {value:234, name:'联盟广告'},
                    {value:135, name:'视频广告'},
                    {value:1548, name:'搜索引擎'}
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };



    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main4'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        series: [
            {
                name: '硬盘使用量',
                type: 'gauge',
                detail: {formatter:'{value}%'},
                data: [{value: 88, name: '已使用'}]
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>
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