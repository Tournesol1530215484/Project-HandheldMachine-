<?php

if (!defined('IN_IA')){

    exit('Access Denied');

}
global $_W,$_GPC;

$openid=m('user')->getOpenid(); 
$popenid=m('user')->islogin(); 
$openid = $openid?$openid:$popenid;
if (empty($_GPC['merch_uid'])) {
	$goodsid=intval($_GPC['id']);  
	$merch_uid=pdo_fetchcolumn('select supplier_uid from '.tablename('sz_yi_goods').' where uniacid = :uniacid and id = :goodsid  and type = 8 ',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$goodsid)); 
}else{
	$merch_uid=intval($_GPC['merch_uid']);
}
 

$op=empty($_GPC['op'])?'display':$_GPC['op'];

if ($_W['isajax']) {
	if ($op == 'getinfo') {
		$info=[];  
		$info['credit3']=m('member')->getCredit($openid,'credit3');
		$info['freeze_credit3']=m('member')->getCredit($openid,'freeze_credit3'); 
		$info['total']['friend']['put']=0;
		$info['total']['friend']['put']=pdo_fetchcolumn('select sum(currency) from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 14 and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 

		$info['total']['friend']['get']=0;
		$tempget=pdo_fetchall('select currency from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type = 13 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 
		if (!empty($tempget)) {
			foreach ($tempget as $key => $value) {
				$tempget[$key]['currency']=trim($value['currency'],'-'); 
				$info['total']['friend']['get']+=intval($tempget[$key]['currency']);	 
			}
		}      
		$info['total']['saler']=0;      
		$info['total']['saler']=pdo_fetchcolumn('select sum(currency) from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 2 and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));     
 		  
		$info['total']['use']=0;  
		$tempUse=pdo_fetchall('select currency from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 
		if (!empty($tempUse)) {
			foreach ($tempUse as $key => $value) {
				$value['currency']=trim($value['currency'],'-'); 
				$info['total']['use']+=intval($value['currency']);		
			}
		}
		$info['openid']=$openid;
		show_json(1,array('info'=>$info));
	}else if ($op == 'post'){   
	// $pindex = max(1, intval($_GPC['page']));
	// 	$psize = 10;
	// 	$condition = ' and uniacid = :uniacid and openid=:openid and merch_uid= :merch_uid ';
	// 	$params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid,':merch_uid'=>$merch_uid);
	// 	$sql = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_barter_consult') . " where 1 {$condition}";
	// 	$total = pdo_fetchcolumn($sql, $params);
	// 	$list = array();
	// 	if (!empty($total)) {
	// 		$sql = 'SELECT id,content,`time`,`type` FROM ' . tablename('sz_yi_barter_consult') . ' where 1 ' . $condition . ' ORDER BY `id` ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	// 		$list = pdo_fetchall($sql, $params); 
	// 		foreach ($list as $key => $value) {
	// 			$list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['time']);
	// 		}
	// 	}
	// 	show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
	} 
}
if ($op == 'detail') {

include $this -> template('barter/code_detail');
exit;
}    

include $this -> template('barter/score');
