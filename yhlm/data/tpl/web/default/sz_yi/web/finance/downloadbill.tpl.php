<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/finance/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/finance/tabs', TEMPLATE_INCLUDEPATH));?>
<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
<div class="panel panel-default">
    <div class="panel-heading">下载对账单</div>
    <div class="panel-body">
        <div class='alert alert-info'>
            <p>每日9:00前完成数据更新，当前数据更新至 <?php  echo date('Y-m-d')?></p>
            <p>微信在次日9点启动生成前一天的对账单，建议商户10点后再获取；</p>
            <p>对账单中涉及金额的字段单位为“元”。</p>
            <p>下载账单接口为单日期接口，请尽量保持账单时间段不要过长。</p>
        </div>
        <div class="form-group">
        	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label class="col-xs-12 col-sm-3 col-md-3 control-label">账单类型</label>
                <div class="col-sm-9 col-xs-12">
                    <label class='radio-inline'><input type='radio' value='ALL' name='type' checked/> 所有账单</label>
                    <label class='radio-inline'><input type='radio' value='SUCCESS' name='type' /> 支付账单</label>
                    <label class='radio-inline'><input type='radio' value='REFUND' name='type' /> 退款帐单</label>
                    <label class='radio-inline'><input type='radio' value='REVOKED' name='type' /> 撤销账单</label>
                </div>
            </div>
        </div>
    	<div class="form-group">
    		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
	            <label class="col-xs-12 col-sm-3 col-md-3 control-label">账单时间</label>
	            <div class="col-sm-9 col-xs-12">
	                <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d', $starttime),'endtime'=>date('Y-m-d', $endtime)));?>
	            </div>
            </div>
        </div>
    	<div class="form-group">
        	<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
        	<div class="col-sm-9 col-xs-12">
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <input name="submit" type="submit" value="下载对账单" class="btn btn-primary span2" >
        	</div>
       </div>
   
        </div>
    </div>
  
</form>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>