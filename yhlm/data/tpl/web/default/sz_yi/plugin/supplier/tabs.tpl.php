<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('supplier.supplier')) { ?><li <?php  if($_GPC['method']=='supplier') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier')?>">供应商管理</a></li><?php  } ?>
    <?php if(cv('supplier.supplier')) { ?><li <?php  if($_GPC['method']=='store') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/store')?>">商家管理</a></li><?php  } ?>
    <?php if(cv('supplier.supplier_list')) { ?><li <?php  if($_GPC['method']=='supplier_list') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_list')?>">供应商订单</a></li><?php  } ?>
    <?php if(cv('supplier.supplier_apply')) { ?><li <?php  if($_GPC['method']=='supplier_apply') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_apply')?>">供应商提现申请</a></li><?php  } ?>
    <?php if(cv('supplier.supplier_finish')) { ?><li <?php  if($_GPC['method']=='supplier_finish') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_finish')?>">供应商提现完成</a></li><?php  } ?>
    <?php if(cv('supplier.supplier_for')) { ?><li <?php  if($_GPC['method']=='supplier_for') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_for')?>">会员申请供应商</a></li><?php  } ?>
    <?php if(cv('supplier.supplier_for_resu')) { ?><li <?php  if($_GPC['method']=='supplier_for_resu') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_for_resu')?>">申请结果</a></li><?php  } ?>
    <?php if(cv('supplier.notice')) { ?><li <?php  if($_GPC['method']=='notice') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/notice')?>">通知设置</a></li><?php  } ?>
    <?php if(cv('supplier.set')) { ?><li <?php  if($_GPC['method']=='set') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('supplier/set')?>">基础设置</a></li><?php  } ?>
</ul>
</div>