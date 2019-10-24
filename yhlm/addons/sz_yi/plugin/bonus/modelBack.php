<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
define('TM_COMMISSION_AGENT_NEW', 'commission_agent_new');
define('TM_BONUS_ORDER_PAY', 'bonus_order_pay');
define('TM_BONUS_ORDER_FINISH', 'bonus_order_finish');
define('TM_COMMISSION_APPLY', 'commission_apply');
define('TM_COMMISSION_CHECK', 'commission_check');
define('TM_BONUS_PAY', 'bonus_pay');
define('TM_BONUS_GLOBAL_PAY', 'bonus_global_pay');
define('TM_BONUS_UPGRADE', 'bonus_upgrade');
define('TM_COMMISSION_BECOME', 'commission_become');
if (!class_exists('BonusModel')){
    class BonusModel extends PluginModel{
        private $agents = array();
        private $parentAgents = array();
        public function getSet(){
            $dephp_0 = parent :: getSet();
            $dephp_0['texts'] = array('agent' => empty($dephp_0['texts']['agent']) ? '代理商' : $dephp_0['texts']['agent'], 'premiername' => empty($dephp_0['texts']['premiername']) ? '全球分红' : $dephp_0['texts']['premiername'], 'center' => empty($dephp_0['texts']['center']) ? '分红中心' : $dephp_0['texts']['center'], 'commission' => empty($dephp_0['texts']['commission']) ? '佣金' : $dephp_0['texts']['commission'], 'commission1' => empty($dephp_0['texts']['commission1']) ? '分红佣金' : $dephp_0['texts']['commission1'], 'commission_total' => empty($dephp_0['texts']['commission_total']) ? '累计分红佣金' : $dephp_0['texts']['commission_total'], 'commission_ok' => empty($dephp_0['texts']['commission_ok']) ? '待分红佣金' : $dephp_0['texts']['commission_ok'], 'commission_apply' => empty($dephp_0['texts']['commission_apply']) ? '已申请佣金' : $dephp_0['texts']['commission_apply'], 'commission_check' => empty($dephp_0['texts']['commission_check']) ? '待打款佣金' : $dephp_0['texts']['commission_check'], 'commission_lock' => empty($dephp_0['texts']['commission_lock']) ? '未结算佣金' : $dephp_0['texts']['commission_lock'], 'commission_detail' => empty($dephp_0['texts']['commission_detail']) ? '分红明细' : $dephp_0['texts']['commission_detail'], 'commission_pay' => empty($dephp_0['texts']['commission_pay']) ? '已分红佣金' : $dephp_0['texts']['commission_pay'], 'order' => empty($dephp_0['texts']['order']) ? '分红订单' : $dephp_0['texts']['order'], 'order_area' => empty($dephp_0['texts']['order_area']) ? '区域订单' : $dephp_0['texts']['order_area'], 'mycustomer' => empty($dephp_0['texts']['mycustomer']) ? '我的下线' : $dephp_0['texts']['mycustomer'], 'agent_province' => empty($dephp_0['texts']['agent_province']) ? '省级代理' : $dephp_0['texts']['agent_province'], 'agent_city' => empty($dephp_0['texts']['agent_city']) ? '市级代理' : $dephp_0['texts']['agent_city'], 'agent_district' => empty($dephp_0['texts']['agent_district']) ? '区级代理' : $dephp_0['texts']['agent_district']);
            return $dephp_0;
        }
        public function getParentAgents($dephp_1, $dephp_2 = 0){		//id 1		获取上级代理
            global $_W;
            $dephp_3 = 'select id, agentid, bonuslevel, bonus_status from ' . tablename('sz_yi_member') . " where id={$dephp_1} and uniacid=" . $_W['uniacid'];
            $dephp_4 = pdo_fetch($dephp_3);
            if(empty($dephp_4)){
                return $this -> parentAgents;
            }else{
            	if(empty($this -> parentAgents[$dephp_4['bonuslevel']])){		//如果分红等级为空
                    $this -> parentAgents[$dephp_4['bonuslevel']] = $dephp_4['id'];		//返回id
                }
                if($dephp_4['agentid'] != 0){				
                    return $this -> getParentAgents($dephp_4['agentid']);		//调用自己查找
                }else{
                    return $this -> parentAgents;
                }
            }
        }
        

        public function getMerch($uid=0){
            return pdo_fetch('seelct * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid=:uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
        }
        

        //根据订单id 查找商家 然后查找上级 查找代理
        public function calculateorder($dephp_5 = 0, $dephp_6 = 0){        //orderid money
            global $_W;
            $dephp_0 = $this -> getSet();
            $dephp_8 = time();
          
            $dephp_9 = pdo_fetch('select openid, supplier_uid from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid limit 1', array(':orderid' => $dephp_5));
            $dephp_10 = $dephp_9['openid'];
            $dephp_11 = array();
            
            $sql = 'SELECT province,city,district FROM'.tablename('sz_yi_dealmerch_user').'WHERE uniacid=:uniacid AND uid=:uid';
            $a = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':uid' => $dephp_9['supplier_uid']));
            if (!empty($a)) {
                $dephp_11['province'] = $a['province'];
                $dephp_11['city']     = $a['city'];
                $dephp_11['area']     = $a['district'];
            }
        
            
            $dephp_12 = pdo_fetchall('select og.id,og.realprice,og.price,og.goodsid,og.total,og.optionname,g.hascommission,g.nocommission,g.bonusmoney from ' . tablename('sz_yi_order_goods') . '  og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id = og.goodsid' . ' where og.orderid=:orderid and og.uniacid=:uniacid', array(':orderid' => $dephp_5, ':uniacid' => $_W['uniacid']));
                //订单信息
            $dephp_13 = m('member') -> getInfo($dephp_10);  //买家信息
            foreach ($dephp_12 as $dephp_14){
                // $dephp_15 = $dephp_14['bonusmoney'] > 0 && !empty($dephp_14['bonusmoney']) ? $dephp_14['bonusmoney'] * $dephp_14['total'] : $dephp_14['price'];
                $dephp_15 = intval($dephp_6);
                //如果订单分红金额大于0 分红*总数  否则直接总价格
                    $dephp_16 = $dephp_13['agentid'];
           
                if(!empty($dephp_16)){      //如果有上级代理
                    $dephp_18 = 0;
                    $dephp_22 = floatval($dephp_0['bart_agent_bonus']) / 100;
                    $tempmerch=$this->getMerch($dephp_9['supplier_uid']);
                    $tempmember=m('member')->getMember($tempmerch['openid']);
                    $dephp_23 = round($dephp_15 * $dephp_22, 2);
                    if(empty($dephp_0['differential'])){
                        $dephp_24 = $dephp_23 - $dephp_18;
                        $dephp_18 = $dephp_23;
                    }else{
                        $dephp_24 = $dephp_23;
                    }
                    $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'],'isbart'=>'1', 'optionname' => $dephp_14['optionname'], 'mid' => $tempmember['id'],'money' => $dephp_24, 'createtime' => $dephp_8);
                    pdo_insert('sz_yi_bonus_goods', $dephp_25);
                }


                if(!empty($dephp_0['area_start'])){     //
                    $dephp_26 = 0;
                    $dephp_27 = floatval($dephp_0['bonus_area_bonus']);        //区级代理
                    if(!empty($dephp_27)){          // 区域代理分红不为空
                        $dephp_28 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_city=\'' . $dephp_11['city'] . '\' and bonus_district=\'' . $dephp_11['area'] . '\' and bonus_area=3 and uniacid=' . $_W['uniacid']);
                            //查找一个区域代理 且与发货地址在同一地区
                            
                            //默认比例 或 区域代理
                        if(!empty($dephp_28)){
                            // if($dephp_28['bonus_area_commission'] > 0){             //如无默认 从设置中获取
                                // $dephp_29 = round($dephp_15 * $dephp_28['bonus_area_commission'] / 100, 2);
                            // }else{  //百分比
                                $dephp_29 = round($dephp_15 * $dephp_0['bonus_area_bonus'] / 100, 2);
                            // }
                            //如开启级差
                            if(empty($dephp_0['differential'])){
                                $dephp_29 = $dephp_29 - $dephp_26;          
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_28['id'], 'isbart'=>'1','bonus_area' => 3, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                            }
                             
                        }
                    }
                    
                    //市级代理
                    $dephp_30 = floatval($dephp_0['bart_city_bonus']);
                    if(!empty($dephp_30)){
                        //一个区域只有一个代理 ? 
                        $dephp_31 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_city=\'' . $dephp_11['city'] . '\' and bonus_area=2 and uniacid=' . $_W['uniacid']);
                        if(!empty($dephp_31)){
                                //具体金额
                            $dephp_29 = round($dephp_15 * $dephp_0['bart_city_bonus'] / 100, 2);
                          
                            if(empty($dephp_0['differential'])){
                                $dephp_29 = $dephp_29 - $dephp_26;
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_31['id'],'isbart'=>'1','bonus_area' => 2, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                                //记录日志
                            }
                        }
                    }
                    
                    //省级代理
                    $dephp_32 = floatval($dephp_0['bart_province_bonus']);
                    if(!empty($dephp_32)){
                        $dephp_33 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_area=1 and uniacid=' . $_W['uniacid']);
                        if(!empty($dephp_33)){
                            
                                $dephp_29 = round($dephp_15 * $dephp_0['bart_province_bonus'] / 100, 2);
                            if(empty($dephp_0['differential'])){
                                $dephp_29 = $dephp_29 - $dephp_26;
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_33['id'], 'isbart'=>'1','bonus_area' => 1, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                            }
                        }
                    }
                }
            }
        }



        //激活易货码
        public function calculateactive($dephp_5 = 0, $dephp_6 = 0,$ordersn=''){        //openid money
            global $_W;
            $dephp_0 = $this->getSet();
            $dephp_8 = time();
            
            $dephp_13=m('member')->getMember($dephp_5);

            $dephp_9 = pdo_fetch('select uid from ' . tablename('sz_yi_perm_user') . ' where openid=:openid and dealmerchid > 0 limit 1', array(':openid' => $dephp_5));
            $dephp_10 = $dephp_9['openid'];
            
            $dephp_11 = array();
            $sql = 'SELECT province,city,district FROM'.tablename('sz_yi_dealmerch_user').'WHERE uniacid=:uniacid AND uid=:uid';
            $a = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':uid' => $dephp_9['uid']));
            if (!empty($a)) {
                $dephp_11['province'] = $a['province'];
                $dephp_11['city']     = $a['city'];
                $dephp_11['area']     = $a['area'];
            }
            
                $dephp_15 = intval($dephp_6);
                //如果订单分红金额大于0 分红*总数  否则直接总价格
                    $dephp_16 = $dephp_13['agentid'];
           
                if(!empty($dephp_16)){      //如果有上级代理
                    $dephp_18 = 0;
                    $tempmember=m('member')->getMember($dephp_16);
                    $dephp_22 = floatval($dephp_0['bart_agent_bonus']) / 100;
                    $dephp_23 = round($dephp_15 * $dephp_22, 2);
                    if(empty($dephp_0['differential'])){
                        $dephp_24 = $dephp_23 - $dephp_18;
                        $dephp_18 = $dephp_23;
                    }else{
                        $dephp_24 = $dephp_23;
                    }
                    $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => '0', 'orderid' => '0', 'total' => '1', 'optionname' =>'激活易货码','ordersn'=>$ordersn,'isbart'=>'1','type'=>'1','mid' => $tempmember['id'],'money' => $dephp_24, 'createtime' => $dephp_8);
                    pdo_insert('sz_yi_bonus_goods', $dephp_25);
                }


                    $dephp_26 = 0;
                if(!empty($dephp_0['area_start'])){     //
                    $dephp_27 = floatval($dephp_0['bonus_area_bonus']);        //区级代理
                    if(!empty($dephp_27)){          // 区域代理分红不为空
                        $dephp_28 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_city=\'' . $dephp_11['city'] . '\' and bonus_district=\'' . $dephp_11['area'] . '\' and bonus_area=3 and uniacid=' . $_W['uniacid']);
                            //查找一个区域代理 且与发货地址在同一地区
                            
                            //默认比例 或 区域代理
                        if(!empty($dephp_28)){
                            // if($dephp_28['bonus_area_commission'] > 0){             //如无默认 从设置中获取
                                // $dephp_29 = round($dephp_15 * $dephp_28['bonus_area_commission'] / 100, 2);
                            // }else{  //百分比
                                $dephp_29 = round($dephp_15 * $dephp_0['bonus_area_bonus'] / 100, 2);
                            // }
                            //如开启级差
                            if(empty($dephp_0['differential'])){
                                $dephp_29 = $dephp_29 - $dephp_26;          
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' =>'0', 'orderid' => '0', 'total' =>'1', 'optionname' =>'激活易货码' ,'ordersn'=>$ordersn,'isbart'=>'1','type'=>'1', 'mid' => $dephp_28['id'], 'bonus_area' => 3, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                            }
                             
                        }
                    }
                    
                    //市级代理
                    $dephp_30 = floatval($dephp_0['bart_city_bonus']);
                    if(!empty($dephp_30)){
                        //一个区域只有一个代理 ? 
                        $dephp_31 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_city=\'' . $dephp_11['city'] . '\' and bonus_area=2 and uniacid=' . $_W['uniacid']);
                        if(!empty($dephp_31)){
                                //具体金额
                            $dephp_29 = round($dephp_15 * $dephp_0['bart_city_bonus'] / 100, 2);
                          
                            if(empty($dephp_0['differential'])){
                                $dephp_29 = $dephp_29 - $dephp_26;
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' =>'0', 'orderid' =>'0', 'total' => '1', 'optionname' => '激活易货码', 'mid' => $dephp_31['id'], 'bonus_area' => 2, 'ordersn'=>$ordersn,'isbart'=>'1','type'=>'1','money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                                //记录日志
                            }
                        }
                    }
                    
                    //省级代理
                    $dephp_32 = floatval($dephp_0['bart_province_bonus']);
                    if(!empty($dephp_32)){
                        $dephp_33 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_area=1 and uniacid=' . $_W['uniacid']);
                        if(!empty($dephp_33)){
                            
                                $dephp_29 = round($dephp_15 * $dephp_0['bart_province_bonus'] / 100, 2);
                            if(empty($dephp_0['differential'])){
                                $dephp_29 = $dephp_29 - $dephp_26;
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => '0', 'orderid' => '0', 'total' =>'1', 'optionname' => '激活易货码', 'mid' => $dephp_33['id'], 'bonus_area' => 1,'ordersn'=>$ordersn,'isbart'=>'1','type'=>'1', 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                            }
                        }
                    }
                }
            // }
        }


        
        public function calculate($dephp_5 = 0, $dephp_6 = true){
            global $_W;
            $dephp_0 = $this -> getSet();
            $dephp_7 = $this -> getLevels();
            $dephp_8 = time();
            // $dephp_9 = pdo_fetch('select openid, address, supplier_uid from ' . tablename('sz_yi_order') . ' where id=:id limit 1', array(':id' => $dephp_5));
            $dephp_9 = pdo_fetch('select openid, supplier_uid from ' . tablename('sz_yi_order_goods') . ' where orderid=:orderid limit 1', array(':orderid' => $dephp_5));
            $dephp_10 = $dephp_9['openid'];
            // $dephp_11 = unserialize($dephp_9['address']);
            $dephp_11 = array();
            // 区域分红由按收货地址分佣改成按发货地址分佣
            $sql = 'SELECT provance,city,area FROM'.tablename('sz_yi_perm_user').'WHERE uniacid=:uniacid AND uid=:uid';
            $a = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':uid' => $dephp_9['supplier_uid']));
            if (!empty($a)) {
                $dephp_11['province'] = $a['provance'];
                $dephp_11['city']     = $a['city'];
                $dephp_11['area']     = $a['area'];
            }
            // 区域分红由按收货地址分佣改成按发货地址分佣
			
            $dephp_12 = pdo_fetchall('select og.id,og.realprice,og.price,og.goodsid,og.total,og.optionname,g.hascommission,g.nocommission,g.bonusmoney from ' . tablename('sz_yi_order_goods') . '  og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id = og.goodsid' . ' where og.orderid=:orderid and og.uniacid=:uniacid', array(':orderid' => $dephp_5, ':uniacid' => $_W['uniacid']));
            	//订单信息
            $dephp_13 = m('member') -> getInfo($dephp_10);	//商家信息
            $dephp_7 = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_bonus_level') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY level asc");
            	//查询等级 (空)
            foreach ($dephp_12 as $dephp_14){
                $dephp_15 = $dephp_14['bonusmoney'] > 0 && !empty($dephp_14['bonusmoney']) ? $dephp_14['bonusmoney'] * $dephp_14['total'] : $dephp_14['price'];
                //如果订单分红金额大于0 分红*总数  否则直接总价格
                if(empty($dephp_0['selfbuy'])){
                    $dephp_16 = $dephp_13['agentid'];
                }else{		//如果开启内购分红 
                    $dephp_16 = $dephp_13['id'];
                }
                if(!empty($dephp_16)){
                    $dephp_17 = $this -> getParentAgents($dephp_16, 1);			//id
                    $dephp_18 = 0;
                    foreach ($dephp_7 as $dephp_19 => $dephp_20){
                        $dephp_21 = $dephp_20['id'];
                        if(array_key_exists($dephp_21, $dephp_17)){
                            if($dephp_20['agent_money'] > 0){
                                $dephp_22 = $dephp_20['agent_money'] / 100;
                            }else{
                                continue;
                            }
                            $dephp_23 = round($dephp_15 * $dephp_22, 2);
                            if(empty($dephp_0['isdistinction'])){
                                $dephp_24 = $dephp_23 - $dephp_18;
                                $dephp_18 = $dephp_23;
                            }else{
                                $dephp_24 = $dephp_23;
                            }
                            $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_17[$dephp_21], 'levelid' => $dephp_21, 'money' => $dephp_24, 'createtime' => $dephp_8);
                            pdo_insert('sz_yi_bonus_goods', $dephp_25);
                        }
                    }
                }

                if(!empty($dephp_0['area_start'])){		//
                    $dephp_26 = 0;
                    $dephp_27 = floatval($dephp_0['bonus_commission3']);		//区级代理
                    if(!empty($dephp_27)){			// 区域代理分红不为空
                        $dephp_28 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_city=\'' . $dephp_11['city'] . '\' and bonus_district=\'' . $dephp_11['area'] . '\' and bonus_area=3 and uniacid=' . $_W['uniacid']);
                        	//查找一个区域代理 且与发货地址在同一地区
                        	
                        	//默认比例 或 区域代理
                        if(!empty($dephp_28)){
                            if($dephp_28['bonus_area_commission'] > 0){ 			//如无默认 从设置中获取
                                $dephp_29 = round($dephp_15 * $dephp_28['bonus_area_commission'] / 100, 2);
                            }else{	//百分比
                                $dephp_29 = round($dephp_15 * $dephp_0['bonus_commission3'] / 100, 2);
                            }
                            //如开启级差
                            if(empty($dephp_0['isdistinction_area'])){
                                $dephp_29 = $dephp_29 - $dephp_26;			
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_28['id'], 'bonus_area' => 3, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                            }
                             
                        }
                    }
                    
                    //市级代理
                    $dephp_30 = floatval($dephp_0['bonus_commission2']);
                    if(!empty($dephp_30)){
                    	//一个区域只有一个代理 ? 
                        $dephp_31 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_city=\'' . $dephp_11['city'] . '\' and bonus_area=2 and uniacid=' . $_W['uniacid']);
                        if(!empty($dephp_31)){
                            if($dephp_31['bonus_area_commission'] > 0){
                                $dephp_29 = round($dephp_15 * $dephp_31['bonus_area_commission'] / 100, 2);
                            }else{		//具体金额
                                $dephp_29 = round($dephp_15 * $dephp_0['bonus_commission2'] / 100, 2);
                            }
                            if(empty($dephp_0['isdistinction_area'])){
                                $dephp_29 = $dephp_29 - $dephp_26;
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_31['id'], 'bonus_area' => 2, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                                //记录日志
                            }
                        }
                    }
                    
                    //省级代理
                    $dephp_32 = floatval($dephp_0['bonus_commission1']);
                    if(!empty($dephp_32)){
                        $dephp_33 = pdo_fetch('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $dephp_11['province'] . '\' and bonus_area=1 and uniacid=' . $_W['uniacid']);
                        if(!empty($dephp_33)){
                            if($dephp_33['bonus_area_commission'] > 0){
                                $dephp_29 = round($dephp_15 * $dephp_33['bonus_area_commission'] / 100, 2);
                            }else{
                                $dephp_29 = round($dephp_15 * $dephp_0['bonus_commission1'] / 100, 2);
                            }
                            if(empty($dephp_0['isdistinction_area'])){
                                $dephp_29 = $dephp_29 - $dephp_26;
                                $dephp_26 = $dephp_29;
                            }
                            if($dephp_29 > 0){
                                $dephp_25 = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $dephp_14['goodsid'], 'orderid' => $dephp_5, 'total' => $dephp_14['total'], 'optionname' => $dephp_14['optionname'], 'mid' => $dephp_33['id'], 'bonus_area' => 1, 'money' => $dephp_29, 'createtime' => $dephp_8);
                                pdo_insert('sz_yi_bonus_goods', $dephp_25);
                            }
                        }
                    }
                }
            }
        }
        
        public function getChildAgents($dephp_1){
            global $_W;
            $dephp_3 = 'select id from ' . tablename('sz_yi_member') . " where agentid={$dephp_1} and status=1 and isagent = 1 and uniacid=" . $_W['uniacid'];
            $dephp_34 = pdo_fetchall($dephp_3);
            foreach ($dephp_34 as $dephp_35){
                $this -> agents[] = $dephp_35['id'];
                $this -> getChildAgents($dephp_35['id']);
            }
            return $this -> agents;
        }
        public function getLevels($dephp_36 = true){
            global $_W;
            if ($dephp_36){
                return pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid order by level asc', array(':uniacid' => $_W['uniacid']));
            }else{
                return pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and (ordermoney>0 or commissionmoney>0) order by level asc', array(':uniacid' => $_W['uniacid']));
            }
        }
        public function premierInfo($dephp_10, $dephp_37 = null){
            if (empty($dephp_37) || !is_array($dephp_37)){
                $dephp_37 = array();
            }
            global $_W;
            $dephp_0 = $this -> getSet();
            $dephp_13 = m('member') -> getInfo($dephp_10);
            $dephp_38 = 0;
            $dephp_39 = 0;
            $dephp_40 = 0;
            $dephp_41 = 0;
            $dephp_42 = 0;
            $dephp_8 = time();
            $dephp_43 = intval($dephp_0['settledays']) * 3600 * 24;
            if (in_array('ok', $dephp_37)){
                $dephp_3 = 'select sum(o.price) as money from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and ({$dephp_8} - o.createtime > {$dephp_43}) ORDER BY o.createtime DESC,o.status DESC";
                $dephp_39 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid']));
            }
            if (in_array('total', $dephp_37)){
                $dephp_3 = 'select sum(o.price) as money from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=1 and o.uniacid=:uniacid  ORDER BY o.createtime DESC,o.status DESC';
                $dephp_38 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid']));
            }
            if (in_array('pay', $dephp_37)){
                $dephp_3 = 'select sum(money) from ' . tablename('sz_yi_bonus_log') . ' where openid=:openid and isglobal=1 and uniacid=:uniacid';
                $dephp_40 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid'], 'openid' => $dephp_13['openid']));
            }
            if (in_array('myorder', $dephp_44)){
                $dephp_45 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $dephp_13['openid']));
                $dephp_41 = $dephp_45['ordermoney'];
                $dephp_42 = $dephp_45['ordercount'];
            }
            $dephp_13['commission_ok'] = round($dephp_39, 2);
            $dephp_13['commission_total'] = round($dephp_38, 2);
            $dephp_13['commission_pay'] = $dephp_40;
            $dephp_13['myordermoney'] = $dephp_41;
            $dephp_13['myordercount'] = $dephp_42;
            return $dephp_13;
        }
        public function getInfo($dephp_10, $dephp_37 = null){
            if (empty($dephp_37) || !is_array($dephp_37)){
                $dephp_37 = array();
            }
            global $_W;
            $dephp_0 = $this -> getSet();
            $dephp_13 = m('member') -> getInfo($dephp_10);
            if(empty($dephp_13['id'])){
                return false;
            }
            $dephp_38 = 0;
            $dephp_39 = 0;
            $dephp_46 = 0;
            $dephp_47 = 0;
            $dephp_48 = 0;
            $dephp_40 = 0;
            $dephp_49 = 0;
            $dephp_50 = 0;
            $dephp_51 = 0;
            $dephp_41 = 0;
            $dephp_42 = 0;
            $dephp_52 = $dephp_13['id'];
            $dephp_8 = time();
            $dephp_43 = intval($dephp_0['settledays']) * 3600 * 24;
            $this -> agents = array();
            if (in_array('totaly', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=0 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} and cg.bonus_area = 0";
                $dephp_49 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid']));
            }
            if (in_array('totaly_area', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=0 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} and cg.bonus_area!=0";
                $dephp_50 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid']));
            }
            if (in_array('ok', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} and ({$dephp_8} - o.createtime > {$dephp_43}) ORDER BY o.createtime DESC,o.status DESC";
                $dephp_39 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid']));
            }
            if (in_array('total', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=1 and o.uniacid=:uniacid and cg.mid = {$dephp_52} ORDER BY o.createtime DESC,o.status DESC";
                $dephp_38 = pdo_fetchcolumn($dephp_3, array(':uniacid' => $_W['uniacid']));
                // $dephp_38 = 88;
            }
            if (in_array('ordercount', $dephp_37)){
                $dephp_53 = pdo_fetchcolumn('select count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_bonus_goods') . ' cg on cg.orderid=o.id  where o.status>=0 and cg.status>=0 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid =' . $dephp_52 . ' and cg.bonus_area=0 limit 1');
            }
            if (in_array('ordercount_area', $dephp_37)){
                $dephp_51 = pdo_fetchcolumn('select count(distinct o.id) as ordercount_area from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_bonus_goods') . ' cg on cg.orderid=o.id  where o.status>=0 and cg.status>=0 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid =' . $dephp_52 . ' and cg.bonus_area!=0 limit 1');
            }
            if (in_array('apply', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=1 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} and ({$dephp_8} - o.createtime <= {$dephp_43}) ORDER BY o.createtime DESC,o.status DESC";
                $dephp_46 = pdo_fetchcolumn($dephp_3);
            }
            if (in_array('check', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=2 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} and ({$dephp_8} - o.createtime <= {$dephp_43}) ORDER BY o.createtime DESC,o.status DESC";
                $dephp_47 = pdo_fetchcolumn($dephp_3);
            }
            if (in_array('pay', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=3 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} ORDER BY o.createtime DESC,o.status DESC";
                $dephp_40 = pdo_fetchcolumn($dephp_3);
            }
            if (in_array('lock', $dephp_37)){
                $dephp_3 = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=1 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and cg.mid = {$dephp_52} and ({$dephp_8} - o.createtime <= {$dephp_43}) ORDER BY o.createtime DESC,o.status DESC";
                $dephp_48 = pdo_fetchcolumn($dephp_3);
            }
            if (in_array('myorder', $dephp_44)){
                $dephp_45 = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $dephp_13['openid']));
                $dephp_41 = $dephp_45['ordermoney'];
                $dephp_42 = $dephp_45['ordercount'];
            }
            $dephp_54 = $this -> getChildAgents($dephp_13['id']);
            $dephp_55 = count($dephp_54);
            $dephp_13['commission_ok'] = isset($dephp_39) ? $dephp_39 : 0;
            $dephp_13['commission_total'] = isset($dephp_38) ? $dephp_38 : 0;
            $dephp_13['commission_pay'] = isset($dephp_40) ? $dephp_40 : 0;
            $dephp_13['commission_apply'] = isset($dephp_46) ? $dephp_46 : 0;
            $dephp_13['commission_check'] = isset($dephp_47) ? $dephp_47 : 0;
            $dephp_13['commission_lock'] = isset($dephp_48) ? $dephp_48 : 0;
            $dephp_13['commission_totaly'] = isset($dephp_49) ? $dephp_49 : 0;
            $dephp_13['commission_totaly_area'] = isset($dephp_50) ? $dephp_50 : 0;
            $dephp_13['ordercount'] = $dephp_53;
            $dephp_13['ordercount_area'] = $dephp_51;
            $dephp_13['agentcount'] = $dephp_55;
            $dephp_13['agentids'] = $dephp_54;
            $dephp_13['myordermoney'] = $dephp_41;
            $dephp_13['myordercount'] = $dephp_42;
            return $dephp_13;
        }
        public function checkOrderConfirm($dephp_5 = '0'){
            global $_W, $_GPC;
            $dephp_0 = $this -> getSet();
            if(empty($dephp_0['start'])){
                return;
            }
            $this -> calculate($dephp_5);
        }
        public function checkOrderPay($dephp_5 = '0'){
            global $_W, $_GPC;
            $dephp_0 = $this -> getSet();
            if(empty($dephp_0['start'])){
                return;
            }
            $dephp_9 = pdo_fetch('select id,openid,ordersn,goodsprice,agentid,paytime from ' . tablename('sz_yi_order') . ' where id=:id and status>=1 and uniacid=:uniacid limit 1', array(':id' => $dephp_5, ':uniacid' => $_W['uniacid']));
            if (empty($dephp_9)){
                return;
            }
            $dephp_10 = $dephp_9['openid'];
            $dephp_13 = m('member') -> getMember($dephp_10);
            if (empty($dephp_13)){
                return;
            }


            $dephp_56 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $dephp_9['id']));
            $dephp_12 = '';
            $dephp_57 = 0;
            foreach ($dephp_56 as $dephp_58){
                $dephp_12 .= "" . $dephp_58['title'] . '( ';
                if (!empty($dephp_58['optiontitle'])){
                    $dephp_12 .= ' 规格: ' . $dephp_58['optiontitle'];
                }
                $dephp_12 .= ' 单价: ' . ($dephp_58['realprice'] / $dephp_58['total']) . ' 数量: ' . $dephp_58['total'] . ' 总价: ' . $dephp_58['realprice'] . '); ';
                $dephp_57 += $dephp_58['realprice'];
            }
            $dephp_59 = pdo_fetchall('select distinct mid from ' . tablename('sz_yi_bonus_goods') . ' where uniacid=:uniacid and orderid=:orderid', array(':orderid' => $dephp_9['id'], ':uniacid' => $_W['uniacid']));
            foreach ($dephp_59 as $dephp_19 => $dephp_60){
                $dephp_10 = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_member') . ' where id=' . $dephp_60['mid'] . ' and uniacid=' . $_W['uniacid']);
                $dephp_61 = pdo_fetchcolumn('select sum(money) from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $dephp_60['mid'] . ' and orderid=' . $dephp_9['id'] . ' and bonus_area=0 and uniacid=' . $_W['uniacid']);
                $this -> sendMessage($dephp_10, array('nickname' => $dephp_13['nickname'], 'ordersn' => $dephp_9['ordersn'], 'price' => $dephp_57, 'goods' => $dephp_12, 'commission' => $dephp_61, 'paytime' => $dephp_9['paytime']), TM_BONUS_ORDER_PAY);
            }

            $this->upgradeLevelByGood_type($dephp_5);
        }
        public function checkOrderFinish($dephp_5 = ''){



            global $_W, $_GPC;
            if (empty($dephp_5)){
                return;
            }
            $dephp_0 = $this -> getSet();
            if(empty($dephp_0['start'])){
                return;
            }
            $dephp_9 = pdo_fetch('select id,openid,ordersn,goodsprice,agentid,paytime,finishtime from ' . tablename('sz_yi_order') . ' where id=:id and status>=1 and uniacid=:uniacid limit 1', array(':id' => $dephp_5, ':uniacid' => $_W['uniacid']));
            if (empty($dephp_9)){
                return;
            }
            $dephp_10 = $dephp_9['openid'];
            $dephp_13 = m('member') -> getMember($dephp_10);
            if (empty($dephp_13)){
                return;
            }

           

            $dephp_56 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $dephp_9['id']));
            $dephp_12 = '';
            $dephp_57 = 0;
            foreach ($dephp_56 as $dephp_58){
                $dephp_12 .= "" . $dephp_58['title'] . '( ';
                if (!empty($dephp_58['optiontitle'])){
                    $dephp_12 .= ' 规格: ' . $dephp_58['optiontitle'];
                }
                $dephp_12 .= ' 单价: ' . ($dephp_58['realprice'] / $dephp_58['total']) . ' 数量: ' . $dephp_58['total'] . ' 总价: ' . $dephp_58['realprice'] . '); ';
                $dephp_57 += $dephp_58['realprice'];
            }
            $dephp_59 = pdo_fetchall('select distinct mid from ' . tablename('sz_yi_bonus_goods') . ' where uniacid=:uniacid and orderid=:orderid', array(':orderid' => $dephp_5, ':uniacid' => $_W['uniacid']));
            foreach ($dephp_59 as $dephp_19 => $dephp_60){
                $dephp_10 = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_member') . ' where id=' . $dephp_60['mid'] . ' and uniacid=' . $_W['uniacid']);
                $dephp_61 = pdo_fetchcolumn('select sum(money) from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $dephp_60['mid'] . ' and orderid=' . $dephp_9['id'] . ' and bonus_area=0 and uniacid=' . $_W['uniacid']);
                $this -> sendMessage($dephp_10, array('nickname' => $dephp_13['nickname'], 'ordersn' => $dephp_9['ordersn'], 'price' => $dephp_57, 'goods' => $dephp_12, 'commission' => $dephp_61, 'finishtime' => $dephp_9['finishtime']), TM_BONUS_ORDER_FINISH);
            }


          $this->upgradeLevelByGood($dephp_5);

        }


        function upgradeLevelByGood_type($_var_1)
        {
            global $_W;
            $_var_0 = $this->getSet();
            if (!$_var_0['level_on']) {
                return;
            }
            $_var_5 = pdo_fetchall('select g.bonus_level_id,g.type as type  from ' . tablename('sz_yi_order_goods') . ' AS og, ' . tablename('sz_yi_goods') . ' AS g WHERE g.bonus_level_id !=0  and og.goodsid = g.id AND og.orderid=:orderid AND og.uniacid=:uniacid  ', array(':orderid' => $_var_1, ':uniacid' => $_W['uniacid']));

            $openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_order') . ' where uniacid=:uniacid and id=:orderid', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_1));

            foreach ($_var_5 as $key => $value) {

                 if($value['type']==4){

                     $_var_192 = $value['bonus_level_id'];
                     $this_level = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $_var_192 ));

                     $_var_196 = $this->getLevel($openid);

                     if($this_level['level']>$_var_196['level']){
                           pdo_update('sz_yi_member', array('bonuslevel' =>$_var_192), array('uniacid' => $_W['uniacid'], 'openid' =>$openid));
                     }


                 }
 

            }

        }        

 
        function upgradeLevelByGood($_var_1)
        {
            global $_W;
            $_var_0 = $this->getSet();
            if (!$_var_0['level_on']) {
                return;
            }
            $_var_5 = pdo_fetchall('select g.bonus_level_id,g.type  from ' . tablename('sz_yi_order_goods') . ' AS og, ' . tablename('sz_yi_goods') . ' AS g WHERE g.bonus_level_id !=0  and og.goodsid = g.id AND og.orderid=:orderid AND og.uniacid=:uniacid  ', array(':orderid' => $_var_1, ':uniacid' => $_W['uniacid']));

            $openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_order') . ' where uniacid=:uniacid and id=:orderid', array(':uniacid' => $_W['uniacid'], ':orderid' => $_var_1));

            foreach ($_var_5 as $key => $value) {
                 $_var_192 = $value['bonus_level_id'];
                 $this_level = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $_var_192 ));

                 $_var_196 = $this->getLevel($openid);

                 if($this_level['level']>$_var_196['level']){
                       pdo_update('sz_yi_member', array('bonuslevel' =>$_var_192), array('uniacid' => $_W['uniacid'], 'openid' =>$openid));
                 }

            }

        }

 






        public function getLevel($dephp_10){
            global $_W;
            if (empty($dephp_10)){
                return false;
            }
            $dephp_13 = m('member') -> getMember($dephp_10);
            if (empty($dephp_13['bonuslevel'])){
                return false;
            }
            $dephp_20 = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $dephp_13['bonuslevel']));
            return $dephp_20;
        }


 

 

        public function upgradeLevelByAgent($dephp_62){
            global $_W;
            if (empty($dephp_62)){
                return false;
            }
            $dephp_0 = $this -> getSet();
          
                     
            $dephp_13 = p('commission') -> getInfo( $dephp_62 , array("ordercount3", "ordermoney3", "order13money", "order13") ); 

            if (empty($dephp_13)){
                return;
            }

 

            if(empty($dephp_13['bonuslevel'])){
                $dephp_63 = false;
                $bonus_level = pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=' . $_W['uniacid'] . ' order by level asc');
            }else{
                $dephp_63 = $this -> getLevel($dephp_13['openid']);
                $dephp_65 = pdo_fetchcolumn('select level from ' . tablename('sz_yi_bonus_level') . ' where  uniacid=:uniacid and id=:bonuslevel order by level asc', array(':uniacid' => $_W['uniacid'], ':bonuslevel' => $dephp_13['bonuslevel']));
                $bonus_level = pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . " where  uniacid='{$_W['uniacid']}' and level>'{$dephp_65}'  order by level asc  ");
            }

            if(empty($bonus_level)){
                return false;
            }

            foreach ($bonus_level as $key => $dephp_64) {

                    $dephp_66 = array(1,2,3,4,5,6,7,8,9,10,11,12);
                    $dephp_67 = true;
          
                    $ok = array();

                    $update_con = json_decode($dephp_64['update_con'],true);

                    $i=0;

                    //一级分销订单金额满
                    if(in_array('1', $dephp_66)){
                        //$dephp_13["order13money"];
                        if(!empty($update_con[1])){
                           $i++;
                           if($dephp_13["order13money"] < $update_con[1]){
                             $dephp_67 = false;
                             
                           }else{
                               $ok[1] = 1;
                           }
                        }  
                       
                    }
                    
                    //分销订单数量满
                    if(in_array('2', $dephp_66)){
                        //$dephp_13["order13money"];
                       if(!empty($update_con[2])){
                           $i++;
                           if($dephp_13["ordercount3"] < $update_con[2]){
                             $dephp_67 = false;
                              
                           }else{
                             $ok[2] = 1;
                           }
                       }
                    }
            
                    //一级分销订单数量满
                    if(in_array('3', $dephp_66)){
                        //$dephp_13["order13money"];
                       if(!empty($update_con[3])){
                           $i++;
                           if($dephp_13["order13"] < $update_con[3]){
                             $dephp_67 = false;
                              
                           }else{
                             $ok[3] = 1;
                           }
                       }
                    }

                    //自购订单金额满
                    if(in_array('4', $dephp_66)){

                        $dephp_68 = pdo_fetchcolumn('select sum(price) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=3 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $dephp_13['openid']));



                        if(!empty($update_con['4'])){
           
                            $i++;
                            if($dephp_68 < $update_con['4']){
                                
                                $dephp_67 = false;
                                 
                            }else{
                                
                                  $ok[4] = 1;
                               
                            }
                        }
                    }

                    //自购订单数量满
                    if(in_array('5', $dephp_66)){

                    
                        $dephp_68 = pdo_fetchcolumn('select count(1) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=3 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $dephp_13['openid']));
                        if(!empty($update_con['5'])){
                            $i++;
                            if($dephp_68 < $update_con['5']){
                                $dephp_67 = false;
                                 
                            }else{
                                $ok[5] = 1;
                            }
                        }
                    }
         
           
                    //下级总人数满
                    if(in_array('6', $dephp_66)){

                        $level_arr = m('member')->getAllNextL($dephp_13['openid']);

                        $count = 0;

                        foreach ($level_arr as $key => $value) {
                              $count+=count($value);
                        }
         
                        if(!empty($update_con['6'])){
                            $i++;
                            if($count < $update_con['6']){
                                $dephp_67 = false;
                                 
                            }else{
                               $ok[6] = 1;
                            }
                        }

                    }
            
                    //下级总人数满
                    if(in_array('7', $dephp_66)){

                        $level_arr = m('member')->getNextL($dephp_13['openid']);

                        $count = count($level_arr);
         
                        if(!empty($update_con['7'])){
                            $i++;
                            if($count < $update_con['7']){
                                $dephp_67 = false;
                                 
                            }else{
                                $ok[7] = 1;
                            }
                        }

                    }

         
                    //团队总人数满
                    if(in_array('8', $dephp_66)){

                    $level_arr = m('member')->getAllNextL($dephp_13['openid'],2);


                     
                        $count = 0;

                        foreach ($level_arr as $key => $value) {
                              $count+=count($value);
                        }
              
         
                        if(!empty($update_con['8'])){
                            $i++;
                            if($count < $update_con['8']){
                                $dephp_67 = false;
                                 
                            }else{
                                $ok[8] = 1;
                            }
                        }

                    }

         
         

                    //一级团队人数满
                    if(in_array('9', $dephp_66)){

                        $level_arr = m('member')->getNextL($dephp_13['openid'],2);

                        $count = count($level_arr);
         
                        if(!empty($update_con['9'])){
                            $i++;
                            if($count < $update_con['9']){
                                $dephp_67 = false;
                                 
                            }else{
                               $ok[9] = 1;
                            }
                        }

                    }
         
         
                    //已提现佣金总金额满
                    if(in_array('10', $dephp_66)){

                       // $level_arr = m('member')->getNextL($_W['openid'],2);

                        $user = m('member')->getInfo($dephp_13['openid']);

                        $user_id = $user['id'];


                        $sql = 'select sum(commission) from '.tablename('sz_yi_commission_apply')." where mid = '{$user_id}' and status = 3 limit 1 ";

                        $commission = pdo_fetchcolumn($sql);
         
                        if(!empty($update_con['10'])){
                            $i++;
                            if($commission < $update_con['10']){
                                $dephp_67 = false;
                                 
                            }else{
                               $ok[10] = 1;
                            }
                        }

                    }
          
/*
                    if(in_array('11', $dephp_66)){
                        if(!empty($update_con['11'])){
                            $i++;
                            if($dephp_13['ordermoney'] < $update_con['11']){
                                $dephp_67 = false;
                                 
                            }else{
                            $ok[11] = 1;
                            }
                        }
                    }
*/
                    if(in_array('11', $dephp_66)){
                        if(!empty($update_con['11'])){
                            $i++;
                            
                            $all_under = m('member')->getAllNextL($dephp_13['openid'],2);


                         

                            if(!empty($all_under)){

                                $all_openid = array();
                          
                                foreach ($all_under as  $value) {
                                   foreach ($value as   $v ) {
                                       $all_openid[] = "'{$v['openid']}'";
                                                                        
                                   }                                 
                                }


                                if(!empty($all_openid)){
                                    
                                    $all_openid = implode(',',$all_openid);
                                    $order_price = pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order')." where uniacid = '{$_W['uniacid']}' and openid in ($all_openid) and status >=1 ");

                                 //   if($dephp_13['ordermoney'] < $update_con['11']){
                                    if($order_price < $update_con['11']){
                                        $dephp_67 = false;
                                         
                                    }else{
                                        pdo_update('sz_yi_member', array('bonuslevel' => $dephp_64['id'], 'bonus_status' => '1'), array('id' => $dephp_13['id']) ); 
                                        $this -> sendMessage($dephp_13['openid'], array('nickname' => $dephp_13['nickname'], 'oldlevel' => $dephp_63, 'newlevel' => $dephp_64,), TM_BONUS_UPGRADE); 
                                    }


                                }
                            }
                        }
                    }

                    if(in_array('12', $dephp_66)){
                        if(!empty($update_con['12'])){
                            $i++;

                            $sql = 'select count(1) from '.tablename('sz_yi_member')." where agentid = '{$dephp_13['id']}' and bonuslevel = '{$update_con['12']}' limit 1 ";
                            $count = pdo_fetchcolumn($sql);
                            if($count<$update_con['commission_level']){
                                  $dephp_67 = false;
                            }else{
                                $ok[12] = 1;
                            }

                     
                        }
                    }


                    if($dephp_64['conditions']==1){
                        if(count($ok)>0){

                            pdo_update('sz_yi_member', array('bonuslevel' => $dephp_64['id'], 'bonus_status' => ''), array('id' => $dephp_13['id']) ); 

                            $this -> sendMessage($dephp_13['openid'], array('nickname' => $dephp_13['nickname'], 'oldlevel' => $dephp_63, 'newlevel' => $dephp_64,), TM_BONUS_UPGRADE); 
                            
                            

                        }
                    }elseif($dephp_64['conditions']==2){
                         

                        if(count($ok)==$i){
              
                            pdo_update('sz_yi_member', array('bonuslevel' => $dephp_64['id'], 'bonus_status' => ''), array('id' => $dephp_13['id']) ); 

                            $this -> sendMessage($dephp_13['openid'], array('nickname' => $dephp_13['nickname'], 'oldlevel' => $dephp_63, 'newlevel' => $dephp_64,), TM_BONUS_UPGRADE); 

                        }
                    }
            }

        }

 
        






/*
        public function upgradeLevelByAgent($dephp_62){
            global $_W;
            if (empty($dephp_62)){
                return false;
            }
            $dephp_0 = $this -> getSet();
            // $dephp_13 = p('commission') -> getInfo($dephp_62, array('ordercount3'));
                     
            $dephp_13 = p('commission') -> getInfo( $dephp_62 , array("ordercount3", "ordermoney3", "order13money", "order13") ); 

            if (empty($dephp_13)){
                return;
            }
            if(empty($dephp_13['bonuslevel'])){
                $dephp_63 = false;
                $dephp_64 = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=' . $_W['uniacid'] . ' order by level asc');
            }else{
                $dephp_63 = $this -> getLevel($dephp_13['openid']);
                $dephp_65 = pdo_fetchcolumn('select level from ' . tablename('sz_yi_bonus_level') . ' where  uniacid=:uniacid and id=:bonuslevel order by level asc', array(':uniacid' => $_W['uniacid'], ':bonuslevel' => $dephp_13['bonuslevel']));
                $dephp_64 = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where  uniacid=:uniacid and level>:levelby order by level asc', array(':uniacid' => $_W['uniacid'], ':levelby' => $dephp_65));
            }
            if(empty($dephp_64)){
                return false;
            }
            $dephp_66 = $dephp_0['leveltype'];
            $dephp_67 = true;


           // if($dephp_64['conditions']==1){}


            if(in_array('1', $dephp_66)){
                //$dephp_13["order13money"];
               if($dephp_13["order13money"] < $dephp_64[1]) $dephp_67 = false;


            }


            if(in_array('4', $dephp_66)){
                $dephp_68 = pdo_fetchcolumn('select sum(price) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=3 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $dephp_13['openid']));
                if(!empty($dephp_64['ordermoney'])){
                    if($dephp_68 < $dephp_64['ordermoney']){
                        $dephp_67 = false;
                    }
                }
            }
            if(in_array('8', $dephp_66)){
                if(!empty($dephp_64['downcount'])){
                    if($dephp_13['agentcount'] < $dephp_64['downcount']){
                        $dephp_67 = false;
                    }
                }
            }
            if(in_array('9', $dephp_66)){
                if(!empty($dephp_64['downcountlevel1'])){
                    if($dephp_13['level1'] < $dephp_64['downcountlevel1']){
                        $dephp_67 = false;
                    }
                }
            }
            if(in_array('11', $dephp_66)){
                if(!empty($dephp_64['commissionmoney'])){
                    if($dephp_13['ordermoney'] < $dephp_64['commissionmoney']){
                        $dephp_67 = false;
                    }
                }
            }


            if($dephp_67 == true){
                pdo_update('sz_yi_member', array('bonuslevel' => $dephp_64['id'], 'bonus_status' => '', array('id' => $dephp_13['id']))); 
                $dephp_69 = $this -> upgradeLevelByAgent($dephp_13['id']); 
                if($dephp_69 == false){
                        $this -> sendMessage($dephp_13['openid'], array('nickname' => $dephp_13['nickname'], 'oldlevel' => $dephp_63, 'newlevel' => $dephp_64,), TM_BONUS_UPGRADE); 
                }
                
                return true; 
            }
            return false;

        }



*/




            function sendMessage($dephp_10 = '', $dephp_25 = array(), $dephp_70 = ''){
                global $_W, $_GPC; $dephp_0 = $this -> getSet(); $dephp_71 = $dephp_0['tm']; $dephp_72 = $dephp_71['templateid']; $dephp_13 = m('member') -> getMember($dephp_10); $dephp_73 = unserialize($dephp_13['noticeset']); if (!is_array($dephp_73)){
                    $dephp_73 = array(); }
                if ($dephp_70 == TM_COMMISSION_AGENT_NEW && !empty($dephp_71['commission_agent_new']) && empty($dephp_73['commission_agent_new'])){
                    $dephp_74 = $dephp_71['commission_agent_new']; $dephp_74 = str_replace('[昵称]', $dephp_25['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', $dephp_25['childtime']), $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['commission_agent_newtitle']) ? $dephp_71['commission_agent_newtitle'] : '新增下线通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_BONUS_ORDER_PAY && !empty($dephp_71['bonus_order_pay']) && empty($dephp_73['bonus_order_pay'])){
                    $dephp_74 = $dephp_71['bonus_order_pay']; $dephp_74 = str_replace('[昵称]', $dephp_25['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', $dephp_25['paytime']), $dephp_74); $dephp_74 = str_replace('[订单编号]', $dephp_25['ordersn'], $dephp_74); $dephp_74 = str_replace('[订单金额]', $dephp_25['price'], $dephp_74); $dephp_74 = str_replace('[分红金额]', $dephp_25['commission'], $dephp_74); $dephp_74 = str_replace('[商品详情]', $dephp_25['goods'], $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['bonus_order_paytitle']) ? $dephp_71['bonus_order_paytitle'] : '分红下线付款通知'), 'keyword2' => array('value' => $dephp_74)); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_BONUS_ORDER_FINISH && !empty($dephp_71['bonus_order_finish']) && empty($dephp_73['bonus_order_finish'])){
                    $dephp_74 = $dephp_71['bonus_order_finish']; $dephp_74 = str_replace('[昵称]', $dephp_25['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', $dephp_25['finishtime']), $dephp_74); $dephp_74 = str_replace('[订单编号]', $dephp_25['ordersn'], $dephp_74); $dephp_74 = str_replace('[订单金额]', $dephp_25['price'], $dephp_74); $dephp_74 = str_replace('[分红金额]', $dephp_25['commission'], $dephp_74); $dephp_74 = str_replace('[商品详情]', $dephp_25['goods'], $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['bonus_order_finishtitle']) ? $dephp_71['bonus_order_finishtitle'] : '分红下线确认收货通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_COMMISSION_APPLY && !empty($dephp_71['commission_apply']) && empty($dephp_73['commission_apply'])){
                    $dephp_74 = $dephp_71['commission_apply']; $dephp_74 = str_replace('[昵称]', $dephp_13['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $dephp_74); $dephp_74 = str_replace('[金额]', $dephp_25['commission'], $dephp_74); $dephp_74 = str_replace('[提现方式]', $dephp_25['type'], $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['commission_applytitle']) ? $dephp_71['commission_applytitle'] : '提现申请提交成功', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_COMMISSION_CHECK && !empty($dephp_71['commission_check']) && empty($dephp_73['commission_check'])){
                    $dephp_74 = $dephp_71['commission_check']; $dephp_74 = str_replace('[昵称]', $dephp_13['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $dephp_74); $dephp_74 = str_replace('[金额]', $dephp_25['commission'], $dephp_74); $dephp_74 = str_replace('[提现方式]', $dephp_25['type'], $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['commission_checktitle']) ? $dephp_71['commission_checktitle'] : '提现申请审核处理完成', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_BONUS_PAY && !empty($dephp_71['bonus_pay']) && empty($dephp_73['bonus_pay'])){
                    $dephp_74 = $dephp_71['bonus_pay']; $dephp_74 = str_replace('[昵称]', $dephp_13['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $dephp_74); $dephp_74 = str_replace('[金额]', $dephp_25['commission'], $dephp_74); $dephp_74 = str_replace('[打款方式]', $dephp_25['type'], $dephp_74); $dephp_74 = str_replace('[代理等级]', $dephp_25['levename'], $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['bonus_paytitle']) ? $dephp_71['bonus_paytitle'] : '分红打款通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_BONUS_GLOBAL_PAY && !empty($dephp_71['bonus_global_pay']) && empty($dephp_73['bonus_global_pay'])){
                    $dephp_74 = $dephp_71['bonus_global_pay']; $dephp_74 = str_replace('[昵称]', $dephp_13['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $dephp_74); $dephp_74 = str_replace('[金额]', $dephp_25['commission'], $dephp_74); $dephp_74 = str_replace('[打款方式]', $dephp_25['type'], $dephp_74); $dephp_74 = str_replace('[代理等级]', $dephp_25['levename'], $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['bonus_global_paytitle']) ? $dephp_71['bonus_global_paytitle'] : '分红打款通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_BONUS_UPGRADE && !empty($dephp_71['bonus_upgrade']) && empty($dephp_73['bonus_upgrade'])){
                    $dephp_74 = $dephp_71['bonus_upgrade']; if(!empty($dephp_25['newlevel']['msgcontent'])){
                        $dephp_74 = $dephp_25['newlevel']['msgcontent']; }
                    $dephp_74 = str_replace('[昵称]', $dephp_13['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', time()), $dephp_74); $dephp_74 = str_replace('[旧等级]', $dephp_25['oldlevel']['levelname'], $dephp_74); $dephp_74 = str_replace('[旧分红比例]', $dephp_25['oldlevel']['agent_money'] . '%', $dephp_74); $dephp_74 = str_replace('[新等级]', $dephp_25['newlevel']['levelname'], $dephp_74); $dephp_74 = str_replace('[新分红比例]', $dephp_25['newlevel']['agent_money'] . '%', $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_25['newlevel']['msgtitle']) ? $dephp_25['newlevel']['msgtitle'] : $dephp_71['bonus_upgradetitle'], 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }else if ($dephp_70 == TM_COMMISSION_BECOME && !empty($dephp_71['commission_become']) && empty($dephp_73['commission_become'])){
                    $dephp_74 = $dephp_71['commission_become']; $dephp_74 = str_replace('[昵称]', $dephp_25['nickname'], $dephp_74); $dephp_74 = str_replace('[时间]', date('Y-m-d H:i:s', $dephp_25['agenttime']), $dephp_74); $dephp_75 = array('keyword1' => array('value' => !empty($dephp_71['commission_becometitle']) ? $dephp_71['commission_becometitle'] : '成为分销商通知', 'color' => '#73a68d'), 'keyword2' => array('value' => $dephp_74, 'color' => '#73a68d')); if (!empty($dephp_72)){
                        m('message') -> sendTplNotice($dephp_10, $dephp_72, $dephp_75); }else{
                        m('message') -> sendCustomNotice($dephp_10, $dephp_75); }
                }
            }
            function perms(){
                return array('bonus' => array('text' => $this -> getName(), 'isplugin' => true, 'child' => array('cover' => array('text' => '入口设置'), 'agent' => array('text' => '代理商管理', 'view' => '浏览', 'edit' => '修改-log', 'user' => '查看下线', 'order' => '查看推广订单(还需有订单权限)', 'changeagent' => '设置代理商'), 'level' => array('text' => '代理商等级', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'), 'send' => array('text' => '级差分红', 'view' => '浏览', 'bont' => '发放按钮'), 'sendall' => array('text' => '全球分红', 'view' => '浏览', 'bont' => '发放按钮'), 'detail' => array('text' => '分红明细', 'view' => '浏览', 'afresh' => '重发分红'), 'notice' => array('text' => '通知设置-log'), 'set' => array('text' => '基础设置-log')))); }
            public function autosend(){
                global $_W, $_GPC; $dephp_8 = time(); $dephp_76 = 0; $dephp_24 = 0; $dephp_77 = false; $dephp_0 = $this -> getSet(); $dephp_78 = m('common') -> getSysset('shop'); if(empty($dephp_0['sendmethod'])){
                    return false; }
                $dephp_79 = strtotime(date('Y-m-d 00:00:00')); if(empty($dephp_0['sendmonth'])){
                    $dephp_80 = $dephp_79-1; }else if($dephp_0['sendmonth'] == 1){
                    $dephp_80 = date('Y-m-d', mktime(0, 0, 0, date('m')-1, 1, date('Y'))); }
                $dephp_43 = intval($dephp_0['settledays']) * 3600 * 24; $dephp_3 = 'select distinct cg.mid from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and ({$dephp_80} - o.finishtime > {$dephp_43})  ORDER BY o.finishtime DESC,o.status DESC"; $dephp_81 = pdo_fetchall($dephp_3); $dephp_82 = 0; if(empty($dephp_81)){
                    return false; }
                foreach ($dephp_81 as $dephp_19 => $dephp_83){
                    $dephp_13 = $this -> getInfo($dephp_83['mid'], array('ok')); $dephp_84 = $dephp_13['commission_ok']; if($dephp_84 <= 0){
                        continue; }
                    $dephp_77 = true; $dephp_85 = 1; $dephp_20 = $this -> getlevel($dephp_13['openid']); if(empty($dephp_0['paymethod'])){
                        m('member') -> setCredit($dephp_13['openid'], 'credit2', $dephp_84); }else{
                        $dephp_86 = m('common') -> createNO('bonus_log', 'logno', 'RB'); $dephp_87 = m('finance') -> pay($dephp_13['openid'], 1, $dephp_84 * 100, $dephp_86, '【' . $dephp_78['name'] . '】' . $dephp_20['levelname'] . '分红'); if (is_error($dephp_87)){
                            $dephp_85 = 0; $dephp_76 = 1; }
                    }
                    pdo_insert('sz_yi_bonus_log', array('openid' => $dephp_13['openid'], 'uid' => $dephp_13['uid'], 'money' => $dephp_84, 'uniacid' => $_W['uniacid'], 'paymethod' => $dephp_0['paymethod'], 'sendpay' => $dephp_85, 'status' => 1, 'ctime' => time(), 'send_bonus_sn' => $dephp_8)); if($dephp_85 == 1){
                        $this -> sendMessage($dephp_13['openid'], array('nickname' => $dephp_13['nickname'], 'levelname' => $dephp_20['levelname'], 'commission' => $dephp_84, 'type' => empty($dephp_0['paymethod']) ? '余额' : '微信钱包'), TM_BONUS_PAY); }
                    $dephp_59 = array('status' => 3, 'applytime' => $dephp_8, 'checktime' => $dephp_8, 'paytime' => $dephp_8, 'invalidtime' => $dephp_8); pdo_update('sz_yi_bonus_goods', $dephp_59, array('mid' => $dephp_13['id'], 'uniacid' => $_W['uniacid'])); $dephp_82 += $dephp_13['commission_ok']; }
                if($dephp_77){
                    $dephp_88 = array('uniacid' => $_W['uniacid'], 'money' => $dephp_82, 'status' => 1, 'ctime' => time(), 'paymethod' => $dephp_0['paymethod'], 'sendpay_error' => $dephp_76, 'utime' => $dephp_79, 'send_bonus_sn' => $dephp_8, 'total' => count($dephp_81)); pdo_insert('sz_yi_bonus', $dephp_88); return true; }
            }
            public function autosendall(){
                global $_W, $_GPC; $dephp_8 = time(); $dephp_76 = 0; $dephp_24 = 0; $dephp_82 = 0; $dephp_77 = false; $dephp_0 = $this -> getSet(); $dephp_78 = m('common') -> getSysset('shop'); if(empty($dephp_0['sendmethod'])){
                    return false; }
                $dephp_43 = intval($dephp_0['settledays']) * 3600 * 24; $dephp_79 = strtotime(date('Y-m-d 00:00:00')); if(empty($dephp_0['sendmonth'])){
                    $dephp_89 = $dephp_79 - 86400; $dephp_80 = $dephp_79 - 1; }else if($dephp_0['sendmonth'] == 1){
                    $dephp_89 = mktime(0, 0, 0, date('m') - 1, 1, date('Y')); $dephp_80 = mktime(0, 0, 0, date('m'), 1, date('Y')) - 1; }
                $dephp_3 = 'select sum(o.price) from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_refund') . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W['uniacid']} and  o.finishtime >={$dephp_89} and o.finishtime < {$dephp_80}  ORDER BY o.finishtime DESC,o.status DESC"; $dephp_90 = pdo_fetchcolumn($dephp_3); $dephp_91 = pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . " where uniacid={$_W['uniacid']} and premier=1"); $dephp_92 = array(); $dephp_82 = 0; foreach ($dephp_91 as $dephp_19 => $dephp_83){
                    $dephp_93 = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . " where uniacid={$_W['uniacid']} and bonuslevel=" . $dephp_83['id'] . ' and bonus_status = 1'); if($dephp_93 > 0){
                        $dephp_94 = round($dephp_90 * $dephp_83['pcommission'] / 100, 2); if($dephp_94 > 0){
                            $dephp_95 = round($dephp_94 / $dephp_93, 2); if($dephp_95 > 0){
                                $dephp_92[$dephp_83['id']] = $dephp_95; $dephp_82 += $dephp_95; }
                        }
                    }
                }
                $dephp_96 = pdo_fetchall('select m.* from ' . tablename('sz_yi_member') . ' m left join ' . tablename('sz_yi_bonus_level') . " l on m.bonuslevel=l.id and m.bonus_status=1 where 1 and l.premier=1 and m.uniacid={$_W['uniacid']}"); foreach ($dephp_96 as $dephp_19 => $dephp_83){
                    $dephp_20 = pdo_fetch('select id, levelname from ' . tablename('sz_yi_bonus_level') . ' where id=' . $dephp_97['bonuslevel']); $dephp_84 = $dephp_92[$dephp_20['id']]; if($dephp_84 <= 0){
                        continue; }
                    $dephp_77 = true; $dephp_85 = 1; $dephp_20 = $this -> getlevel($dephp_13['openid']); if(empty($dephp_0['paymethod'])){
                        m('member') -> setCredit($dephp_83['openid'], 'credit2', $dephp_84); }else{
                        $dephp_86 = m('common') -> createNO('bonus_log', 'logno', 'RB'); $dephp_87 = m('finance') -> pay($dephp_83['openid'], 1, $dephp_84 * 100, $dephp_86, '【' . $dephp_78['name'] . '】' . $dephp_83['levelname'] . '分红'); if (is_error($dephp_87)){
                            $dephp_85 = 0; $dephp_76 = 1; }
                    }
                    pdo_insert('sz_yi_bonus_log', array('openid' => $dephp_13['openid'], 'uid' => $dephp_13['uid'], 'money' => $dephp_84, 'uniacid' => $_W['uniacid'], 'paymethod' => $dephp_0['paymethod'], 'sendpay' => $dephp_85, 'isglobal' => 1, 'status' => 1, 'ctime' => time(), 'send_bonus_sn' => $dephp_8)); if($dephp_85 == 1){
                        $this -> sendMessage($dephp_13['openid'], array('nickname' => $dephp_13['nickname'], 'levelname' => $dephp_20['levelname'], 'commission' => $dephp_84, 'type' => empty($dephp_0['paymethod']) ? '余额' : '微信钱包'), TM_BONUS_GLOPAL_PAY); }
                }
                if($dephp_77){
                    $dephp_88 = array('uniacid' => $_W['uniacid'], 'money' => $dephp_82, 'status' => 1, 'ctime' => time(), 'paymethod' => $dephp_0['paymethod'], 'sendpay_error' => $dephp_76, 'isglobal' => 1, 'utime' => $dephp_79, 'send_bonus_sn' => $dephp_8, 'total' => $dephp_98); pdo_insert('sz_yi_bonus', $dephp_88); }
            }
        }
    }
    