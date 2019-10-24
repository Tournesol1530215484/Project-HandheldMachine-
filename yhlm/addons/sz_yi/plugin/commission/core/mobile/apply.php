<?php
global $_W, $_GPC;
$openid = m('user')->getOpenid();
if ($_W['isajax']) {
	$level = $this->set['level'];
	$member = $this->model->getInfo($openid, array('ok'));
	$time = time();
	$day_times = intval($this->set['settledays']) * 3600 * 24;
	$commission_ok = $member['commission_ok'];
	$cansettle = $commission_ok >= floatval($this->set['withdraw']);
	$member['commission_ok'] = number_format($commission_ok, 2);
	// 手续费
	$poundage = $member['commission_ok'] * $this->set['scale'] / 100;
	$commission_ok -= $poundage;

	//是否开启佣金提现2016.8.25 add
	$this->set['extract_commission'] = m('commission')->getAuthority('is_withdraw', $this->set['extract_commission'] );
	if( empty($this->set['extract_commission']) ){
		show_json(-1, '系统未开启分佣提现!');
	}
	//end 
	if ($_W['ispost']) {
		$orderids = array();
		if ($level >= 1) {
			$level1_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . " where o.agentid=:agentid and o.status>=3  and og.status1=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times}) and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']));
			foreach ($level1_orders as $o) {
				if (empty($o['id'])) {
					continue;
				}
				$orderids[] = array('orderid' => $o['id'], 'level' => 1);
			}
		}
		if ($level >= 2) {
			if ($member['level1'] > 0) {
				$level2_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . " where o.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")  and o.status>=3  and og.status2=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times}) and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level2_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array('orderid' => $o['id'], 'level' => 2);
				}
			}
		}
		if ($level >= 3) {
			if ($member['level2'] > 0) {
				$level3_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . " where o.agentid in( " . implode(',', array_keys($member['level2_agentids'])) . ")  and o.status>=3  and  og.status3=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level3_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 3
					);
				}
			}
		}
		if ($level >= 4) {
			if ($member['level3'] > 0) {
				$level4_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status4=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level4_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 4
					);
				}
			}
		}
		if ($level >= 5) {
			if ($member['level4'] > 0) {
				$level5_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status5=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level5_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 5
					);
				}
			}
		}
		if ($level >= 6) {
			if ($member['level5'] > 0) {
				$level6_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status6=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level6_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 6
					);
				}
			}
		}
		if ($level >= 7) {
			if ($member['level6'] > 0) {
				$level7_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status7=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level7_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 7
					);
				}
			}
		}
		if ($level >= 8) {
			if ($member['level7'] > 0) {
				$level8_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status8=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level8_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 8
					);
				}
			}
		}
		if ($level >= 9) {
			if ($member['level8'] > 0) {
				$level9_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status9=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level9_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 9
					);
				}
			}
		}
		if ($level >= 10) {
			if ($member['level9'] > 0) {
				$level10_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status10=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level10_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 10
					);
				}
			}
		}
		if ($level >= 11) {
			if ($member['level10'] > 0) {
				$level11_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status11=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level11_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 11
					);
				}
			}
		}
		if ($level >= 12) {
			if ($member['level11'] > 0) {
				$level12_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status12=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level12_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 12
					);
				}
			}
		}
		if ($level >= 13) {
			if ($member['level12'] > 0) {
				$level13_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status13=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level13_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 13
					);
				}
			}
		}
		if ($level >= 14) {
			if ($member['level13'] > 0) {
				$level14_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status14=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level14_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 14
					);
				}
			}
		}
		if ($level >= 15) {
			if ($member['level14'] > 0) {
				$level15_orders = pdo_fetchall('select distinct o.id from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($member['level3_agentids'])) . ")  and o.status>=3  and  og.status15=0 and og.nocommission=0 and ({$time} - o.createtime > {$day_times})   and o.uniacid=:uniacid  group by o.id", array(':uniacid' => $_W['uniacid']));
				foreach ($level15_orders as $o) {
					if (empty($o['id'])) {
						continue;
					}
					$orderids[] = array(
						'orderid' => $o['id'], 
						'level' => 15
					);
				}
			}
		}
		$time = time();
		foreach ($orderids as $o) {
			pdo_update('sz_yi_order_goods', array(
				'status' . $o['level'] => 1, 
				'applytime' . $o['level'] => $time
			), array(
				'orderid' => $o['orderid'], 
				'uniacid' => $_W['uniacid']
			));
		}
		$applyno = m('common')->createNO('commission_apply', 'applyno', 'CA');
		$apply = array(
			'uniacid' => $_W['uniacid'], 
			'applyno' => $applyno, 
			'orderids' => iserializer($orderids), 
			'mid' => $member['id'], 
			'commission' => $commission_ok, 
			'type' => intval($_GPC['type']), 
			'status' => 1, 
			'applytime' => $time
		);
		pdo_insert('sz_yi_commission_apply', $apply);
		$returnurl = urlencode($this->createMobileUrl('member/withdraw'));
		$infourl = $this->createMobileUrl('member/info', array('returnurl' => $returnurl));
		$this->model->sendMessage($openid, array(
			'commission' => $commission_ok, 
			'type' => $apply['type'] == 1 ? '微信' : '余额'), 
			TM_COMMISSION_APPLY
		);
		show_json(1, '已提交,请等待审核!');
	}
	$returnurl = urlencode($this->createPluginMobileUrl('commission/apply'));
	$infourl = $this->createMobileUrl('member/info', array('returnurl' => $returnurl));
	
	$member['commission_ok'] -= $poundage; // 扣除手续费后的可提现金额
	show_json(1, array(
		'commission_ok' => $member['commission_ok'], 
		'cansettle' => $cansettle, 
		'member' => $member, 
		'set' => $this->set, 
		'infourl' => $infourl, 
		'noinfo' => empty($member['realname']),
		'poundage' => $poundage, // 手续费
	));
}
include $this->template('apply');
