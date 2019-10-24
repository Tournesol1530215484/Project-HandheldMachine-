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
	$merchname=pdo_fetchcolumn('select merchname from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid = :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$merch_uid)); 
}else{
	$merch_uid=intval($_GPC['merch_uid']);
}
 

$op=empty($_GPC['op'])?'display':$_GPC['op'];

if ($_W['isajax']) {
	if ($op == 'get') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = ' and uniacid = :uniacid and openid=:openid and merch_uid= :merch_uid ';
		$params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid,':merch_uid'=>$merch_uid);
		$sql = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_barter_consult') . " where 1 {$condition}";
		$total = pdo_fetchcolumn($sql, $params);
		$list = array();
		if (!empty($total)) {
			$sql = 'SELECT id,content,`time`,`type` FROM ' . tablename('sz_yi_barter_consult') . ' where 1 ' . $condition . ' ORDER BY `id` ASC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql, $params); 
			foreach ($list as $key => $value) {
				$list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['time']);
			}
		}
		show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));

	}else if ($op == 'post'){
		$merch_uid=intval($_GPC['merch_uid']);
		$exists=pdo_fetchcolumn('select max(id) from '.tablename('sz_yi_barter_consult').' where merch_uid = :merch_uid and openid = :openid and uniacid = :uniacid',array(':merch_uid'=>$merch_uid,':openid'=>$openid,':uniacid'=>$_W['uniacid']));
		
		$data = array( 
				'pid'=>0,
				'uniacid'=>$_W['uniacid'],
				'openid'=>$openid,
				'merch_uid'=>$merch_uid,
				'content'=>trim($_GPC['content']),
				'type'=>2,
				'time'=>time()
			); 

		$exists && $data['pid']=$exists;	
		pdo_insert('sz_yi_barter_consult',$data);
		$id=pdo_insertid(); 
		$id?show_json(1,$id):show_json(0,'消息提交失败!'); 
	}

} 
   

include $this -> template('barter/consult');
