<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('sysset.view.shop')) { ?><li <?php  if($_GPC['op']=='shop') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'shop'))?>">商城设置</a></li><?php  } ?>
    <?php if(cv('sysset.view.follow')) { ?><li  <?php  if($_GPC['op']=='follow') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'follow'))?>">引导及分享设置</a></li><?php  } ?>
    <?php if(cv('sysset.view.notice')) { ?><li  <?php  if($_GPC['op']=='notice') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'notice'))?>">提醒及模板消息设置</a></li><?php  } ?>
   <!-- <?php if(cv('shop.dispatch.view')) { ?><li <?php  if($_GPC['p'] == 'dispatch') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createWebUrl('shop/dispatch')?>">配送方式</a></li><?php  } ?>-->
    <?php if(cv('sysset.view.trade')) { ?><li  <?php  if($_GPC['op']=='trade') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'trade'))?>">交易设置</a></li><?php  } ?>
    <?php if(cv('sysset.view.pay')) { ?><li  <?php  if($_GPC['op']=='pay') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'pay'))?>">支付方式设置</a></li><?php  } ?>
    <?php if(cv('sysset.view.template')) { ?><li  <?php  if($_GPC['op']=='template') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'template'))?>">模板设置</a></li><?php  } ?>
    <?php if(cv('sysset.view.contact')) { ?><li  <?php  if($_GPC['op']=='contact') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'contact'))?>">联系方式设置</a></li><?php  } ?>
    <?php if(cv('sysset.view.sms')) { ?><li  <?php  if($_GPC['op']=='sms') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('sysset',array('op'=>'sms'))?>">短信设置</a></li><?php  } ?>
</ul> 
</div>
