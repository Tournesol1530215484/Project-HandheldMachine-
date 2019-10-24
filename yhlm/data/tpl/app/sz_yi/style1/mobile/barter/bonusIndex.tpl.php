<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>收红包 看广告</title>
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/style1/static/css/bonus_index.css" />
<div class="big_body">
    <div class="page_topbar">
        <a href="javascript:;" class="back" onclick="history.back()"><i class="fa fa-angle-left"></i></a>
        <div class="title">收红包&nbsp;&nbsp;<a href="<?php  echo $this->createMobileUrl('barter/ad')?>" class="see-ad-link">看广告</a></div>
        <a href="javascript:;" class="home" onClick="showdescription()">说明</a>
    </div>
    <div id="list-type-box">
        <ul class="list-type">
            <li id="type-tag1" class="type-tag <?php  if($_GPC['type']=='' || $_GPC['type']=='1') { ?>action<?php  } ?>" data-type="1"><!-- onclick="tab(1)" -->
                <span class="type-nav-tag block-span">现金收入</span>
                <span class="value-nav-tag block-span for-nowrap">￥
                <?php  if($info['totalCash']) { ?>
                    <?php  echo $info['totalCash'];?>
                    <?php  } else { ?>
                    0.000
                    <?php  } ?></span>
            </li>
            <li id="type-tag2" class="type-tag <?php  if($_GPC['type']=='2') { ?>action<?php  } ?>" data-type="2"><!--  onclick="tab(2)" -->
                <span class="type-nav-tag block-span">换货码收入</span>
                <span class="value-nav-tag block-span for-nowrap">
                    <?php  if($info['code']) { ?>
                    <?php  echo $info['totalCode'];?>
                    <?php  } else { ?>
                    0.000
                    <?php  } ?>
                </span>
            </li>
        </ul>
    </div>
    <div class="tab_con">
        <div class="con <?php  if($_GPC['type']=='' || $_GPC['type']=='1') { ?>active<?php  } ?>" id="con_1" <?php  if($_GPC['type']=='' || $_GPC['type']=='1') { ?>style='display:block'<?php  } ?>>
            <ul class="menu-part-box"> 
                <!--新增广告-->
                <li class="list1 in-type"> <a href="<?php  echo $this->createMobileUrl('barter/bonus',array('op'=>'bonusAd','who'=>'cash'))?>" class="go-link">看红包广告赚的<i class="fa fa-angle-right trun-right" style="font-size:26px; line-height:44px;float: right;"></i><span class="num-value">￥
                    <?php  if($info['cash']) { ?>
                    <?php  echo $info['cash'];?>
                    <?php  } else { ?>
                    0.000
                    <?php  } ?>
                </span></a></li> 
                <!--草稿箱-->
                <li class="list1 in-type"> <a href="<?php  echo $this->createMobileUrl('barter/bonus',array('op'=>'fans','who'=>'cash'))?>" class="go-link">粉丝帮我赚的<i class="fa fa-angle-right trun-right" style="font-size:26px; line-height:44px;float: right;"></i><span class="num-value">￥
                    <?php  if($info['fanscash']) { ?>
                    <?php  echo $info['fanscash'];?>
                    <?php  } else { ?>
                    0.000
                    <?php  } ?>
                </span></a></li>
            </ul> 
        </div>
        <div class="con <?php  if($_GPC['type']=='2') { ?>active<?php  } ?>" id="con_2" <?php  if($_GPC['type']=='2') { ?>style='display:block'<?php  } ?>>
            <ul class="menu-part-box"> 
                <!--新增广告-->
                <li class="list1 in-type"> <a href="<?php  echo $this->createMobileUrl('barter/bonus',array('op'=>'bonusAd','who'=>'code'))?>" class="go-link">我拆红包的<i class="fa fa-angle-right trun-right" style="font-size:26px; line-height:44px;float: right;"></i><span class="num-value">
                    <?php  if($info['code']) { ?>
                    <?php  echo $info['code'];?>
                    <?php  } else { ?>
                    0.000
                    <?php  } ?>
                换货码</span></a></li> 
                <li class="list1 in-type"> <a href="<?php  echo $this->createMobileUrl('barter/bonus',array('op'=>'fans','who'=>'code'))?>" class="go-link">粉丝帮我赚的<i class="fa fa-angle-right trun-right" style="font-size:26px; line-height:44px;float: right;"></i><span class="num-value">
                    <?php  if($info['fanscode']) { ?>
                    <?php  echo $info['fanscode'];?>
                    <?php  } else { ?>
                    0.000
                    <?php  } ?>
                换货码</span></a></li> 
            </ul> 
        </div>
    </div>
    <div class="sort-box">       
        <a href='<?php  echo $this->createPluginMobileUrl("activity/center",array("op"=>"sort"))?>' class="sort-link">
            赚钱排行榜
            <i class="fa fa-angle-right trun-right" style="font-size:26px; line-height:44px;float: right;"></i>
        </a>
    </div>
    <div class="nav-container status-nav-container">
        <ul class="nav-box clearfloat">
            <a class="nav-item-link <?php  if($_GPC['status']=='' || $_GPC['status']=='1') { ?>nav-on<?php  } ?>" href="javascript:void(0);" data-status="1">
                <li class="nav-item nav-name for-nowrap">
                   未拆(
                   <?php  if($info['status0']) { ?>
                   <?php  echo $info['status0'];?>
                   <?php  } else { ?>
                   0
                   <?php  } ?>
                   )
                </li>
            </a>
            <a class="nav-item-link <?php  if($_GPC['status']=='2') { ?>nav-on<?php  } ?>" href="javascript:void(0);" data-status="2">
                <li class="nav-item nav-name for-nowrap">
                   已拆(
                   <?php  if($info['status1']) { ?>
                    <?php  echo $info['status1'];?>
                   <?php  } else { ?>
                   0
                   <?php  } ?>
                   )
                </li>
            </a>
            <a class="nav-item-link <?php  if($_GPC['status']=='3') { ?>nav-on<?php  } ?>" href="javascript:void(0);" data-status="3">
                <li class="nav-item nav-name for-nowrap">
                   已错过(
                   <?php  if($info['status2']) { ?>
                    <?php  echo $info['status2'];?>
                   <?php  } else { ?>
                   0
                   <?php  } ?>
                   )
                </li>
            </a>
        </ul>
    </div>
    <div class="list-box">
        <ul class="list-ul">      
            <!-- 下拉加载数据容器 -->
        </ul> 
    </div>
    <div class="description_layer"></div> 
    <div class="description">
        <div class="description-tag">收红包说明</div>

        <div class="description-cont"><!-- 这里加载收红包说明 -->
            <?php  echo html_entity_decode($sets['bart']['protocol'][4]['content'])?> 
        </div>
        <div class="close icon" onClick="closedescription()"></div>
    </div>
</div>
<!-- 两种模板 排行记录 或者 排行记录暂无数据为空 -->
<script id="tpl_list" type="text/html">
    <%each list as g%>
        <li class="list-item clearfloat">
            <a href="<%g.url%>" class="bonus-ad-link" style="display: block;text-decoration: none;">
                <div class="clearfloat flex-center">
                    <div class="goods-link floatl"><!-- 红包广告图片封面图(背景图形式) 根据红包类型字段来标志是现金红包还是换货码红包,通过类名cash-pic-box/barter-pic-box来控制-->
                        <div class="pic-box <%if g.bonusType == 1%>cash-pic-box<%else%>barter-pic-box<%/if%>" style="background: url('<%g.thumb[0]%>') no-repeat center; background-size: cover;">
                        </div>
                    </div>
                    <div class="content-box floatr">
                        <div class="goods-tag">
                            <div class="goods-link line-clamp2"><!-- 红包广告名称 -->
                            <%g.title%>
                            </div> 
                        </div>
                        <div class="show-tag"><%g.merchname%></div><!-- 不知道是地址还是店铺公司 -->
                        <div class="show-tag show-value-tag">
                            <%if g.bonustype == 1%>
                                ￥ <%g.money%>
                            <%else if g.bonustype == 2%>
                                 <%g.money%> 换货码 
                            <%/if%>
                        </div><!-- 红包价值,根据红包类型选择显示 换货码 还是 ￥ -->
                        <div class="show-tag"><%g.ctime%></div><!-- 时间 -->
                    </div>
                </div>
            </a>
        </li>
    <%/each%>
</script>
<script id="tpl_null" type="text/html">
    <div class="no-list">暂无该类型红包</div>
</script>
<script type="text/javascript">
    var page = 1; 
    require(['tpl', 'core'], function(tpl, core) {
        $('.type-tag').click(function() { 
            var type = $(this).data('type');
            location.href = core.getUrl('barter/bonus', {op:'bonusIndex',status: '',type: type});//,debug: 1
        });
        function bindEvents(){
            $('.look-detail').unbind('click').click(function() {
                var adid = $(this).data('adid');
                location.href = core.getUrl('plugin/suppliermenu/ad', {op:'detail',id: adid});
            });
            $('.delivery-now').unbind('click').click(function() {
                var theadid = $(this).data('adid');
                //立即投递异步请求
                core.json('order/op',{'op':'', id:theadid},function(json){
                    if(json.status==1){
                        //执行成功 需要运行的代码...
                        core.tip.show(json.result);
                        return;
                    }
                    core.tip.show(json.result);
                 },true,true);
            });
        }
        //,debug: 1
        core.json('barter/bonus', {page:page,op:'bonusIndex',ac:'getBonus', status: '<?php  echo $_GPC['status'];?>',type: '<?php  echo $_GPC['type'];?>'}, function(json) {
            $('.nav-item-link').click(function() { 
                var status = $(this).data('status');
                location.href = core.getUrl('barter/bonus', {op:'bonusIndex',status: status,type: '<?php  echo $_GPC['type'];?>'});//,debug: 1
            });
            if (json.result.list.length <= 0) {
                $(".list-box .list-ul").html(tpl('tpl_null'));
                return;
            }
            $(".list-box .list-ul").html(tpl('tpl_list',json.result));
            bindEvents();
            
            var loaded = false;
            var stop=true; 
            $(window).scroll(function(){ 
                if(loaded){
                    return;
                }
                totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()); 
                if($(document).height() <= totalheight){
                    if(stop==true){ 
                        stop=false; 
                        $('.list-box .list-ul').append('<div id="list_loading"><i class="fa fa-spinner fa-spin"></i> 正在加载...</div>');
                        page++;
                        //,debug: 1
                        core.json('barter/bonus', {page:page,op:'bonusIndex',ac:'getBonus',status:'<?php  echo $_GPC['status'];?>',type: '<?php  echo $_GPC['type'];?>'}, function(morejson) {  
                            stop = true;
                            $('#list_loading').remove();
                            $(".list-box .list-ul").append(tpl('tpl_list', morejson.result));
                            bindEvents();
                            if (morejson.status == 0) {
                                $('.list-box .list-ul').append('<div id="list_loading">已经加载全部广告</div>');
                                loaded = true;
                                return;
                            }
                        },true); 
                    } 
                } 
            });

        }, true);
    });
</script>
<script language="javascript">
    function closedescription(){
        $('.description_layer').fadeOut(100);
        $('.description').fadeOut(100);
    }
    function showdescription(){
        $('.description_layer').fadeIn(200);
        $('.description_layer').unbind('click').click(function(){
            closedescription();
        });
        $('.description').fadeIn(200);
    }
    //现金收入 换货码收入 标签切换
    // function tab(n){
    //     $('#con_'+n).fadeIn().addClass('active');;
    //     $('#con_'+n).siblings().hide().removeClass('active');
    //     $('#type-tag'+n).addClass('action');
    //     $('#type-tag'+n).siblings().removeClass('action');
    // }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>