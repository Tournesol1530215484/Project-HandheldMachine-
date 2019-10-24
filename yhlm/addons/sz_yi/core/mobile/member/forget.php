<?php
/**
 * 忘记密码
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
 
if (!defined("IN_IA")) {
	die("Access Denied");
}
global $_W, $_GPC;
if ($_W["isajax"]) {
	if ($_W["ispost"]) {
		$mc = $_GPC["memberdata"];
		$info = pdo_fetch("select * from " . tablename("sz_yi_member") . " where mobile=:mobile and uniacid=:uniacid limit 1", array(":uniacid" => $_W["uniacid"], ":mobile" => $mc["mobile"]));
		if (!$info) {
			show_json(0, array("msg" => "手机号码不存在"));
			die;
		}
		pdo_update("sz_yi_member", array("pwd" => md5($mc["password"])), array("mobile" => $mc["mobile"]));
		$mid = $_GPC["mid"] ? "&mid=" . $_GPC["mid"] : "";
		$url = "/app/index.php?i={$_W["uniacid"]}&c=entry&p=login&do=member&m=sz_yi" . $mid;
		show_json(1, array("preurl" => $url));
	}
}
include $this->template("member/forget");
?>