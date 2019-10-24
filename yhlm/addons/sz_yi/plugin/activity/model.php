<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
define('TM_activity_PAY', 'activity_pay');
if (!class_exists('activityModel')){
    class activityModel extends PluginModel{
        public $parentAgents = "";
        public function getInfo($dephp_0, $dephp_1 = null){
            if (empty($dephp_1) || !is_array($dephp_1)){
                $dephp_1 = array();
            }
            global $_W;
            $dephp_2 = $this -> getSet();
            $dephp_3 = intval($dephp_2['level']);
            $dephp_4 = m('member') -> getMember($dephp_0);
            $dephp_5 = $this -> getLevel($dephp_0);
            $dephp_6 = time();
            $dephp_7 = intval($dephp_2['settledays']) * 3600 * 24;
            $dephp_8 = 0;
            $dephp_9 = 0;
            $dephp_10 = 0;
            $dephp_11 = 0;
            $dephp_12 = 0;
            $dephp_13 = 0;
            $dephp_14 = 0;
            $dephp_15 = 0;
            $dephp_16 = 0;
            $dephp_17 = 0;
            $dephp_18 = 0;
            $dephp_19 = 0;
            $dephp_20 = 0;
            $dephp_21 = 0;
            $dephp_22 = 0;
            $dephp_23 = 0;
            $dephp_24 = 0;
            $dephp_25 = 0;
            $dephp_26 = 0;
            $dephp_27 = 0;
            $dephp_28 = 0;
            $dephp_29 = 0;
            $dephp_30 = 0;
            $dephp_31 = 0;
            $dephp_32 = 0;
            $dephp_33 = 0;
            $dephp_34 = 0;
            $dephp_35 = 0;
            if ($dephp_3 >= 1){
                if (in_array('ordercount0', $dephp_1)){
                    $dephp_36 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=0 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    $dephp_24 += $dephp_36['ordercount'];
                    $dephp_9 += $dephp_36['ordercount'];
                    $dephp_10 += $dephp_36['ordermoney'];
                }
                if (in_array('ordercount', $dephp_1)){
                    $dephp_36 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=1 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    $dephp_27 += $dephp_36['ordercount'];
                    $dephp_11 += $dephp_36['ordercount'];
                    $dephp_12 += $dephp_36['ordermoney'];
                }
                if (in_array('ordercount3', $dephp_1)){
                    $dephp_37 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid=:agentid and o.status>=3 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    $dephp_30 += $dephp_37['ordercount'];
                    $dephp_13 += $dephp_37['ordercount'];
                    $dephp_14 += $dephp_37['ordermoney'];
                    $dephp_33 += $dephp_37['ordermoney'];
                }
                if (in_array('total', $dephp_1)){
                    $dephp_38 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    foreach ($dephp_38 as $dephp_39){
                        $dephp_40 = iunserializer($dephp_39['commissions']);
                        $dephp_41 = iunserializer($dephp_39['commission1']);
                        if (empty($dephp_40)){
                            $dephp_15 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                        }else{
                            $dephp_15 += isset($dephp_40['level1']) ? floatval($dephp_40['level1']) : 0;
                        }
                    }
                }
                if (in_array('ok', $dephp_1)){
                    $dephp_38 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$dephp_6} - o.createtime > {$dephp_7}) and og.status1=0  and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    foreach ($dephp_38 as $dephp_39){
                        $dephp_40 = iunserializer($dephp_39['commissions']);
                        $dephp_41 = iunserializer($dephp_39['commission1']);
                        if (empty($dephp_40)){
                            $dephp_16 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                        }else{
                            $dephp_16 += isset($dephp_40['level1']) ? $dephp_40['level1'] : 0;
                        }
                    }
                }
                if (in_array('lock', $dephp_1)){
                    $dephp_42 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$dephp_6} - o.createtime <= {$dephp_7})  and og.status1=0  and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    foreach ($dephp_42 as $dephp_39){
                        $dephp_40 = iunserializer($dephp_39['commissions']);
                        $dephp_41 = iunserializer($dephp_39['commission1']);
                        if (empty($dephp_40)){
                            $dephp_19 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                        }else{
                            $dephp_19 += isset($dephp_40['level1']) ? $dephp_40['level1'] : 0;
                        }
                    }
                }
                if (in_array('apply', $dephp_1)){
                    $dephp_43 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=3 and og.status1=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    foreach ($dephp_43 as $dephp_39){
                        $dephp_40 = iunserializer($dephp_39['commissions']);
                        $dephp_41 = iunserializer($dephp_39['commission1']);
                        if (empty($dephp_40)){
                            $dephp_17 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                        }else{
                            $dephp_17 += isset($dephp_40['level1']) ? $dephp_40['level1'] : 0;
                        }
                    }
                }
                if (in_array('check', $dephp_1)){
                    $dephp_43 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=3 and og.status1=2 and og.nocommission=0 and o.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    foreach ($dephp_43 as $dephp_39){
                        $dephp_40 = iunserializer($dephp_39['commissions']);
                        $dephp_41 = iunserializer($dephp_39['commission1']);
                        if (empty($dephp_40)){
                            $dephp_18 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                        }else{
                            $dephp_18 += isset($dephp_40['level1']) ? $dephp_40['level1'] : 0;
                        }
                    }
                }
                if (in_array('pay', $dephp_1)){
                    $dephp_43 = pdo_fetchall('select og.commission1,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid=:agentid and o.status>=3 and og.status1=3 and og.nocommission=0 and o.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']));
                    foreach ($dephp_43 as $dephp_39){
                        $dephp_40 = iunserializer($dephp_39['commissions']);
                        $dephp_41 = iunserializer($dephp_39['commission1']);
                        if (empty($dephp_40)){
                            $dephp_20 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                        }else{
                            $dephp_20 += isset($dephp_40['level1']) ? $dephp_40['level1'] : 0;
                        }
                    }
                }
                $dephp_44 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid=:agentid and isagent=1 and status=1 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':agentid' => $dephp_4['id']), 'id');
                $dephp_21 = count($dephp_44);
                $dephp_8 += $dephp_21;
            }
            if ($dephp_3 >= 2){
                if ($dephp_21 > 0){
                    if (in_array('ordercount0', $dephp_1)){
                        $dephp_45 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=0 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
                        $dephp_25 += $dephp_45['ordercount'];
                        $dephp_9 += $dephp_45['ordercount'];
                        $dephp_10 += $dephp_45['ordermoney'];
                    }
                    if (in_array('ordercount', $dephp_1)){
                        $dephp_45 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=1 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
                        $dephp_28 += $dephp_45['ordercount'];
                        $dephp_11 += $dephp_45['ordercount'];
                        $dephp_12 += $dephp_45['ordermoney'];
                    }
                    if (in_array('ordercount3', $dephp_1)){
                        $dephp_46 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=3 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
                        $dephp_31 += $dephp_46['ordercount'];
                        $dephp_13 += $dephp_46['ordercount'];
                        $dephp_14 += $dephp_46['ordermoney'];
                        $dephp_34 += $dephp_46['ordermoney'];
                    }
                    if (in_array('total', $dephp_1)){
                        $dephp_47 = pdo_fetchall('select og.commission2,og.commissions from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_47 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission2']);
                            if (empty($dephp_40)){
                                $dephp_15 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_15 += isset($dephp_40['level2']) ? $dephp_40['level2'] : 0;
                            }
                        }
                    }
                    if (in_array('ok', $dephp_1)){
                        $dephp_47 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ")  and ({$dephp_6} - o.createtime > {$dephp_7}) and o.status>=3 and og.status2=0 and og.nocommission=0  and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_47 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission2']);
                            if (empty($dephp_40)){
                                $dephp_16 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_16 += isset($dephp_40['level2']) ? $dephp_40['level2'] : 0;
                            }
                        }
                    }
                    if (in_array('lock', $dephp_1)){
                        $dephp_48 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ")  and ({$dephp_6} - o.createtime <= {$dephp_7}) and og.status2=0 and o.status>=3 and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_48 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission2']);
                            if (empty($dephp_40)){
                                $dephp_19 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_19 += isset($dephp_40['level2']) ? $dephp_40['level2'] : 0;
                            }
                        }
                    }
                    if (in_array('apply', $dephp_1)){
                        $dephp_49 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=3 and og.status2=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_49 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission2']);
                            if (empty($dephp_40)){
                                $dephp_17 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_17 += isset($dephp_40['level2']) ? $dephp_40['level2'] : 0;
                            }
                        }
                    }
                    if (in_array('check', $dephp_1)){
                        $dephp_50 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=3 and og.status2=2 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_50 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission2']);
                            if (empty($dephp_40)){
                                $dephp_18 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_18 += isset($dephp_40['level2']) ? $dephp_40['level2'] : 0;
                            }
                        }
                    }
                    if (in_array('pay', $dephp_1)){
                        $dephp_50 = pdo_fetchall('select og.commission2,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_44)) . ')  and o.status>=3 and og.status2=3 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_50 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission2']);
                            if (empty($dephp_40)){
                                $dephp_20 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_20 += isset($dephp_40['level2']) ? $dephp_40['level2'] : 0;
                            }
                        }
                    }
                    $dephp_51 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where agentid in( ' . implode(',', array_keys($dephp_44)) . ') and isagent=1 and status=1 and uniacid=:uniacid', array(':uniacid' => $_W['uniacid']), 'id');
                    $dephp_22 = count($dephp_51);
                    $dephp_8 += $dephp_22;
                }
            }
            if ($dephp_3 >= 3){
                if ($dephp_22 > 0){
                    if (in_array('ordercount0', $dephp_1)){
                        $dephp_52 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=0 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
                        $dephp_26 += $dephp_52['ordercount'];
                        $dephp_9 += $dephp_52['ordercount'];
                        $dephp_10 += $dephp_52['ordermoney'];
                    }
                    if (in_array('ordercount', $dephp_1)){
                        $dephp_52 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=1 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
                        $dephp_29 += $dephp_52['ordercount'];
                        $dephp_11 += $dephp_52['ordercount'];
                        $dephp_12 += $dephp_52['ordermoney'];
                    }
                    if (in_array('ordercount3', $dephp_1)){
                        $dephp_53 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=3 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
                        $dephp_32 += $dephp_53['ordercount'];
                        $dephp_13 += $dephp_53['ordercount'];
                        $dephp_14 += $dephp_53['ordermoney'];
                        $dephp_35 += $dephp_52['ordermoney'];
                    }
                    if (in_array('total', $dephp_1)){
                        $dephp_54 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_54 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission3']);
                            if (empty($dephp_40)){
                                $dephp_15 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_15 += isset($dephp_40['level3']) ? $dephp_40['level3'] : 0;
                            }
                        }
                    }
                    if (in_array('ok', $dephp_1)){
                        $dephp_54 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ")  and ({$dephp_6} - o.createtime > {$dephp_7}) and o.status>=3 and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_54 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission3']);
                            if (empty($dephp_40)){
                                $dephp_16 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_16 += isset($dephp_40['level3']) ? $dephp_40['level3'] : 0;
                            }
                        }
                    }
                    if (in_array('lock', $dephp_1)){
                        $dephp_55 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ")  and o.status>=3 and ({$dephp_6} - o.createtime > {$dephp_7}) and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_55 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission3']);
                            if (empty($dephp_40)){
                                $dephp_19 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_19 += isset($dephp_40['level3']) ? $dephp_40['level3'] : 0;
                            }
                        }
                    }
                    if (in_array('apply', $dephp_1)){
                        $dephp_56 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=3 and og.status3=1 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_56 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission3']);
                            if (empty($dephp_40)){
                                $dephp_17 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_17 += isset($dephp_40['level3']) ? $dephp_40['level3'] : 0;
                            }
                        }
                    }
                    if (in_array('check', $dephp_1)){
                        $dephp_57 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=3 and og.status3=2 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_57 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission3']);
                            if (empty($dephp_40)){
                                $dephp_18 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_18 += isset($dephp_40['level3']) ? $dephp_40['level3'] : 0;
                            }
                        }
                    }
                    if (in_array('pay', $dephp_1)){
                        $dephp_57 = pdo_fetchall('select og.commission3,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on o.id = og.orderid' . ' where o.agentid in( ' . implode(',', array_keys($dephp_51)) . ')  and o.status>=3 and og.status3=3 and og.nocommission=0 and o.uniacid=:uniacid', array(':uniacid' => $_W['uniacid']));
                        foreach ($dephp_57 as $dephp_39){
                            $dephp_40 = iunserializer($dephp_39['commissions']);
                            $dephp_41 = iunserializer($dephp_39['commission3']);
                            if (empty($dephp_40)){
                                $dephp_20 += isset($dephp_41['level' . $dephp_5['id']]) ? $dephp_41['level' . $dephp_5['id']] : $dephp_41['default'];
                            }else{
                                $dephp_20 += isset($dephp_40['level3']) ? $dephp_40['level3'] : 0;
                            }
                        }
                    }
                    $dephp_58 = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and agentid in( ' . implode(',', array_keys($dephp_51)) . ') and isagent=1 and status=1', array(':uniacid' => $_W['uniacid']), 'id');
                    $dephp_23 = count($dephp_58);
                    $dephp_8 += $dephp_23;
                }
            }
            $dephp_4['agentcount'] = $dephp_8;
            $dephp_4['ordercount'] = $dephp_11;
            $dephp_4['ordermoney'] = $dephp_12;
            $dephp_4['order1'] = $dephp_27;
            $dephp_4['order2'] = $dephp_28;
            $dephp_4['order3'] = $dephp_29;
            $dephp_4['ordercount3'] = $dephp_13;
            $dephp_4['ordermoney3'] = $dephp_14;
            $dephp_4['order13'] = $dephp_30;
            $dephp_4['order23'] = $dephp_31;
            $dephp_4['order33'] = $dephp_32;
            $dephp_4['order13money'] = $dephp_33;
            $dephp_4['order23money'] = $dephp_34;
            $dephp_4['order33money'] = $dephp_35;
            $dephp_4['ordercount0'] = $dephp_9;
            $dephp_4['ordermoney0'] = $dephp_10;
            $dephp_4['order10'] = $dephp_24;
            $dephp_4['order20'] = $dephp_25;
            $dephp_4['order30'] = $dephp_26;
            $dephp_4['commission_total'] = round($dephp_15, 2);
            $dephp_4['commission_ok'] = round($dephp_16, 2);
            $dephp_4['commission_lock'] = round($dephp_19, 2);
            $dephp_4['commission_apply'] = round($dephp_17, 2);
            $dephp_4['commission_check'] = round($dephp_18, 2);
            $dephp_4['commission_pay'] = round($dephp_20, 2);
            $dephp_4['level1'] = $dephp_21;
            $dephp_4['level1_agentids'] = $dephp_44;
            $dephp_4['level2'] = $dephp_22;
            $dephp_4['level2_agentids'] = $dephp_51;
            $dephp_4['level3'] = $dephp_23;
            $dephp_4['level3_agentids'] = $dephp_58;
            $dephp_4['agenttime'] = date('Y-m-d H:i', $dephp_4['agenttime']);
            return $dephp_4;
        }
       function perms(){
            return array(
				'activity' => array(
						'text' => $this -> getName(), 
						'isplugin' => true,  
						'child' => array(
							'activity' => array('text' => '活动管理'),
                            'activity.add' => array('text' => '我发布的活动'),
                            'member' => array('text' => '用户管理'),
                            'info' => array('text' => '账号信息'),
                            'article' => array('text' => '文章管理'),
                            'article.add' => array('text' => '我发布的文章'),
                            'article.draft' => array('text' => '草稿箱'),
                            'art' => array('text' => '艺术作品'),  
                            'signup' => array('text' => '签到'),  
                        ), 
				) 
			); 
        } 
        
        public function allPerms(){
            $dephp_59 = array('shop' => array('text' => '商城管理', 'child' => array('goods' => array('text' => '商品', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'category' => array('text' => '商品分类', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'dispatch' => array('text' => '配送方式', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'adv' => array('text' => '幻灯片', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'notice' => array('text' => '公告', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'comment' => array('text' => '评价', 'view' => '浏览', 'add' => '添加评论-log', 'edit' => '回复-log', 'delete' => '删除-log'),)), 'member' => array('text' => '会员管理', 'child' => array('member' => array('text' => '会员', 'view' => '浏览', 'edit' => '修改-log', 'delete' => '删除-log', 'export' => '导出-log'), 'group' => array('text' => '会员组', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'level' => array('text' => '会员等级', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'))), 'order' => array('text' => '订单管理', 'child' => array('view' => array('text' => '浏览', 'status_1' => '浏览关闭订单', 'status0' => '浏览待付款订单', 'status1' => '浏览已付款订单', 'status2' => '浏览已发货订单', 'status3' => '浏览完成的订单', 'status4' => '浏览退货申请订单', 'status5' => '浏览已退货订单', 'status9' => '浏览提现申请'), 'op' => array('text' => '操作', 'pay' => '确认付款-log', 'send' => '发货-log', 'sendcancel' => '取消发货-log', 'finish' => '确认收货(快递单)-log', 'verify' => '确认核销(核销单)-log', 'fetch' => '确认取货(自提单)-log', 'close' => '关闭订单-log', 'refund' => '退货处理-log', 'export' => '导出订单-log', 'changeprice' => '订单改价-log'))), 'finance' => array('text' => '财务管理', 'child' => array('recharge' => array('text' => '充值', 'view' => '浏览', 'credit1' => '充值积分-log', 'credit2' => '充值余额-log', 'refund' => '充值退款-log', 'export' => '导出充值记录-log'), 'withdraw' => array('text' => '提现', 'view' => '浏览', 'withdraw' => '提现-log', 'export' => '导出提现记录-log'), 'downloadbill' => array('text' => '下载对账单'),)), 'statistics' => array('text' => '数据统计', 'child' => array('view' => array('text' => '浏览权限', 'sale' => '销售指标', 'sale_analysis' => '销售统计', 'order' => '订单统计', 'goods' => '商品销售统计', 'goods_rank' => '商品销售排行', 'goods_trans' => '商品销售转化率', 'member_cost' => '会员消费排行', 'member_increase' => '会员增长趋势'), 'export' => array('text' => '导出', 'sale' => '导出销售统计-log', 'order' => '导出订单统计-log', 'goods' => '导出商品销售统计-log', 'goods_rank' => '导出商品销售排行-log', 'goods_trans' => '商品销售转化率-log', 'member_cost' => '会员消费排行-log'),)), 'sysset' => array('text' => '系统设置', 'child' => array('view' => array('text' => '浏览', 'shop' => '商城设置', 'follow' => '引导及分享设置', 'notice' => '模板消息设置', 'trade' => '交易设置', 'pay' => '支付方式设置', 'template' => '模板设置', 'member' => '会员设置', 'category' => '分类层级设置', 'contact' => '联系方式设置'), 'save' => array('text' => '修改', 'shop' => '修改商城设置-log', 'follow' => '修改引导及分享设置-log', 'notice' => '修改模板消息设置-log', 'trade' => '修改交易设置-log', 'pay' => '修改支付方式设置-log', 'template' => '模板设置-log', 'member' => '会员设置-log', 'category' => '分类层级设置-log', 'contact' => '联系方式设置-log'))),);
            $dephp_60 = m('plugin') -> getAll();
            foreach ($dephp_60 as $dephp_61){
                $dephp_62 = p($dephp_61['identity']);
                if ($dephp_62){
                    if (method_exists($dephp_62, 'perms')){
                        $dephp_63 = $dephp_62 -> perms();
                        $dephp_59 = array_merge($dephp_59, $dephp_63);
                    }
                }
            }
            return $dephp_59;
        }
        public function getSet(){
            $dephp_2 = parent :: getSet();
            return $dephp_2;
        }

        
        public function getSysSet($ac=false,$sure=false){
            global $_W;
            $dephp_2 =pdo_fetch('select * from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
            if ($sure) {
                return $dephp_2;
            }else{

                $dephp_3=$dephp_2['sets'];
                if ($ac) {

                    $dephp_4=unserialize($dephp_3);
                    return $dephp_3[$ac];
                }else{

                    return unserialize($dephp_3);
                }
            }
        }

        public function sendMessage($dephp_64 = '', $dephp_65 = array(), $dephp_66 = ''){
            $dephp_4 = m('member') -> getMember($dephp_64);
            if ($dephp_66 == TM_activity_PAY){
                $dephp_67 = '恭喜您，您的提现将通过 [提现方式] 转账提现金额为[金额]已在[时间]转账到您的账号，敬请查看';
                $dephp_67 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $dephp_67);
                $dephp_67 = str_replace('[金额]', $dephp_65['money'], $dephp_67);
                $dephp_67 = str_replace('[提现方式]', $dephp_65['type'], $dephp_67);
                $dephp_68 = array('keyword1' => array('value' => '供应商打款通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_67, 'color' => '#73a68d'));
                m('message') -> sendCustomNotice($dephp_64, $dephp_68);
            }
        }
        public function sendactivityInform($dephp_64 = '', $dephp_69 = ''){
		//$dephp_64:openid ,$status: 状态
            if ($dephp_69 == 1){
                $dephp_70 = '驳回';
            }else{
                $dephp_70 = '通过';
            }
            $dephp_71 = $this -> getSet();
            $dephp_72 = $dephp_71['tm'];
            $dephp_67 = $dephp_72['commission_become'];
            $dephp_67 = str_replace('[状态]', $dephp_70, $dephp_67);
            $dephp_67 = str_replace('[时间]', date('Y-m-d H:i', time()), $dephp_67);
            if (!empty($dephp_72['commission_becometitle'])){
                $dephp_73 = $dephp_72['commission_becometitle'];
            }else{
                $dephp_73 = '会员申请供应商通知';
            }
            $dephp_68 = array('keyword1' => array('value' => $dephp_73, 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_67, 'color' => '#73a68d'));
			
			$member     = m('member')->getMember($dephp_64);
		$usernotice = unserialize($member['noticeset']);
		 //file_put_contents(dirname(__FILE__).'/usernotice',json_encode( $usernotice)); 
		if($usernotice['commission_supp']!=-1 ){
		
			m('message') -> sendCustomNotice($dephp_64, $dephp_68);
			
		}
			
            
        }
        public function order_split($dephp_74){
            global $_W;
            if(empty($dephp_74)){
                return;
            }
            $dephp_75 = pdo_fetchall('select distinct activity_uid from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid and uniacid=:uniacid', array(':orderid' => $dephp_74, ':uniacid' => $_W['uniacid']));
            if(count($dephp_75) == 1){
                pdo_update('sz_yi_order', array('activity_uid' => $dephp_75[0]['activity_uid']), array('id' => $dephp_74, 'uniacid' => $_W['uniacid']));
                return;
            }
            $dephp_76 = pdo_fetchall('select activity_uid, id from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid and uniacid=:uniacid ', array(':orderid' => $dephp_74, ':uniacid' => $_W['uniacid']));
            $dephp_77 = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where  id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $dephp_74));
            $dephp_78 = ture;
            $dephp_79 = array();
            foreach ($dephp_76 as $dephp_80 => $dephp_81){
                $dephp_79[$dephp_81['activity_uid']][]['id'] = $dephp_81['id'];
            }
            $dephp_82 = false;
            unset($dephp_77['id']);
            unset($dephp_77['uniacid']);
            $dephp_83 = $dephp_77['dispatchprice'];
            $dephp_84 = $dephp_77['olddispatchprice'];
            $dephp_85 = $dephp_77['changedispatchprice'];
            if(!empty($dephp_79)){
                foreach ($dephp_79 as $dephp_80 => $dephp_81){
                    $dephp_86 = $dephp_77;
                    $dephp_87 = 0;
                    $dephp_88 = 0;
                    $dephp_89 = 0;
                    $dephp_90 = 0;
                    $dephp_91 = 0;
                    $dephp_92 = 0;
                    $dephp_93 = 0;
                    $dephp_94 = 0;
                    $dephp_95 = 0;
                    foreach($dephp_81 as $dephp_96){
                        $dephp_70 = pdo_fetch('select price,realprice,oldprice,activity_uid from ' . tablename('sz_yi_order_goods') . ' where id=:id and uniacid=:uniacid ', array(':id' => $dephp_96['id'], ':uniacid' => $_W['uniacid']));
							file_put_contents(dirname(__FILE__).'/dephp_70',json_encode( $dephp_70)); 
                        $dephp_87 += $dephp_70['price'];
                        $dephp_88 += $dephp_70['realprice'];
                        $dephp_89 += $dephp_70['oldprice'];
                        $dephp_91 += $dephp_70['price'];
                        $dephp_97 = $dephp_80;
                        $dephp_90 += $dephp_70['changeprice'];
                        $dephp_98 = $dephp_70['price'] / $dephp_86['goodsprice'];
                        $dephp_92 += round($dephp_98 * $dephp_86['couponprice'], 2);
                        $dephp_93 += round($dephp_98 * $dephp_86['discountprice'], 2);
                        $dephp_94 += round($dephp_98 * $dephp_86['deductprice'], 2);
                        $dephp_95 += round($dephp_98 * $dephp_86['deductcredit2'], 2);
                    }
					
					
				$_var_0  = pdo_fetch('select total,goodsid,price from ' . tablename('sz_yi_order_goods') . ' where id=:id and uniacid=:uniacid ', array(':id' => $dephp_96['id'], ':uniacid' => $_W['uniacid']));
				
				$_var_1  = pdo_fetch('select dispatchtype,dispatchprice,dispatchid,ednum,edmoney from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid ', array(':id' => $_var_0['goodsid'], ':uniacid' => $_W['uniacid']));
				//ednum为单品满件包邮,edmoney为单品满额包邮
				if($_var_1['dispatchtype']==1){
						//统一
						$_var_5 =  intval($_var_1['ednum']);
						$_var_6 = intval($_var_1['edmoney']);
						if(!empty($_var_5)  || !empty($_var_6)){
							
							if(!empty($_var_5) ){
								
								if($_var_0['total'] >= $_var_5){
									 $_var_money = 0;
								}else if(!empty($_var_6)){
										if($_var_0['price'] >= $_var_6){
											$_var_money = 0;
										}else{
											$_var_money = $_var_1['dispatchprice'] * $_var_0['total'];
										}
							   
							   }else{
									
									$_var_money = $_var_1['dispatchprice'] * $_var_0['total'];
	
							   }
							
							}else{
								
								if($_var_0['price'] >= $_var_6){
									$_var_money = 0;
								}else{
									$_var_money = $_var_1['dispatchprice'] * $_var_0['total'];
								}
								
							}
						}else{
							
							$_var_money = $_var_1['dispatchprice'] * $_var_0['total'];
						
						}
					
				
				}else{
					$_var_2  = pdo_fetch('select secondprice,calculatetype,firstnumprice,secondnumprice,secondnum,firstnum    from ' . tablename('sz_yi_dispatch') . ' where id=:id and uniacid=:uniacid ', array(':id' => $_var_1['dispatchid'], ':uniacid' => $_W['uniacid']));
					//$_var_2['calculatetype']为1是件,2重量
						$_var_5 =  intval($_var_1['ednum']);
						$_var_6 = intval($_var_1['edmoney']);
						if(!empty($_var_5)  || !empty($_var_6)){
								file_put_contents(dirname(__FILE__).'/edmoney',json_encode($_var_6)); 
								file_put_contents(dirname(__FILE__).'/ednum',json_encode($_var_5)); 
							if(!empty($_var_5) ){
								
								if($_var_0['total'] >= $_var_5){
									 $_var_money = 0;
								}else if(!empty($_var_6)){
										if($_var_0['price'] >= $_var_6){
											$_var_money = 0;
										}else{
											if($_var_2['calculatetype']==1){
												if($_var_2['firstnum']>=$_var_0['total']){
													$_var_money = $_var_2['firstnumprice'];
												}else{
													
													$_var_1_money = $_var_2['firstnumprice'];//首价格
													$num = $_var_0['total'] - $_var_2['firstnum'];
													$_var_2_money = $_var_2['secondnumprice'] * $num;//首价格
													$_var_money = $_var_1_money + $_var_2_money   ;
												}
												
											}else{
											
													$_var_money = $_var_2['secondprice'] * $_var_0['total'];
											}
										}
							   
							   }else{
									
									if($_var_2['calculatetype']==1){
										if($_var_2['firstnum']>=$_var_0['total']){
											$_var_money = $_var_2['firstnumprice'];
										}else{
											
											$_var_1_money = $_var_2['firstnumprice'];//首价格
											$num = $_var_0['total'] - $_var_2['firstnum'];
											$_var_2_money = $_var_2['secondnumprice'] * $num;//首价格
											$_var_money = $_var_1_money + $_var_2_money   ;

										}
										
									}else{
									
										$_var_money = $_var_2['secondprice'] * $_var_0['total'];
									}
	
							   }
							
							}else{
								
								if($_var_0['price'] >= $_var_6){
									$_var_money = 0;
								}else{
									if($_var_2['calculatetype']==1){
										if($_var_2['firstnum']>=$_var_0['total']){
											$_var_money = $_var_2['firstnumprice'];
										}else{
											
											$_var_1_money = $_var_2['firstnumprice'];//首价格
											$num = $_var_0['total'] - $_var_2['firstnum'];
											$_var_2_money = $_var_2['secondnumprice'] * $num;//首价格
											$_var_money = $_var_1_money + $_var_2_money   ;
											
										
										}
										
									}else{
									
									$_var_money = $_var_2['secondprice'] * $_var_0['total'];
									}
								}
								
							}
						}else{
							
							if($_var_2['calculatetype']==1){
								if($_var_2['firstnum']>=$_var_0['total']){
									$_var_money = $_var_2['firstnumprice'];
								}else{
									
									$_var_1_money = $_var_2['firstnumprice'];//首价格
									$num = $_var_0['total'] - $_var_2['firstnum'];
									$_var_2_money = $_var_2['secondnumprice'] * $num;//首价格
									$_var_money = $_var_1_money + $_var_2_money   ;
									
								
								}
							
							}else{
							
								$_var_money = $_var_2['secondprice'] * $_var_0['total'];
							}
						
						}
						
					
				}
					
						
                    $dephp_86['oldprice'] = $dephp_89;
                    $dephp_86['goodsprice'] = $dephp_91;
                    $dephp_86['activity_uid'] = $dephp_97;
                    $dephp_86['couponprice'] = $dephp_92;
                    $dephp_86['discountprice'] = $dephp_93;
                    $dephp_86['deductprice'] = $dephp_94;
                    $dephp_86['deductcredit2'] = $dephp_95;
                    $dephp_86['changeprice'] = $dephp_90;
					
                   // $dephp_86['dispatchprice'] = round($dephp_83 / (count($dephp_70)), 2);
				    $dephp_86['dispatchprice'] = $_var_money;
                   // $dephp_86['olddispatchprice'] = round($dephp_84 / (count($dephp_70)), 2);
				    $dephp_86['olddispatchprice'] = $_var_money;
                    $dephp_86['changedispatchprice'] = round($dephp_85 / (count($dephp_70)), 2);
                    $dephp_86['price'] = $dephp_88 - $dephp_92 - $dephp_93 - $dephp_94 - $dephp_95 + $dephp_86['dispatchprice'];
                    if($dephp_82 == false){
                        pdo_update('sz_yi_order', $dephp_86, array('id' => $dephp_74, 'uniacid' => $_W['uniacid']));
                        $dephp_82 = ture;
                    }else{
                        $dephp_86['uniacid'] = $_W['uniacid'];
                        $dephp_99 = m('common') -> createNO('order', 'ordersn', 'SH');
                        $dephp_86['ordersn'] = $dephp_99;
                        pdo_insert('sz_yi_order', $dephp_86);
                        $dephp_100 = pdo_insertid();
                        $dephp_101 = array('orderid' => $dephp_100);
                        foreach ($dephp_81 as $dephp_102){
                            pdo_update('sz_yi_order_goods', $dephp_101 , array('id' => $dephp_102['id'], 'uniacid' => $_W['uniacid']));
                        }
                    }
                }
            }
        }
    }
}
