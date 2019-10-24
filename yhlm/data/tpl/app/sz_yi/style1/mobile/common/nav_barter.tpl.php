<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
	.nav-container{ 
        position: fixed; 
        margin: 0px;
        padding: 0px; 
        bottom: 0px;
        width: 100%;
        height: 49px;
        z-index: 1000;
        max-width: 720px;
        background: #f00605;
    }
    .nav-container .nav-box{
        position: relative;
        padding: 0;
        margin: 0;
        list-style-type: none;
        width: 100%;
        height: 100%;
    }
    .nav-container .nav-box .nav-item-link{
        display: block;
        float: left;
        width: 20%;
        height: 100%;
        text-align: center;
        position: relative;
        font-size: 14px;
        color: #fff;
        text-decoration: none;
    }
    .nav-container .nav-box .nav-item{
    	height: 100%;
    }
    .nav-container .nav-box .nav-item .i-box,
    .nav-container .nav-box .nav-item .nav-name{
    	display: block;
    	width: 100%;
    	text-align: center;
    }
    .nav-container .nav-box .nav-item .i-box{
    	font-size: 16px;
		padding-top: 5px;
		line-height: 21px;
    }
    .huanhuo i{
        font-size: 26px;
        position: absolute;
        top: -18px;
        background: #f00605;
        border-radius: 50%;
        padding: 6px;
        left: 25%;
    }
    .huanhuo .i-box{
        height: 27px;
    }
    .huanhuo img{
        width: 46px;
        position: absolute;
        background: red;
        border-radius: 50%;
        padding: 1px;
        left: 21%;
        top: -22px;
    }
</style>
<div class="nav-container">
    <ul class="nav-box">
        <a class="nav-item-link index-page" href="<?php  echo $this->createMobileUrl('shop/index')?>">
            <li class="nav-item">
                <span class="i-box"><i class="fa fa-home"></i></span>
                <span class="nav-name">首页</span>
            </li>
        </a>
        <a class="nav-item-link mynotice-page" href="<?php  echo $this->createMobileUrl('barter')?>" >
            <li class="nav-item">
                <span class="i-box"><i class="fa fa-gavel"></i></span>
                <span class="nav-name">换货平台</span>
            </li>
        </a>
        <a class="nav-item-link mycenter-page mall huanhuo" href="http://jhzh66.com/app/index.php?i=8&c=entry&method=dealgoods&p=suppliermenu&op=post&merch=5&m=sz_yi&do=plugin">
            <li class="nav-item">
                <span class="i-box"><img src="../addons/sz_yi/plugin/bartact/template/mobile/default/img/foot-fatie.png"></span>
                <span class="nav-name">发换货</span>
            </li>
        </a>
        <a class="nav-item-link mall" href="<?php  echo $this->createMobileUrl('barter/merch')?>">
            <li class="nav-item">
                <span class="i-box"><i class="fa fa-comments-o"></i></span>
                <span class="nav-name">同城换货</span>
            </li>
        </a>
        <a class="nav-item-link mall" href="<?php  echo $this->createMobileUrl('member')?>">
            <li class="nav-item">
                <span class="i-box"><i class="fa fa-user"></i></span>
                <span class="nav-name">会员中心</span>
            </li>
        </a>
    </ul>
</div>
