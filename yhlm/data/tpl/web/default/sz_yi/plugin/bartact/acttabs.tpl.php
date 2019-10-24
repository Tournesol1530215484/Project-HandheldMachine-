<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('bartact.bartact')) { ?><?php  } ?><li <?php  if($_GPC['method']=='activity') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/activity')?>">活动</a></li>
   
    <?php if(cv('bartact.bartact')) { ?><?php  } ?><li <?php  if($_GPC['method']=='activity_type') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/activity_type')?>">活动分类</a></li> 		 
    <?php if(cv('bartact.bartact')) { ?><?php  } ?><li <?php  if($_GPC['method']=='activity_for') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/activity_for')?>">活动审核</a></li> 	
    <?php  if($_GPC['debug']) { ?>
		<li <?php  if($_GPC['method']=='activity_type') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/activity_type')?>">比赛分类</a></li>	 	 	
    <?php  } ?>	 
</ul>
</div>                                   	 	 	