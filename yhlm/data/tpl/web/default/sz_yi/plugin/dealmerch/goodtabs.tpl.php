<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('shop.goods.view')) { ?><li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'goods' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/goods')?>">商品管理</a></li><?php  } ?>
    <?php if(cv('shop.goods.view')) { ?><li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'dealgoods_for' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealgoods_for')?>">商品审核</a></li><?php  } ?>
    
    <li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'ad' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/ad')?>">广告管理</a></li> 		

    <li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'ad_for' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/ad_for')?>">广告审核</a></li>

	<li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'report_type' && $operation == 'display' ||$operation == 'post') { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/report_type')?>">举报类型</a></li>

    <li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'report_type' && $operation == 'list' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/report_type',array('op'=>'list'))?>">举报广告信息</a></li>
                
    <?php  if(false) { ?> 	         	        	 	 	 	  	 
    	<li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'ad_set' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/ad_set',array('op'=>'demo'))?>">广告设置</a></li>
    <?php  } ?>
    <li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'ad_type' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/ad_type')?>">广告分类</a></li> 

    <li <?php  if($_GPC['p'] == 'dealmerch' && $_GPC['method'] == 'city' ) { ?> class="active" <?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/city')?>">热门城市</a></li> 			 		 		 	  	 	 	 	       
</ul> 		 	 	 
</div>
