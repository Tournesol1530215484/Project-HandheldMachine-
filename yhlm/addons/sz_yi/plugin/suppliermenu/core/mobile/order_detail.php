	<?php
global $_W, $_GPC;

    $popenid        = m('user')->islogin();
    $openid = m('user')->getOpenid();
    $openid = $openid?$openid:$popenid;
$member = m('member')->getMember($openid);
$set=$this->getset();
$op=!empty($_GPC['op'])?$_GPC['op']:'display';

if ($op == 'display') {	 	 	 
	$order_id=intval($_GPC['id']);
	$param=[	 	 	 	 	 	 
	    ':orderid'=>$order_id,
	    ':uniacid'=>$_W['uniacid']
	];

	$order=pdo_fetch('select o.id ,o.address,o.carrier ,o.status , o.isverify , o.paytype , o.price , o.dispatchprice ,
	 o.virtual ,o.dispatchtype, o.addressid , o.virtual_str,o.olddispatchprice , o.goodsprice, o.discountprice,o.discountprice,
	 o.deductprice , o.deductcredit2,o.changeprice,o.changedispatchprice , o.couponprice ,o.ordersn,o.finishtime,
	 o.expresssn ,o.iscomment,re.status as canrefund ,o.refundid from '.tablename('sz_yi_order').' as o left join '
	.tablename('sz_yi_order_refund').' as re on re.id=o.refundid where o.id=:orderid and o.uniacid=:uniacid',$param);

	$goods=pdo_fetchall('select og.goodsid,og.total,og.total,og.diyformfields,og.optionid,og.price,og.diyformfields,g.thumb,g.title from '
	.tablename('sz_yi_order_goods').' as og left join '.tablename('sz_yi_goods').' as g on g.id = og.goodsid where og.orderid=:orderid and og.uniacid=:uniacid',$param);
 	 
	foreach ($order as $key => &$value) {
	    if ($key=='address'|| $key=='carrier'){
	        $order[$key]=unserialize($order[$key]);
	    }
	}

	$address=$order['address'];
	$carrier=$order['carrier'];
	include $this->template('order_detail');
	exit;
}else if($op == 'refund'){	 

	$id=$_GPC['id'];
	
	if ($_W['isajax']) {
		$item = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		 			
		if ($item['isexchange'] == 1 && $_GPC['refundstatus'] == 1) {	 	 	 
				
			if (doubleval($item['dispatchprice']) > 0) {	 		 
				$supplierOpenid=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid limit 1',array(':uniacid'=>$_W['uniacid'],':uid'=>$item['supplier_uid']));
				m('member')->setCredit($item['openid'],'credit2',doubleval($item['dispatchprice']));
			}   
			//只能在订单没有完成的时候申请退款 易货邮费还没成为提现金额 冻结易货码也没开始结算
			$sure=pdo_fetchcolumn('select id from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 2 and dealsn = :sn',array(':uniacid'=>$_W['uniacid'],':sn'=>$item['ordersn']));
			if($sure){		//为了让之前下单的商品不重复转易货码
				m('member')->setCredit($supplierOpenid,'freeze_credit3',-doubleval($item['goodsprice']));
			}
			m('member')->setCredit($item['openid'],'credit3',doubleval($item['goodsprice']));
			$refundarr=array(
				'refundid'=>0,
				'refundtime'=>time(),	 	
				'status' =>'-1'
			);
			$tmu=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and dealmerchid > 0');	 	 	
            m('log')->putBarterCodeLog($item['openid'],$tmu,10,1,3,+floatval($item['goodsprice']),$item['ordersn'],'易货退款收入');
			pdo_update('sz_yi_order',$refundarr,array('id'=>$item['id'],'uniacid'=>$_W['uniacid']));
			pdo_update('sz_yi_order_refund',array('status' => '1'),array('id'=>$item['refundid']));
			show_json(1,'退款申请处理成功!');
		}else{     
			order_list_refund($item);			 	 		
		}
	}
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':id'=>$id,
	);


	$order=pdo_fetch('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and id = :id',$params);
	$merch=p('bonus')->getMerch($order['supplier_uid']);
	$merch['openid'] != $openid && m('tools')->tip('没有该订单!');
	$order['refundid'] == 0 && m('tools')->tip('该订单没有申请退款,或已经退款成功!');

	include $this->template('refund');
	exit;	 	 
}	

	 			 

function order_list_refund($_var_21)
{
	global $_W, $_GPC;
	$_var_29 = m('common')->getSysset('shop');
	if (empty($_var_21['refundid'])) {
		show_json(0,'订单未申请退款，不需处理！');
	}
	$_var_22 = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id and status=0 limit 1', array(':id' => $_var_21['refundid']));
	if (empty($_var_22)) {
		pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
		show_json(0,'未找到退款申请，不需处理！');
	}
	if (empty($_var_22['refundno'])) {
		$_var_22['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');
		pdo_update('sz_yi_order_refund', array('refundno' => $_var_22['refundno']), array('id' => $_var_22['id']));
	}
	$_var_30 = intval($_GPC['refundstatus']);
	$_var_31 = $_GPC['refundcontent'];
	if ($_var_30 == 0) {
		show_json(0,'暂不处理');
	} else if ($_var_30 == 1) {
		$_var_8 = $_var_21['ordersn'];
		if (!empty($_var_21['ordersn2'])) {
			$_var_32 = sprintf('%02d', $_var_21['ordersn2']);
			$_var_8 .= 'GJ' . $_var_32;
		}
		$_var_33 = $_var_22['price'];
		$_var_34 = pdo_fetchall('SELECT g.id,g.credit, o.total,o.realprice FROM ' . tablename('sz_yi_order_goods') . ' o left join ' . tablename('sz_yi_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.uniacid=:uniacid', array(':orderid' => $_var_21['id'], ':uniacid' => $_W['uniacid']));
		$_var_35 = 0;
		foreach ($_var_34 as $_var_36) {
			$_var_35 += $_var_36['credit'] * $_var_36['total'];
		}
		$_var_37 = 0;
		if ($_var_21['paytype'] == 1) {
			m('member')->setCredit($_var_21['openid'], 'credit2', $_var_33, array(0, $_var_29['name'] . "退款: {$_var_33}元 订单号: " . $_var_21['ordersn']));
			$_var_38 = true;
		} else if ($_var_21['paytype'] == 21) {
			$_var_33 = round($_var_33 - $_var_21['deductcredit2'], 2);
			$_var_38 = m('finance')->refund($_var_21['openid'], $_var_8, $_var_22['refundno'], $_var_21['price'] * 100, $_var_33 * 100);
			$_var_37 = 2;
		} else {
			if ($_var_33 < 1) {
				show_json(0,'退款金额必须大于1元，才能使用微信企业付款退款!');
			}
			$_var_33 = round($_var_33 - $_var_21['deductcredit2'], 2);
			$_var_38 = m('finance')->pay($_var_21['openid'], 1, $_var_33 * 100, $_var_22['refundno'], $_var_29['name'] . "退款: {$_var_33}元 订单号: " . $_var_21['ordersn']);
			$_var_37 = 1;
		}
		if (is_error($_var_38)) {
			show_json(0,$_var_38['message']);
		}
		if ($_var_35 > 0) {
			m('member')->setCredit($_var_21['openid'], 'credit1', -$_var_35, array(0, $_var_29['name'] . "退款扣除积分: {$_var_35} 订单号: " . $_var_21['ordersn']));
		}
		if ($_var_21['deductcredit'] > 0) {
			m('member')->setCredit($_var_21['openid'], 'credit1', $_var_21['deductcredit'], array('0', $_var_29['name'] . "购物返还抵扣积分 积分: {$_var_21['deductcredit']} 抵扣金额: {$_var_21['deductprice']} 订单号: {$_var_21['ordersn']}"));
		}
		if (!empty($_var_37)) {
			if ($_var_21['deductcredit2'] > 0) {
				m('member')->setCredit($_var_21['openid'], 'credit2', $_var_21['deductcredit2'], array('0', $_var_29['name'] . "购物返还抵扣余额 积分: {$_var_21['deductcredit2']} 订单号: {$_var_21['ordersn']}"));
			}
		}
		pdo_update('sz_yi_order_refund', array('reply' => '', 'status' => 1, 'refundtype' => $_var_37), array('id' => $_var_21['refundid']));
		m('notice')->sendOrderMessage($_var_21['id'], true);
		pdo_update('sz_yi_order', array('refundid' => 0, 'status' => -1, 'refundtime' => time()), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
		foreach ($_var_34 as $_var_36) {
			$_var_39 = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $_var_36['id'], ':uniacid' => $_W['uniacid']));
			pdo_update('sz_yi_goods', array('salesreal' => $_var_39), array('id' => $_var_36['id']));
		}
		plog('order.op.refund', "订单退款 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	} else if ($_var_30 == -1) {
		pdo_update('sz_yi_order_refund', array('reply' => $_var_31, 'status' => -1), array('id' => $_var_21['refundid']));
		m('notice')->sendOrderMessage($_var_21['id'], true);
		plog('order.op.refund', "订单退款拒绝 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']} 原因: {$_var_31}");
		pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	} else if ($_var_30 == 2) {
		$_var_37 = 2;
		pdo_update('sz_yi_order_refund', array('reply' => '', 'status' => 1, 'refundtype' => $_var_37), array('id' => $_var_21['refundid']));
		m('notice')->sendOrderMessage($_var_21['id'], true);
		pdo_update('sz_yi_order', array('refundid' => 0, 'status' => -1, 'refundtime' => time()), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
		foreach ($_var_34 as $_var_36) {
			$_var_39 = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $_var_36['id'], ':uniacid' => $_W['uniacid']));
			pdo_update('sz_yi_goods', array('salesreal' => $_var_39), array('id' => $_var_36['id']));
		}
	}
	show_json(1,'退款申请处理成功!');
}


