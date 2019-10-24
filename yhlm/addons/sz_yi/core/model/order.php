<?php
/**
 * 订单类
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Sz_DYi_Order
{
    function getDispatchPrice($dephp_0, $dephp_1, $dephp_2 = -1){
        if (empty($dephp_1)){
            return 0;
        }
        $dephp_3 = 0;
        if ($dephp_2 == -1){
            $dephp_2 = $dephp_1['calculatetype'];
        }
        if ($dephp_2 == 1){
            if ($dephp_0 <= $dephp_1['firstnum']){
                $dephp_3 = floatval($dephp_1['firstnumprice']);
            }else{
                $dephp_3 = floatval($dephp_1['firstnumprice']);
                $dephp_4 = $dephp_0 - floatval($dephp_1['firstnum']);
                $dephp_5 = floatval($dephp_1['secondnum']) <= 0 ? 1 : floatval($dephp_1['secondnum']);
                $dephp_6 = 0;
                if ($dephp_4 % $dephp_5 == 0){
                    $dephp_6 = ($dephp_4 / $dephp_5) * floatval($dephp_1['secondnumprice']);
                }else{
                    $dephp_6 = ((int) ($dephp_4 / $dephp_5) + 1) * floatval($dephp_1['secondnumprice']);
                }
//                print_r($dephp_6);exit;
                $dephp_3 += $dephp_6;
            }
        }else{
            if ($dephp_0 <= $dephp_1['firstweight']){
                $dephp_3 = floatval($dephp_1['firstprice']);
            }else{
                $dephp_3 = floatval($dephp_1['firstprice']);
                $dephp_4 = $dephp_0 - floatval($dephp_1['firstweight']);
                $dephp_5 = floatval($dephp_1['secondweight']) <= 0 ? 1 : floatval($dephp_1['secondweight']);
                $dephp_6 = 0;
                if ($dephp_4 % $dephp_5 == 0){
                    $dephp_6 = ($dephp_4 / $dephp_5) * floatval($dephp_1['secondprice']);
                }else{
                    $dephp_6 = ((int) ($dephp_4 / $dephp_5) + 1) * floatval($dephp_1['secondprice']);
                }
                $dephp_3 += $dephp_6;
            }
        }
//        print_r($dephp_3);exit;
        return $dephp_3;

    }

    /*
    function getDispatchPrice($weight, $d)
    {
        if (empty($d)) {
            return 0;
        }
        $price = 0;
        if ($weight <= $d['firstweight']) {
            $price = floatval($d['firstprice']);
        } else {
            $price         = floatval($d['firstprice']);
            $secondweight  = $weight - floatval($d['firstweight']);
            $dsecondweight = floatval($d['secondweight']) <= 0 ? 1 : floatval($d['secondweight']);
            $secondprice   = 0;
            if ($secondweight % $dsecondweight == 0) {
                $secondprice = ($secondweight / $dsecondweight) * floatval($d['secondprice']);
            } else {
                $secondprice = ((int) ($secondweight / $dsecondweight) + 1) * floatval($d['secondprice']);
            }
            $price += $secondprice;
        }
        return $price;
    }
     */

    /*
    function getCityDispatchPrice($_var_6, $_var_7, $weight, $d)
    {
        if (is_array($_var_6) && count($_var_6) > 0) {
            foreach ($_var_6 as $_var_8) {
                $_var_9 = explode(';', $_var_8['citys']);
                if (in_array($_var_7, $_var_9) && !empty($_var_9)) {
                    return $this->getDispatchPrice($weight, $_var_8);
                }
            }
        }
        return $this->getDispatchPrice($weight, $d);
    }
     */

    function getCityDispatchPrice($dephp_7, $dephp_8, $dephp_0, $dephp_1){
        if (is_array($dephp_7) && count($dephp_7) > 0){

            foreach ($dephp_7 as $dephp_9){
                $dephp_10 = explode(';', $dephp_9['citys']);
                if (in_array($dephp_8, $dephp_10) && !empty($dephp_10)){
                    return $this -> getDispatchPrice($dephp_0, $dephp_9, $dephp_1['calculatetype']);
                }
            }
        }

        return $this -> getDispatchPrice($dephp_0, $dephp_1);
    }

    /**
     * 支付完成回调方法
	 *
     * @param params array
     * @return array()
     * modify RainYang 2016.4.7
     */
    public function payResult1($params) // 这个方法永远也调用不到。
    {
        global $_W;
        $fee     = $params['fee'];
        $data    = array( 'status' => $params['result'] == 'success' ? 1 : 0 );

        $ordersn = $params['tid'];

		//订单信息
        $order   = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order') . ' WHERE  ordersn=:ordersn AND uniacid=:uniacid limit 1', array( ':uniacid' => $_W['uniacid'], ':ordersn' => $ordersn ));

        //验证paylog里金额是否与订单金额一致
        $log = pdo_fetch('select * from ' . tablename('core_paylog') . ' where `uniacid`=:uniacid and fee=:fee and `module`=:module and `tid`=:tid limit 1', array( ':uniacid' => $_W['uniacid'], ':module' => 'sz_yi', ':fee' => $fee, ':tid' => $order['ordersn'] ));
		
        if (empty($log)) {
            show_json(-1, '订单金额错误, 请重试!');
            exit;
        }

        $orderid = $order['id'];


        if ($params['from'] == 'return') {
            $address = false;
            if (empty($order['dispatchtype'])) {

                $address = pdo_fetch('select realname,mobile,address from ' . tablename('sz_yi_member_address') . ' where id=:id limit 1', array( ':id' => $order['addressid'] ));
            }
            $carrier = false;
            if ($order['dispatchtype'] == 1 || $order['isvirtual'] == 1) {

                $carrier = unserialize($order['carrier']);
            }
            
            if ($params['type'] == 'cash') {
                return array( 'result' => 'success', 'order' => $order, 'address' => $address, 'carrier' => $carrier );
            } else {
				
                if ($order['status'] == 0) {
                    $pv = p('virtual');
                    if (!empty($order['virtual']) && $pv) {
                        $pv->pay($order);
                    } else {
                        pdo_update('sz_yi_order', array( 'status' => 1, 'paytime' => time() ), array( 'id' => $orderid ));
                        if ($order['deductcredit2'] > 0) {
                            $shopset = m('common')->getSysset('shop');
                            m('member')->setCredit($order['openid'], 'credit2', -$order['deductcredit2'], array( 0, $shopset['name'] ."余额抵扣: {$order['deductcredit2']} 订单号: ".$order['ordersn']));
                        }


						/*设置库存、赠送积分*/
                        $this->setStocksAndCredits($orderid, 1);
						
		
						/*优惠券插件*/
                        if (p('coupon') && !empty($order['couponid'])) {
                            p('coupon')->backConsumeCoupon($order['id']);
                        }						
                        m('notice')->sendOrderMessage($orderid);//发送订单信息
						/*分销插件*/
                        if (p('commission')) {
                            p('commission')->checkOrderPay($order['id']);//检测订单支付
                        }
                    }
                }

                //订单分解
                /**订单分解修改，订单会员折扣、积分折扣、余额抵扣、使用优惠劵后订单分解按商品价格与总商品价格比例拆分，使用运费的平分运费。添加平分修改运费以及修改订单金额的信息到新的订单表中。**/
                if(p('supplier')){
                    $order_info = $order;
                    $resolve_order_goods = pdo_fetchall('select * from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid and uniacid=:uniacid ',array( ':orderid' => $order['id'], ':uniacid' => $_W['uniacid'] ));

                    // return $resolve_order_goods;
                    $datas = array();
                    $num = false;
                    //对应供应商商品循环到对应供应商下
                    foreach ($resolve_order_goods as $key => $value) {
                        $datas[$value['supplier_uid']][]['id'] = $value['id'];
                    }
                    unset($order['id']);
                    $dispatchprice = $order['dispatchprice'];
                    $olddispatchprice = $order['olddispatchprice'];
                    $changedispatchprice = $order['changedispatchprice'];
                    if(!empty($datas)){
                        foreach ($datas as $key => $value) {
                            $price = 0;
                            $realprice = 0;
                            $oldprice = 0;
                            $changeprice = 0;
                            $goodsprice = 0;
                            $couponprice = 0;
                            $discountprice = 0;
                            $deductprice = 0;
                            $deductcredit2 = 0;
                            foreach($value as $v){
                                $resu = pdo_fetch('select price,realprice,oldprice,supplier_uid from ' . tablename('sz_yi_order_goods') . ' where id=:id and uniacid=:uniacid ',array(
                                        ':id' => $v['id'],
                                        ':uniacid' => $_W['uniacid']
                                    ));
                                $price += $resu['price'];
                                $realprice += $resu['realprice'];
                                $oldprice += $resu['oldprice'];
                                $goodsprice += $resu['price'];
                                $supplier_uid = $resu['supplier_uid'];
                                $changeprice += $resu['changeprice'];
                                //计算order_goods表中的价格占订单商品总额的比例
                                $scale = $resu['price']/$order['goodsprice'];
                                //按比例计算优惠劵金额
                                $couponprice += round($scale*$order['couponprice'],2);
                                //按比例计算会员折扣金额
                                $discountprice += round($scale*$order['discountprice'],2);
                                //按比例计算积分金额
                                $deductprice += round($scale*$order['deductprice'],2);
                                //按比例计算消费余额金额
                                $deductcredit2 += round($scale*$order['deductcredit2'],2); 
                            }
                            
                            $order['oldprice'] = $oldprice;
                            $order['goodsprice'] = $goodsprice;
                            $order['supplier_uid'] = $supplier_uid;
                            $order['couponprice'] = $couponprice;
                            $order['discountprice'] = $discountprice;
                            $order['deductprice'] = $deductprice;
                            $order['deductcredit2'] = $deductcredit2;
                            $order['changeprice'] = $changeprice;
                            //平分实际支付运费金额
                            $order['dispatchprice'] = round($dispatchprice/(count($resu)),2);
                            //平分老的支付运费金额
                            $order['olddispatchprice'] = round($olddispatchprice/(count($resu)),2);
                            //平分修改后支付运费金额
                            $order['changedispatchprice'] = round($changedispatchprice/(count($resu)),2);
                            //新订单金额计算，实际支付金额减计算后优惠劵金额、会员折金额、积分金额、余额抵扣金额，在加上实际运费的金额。
                            $order['price'] = $realprice - $couponprice - $discountprice - $deductprice - $deductcredit2 + $order['dispatchprice'];

                            if($num == false){
                                pdo_update('sz_yi_order', $order, array(
                                    'id' => $order_id,
                                    'uniacid' => $_W['uniacid']
                                    ));
                                $num = ture;
                                
                            }else{
                                $ordersn = m('common')->createNO('order', 'ordersn', 'SH');
                                $order['ordersn'] = $ordersn;
                                pdo_insert('sz_yi_order', $order);
                                $logid = pdo_insertid();
                                $oid = array(
                                    'orderid' => $logid
                                    );
                                foreach ($value as $val) {
                                    pdo_update('sz_yi_order_goods',$oid ,array('id' => $val['id'],'uniacid' => $_W['uniacid']));
                                }
                                
                            }
                        }
                    }
                }else{
                    $order_info = $order;
                }

                return array(
                    'result' => 'success',
                    'order' => $order_info,
                    'address' => $address,
                    'carrier' => $carrier,
                    'virtual' => $order['virtual']
                );
            }
        }
    }

	public function payResult($dephp_11)
    {
        global $_W;
        $dephp_12 = $dephp_11['fee'];
        $dephp_13 = array('status' => $dephp_11['result'] == 'success' ? 1 : 0);
        $dephp_14 = $dephp_11['tid'];
        $dephp_15 = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where  ordersn=:ordersn and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':ordersn' => $dephp_14));
        $dephp_16 = pdo_fetch('select * from ' . tablename('core_paylog') . ' where `uniacid`=:uniacid and fee=:fee and `module`=:module and `tid`=:tid limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'sz_yi', ':fee' => $dephp_12, ':tid' => $dephp_15['ordersn']));
        if (empty($dephp_16)){
            show_json(-1, '订单金额错误, 请重试!');
            exit;
        }
        $dephp_17 = $dephp_15['id'];
        if ($dephp_11['from'] == 'return'){
            $dephp_18 = false;
            if (empty($dephp_15['dispatchtype'])){
                $dephp_18 = pdo_fetch('select realname,mobile,address from ' . tablename('sz_yi_member_address') . ' where id=:id limit 1', array(':id' => $dephp_15['addressid']));
            }
            $dephp_19 = false;
            if ($dephp_15['dispatchtype'] == 1 || $dephp_15['isvirtual'] == 1){
                $dephp_19 = unserialize($dephp_15['carrier']);
            }
            if ($dephp_11['type'] == 'cash'){
                return array('result' => 'success', 'order' => $dephp_15, 'address' => $dephp_18, 'carrier' => $dephp_19);
            }else{
                if ($dephp_15['status'] == 0){
                    $dephp_20 = p('virtual');
                    if (!empty($dephp_15['virtual']) && $dephp_20){
                        $dephp_20 -> pay($dephp_15);
                    }else{
                        pdo_update('sz_yi_order', array('status' => 1, 'paytime' => time()), array('id' => $dephp_17));
                        if ($dephp_15['deductcredit2'] > 0){
                            $dephp_21 = m('common') -> getSysset('shop');
                            m('member') -> setCredit($dephp_15['openid'], 'credit2', - $dephp_15['deductcredit2'], array(0, $dephp_21['name'] . "余额抵扣: {$dephp_15['deductcredit2']} 订单号: " . $dephp_15['ordersn']));
                        }
                        $this -> setStocksAndCredits($dephp_17, 1);
                        if (p('coupon') && !empty($dephp_15['couponid'])){
                            p('coupon') -> backConsumeCoupon($dephp_15['id']);
                        }
                        m('notice') -> sendOrderMessage($dephp_17);
                        if (p('commission')){
                            p('commission') -> checkOrderPay($dephp_15['id']);
                        }
                    }
                }
                if(p('supplier')){
                    p('supplier') -> order_split($dephp_17);
                }
                $dephp_22 = pdo_fetch('select o.dispatchprice,o.ordersn,o.price,og.optionname as optiontitle,og.optionid,og.total from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_goods') . 'og on og.orderid = o.id  where o.id = :id and o.uniacid=:uniacid', array(':id' => $dephp_17, ':uniacid' => $_W['uniacid']));
                $dephp_23 = 'SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid FROM ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where og.orderid=:orderid order by og.id asc';
                $dephp_22['goods1'] = set_medias(pdo_fetchall($dephp_23, array(':orderid' => $dephp_17)), 'thumb');
                $dephp_22['goodscount'] = count($dephp_22['goods1']);
                return array('result' => 'success', 'order' => $dephp_15, 'address' => $dephp_18, 'carrier' => $dephp_19, 'virtual' => $dephp_15['virtual'], 'goods' => $dephp_22);
            }
        }
    }
	/**
	* 设置库存、赠送积分
	*
	* @param
	* @return array 数组格式的返回结果
	*/
    function setStocksAndCredits($orderid = '', $type = 0)
    {
        global $_W;
		//订单
        $order   = pdo_fetch('SELECT id,ordersn,price,openid,dispatchtype,addressid,carrier,status FROM ' . tablename('sz_yi_order') . ' WHERE id=:id LIMIT 1', array(':id' => $orderid));
		//产品
        $goods   = pdo_fetchall(" SELECT og.goodsid,og.total,g.totalcnf,og.realprice,g.credit,og.optionid,g.total as goodstotal,g.sales,g.salesreal ".
		                        " FROM " . tablename('sz_yi_order_goods') . " og " . " LEFT JOIN " . tablename('sz_yi_goods') . " g ON g.id=og.goodsid " . 
								" WHERE og.orderid=:orderid and og.uniacid=:uniacid ", array( ':uniacid' => $_W['uniacid'], ':orderid' => $orderid ));
        $credits = 0;
        foreach ($goods as $g) {		
            $stocktype = 0;//库存类型
            if ($type == 0) {
                if ($g['totalcnf'] == 0) {
                    $stocktype = -1;
                }
            } else if ($type == 1) {
                if ($g['totalcnf'] == 1) {
                    $stocktype = -1;
                }
            } else if ($type == 2) {
                if ($order['status'] >= 1) {
                    if ($g['totalcnf'] == 1) {
                        $stocktype = 1;
                    }
                } else {
                    if ($g['totalcnf'] == 0) {
                        $stocktype = 1;
                    }
                }
            }
            if (!empty($stocktype)) {
                if (!empty($g['optionid'])) {
                    $option = m('goods')->getOption($g['goodsid'], $g['optionid']);
                    if (!empty($option) && $option['stock'] != -1) {
                        $stock = -1;
                        if ($stocktype == 1) {
                            $stock = $option['stock'] + $g['total'];
                        } else if ($stocktype == -1) {
                            $stock = $option['stock'] - $g['total'];
                            $stock <= 0 && $stock = 0;
                        }
                        if ($stock != -1) {
                            pdo_update('sz_yi_goods_option', array( 'stock' => $stock ), array( 'uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'id' => $g['optionid'] ));
                        }
                    }
                }
                if (!empty($g['goodstotal']) && $g['goodstotal'] != -1) {
                    $totalstock = -1;
                    if ($stocktype == 1) {
                        $totalstock = $g['goodstotal'] + $g['total'];
                    } else if ($stocktype == -1) {
                        $totalstock = $g['goodstotal'] - $g['total'];
                        $totalstock <= 0 && $totalstock = 0;
                    }
                    if ($totalstock != -1) {
                        pdo_update('sz_yi_goods', array( 'total' => $totalstock ), array( 'uniacid' => $_W['uniacid'], 'id' => $g['goodsid'] ));
                    }
                }
            }
			/*赠送积分*/
            $gcredit = trim($g['credit']);
            if (!empty($gcredit)) {
                if (strexists($gcredit, '%')) {
                    $credits += intval(floatval(str_replace('%', '', $gcredit)) / 100 * $g['realprice']);
                } else {
                    $credits += intval($g['credit']) * $g['total'];
                }
            }			
            if ($type == 0) {
                pdo_update('sz_yi_goods', array( 'sales' => $g['sales'] + $g['total'] ), array( 'uniacid' => $_W['uniacid'], 'id' => $g['goodsid'] ));
            } elseif ($type == 1) {
                if ($order['status'] >= 1) {
                    $salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) ' .
					           ' FROM ' . tablename('sz_yi_order_goods') . ' og ' . ' LEFT JOIN ' . tablename('sz_yi_order') . ' o ON o.id = og.orderid ' . 
						       ' WHERE og.goodsid=:goodsid AND o.status>=1 AND o.uniacid=:uniacid LIMIT 1', array(':goodsid' => $g['goodsid'], ':uniacid' => $_W['uniacid']));
                    pdo_update('sz_yi_goods', array( 'salesreal' => $salesreal ), array( 'id' => $g['goodsid']));
                }
            }
			
        }
        if ($credits > 0) {
            $shopset = m('common')->getSysset('shop');
            if ($type == 1) {
                m('member')->setCredit($order['openid'], 'credit1', $credits, array( 0, $shopset['name'] . '购物积分 订单号: ' . $order['ordersn'] ));
            } elseif ($type == 2) {
                if ($order['status'] >= 1) {
                    m('member')->setCredit($order['openid'], 'credit1', -$credits, array( 0, $shopset['name'] . '购物取消订单扣除积分 订单号: ' . $order['ordersn'] ));
                }
            }
        }
    }
		
    function getDefaultDispatch(){
        global $_W;
        $dephp_31 = 'select * from ' . tablename('sz_yi_dispatch') . ' where isdefault=1 and uniacid=:uniacid and enabled=1 Limit 1';
        //$dephp_31 = 'select * from ' . tablename('sz_yi_dispatch') . ' where uniacid=:uniacid and enabled=1 Limit 1';
        $dephp_11 = array(':uniacid' => $_W['uniacid']);
        $dephp_13 = pdo_fetch($dephp_31, $dephp_11);
        return $dephp_13;
    }
    function getNewDispatch(){
        global $_W;
        $dephp_31 = 'select * from ' . tablename('sz_yi_dispatch') . ' where uniacid=:uniacid and enabled=1 order by id desc Limit 1';
        $dephp_11 = array(':uniacid' => $_W['uniacid']);
        $dephp_13 = pdo_fetch($dephp_31, $dephp_11);
        return $dephp_13;
    }
    function getOneDispatch($dephp_32){
        global $_W;
        $dephp_31 = 'select * from ' . tablename('sz_yi_dispatch') . ' where id=:id and uniacid=:uniacid and enabled=1 Limit 1';
        $dephp_11 = array(':id' => $dephp_32, ':uniacid' => $_W['uniacid']);
        $dephp_13 = pdo_fetch($dephp_31, $dephp_11);
        return $dephp_13;
    }
}
