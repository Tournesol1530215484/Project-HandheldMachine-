<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
define('TM_COMMISSION_AGENT_NEW', 'commission_agent_new');
define('TM_COMMISSION_ORDER_PAY', 'commission_order_pay');
define('TM_COMMISSION_ORDER_FINISH', 'commission_order_finish');
define('TM_COMMISSION_APPLY', 'commission_apply');
define('TM_COMMISSION_CHECK', 'commission_check');
define('TM_COMMISSION_PAY', 'commission_pay');
define('TM_COMMISSION_UPGRADE', 'commission_upgrade');
define('TM_COMMISSION_BECOME', 'commission_become');
if (!class_exists('CommissionModel')) {
	class CommissionModel extends PluginModel
	{
		public function getSet()
		{
			$_var_0 = parent::getSet();
			$_var_0['texts'] = array(
				'agent' => empty($_var_0['texts']['agent']) ? '分销商' : $_var_0['texts']['agent'], 
				'shop' => empty($_var_0['texts']['shop']) ? '小店' : $_var_0['texts']['shop'], 
				'myshop' => empty($_var_0['texts']['myshop']) ? '我的小店' : $_var_0['texts']['myshop'], 
				'center' => empty($_var_0['texts']['center']) ? '分销中心' : $_var_0['texts']['center'], 
				'become' => empty($_var_0['texts']['become']) ? '成为分销商' : $_var_0['texts']['become'], 
				'withdraw' => empty($_var_0['texts']['withdraw']) ? '提现' : $_var_0['texts']['withdraw'], 
				'commission' => empty($_var_0['texts']['commission']) ? '佣金' : $_var_0['texts']['commission'], 
				'commission1' => empty($_var_0['texts']['commission1']) ? '分销佣金' : $_var_0['texts']['commission1'], 
				'commission_total' => empty($_var_0['texts']['commission_total']) ? '累计佣金' : $_var_0['texts']['commission_total'], 
				'commission_ok' => empty($_var_0['texts']['commission_ok']) ? '可提现佣金' : $_var_0['texts']['commission_ok'], 
				'commission_apply' => empty($_var_0['texts']['commission_apply']) ? '已申请佣金' : $_var_0['texts']['commission_apply'], 
				'commission_check' => empty($_var_0['texts']['commission_check']) ? '待打款佣金' : $_var_0['texts']['commission_check'], 
				'commission_lock' => empty($_var_0['texts']['commission_lock']) ? '未结算佣金' : $_var_0['texts']['commission_lock'], 
				'commission_detail' => empty($_var_0['texts']['commission_detail']) ? '佣金明细' : $_var_0['texts']['commission_detail'], 
				'commission_pay' => empty($_var_0['texts']['commission_pay']) ? '成功提现佣金' : $_var_0['texts']['commission_pay'], 
				'order' => empty($_var_0['texts']['order']) ? '分销订单' : $_var_0['texts']['order'], 
				'myteam' => empty($_var_0['texts']['myteam']) ? '我的团队' : $_var_0['texts']['myteam'], 
				'c1' => empty($_var_0['texts']['c1']) ? '一级' : $_var_0['texts']['c1'], 
				'c2' => empty($_var_0['texts']['c2']) ? '二级' : $_var_0['texts']['c2'], 
				'c3' => empty($_var_0['texts']['c3']) ? '三级' : $_var_0['texts']['c3'], 
				'c4' => empty($_var_0['texts']['c4']) ? '四级' : $_var_0['texts']['c4'], 
				'c5' => empty($_var_0['texts']['c5']) ? '五级' : $_var_0['texts']['c5'], 
				'c6' => empty($_var_0['texts']['c6']) ? '六级' : $_var_0['texts']['c6'], 
				'c7' => empty($_var_0['texts']['c7']) ? '七级' : $_var_0['texts']['c7'], 
				'c8' => empty($_var_0['texts']['c8']) ? '八级' : $_var_0['texts']['c8'], 
				'c9' => empty($_var_0['texts']['c9']) ? '九级' : $_var_0['texts']['c9'], 
				'c10' => empty($_var_0['texts']['c10']) ? '十级' : $_var_0['texts']['c10'], 
				'c11' => empty($_var_0['texts']['c11']) ? '十一级' : $_var_0['texts']['c11'], 
				'c12' => empty($_var_0['texts']['c12']) ? '十二级' : $_var_0['texts']['c12'], 
				'c13' => empty($_var_0['texts']['c13']) ? '十三级' : $_var_0['texts']['c13'], 
				'c14' => empty($_var_0['texts']['c14']) ? '十四级' : $_var_0['texts']['c14'], 
				'c15' => empty($_var_0['texts']['c15']) ? '十五级' : $_var_0['texts']['c15'], 
				'mycustomer' => empty($_var_0['texts']['mycustomer']) ? '我的客户' : $_var_0['texts']['mycustomer'],);
			return $_var_0;
		}
        /*
		public function calculate($orderid = 0, $update = true)
        {
            global $_W;
            $set    = $this->getSet();
            $levels = $this->getLevels();
            $goods  = pdo_fetchall("select og.id,og.realprice,og.total,g.hascommission,g.nocommission, g.commission1_rate,g.commission1_pay,g.commission2_rate,g.commission2_pay,g.commission3_rate,g.commission3_pay from " . tablename('sz_yi_order_goods') . '  og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id = og.goodsid' . ' where og.orderid=:orderid and og.uniacid=:uniacid', array(
                ':orderid' => $orderid,
                ':uniacid' => $_W['uniacid']
            ));//,g.commission4_rate,g.commission4_pay,g.commission5_rate,g.commission5_pay,g.commission6_rate,g.commission6_pay,g.commission7_rate,g.commission7_pay,g.commission8_rate,g.commission8_pay,g.commission9_rate,g.commission9_pay

            if ($set['level'] > 0) {
            	$highlevel = $set['level'];
                foreach ($goods as &$cinfo) {
                    $price = $cinfo['realprice'];
                    if (empty($cinfo['nocommission'])) {
                        if ($cinfo['hascommission'] == 1) {
                        	for($i=1;$i<=$highlevel;$i++)
                        	{
	                            $cinfo['commission'.$i] = array(
	                                'default' => $highlevel >= $i ? ($cinfo['commission'.$i.'_rate'] > 0 ? round($cinfo['commission'.$i.'_rate'] * $price / 100, 2) . "" : round($cinfo['commission'.$i.'_pay'] * $cinfo['total'], 2)) : 0
	                            );
                        	}
                           
                        } else {
                        	for($i=1;$i<=$highlevel;$i++)
                        	{
	                            $cinfo['commission'.$i] = array(
	                                'default' => $highlevel >= $i ? round($set['commission'.$i] * $price / 100, 2) . "" : 0
	                            );
                        	}
                            foreach ($levels as $level) {
                            	for($i=1;$i<=$highlevel;$i++)
                            	{
                                  $cinfo['commission'.$i]['level' . $level['id']] = $highlevel >= $i ? round($level['commission'.$i] * $price / 100, 2) . "" : 0;
 	                            }
                           }
                        }
                    } else {
                    	for($i=1;$i<=$highlevel;$i++)
                    	{
	                        $cinfo['commission'.$i] = array(
	                            'default' => 0
	                        );
                    	}
                      
                        foreach ($levels as $level) {
                        	for($i=1;$i<=$highlevel;$i++)
                        	{
                           	  $cinfo['commission1']['level' . $level['id']] = 0;
                        	}
                        }
                    }
                    if ($update) {
                    	$data = array();
                    	for($i=1;$i<=$highlevel;$i++)
                    	{
                    		$data['commission'.$i] = iserializer($cinfo['commission'.$i]);
                    	}
                    	$data['nocommission'] = $cinfo['nocommission'];
                    	
                        pdo_update('ewei_shop_order_goods', $data, array(
                            'id' => $cinfo['id']
                        ));
                    }
                }
                unset($cinfo);
            }
            return $goods;
        }
		*/
		public function calculate($_var_1 = 0, $_var_2 = true)
		{
			global $_W;
			$_var_0 = $this->getSet();
			$_var_3 = $this->getLevels();
			$_var_4 = pdo_fetchcolumn('select agentid from ' . tablename('sz_yi_order') . ' where id=:id limit 1', array(':id' => $_var_1));
			$_var_5 = pdo_fetchall('select og.id,og.realprice,og.total,g.hascommission,g.nocommission, g.commission1_rate,g.commission1_pay,g.commission2_rate,g.commission2_pay,g.commission3_rate,g.commission3_pay,g.commission4_rate,g.commission4_pay,g.commission5_rate,g.commission5_pay,g.commission6_rate,g.commission6_pay,g.commission7_rate,g.commission7_pay,g.commission8_rate,g.commission8_pay,g.commission9_rate,g.commission9_pay,g.commission10_rate,g.commission10_pay,g.commission11_rate,g.commission11_pay,g.commission12_rate,g.commission12_pay,g.commission13_rate,g.commission13_pay,g.commission14_rate,g.commission14_pay,g.commission15_rate,g.commission15_pay,og.commissions,og.optionid,g.productprice,g.marketprice,g.costprice from ' . tablename('sz_yi_order_goods') . '  og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id = og.goodsid' . ' where og.orderid=:orderid and og.uniacid=:uniacid', array(':orderid' => $_var_1, ':uniacid' => $_W['uniacid']));
			if ($_var_0['level'] > 0) {
				foreach ($_var_5 as &$_var_6) {
					$_var_7 = $this->calculate_method($_var_6);
					if (empty($_var_6['nocommission']) && $_var_7 > 0) {
						if ($_var_6['hascommission'] == 1) {
							$_var_6['commission1']  = array('default' => $_var_0['level'] >= 1  ? ($_var_6['commission1_rate']  > 0 ? round($_var_6['commission1_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission1_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission2']  = array('default' => $_var_0['level'] >= 2  ? ($_var_6['commission2_rate']  > 0 ? round($_var_6['commission2_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission2_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission3']  = array('default' => $_var_0['level'] >= 3  ? ($_var_6['commission3_rate']  > 0 ? round($_var_6['commission3_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission3_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission4']  = array('default' => $_var_0['level'] >= 4  ? ($_var_6['commission4_rate']  > 0 ? round($_var_6['commission4_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission4_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission5']  = array('default' => $_var_0['level'] >= 5  ? ($_var_6['commission5_rate']  > 0 ? round($_var_6['commission5_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission5_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission6']  = array('default' => $_var_0['level'] >= 6  ? ($_var_6['commission6_rate']  > 0 ? round($_var_6['commission6_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission6_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission7']  = array('default' => $_var_0['level'] >= 7  ? ($_var_6['commission7_rate']  > 0 ? round($_var_6['commission7_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission7_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission8']  = array('default' => $_var_0['level'] >= 8  ? ($_var_6['commission8_rate']  > 0 ? round($_var_6['commission8_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission8_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission9']  = array('default' => $_var_0['level'] >= 9  ? ($_var_6['commission9_rate']  > 0 ? round($_var_6['commission9_rate']  * $_var_7 / 100, 2) . "" : round($_var_6['commission9_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission10'] = array('default' => $_var_0['level'] >= 10 ? ($_var_6['commission10_rate'] > 0 ? round($_var_6['commission10_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission10_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission11'] = array('default' => $_var_0['level'] >= 11 ? ($_var_6['commission11_rate'] > 0 ? round($_var_6['commission11_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission11_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission12'] = array('default' => $_var_0['level'] >= 12 ? ($_var_6['commission12_rate'] > 0 ? round($_var_6['commission12_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission12_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission13'] = array('default' => $_var_0['level'] >= 13 ? ($_var_6['commission13_rate'] > 0 ? round($_var_6['commission13_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission13_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission14'] = array('default' => $_var_0['level'] >= 14 ? ($_var_6['commission14_rate'] > 0 ? round($_var_6['commission14_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission14_pay'] * $_var_6['total'], 2)) : 0);
							$_var_6['commission15'] = array('default' => $_var_0['level'] >= 15 ? ($_var_6['commission15_rate'] > 0 ? round($_var_6['commission15_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission15_pay'] * $_var_6['total'], 2)) : 0);
							foreach ($_var_3 as $_var_8) {
								$_var_6['commission1']['level' . $_var_8['id']] = $_var_6['commission1_rate'] > 0 ? round($_var_6['commission1_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission1_pay'] * $_var_6['total'], 2);
								$_var_6['commission2']['level' . $_var_8['id']] = $_var_6['commission2_rate'] > 0 ? round($_var_6['commission2_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission2_pay'] * $_var_6['total'], 2);
								$_var_6['commission3']['level' . $_var_8['id']] = $_var_6['commission3_rate'] > 0 ? round($_var_6['commission3_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission3_pay'] * $_var_6['total'], 2);
								$_var_6['commission4']['level' . $_var_8['id']] = $_var_6['commission4_rate'] > 0 ? round($_var_6['commission4_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission4_pay'] * $_var_6['total'], 2);
								$_var_6['commission5']['level' . $_var_8['id']] = $_var_6['commission5_rate'] > 0 ? round($_var_6['commission5_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission5_pay'] * $_var_6['total'], 2);
								$_var_6['commission6']['level' . $_var_8['id']] = $_var_6['commission6_rate'] > 0 ? round($_var_6['commission6_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission6_pay'] * $_var_6['total'], 2);
								$_var_6['commission7']['level' . $_var_8['id']] = $_var_6['commission7_rate'] > 0 ? round($_var_6['commission7_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission7_pay'] * $_var_6['total'], 2);
								$_var_6['commission8']['level' . $_var_8['id']] = $_var_6['commission8_rate'] > 0 ? round($_var_6['commission8_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission8_pay'] * $_var_6['total'], 2);
								$_var_6['commission9']['level' . $_var_8['id']] = $_var_6['commission9_rate'] > 0 ? round($_var_6['commission9_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission9_pay'] * $_var_6['total'], 2);
								$_var_6['commission10']['level' . $_var_8['id']] = $_var_6['commission10_rate'] > 0 ? round($_var_6['commission10_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission10_pay'] * $_var_6['total'], 2);
								$_var_6['commission11']['level' . $_var_8['id']] = $_var_6['commission11_rate'] > 0 ? round($_var_6['commission11_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission11_pay'] * $_var_6['total'], 2);
								$_var_6['commission12']['level' . $_var_8['id']] = $_var_6['commission12_rate'] > 0 ? round($_var_6['commission12_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission12_pay'] * $_var_6['total'], 2);
								$_var_6['commission13']['level' . $_var_8['id']] = $_var_6['commission13_rate'] > 0 ? round($_var_6['commission13_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission13_pay'] * $_var_6['total'], 2);
								$_var_6['commission14']['level' . $_var_8['id']] = $_var_6['commission14_rate'] > 0 ? round($_var_6['commission14_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission14_pay'] * $_var_6['total'], 2);
								$_var_6['commission15']['level' . $_var_8['id']] = $_var_6['commission15_rate'] > 0 ? round($_var_6['commission15_rate'] * $_var_7 / 100, 2) . "" : round($_var_6['commission15_pay'] * $_var_6['total'], 2);
							}
						} else {
							$_var_6['commission1']  = array('default' => $_var_0['level'] >= 1  ? round($_var_0['commission1']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission2']  = array('default' => $_var_0['level'] >= 2  ? round($_var_0['commission2']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission3']  = array('default' => $_var_0['level'] >= 3  ? round($_var_0['commission3']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission4']  = array('default' => $_var_0['level'] >= 4  ? round($_var_0['commission4']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission5']  = array('default' => $_var_0['level'] >= 5  ? round($_var_0['commission5']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission6']  = array('default' => $_var_0['level'] >= 6  ? round($_var_0['commission6']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission7']  = array('default' => $_var_0['level'] >= 7  ? round($_var_0['commission7']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission8']  = array('default' => $_var_0['level'] >= 8  ? round($_var_0['commission8']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission9']  = array('default' => $_var_0['level'] >= 9  ? round($_var_0['commission9']  * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission10'] = array('default' => $_var_0['level'] >= 10 ? round($_var_0['commission10'] * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission11'] = array('default' => $_var_0['level'] >= 11 ? round($_var_0['commission11'] * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission12'] = array('default' => $_var_0['level'] >= 12 ? round($_var_0['commission12'] * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission13'] = array('default' => $_var_0['level'] >= 13 ? round($_var_0['commission13'] * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission14'] = array('default' => $_var_0['level'] >= 14 ? round($_var_0['commission14'] * $_var_7 / 100, 2) . "" : 0);
							$_var_6['commission15'] = array('default' => $_var_0['level'] >= 15 ? round($_var_0['commission15'] * $_var_7 / 100, 2) . "" : 0);
							foreach ($_var_3 as $_var_8) {
								$_var_6['commission1']['level' . $_var_8['id']]  = $_var_0['level'] >= 1  ? round($_var_8['commission1']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission2']['level' . $_var_8['id']]  = $_var_0['level'] >= 2  ? round($_var_8['commission2']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission3']['level' . $_var_8['id']]  = $_var_0['level'] >= 3  ? round($_var_8['commission3']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission4']['level' . $_var_8['id']]  = $_var_0['level'] >= 4  ? round($_var_8['commission4']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission5']['level' . $_var_8['id']]  = $_var_0['level'] >= 5  ? round($_var_8['commission5']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission6']['level' . $_var_8['id']]  = $_var_0['level'] >= 6  ? round($_var_8['commission6']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission7']['level' . $_var_8['id']]  = $_var_0['level'] >= 7  ? round($_var_8['commission7']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission8']['level' . $_var_8['id']]  = $_var_0['level'] >= 8  ? round($_var_8['commission8']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission9']['level' . $_var_8['id']]  = $_var_0['level'] >= 9  ? round($_var_8['commission9']  * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission10']['level' . $_var_8['id']] = $_var_0['level'] >= 10 ? round($_var_8['commission10'] * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission11']['level' . $_var_8['id']] = $_var_0['level'] >= 11 ? round($_var_8['commission11'] * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission12']['level' . $_var_8['id']] = $_var_0['level'] >= 12 ? round($_var_8['commission12'] * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission13']['level' . $_var_8['id']] = $_var_0['level'] >= 13 ? round($_var_8['commission13'] * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission14']['level' . $_var_8['id']] = $_var_0['level'] >= 14 ? round($_var_8['commission14'] * $_var_7 / 100, 2) . "" : 0;
								$_var_6['commission15']['level' . $_var_8['id']] = $_var_0['level'] >= 15 ? round($_var_8['commission15'] * $_var_7 / 100, 2) . "" : 0;
							}
						}
					} else {
						$_var_6['commission1'] = array('default' => 0);
						$_var_6['commission2'] = array('default' => 0);
						$_var_6['commission3'] = array('default' => 0);
						$_var_6['commission4'] = array('default' => 0);
						$_var_6['commission5'] = array('default' => 0);
						$_var_6['commission6'] = array('default' => 0);
						$_var_6['commission7'] = array('default' => 0);
						$_var_6['commission8'] = array('default' => 0);
						$_var_6['commission9'] = array('default' => 0);
						$_var_6['commission10'] = array('default' => 0);
						$_var_6['commission11'] = array('default' => 0);
						$_var_6['commission12'] = array('default' => 0);
						$_var_6['commission13'] = array('default' => 0);
						$_var_6['commission14'] = array('default' => 0);
						$_var_6['commission15'] = array('default' => 0);
						foreach ($_var_3 as $_var_8) {
							$_var_6['commission1']['level' . $_var_8['id']] = 0;
							$_var_6['commission2']['level' . $_var_8['id']] = 0;
							$_var_6['commission3']['level' . $_var_8['id']] = 0;
							$_var_6['commission4']['level' . $_var_8['id']] = 0;
							$_var_6['commission5']['level' . $_var_8['id']] = 0;
							$_var_6['commission6']['level' . $_var_8['id']] = 0;
							$_var_6['commission7']['level' . $_var_8['id']] = 0;
							$_var_6['commission8']['level' . $_var_8['id']] = 0;
							$_var_6['commission9']['level' . $_var_8['id']] = 0;
							$_var_6['commission10']['level' . $_var_8['id']] = 0;
							$_var_6['commission11']['level' . $_var_8['id']] = 0;
							$_var_6['commission12']['level' . $_var_8['id']] = 0;
							$_var_6['commission13']['level' . $_var_8['id']] = 0;
							$_var_6['commission14']['level' . $_var_8['id']] = 0;
							$_var_6['commission15']['level' . $_var_8['id']] = 0;
						}
					}
					if ($_var_2) {
						$_var_9 = array(
							'level1' => 0, 
							'level2' => 0, 
							'level3' => 0, 
							'level4' => 0, 
							'level5' => 0, 
							'level6' => 0, 
							'level7' => 0, 
							'level8' => 0, 
							'level9' => 0, 
							'level10' => 0, 
							'level11' => 0, 
							'level12' => 0, 
							'level13' => 0, 
							'level14' => 0, 
							'level15' => 0
						);
						if (!empty($_var_4)) {
							$_var_10 = m('member')->getMember($_var_4);
							if ($_var_10['isagent'] == 1 && $_var_10['status'] == 1) {
								$_var_11 = $this->getLevel($_var_10['openid']);
								$_var_9['level1'] = empty($_var_11) ? round($_var_6['commission1']['default'], 2) : round($_var_6['commission1']['level' . $_var_11['id']], 2);
								if (!empty($_var_10['agentid'])) {
									$_var_12 = m('member')->getMember($_var_10['agentid']);
									$_var_13 = $this->getLevel($_var_12['openid']);
									$_var_9['level2'] = empty($_var_13) ? round($_var_6['commission2']['default'], 2) : round($_var_6['commission2']['level' . $_var_13['id']], 2);
									if (!empty($_var_12['agentid'])) {
										$_var_14 = m('member')->getMember($_var_12['agentid']);
										$_var_15 = $this->getLevel($_var_14['openid']);
										$_var_9['level3'] = empty($_var_15) ? round($_var_6['commission3']['default'], 2) : round($_var_6['commission3']['level' . $_var_15['id']], 2);
										if (!empty($_var_14['agentid'])) {
											$siji = m('member')->getMember($_var_14['agentid']);//四级
											$level4 = $this->getLevel($siji['openid']);
											$_var_9['level4'] = empty($level4) ? round($_var_6['commission4']['default'], 2) : round($_var_6['commission4']['level' . $level4['id']], 2);
											if (!empty($siji['agentid'])) {
												$wuji = m('member')->getMember($siji['agentid']);//五级
												$level5 = $this->getLevel($wuji['openid']);//
												$_var_9['level5'] = empty($level5) ? round($_var_6['commission5']['default'], 2) : round($_var_6['commission5']['level' . $level5['id']], 2);
												if (!empty($wuji['agentid'])) {
													$liuji = m('member')->getMember($wuji['agentid']);//六级
													$level6 = $this->getLevel($liuji['openid']);
													$_var_9['level6'] = empty($level6) ? round($_var_6['commission6']['default'], 2) : round($_var_6['commission6']['level' . $level6['id']], 2);
													if (!empty($liuji['agentid'])) {
														$qiji = m('member')->getMember($liuji['agentid']);//七级
														$level7 = $this->getLevel($qiji['openid']);
														$_var_9['level7'] = empty($level7) ? round($_var_6['commission7']['default'], 2) : round($_var_6['commission7']['level' . $level7['id']], 2);
														if (!empty($qiji['agentid'])) {
															$baji = m('member')->getMember($qiji['agentid']);//八级
															$level8 = $this->getLevel($baji['openid']);
															$_var_9['level8'] = empty($level8) ? round($_var_6['commission8']['default'], 2) : round($_var_6['commission8']['level' . $level8['id']], 2);
															if (!empty($baji['agentid'])) {
																$jiuji = m('member')->getMember($baji['agentid']);//九级
																$level9 = $this->getLevel($jiuji['openid']);
																$_var_9['level9'] = empty($level9) ? round($_var_6['commission9']['default'], 2) : round($_var_6['commission9']['level' . $level9['id']], 2);
																if (!empty($jiuji['agentid'])) {
																	$shiji = m('member')->getMember($jiuji['agentid']);//十级
																	$level10 = $this->getLevel($shiji['openid']);
																	$_var_9['level10'] = empty($level10) ? round($_var_6['commission10']['default'], 2) : round($_var_6['commission10']['level' . $level10['id']], 2);
																	if (!empty($shiji['agentid'])) {
																		$shiyiji = m('member')->getMember($shiji['agentid']);//十一级
																		$level11 = $this->getLevel($shiyiji['openid']);
																		$_var_9['level11'] = empty($level11) ? round($_var_6['commission11']['default'], 2) : round($_var_6['commission11']['level' . $level11['id']], 2);
																		if (!empty($shiyiji['agentid'])) {
																			$shierji = m('member')->getMember($shiyiji['agentid']);//十二级
																			$level12 = $this->getLevel($shierji['openid']);
																			$_var_9['level12'] = empty($level12) ? round($_var_6['commission12']['default'], 2) : round($_var_6['commission12']['level' . $level12['id']], 2);
																			if (!empty($shierji['agentid'])) {
																				$shisanji = m('member')->getMember($shierji['agentid']);//十三级
																				$level13 = $this->getLevel($shisanji['openid']);
																				$_var_9['level13'] = empty($level13) ? round($_var_6['commission13']['default'], 2) : round($_var_6['commission13']['level' . $level13['id']], 2);
																				if (!empty($shisanji['agentid'])) {
																					$shisiji = m('member')->getMember($shisanji['agentid']);//十四级
																					$level14 = $this->getLevel($shisiji['openid']);
																					$_var_9['level14'] = empty($level14) ? round($_var_6['commission14']['default'], 2) : round($_var_6['commission14']['level' . $level14['id']], 2);
																					if (!empty($shisiji['agentid'])) {
																						$shiwuji = m('member')->getMember($shisiji['agentid']);//十五级
																						$level15 = $this->getLevel($shiwuji['openid']);
																						$_var_9['level15'] = empty($level15) ? round($_var_6['commission15']['default'], 2) : round($_var_6['commission15']['level' . $level15['id']], 2);
																					}
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
						pdo_update('sz_yi_order_goods', array(
							'commission1'  => iserializer($_var_6['commission1']), 
							'commission2'  => iserializer($_var_6['commission2']), 
							'commission3'  => iserializer($_var_6['commission3']), 
							'commission4'  => iserializer($_var_6['commission4']), 
							'commission5'  => iserializer($_var_6['commission5']), 
							'commission6'  => iserializer($_var_6['commission6']), 
							'commission7'  => iserializer($_var_6['commission7']), 
							'commission8'  => iserializer($_var_6['commission8']), 
							'commission9'  => iserializer($_var_6['commission9']), 
							'commission10' => iserializer($_var_6['commission10']), 
							'commission11' => iserializer($_var_6['commission11']), 
							'commission12' => iserializer($_var_6['commission12']), 
							'commission13' => iserializer($_var_6['commission13']), 
							'commission14' => iserializer($_var_6['commission14']), 
							'commission15' => iserializer($_var_6['commission15']), 
							'commissions' => iserializer($_var_9), 
							'nocommission' => $_var_6['nocommission']
							), array(
								'id' => $_var_6['id']
							)
						);
					}
				}
				unset($_var_6);
			}
			return $_var_5;
		}

		public function calculate_method($_var_16)
		{
			global $_W;
			$_var_0 = $this->getSet();
			$_var_17 = $_var_16['realprice'];
			if (empty($_var_0['culate_method'])) {
				return $_var_17;
			} else {
				$_var_18 = $_var_16['productprice'] * $_var_16['total'];
				$_var_19 = $_var_16['marketprice'] * $_var_16['total'];
				$_var_20 = $_var_16['costprice'] * $_var_16['total'];
				if ($_var_16['optionid'] != 0) {
					$_var_21 = pdo_fetch('select productprice,marketprice,costprice from ' . tablename('sz_yi_goods_option') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $_var_16['optionid'], ':uniacid' => $_W['uniacid']));
					$_var_18 = $_var_21['productprice'] * $_var_16['total'];
					$_var_19 = $_var_21['marketprice'] * $_var_16['total'];
					$_var_20 = $_var_21['costprice'] * $_var_16['total'];
				}
				if ($_var_0['culate_method'] == 1) {
					return $_var_18;
				} else if ($_var_0['culate_method'] == 2) {
					return $_var_19;
				} else if ($_var_0['culate_method'] == 3) {
					return $_var_20;
				} else if ($_var_0['culate_method'] == 4) {
					$_var_7 = $_var_17 - $_var_20;
					return $_var_7 > 0 ? $_var_7 : 0;
				}
			}
		}

		public function getOrderCommissions($_var_22 = 0, $_var_23 = 0)
		{
			global $_W;
			$_var_0 = $this->getSet();
			$_var_24 = pdo_fetchcolumn('select agentid from ' . tablename('sz_yi_order') . ' where id=:id limit 1', array(':id' => $_var_22));
			$_var_25 = pdo_fetch('select commission1,commission2,commission3 from ' . tablename('sz_yi_order_goods') . ' where id=:id and orderid=:orderid and uniacid=:uniacid and nocommission=0 limit 1', array(':id' => $_var_23, ':orderid' => $_var_22, ':uniacid' => $_W['uniacid']));
			$_var_26 = array('level1' => 0, 'level2' => 0, 'level3' => 0);
			if ($_var_0['level'] > 0) {
				$_var_27 = iunserializer($_var_25['commission1']);
				$_var_28 = iunserializer($_var_25['commission2']);
				$_var_29 = iunserializer($_var_25['commission3']);
				if (!empty($_var_24)) {
					$_var_30 = m('member')->getMember($_var_24);
					if ($_var_30['isagent'] == 1 && $_var_30['status'] == 1) {
						$_var_31 = $this->getLevel($_var_30['openid']);
						$_var_26['level1'] = empty($_var_31) ? round($_var_27['default'], 2) : round($_var_27['level' . $_var_31['id']], 2);
						if (!empty($_var_30['agentid'])) {
							$_var_32 = m('member')->getMember($_var_30['agentid']);
							$_var_33 = $this->getLevel($_var_32['openid']);
							$_var_26['level2'] = empty($_var_33) ? round($_var_28['default'], 2) : round($_var_28['level' . $_var_33['id']], 2);
							if (!empty($_var_32['agentid'])) {
								$_var_34 = m('member')->getMember($_var_32['agentid']);
								$_var_35 = $this->getLevel($_var_34['openid']);
								$_var_26['level3'] = empty($_var_35) ? round($_var_29['default'], 2) : round($_var_29['level' . $_var_35['id']], 2);
							}
						}
					}
				}
			}
			return $_var_26;
		}

		
        public function getInfo($openid, $options = null)
        {
            if (empty($options) || !is_array($options)) {
                $options = array();
            }
            global $_W;
            $set              = $this->getSet();
            $level            = intval($set['level']);
            $member           = m('member')->getInfo($openid);
            
            $time             = time();
            $day_times        = intval($set['settledays']) * 3600 * 24;
            $agentcount       = 0;
            $ordercount0      = 0;
            $ordermoney0      = 0;
            $ordercount       = 0;
            $ordermoney       = 0;
            $ordercount3      = 0;
            $ordermoney3      = 0;
			
           
            $oresult = array();
            for($i=1;$i<=$level;$i++)
            {
				$commission_total = 0;
				$commission_ok    = 0;
				$commission_apply = 0;
				$commission_check = 0;
				$commission_lock  = 0;
				$commission_pay   = 0;
				$member['level'.$i] = 0;
	            if ($i==1)
	            {
	            	$result = array();
		                if (in_array('ordercount0', $options)) {
		                    $level1_ordercount = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=0 and og.status'.$i.'>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    $result['ordercount1'] = $level1_ordercount['ordercount'];
		                    $result['ordermoney1'] = $level1_ordercount['ordermoney'];
		                }
		                if (in_array('ordercount', $options)) {
		                    $level1_ordercount = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=1 and og.status'.$i.'>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    $result['ordercount2'] = $level1_ordercount['ordercount'];
		                    $result['ordermoney2'] = $level1_ordercount['ordermoney'];
		                }
		                if (in_array('ordercount3', $options)) {
		                    $level1_ordercount3 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=3 and og.status'.$i.'>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    $result['ordercount3'] = $level1_ordercount3['ordercount'];
		                    $result['ordermoney3'] = $level1_ordercount3['ordermoney'];
		                }
		                if (in_array('total', $options)) {
		                    $level1_commissions = pdo_fetchall('select og.commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid", array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    foreach ($level1_commissions as $c) {
		                         $commission = iunserializer($c['commission'.$i]);
		                         $agentLevel       = $this->getLevel($openid,$c['createtime']);
		                         $commission_total += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
		                    }
							$result['commission_total'] = $commission_total;
		                }
		                if (in_array('ok', $options)) {
		                    $level1_commissions = pdo_fetchall('select og.commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$time} - o.createtime > {$day_times}) and og.status".$i."=0  and o.uniacid=:uniacid", array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    foreach ($level1_commissions as $c) {
		                        $commission = iunserializer($c['commission'.$i]);
		                        $agentLevel       = $this->getLevel($openid,$c['createtime']);
		                        $commission_ok += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
		                    }
							$result['commission_ok'] = $commission_ok;
		                }
		                if (in_array('lock', $options)) {
		                    $level1_commissions1 = pdo_fetchall('select og.commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$time} - o.createtime <= {$day_times})  and og.status".$i."=0  and o.uniacid=:uniacid", array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    foreach ($level1_commissions1 as $c) {
		                        $commission = iunserializer($c['commission'.$i]);
		                        $agentLevel       = $this->getLevel($openid,$c['createtime']);
		                        $commission_lock += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
		                    }
							$result['commission_lock'] =$commission_lock;
		                }
		                if (in_array('apply', $options)) {
		                    $level1_commissions2 = pdo_fetchall('select og.commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.status".$i."=1 and og.nocommission=0 and o.uniacid=:uniacid", array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    foreach ($level1_commissions2 as $c) {
		                        $commission = iunserializer($c['commission'.$i]);
		                        $agentLevel       = $this->getLevel($openid,$c['createtime']);
		                         $commission_applay += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
		                    }
							$result['commission_apply'] =  $commission_applay;
		                }
		                if (in_array('check', $options)) {
		                    $level1_commissions2 = pdo_fetchall('select og.commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.status".$i."=2 and og.nocommission=0 and o.uniacid=:uniacid ", array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    foreach ($level1_commissions2 as $c) {
		                        $commission = iunserializer($c['commission1']);
		                        $agentLevel       = $this->getLevel($openid,$c['createtime']);
		                        $commission_check += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
		                    }
							$result['commission_check'] = $commission_check;
		                }
		                if (in_array('pay', $options)) {
		                    $level1_commissions2 = pdo_fetchall('select og.commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.status".$i."=3 and og.nocommission=0 and o.uniacid=:uniacid ", array(
		                        ':uniacid' => $_W['uniacid'],
		                        ':agentid' => $member['id']
		                    ));
		                    foreach ($level1_commissions2 as $c) {
		                        $commission = iunserializer($c['commission'.$i]);
		                        $agentLevel       = $this->getLevel($openid,$c['createtime']);
		                        $commission_pay += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
		                    }
							$result['commission_pay'] = $commission_pay;
		                }
		                $result['level_agentids']=$level_agentids =pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid=:agentid and isagent=1 and status=1 and uniacid=:uniacid ', array(
		                    ':uniacid' => $_W['uniacid'],
		                    ':agentid' => $member['id']
		                ), 'id');
		                $result['levelcount'] = count($level_agentids);
		                if(!empty($result))
		                {
		                	$oresult['level'.$i]= $result;
		                }
						
		                unset($result);
			}elseif($oresult['level'.($i-1)]['levelcount']>0&&$i>1){
				    				  
	                $j = $i-1;
	                $result = array();
	                if (!empty($oresult['level'.$j])) {
	                    if (in_array('ordercount0', $options)) {
	                        $level2_ordercount = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ')  and o.status>=0 and og.status'.$i.'>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        $result['ordercount1'] = $level2_ordercount['ordercount'];
	                        $result['ordermoney1'] = $level2_ordercount['ordermoney'];
	                    }
	                    if (in_array('ordercount', $options)) {
	                        $level2_ordercount = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ') and o.status>=1 and og.status'.$i.'>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        $result['ordercount2'] = $level2_ordercount['ordercount'];
	                        $result['ordermoney2'] = $level2_ordercount['ordermoney'];
	                    }
	                    if (in_array('ordercount3', $options)) {
	                        $level2_ordercount3 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ')   and o.status>=3 and og.status'.$i.'>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        $result['ordercount3'] += $level2_ordercount3['ordercount'];
	                        $result['ordermoney3'] += $level2_ordercount3['ordermoney'];
	                    }
	                    if (in_array('total', $options)) {
	                        $level2_commissions = pdo_fetchall('select commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ")  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid", array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        foreach ($level2_commissions as $c) {
	                            $commission = iunserializer($c['commission'.$i]);
	                            $agentLevel       = $this->getLevel($openid,$c['createtime']);
	                            $commission_total += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
	                        }
							$result['commission_total'] = $commission_total;
	                    }
	                    if (in_array('ok', $options)) {
	                        $level2_commissions = pdo_fetchall('select commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ")  and ({$time} - o.createtime > {$day_times}) and o.status>=3 and og.status".$i."=0 and og.nocommission=0  and o.uniacid=:uniacid", array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        foreach ($level2_commissions as $c) {
	                            $commission = iunserializer($c['commission'.$i]);
	                            $agentLevel       = $this->getLevel($openid,$c['createtime']);
	                            $commission_ok += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
	                        }
							$result['commission_ok']  = $commission_ok;
	                    }
	                    if (in_array('lock', $options)) {
	                        $level2_commissions1 = pdo_fetchall('select commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ")  and ({$time} - o.createtime <= {$day_times}) and og.status".$i."=0 and o.status>=3 and og.nocommission=0 and o.uniacid=:uniacid", array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        foreach ($level2_commissions1 as $c) {
	                            $commission = iunserializer($c['commission'.$i]);
	                            $agentLevel       = $this->getLevel($openid,$c['createtime']);
	                             $commission_lock += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
	                        }
							$result['commission_lock'] = $commission_lock;
	                    }
	                    if (in_array('apply', $options)) {
	                        $level2_commissions2 = pdo_fetchall('select commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ")  and o.status>=3 and og.status".$i."=1 and og.nocommission=0 and o.uniacid=:uniacid", array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        foreach ($level2_commissions2 as $c) {
	                            $commission = iunserializer($c['commission'.$i]);
	                            $agentLevel       = $this->getLevel($openid,$c['createtime']);
	                             $commission_apply  += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
	                        }
							$result['commission_apply'] = $commission_apply;
	                    }
	                    if (in_array('check', $options)) {
	                        $level2_commissions3 = pdo_fetchall('select commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ")  and o.status>=3 and og.status".$i."=2 and og.nocommission=0 and o.uniacid=:uniacid", array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        foreach ($level2_commissions3 as $c) {
	                            $commission = iunserializer($c['commission'.$i]);
	                            $agentLevel       = $this->getLevel($openid,$c['createtime']);
	                             $commission_check += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
	                        }
							$result['commission_check'] = $commission_check;
	                    }
	                    if (in_array('pay', $options)) {
	                        $level2_commissions3 = pdo_fetchall('select commission'.$i.',o.createtime  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ")  and o.status>=3 and og.status".$i."=3 and og.nocommission=0 and o.uniacid=:uniacid", array(
	                            ':uniacid' => $_W['uniacid']
	                        ));
	                        foreach ($level2_commissions3 as $c) {
	                            $commission = iunserializer($c['commission'.$i]);
	                            $agentLevel       = $this->getLevel($openid,$c['createtime']);
	                            $commission_pay += isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
	                        }
							$result['commission_pay'] = $commission_pay;
	                    }
	                    $result['level_agentids']=$level_agentids = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid in( ' . implode(',', array_keys($oresult['level'.$j]['level_agentids'])) . ') and isagent=1 and status=1 and uniacid=:uniacid', array(
	                        ':uniacid' => $_W['uniacid']
	                    ), 'id');
	                    $result['levelcount']  = count($result['level_agentids']);
	                    if(!empty($result))
	                    {
	                    	$oresult['level'.$i]= $result;
	                    }
	                    unset($result);
	                }
	            }
            }
            
			$i=2;
            if(is_array($oresult)&&!empty($oresult))
            {	
            	foreach($oresult as $re)
            	{
					$j=$i-1;
					$ordercount0 +=  isset($re['ordercount1'])?$re['ordercount1']:0;
					$ordermoney0 +=  isset($re['ordermoney1'])?$re['ordermoney1']:0;
            		$ordercount2 += isset($re['ordercount2'])?$re['ordercount2']:0;
            		$ordermoney2 += isset($re['ordermoney2'])?$re['ordermoney2']:0;
            		$ordercount3 += isset($re['ordercount3'])?$re['ordercount3']:0;
            		$ordermoney3 += isset($re['ordermoney3'])?$re['ordermoney3']:0;
            		$member['order'.$j.'2'] = isset($re['ordercount2'])?$re['ordercount2']:0;
            		$member['order'.$j.'1'] = isset($re['ordercount1'])?$re['ordercount1']:0;
            		$member['order'.$j.'3'] = isset($re['ordercount3'])?$re['ordercount3']:0;
            		$member['ordermoney'.$j.'2'] = isset($re['ordermoney2'])?$re['ordermoney2']:0;
            		$member['ordermoney'.$j.'1'] = isset($re['ordermoney1'])?$re['ordermoney1']:0;
            		$member['ordermoney'.$j.'3'] = isset($re['ordermoney3'])?$re['ordermoeny3']:0;
            		$commission_total += isset($re['commission_total'])?$re['commission_total']:0;
            		$commission_ok += isset($re['commission_ok'])?$re['commission_ok']:0;
            		$commission_lock += isset($re['commission_lock'])?$re['commission_lock']:0;
            		$commission_apply += isset($re['commission_apply'])?$re['commission_apply']:0;
            		$commission_check += isset($re['commission_check'])?$re['commission_check']:0;
            		$commission_pay += isset($re['commission_pay'])?$re['commission_pay']:0;
					$member['level'.$j.'_agentids'] = isset($re['level_agentids'])?$re['level_agentids']:null;
					$agentcount += $re['levelcount'] ;
            		$member['level'.$j] = $re['levelcount'];
            		$i++;
            	}
            }
            $member['agentcount']       = $agentcount;
            $member['ordercount']       = $ordercount2;
            $member['ordermoney']       = $ordermoney2;
            $member['ordercount3']      = $ordercount3;
            $member['ordermoney3']      = $ordermoney3;
            $member['ordercount0']      = $ordercount0;
            $member['ordermoney0']      = $ordermoney0;
            $member['commission_total'] = round($commission_total, 2);
            $member['commission_ok']    = round($commission_ok, 2);
            $member['commission_lock']  = round($commission_lock, 2);
            $member['commission_apply'] = round($commission_apply, 2);
            $member['commission_check'] = round($commission_check, 2);
            $member['commission_pay']   = round($commission_pay, 2);
            $member['agenttime']        = date('Y-m-d H:i', $member['agenttime']);
            return $member;
        }
        		
	/*	
		public function getInfo($_var_36, $_var_37 = null)
		{
			if (empty($_var_37) || !is_array($_var_37)) {
				$_var_37 = array();
			}
			global $_W;
			$_var_0 = $this->getSet();
			$_var_38 = intval($_var_0['level']);
			
			$_var_39 = m('member')->getMember($_var_36);
			$_var_40 = $this->getLevel($_var_36);
			$_var_41 = time();
			$_var_42 = intval($_var_0['settledays']) * 3600 * 24;
			$_var_43 = 0;
			$_var_44 = 0;
			$_var_45 = 0;
			$_var_46 = 0;
			$_var_47 = 0;
			$_var_48 = 0;
			$_var_49 = 0;
			$_var_50 = 0;
			$_var_51 = 0;
			$_var_52 = 0;
			$_var_53 = 0;
			$_var_54 = 0;
			$_var_55 = 0;
			$_var_56 = 0;
			$_var_57 = 0;
			$_var_58 = 0;
			$level4  = 0;
			$level5  = 0;
			$level6  = 0;
			$level7  = 0;
			$level8  = 0;
			$level9  = 0;
			$level10 = 0;
			$level11 = 0;
			$level12 = 0;
			$level13 = 0;
			$level14 = 0;
			$level15 = 0;
			
			$_var_59 = 0;
			$_var_60 = 0;
			$_var_61 = 0;
			$order40 = 0;
		
			
			$_var_62 = 0;
			$_var_63 = 0;
			$_var_64 = 0;
			$order4  = 0;
			
			$_var_65 = 0;
			$_var_66 = 0;
			$_var_67 = 0;
			$order43 = 0;
			
			$_var_68 = 0;
			$_var_69 = 0;
			$_var_70 = 0;
			$_var_71 = 0;
			$_var_72 = 0;
			if ($_var_38 >= 1) {
				if (in_array('ordercount0', $_var_37)) {
					$_var_73 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=0 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					$_var_59 += $_var_73['ordercount'];
					$_var_44 += $_var_73['ordercount'];
					$_var_45 += $_var_73['ordermoney'];
				}
				if (in_array('ordercount', $_var_37)) {
					$_var_73 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=1 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					$_var_62 += $_var_73['ordercount'];
					$_var_46 += $_var_73['ordercount'];
					$_var_47 += $_var_73['ordermoney'];
				}
				if (in_array('ordercount3', $_var_37)) {
					$_var_74 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=3 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					$_var_65 += $_var_74['ordercount'];
					$_var_48 += $_var_74['ordercount'];
					$_var_49 += $_var_74['ordermoney'];
					$_var_68 += $_var_74['ordermoney'];
				}
				if (in_array('total', $_var_37)) {
					$_var_75 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					foreach ($_var_75 as $_var_76) {
						$_var_26 = iunserializer($_var_76['commissions']);
						$_var_77 = iunserializer($_var_76['commission1']);
						if (empty($_var_26)) {
							$_var_50 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
						} else {
							$_var_50 += isset($_var_26['level1']) ? floatval($_var_26['level1']) : 0;
						}
					}
				}
				if (in_array('ok', $_var_37)) {
					$_var_75 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$_var_41} - o.createtime > {$_var_42}) and og.status1=0  and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					foreach ($_var_75 as $_var_76) {
						$_var_26 = iunserializer($_var_76['commissions']);
						$_var_77 = iunserializer($_var_76['commission1']);
						if (empty($_var_26)) {
							$_var_51 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
						} else {
							$_var_51 += isset($_var_26['level1']) ? $_var_26['level1'] : 0;
						}
					}
				}
				if (in_array('lock', $_var_37)) {
					$_var_78 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$_var_41} - o.createtime <= {$_var_42})  and og.status1=0  and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					foreach ($_var_78 as $_var_76) {
						$_var_26 = iunserializer($_var_76['commissions']);
						$_var_77 = iunserializer($_var_76['commission1']);
						if (empty($_var_26)) {
							$_var_54 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
						} else {
							$_var_54 += isset($_var_26['level1']) ? $_var_26['level1'] : 0;
						}
					}
				}
				if (in_array('apply', $_var_37)) {
					$_var_79 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=3 and og.status1=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					foreach ($_var_79 as $_var_76) {
						$_var_26 = iunserializer($_var_76['commissions']);
						$_var_77 = iunserializer($_var_76['commission1']);
						if (empty($_var_26)) {
							$_var_52 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
						} else {
							$_var_52 += isset($_var_26['level1']) ? $_var_26['level1'] : 0;
						}
					}
				}
				if (in_array('check', $_var_37)) {
					$_var_79 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=3 and og.status1=2 and og.nocommission=0 and o.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					foreach ($_var_79 as $_var_76) {
						$_var_26 = iunserializer($_var_76['commissions']);
						$_var_77 = iunserializer($_var_76['commission1']);
						if (empty($_var_26)) {
							$_var_53 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
						} else {
							$_var_53 += isset($_var_26['level1']) ? $_var_26['level1'] : 0;
						}
					}
				}
				if (in_array('pay', $_var_37)) {
					$_var_79 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=3 and og.status1=3 and og.nocommission=0 and o.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']));
					foreach ($_var_79 as $_var_76) {
						$_var_26 = iunserializer($_var_76['commissions']);
						$_var_77 = iunserializer($_var_76['commission1']);
						if (empty($_var_26)) {
							$_var_55 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
						} else {
							$_var_55 += isset($_var_26['level1']) ? $_var_26['level1'] : 0;
						}
					}
				}
				$_var_80 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid=:agentid and isagent=1 and status=1 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':agentid' => $_var_39['id']), 'id');
				$_var_56 = count($_var_80);
				$_var_43 += $_var_56;
			}
			if ($_var_38 >= 2) {
				if ($_var_56 > 0) {
					if (in_array('ordercount0', $_var_37)) {
						$_var_81 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=0 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$_var_60 += $_var_81['ordercount'];
						$_var_44 += $_var_81['ordercount'];
						$_var_45 += $_var_81['ordermoney'];
					}
					if (in_array('ordercount', $_var_37)) {
						$_var_81 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=1 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$_var_63 += $_var_81['ordercount'];
						$_var_46 += $_var_81['ordercount'];
						$_var_47 += $_var_81['ordermoney'];
					}
					if (in_array('ordercount3', $_var_37)) {
						$_var_82 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=3 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$_var_66 += $_var_82['ordercount'];
						$_var_48 += $_var_82['ordercount'];
						$_var_49 += $_var_82['ordermoney'];
						$_var_69 += $_var_82['ordermoney'];
					}
					if (in_array('total', $_var_37)) {
						$_var_83 = pdo_fetchall('select og.commission2,og.commissions from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_83 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission2']);
							if (empty($_var_26)) {
								$_var_50 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_50 += isset($_var_26['level2']) ? $_var_26['level2'] : 0;
							}
						}
					}
					if (in_array('ok', $_var_37)) {
						$_var_83 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ")  and ({$_var_41} - o.createtime > {$_var_42}) and o.status>=3 and og.status2=0 and og.nocommission=0  and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
						foreach ($_var_83 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission2']);
							if (empty($_var_26)) {
								$_var_51 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_51 += isset($_var_26['level2']) ? $_var_26['level2'] : 0;
							}
						}
					}
					if (in_array('lock', $_var_37)) {
						$_var_84 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ")  and ({$_var_41} - o.createtime <= {$_var_42}) and og.status2=0 and o.status>=3 and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
						foreach ($_var_84 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission2']);
							if (empty($_var_26)) {
								$_var_54 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_54 += isset($_var_26['level2']) ? $_var_26['level2'] : 0;
							}
						}
					}
					if (in_array('apply', $_var_37)) {
						$_var_85 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=3 and og.status2=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_85 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission2']);
							if (empty($_var_26)) {
								$_var_52 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_52 += isset($_var_26['level2']) ? $_var_26['level2'] : 0;
							}
						}
					}
					if (in_array('check', $_var_37)) {
						$_var_86 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=3 and og.status2=2 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_86 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission2']);
							if (empty($_var_26)) {
								$_var_53 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_53 += isset($_var_26['level2']) ? $_var_26['level2'] : 0;
							}
						}
					}
					if (in_array('pay', $_var_37)) {
						$_var_86 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($_var_80)) . ')  and o.status>=3 and og.status2=3 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_86 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission2']);
							if (empty($_var_26)) {
								$_var_55 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_55 += isset($_var_26['level2']) ? $_var_26['level2'] : 0;
							}
						}
					}
					$_var_87 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid in( ' . implode(',', array_keys($_var_80)) . ') and isagent=1 and status=1 and uniacid=:uniacid', array(':uniacid' => $_W['uniacid']), 'id');
					$_var_57 = count($_var_87);
					$_var_43 += $_var_57;
				}
			}
			if ($_var_38 >= 3) {
				if ($_var_57 > 0) {
					if (in_array('ordercount0', $_var_37)) {
						$_var_88 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=0 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$_var_61 += $_var_88['ordercount'];
						$_var_44 += $_var_88['ordercount'];
						$_var_45 += $_var_88['ordermoney'];
					}
					if (in_array('ordercount', $_var_37)) {
						$_var_88 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=1 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$_var_64 += $_var_88['ordercount'];
						$_var_46 += $_var_88['ordercount'];
						$_var_47 += $_var_88['ordermoney'];
					}
					if (in_array('ordercount3', $_var_37)) {
						$_var_89 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=3 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$_var_67 += $_var_89['ordercount'];
						$_var_48 += $_var_89['ordercount'];
						$_var_49 += $_var_89['ordermoney'];
						$_var_70 += $_var_88['ordermoney'];
					}
					if (in_array('total', $_var_37)) {
						$_var_90 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_90 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_50 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_50 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('ok', $_var_37)) {
						$_var_90 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ")  and ({$_var_41} - o.createtime > {$_var_42}) and o.status>=3 and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
						foreach ($_var_90 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_51 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_51 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('lock', $_var_37)) {
						$_var_91 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ")  and o.status>=3 and ({$_var_41} - o.createtime > {$_var_42}) and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
						foreach ($_var_91 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_54 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_54 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('apply', $_var_37)) {
						$_var_92 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=3 and og.status3=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_92 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_52 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_52 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('check', $_var_37)) {
						$_var_93 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=3 and og.status3=2 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_93 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_53 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_53 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('pay', $_var_37)) {
						$_var_93 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_87)) . ')  and o.status>=3 and og.status3=3 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($_var_93 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_55 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_55 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					$_var_94 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and agentid in( ' . implode(',', array_keys($_var_87)) . ') and isagent=1 and status=1', array(':uniacid' => $_W['uniacid']), 'id');
					$_var_58 = count($_var_94);
					$_var_43 += $_var_58;
				}
			}

			
			if ($_var_38 >= 4) {
				if ($_var_58 > 0) {
					if (in_array('ordercount0', $_var_37)) {
						$level4_ordercount = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=0 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$order40 += $level4_ordercount['ordercount'];
						$_var_44 += $level4_ordercount['ordercount'];
						$_var_45 += $level4_ordercount['ordermoney'];
					}
					if (in_array('ordercount', $_var_37)) {
						$level4_ordercount = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=1 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$order4 += $level4_ordercount['ordercount'];
						$_var_46 += $level4_ordercount['ordercount'];
						$_var_47 += $level4_ordercount['ordermoney'];
					}
					if (in_array('ordercount3', $_var_37)) {
						$level4_ordercount4 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=3 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
						$order43 += $level4_ordercount4['ordercount'];
						$_var_48 += $level4_ordercount4['ordercount'];
						$_var_49 += $level4_ordercount4['ordermoney'];
						$_var_70 += $level4_ordercount['ordermoney'];
					}
					if (in_array('total', $_var_37)) {
						$level4_commissions = pdo_fetchall('select og.commission4,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($level4_commissions as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission4']);
							if (empty($_var_26)) {
								$_var_50 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_50 += isset($_var_26['level4']) ? $_var_26['level4'] : 0;
							}
						}
					}
					if (in_array('ok', $_var_37)) {
						$level4_commissions = pdo_fetchall('select og.commission4,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ")  and ({$_var_41} - o.createtime > {$_var_42}) and o.status>=3 and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
						foreach ($level4_commissions as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission4']);
							if (empty($_var_26)) {
								$_var_51 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_51 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('lock', $_var_37)) {
						$level4_commissions4 = pdo_fetchall('select og.commission4,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ")  and o.status>=3 and ({$_var_41} - o.createtime > {$_var_42}) and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
						foreach ($level4_commissions1 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission4']);
							if (empty($_var_26)) {
								$_var_54 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_54 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('apply', $_var_37)) {
						$level4_commissions2 = pdo_fetchall('select og.commission4,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=3 and og.status3=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($level4_commissions2 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission3']);
							if (empty($_var_26)) {
								$_var_52 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_52 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('check', $_var_37)) {
						$level4_commissions3 = pdo_fetchall('select og.commission4,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=3 and og.status3=2 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($level4_commissions3 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission4']);
							if (empty($_var_26)) {
								$_var_53 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_53 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					if (in_array('pay', $_var_37)) {
						$level4_commissions3 = pdo_fetchall('select og.commission4,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($_var_94)) . ')  and o.status>=3 and og.status3=3 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
						foreach ($level4_commissions3 as $_var_76) {
							$_var_26 = iunserializer($_var_76['commissions']);
							$_var_77 = iunserializer($_var_76['commission4']);
							if (empty($_var_26)) {
								$_var_55 += isset($_var_77['level' . $_var_40['id']]) ? $_var_77['level' . $_var_40['id']] : $_var_77['default'];
							} else {
								$_var_55 += isset($_var_26['level3']) ? $_var_26['level3'] : 0;
							}
						}
					}
					$level4_agentids = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and agentid in( ' . implode(',', array_keys($_var_94)) . ') and isagent=1 and status=1', array(':uniacid' => $_W['uniacid']), 'id');
					$level4 = count($level4_agentids);
					$_var_43 += $level4;
				}
			}			
			
			
			
			if (in_array('myorder', $_var_37)) {
				$_var_95 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_39['openid']));
				$_var_71 = $_var_95['ordermoney'];
				$_var_72 = $_var_95['ordercount'];
			}
			$_var_39['agentcount'] = $_var_43;
			$_var_39['ordercount'] = $_var_46;
			$_var_39['ordermoney'] = $_var_47;
			$_var_39['order1'] = $_var_62;
			$_var_39['order2'] = $_var_63;
			$_var_39['order3'] = $_var_64;
			$_var_39['ordercount3'] = $_var_48;
			$_var_39['ordermoney3'] = $_var_49;
			$_var_39['order13'] = $_var_65;
			$_var_39['order23'] = $_var_66;
			$_var_39['order33'] = $_var_67;
			$_var_39['order43'] = $order43;
			$_var_39['order13money'] = $_var_68;
			$_var_39['order23money'] = $_var_69;
			$_var_39['order33money'] = $_var_70;
			$_var_39['ordercount0'] = $_var_44;
			$_var_39['ordermoney0'] = $_var_45;
			$_var_39['order10'] = $_var_59;
			$_var_39['order20'] = $_var_60;
			$_var_39['order30'] = $_var_61;
			$_var_39['order40'] = $order40;
			$_var_39['commission_total'] = round($_var_50, 2);
			$_var_39['commission_ok'] = round($_var_51, 2);
			$_var_39['commission_lock'] = round($_var_54, 2);
			$_var_39['commission_apply'] = round($_var_52, 2);
			$_var_39['commission_check'] = round($_var_53, 2);
			$_var_39['commission_pay'] = round($_var_55, 2);
			$_var_39['level1'] = $_var_56;
			$_var_39['level1_agentids'] = $_var_80;
			$_var_39['level2'] = $_var_57;
			$_var_39['level2_agentids'] = $_var_87;
			$_var_39['level3'] = $_var_58;
			$_var_39['level3_agentids'] = $_var_94;
			$_var_39['level4'] = $level4;
			$_var_39['level4_agentids'] = $level4_agentids;
			$_var_39['agenttime'] = date('Y-m-d H:i', $_var_39['agenttime']);
			$_var_39['myoedermoney'] = $_var_71;
			$_var_39['myordercount'] = $_var_72;
			return $_var_39;
		}
*/
		public function getAgents($_var_22 = 0)
		{
			global $_W, $_GPC;
			$_var_96 = array();
			$_var_97 = pdo_fetch('select id,agentid,openid from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $_var_22, ':uniacid' => $_W['uniacid']));
			if (empty($_var_97)) {
				return $_var_96;
			}
			
			$_var_30 = m('member')->getMember($_var_97['agentid']);
			
			if (!empty($_var_30) && $_var_30['isagent'] == 1 && $_var_30['status'] == 1) {
				$_var_96[] = $_var_30;//一级 
				if (!empty($_var_30['agentid'])) {
					$_var_32 = m('member')->getMember($_var_30['agentid']);
					if (!empty($_var_32) && $_var_32['isagent'] == 1 && $_var_32['status'] == 1) {
						$_var_96[] = $_var_32;//二级
						if (!empty($_var_32['agentid'])) {
							$_var_34 = m('member')->getMember($_var_32['agentid']);
							if (!empty($_var_34) && $_var_34['isagent'] == 1 && $_var_34['status'] == 1) {
								$_var_96[] = $_var_34;//三级
								if (!empty($_var_34['agentid'])) {
									$siji = m('member')->getMember($_var_34['agentid']);
									if (!empty($siji) && $siji['isagent'] == 1 && $siji['status'] == 1) {
										$_var_96[] = $siji;//四级
										if (!empty($siji['agentid'])) {
											$wuji = m('member')->getMember($siji['agentid']);
											if (!empty($wuji) && $wuji['isagent'] == 1 && $wuji['status'] == 1) {
												$_var_96[] = $wuji;//五级
												if (!empty($wuji['agentid'])) {
													$liuji = m('member')->getMember($wuji['agentid']);
													if (!empty($liuji) && $liuji['isagent'] == 1 && $liuji['status'] == 1) {
														$_var_96[] = $liuji;//六级
														if (!empty($liuji['agentid'])) {
															$qiji = m('member')->getMember($liuji['agentid']);
															if (!empty($qiji) && $qiji['isagent'] == 1 && $qiji['status'] == 1) {
																$_var_96[] = $qiji;//七级
																if (!empty($qiji['agentid'])) {
																	$baji = m('member')->getMember($qiji['agentid']);
																	if (!empty($baji) && $baji['isagent'] == 1 && $baji['status'] == 1) {
																		$_var_96[] = $baji;//八级
																		if (!empty($baji['agentid'])) {
																			$jiuji = m('member')->getMember($baji['agentid']);
																			if (!empty($jiuji) && $jiuji['isagent'] == 1 && $jiuji['status'] == 1) {
																				$_var_96[] = $jiuji;//九级
																				if (!empty($jiuji['agentid'])) {
																					$shiji = m('member')->getMember($jiuji['agentid']);
																					if (!empty($shiji) && $shiji['isagent'] == 1 && $shiji['status'] == 1) {
																						$_var_96[] = $shiji;//十级
																						if (!empty($shiji['agentid'])) {
																							$shiyiji = m('member')->getMember($shiji['agentid']);
																							if (!empty($shiyiji) && $shiyiji['isagent'] == 1 && $shiyiji['status'] == 1) {
																								$_var_96[] = $shiyiji;//十一级
																								if (!empty($shiyiji['agentid'])) {
																									$shierji = m('member')->getMember($shiyiji['agentid']);
																									if (!empty($shierji) && $shierji['isagent'] == 1 && $shierji['status'] == 1) {
																										$_var_96[] = $shierji;//十二级
																										if (!empty($shierji['agentid'])) {
																											$shisanji = m('member')->getMember($shierji['agentid']);
																											if (!empty($shisanji) && $shisanji['isagent'] == 1 && $shisanji['status'] == 1) {
																												$_var_96[] = $shisanji;//十三级
																												if (!empty($shisanji['agentid'])) {
																													$shisiji = m('member')->getMember($shisanji['agentid']);
																													if (!empty($shisiji) && $shisiji['isagent'] == 1 && $shisiji['status'] == 1) {
																														$_var_96[] = $shisiji;//十四级
																														if (!empty($shisiji['agentid'])) {
																															$shiwuji = m('member')->getMember($shisiji['agentid']);
																															if (!empty($shiwuji) && $shiwuji['isagent'] == 1 && $shiwuji['status'] == 1) {
																																$_var_96[] = $shiwuji;//十五级
																																
																															}
																														}
																													}
																												}
																											}
																										}
																									}
																								}
																							}
																						}
																					}
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			return $_var_96;
		}

		public function isAgent($_var_36)
		{
			if (empty($_var_36)) {
				return false;
			}
			if (is_array($_var_36)) {
				return $_var_36['isagent'] == 1 && $_var_36['status'] == 1;
			}
			$_var_39 = m('member')->getMember($_var_36);
			return $_var_39['isagent'] == 1 && $_var_39['status'] == 1;
		}

		public function getCommission($_var_25)
		{
			global $_W;
			$_var_0 = $this->getSet();
			$_var_77 = 0;
			if ($_var_25['hascommission'] == 1) {
				$_var_77 = $_var_0['level'] >= 1 ? ($_var_25['commission1_rate'] > 0 ? ($_var_25['commission1_rate'] * $_var_25['marketprice'] / 100) : $_var_25['commission1_pay']) : 0;
			} else {
				$_var_36 = m('user')->getOpenid();
				$_var_38 = $this->getLevel($_var_36);
				if (!empty($_var_38)) {
					$_var_77 = $_var_0['level'] >= 1 ? round($_var_38['commission1'] * $_var_25['marketprice'] / 100, 2) : 0;
				} else {
					$_var_77 = $_var_0['level'] >= 1 ? round($_var_0['commission1'] * $_var_25['marketprice'] / 100, 2) : 0;
				}
			}
			return $_var_77;
		}

		public function createMyShopQrcode($_var_98 = 0, $_var_99 = 0)
		{
			global $_W;
			$_var_100 = IA_ROOT . '/addons/sz_yi/data/qrcode/' . $_W['uniacid'];
			if (!is_dir($_var_100)) {
				load()->func('file');
				mkdirs($_var_100);
			}
			$_var_101 = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=commission&method=myshop&mid=' . $_var_98;
			if (!empty($_var_99)) {
				$_var_101 .= '&posterid=' . $_var_99;
			}
			$_var_102 = 'myshop_' . $_var_99 . '_' . $_var_98 . '.png';
			$_var_103 = $_var_100 . '/' . $_var_102;
			if (!is_file($_var_103)) {
				require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
				QRcode::png($_var_101, $_var_103, QR_ECLEVEL_H, 4);
			}
			return $_W['siteroot'] . 'addons/sz_yi/data/qrcode/' . $_W['uniacid'] . '/' . $_var_102;
		}

		private function createImage($_var_101)
		{
			load()->func('communication');
			$_var_104 = ihttp_request($_var_101);
			return imagecreatefromstring($_var_104['content']);
		}

		public function createGoodsImage($_var_25, $_var_105)
		{
			global $_W, $_GPC;
			$_var_25 = set_medias($_var_25, 'thumb');
			$_var_36 = m('user')->getOpenid();
			$_var_106 = m('member')->getMember($_var_36);
			if ($_var_106['isagent'] == 1 && $_var_106['status'] == 1) {
				$_var_107 = $_var_106;
			} else {
				$_var_98 = intval($_GPC['mid']);
				if (!empty($_var_98)) {
					$_var_107 = m('member')->getMember($_var_98);
				}
			}
			$_var_100 = IA_ROOT . '/addons/sz_yi/data/poster/' . $_W['uniacid'] . '/';
			if (!is_dir($_var_100)) {
				load()->func('file');
				mkdirs($_var_100);
			}
			$_var_108 = empty($_var_25['commission_thumb']) ? $_var_25['thumb'] : tomedia($_var_25['commission_thumb']);
			$_var_109 = md5(json_encode(array('id' => $_var_25['id'], 'marketprice' => $_var_25['marketprice'], 'productprice' => $_var_25['productprice'], 'img' => $_var_108, 'openid' => $_var_36, 'version' => 4)));
			$_var_102 = $_var_109 . '.jpg';
			if (!is_file($_var_100 . $_var_102)) {
				set_time_limit(0);
				$_var_110 = IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';
				$_var_111 = imagecreatetruecolor(640, 1225);
				if (!is_weixin()) {
					$_var_112 = 196;
					$_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster_pc.jpg');
				} else {
					$_var_112 = 50;
					$_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster.jpg');
				}
				$_var_114 = $_var_107['realname'] ? $_var_107['realname'] : $_var_107['nickname'];
				$_var_114 = $_var_114 ? $_var_114 : $_var_107['mobile'];
				imagecopy($_var_111, $_var_113, 0, 0, 0, 0, 640, 1225);
				imagedestroy($_var_113);
				$_var_115 = preg_replace('/\\/0$/i', '/96', $_var_107['avatar']);
				$_var_116 = $this->createImage($_var_115);
				$_var_117 = imagesx($_var_116);
				$_var_118 = imagesy($_var_116);
				imagecopyresized($_var_111, $_var_116, 24, 32, 0, 0, 88, 88, $_var_117, $_var_118);
				imagedestroy($_var_116);
				$_var_119 = $this->createImage($_var_108);
				$_var_117 = imagesx($_var_119);
				$_var_118 = imagesy($_var_119);
				imagecopyresized($_var_111, $_var_119, 0, 160, 0, 0, 640, 640, $_var_117, $_var_118);
				imagedestroy($_var_119);
				$_var_120 = imagecreatetruecolor(640, 127);
				imagealphablending($_var_120, false);
				imagesavealpha($_var_120, true);
				$_var_121 = imagecolorallocatealpha($_var_120, 0, 0, 0, 25);
				imagefill($_var_120, 0, 0, $_var_121);
				imagecopy($_var_111, $_var_120, 0, 678, 0, 0, 640, 127);
				imagedestroy($_var_120);
				$_var_122 = tomedia(m('qrcode')->createGoodsQrcode($_var_107['id'], $_var_25['id']));
				$_var_123 = $this->createImage($_var_122);
				$_var_117 = imagesx($_var_123);
				$_var_118 = imagesy($_var_123);
				imagecopyresized($_var_111, $_var_123, $_var_112, 835, 0, 0, 250, 250, $_var_117, $_var_118);
				imagedestroy($_var_123);
				$_var_124 = imagecolorallocate($_var_111, 0, 3, 51);
				$_var_125 = imagecolorallocate($_var_111, 240, 102, 0);
				$_var_126 = imagecolorallocate($_var_111, 255, 255, 255);
				$_var_127 = imagecolorallocate($_var_111, 255, 255, 0);
				$_var_128 = '我是';
				imagettftext($_var_111, 20, 0, 150, 70, $_var_124, $_var_110, $_var_128);
				imagettftext($_var_111, 20, 0, 210, 70, $_var_125, $_var_110, $_var_114);
				$_var_129 = '我要为';
				imagettftext($_var_111, 20, 0, 150, 105, $_var_124, $_var_110, $_var_129);
				$_var_130 = $_var_105['name'];
				imagettftext($_var_111, 20, 0, 240, 105, $_var_125, $_var_110, $_var_130);
				$_var_131 = imagettfbbox(20, 0, $_var_110, $_var_130);
				$_var_132 = $_var_131[4] - $_var_131[6];
				$_var_133 = '代言';
				imagettftext($_var_111, 20, 0, 240 + $_var_132 + 10, 105, $_var_124, $_var_110, $_var_133);
				$_var_134 = mb_substr($_var_25['title'], 0, 50, 'utf-8');
				imagettftext($_var_111, 20, 0, 30, 730, $_var_126, $_var_110, $_var_134);
				$_var_135 = '￥' . number_format($_var_25['marketprice'], 2);
				imagettftext($_var_111, 25, 0, 25, 780, $_var_127, $_var_110, $_var_135);
				$_var_131 = imagettfbbox(26, 0, $_var_110, $_var_135);
				$_var_132 = $_var_131[4] - $_var_131[6];
				if ($_var_25['productprice'] > 0) {
					$_var_136 = '￥' . number_format($_var_25['productprice'], 2);
					imagettftext($_var_111, 22, 0, 25 + $_var_132 + 10, 780, $_var_126, $_var_110, $_var_136);
					$_var_137 = 25 + $_var_132 + 10;
					$_var_131 = imagettfbbox(22, 0, $_var_110, $_var_136);
					$_var_132 = $_var_131[4] - $_var_131[6];
					imageline($_var_111, $_var_137, 770, $_var_137 + $_var_132 + 20, 770, $_var_126);
					imageline($_var_111, $_var_137, 771.5, $_var_137 + $_var_132 + 20, 771, $_var_126);
				}
				imagejpeg($_var_111, $_var_100 . $_var_102);
				imagedestroy($_var_111);
			}
			return $_W['siteroot'] . 'addons/sz_yi/data/poster/' . $_W['uniacid'] . '/' . $_var_102;
		}

		public function createShopImage($_var_105)
		{
			global $_W, $_GPC;
			$_var_105 = set_medias($_var_105, 'signimg');
			$_var_100 = IA_ROOT . '/addons/sz_yi/data/poster/' . $_W['uniacid'] . '/';
			if (!is_dir($_var_100)) {
				load()->func('file');
				mkdirs($_var_100);
			}
			$_var_98 = intval($_GPC['mid']);
			$_var_36 = m('user')->getOpenid();
			$_var_106 = m('member')->getMember($_var_36);
			if ($_var_106['isagent'] == 1 && $_var_106['status'] == 1) {
				$_var_107 = $_var_106;
			} else {
				$_var_98 = intval($_GPC['mid']);
				if (!empty($_var_98)) {
					$_var_107 = m('member')->getMember($_var_98);
				}
			}
			$_var_109 = md5(json_encode(array('openid' => $_var_36, 'signimg' => $_var_105['signimg'], 'version' => 4)));
			$_var_102 = $_var_109 . '.jpg';
			if (!is_file($_var_100 . $_var_102)) {
				set_time_limit(0);
				@ini_set('memory_limit', '256M');
				$_var_110 = IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';
				$_var_111 = imagecreatetruecolor(640, 1225);
				$_var_124 = imagecolorallocate($_var_111, 0, 3, 51);
				$_var_125 = imagecolorallocate($_var_111, 240, 102, 0);
				$_var_126 = imagecolorallocate($_var_111, 255, 255, 255);
				$_var_127 = imagecolorallocate($_var_111, 255, 255, 0);
				if (!is_weixin()) {
					$_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster_pc.jpg');
					$_var_112 = 196;
				} else {
					$_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster.jpg');
					$_var_112 = 50;
				}
				$_var_114 = $_var_107['realname'] ? $_var_107['realname'] : $_var_107['nickname'];
				$_var_114 = $_var_114 ? $_var_114 : $_var_107['mobile'];
				imagecopy($_var_111, $_var_113, 0, 0, 0, 0, 640, 1225);
				imagedestroy($_var_113);
				$_var_115 = preg_replace('/\\/0$/i', '/96', $_var_107['avatar']);
				$_var_116 = $this->createImage($_var_115);
				$_var_117 = imagesx($_var_116);
				$_var_118 = imagesy($_var_116);
				imagecopyresized($_var_111, $_var_116, 24, 32, 0, 0, 88, 88, $_var_117, $_var_118);
				imagedestroy($_var_116);
				$_var_119 = $this->createImage($_var_105['signimg']);
				$_var_117 = imagesx($_var_119);
				$_var_118 = imagesy($_var_119);
				imagecopyresized($_var_111, $_var_119, 0, 160, 0, 0, 640, 640, $_var_117, $_var_118);
				imagedestroy($_var_119);
				$_var_138 = tomedia($this->createMyShopQrcode($_var_107['id']));
				$_var_123 = $this->createImage($_var_138);
				$_var_117 = imagesx($_var_123);
				$_var_118 = imagesy($_var_123);
				imagecopyresized($_var_111, $_var_123, $_var_112, 835, 0, 0, 250, 250, $_var_117, $_var_118);
				imagedestroy($_var_123);
				$_var_128 = '我是';
				imagettftext($_var_111, 20, 0, 150, 70, $_var_124, $_var_110, $_var_128);
				imagettftext($_var_111, 20, 0, 210, 70, $_var_125, $_var_110, $_var_114);
				$_var_129 = '我要为';
				imagettftext($_var_111, 20, 0, 150, 105, $_var_124, $_var_110, $_var_129);
				$_var_130 = $_var_105['name'];
				imagettftext($_var_111, 20, 0, 240, 105, $_var_125, $_var_110, $_var_130);
				$_var_131 = imagettfbbox(20, 0, $_var_110, $_var_130);
				$_var_132 = $_var_131[4] - $_var_131[6];
				$_var_133 = '代言';
				imagettftext($_var_111, 20, 0, 240 + $_var_132 + 10, 105, $_var_124, $_var_110, $_var_133);
				imagejpeg($_var_111, $_var_100 . $_var_102);
				imagedestroy($_var_111);
			}
			return $_W['siteroot'] . 'addons/sz_yi/data/poster/' . $_W['uniacid'] . '/' . $_var_102;
		}

		public function checkAgent()
		{
			global $_W, $_GPC;
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return;
			}
			$_var_36 = m('user')->getOpenid();
			if (empty($_var_36)) {
				return;
			}
			$_var_39 = m('member')->getMember($_var_36);
			if (empty($_var_39)) {
				return;
			}
			$_var_139 = false;
			$_var_98 = intval($_GPC['mid']);
			if (!empty($_var_98)) {
				$_var_139 = m('member')->getMember($_var_98);
			}
			$_var_140 = !empty($_var_139) && $_var_139['isagent'] == 1 && $_var_139['status'] == 1;
			if ($_var_140) {
				if ($_var_139['openid'] != $_var_36) {
					$_var_141 = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_commission_clickcount') . ' where uniacid=:uniacid and openid=:openid and from_openid=:from_openid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_36, ':from_openid' => $_var_139['openid']));
					if ($_var_141 <= 0) {
						$_var_142 = array('uniacid' => $_W['uniacid'], 'openid' => $_var_36, 'from_openid' => $_var_139['openid'], 'clicktime' => time());
						pdo_insert('sz_yi_commission_clickcount', $_var_142);
						pdo_update('sz_yi_member', array('clickcount' => $_var_139['clickcount'] + 1), array('uniacid' => $_W['uniacid'], 'id' => $_var_139['id']));
					}
				}
			}
			if ($_var_39['isagent'] == 1) {
				return;
			}
			if ($_var_143 == 0) {
				$_var_144 = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . ' where id<:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $_var_39['id']));
				if ($_var_144 <= 0) {
					pdo_update('sz_yi_member', array('isagent' => 1, 'status' => 1, 'agenttime' => time(), 'agentblack' => 0), array('uniacid' => $_W['uniacid'], 'id' => $_var_39['id']));
					return;
				}
			}
			$_var_41 = time();
			$_var_145 = intval($_var_0['become_child']);
			if ($_var_140 && empty($_var_39['agentid'])) {
				if ($_var_39['id'] != $_var_139['id']) {
					if (empty($_var_145)) {
						if (empty($_var_39['fixagentid'])) {
							pdo_update('sz_yi_member', array('agentid' => $_var_139['id'], 'childtime' => $_var_41), array('uniacid' => $_W['uniacid'], 'id' => $_var_39['id']));
							$this->sendMessage($_var_139['openid'], array('nickname' => $_var_39['nickname'], 'childtime' => $_var_41), TM_COMMISSION_AGENT_NEW);
							$this->upgradeLevelByAgent($_var_139['id']);
						}
					} else {
						pdo_update('sz_yi_member', array('inviter' => $_var_139['id']), array('uniacid' => $_W['uniacid'], 'id' => $_var_39['id']));
					}
				}
			}
			$_var_146 = intval($_var_0['become_check']);
			if (empty($_var_0['become'])) {
				if (empty($_var_39['agentblack'])) {
					pdo_update('sz_yi_member', array('isagent' => 1, 'status' => $_var_146, 'agenttime' => $_var_146 == 1 ? $_var_41 : 0), array('uniacid' => $_W['uniacid'], 'id' => $_var_39['id']));
					if ($_var_146 == 1) {
						$this->sendMessage($_var_36, array('nickname' => $_var_39['nickname'], 'agenttime' => $_var_41), TM_COMMISSION_BECOME);
						if ($_var_140) {
							$this->upgradeLevelByAgent($_var_139['id']);
						}
					}
				}
			}
		}

		public function checkOrderConfirm($_var_1 = '0')
		{
			global $_W, $_GPC;
			if (empty($_var_1)) {
				return;
			}
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return;
			}
			$_var_147 = pdo_fetch('select id,openid,ordersn,goodsprice,agentid,paytime from ' . tablename('sz_yi_order') . ' where id=:id and status>=0 and uniacid=:uniacid limit 1', array(':id' => $_var_1, ':uniacid' => $_W['uniacid']));
			if (empty($_var_147)) {
				return;
			}
			$_var_148 = $_var_147['openid'];
			$_var_149 = m('member')->getMember($_var_148);
			if (empty($_var_149)) {
				return;
			}
			$_var_150 = p('bonus');
			if (!empty($_var_150)) {
				$_var_151 = $_var_150->getSet();
				if (!empty($_var_151['start'])) {
					$_var_150->checkOrderConfirm($_var_1);
				}
			}
			$_var_152 = intval($_var_0['become_child']);
			$_var_153 = false;
			if (empty($_var_152)) {
				$_var_153 = m('member')->getMember($_var_149['agentid']);
			} else {
				$_var_153 = m('member')->getMember($_var_149['inviter']);
			}
			$_var_154 = !empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1;
			$_var_155 = time();
			$_var_152 = intval($_var_0['become_child']);
			if ($_var_154) {
				if ($_var_152 == 1) {
					if (empty($_var_149['agentid']) && $_var_149['id'] != $_var_153['id']) {
						if (empty($_var_149['fixagentid'])) {
							$_var_149['agentid'] = $_var_153['id'];
							pdo_update('sz_yi_member', array(
									'agentid' => $_var_153['id'], 
									'childtime' => $_var_155
								), array(
									'uniacid' => $_W['uniacid'], 
									'id' => $_var_149['id']
								));
							$this->sendMessage(
								$_var_153['openid'], array(
									'nickname' => $_var_149['nickname'], 
									'childtime' => $_var_155
								), TM_COMMISSION_AGENT_NEW
							);
							$this->upgradeLevelByAgent($_var_153['id']);
						}
					}
				}
			}
			$_var_4 = $_var_149['agentid'];
			if ($_var_149['isagent'] == 1 && $_var_149['status'] == 1) {
				if (!empty($_var_0['selfbuy'])) {
					$_var_4 = $_var_149['id'];
				}
			}
			if (!empty($_var_4)) {
				pdo_update('sz_yi_order', array('agentid' => $_var_4), array('id' => $_var_1));
			}
			$this->calculate($_var_1);
		}

		public function checkOrderPay($_var_1 = '0')
		{
			global $_W, $_GPC;
			if (empty($_var_1)) {
				return;
			}
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return;
			}
			$_var_147 = pdo_fetch('select id,openid,ordersn,goodsprice,agentid,paytime from ' . tablename('sz_yi_order') . ' where id=:id and status>=1 and uniacid=:uniacid limit 1', array(':id' => $_var_1, ':uniacid' => $_W['uniacid']));
			if (empty($_var_147)) {
				return;
			}
			$_var_148 = $_var_147['openid'];
			$_var_149 = m('member')->getMember($_var_148);
			if (empty($_var_149)) {
				return;
			}
			$_var_150 = p('bonus');
			if (!empty($_var_150)) {
				$_var_151 = $_var_150->getSet();
				if (!empty($_var_151['start'])) {
					$_var_150->checkOrderPay($_var_1);
				}
			}
			$_var_152 = intval($_var_0['become_child']);
			$_var_153 = false;
			if (empty($_var_152)) {
				$_var_153 = m('member')->getMember($_var_149['agentid']);
			} else {
				$_var_153 = m('member')->getMember($_var_149['inviter']);
			}
			$_var_154 = !empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1;
			$_var_155 = time();
			$_var_152 = intval($_var_0['become_child']);
			if ($_var_154) {
				if ($_var_152 == 2) {
					if (empty($_var_149['agentid']) && $_var_149['id'] != $_var_153['id']) {
						if (empty($_var_149['fixagentid'])) {
							$_var_149['agentid'] = $_var_153['id'];
							pdo_update('sz_yi_member', array('agentid' => $_var_153['id'], 'childtime' => $_var_155), array('uniacid' => $_W['uniacid'], 'id' => $_var_149['id']));
							$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'childtime' => $_var_155), TM_COMMISSION_AGENT_NEW);
							$this->upgradeLevelByAgent($_var_153['id']);
							if (empty($_var_147['agentid'])) {
								$_var_147['agentid'] = $_var_153['id'];
								pdo_update('sz_yi_order', array('agentid' => $_var_153['id']), array('id' => $_var_1));
								$this->calculate($_var_1);
							}
						}
					}
				}
			}
			$_var_156 = $_var_149['isagent'] == 1 && $_var_149['status'] == 1;
			if (!$_var_156) {
				if (intval($_var_0['become']) == 4 && !empty($_var_0['become_goodsid'])) {
					$_var_157 = pdo_fetchall('select goodsid from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid and uniacid=:uniacid  ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']), 'goodsid');
					if (in_array($_var_0['become_goodsid'], array_keys($_var_157))) {
						if (empty($_var_149['agentblack'])) {
							pdo_update('sz_yi_member', array('status' => 1, 'isagent' => 1, 'agenttime' => $_var_155), array('uniacid' => $_W['uniacid'], 'id' => $_var_149['id']));
							$this->sendMessage($_var_148, array('nickname' => $_var_149['nickname'], 'agenttime' => $_var_155), TM_COMMISSION_BECOME);
							if (!empty($_var_153)) {
								$this->upgradeLevelByAgent($_var_153['id']);
							}
						}
					}
				}
			}
			if (!$_var_156 && empty($_var_0['become_order'])) {
				$_var_155 = time();
				if ($_var_0['become'] == 2 || $_var_0['become'] == 3) {
					$_var_158 = true;
					if (!empty($_var_149['agentid'])) {
						$_var_153 = m('member')->getMember($_var_149['agentid']);
						if (empty($_var_153) || $_var_153['isagent'] != 1 || $_var_153['status'] != 1) {
							$_var_158 = false;
						}
					}
					if ($_var_158) {
						$_var_159 = false;
						if ($_var_0['become'] == '2') {
							$_var_160 = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=1 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_148));
							$_var_159 = $_var_160 >= intval($_var_0['become_ordercount']);
						} else if ($_var_0['become'] == '3') {
							$_var_161 = pdo_fetchcolumn('select sum(og.realprice) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on og.orderid=o.id  where o.openid=:openid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_148));
							$_var_159 = $_var_161 >= floatval($_var_0['become_moneycount']);
						}
						if ($_var_159) {
							if (empty($_var_149['agentblack'])) {
								$_var_162 = intval($_var_0['become_check']);
								pdo_update('sz_yi_member', array('status' => $_var_162, 'isagent' => 1, 'agenttime' => $_var_155), array('uniacid' => $_W['uniacid'], 'id' => $_var_149['id']));
								if ($_var_162 == 1) {
									$this->sendMessage($_var_148, array('nickname' => $_var_149['nickname'], 'agenttime' => $_var_155), TM_COMMISSION_BECOME);
									if ($_var_158) {
										$this->upgradeLevelByAgent($_var_153['id']);
									}
								}
							}
						}
					}
				}
			}
			if (!empty($_var_147['agentid'])) {
				$_var_153 = m('member')->getMember($_var_147['agentid']);
				if (!empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1) {
					if ($_var_147['agentid'] == $_var_153['id']) {
						$_var_16 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']));
						$_var_5 = '';
						$_var_8 = $_var_153['agentlevel'];
						$_var_163 = 0;
						$_var_164 = 0;
						foreach ($_var_16 as $_var_165) {
							$_var_5 .= "" . $_var_165['title'] . '( ';
							if (!empty($_var_165['optiontitle'])) {
								$_var_5 .= ' 规格: ' . $_var_165['optiontitle'];
							}
							$_var_5 .= ' 单价: ' . ($_var_165['realprice'] / $_var_165['total']) . ' 数量: ' . $_var_165['total'] . ' 总价: ' . $_var_165['realprice'] . '); ';
							$_var_166 = iunserializer($_var_165['commission1']);
							$_var_163 += isset($_var_166['level' . $_var_8]) ? $_var_166['level' . $_var_8] : $_var_166['default'];
							$_var_164 += $_var_165['realprice'];
						}
						$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'ordersn' => $_var_147['ordersn'], 'price' => $_var_164, 'goods' => $_var_5, 'commission' => $_var_163, 'paytime' => $_var_147['paytime'],), TM_COMMISSION_ORDER_PAY);
					}
				}
				if (!empty($_var_0['remind_message']) && $_var_0['level'] >= 2) {
					if (!empty($_var_153['agentid'])) {
						$_var_153 = m('member')->getMember($_var_153['agentid']);
						if (!empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1) {
							if ($_var_147['agentid'] != $_var_153['id']) {
								$_var_16 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission2 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']));
								$_var_5 = '';
								$_var_8 = $_var_153['agentlevel'];
								$_var_163 = 0;
								$_var_164 = 0;
								foreach ($_var_16 as $_var_165) {
									$_var_5 .= "" . $_var_165['title'] . '( ';
									if (!empty($_var_165['optiontitle'])) {
										$_var_5 .= ' 规格: ' . $_var_165['optiontitle'];
									}
									$_var_5 .= ' 单价: ' . ($_var_165['realprice'] / $_var_165['total']) . ' 数量: ' . $_var_165['total'] . ' 总价: ' . $_var_165['realprice'] . '); ';
									$_var_166 = iunserializer($_var_165['commission2']);
									$_var_163 += isset($_var_166['level' . $_var_8]) ? $_var_166['level' . $_var_8] : $_var_166['default'];
									$_var_164 += $_var_165['realprice'];
								}
								$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'ordersn' => $_var_147['ordersn'], 'price' => $_var_164, 'goods' => $_var_5, 'commission' => $_var_163, 'paytime' => $_var_147['paytime'],), TM_COMMISSION_ORDER_PAY);
							}
						}
						if (!empty($_var_153['agentid']) && $_var_0['level'] >= 3) {
							$_var_153 = m('member')->getMember($_var_153['agentid']);
							if (!empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1) {
								if ($_var_147['agentid'] != $_var_153['id']) {
									$_var_16 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission3 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']));
									$_var_5 = '';
									$_var_8 = $_var_153['agentlevel'];
									$_var_163 = 0;
									$_var_164 = 0;
									foreach ($_var_16 as $_var_165) {
										$_var_5 .= "" . $_var_165['title'] . '( ';
										if (!empty($_var_165['optiontitle'])) {
											$_var_5 .= ' 规格: ' . $_var_165['optiontitle'];
										}
										$_var_5 .= ' 单价: ' . ($_var_165['realprice'] / $_var_165['total']) . ' 数量: ' . $_var_165['total'] . ' 总价: ' . $_var_165['realprice'] . '); ';
										$_var_166 = iunserializer($_var_165['commission3']);
										$_var_163 += isset($_var_166['level' . $_var_8]) ? $_var_166['level' . $_var_8] : $_var_166['default'];
										$_var_164 += $_var_165['realprice'];
									}
									$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'ordersn' => $_var_147['ordersn'], 'price' => $_var_164, 'goods' => $_var_5, 'commission' => $_var_163, 'paytime' => $_var_147['paytime'],), TM_COMMISSION_ORDER_PAY);
								}
							}
						}
					}
				}
			}
		}

		public function checkOrderFinish($_var_1 = '')
		{
			global $_W, $_GPC;
			if (empty($_var_1)) {
				return;
			}
			$_var_147 = pdo_fetch('select id,openid, ordersn,goodsprice,agentid,finishtime from ' . tablename('sz_yi_order') . ' where id=:id and status>=3 and uniacid=:uniacid limit 1', array(':id' => $_var_1, ':uniacid' => $_W['uniacid']));
			if (empty($_var_147)) {
				return;
			}
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return;
			}
			$_var_148 = $_var_147['openid'];
			$_var_149 = m('member')->getMember($_var_148);
			if (empty($_var_149)) {
				return;
			}
			$_var_150 = p('bonus');
			if (!empty($_var_150)) {
				$_var_151 = $_var_150->getSet();
				if (!empty($_var_151['start'])) {
					$_var_150->checkOrderFinish($_var_1);
				}
			}
			$_var_155 = time();
			$_var_156 = $_var_149['isagent'] == 1 && $_var_149['status'] == 1;
			if (!$_var_156 && $_var_0['become_order'] == 1) {
				if ($_var_0['become'] == 2 || $_var_0['become'] == 3) {
					$_var_158 = true;
					if (!empty($_var_149['agentid'])) {
						$_var_153 = m('member')->getMember($_var_149['agentid']);
						if (empty($_var_153) || $_var_153['isagent'] != 1 || $_var_153['status'] != 1) {
							$_var_158 = false;
						}
					}
					if ($_var_158) {
						$_var_159 = false;
						if ($_var_0['become'] == '2') {
							$_var_160 = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=3 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_148));
							$_var_159 = $_var_160 >= intval($_var_0['become_ordercount']);
						} else if ($_var_0['become'] == '3') {
							$_var_161 = pdo_fetchcolumn('select sum(goodsprice) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=3 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_148));
							$_var_159 = $_var_161 >= floatval($_var_0['become_moneycount']);
						}
						if ($_var_159) {
							if (empty($_var_149['agentblack'])) {
								$_var_162 = intval($_var_0['become_check']);
								pdo_update('sz_yi_member', array('status' => $_var_162, 'isagent' => 1, 'agenttime' => $_var_155), array('uniacid' => $_W['uniacid'], 'id' => $_var_149['id']));
								if ($_var_162 == 1) {
									$this->sendMessage($_var_149['openid'], array('nickname' => $_var_149['nickname'], 'agenttime' => $_var_155), TM_COMMISSION_BECOME);
									if ($_var_158) {
										$this->upgradeLevelByAgent($_var_153['id']);
									}
								}
							}
						}
					}
				}
			}
			if (!empty($_var_147['agentid'])) {
				$_var_153 = m('member')->getMember($_var_147['agentid']);
				if (!empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1) {
					if ($_var_147['agentid'] == $_var_153['id']) {
						$_var_16 = pdo_fetchall('select g.id,g.title,og.total,og.realprice,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']));
						$_var_5 = '';
						$_var_8 = $_var_153['agentlevel'];
						$_var_163 = 0;
						$_var_164 = 0;
						foreach ($_var_16 as $_var_165) {
							$_var_5 .= "" . $_var_165['title'] . '( ';
							if (!empty($_var_165['optiontitle'])) {
								$_var_5 .= ' 规格: ' . $_var_165['optiontitle'];
							}
							$_var_5 .= ' 单价: ' . ($_var_165['realprice'] / $_var_165['total']) . ' 数量: ' . $_var_165['total'] . ' 总价: ' . $_var_165['realprice'] . '); ';
							$_var_166 = iunserializer($_var_165['commission1']);
							$_var_163 += isset($_var_166['level' . $_var_8]) ? $_var_166['level' . $_var_8] : $_var_166['default'];
							$_var_164 += $_var_165['realprice'];
						}
						$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'ordersn' => $_var_147['ordersn'], 'price' => $_var_164, 'goods' => $_var_5, 'commission' => $_var_163, 'finishtime' => $_var_147['finishtime'],), TM_COMMISSION_ORDER_FINISH);
					}
				}
				if (!empty($_var_0['remind_message']) && $_var_0['level'] >= 2) {
					if (!empty($_var_153['agentid'])) {
						$_var_153 = m('member')->getMember($_var_153['agentid']);
						if (!empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1) {
							if ($_var_147['agentid'] != $_var_153['id']) {
								$_var_16 = pdo_fetchall('select g.id,g.title,og.total,og.realprice,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission2 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']));
								$_var_5 = '';
								$_var_8 = $_var_153['agentlevel'];
								$_var_163 = 0;
								$_var_164 = 0;
								foreach ($_var_16 as $_var_165) {
									$_var_5 .= "" . $_var_165['title'] . '( ';
									if (!empty($_var_165['optiontitle'])) {
										$_var_5 .= ' 规格: ' . $_var_165['optiontitle'];
									}
									$_var_5 .= ' 单价: ' . ($_var_165['realprice'] / $_var_165['total']) . ' 数量: ' . $_var_165['total'] . ' 总价: ' . $_var_165['realprice'] . '); ';
									$_var_166 = iunserializer($_var_165['commission2']);
									$_var_163 += isset($_var_166['level' . $_var_8]) ? $_var_166['level' . $_var_8] : $_var_166['default'];
									$_var_164 += $_var_165['realprice'];
								}
								$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'ordersn' => $_var_147['ordersn'], 'price' => $_var_164, 'goods' => $_var_5, 'commission' => $_var_163, 'finishtime' => $_var_147['finishtime'],), TM_COMMISSION_ORDER_FINISH);
							}
						}
						if (!empty($_var_153['agentid']) && $_var_0['level'] >= 3) {
							$_var_153 = m('member')->getMember($_var_153['agentid']);
							if (!empty($_var_153) && $_var_153['isagent'] == 1 && $_var_153['status'] == 1) {
								if ($_var_147['agentid'] != $_var_153['id']) {
									$_var_16 = pdo_fetchall('select g.id,g.title,og.total,og.realprice,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission3 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_147['id']));
									$_var_5 = '';
									$_var_8 = $_var_153['agentlevel'];
									$_var_163 = 0;
									$_var_164 = 0;
									foreach ($_var_16 as $_var_165) {
										$_var_5 .= "" . $_var_165['title'] . '( ';
										if (!empty($_var_165['optiontitle'])) {
											$_var_5 .= ' 规格: ' . $_var_165['optiontitle'];
										}
										$_var_5 .= ' 单价: ' . ($_var_165['realprice'] / $_var_165['total']) . ' 数量: ' . $_var_165['total'] . ' 总价: ' . $_var_165['realprice'] . '); ';
										$_var_166 = iunserializer($_var_165['commission3']);
										$_var_163 += isset($_var_166['level' . $_var_8]) ? $_var_166['level' . $_var_8] : $_var_166['default'];
										$_var_164 += $_var_165['realprice'];
									}
									$this->sendMessage($_var_153['openid'], array('nickname' => $_var_149['nickname'], 'ordersn' => $_var_147['ordersn'], 'price' => $_var_164, 'goods' => $_var_5, 'commission' => $_var_163, 'finishtime' => $_var_147['finishtime'],), TM_COMMISSION_ORDER_FINISH);
								}
							}
						}
					}
				}
			}
			$this->upgradeLevelByOrder($_var_148);
			$this->upgradeLevelByGood($_var_1);
		}

		function getShop($_var_167)
		{
			global $_W;
			$_var_39 = m('member')->getMember($_var_167);
			$_var_168 = pdo_fetch('select * from ' . tablename('sz_yi_commission_shop') . ' where uniacid=:uniacid and mid=:mid limit 1', array(':uniacid' => $_W['uniacid'], ':mid' => $_var_39['id']));
			$_var_169 = m('common')->getSysset(array('shop', 'share'));
			$_var_0 = $_var_169['shop'];
			$_var_170 = $_var_169['share'];
			$_var_171 = $_var_170['desc'];
			if (empty($_var_171)) {
				$_var_171 = $_var_0['description'];
			}
			if (empty($_var_171)) {
				$_var_171 = $_var_0['name'];
			}
			$_var_172 = $this->getSet();
			if (empty($_var_168)) {
				$_var_168 = array('name' => $_var_39['nickname'] . '的' . $_var_172['texts']['shop'], 'logo' => $_var_39['avatar'], 'desc' => $_var_171, 'img' => tomedia($_var_0['img']),);
			} else {
				if (empty($_var_168['name'])) {
					$_var_168['name'] = $_var_39['nickname'] . '的' . $_var_172['texts']['shop'];
				}
				if (empty($_var_168['logo'])) {
					$_var_168['logo'] = tomedia($_var_39['avatar']);
				}
				if (empty($_var_168['img'])) {
					$_var_168['img'] = tomedia($_var_0['img']);
				}
				if (empty($_var_168['desc'])) {
					$_var_168['desc'] = $_var_171;
				}
			}
			return $_var_168;
		}

		function getLevels($_var_173 = true)
		{
			global $_W;
			if ($_var_173) {
				return pdo_fetchall('select * from ' . tablename('sz_yi_commission_level') . ' where uniacid=:uniacid order by commission1 asc', array(':uniacid' => $_W['uniacid']));
			} else {
				return pdo_fetchall('select * from ' . tablename('sz_yi_commission_level') . ' where uniacid=:uniacid and (ordermoney>0 or commissionmoney>0) order by commission1 asc', array(':uniacid' => $_W['uniacid']));
			}
		}

		function getLevel($_var_36)
		{
			global $_W;
			if (empty($_var_36)) {
				return false;
			}
			$_var_39 = m('member')->getMember($_var_36);
			if (empty($_var_39['agentlevel'])) {
				return false;
			}
			$_var_38 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $_var_39['agentlevel']));
			return $_var_38;
		}

		function upgradeLevelByOrder($_var_36)
		{
			global $_W;
			if (empty($_var_36)) {
				return false;
			}
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return false;
			}
			$_var_167 = m('member')->getMember($_var_36);
			if (empty($_var_167)) {
				return;
			}
			$_var_150 = p('bonus');
			if (!empty($_var_150)) {
				$_var_151 = $_var_150->getSet();
				if (!empty($_var_151['start'])) {
					$_var_150->upgradeLevelByAgent($_var_36);
				}
			}
			$_var_174 = intval($_var_0['leveltype']);
			if ($_var_174 == 4 || $_var_174 == 5) {
				if (!empty($_var_167['agentnotupgrade'])) {
					return;
				}
				$_var_175 = $this->getLevel($_var_167['openid']);
				if (empty($_var_175['id'])) {
					$_var_175 = array('levelname' => empty($_var_0['levelname']) ? '普通等级' : $_var_0['levelname'], 'commission1' => $_var_0['commission1'], 'commission2' => $_var_0['commission2'], 'commission3' => $_var_0['commission3']);
				}
				$_var_176 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_var_36));
				$_var_47 = $_var_176['ordermoney'];
				$_var_46 = $_var_176['ordercount'];
				if ($_var_174 == 4) {
					$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_47} >= ordermoney and ordermoney>0  order by ordermoney desc limit 1", array(':uniacid' => $_W['uniacid']));
					if (empty($_var_177)) {
						return;
					}
					if (!empty($_var_175['id'])) {
						if ($_var_175['id'] == $_var_177['id']) {
							return;
						}
						if ($_var_175['ordermoney'] > $_var_177['ordermoney']) {
							return;
						}
					}
				} else if ($_var_174 == 5) {
					$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_46} >= ordercount and ordercount>0  order by ordercount desc limit 1", array(':uniacid' => $_W['uniacid']));
					if (empty($_var_177)) {
						return;
					}
					if (!empty($_var_175['id'])) {
						if ($_var_175['id'] == $_var_177['id']) {
							return;
						}
						if ($_var_175['ordercount'] > $_var_177['ordercount']) {
							return;
						}
					}
				}
				pdo_update('sz_yi_member', array('agentlevel' => $_var_177['id']), array('id' => $_var_167['id']));
				$this->sendMessage($_var_167['openid'], array('nickname' => $_var_167['nickname'], 'oldlevel' => $_var_175, 'newlevel' => $_var_177,), TM_COMMISSION_UPGRADE);
			} else if ($_var_174 >= 0 && $_var_174 <= 3) {
				$_var_96 = array();
				if (!empty($_var_0['selfbuy'])) {
					$_var_96[] = $_var_167;
				}
				if (!empty($_var_167['agentid'])) {
					$_var_30 = m('member')->getMember($_var_167['agentid']);
					if (!empty($_var_30)) {
						$_var_96[] = $_var_30;
						if (!empty($_var_30['agentid']) && $_var_30['isagent'] == 1 && $_var_30['status'] == 1) {
							$_var_32 = m('member')->getMember($_var_30['agentid']);
							if (!empty($_var_32) && $_var_32['isagent'] == 1 && $_var_32['status'] == 1) {
								$_var_96[] = $_var_32;
								if (empty($_var_0['selfbuy'])) {
									if (!empty($_var_32['agentid']) && $_var_32['isagent'] == 1 && $_var_32['status'] == 1) {
										$_var_34 = m('member')->getMember($_var_32['agentid']);
										if (!empty($_var_34) && $_var_34['isagent'] == 1 && $_var_34['status'] == 1) {
											$_var_96[] = $_var_34;
										}
									}
								}
							}
						}
					}
				}
				if (empty($_var_96)) {
					return;
				}
				foreach ($_var_96 as $_var_178) {
					$_var_179 = $this->getInfo($_var_178['id'], array('ordercount3', 'ordermoney3', 'order13money', 'order13'));
					if (!empty($_var_179['agentnotupgrade'])) {
						continue;
					}
					$_var_175 = $this->getLevel($_var_178['openid']);
					if (empty($_var_175['id'])) {
						$_var_175 = array('levelname' => empty($_var_0['levelname']) ? '普通等级' : $_var_0['levelname'], 'commission1' => $_var_0['commission1'], 'commission2' => $_var_0['commission2'], 'commission3' => $_var_0['commission3']);
					}
					if ($_var_174 == 0) {
						$_var_47 = $_var_179['ordermoney3'];
						$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid and {$_var_47} >= ordermoney and ordermoney>0  order by ordermoney desc limit 1", array(':uniacid' => $_W['uniacid']));
						if (empty($_var_177)) {
							continue;
						}
						if (!empty($_var_175['id'])) {
							if ($_var_175['id'] == $_var_177['id']) {
								continue;
							}
							if ($_var_175['ordermoney'] > $_var_177['ordermoney']) {
								continue;
							}
						}
					} else if ($_var_174 == 1) {
						$_var_47 = $_var_179['order13money'];
						$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid and {$_var_47} >= ordermoney and ordermoney>0  order by ordermoney desc limit 1", array(':uniacid' => $_W['uniacid']));
						if (empty($_var_177)) {
							continue;
						}
						if (!empty($_var_175['id'])) {
							if ($_var_175['id'] == $_var_177['id']) {
								continue;
							}
							if ($_var_175['ordermoney'] > $_var_177['ordermoney']) {
								continue;
							}
						}
					} else if ($_var_174 == 2) {
						$_var_46 = $_var_179['ordercount3'];
						$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_46} >= ordercount and ordercount>0  order by ordercount desc limit 1", array(':uniacid' => $_W['uniacid']));
						if (empty($_var_177)) {
							continue;
						}
						if (!empty($_var_175['id'])) {
							if ($_var_175['id'] == $_var_177['id']) {
								continue;
							}
							if ($_var_175['ordercount'] > $_var_177['ordercount']) {
								continue;
							}
						}
					} else if ($_var_174 == 3) {
						$_var_46 = $_var_179['order13'];
						$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_46} >= ordercount and ordercount>0  order by ordercount desc limit 1", array(':uniacid' => $_W['uniacid']));
						if (empty($_var_177)) {
							continue;
						}
						if (!empty($_var_175['id'])) {
							if ($_var_175['id'] == $_var_177['id']) {
								continue;
							}
							if ($_var_175['ordercount'] > $_var_177['ordercount']) {
								continue;
							}
						}
					}
					pdo_update('sz_yi_member', array('agentlevel' => $_var_177['id']), array('id' => $_var_178['id']));
					$this->sendMessage($_var_178['openid'], array('nickname' => $_var_178['nickname'], 'oldlevel' => $_var_175, 'newlevel' => $_var_177,), TM_COMMISSION_UPGRADE);
				}
			}
		}

		function upgradeLevelByAgent($_var_36)
		{
			global $_W;
			if (empty($_var_36)) {
				return false;
			}
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return false;
			}
			$_var_167 = m('member')->getMember($_var_36);
			if (empty($_var_167)) {
				return;
			}
			$_var_150 = p('bonus');
			if (!empty($_var_150)) {
				$_var_151 = $_var_150->getSet();
				if (!empty($_var_151['start'])) {
					$_var_150->upgradeLevelByAgent($_var_36);
				}
			}
			$_var_174 = intval($_var_0['leveltype']);
			if ($_var_174 < 6 || $_var_174 > 9) {
				return;
			}
			$_var_179 = $this->getInfo($_var_167['id'], array());
			if ($_var_174 == 6 || $_var_174 == 8) {
				$_var_96 = array($_var_167);
				if (!empty($_var_167['agentid'])) {
					$_var_30 = m('member')->getMember($_var_167['agentid']);
					if (!empty($_var_30)) {
						$_var_96[] = $_var_30;
						if (!empty($_var_30['agentid']) && $_var_30['isagent'] == 1 && $_var_30['status'] == 1) {
							$_var_32 = m('member')->getMember($_var_30['agentid']);
							if (!empty($_var_32) && $_var_32['isagent'] == 1 && $_var_32['status'] == 1) {
								$_var_96[] = $_var_32;
							}
						}
					}
				}
				if (empty($_var_96)) {
					return;
				}
				foreach ($_var_96 as $_var_178) {
					$_var_179 = $this->getInfo($_var_178['id'], array());
					if (!empty($_var_179['agentnotupgrade'])) {
						continue;
					}
					$_var_175 = $this->getLevel($_var_178['openid']);
					if (empty($_var_175['id'])) {
						$_var_175 = array('levelname' => empty($_var_0['levelname']) ? '普通等级' : $_var_0['levelname'], 'commission1' => $_var_0['commission1'], 'commission2' => $_var_0['commission2'], 'commission3' => $_var_0['commission3']);
					}
					if ($_var_174 == 6) {
						$_var_180 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid=:agentid and uniacid=:uniacid ', array(':agentid' => $_var_167['id'], ':uniacid' => $_W['uniacid']), 'id');
						$_var_181 += count($_var_180);
						if (!empty($_var_180)) {
							$_var_182 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid in( ' . implode(',', array_keys($_var_180)) . ') and uniacid=:uniacid', array(':uniacid' => $_W['uniacid']), 'id');
							$_var_181 += count($_var_182);
							if (!empty($_var_182)) {
								$_var_183 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid in( ' . implode(',', array_keys($_var_182)) . ') and uniacid=:uniacid', array(':uniacid' => $_W['uniacid']), 'id');
								$_var_181 += count($_var_183);
							}
						}
						$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_181} >= downcount and downcount>0  order by downcount desc limit 1", array(':uniacid' => $_W['uniacid']));
					} else if ($_var_174 == 8) {
						$_var_181 = $_var_179['level1'] + $_var_179['level2'] + $_var_179['level3'];
						$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_181} >= downcount and downcount>0  order by downcount desc limit 1", array(':uniacid' => $_W['uniacid']));
					}
					if (empty($_var_177)) {
						continue;
					}
					if ($_var_177['id'] == $_var_175['id']) {
						continue;
					}
					if (!empty($_var_175['id'])) {
						if ($_var_175['downcount'] > $_var_177['downcount']) {
							continue;
						}
					}
					pdo_update('sz_yi_member', array('agentlevel' => $_var_177['id']), array('id' => $_var_178['id']));
					$this->sendMessage($_var_178['openid'], array('nickname' => $_var_178['nickname'], 'oldlevel' => $_var_175, 'newlevel' => $_var_177,), TM_COMMISSION_UPGRADE);
				}
			} else {
				if (!empty($_var_167['agentnotupgrade'])) {
					return;
				}
				$_var_175 = $this->getLevel($_var_167['openid']);
				if (empty($_var_175['id'])) {
					$_var_175 = array('levelname' => empty($_var_0['levelname']) ? '普通等级' : $_var_0['levelname'], 'commission1' => $_var_0['commission1'], 'commission2' => $_var_0['commission2'], 'commission3' => $_var_0['commission3']);
				}
				if ($_var_174 == 7) {
					$_var_181 = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . ' where agentid=:agentid and uniacid=:uniacid ', array(':agentid' => $_var_167['id'], ':uniacid' => $_W['uniacid']));
					$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_181} >= downcount and downcount>0  order by downcount desc limit 1", array(':uniacid' => $_W['uniacid']));
				} else if ($_var_174 == 9) {
					$_var_181 = $_var_179['level1'];
					$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_181} >= downcount and downcount>0  order by downcount desc limit 1", array(':uniacid' => $_W['uniacid']));
				}
				if (empty($_var_177)) {
					return;
				}
				if ($_var_177['id'] == $_var_175['id']) {
					return;
				}
				if (!empty($_var_175['id'])) {
					if ($_var_175['downcount'] > $_var_177['downcount']) {
						return;
					}
				}
				pdo_update('sz_yi_member', array('agentlevel' => $_var_177['id']), array('id' => $_var_167['id']));
				$this->sendMessage($_var_167['openid'], array('nickname' => $_var_167['nickname'], 'oldlevel' => $_var_175, 'newlevel' => $_var_177,), TM_COMMISSION_UPGRADE);
			}
		}

		function upgradeLevelByCommissionOK($_var_36)
		{
			global $_W;
			if (empty($_var_36)) {
				return false;
			}
			$_var_0 = $this->getSet();
			if (empty($_var_0['level'])) {
				return false;
			}
			$_var_167 = m('member')->getMember($_var_36);
			if (empty($_var_167)) {
				return;
			}
			$_var_150 = p('bonus');
			if (!empty($_var_150)) {
				$_var_151 = $_var_150->getSet();
				if (!empty($_var_151['start'])) {
					$_var_150->upgradeLevelByAgent($_var_36);
				}
			}
			$_var_174 = intval($_var_0['leveltype']);
			if ($_var_174 != 10) {
				return;
			}
			if (!empty($_var_167['agentnotupgrade'])) {
				return;
			}
			$_var_175 = $this->getLevel($_var_167['openid']);
			if (empty($_var_175['id'])) {
				$_var_175 = array('levelname' => empty($_var_0['levelname']) ? '普通等级' : $_var_0['levelname'], 'commission1' => $_var_0['commission1'], 'commission2' => $_var_0['commission2'], 'commission3' => $_var_0['commission3']);
			}
			$_var_179 = $this->getInfo($_var_167['id'], array('pay'));
			$_var_184 = $_var_179['commission_pay'];
			$_var_177 = pdo_fetch('select * from ' . tablename('sz_yi_commission_level') . " where uniacid=:uniacid  and {$_var_184} >= commissionmoney and commissionmoney>0  order by commissionmoney desc limit 1", array(':uniacid' => $_W['uniacid']));
			if (empty($_var_177)) {
				return;
			}
			if ($_var_175['id'] == $_var_177['id']) {
				return;
			}
			if (!empty($_var_175['id'])) {
				if ($_var_175['commissionmoney'] > $_var_177['commissionmoney']) {
					return;
				}
			}
			pdo_update('sz_yi_member', array('agentlevel' => $_var_177['id']), array('id' => $_var_167['id']));
			$this->sendMessage($_var_167['openid'], array('nickname' => $_var_167['nickname'], 'oldlevel' => $_var_175, 'newlevel' => $_var_177,), TM_COMMISSION_UPGRADE);
		}

		function sendMessage($_var_36 = '', $_var_185 = array(), $_var_186 = '')
		{
			global $_W, $_GPC;
			$_var_0 = $this->getSet();
			$_var_187 = $_var_0['tm'];
			$_var_188 = $_var_187['templateid'];
			$_var_39 = m('member')->getMember($_var_36);
			$_var_189 = unserialize($_var_39['noticeset']);
			if (!is_array($_var_189)) {
				$_var_189 = array();
			}
			if ($_var_186 == TM_COMMISSION_AGENT_NEW && !empty($_var_187['commission_agent_new']) && empty($_var_189['commission_agent_new'])) {
				$_var_190 = $_var_187['commission_agent_new'];
				$_var_190 = str_replace('[昵称]', $_var_185['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', $_var_185['childtime']), $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_agent_newtitle']) ? $_var_187['commission_agent_newtitle'] : '新增下线通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_ORDER_PAY && !empty($_var_187['commission_order_pay']) && empty($_var_189['commission_order_pay'])) {
				$_var_190 = $_var_187['commission_order_pay'];
				$_var_190 = str_replace('[昵称]', $_var_185['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', $_var_185['paytime']), $_var_190);
				$_var_190 = str_replace('[订单编号]', $_var_185['ordersn'], $_var_190);
				$_var_190 = str_replace('[订单金额]', $_var_185['price'], $_var_190);
				$_var_190 = str_replace('[佣金金额]', $_var_185['commission'], $_var_190);
				$_var_190 = str_replace('[商品详情]', $_var_185['goods'], $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_order_paytitle']) ? $_var_187['commission_order_paytitle'] : '下线付款通知'), 'keyword2' => array('value' => $_var_190));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_ORDER_FINISH && !empty($_var_187['commission_order_finish']) && empty($_var_189['commission_order_finish'])) {
				$_var_190 = $_var_187['commission_order_finish'];
				$_var_190 = str_replace('[昵称]', $_var_185['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', $_var_185['finishtime']), $_var_190);
				$_var_190 = str_replace('[订单编号]', $_var_185['ordersn'], $_var_190);
				$_var_190 = str_replace('[订单金额]', $_var_185['price'], $_var_190);
				$_var_190 = str_replace('[佣金金额]', $_var_185['commission'], $_var_190);
				$_var_190 = str_replace('[商品详情]', $_var_185['goods'], $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_order_finishtitle']) ? $_var_187['commission_order_finishtitle'] : '下线确认收货通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_APPLY && !empty($_var_187['commission_apply']) && empty($_var_189['commission_apply'])) {
				$_var_190 = $_var_187['commission_apply'];
				$_var_190 = str_replace('[昵称]', $_var_39['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $_var_190);
				$_var_190 = str_replace('[金额]', $_var_185['commission'], $_var_190);
				$_var_190 = str_replace('[提现方式]', $_var_185['type'], $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_applytitle']) ? $_var_187['commission_applytitle'] : '提现申请提交成功', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_CHECK && !empty($_var_187['commission_check']) && empty($_var_189['commission_check'])) {
				$_var_190 = $_var_187['commission_check'];
				$_var_190 = str_replace('[昵称]', $_var_39['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $_var_190);
				$_var_190 = str_replace('[金额]', $_var_185['commission'], $_var_190);
				$_var_190 = str_replace('[提现方式]', $_var_185['type'], $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_checktitle']) ? $_var_187['commission_checktitle'] : '提现申请审核处理完成', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_PAY && !empty($_var_187['commission_pay']) && empty($_var_189['commission_pay'])) {
				$_var_190 = $_var_187['commission_pay'];
				$_var_190 = str_replace('[昵称]', $_var_39['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $_var_190);
				$_var_190 = str_replace('[金额]', $_var_185['commission'], $_var_190);
				$_var_190 = str_replace('[提现方式]', $_var_185['type'], $_var_190);
				$_var_190 = str_replace('[微信比例]', $_var_0['withdraw_wechat'], $_var_190);
				$_var_190 = str_replace('[商城余额比例]', $_var_0['withdraw_balance'], $_var_190);
				$_var_190 = str_replace('[税费和服务费比例]', $_var_0['withdraw_factorage'], $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_paytitle']) ? $_var_187['commission_paytitle'] : '佣金打款通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_UPGRADE && !empty($_var_187['commission_upgrade']) && empty($_var_189['commission_upgrade'])) {
				$_var_190 = $_var_187['commission_upgrade'];
				$_var_190 = str_replace('[昵称]', $_var_39['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $_var_190);
				$_var_190 = str_replace('[旧等级]', $_var_185['oldlevel']['levelname'], $_var_190);
				$_var_190 = str_replace('[旧一级分销比例]', $_var_185['oldlevel']['commission1'] . '%', $_var_190);
				$_var_190 = str_replace('[旧二级分销比例]', $_var_185['oldlevel']['commission2'] . '%', $_var_190);
				$_var_190 = str_replace('[旧三级分销比例]', $_var_185['oldlevel']['commission3'] . '%', $_var_190);
				$_var_190 = str_replace('[新等级]', $_var_185['newlevel']['levelname'], $_var_190);
				$_var_190 = str_replace('[新一级分销比例]', $_var_185['newlevel']['commission1'] . '%', $_var_190);
				$_var_190 = str_replace('[新二级分销比例]', $_var_185['newlevel']['commission2'] . '%', $_var_190);
				$_var_190 = str_replace('[新三级分销比例]', $_var_185['newlevel']['commission3'] . '%', $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_upgradetitle']) ? $_var_187['commission_upgradetitle'] : '分销等级升级通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			} else if ($_var_186 == TM_COMMISSION_BECOME && !empty($_var_187['commission_become']) && empty($_var_189['commission_become'])) {
				$_var_190 = $_var_187['commission_become'];
				$_var_190 = str_replace('[昵称]', $_var_185['nickname'], $_var_190);
				$_var_190 = str_replace('[时间]', date('Y-m-d H:i:s', $_var_185['agenttime']), $_var_190);
				$_var_191 = array('keyword1' => array('value' => !empty($_var_187['commission_becometitle']) ? $_var_187['commission_becometitle'] : '成为分销商通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $_var_190, 'color' => '#73a68d'));
				if (!empty($_var_188)) {
					m('message')->sendTplNotice($_var_36, $_var_188, $_var_191);
				} else {
					m('message')->sendCustomNotice($_var_36, $_var_191);
				}
			}
		}

		function perms()
		{
			return array('commission' => array('text' => $this->getName(), 'isplugin' => true, 'child' => array('cover' => array('text' => '入口设置'), 'agent' => array('text' => '分销商', 'view' => '浏览', 'check' => '审核-log', 'edit' => '修改-log', 'agentblack' => '黑名单操作-log', 'delete' => '删除-log', 'user' => '查看下线', 'order' => '查看推广订单(还需有订单权限)', 'changeagent' => '设置分销商'), 'level' => array('text' => '分销商等级', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'apply' => array('text' => '佣金审核', 'view1' => '浏览待审核', 'view2' => '浏览已审核', 'view3' => '浏览已打款', 'view_1' => '浏览无效', 'export1' => '导出待审核-log', 'export2' => '导出已审核-log', 'export3' => '导出已打款-log', 'export_1' => '导出无效-log', 'check' => '审核-log', 'pay' => '打款-log', 'cancel' => '重新审核-log'), 'notice' => array('text' => '通知设置-log'), 'increase' => array('text' => '分销商趋势图'), 'changecommission' => array('text' => '修改佣金-log'), 'set' => array('text' => '基础设置-log'))));
		}

		function upgradeLevelByGood($_var_1)
		{
			global $_W;
			$_var_0 = $this->getSet();
			if (!$_var_0['upgrade_by_good']) {
				return;
			}
			$_var_5 = pdo_fetch('select g.commission_level_id from ' . tablename('sz_yi_order_goods') . ' AS og, ' . tablename('sz_yi_goods') . ' AS g WHERE og.goodsid = g.id AND og.orderid=:orderid AND og.uniacid=:uniacid LIMIT 1', array(':orderid' => $_var_1, ':uniacid' => $_W['uniacid']));
			$_var_192 = $_var_5['commission_level_id'];
			if ($_var_192) {
				$_var_3 = $this->getLevels();
				foreach ($_var_3 as $_var_8) {
					if ($_var_8['id'] == $_var_192) {
						$_var_193 = $_var_8['commission1'];
						$_var_194 = $_var_8['commission2'];
						$_var_195 = $_var_8['commission3'];
					}
				}
				$_var_148 = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_order') . ' where uniacid=:uniacid and id=:orderid', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_1));
				$_var_196 = $this->getLevel($_var_148);
				if (!$_var_196 || $_var_196['commission1'] < $_var_193 || $_var_196['commission2'] < $_var_194 || $_var_196['commission3'] < $_var_195) {
					pdo_update('sz_yi_member', array('agentlevel' => $_var_192), array('uniacid' => $_W['uniacid'], 'openid' => $_var_148));
				}
			}
		}
	}
}