<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    <?php if(cv('dealmerch.dealmerch')) { ?><li <?php  if($_GPC['method']=='dealmerch') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch')?>">商家管理</a></li><?php  } ?>

    <?php if(cv('dealmerch.dealinfo_for')) { ?><li <?php  if($_GPC['method']=='dealinfo_for') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealinfo_for')?>">商家审核</a></li><?php  } ?>
    <?php if(cv('dealmerch.dealmerch_work_number')) { ?><li <?php  if($_GPC['method']=='dealmerch_work_number') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_work_number')?>">开户工号管理</a></li><?php  } ?>
    <?php if(cv('dealmerch.dealmerch_for')) { ?><li <?php  if($_GPC['method']=='dealmerch_for') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_for')?>">会员申请商家</a></li><?php  } ?>
    <?php if(cv('dealmerch.dealmerch_for_resu')) { ?><li <?php  if($_GPC['method']=='dealmerch_for_resu') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_for_resu')?>">申请结果</a></li><?php  } ?>
    <?php if(cv('dealmerch.query')) { ?>
    <?php  } ?>  
        <li <?php  if($_GPC['method']=='query') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/query')?>">会员信息查询</a></li>

    <?php if(cv('dealmerch.setmenu')) { ?><li <?php  if($_GPC['method']=='setmenu') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/setmenu')?>">菜单设置</a></li>
    <?php  } ?>

    <?php if(cv('dealmerch.set')) { ?><li <?php  if($_GPC['method']=='log') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/log')?>">易货码充值记录</a></li><?php  } ?>

    <?php if(cv('dealmerch.set')) { ?><li <?php  if($_GPC['method']=='cur_log') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/cur_log')?>">免换货服务费充值记录</a></li><?php  } ?>

    <?php if(cv('dealmerch.set')) { ?><li <?php  if($_GPC['method']=='set') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/set')?>">商城设置</a></li><?php  } ?>  
    <!-- <li <?php  if($_GPC['method']=='audit') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/set')?>">购买年会员审核</a></li> -->           
    <li <?php  if($_GPC['method']=='member' && $_GPC['op'] != 'log') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/member')?>">年会员设置</a></li>

    <li <?php  if($_GPC['method']=='member' && $_GPC['op'] == 'log') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('dealmerch/member',array('op'=>'log'))?>">年会员购买记录</a></li>                                                   
</ul>
</div>