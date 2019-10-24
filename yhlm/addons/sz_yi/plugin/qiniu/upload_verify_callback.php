<?php
//require_once __DIR__ . '/../autoload.php';

require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');

use Qiniu\Auth;

$accessKey = getenv('XLTpD9g6brmqlobhg3ViXr0mluwya4y0w2fVfn36');
$secretKey = getenv('wFRTM6Xy-3BHeuvjvA1MBF347_RYJWjzRNL5ZCss');
$bucket = getenv('yhlm');

$auth = new Auth($accessKey, $secretKey);

//获取回调的body信息
$callbackBody = file_get_contents('php://input');

//回调的contentType
$contentType = 'application/x-www-form-urlencoded';

//回调的签名信息，可以验证该回调是否来自七牛
$authorization = $_SERVER['HTTP_AUTHORIZATION'];

//七牛回调的url，具体可以参考：http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
$url = 'http://jhzh66.com/addons/sz_yi/plugin/qiniu/upload_verify_callback.php';

$isQiniuCallback = $auth->verifyCallback($contentType, $authorization, $url, $callbackBody);

if ($isQiniuCallback) {
    $resp = array('ret' => 'success');
} else {
    $resp = array('ret' => 'failed');
}

echo json_encode($resp);
