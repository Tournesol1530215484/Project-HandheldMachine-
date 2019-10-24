<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>易货动态</title><!-- 这里要动态获取标题，内置标签变量？ -->
<style type="text/css">
    html{
        font-size: 10px;
    }
    body{
        background: #fff;
    }
    #big_body{width:100%;margin:0px; float: left;}
    .customer_top {height: 44px; width: 100%; background: #f00605; /*border-bottom: 1px solid #ccc;*/}
    .back{
        height: 100%;
        line-height: 44px;
        width: 44px;
        margin-left: 10px;
        float: left;
        font-size: 16px;
        color: #fff;
        position: relative;
    }
    /*返回大于号按钮样式*/
    .back:after {
      content: "";
      display: block;
      clear: both;
      width: 10px;
      height: 10px;
      border-left: 2px solid rgb(255,255,255);
      border-bottom: 2px solid rgb(255,255,255);
      position: absolute;
      left: 10%;
      top: 50%;
      margin-top: -2px;
      -moz-transform: rotate(45deg) translateY(-50%);
      -ms-transform: rotate(45deg) translateY(-50%);
      -o-transform: rotate(45deg) translateY(-50%);
      -webkit-transform: rotate(45deg) translateY(-50%);
      transform: rotate(45deg) translateY(-50%);
    }
    .customer_top .title1{height: 100%;line-height: 44px;display: inline-block;width: calc(100% - 60px);text-align: center;color:#fff;font-size: 1.6rem;}
    .back{width: 30px; height: 100%;font-size: 22px;border-radius: 50%;float: left;line-height: 44px; font-family:serif,"PingFang SC", Helvetica, "Helvetica Neue", "微软雅黑", Tahoma, Arial, sans-serif;font-weight: bold;}
    .dynamic-box .dynamic-item-box .dynamic-date{
        padding: 5px 10px;
        font-size: 1.4rem;
        background: #e8e7e7;
        color: #999;
    }
    .dynamic-box .dynamic-content{
        padding-left: 20px;
    }
    .dynamic-box .dynamic-item-record{
        position: relative;
    }
    .dynamic-box .dynamic-content .dynamic-item-bottom{
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        width: calc(100% - 25px);
        font-size: 1.4rem;
        float: right;
        height: 100%;
    }
    .prefix-icon{
        display: block;
        color: #000;
        position: absolute;
        width: 16px;
        height: 16px;
        border: solid 1px #d86060;
        border-radius: 100%;
        left: 0px;
        top: 50%;
        transform: translateY(-50%);
    }
    .prefix-icon-line{
        content: '';
        position: absolute;
        width: 2px;
        height: 100%;
        background-color: #ec4545;
        left: 7px;
        top: 0px;
    }
    .prefix-icon:after{
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #d86060;
        border-radius: 50%;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
    }
    .dynamic-box .dynamic-content .dynamic-item-time{
        color: #999;
        display: block;
        float: left;
        width: 25%;
    }
    .dynamic-box .dynamic-content .dynamic-item-cot{
        display: block;
        width: 75%;
        float: right;
        padding-right: 20px;
        color: #a14db5;
    }
    .txtMarquee-top{  
        position:relative; 
    }
    .no-dynamic{
        text-align: center;
        font-size: 1.4rem;
        padding: 10px;
        color: #999;
    }
</style>

<div id="big_body">
    <div class="customer_top">
        <div class="back" onclick='history.back()'></div>
        <div class="title1">易货动态</div>
    </div>
    
    <div class="dynamic-box">
        <!-- 这里循环动态记录 或者 排行记录暂无数据为空 两种选择-->
        <!-- <div class="no-dynamic">暂无动态哦</div> -->
        <div class="dynamic-item-box">
            <div class="dynamic-date">
                <?php  echo date('Y-m-d')?>
            </div>
            <div class="txtMarquee-top">
                <div class="bd dynamic-content">
                    <ul>
                        <!-- <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*1颖易得【茶宠烟灰缸】商品颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*2颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*3颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*4颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*5颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*6颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*7颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*8颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*9颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*10颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*11颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*12颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*13颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*14颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li>
                        <li class="dynamic-item-record clearfloat">
                            <div class="dynamic-item-bottom">
                                <span class="prefix-icon-line"></span>
                                <span class="prefix-icon"></span>
                                <span class="dynamic-item-time">[15:20:34]</span>
                                <span class="dynamic-item-cot">*15颖易得【茶宠烟灰缸】商品</span>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div> 
        <!-- 这里循环动态记录 或者 排行记录暂无数据为空end 后要删 只是看样式 -->
    </div>
</div>
<!-- 两种模板 排行记录 或者 排行记录暂无数据为空 -->
<!-- 模板需要填充的数据 年月日 时分秒 动态内容 所获动态都是今天一天的动态 -->
<script id="tpl_dynamic" type="text/html">
    <div class="dynamic-item-box">
        <div class="dynamic-date"> <!-- 这里是日期 年月日 -->
            <?php  echo date('Y-m-d')?>
        </div>
        <div class="txtMarquee-top">
            <div class="bd dynamic-content">
                <ul>                 
                    <%each list as g%>
                    <li class="dynamic-item-record clearfloat">
                        <div class="dynamic-item-bottom">
                            <span class="prefix-icon-line"></span>
                            <span class="prefix-icon"></span>
                            <span class="dynamic-item-time">[<%g.time%>]</span><!-- 这里是 时分秒 -->
                            <span class="dynamic-item-cot"> * <%g.total%><%g.realname%>得【<%g.title%>】商品</span><!-- 这里是 都动态内容 包括用户所易商品和商品上架信息 -->
                        </div>
                    </li>
                    <%/each%>
                </ul>
            </div>
        </div>
    </div>
</script>
<script id="tpl_null" type="text/html">
    <div class="no-dynamic">暂无动态哦</div>
</script>
<script type="text/javascript">
    require(['tpl', 'core'], function(tpl, core) {

        //加载父级分类
        core.json('barter/dynamic', {op:'more'}, function(json) {
            if(json.status){                        
                $('.dynamic-box').html(tpl('tpl_dynamic',json.result)); 
                //异步加载要保证异步获取代码成功后，插入数据，再执行superslide，才能正常运行
                require(['jquery.SuperSlide.2.1.1'], function(jQuery){
                    jQuery(".txtMarquee-top").slide({mainCell:".bd ul",autoPlay:true,effect:"topMarquee",vis:14,interTime:50});
                });
            }else{
                $('.dynamic-box').html(tpl('tpl_null'));
            }
        }, true);
        //初始化文字无缝向上滚动，如若异步加载数据后没有效果则把下面代码提至异步请求里面
        //vis:14 可视个数 少于它，文字不会向上滚动
        require(['jquery.SuperSlide.2.1.1'], function(jQuery){
            jQuery(".txtMarquee-top").slide({mainCell:".bd ul",autoPlay:true,effect:"topMarquee",vis:14,interTime:50,trigger:"click",mouseOverStop:false});
    
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>