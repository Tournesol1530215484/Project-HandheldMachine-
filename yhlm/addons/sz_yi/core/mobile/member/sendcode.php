<?php



/**
 * 发送信息
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */



if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$mc = $_GPC['memberdata'];  //'18646588292';

$op = empty($_GPC['op']) ? 'sendcode' : trim($_GPC['op']);

session_start();

if($op == 'sendcode'){

    $mobile = $_GPC['mobile'];

    if(empty($mobile)){
        show_json(0, '请填入手机号');
    }

    $info = pdo_fetch('select id from ' . tablename('sz_yi_member') . ' where mobile=:mobile and pwd!="" and uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':mobile' => $mobile
    ));

    if(!empty($info)){
        show_json(0, '该手机号已被注册！不能获取验证码。');
    } 

    $code = rand(1000, 9999);

    $_SESSION['codetime'] = time();
    $_SESSION['code'] = $code;
    $_SESSION['code_mobile'] = $mobile;
    $_var_7 = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

    $dephp_4 = unserialize($_var_7['sets']);
    // var_dump($dephp_4);
    // die;
    // 根据公众号获取公众号名字
    // $post_data = array(
    //     'account' => $dephp_4['sms']['account'],
    //     'password' => strtoupper(md5($dephp_4['sms']['password'])),
    //     'mobile' => $mobile,
    //     'content' => $message,
    //     'requestId' => '523491875',
    //     'extno' => '33'
    // );
    // $post_data = json_encode($post_data,true);
    // $list = curl_request('http://www.17int.cn/xxsmsweb/smsapi/send.json',$post_data);
    $name = pdo_fetchcolumn("select name from hs_uni_account where uniacid = {$_W['uniacid']}");
    $message = '【'.$name.'】您的验证码是:'.$code.',2分钟后过期，请您及时验证！'; // 填写测试短信

    
    $re=send_zhangjun($mobile,$message);

    if($re['returnsms']['message'] == 'ok'){
        show_json(1);
    }else{
        show_json(0);
    }

}else if ($op == 'forgetcode'){

    $mobile = $_GPC['mobile'];

    if(empty($mobile)){
        show_json(0, '请填入手机号');
    }

    $info = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where mobile=:mobile and pwd!="" and uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':mobile' => $mobile
    ));

    if(empty($info)){
        show_json(0, '该手机号未注册！不能找回密码。');
    } 

    $code = rand(1000, 9999);

    $_SESSION['codetime'] = time();

    $_SESSION['code'] = $code;

    $_SESSION['code_mobile'] = $mobile;
    //  $content = "您的安全码是：". $code ."。请不要把安全码泄露给其他人。如非本人操作，可不用理会！";
    $_var_7 = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

    $dephp_4 = unserialize($_var_7['sets']);

    $name = $dephp_4['shop']['name'];

    $name = pdo_fetchcolumn("select name from hs_uni_account where uniacid = {$_W['uniacid']}");
    $message = '【'.$name.'】您的验证码是:'.$code.',2分钟后过期，请您及时验证！'; // 填写测试短信

    // $post_data = array(
    //     'account' => $dephp_4['sms']['account'],
    //     'password' => strtoupper(md5($dephp_4['sms']['password'])),
    //     'mobile' => $mobile,
    //     'content' => $message,
    //     'requestId' => '523491875',
    //     'extno' => '33'
    // );

    // $post_data = json_encode($post_data,true);

    // $list = curl_request('http://www.17int.cn/xxsmsweb/smsapi/send.json',$post_data);

    // if($list['errorCode'] == 'ALLSuccess'){
    //     show_json(1);
    // }else{
    //     show_json(0);
    // }
    $re=send_zhangjun($mobile,$message);

    if($re['returnsms']['message'] == 'ok'){
        show_json(1);
    }else{
        show_json(0);
    }


}else if ($op == 'bindmobilecode'){

    $mobile = $_GPC['mobile'];

    if(empty($mobile)){
        show_json(0, '请填入手机号');
    }

    $checks = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where mobile=:mobile and uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':mobile' => $mobile
    ));

    if($checks){
        show_json(0, '该手机号已被注册！');
    }else{
        $info = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where mobile=:mobile and pwd!="" and uniacid=:uniacid limit 1', array(
            ':uniacid' => $_W['uniacid'],
            ':mobile' => $mobile
        ));

        $code = rand(1000, 9999);

        $_SESSION['codetime'] = time();

        $_SESSION['code'] = $code;

        $_SESSION['code_mobile'] = $mobile;
      //  $content = "您的安全码是：". $code ."。请不要把安全码泄露给其他人。如非本人操作，可不用理会！";
        $_var_7 = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

        $dephp_4 = unserialize($_var_7['sets']);

        $name = $dephp_4['shop']['name'];

        $name = pdo_fetchcolumn("select name from hs_uni_account where uniacid = {$_W['uniacid']}");
        $message = '【'.$name.'】您的验证码是:'.$code.',2分钟后过期，请您及时验证！'; // 填写测试短信

 

        // $post_data = array(
        //     'account' => $dephp_4['sms']['account'],
        //     'password' => strtoupper(md5($dephp_4['sms']['password'])),
        //     'mobile' => $mobile,
        //     'content' => $message,
        //     'requestId' => '523491875',
        //     'extno' => '33'
        // );

        // $post_data = json_encode($post_data,true);

        // $list = curl_request('http://www.17int.cn/xxsmsweb/smsapi/send.json',$post_data);

        // if($list['errorCode'] == 'ALLSuccess'){
        //    show_json(1);
        // }else{
        //    show_json(0);
        // }

        $re=send_zhangjun($mobile,$message);

        if($re['returnsms']['message'] == 'ok'){
            show_json(1);
        }else{
            show_json(0);
        }

    }

}else if ($op == 'checkcode'){

    $code = $_GPC['code'];

    $openid = m('user') -> getOpenid(); 

    if(($_SESSION['codetime']+60*5) < time()){
        show_json(0, '验证码已过期,请重新获取');
    }

    if($_SESSION['code'] != $code){
        show_json(0, '验证码错误,请重新获取');
    }

    show_json(1);  

}else if ($op == 'ismobile'){

    $mobile = $_GPC['mobile'];

    if(empty($mobile)){
        show_json(0, '请填入手机号');
    }

    $info = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where mobile=:mobile and pwd!="" and uniacid=:uniacid limit 1', array(
        ':uniacid' => $_W['uniacid'],
        ':mobile' => $mobile
    ));

    if(!empty($info)){
        show_json(0, '该手机号已被注册！');
    }else{
        show_json(1); 
    }    
}





function curl_request($url,$postStr = ""){



    $header = array(



        'Content-Type: application/json',



    );



    $curl = curl_init($url);



    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);



    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");



    curl_setopt($curl, CURLOPT_POSTFIELDS, $postStr);



    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



    curl_setopt($curl, CURLOPT_FAILONERROR, false);



    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);



    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);



    $response = curl_exec($curl) or die("error：".curl_errno($curl));



    curl_close($curl);



    $result = (array)json_decode($response);



    return $result;

}





