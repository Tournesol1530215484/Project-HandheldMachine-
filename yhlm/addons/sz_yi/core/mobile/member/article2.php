<?php

//微信授权登录
if (!defined("IN_IA")) {
	die("Access Denied");

}

global $_W, $_GPC;

$article2=pdo_fetch("select article_content from hs_sz_yi_article where article_title = '易货联盟用户服务协议' ");

include $this->template("member/article2");

?>	