<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
class Sz_DYi_User{
    private $sessionid;
    public function __construct(){
        global $_W;
        $this -> sessionid = "__cookie_sz_yi_201507200000_{$_W['uniacid']}";
    }
    function getOpenid(){
        $dephp_0 = $this -> getInfo(false, true);
        return $dephp_0['openid'];
    }
    public function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $pi = 3.1415926000000001;
        $er = 6378.1369999999997;
        $radLat1 = ($lat1 * $pi) / 180;
        $radLat2 = ($lat2 * $pi) / 180;
        $a = $radLat1 - $radLat2;
        $b = (($lng1 * $pi) / 180) - (($lng2 * $pi) / 180);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + (cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))));
        $s = $s * $er;
        $s = round($s * 1000);
        if (1 < $len_type)
        {
            $s /= 1000;
        }
        return round($s, $decimal);
        /*  $earthRadius = 6367000; //approximate radius of earth in meters
         $lat1 = ($lat1 * pi() ) / 180;
         $lng1 = ($lng1 * pi() ) / 180;
         $lat2 = ($lat2 * pi() ) / 180;
         $lng2 = ($lng2 * pi() ) / 180;
         $calcLongitude = $lng2 - $lng1;
         $calcLatitude = $lat2 - $lat1;
         $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
         $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
         $calculatedDistance = $earthRadius * $stepTwo;
         if (1 < $len_type)
         {
         $calculatedDistance /= 1000000;
         }
        return round($calculatedDistance); */
    
    }
    function getPerOpenid(){
        global $_W, $_GPC;
        $dephp_1 = 24 * 3600 * 3;
        session_set_cookie_params($dephp_1);
        @session_start();
        $dephp_2 = "__cookie_sz_yi_openid_{$_W['uniacid']}";
        $dephp_3 = base64_decode($_COOKIE[$dephp_2]);
        if (!empty($dephp_3)){
            return $dephp_3;
        }
        load() -> func('communication');
        $dephp_4 = $_W['account']['key'];
        $dephp_5 = $_W['account']['secret'];
        $dephp_6 = "";
        $dephp_7 = $_GPC['code'];
        $dephp_8 = $_W['siteroot'] . 'app/index.php?' . $_SERVER['QUERY_STRING'];
        if (empty($dephp_7)){
            $dephp_9 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $dephp_4 . '&redirect_uri=' . urlencode($dephp_8) . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
            header('location: ' . $dephp_9);
            exit();
        }else{
            $dephp_10 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $dephp_4 . '&secret=' . $dephp_5 . '&code=' . $dephp_7 . '&grant_type=authorization_code';
            $dephp_11 = ihttp_get($dephp_10);
            $dephp_12 = @json_decode($dephp_11['content'], true);
            if (!empty($dephp_12) && is_array($dephp_12) && $dephp_12['errmsg'] == 'invalid code'){
                $dephp_9 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $dephp_4 . '&redirect_uri=' . urlencode($dephp_8) . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
                header('location: ' . $dephp_9);
                exit();
            }
            if (is_array($dephp_12) && !empty($dephp_12['openid'])){
                $dephp_6 = $dephp_12['access_token'];
                $dephp_3 = $dephp_12['openid'];
                setcookie($dephp_2, base64_encode($dephp_3));
            }else{
                $dephp_13 = explode('&', $_SERVER['QUERY_STRING']);
                $dephp_14 = array();
                foreach ($dephp_13 as $dephp_15){
                    if (!strexists($dephp_15, 'code=') && !strexists($dephp_15, 'state=') && !strexists($dephp_15, 'from=') && !strexists($dephp_15, 'isappinstalled=')){
                        $dephp_14[] = $dephp_15;
                    }
                }
                $dephp_16 = $_W['siteroot'] . 'app/index.php?' . implode('&', $dephp_14);
                $dephp_9 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $dephp_4 . '&redirect_uri=' . urlencode($dephp_16) . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
                header('location: ' . $dephp_9);
                exit;
            }
        }
        return $dephp_3;
    }
    function isLogin(){
        global $_W, $_GPC;
        @session_start();
        $dephp_2 = "__cookie_sz_yi_userid_{$_W['uniacid']}";
        $dephp_3 = base64_decode($_COOKIE[$dephp_2]);
        if (empty($_SERVER['HTTP_USER_AGENT']) && empty($dephp_3) && $_GPC['token']){
            $dephp_3 = $_GPC['token'];
        }
        if (!empty($dephp_3)){
            return $dephp_3;
        }
        return false;
    }
    
    function getUserInfo(){
        global $_W, $_GPC;
        $dephp_17 = array('address', 'commission', 'cart');
        $dephp_18 = array('category', 'login' , 'receive', 'close','news','vip','send','ready','sale','shopping', 'designer', 'register', 'sendcode', 'bindmobile', 'forget','weixin', 'article','article1','article2','bartact');
        $dephp_19 = array('shop', 'login', 'register','plugin',);
        if(!$_GPC['p'] && $_GPC['do'] == 'shop'){
            return;
        }
      
        if((!in_array($_GPC['p'], $dephp_18) && !in_array($_GPC['do'], $dephp_19)) or (in_array($_GPC['p'], $dephp_17))){
            if(( $_GPC['method'] != 'myshop' &&  $_GPC['method'] != 'api'   ) or ($_GPC['c'] != 'entry')){
                $dephp_3 = $this -> isLogin();
                if(!$dephp_3 && $_GPC['p'] != 'cart'){
                    if($_GPC['do'] != 'runtasks'){
                        setcookie('preUrl', $_W['siteurl']);
                    }
                    $dephp_20 = ($_GPC['mid']) ? '&mid=' . $_GPC['mid'] : "";
                    $dephp_8 = "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&p=login&do=member&m=sz_yi" . $dephp_20;
                    redirect($dephp_8);
                }else{
                    $dephp_0 = array('openid' => $dephp_3, 'headimgurl' => '',);
                    return $dephp_0;
                }
            }
        }
    }
    function getInfo($dephp_21 = false, $dephp_22 = false){

        global $_W, $_GPC;
        if(!is_weixin()){
            return $this -> getUserInfo();
        }
        $dephp_0 = array();
        if (SZ_YI_DEBUG){
            $dephp_0 = array('openid' => 'oVwSVuJXB7lGGc93d0gBXQ_h-czc', 'nickname' => '小萝莉', 'headimgurl' => '', 'province' => '香港', 'city' => '九龙');
        }else{
            load() -> model('mc');
            if (empty($_GPC['directopenid'])){
                $dephp_0 = mc_oauth_userinfo();
            }else{
                $dephp_0 = array('openid' => $this -> getPerOpenid());
            }
            $dephp_23 = false;
            if ($_W['container'] != 'wechat'){
                if ($_GPC['do'] == 'order' && $_GPC['p'] == 'pay'){
                    $dephp_23 = false;
                }
                if ($_GPC['do'] == 'member' && $_GPC['p'] == 'recharge'){
                    $dephp_23 = false;
                }
                if ($_GPC['do'] == 'plugin' && $_GPC['p'] == 'article' && $_GPC['preview'] == '1'){
                    $dephp_23 = false;
                }
            }
        }
        if ($dephp_21){
            return urlencode(base64_encode(json_encode($dephp_0)));
        }
        return $dephp_0;
    }
    function oauth_info(){
        global $_W, $_GPC;
        if ($_W['container'] != 'wechat'){
            if ($_GPC['do'] == 'order' && $_GPC['p'] == 'pay'){
                return array();
            }
            if ($_GPC['do'] == 'member' && $_GPC['p'] == 'recharge'){
                return array();
            }
        }
        $dephp_1 = 24 * 3600 * 3;
        session_set_cookie_params($dephp_1);
        @session_start();
        $dephp_24 = "__cookie_sz_yi_201507100000_{$_W['uniacid']}";
        $dephp_25 = json_decode(base64_decode($_SESSION[$dephp_24]), true);
        $dephp_3 = is_array($dephp_25) ? $dephp_25['openid'] : '';
        $dephp_26 = is_array($dephp_25) ? $dephp_25['openid'] : '';
        if (!empty($dephp_3)){
            return $dephp_25;
        }
        load() -> func('communication');
        $dephp_4 = $_W['account']['key'];
        $dephp_5 = $_W['account']['secret'];
        $dephp_6 = "";
        $dephp_7 = $_GPC['code'];
        $dephp_8 = $_W['siteroot'] . 'app/index.php?' . $_SERVER['QUERY_STRING'];
        if (empty($dephp_7)){
            $dephp_9 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $dephp_4 . '&redirect_uri=' . urlencode($dephp_8) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
            header('location: ' . $dephp_9);
            exit();
        }else{
            $dephp_10 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $dephp_4 . '&secret=' . $dephp_5 . '&code=' . $dephp_7 . '&grant_type=authorization_code';
            $dephp_11 = ihttp_get($dephp_10);
            $dephp_12 = @json_decode($dephp_11['content'], true);
            if (!empty($dephp_12) && is_array($dephp_12) && $dephp_12['errmsg'] == 'invalid code'){
                $dephp_9 = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $dephp_4 . '&redirect_uri=' . urlencode($dephp_8) . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
                
                header('location: ' . $dephp_9);
                exit();
            }
            if (empty($dephp_12) || !is_array($dephp_12) || empty($dephp_12['access_token']) || empty($dephp_12['openid'])){
                die('获取token失败,请重新进入!');
            }else{
                $dephp_6 = $dephp_12['access_token'];
                $dephp_3 = $dephp_12['openid'];
            }
        }
        $dephp_27 = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $dephp_6 . '&openid=' . $dephp_3 . '&lang=zh_CN';

        $dephp_11 = ihttp_get($dephp_27);
        $dephp_0 = @json_decode($dephp_11['content'], true);
        if (isset($dephp_0['nickname'])){
            $_SESSION[$dephp_24] = base64_encode(json_encode($dephp_0));
            return $dephp_0;
        }else{
            die('获取用户信息失败，请重新进入!');
        }
    }
    function followed($dephp_3 = ''){
        global $_W;
        $dephp_28 = !empty($dephp_3);
        if ($dephp_28){
            $dephp_29 = pdo_fetch('select follow from ' . tablename('mc_mapping_fans') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':openid' => $dephp_3, ':uniacid' => $_W['uniacid']));
            $dephp_28 = $dephp_29['follow'] == 1;
        }
        return $dephp_28;
    }


    function test(){



        echo "this is test";
    }





}
