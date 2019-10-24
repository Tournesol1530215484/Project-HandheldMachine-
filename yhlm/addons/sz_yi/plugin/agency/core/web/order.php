<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();
if ($operation == 'display') {
	ca('order.view.status_1|order.view.status0|order.view.status1|order.view.status2|order.view.status3|order.view.status4|order.view.status5');
	if (p('supplier')) {
		$roleid = pdo_fetchcolumn('select roleid from' . tablename('sz_yi_perm_user') . ' where uid=' . $_W['uid'] . ' and uniacid=' . $_W['uniacid']);
		if ($roleid == 0) {
			$perm_role = 0;
		} else {
			$perm_role = pdo_fetchcolumn('select status1 from' . tablename('sz_yi_perm_role') . ' where uniacid = '.$_W['uniacid'].'  and id=' . $roleid);
		}
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$status = $_GPC['status'];
	$sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
	$condition = ' o.uniacid = :uniacid and o.deleted=0';
	$paras = $paras1 = array(':uniacid' => $_W['uniacid']);
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		if ($_GPC['searchtime'] == '1') {
			$condition .= ' AND o.createtime >= :starttime AND o.createtime <= :endtime ';
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
		}
	}
	if (empty($pstarttime) || empty($pendtime)) {
		$pstarttime = strtotime('-1 month');
		$pendtime = time();
	}
	if (!empty($_GPC['ptime'])) {
		$pstarttime = strtotime($_GPC['ptime']['start']);
		$pendtime = strtotime($_GPC['ptime']['end']);
		if ($_GPC['psearchtime'] == '1') {
			$condition .= ' AND o.paytime >= :pstarttime AND o.paytime <= :pendtime ';
			$paras[':pstarttime'] = $pstarttime;
			$paras[':pendtime'] = $pendtime;
		}
	}
	if (empty($fstarttime) || empty($fendtime)) {
		$fstarttime = strtotime('-1 month');
		$fendtime = time();
	}
	if (!empty($_GPC['ftime'])) {
		$fstarttime = strtotime($_GPC['ftime']['start']);
		$fendtime = strtotime($_GPC['ftime']['end']);
		if ($_GPC['fsearchtime'] == '1') {
			$condition .= ' AND o.finishtime >= :fstarttime AND o.finishtime <= :fendtime ';
			$paras[':fstarttime'] = $fstarttime;
			$paras[':fendtime'] = $fendtime;
		}
	}
	if (empty($sstarttime) || empty($sendtime)) {
		$sstarttime = strtotime('-1 month');
		$sendtime = time();
	}
	if (!empty($_GPC['stime'])) {
		$sstarttime = strtotime($_GPC['stime']['start']);
		$sendtime = strtotime($_GPC['stime']['end']);
		if ($_GPC['ssearchtime'] == '1') {
			$condition .= ' AND o.sendtime >= :sstarttime AND o.sendtime <= :sendtime ';
			$paras[':sstarttime'] = $sstarttime;
			$paras[':sendtime'] = $sendtime;
		}
	}
	if ($_GPC['paytype'] != '') {
		if ($_GPC['paytype'] == '2') {
			$condition .= ' AND ( o.paytype =21 or o.paytype=22 or o.paytype=23 )';
		} else {
			$condition .= ' AND o.paytype =' . intval($_GPC['paytype']);
		}
	}
	if (!empty($_GPC['keyword'])) {
		$_GPC['keyword'] = trim($_GPC['keyword']);
		$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
	}
	if (!empty($_GPC['expresssn'])) {
		$_GPC['expresssn'] = trim($_GPC['expresssn']);
		$condition .= " AND o.expresssn LIKE '%{$_GPC['expresssn']}%'";
	}
	if (!empty($_GPC['member'])) {
		$_GPC['member'] = trim($_GPC['member']);
		$condition .= " AND (m.realname LIKE '%{$_GPC['member']}%' or m.mobile LIKE '%{$_GPC['member']}%' or m.nickname LIKE '%{$_GPC['member']}%' " . " or a.realname LIKE '%{$_GPC['member']}%' or a.mobile LIKE '%{$_GPC['member']}%' or o.carrier LIKE '%{$_GPC['member']}%')";
	}
	if (!empty($_GPC['saler'])) {
		$_GPC['saler'] = trim($_GPC['saler']);
		$condition .= " AND (sm.realname LIKE '%{$_GPC['saler']}%' or sm.mobile LIKE '%{$_GPC['saler']}%' or sm.nickname LIKE '%{$_GPC['saler']}%' " . " or s.salername LIKE '%{$_GPC['saler']}%' )";
	}
	if (!empty($_GPC['storeid'])) {
		$_GPC['storeid'] = trim($_GPC['storeid']);
		$condition .= ' AND o.verifystoreid=' . intval($_GPC['storeid']);
	}
	$statuscondition = '';
	if ($status != '') {
		if ($status == -1) {
			ca('order.view.status_1');
		} else {
			ca('order.view.status' . intval($status));
		}
		if ($status == '-1') {
			$statuscondition = ' AND o.status=-1 and o.refundtime=0';
		} else if ($status == '4') {
			$statuscondition = ' AND o.refundid<>0';
		} else if ($status == '5') {
			$statuscondition = ' AND o.refundtime<>0';
		} else if ($status == '1') {
			$statuscondition = ' AND ( o.status = 1 or (o.status=0 and o.paytype=3) )';
		} else if ($status == '0') {
			$statuscondition = ' AND o.status = 0 and o.paytype<>3';
		} else {
			$statuscondition = ' AND o.status = ' . intval($status);
		}
	}
	$bonusagentid = intval($_GPC['bonusagentid']);
	if (!empty($bonusagentid)) {
		$sql = 'select distinct orderid from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $bonusagentid . ' ORDER BY id DESC';
		$bonusoderids = pdo_fetchall($sql);
		$inorderids = "";
		if (!empty($bonusoderids)) {
			foreach ($bonusoderids as $key => $value) {
				if ($key != 0) {
					$inorderids .= ',';
				}
				$inorderids = $value['orderid'];
			}
			$condition .= ' and  o.id in(' . $inorderids . ')';
		} else {
			$condition .= ' and  o.id=0';
		}
	}
	$agentid = intval($_GPC['agentid']);
	$p = p('commission');
	$level = 0;
	if ($p) {
		$cset = $p->getSet();
		$level = intval($cset['level']);
	}
	$olevel = intval($_GPC['olevel']);
	if (!empty($agentid) && $level > 0) {
		$agent = $p->getInfo($agentid, array());
		if (!empty($agent)) {
			$agentLevel = $p->getLevel($agentid);
		}
		if (empty($olevel)) {
			if ($level >= 1) {
				$condition .= ' and  ( o.agentid=' . intval($_GPC['agentid']);
			}
			if ($level >= 2 && $agent['level2'] > 0) {
				$condition .= ' or o.agentid in( ' . implode(',', array_keys($agent['level1_agentids'])) . ')';
			}
			if ($level >= 3 && $agent['level3'] > 0) {
				$condition .= ' or o.agentid in( ' . implode(',', array_keys($agent['level2_agentids'])) . ')';
			}
			if ($level >= 1) {
				$condition .= ')';
			}
		} else {
			if ($olevel == 1) {
				$condition .= ' and  o.agentid=' . intval($_GPC['agentid']);
			} else if ($olevel == 2) {
				if ($agent['level2'] > 0) {
					$condition .= ' and o.agentid in( ' . implode(',', array_keys($agent['level1_agentids'])) . ')';
				} else {
					$condition .= ' and o.agentid in( 0 )';
				}
			} else if ($olevel == 3) {
				if ($agent['level3'] > 0) {
					$condition .= ' and o.agentid in( ' . implode(',', array_keys($agent['level2_agentids'])) . ')';
				} else {
					$condition .= ' and o.agentid in( 0 )';
				}
			}
		}
	}
	if (p('supplier')) {
		$cond = "";
		if ($perm_role == 1) {
			$cond .= " and o.supplier_uid={$_W['uid']} ";
			$supplierapply = pdo_fetchall('select u.uid,p.realname,p.mobile,p.banknumber,p.accountname,p.accountbank,a.applysn,a.apply_money,a.apply_time,a.type,a.finish_time,a.status from ' . tablename('sz_yi_supplier_apply') . ' a ' . ' left join' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid ' . 'left join' . tablename('users') . ' u on a.uid=u.uid where u.uid=' . $_W['uid']);
			$totals['status9'] = count($supplierapply);
			$costmoney = 0;
			$sp_goods = pdo_fetchall('select og.* from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$_W['uid']} and o.status=3 and og.supplier_apply_status=0");
			foreach ($sp_goods as $key => $value) {
				if ($value['goods_op_cost_price'] > 0) {
					$costmoney += $value['goods_op_cost_price'] * $value['total'];
				} elseif ($value['costprice'] > 0) { // 计算成本价
		            $costmoney += $value['costprice'] * $value['total'];
				} else {
					$option = pdo_fetch('select * from ' . tablename('sz_yi_goods_option') . " where uniacid={$_W['uniacid']} and goodsid={$value['goodsid']} and id={$value['optionid']}");
					if ($option['costprice'] > 0) {
						$costmoney += $option['costprice'] * $value['total'];
					} else {
						$goods_info = pdo_fetch('select * from' . tablename('sz_yi_goods') . " where uniacid={$_W['uniacid']} and id={$value['goodsid']}");
						$costmoney += $goods_info['costprice'] * $value['total'];
					}
				}
			}
			$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_perm_user') . ' where uid=:uid and uniacid=:uniacid', array(':uid' => $_W['uid'], ':uniacid' => $_W['uniacid']));
			if (empty($openid)) {
				message('暂未绑定微信，请联系管理员', '', 'error');
			}
			$applytype = intval($_GPC['applytype']);
			if (!empty($applytype)) {
				$mygoodsid = pdo_fetchall('select id from ' . tablename('sz_yi_order_goods') . 'where supplier_uid=:supplier_uid and supplier_apply_status = 0', array(':supplier_uid' => $_W['uid']));
				if (empty($mygoodsid)) {
					message('没有可提现的订单金额');
				}
				$applysn = m('common')->createNO('commission_apply', 'applyno', 'CA');
				// type 1手动2微信3余额
				$data = array('uid' => $_W['uid'], 'apply_money' => $costmoney, 'apply_time' => time(), 'status' => 0, 'type' => $applytype, 'applysn' => $applysn);
				pdo_insert('sz_yi_supplier_apply', $data);
				foreach ($mygoodsid as $ids) {
					$arr = array('supplier_apply_status' => 1);
					pdo_update('sz_yi_order_goods', $arr, array('id' => $ids['id']));
				}
				message('提现申请已提交，请耐心等待!', $this->createPluginWebUrl('suppliermenu/order'), 'success');
			}
		}
	}
	$sql = 'select o.* , a.realname as arealname,a.mobile as amobile,a.province as aprovince ,a.city as acity , a.area as aarea,a.address as aaddress, d.dispatchname,m.nickname,m.id as mid,m.realname as mrealname,m.mobile as mmobile,sm.id as salerid,sm.nickname as salernickname,s.salername from ' . tablename('sz_yi_order') . ' o' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid and m.uniacid =  o.uniacid ' . ' left join ' . tablename('sz_yi_member_address') . ' a on a.id=o.addressid ' . ' left join ' . tablename('sz_yi_dispatch') . ' d on d.id = o.dispatchid ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " where $condition $statuscondition $cond ORDER BY o.createtime DESC,o.status DESC  ";
	if (empty($_GPC['export'])) {
		$sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	}
	$list = pdo_fetchall($sql, $paras);
	$paytype = array('0' => array('css' => 'default', 'name' => '未支付'), '1' => array('css' => 'danger', 'name' => '余额支付'), '11' => array('css' => 'default', 'name' => '后台付款'), '2' => array('css' => 'danger', 'name' => '在线支付'), '21' => array('css' => 'success', 'name' => '微信支付'), '22' => array('css' => 'warning', 'name' => '支付宝支付'), '23' => array('css' => 'warning', 'name' => '银联支付'), '3' => array('css' => 'primary', 'name' => '货到付款'),);
	$orderstatus = array('-1' => array('css' => 'default', 'name' => '已关闭'), '0' => array('css' => 'danger', 'name' => '待付款'), '1' => array('css' => 'info', 'name' => '待发货'), '2' => array('css' => 'warning', 'name' => '待收货'), '3' => array('css' => 'success', 'name' => '已完成'));
	foreach ($list as & $value) {
		$s = $value['status'];
		$pt = $value['paytype'];
		$value['statusvalue'] = $s;
		$value['statuscss'] = $orderstatus[$value['status']]['css'];
		$value['status'] = $orderstatus[$value['status']]['name'];
		if ($pt == 3 && empty($value['statusvalue'])) {
			$value['statuscss'] = $orderstatus[1]['css'];
			$value['status'] = $orderstatus[1]['name'];
		}
		if ($s == 1) {
			if ($value['isverify'] == 1) {
				$value['status'] = '待使用';
			} else if (empty($value['addressid'])) {
				$value['status'] = '待取货';
			}
		}
		if ($s == -1) {
			if (!empty($value['refundtime'])) {
				$value['status'] = '已退款';
			}
		}
		$value['paytypevalue'] = $pt;
		$value['css'] = $paytype[$pt]['css'];
		$value['paytype'] = $paytype[$pt]['name'];
		$value['dispatchname'] = empty($value['addressid']) ? '自提' : $value['dispatchname'];
		if (empty($value['dispatchname'])) {
			$value['dispatchname'] = '快递';
		}
		if ($value['isverify'] == 1) {
			$value['dispatchname'] = '线下核销';
		} else if ($value['isvirtual'] == 1) {
			$value['dispatchname'] = '虚拟物品';
		} else if (!empty($value['virtual'])) {
			$value['dispatchname'] = '虚拟物品(卡密)<br/>自动发货';
		}
		if ($value['dispatchtype'] == 1 || !empty($value['isverify']) || !empty($value['virtual']) || !empty($value['isvirtual'])) {
			$value['address'] = '';
			$carrier = iunserializer($value['carrier']);
			if (is_array($carrier)) {
				$value['addressdata']['realname'] = $value['realname'] = $carrier['carrier_realname'];
				$value['addressdata']['mobile'] = $value['mobile'] = $carrier['carrier_mobile'];
			}
		} else {
			$address = iunserializer($value['address']);
			$isarray = is_array($address);
			$value['realname'] = $isarray ? $address['realname'] : $value['arealname'];
			$value['mobile'] = $isarray ? $address['mobile'] : $value['amobile'];
			$value['province'] = $isarray ? $address['province'] : $value['aprovince'];
			$value['city'] = $isarray ? $address['city'] : $value['acity'];
			$value['area'] = $isarray ? $address['area'] : $value['aarea'];
			$value['address'] = $isarray ? $address['address'] : $value['aaddress'];
			$value['address_province'] = $value['province'];
			$value['address_city'] = $value['city'];
			$value['address_area'] = $value['area'];
			$value['address_address'] = $value['address'];
			$value['address'] = $value['province'] . ' ' . $value['city'] . ' ' . $value['area'] . ' ' . $value['address'];
			$value['addressdata'] = array('realname' => $value['realname'], 'mobile' => $value['mobile'], 'address' => $value['address'],);
		}
		$commission1 = -1;
		$commission2 = -1;
		$commission3 = -1;
		$m1 = false;
		$m2 = false;
		$m3 = false;
		if (!empty($level) && empty($agentid)) {
			if (!empty($value['agentid'])) {
				$m1 = m('member')->getMember($value['agentid']);
				$commission1 = 0;
				if (!empty($m1['agentid'])) {
					$m2 = m('member')->getMember($m1['agentid']);
					$commission2 = 0;
					if (!empty($m2['agentid'])) {
						$m3 = m('member')->getMember($m2['agentid']);
						$commission3 = 0;
					}
				}
			}
		}
		$order_goods = pdo_fetchall('select g.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn,og.productsn as option_productsn, og.total,og.price,og.optionname as optiontitle, og.realprice,og.changeprice,og.oldprice,og.commission1,og.commission2,og.commission3,og.commissions,og.diyformdata,og.diyformfields from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $value['id']));
		$goods = '';
		foreach ($order_goods as & $og) {
			if (!empty($level) && empty($agentid)) {
				$commissions = iunserializer($og['commissions']);
				if (!empty($m1)) {
					if (is_array($commissions)) {
						$commission1 += isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
					} else {
						$c1 = iunserializer($og['commission1']);
						$l1 = $p->getLevel($m1['openid']);
						$commission1 += isset($c1['level' . $l1['id']]) ? $c1['level' . $l1['id']] : $c1['default'];
					}
				}
				if (!empty($m2)) {
					if (is_array($commissions)) {
						$commission2 += isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
					} else {
						$c2 = iunserializer($og['commission2']);
						$l2 = $p->getLevel($m2['openid']);
						$commission2 += isset($c2['level' . $l2['id']]) ? $c2['level' . $l2['id']] : $c2['default'];
					}
				}
				if (!empty($m3)) {
					if (is_array($commissions)) {
						$commission3 += isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
					} else {
						$c3 = iunserializer($og['commission3']);
						$l3 = $p->getLevel($m3['openid']);
						$commission3 += isset($c3['level' . $l3['id']]) ? $c3['level' . $l3['id']] : $c3['default'];
					}
				}
			}
			$goods .= "" . $og['title'] . "";
			if (!empty($og['optiontitle'])) {
				$goods .= ' 规格: ' . $og['optiontitle'];
			}
			if (!empty($og['option_goodssn'])) {
				$og['goodssn'] = $og['option_goodssn'];
			}
			if (!empty($og['option_productsn'])) {
				$og['productsn'] = $og['option_productsn'];
			}
			if (!empty($og['goodssn'])) {
				$goods .= ' 商品编号: ' . $og['goodssn'];
			}
			if (!empty($og['productsn'])) {
				$goods .= ' 商品条码: ' . $og['productsn'];
			}
			$goods .= ' 单价: ' . ($og['price'] / $og['total']) . ' 折扣后: ' . ($og['realprice'] / $og['total']) . ' 数量: ' . $og['total'] . ' 总价: ' . $og['price'] . ' 折扣后: ' . $og['realprice'] . "";
			if ($plugin_diyform && !empty($og['diyformfields']) && !empty($og['diyformdata'])) {
				$diyformdata_array = $plugin_diyform->getDatas(iunserializer($og['diyformfields']), iunserializer($og['diyformdata']));
				$diyformdata = "";
				foreach ($diyformdata_array as $da) {
					$diyformdata .= $da['name'] . ': ' . $da['value'] . "";
				}
				$og['goods_diyformdata'] = $diyformdata;
			}
		}
		unset($og);
		if (!empty($level) && empty($agentid)) {
			$value['commission1'] = $commission1;
			$value['commission2'] = $commission2;
			$value['commission3'] = $commission3;
		}
		$value['goods'] = set_medias($order_goods, 'thumb');
		$value['goods_str'] = $goods;
		if (!empty($agentid) && $level > 0) {
			$commission_level = 0;
			if ($value['agentid'] == $agentid) {
				$value['level'] = 1;
				$level1_commissions = pdo_fetchall('select commission1,commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.orderid=:orderid and o.agentid= ' . $agentid . '  and o.uniacid=:uniacid', array(':orderid' => $value['id'], ':uniacid' => $_W['uniacid']));
				foreach ($level1_commissions as $c) {
					$commission = iunserializer($c['commission1']);
					$commissions = iunserializer($c['commissions']);
					if (empty($commissions)) {
						$commission_level += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
					} else {
						$commission_level += isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
					}
				}
			} else if (in_array($value['agentid'], array_keys($agent['level1_agentids']))) {
				$value['level'] = 2;
				if ($agent['level2'] > 0) {
					$level2_commissions = pdo_fetchall('select commission2,commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.orderid=:orderid and  o.agentid in ( ' . implode(',', array_keys($agent['level1_agentids'])) . ')  and o.uniacid=:uniacid', array(':orderid' => $value['id'], ':uniacid' => $_W['uniacid']));
					foreach ($level2_commissions as $c) {
						$commission = iunserializer($c['commission2']);
						$commissions = iunserializer($c['commissions']);
						if (empty($commissions)) {
							$commission_level += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
						} else {
							$commission_level += isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
						}
					}
				}
			} else if (in_array($value['agentid'], array_keys($agent['level2_agentids']))) {
				$value['level'] = 3;
				if ($agent['level3'] > 0) {
					$level3_commissions = pdo_fetchall('select commission3,commissions from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where og.orderid=:orderid and  o.agentid in ( ' . implode(',', array_keys($agent['level2_agentids'])) . ')  and o.uniacid=:uniacid', array(':orderid' => $value['id'], ':uniacid' => $_W['uniacid']));
					foreach ($level3_commissions as $c) {
						$commission = iunserializer($c['commission3']);
						$commissions = iunserializer($c['commissions']);
						if (empty($commissions)) {
							$commission_level += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
						} else {
							$commission_level += isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
						}
					}
				}
			}
			$value['commission'] = $commission_level;
		}
	}
	unset($value);
	if ($_GPC['export'] == 1) {
		ca('order.op.export');
		plog('order.op.export', '导出订单');
		$columns = array(array('title' => '订单编号', 'field' => 'ordersn', 'width' => 24), array('title' => '粉丝昵称', 'field' => 'nickname', 'width' => 12), array('title' => '会员姓名', 'field' => 'mrealname', 'width' => 12), array('title' => '会员手机手机号', 'field' => 'mmobile', 'width' => 12), array('title' => '收货姓名(或自提人)', 'field' => 'realname', 'width' => 12), array('title' => '联系电话', 'field' => 'mobile', 'width' => 12), array('title' => '收货地址', 'field' => 'address_province', 'width' => 12), array('title' => '', 'field' => 'address_city', 'width' => 12), array('title' => '', 'field' => 'address_area', 'width' => 12), array('title' => '', 'field' => 'address_address', 'width' => 12), array('title' => '商品名称', 'field' => 'goods_title', 'width' => 24), array('title' => '商品编码', 'field' => 'goods_goodssn', 'width' => 12), array('title' => '商品规格', 'field' => 'goods_optiontitle', 'width' => 12), array('title' => '商品数量', 'field' => 'goods_total', 'width' => 12), array('title' => '商品单价(折扣前)', 'field' => 'goods_price1', 'width' => 12), array('title' => '商品单价(折扣后)', 'field' => 'goods_price2', 'width' => 12), array('title' => '商品价格(折扣后)', 'field' => 'goods_rprice1', 'width' => 12), array('title' => '商品价格(折扣后)', 'field' => 'goods_rprice2', 'width' => 12), array('title' => '支付方式', 'field' => 'paytype', 'width' => 12), array('title' => '配送方式', 'field' => 'dispatchname', 'width' => 12), array('title' => '商品小计', 'field' => 'goodsprice', 'width' => 12), array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12), array('title' => '积分抵扣', 'field' => 'deductprice', 'width' => 12), array('title' => '余额抵扣', 'field' => 'deductcredit2', 'width' => 12), array('title' => '满额立减', 'field' => 'deductenough', 'width' => 12), array('title' => '优惠券优惠', 'field' => 'couponprice', 'width' => 12), array('title' => '订单改价', 'field' => 'changeprice', 'width' => 12), array('title' => '运费改价', 'field' => 'changedispatchprice', 'width' => 12), array('title' => '应收款', 'field' => 'price', 'width' => 12), array('title' => '状态', 'field' => 'status', 'width' => 12), array('title' => '下单时间', 'field' => 'createtime', 'width' => 24), array('title' => '付款时间', 'field' => 'paytime', 'width' => 24), array('title' => '发货时间', 'field' => 'sendtime', 'width' => 24), array('title' => '完成时间', 'field' => 'finishtime', 'width' => 24), array('title' => '快递公司', 'field' => 'expresscom', 'width' => 24), array('title' => '快递单号', 'field' => 'expresssn', 'width' => 24), array('title' => '订单备注', 'field' => 'remark', 'width' => 36), array('title' => '核销员', 'field' => 'salerinfo', 'width' => 24), array('title' => '核销门店', 'field' => 'storeinfo', 'width' => 36), array('title' => '订单自定义信息', 'field' => 'order_diyformdata', 'width' => 36), array('title' => '商品自定义信息', 'field' => 'goods_diyformdata', 'width' => 36),);
		if (!empty($agentid) && $level > 0) {
			$columns[] = array('title' => '分销级别', 'field' => 'level', 'width' => 24);
			$columns[] = array('title' => '分销佣金', 'field' => 'commission', 'width' => 24);
		}
		foreach ($list as & $row) {
			$row['ordersn'] = $row['ordersn'] . ' ';
			if ($row['deductprice'] > 0) {
				$row['deductprice'] = '-' . $row['deductprice'];
			}
			if ($row['deductcredit2'] > 0) {
				$row['deductcredit2'] = '-' . $row['deductcredit2'];
			}
			if ($row['deductenough'] > 0) {
				$row['deductenough'] = '-' . $row['deductenough'];
			}
			if ($row['changeprice'] < 0) {
				$row['changeprice'] = '-' . $row['changeprice'];
			} else if ($row['changeprice'] > 0) {
				$row['changeprice'] = '+' . $row['changeprice'];
			}
			if ($row['changedispatchprice'] < 0) {
				$row['changedispatchprice'] = '-' . $row['changedispatchprice'];
			} else if ($row['changedispatchprice'] > 0) {
				$row['changedispatchprice'] = '+' . $row['changedispatchprice'];
			}
			if ($row['couponprice'] > 0) {
				$row['couponprice'] = '-' . $row['couponprice'];
			}
			$row['expresssn'] = $row['expresssn'] . ' ';
			$row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
			$row['paytime'] = !empty($row['paytime']) ? date('Y-m-d H:i:s', $row['paytime']) : '';
			$row['sendtime'] = !empty($row['sendtime']) ? date('Y-m-d H:i:s', $row['sendtime']) : '';
			$row['finishtime'] = !empty($row['finishtime']) ? date('Y-m-d H:i:s', $row['finishtime']) : '';
			$row['salerinfo'] = "";
			$row['storeinfo'] = "";
			if (!empty($row['verifyopenid'])) {
				$row['salerinfo'] = '[' . $row['salerid'] . ']' . $row['salername'] . '(' . $row['salernickname'] . ')';
			}
			if (!empty($row['verifystoreid'])) {
				$row['storeinfo'] = pdo_fetchcolumn('select storename from ' . tablename('sz_yi_store') . ' where id=:storeid limit 1 ', array(':storeid' => $row['verifystoreid']));
			}
			if ($plugin_diyform && !empty($row['diyformfields']) && !empty($row['diyformdata'])) {
				$diyformdata_array = p('diyform')->getDatas(iunserializer($row['diyformfields']), iunserializer($row['diyformdata']));
				$diyformdata = "";
				foreach ($diyformdata_array as $da) {
					$diyformdata .= $da['name'] . ': ' . $da['value'] . "";
				}
				$row['order_diyformdata'] = $diyformdata;
			}
		}
		unset($row);
		$exportlist = array();
		foreach ($list as & $r) {
			$ogoods = $r['goods'];
			unset($r['goods']);
			foreach ($ogoods as $k => $g) {
				if ($k > 0) {
					$r['ordersn'] = '';
					$r['realname'] = '';
					$r['mobile'] = '';
					$r['nickname'] = '';
					$r['mrealname'] = '';
					$r['mmobile'] = '';
					$r['address'] = '';
					$r['address_province'] = '';
					$r['address_city'] = '';
					$r['address_area'] = '';
					$r['address_address'] = '';
					$r['paytype'] = '';
					$r['dispatchname'] = '';
					$r['dispatchprice'] = '';
					$r['goodsprice'] = '';
					$r['status'] = '';
					$r['createtime'] = '';
					$r['sendtime'] = '';
					$r['finishtime'] = '';
					$r['expresscom'] = '';
					$r['expresssn'] = '';
					$r['deductprice'] = '';
					$r['deductcredit2'] = '';
					$r['deductenough'] = '';
					$r['changeprice'] = '';
					$r['changedispatchprice'] = '';
					$r['price'] = '';
					$r['order_diyformdata'] = '';
				}
				$r['goods_title'] = $g['title'];
				$r['goods_goodssn'] = $g['goodssn'];
				$r['goods_optiontitle'] = $g['optiontitle'];
				$r['goods_total'] = $g['total'];
				$r['goods_price1'] = $g['price'] / $g['total'];
				$r['goods_price2'] = $g['realprice'] / $g['total'];
				$r['goods_rprice1'] = $g['price'];
				$r['goods_rprice2'] = $g['realprice'];
				$r['goods_diyformdata'] = $g['goods_diyformdata'];
				$exportlist[] = $r;
			}
		}
		unset($r);
		m('excel')->export($exportlist, array('title' => '订单数据-' . date('Y-m-d-H-i', time()), 'columns' => $columns));
	}
	$totalmoney = pdo_fetchcolumn('SELECT ifnull(sum(o.price),0) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition $statuscondition $cond ", $paras);
	$totals['all'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE o.uniacid = :uniacid and o.deleted=0 $cond ", $paras);
	$totals['status_1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=-1 and o.refundtime=0 $cond ", $paras);
	$totals['status0'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=0 and o.paytype<>3 $cond ", $paras);
	$totals['status1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and ( o.status=1 or ( o.status=0 and o.paytype=3) ) $cond ", $paras);
	$totals['status2'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=2 $cond ", $paras);
	$totals['status3'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=3 $cond ", $paras);
	$totals['status4'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.refundid<>0 $cond ", $paras);
	$totals['status5'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id  order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.refundtime<>0 $cond ", $paras);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition $statuscondition " . $cond, $paras);
	$pager = pagination($total, $pindex, $psize);
	$stores = pdo_fetchall('select id,storename from ' . tablename('sz_yi_store') . ' where uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));
	load()->func('tpl');
	include $this->template('order/list');
	exit;
} elseif ($operation == 'detail') {
	$id = intval($_GPC['id']);
	$p = p('commission');
	$item = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
	$item['statusvalue'] = $item['status'];
	$shopset = m('common')->getSysset('shop');
	if (empty($item)) {
		message('抱歉，订单不存在!', referer(), 'error');
	}
	if (!empty($item['refundid'])) {
		ca('order.view.status4');
	} else {
		if ($item['status'] == -1) {
			ca('order.view.status_1');
		} else {
			ca('order.view.status' . $item['status']);
		}
	}
	if ($_W['ispost']) {
		pdo_update('sz_yi_order', array('remark' => trim($_GPC['remark']),), array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
		plog('order.op.saveremark', "订单保存备注  ID: {$item['id']} 订单号: {$item['ordersn']}");
		message('订单备注保存成功！', $this->createWebUrl('order', array('op' => 'detail', 'id' => $item['id'])), 'success');
	}
	$member = m('member')->getMember($item['openid']);
	$dispatch = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_dispatch') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $item['dispatchid'], ':uniacid' => $_W['uniacid']));
	if (empty($item['addressid'])) {
		$user = unserialize($item['carrier']);
	} else {
		$user = iunserializer($item['address']);
		if (!is_array($user)) {
			$user = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_member_address') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $item['addressid'], ':uniacid' => $_W['uniacid']));
		}
		$address_info = $user['address'];
		$user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['address'];
		$item['addressdata'] = array('realname' => $user['realname'], 'mobile' => $user['mobile'], 'address' => $user['address'],);
	}
	$refund = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order_refund') . ' WHERE orderid = :orderid and uniacid=:uniacid order by id desc', array(':orderid' => $item['id'], ':uniacid' => $_W['uniacid']));
	$diyformfields = "";
	$plugin_diyform = p('diyform');
	if ($plugin_diyform) {
		$diyformfields = ',diyformfields,diyformdata';
	}
	$goods = pdo_fetchall("SELECT g.*, o.goodssn as option_goodssn, o.productsn as option_productsn,o.total,g.type,o.optionname,o.optionid,o.price as orderprice,o.realprice,o.changeprice,o.oldprice,o.commission1,o.commission2,o.commission3,o.commissions{$diyformfields} FROM " . tablename('sz_yi_order_goods') . ' o left join ' . tablename('sz_yi_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.uniacid=:uniacid', array(':orderid' => $id, ':uniacid' => $_W['uniacid']));
	foreach ($goods as & $r) {
		if (!empty($r['option_goodssn'])) {
			$r['goodssn'] = $og['option_goodssn'];
		}
		if (!empty($og['option_productsn'])) {
			$r['productsn'] = $og['option_productsn'];
		}
		if ($plugin_diyform) {
			$r['diyformfields'] = iunserializer($r['diyformfields']);
			$r['diyformdata'] = iunserializer($r['diyformdata']);
		}
	}
	unset($r);
	$item['goods'] = $goods;
	$agents = array();
	if ($p) {
		$agents = $p->getAgents($id);
		$m1  = isset($agents[0]) ? $agents[0] : false;
		$m2  = isset($agents[1]) ? $agents[1] : false;
		$m3  = isset($agents[2]) ? $agents[2] : false;
		$m4  = isset($agents[3]) ? $agents[3] : false;
		$m5  = isset($agents[4]) ? $agents[4] : false;
		$m6  = isset($agents[5]) ? $agents[5] : false;
		$m7  = isset($agents[6]) ? $agents[6] : false;
		$m8  = isset($agents[7]) ? $agents[7] : false;
		$m9  = isset($agents[8]) ? $agents[8] : false;
		$m10 = isset($agents[9]) ? $agents[9] : false;
		$m11 = isset($agents[10]) ? $agents[10] : false;
		$m12 = isset($agents[11]) ? $agents[11] : false;
		$m13 = isset($agents[12]) ? $agents[12] : false;
		$m14 = isset($agents[13]) ? $agents[13] : false;
		$m15 = isset($agents[14]) ? $agents[14] : false;
		$commission1 = 0;
		$commission2 = 0;
		$commission3 = 0;
		$commission4 = 0;
		$commission5 = 0;
		$commission6 = 0;
		$commission7 = 0;
		$commission8 = 0;
		$commission9 = 0;
		$commission10 = 0;
		$commission11 = 0;
		$commission12 = 0;
		$commission13 = 0;
		$commission14 = 0;
		$commission15 = 0;
		foreach ($goods as & $og) {
			$oc1 = 0;
			$oc2 = 0;
			$oc3 = 0;
			$oc4 = 0;
			$oc5 = 0;
			$oc6 = 0;
			$oc7 = 0;
			$oc8 = 0;
			$oc9 = 0;
			$oc10 = 0;
			$oc11 = 0;
			$oc12 = 0;
			$oc13 = 0;
			$oc14 = 0;
			$oc15 = 0;
			$commissions = iunserializer($og['commissions']);
			if (!empty($m1)) {
				if (is_array($commissions)) {
					$oc1 = isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
				} else {
					
					$c1 = iunserializer($og['commission1']);
					$l1 = $p->getLevel($m1['openid']);
					$oc1 = isset($c1['level' . $l1['id']]) ? $c1['level' . $l1['id']] : $c1['default'];
				}
				$og['oc1'] = $oc1;
				$commission1 += $oc1;
			}
			if (!empty($m2)) {
				if (is_array($commissions)) {
					$oc2 = isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
				} else {
					$c2 = iunserializer($og['commission2']);
					$l2 = $p->getLevel($m2['openid']);
					$oc2 = isset($c2['level' . $l2['id']]) ? $c2['level' . $l2['id']] : $c2['default'];
				}
				$og['oc2'] = $oc2;
				$commission2 += $oc2;
			}
			if (!empty($m3)) {
				if (is_array($commissions)) {
					
					$oc3 = isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
				} else {
					$c3 = iunserializer($og['commission3']);
					$l3 = $p->getLevel($m3['openid']);
					$oc3 = isset($c3['level' . $l3['id']]) ? $c3['level' . $l3['id']] : $c3['default'];
				}
				$og['oc3'] = $oc3;
				$commission3 += $oc3;
			}
			if (!empty($m4)) {
				if (is_array($commissions)) {
					$oc4 = isset($commissions['level4']) ? floatval($commissions['level4']) : 0;
				} else {
					$c4 = iunserializer($og['commission4']);
					$l4 = $p->getLevel($m4['openid']);
					$oc4 = isset($c4['level' . $l4['id']]) ? $c4['level' . $l4['id']] : $c4['default'];
				}
				$og['oc4'] = $oc4;
				$commission4 += $oc4;
			}
			if (!empty($m5)) {
				if (is_array($commissions)) {
					$oc5 = isset($commissions['level5']) ? floatval($commissions['level5']) : 0;
				} else {
					$c5 = iunserializer($og['commission5']);
					$l5 = $p->getLevel($m5['openid']);
					$oc5 = isset($c5['level' . $l5['id']]) ? $c5['level' . $l5['id']] : $c5['default'];
				}
				$og['oc5'] = $oc5;
				$commission5 += $oc5;
			}
			if (!empty($m6)) {
				if (is_array($commissions)) {
					$oc6 = isset($commissions['level6']) ? floatval($commissions['level6']) : 0;
				} else {
					$c6 = iunserializer($og['commission6']);
					$l6 = $p->getLevel($m6['openid']);
					$oc6 = isset($c6['level' . $l6['id']]) ? $c6['level' . $l6['id']] : $c6['default'];
				}
				$og['oc6'] = $oc6;
				$commission6 += $oc6;
			}
			if (!empty($m7)) {
				if (is_array($commissions)) {
					$oc7 = isset($commissions['level7']) ? floatval($commissions['level7']) : 0;
				} else {
					$c7 = iunserializer($og['commission7']);
					$l7 = $p->getLevel($m7['openid']);
					$oc7 = isset($c7['level' . $l7['id']]) ? $c7['level' . $l7['id']] : $c7['default'];
				}
				$og['oc7'] = $oc7;
				$commission7 += $oc7;
			}
			if (!empty($m8)) {
				if (is_array($commissions)) {
					$oc8 = isset($commissions['level8']) ? floatval($commissions['level8']) : 0;
				} else {
					$c8 = iunserializer($og['commission8']);
					$l8 = $p->getLevel($m8['openid']);
					$oc8 = isset($c8['level' . $l8['id']]) ? $c8['level' . $l8['id']] : $c8['default'];
				}
				$og['oc8'] = $oc8;
				$commission8 += $oc8;
			}
			if (!empty($m9)) {
				if (is_array($commissions)) {
					$oc9 = isset($commissions['level9']) ? floatval($commissions['level9']) : 0;
				} else {
					$c9 = iunserializer($og['commission9']);
					$l9 = $p->getLevel($m9['openid']);
					$oc9 = isset($c9['level' . $l9['id']]) ? $c9['level' . $l9['id']] : $c9['default'];
				}
				$og['oc9'] = $oc9;
				$commission9 += $oc9;
			}
			if (!empty($m10)) {
				if (is_array($commissions)) {
					$oc10 = isset($commissions['level10']) ? floatval($commissions['level10']) : 0;
				} else {
					$c10 = iunserializer($og['commission10']);
					$l10 = $p->getLevel($m10['openid']);
					$oc10 = isset($c10['level' . $l10['id']]) ? $c10['level' . $l10['id']] : $c10['default'];
				}
				$og['oc10'] = $oc10;
				$commission10 += $oc10;
			}
			if (!empty($m11)) {
				if (is_array($commissions)) {
					$oc11 = isset($commissions['level11']) ? floatval($commissions['level11']) : 0;
				} else {
					$c11 = iunserializer($og['commission11']);
					$l11 = $p->getLevel($m11['openid']);
					$oc11 = isset($c11['level' . $l11['id']]) ? $c11['level' . $l11['id']] : $c11['default'];
				}
				$og['oc11'] = $oc11;
				$commission11 += $oc11;
			}
			if (!empty($m12)) {
				if (is_array($commissions)) {
					$oc12 = isset($commissions['level12']) ? floatval($commissions['level12']) : 0;
				} else {
					$c12 = iunserializer($og['commission12']);
					$l12 = $p->getLevel($m12['openid']);
					$oc12 = isset($c12['level' . $l12['id']]) ? $c12['level' . $l12['id']] : $c12['default'];
				}
				$og['oc12'] = $oc12;
				$commission12 += $oc12;
			}
			if (!empty($m13)) {
				if (is_array($commissions)) {
					$oc13 = isset($commissions['level13']) ? floatval($commissions['level13']) : 0;
				} else {
					$c13 = iunserializer($og['commission13']);
					$l13 = $p->getLevel($m13['openid']);
					$oc13 = isset($c13['level' . $l13['id']]) ? $c13['level' . $l13['id']] : $c13['default'];
				}
				$og['oc13'] = $oc13;
				$commission13 += $oc13;
			}
			if (!empty($m14)) {
				if (is_array($commissions)) {
					$oc14 = isset($commissions['level14']) ? floatval($commissions['level14']) : 0;
				} else {
					$c14 = iunserializer($og['commission14']);
					$l14 = $p->getLevel($m14['openid']);
					$oc14 = isset($c14['level' . $l14['id']]) ? $c14['level' . $l14['id']] : $c14['default'];
				}
				$og['oc14'] = $oc14;
				$commission14 += $oc14;
			}
			if (!empty($m15)) {
				if (is_array($commissions)) {
					$oc15 = isset($commissions['level15']) ? floatval($commissions['level15']) : 0;
				} else {
					$c15 = iunserializer($og['commission15']);
					$l15 = $p->getLevel($m15['openid']);
					$oc15 = isset($c15['level' . $l15['id']]) ? $c15['level' . $l15['id']] : $c15['default'];
				}
				$og['oc15'] = $oc15;
				$commission15 += $oc15;
			}
		}
		unset($og);
	}
	$_W['uid'] && $cond = ' and o.supplier_uid = '.$_W['uid'];
	$condition = ' o.uniacid=:uniacid and o.deleted=0'.$cond;
	$paras = array(':uniacid' => $_W['uniacid']);
	$totals = array();
	$totals['all'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition", $paras);
	$totals['status_1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=-1 and o.refundtime=0", $paras);
	$totals['status0'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=0 and o.paytype<>3", $paras);
	$totals['status1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and ( o.status=1 or ( o.status=0 and o.paytype=3) )", $paras);
	$totals['status2'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=2", $paras);
	$totals['status3'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=3", $paras);
	$totals['status4'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.refundid<>0 and r.status=0", $paras);
	$totals['status5'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.refundtime<>0", $paras);
	$coupon = false;
	if (p('coupon') && !empty($item['couponid'])) {
		$coupon = p('coupon')->getCouponByDataID($item['couponid']);
	}
	if (p('verify')) {
		if (!empty($item['verifyopenid'])) {
			$saler = m('member')->getMember($item['verifyopenid']);
			$saler['salername'] = pdo_fetchcolumn('select salername from ' . tablename('sz_yi_saler') . ' where openid=:openid and uniacid=:uniacid limit 1 ', array(':uniacid' => $_W['uniacid'], ':openid' => $item['verifyopenid']));
		}
		if (!empty($item['verifystoreid'])) {
			$store = pdo_fetch('select * from ' . tablename('sz_yi_store') . ' where id=:storeid limit 1 ', array(':storeid' => $item['verifystoreid']));
		}
	}
	$show = 1;
	$diyform_flag = 0;
	$diyform_plugin = p('diyform');
	$order_fields = false;
	$order_data = false;
	if ($diyform_plugin) {
		$diyform_set = $diyform_plugin->getSet();
		foreach ($goods as $g) {
			if (!empty($g['diyformdata'])) {
				$diyform_flag = 1;
				break;
			}
		}
		if (!empty($item['diyformid'])) {
			$orderdiyformid = $item['diyformid'];
			if (!empty($orderdiyformid)) {
				$diyform_flag = 1;
				$order_fields = iunserializer($item['diyformfields']);
				$order_data = iunserializer($item['diyformdata']);
			}
		}
	}
	load()->func('tpl');
	include $this->template('order/detail');
	exit;
} elseif ($operation == 'saveaddress') {
	$provance = $_GPC['provance'];
	$realname = $_GPC['realname'];
	$mobile = $_GPC['mobile'];
	$city = $_GPC['city'];
	$area = $_GPC['area'];
	$address = trim($_GPC['address']);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		if (empty($realname)) {
			$ret = '请填写收件人姓名！';
			show_json(0, $ret);
		}
		if (empty($mobile)) {
			$ret = '请填写收件人手机！';
			show_json(0, $ret);
		}
		if ($provance == '请选择省份') {
			$ret = '请选择省份！';
			show_json(0, $ret);
		}
		if (empty($address)) {
			$ret = '请填写详细地址！';
			show_json(0, $ret);
		}
		$item = pdo_fetch('SELECT address FROM ' . tablename('sz_yi_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
		$address_array = iunserializer($item['address']);
		$address_array['realname'] = $realname;
		$address_array['mobile'] = $mobile;
		$address_array['provance'] = $provance;
		$address_array['city'] = $city;
		$address_array['area'] = $area;
		$address_array['address'] = $address;
		$address_array = iserializer($address_array);
		pdo_update('sz_yi_order', array('address' => $address_array), array('id' => $id, 'uniacid' => $_W['uniacid']));
		$ret = '修改成功';
		show_json(1, $ret);
	} else {
		$ret = 'Url参数错误！请重试！';
		show_json(0, $ret);
	}
} elseif ($operation == 'delete') {
	ca('order.op.delete');
	$orderid = intval($_GPC['id']);
	pdo_update('sz_yi_order', array('deleted' => 1), array('id' => $orderid, 'uniacid' => $_W['uniacid']));
	plog('order.op.delete', "订单删除 ID: {$id}");
	message('订单删除成功', $this->createWebUrl('order', array('op' => 'display')), 'success');
} elseif ($operation == 'deal') {
	$id = intval($_GPC['id']);
	$item = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_order') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));
	$shopset = m('common')->getSysset('shop');
	if (empty($item)) {
		message('抱歉，订单不存在!', referer(), 'error');
	}
	if (!empty($item['refundid'])) {
		ca('order.view.status4');
	} else {
		if ($item['status'] == -1) {
			ca('order.view.status_1');
		} else {
			ca('order.view.status' . $item['status']);
		}
	}
	$to = trim($_GPC['to']);
	if ($to == 'confirmpay') {
		zymfunc_5($item);
	} else if ($to == 'cancelpay') {
		order_list_cancelpay($item);
	} else if ($to == 'confirmsend') {
		zymfunc_3($item);
	} else if ($to == 'cancelsend') {
		zymfunc_4($item);
	} else if ($to == 'confirmsend1') {
		zymfunc_1($item);
	} else if ($to == 'cancelsend1') {
		zymfunc_2($item);
	} else if ($to == 'finish') {
		order_list_finish($item);
	} else if ($to == 'close') {
		order_list_close($item);
	} else if ($to == 'refund') {
		order_list_refund($item);
	} else if ($to == 'changepricemodal') {
		if (!empty($item['status'])) {
			exit('-1');
		}
		$order_goods = pdo_fetchall('select og.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn,og.productsn as option_productsn, og.total,og.price,og.optionname as optiontitle, og.realprice,og.oldprice from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $item['id']));
		if (empty($item['addressid'])) {
			$user = unserialize($item['carrier']);
			$item['addressdata'] = array('realname' => $user['carrier_realname'], 'mobile' => $user['carrier_mobile']);
		} else {
			$user = iunserializer($item['address']);
			if (!is_array($user)) {
				$user = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_member_address') . ' WHERE id = :id and uniacid=:uniacid', array(':id' => $item['addressid'], ':uniacid' => $_W['uniacid']));
			}
			$user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['address'];
			$item['addressdata'] = array('realname' => $user['realname'], 'mobile' => $user['mobile'], 'address' => $user['address'],);
		}
		load()->func('tpl');
		include $this->template('order/changeprice');
		exit;
	} else if ($to == 'confirmchangeprice') {
		$changegoodsprice = $_GPC['changegoodsprice'];
		if (!is_array($changegoodsprice)) {
			message('未找到改价内容!', '', 'error');
		}
		$changeprice = 0;
		foreach ($changegoodsprice as $ogid => $change) {
			$changeprice += floatval($change);
		}
		$dispatchprice = floatval($_GPC['changedispatchprice']);
		if ($dispatchprice < 0) {
			$dispatchprice = 0;
		}
		$orderprice = $item['price'] + $changeprice;
		$changedispatchprice = 0;
		if ($dispatchprice != $item['dispatchprice']) {
			$changedispatchprice = $dispatchprice - $item['dispatchprice'];
			$orderprice += $changedispatchprice;
		}
		if ($orderprice < 0) {
			message('订单实际支付价格不能小于0元！', '', 'error');
		}
		foreach ($changegoodsprice as $ogid => $change) {
			$og = pdo_fetch('select price,realprice from ' . tablename('sz_yi_order_goods') . ' where id=:ogid and uniacid=:uniacid limit 1', array(':ogid' => $ogid, ':uniacid' => $_W['uniacid']));
			if (!empty($og)) {
				$realprice = $og['realprice'] + $change;
				if ($realprice < 0) {
					message('单个商品不能优惠到负数', '', 'error');
				}
			}
		}
		$ordersn2 = $item['ordersn2'] + 1;
		if ($ordersn2 > 99) {
			message('超过改价次数限额', '', 'error');
		}
		$orderupdate = array();
		if ($orderprice != $item['price']) {
			$orderupdate['price'] = $orderprice;
			$orderupdate['ordersn2'] = $item['ordersn2'] + 1;
		}
		$orderupdate['changeprice'] = $item['changeprice'] + $changeprice;
		if ($dispatchprice != $item['dispatchprice']) {
			$orderupdate['dispatchprice'] = $dispatchprice;
			$orderupdate['changedispatchprice'] += $changedispatchprice;
		}
		if (!empty($orderupdate)) {
			pdo_update('sz_yi_order', $orderupdate, array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
		}
		foreach ($changegoodsprice as $ogid => $change) {
			$og = pdo_fetch('select price,realprice,changeprice from ' . tablename('sz_yi_order_goods') . ' where id=:ogid and uniacid=:uniacid limit 1', array(':ogid' => $ogid, ':uniacid' => $_W['uniacid']));
			if (!empty($og)) {
				$realprice = $og['realprice'] + $change;
				$changeprice = $og['changeprice'] + $change;
				pdo_update('sz_yi_order_goods', array('realprice' => $realprice, 'changeprice' => $changeprice), array('id' => $ogid));
			}
		}
		if (abs($changeprice) > 0) {
			$pluginc = p('commission');
			if ($pluginc) {
				$pluginc->calculate($item['id'], true);
			}
		}
		plog('order.op.changeprice', "订单号： {$item['ordersn']} <br/> 价格： {$item['price']} -> {$orderprice}");
		message('订单改价成功!', referer(), 'success');
	} else if ($to == 'express') {
		$express = trim($item['express']);
		$expresssn = trim($item['expresssn']);
		$arr = getList($express, $expresssn);
		if (!$arr) {
			$arr = getList($express, $expresssn);
			if (!$arr) {
				die('未找到物流信息.');
			}
		}
		$len = count($arr);
		$step1 = explode('<br />', str_replace('&middot;', "", $arr[0]));
		$step2 = explode('<br />', str_replace('&middot;', "", $arr[$len - 1]));
		for ($i = 0; $i < $len; $i++) {
			if (strtotime(trim($step1[0])) > strtotime(trim($step2[0]))) {
				$row = $arr[$i];
			} else {
				$row = $arr[$len - $i - 1];
			}
			$step = explode('<br />', str_replace('&middot;', "", $row));
			$list[] = array('time' => trim($step[0]), 'step' => trim($step[1]), 'ts' => strtotime(trim($step[0])));
		}
		load()->func('tpl');
		include $this->template('order/express');
		exit;
	}
	exit;
} elseif ($operation == 'apply') {
	// 查询提现权限 1是供应商 2是商家
	$type = pdo_fetchcolumn(' select type from  '.tablename('sz_yi_perm_user')." where uid = :uid limit 1 ", array(':uid' => $_W['uid']));
	$set = p('supplier')->getSet();
	if ($type == 1) {
	    $authority = $set['power'];
	} elseif ($type == 2) {
	    $authority = $set['storepower'];
	}
	$condition = ' o.uniacid = :uniacid and o.deleted=0';
	$paras = $paras1 = array(':uniacid' => $_W['uniacid']);
	$cond  = " and o.supplier_uid={$_W['uid']} ";
	$totalmoney = pdo_fetchcolumn('SELECT ifnull(sum(o.price),0) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition $statuscondition $cond ", $paras);
	$totals['all'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE o.uniacid = :uniacid and o.deleted=0 $cond ", $paras);
	$totals['status_1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=-1 and o.refundtime=0 $cond ", $paras);
	$totals['status0'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=0 and o.paytype<>3 $cond ", $paras);
	$totals['status1'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and ( o.status=1 or ( o.status=0 and o.paytype=3) ) $cond ", $paras);
	$totals['status2'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=2 $cond ", $paras);
	$totals['status3'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.status=3 $cond ", $paras);
	$totals['status4'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.refundid<>0 $cond ", $paras);
	$totals['status5'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id  order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition and o.refundtime<>0 $cond ", $paras);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' o ' . ' left join ( select rr.id,rr.orderid,rr.status from ' . tablename('sz_yi_order_refund') . ' rr left join ' . tablename('sz_yi_order') . ' ro on rr.orderid =ro.id order by rr.id desc limit 1) r on r.orderid= o.id' . ' left join ' . tablename('sz_yi_member') . ' m on m.openid=o.openid  and m.uniacid =  o.uniacid' . ' left join ' . tablename('sz_yi_member_address') . ' a on o.addressid = a.id ' . ' left join ' . tablename('sz_yi_member') . ' sm on sm.openid = o.verifyopenid and sm.uniacid=o.uniacid' . ' left join ' . tablename('sz_yi_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' . " WHERE $condition $statuscondition " . $cond, $paras);
	$costmoney = 0;
	$sp_goods = pdo_fetchall('select og.* from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . " o on (o.id=og.orderid) where og.uniacid={$_W['uniacid']} and og.supplier_uid={$_W['uid']} and o.status=3 and og.supplier_apply_status=0");
	foreach ($sp_goods as $key => $value) {
		if ($value['goods_op_cost_price'] > 0) {
			$costmoney += $value['goods_op_cost_price'] * $value['total'];
		} elseif ($value['costprice'] > 0) { // 计算成本价
            $costmoney += $value['costprice'] * $value['total'];
        }  else {
			$option = pdo_fetch('select * from ' . tablename('sz_yi_goods_option') . " where uniacid={$_W['uniacid']} and goodsid={$value['goodsid']} and id={$value['optionid']}");
			if ($option['costprice'] > 0) {
				$costmoney += $option['costprice'] * $value['total'];
			} else {
				$goods_info = pdo_fetch('select * from' . tablename('sz_yi_goods') . " where uniacid={$_W['uniacid']} and id={$value['goodsid']}");
				$costmoney += $goods_info['costprice'] * $value['total'];
			}
		}
	}
	include $this->template('order/apply');
	exit;
}
function sortByTime($_var_0, $_var_1)
{
	if ($_var_0['ts'] == $_var_1['ts']) {
		return 0;
	} else {
		return $_var_0['ts'] > $_var_1['ts'] ? 1 : -1;
	}
}

function getList($_var_2, $_var_3)
{
	$_var_4 = 'http://wap.kuaidi100.com/wap_result.jsp?rand=' . time() . "&id={$_var_2}&fromWeb=null&postid={$_var_3}";
	load()->func('communication');
	$_var_5 = ihttp_request($_var_4);
	$_var_6 = $_var_5['content'];
	if (empty($_var_6)) {
		return array();
	}
	preg_match_all('/\\<p\\>&middot;(.*)\\<\\/p\\>/U', $_var_6, $_var_7);
	if (!isset($_var_7[1])) {
		return false;
	}
	return $_var_7[1];
}

function changeWechatSend($_var_8, $_var_9, $_var_10 = '')
{
	global $_W;
	$_var_11 = pdo_fetch('SELECT plid, openid, tag FROM ' . tablename('core_paylog') . " WHERE tid = '{$_var_8}' AND status = 1 AND type = 'wechat'");
	if (!empty($_var_11['openid'])) {
		$_var_11['tag'] = iunserializer($_var_11['tag']);
		$_var_12 = $_var_11['tag']['acid'];
		load()->model('account');
		$_var_13 = account_fetch($_var_12);
		$_var_14 = uni_setting($_var_13['uniacid'], 'payment');
		if ($_var_14['payment']['wechat']['version'] == '2') {
			return true;
		}
		$_var_15 = array('appid' => $_var_13['key'], 'openid' => $_var_11['openid'], 'transid' => $_var_11['tag']['transaction_id'], 'out_trade_no' => $_var_11['plid'], 'deliver_timestamp' => TIMESTAMP, 'deliver_status' => $_var_9, 'deliver_msg' => $_var_10,);
		$_var_16 = $_var_15;
		$_var_16['appkey'] = $_var_14['payment']['wechat']['signkey'];
		ksort($_var_16);
		$_var_17 = '';
		foreach ($_var_16 as $_var_18 => $_var_19) {
			$_var_18 = strtolower($_var_18);
			$_var_17 .= "{$_var_18}={$_var_19}&";
		}
		$_var_15['app_signature'] = sha1(rtrim($_var_17, '&'));
		$_var_15['sign_method'] = 'sha1';
		$_var_13 = WeAccount::create($_var_12);
		$_var_20 = $_var_13->changeOrderStatus($_var_15);
		if (is_error($_var_20)) {
			message($_var_20['message']);
		}
	}
}

function order_list_backurl()
{
	global $_GPC;
	return $_GPC['op'] == 'detail' ? $this->createWebUrl('order') : referer();
}

function zymfunc_3($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.send');
	if (empty($_var_21['addressid'])) {
		message('无收货地址，无法发货！');
	}
	if ($_var_21['paytype'] != 3) {
		if ($_var_21['status'] != 1) {
			message('订单未付款，无法发货！');
		}
	}
	if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
		message('请输入快递单号！');
	}
	if (!empty($_var_21['transid'])) {
		changeWechatSend($_var_21['ordersn'], 1);
	}
	pdo_update('sz_yi_order', array('status' => 2, 'express' => trim($_GPC['express']), 'expresscom' => trim($_GPC['expresscom']), 'expresssn' => trim($_GPC['expresssn']), 'sendtime' => time()), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	if (!empty($_var_21['refundid'])) {
		$_var_22 = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id limit 1', array(':id' => $_var_21['refundid']));
		if (!empty($_var_22)) {
			pdo_update('sz_yi_order_refund', array('status' => -1), array('id' => $_var_21['refundid']));
			pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $_var_21['id']));
		}
	}
	m('notice')->sendOrderMessage($_var_21['id']);
	plog('order.op.send', "订单发货 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']} <br/>快递公司: {$_GPC['expresscom']} 快递单号: {$_GPC['expresssn']}");
	message('发货操作成功！', order_list_backurl(), 'success');
}

function zymfunc_1($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.fetch');
	if ($_var_21['status'] != 1) {
		message('订单未付款，无法确认取货！');
	}
	$_var_23 = time();
	$_var_24 = array('status' => 3, 'sendtime' => $_var_23, 'finishtime' => $_var_23);
	if ($_var_21['isverify'] == 1) {
		$_var_24['verified'] = 1;
		$_var_24['verifytime'] = $_var_23;
		$_var_24['verifyopenid'] = "";
	}
	pdo_update('sz_yi_order', $_var_24, array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	if (!empty($_var_21['refundid'])) {
		$_var_22 = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id limit 1', array(':id' => $_var_21['refundid']));
		if (!empty($_var_22)) {
			pdo_update('sz_yi_order_refund', array('status' => -1), array('id' => $_var_21['refundid']));
			pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $_var_21['id']));
		}
	}
	m('member')->upgradeLevel($_var_21['openid']);
	m('notice')->sendOrderMessage($_var_21['id']);
	if (p('commission')) {
		p('commission')->checkOrderFinish($_var_21['id']);
	}
	plog('order.op.fetch', "订单确认取货 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('发货操作成功！', order_list_backurl(), 'success');
}

function zymfunc_4($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.sendcancel');
	if ($_var_21['status'] != 2) {
		message('订单未发货，不需取消发货！');
	}
	if (!empty($_var_21['transid'])) {
		changeWechatSend($_var_21['ordersn'], 0, $_GPC['cancelreson']);
	}
	pdo_update('sz_yi_order', array('status' => 1, 'sendtime' => 0), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	plog('order.op.sencancel', "订单取消发货 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('取消发货操作成功！', order_list_backurl(), 'success');
}

function zymfunc_2($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.fetchcancel');
	if ($_var_21['status'] != 3) {
		message('订单未取货，不需取消！');
	}
	pdo_update('sz_yi_order', array('status' => 1, 'finishtime' => 0), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	plog('order.op.fetchcancel', "订单取消取货 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('取消发货操作成功！', order_list_backurl(), 'success');
}

function order_list_finish($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.finish');
	pdo_update('sz_yi_order', array('status' => 3, 'finishtime' => time()), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	m('member')->upgradeLevel($_var_21['openid']);
	m('notice')->sendOrderMessage($_var_21['id']);
	if (p('coupon') && !empty($_var_21['couponid'])) {
		p('coupon')->backConsumeCoupon($_var_21['id']);
	}
	if (p('commission')) {
		p('commission')->checkOrderFinish($_var_21['id']);
	}
	plog('order.op.finish', "订单完成 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('订单操作成功！', order_list_backurl(), 'success');
}

function order_list_cancelpay($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.paycancel');
	if ($_var_21['status'] != 1) {
		message('订单未付款，不需取消！');
	}
	m('order')->setStocksAndCredits($_var_21['id'], 2);
	pdo_update('sz_yi_order', array('status' => 0, 'cancelpaytime' => time()), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	plog('order.op.paycancel', "订单取消付款 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('取消订单付款操作成功！', order_list_backurl(), 'success');
}

function zymfunc_5($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.pay');
	if ($_var_21['status'] > 1) {
		message('订单已付款，不需重复付款！');
	}
	$_var_25 = p('virtual');
	if (!empty($_var_21['virtual']) && $_var_25) {
		$_var_25->pay($_var_21);
	} else {
		$_var_26 = pdo_fetch('SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'sz_yi', ':tid' => $_var_21['ordersn']));
		pdo_update('sz_yi_order', array('paytype' => '11'), array('uniacid' => $_W['uniacid'], 'id' => $_var_21['id']));
		$_var_27 = array();
		$_var_27['result'] = 'success';
		$_var_27['from'] = 'return';
		$_var_27['tid'] = $_var_26['tid'];
		$_var_27['user'] = $_var_21['openid'];
		$_var_27['fee'] = $_var_21['price'];
		$_var_27['weid'] = $_W['uniacid'];
		$_var_27['uniacid'] = $_W['uniacid'];
		$_var_28 = m('order')->payResult($_var_27);
	}
	plog('order.op.pay', "订单确认付款 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('确认订单付款操作成功！', order_list_backurl(), 'success');
}

function order_list_close($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.close');
	if ($_var_21['status'] == -1) {
		message('订单已关闭，无需重复关闭！');
	} else if ($_var_21['status'] >= 1) {
		message('订单已付款，不能关闭！');
	}
	if (!empty($_var_21['transid'])) {
		changeWechatSend($_var_21['ordersn'], 0, $_GPC['reson']);
	}
	pdo_update('sz_yi_order', array('status' => -1, 'canceltime' => time(), 'remark' => $_var_21['remark'] . "" . $_GPC['remark']), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
	if ($_var_21['deductcredit'] > 0) {
		$_var_29 = m('common')->getSysset('shop');
		m('member')->setCredit($_var_21['openid'], 'credit1', $_var_21['deductcredit'], array('0', $_var_29['name'] . "购物返还抵扣积分 积分: {$_var_21['deductcredit']} 抵扣金额: {$_var_21['deductprice']} 订单号: {$_var_21['ordersn']}"));
	}
	if (p('coupon') && !empty($_var_21['couponid'])) {
		p('coupon')->returnConsumeCoupon($_var_21['id']);
	}
	plog('order.op.close', "订单关闭 ID: {$_var_21['id']} 订单号: {$_var_21['ordersn']}");
	message('订单关闭操作成功！', order_list_backurl(), 'success');
}

function order_list_refund($_var_21)
{
	global $_W, $_GPC;
	ca('order.op.refund');
	$_var_29 = m('common')->getSysset('shop');
	if (empty($_var_21['refundid'])) {
		message('订单未申请退款，不需处理！');
	}
	$_var_22 = pdo_fetch('select * from ' . tablename('sz_yi_order_refund') . ' where id=:id and status=0 limit 1', array(':id' => $_var_21['refundid']));
	if (empty($_var_22)) {
		pdo_update('sz_yi_order', array('refundid' => 0), array('id' => $_var_21['id'], 'uniacid' => $_W['uniacid']));
		message('未找到退款申请，不需处理！');
	}
	if (empty($_var_22['refundno'])) {
		$_var_22['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');
		pdo_update('sz_yi_order_refund', array('refundno' => $_var_22['refundno']), array('id' => $_var_22['id']));
	}
	$_var_30 = intval($_GPC['refundstatus']);
	$_var_31 = $_GPC['refundcontent'];
	if ($_var_30 == 0) {
		message('暂不处理', referer());
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
				message('退款金额必须大于1元，才能使用微信企业付款退款!', '', 'error');
			}
			$_var_33 = round($_var_33 - $_var_21['deductcredit2'], 2);
			$_var_38 = m('finance')->pay($_var_21['openid'], 1, $_var_33 * 100, $_var_22['refundno'], $_var_29['name'] . "退款: {$_var_33}元 订单号: " . $_var_21['ordersn']);
			$_var_37 = 1;
		}
		if (is_error($_var_38)) {
			message($_var_38['message'], '', 'error');
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
	message('退款申请处理成功!', order_list_backurl(), 'success');
}