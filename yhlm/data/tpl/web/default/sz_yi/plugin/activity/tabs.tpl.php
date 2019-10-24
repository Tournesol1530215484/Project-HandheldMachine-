<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('activity.activity')) { ?><li <?php  if($_GPC['method']=='activity' && $_GPC['op'] == 'add') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'add'))?>">发布活动</a></li><?php  } ?>
    <?php if(cv('activity.activity')) { ?>
        <li <?php  if($_GPC['method']=='activity' && $_GPC['op'] == '') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/activity')?>">我发布的活动</a></li>
        <?php  if($_GPC['op'] == 'NetMap') { ?>	 		 
        <li <?php  if($_GPC['method']=='activity' && $_GPC['op'] == 'NetMap') { ?>class="active"<?php  } ?>><a href="javascirpt:void(0);">报名网络图</a></li>
        <?php  } ?>	 	 			 	 		 	 	 	 
    <?php  } ?>		 	 	 		 	 
</ul>	 	
</div>		 	 	 