<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('bartact.bartact')) { ?><?php  } ?><li <?php  if($_GPC['method']=='article') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/article')?>">文章</a></li>
    <?php  if(true) { ?><li <?php  if($_GPC['method']=='article_type') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/article_type')?>">文章分类</a></li><?php  } ?>		 
</ul> 	 	 
</div>                                   