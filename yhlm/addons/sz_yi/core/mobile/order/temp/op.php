<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid    = m('user')->getOpenid();
$uniacid   = $_W['uniacid'];
if ($_W['isajax']) {
	if ($operation == 'cancel') {
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch('select id,ordersn,openid,status,deductcredit,deductprice,couponid from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
		if (empty($order)) {
			show_json(0, '订单未找到!');
		}
		if ($order['status'] != 0) {
			show_json(0, '订单已支付，不能取消!');
		}
		pdo_update('sz_yi_order', array('status' => -1, 'canceltime' => time()), array('id' => $order['id']));
		m('notice')->sendOrderMessage($orderid);
		if ($order['deductprice'] > 0) {
			$shop = m('common')->getSysset('shop');
			m('member')->setCredit($order['openid'], 'credit1', $order['deductcredit'], array('0', $shop['name'] . "购物返还抵扣积分 积分: {$order['deductcredit']} 抵扣金额: {$order['deductprice']} 订单号: {$order['ordersn']}"));
		}
		if (p('coupon') && !empty($order['couponid'])) {
			p('coupon')->returnConsumeCoupon($orderid);
		}
		show_json(1);
	} else if ($operation == 'complete') {
		//订单完成
		$action = !empty($_GPC['special']) ? $_GPC['special'] : 'ordinary';
	
		// 普通订单
		if ($action == 'ordinary') {
			// show_json(-1, '普通订单!');
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch('select id,status,openid,goodsprice,supplier_uid,isexchange,couponid,ordersn from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

			$order_goods = pdo_fetch('select goodsid from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid and uniacid=:uniacid and openid=:openid limit 1', array(':orderid' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

			$goods = pdo_fetch('select isreturn from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid  limit 1', array(':id' => $order_goods['goodsid'], ':uniacid' => $uniacid));

			if($goods['isreturn']==1){
				// $re =	p('return') -> returnconfirm($orderid);
				// file_put_contents(dirname(__FILE__).'/dasdsadsa',json_encode( $orderid));
			}

			if (empty($order)) {
				show_json(0, '订单未找到!');
			}
			if ($order['status'] != 2) {
				show_json(0, '订单未发货，不能确认收货!');
			}
			$data = array('status' => 3, 'finishtime' => time());
			if ($order['special'] == 1) {
				$data['special_status'] = 'm3';
			}
			
			pdo_update('sz_yi_order', $data, array('id' => $order['id']));
			m('member')->upgradeLevel($order['openid']);
			if (p('coupon') && !empty($order['couponid'])) {
				p('coupon')->backConsumeCoupon($orderid);
			}
			m('notice')->sendOrderMessage($orderid);
			if (p('commission')) {
				p('commission')->checkOrderFinish($orderid);
			}
			// 订单确定收货 2016年11月23日 10:28:06 
				//易货订单返回商家易货码 start
	        if ($order['isexchange'] == 1){
		    	// $order_goods = pdo_fetchall('select og.id,g.title,og.price,og.dispatchprice,og.goodsid,og.supplier_uid,og.optionid,g.thumb, g.total as stock,og.total as buycount,g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,g.buylevels,g.buygroups from  ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on og.goodsid = g.id ' . ' where og.orderid=:orderid and og.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $orderid));
		            $seller=pdo_fetchcolumn('select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid and dealmerchid > 0 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$order['supplier_uid']));   
		            $currency_credit3=m('member')->getCredit($seller,'currency_credit3');
					if(floatval($currency_credit3) >= floatval($order['goodsprice'])){     
						m('member') -> setCredit($seller, 'currency_credit3', -floatval($order['goodsprice'])); 	//卖家获得易货码 冻结 如果卖家存在易货额度将自动使用易货额度激活
						m('member') -> setCredit($seller, 'credit3', floatval($order['goodsprice'])); 	//卖家获得易货码 已使用易货额度自动激活
						m('log')->putBarterCurrencyLog($seller,$order['supplier_uid'],11,-floatval($order['goodsprice']),$order['ordersn'],'销售易货码自动解冻');
					}else{
						m('member') -> setCredit($seller, 'freeze_credit3', floatval($order['goodsprice'])); 	//卖家获得易货码 冻结 如果卖家存在易货额度将自动使用易货额度激活
					}     
		            m('log')->putBarterCodeLog($seller,$order['supplier_uid'],2,1,1,floatval($order['goodsprice']),$order['ordersn'],'销售收入');
	     
	        }
	        // end
			p('descreturn')->insertDescReturnOrder($orderid);
			p('descreturn')->promptly($orderid); // 2016年12月21日 15:22:32
			 	 	
			p('descreturn')->moneyratio($orderid); // 供应商佣金比例的发放
			

			show_json(1);
		// 特殊订单 第一次收货
		} elseif ($action == 1) {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch('select id,status,openid from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

			if (empty($order)) {
				show_json(0, '订单未找到!');
			}
			if ($order['status'] != 2) {
				show_json(0, '订单未发货，不能确认收货!');
			}
			pdo_update('sz_yi_order', array('special_status' => 'm1'), array('id' => $order['id']));

			show_json(1);
		} elseif ($action == 2) {
			// show_json(0, '2');
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch('select id,status,openid from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

			if (empty($order)) {
				show_json(0, '订单未找到!');
			}
			if ($order['status'] != 2) {
				show_json(0, '订单未发货，不能确认收货!');
			}
			pdo_update('sz_yi_order', array('special_status' => 'm2'), array('id' => $order['id']));

			show_json(1);
		}
	} else if ($operation == 'refund') {
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch('select id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,virtual from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
		if (empty($order)) {
			show_json(0, '订单未找到!');
		}
		if ($order['status'] != 1 && $order['status'] != 2 && $order['status'] != 3) {
			show_json(0, '订单未付款或未收货，不能申请退款!');
		} else {
			if ($order['status'] == 3) {
				if (!empty($order['virtual']) || $order['isverify'] == 1) {
					show_json(0, '此订单不允许退款!');
				} else {
					$tradeset = m('common')->getSysset('trade');
					$refunddays = intval($tradeset['refunddays']);
					if ($refunddays > 0) {
						$days = intval((time() - $order['finishtime']) / 3600 / 24);
						if ($days > $refunddays) {
							show_json(0, '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!');
						}
					} else {
						show_json(0, '订单完成, 无法申请退款!');
					}
				}
			}
		}
		$order['refundprice'] = $order['price'] + $order['deductcredit2'];
		if ($order['status'] >= 3) {
			$order['refundprice'] -= $order['dispatchprice'];
		}
		$refundid = $order['refundid'];
		if ($_W['ispost']) {
			if (!empty($_GPC['cancel'])) {
				pdo_update('sz_yi_order_refund', array('status' => -1), array('id' => $refundid));
				pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $orderid));
				show_json(1);
			} else {
				$refund = array('uniacid' => $uniacid, 'orderid' => $orderid, 'refundno' => m('common')->createNO('order_refund', 'refundno', 'SR'), 'price' => $order['refundprice'], 'reason' => $_GPC['refunddata']['reason'], 'content' => $_GPC['refunddata']['content']);
				if (empty($refundid)) {
					$refund['createtime'] = time();
					pdo_insert('sz_yi_order_refund', $refund);
					$refundid = pdo_insertid();
					pdo_update('sz_yi_order', array('refundid' => $refundid), array('id' => $orderid));
				} else {
					pdo_update('sz_yi_order_refund', $refund, array('id' => $refundid));
				}
				m('notice')->sendOrderMessage($orderid, true);
				show_json(1);
			}
		}
		$refund = false;
		if (!empty($refundid)) {
			$refund = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id and uniacid=:uniacid and orderid=:orderid limit 1', array(':id' => $refundid, ':uniacid' => $uniacid, ':orderid' => $orderid));
			$refund['createtime'] = date('Y-m-d H:i', $refund['createtime']);
		}
		show_json(1, array('order' => $order, 'refund' => $refund));
	} else if ($operation == 'comment') {
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch('select id,status,iscomment from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
		if (empty($order)) {
			show_json(0, '订单未找到!');
		}
		if ($order['status'] != 3 && $order['status'] != 4) {
			show_json(0, '订单未收货，不能评价!');
		}
		if ($order['iscomment'] >= 2) {
			show_json(0, '您已经评价了!');
		}
		$comments = $_GPC['comments'];
		if ($_W['ispost'] && is_array($comments)) {
			$member = m('member')->getMember($openid);
			foreach ($comments as $c) {
				$old_c = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order_comment') . ' where uniacid=:uniacid and orderid=:orderid and goodsid=:goodsid limit 1', array(':uniacid' => $_W['uniacid'], ':goodsid' => $c['goodsid'], ':orderid' => $orderid));
				if (empty($old_c)) {
					$comment = array('uniacid' => $uniacid, 'orderid' => $orderid, 'goodsid' => $c['goodsid'], 'level' => $c['level'], 'content' => $c['content'], 'images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()), 'openid' => $openid, 'nickname' => $member['nickname'], 'headimgurl' => $member['avatar'], 'createtime' => time());
					pdo_insert('sz_yi_order_comment', $comment);
				} else {
					$comment = array('append_content' => $c['content'], 'append_images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()));
					pdo_update('sz_yi_order_comment', $comment, array('uniacid' => $_W['uniacid'], 'goodsid' => $c['goodsid'], 'orderid' => $orderid));
				}
			}
			if ($order['iscomment'] <= 0) {
				$d['iscomment'] = 1;
			} else {
				$d['iscomment'] = 2;
			}
			pdo_update('sz_yi_order', $d, array('id' => $orderid));
			show_json(1);
		}
		$goods = pdo_fetchall('select og.id,og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' left join ' . tablename('sz_yi_goods_option') . ' o on o.id=og.optionid ' . ' where og.orderid=:orderid and og.uniacid=:uniacid ', array(':uniacid' => $uniacid, ':orderid' => $orderid));
		$goods = set_medias($goods, 'thumb');
		show_json(1, array('order' => $order, 'goods' => $goods));
	} else if ($operation == 'delete') {
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch('select id,status from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));
		if (empty($order)) {
			show_json(0, '订单未找到!');
		}
		if ($order['status'] != 3 && $order['status'] != -1) {
			show_json(0, '订单无交易，不能删除!');
		}
		pdo_update('sz_yi_order', array('userdeleted' => 1), array('id' => $order['id']));
		show_json(1);
	}
}
if ($operation == 'refund') {
    $tradeset = m('common')->getSysset('trade');
    if(!isMobile()){
    	include $this->template('member/center');
    }
    include $this->template('order/refund');
} else if ($operation == 'comment') {
    include $this->template('order/comment');
}
