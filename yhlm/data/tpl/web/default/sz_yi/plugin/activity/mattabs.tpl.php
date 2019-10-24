<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <li <?php  if($_GPC['method']=='match') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/matchs')?>">评选管理</a></li>

    <li <?php  if($_GPC['method']=='match_type') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('activity/matchs',array('op'=>'add','type'=>1))?>">添加比赛</a></li>
   	 	 	 		 	 	 	 	
</ul>	 		 				 		 	 	 	
</div>		 	 	 