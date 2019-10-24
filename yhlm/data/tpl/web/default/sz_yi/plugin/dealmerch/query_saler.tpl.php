<?php defined('IN_IA') or exit('Access Denied');?><div style='max-height:500px;overflow:auto;min-width:1000px;'>
<table class="table table-hover" style="min-width:1000px;">
    <thead>
        <th>兑换点名称</th>
        <th>兑换点地址</th>
        <th>联系电话</th>
        <th>兑换日期</th>
        <th>兑换时间</th>
        <th>操作</th>
    </thead>
    <tbody>
        <?php  if(is_array($ds)) { foreach($ds as $row) { ?>
        <tr>
            <td><?php  echo $row['title'];?></td>
            <td><?php  echo $row['address'];?></td>
            <td><?php  echo $row['mobile'];?></td>
            <td><?php  echo $row['exchangeDate'];?></td>
            <td><?php  echo $row['exchangeTime'];?></td>
            <td style="width:80px;"><a href="javascript:;" onclick='select_saler(<?php  echo json_encode($row);?>)'>选择</a></td>
        </tr>
        <?php  } } ?>
        <?php  if(count($ds)<=0) { ?>
        <tr> 
            <td colspan='4' align='center'>未找到相关兑换点, 点击<a href="<?php  echo $this->createPluginWebUrl('dealmerch/dealmerch_exchange')?>" target='_blank'>【新增兑换点】</a></td>
        </tr>
        <?php  } ?>
    </tbody>
</table>
</div>