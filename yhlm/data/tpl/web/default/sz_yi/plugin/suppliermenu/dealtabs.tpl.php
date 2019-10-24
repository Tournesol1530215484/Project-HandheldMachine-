<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <li <?php  if($_GPC['method'] == 'dealmerch_add') { ?>class="active"<?php  } ?>>
    	<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_add')?>">商家基本信息</a>
    </li>	

    <li <?php  if($_GPC['method'] == 'dealmerch_exchange') { ?>class="active"<?php  } ?>>
    	<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_exchange')?>">兑换点管理</a>
    </li>

    <li <?php  if($_GPC['method'] == 'dealmerch_adminset') { ?>class="active"<?php  } ?>>
    	<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_adminset')?>">管理员管理</a>
    </li>

    <li <?php  if($_GPC['method'] == 'consult') { ?>class="active"<?php  } ?>>	 
    	<a href="<?php  echo $this->createPluginWebUrl('suppliermenu/consult')?>">客户咨询管理</a>
    </li>	 	 	

    <li <?php  if($_GPC['method'] == 'dealmerch_work_number') { ?>class="active"<?php  } ?>>                                 
        <a href="<?php  echo $this->createPluginWebUrl('suppliermenu/dealmerch_work_number')?>">开户工号管理</a>
    </li>
         	 	 	 	 	 
</ul>    
</div>