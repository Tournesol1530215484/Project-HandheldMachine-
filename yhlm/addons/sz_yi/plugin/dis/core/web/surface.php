<?php
global $_W, $_GPC;

ca('commission.notice');
$set = $this->getSet();
if (checksubmit('submit')) {
 	 
 	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_order'));
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_order_goods'));
 	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_commission_apply'));
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_commission_log'));
/* 	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_returnmatter_money')); 
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_returnmatter')); */
	 
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_member'));
	pdo_query(" TRUNCATE TABLE  ".tablename('mc_members'));
	pdo_query(" TRUNCATE TABLE  ".tablename('mc_credits_recharge'));
	
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_descreturn_order'));
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_descreturn_log'));
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_descreturn_list')); 
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_af_supplier'));
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_perm_user'));
/* 		pdo_query(" TRUNCATE TABLE  ".tablename('two_way')); */
	
	pdo_query(" TRUNCATE TABLE  ".tablename('sz_yi_member_log'));
	 
 
    message('数据清空成功!', referer(), 'success');
}
load()->func('tpl');
include $this->template('surface');
