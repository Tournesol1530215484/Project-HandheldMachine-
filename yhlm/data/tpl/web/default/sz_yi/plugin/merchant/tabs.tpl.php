<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('merchant.merchant')) { ?><li <?php  if($_GPC['method']=='merchant') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('merchant/merchant')?>">商家管理</a></li><?php  } ?>
    <?php if(cv('merchant.set')) { ?><li <?php  if($_GPC['method']=='set') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('merchant/set')?>">基础设置</a></li><?php  } ?>
    <?php if(cv('merchant.merchant_list')) { ?><li <?php  if($_GPC['method']=='merchant_list') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('merchant/merchant_list')?>">商家订单</a></li><?php  } ?>
    <?php if(cv('merchant.merchant_for')) { ?><li <?php  if($_GPC['method']=='merchant_for') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('merchant/merchant_for')?>">会员申请商家</a></li><?php  } ?>
    <?php if(cv('merchant.merchant_for_resu')) { ?><li <?php  if($_GPC['method']=='merchant_for_resu') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('merchant/merchant_for_resu')?>">申请结果</a></li><?php  } ?>
</ul>
</div>