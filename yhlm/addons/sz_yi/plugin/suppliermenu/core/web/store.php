<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'query';

if ($operation == 'query') {                //查询兑换点
    $kwd                = trim($_GPC['keyword']);
    $uid                = trim($_GPC['uid']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];       //只能选择自己的兑换点
    
//  if (!empty($uid)) {
//  	var_dump($uid);
//      $condition .= " AND merch_uid = :uid "; 
//      $params[':uid'] =$uid; 	 		 	
//  }
    $condition   = " uniacid=:uniacid and status = 1 and merch_uid = {$_W['uid']} ";
	
    if (!empty($kwd)) {
        $condition .= " AND `title` LIKE :keyword "; 
        $condition .= " or `address` LIKE :keyword "; 
        $condition .= " or `mobile` LIKE :keyword ";
        $params[':keyword'] = "%{$kwd}%"; 
    }

    $ds = pdo_fetchall('SELECT id,title,address,mobile,exchangeDate,exchangeTime FROM ' . tablename('sz_yi_exchange_address') . " WHERE {$condition} order by id asc", $params);
    include $this->template('query_saler');
    exit;
}else if ($operation == 'getProduct') {                //查询商品
    $kwd                = trim($_GPC['keyword']);
    // $uid                = trim($_W['uid']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];   
    $pm=p('bonus')->getMerch($_W['uid']);    
    $tempuid=pdo_fetchall('select uid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and  status = 1',array(':uniacid'=>$_W['uniacid'],':openid'=>$pm['openid']));
    foreach ($tempuid as $key => $value) {
        $uid[]=$value['uid'];
    }
    unset($tempuid);

    $condition   = " uniacid=:uniacid and status = 1 and supplier_uid in (".implode(',',$uid).") ";     //不只是易货商品
    
    if (!empty($kwd)) {
        $condition .= " AND `title` LIKE :keyword "; 
        $params[':keyword'] = "%{$kwd}%"; 
    }                   

    $ds = pdo_fetchall('SELECT id,title,thumb,marketprice,type FROM ' . tablename('sz_yi_goods') . " WHERE {$condition} order by id asc", $params);
   
    if ($ds) {
        foreach ($ds as $key => $value) {
            $ds[$key]['thumb']=tomedia($value['thumb']);        
            if ($value['type'] == '8') {
                $temp=pdo_fetchcolumn('select max(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid = :uniacid and goodsid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));
                $ds[$key]['marketprice']=$temp;
            }
        }
    }
    include $this->template('query_product');
    exit;
}




load()->func('tpl'); 
include $this->template('store'); 
