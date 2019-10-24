<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
  <li class="active"><a href="<?php  echo url('home/welcome/' . $do);?>">账号概况 - <?php  echo $title;?></a></li>
</ul>
<div class="clearfix welcome-container">
    <?php  if($do == 'platform') { ?>
    <div class="account"> <?php  if(is_array($accounts)) { foreach($accounts as $account) { ?>
        <div class="panel panel-default row" style="margin-left:2px; margin-right:2px">
            <div class="panel-body">
                <div class="clearfix">
                    <div class="col-sm-7">
                        <p> <strong><?php  echo $account['name'];?></strong> <span class="label label-success" style="display:inline-block; margin-right:10px;"> <?php  if($account['level'] == 1) { ?>订阅号<?php  } ?>
            <?php  if($account['level'] == 2) { ?>普通服务号<?php  } ?>
            <?php  if($account['level'] == 3) { ?>认证订阅号<?php  } ?>
            <?php  if($account['level'] == 4) { ?>认证服务号/认证媒体/政府订阅号<?php  } ?> </span> <?php  if($account['isconnect'] == 1) { ?> <span class="text-success"><i class="fa fa-check-circle"></i> 成功接入<?php  echo $accounttypes[$account['type']]['title'];?></span> <?php  } else { ?> <span class="text-warning"><i class="fa fa-times-circle"></i> 未接入<?php  echo $accounttypes[$account['type']]['title'];?></span> <?php  } ?> </p>
                        <p><strong>接口地址： </strong> <a href="javascript:;" style="color:#66667C;"><?php  echo $_W['siteroot'];?>api.php?id=<?php  echo $account['acid'];?></a></p>
                        <p><strong>　Token： </strong> <a href="javascript:;" title="点击复制Token" style="color:#66667C;"><?php  echo $account['token'];?></a></p>
                        <p><strong>关键指标： </strong> <a href="<?php  echo url('account/summary/', array('acid' => $_W['acid'], 'uniacid' => $_W['uniacid']));?>" style="color:#66667C;" target="_blank">查看详解</a></p>
                        <!--p><strong>基本回复统计情况： </strong> <a href="<?php  echo url('account/summary/', array('acid' => $_W['acid'], 'uniacid' => $_W['uniacid']));?>" style="color:#66667C;" target="_blank">查看详解</a></p>
                        <p><strong>高级功能统计情况： </strong> <a href="<?php  echo url('account/summary/', array('acid' => $_W['acid'], 'uniacid' => $_W['uniacid']));?>" style="color:#66667C;" target="_blank">查看详解</a></p-->
                    </div>
                    <div class="col-sm-5 text-right"> <img <?php  if(file_exists(IA_ROOT . '/attachment/qrcode_'.$account['acid'].'.jpg')) { ?> src="<?php  echo $_W['attachurl_local'];?>qrcode_<?php  echo $account['acid'];?>.jpg?acid=<?php  echo $account['acid'];?>"<?php  } else { ?>src="resource/images/gw-qr.jpg"<?php  } ?> class="img-responsive img-thumbnail" width="150" /> <img <?php  if(file_exists(IA_ROOT . '/attachment/headimg_'.$account['acid'].'.jpg')) { ?> src="<?php  echo $_W['attachurl_local'];?>headimg_<?php  echo $account['acid'];?>.jpg?acid=<?php  echo $account['acid'];?>"<?php  } else { ?>src="resource/images/gw-wx.gif"<?php  } ?> class="img-responsive img-thumbnail" width="150" /> </div>
                </div>
            </div>
        </div>
        <?php  } } ?>
    </div>
    <script>
        $('.account p a').each(function(){
            util.clip(this, $(this).text());
        });
    </script>
    <div class="row">
        <div class="col-xs-12 col-md-6 panelTop">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><b>营销统计</b> <small>本公众号的业绩累计汇总</small></h4>
                </div>
                <div class="row">
                    <div class="col-xs-12  col-sm-6 col-md-8 panelTop-left">
                        <p>昨日营业额：<?php  if($turnover1 ) { ?><?php  echo $turnover1;?><?php  } else { ?>0.00<?php  } ?>元</p>
                        <p>今日营业额：<?php  if($turnover2 ) { ?><?php  echo $turnover2;?><?php  } else { ?>0.00<?php  } ?>元</p>
                        <p>昨日订单量：<?php  echo $order_sum1;?>单</p>
                        <p>今日订单量：<?php  echo $order_sum2;?>单</p>
                        <div class="row">
                            <div> <i>营业额</i>：<i class="fa fa-long-arrow-down"></i><?php  echo $percentage1;?>% </div>
                            <div> <i>订单量</i>：<i class="fa fa-long-arrow-up"></i><?php  echo $percentage2;?>% </div>
                        </div>
                    </div>
                    <div id="pie3" class="pieDiv" style="padding: 0px;">
                        <canvas class="flot-base" width="150" height="150" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 150px; height: 150px;"></canvas>
                        <canvas class="flot-overlay" width="150" height="150" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 150px; height: 150px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 panelTop" style="padding-left:0px">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><b>分销员粉丝增长统计</b> <small>本公众号的分销员粉丝累计汇总</small></h4>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-8 panelTop-left">
                        <p>昨日分销员数：<?php  echo $yestoday_add_agent_num;?></p>
                        <p>今日分销员数：<?php  echo $today_add_agent_num;?></p>
                        <p>昨日粉丝数：<?php  echo $yestoday_add_num;?></p>
                        <p>今日粉丝数：<?php  echo $today_add_num;?></p>
                        <div class="row">
                            <div class="col-xs-12  col-md-12 col-lg-6"> <i>分销员数</i>：<i class="fa fa-long-arrow-up"></i>0%<br>
                                <i>粉丝净增长数</i>：<i class="fa fa-long-arrow-up"></i><?php  echo $today_total_num;?></div>
                            <div class="col-xs-12  col-md-12 col-lg-6"> <i>粉丝数</i>：<i class="fa fa-long-arrow-up"></i>0% </div>
                        </div>
                    </div>
                    <div id="pie4" class="pieDiv" style="padding: 0px;">
                        <canvas class="flot-base" width="150" height="150" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 150px; height: 150px;"></canvas>
                        <canvas class="flot-overlay" width="150" height="150" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 150px; height: 150px;"></canvas>
          <span class="pieLabel" id="pieLabel0" style="position: absolute; top: 56px; left: 110.5px;">
          <div style="font-size:8pt;text-align:center;padding:2px;color:white;">昨日<br>
              50%</div>
          </span><span class="pieLabel" id="pieLabel1" style="position: absolute; top: 56px; left: 10.5px;">
          <div style="font-size:8pt;text-align:center;padding:2px;color:white;">今日<br>
              50%</div>
          </span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><b>店铺订单状态</b> <small>本公众号的订单状态</small></h4>
                </div>
                <div class="panel-order">
                    <div class="row"> <a class="col-lg-2 col-sm-4 col-xs-12" href="index.php?c=site&a=entry&op=display&status=0&do=order&m=sz_yi"> <i class="icon-nav icon-nav-orderIcon1"></i>
                        <div>
                            <div class="colRed f20"><?php  echo $order_num1;?></div>
                            <p class="gray f14">待付款订单</p>
                        </div>
                    </a> <a class="col-lg-2 col-sm-4 col-xs-12" href="index.php?c=site&a=entry&op=display&status=1&do=order&m=sz_yi"> <i class="icon-nav icon-nav-orderIcon2"></i>
                        <div>
                            <div class="colRed f20"><?php  echo $order_num2;?></div>
                            <p class="gray f14">待发货订单</p>
                        </div>
                    </a> <a class="col-lg-2 col-sm-4 col-xs-12" href="index.php?c=site&a=entry&op=display&status=2&do=order&m=sz_yi"> <i class="icon-nav icon-nav-orderIcon3"></i>
                        <div>
                            <div class="colRed f20"><?php  echo $order_num3;?></div>
                            <p class="gray f14">待收货订单</p>
                        </div>
                    </a> <a class="col-lg-2 col-sm-4 col-xs-12" href="index.php?c=site&a=entry&op=display&status=-1&do=order&m=sz_yi"> <i class="icon-nav icon-nav-orderIcon4"></i>
                        <div>
                            <div class="colRed f20"><?php  echo $order_num4;?></div>
                            <p class="gray f14">已关闭订单</p>
                        </div>
                    </a> <a class="col-lg-2 col-sm-4 col-xs-12" href="index.php?c=site&a=entry&op=display&status=3&do=order&m=sz_yi"> <i class="icon-nav icon-nav-orderIcon5"></i>
                        <div>
                            <div class="colRed f20"><?php  echo $order_num5;?></div>
                            <p class="gray f14">本月完成订单</p>
                        </div>
                    </a> <a class="col-lg-2 col-sm-4 col-xs-12" href="index.php?c=site&a=entry&op=display&status=3&do=order&m=sz_yi"> <i class="icon-nav icon-nav-orderIcon6"></i>
                        <div>
                            <div class="colRed f20"><?php  echo $order_num6;?></div>
                            <p class="gray f14">总完成订单</p>
                        </div>
                    </a> </div>
                </div>
            </div>
        </div>
    </div>
    <?php  } ?>
  <?php  if($do != 'ext') { ?> 
  <!--    <div class="page-header">
		<h4><i class="fa fa-plane"></i> 快捷操作</h4>
	</div>
	<div class="shortcut clearfix">
		<a href="<?php  echo url('platform/reply', array('m' => 'userapi'))?>">
			<i class="fa fa-sitemap"></i>
			<span>自定义接口</span>
		</a>
		<?php  if(is_array($shortcuts)) { foreach($shortcuts as $shortcut) { ?>
			<a href="<?php  echo $shortcut['link'];?>" title="<?php  echo $shortcut['title'];?>">
				<img src="<?php  echo $shortcut['image'];?>" alt="<?php  echo $shortcut['title'];?>" class="img-rounded" />
				<span><?php  echo $shortcut['title'];?></span>
			</a>
		<?php  } } ?>
	</div>--> 
  <?php  } ?>
  <?php  if($do == 'platform') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-platform', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-platform', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'mall') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-mall', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-mall', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'fenxiao') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-fenxiao', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-fenxiao', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'supplier') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-supplier', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-supplier', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'bonus') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-bonus', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-bonus', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>   
  <?php  if($do == 'cancel') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-cancel', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-cancel', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>  
  <?php  if($do == 'site') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-site', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-site', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'mc') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-mc', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-mc', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'setting') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-setting', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-setting', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'ext') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-ext', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-ext', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'solution') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-solution', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-solution', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?>
  <?php  if($do == 'authority') { ?>
  <?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('home/welcome-platform', TEMPLATE_INCLUDEPATH)) : (include template('home/welcome-platform', TEMPLATE_INCLUDEPATH));?>
  <?php  } ?> </div>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?> 