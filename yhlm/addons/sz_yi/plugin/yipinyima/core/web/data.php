<?php
/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->func('tpl');
if ($operation == 'display')
    {
        //print_r($_GPC);exit;
    ca('yipinyima.data.view');
    $id = $_GPC['id'];
     $goodsid=$_GPC['goodsid'];
	//分页数据，以及模糊查询
	$pindex = max(1, intval($_GPC['page']));
	//当前页码
	$psize = 10;
	//设置分页大小
	$condition = " uniacid = '{$_W['uniacid']}' and yipin_id =' {$id} '";


	$item = pdo_fetchall("SELECT * FROM " . tablename('yipinyimaerwei') . " WHERE $condition ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('yipinyimaerwei') . "WHERE $condition ");
	//记录总数
	$pager = pagination($total, $pindex, $psize);
		foreach($item as $k=>$v){
			
				$item2 = pdo_fetchall("SELECT * FROM " . tablename('yipinyima') . 
							" where id=" . $v['yipin_id'] . "  and uniacid=" . $_W['uniacid'] . "");
				foreach($item2 as $key2 => $value2){
					
					$roleid_item = unserialize($value2['jifen']);
					$commision_level_name = '';
					foreach($roleid_item as $key=>$var){
						if($var['id']==0){
						$commision_level_name .= "全部会员";
						}
						
						$var['jifen']   = empty($var['jifen'])? 0 : $var['jifen'];
						$var['xianjin'] = empty($var['xianjin'])? 0 : $var['xianjin'];
						$var['subcouponnum'] = empty($var['subcouponnum'])? 0 : $var['subcouponnum'];
						//Array ( [id] => 2 [itemid] => 2 [jifen] => 10 [xianjin] => [subcouponid] => 2 [subcouponnum] => )
						$commision_level = pdo_fetch("select id,uniacid,levelname from ".tablename('sz_yi_commission_level'). " where id='".$var['itemid']."'");	
						//$commision_level_name = empty($commision_level_name)? $commision_level['levelname'] : ','.$commision_level['levelname'];
						//if( $var['subcouponid'] ){
							$melsfds = pdo_fetch("select id,couponname from".tablename('sz_yi_coupon'). " where id='".$var['subcouponid']."' and  uniacid='".$_W['uniacid']."'");
						//}
						$jifen[$k]   .= empty($jifen[$k])?   $commision_level['levelname'].' '.$var['jifen'].'分'   : '<br />'.$commision_level['levelname'].' '.$var['jifen'].'分';
						$xianjin[$k] .= empty($xianjin[$k])? $commision_level['levelname'].' '.$var['xianjin'].'元' : '<br />'.$commision_level['levelname'].' '.$var['xianjin'].'元';
						$coupon[$k]  .= empty($coupon[$k])?  $commision_level['levelname'].' '.$melsfds['couponname'] .' '.$var['subcouponnum'].'张' : '<br />'.$commision_level['levelname'] . ' '. $melsfds['couponname'] .' '.$var['subcouponnum'].'张';
						
						//$shaoma_info = pdo_fetchall("SELECT *  FROM " . tablename('yipinyimajilu') . 
								//		" WHERE er_id=" . $v['id'] . "");
						//print_r($shaoma_info);
						
					}
					
				}
				$item[$k]['jifen']   = $jifen[$k];
				$item[$k]['xianjin'] = $xianjin[$k];
				$item[$k]['coupon']  = $coupon[$k];
				$item[$k]['commision_level_name'] = $commision_level_name;
				$item[$k]['couponname'] = $melsfds['couponname'];
				$item[$k]['subcouponnum']=$var['subcouponnum'];
				$item[$k]['jinm']=$var['jifen'];
				$item[$k]['xinj']=$var['xianjin'];	
							
						
				$item3 = pdo_fetchall("SELECT * FROM " . tablename('yipinyimajilu') ." where er_id=" . $v['id'] . "  and uniacid=" . $_W['uniacid'] . "");
				foreach($item3 as $key3 => $value3){
					$shi=date ('Y-m-d H:i:s',$value3['zt_shijian']);
					
					$zt_status[$k] += $value3['zt_status'];
					$shu[$k]   .= empty($shu[$k])? ($key3+1) . '、' .$value3['zt_status'].'次'  : '<br />'.($key3+1) . '、' .$value3['zt_status'].'次';
					$ren[$k]   .= empty($ren[$k])? ($key3+1) . '、' .$value3['zt_name'].' / '.$value3['zt_phosh']  : '<br />'.($key3+1) . '、' .$value3['zt_name'].' / '.$value3['zt_phosh'];
					$shijian[$k]   .= empty($shijian[$k])?  ($key3+1) . ' 、' .$shi.''  : '<br />' .($key3+1) . '、' .$shi;
				}
				
				$item[$k]['shaomashu']=$shu[$k];
				$item[$k]['shaomaren']=$ren[$k];
				$item[$k]['shaomashijian']=$shijian[$k];
				
				
		}
		

       /*$item = pdo_fetchall("SELECT *  FROM " . tablename('yipinyimaerwei') . " d " .
            " left join " . tablename('yipinyima') . ' g on g.id = d.yipin_id ' .
			" left join " .tablename('yipinyimajilu') .' m on m.er_id =d.id ' .
            " where  d.yipin_id=" . $id . "  and d.uniacid=" . $_W['uniacid'] . "");
		foreach($item as $k=>$v){
			$roleid_item = unserialize($v['jifen']);
			$commision_level_name = '';
			foreach($roleid_item as $key=>$var){
				$var['jifen']   = empty($var['jifen'])? 0 : $var['jifen'];
				$var['xianjin'] = empty($var['xianjin'])? 0 : $var['xianjin'];
				$var['subcouponnum'] = empty($var['subcouponnum'])? 0 : $var['subcouponnum'];
				//Array ( [id] => 2 [itemid] => 2 [jifen] => 10 [xianjin] => [subcouponid] => 2 [subcouponnum] => )
				$commision_level = pdo_fetch("select id,uniacid,levelname from ".tablename('sz_yi_commission_level'). " where id='".$var['itemid']."'");	
				//$commision_level_name = empty($commision_level_name)? $commision_level['levelname'] : ','.$commision_level['levelname'];
				//if( $var['subcouponid'] ){
					$melsfds = pdo_fetch("select id,couponname from".tablename('sz_yi_coupon'). " where id='".$var['subcouponid']."' and  uniacid='".$_W['uniacid']."'");
				//}
				$jifen[$k]   .= empty($jifen[$k])?   $commision_level['levelname'].' '.$var['jifen'].'分'   : '<br />'.$commision_level['levelname'].' '.$var['jifen'].'分';
				$xianjin[$k] .= empty($xianjin[$k])? $commision_level['levelname'].' '.$var['xianjin'].'元' : '<br />'.$commision_level['levelname'].' '.$var['xianjin'].'元';
				$coupon[$k]  .= empty($coupon[$k])?  $commision_level['levelname'].' '.$melsfds['couponname'] .' '.$var['subcouponnum'].'张' : '<br />'.$commision_level['levelname'] . ' '. $melsfds['couponname'] .' '.$var['subcouponnum'].'张';
				
				//$shaoma_info = pdo_fetchall("SELECT *  FROM " . tablename('yipinyimajilu') . 
            			//		" WHERE er_id=" . $v['id'] . "");
				//print_r($shaoma_info);
				
			}
			$item[$k]['jifen']   = $jifen[$k];
			$item[$k]['xianjin'] = $xianjin[$k];
			$item[$k]['coupon']  = $coupon[$k];
			$item[$k]['commision_level_name'] = $commision_level_name;
			$item[$k]['couponname'] = $melsfds['couponname'];
			$item[$k]['subcouponnum']=$var['subcouponnum'];
			$item[$k]['jinm']=$var['jifen'];
			$item[$k]['xinj']=$var['xianjin'];
		} */
}elseif ($operation == 'smsd') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted';
	$params = array(':uniacid' => $_W['uniacid'], ':deleted' => '0');
	if (!empty($_GPC['keyword'])) {
		$_GPC['keyword'] = trim($_GPC['keyword']);
		$condition .= ' AND `zt_name` LIKE :zt_name';
		$params[':zt_name'] = '%' . trim($_GPC['keyword']) . '%';
	}
	if (!empty($_GPC['keyword'])) {
		$_GPC['keyword'] = trim($_GPC['keyword']);
		$condition .= ' AND `title` LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
	}
	if (!empty($_GPC['keyword'])) {
		$_GPC['keyword'] = trim($_GPC['keyword']);
		$condition .= ' AND `title` LIKE :title';
		$params[':title'] = '%' . trim($_GPC['keyword']) . '%';
	}
}
include $this->template('data');
