<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'list' : $_GPC['op'];
// 商家列表 http://sz1.61why.com/app/index.php?i=2&c=entry&method=store&p=supplier&m=sz_yi&do=plugin
if ($op == 'list') {

	if ($_W['isajax']) {

		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$sql    = 'select * from'.tablename('sz_yi_store_data').'where uniacid = :uniacid and isopen = 1';
		$sql   .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$list   = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		$tsql = 'select count(id) from '.tablename('sz_yi_order').' where uniacid = :uniacid and status = 3 and supplier_uid = :supplier_uid';
		$csql = 'select af.productname from '.tablename('sz_yi_af_supplier') .' as af left join '.tablename('sz_yi_perm_user').' as pu on pu.openid = af.openid left join '.tablename('sz_yi_store_data').' as sd on sd.storeid=pu.uid where af.uniacid = :uniacid';
		foreach ($list as & $value) {
			$value['url'] = $this->createPluginMobileUrl('supplier/store', array('storeid' => $value['storeid'], 'op' => 'skip'));
			// 遍历3个商品
			$value['goods'] = m('goods')->getList(array('pagesize' => 3, 'supplier_uid' => $value['storeid']));
			// 商品总数
			$value['goodstatol'] = m('goods')->tatol(array('supplier_uid' => $value['storeid']));
			// 查询销量
			$value['to'] = pdo_fetchcolumn($tsql, array(':uniacid' => $_W['uniacid'], ':supplier_uid' => $value['storeid']));
			// 查询行业类别 hs_sz_yi_af_supplier
			$value['class'] = pdo_fetchcolumn($csql, array(':uniacid' => $_W['uniacid']));
		}
		$list = set_medias($list, 'logo');
		// 商品地址
		foreach ($list as & $value) {
			foreach ($value['goods'] as & $v) {
				$v['gurl'] = $this->createMobileUrl('shop/detail', array('id' => $v['id']));
			}
		}
		// End 商品地址
		show_json(1, array('list' => $list, 'pagesize' => $psize));
	}

} elseif ($op == 'skip') {

	$storeid = $_GPC['storeid'];
	$sql = 'select id from'.tablename('sz_yi_perm_user').'where uniacid = :uniacid and uid = :uid and type = 2';
	$isSeller = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':uid' => $storeid));
	if (!empty($isSeller)) {
		// 把商家id存入Cookie
		setcookie('storeid', $storeid);
		header('Location:'. $this->createPluginMobileUrl('supplier/store', array('op' => 'store')));
		exit;
	}
	setcookie('storeid', '');
	echo "<script>alert('商家不存在！');</script>";
	// header('Location:'. $this->createPluginMobileUrl('supplier/store', ['op' => 'list']));
	exit;

} elseif ($op == 'store') {

	$storeid = $_COOKIE['storeid']; // 门店id
	$sql = 'select * from'.tablename('sz_yi_store_data').'where uniacid = :uniacid and isopen = 1';
	$ss = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));
	if (!$ss) {
		echo "店铺已关闭！";
		exit;
	}
	$designer = p('designer');
	if (empty($this->yzShopSet['ispc']) || isMobile()) {
	    if ($designer) {
	        $pagedata = $designer->getPage(1, $storeid);
	        if ($pagedata) {
	            extract($pagedata);
	            $guide = $designer->getGuide($system, $pageinfo);
	            $_W['shopshare'] = array(
	                'title' => $share['title'],
	                'imgUrl' => $share['imgUrl'],
	                'desc' => $share['desc'],
	                'link' => $this->createMobileUrl('shop')
	            );
	            include $this->template('shop/index_diy');
	            exit;
	        } else {
	        	header('Location:'.$this->createPluginMobileUrl('supplier/store', array('op' => 'mystore')));
	        	exit;
	        }
	    }
	}

} elseif ($op == 'mystore') {
	$storeid = $_COOKIE['storeid']; // 门店id
	$sql = 'select * from'.tablename('sz_yi_store_data').'where uniacid = :uniacid and isopen = 1';
	$isopen = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));
	if (false === $isopen) {
		echo "店铺已关闭！";
		exit;
	}

	$designer = p('designer');
	if (empty($this->yzShopSet['ispc']) || isMobile()) {
	    if ($designer) {
	        $pagedata = $designer->getPage(1, $storeid);
	        if ($pagedata) {
	            extract($pagedata);
	            $guide = $designer->getGuide($system, $pageinfo);
	            $_W['shopshare'] = array(
	                'title' => $share['title'],
	                'imgUrl' => $share['imgUrl'],
	                'desc' => $share['desc'],
	                'link' => $this->createMobileUrl('shop')
	            );
	            include $this->template('shop/index_diy');
	            exit;
	        }
	    }
	}
	// 查询店铺资料
	$sql = 'select * from'.tablename('sz_yi_store_data').'where uniacid = :uniacid and storeid = :storeid';
	$info = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':storeid' => $storeid));
	// 全部商家数
	$info['goodstatol'] = m('goods')->tatol(array('supplier_uid' => $storeid));
	// 查询销量
	$tsql = 'select count(id) from '.tablename('sz_yi_order').' where uniacid = :uniacid and status = 3 and supplier_uid = :supplier_uid';
	$info['to'] = pdo_fetchcolumn($tsql, array(':uniacid' => $_W['uniacid'], ':supplier_uid' => $storeid));

	if ($_W['isajax']) {

		$args = array(
		    'page' => $_GPC['page'],
		    'pagesize' => 6,
		    'order' => 'displayorder desc,createtime desc',
		    'by' => '',
		    'supplier_uid' => $storeid
		);
		$type = array(
		    '1' => 'isnew',
		    '2' => 'ishot',
		    '3' => 'isrecommand',
		    '4' => 'isdiscount',
		    '5' => 'istime'
		);
	    if( empty($_GPC['type']) || !array_key_exists($_GPC['type'], $type) ){
	    	// 默认不转参数 查询全部商品
	        // $args['isnew'] = 1 ;
	    } else {
	        $args[$type[$_GPC['type']]] = 1;
	    }
		if ( !empty($shop['selectgoods']) ) {
			$goodsids = explode(',', $shop['goodsids']);
			if (!empty($goodsids)) {
				$args['ids'] = trim($shop['goodsids']);
			}
		}
		$goods = m('goods')->getList($args);
		show_json(1, array('goods' => $goods, 'pagesize' => $args['pagesize'], 'info' => $info, 'goodstatol' => $goodstatol));

	} else {
	    include $this->template('mystore');
	    exit;
	}
} elseif ($op == 'searchstore') {
	if ($_W['isajax']) {
		$keywords = '%'.trim($_GPC['keywords']).'%';
		$sql  = 'select storeid,storename from'.tablename('sz_yi_store_data').'where uniacid = :uniacid and storename like :like and isopen = 1';
		$list = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':like' => $keywords));
		foreach ($list as & $value) {
			$value['url'] = $this->createPluginMobileUrl('supplier/store', array('storeid' => $value['storeid'], 'op' => 'skip'));
		}
		show_json(1, array('list' => $list));
	}
} elseif ($op == 'searchgoods') {
	if ($_W['isajax']) {
		$keywords = '%'.trim($_GPC['keywords']).'%';
		$goods = m('goods')->getList(array('pagesize' => 100000, 'supplier_uid' => $_COOKIE['storeid'], 'keywords' => $keywords));
		show_json(1, array('list' => $goods));

	}
}
include $this->template('store');