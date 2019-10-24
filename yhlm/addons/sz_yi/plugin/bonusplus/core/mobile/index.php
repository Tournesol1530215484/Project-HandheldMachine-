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
global $_W, $_GPC;
$openid = m("user")->getOpenid();
$level = $this->model->getLevel($openid);
$set = $this->getSet();
$member = $this->model->getInfo($openid, array("total", "ordercount", "ok"));
$cansettle = $member["commission_ok"] > 0 && $member["commission_ok"] >= floatval($this->set["withdraw"]);
$commission_ok = $member["commission_ok"];
$member["nickname"] = empty($member["nickname"]) ? $member["mobile"] : $member["nickname"];
$member["agentcount"] = number_format($member["agentcount"], 0);
$member["ordercount0"] = number_format($member["ordercount"], 0);
$member["commission_ok"] = number_format($member["commission_ok"], 2);
$member["commission_pay"] = number_format($member["commission_pay"], 2);
$member["commission_total"] = number_format($member["commission_total"], 2);
$member["customercount"] = count($this->model->getChildAgents($member["id"]));
if (mb_strlen($member["nickname"], "utf-8") > 6) {
	$member["nickname"] = mb_substr($member["nickname"], 0, 6, "utf-8");
}
$openselect = false;
if ($this->set["select_goods"] == "1") {
	if (empty($member["agentselectgoods"]) || $member["agentselectgoods"] == 2) {
		$openselect = true;
	}
} else {
	if ($member["agentselectgoods"] == 2) {
		$openselect = true;
	}
}
$this->set["openselect"] = $openselect;
include $this->template("index");