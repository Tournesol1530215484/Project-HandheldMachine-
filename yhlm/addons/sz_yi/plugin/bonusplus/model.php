<?php
/**
 * 分红模型
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
if (!defined("IN_IA")) { exit("Access Denied"); }
define("TM_COMMISSION_AGENT_NEW", "commission_agent_new"); 
define("TM_BONUS_ORDER_PAY", "bonusplus_order_pay"); 
define("TM_BONUS_ORDER_FINISH", "bonusplus_order_finish"); 
define("TM_COMMISSION_APPLY", "commission_apply"); 
define("TM_COMMISSION_CHECK", "commission_check"); 
define("TM_BONUS_PAY", "bonusplus_pay"); 
define("TM_BONUS_GLOBAL_PAY", "bonusplus_global_pay"); 
define("TM_BONUS_UPGRADE", "bonusplus_upgrade"); 
define("TM_COMMISSION_BECOME", "commission_become"); 
if (!class_exists("BonusplusModel")) { 

	class BonusplusModel extends PluginModel { 
	
		private $agents = array(); 
		private $parentAgents = array(); 

		public function getSet() { 
	$_var_0 = parent::getSet(); $_var_0["texts"] = array("agent" => empty($_var_0["texts"]["agent"]) ? "代理商" : $_var_0["texts"]["agent"],"premiername" => empty($_var_0["texts"]["premiername"]) ? "最高级别" : $_var_0["texts"]["premiername"], "shop" => empty($_var_0["texts"]["shop"]) ? "小店" : $_var_0["texts"]["shop"], "myshop" => empty($_var_0["texts"]["myshop"]) ? "我的小店" : $_var_0["texts"]["myshop"], "center" => empty($_var_0["texts"]["center"]) ? "分红中心" : $_var_0["texts"]["center"], "become" => empty($_var_0["texts"]["become"]) ? "成为分销商" : $_var_0["texts"]["become"], "withdraw" => empty($_var_0["texts"]["withdraw"]) ? "提现" : $_var_0["texts"]["withdraw"], "commission" => empty($_var_0["texts"]["commission"]) ? "佣金" : $_var_0["texts"]["commission"], "commission1" => empty($_var_0["texts"]["commission1"]) ? "分红佣金" : $_var_0["texts"]["commission1"], "commission_total" => empty($_var_0["texts"]["commission_total"]) ? "累计分红佣金" : $_var_0["texts"]["commission_total"], "commission_ok" => empty($_var_0["texts"]["commission_ok"]) ? "待分红佣金" : $_var_0["texts"]["commission_ok"], "commission_apply" => empty($_var_0["texts"]["commission_apply"]) ? "已申请佣金" : $_var_0["texts"]["commission_apply"], "commission_check" => empty($_var_0["texts"]["commission_check"]) ? "待打款佣金" : $_var_0["texts"]["commission_check"], "commission_lock" => empty($_var_0["texts"]["commission_lock"]) ? "未结算佣金" : $_var_0["texts"]["commission_lock"], "commission_detail" => empty($_var_0["texts"]["commission_detail"]) ? "分红明细" : $_var_0["texts"]["commission_detail"], "commission_pay" => empty($_var_0["texts"]["commission_pay"]) ? "成功提现佣金" : $_var_0["texts"]["commission_pay"], "order" => empty($_var_0["texts"]["order"]) ? "分销订单" : $_var_0["texts"]["order"], "myteam" => empty($_var_0["texts"]["myteam"]) ? "我的团队" : $_var_0["texts"]["myteam"], "c1" => empty($_var_0["texts"]["c1"]) ? "一级" : $_var_0["texts"]["c1"], "c2" => empty($_var_0["texts"]["c2"]) ? "二级" : $_var_0["texts"]["c2"], "c3" => empty($_var_0["texts"]["c3"]) ? "三级" : $_var_0["texts"]["c3"], "mycustomer" => empty($_var_0["texts"]["mycustomer"]) ? "我的客户" : $_var_0["texts"]["mycustomer"],); return $_var_0; }
	
//获取上级代理商	
public function getParentAgents($_var_1, $_var_2 = 0){ 
	global $_W; 
	//$_var_3 = $this->getLevel(); 
	$_var_4 = "select id, agentid, bonuspluslevel, bonusplus_status from " . tablename("sz_yi_member") . " where id={$_var_1} and isagent = 1 and uniacid=".$_W["uniacid"];
	$_var_5 = pdo_fetch($_var_4); 
	if(empty($_var_5)){ 
		return $this->parentAgents; 
	}else{ 
		if(empty($this->parentAgents[$_var_5["bonuspluslevel"]])){ 
			$this->parentAgents[$_var_5["bonuspluslevel"]] = $_var_5["id"]; 
		} 
		if($_var_5["agentid"] != 0){ 
			return $this->getParentAgents($_var_5["agentid"]); 
		}else{ 
			return $this->parentAgents; 
		} 
	} 
} 

public function calculate( $_var_6 = 0, $_var_7 = true) { 
	global $_W; 
	$_var_0 = $this->getSet(); 
	$_var_3 = $this->getLevels(); 
	$_var_8 = time(); 
	$_var_9 = pdo_fetchcolumn("select openid from " . tablename("sz_yi_order") . " where id=:id limit 1", array(":id" => $_var_6)); $_var_10 = pdo_fetchall('select og.id,og.realprice,og.goodsid,og.total,og.optionname,g.hascommission,g.nocommission,g.bonusplusmoney from ' . tablename("sz_yi_order_goods") . "  og " . " left join " . tablename("sz_yi_goods") . " g on g.id = og.goodsid" . " where og.orderid=:orderid and og.uniacid=:uniacid", array(":orderid" => $_var_6, ":uniacid" => $_W["uniacid"]));
	$_var_11 = m("member")->getInfo($_var_9); $_var_3 = pdo_fetchall("SELECT * FROM " . tablename("sz_yi_bonusplus_level") . " WHERE uniacid = '{$_W["uniacid"]}' ORDER BY level asc"); foreach ($_var_10 as $_var_12) { $_var_13 = $_var_12["bonusplusmoney"] > 0.00 ? $_var_12["bonusplusmoney"] : $_var_12["realprice"]; if(empty($_var_0["selfbuy"])){ if($_var_11["agentid"] == 0){ return; } $_var_14 = $_var_11["agentid"]; }else{ $_var_14 = $_var_11["id"]; } $_var_15 = $this->getParentAgents($_var_14, 1); $_var_16 = 0; foreach ($_var_3 as $_var_17 => $_var_18) { $_var_19 = $_var_18["id"]; if(array_key_exists($_var_19, $_var_15)){ if($_var_18["agent_money"] > 0){ $_var_20 = $_var_18["agent_money"]/100; }else{ continue; } $_var_21 = round($_var_13 * $_var_20, 2); $_var_22 = $_var_21 - $_var_16; $_var_23 = array( "uniacid" => $_W["uniacid"], "ordergoodid" => $_var_12["goodsid"], "orderid" => $_var_6, "total" => $_var_12["total"], "optionname" => $_var_12["optionname"], "mid" => $_var_15[$_var_19], "levelid" => $_var_19, "money" => $_var_22, "createtime" => $_var_8 ); pdo_insert("sz_yi_bonusplus_goods", $_var_23); $_var_16 = $_var_21; } } } } 
	
public function getChildAgents($_var_1){ 
	global $_W; $_var_4 = "select id from " . tablename("sz_yi_member") . " where agentid={$_var_1} and status=1 and isagent = 1 and uniacid=".$_W["uniacid"]; $_var_24 = pdo_fetchall($_var_4); foreach ($_var_24 as $_var_25) { $this->agents[] = $_var_25["id"]; $this->getChildAgents($_var_25["id"]); } return $this->agents; } 
	
public function getLevels($_var_26 = true) {
	global $_W; 
	if ($_var_26) { 
		return pdo_fetchall("select * from " . tablename("sz_yi_bonusplus_level") . " where uniacid=:uniacid order by level asc", array( ":uniacid" => $_W["uniacid"] )); 
	} else { 
		return pdo_fetchall("select * from " . tablename("sz_yi_bonusplus_level") . " where uniacid=:uniacid and (ordermoney>0 or commissionmoney>0) order by level asc", array( ":uniacid" => $_W["uniacid"] )); 
	} 
} 

public function premierInfo($_var_9, $_var_27 = null){ if (empty($_var_27) || !is_array($_var_27)) { $_var_27 = array(); } global $_W; $_var_0 = $this->getSet(); $_var_11 = m("member")->getInfo($_var_9); $_var_28 = 0; $_var_29 = 0; $_var_30 = 0; $_var_31 = 0; $_var_32 = 0; $_var_8 = time(); $_var_33 = intval($_var_0["settledaysdf"]) * 3600 * 24; if (in_array("ok", $_var_27)) { $_var_4 = "select sum(o.price) as money from " . tablename("sz_yi_order") . " o left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and ({$_var_8} - o.createtime > {$_var_33}) ORDER BY o.createtime DESC,o.status DESC"; $_var_29 = pdo_fetchcolumn($_var_4, array(":uniacid" => $_W["uniacid"])); } if (in_array("total", $_var_27)) { $_var_4 = "select sum(o.price) as money from " . tablename("sz_yi_order") . " o left join " . tablename("sz_yi_order_refund") . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=1 and o.uniacid=:uniacid  ORDER BY o.createtime DESC,o.status DESC'; $_var_28 = pdo_fetchcolumn($_var_4, array(":uniacid" => $_W["uniacid"])); } 
	if (in_array("pay", $_var_27)) { 
		$_var_4 = "select sum(money) from " . tablename("sz_yi_bonusplus_log") . " where openid=:openid and isglobal=1 and uniacid=:uniacid"; $_var_30 = pdo_fetchcolumn($_var_4, array(":uniacid" => $_W["uniacid"], "openid" => $_var_11["openid"])); } 
	
	if (in_array("myorder", $_var_34)) { $_var_35 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_11["openid"])); $_var_31 = $_var_35["ordermoney"]; $_var_32 = $_var_35["ordercount"]; } $_var_11["commission_ok"] = round($_var_29, 2); $_var_11["commission_total"] = round($_var_28, 2); $_var_11["commission_pay"] = $_var_30; $_var_11["myordermoney"] = $_var_31; $_var_11["myordercount"] = $_var_32; return $_var_11; } 
	
	
public function getInfo($openid, $filter = null){ 
	if (empty($filter) || !is_array($filter)) { 
		$filter = array(); 
	} 
	global $_W; 
	$bonusset = $this->getSet(); 
	$member = m("member")->getInfo($openid); 
	$commission_total = 0; 
	$commission_ok = 0; 
	$_var_36 = 0; 
	$_var_37 = 0; 
	$_var_38 = 0; 
	$_var_30 = 0; 
	$_var_39 = 0; 
	$_var_31 = 0; 
	$_var_32 = 0; 
	$uid = $member["id"] ? $member["id"] : $openid; 
	$_var_8 = time(); 
	$ledaytime = intval($bonusset["settledaysdf"]) * 3600 * 24; 
	$sql1 = array(); 
	
	if (in_array("totaly", $filter)) { 
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid and cg.status=0 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=0 and o.uniacid={$_W["uniacid"]} and cg.mid = {$uid}"; 
		$_var_39 = pdo_fetchcolumn($sql, array(":uniacid" => $_W["uniacid"]));
	}
	if (in_array("ok", $filter)) { 
		//$sql  = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid and cg.status=0 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and cg.mid = {$uid} and ({$_var_8} - o.createtime > {$ledaytime}) ORDER BY o.createtime DESC,o.status DESC";
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_log")."  lg on o.id=lg.orderid left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=3 and o.uniacid=:uniacid and lg.uid = {$uid} and lg.status=0 ORDER BY o.createtime DESC,o.status DESC";
		$commission_ok = pdo_fetchcolumn($sql, array(":uniacid" => $_W["uniacid"])); 
	} 
	
	if (in_array("total", $filter)) { 		
		//$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=1 and o.uniacid=:uniacid and cg.mid = {$uid} ORDER BY o.createtime DESC,o.status DESC";
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_log")."  lg on o.id=lg.orderid left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=3 and o.uniacid=:uniacid and lg.uid = {$uid} and lg.status>0 ORDER BY o.createtime DESC,o.status DESC";
		$commission_total = pdo_fetchcolumn($sql, array(":uniacid" => $_W["uniacid"]));
	}
	if (in_array("ordercount", $filter)) { 
		$sql2 = pdo_fetchcolumn("select count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_bonusplus_goods") . " cg on cg.orderid=o.id  where o.status>=0 and cg.status>=0 and o.uniacid=".$_W["uniacid"]." and cg.mid =".$uid." limit 1"); 
	} 
	if (in_array("apply", $filter)) { 
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid and cg.status=1 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and cg.mid = {$uid} and ({$_var_8} - o.createtime <= {$ledaytime}) ORDER BY o.createtime DESC,o.status DESC";
		$_var_36 = pdo_fetchcolumn($sql); 
	} 
	if (in_array("check", $filter)) { 
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid and cg.status=2 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and cg.mid = {$uid} and ({$_var_8} - o.createtime <= {$ledaytime}) ORDER BY o.createtime DESC,o.status DESC";
		$_var_37 = pdo_fetchcolumn($sql); 
	} 
	if (in_array("pay", $filter)) { 
		//$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid and cg.status=3 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and cg.mid = {$uid} ORDER BY o.createtime DESC,o.status DESC";
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_log")."  lg on o.id=lg.orderid left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=3 and o.uniacid=:uniacid and lg.uid = {$uid} and lg.status>0 ORDER BY o.createtime DESC,o.status DESC";
		$_var_30 = pdo_fetchcolumn($sql); 
	} 
	if (in_array("lock", $filter)) { 
		$sql = "select sum(money) as money from " . tablename("sz_yi_order") . " o left join  ".tablename("sz_yi_bonusplus_goods")."  cg on o.id=cg.orderid and cg.status=1 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and cg.mid = {$uid} and ({$_var_8} - o.createtime <= {$ledaytime}) ORDER BY o.createtime DESC,o.status DESC";
		$_var_38 = pdo_fetchcolumn($sql); 
	} 
	if (in_array("myorder", $_var_34)) {
		$_var_35 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $member["openid"]));
		$_var_31 = $_var_35["ordermoney"]; 
		$_var_32 = $_var_35["ordercount"]; 
	} 
	if( $member["id"]){	
		$sql1 = $this->getChildAgents($member["id"]); 
	}
	
	$sql3 = count($sql1); 
	$member["commission_ok"] = isset($commission_ok) ? $commission_ok : 0; 
	$member["commission_total"] = isset($commission_total) ? $commission_total : 0; 
	$member["commission_pay"] = isset($_var_30) ? $_var_30 : 0; 
	$member["commission_apply"] = isset($_var_36) ? $_var_36 : 0; 
	$member["commission_check"] = isset($_var_37) ? $_var_37 : 0; 
	$member["commission_lock"] = isset($_var_38) ? $_var_38 : 0; 
	$member["commission_totaly"] = isset($_var_39) ? $_var_39 : 0; 
	$member["ordercount"] = $sql2; 
	$member["agentcount"] = $sql3; 
	$member["agentids"] = $sql1; 
	$member["myordermoney"] = $_var_31; 
	$member["myordercount"] = $_var_32; 
	return $member; 
} 

//检测订单完成			
public function checkOrderConfirm($_var_6 = '0') { 
	global $_W, $_GPC; 
	$_var_0 = $this->getSet(); 
	if(empty($_var_0["start"])){ 
		return; 
	} 
	$this->calculate($_var_6); 
} 

//检测订单支付
public function checkOrderPay($_var_6 = '0') { 
	global $_W, $_GPC; 
	$_var_0 = $this->getSet(); 
	if(empty($_var_0["start"])){ 
		return; 
	} 
	$_var_44 = pdo_fetch("select id,openid,ordersn,goodsprice,agentid,paytime from " . tablename("sz_yi_order") . " where id=:id and status>=1 and uniacid=:uniacid limit 1", array(":id" => $_var_6, ":uniacid" => $_W["uniacid"])); 
	if (empty($_var_44)) { 
		return; 
	} 
	$_var_9 = $_var_44["openid"]; $_var_11 = m("member")->getMember($_var_9); if (empty($_var_11)) { return; } $_var_45 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_44["id"])); $_var_10 = ''; $_var_46 = 0; foreach ($_var_45 as $_var_47) { $_var_10 .= "" . $_var_47["title"] . "( "; if (!empty($_var_47["optiontitle"])) { $_var_10 .= " 规格: " . $_var_47["optiontitle"]; } $_var_10 .= " 单价: " . ($_var_47["realprice"] / $_var_47["total"]) . " 数量: " . $_var_47["total"] . " 总价: " . $_var_47["realprice"] . "); "; $_var_46 += $_var_47["realprice"]; } $_var_48 = pdo_fetchall("select distinct mid from " . tablename("sz_yi_bonusplus_goods") . " where uniacid=:uniacid and orderid=:orderid", array(":orderid" => $_var_44["id"], ":uniacid" => $_W["uniacid"])); foreach ($_var_48 as $_var_17 => $_var_49) { $_var_9 = pdo_fetchcolumn("select openid from " . tablename("sz_yi_member") . " where id=".$_var_49["mid"]." and uniacid=".$_W["uniacid"]); $_var_50 = pdo_fetchcolumn("select sum(money) from " . tablename("sz_yi_bonusplus_goods") . " where mid=".$_var_49["mid"]." and orderid=".$_var_44["id"]." and uniacid=".$_W["uniacid"]); $this->sendMessage($_var_9, array("nickname" => $_var_11["nickname"], "ordersn" => $_var_44["ordersn"], "price" => $_var_46, "goods" => $_var_10, "commission" => $_var_50, "paytime" => $_var_44["paytime"]), TM_BONUS_ORDER_PAY); } } 

public function checkOrderFinish($_var_6 = '') { global $_W, $_GPC; if (empty($_var_6)) { return; } $_var_0 = $this->getSet(); if(empty($_var_0["start"])){ return; } $_var_44 = pdo_fetch("select id,openid,ordersn,goodsprice,agentid,paytime from " . tablename("sz_yi_order") . " where id=:id and status>=1 and uniacid=:uniacid limit 1", array(":id" => $_var_6, ":uniacid" => $_W["uniacid"])); if (empty($_var_44)) { return; } $_var_9 = $_var_44["openid"]; $_var_11 = m("member")->getMember($_var_9); if (empty($_var_11)) { return; } $_var_45 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_44["id"])); $_var_10 = ''; $_var_46 = 0; foreach ($_var_45 as $_var_47) { $_var_10 .= "" . $_var_47["title"] . "( "; if (!empty($_var_47["optiontitle"])) { $_var_10 .= " 规格: " . $_var_47["optiontitle"]; } $_var_10 .= " 单价: " . ($_var_47["realprice"] / $_var_47["total"]) . " 数量: " . $_var_47["total"] . " 总价: " . $_var_47["realprice"] . "); "; $_var_46 += $_var_47["realprice"]; } $_var_48 = pdo_fetchall("select distinct mid from " . tablename("sz_yi_bonusplus_goods") . " where uniacid=:uniacid and orderid=:orderid", array(":orderid" => $_var_6, ":uniacid" => $_W["uniacid"])); foreach ($_var_48 as $_var_17 => $_var_49) { $_var_9 = pdo_fetchcolumn("select openid from " . tablename("sz_yi_member") . " where id=".$_var_49["mid"]." and uniacid=".$_W["uniacid"]); $_var_50 = pdo_fetchcolumn("select sum(money) from " . tablename("sz_yi_bonusplus_goods") . " where mid=".$_var_49["mid"]." and orderid=".$_var_44["id"]." and uniacid=".$_W["uniacid"]); $this->sendMessage($_var_9, array("nickname" => $_var_11["nickname"], "ordersn" => $_var_44["ordersn"], "price" => $_var_46, "goods" => $_var_10, "commission" => $_var_50, "paytime" => $_var_44["paytime"]), TM_BONUS_ORDER_FINISH); } } 

public function getLevel($_var_9) { global $_W; if (empty($_var_9)) { return false; } $_var_11 = m("member")->getMember($_var_9); if (empty($_var_11["bonuspluslevel"])) { return false; } $_var_18 = pdo_fetch("select * from " . tablename("sz_yi_bonusplus_level") . " where uniacid=:uniacid and id=:id limit 1", array(":uniacid" => $_W["uniacid"], ":id" => $_var_11["bonuspluslevel"])); return $_var_18; } 

public function upgradeLevelByAgent($_var_51) { global $_W; if (empty($_var_51)) { return false; } $_var_0 = $this->getSet(); $_var_11 = p("commission")->getInfo($_var_51, array('')); if (empty($_var_11)) { return; } if(empty($_var_11["bonuspluslevel"])){ $_var_52 = false; $_var_53 = pdo_fetch("select * from " . tablename("sz_yi_bonusplus_level") . " where uniacid=".$_W["uniacid"]." order by level asc"); }else{ $_var_52 = $this->getLevel($_var_11["openid"]); $_var_54 = pdo_fetchcolumn("select level from " . tablename("sz_yi_bonusplus_level") . " where  uniacid=:uniacid and id=:bonuspluslevel order by level asc", array( ":uniacid" => $_W["uniacid"], ":bonuspluslevel" => $_var_11["bonuspluslevel"] ) ); $_var_53 = pdo_fetch("select * from " . tablename("sz_yi_bonusplus_level") . " where  uniacid=:uniacid and level>:levelby order by level asc", array( ":uniacid" => $_W["uniacid"], ":levelby" => $_var_54 ) ); } if(empty($_var_53)){ return false; } $_var_55 = $_var_0["leveltype"]; $_var_56 = true; if(in_array("4", $_var_55)){ $_var_57 = pdo_fetchcolumn("select sum(price) from " . tablename("sz_yi_order") . " where openid=:openid and status>=3 and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_11["openid"])); if(!empty($_var_53["ordermoney"])){ if($_var_57 < $_var_53["ordermoney"]){ $_var_56 = false; } } } if(in_array("8", $_var_55)){ if(!empty($_var_53["downcount"])){ if($_var_11["agentcount"] < $_var_53["downcount"]){ $_var_56 = false; } } } if(in_array("9", $_var_55)){ if(!empty($_var_53["downcountlevel1"])){ if($_var_11["level1"] < $_var_53["downcountlevel1"]){ $_var_56 = false; } } } if($_var_56 == true){ pdo_update("sz_yi_member", array("bonuspluslevel" => $_var_53["id"], "bonusplus_status" =>1), array("id" => $_var_11["id"])); $_var_58 = $this->upgradeLevelByAgent($_var_11["id"]); if($_var_58 == false){ $this->sendMessage($_var_11["openid"], array("nickname" => $_var_11["nickname"], "oldlevel" => $_var_52, "newlevel" => $_var_53,), TM_BONUS_UPGRADE); } return true; } return false; } 

function sendMessage($_var_9 = '', $_var_23 = array(), $_var_59 = '') { global $_W, $_GPC; $_var_0 = $this->getSet(); $_var_60 = $_var_0["tm"]; $_var_61 = $_var_60["templateid"]; $_var_11 = m("member")->getMember($_var_9); $_var_62 = unserialize($_var_11["noticeset"]); if (!is_array($_var_62)) { $_var_62 = array(); } if ($_var_59 == TM_COMMISSION_AGENT_NEW && !empty($_var_60["commission_agent_new"]) && empty($_var_62["commission_agent_new"])) { $_var_63 = $_var_60["commission_agent_new"]; $_var_63 = str_replace("[昵称]", $_var_23["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_23["childtime"]), $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["commission_agent_newtitle"]) ? $_var_60["commission_agent_newtitle"] : "新增下线通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_BONUS_ORDER_PAY && !empty($_var_60["bonusplus_order_pay"]) && empty($_var_62["bonusplus_order_pay"])) { $_var_63 = $_var_60["bonusplus_order_pay"]; $_var_63 = str_replace("[昵称]", $_var_23["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_23["paytime"]), $_var_63); $_var_63 = str_replace("[订单编号]", $_var_23["ordersn"], $_var_63); $_var_63 = str_replace("[订单金额]", $_var_23["price"], $_var_63); $_var_63 = str_replace("[分红金额]", $_var_23["commission"], $_var_63); $_var_63 = str_replace("[商品详情]", $_var_23["goods"], $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["bonusplus_order_paytitle"]) ? $_var_60["bonusplus_order_paytitle"] : "分红下线付款通知"), "keyword2" => array("value" => $_var_63)); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_BONUS_ORDER_FINISH && !empty($_var_60["bonusplus_order_finish"]) && empty($_var_62["bonusplus_order_finish"])) { $_var_63 = $_var_60["bonusplus_order_finish"]; $_var_63 = str_replace("[昵称]", $_var_23["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_23["finishtime"]), $_var_63); $_var_63 = str_replace("[订单编号]", $_var_23["ordersn"], $_var_63); $_var_63 = str_replace("[订单金额]", $_var_23["price"], $_var_63); $_var_63 = str_replace("[分红金额]", $_var_23["commission"], $_var_63); $_var_63 = str_replace("[商品详情]", $_var_23["goods"], $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["bonusplus_order_finishtitle"]) ? $_var_60["bonusplus_order_finishtitle"] : "分红下线确认收货通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_COMMISSION_APPLY && !empty($_var_60["commission_apply"]) && empty($_var_62["commission_apply"])) { $_var_63 = $_var_60["commission_apply"]; $_var_63 = str_replace("[昵称]", $_var_11["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_63); $_var_63 = str_replace("[金额]", $_var_23["commission"], $_var_63); $_var_63 = str_replace("[提现方式]", $_var_23["type"], $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["commission_applytitle"]) ? $_var_60["commission_applytitle"] : "提现申请提交成功", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_COMMISSION_CHECK && !empty($_var_60["commission_check"]) && empty($_var_62["commission_check"])) { $_var_63 = $_var_60["commission_check"]; $_var_63 = str_replace("[昵称]", $_var_11["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_63); $_var_63 = str_replace("[金额]", $_var_23["commission"], $_var_63); $_var_63 = str_replace("[提现方式]", $_var_23["type"], $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["commission_checktitle"]) ? $_var_60["commission_checktitle"] : "提现申请审核处理完成", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_BONUS_PAY && !empty($_var_60["bonusplus_pay"]) && empty($_var_62["bonusplus_pay"])) { $_var_63 = $_var_60["bonusplus_pay"]; $_var_63 = str_replace("[昵称]", $_var_11["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_63); $_var_63 = str_replace("[金额]", $_var_23["commission"], $_var_63); $_var_63 = str_replace("[打款方式]", $_var_23["type"], $_var_63); $_var_63 = str_replace("[代理等级]", $_var_23["levename"], $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["bonusplus_paytitle"]) ? $_var_60["bonusplus_paytitle"] : "分红打款通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_BONUS_GLOBAL_PAY && !empty($_var_60["bonusplus_global_pay"]) && empty($_var_62["bonusplus_global_pay"])) { $_var_63 = $_var_60["bonusplus_global_pay"]; $_var_63 = str_replace("[昵称]", $_var_11["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_63); $_var_63 = str_replace("[金额]", $_var_23["commission"], $_var_63); $_var_63 = str_replace("[打款方式]", $_var_23["type"], $_var_63); $_var_63 = str_replace("[代理等级]", $_var_23["levename"], $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["bonusplus_global_paytitle"]) ? $_var_60["bonusplus_global_paytitle"] : "分红打款通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_BONUS_UPGRADE && !empty($_var_60["bonusplus_upgrade"]) && empty($_var_62["bonusplus_upgrade"])) { $_var_63 = $_var_60["bonusplus_upgrade"]; $_var_63 = str_replace("[昵称]", $_var_11["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_63); $_var_63 = str_replace("[旧等级]", $_var_23["oldlevel"]["levelname"], $_var_63); $_var_63 = str_replace("[旧分红比例]", $_var_23["oldlevel"]["agent_money"] . "%", $_var_63); $_var_63 = str_replace("[新等级]", $_var_23["newlevel"]["levelname"], $_var_63); $_var_63 = str_replace("[新分红比例]", $_var_23["newlevel"]["agent_money"] . "%", $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["bonusplus_upgradetitle"]) ? $_var_60["bonusplus_upgradetitle"] : "代理商等级升级通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if(!empty($_var_23["newlevel"]["content"])){ $_var_64 .= $_var_23["newlevel"]["content"]; } if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } else if ($_var_59 == TM_COMMISSION_BECOME && !empty($_var_60["commission_become"]) && empty($_var_62["commission_become"])) { $_var_63 = $_var_60["commission_become"]; $_var_63 = str_replace("[昵称]", $_var_23["nickname"], $_var_63); $_var_63 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_23["agenttime"]), $_var_63); $_var_64 = array("keyword1" => array("value" => !empty($_var_60["commission_becometitle"]) ? $_var_60["commission_becometitle"] : "成为分销商通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_63, "color" => "#73a68d")); if (!empty($_var_61)) { m("message")->sendTplNotice($_var_9, $_var_61, $_var_64); } else { m("message")->sendCustomNotice($_var_9, $_var_64); } } } 

function perms() { 
	return array(
		"commission" => array("text" => $this->getName(), "isplugin" => true, "child" => array("cover" => array("text" => "入口设置"), 
		"agent" => array("text" => "分销商", "view" => "浏览", "check" => "审核-log", "edit" => "修改-log", "agentblack" => "黑名单操作-log", "delete" => "删除-log", "user" => "查看下线", "order" => "查看推广订单(还需有订单权限)", "changeagent" => "设置分销商"), 
		"level" => array("text" => "分销商等级", "view" => "浏览", "add" => "添加-log", "edit" => "修改-log", "delete" => "删除-log"), "apply" => array("text" => "佣金审核", "view1" => "浏览待审核", "view2" => "浏览已审核", "view3" => "浏览已打款", "view_1" => "浏览无效", "export1" => "导出待审核-log", "export2" => "导出已审核-log", "export3" => "导出已打款-log", "export_1" => "导出无效-log", "check" => "审核-log", "pay" => "打款-log", "cancel" => "重新审核-log"), "notice" => array("text" => "通知设置-log"), "increase" => array("text" => "分销商趋势图"), 
		"changecommission" => array("text" => "修改佣金-log"), 
		"set" => array("text" => "基础设置-log")))
	); 
} 
public function sendorder($orderid){
	global $_W, $_GPC;
	$bonusplus_set = $this->getSet();
	$delaytime = intval($bonusplus_set["settledaysdf"]) * 3600 * 24;
	$bonuslog = pdo_fetch('select * from ' . tablename('sz_yi_bonusplus') . ' where uniacid=:uniacid and orderid=:orderid limit 1', array(
		':uniacid' => $_W['uniacid'],
		':orderid' => $orderid
	));
	if(empty($bonuslog))
	{
		$bonussn = time();
		$orderinfo = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where uniacid=:uniacid and id=:orderid limit 1', array(
			':uniacid' => $_W['uniacid'],
			':orderid' => $orderid
		));
		$openid = $orderinfo['openid'];
		$member = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and openid=:openid limit 1', array(
			':uniacid' => $_W['uniacid'],
			':openid' => $openid
		));
		$parentlist = $this->getParentAgents($member['id']);
		$t = 0;
		$renshu = 0;
		$allyongjin = 0;
		foreach($parentlist as $k=>$item)
		{
			$personyongjin = 0;
			$curuid = $item;
			//$curmember = m("member")->getMember($curuid);
			$curlevel = $this->getLevel($curuid);
			$ordergoodlist = pdo_fetchall('select * from ' . tablename('sz_yi_order_goods') . ' where uniacid=:uniacid and orderid=:orderid limit 1', array(
				':uniacid' => $_W['uniacid'],
				':orderid' => $orderid
			));
			$options_first = json_decode($curlevel['options_first'], true);
			$options_second = json_decode($curlevel['options_second'], true);
			$options_three = json_decode($curlevel['options_three'], true);
			foreach($ordergoodlist as $g)
			{
				$goodid = $g['goodsid'];
				if(strstr($options_three[$t],"[".$goodid."]"))
				{
					$goodinfo = pdo_fetch('select * from ' . tablename('sz_yi_goods') . ' where uniacid=:uniacid and id=:goodsid limit 1', array(
						':uniacid' => $_W['uniacid'],
						':goodsid' => $goodid
					));
					$jishu = $goodinfo['marketprice'];
					if(!empty($goodinfo['bonusplusmoney']))
					{
						if($goodinfo['bonusplusmoney'] > 0)
						{
							$jishu = $goodinfo['bonusplusmoney'];
						}
					}
					$yongjin = $jishu * $g['total'] * $options_first[$t] / 100;
					$yongjin = number_format($yongjin, 2);
					$personyongjin += $yongjin;
					if($personyongjin > 0)
					{
						$bonusgooddata = array(
							'uniacid' => $_W['uniacid'],
							'uid' => $curuid,
							'ordergoodid' => $goodid,
							'orderid' => $orderid,
							'total' => $g['total'],
							'mid' => $member['id'],
							'levelid' => $curlevel['id'],
							'level' => $t,
							'money' => $yongjin,
							'createtime' => TIMESTAMP
						);
						pdo_insert('sz_yi_bonusplus_goods', $bonusgooddata);
					}
					
				}
			}
			if($personyongjin > 0)
			{
				$renshu++;
				$qishu = intval($options_second[$t]);
				$peryongjin = $personyongjin / $qishu;
				$peryongjin = number_format($peryongjin, 2);
				$tian = 1;
				if($bonusplus_set["sendmonth"]==0)
				{
					$tian = 1;
				}
				else if($bonusplus_set["sendmonth"]==1)
				{
					$tian = 30;
				}
				else if($bonusplus_set["sendmonth"]==2)
				{
					$tian = 365;
				}
				for($i=0;$i<$qishu;$i++)
				{
					$bonuslogdata = array(
						'uniacid' => $_W['uniacid'],
						'uid' => $curuid,
						'orderid' => $orderid,
						'fromuid' => $member['id'],
						'qishu' => $qishu,
						'curqishu' => $i+1,
						'money' => $peryongjin,
						'send_bonus_sn' => $bonussn,
						'paymethod' => $bonusplus_set['paymethod'],
						'isglobal' => 1,
						'ctime' => TIMESTAMP,
						'paytime' => $delaytime + intval($bonussn) + $i * 3600 * 24 * $tian
					);
					pdo_insert('sz_yi_bonusplus_log', $bonuslogdata);
				}
				
			}
			$allyongjin += $personyongjin;
			$t++;
		}
		if($allyongjin > 0)
		{
			$bonusplusdata = array(
				'uniacid' => $_W['uniacid'],
				'orderid' => $orderid,
				'send_bonus_sn' => $bonussn,
				'money' => $allyongjin,
				'total' => $renshu,
				'type' => 1,
				'paymethod' => $bonusplus_set['paymethod'],
				'isglobal' => 1,
				'ctime' => TIMESTAMP
			);
			pdo_insert('sz_yi_bonusplus', $bonusplusdata);
			
			
		}
	}
}

public function autosendorder(){
	global $_W, $_GPC;
	$sendpaytime = time();
	$bonusplus_set = $this->getSet();
	$shopset = m("common")->getSysset("shop");
	$endtime = strtotime(date("Y-m-d 23:59:59"));
	$zhengdian = $bonusplus_set["senddaytime"];
	if(empty($zhengdian))
	{
		$zhengdian = 10;
	}
	// if($bonusplus_set['sendmethod'] == 0)
	// {
		// $zhengdian = 0;
	// }
	$starttime = strtotime(date("Y-m-d ".$zhengdian.":00:00"));
	
	$bonussql = "select lg.* from " . tablename("sz_yi_bonusplus_log") . " lg left join  ".tablename("sz_yi_order")."  o on o.id=lg.orderid and lg.status=0 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and lg.paytime > {$starttime} and lg.paytime < {$endtime} ORDER BY o.finishtime DESC,o.status DESC";
	$bonuslog = pdo_fetchall($bonussql);
	if(!empty($bonuslog))
	{
		foreach($bonuslog as $k=>$item)
		{
			$member = m("member")->getInfo($item['uid']);
			if($member['agentblack'] == 1 || $member['isblack'] == 1){
				continue;
			}
			$payed = 1;
			$level = $this->getlevel($member["openid"]);
			
			if(empty($bonusplus_set["paymethod"])){
				m("member")->setCredit($member["openid"], "credit2", $item['money']);
			}else{
				$logno = m("common")->createNO("bonus_log", "logno", "RB");
				$wxpay = m("finance")->pay($member["openid"], 1, $item['money'] * 100, $logno, "【" . $shopset["name"]. "】".$level["levelname"]."静态分红");
				if (is_error($wxpay))
				{
					$payed = 0;
				}
			}
			if($payed == 1){
				$this->sendMessage($member["openid"], array("nickname" => $member["nickname"], "levelname" => $level["levelname"], "commission" => $item['money'], "type" => empty($bonusplus_set["paymethod"]) ? "余额" : "微信钱包"), TM_BONUS_PAY);
			}
			// $bonusgooddata = array(
				// "status" => 3,
				// "applytime" => $sendpaytime,
				// "checktime" => $sendpaytime,
				// "paytime" => $sendpaytime,
				// "invalidtime" => $sendpaytime
			// );
			// pdo_update("sz_yi_bonus_goods", $bonusgooddata, array("mid" => $_var_11["id"], "uniacid" => $_W["uniacid"])); $_var_71 += $_var_11["commission_ok"];
			// $bonuslogdata = array(
				// "status" => 1,
				// "paytime" => $sendpaytime
			// );
			// pdo_update("sz_yi_bonus_goods", $bonuslogdata, array("id" => $item["id"]));
			
			$bonuslogdata = array(
				"status" => 1,
				"sendpaytime" => $sendpaytime
			);
			pdo_update("sz_yi_bonusplus_log", $bonuslogdata, array("id" => $item["id"]));
			
			
		}
	}
}

public function autosend(){ 
	global $_W, $_GPC;
	$_var_8 = time();
	$_var_65 = 0;
	$_var_22 = 0;
	$_var_66 = false;
	$_var_0 = $this->getSet();
	$_var_67 = m("common")->getSysset("shop");
	if(empty($_var_0["sendmethod"])){
		return false;
	}
	$_var_68 = strtotime(date("Y-m-d 00:00:00"));
	if(empty($_var_0["sendmonth"])){
		$_var_69 = $_var_68-1;
	}
	else if($_var_0["sendmonth"] == 1){
		$_var_69 = date("Y-m-d", mktime(0,0,0,date("m")-1,1,date("Y")));
	}
	$_var_33 = intval($_var_0["settledaysdf"]) * 3600 * 24;
	$_var_4 = "select distinct cg.mid from " . tablename("sz_yi_bonus_goods") . " cg left join  ".tablename("sz_yi_order")."  o on o.id=cg.orderid and cg.status=0 left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_W["uniacid"]} and ({$_var_69} - o.finishtime > {$_var_33})  ORDER BY o.finishtime DESC,o.status DESC";
	$_var_70 = pdo_fetchall($_var_4);
	$_var_71 = 0;
	if(empty($_var_70)){
		return false;
	}
	foreach ($_var_70 as $_var_17 => $_var_72)
	{
		$_var_11 = $this->getInfo($_var_72["mid"], array("ok"));
		$_var_73 = $_var_11["commission_ok"];
		if($_var_73<=0){
			continue;
		}
		$_var_66 = true;
		$_var_74 = 1;
		$_var_18 = $this->getlevel($_var_11["openid"]);
		if(empty($_var_0["paymethod"])){
			m("member")->setCredit($_var_11["openid"], "credit2", $_var_73);
		}else{
			$_var_75 = m("common")->createNO("bonus_log", "logno", "RB");
			$_var_76 = m("finance")->pay($_var_11["openid"], 1, $_var_73 * 100, $_var_75, "【" . $_var_67["name"]. "】".$_var_18["levelname"]."分红");
			if (is_error($_var_76))
			{
				$_var_74 = 0;
				$_var_65 = 1;
			}
		}
		pdo_insert("sz_yi_bonus_log", array( "openid" => $_var_11["openid"], "uid" => $_var_11["uid"], "money" => $_var_73, "uniacid" => $_W["uniacid"], "paymethod" => $_var_0["paymethod"], "sendpay" => $_var_74, "status" => 1, "ctime" => time(), "send_bonus_sn" => $_var_8 ));
		if($_var_74 == 1){
			$this->sendMessage($_var_11["openid"], array("nickname" => $_var_11["nickname"], "levelname" => $_var_18["levelname"], "commission" => $_var_73, "type" => empty($_var_0["paymethod"]) ? "余额" : "微信钱包"), TM_BONUS_PAY);
		}
		$_var_48 = array( "status" => 3, "applytime" => $_var_8, "checktime" => $_var_8, "paytime" => $_var_8, "invalidtime" => $_var_8 );
		pdo_update("sz_yi_bonus_goods", $_var_48, array("mid" => $_var_11["id"], "uniacid" => $_W["uniacid"])); $_var_71 += $_var_11["commission_ok"];
	}
	if($_var_66){
		$_var_77 = array( "uniacid" => $_W["uniacid"], "money" => $_var_71, "status" => 1, "ctime" => time(), "paymethod" => $_var_0["paymethod"], "sendpay_error" => $_var_65, "utime" => $_var_68, "send_bonus_sn" => $_var_8, "total" => count($_var_70) );
		pdo_insert("sz_yi_bonus", $_var_77); return true;
	}
}

	public function autosendall(){
		$_var_8 = time();
		$_var_65 = 0;
		$_var_22 = 0;
		$_var_71 = 0;
		$_var_66 = false;
		$_var_0 = $this->getSet();
		$_var_67 = m("common")->getSysset("shop");
		if(empty($_var_0["sendmethod"])){
			return false;
		}
		$_var_68 = strtotime(date("Y-m-d 00:00:00"));
		if(empty($_var_0["sendmonth"])){
			$_var_69 = $_var_68-1;
		}
		else if($_var_0["sendmonth"] == 1){
			$_var_69 = date("Y-m-d", mktime(0,0,0,date("m")-1,1,date("Y")));
		}
		$_var_4 = "select sum(o.price) from ".tablename("sz_yi_order")." o left join " . tablename("sz_yi_order_refund") . " r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid={$_var_78["uniacid"]} and ({$_var_8} - o.finishtime > {$_var_69})  ORDER BY o.finishtime DESC,o.status DESC";
		$_var_79 = pdo_fetchcolumn($_var_4); $_var_80 = pdo_fetchall("select * from ".tablename("sz_yi_bonus_level")." where uniacid={$_var_78["uniacid"]} and premier=1");
		$_var_81 = array();
		$_var_71 = 0;
		foreach ($_var_80 as $_var_17 => $_var_72) {
			$_var_82 = pdo_fetchcolumn("select count(*) from ".tablename("sz_yi_member")." where uniacid={$_var_78["uniacid"]} and bonuslevel=".$_var_72["id"]." and bonus_status = 1");
			if($_var_82>0){
				$_var_83 = round($_var_79*$_var_72["pcommission"]/100,2);
				if($_var_83 > 0){
					$_var_84 = round($_var_83/$_var_82,2);
					if($_var_84 > 0){
						$_var_81[$_var_72["id"]] = $_var_84; $_var_71 += $_var_84;
					}
				}
			}
		}
		$_var_85 = pdo_fetchall("select m.* from ".tablename("sz_yi_member")." m left join " . tablename("sz_yi_bonus_level") . " l on m.bonuslevel=l.id and m.bonus_status=1 where 1 and l.premier=1 and m.uniacid={$_var_78["uniacid"]}");
		foreach ($_var_85 as $_var_17 => $_var_72)
		{
			$_var_18 = pdo_fetch("select id, levelname from " . tablename("sz_yi_bonus_level") . " where id=".$_var_86["bonuslevel"]);
			$_var_73 = $_var_81[$_var_18["id"]];
			if($_var_73<=0){
				continue;
			}
			$_var_66 = true;
			$_var_74 = 1;
			$_var_18 = $this->getlevel($_var_11["openid"]);
			if(empty($_var_0["paymethod"])){
				m("member")->setCredit($_var_72["openid"], "credit2", $_var_73);
			}else{
				$_var_75 = m("common")->createNO("bonus_log", "logno", "RB");
				$_var_76 = m("finance")->pay($_var_72["openid"], 1, $_var_73 * 100, $_var_75, "【" . $_var_67["name"]. "】".$_var_72["levelname"]."分红");
				if (is_error($_var_76))
				{
					$_var_74 = 0;
					$_var_65 = 1;
				}
			}
			pdo_insert("sz_yi_bonus_log", array( "openid" => $_var_11["openid"], "uid" => $_var_11["uid"], "money" => $_var_73, "uniacid" => $_var_78["uniacid"], "paymethod" => $_var_0["paymethod"], "sendpay" => $_var_74, "isglobal" => 1, "status" => 1, "ctime" => time(), "send_bonus_sn" => $_var_8 ));
			if($_var_74 == 1){
				$this->sendMessage($_var_11["openid"], array("nickname" => $_var_11["nickname"], "levelname" => $_var_18["levelname"], "commission" => $_var_73, "type" => empty($_var_0["paymethod"]) ? "余额" : "微信钱包"), TM_BONUS_GLOPAL_PAY);
			}
		}
		if($_var_66){
			$_var_77 = array( "uniacid" => $_var_78["uniacid"], "money" => $_var_71, "status" => 1, "ctime" => time(), "paymethod" => $_var_0["paymethod"], "sendpay_error" => $_var_65, "isglobal" => 1, "utime" => $_var_68, "send_bonus_sn" => $_var_8, "total" => $_var_87 );
			pdo_insert("sz_yi_bonus", $_var_77);
		}
	}
}
}