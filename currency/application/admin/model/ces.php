<?php

	public function allPerms()

		{

			$perms = array('shop' => array('text' => '商城管理', 'child' => array('goods' => array('text' => '商品', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'category' => array('text' => '商品分类', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'dispatch' => array('text' => '配送方式', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'adv' => array('text' => '幻灯片', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'notice' => array('text' => '公告', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'comment' => array('text' => '评价', 'view' => '浏览', 'add' => '添加评论-log', 'edit' => '回复-log', 'delete' => '删除-log'),)), 'member' => array('text' => '会员管理', 'child' => array('member' => array('text' => '会员', 'view' => '浏览', 'edit' => '修改-log', 'delete' => '删除-log', 'export' => '导出-log'), 'group' => array('text' => '会员组', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'level' => array('text' => '会员等级', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'))), 'order' => array('text' => '订单管理', 'child' => array('view' => array('text' => '浏览', 'status_1' => '浏览关闭订单', 'status0' => '浏览待付款订单', 'status1' => '浏览已付款订单', 'status2' => '浏览已发货订单', 'status3' => '浏览完成的订单', 'status4' => '浏览退货申请订单', 'status5' => '浏览已退货订单',), 'op' => array('text' => '操作', 'pay' => '确认付款-log', 'send' => '发货-log', 'sendcancel' => '取消发货-log', 'finish' => '确认收货(快递单)-log', 'verify' => '确认核销(核销单)-log', 'fetch' => '确认取货(自提单)-log', 'close' => '关闭订单-log', 'refund' => '退货处理-log', 'export' => '导出订单-log', 'changeprice' => '订单改价-log'))), 'finance' => array('text' => '财务管理', 'child' => array('recharge' => array('text' => '充值', 'view' => '浏览', 'credit1' => '充值积分-log', 'credit2' => '充值余额-log','credit3' => '充值易货码-log','currency_credit3' => '充值易货额度-log', 'refund' => '充值退款-log', 'export' => '导出充值记录-log'), 'withdraw' => array('text' => '提现', 'view' => '浏览', 'withdraw' => '提现-log', 'export' => '导出提现记录-log'), 'downloadbill' => array('text' => '下载对账单'),)), 'statistics' => array('text' => '数据统计', 'child' => array('view' => array('text' => '浏览权限', 'sale' => '销售指标', 'sale_analysis' => '销售统计', 'order' => '订单统计', 'goods' => '商品销售统计', 'goods_rank' => '商品销售排行', 'goods_trans' => '商品销售转化率', 'member_cost' => '会员消费排行', 'member_increase' => '会员增长趋势'), 'export' => array('text' => '导出', 'sale' => '导出销售统计-log', 'order' => '导出订单统计-log', 'goods' => '导出商品销售统计-log', 'goods_rank' => '导出商品销售排行-log', 'goods_trans' => '商品销售转化率-log', 'member_cost' => '会员消费排行-log'),)), 'sysset' => array('text' => '系统设置', 'child' => array('view' => array('text' => '浏览', 'shop' => '商城设置', 'follow' => '引导及分享设置', 'notice' => '模板消息设置', 'trade' => '交易设置', 'pay' => '支付方式设置', 'template' => '模板设置', 'member' => '会员设置', 'category' => '分类层级设置', 'contact' => '联系方式设置'), 'save' => array('text' => '修改', 'shop' => '修改商城设置-log', 'follow' => '修改引导及分享设置-log', 'notice' => '修改模板消息设置-log', 'trade' => '修改交易设置-log', 'pay' => '修改支付方式设置-log', 'template' => '模板设置-log', 'member' => '会员设置-log', 'category' => '分类层级设置-log', 'contact' => '联系方式设置-log'))),);

			$plugins = m('plugin')->getAll();

			foreach ($plugins as $plugin) {

				$instance = p($plugin['identity']);

				if ($instance) { 	 	 	

					if (method_exists($instance, 'perms')) {

						

						$plugin_perms = $instance->perms();

						$perms = array_merge($perms, $plugin_perms);

					}

				}

			}

			return $perms;

		}





		

		public function getLogTypes()

		{

			$types = array();

			$perms = $this->allPerms();

			foreach ($perms as $pk => $p) {

				if (isset($p['child'])) {

					foreach ($p['child'] as $ck => $child) {

						foreach ($child as $k => $v) {

							if (strexists($v, '-log')) {

								$text = str_replace("-log", "", $p['text'] . "-" . $child['text'] . "-" . $v);

								if ($k == 'text') {

									$text = str_replace("-log", "", $p['text'] . "-" . $child['text']);

								}

								$types[] = array('text' => $text, 'value' => str_replace(".text", "", $pk . "." . $ck . "." . $k));

							}

						}

					}

				} else {

					foreach ($p as $k => $v) {

						if (strexists($v, '-log')) {

							$text = str_replace("-log", "", $p['text'] . "-" . $v);

							if ($k == 'text') {

								$text = str_replace("-log", "", $p['text']);

							}

							$types[] = array('text' => $text, 'value' => str_replace(".text", "", $pk . "." . $k));

						}

					}

				}

			}

			return $types;

		}






public function log($type = '', $op = '')

		{

			global $_W;

			static $_logtypes;

			if (!$_logtypes) {

				$_logtypes = $this->getLogTypes();

			}

			$log = array('uniacid' => $_W['uniacid'], 'uid' => $_W['uid'], 'name' => $this->getLogName($type, $_logtypes), 'type' => $type, 'op' => $op, 'ip' => CLIENT_IP, 'createtime' => time());

			pdo_insert('sz_yi_perm_log', $log);

		}





function plog($type = '', $op = '')

{

    $perm = p('perm');

    if ($perm) {

        $perm->log($type, $op);

    }

}





plog('dealmerch.goods.add', "添加易货商品 ID: {$id}");