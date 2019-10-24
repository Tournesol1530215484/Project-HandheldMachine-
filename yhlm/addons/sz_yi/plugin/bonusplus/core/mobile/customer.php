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
$member = $this->model->getInfo($openid);
$condition = '';
$pindex = max(1, intval($_GPC["page"]));
$psize = 20;
$list = array();
$total = 0;
if (!empty($member["agentcount"])) {
	$total = $member["agentcount"];
	$inagents = implode(",", $member["agentids"]);
	$sql = "select * from " . tablename("sz_yi_member") . " where id in (" . $inagents . ") and uniacid = " . $_W["uniacid"] . " {$condition}  ORDER BY id desc limit " . ($pindex - 1) * $psize . "," . $psize;
	$list = pdo_fetchall($sql);
}
if ($_W["isajax"]) {
	foreach ($list as &$row) {
		$row["createtime"] = date("Y-m-d H:i", $row["createtime"]);
		$ordercount = pdo_fetchcolumn("select count(id) from " . tablename("sz_yi_order") . " where openid=:openid and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $row["openid"]));
		$row["ordercount"] = number_format(intval($ordercount), 0);
		$moneycount = pdo_fetchcolumn("select sum(og.realprice) from " . tablename("sz_yi_order_goods") . " og " . " left join " . tablename("sz_yi_order") . " o on og.orderid=o.id where o.openid=:openid  and o.status>=1 and o.uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":openid" => $row["openid"]));
		$row["moneycount"] = number_format(floatval($moneycount), 2);
	}
	unset($row);
	show_json(1, array("list" => $list, "pagesize" => $psize));
}
include $this->template("customer");