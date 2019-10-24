<?php
/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') { 
    ca('jszd.temp.view');
    $p = p("commission");
	
	$pindex    = max(1, intval($_GPC['page'])); 
    $psize     = 30;
    $params    = array();  
	 
	  $condition = "";
	
	   
	 //筛选类型 
	 if (!empty($_GPC['first_input'])) {
        $first_input = $_GPC['first_input'];
		$input_value = $_GPC['input_value'];
		 
		if($first_input ==  "ordersn"){//订单号搜索
			 $condition .= ' and ' .  $first_input . " = " .  " '$input_value '";

		}elseif($first_input == "agentid"){
			if(!empty($input_value)){  
		  
		 
		 	  $condition .= ' and ' .  $first_input . " = " .  " '$input_value '";

		 	  }
		}elseif($first_input == "realname"){//客户名称搜索
				if(!empty($input_value)){
				$member = pdo_fetch("select openid from " . tablename('sz_yi_member') .  " where uniacid = " . $_W['uniacid'] . "  and  nickname = " . "'$input_value'" . " order by id asc ");
				$aa = $member['openid'];
				$condition .= " and openid = " . "'$aa'";
				
				}
		}
		
    } 

	if($_GPC['day1']){ 
			$day1 = $_GPC['day1'];
			$day2 = date('Y-m-d',$day1);
			$today = strtotime($day2); 
			$tom = strtotime(date("Y-m-d",strtotime("+1 day")));   
			$params[':today'] = $today;
			$params[':tom'] = $tom;
			$condition .= " AND createtime >= :today AND createtime <= :tom ";	
	}elseif($_GPC['day2']){
		   $two = strtotime(date("Y-m-d",strtotime("-2 day")));
		   $tom = strtotime(date("Y-m-d",strtotime("+1 day")));   
		   $params[':two'] = $two; 
		   $params[':tom'] = $tom;
		   $condition .= " AND createtime >= :two AND createtime <= :tom ";
	}elseif($_GPC['day3']){ 
	
			$this_Monday = strtotime(date("Y-m-d",strtotime("-1 week Monday")));
			$tom = strtotime(date("Y-m-d",strtotime("+1 day")));  
			 $today = strtotime($_GPC['day3']);
			 $params[':this_Monday'] = $this_Monday;
		   $params[':tom'] = $tom;
		   $condition .= " AND createtime >= :this_Monday AND createtime <= :tom ";
	}elseif($_GPC['day4']){
		 $today = strtotime($_GPC['day3']);
			$This_month = strtotime(date("Y-m-d",mktime(0, 0 , 0,date("m"),1,date("Y"))));
			$tom = strtotime(date("Y-m-d",strtotime("+1 day")));  
			$params[':This_month'] = $This_month;
		    $params[':tom'] = $tom;
		    $condition .= " AND createtime >= :This_month AND createtime <= :tom ";;
	}elseif($_GPC['day5']){
	
			$Three_months = strtotime(date("Y-m-d",strtotime("-2 month"))); 
			$tom = strtotime(date("Y-m-d",strtotime("+1 day")));  
			$params[':Three_months'] = $Three_months;
		    $params[':tom'] = $tom;
		    $condition .= " AND createtime >= :Three_months AND createtime <= :tom ";; 
	}elseif (!empty($_GPC['time'])) {
        $starttime = strtotime($_GPC['time']['start']);
        $endtime   = strtotime($_GPC['time']['end']);
         if ($_GPC['searchtime'] == '1') {
            $condition .= " AND createtime >= :starttime AND createtime <= :endtime ";
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
			
		  } 
    }
	

 	  $member =  m("member")->getMember($oe["openid"]);
	  $sql = "select id,ordersn,openid,createtime,finishtime,price,supplier_uid,agentid from" . tablename('sz_yi_order') .  " where uniacid = " . $_W['uniacid'] . " {$condition}  order by id asc ";

	  $sql .= " limit " . ($pindex - 1) * $psize . ',' . $psize;
	  $irme  = pdo_fetchall($sql, $params);

    $agents = array();
    if ($p) {
        $list = array();
           foreach ($irme as $k=> $oe){
		/*     print_r($oe['id']."-");    */
                   $agents =  $p->getAgents($oe['id']);
				   /* print_r('<pre>');
					print_r($agents); */
                   $member =  m("member")->getMember($oe["openid"]);
                   $goods = pdo_fetchall("select commissions from ".tablename('sz_yi_order_goods'). " where orderid='".$oe['id']."' and  uniacid='".$_W['uniacid']."'");
                   $level1=0;$level2=0;$level3=0;
				     $level1=4;$level2=5;$level3=6;
					   $level1=7;$level2=8;$level3=9;
					     $level1=10;$level2=11;$level3=12;
						   $level1=13;$level2=14;$level3=15;
				   /* print_r('<pre>'); print_r($goods); */
				    $coms1 = iunserializer($goods[0]['commissions']);
					
                   foreach($goods as $key=>$val){
                       $coms = iunserializer($val['commissions']);
					   
					   $c .=$coms;
                       $level1 = $coms['level1'];
                       $level2 = $coms['level2'];
                       $level3 = $coms['level3'];
                       $level4 = $coms['level4'];
                       $level5 = $coms['level5'];
                       $level6 = $coms['level6'];
                       $level7 = $coms['level7'];
                       $level8 = $coms['level8'];
                       $level9 = $coms['level9'];
					   $level10 = $coms['level10'];
                       $level11 = $coms['level11'];
                       $level12 = $coms['level12'];
					   $level13 = $coms['level13'];
                       $level14 = $coms['level14'];
                       $level15 = $coms['level15'];					   
                   }
				    
				
 						   $c = array_sum($coms);
						   $cum = $c+$cum;
                   $list[$k]['orderid']=$oe['id'];
                   $list[$k]['ordersn']=$oe['ordersn'];
                   $list[$k]['nickname'] = $member['nickname'];
                   $list[$k]['createtime']=$oe['createtime'];
                   $list[$k]['finishtime']=$oe['finishtime'];
                   $list[$k]['price']=$oe['price'];
                   $list[$k]['hile']=$oe['supplier_uid'];
				 
					
                   if(!empty($level1)){
                       $list[$k]['level1']['realname'] = $agents[0]['realname'];
					   $list[$k]['level1']['agentlevel'] =$agents[0]['agentlevel'];
					   $commission = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level1']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."' ");
					   $list[$k]['level1']['agentlevel'] = $commission;
                       $list[$k]['level1']['money']=$level1;
                   }
                   if(!empty($level2)){
                       $list[$k]['level2']['realname'] =  empty($agents[1]['realname']) ? $agents[1]['nickname'] : $agents[1]['realname'];
					   $list[$k]['level2']['agentlevel'] =  empty($agents[1]['agentlevel']) ? $agents[1]['agentlevel'] : $agents[1]['agentlevel'];
					     $commission2 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level2']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level2']['agentlevel'] = $commission2;
                       $list[$k]['level2']['money']=$level2;
					   /* print_r($agents[1]); */
                   }
                   if(!empty($level3)){
                       $list[$k]['level3']['realname'] =  empty($agents[2]['realname']) ? $agents[2]['nickname'] : $agents[2]['realname'] ;
					   $list[$k]['level3']['agentlevel'] =  empty($agents[2]['agentlevel']) ? $agents[2]['agentlevel'] : $agents[2]['agentlevel'] ;
					   $commission3 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level3']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level3']['agentlevel'] = $commission3;
                       $list[$k]['level3']['money']=$level3;
                   }
				    if(!empty($level4)){
                       $list[$k]['level4']['realname'] =  empty($agents[3]['realname']) ? $agents[3]['nickname'] : $agents[32]['realname'] ;
					   $list[$k]['level4']['agentlevel'] =  empty($agents[3]['agentlevel']) ? $agents[3]['agentlevel'] : $agents[3]['agentlevel'] ;
					   $commission4 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level4']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level4']['agentlevel'] = $commission4;
                       $list[$k]['level4']['money']=$level4;
                   }
				    if(!empty($level5)){
                       $list[$k]['level5']['realname'] =  empty($agents[4]['realname']) ? $agents[4]['nickname'] : $agents[4]['realname'] ;
					   $list[$k]['level5']['agentlevel'] =  empty($agents[4]['agentlevel']) ? $agents[4]['agentlevel'] : $agents[4]['agentlevel'] ;
					   $commission4 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level5']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level5']['agentlevel'] = $commission4;
                       $list[$k]['level5']['money']=$level5;
                   }
				    if(!empty($level6)){
                       $list[$k]['level6']['realname'] =  empty($agents[5]['realname']) ? $agents[5]['nickname'] : $agents[5]['realname'] ;
					   $list[$k]['level6']['agentlevel'] =  empty($agents[5]['agentlevel']) ? $agents[5]['agentlevel'] : $agents[5]['agentlevel'] ;
					   $commission5 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level6']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level6']['agentlevel'] = $commission5;
                       $list[$k]['level6']['money']=$level6;
                   }
				   if(!empty($level7)){
                       $list[$k]['level7']['realname'] =  empty($agents[6]['realname']) ? $agents[6]['nickname'] : $agents[6]['realname'] ;
					   $list[$k]['level7']['agentlevel'] =  empty($agents[6]['agentlevel']) ? $agents[6]['agentlevel'] : $agents[6]['agentlevel'] ;
					   $commission6 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level7']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level7']['agentlevel'] = $commission6;
                       $list[$k]['level7']['money']=$level7;
                   }
				   if(!empty($level8)){
                       $list[$k]['level8']['realname'] =  empty($agents[7]['realname']) ? $agents[7]['nickname'] : $agents[7]['realname'] ;
					   $list[$k]['level8']['agentlevel'] =  empty($agents[7]['agentlevel']) ? $agents[7]['agentlevel'] : $agents[7]['agentlevel'] ;
					   $commission7 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level8']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level8']['agentlevel'] = $commission7;
                       $list[$k]['level8']['money']=$level8;
                   }
				   if(!empty($level9)){
                       $list[$k]['level9']['realname'] =  empty($agents[8]['realname']) ? $agents[8]['nickname'] : $agents[8]['realname'] ;
					   $list[$k]['level9']['agentlevel'] =  empty($agents[8]['agentlevel']) ? $agents[8]['agentlevel'] : $agents[8]['agentlevel'] ;
					   $commission8 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level9']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level9']['agentlevel'] = $commission8;
                       $list[$k]['level9']['money']=$level9;
                   }
				   if(!empty($level10)){
                       $list[$k]['level10']['realname'] =  empty($agents[9]['realname']) ? $agents[9]['nickname'] : $agents[9]['realname'] ;
					   $list[$k]['level10']['agentlevel'] =  empty($agents[9]['agentlevel']) ? $agents[9]['agentlevel'] : $agents[9]['agentlevel'] ;
					   $commission9 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level10']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level10']['agentlevel'] = $commission9;
                       $list[$k]['level10']['money']=$level10;
                   }if(!empty($level11)){
                       $list[$k]['level11']['realname'] =  empty($agents[10]['realname']) ? $agents[10]['nickname'] : $agents[10]['realname'] ;
					   $list[$k]['level11']['agentlevel'] =  empty($agents[10]['agentlevel']) ? $agents[10]['agentlevel'] : $agents[10]['agentlevel'] ;
					   $commission10 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level11']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level11']['agentlevel'] = $commission10;
                       $list[$k]['level11']['money']=$level11;
                   }if(!empty($level12)){
                       $list[$k]['level12']['realname'] =  empty($agents[11]['realname']) ? $agents[11]['nickname'] : $agents[11]['realname'] ;
					   $list[$k]['level12']['agentlevel'] =  empty($agents[11]['agentlevel']) ? $agents[11]['agentlevel'] : $agents[11]['agentlevel'] ;
					   $commission11 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level12']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level12']['agentlevel'] = $commission11;
                       $list[$k]['level12']['money']=$level12;
                   }if(!empty($level13)){
                       $list[$k]['level13']['realname'] =  empty($agents[12]['realname']) ? $agents[12]['nickname'] : $agents[12]['realname'] ;
					   $list[$k]['level13']['agentlevel'] =  empty($agents[12]['agentlevel']) ? $agents[12]['agentlevel'] : $agents[12]['agentlevel'] ;
					   $commission12 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level13']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level13']['agentlevel'] = $commission12;
                       $list[$k]['level13']['money']=$level13;
                   }
				   if(!empty($level14)){
                       $list[$k]['level14']['realname'] =  empty($agents[13]['realname']) ? $agents[13]['nickname'] : $agents[13]['realname'] ;
					   $list[$k]['level14']['agentlevel'] =  empty($agents[13]['agentlevel']) ? $agents[13]['agentlevel'] : $agents[13]['agentlevel'] ;
					   $commission13 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level14']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level14']['agentlevel'] = $commission13;
                       $list[$k]['level14']['money']=$level14;
                   }
				   if(!empty($level15)){
                       $list[$k]['level15']['realname'] =  empty($agents[14]['realname']) ? $agents[14]['nickname'] : $agents[14]['realname'] ;
					   $list[$k]['level15']['agentlevel'] =  empty($agents[14]['agentlevel']) ? $agents[14]['agentlevel'] : $agents[14]['agentlevel'] ;
					   $commission14 = pdo_fetch("select levelname from ".tablename('sz_yi_commission_level'). " where  id = ".  $list[$k]['level15']['agentlevel'] ."  and   uniacid='" . $_W['uniacid'] ."'");
					   $list[$k]['level15']['agentlevel'] = $commission14;
                       $list[$k]['level15']['money']=$level15;
                   }
				   

            }


    }
	  
		if(($first_input == "username")){
			foreach($list as $k=>$val){

				if( $val['level1']['realname'] == $_GPC['input_value']){
					$var1 = array(
					'orderid'=>$val['orderid'],
					'ordersn'=>$val['ordersn'],
					'nickname'=>$val['nickname'],
					'createtime'=>$val['createtime'],
					'finishtime'=>$val['finishtime'],
					'price'=>$val['price'],
					'hile'=>$val['hile'],
					);
						$va =  array('level1' => $val['level1']);
						 $list2 =array_merge($var1,$va); 
	
					}elseif($val['level2']['realname'] == $_GPC['input_value']){
					
					$var1 = array(
					'orderid'=>$val['orderid'],
					'ordersn'=>$val['ordersn'],
					'nickname'=>$val['nickname'],
					'createtime'=>$val['createtime'],
					'finishtime'=>$val['finishtime'],
					'price'=>$val['price'],
					'hile'=>$val['hile'],
					);
					
						$va =  array('level2' => $val['level2']);
						 $list2 =array_merge($var1,$va);
					}
	
				}
					$b=count($list2);
					for($n=0; $n<$b;$n++){
						$list3[$n] = $list2;
		
					}
		 	
	}
	else{
	
		$list3 = $list;
	}
	/* print_r('<pre>');
			print_r($list3);  */
	
	 $total = pdo_fetchcolumn("select count(id) from " . tablename('sz_yi_order') . " where uniacid = " . $_W['uniacid'] . "  {$condition} ", $params);
	 $pager = pagination($total, $pindex, $psize);
	
    if ($_GPC['export'] == '1') {
        ca('jszd.temp.export');
        plog('jszd.temp.export', '导出结算账单');
         foreach ($list as &$row) {
				$row['ordersn']=$row['ordersn'];
				$row['createtime'] = date('Y-m-d H:i', $row['createtime']);
				$row['finishtime']   = empty($row['agenttime']) ? '' : date('Y-m-d H:i', $row['finishtime']);
				$row['hile']  = empty($row['hile']) ? '商城订单' : '供应商订单';
				$row['realname'] =$row['level1']['realname'].'->'.$row['level2']['realname'].'->'.$row['level3']['realname'];
				$row['money']=$row['level1']['money'].'->'.$row['level2']['money'].'->'.$row['level3']['money'];
   		 }
    unset($row);
        m('excel')->export($list, array(
            "title" => "结算账单-" . date('Y-m-d-H-i', time()),
            "columns" => array(
                array(
                    'title' => '订单号',
                    'field' => 'ordersn',
                    'width' => 25
                ),
                array(
                    'title' => '客户名称',
                    'field' => 'nickname',
                    'width' => 12
                ),
                array(
                    'title' => '分销员',
                    'field' => 'realname',
                    'width' => 12
                ),
                array(
                    'title' => '订单类型',
                    'field' => 'hile',
                    'width' => 12
                ),
                array(
                    'title' => '金额(元)',
                    'field' => 'price',
                    'width' => 12
                ),
                array(
                    'title' => '下单时间',
                    'field' => 'createtime',
                    'width' => 28
                ),
                array(
                    'title' => '结算时间',
                    'field' => 'createtime',
                    'width' => 28
                ),
                array(
                    'title' => '结算佣金',
                    'field' => 'money',
                    'width' => 12
                ),

            )

        ));

    }
}elseif ($operation == 'jintian') {
	
}
load()->func('tpl');
include $this->template('temp');
