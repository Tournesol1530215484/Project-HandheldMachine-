<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-1 control-label">商品详情</label>
	<div class="col-sm-9 col-xs-12">
      <?php if( ce('shop.goods' ,$item) ) { ?>
        <?php  echo tpl_ueditor('content',$item['content'])?>
        <?php  } else { ?>
        <textarea id='detail' style='display:none'><?php  echo $item['content'];?></textarea>
        <a href='javascript:preview_html("#detail")' class="btn btn-default">查看内容</a>
        <?php  } ?>
	</div>
</div>