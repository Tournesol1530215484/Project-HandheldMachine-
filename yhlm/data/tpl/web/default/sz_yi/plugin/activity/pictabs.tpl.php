<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('activity.picture')) { ?><li <?php  if($_GPC['method']=='picture' && $_GPC['op'] == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/picture',array('op'=>'post'))?>">发布图片</a></li><?php  } ?>
    <?php if(cv('activity.picture')) { ?>  	 	
        <li <?php  if($_GPC['method']=='picture' && $_GPC['op'] == '') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/picture')?>">我发布的图片</a></li>

        <?php  if(false) { ?>
        
        <li <?php  if($_GPC['method']=='picture' && $_GPC['op'] == 'draft') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/picture',array('op'=>'draft'))?>">草稿箱</a></li>

        <li <?php  if($_GPC['method']=='picture' && $_GPC['op'] == 'comment') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/picture',array('op'=>'comment'))?>">评论审核</a></li>	 	
        <?php  } ?>
        
    <?php  } ?>
</ul>
</div>