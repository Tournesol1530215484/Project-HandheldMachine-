<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <li <?php  if($_GPC['method']=='match') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/match')?>">评选管理</a></li>

    <li <?php  if($_GPC['method']=='match_type') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bartact/match_type')?>">评选类别</a></li>
   	 	 	 		 	 	 	 	
</ul>	 		 				 		 	 	 	
</div>		 	 	 