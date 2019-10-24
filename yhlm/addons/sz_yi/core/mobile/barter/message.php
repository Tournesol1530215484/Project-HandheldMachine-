<?php

if (!defined('IN_IA')){

    exit('Access Denied');

}
global $_W,$_GPC;

$openid=m('user')->getOpenid(); 
$popenid=m('user')->islogin(); 
$openid = $openid?$openid:$popenid;
$uniacid=$_W['uniacid'];

$op=empty($_GPC['op'])?'display':$_GPC['op'];

if ($op == 'goods') {		//商品管理消息

	if ($_W['isajax']) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where openid=:openid and uniacid=:uniacid and dealmerchid > 1',array(':uniacid'=>$uniacid,':openid'=>$openid));  
		if (!empty($uid)) {  
		 	$list=pdo_fetchall('select gl.*,g.title from '.tablename('sz_yi_goods_log').' gl left join '.tablename('sz_yi_goods').' g on g.id=gl.goodsid where gl.uniacid = :uniacid and  gl.uid = :uid order by gl.id desc limit  '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$uniacid,':uid'=>$uid));
		 	foreach ($list as $key => $value) {
		 		$list[$key]['audit_time']=date('Y-m-d',$list[$key]['audit_time']);
		 		$list[$key]['sub_time']=date('Y-m-d',$list[$key]['sub_time']); 
		 	    }      
			 show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'op'=>$op));  
			// show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));  
		}  
		show_json(0,'你还是不是易货商家,快去注册商家吧'); 
	}

	include $this->template('barter/detail');
	exit;
}else if ($op == 'post'){		//邮寄 

	if ($_W['isajax']) {  
		$pindex = max(1, intval($_GPC['page'])); 
		$psize = 10;  
		$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where openid=:openid and uniacid=:uniacid and dealmerchid > 1',array(':uniacid'=>$uniacid,':openid'=>$openid));
		if (!empty($uid)) {  
			$list=pdo_fetchall('select o.status,o.createtime,o.price,g.title,og.total,m.realname,m.nickname from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on og.orderid=o.id left join '.tablename('sz_yi_goods').' g on g.id=og.goodsid left join '.tablename('sz_yi_member').' m on m.openid = o.openid  where o.uniacid = :uniacid and o.addressid > 0 and o.storeid = 0 and o.isexchange = 1 and o.supplier_uid = :uid order by o.id desc limit  '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$uniacid,':uid'=>$uid));   

			if (!empty($list)) {   
				foreach ($list as $key => $value) {
					$list[$key]['createtime']=date('Y-m-d',$value['createtime']); 
						if ($list[$key]['status'] == 0) {
							$list[$key]['sure']='待付款';
						}else if ($list[$key]['status'] == 1){
							$list[$key]['sure']='待发货'; 	
						}else if ($list[$key]['status'] == 2){
							$list[$key]['sure']='已发货'; 	
						}else if ($list[$key]['status'] == 3){
							$list[$key]['sure']='已完成'; 	
						}else if ($list[$key]['status'] == -1){
							$list[$key]['sure']='取消中'; 	
						} 
				}
			  }   
			show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'op'=>$op));   
		}  
		show_json(0,'你还是不是易货商家,快去注册商家吧');  
	}
	include $this -> template('barter/detail');  
	exit; 

}else if ($op == 'local'){		//本地
  
	if ($_W['isajax']) { 
		$pindex = max(1, intval($_GPC['page']));  
		$psize = 10; 
		$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where openid=:openid and uniacid=:uniacid and dealmerchid > 1',array(':uniacid'=>$uniacid,':openid'=>$openid));
		if (!empty($uid)) { 
			$list=pdo_fetchall('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and addressid = 0 and storeid > 0 and isexchange = 1 and supplier_uid = :uid order by id desc limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$uniacid,':uid'=>$uid));    
			show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'op'=>$op));  
		}   
		show_json(0,'你还是不是易货商家,快去注册商家吧'); 
	}
	
	include $this -> template('barter/detail'); 
	exit; 
}else if ($op == 'friend'){		//好友 
	$pindex = max(1, intval($_GPC['page'])); 
	$psize = 10;  
	if ($_W['isajax']) {
		$list=pdo_fetchall('select tl.*,m.realname,m.nickname from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.sponsor_openid = m.openid where tl.uniacid = :uniacid and tl.recipient_openid = :openid order by tl.id desc limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$uniacid,':openid'=>$openid)); 
		if (!empty($list)) {  
			show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'op'=>$op));
	    } 
	    show_json(0,'没有相关消息');     
	}
 
	include $this -> template('barter/detail'); 
	exit; 
}else if ($op == 'consult'){	//客服 
	if ($_W['isajax']) {
		$pindex = max(1, intval($_GPC['page'])); 
		$psize = 10; 
		$condition = ' and bc.uniacid = :uniacid and bc.openid=:openid ';
		$params = array(':uniacid' => $uniacid, ':openid' => $openid);
		$sql = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_barter_consult') . " bc where 1 {$condition}";
		$total = pdo_fetchcolumn($sql, $params);
		$list = array();    
		if (!empty($total)) { 
			$sql = 'SELECT bc.id,bc.merch_uid,du.merchname FROM ' . tablename('sz_yi_barter_consult') . ' bc left join '.tablename('sz_yi_dealmerch_user').' du on du.uid = bc.merch_uid where 1 ' . $condition . ' group by bc.merch_uid ORDER BY bc.id desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize; 
			$list = pdo_fetchall($sql,array(':uniacid' => $uniacid, ':openid' => $openid));                        
			foreach ($list as $key => $value) { 
	 			$tempinfo=pdo_fetch('select time,content,type from '.tablename('sz_yi_barter_consult').' where uniacid = :uniacid and merch_uid= :merch_uid and openid= :openid order by id desc ',array(':uniacid'=>$uniacid,':openid'=>$openid,':merch_uid'=>$list[$key]['merch_uid']));
	 			$goodsid=pdo_fetchcolumn('select id from '.tablename('sz_yi_goods').' where uniacid = :uniacid and type = 8 and supplier_uid= :uid ',array(':uniacid'=>$uniacid,':uid'=>$list[$key]['merch_uid']));
	 			$list[$key]['url']=$this->createMobileUrl('barter/consult',array('id'=>$goodsid));
				$list[$key]['content']=$tempinfo['content'];
			    $list[$key]['time']=$tempinfo['time'];
			    $list[$key]['type']=$tempinfo['type']; 
			    $list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['time']); 
				$exist=pdo_fetchcolumn('select id from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and id= :id',array(':uniacid'=>$uniacid,':id'=>$list[$key]['id']));
				if ($exist) {
				}else{
					unset($list[$key]);
				} 
			}  
		} 
		show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'op'=>$op)); 
	}   
   
include $this -> template('barter/detail'); 
	exit; 
	
}else if ($op == 'show'){

	include $this -> template('barter/consult'); 		//详细消息
	exit; 
}

include $this -> template('barter/message');  
