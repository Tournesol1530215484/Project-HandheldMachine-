<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <label class="col-xs-12 col-sm-3 col-md-3 control-label">积分抵扣</label>	
	    <div class="col-sm-9">	
	       <?php if( ce('shop.goods' ,$item) ) { ?>	
	        <div class='input-group'>	
	            <span class="input-group-addon">最多抵扣</span>	
	            <input type="text" name="deduct"  value="<?php  echo $item['deduct'];?>" class="form-control" />	
	            <span class="input-group-addon">元</span>	
	        </div>	
	       <span class="help-block">如果设置0，则不支持积分抵扣</span>	
	          <?php  } else { ?>	
	          <div class='form-control-static'>	
	              <?php  if(empty($item['deduct'])) { ?>不支持积分抵扣<?php  } else { ?>最多抵扣 <?php  echo $item['deduct'];?> 元 <?php  } ?>	
	         </div>	
	          <?php  } ?>	
	    </div>   	
	</div> 	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <label class="col-xs-12 col-sm-3 col-md-3 control-label">余额抵扣</label>	
	    <div class="col-sm-9"> 	
	       <?php if( ce('shop.goods' ,$item) ) { ?>	
	        <div class='input-group'>	
	            <span class="input-group-addon">最多抵扣</span>	
	            <input type="text" name="deduct2"  value="<?php  echo $item['deduct2'];?>" class="form-control" />	
	            <span class="input-group-addon">元</span>	
	        </div>	
	       <span class="help-block">如果设置0，则支持全额抵扣，设置-1 不支持余额抵扣</span>	
	          <?php  } else { ?>	
	          <div class='form-control-static'>	
	              <?php  if(empty($item['deduct2'])) { ?>	
				  支持全额抵扣	
				  <?php  } else if($item['deduct2']==-1) { ?>	
				  不支持余额抵扣	
				  <?php  } else { ?>	
				  最多抵扣 <?php  echo $item['deduct2'];?> 元 <?php  } ?>	
	         </div>	
	          <?php  } ?>	
	    </div>   	</div>
</div> 

<div class="form-group">	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <label class="col-xs-12 col-sm-3 col-md-3 control-label">单品满件包邮</label>	
	    <div class="col-sm-9">	
	       <?php if( ce('shop.goods' ,$item) ) { ?>	
	        <div class='input-group'>	
	            <span class="input-group-addon">满</span>	
	            <input type="text" name="ednum"  value="<?php  echo $item['ednum'];?>" class="form-control" />	
	            <span class="input-group-addon">件</span>	
	        </div>	
	       <span class="help-block">如果设置0或空，则不支持满件包邮</span>	
	          <?php  } else { ?>	
	          <div class='form-control-static'>	
	              <?php  if(empty($item['ednum'])) { ?>不支持满件包邮<?php  } else { ?>支持 <?php  } ?>	
	         </div>	
	          <?php  } ?>	
	    </div>   	
	</div> 	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <label class="col-xs-12 col-sm-3 col-md-3 control-label">单品满额包邮</label>	
	    <div class="col-sm-9">	
	       <?php if( ce('shop.goods' ,$item) ) { ?>	
	        <div class='input-group'>	
	            <span class="input-group-addon">满</span>	
	            <input type="text" name="edmoney"  value="<?php  echo $item['edmoney'];?>" class="form-control" />	
	            <span class="input-group-addon">元</span>	
	        </div>	
	       <span class="help-block">如果设置0或空，则不支持满额包邮</span>	
	          <?php  } else { ?>	
	          <div class='form-control-static'>	
	              <?php  if(empty($item['edmoney'])) { ?>不支持满额包邮<?php  } else { ?>支持 <?php  } ?>	
	         </div>	
	          <?php  } ?>	
	    </div>   	</div>
</div> 

<div class="form-group">	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	    <label class="col-xs-12 col-sm-3 col-md-3 control-label">不参与单品包邮地区</label>	
	    <div class="col-sm-9 col-xs-12">	
	       <?php if( ce('shop.goods' ,$item) ) { ?>	
	        <div id="areas" class="form-control-static"><?php  echo $item['edareas'];?></div>	
	                           <a href="javascript:;" class="btn btn-default" onclick="selectAreas()">添加不参加满包邮的地区</a>	
	                           <input type="hidden" id='selectedareas' name="edareas" value="<?php  echo $item['edareas'];?>" />	
	       <span class="help-block">如果设置0或空，则不支持满件包邮</span>	
	          <?php  } else { ?>	
	      	
	            <div class='form-control-static'><?php  echo $set['enoughareas'];?></div>	
	       	
	          <?php  } ?>	
	    </div>   	
	</div></div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/selectareas', TEMPLATE_INCLUDEPATH)) : (include template('sale/selectareas', TEMPLATE_INCLUDEPATH));?>