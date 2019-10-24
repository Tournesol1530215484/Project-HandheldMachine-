<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid    = m('user')->getOpenid();
$popenid        = m('user')->islogin();
$openid = $openid?$openid:$popenid;
// var_dump($openid); 	 		  	 	
// exit;
$uniacid   = $_W['uniacid'];
if ($_W['isajax']) {	
	if ($operation == 'display') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 10;
		$condition = ' and f.uniacid = :uniacid and f.openid=:openid and f.deleted=0 '; //与店铺同用一个操作 and merchid is null
		$params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid);
		$sql = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_member_favorite') . " f where 1 {$condition}";
		$total = pdo_fetchcolumn($sql, $params);
		$list = array();
		if (!empty($total)) {
			$sql = 'SELECT f.id,f.merchid,f.goodsid,g.title,g.thumb,g.marketprice,g.productprice,mu.merchid merch,mu.dealmerchid dealmerch,mu.type FROM ' . tablename('sz_yi_member_favorite') . ' f ' . ' left join ' . tablename('sz_yi_goods') . ' g on f.goodsid = g.id left join '.tablename('sz_yi_perm_user').' as mu on f.merchid=mu.uid  where 1 ' . $condition . ' ORDER BY `id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql, $params);
			foreach ($list as $key => $value) {
				if (!empty($value['merchid'])) {
					if ($value['merch'] > 0) {
						$minfo=pdo_fetch('select * from '.tablename('sz_yi_merch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$list[$key]['merchname']=$minfo['merchname'];
						$list[$key]['logo']=tomedia($minfo['logo'])?:tomedia($minfo['img']);

						$price=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$people=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$list[$key]['average']=floatval($price / $people);
						$list[$key]['url']=$this->createMobileUrl('member/merch',array('op'=>'detail','id'=>$minfo['id']));	 	  		
					}else if($value['dealmerch'] > 0){
						$minfo=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merch']));	
						$list[$key]['merchname']=$minfo['merchname'];
						$list[$key]['logo']=tomedia($minfo['logo'])?:tomedia($minfo['img']);
						$list[$key]['url']=$this->createPluginMobileUrl('supplier/store',array('op'=>'skip','merch'=>'5','uid'=>$minfo['uid']));
						$price=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$people=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$list[$key]['average']=floatval($price / $people); 
						
					}else{
						$minfo=pdo_fetch('select * from '.tablename('sz_yi_store_data').' where uniacid = :uniacid and storeid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merch']));	
						$list[$key]['merchname']=$minfo['storename'];
						$list[$key]['logo']=tomedia($minfo['logo'])?:tomedia($minfo['signboard']);
						$list[$key]['url']=$this->createPluginMobileUrl('supplier/store',array('op'=>'skip','merch'=>'2','storeid'=>$minfo['storeid'])); 
						$price=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$people=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['merchid']));
						$list[$key]['average']=floatval($price / $people);
					}
				}
			}
				
			$list = set_medias($list, 'thumb');
			$list = set_medias($list, 'img');
		}
		show_json(1, array('total' => $total, 'list' => $list, 'pagesize' => $psize));
	} else if ($operation == 'set') {
		$id = intval($_GPC['id']);
		$goods = pdo_fetch('select id from ' . tablename('sz_yi_goods') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));
		if (empty($goods)) {
			show_json(0, '商品未找到');
		}
		$data = pdo_fetch('select id,deleted from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and goodsid=:id and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':id' => $id));
		if (empty($data)) {
			$data = array('uniacid' => $_W['uniacid'], 'openid' => $openid, 'goodsid' => $id, 'createtime' => time());
			pdo_insert('sz_yi_member_favorite', $data);
			show_json(1, array('isfavorite' => true));
		} else {
			if (empty($data['deleted'])) {
				pdo_update('sz_yi_member_favorite', array('deleted' => 1), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
				show_json(1, array('isfavorite' => false));
			} else {
				pdo_update('sz_yi_member_favorite', array('deleted' => 0), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
				show_json(1, array('isfavorite' => true));
			}
		}
	} else if ($operation == 'remove' && $_W['ispost']) {
		$ids = $_GPC['ids'];
		if (empty($ids) || !is_array($ids)) {
			show_json(0, '参数错误');
		}
		$sql = "update " . tablename('sz_yi_member_favorite') . ' set deleted=1 where uniacid=:uniacid and openid=:openid and id in (' . implode(',', $ids) . ')';
		pdo_query($sql, array(':uniacid' => $uniacid, ':openid' => $openid));
		show_json(1);
	}
}
if($_GPC[style] == 1){
	include $this->template('shop/favorite1');
}else if($_GPC[style] == 2){
	include $this->template('shop/favorite2');
}else{
	include $this->template('shop/favorite');
}