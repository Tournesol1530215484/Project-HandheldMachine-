<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('statistics.view.sale')) { ?><li <?php  if($_GPC['p'] == 'sale' || empty($_GPC['p'])) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/sale')?>">销售统计</a></li><?php  } ?>
    <?php if(cv('statistics.view.sale_analysis')) { ?><li <?php  if($_GPC['p'] == 'sale_analysis') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/sale_analysis')?>">销售指标</a></li><?php  } ?>
    <?php if(cv('statistics.view.order')) { ?><li <?php  if($_GPC['p'] == 'order') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/order')?>">订单统计</a></li><?php  } ?>
    <?php if(cv('statistics.view.goods')) { ?><li <?php  if($_GPC['p'] == 'goods') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/goods')?>">商品销售明细</a></li><?php  } ?>
    <?php if(cv('statistics.view.goods_rank')) { ?><li <?php  if($_GPC['p'] == 'goods_rank') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/goods_rank')?>">商品销售排行</a></li><?php  } ?>
    <?php if(cv('statistics.view.goods_trans')) { ?><li <?php  if($_GPC['p'] == 'goods_trans') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/goods_trans')?>">商品销售转化率</a></li><?php  } ?>
    <?php if(cv('statistics.view.member_cost')) { ?><li <?php  if($_GPC['p'] == 'member_cost') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/member_cost')?>"> 会员消费排行</a></li><?php  } ?>
    <?php if(cv('statistics.view.member_increase')) { ?><li <?php  if($_GPC['p'] == 'member_increase') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('statistics/member_increase')?>">会员增长趋势</a></li><?php  } ?>
    
</ul>  
</div>