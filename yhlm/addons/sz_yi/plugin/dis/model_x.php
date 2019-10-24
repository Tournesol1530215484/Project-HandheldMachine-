<?php



/*=============================================================================

#         Desc: 专业承接微信分销商城二次开发及相关微信功能模块的开发与定制

#       Author: Man.Dan - http://www.jzwshop.com

#        Email: 82089092@qq.com

#     HomePage: http://www.jzwshop.com

#      Version: 0.0.1

#   LastChange: 2016-02-05 02:08:51

#      History:

=============================================================================*/

if (!defined("IN_IA")) { exit("Access Denied"); } 

define("TM_COMMISSION_AGENT_NEW", "commission_agent_new");   

define("TM_COMMISSION_ORDER_PAY", "commission_order_pay"); 

define("TM_COMMISSION_ORDER_FINISH", "commission_order_finish"); 
 
define("TM_COMMISSION_APPLY", "commission_apply"); 

define("TM_COMMISSION_CHECK", "commission_check"); 

define("TM_COMMISSION_PAY", "commission_pay"); 

define("TM_COMMISSION_UPGRADE", "commission_upgrade"); 

define("TM_COMMISSION_BECOME", "commission_become"); 

if (!class_exists("DisModel")) { 
	class DisModel extends PluginModel { 
	public function getSet() {
		$_var_0 = parent::getSet();
		$_var_0["texts"] = array(
			"agent" => empty($_var_0["texts"]["agent"]) ? "分销商" : $_var_0["texts"]["agent"],
			"shop" => empty($_var_0["texts"]["shop"]) ? "小店" : $_var_0["texts"]["shop"],
			"myshop" => empty($_var_0["texts"]["myshop"]) ? "我的小店" : $_var_0["texts"]["myshop"],
			"center" => empty($_var_0["texts"]["center"]) ? "分销中心" : $_var_0["texts"]["center"],
			"become" => empty($_var_0["texts"]["become"]) ? "成为分销商" : $_var_0["texts"]["become"],
			"withdraw" => empty($_var_0["texts"]["withdraw"]) ? "提现" : $_var_0["texts"]["withdraw"],
			"commission" => empty($_var_0["texts"]["commission"]) ? "佣金" : $_var_0["texts"]["commission"],
			"commission1" => empty($_var_0["texts"]["commission1"]) ? "分销佣金" : $_var_0["texts"]["commission1"],
			"commission_total" => empty($_var_0["texts"]["commission_total"]) ? "累计佣金" : $_var_0["texts"]["commission_total"],
			"commission_ok" => empty($_var_0["texts"]["commission_ok"]) ? "可提现佣金" : $_var_0["texts"]["commission_ok"],
			"commission_apply" => empty($_var_0["texts"]["commission_apply"]) ? "已申请佣金" : $_var_0["texts"]["commission_apply"],
			"commission_check" => empty($_var_0["texts"]["commission_check"]) ? "待打款佣金" : $_var_0["texts"]["commission_check"],
			"commission_lock" => empty($_var_0["texts"]["commission_lock"]) ? "未结算佣金" : $_var_0["texts"]["commission_lock"],
			"commission_detail" => empty($_var_0["texts"]["commission_detail"]) ? "佣金明细" : $_var_0["texts"]["commission_detail"],
			"commission_pay" => empty($_var_0["texts"]["commission_pay"]) ? "成功提现佣金" : $_var_0["texts"]["commission_pay"],
			"order" => empty($_var_0["texts"]["order"]) ? "分销订单" : $_var_0["texts"]["order"],
			"myteam" => empty($_var_0["texts"]["myteam"]) ? "我的团队" : $_var_0["texts"]["myteam"],
			"c1" => empty($_var_0["texts"]["c1"]) ? "一级" : $_var_0["texts"]["c1"],
			"c2" => empty($_var_0["texts"]["c2"]) ? "二级" : $_var_0["texts"]["c2"],
			"c3" => empty($_var_0["texts"]["c3"]) ? "三级" : $_var_0["texts"]["c3"],
			"mycustomer" => empty($_var_0["texts"]["mycustomer"]) ? "我的客户" : $_var_0["texts"]["mycustomer"],
		);
		return $_var_0;
	}
	
	
	
		      
			  
		public function getpath(){
		
		echo "fdafd";
		
		}	  
	
	
	
	public function calculate($_var_1 = 0, $_var_2 = true) {
		global $_W;
		$_var_0 = $this->getSet();
		$_var_3 = $this->getLevels();
		$_var_4 = pdo_fetchcolumn("select agentid from " . tablename("sz_yi_order") . " where id=:id limit 1", array(":id" => $_var_1));
		$_var_5 = pdo_fetchall('select og.id,og.realprice,og.total,g.hascommission,g.nocommission, g.commission1_rate,g.commission1_pay,g.commission2_rate,g.commission2_pay,g.commission3_rate,g.commission3_pay,og.commissions from ' . tablename("sz_yi_order_goods") . "  og " . " left join " . tablename("sz_yi_goods") . " g on g.id = og.goodsid" . " where og.orderid=:orderid and og.uniacid=:uniacid", array(":orderid" => $_var_1, ":uniacid" => $_W["uniacid"]));
		if ($_var_0["level"] > 0) {
			foreach ($_var_5 as &$_var_6) {
				$_var_7 = $_var_6["realprice"];
				if (empty($_var_6["nocommission"])) {
					if ($_var_6["hascommission"] == 1) {
						$_var_6["commission1"] = array("default" => $_var_0["level"] >= 1 ? ($_var_6["commission1_rate"] > 0 ? round($_var_6["commission1_rate"] * $_var_7 / 100, 2) . "" : round($_var_6["commission1_pay"] * $_var_6["total"], 2)) : 0);
						$_var_6["commission2"] = array("default" => $_var_0["level"] >= 2 ? ($_var_6["commission2_rate"] > 0 ? round($_var_6["commission2_rate"] * $_var_7 / 100, 2) . "" : round($_var_6["commission2_pay"] * $_var_6["total"], 2)) : 0);
						$_var_6["commission3"] = array("default" => $_var_0["level"] >= 3 ? ($_var_6["commission3_rate"] > 0 ? round($_var_6["commission3_rate"] * $_var_7 / 100, 2) . "" : round($_var_6["commission3_pay"] * $_var_6["total"], 2)) : 0);
						foreach ($_var_3 as $_var_8) { $_var_6["commission1"]["level" . $_var_8["id"]] = $_var_6["commission1_rate"] > 0 ? round($_var_6["commission1_rate"] * $_var_7 / 100, 2) . "" : round($_var_6["commission1_pay"] * $_var_6["total"], 2); $_var_6["commission2"]["level" . $_var_8["id"]] = $_var_6["commission2_rate"] > 0 ? round($_var_6["commission2_rate"] * $_var_7 / 100, 2) . "" : round($_var_6["commission2_pay"] * $_var_6["total"], 2); $_var_6["commission3"]["level" . $_var_8["id"]] = $_var_6["commission3_rate"] > 0 ? round($_var_6["commission3_rate"] * $_var_7 / 100, 2) . "" : round($_var_6["commission3_pay"] * $_var_6["total"], 2); } } else { $_var_6["commission1"] = array("default" => $_var_0["level"] >= 1 ? round($_var_0["commission1"] * $_var_7 / 100, 2) . "" : 0); $_var_6["commission2"] = array("default" => $_var_0["level"] >= 2 ? round($_var_0["commission2"] * $_var_7 / 100, 2) . "" : 0); $_var_6["commission3"] = array("default" => $_var_0["level"] >= 3 ? round($_var_0["commission3"] * $_var_7 / 100, 2) . "" : 0); foreach ($_var_3 as $_var_8) { $_var_6["commission1"]["level" . $_var_8["id"]] = $_var_0["level"] >= 1 ? round($_var_8["commission1"] * $_var_7 / 100, 2) . "" : 0; $_var_6["commission2"]["level" . $_var_8["id"]] = $_var_0["level"] >= 2 ? round($_var_8["commission2"] * $_var_7 / 100, 2) . "" : 0; $_var_6["commission3"]["level" . $_var_8["id"]] = $_var_0["level"] >= 3 ? round($_var_8["commission3"] * $_var_7 / 100, 2) . "" : 0; } } } else { $_var_6["commission1"] = array("default" => 0); $_var_6["commission2"] = array("default" => 0); $_var_6["commission3"] = array("default" => 0); foreach ($_var_3 as $_var_8) { $_var_6["commission1"]["level" . $_var_8["id"]] = 0; $_var_6["commission2"]["level" . $_var_8["id"]] = 0; $_var_6["commission3"]["level" . $_var_8["id"]] = 0; } } if ($_var_2) { $_var_9 = array("level1" => 0, "level2" => 0, "level3" => 0); if (!empty($_var_4)) { $_var_10 = m("member")->getMember($_var_4); if ($_var_10["isagent"] == 1 && $_var_10["status"] == 1) { $_var_11 = $this->getLevel($_var_10["openid"]); $_var_9["level1"] = empty($_var_11) ? round($_var_6["commission1"]["default"], 2) : round($_var_6["commission1"]["level" . $_var_11["id"]], 2); if (!empty($_var_10["agentid"])) { $_var_12 = m("member")->getMember($_var_10["agentid"]); $_var_13 = $this->getLevel($_var_12["openid"]); $_var_9["level2"] = empty($_var_13) ? round($_var_6["commission2"]["default"], 2) : round($_var_6["commission2"]["level" . $_var_13["id"]], 2); if (!empty($_var_12["agentid"])) { $_var_14 = m("member")->getMember($_var_12["agentid"]); $_var_15 = $this->getLevel($_var_14["openid"]); $_var_9["level3"] = empty($_var_15) ? round($_var_6["commission3"]["default"], 2) : round($_var_6["commission3"]["level" . $_var_15["id"]], 2); } } } } pdo_update("sz_yi_order_goods", array("commission1" => iserializer($_var_6["commission1"]), "commission2" => iserializer($_var_6["commission2"]), "commission3" => iserializer($_var_6["commission3"]), "commissions" => iserializer($_var_9), "nocommission" => $_var_6["nocommission"]), array("id" => $_var_6["id"])); } } unset($_var_6); } return $_var_5; }
	
	public function getOrderCommissions($_var_1 = 0, $_var_16 = 0) { global $_W; $_var_0 = $this->getSet(); $_var_4 = pdo_fetchcolumn("select agentid from " . tablename("sz_yi_order") . " where id=:id limit 1", array(":id" => $_var_1)); $_var_5 = pdo_fetch("select commission1,commission2,commission3 from " . tablename("sz_yi_order_goods") . " where id=:id and orderid=:orderid and uniacid=:uniacid and nocommission=0 limit 1", array(":id" => $_var_16, ":orderid" => $_var_1, ":uniacid" => $_W["uniacid"])); $_var_9 = array("level1" => 0, "level2" => 0, "level3" => 0); if ($_var_0["level"] > 0) { $_var_17 = iunserializer($_var_5["commission1"]); $_var_18 = iunserializer($_var_5["commission2"]); $_var_19 = iunserializer($_var_5["commission3"]); if (!empty($_var_4)) { $_var_10 = m("member")->getMember($_var_4); if ($_var_10["isagent"] == 1 && $_var_10["status"] == 1) { $_var_11 = $this->getLevel($_var_10["openid"]); $_var_9["level1"] = empty($_var_11) ? round($_var_17["default"], 2) : round($_var_17["level" . $_var_11["id"]], 2); if (!empty($_var_10["agentid"])) { $_var_12 = m("member")->getMember($_var_10["agentid"]); $_var_13 = $this->getLevel($_var_12["openid"]); $_var_9["level2"] = empty($_var_13) ? round($_var_18["default"], 2) : round($_var_18["level" . $_var_13["id"]], 2); if (!empty($_var_12["agentid"])) { $_var_14 = m("member")->getMember($_var_12["agentid"]); $_var_15 = $this->getLevel($_var_14["openid"]); $_var_9["level3"] = empty($_var_15) ? round($_var_19["default"], 2) : round($_var_19["level" . $_var_15["id"]], 2); } } } } } return $_var_9; }
	public function getInfo($_var_20, $_var_21 = null) { if (empty($_var_21) || !is_array($_var_21)) { $_var_21 = array(); } global $_W; $_var_0 = $this->getSet(); $_var_8 = intval($_var_0["level"]); $_var_22 = m("member")->getMember($_var_20); $_var_23 = $this->getLevel($_var_20); $_var_24 = time(); $_var_25 = intval($_var_0["settledays"]) * 3600 * 24; $_var_26 = 0; $_var_27 = 0; $_var_28 = 0; $_var_29 = 0; $_var_30 = 0; $_var_31 = 0; $_var_32 = 0; $_var_33 = 0; $_var_34 = 0; $_var_35 = 0; $_var_36 = 0; $_var_37 = 0; $_var_38 = 0; $_var_39 = 0; $_var_40 = 0; $_var_41 = 0; $_var_42 = 0; $_var_43 = 0; $_var_44 = 0; $_var_45 = 0; $_var_46 = 0; $_var_47 = 0; $_var_48 = 0; $_var_49 = 0; $_var_50 = 0; $_var_51 = 0; $_var_52 = 0; $_var_53 = 0; $_var_54 = 0; $_var_55 = 0; if ($_var_8 >= 1) { if (in_array("ordercount0", $_var_21)) { $_var_56 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . ' where o.agentid=:agentid and o.status>=0 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); $_var_42 += $_var_56["ordercount"]; $_var_27 += $_var_56["ordercount"]; $_var_28 += $_var_56["ordermoney"]; } if (in_array("ordercount", $_var_21)) { $_var_56 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . ' where o.agentid=:agentid and o.status>=1 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); $_var_45 += $_var_56["ordercount"]; $_var_29 += $_var_56["ordercount"]; $_var_30 += $_var_56["ordermoney"]; } if (in_array("ordercount3", $_var_21)) { $_var_57 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . ' where o.agentid=:agentid and o.status>=3 and og.status1>=0 and og.nocommission=0 and o.uniacid=:uniacid  limit 1', array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); $_var_48 += $_var_57["ordercount"]; $_var_31 += $_var_57["ordercount"]; $_var_32 += $_var_57["ordermoney"]; $_var_51 += $_var_57["ordermoney"]; } if (in_array("total", $_var_21)) { $_var_58 = pdo_fetchall("select og.commission1,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid=:agentid and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); foreach ($_var_58 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission1"]); if (empty($_var_9)) { $_var_33 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_33 += isset($_var_9["level1"]) ? floatval($_var_9["level1"]) : 0; } } } if (in_array("ok", $_var_21)) { $_var_58 = pdo_fetchall("select og.commission1,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$_var_24} - o.createtime > {$_var_25}) and og.status1=0  and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); foreach ($_var_58 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission1"]); if (empty($_var_9)) { $_var_34 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_34 += isset($_var_9["level1"]) ? $_var_9["level1"] : 0; } } } if (in_array("lock", $_var_21)) { $_var_61 = pdo_fetchall("select og.commission1,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid=:agentid and o.status>=3 and og.nocommission=0 and ({$_var_24} - o.createtime <= {$_var_25})  and og.status1=0  and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); foreach ($_var_61 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission1"]); if (empty($_var_9)) { $_var_37 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_37 += isset($_var_9["level1"]) ? $_var_9["level1"] : 0; } } } if (in_array("apply", $_var_21)) { $_var_62 = pdo_fetchall("select og.commission1,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . ' where o.agentid=:agentid and o.status>=3 and og.status1=1 and og.nocommission=0 and o.uniacid=:uniacid', array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); foreach ($_var_62 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission1"]); if (empty($_var_9)) { $_var_35 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_35 += isset($_var_9["level1"]) ? $_var_9["level1"] : 0; } } } if (in_array("check", $_var_21)) { $_var_62 = pdo_fetchall("select og.commission1,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . ' where o.agentid=:agentid and o.status>=3 and og.status1=2 and og.nocommission=0 and o.uniacid=:uniacid ', array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); foreach ($_var_62 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission1"]); if (empty($_var_9)) { $_var_36 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_36 += isset($_var_9["level1"]) ? $_var_9["level1"] : 0; } } } if (in_array("pay", $_var_21)) { $_var_62 = pdo_fetchall("select og.commission1,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . ' where o.agentid=:agentid and o.status>=3 and og.status1=3 and og.nocommission=0 and o.uniacid=:uniacid ', array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"])); foreach ($_var_62 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission1"]); if (empty($_var_9)) { $_var_38 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_38 += isset($_var_9["level1"]) ? $_var_9["level1"] : 0; } } } $_var_63 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid=:agentid and isagent=1 and status=1 and uniacid=:uniacid ", array(":uniacid" => $_W["uniacid"], ":agentid" => $_var_22["id"]), "id"); $_var_39 = count($_var_63); $_var_26 += $_var_39; } if ($_var_8 >= 2) { if ($_var_39 > 0) { if (in_array("ordercount0", $_var_21)) { $_var_64 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=0 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"])); $_var_43 += $_var_64["ordercount"]; $_var_27 += $_var_64["ordercount"]; $_var_28 += $_var_64["ordermoney"]; } if (in_array("ordercount", $_var_21)) { $_var_64 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=1 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"])); $_var_46 += $_var_64["ordercount"]; $_var_29 += $_var_64["ordercount"]; $_var_30 += $_var_64["ordermoney"]; } if (in_array("ordercount3", $_var_21)) { $_var_65 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct o.id) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=3 and og.status2>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"])); $_var_49 += $_var_65["ordercount"]; $_var_31 += $_var_65["ordercount"]; $_var_32 += $_var_65["ordermoney"]; $_var_52 += $_var_65["ordermoney"]; } if (in_array("total", $_var_21)) { $_var_66 = pdo_fetchall("select og.commission2,og.commissions from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_66 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission2"]); if (empty($_var_9)) { $_var_33 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_33 += isset($_var_9["level2"]) ? $_var_9["level2"] : 0; } } } if (in_array("ok", $_var_21)) { $_var_66 = pdo_fetchall("select og.commission2,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and ({$_var_24} - o.createtime > {$_var_25}) and o.status>=3 and og.status2=0 and og.nocommission=0  and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_66 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission2"]); if (empty($_var_9)) { $_var_34 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_34 += isset($_var_9["level2"]) ? $_var_9["level2"] : 0; } } } if (in_array("lock", $_var_21)) { $_var_67 = pdo_fetchall("select og.commission2,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and ({$_var_24} - o.createtime <= {$_var_25}) and og.status2=0 and o.status>=3 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_67 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission2"]); if (empty($_var_9)) { $_var_37 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_37 += isset($_var_9["level2"]) ? $_var_9["level2"] : 0; } } } if (in_array("apply", $_var_21)) { $_var_68 = pdo_fetchall("select og.commission2,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=3 and og.status2=1 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_68 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission2"]); if (empty($_var_9)) { $_var_35 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_35 += isset($_var_9["level2"]) ? $_var_9["level2"] : 0; } } } if (in_array("check", $_var_21)) { $_var_69 = pdo_fetchall("select og.commission2,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=3 and og.status2=2 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_69 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission2"]); if (empty($_var_9)) { $_var_36 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_36 += isset($_var_9["level2"]) ? $_var_9["level2"] : 0; } } } if (in_array("pay", $_var_21)) { $_var_69 = pdo_fetchall("select og.commission2,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid " . " where o.agentid in( " . implode(",", array_keys($_var_63)) . ")  and o.status>=3 and og.status2=3 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_69 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission2"]); if (empty($_var_9)) { $_var_38 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_38 += isset($_var_9["level2"]) ? $_var_9["level2"] : 0; } } } $_var_70 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid in( " . implode(",", array_keys($_var_63)) . ") and isagent=1 and status=1 and uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]), "id"); $_var_40 = count($_var_70); $_var_26 += $_var_40; } } if ($_var_8 >= 3) { if ($_var_40 > 0) { if (in_array("ordercount0", $_var_21)) { $_var_71 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=0 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"])); $_var_44 += $_var_71["ordercount"]; $_var_27 += $_var_71["ordercount"]; $_var_28 += $_var_71["ordermoney"]; } if (in_array("ordercount", $_var_21)) { $_var_71 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=1 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"])); $_var_47 += $_var_71["ordercount"]; $_var_29 += $_var_71["ordercount"]; $_var_30 += $_var_71["ordermoney"]; } if (in_array("ordercount3", $_var_21)) { $_var_72 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=3 and og.status3>=0 and og.nocommission=0 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"])); $_var_50 += $_var_72["ordercount"]; $_var_31 += $_var_72["ordercount"]; $_var_32 += $_var_72["ordermoney"]; $_var_53 += $_var_71["ordermoney"]; } if (in_array("total", $_var_21)) { $_var_73 = pdo_fetchall("select og.commission3,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=1 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_73 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission3"]); if (empty($_var_9)) { $_var_33 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_33 += isset($_var_9["level3"]) ? $_var_9["level3"] : 0; } } } if (in_array("ok", $_var_21)) { $_var_73 = pdo_fetchall("select og.commission3,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and ({$_var_24} - o.createtime > {$_var_25}) and o.status>=3 and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_73 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission3"]); if (empty($_var_9)) { $_var_34 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_34 += isset($_var_9["level3"]) ? $_var_9["level3"] : 0; } } } if (in_array("lock", $_var_21)) { $_var_74 = pdo_fetchall("select og.commission3,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=3 and ({$_var_24} - o.createtime > {$_var_25}) and og.status3=0  and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_74 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission3"]); if (empty($_var_9)) { $_var_37 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_37 += isset($_var_9["level3"]) ? $_var_9["level3"] : 0; } } } if (in_array("apply", $_var_21)) { $_var_75 = pdo_fetchall("select og.commission3,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=3 and og.status3=1 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_75 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission3"]); if (empty($_var_9)) { $_var_35 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_35 += isset($_var_9["level3"]) ? $_var_9["level3"] : 0; } } } if (in_array("check", $_var_21)) { $_var_76 = pdo_fetchall("select og.commission3,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=3 and og.status3=2 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_76 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission3"]); if (empty($_var_9)) { $_var_36 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_36 += isset($_var_9["level3"]) ? $_var_9["level3"] : 0; } } } if (in_array("pay", $_var_21)) { $_var_76 = pdo_fetchall("select og.commission3,og.commissions  from " . tablename("sz_yi_order_goods") . " og " . " left join  " . tablename("sz_yi_order") . " o on o.id = og.orderid" . " where o.agentid in( " . implode(",", array_keys($_var_70)) . ")  and o.status>=3 and og.status3=3 and og.nocommission=0 and o.uniacid=:uniacid", array(":uniacid" => $_W["uniacid"])); foreach ($_var_76 as $_var_59) { $_var_9 = iunserializer($_var_59["commissions"]); $_var_60 = iunserializer($_var_59["commission3"]); if (empty($_var_9)) { $_var_38 += isset($_var_60["level" . $_var_23["id"]]) ? $_var_60["level" . $_var_23["id"]] : $_var_60["default"]; } else { $_var_38 += isset($_var_9["level3"]) ? $_var_9["level3"] : 0; } } } $_var_77 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where uniacid=:uniacid and agentid in( " . implode(",", array_keys($_var_70)) . ") and isagent=1 and status=1", array(":uniacid" => $_W["uniacid"]), "id"); $_var_41 = count($_var_77); $_var_26 += $_var_41; } } if (in_array("myorder", $_var_21)) { $_var_78 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_22["openid"])); $_var_54 = $_var_78["ordermoney"]; $_var_55 = $_var_78["ordercount"]; } $_var_22["agentcount"] = $_var_26; $_var_22["ordercount"] = $_var_29; $_var_22["ordermoney"] = $_var_30; $_var_22["order1"] = $_var_45; $_var_22["order2"] = $_var_46; $_var_22["order3"] = $_var_47; $_var_22["ordercount3"] = $_var_31; $_var_22["ordermoney3"] = $_var_32; $_var_22["order13"] = $_var_48; $_var_22["order23"] = $_var_49; $_var_22["order33"] = $_var_50; $_var_22["order13money"] = $_var_51; $_var_22["order23money"] = $_var_52; $_var_22["order33money"] = $_var_53; $_var_22["ordercount0"] = $_var_27; $_var_22["ordermoney0"] = $_var_28; $_var_22["order10"] = $_var_42; $_var_22["order20"] = $_var_43; $_var_22["order30"] = $_var_44; $_var_22["commission_total"] = round($_var_33, 2); $_var_22["commission_ok"] = round($_var_34, 2); $_var_22["commission_lock"] = round($_var_37, 2); $_var_22["commission_apply"] = round($_var_35, 2); $_var_22["commission_check"] = round($_var_36, 2); $_var_22["commission_pay"] = round($_var_38, 2); $_var_22["level1"] = $_var_39; $_var_22["level1_agentids"] = $_var_63; $_var_22["level2"] = $_var_40; $_var_22["level2_agentids"] = $_var_70; $_var_22["level3"] = $_var_41; $_var_22["level3_agentids"] = $_var_77; $_var_22["agenttime"] = date("Y-m-d H:i", $_var_22["agenttime"]); $_var_22["myoedermoney"] = $_var_54; $_var_22["myordercount"] = $_var_55; return $_var_22; }
	public function getAgents($_var_1 = 0) { global $_W, $_GPC; $_var_79 = array(); $_var_80 = pdo_fetch("select id,agentid,openid from " . tablename("sz_yi_order") . " where id=:id and uniacid=:uniacid limit 1", array(":id" => $_var_1, ":uniacid" => $_W["uniacid"])); if (empty($_var_80)) { return $_var_79; } $_var_10 = m("member")->getMember($_var_80["agentid"]); if (!empty($_var_10) && $_var_10["isagent"] == 1 && $_var_10["status"] == 1) { $_var_79[] = $_var_10; if (!empty($_var_10["agentid"])) { $_var_12 = m("member")->getMember($_var_10["agentid"]); if (!empty($_var_12) && $_var_12["isagent"] == 1 && $_var_12["status"] == 1) { $_var_79[] = $_var_12; if (!empty($_var_12["agentid"])) { $_var_14 = m("member")->getMember($_var_12["agentid"]); if (!empty($_var_14) && $_var_14["isagent"] == 1 && $_var_14["status"] == 1) { $_var_79[] = $_var_14; } } } } } return $_var_79; }
	public function isAgent($_var_20) { if (empty($_var_20)) { return false; } if (is_array($_var_20)) { return $_var_20["isagent"] == 1 && $_var_20["status"] == 1; } $_var_22 = m("member")->getMember($_var_20); return $_var_22["isagent"] == 1 && $_var_22["status"] == 1; }
	public function getCommission($_var_5) { global $_W; $_var_0 = $this->getSet(); $_var_60 = 0; if ($_var_5["hascommission"] == 1) { $_var_60 = $_var_0["level"] >= 1 ? ($_var_5["commission1_rate"] > 0 ? ($_var_5["commission1_rate"] * $_var_5["marketprice"] / 100) : $_var_5["commission1_pay"]) : 0; } else { $_var_20 = m("user")->getOpenid(); $_var_8 = $this->getLevel($_var_20); if (!empty($_var_8)) { $_var_60 = $_var_0["level"] >= 1 ? round($_var_8["commission1"] * $_var_5["marketprice"] / 100, 2) : 0; } else { $_var_60 = $_var_0["level"] >= 1 ? round($_var_0["commission1"] * $_var_5["marketprice"] / 100, 2) : 0; } } return $_var_60; }
	public function createMyShopQrcode($_var_81 = 0, $_var_82 = 0) { global $_W; $_var_83 = IA_ROOT . "/addons/sz_yi/data/qrcode/" . $_W["uniacid"]; if (!is_dir($_var_83)) { load()->func("file"); mkdirs($_var_83); } $_var_84 = $_W["siteroot"] . "app/index.php?i=" . $_W["uniacid"] . "&c=entry&m=sz_yi&do=plugin&p=commission&method=myshop&mid=" . $_var_81; if (!empty($_var_82)) { $_var_84 .= "&posterid=" . $_var_82; } $_var_85 = "myshop_" . $_var_82 . "_" . $_var_81 . ".png"; $_var_86 = $_var_83 . "/" . $_var_85; if (!is_file($_var_86)) { require IA_ROOT . "/framework/library/qrcode/phpqrcode.php"; QRcode::png($_var_84, $_var_86, QR_ECLEVEL_H, 4); } return $_W["siteroot"] . "addons/sz_yi/data/qrcode/" . $_W["uniacid"] . "/" . $_var_85; } private function createImage($_var_84) { load()->func("communication"); $_var_87 = ihttp_request($_var_84); return imagecreatefromstring($_var_87["content"]); }
	public function createGoodsImage($_var_5, $_var_88) { global $_W, $_GPC; $_var_5 = set_medias($_var_5, "thumb"); $_var_20 = m("user")->getOpenid(); $_var_89 = m("member")->getMember($_var_20); if ($_var_89["isagent"] == 1 && $_var_89["status"] == 1) { $_var_90 = $_var_89; } else { $_var_81 = intval($_GPC["mid"]); if (!empty($_var_81)) { $_var_90 = m("member")->getMember($_var_81); } } $_var_83 = IA_ROOT . "/addons/sz_yi/data/poster/" . $_W["uniacid"] . "/"; if (!is_dir($_var_83)) { load()->func("file"); mkdirs($_var_83); } $_var_91 = empty($_var_5["commission_thumb"]) ? $_var_5["thumb"] : tomedia($_var_5["commission_thumb"]); $_var_92 = md5(json_encode(array("id" => $_var_5["id"], "marketprice" => $_var_5["marketprice"], "productprice" => $_var_5["productprice"], "img" => $_var_91, "openid" => $_var_20, "version" => 4))); $_var_85 = $_var_92 . ".jpg"; if (!is_file($_var_83 . $_var_85)) { set_time_limit(0); $_var_93 = IA_ROOT . "/addons/sz_yi/static/fonts/msyh.ttf"; $_var_94 = imagecreatetruecolor(640, 1225); $_var_95 = imagecreatefromjpeg(IA_ROOT . "/addons/sz_yi/plugin/commission/images/poster.jpg"); imagecopy($_var_94, $_var_95, 0, 0, 0, 0, 640, 1225); imagedestroy($_var_95); $_var_96 = preg_replace("/\\/0\$/i", "/96", $_var_90["avatar"]); $_var_97 = $this->createImage($_var_96); $_var_98 = imagesx($_var_97); $_var_99 = imagesy($_var_97); imagecopyresized($_var_94, $_var_97, 24, 32, 0, 0, 88, 88, $_var_98, $_var_99); imagedestroy($_var_97); $_var_100 = $this->createImage($_var_91); $_var_98 = imagesx($_var_100); $_var_99 = imagesy($_var_100); imagecopyresized($_var_94, $_var_100, 0, 160, 0, 0, 640, 640, $_var_98, $_var_99); imagedestroy($_var_100); $_var_101 = imagecreatetruecolor(640, 127); imagealphablending($_var_101, false); imagesavealpha($_var_101, true); $_var_102 = imagecolorallocatealpha($_var_101, 0, 0, 0, 25); imagefill($_var_101, 0, 0, $_var_102); imagecopy($_var_94, $_var_101, 0, 678, 0, 0, 640, 127); imagedestroy($_var_101); $_var_103 = tomedia(m("qrcode")->createGoodsQrcode($_var_90["id"], $_var_5["id"])); $_var_104 = $this->createImage($_var_103); $_var_98 = imagesx($_var_104); $_var_99 = imagesy($_var_104); imagecopyresized($_var_94, $_var_104, 50, 835, 0, 0, 250, 250, $_var_98, $_var_99); imagedestroy($_var_104); $_var_105 = imagecolorallocate($_var_94, 0, 3, 51); $_var_106 = imagecolorallocate($_var_94, 240, 102, 0); $_var_107 = imagecolorallocate($_var_94, 255, 255, 255); $_var_108 = imagecolorallocate($_var_94, 255, 255, 0); $_var_109 = "我是"; imagettftext($_var_94, 20, 0, 150, 70, $_var_105, $_var_93, $_var_109); imagettftext($_var_94, 20, 0, 210, 70, $_var_106, $_var_93, $_var_90["nickname"]); $_var_110 = "我要为"; imagettftext($_var_94, 20, 0, 150, 105, $_var_105, $_var_93, $_var_110); $_var_111 = $_var_88["name"]; imagettftext($_var_94, 20, 0, 240, 105, $_var_106, $_var_93, $_var_111); $_var_112 = imagettfbbox(20, 0, $_var_93, $_var_111); $_var_113 = $_var_112[4] - $_var_112[6]; $_var_114 = "代言"; imagettftext($_var_94, 20, 0, 240 + $_var_113 + 10, 105, $_var_105, $_var_93, $_var_114); $_var_115 = mb_substr($_var_5["title"], 0, 50, "utf-8"); imagettftext($_var_94, 20, 0, 30, 730, $_var_107, $_var_93, $_var_115); $_var_116 = "￥" . number_format($_var_5["marketprice"], 2); imagettftext($_var_94, 25, 0, 25, 780, $_var_108, $_var_93, $_var_116); $_var_112 = imagettfbbox(26, 0, $_var_93, $_var_116); $_var_113 = $_var_112[4] - $_var_112[6]; if ($_var_5["productprice"] > 0) { $_var_117 = "￥" . number_format($_var_5["productprice"], 2); imagettftext($_var_94, 22, 0, 25 + $_var_113 + 10, 780, $_var_107, $_var_93, $_var_117); $_var_118 = 25 + $_var_113 + 10; $_var_112 = imagettfbbox(22, 0, $_var_93, $_var_117); $_var_113 = $_var_112[4] - $_var_112[6]; imageline($_var_94, $_var_118, 770, $_var_118 + $_var_113 + 20, 770, $_var_107); imageline($_var_94, $_var_118, 771.5, $_var_118 + $_var_113 + 20, 771, $_var_107); } imagejpeg($_var_94, $_var_83 . $_var_85); imagedestroy($_var_94); } return $_W["siteroot"] . "addons/sz_yi/data/poster/" . $_W["uniacid"] . "/" . $_var_85; }
	public function createShopImage($_var_88) { global $_W, $_GPC; $_var_88 = set_medias($_var_88, "signimg"); $_var_83 = IA_ROOT . "/addons/sz_yi/data/poster/" . $_W["uniacid"] . "/"; if (!is_dir($_var_83)) { load()->func("file"); mkdirs($_var_83); } $_var_81 = intval($_GPC["mid"]); $_var_20 = m("user")->getOpenid(); $_var_89 = m("member")->getMember($_var_20); if ($_var_89["isagent"] == 1 && $_var_89["status"] == 1) { $_var_90 = $_var_89; } else { $_var_81 = intval($_GPC["mid"]); if (!empty($_var_81)) { $_var_90 = m("member")->getMember($_var_81); } } $_var_92 = md5(json_encode(array("openid" => $_var_20, "signimg" => $_var_88["signimg"], "version" => 4))); $_var_85 = $_var_92 . ".jpg"; if (!is_file($_var_83 . $_var_85)) { set_time_limit(0); @ini_set("memory_limit", "256M"); $_var_93 = IA_ROOT . "/addons/sz_yi/static/fonts/msyh.ttf"; $_var_94 = imagecreatetruecolor(640, 1225); $_var_105 = imagecolorallocate($_var_94, 0, 3, 51); $_var_106 = imagecolorallocate($_var_94, 240, 102, 0); $_var_107 = imagecolorallocate($_var_94, 255, 255, 255); $_var_108 = imagecolorallocate($_var_94, 255, 255, 0); $_var_95 = imagecreatefromjpeg(IA_ROOT . "/addons/sz_yi/plugin/commission/images/poster.jpg"); imagecopy($_var_94, $_var_95, 0, 0, 0, 0, 640, 1225); imagedestroy($_var_95); $_var_96 = preg_replace("/\\/0\$/i", "/96", $_var_90["avatar"]); $_var_97 = $this->createImage($_var_96); $_var_98 = imagesx($_var_97); $_var_99 = imagesy($_var_97); imagecopyresized($_var_94, $_var_97, 24, 32, 0, 0, 88, 88, $_var_98, $_var_99); imagedestroy($_var_97); $_var_100 = $this->createImage($_var_88["signimg"]); $_var_98 = imagesx($_var_100); $_var_99 = imagesy($_var_100); imagecopyresized($_var_94, $_var_100, 0, 160, 0, 0, 640, 640, $_var_98, $_var_99); imagedestroy($_var_100); $_var_119 = tomedia($this->createMyShopQrcode($_var_90["id"])); $_var_104 = $this->createImage($_var_119); $_var_98 = imagesx($_var_104); $_var_99 = imagesy($_var_104); imagecopyresized($_var_94, $_var_104, 50, 835, 0, 0, 250, 250, $_var_98, $_var_99); imagedestroy($_var_104); $_var_109 = "我是"; imagettftext($_var_94, 20, 0, 150, 70, $_var_105, $_var_93, $_var_109); imagettftext($_var_94, 20, 0, 210, 70, $_var_106, $_var_93, $_var_90["nickname"]); $_var_110 = "我要为"; imagettftext($_var_94, 20, 0, 150, 105, $_var_105, $_var_93, $_var_110); $_var_111 = $_var_88["name"]; imagettftext($_var_94, 20, 0, 240, 105, $_var_106, $_var_93, $_var_111); $_var_112 = imagettfbbox(20, 0, $_var_93, $_var_111); $_var_113 = $_var_112[4] - $_var_112[6]; $_var_114 = "代言"; imagettftext($_var_94, 20, 0, 240 + $_var_113 + 10, 105, $_var_105, $_var_93, $_var_114); imagejpeg($_var_94, $_var_83 . $_var_85); imagedestroy($_var_94); } return $_W["siteroot"] . "addons/sz_yi/data/poster/" . $_W["uniacid"] . "/" . $_var_85; }
	public function checkAgent() { global $_W, $_GPC; $_var_0 = $this->getSet(); if (empty($_var_0["level"])) { return; } $_var_20 = m("user")->getOpenid(); if (empty($_var_20)) { return; } $_var_22 = m("member")->getMember($_var_20); if (empty($_var_22)) { return; } $_var_120 = false; $_var_81 = intval($_GPC["mid"]); if (!empty($_var_81)) { $_var_120 = m("member")->getMember($_var_81); } $_var_121 = !empty($_var_120) && $_var_120["isagent"] == 1 && $_var_120["status"] == 1; if ($_var_121) { if ($_var_120["openid"] != $_var_20) { $_var_122 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_commission_clickcount") . " where uniacid=:uniacid and openid=:openid and from_openid=:from_openid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_20, ":from_openid" => $_var_120["openid"])); if ($_var_122 <= 0) { $_var_123 = array("uniacid" => $_W["uniacid"], "openid" => $_var_20, "from_openid" => $_var_120["openid"], "clicktime" => time()); pdo_insert("sz_yi_commission_clickcount", $_var_123); pdo_update("sz_yi_member", array("clickcount" => $_var_120["clickcount"] + 1), array("uniacid" => $_W["uniacid"], "id" => $_var_120["id"])); } } } if ($_var_22["isagent"] == 1) { return; } if ($_var_124 == 0) { $_var_125 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_member") . " where id<:id and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":id" => $_var_22["id"])); if ($_var_125 <= 0) { pdo_update("sz_yi_member", array("isagent" => 1, "status" => 1, "agenttime" => time(), "agentblack" => 0), array("uniacid" => $_W["uniacid"], "id" => $_var_22["id"])); return; } } $_var_24 = time(); $_var_126 = intval($_var_0["become_child"]); if ($_var_121 && empty($_var_22["agentid"])) { if ($_var_22["id"] != $_var_120["id"]) { if (empty($_var_126)) { if (empty($_var_22["fixagentid"])) { pdo_update("sz_yi_member", array("agentid" => $_var_120["id"], "childtime" => $_var_24), array("uniacid" => $_W["uniacid"], "id" => $_var_22["id"])); $this->sendMessage($_var_120["openid"], array("nickname" => $_var_22["nickname"], "childtime" => $_var_24), TM_COMMISSION_AGENT_NEW); $this->upgradeLevelByAgent($_var_120["id"]); } } else { pdo_update("sz_yi_member", array("inviter" => $_var_120["id"]), array("uniacid" => $_W["uniacid"], "id" => $_var_22["id"])); } } } $_var_127 = intval($_var_0["become_check"]); if (empty($_var_0["become"])) { if (empty($_var_22["agentblack"])) { pdo_update("sz_yi_member", array("isagent" => 1, "status" => $_var_127, "agenttime" => $_var_127 == 1 ? $_var_24 : 0), array("uniacid" => $_W["uniacid"], "id" => $_var_22["id"])); if ($_var_127 == 1) { $this->sendMessage($_var_20, array("nickname" => $_var_22["nickname"], "agenttime" => $_var_24), TM_COMMISSION_BECOME); if ($_var_121) { $this->upgradeLevelByAgent($_var_120["id"]); } } } } }
	public function checkOrderConfirm($_var_128 = '0') { global $_W, $_GPC; if (empty($_var_128)) { return; } $_var_129 = $this->getSet(); if (empty($_var_129["level"])) { return; } $_var_130 = pdo_fetch("select id,openid,ordersn,goodsprice,agentid,paytime from " . tablename("sz_yi_order") . " where id=:id and status>=0 and uniacid=:uniacid limit 1", array(":id" => $_var_128, ":uniacid" => $_W["uniacid"])); if (empty($_var_130)) { return; } $_var_131 = $_var_130["openid"]; $_var_132 = m("member")->getMember($_var_131); if (empty($_var_132)) { return; } $_var_133 = p("bonus"); if(!empty($_var_133)){ $_var_134 = $_var_133->getSet(); if(!empty($_var_134["start"])){ $_var_133->checkOrderConfirm($_var_128); } } $_var_135 = intval($_var_129["become_child"]); $_var_136 = false; if (empty($_var_135)) { $_var_136 = m("member")->getMember($_var_132["agentid"]); } else { $_var_136 = m("member")->getMember($_var_132["inviter"]); } $_var_137 = !empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1; $_var_138 = time(); $_var_135 = intval($_var_129["become_child"]); if ($_var_137) { if ($_var_135 == 1) { if (empty($_var_132["agentid"]) && $_var_132["id"] != $_var_136["id"]) { if (empty($_var_132["fixagentid"])) { $_var_132["agentid"] = $_var_136["id"]; pdo_update("sz_yi_member", array("agentid" => $_var_136["id"], "childtime" => $_var_138), array("uniacid" => $_W["uniacid"], "id" => $_var_132["id"])); $this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "childtime" => $_var_138), TM_COMMISSION_AGENT_NEW); $this->upgradeLevelByAgent($_var_136["id"]); } } } } $_var_139 = $_var_132["agentid"]; if ($_var_132["isagent"] == 1 && $_var_132["status"] == 1) { if (!empty($_var_129["selfbuy"])) { $_var_139 = $_var_132["id"]; } } if (!empty($_var_139)) { pdo_update("sz_yi_order", array("agentid" => $_var_139), array("id" => $_var_128)); } $this->calculate($_var_128); }
	public function checkOrderPay($_var_128 = '0') { global $_W, $_GPC; if (empty($_var_128)) { return; } $_var_129 = $this->getSet(); if (empty($_var_129["level"])) { return; } $_var_130 = pdo_fetch("select id,openid,ordersn,goodsprice,agentid,paytime from " . tablename("sz_yi_order") . " where id=:id and status>=1 and uniacid=:uniacid limit 1", array(":id" => $_var_128, ":uniacid" => $_W["uniacid"])); if (empty($_var_130)) { return; } $_var_131 = $_var_130["openid"]; $_var_132 = m("member")->getMember($_var_131); if (empty($_var_132)) { return; } $_var_133 = p("bonus"); if(!empty($_var_133)){ $_var_134 = $_var_133->getSet(); if(!empty($_var_134["start"])){ $_var_133->checkOrderPay($_var_128); } } $_var_135 = intval($_var_129["become_child"]); $_var_136 = false; if (empty($_var_135)) { $_var_136 = m("member")->getMember($_var_132["agentid"]); } else { $_var_136 = m("member")->getMember($_var_132["inviter"]); } $_var_137 = !empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1; $_var_138 = time(); $_var_135 = intval($_var_129["become_child"]); if ($_var_137) { if ($_var_135 == 2) { if (empty($_var_132["agentid"]) && $_var_132["id"] != $_var_136["id"]) { if (empty($_var_132["fixagentid"])) { $_var_132["agentid"] = $_var_136["id"]; pdo_update("sz_yi_member", array("agentid" => $_var_136["id"], "childtime" => $_var_138), array("uniacid" => $_W["uniacid"], "id" => $_var_132["id"])); $this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "childtime" => $_var_138), TM_COMMISSION_AGENT_NEW); $this->upgradeLevelByAgent($_var_136["id"]); if (empty($_var_130["agentid"])) { $_var_130["agentid"] = $_var_136["id"]; pdo_update("sz_yi_order", array("agentid" => $_var_136["id"]), array("id" => $_var_128)); $this->calculate($_var_128); } } } } } $_var_140 = $_var_132["isagent"] == 1 && $_var_132["status"] == 1; if (!$_var_140) { if (intval($_var_129["become"]) == 4 && !empty($_var_129["become_goodsid"])) { $_var_141 = pdo_fetchall("select goodsid from " . tablename("sz_yi_order_goods") . " where orderid=:orderid and uniacid=:uniacid  ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"]), "goodsid"); if (in_array($_var_129["become_goodsid"], array_keys($_var_141))) { if (empty($_var_132["agentblack"])) { pdo_update("sz_yi_member", array("status" => 1, "isagent" => 1, "agenttime" => $_var_138), array("uniacid" => $_W["uniacid"], "id" => $_var_132["id"])); $this->sendMessage($_var_131, array("nickname" => $_var_132["nickname"], "agenttime" => $_var_138), TM_COMMISSION_BECOME); if (!empty($_var_136)) { $this->upgradeLevelByAgent($_var_136["id"]); } } } } } if (!$_var_140 && empty($_var_129["become_order"])) { $_var_138 = time(); if ($_var_129["become"] == 2 || $_var_129["become"] == 3) { $_var_142 = true; if (!empty($_var_132["agentid"])) { $_var_136 = m("member")->getMember($_var_132["agentid"]); if (empty($_var_136) || $_var_136["isagent"] != 1 || $_var_136["status"] != 1) { $_var_142 = false; } } if ($_var_142) { $_var_143 = false; if ($_var_129["become"] == "2") { $_var_144 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_order") . " where openid=:openid and status>=1 and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_131)); $_var_143 = $_var_144 >= intval($_var_129["become_ordercount"]); } else if ($_var_129["become"] == "3") { $_var_145 = pdo_fetchcolumn("select sum(og.realprice) from " . tablename("sz_yi_order_goods") . " og left join " . tablename("sz_yi_order") . " o on og.orderid=o.id  where o.openid=:openid and o.status>=1 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_131)); $_var_143 = $_var_145 >= floatval($_var_129["become_moneycount"]); } if ($_var_143) { if (empty($_var_132["agentblack"])) { $_var_146 = intval($_var_129["become_check"]); pdo_update("sz_yi_member", array("status" => $_var_146, "isagent" => 1, "agenttime" => $_var_138), array("uniacid" => $_W["uniacid"], "id" => $_var_132["id"])); if ($_var_146 == 1) { $this->sendMessage($_var_131, array("nickname" => $_var_132["nickname"], "agenttime" => $_var_138), TM_COMMISSION_BECOME); if ($_var_142) { $this->upgradeLevelByAgent($_var_136["id"]); } } } } } } } if (!empty($_var_130["agentid"])) { $_var_136 = m("member")->getMember($_var_132["agentid"]); if (!empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1) { if ($_var_130["agentid"] == $_var_136["id"]) { $_var_147 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"])); $_var_148 = ''; $_var_149 = $_var_136["agentlevel"]; $_var_150 = 0; $_var_151 = 0; foreach ($_var_147 as $_var_152) { $_var_148 .= "" . $_var_152["title"] . "( "; if (!empty($_var_152["optiontitle"])) { $_var_148 .= " 规格: " . $_var_152["optiontitle"]; } $_var_148 .= " 单价: " . ($_var_152["realprice"] / $_var_152["total"]) . " 数量: " . $_var_152["total"] . " 总价: " . $_var_152["realprice"] . "); "; $_var_153 = iunserializer($_var_152["commission1"]); $_var_150 += isset($_var_153["level" . $_var_149]) ? $_var_153["level" . $_var_149] : $_var_153["default"]; $_var_151 += $_var_152["realprice"]; } $this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "ordersn" => $_var_130["ordersn"], "price" => $_var_151, "goods" => $_var_148, "commission" => $_var_150, "paytime" => $_var_130["paytime"],), TM_COMMISSION_ORDER_PAY); } } if(!empty($_var_129["remind_message"])){ if (!empty($_var_136["agentid"])) { $_var_136 = m("member")->getMember($_var_136["agentid"]); if (!empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1) { if ($_var_130["agentid"] != $_var_136["id"]) { $_var_147 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission2 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"])); $_var_148 = ''; $_var_149 = $_var_136["agentlevel"]; $_var_150 = 0; $_var_151 = 0; foreach ($_var_147 as $_var_152) { $_var_148 .= "" . $_var_152["title"] . "( "; if (!empty($_var_152["optiontitle"])) { $_var_148 .= " 规格: " . $_var_152["optiontitle"]; } $_var_148 .= " 单价: " . ($_var_152["realprice"] / $_var_152["total"]) . " 数量: " . $_var_152["total"] . " 总价: " . $_var_152["realprice"] . "); "; $_var_153 = iunserializer($_var_152["commission2"]); $_var_150 += isset($_var_153["level" . $_var_149]) ? $_var_153["level" . $_var_149] : $_var_153["default"]; $_var_151 += $_var_152["realprice"]; } $this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "ordersn" => $_var_130["ordersn"], "price" => $_var_151, "goods" => $_var_148, "commission" => $_var_150, "paytime" => $_var_130["paytime"],), TM_COMMISSION_ORDER_PAY); } } if (!empty($_var_136["agentid"])) { $_var_136 = m("member")->getMember($_var_136["agentid"]); if (!empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1) { if ($_var_130["agentid"] != $_var_136["id"]) { $_var_147 = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission3 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"])); $_var_148 = ''; $_var_149 = $_var_136["agentlevel"]; $_var_150 = 0; $_var_151 = 0; foreach ($_var_147 as $_var_152) { $_var_148 .= "" . $_var_152["title"] . "( "; if (!empty($_var_152["optiontitle"])) { $_var_148 .= " 规格: " . $_var_152["optiontitle"]; } $_var_148 .= " 单价: " . ($_var_152["realprice"] / $_var_152["total"]) . " 数量: " . $_var_152["total"] . " 总价: " . $_var_152["realprice"] . "); "; $_var_153 = iunserializer($_var_152["commission3"]); $_var_150 += isset($_var_153["level" . $_var_149]) ? $_var_153["level" . $_var_149] : $_var_153["default"]; $_var_151 += $_var_152["realprice"]; } $this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "ordersn" => $_var_130["ordersn"], "price" => $_var_151, "goods" => $_var_148, "commission" => $_var_150, "paytime" => $_var_130["paytime"],), TM_COMMISSION_ORDER_PAY); } } } } } } }
public function checkOrderFinish($_var_128 = '') {
	global $_W, $_GPC;
	if (empty($_var_128)) {
		return;
	}
	$_var_130 = pdo_fetch("select id,openid, ordersn,goodsprice,agentid,finishtime from " . tablename("sz_yi_order") . " where id=:id and status>=3 and uniacid=:uniacid limit 1", array(":id" => $_var_128, ":uniacid" => $_W["uniacid"]));
	if (empty($_var_130)) { return; }
	$_var_129 = $this->getSet();
	if (empty($_var_129["level"])) { return; }
	$_var_131 = $_var_130["openid"];
	$_var_132 = m("member")->getMember($_var_131);
	if (empty($_var_132)) { return; }
	$_var_133 = p("bonus");
	if(!empty($_var_133)){
		$_var_134 = $_var_133->getSet();
		if(!empty($_var_134["start"])){
			$_var_133->checkOrderFinish($_var_128);
		}
	}
	$pluginbonusplus = p("bonusplus"); 
	if(!empty($pluginbonusplus))
	{
		$bonusplus_set = $pluginbonusplus->getSet();
		if(!empty($bonusplus_set["start"])){
			$pluginbonusplus->checkOrderFinish($_var_128);
		}
	}
	$_var_138 = time();
	$_var_140 = $_var_132["isagent"] == 1 && $_var_132["status"] == 1;
	if (!$_var_140 && $_var_129["become_order"] == 1) {
		if ($_var_129["become"] == 2 || $_var_129["become"] == 3) {
			$_var_142 = true; if (!empty($_var_132["agentid"])) {
				$_var_136 = m("member")->getMember($_var_132["agentid"]);
				if (empty($_var_136) || $_var_136["isagent"] != 1 || $_var_136["status"] != 1) {
					$_var_142 = false;
				}
			}
			if ($_var_142) {
				$_var_143 = false;
				if ($_var_129["become"] == "2") {
					$_var_144 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_order") . " where openid=:openid and status>=3 and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_131));
					$_var_143 = $_var_144 >= intval($_var_129["become_ordercount"]);
				}
				else if ($_var_129["become"] == "3") {
					$_var_145 = pdo_fetchcolumn("select sum(goodsprice) from " . tablename("sz_yi_order") . " where openid=:openid and status>=3 and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $_var_131));
					$_var_143 = $_var_145 >= floatval($_var_129["become_moneycount"]);
				}
				if ($_var_143) {
					if (empty($_var_132["agentblack"])) {
						$_var_146 = intval($_var_129["become_check"]);
						pdo_update("sz_yi_member", array("status" => $_var_146, "isagent" => 1, "agenttime" => $_var_138), array("uniacid" => $_W["uniacid"], "id" => $_var_132["id"]));
						if ($_var_146 == 1) {
							$this->sendMessage($_var_132["openid"], array("nickname" => $_var_132["nickname"], "agenttime" => $_var_138), TM_COMMISSION_BECOME);
							if ($_var_142) {
								$this->upgradeLevelByAgent($_var_136["id"]);
							}
						}
					}
				}
			}
		}
	}
	if (!empty($_var_130["agentid"])) {
		$_var_136 = m("member")->getMember($_var_132["agentid"]);
		if (!empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1) {
			if ($_var_130["agentid"] == $_var_136["id"]) {
				$_var_147 = pdo_fetchall('select g.id,g.title,og.total,og.realprice,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"]));
				$_var_148 = '';
				$_var_149 = $_var_136["agentlevel"];
				$_var_150 = 0;
				$_var_151 = 0;
				foreach ($_var_147 as $_var_152) {
					$_var_148 .= "" . $_var_152["title"] . "( ";
					if (!empty($_var_152["optiontitle"])) {
						$_var_148 .= " 规格: " . $_var_152["optiontitle"];
					}
					$_var_148 .= " 单价: " . ($_var_152["realprice"] / $_var_152["total"]) . " 数量: " . $_var_152["total"] . " 总价: " . $_var_152["realprice"] . "); ";
					$_var_153 = iunserializer($_var_152["commission1"]);
					$_var_150 += isset($_var_153["level" . $_var_149]) ? $_var_153["level" . $_var_149] : $_var_153["default"];
					$_var_151 += $_var_152["realprice"];
				}
				$this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "ordersn" => $_var_130["ordersn"], "price" => $_var_151, "goods" => $_var_148, "commission" => $_var_150, "finishtime" => $_var_130["finishtime"],), TM_COMMISSION_ORDER_FINISH);
			}
		}
		if(!empty($_var_129["remind_message"])){
			if (!empty($_var_136["agentid"])) {
				$_var_136 = m("member")->getMember($_var_136["agentid"]);
				if (!empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1) {
					if ($_var_130["agentid"] != $_var_136["id"]) {
						$_var_147 = pdo_fetchall('select g.id,g.title,og.total,og.realprice,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission2 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"]));
						$_var_148 = '';
						$_var_149 = $_var_136["agentlevel"];
						$_var_150 = 0;
						$_var_151 = 0;
						foreach ($_var_147 as $_var_152) {
							$_var_148 .= "" . $_var_152["title"] . "( ";
							if (!empty($_var_152["optiontitle"])) {
								$_var_148 .= " 规格: " . $_var_152["optiontitle"];
							}
							$_var_148 .= " 单价: " . ($_var_152["realprice"] / $_var_152["total"]) . " 数量: " . $_var_152["total"] . " 总价: " . $_var_152["realprice"] . "); ";
							$_var_153 = iunserializer($_var_152["commission2"]);
							$_var_150 += isset($_var_153["level" . $_var_149]) ? $_var_153["level" . $_var_149] : $_var_153["default"];
							$_var_151 += $_var_152["realprice"]; } $this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "ordersn" => $_var_130["ordersn"], "price" => $_var_151, "goods" => $_var_148, "commission" => $_var_150, "finishtime" => $_var_130["finishtime"],), TM_COMMISSION_ORDER_FINISH);
						}
					}
					if (!empty($_var_136["agentid"])) {
						$_var_136 = m("member")->getMember($_var_136["agentid"]);
						if (!empty($_var_136) && $_var_136["isagent"] == 1 && $_var_136["status"] == 1) {
							if ($_var_130["agentid"] != $_var_136["id"]) {
								$_var_147 = pdo_fetchall('select g.id,g.title,og.total,og.realprice,og.price,og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission3 from ' . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array(":uniacid" => $_W["uniacid"], ":orderid" => $_var_130["id"]));
								$_var_148 = '';
								$_var_149 = $_var_136["agentlevel"];
								$_var_150 = 0;
								$_var_151 = 0;
								foreach ($_var_147 as $_var_152) {
									$_var_148 .= "" . $_var_152["title"] . "( ";
									if (!empty($_var_152["optiontitle"])) {
										$_var_148 .= " 规格: " . $_var_152["optiontitle"];
									}
									$_var_148 .= " 单价: " . ($_var_152["realprice"] / $_var_152["total"]) . " 数量: " . $_var_152["total"] . " 总价: " . $_var_152["realprice"] . "); ";
									$_var_153 = iunserializer($_var_152["commission3"]); $_var_150 += isset($_var_153["level" . $_var_149]) ? $_var_153["level" . $_var_149] : $_var_153["default"];
									$_var_151 += $_var_152["realprice"];
								}
								$this->sendMessage($_var_136["openid"], array("nickname" => $_var_132["nickname"], "ordersn" => $_var_130["ordersn"], "price" => $_var_151, "goods" => $_var_148, "commission" => $_var_150, "finishtime" => $_var_130["finishtime"],), TM_COMMISSION_ORDER_FINISH);
							}
						}
					}
				}
		}
	}
	$this->upgradeLevelByOrder($_var_131);
	$this->upgradeLevelByGood($_var_128);
} 



function getShop($_var_154) {

	global $_W; 

	$_var_22 = m("member")->getMember($_var_154); 

	$_var_155 = pdo_fetch("select * from " . tablename("sz_yi_commission_shop") . " where uniacid=:uniacid and mid=:mid limit 1", array(":uniacid" => $_W["uniacid"], ":mid" => $_var_22["id"])); 

	$_var_156 = m("common")->getSysset(array("shop", "share")); 

	$_var_0 = $_var_156["shop"]; 

	$_var_157 = $_var_156["share"]; 

	$_var_158 = $_var_157["desc"]; 

	if (empty($_var_158)) { 

		$_var_158 = $_var_0["description"]; 

	} 

	if (empty($_var_158)) { 

		$_var_158 = $_var_0["name"]; 

	} 

	$_var_159 = $this->getSet(); 

	if (empty($_var_155)) { 

		$_var_155 = array(

			"name" => $_var_22["nickname"] . "的" . $_var_159["texts"]["shop"], 

			"logo" => $_var_22["avatar"], "desc" => $_var_158, 

			"img" => tomedia($_var_0["img"]),); 

	} else { 

		if (empty($_var_155["name"])) { 

			$_var_155["name"] = $_var_22["nickname"] . "的" . $_var_159["texts"]["shop"]; } 

		if (empty($_var_155["logo"])) { 

			$_var_155["logo"] = tomedia($_var_22["avatar"]); 

		} 

		if (empty($_var_155["img"])) { 

			$_var_155["img"] = tomedia($_var_0["img"]); 

		} 

		if (empty($_var_155["desc"])) { 

			$_var_155["desc"] = $_var_158; 

		} 

	} 

	return $_var_155; 

		

} 

		

		

		

function getLevels($_var_160 = true) { global $_W; if ($_var_160) { return pdo_fetchall("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid order by commission1 asc", array(":uniacid" => $_W["uniacid"])); } else { return pdo_fetchall("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid and (ordermoney>0 or commissionmoney>0) order by commission1 asc", array(":uniacid" => $_W["uniacid"])); } }
function getLevel($_var_20) { global $_W; if (empty($_var_20)) { return false; } $_var_22 = m("member")->getMember($_var_20); if (empty($_var_22["agentlevel"])) { return false; } $_var_8 = pdo_fetch("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid and id=:id limit 1", array(":uniacid" => $_W["uniacid"], ":id" => $_var_22["agentlevel"])); return $_var_8; }
function upgradeLevelByOrder($openid) {
	global $_W;
	if (empty($openid)) { return false; }
	$set = $this->getSet();
	if (empty($set["level"])) { return false; }
	$member = m("member")->getMember($openid);
	if (empty($member)) { return; } 
	$pluginbonus = p("bonus"); 
	if(!empty($pluginbonus))
	{
		$bonus_set = $pluginbonus->getSet();
		if(!empty($bonus_set["start"])){
			$pluginbonus->upgradeLevelByAgent($openid);
		}
	}
	$pluginbonusplus = p("bonusplus"); 
	if(!empty($pluginbonusplus))
	{
		$bonusplus_set = $pluginbonusplus->getSet();
		if(!empty($bonusplus_set["start"])){
			$pluginbonusplus->upgradeLevelByAgent($openid);
		}
	}
	/* 满足条件 */
	if (!empty($member["agentnotupgrade"])) { return; }
	$oldlevel = $this->getLevel($member["openid"]);
	if (empty($oldlevel["id"])) {
		$oldlevel = array("levelname" => empty($set["levelname"]) ? "普通等级" : $set["levelname"], "commission1" => $set["commission1"], "commission2" => $set["commission2"], "commission3" => $set["commission3"]);
	}
	$uperlevel  = pdo_fetchall("select * from " . tablename('sz_yi_commission_level') . ' where weight>:weight and uniacid=:uniacid order by weight desc', array(
		':weight' => $oldlevel['weight'],
		':uniacid' => $_W['uniacid']
	));
	foreach($uperlevel as $curlevel) {
		$levelok = 0;
		$levelset = @json_decode($curlevel['updatelevel'], true);
		
		$_var_79 = array();
		if (!empty($set["selfbuy"])) {
			$_var_79[] = $member;
		}
		if (!empty($member["agentid"])) {
			$_var_10 = m("member")->getMember($member["agentid"]);
			if (!empty($_var_10)) {
				$_var_79[] = $_var_10;
				if (!empty($_var_10["agentid"]) && $_var_10["isagent"] == 1 && $_var_10["status"] == 1) {
					$_var_12 = m("member")->getMember($_var_10["agentid"]);
					if (!empty($_var_12) && $_var_12["isagent"] == 1 && $_var_12["status"] == 1) {
						$_var_79[] = $_var_12;
						if (empty($set["selfbuy"])) {
							if (!empty($_var_12["agentid"]) && $_var_12["isagent"] == 1 && $_var_12["status"] == 1) {
								$_var_14 = m("member")->getMember($_var_12["agentid"]);
								if (!empty($_var_14) && $_var_14["isagent"] == 1 && $_var_14["status"] == 1) {
									$_var_79[] = $_var_14;
								}
							}
						}
					}
				}
			}
		}
		$tiaojian1 = false; //分销订单满
		$tiaojian2 = false; //一级分销满
		$tiaojian3 = false; //分销订单数
		$tiaojian4 = false; //一级分销订单总数
		$tiaojian5 = false; //自购订单金额
		$tiaojian6 = false; //自购订单数量(
		$tiaojian7 = false; //下线总人数
		$tiaojian8 = false; 
		$tiaojian9 = false; 
		$tiaojian10 = false; 
		$tiaojian11 = false; 
		$tiaojian12 = false; 
		foreach ($_var_79 as $_var_165) {
			$_var_166 = $this->getInfo($_var_165["id"], array("ordercount3", "ordermoney3", "order13money", "order13"));

			if(!empty($levelset[0])) {
				$_var_30 = $_var_166["ordermoney3"];
				if($_var_30 >= $levelset[0])
				{
					$tiaojian1 = true;
				}
			}else{
				$tiaojian1 = true;
			}
			if(!empty($levelset[1])) {
				$_var_30 = $_var_166["order13money"];
				if($_var_30 >= $levelset[1])
				{
					$tiaojian2 = true;
				}
			}else{
				$tiaojian2 = true;
			}
			if(!empty($levelset[2])) {
				$_var_30 = $_var_166["ordercount3"];
				if($_var_30 >= $levelset[2])
				{
					$tiaojian3 = true;
				}
			}else{
				$tiaojian3 = true;
			}
			if(!empty($levelset[3])) {
				$_var_30 = $_var_166["order13"];
				if($_var_30 >= $levelset[3])
				{
					$tiaojian4 = true;
				}
			}else{
				$tiaojian4 = true;
			}
		}
		
		$_var_163 = pdo_fetch("select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from " . tablename("sz_yi_order") . " o " . " left join  " . tablename("sz_yi_order_goods") . " og on og.orderid=o.id " . " where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $openid));
		$_var_30 = $_var_163["ordermoney"];
		$_var_29 = $_var_163["ordercount"];
		if(!empty($levelset[4])) {
			if($_var_30 >= $levelset[4])
			{
				$tiaojian5 = true;
			}
		}else{
			$tiaojian5 = true;
		}
		if(!empty($levelset[5])) {
			if($_var_29 >= $levelset[5])
			{
				$tiaojian6 = true;
			}
		}else{
			$tiaojian6 = true;
		}
		

		$agentuser = array($member);
		if (!empty($member["agentid"])) {
			$_var_10 = m("member")->getMember($member["agentid"]);
			if (!empty($_var_10)) {
				$agentuser[] = $_var_10;
				if (!empty($_var_10["agentid"]) && $_var_10["isagent"] == 1 && $_var_10["status"] == 1) {
					$_var_12 = m("member")->getMember($_var_10["agentid"]);
					if (!empty($_var_12) && $_var_12["isagent"] == 1 && $_var_12["status"] == 1) {
						$agentuser[] = $_var_12;
					}
				}
			}
		}
		foreach ($agentuser as $_var_165) {
			$_var_166 = $this->getInfo($_var_165["id"], array());

			if(!empty($levelset[6])) {
				$_var_167 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid=:agentid and uniacid=:uniacid ", array(":agentid" => $member["id"], ":uniacid" => $_W["uniacid"]), "id");
				$_var_168 += count($_var_167);
				if (!empty($_var_167)) {
					$_var_169 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid in( " . implode(",", array_keys($_var_167)) . ") and uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]), "id");
					$_var_168 += count($_var_169);
					if (!empty($_var_169)) {
						$_var_170 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid in( " . implode(",", array_keys($_var_169)) . ") and uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]), "id");
						$_var_168 += count($_var_170);
					}
				}
				if($_var_168 >= $levelset[6])
				{
					$tiaojian7 = true;
				}
			}else{
				$tiaojian7 = true;
			}
			if(!empty($levelset[8])) {
				$_var_168 = $_var_166["level1"] + $_var_166["level2"] + $_var_166["level3"];
				if($_var_168 >= $levelset[8])
				{
					$tiaojian9 = true;
				}
			}else{
				$tiaojian9 = true;
			}
		}
		$_var_166 = $this->getInfo($member["id"], array());
		if(!empty($levelset[7])) {
			$_var_168 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_member") . " where agentid=:agentid and uniacid=:uniacid ", array(":agentid" => $member["id"], ":uniacid" => $_W["uniacid"]));
			if($_var_168 >= $levelset[7])
			{
				$tiaojian8 = true;
			}
		}else{
			$tiaojian8 = true;
		}
		if(!empty($levelset[9])) {
			$_var_168 = $_var_166["level1"];
			if($_var_168 >= $levelset[9])
			{
				$tiaojian10 = true;
			}
		}else{
			$tiaojian10 = true;
		}
		if(!empty($levelset[10])) {
			if($member['credit1'] >= $levelset[10])
			{
				$tiaojian11 = true;
			}
		}else{
			$tiaojian11 = true;
		}
		if(!empty($levelset[11]) && !empty($levelset[12])) {
			$_var_168 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_member") . " where agentlevel=:agentlevel and agentid=:agentid and uniacid=:uniacid ", array(":agentlevel" => $levelset[11], ":agentid" => $member["id"], ":uniacid" => $_W["uniacid"]));
			if($_var_168 >= $levelset[12])
			{
				$tiaojian12 = true;
			}
		}else{
			$tiaojian12 = true;
		}
		if($tiaojian1){$levelok++;}
		if($tiaojian2){$levelok++;}
		if($tiaojian3){$levelok++;}
		if($tiaojian4){$levelok++;}
		if($tiaojian5){$levelok++;}
		if($tiaojian6){$levelok++;}
		if($tiaojian7){$levelok++;}
		if($tiaojian8){$levelok++;}
		if($tiaojian9){$levelok++;}
		if($tiaojian10){$levelok++;}
		if($tiaojian11){$levelok++;}
		if($tiaojian12){$levelok++;}
		
		$canupdate = false;
		if($curlevel['tiaojian']=='1'){
			if($levelok > 0)
			{
				$canupdate =true;
			}
		}
		else
		{
			if($levelok > 11)
			{
				$canupdate =true;
			}
		}
		if($canupdate)
		{
			pdo_update("sz_yi_member", array("agentlevel" => $curlevel["id"]), array("id" => $member["id"]));
			$this->sendMessage($openid, array("nickname" => $member["nickname"], "oldlevel" => $oldlevel, "newlevel" => $curlevel,), TM_COMMISSION_UPGRADE);
			break;
		}
		
		
		
		
	}
}
function upgradeLevelByAgent($openid) {
	$this->upgradeLevelByOrder($openid);
	// global $_W;
	// if (empty($openid)) { return false; }
	// $set = $this->getSet();
	// if (empty($set["level"])) { return false; }
	// $member = m("member")->getMember($openid);
	// if (empty($member)) { return; }
	// $pluginbonus = p("bonus");
	// if(!empty($pluginbonus)){
		// $bonus_set = $pluginbonus->getSet();
		// if(!empty($bonus_set["start"])){
			// $pluginbonus->upgradeLevelByAgent($openid);
		// }
	// }
	// $pluginbonusplus = p("bonusplus"); 
	// if(!empty($pluginbonusplus))
	// {
		// $bonusplus_set = $pluginbonusplus->getSet();
		// if(!empty($bonusplus_set["start"])){
			// $pluginbonusplus->upgradeLevelByAgent($openid);
		// }
	// }
	// $leveltype = intval($set["leveltype"]);
	// if ($leveltype < 6 || $leveltype > 9) { return; }
	// $_var_166 = $this->getInfo($member["id"], array());
	// if ($leveltype == 6 || $leveltype == 8) {
		// $_var_79 = array($member);
		// if (!empty($member["agentid"])) {
			// $_var_10 = m("member")->getMember($member["agentid"]);
			// if (!empty($_var_10)) {
				// $_var_79[] = $_var_10;
				// if (!empty($_var_10["agentid"]) && $_var_10["isagent"] == 1 && $_var_10["status"] == 1) {
					// $_var_12 = m("member")->getMember($_var_10["agentid"]);
					// if (!empty($_var_12) && $_var_12["isagent"] == 1 && $_var_12["status"] == 1) {
						// $_var_79[] = $_var_12;
					// }
				// }
			// }
		// }
		// if (empty($_var_79)) { return; }
		// foreach ($_var_79 as $_var_165) {
			// $_var_166 = $this->getInfo($_var_165["id"], array());
			// if (!empty($_var_166["agentnotupgrade"])) { continue; }
			// $_var_162 = $this->getLevel($_var_165["openid"]);
			// if (empty($_var_162["id"])) {
				// $_var_162 = array("levelname" => empty($set["levelname"]) ? "普通等级" : $set["levelname"], "commission1" => $set["commission1"], "commission2" => $set["commission2"], "commission3" => $set["commission3"]);
			// }
			// if ($leveltype == 6) {
				// $_var_167 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid=:agentid and uniacid=:uniacid ", array(":agentid" => $member["id"], ":uniacid" => $_W["uniacid"]), "id");
				// $_var_168 += count($_var_167);
				// if (!empty($_var_167)) {
					// $_var_169 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid in( " . implode(",", array_keys($_var_167)) . ") and uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]), "id");
					// $_var_168 += count($_var_169);
					// if (!empty($_var_169)) {
						// $_var_170 = pdo_fetchall("select id from " . tablename("sz_yi_member") . " where agentid in( " . implode(",", array_keys($_var_169)) . ") and uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]), "id");
						// $_var_168 += count($_var_170);
					// }
				// }
				// $_var_164 = pdo_fetch("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid  and {$_var_168} >= downcount and downcount>0  order by downcount desc limit 1", array(":uniacid" => $_W["uniacid"]));
			// }
			// else if ($leveltype == 8) {
				// $_var_168 = $_var_166["level1"] + $_var_166["level2"] + $_var_166["level3"]; $_var_164 = pdo_fetch("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid  and {$_var_168} >= downcount and downcount>0  order by downcount desc limit 1", array(":uniacid" => $_W["uniacid"]));
			// }
			// if (empty($_var_164)) { continue; }
			// if ($_var_164["id"] == $_var_162["id"]) { continue; }
			// if (!empty($_var_162["id"])) {
				// if ($_var_162["downcount"] > $_var_164["downcount"]) { continue; }
			// }
			// pdo_update("sz_yi_member", array("agentlevel" => $_var_164["id"]), array("id" => $_var_165["id"]));
			// $this->sendMessage($_var_165["openid"], array("nickname" => $_var_165["nickname"], "oldlevel" => $_var_162, "newlevel" => $_var_164,), TM_COMMISSION_UPGRADE);
		// }
	// }
	// else {
		// if (!empty($member["agentnotupgrade"])) { return; }
		// $_var_162 = $this->getLevel($member["openid"]);
		// if (empty($_var_162["id"])) {
			// $_var_162 = array("levelname" => empty($set["levelname"]) ? "普通等级" : $set["levelname"], "commission1" => $set["commission1"], "commission2" => $set["commission2"], "commission3" => $set["commission3"]);
		// }
		// if ($leveltype == 7) {
			// $_var_168 = pdo_fetchcolumn("select count(*) from " . tablename("sz_yi_member") . " where agentid=:agentid and uniacid=:uniacid ", array(":agentid" => $member["id"], ":uniacid" => $_W["uniacid"]));
			// $_var_164 = pdo_fetch("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid  and {$_var_168} >= downcount and downcount>0  order by downcount desc limit 1", array(":uniacid" => $_W["uniacid"]));
		// }
		// else if ($leveltype == 9) {
			// $_var_168 = $_var_166["level1"];
			// $_var_164 = pdo_fetch("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid  and {$_var_168} >= downcount and downcount>0  order by downcount desc limit 1", array(":uniacid" => $_W["uniacid"]));
		// }
		// if (empty($_var_164)) { return; }
		// if ($_var_164["id"] == $_var_162["id"]) { return; }
		// if (!empty($_var_162["id"])) {
			// if ($_var_162["downcount"] > $_var_164["downcount"]) { return; }
		// }
		// pdo_update("sz_yi_member", array("agentlevel" => $_var_164["id"]), array("id" => $member["id"]));
		// $this->sendMessage($member["openid"], array("nickname" => $member["nickname"], "oldlevel" => $_var_162, "newlevel" => $_var_164,), TM_COMMISSION_UPGRADE);
	// }
}
function upgradeLevelByCommissionOK($_var_20) { global $_W; if (empty($_var_20)) { return false; } $_var_0 = $this->getSet(); if (empty($_var_0["level"])) { return false; } $_var_154 = m("member")->getMember($_var_20); if (empty($_var_154)) { return; }
$pluginbonus = p("bonus");
if(!empty($pluginbonus)){
	$bonus_set = $pluginbonus->getSet();
	if(!empty($bonus_set["start"])){
		$pluginbonus->upgradeLevelByAgent($_var_20);
	}
}
$pluginbonusplus = p("bonusplus"); 
if(!empty($pluginbonusplus))
{
	$bonusplus_set = $pluginbonusplus->getSet();
	if(!empty($bonusplus_set["start"])){
		$pluginbonusplus->upgradeLevelByAgent($_var_20);
	}
}
 $_var_161 = intval($_var_0["leveltype"]); if ($_var_161 != 10) { return; } if (!empty($_var_154["agentnotupgrade"])) { return; } $_var_162 = $this->getLevel($_var_154["openid"]); if (empty($_var_162["id"])) { $_var_162 = array("levelname" => empty($_var_0["levelname"]) ? "普通等级" : $_var_0["levelname"], "commission1" => $_var_0["commission1"], "commission2" => $_var_0["commission2"], "commission3" => $_var_0["commission3"]); } $_var_166 = $this->getInfo($_var_154["id"], array("pay")); $_var_171 = $_var_166["commission_pay"]; $_var_164 = pdo_fetch("select * from " . tablename("sz_yi_commission_level") . " where uniacid=:uniacid  and {$_var_171} >= commissionmoney and commissionmoney>0  order by commissionmoney desc limit 1", array(":uniacid" => $_W["uniacid"])); if (empty($_var_164)) { return; } if ($_var_162["id"] == $_var_164["id"]) { return; } if (!empty($_var_162["id"])) { if ($_var_162["commissionmoney"] > $_var_164["commissionmoney"]) { return; } } pdo_update("sz_yi_member", array("agentlevel" => $_var_164["id"]), array("id" => $_var_154["id"])); $this->sendMessage($_var_154["openid"], array("nickname" => $_var_154["nickname"], "oldlevel" => $_var_162, "newlevel" => $_var_164,), TM_COMMISSION_UPGRADE); }
function sendMessage($_var_20 = '', $_var_172 = array(), $_var_173 = '') { global $_W, $_GPC; $_var_0 = $this->getSet(); $_var_174 = $_var_0["tm"]; $_var_175 = $_var_174["templateid"]; $_var_22 = m("member")->getMember($_var_20); $_var_176 = unserialize($_var_22["noticeset"]); if (!is_array($_var_176)) { $_var_176 = array(); } if ($_var_173 == TM_COMMISSION_AGENT_NEW && !empty($_var_174["commission_agent_new"]) && empty($_var_176["commission_agent_new"])) { $_var_177 = $_var_174["commission_agent_new"]; $_var_177 = str_replace("[昵称]", $_var_172["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_172["childtime"]), $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_agent_newtitle"]) ? $_var_174["commission_agent_newtitle"] : "新增下线通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_ORDER_PAY && !empty($_var_174["commission_order_pay"]) && empty($_var_176["commission_order_pay"])) { $_var_177 = $_var_174["commission_order_pay"]; $_var_177 = str_replace("[昵称]", $_var_172["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_172["paytime"]), $_var_177); $_var_177 = str_replace("[订单编号]", $_var_172["ordersn"], $_var_177); $_var_177 = str_replace("[订单金额]", $_var_172["price"], $_var_177); $_var_177 = str_replace("[佣金金额]", $_var_172["commission"], $_var_177); $_var_177 = str_replace("[商品详情]", $_var_172["goods"], $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_order_paytitle"]) ? $_var_174["commission_order_paytitle"] : "下线付款通知"), "keyword2" => array("value" => $_var_177)); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_ORDER_FINISH && !empty($_var_174["commission_order_finish"]) && empty($_var_176["commission_order_finish"])) { $_var_177 = $_var_174["commission_order_finish"]; $_var_177 = str_replace("[昵称]", $_var_172["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_172["finishtime"]), $_var_177); $_var_177 = str_replace("[订单编号]", $_var_172["ordersn"], $_var_177); $_var_177 = str_replace("[订单金额]", $_var_172["price"], $_var_177); $_var_177 = str_replace("[佣金金额]", $_var_172["commission"], $_var_177); $_var_177 = str_replace("[商品详情]", $_var_172["goods"], $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_order_finishtitle"]) ? $_var_174["commission_order_finishtitle"] : "下线确认收货通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_APPLY && !empty($_var_174["commission_apply"]) && empty($_var_176["commission_apply"])) { $_var_177 = $_var_174["commission_apply"]; $_var_177 = str_replace("[昵称]", $_var_22["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_177); $_var_177 = str_replace("[金额]", $_var_172["commission"], $_var_177); $_var_177 = str_replace("[提现方式]", $_var_172["type"], $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_applytitle"]) ? $_var_174["commission_applytitle"] : "提现申请提交成功", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_CHECK && !empty($_var_174["commission_check"]) && empty($_var_176["commission_check"])) { $_var_177 = $_var_174["commission_check"]; $_var_177 = str_replace("[昵称]", $_var_22["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_177); $_var_177 = str_replace("[金额]", $_var_172["commission"], $_var_177); $_var_177 = str_replace("[提现方式]", $_var_172["type"], $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_checktitle"]) ? $_var_174["commission_checktitle"] : "提现申请审核处理完成", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_PAY && !empty($_var_174["commission_pay"]) && empty($_var_176["commission_pay"])) { $_var_177 = $_var_174["commission_pay"]; $_var_177 = str_replace("[昵称]", $_var_22["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_177); $_var_177 = str_replace("[金额]", $_var_172["commission"], $_var_177); $_var_177 = str_replace("[提现方式]", $_var_172["type"], $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_paytitle"]) ? $_var_174["commission_paytitle"] : "佣金打款通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_UPGRADE && !empty($_var_174["commission_upgrade"]) && empty($_var_176["commission_upgrade"])) { $_var_177 = $_var_174["commission_upgrade"]; $_var_177 = str_replace("[昵称]", $_var_22["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", time()), $_var_177); $_var_177 = str_replace("[旧等级]", $_var_172["oldlevel"]["levelname"], $_var_177); $_var_177 = str_replace("[旧一级分销比例]", $_var_172["oldlevel"]["commission1"] . "%", $_var_177); $_var_177 = str_replace("[旧二级分销比例]", $_var_172["oldlevel"]["commission2"] . "%", $_var_177); $_var_177 = str_replace("[旧三级分销比例]", $_var_172["oldlevel"]["commission3"] . "%", $_var_177); $_var_177 = str_replace("[新等级]", $_var_172["newlevel"]["levelname"], $_var_177); $_var_177 = str_replace("[新一级分销比例]", $_var_172["newlevel"]["commission1"] . "%", $_var_177); $_var_177 = str_replace("[新二级分销比例]", $_var_172["newlevel"]["commission2"] . "%", $_var_177); $_var_177 = str_replace("[新三级分销比例]", $_var_172["newlevel"]["commission3"] . "%", $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_upgradetitle"]) ? $_var_174["commission_upgradetitle"] : "分销等级升级通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } else if ($_var_173 == TM_COMMISSION_BECOME && !empty($_var_174["commission_become"]) && empty($_var_176["commission_become"])) { $_var_177 = $_var_174["commission_become"]; $_var_177 = str_replace("[昵称]", $_var_172["nickname"], $_var_177); $_var_177 = str_replace("[时间]", date("Y-m-d H:i:s", $_var_172["agenttime"]), $_var_177); $_var_178 = array("keyword1" => array("value" => !empty($_var_174["commission_becometitle"]) ? $_var_174["commission_becometitle"] : "成为分销商通知", "color" => "#73a68d"), "keyword2" => array("value" => $_var_177, "color" => "#73a68d")); if (!empty($_var_175)) { m("message")->sendTplNotice($_var_20, $_var_175, $_var_178); } else { m("message")->sendCustomNotice($_var_20, $_var_178); } } }
function perms() { return array("commission" => array("text" => $this->getName(), "isplugin" => true, "child" => array("cover" => array("text" => "入口设置"), "agent" => array("text" => "分销商", "view" => "浏览", "check" => "审核-log", "edit" => "修改-log", "agentblack" => "黑名单操作-log", "delete" => "删除-log", "user" => "查看下线", "order" => "查看推广订单(还需有订单权限)", "changeagent" => "设置分销商"), "level" => array("text" => "分销商等级", "view" => "浏览", "add" => "添加-log", "edit" => "修改-log", "delete" => "删除-log"), "apply" => array("text" => "佣金审核", "view1" => "浏览待审核", "view2" => "浏览已审核", "view3" => "浏览已打款", "view_1" => "浏览无效", "export1" => "导出待审核-log", "export2" => "导出已审核-log", "export3" => "导出已打款-log", "export_1" => "导出无效-log", "check" => "审核-log", "pay" => "打款-log", "cancel" => "重新审核-log"), "notice" => array("text" => "通知设置-log"), "increase" => array("text" => "分销商趋势图"), "changecommission" => array("text" => "修改佣金-log"), "set" => array("text" => "基础设置-log")))); }
function upgradeLevelByGood($_var_128) { global $_W; $_var_129 = $this->getSet(); if (!$_var_129["upgrade_by_good"]) { return; } $_var_148 = pdo_fetch("select g.commission_level_id from " . tablename("sz_yi_order_goods") . " AS og, " . tablename("sz_yi_goods") . " AS g WHERE og.goodsid = g.id AND og.orderid=:orderid AND og.uniacid=:uniacid LIMIT 1", array( ":orderid" => $_var_128, ":uniacid" => $_W["uniacid"] )); $_var_179 = $_var_148["commission_level_id"]; if ($_var_179) { $_var_180 = $this->getLevels(); foreach ($_var_180 as $_var_149) { if ($_var_149["id"] == $_var_179) { $_var_181 = $_var_149["commission1"]; $_var_182 = $_var_149["commission2"]; $_var_183 = $_var_149["commission3"]; } } $_var_131 = pdo_fetchcolumn("select openid from " . tablename("sz_yi_order") . " where uniacid=:uniacid and id=:orderid", array( ":uniacid" => $_W["uniacid"], ":orderid" => $_var_128 )); $_var_184 = $this->getLevel($_var_131); if (!$_var_184 || $_var_184["commission1"] < $_var_181 || $_var_184["commission2"] < $_var_182 || $_var_184["commission3"] < $_var_183 ) { pdo_update("sz_yi_member", array("agentlevel" => $_var_179), array("uniacid" => $_W["uniacid"], "openid" => $_var_131)); } } } } }