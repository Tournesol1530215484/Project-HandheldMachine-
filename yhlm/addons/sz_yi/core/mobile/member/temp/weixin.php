<?php

//微信授权登录
if (!defined("IN_IA")) {
	die("Access Denied");

}

global $_W, $_GPC;
// if ($_W["isajax"]) {
// 	//开启微信授权登录
// 	$redirect_uri=;
// 	$appid="wx3938f2691dae0fae";
// 	$response_type="code";
// 	$scope="snsapi_login";
// 	$state="STATE#wechat_redirect";
//  	// $url="https://open.weixin.qq.com/connect/qrconnect?appid='.$openid.'&redirect_uri='.$redirect_uri.'&response_type='.snsapi_login.'&scope=snsapi_login&state=STATE#wechat_redirect";

//  	// $url="https://open.weixin.qq.com/connect/qrconnect?appid='.$app.'&redirect_uri='.redirect_uri.'&response_type='.response_type.'&scope='.$scope.'&state='.$state.'";

//  	$url="https://open.weixin.qq.com/connect/qrconnect?appid=wx3938f2691dae0fae&redirect_uri=urlEncode('www.jhzh66.com')&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";

// 	$ch = curl_init();
// 	$timeout = 5;
// 	curl_setopt($ch, CURLOPT_URL, $url);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
// 	$contents = curl_exec($ch);
// 	curl_close($ch);
// 	show_json(1, array("preurl" => $contents));

// }

	//var_dump($_GPC['openid']);



include $this->template("member/weixin");

?>	