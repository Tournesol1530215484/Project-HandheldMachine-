<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-1 control-label">售后服务</label>
	<div class="col-sm-9 col-xs-12">
      <?php if( ce('dealmerch.goods.edit' ,$item) ) { ?>
        <?php  echo tpl_ueditor('salesServices',$item['afterSalesServices'])?>
        <?php  } else { ?>
        <textarea id='salesServices' style='display:none'><?php  echo $item['afterSalesServices'];?></textarea>
        <a href='javascript:preview_html("#salesServices")' class="btn btn-default">查看内容</a>
        <?php  } ?>
	</div>
</div>
