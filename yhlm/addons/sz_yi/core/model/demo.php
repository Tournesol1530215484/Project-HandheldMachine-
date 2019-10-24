<?php

/*=============================================================================

#     FileName: goods.php

#         Desc: ��Ʒ��

#       Author: Yunzhong - http://www.yunzshop.com

#        Email: 1084070868@qq.com

#     HomePage: http://www.yunzshop.com

#      Version: 0.0.1

#   LastChange: 2016-02-05 02:32:56

#      History:

=============================================================================*/

if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Sz_DYi_Demo{



	 /**

* 数字转换为中文

* @param  string|integer|float  $num  目标数字

* @param  integer $mode 模式[true:金额（默认）,false:普通数字表示]

* @param  boolean $sim 使用小写（默认）

* @return string

*/
	
	 function num2chr($num,$mode = true,$sim = true){

	    if(!is_numeric($num)) return '含有非数字非小数点字符！';

	    $char    = $sim ? array('零','一','二','三','四','五','六','七','八','九')

	    : array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖');

	    $unit    = $sim ? array('','十','百','千','','万','亿','兆')

	    : array('','拾','佰','仟','','萬','億','兆');

	    // $retval  = $mode ? '元':'点';
	    // $retval  = $mode ? '':'';

	    //小数部分

	    if(strpos($num, '.')){

	        list($num,$dec) = explode('.', $num);

	        $dec = strval(round($dec,2));

	        if($mode){

	            $retval .= "{$char[$dec['0']]}角{$char[$dec['1']]}分";

	        }else{

	            for($i = 0,$c = strlen($dec);$i < $c;$i++) {

	                $retval .= $char[$dec[$i]];

	            }

	        }

	    }

	    //整数部分

	    $str = $mode ? strrev(intval($num)) : strrev($num);

	    for($i = 0,$c = strlen($str);$i < $c;$i++) {

	        $out[$i] = $char[$str[$i]];

	        if($mode){

	            $out[$i] .= $str[$i] != '0'? $unit[$i%4] : '';

	                if($i>1 and $str[$i]+$str[$i-1] == 0){

	                $out[$i] = '';

	            }

	                if($i%4 == 0){

	                $out[$i] .= $unit[4+floor($i/4)];

	            }

	        }

	    }
	    
	    $retval = join('',array_reverse($out)) . $retval;

	    return $retval;

	 }




	 //实例调用=====================================================

	// $num = '0123648867.789';

	// echo $num,'<br>';

	//  //普通数字的汉字表示

	// echo '普通:',number2chinese($num,false),'';

	// echo '<br>';

	//  //金额汉字表示

	// echo '金额(简体):',number2chinese($num,true),'';

	// echo '<br>';

	// echo '金额(繁体):',number2chinese($num,true,false);


	 function getCommissionList($openid,$status,$page){

	 		global $_W;	 		  	 

            $set=p('commission')->getSet();
            
			$member = p('commission')->getInfo($openid, array('ordercount0'));
			$agentLevel = p('commission')->getLevel($openid);
			$level = intval($set['level']);
			$commissioncount = 0;	 		 	 	 	
			$orderallmoney = 0;
			$status          = trim($status);
			$condition       = ' and o.status>=0';
			if ($status != '') {
			    $condition = ' and o.status=' . intval($status);
			}
			$orders     = array();
			$level1     = $member['level1'];
			$level2     = $member['level2'];
			$level3     = $member['level3'];
			$ordercount = $member['ordercount0'];


			//分销订单商品详情：分销中心分销订单是否显示商品详情
			$set['openorderdetail'] = m('commission')->getAuthority('show_goods', $set['openorderdetail'] );
			//分销订单购买者详情: 分销中心分销订单是否显示购买者
			$set['openorderbuyer']  = m('commission')->getAuthority('show_customer', $set['openorderbuyer'] );

			if ($level >= 1) {
			    
			    $level1_memberids = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and agentid=:agentid', array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']), 'id');
			    $level1_orders = pdo_fetchall('select commission1,o.id,o.createtime,o.price,og.commissions from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . " where o.uniacid=:uniacid and o.agentid=:agentid {$condition} and og.status1>=0 and og.nocommission=0", array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']));

			    foreach ($level1_orders as $o) {
			        if (empty($o['id'])) {
			            continue;
			        }
			        $commissions = iunserializer($o['commissions']);
			        $commission = iunserializer($o['commission1']);
			        
			        if (empty($commissions)) {
			            $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			        } else {
			            $commission_ok = isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
			        }
			        $hasorder = false;
			        foreach ($orders as &$or) {
			            if ($or['id'] == $o['id'] && $or['level'] == 1) {
			                $or['commission'] += $commission_ok;
			                $hasorder = true;
			                break;
			            }
			        }
			        unset($or);
			        if (!$hasorder) {
			            $orders[] = array('id' => $o['id'], 'commission' => $commission_ok, 'createtime' => $o['createtime'], 'level' => 1);
			        }
			        $commissioncount += $commission_ok;
			        $orderallmoney += $o['price'];
			    }
			    
			}

			if ($level >= 2) {
			    
			    if ($level1 > 0) {
			        $level2_orders = pdo_fetchall('select commission2 ,o.id,o.createtime,o.price,og.commissions   from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . " where o.uniacid=:uniacid and o.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")  {$condition}  and og.status2>=0 and og.nocommission=0 ", array(':uniacid' => $_W['uniacid']));
			        foreach ($level2_orders as $o) {
			            if (empty($o['id'])) {
			                continue;
			            }
			            $commissions = iunserializer($o['commissions']);
			            $commission = iunserializer($o['commission2']);
			            if (empty($commissions)) {
			                $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			            } else {
			                $commission_ok = isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
			            }
			            $hasorder = false;
			            foreach ($orders as &$or) {
			                if ($or['id'] == $o['id'] && $or['level'] == 2) {
			                    $or['commission'] += $commission_ok;
			                    $hasorder = true;
			                    break;
			                }
			            }
			            unset($or);
			            if (!$hasorder) {
			                $orders[] = array('id' => $o['id'], 'commission' => $commission_ok, 'createtime' => $o['createtime'], 'level' => 2);
			            }
			            $commissioncount += $commission_ok;
			            $orderallmoney += $o['price'];
			        }
			    }
			    
			}

			if ($level >= 3) {
			    
			    if ($level2 > 0) {
			        $level3_orders = pdo_fetchall('select commission3 ,o.id,o.createtime,o.price,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where o.uniacid=:uniacid and o.agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ")  {$condition} and og.status3>=0 and og.nocommission=0", array(':uniacid' => $_W['uniacid']));
			        
			        foreach ($level3_orders as $o) {
			            if (empty($o['id'])) {
			                continue;
			            }
			            $commissions = iunserializer($o['commissions']);
			            $commission = iunserializer($o['commission3']);
			            if (empty($commissions)) {
			                $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			            } else {
			                $commission_ok = isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
			            }
			            $hasorder = false;
			            foreach ($orders as &$or) {
			                if ($or['id'] == $o['id'] && $or['level'] == 3) {
			                    $or['commission'] += $commission_ok;
			                    $hasorder = true;
			                    break;
			                }
			            }
			            unset($or);
			            if (!$hasorder) {
			                $orders[] = array('id' => $o['id'], 'commission' => $commission_ok, 'createtime' => $o['createtime'], 'level' => 3);
			            }
			            $commissioncount += $commission_ok;
			            $orderallmoney += $o['price'];
			        }
			    }   
			}



			for ($i=4; $i<=15 ; $i++) { 

			        if ($level >= $i) {         
			            if ($member['level'.($i-1)] > 0) {
			                $level_orders = pdo_fetchall('select commission'.$i.', o.id,o.createtime,o.price,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where o.uniacid=:uniacid and o.agentid in( ' . implode(',', array_keys($member['level'.($i-1).'_agentids'])) . ")  {$condition} and og.status{$i}>=0 and og.nocommission=0", array(':uniacid' => $_W['uniacid']));
			                
			                foreach ($level_orders as $o) {
			                    if (empty($o['id'])) {
			                        continue;
			                    }
			                    $commissions = iunserializer($o['commissions']);
			                    $commission = iunserializer($o['commission'.$i]);
			                    if (empty($commissions)) {
			                        $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			                    } else {
			                        $commission_ok = isset($commissions['level'.$i]) ? floatval($commissions['level'.$i]) : 0;
			                    }
			                    $hasorder = false;
			                    foreach ($orders as &$or) {
			                        if ($or['id'] == $o['id'] && $or['level'] == $i) {
			                            $or['commission'] += $commission_ok;
			                            $hasorder = true;
			                            break;
			                        }
			                    }
			                    unset($or);
			                    if (!$hasorder) {
			                        $orders[] = array('id' => $o['id'], 'commission' => $commission_ok, 'createtime' => $o['createtime'], 'level' => $i);
			                    }
			                    $commissioncount += $commission_ok;
			                    $orderallmoney += $o['price'];
			                }
			            }   
			        }

			}


			if ($_W['isajax']) {
			        
			    $temp=m('member')->getMember($openid);
			    if ($temp['id'] == 4705 ) {
			        // show_json($orders);
			    }           
			    $pindex = max(1, intval($page));
			    $psize = 20;
			    $pageorders = array();
			    $orders1 = array_slice($orders, ($pindex - 1) * $psize, $psize);
			    $orderids = array();
			    foreach ($orders1 as $o) {
			        $orderids[$o['id']] = $o;
			    }
			    $list = array();
			    if (!empty($orderids)) {
			        $list = pdo_fetchall("select id,ordersn,openid,createtime,status from " . tablename('sz_yi_order') . "  where uniacid ={$_W['uniacid']} and id in ( " . implode(',', array_keys($orderids)) . ") order by id desc");
			        foreach ($list as &$row) {
			            $row['commission'] = number_format($orderids[$row['id']]['commission'], 2);
			            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
			            if ($row['status'] == 0) {
			                $row['status'] = '待付款';
			            } else if ($row['status'] == 1) {
			                $row['status'] = '已付款';
			            } else if ($row['status'] == 2) {
			                $row['status'] = '待收货';
			            } else if ($row['status'] == 3) {
			                $row['status'] = '已完成';
			            }
			            if ($orderids[$row['id']]['level'] == 1) {
			                $row['level'] = '一';
			            } else if ($orderids[$row['id']]['level'] == 2) {
			                $row['level'] = '二';
			            } else if ($orderids[$row['id']]['level'] == 3) {
			                $row['level'] = '三';
			            }
			            
			            if (!empty($set['openorderdetail'])) {
			                $goods = pdo_fetchall("SELECT og.id,og.goodsid,g.thumb,og.price,og.total,g.title,og.optionname," . "og.commission1,og.commission2,og.commission3,og.commissions," . "og.status1,og.status2,og.status3," . "og.content1,og.content2,og.content3 from " . tablename('sz_yi_order_goods') . " og" . " left join " . tablename('sz_yi_goods') . " g on g.id=og.goodsid  " . " where og.orderid=:orderid and og.nocommission=0 and og.uniacid = :uniacid order by og.createtime  desc ", array(':uniacid' => $_W['uniacid'], ':orderid' => $row['id']));
			                $goods = set_medias($goods, 'thumb');
			                foreach ($goods as &$g) {
			                    $commissions = iunserializer($g['commissions']);
			                    if ($orderids[$row['id']]['level'] == 1) {
			                        $commission = iunserializer($g['commission1']);
			                        if (empty($commissions)) {
			                            $g['commission'] = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			                        } else {
			                            $g['commission'] = isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
			                        }
			                    } else if ($orderids[$row['id']]['level'] == 2) {
			                        $commission = iunserializer($g['commission2']);
			                        if (empty($commissions)) {
			                            $g['commission'] = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			                        } else {
			                            $g['commission'] = isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
			                        }
			                    } else if ($orderids[$row['id']]['level'] == 3) {
			                        $commission = iunserializer($g['commission3']);
			                        if (empty($commissions)) {
			                            $g['commission'] = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
			                        } else {
			                            $g['commission'] = isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
			                        }
			                    }
			                    $g['commission'] = number_format($g['commission'], 2);
			                }
			                unset($g);
			                $row['order_goods'] = set_medias($goods, 'thumb');
			            }
			            if (!empty($set['openorderbuyer'])) {
			                $row['buyer'] = m('member')->getMember($row['openid']);
			            }
			        }
			        unset($row);
			    }
			    
			    show_json(1, array('list' => $list, 'pagesize' => $psize));
			}
        }


}

