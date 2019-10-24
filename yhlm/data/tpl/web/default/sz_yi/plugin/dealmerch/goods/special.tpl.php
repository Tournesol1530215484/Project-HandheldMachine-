<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
	<label class="col-xs-12 col-sm-3 col-md-1 control-label">特色卖点</label>
	<div class="col-sm-9 col-xs-12">
        <?php if( ce('dealmerch.goods.edit' ,$item) ) { ?>
            <?php  echo tpl_ueditor('special',$item['special'])?>
        <?php  } else { ?>
        <textarea id='special' style='display:none'><?php  echo $item['special'];?></textarea>
        <a href='javascript:preview_html("#special")' class="btn btn-default">查看内容</a>
        <?php  } ?>
	</div>
</div>
