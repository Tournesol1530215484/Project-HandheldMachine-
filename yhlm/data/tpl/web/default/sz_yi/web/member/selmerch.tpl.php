<?php defined('IN_IA') or exit('Access Denied');?><div style='max-height:500px;overflow:auto;min-width:850px;'>
<table class="table table-hover" style="min-width:850px;">
    <tbody>   
        <?php  if(is_array($ds)) { foreach($ds as $row) { ?>
        <tr>
            <td><img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' /> <?php  echo $row['username'];?></td>       
            <td><?php  echo $row['realname'];?></td>      
            <td><?php  echo $row['mobile'];?></td>        
            <?php  if($_GPC['type'] == 'goods') { ?>
                <td style="width:80px;"><a href="javascript:;" onclick='select_member1(<?php  echo json_encode($row);?>)'>选择</a></td>                               
            <?php  } else { ?>                           
                <td style="width:80px;"><a href="javascript:;" onclick='select_member(<?php  echo json_encode($row);?>)'>选择</a></td>
            <?php  } ?>
        </tr>                        
        <?php  } } ?>
        <?php  if(count($ds)<=0) { ?>
        <tr> 
            <td colspan='4' align='center'>未找到会员</td>
        </tr>
        <?php  } ?>
    </tbody>
</table>
    </div>