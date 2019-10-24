<?php

//微信授权登录
if (!defined("IN_IA")) {
	die("Access Denied");

}

global $_W, $_GPC;

$article1=pdo_fetch("select article_content from hs_sz_yi_article where article_title = '易货联盟隐私政策' ");


include $this->template("member/article1");

?>	