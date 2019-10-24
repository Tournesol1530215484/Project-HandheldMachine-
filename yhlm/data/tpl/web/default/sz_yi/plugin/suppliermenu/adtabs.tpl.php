<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <li <?php  if($_GPC['op'] == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('suppliermenu/ad',array('op'=>'post'))?>">新增广告</a></li>
    <li <?php  if($_GPC['op'] == 'draft') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('suppliermenu/ad',array('op'=>'draft'))?>">草稿箱</a></li>
    <li <?php  if($_GPC['op'] == 'list') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('suppliermenu/ad',array('op'=>'list'))?>">广告列表</a></li>
    <li <?php  if($_GPC['op'] == 'code') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('suppliermenu/ad',array('op'=>'code'))?>">换货码投放列表</a></li>
    <li <?php  if($_GPC['op'] == 'bonus') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('suppliermenu/ad',array('op'=>'bonus'))?>">红包投放列表</a></li>

     <?php  if($_GPC['method'] == 'ad' && $_GPC['op'] == 'more') { ?>
    <li <?php  if($_GPC['method'] == 'ad' && $_GPC['op'] == 'more') { ?>class="active"<?php  } ?>>                                 
        <a href="javascript:void(0);">拆红包详情</a>         
    </li>           
    <?php  } ?> 

    <?php  if($_GPC['method'] == 'ad' && $_GPC['op'] == 'palyDetail') { ?>
    <li <?php  if($_GPC['method'] == 'ad' && $_GPC['op'] == 'palyDetail') { ?>class="active"<?php  } ?>>                                 
        <a href="javascript:void(0);">播放详情</a>                                 
    </li>                                
    <?php  } ?>        


</ul>
</div>