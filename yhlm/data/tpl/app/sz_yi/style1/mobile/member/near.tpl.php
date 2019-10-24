<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en" class="am-touch js cssanimations">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta name="viewport" content="initial-scale=1, user-scalable=0, minimal-ui" charset="UTF-8">
    <title>
        附近
    </title>
    <script src="../addons/sz_yi/template/mobile/default/member/merch/js/jquery.js"></script>
    <!-- <script src="../addons/sz_yi/template/mobile/default/member/merch/js/jquery.cookie.js"></script> -->
    <script src="../addons/sz_yi/template/mobile/default/member/merch/js/dropload.min.js"></script>
    <link href="../addons/sz_yi/template/mobile/default/member/merch/css/amazeui.min.css" rel="stylesheet">
    <link href="../addons/sz_yi/template/mobile/default/member/merch/css/city.css" rel="stylesheet" type="text/css">
    <script src="../addons/sz_yi/template/mobile/default/member/merch/js/amazeui.min.js">
    </script>
    <script src="../addons/sz_yi/template/mobile/default/member/merch/js/layer.js">
    </script>
    <!-- <script src="../addons/sz_yi/template/mobile/default/member/merch/js/cookie.js">
    </script> -->
    <link rel="stylesheet" href="../addons/sz_yi/template/mobile/default/member/merch/css/layer.css" id="layuicss-skinlayercss">

    <link rel="stylesheet" href="../addons/sz_yi/template/mobile/default/member/merch/css/dropload.css">
    <!--<link rel="stylesheet" type="text/css" href="minirefresh.min.css"
            />
            -->
    <link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/default/member/merch/css/minirefresh.min.css" />
    <!--<script src="minirefresh.min.js"></script>-->
    <script src="../addons/sz_yi/template/mobile/default/member/merch/js/minirefresh.min.js">
    </script>

    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=2SxShLokVzpxylYYQXNvr4WEQnO5wD8E"></script>
    <script type="text/javascript" src="http://developer.baidu.com/map/jsdemo/demo/convertor.js"></script>

    <style>
        body,
        ul,
        li,
        img,
        p,
        div {
            margin: 0px;
            padding: 0px;
        }

        ul,
        li {
            list-style: none
        }

        a:link,
        a:visited {
            color: black;
            text-decoration: none;
        }

        a:hover {
            color: black;
        }

        .clear {
            clear: both
        }

        ul {
            display: block;
            height: 90px;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .nav-con {
            border-top: 10px solid white;
            border-bottom: 0px solid #FFFAF5;
        }

        .nav-con li {
            /*margin: 5px 0;*/
            padding: 5px 0;
            width: 20%;
            line-height: 30px;
            background-color: #fff;
            font-size: 12px;
            float: left;
            text-align: center;
        }

        .nav-con li i {
            display: block;
            width: 52px;
            height: 52px;
            margin: auto;
        }

        .nav-con img {
            width: 45px;
            height: 45px;
        }

        .reci li {
            float: left;
            margin: 5px;
        }

        @-webkit-keyframes opacity {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes opacity {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        }
    </style>

    <style>
        * {
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            -webkit-text-size-adjust: none;
        }

        html {
            font-size: 10px;
        }

        body {
            background-color: #f5f5f5;
            font-size: 1.2em;
        }

        .header {
            height: 44px;
            line-height: 44px;
            border-bottom: 1px solid #ccc;
            background-color: #eee;
        }

        .header h1 {
            text-align: center;
            font-size: 2rem;
            font-weight: normal;
        }

        .content-rank {
            max-width: 640px;
            margin: 0 auto;
            background-color: #fff;
        }

        .content-rank .item {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            -webkit-box-align: center;
            box-align: center;
            -webkit-align-items: center;
            align-items: center;
            padding: 3.125%;
            border-bottom: 1px solid #ddd;
            color: #333;
            text-decoration: none;
        }

        .content-rank .item img {
            display: block;
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
        }

        .content-rank .item h3 {
            display: block;
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -ms-flex: 1;
            flex: 1;
            width: 100%;
            max-height: 40px;
            overflow: hidden;
            line-height: 20px;
            margin: 0 10px;
            font-size: 1.2rem;
        }

        .content-rank .item .date {
            display: block;
            height: 20px;
            line-height: 20px;
            color: #999;
        }

        .opacity {
            -webkit-animation: opacity 0.3s linear;
            animation: opacity 0.3s linear;
        }

        @-webkit-keyframes opacity {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes opacity {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
        .flex-center {
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }
        .company-link{
            display: block;
        }
        /*覆盖*/
        .city-container {
            width: 90%;
        }
        .search-btn-box{
            padding: 60px 0 10px 20px;
            border-bottom: 1px solid #e8ecf1;
        }
        .search-cot-box {
            border-radius: 5px;
        }
        .search-cot-box .city-cot {
            width: calc(100% - 35px);
            font-size: 14px;
            height: 30px;
            padding: 3px 5px;
            margin-bottom: 0;
            border: 0;
            margin: 0;
            outline: none;
        }
        .search-cot-box .city-post {
            font-size: 14px;
            width: 50px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            background: #009BF8;
            color: #fff;
        }
        .customize-list .city-letter{
            margin-bottom: 5px;
        }
        .customize-list .special-city{
            float: left;
            width: 31%;
            margin-right: 2%;
            text-align: center;
            border: 0;
            border: 1px solid #ccc;
            margin-top: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 40px;
            line-height: 40px;
            border-radius: 5px;
        }
        .showLetter{
            width: auto;
            height: auto;
            border: 0;
        }
        #list_loading{ 
            width: 94%;
            padding: 10px;
            color: #666;
            text-align: center;
            margin: 0 auto;
        } 
    </style>
</head>

<body style="margin: 0px;">

    <div style="height: 50px;line-height:50px;background-color: #00d3be;position: fixed;top:0;width: 100%;z-index: 1000000000000;">
        <form id="post_form" method="post" action="">
            <input type="hidden" name="typeid" id="typeid" value="" />
            <div style="float:left;margin-left:16%;position:fixed;" onclick="javascript:;">
                <img src="../addons/sz_yi/template/mobile/default/member/merch/img/sousou.png" style="width: 20px;height: 20px;">
            </div>
            <div id="sou">
                <span class="nav-city" style="line-height: 1;display: inline-block;vertical-align: middle;text-align: center;font-size: 1.2rem;color: #111;position: relative;left: 1rem;">武汉市</span>
                <input id="bname" name="bname" type="text" placeholder="搜商家......" style="width:60%;padding:0 0 0 8%;color:#999;margin-left:4%;border:none;height: 32px;line-height: 32px;border-radius:32px;">
            </div>
        </form>
        <div id="reci" style="line-height:25px;position: absolute;top: 3%;right: 0;">
            <div id="qusou" onclick="Search()" class="sure" style="float: right;background-color:#00d3be;padding:0 5px 0 5px;border-radius:3px;color:white;margin-right: 10px;margin-top:10px;font-size:13px;">
                确认搜索
            </div>
            <!-- <div style="margin-left:10px;padding-top:10px;">热门搜索</div> -->
            <div class="reci">
                <!-- <ul style="color:#666;margin-left:5px;">						<li>							<span onclick="hot(&#39;酒店&#39;)" style="background-color:#eee;font-size:13px;padding:5px 10px 5px 10px;">酒店</span>						</li>						<li>							<span onclick="hot(&#39;火锅&#39;)" style="background-color:#eee;font-size:13px;padding:5px 10px 5px 10px;">火锅</span>						</li>						<li>							<span onclick="hot(&#39;美食&#39;)" style="background-color:#eee;font-size:13px;padding:5px 10px 5px 10px;">美食</span>						</li>					</ul> -->
            </div>
        </div>
    </div>

    <div id='diqunone'>

        <div data-am-widget="slider" style="margin-top:45px;" class="am-slider am-slider-a1 am-no-layout" data-am-slider="{&quot;directionNav&quot;:false}">

        </div>
        <div style="border-bottom: 8px solid #eee;line-height:20px;color:#2bb8aa;font-size:14px;">
        </div>

        <div class="clear">
        </div>

        <div>
            <div style="border-bottom:3px solid #eee; font-size:14px;color:#bbb;text-align:center;line-height: 35px;">
                <i style="position:absolute;width:30%;height:1px;border-top:1px solid #bbb;left: 5%;margin-top:17px;"></i>
                <img style="width:28px;" src="../addons/sz_yi/template/mobile/default/member/merch/img/heart.png" alt="脑补心形">
                     <b> 附近商家</b>
                <i style="position:absolute;width:30%;height:1px;border-top:1px solid #bbb;right: 5%;margin-top:17px;"></i>
            </div>
            <div id="container">
            </div>

            <div id='content-rank'>
                <div class="min-a">

                </div>
            </div>
        </div>

    </div>

    <div id="diqu" style="display: none;">

        <!--显示点击的是哪一个字母-->
        <div id="showLetter" class="showLetter"><span>A</span></div>
        <!--城市索引查询-->
        <div class="letter">
            <ul>
                <li>
                    <a href="javascript:;" data-type="current_city">当前</a>
                </li>
                <li>
                    <a href="javascript:;" data-type="hot_city">热门</a>
                </li>
                <li>
                    <a href="javascript:;">A</a>
                </li>
                <li>
                    <a href="javascript:;">B</a>
                </li>
                <li>
                    <a href="javascript:;">C</a>
                </li>
                <li>
                    <a href="javascript:;">D</a>
                </li>
                <li>
                    <a href="javascript:;">E</a>
                </li>
                <li>
                    <a href="javascript:;">F</a>
                </li>
                <li>
                    <a href="javascript:;">G</a>
                </li>
                <li>
                    <a href="javascript:;">H</a>
                </li>
                <li>
                    <a href="javascript:;">J</a>
                </li>
                <li>
                    <a href="javascript:;">K</a>
                </li>
                <li>
                    <a href="javascript:;">L</a>
                </li>
                <li>
                    <a href="javascript:;">M</a>
                </li>
                <li>
                    <a href="javascript:;">N</a>
                </li>
                <li>
                    <a href="javascript:;">P</a>
                </li>
                <li>
                    <a href="javascript:;">Q</a>
                </li>
                <li>
                    <a href="javascript:;">R</a>
                </li>
                <li>
                    <a href="javascript:;">S</a>
                </li>
                <li>
                    <a href="javascript:;">T</a>
                </li>
                <li>
                    <a href="javascript:;">W</a>
                </li>
                <li>
                    <a href="javascript:;">X</a>
                </li>
                <li>
                    <a href="javascript:;">Y</a>
                </li>
                <li>
                    <a href="javascript:;">Z</a>
                </li>
            </ul>
        </div>
        <!--城市列表-->
        <div class="container city-container">
            <div class="city-list search-btn-box">
                <div class="search-cot-box flex-center">
                    <input class="city-cot" type="text" name="keywords" placeholder="输入城市名">
                    <div class="city-post">查找</div>
                </div>
            </div>
            <div class="city">
                <div class="city-list customize-list clearfloat"><span class="city-letter" id="current_city">定位区域</span>
                    <p class="special-city show-current-city" data-id="1">武汉</p>
                </div>
                <div class="city-list customize-list customize-list2 clearfloat"><span class="city-letter" id="hot_city">热门区域</span>
                    <!-- 在这里循环后台设置的热门区域 -->
                    <?php  if(!empty($city)) { ?>
                        <?php  if(is_array($city)) { foreach($city as $v) { ?>      
                        <p class="special-city" data-id="<?php  echo $v['id'];?>"><?php  echo $v['title'];?></p>
                        <?php  } } ?>             
                    <?php  } else { ?>                
                    <p class="special-city" data-id="3">广州市</p>
                    <p class="special-city" data-id="4">武汉市</p>
                    <p class="special-city" data-id="5">济宁市</p>
                    <p class="special-city" data-id="6">北京市</p>
                    <?php  } ?>       
                </div>
                <div class="city-list"><span class="city-letter" id="A1">A</span>
                    <p data-id="210300">鞍山市</p>
                    <p data-id="152900">阿拉善盟</p>
                    <p data-id="340800">安庆市</p>
                    <p data-id="410500">安阳市</p>
                    <p data-id="542500">阿里地区</p>
                    <p data-id="610900">安康市</p>
                    <p data-id="520400">安顺市</p>
                    <p data-id="513200">阿坝藏族羌族自治州</p>
                    <p data-id="659002">阿拉尔市</p>
                    <p data-id="652900">阿克苏地区</p>
                    <p data-id="820100">澳门特别行政区</p>
                    <p data-id="654300">阿勒泰地区</p>
                </div>
                <div class="city-list"><span class="city-letter" id="B1">B</span>
                    <p data-id="220800">白城市</p>
                    <p data-id="150200">包头市</p>
                    <p data-id="150800">巴彦淖尔市</p>
                    <p data-id="130600">保定市</p>
                    <p data-id="210500">本溪市</p>
                    <p data-id="220600">白山市</p>
                    <p data-id="341600">亳州市</p>
                    <p data-id="340300">蚌埠市</p>
                    <p data-id="371600">滨州市</p>
                    <p data-id="620400">白银市</p>
                    <p data-id="610300">宝鸡市</p>
                    <p data-id="530500">保山市</p>
                    <p data-id="469030">白沙黎族自治县</p>
                    <p data-id="451000">百色市</p>
                    <p data-id="522401">毕节市</p>
                    <p data-id="450500">北海市</p>
                    <p data-id="511900">巴中市</p>
                    <p data-id="469035">保亭黎族苗族自治县</p>
                    <p data-id="652800">巴音郭楞蒙古自治州</p>
                    <p data-id="652700">博尔塔拉蒙古自治州</p>
                    <p data-id="110100">北京市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="C1">C</span>
                    <p data-id="140400">长治市</p>
                    <p data-id="130900">沧州市</p>
                    <p data-id="320400">常州市</p>
                    <p data-id="330282">慈溪市</p>
                    <p data-id="320581">常熟市</p>
                    <p data-id="130800">承德市</p>
                    <p data-id="150400">赤峰市</p>
                    <p data-id="220100">长春市</p>
                    <p data-id="431000">郴州市</p>
                    <p data-id="430100">长沙市</p>
                    <p data-id="341100">滁州市</p>
                    <p data-id="430700">常德市</p>
                    <p data-id="341400">巢湖市</p>
                    <p data-id="341700">池州市</p>
                    <p data-id="469027">澄迈县</p>
                    <p data-id="451400">崇左市</p>
                    <p data-id="469031">昌江黎族自治县</p>
                    <p data-id="532300">楚雄彝族自治州</p>
                    <p data-id="445100">潮州市</p>
                    <p data-id="500100">重庆市</p>
                    <p data-id="510100">成都市</p>
                    <p data-id="542100">昌都地区</p>
                    <p data-id="652300">昌吉回族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="D1">D</span>
                    <p data-id="232700">大兴安岭地区</p>
                    <p data-id="140200">大同市</p>
                    <p data-id="230600">大庆市</p>
                    <p data-id="321181">丹阳市</p>
                    <p data-id="210200">大连市</p>
                    <p data-id="210600">丹东市</p>
                    <p data-id="370500">东营市</p>
                    <p data-id="371400">德州市</p>
                    <p data-id="511700">达州市</p>
                    <p data-id="532900">大理白族自治州</p>
                    <p data-id="469003">儋州市</p>
                    <p data-id="469025">定安县</p>
                    <p data-id="533400">迪庆藏族自治州</p>
                    <p data-id="510600">德阳市</p>
                    <p data-id="469007">东方市</p>
                    <p data-id="533100">德宏傣族景颇族自治州</p>
                    <p data-id="441900">东莞市</p>
                    <p data-id="621100">定西市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="E1">E</span>
                    <p data-id="150600">鄂尔多斯市</p>
                    <p data-id="420700">鄂州市</p>
                    <p data-id="422800">恩施土家族苗族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="F1">F</span>
                    <p data-id="210900">阜新市</p>
                    <p data-id="210400">抚顺市</p>
                    <p data-id="350181">福清市</p>
                    <p data-id="341200">阜阳市</p>
                    <p data-id="370983">肥城市</p>
                    <p data-id="361000">抚州市</p>
                    <p data-id="350100">福州市</p>
                    <p data-id="440600">佛山市</p>
                    <p data-id="450600">防城港市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="G1">G</span>
                    <p data-id="440100">广州市</p>
                    <p data-id="360700">赣州市</p>
                    <p data-id="510800">广元市</p>
                    <p data-id="511600">广安市</p>
                    <p data-id="450300">桂林市</p>
                    <p data-id="450800">贵港市</p>
                    <p data-id="520100">贵阳市</p>
                    <p data-id="513300">甘孜藏族自治州</p>
                    <p data-id="623000">甘南藏族自治州</p>
                    <p data-id="640400">固原市</p>
                    <p data-id="632600">果洛藏族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="H1">H</span>
                    <p data-id="231100">黑河市</p>
                    <p data-id="211400">葫芦岛市</p>
                    <p data-id="330481">海宁市</p>
                    <p data-id="320800">淮安市</p>
                    <p data-id="131100">衡水市</p>
                    <p data-id="150100">呼和浩特市</p>
                    <p data-id="330500">湖州市</p>
                    <p data-id="230400">鹤岗市</p>
                    <p data-id="150700">呼伦贝尔市</p>
                    <p data-id="230100">哈尔滨市</p>
                    <p data-id="130400">邯郸市</p>
                    <p data-id="330100">杭州市</p>
                    <p data-id="410600">鹤壁市</p>
                    <p data-id="371700">菏泽市</p>
                    <p data-id="420200">黄石市</p>
                    <p data-id="431200">怀化市</p>
                    <p data-id="340600">淮北市</p>
                    <p data-id="421100">黄冈市</p>
                    <p data-id="430400">衡阳市</p>
                    <p data-id="340100">合肥市</p>
                    <p data-id="340400">淮南市</p>
                    <p data-id="341000">黄山市</p>
                    <p data-id="451200">河池市</p>
                    <p data-id="460100">海口市</p>
                    <p data-id="441600">河源市</p>
                    <p data-id="532500">红河哈尼族彝族自治州</p>
                    <p data-id="441300">惠州市</p>
                    <p data-id="610700">汉中市</p>
                    <p data-id="451100">贺州市</p>
                    <p data-id="632800">海西蒙古族藏族自治州</p>
                    <p data-id="632100">海东市</p>
                    <p data-id="632300">黄南藏族自治州</p>
                    <p data-id="652200">哈密地区</p>
                    <p data-id="632200">海北藏族自治州</p>
                    <p data-id="653200">和田地区</p>
                    <p data-id="632500">海南藏族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="J1">J</span>
                    <p data-id="210700">锦州市</p>
                    <p data-id="330700">金华市</p>
                    <p data-id="140700">晋中市</p>
                    <p data-id="320281">江阴市</p>
                    <p data-id="220200">吉林市</p>
                    <p data-id="230800">佳木斯市</p>
                    <p data-id="230300">鸡西市</p>
                    <p data-id="330400">嘉兴市</p>
                    <p data-id="140500">晋城市</p>
                    <p data-id="350582">晋江市</p>
                    <p data-id="370282">即墨市</p>
                    <p data-id="360800">吉安市</p>
                    <p data-id="370100">济南市</p>
                    <p data-id="420800">荆门市</p>
                    <p data-id="410800">焦作市</p>
                    <p data-id="370800">济宁市</p>
                    <p data-id="410881">济源市</p>
                    <p data-id="421000">荆州市</p>
                    <p data-id="360400">九江市</p>
                    <p data-id="360200">景德镇市</p>
                    <p data-id="445200">揭阳市</p>
                    <p data-id="620300">金昌市</p>
                    <p data-id="440700">江门市</p>
                    <p data-id="620200">嘉峪关市</p>
                    <p data-id="620900">酒泉市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="K1">K</span>
                    <p data-id="320583">昆山市</p>
                    <p data-id="410200">开封市</p>
                    <p data-id="530100">昆明市</p>
                    <p data-id="650200">克拉玛依市</p>
                    <p data-id="653000">克孜勒苏柯尔克孜自治州</p>
                    <p data-id="653100">喀什地区</p>
                </div>
                <div class="city-list"><span class="city-letter" id="L1">L</span>
                    <p data-id="141000">临汾市</p>
                    <p data-id="131000">廊坊市</p>
                    <p data-id="211000">辽阳市</p>
                    <p data-id="220400">辽源市</p>
                    <p data-id="141100">吕梁市</p>
                    <p data-id="320700">连云港市</p>
                    <p data-id="371200">莱芜市</p>
                    <p data-id="411100">漯河市</p>
                    <p data-id="331100">丽水市</p>
                    <p data-id="341500">六安市</p>
                    <p data-id="431300">娄底市</p>
                    <p data-id="350800">龙岩市</p>
                    <p data-id="370681">龙口市</p>
                    <p data-id="371300">临沂市</p>
                    <p data-id="410300">洛阳市</p>
                    <p data-id="371500">聊城市</p>
                    <p data-id="530700">丽江市</p>
                    <p data-id="451300">来宾市</p>
                    <p data-id="510500">泸州市</p>
                    <p data-id="530900">临沧市</p>
                    <p data-id="469033">乐东黎族自治县</p>
                    <p data-id="511100">乐山市</p>
                    <p data-id="620100">兰州市</p>
                    <p data-id="450200">柳州市</p>
                    <p data-id="513400">凉山彝族自治州</p>
                    <p data-id="469034">陵水黎族自治县</p>
                    <p data-id="542600">林芝地区</p>
                    <p data-id="469028">临高县</p>
                    <p data-id="540100">拉萨市</p>
                    <p data-id="520200">六盘水市</p>
                    <p data-id="621200">陇南市</p>
                    <p data-id="622900">临夏回族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="M1">M</span>
                    <p data-id="231000">牡丹江市</p>
                    <p data-id="340500">马鞍山市</p>
                    <p data-id="510700">绵阳市</p>
                    <p data-id="511400">眉山市</p>
                    <p data-id="440900">茂名市</p>
                    <p data-id="441400">梅州市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="N1">N</span>
                    <p data-id="320100">南京市</p>
                    <p data-id="330200">宁波市</p>
                    <p data-id="320600">南通市</p>
                    <p data-id="360100">南昌市</p>
                    <p data-id="411300">南阳市</p>
                    <p data-id="350700">南平市</p>
                    <p data-id="350900">宁德市</p>
                    <p data-id="350583">南安市</p>
                    <p data-id="542400">那曲地区</p>
                    <p data-id="450100">南宁市</p>
                    <p data-id="511300">南充市</p>
                    <p data-id="511000">内江市</p>
                    <p data-id="533300">怒江傈僳族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="P1">P</span>
                    <p data-id="211100">盘锦市</p>
                    <p data-id="360300">萍乡市</p>
                    <p data-id="410400">平顶山市</p>
                    <p data-id="410900">濮阳市</p>
                    <p data-id="350300">莆田市</p>
                    <p data-id="510400">攀枝花市</p>
                    <p data-id="530800">普洱市</p>
                    <p data-id="620800">平凉市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="Q1">Q</span>
                    <p data-id="130300">秦皇岛市</p>
                    <p data-id="230200">齐齐哈尔市</p>
                    <p data-id="230900">七台河市</p>
                    <p data-id="350500">泉州市</p>
                    <p data-id="429005">潜江市</p>
                    <p data-id="370200">青岛市</p>
                    <p data-id="330800">衢州市</p>
                    <p data-id="441800">清远市</p>
                    <p data-id="522700">黔南布依族苗族自治州</p>
                    <p data-id="450700">钦州市</p>
                    <p data-id="530300">曲靖市</p>
                    <p data-id="522300">黔西南布依族苗族自治州</p>
                    <p data-id="621000">庆阳市</p>
                    <p data-id="522600">黔东南苗族侗族自治州</p>
                    <p data-id="469002">琼海市</p>
                    <p data-id="469036">琼中黎族苗族自治县</p>
                </div>
                <div class="city-list"><span class="city-letter" id="R1">R</span>
                    <p data-id="320682">如皋市</p>
                    <p data-id="371082">荣成市</p>
                    <p data-id="371100">日照市</p>
                    <p data-id="542301">日喀则市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="S1">S</span>
                    <p data-id="220300">四平市</p>
                    <p data-id="231200">绥化市</p>
                    <p data-id="220700">松原市</p>
                    <p data-id="320500">苏州市</p>
                    <p data-id="310100">上海市</p>
                    <p data-id="321300">宿迁市</p>
                    <p data-id="330600">绍兴市</p>
                    <p data-id="140600">朔州市</p>
                    <p data-id="230500">双鸭山市</p>
                    <p data-id="210100">沈阳市</p>
                    <p data-id="330682">上虞市</p>
                    <p data-id="130100">石家庄市</p>
                    <p data-id="440500">汕头市</p>
                    <p data-id="350400">三明市</p>
                    <p data-id="429021">神农架林区</p>
                    <p data-id="361100">上饶市</p>
                    <p data-id="411400">商丘市</p>
                    <p data-id="421300">随州市</p>
                    <p data-id="341300">宿州市</p>
                    <p data-id="411200">三门峡市</p>
                    <p data-id="420300">十堰市</p>
                    <p data-id="440300">深圳市</p>
                    <p data-id="430500">邵阳市</p>
                    <p data-id="440200">韶关市</p>
                    <p data-id="441500">汕尾市</p>
                    <p data-id="510900">遂宁市</p>
                    <p data-id="611000">商洛市</p>
                    <p data-id="542200">山南地区</p>
                    <p data-id="460200">三亚市</p>
                    <p data-id="640200">石嘴山市</p>
                    <p data-id="659001">石河子市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="T1">T</span>
                    <p data-id="140100">太原市</p>
                    <p data-id="211200">铁岭市</p>
                    <p data-id="220500">通化市</p>
                    <p data-id="130200">唐山市</p>
                    <p data-id="320585">太仓市</p>
                    <p data-id="120100">天津市</p>
                    <p data-id="321200">泰州市</p>
                    <p data-id="150500">通辽市</p>
                    <p data-id="331000">台州市</p>
                    <p data-id="370900">泰安市</p>
                    <p data-id="429006">天门市</p>
                    <p data-id="340700">铜陵市</p>
                    <p data-id="522201">铜仁市</p>
                    <p data-id="469026">屯昌县</p>
                    <p data-id="610200">铜川市</p>
                    <p data-id="620500">天水市</p>
                    <p data-id="654200">塔城地区</p>
                    <p data-id="659003">图木舒克市</p>
                    <p data-id="652100">吐鲁番地区</p>
                    <p data-id="710100">台湾</p>
                </div>
                <div class="city-list"><span class="city-letter" id="W1">W</span>
                    <p data-id="330300">温州市</p>
                    <p data-id="320200">无锡市</p>
                    <p data-id="150900">乌兰察布市</p>
                    <p data-id="150300">乌海市</p>
                    <p data-id="340200">芜湖市</p>
                    <p data-id="420100">武汉市</p>
                    <p data-id="370700">潍坊市</p>
                    <p data-id="371000">威海市</p>
                    <p data-id="469006">万宁市</p>
                    <p data-id="610500">渭南市</p>
                    <p data-id="469005">文昌市</p>
                    <p data-id="469001">五指山市</p>
                    <p data-id="620600">武威市</p>
                    <p data-id="450400">梧州市</p>
                    <p data-id="532600">文山壮族苗族自治州</p>
                    <p data-id="659004">五家渠市</p>
                    <p data-id="640300">吴忠市</p>
                    <p data-id="650100">乌鲁木齐市</p>
                </div>
                <div class="city-list"><span class="city-letter" id="X1">X</span>
                    <p data-id="140900">忻州市</p>
                    <p data-id="152500">锡林郭勒盟</p>
                    <p data-id="130500">邢台市</p>
                    <p data-id="152200">兴安盟</p>
                    <p data-id="320300">徐州市</p>
                    <p data-id="410700">新乡市</p>
                    <p data-id="420600">襄阳市</p>
                    <p data-id="360500">新余市</p>
                    <p data-id="411500">信阳市</p>
                    <p data-id="429004">仙桃市</p>
                    <p data-id="411000">许昌市</p>
                    <p data-id="430300">湘潭市</p>
                    <p data-id="350200">厦门市</p>
                    <p data-id="341800">宣城市</p>
                    <p data-id="420900">孝感市</p>
                    <p data-id="421200">咸宁市</p>
                    <p data-id="433100">湘西土家族苗族自治州</p>
                    <p data-id="610100">西安市</p>
                    <p data-id="610400">咸阳市</p>
                    <p data-id="532800">西双版纳傣族自治州</p>
                    <p data-id="630100">西宁市</p>
                    <p data-id="810100">香港特别行政区</p>
                </div>
                <div class="city-list"><span class="city-letter" id="Y1">Y</span>
                    <p data-id="320282">宜兴市</p>
                    <p data-id="222400">延边朝鲜族自治州</p>
                    <p data-id="321000">扬州市</p>
                    <p data-id="140800">运城市</p>
                    <p data-id="320900">盐城市</p>
                    <p data-id="140300">阳泉市</p>
                    <p data-id="330281">余姚市</p>
                    <p data-id="230700">伊春市</p>
                    <p data-id="210800">营口市</p>
                    <p data-id="370600">烟台市</p>
                    <p data-id="420500">宜昌市</p>
                    <p data-id="430600">岳阳市</p>
                    <p data-id="360900">宜春市</p>
                    <p data-id="430900">益阳市</p>
                    <p data-id="330782">义乌市</p>
                    <p data-id="360600">鹰潭市</p>
                    <p data-id="431100">永州市</p>
                    <p data-id="450900">玉林市</p>
                    <p data-id="511800">雅安市</p>
                    <p data-id="530400">玉溪市</p>
                    <p data-id="441700">阳江市</p>
                    <p data-id="610800">榆林市</p>
                    <p data-id="511500">宜宾市</p>
                    <p data-id="445300">云浮市</p>
                    <p data-id="610600">延安市</p>
                    <p data-id="654000">伊犁哈萨克自治州</p>
                    <p data-id="640100">银川市</p>
                    <p data-id="632700">玉树藏族自治州</p>
                </div>
                <div class="city-list"><span class="city-letter" id="Z1">Z</span>
                    <p data-id="130700">张家口市</p>
                    <p data-id="330681">诸暨市</p>
                    <p data-id="321100">镇江市</p>
                    <p data-id="320582">张家港市</p>
                    <p data-id="211300">朝阳市</p>
                    <p data-id="430800">张家界市</p>
                    <p data-id="410100">郑州市</p>
                    <p data-id="370400">枣庄市</p>
                    <p data-id="330900">舟山市</p>
                    <p data-id="440183">增城市</p>
                    <p data-id="440400">珠海市</p>
                    <p data-id="411600">周口市</p>
                    <p data-id="370300">淄博市</p>
                    <p data-id="430200">株洲市</p>
                    <p data-id="350600">漳州市</p>
                    <p data-id="411700">驻马店市</p>
                    <p data-id="440800">湛江市</p>
                    <p data-id="520300">遵义市</p>
                    <p data-id="510300">自贡市</p>
                    <p data-id="530600">昭通市</p>
                    <p data-id="441200">肇庆市</p>
                    <p data-id="442000">中山市</p>
                    <p data-id="620700">张掖市</p>
                    <p data-id="512000">资阳市</p>
                    <p data-id="640500">中卫市</p>
                </div>
            </div>
        </div>

    </div>


    <script type="text/javascript" src="../addons/sz_yi/template/mobile/default/member/merch/js/index.js"></script>
    <script type="text/javascript">
        var lng, lat, city, typeid, keyword;
        var page = 1;
        var loaded = false;
        var stop = true;
        //原本的方法获取当前位置 Cookie存取已弃
        /*
        function  decodeUnicode(str)  {
            str  =  str.replace(/\\/g,  "%");
            return  unescape(str);
        }
        if(getCookie('lng') == null || getCookie('lng') == undefined || getCookie('lat') == null || getCookie('lat') == undefined || decodeUnicode(getCookie('city')) == null || decodeUnicode(getCookie('city')) == undefined ) {
            var map = new BMap.Map("allmap");
            navigator.geolocation.getCurrentPosition(function (position) {
                var lng = position.coords.longitude; //lng 
                var lat = position.coords.latitude; //lat
                setCookie('lat', lat, 1);
                setCookie('lng', lng, 1);
                var point = new BMap.Point(lng, lat);
                var gc = new BMap.Geocoder();
                gc.getLocation(point, function (rs) {
                    var addComp = rs.addressComponents;
                    setCookie('city', addComp.city, 1);
                    var city = addComp.city;
                    $(".nav-city").html(mysubstr(city));
                });
            });
        }*/
        //关于状态码
        //BMAP_STATUS_SUCCESS 检索成功。对应数值“0”。
        //BMAP_STATUS_CITY_LIST 城市列表。对应数值“1”。
        //BMAP_STATUS_UNKNOWN_LOCATION  位置结果未知。对应数值“2”。
        //BMAP_STATUS_UNKNOWN_ROUTE 导航结果未知。对应数值“3”。
        //BMAP_STATUS_INVALID_KEY 非法密钥。对应数值“4”。
        //BMAP_STATUS_INVALID_REQUEST 非法请求。对应数值“5”。
        //BMAP_STATUS_PERMISSION_DENIED 没有权限。对应数值“6”。(自 1.1 新增)
        //BMAP_STATUS_SERVICE_UNAVAILABLE 服务不可用。对应数值“7”。(自 1.1 新增)
        //BMAP_STATUS_TIMEOUT 超时。对应数值“8”。(自 1.1 新增)
        
        //定位
        
        //var options={
        //  enableHighAccuracy:true, 
        //  maximumAge:6000,
        //  timeout:6000
        //}
        //if(navigator.geolocation){
              //浏览器支持geolocation
             // navigator.geolocation.getCurrentPosition(onSuccess,onError,options);

         // }else{
        //浏览器不支持geolocation
        //console.log("浏览器支持geolocation不支持h5 geolocation");
        var myComponents2;
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var rp = new BMap.Point(r.point.lng,r.point.lat);  
                lng = r.point.lng; //lng
                lat = r.point.lat; //lat
                var gc = new BMap.Geocoder();  
                gc.getLocation(rp,function(rs){
                    var addComp = rs.addressComponents;
                    //区不准
                    //console.log('您的位置：'+addComp.province + " " + addComp.city + " " + addComp.district + " " + addComp.street);
                    myComponents2=addComp.district;
                    //lng = addComp.longitude; //lng
                    //lat = addComp.latitude; //lat
                    city = addComp.city;
                    $(".nav-city").html(mysubstr(city));
                    $(".show-current-city").html(city);
                    changeLoading();
                    //console.log(lng + lat+ city);
                    getList();
                });
            }else {
                alert('您的位置:定位失败');
            }        
        },{enableHighAccuracy: true});
        //}

        function mysubstr(string){
            if(string.length>3){
                temp=string.slice(0,2);
                return temp+'....';
            }
            return string;
        }
        /*不用cookie,lng or lat or city 任意一个为空则授权定位并吧通过经纬度获取的城市名称填入html中*/
        if(city == '' || city == 'null' || city == 'undefined' || city ==undefined || city ==null){
            city='武汉市';
        }
        //加载到选择城市中1122
        $('.nav-city').html(mysubstr(city));
        function Search(){
            city = "";
            typeid = "";
            var keyword1 = $.trim($('#bname').val());
            if(keyword1.length == 0){
                alert("搜索词不能为空");
                return;
            }
            keyword = keyword1;
            page = 1;
            loaded = false;
            stop = true;
            getList();
        }
        function getList(){
            $.ajax({
                type: "post",
                url: "<?php  echo $this->createMobileUrl('member/merch',array('op'=>'ajaxList'))?>",
                data: {
                    lng: lng,
                    lat: lat,
                    city: city,
                    page: page,
                    typeid: typeid,
                    keyword: keyword
                },
                dataType: 'json',
                success: function(data) {
                    var arrLen = data.result.list.length;
                    var result = '';
                    for(var i = 0; i < arrLen; i++) {
                        if(data.result.list[i].img == ""){
                            data.result.list[i].img = "../addons/sz_yi/template/mobile/default/member/merch/img/default_logo_img.png";
                        }
                        if(data.result.list[i].lat != "" && data.result.list[i].lng != ""){
                            data.result.list[i].distance = data.result.list[i].distance + 'km';
                        }
                        result += "<a href='" + data.result.list[i].url + "'>" +
                            '<div style="color:#808080">' +
                            '<div>' +
                            '<img  src=' + data.result.list[i].img + '  width="80px" height="80px" style="margin:10px;border-radius:4px;">' +
                            '</div>' +
                            '<div style="font-size:12px;color:#666;margin-left:100px;margin-top:-90px;position: relative;">' +
                            '<span style="font-size:1.4rem;color:#00C7C7;width: 60%;display: block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">' +
                            data.result.list[i].merchname +
                            '</span>' +
                            '<br>' +
                            '<span>' +
                            '联系电话：' +
                            '</span>' +
                            '<span style="font-size:13px;">' +
                            data.result.list[i].mobile +
                            '</span>' +
                            '<br>' +
                            '<span>' +
                            '地址：' +
                            '</span>' +
                            '<span style="color:#000;">' +
                            data.result.list[i].address +
                            '</span>' +

                            '<span style="float:right;margin-right: 3%;position: absolute;right: 0;top: 0;color:#666;">' +
                            '<span style="font-size:1.6rem;" class="am-icon-map-marker"></span>'+
                            data.result.list[i].distance +
                            '</span>' +
                            '</span>' +
                            '</div>' +
                            '<div style="border-bottom:3px solid #eee;margin-top:5px;padding: 2%;">' +

                            '</div>' +
                            '</div>' +
                            '</a>';
                    }
                    $('.min-a').html(result);
                    removeLoading();
                    $('.min-a').append('<div id="list_loading" class="getmore">点击加载更多</div>'); 
                    getmore();
                }
            });
        }
        function getmore(){
            $(".getmore").unbind("click").click(function(){ 
                if(loaded){
                    return;
                }
                if(stop==true){ 
                    stop=false; 
                    page++;
                    $.ajax({
                        type: "post",
                        url: "<?php  echo $this->createMobileUrl('member/merch',array('op'=>'ajaxList'))?>",
                        data: {
                            lat: lat,
                            lng: lng,
                            city: city,
                            page: page,
                            typeid: typeid,
                            keyword: keyword
                        },
                        dataType: 'json',
                        success: function(data) {
                            stop = true;        
                            $('#list_loading').remove();
                            var arrLen = data.result.list.length;
                            var result = "";
                            if(arrLen > 0) {
                                for(var i = 0; i < arrLen; i++) {
                                    if(data.result.list[i].img == ""){
                                        data.result.list[i].img = "../addons/sz_yi/template/mobile/default/member/merch/img/default_logo_img.png";
                                    }
                                    if(data.result.list[i].lat != "" && data.result.list[i].lng != ""){
                                        data.result.list[i].distance = data.result.list[i].distance + 'km';
                                    }
                                    result += "<a href='" + data.result.list[i].url + "'>" +
                                        '<div style="color:#808080">' +
                                        '<div>' +
                                        '<img  src=' + data.result.list[i].img + '  width="80px" height="80px" style="margin:10px;border-radius:4px;">' +
                                        '</div>' +
                                        '<div style="font-size:12px;color:#666;margin-left:100px;margin-top:-90px;position: relative;">' +
                                        '<span style="font-size:1.4rem;color:#00C7C7;width: 60%;display: block;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">' +
                                        data.result.list[i].merchname +
                                        '</span>' +
                                        '<br>' +
                                        '<span>' +
                                        '联系电话：' +
                                        '</span>' +
                                        '<span style="font-size:13px;">' +
                                        data.result.list[i].mobile +
                                        '</span>' +
                                        '<br>' +
                                        '<span>' +
                                        '地址：' +
                                        '</span>' +
                                        '<span style="color:#000;">' +
                                        data.result.list[i].address +
                                        '</span>' +

                                        '<span style="float:right;margin-right: 3%;position: absolute;right: 0;top: 0;color:#666;">' +
                                        '<span style="font-size:1.6rem;" class="am-icon-map-marker"></span>'+
                                        data.result.list[i].distance +
                                        '</span>' +
                                        '</span>' +
                                        '</div>' +
                                        '<div style="border-bottom:3px solid #eee;margin-top:5px;padding: 2%;">' +

                                        '</div>' +
                                        '</div>' +
                                        '</a>';
                                }
                            } 
                            $('.min-a').append(result);
                            if (data.result.list.length <data.result.pagesize) {
                                $('.min-a').append('<div id="list_loading">已经加载全部</div>');
                                loaded = true;
                                return;
                            }else{
                                $('.min-a').append('<div id="list_loading" class="getmore">点击加载更多</div>');
                                getmore(page,loaded,stop);
                            }
                        }
                    });
                }
            });
        }
    </script>
    <script type="text/javascript">
        //城市
        $(".nav-city").click(function() {
            $("#diqu").toggle();
            $("#diqunone").toggle();
        });
        // 显示城市
        $('body').on('click', '.city-list p', function() {
            //点击的时候获取点击的数据
            var res = $(this).html();
            page = 1;
            loaded = false;
            stop = true;
            city = res;
            typeid = "";
            keyword = "";
            $(".nav-city").html(mysubstr(res));
            getList();
            $("#diqu").toggle();
            $("#diqunone").toggle();
        });
        $(".city-post").click(function(){
            var city_keyword = $.trim($(".city-cot").val());
            if(city_keyword.length == 0){
                alert('请输入城市');
                return;
            }
            var search_city = $.trim($(".city-cot").val());
            var regExp = new RegExp(search_city);
            var city_list = $(".city").text();
            if(!regExp.test(city_list)){
                alert('没有该城市');
            }else{
                $(".city").find(".city-list p").each(function(){
                    var n_city = $(this).text();
                    if(regExp.test(n_city)){
                        //jquery滚动到页面指定位置定位方法
                        $("html,body").animate({scrollTop: $(this).offset().top - ($(window).height() * 0.07 + 20)},1);
                    }
                });
            }
        });
        $(".type-item").click(function(){
            var typeid = $(this).data('typeid');
            type(typeid);
        });
        // 点击分类
        function type(id) { 
            typeid = id;
            keyword = "";
            city = "";
            //setCookie('thisId',id,1);
            page = 1;
            loaded = false;
            stop = true;
            getList();
        }
        // 下拉刷新
        $(function() {
            function getUrlParam(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
                var r = window.location.search.substr(1).match(reg); //匹配目标参数
                if(r != null) return unescape(r[2]);
                return null; //返回参数值
            }
            //获取参数
            typeid = getUrlParam('typeid');

            // console.log(member);
            // var typeidCon = document.getElementById('#typeid');
            // typeidCon = typeid.val();   
            getList();
        });
    </script>


    <!--	footer-navB  -->
    <div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default " id="" >
        <ul class="am-navbar-nav am-cf am-avg-sm-4" style="background:#00d3be;">
            <li>
                <a href="<?php  echo $this->createMobileUrl('shop')?>" class="" style="color: #fff">
                    <span class="am-icon-home"></span>
                    <span class="am-navbar-label">主页</span>
                </a>
            </li>
            <li <?php  if($op=='display') { ?>style="background:#20bfa1;"<?php  } ?>>
            <a href="<?php  echo $this->createMobileUrl('member/merch')?>" class="" style="color: #fff">
                <span class="am-icon-sign-in"></span>
                <span class="am-navbar-label">本地联盟</span>
            </a>
            </li>
            <li <?php  if($op=='near') { ?>style="background:#20bfa1;"<?php  } ?>>
                <a href="<?php  echo $this->createMobileUrl('member/merch',array('op'=>'near'))?>" class="" style="color: #fff">
                    <span class="am-icon-map-marker"></span>
                    <span class="am-navbar-label">附近</span>
                </a>
            </li>
            <li >
                <a href="http://www.huodongquan.net/index.php/addon/HotActivity/HotActivity/leagueindex/id/1.html?from=groupmessage&isappinstalled=0" class="" style="color: #fff">
                    <span class="am-icon-flag"></span>
                    <span class="am-navbar-label">活动首页</span>
                </a>
            </li>
            <li >
                <a href="<?php  echo $this->createMobileUrl('member')?>" class="" style="color: #fff">
                    <span class="am-icon-user"></span>
                    <span class="am-navbar-label">我的</span>
                </a>
            </li>
        </ul>
    </div>
    <!--	footer-navE	 -->
    <script type="text/javascript" src="../addons/sz_yi/template/mobile/default/member/merch/js/zepto.min.js"></script>
    <!-- <script type="text/javascript" src="../addons/sz_yi/template/mobile/default/member/merch/js/city.js"></script> -->
    <script type="text/javascript">
        $(function () {
            $('.city-container').show();
            // 显示选中的城市字母
            $('body').on('click', '.letter a', function () {
                var s = $(this).html();
                var type = $(this).data("type")
                if( type == "current_city"){
                    $(window).scrollTop($('#' + type).offset().top);
                    
                }else if( type == "hot_city"){
                    $(window).scrollTop($('#' + type).offset().top);
                    
                }else{
                    $(window).scrollTop($('#' + s + '1').offset().top);
                }
                $("#showLetter span").html(s);
                $("#showLetter").show().delay(500).hide(0);
            });
        });
        function changeLoading() {
            var u = navigator.userAgent,
                app = navigator.appVersion;
            if ($('#core_loading1').length <= 0) {
                $('body').append('<div id="core_loading1" style="top:50%;left:50%;margin-left:-35px;margin-top:-30px;position:absolute;width:80px;height:80px; z-index:999999;background: rgba(0,0,0,.4);"><img src="../addons/sz_yi/static/images/loading.gif" width="80" /><div style="color:#fff;font-size:12px;text-align:center;">正在转换到<br />当前城市<div></div>')
            } else {
                $('#core_loading1').show();
            }
        }
        function removeLoading() {
            $('#core_loading1').hide();
        }
    </script>
</body>

</html>