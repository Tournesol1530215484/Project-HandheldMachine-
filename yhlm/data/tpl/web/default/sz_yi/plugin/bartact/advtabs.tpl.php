<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('bartact.bartact')) { ?><?php  } ?><li <?php  if($_GPC['method']=='slide') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/slide')?>">幻灯片</a></li>
    <?php if(cv('bartact.bartact')) { ?><?php  } ?><li <?php  if($_GPC['method']=='poster') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/poster')?>">海报</a></li>
</ul>  	
</div>                                   