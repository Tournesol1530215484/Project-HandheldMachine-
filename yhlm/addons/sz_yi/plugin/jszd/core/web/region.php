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

			 $condition1 .= ' and ' .  $first_input . " = " .  " '$input_value '";

		}elseif($first_input == "nickname"){  

			if(!empty($input_value)){

			

		 	  $condition2 .= ' and ' .  $first_input . " = " .  " '$input_value '";

		 	  }

		}elseif($first_input == "id"){ 

			if(!empty($input_value)){

		 	  $condition2 .= ' and ' .  $first_input . " = " .  " '$input_value '";

		 	  }

		}elseif($first_input == "realname"){//客户名称搜索

				if(!empty($input_value)){

				$member = pdo_fetch("select openid from " . tablename('sz_yi_member') .  " where uniacid = " . $_W['uniacid'] . "  and  nickname = " . "'$input_value'" . " order by id asc ");

				$aa = $member['openid'];

				$condition1 .= " and openid = " . "'$aa'";

				

				}

		}

		 

    } 

	

	//时间快键

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

	



 	  $memb =  m("member")->getMember($oe["openid"]);

	  // $sql = "select orderid,mid,levelid,mid,money from" . tablename('sz_yi_bonus_goods') .  " where uniacid = " . $_W['uniacid'] . "  and bonus_area >'0'   {$condition}    order by id asc ";

	  $sql = "select orderid,mid,levelid,mid,money,levelid from" . tablename('sz_yi_bonus_goods') .  " where uniacid = " . $_W['uniacid'] . "   and bonus_area >0  {$condition}    order by id asc ";

	  

	

	  $sql .= " limit " . ($pindex - 1) * $psize . ',' . $psize;

	  $irme  = pdo_fetchall($sql, $params);

	  

	 

	

					



    $agents = array();

    if ($irme) {



        $list = array();

           foreach ($irme as $k=> $oe){

				$order =  pdo_fetch("select ordersn,price,createtime,paytime,openid from " . tablename('sz_yi_order'). " where  id = '" . $oe['orderid'] . "'  and   uniacid=  " . $_W['uniacid'] ."  {$condition1}  order by id asc");		

				$openid = $order['openid'];

				 //买家资材信息

				 //买家资材信息

				$member =  pdo_fetch("select id,realname,bonuslevel,agentid,bonus_province,bonus_city,bonus_district,nickname from ".tablename('sz_yi_member'). " where  openid = '".$openid."'   and   uniacid=" . $_W['uniacid'] ."  order by id asc");

				$member['realname'] = $member['realname']?$member['realname']:$member['nickname'];

				$order_member = array_merge($order,$member);

				  //分红的会员等级

				if(!empty($oe['levelid'])){

					 $levelname  = pdo_fetch("select levelname  from " . tablename('sz_yi_bonus_level'). " where  id = '" . $oe['levelid'] . "'  and   uniacid=  " . $_W['uniacid'] ." order by id asc");	
				}else{
						$levelname['levelname'] = "普通等级";
				}


				    

				  //$levelname  = pdo_fetch("select levelname as levelname_1  from " . tablename('sz_yi_bonus_level'). " where  id = '" . $oe['levelid'] . "'  and   uniacid=  " . $_W['uniacid'] ." order by id asc");	

		    	$le_om = array_merge($order_member,$levelname);

				  //分红的会员等级资材信息

				$realname_1 =  pdo_fetch("select realname as realname_1,nickname as nickname_1,bonus_area from ".tablename('sz_yi_member'). " where  id = ". $oe['mid'] ."  and   uniacid=" . $_W['uniacid'] ." {$condition2} order by id asc");

				$realname_1['realname_1'] = $realname_1['realname_1']?$realname_1['realname_1']:$realname_1['nickname_1'];

				$re_lo = array_merge($le_om,$realname_1);

				//买家分红等级
				 // var_dump($member);
				// var_dump($re_lo);
				if($member['bonuslevel']==0){
					// $bonuslevel = $bonuslevel['levelname'];
					$bonuslevel['levelname'] = '普通等级';
					$bo_rl = array_merge($re_lo,$bonuslevel);
				} else{
					$bonuslevel  = pdo_fetch("select levelname  from " . tablename('sz_yi_bonus_level'). " where  id = '" . $member['bonuslevel'] . "'  and   uniacid=  " . $_W['uniacid'] ." order by id asc");			 				
					$bo_rl = array_merge($re_lo,$bonuslevel);
				}
				// var_dump($bo_rl);
				$money = array( 'money' => $oe['money']);

				$mo_ln = array_merge($bo_rl,$money); 
				$c = array_sum($money);

				$monc = $mo_ln['money'];

				$cum = $monc+$cum;
				// var_dump($mo_ln);
				// die;
				$list[$k] = $mo_ln;



            }





    }

	

	

	

	 $total = pdo_fetchcolumn("select count(id) from " . tablename('sz_yi_bonus_goods') . " where uniacid = " . $_W['uniacid'] . "  {$condition} ", $params);

	 $pager = pagination($total, $pindex, $psize);

//print_r($list);exit;

    if ($_GPC['export'] == '1') {

        ca('jszd.temp.export');

        plog('jszd.temp.export', '导出结算账单');

         foreach ($list as &$row) {

					$row['ordersn']=$row['ordersn'];

				$row['createtime'] = date('Y-m-d H:i', $row['createtime']);

				$row['finishtime']   = empty($row['agenttime']) ? '' : date('Y-m-d H:i', $row['finishtime']);

				$row['hile']  = empty($row['hile']) ? '商城订单' : '供应商订单';

				$row['realname'] =$row['realname'];

				$row['realname_1'] =$row['realname_1'];

				$row['money']=$row['money'].'元';

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

                    'field' => 'realname_1',

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

include $this->template('region');

