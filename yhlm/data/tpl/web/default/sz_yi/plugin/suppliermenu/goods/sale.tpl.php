<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">积分抵扣</label>
    <div class="col-sm-4">
  
        <div class='input-group'>
            <span class="input-group-addon">最多抵扣</span>
            <input type="text" name="deduct"  value="<?php  echo $item['deduct'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0，则不支持积分抵扣</span>
        
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">余额抵扣</label>
    <div class="col-sm-4"> 
 
        <div class='input-group'>
            <span class="input-group-addon">最多抵扣</span>
            <input type="text" name="deduct2"  value="<?php  echo $item['deduct2'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0，则支持全额抵扣，设置-1 不支持余额抵扣</span>
       
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">单品满件包邮</label>
    <div class="col-sm-4">
     
        <div class='input-group'>
            <span class="input-group-addon">满</span>
            <input type="text" name="ednum"  value="<?php  echo $item['ednum'];?>" class="form-control" />
            <span class="input-group-addon">件</span>
        </div>
       <span class="help-block">如果设置0或空，则不支持满件包邮</span>
       
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">单品满额包邮</label>
    <div class="col-sm-4">
 
        <div class='input-group'>
            <span class="input-group-addon">满</span>
            <input type="text" name="edmoney"  value="<?php  echo $item['edmoney'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0或空，则不支持满额包邮</span>
 
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">不参与单品包邮地区</label>
    <div class="col-sm-9 col-xs-12">
 
        <div id="areas" class="form-control-static"><?php  echo $item['edareas'];?></div>
                           <a href="javascript:;" class="btn btn-default" onclick="selectAreas()">添加不参加满包邮的地区</a>
                           <input type="hidden" id='selectedareas' name="edareas" value="<?php  echo $item['edareas'];?>" />
       <span class="help-block">如果设置0或空，则不支持满件包邮</span>
        
    </div>   
</div> 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('goods/selectareas', TEMPLATE_INCLUDEPATH)) : (include template('goods/selectareas', TEMPLATE_INCLUDEPATH));?>