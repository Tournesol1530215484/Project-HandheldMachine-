<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">	 	 
    <?php if(cv('activity.activity')) { ?><li <?php  if($_GPC['method']=='card' && $_GPC['op'] == 'add') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'add'))?>">个人信息</a></li><?php  } ?>
    <?php if(cv('activity.activity')) { ?>	 			  		  	 	 	
        <li <?php  if($_GPC['method']=='card' && $_GPC['op'] == 'banner') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/card',array('op'=>'banner'))?>">轮播图设置</a></li>	 

        <!-- <li <?php  if($_GPC['method']=='activity' && $_GPC['op'] == 'comment') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/activity',array('op'=>'comment'))?>">评论审核</a></li> -->
    <?php  } ?>
</ul>
</div>