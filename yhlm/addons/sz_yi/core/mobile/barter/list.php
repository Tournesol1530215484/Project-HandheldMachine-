<?php

 if (!defined('IN_IA')){

    exit('Access Denied');

}

global $_W, $_GPC;
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$openid = m('user') -> getOpenid();
//var_dump($openid);die;
$uniacid = $_W['uniacid'];

$set = set_medias(m('common') -> getSysset('shop'), array('logo', 'img'));

$commission = p('commission');

if ($commission){

    $shopid = intval($_GPC['shopid']);

    if (!empty($shopid)){

        $myshop = set_medias($commission -> getShop($shopid), array('img', 'logo'));

    }

}



$supplier = !empty($_GPC['supplier'])?$_GPC['supplier']:0;



$current_category = false;

if (!empty($_GPC['tcate'])){

    $current_category = pdo_fetch('select id,parentid,name,level from ' . tablename('sz_yi_category') . ' where id=:id 

        and uniacid=:uniacid order by displayorder DESC', array(':id' => intval($_GPC['tcate']), ':uniacid' => $_W['uniacid']));

}elseif (!empty($_GPC['ccate'])){

    $current_category = pdo_fetch('select id,parentid,name,level from ' . tablename('sz_yi_category') . ' where id=:id 

        and uniacid=:uniacid order by displayorder DESC', array(':id' => intval($_GPC['ccate']), ':uniacid' => $_W['uniacid']));

}elseif (!empty($_GPC['pcate'])){

    $current_category = pdo_fetch('select id,parentid,name,level from ' . tablename('sz_yi_category') . ' where id=:id 

        and uniacid=:uniacid order by displayorder DESC', array(':id' => intval($_GPC['pcate']), ':uniacid' => $_W['uniacid']));

}

$parent_category = pdo_fetch('select id,parentid,name,level from ' . tablename('sz_yi_category') . ' where id=:id 

    and uniacid=:uniacid limit 1', array(':id' => $current_category['parentid'], ':uniacid' => $_W['uniacid']));

// $args = array(

//     'pagesize'     => 3,

//     'page'         => empty($_GPC['page'])?1:intval($_GPC['page']),

//     'isnew'        => $_GPC['isnew'],

//     'ishot'        => $_GPC['ishot'],

//     'isrecommand'  => $_GPC['isrecommand'],

//     'isdiscount'   => $_GPC['isdiscount'],

//     'istime'       => $_GPC['istime'],

//     'keywords'     => $_GPC['keywords'],

//     'pcate'        => $_GPC['pcate'],

//     'ccate'        => $_GPC['ccate'],

//     'tcate'        => $_GPC['tcate'],

//     'order'        => $_GPC['order'],

//     'by'           => $_GPC['by'],

//     'supplier_uid' => $supplier,

//     'price1'       => $_GPC['price1'],

//     'price2'       => $_GPC['price2'],

//     'city'       => $_GPC['city'],

// );   extension键值是扩展条件

$args = array(
    'extension'=>' and g.type = 8 and g.status = 1 and g.ischeck = 1 ',
    'pagesize' => 20,
    'page' => $_GPC['page'],        
    'isnew' => $_GPC['isnew'],
    'ishot' => $_GPC['ishot'], 
    'isrecommand' => $_GPC['isrecommand'], 
    'isdiscount' => $_GPC['isdiscount'], 
    'istime' => $_GPC['istime'], 
    'keywords' => $_GPC['keywords'], 
    'pcate' => $_GPC['pcate'], 
    'ccate' => $_GPC['ccate'], 
    'tcate' => $_GPC['tcate'], 
    'order' => $_GPC['order'], 
    'by' => $_GPC['by'],
    'supplier_uid'=>$supplier,
    'minp' => $_GPC['minp'],
    'maxp' => $_GPC['maxp']
);
         


if (!empty($myshop['selectgoods']) && !empty($myshop['goodsids'])){

    $args['ids'] = $myshop['goodsids'];

}

$condition = ' and type = 8 and `uniacid` = :uniacid AND `deleted` = 0 and status=1 and ischeck = 1';

$params = array(':uniacid' => $_W['uniacid']);

if (!empty($args['ids'])){

    $condition .= ' and id in ( ' . $args['ids'] . ')';

}

$isnew = !empty($args['isnew']) ? 1 : 0;

if (!empty($isnew)){

    $condition .= ' and isnew=1';

}

$ishot = !empty($args['ishot']) ? 1 : 0;

if (!empty($ishot)){

    $condition .= ' and ishot=1';

}

$isrecommand = !empty($args['isrecommand']) ? 1 : 0;

if (!empty($isrecommand)){

    $condition .= ' and isrecommand=1';

}

$isdiscount = !empty($args['isdiscount']) ? 1 : 0;

if (!empty($isdiscount)){

    $condition .= ' and isdiscount=1';

}

$istime = !empty($args['istime']) ? 1 : 0;

if (!empty($istime)){

    $condition .= ' and istime=1 and ' . time() . '>=timestart and ' . time() . '<=timeend';

}

$keywords = !empty($args['keywords']) ? $args['keywords'] : '';

if (!empty($keywords)){

    $condition .= ' AND `title` LIKE :title';

    $params[':title'] = '%' . trim($keywords) . '%';

}

$tcate = !empty($args['tcate']) ? intval($args['tcate']) : 0;

if (!empty($tcate)){

    $condition .= " AND (`tcate` = :tcate or FIND_IN_SET({$tcate},cates)<>0)";

    $params[':tcate'] = intval($tcate);

}

$ccate = !empty($args['ccate']) ? intval($args['ccate']) : 0;

if (!empty($ccate)){

    $condition .= " AND ( `ccate` = :ccate or  FIND_IN_SET({$ccate},cates)<>0 )";

    $params[':ccate'] = intval($ccate);

}

$pcate = !empty($args['pcate']) ? intval($args['pcate']) : 0;

if (!empty($pcate)){

    $condition .= ' AND `pcate` = :pcate';

    $params[':pcate'] = intval($pcate);

}



// 区域筛选

$city = !empty($args['city']) ? $args['city'] : '';

if (!empty($city)) {

    $condition .= ' AND `city` LIKE :city';

    $params[':city'] = '%' . trim($city) . '%';

}

// 区域筛选
//  去除  uid为0只有7个商品
// $condition.=' AND `supplier_uid` = :supplier  ';
// $params[':supplier'] = $supplier ;



// var_dump($condition);
// var_dump($params);
if ($_GPC['debug']) {

}else{
    $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('sz_yi_goods') . " where 1 {$condition}", $params);
}
// var_dump($total);
$pindex = max(1, intval($_GPC['page']));



$goods = m('goods')->getDealList($args);
// $goods = m('goods') -> getList($args);
if ($_GPC['ac'] == 'demo') {                
    // var_dump($goods);         
    // exit;        
}        
if ($_GPC['debug']) {                        
    // $args['extension']='  ';
    // var_dump($goods);
    // exit;            
}                        
         
// var_dump($goods);die;
// $pager           = pagination($total, $pindex, $psize);
//showlevels 会员等级浏览权限

//showgroups  会员组浏览权限

//用户信息
$memberqx = pdo_fetch("SELECT groupid,level FROM ".tablename("sz_yi_member")." WHERE uniacid=:uniacid AND openid=:openid ",array(":uniacid"=>$_W['uniacid'],":openid"=>$openid));
//var_dump($openid);die;
foreach ($goods as $key => $value) {
    $goods[$key]['isxs'] = 1 ;
	//判断用户组是否有权限浏览这个商品
	if(!empty($value['showgroups'])){ 
		$showgroups = explode(",",$value['showgroups']);
		if(!in_array($memberqx['groupid'],$showgroups) ){ 
			 $goods[$key]['isxs'] = 0;
            
		}
	}
	//判断用户等级是否有权限
	if(!empty($value['showlevels'])){ 
		$showgroups = explode(",",$value['showlevels']);
		if(!in_array($memberqx['level'],$showgroups) ){ 
			$goods[$key]['isxs'] = 0;
            
		}
	}

    $goods[$key]['minprice']=pdo_fetchcolumn('select min(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid='.$_W['uniacid'].' and goodsid='.$goods[$key]['id']);
    $goods[$key]['maxprice']=pdo_fetchcolumn('select max(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid='.$_W['uniacid'].' and goodsid='.$goods[$key]['id']);

}


$category = false;

if (intval($_GPC['page']) <= 1){

    if (!empty($_GPC['tcate'])){

        $parent_category1 = pdo_fetch('select id,parentid,name,level,thumb from ' . tablename('sz_yi_category') . ' 

            where id=:id and uniacid=:uniacid limit 1', array(':id' => $parent_category['parentid'], ':uniacid' => $_W['uniacid']));

        $category = pdo_fetchall('select id,name,level,thumb from ' . tablename('sz_yi_category') . ' 

            where parentid=:parentid 

            and enabled=1 and uniacid=:uniacid order by level asc, isrecommand desc, displayorder DESC', array(':parentid' => $parent_category['id'], ':uniacid' => $_W['uniacid']));

        $category = array_merge(array(array('id' => 0, 'name' => '全部分类', 'level' => 0), $parent_category1, $parent_category), $category);

    }elseif (!empty($_GPC['ccate'])){

        if (intval($set['catlevel']) == 3){

            $category = pdo_fetchall('select id,name,level,thumb from ' . tablename('sz_yi_category') . ' where 

                (parentid=:parentid or id=:parentid) and enabled=1  and uniacid=:uniacid 

                order by level asc, isrecommand desc, displayorder DESC', array(':parentid' => intval($_GPC['ccate']), ':uniacid' => $_W['uniacid']));

        }else{

            $category = pdo_fetchall('select id,name,level,thumb from ' . tablename('sz_yi_category') . ' where 

                parentid=:parentid and enabled=1 and uniacid=:uniacid order by level asc, 

                isrecommand desc, displayorder DESC', array(':parentid' => $parent_category['id'], ':uniacid' => $_W['uniacid']));

        }

        $category = array_merge(array(array('id' => 0, 'name' => '全部分类', 'level' => 0), $parent_category), $category);

    }elseif (!empty($_GPC['pcate'])){

        $category = pdo_fetchall('select id,name,level,thumb from ' . tablename('sz_yi_category') . ' 

            where (parentid=:parentid or id=:parentid) and enabled=1 and uniacid=:uniacid order by level asc, 

            isrecommand desc, displayorder DESC', array(':parentid' => intval($_GPC['pcate']), ':uniacid' => $_W['uniacid']));

        $category = array_merge(array(array('id' => 0, 'name' => '全部分类', 'level' => 0)), $category);

    }else{

        $category = pdo_fetchall('select id,name,level,thumb from ' . tablename('sz_yi_category') . ' 

            where parentid=0 and enabled=1 and id<>:localid and uniacid=:uniacid order by displayorder DESC', array(':localid'=>'824',':uniacid' => $_W['uniacid']));
    }
    foreach ($category as & $c){

        $c['thumb'] = tomedia($c['thumb']);

        if ($current_category['id'] == $c['id']){

            $c['on'] = true;

        }

    }

    unset($c);

}

if ($_W['isajax']){

    show_json(1, array('goods' => $goods, 'city'=>$city,'pagesize' => $args['pagesize'], 'category' => $category, 'current_category' => $current_category));

}

$pager = pagination($total, $pindex, $args['pagesize']);
if ($_GPC['debug']) {                                              
    include $this -> template('barter/list_debug');
    exit;
}   
include $this -> template('barter/list_debug');

