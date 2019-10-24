<?php
global $_W, $_GPC;

require_once  '../autoload.php';
header('Access-Control-Allow-Origin:*');

use Qiniu\Auth;

$bucket = 'yhlm';
$accessKey = 'XLTpD9g6brmqlobhg3ViXr0mluwya4y0w2fVfn36';
$secretKey = 'wFRTM6Xy-3BHeuvjvA1MBF347_RYJWjzRNL5ZCss';
$auth = new Auth($accessKey, $secretKey);


//$upToken = $auth->uploadToken($bucket);

$policy = array(
    'returnUrl' => 'fileinfo.php',
    'returnBody' => '{"fname": $(fname)}',
);
$upToken = $auth->uploadToken($bucket, null, 3600, $policy);


show_json(1,array('upToken'=>$upToken));

//echo $upToken;
