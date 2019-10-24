<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">

    <div class="alert alert-danger">
        警告：当模板中已经添加数据后切换自定义表单模板有可能导致无法使用！
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">自定义表单形式</label>

      
        <div class="col-sm-9 col-xs-12">
            <label class="radio radio-inline" style="float: left;">
                <input type="radio" value="0" name="diyformtype" <?php  if(empty($item['diyformtype'])) { ?>checked="true"<?php  } ?>> 关闭
            </label>
            <label class="radio radio-inline" style="float: left;">
                <input type="radio" value="1" name="diyformtype" <?php  if($item['diyformtype']!= 0) { ?>checked="true"<?php  } ?>> 开启
            </label>
		 
            <select id="user_diyform" name="diyformid" class="form-control" style="width:167px; float: left;margin-left:10px;">
                <option value="<?php  echo $value['id'];?>" <?php  if($item['diyformid']==0) { ?>selected<?php  } ?>>--选择自定义表单--</option>
                <?php  if(is_array($form_list)) { foreach($form_list as $key => $value) { ?>
                <option value="<?php  echo $value['id'];?>" <?php  if($item['diyformid']==$value['id']) { ?>selected<?php  } ?>><?php  echo $value['title'];?></option>
                <?php  } } ?>
            </select>

        </div>
 

 

    </div>

  
</div> 