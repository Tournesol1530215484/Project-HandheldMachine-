<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$openid         = m('user')->getOpenid();
$member         = m('member')->getInfo($openid);
$template_flag  = 0;

/*微信jssdk接口*/
require_once IA_ROOT . '/addons/sz_yi/wxjs/jssdk.php';
$appid     = 'wxf9c362da5caa07d5';
$appsecret = 'e3e4f253ad41ba49d520897de3dd7da8';
$jssdk = new JSSDK( $appid, $appsecret );
$signPackage = $jssdk->GetSignPackage();

include $this->template('member/info2');