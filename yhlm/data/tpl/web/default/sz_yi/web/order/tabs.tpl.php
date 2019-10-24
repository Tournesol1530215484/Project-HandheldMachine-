<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
      <?php if(cv('order.view.status0|order.view.status1|order.view.status2|order.view.status3|order.view.status4|order.view.status_1')) { ?>
    <li <?php  if($operation == 'display' && $status == '' && $_GPC['refund']!='1') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'))?>">全部订单<em>(<?php  echo $totals['all'];?>)</em></a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status0')) { ?>
    <li <?php  if($operation == 'display' && $status == '0') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 0))?>">待付款<em>(<?php  echo $totals['status0'];?>)</em></a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status1')) { ?>
    <li <?php  if($operation == 'display' && $status == '1') { ?> class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 1))?>">待发货<em>(<?php  echo $totals['status1'];?>)</em></a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status2')) { ?>
    <li <?php  if($operation == 'display' && $status == '2') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 2))?>">待收货<em>(<?php  echo $totals['status2'];?>)</em></a>
    </li>
    <?php  } ?>
    
    <?php if(cv('order.view.status3')) { ?>
    <li <?php  if($operation == 'display' && $status == '3') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 3))?>">已完成<em>(<?php  echo $totals['status3'];?>)</em></a>
    </li>
    <?php  } ?>
    
     <?php if(cv('order.view.status_1')) { ?>
    <li <?php  if($operation == 'display' && $status == '-1') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => -1))?>">已关闭<em>(<?php  echo $totals['status_1'];?>)</em></a>
    </li>
    <?php  } ?>
      
    
    <?php if(cv('order.view.status4')) { ?>
     <li <?php  if($operation == 'display' && $status== '4') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 4))?>">退款申请<em>(<?php  echo $totals['status4'];?>)</em></a>
    </li>
    <?php  } ?>
     
    <?php if(cv('order.view.status5')) { ?>
    <li <?php  if($operation == 'display' && $status == '5') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 5))?>">已退款<em>(<?php  echo $totals['status5'];?>)</em></a>
    </li>
    <?php  } ?>
    <?php if(cv('order.view.status9')) { ?>
    <?php  if(!empty($perm_role)) { ?>
    <li <?php  if($operation == 'display' && $status == '9') { ?>class="active"<?php  } ?>>
        <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display', 'status' => 9))?>">提现申请状态<em>(<?php  if($totals['status9']) { ?><?php  echo $totals['status9'];?><?php  } else { ?>0<?php  } ?>)</em></a>
    </li>
    <?php  } ?>
    <?php  } ?>
    <?php  if($operation == 'detail') { ?>
    <li class="active">
        <a href="#">订单详情</a>
    </li>
    <?php  } ?>
</ul>
</div>
