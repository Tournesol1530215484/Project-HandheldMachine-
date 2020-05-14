<?php
namespace  app\admin\controller;
use app\BaseController;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;// 签名加密方式
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use think\facade\Config;
class AccessToken extends BaseController{
    /**
     * 生成token,只有两部分，没有签名部分
     */
    public function getToken(){
        //创建jwt
        $time = time();
        $token = (new Builder())->issuedBy('http://maicaii.com') // 发行者
        ->permittedFor('http://maicaii.com') // 观众
        ->identifiedBy('4f1g23a12aa', true) // id (jti claim),
        ->issuedAt($time) //  发行时间(iat claim)
        ->canOnlyBeUsedAfter($time + 60) // 可使用时间 (nbf claim)
        ->expiresAt($time + 3600) // 过期时间(exp claim)
        ->withClaim('usernam', 'Gonggui') // 配置一个新的字段
        ->withClaim('password', 'Gonggui') // 配置一个新的字段
        ->getToken(); // 生成令牌

        echo $token;
    }

    /**
     * 生成带签名的token
     * @return \Lcobucci\JWT\Token
     */
    public function getSignToken(){
        $tokenkey=Config::get('tokenkey');
        $time = time();
        $token = (new Builder())->issuedBy('http://maicaii.com') // 发行者
        ->permittedFor('http://maicaii.cn') // 观众
        ->identifiedBy('4f1g23a12aa', true) // id (jti claim),
        ->issuedAt($time) //  发行时间(iat claim)
        ->canOnlyBeUsedAfter($time + 60) // 可使用时间 (nbf claim)
        ->expiresAt($time + 3600) // 过期时间(exp claim)
        ->withClaim('username', 'Gonggui') // 配置一个新的字段
        ->withClaim('password', 'Gonggui') // 配置一个新的字段
        ->getToken(new Sha256(),new Key('8848')); // 生成令牌 key要保密

        // echo $token;

        return $token;
    }


    public function checkToken(){
        //之前生成的token
        $token='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImp0aSI6IjRmMWcyM2ExMmFhIn0.eyJpc3MiOiJodHRwOlwvXC9tYWljYWlpLmNvbSIsImF1ZCI6Imh0dHA6XC9cL21haWNhaWkuY24iLCJqdGkiOiI0ZjFnMjNhMTJhYSIsImlhdCI6MTU4NzQ3NzA5MiwibmJmIjoxNTg3NDc3MTUyLCJleHAiOjE1ODc0ODA2OTIsInVzZXJuYW1lIjoiR29uZ2d1aSIsInBhc3N3b3JkIjoiR29uZ2d1aSJ9.fqMNH9aFIDCDEz5oxD-uHBE9c3CWG1HkU-6JjagjrgE';

        $token=(new Parser())->parse($token);//将字符串改为Parser对象
        $data = new ValidationData();
        $data->setIssuer('http://maicaii.com');//发行人
        $data->setAudience('http://maicaii.cn');//听众
        $data->setId('4f1g23a12aa');
        $time=time();
        var_dump($token->validate($data)); //bool(true)

        $data->setCurrentTime($time + 60);//true
        var_dump($token->validate($data));
        $data->setCurrentTime($time + 3600);
        var_dump($token->validate($data));
    }

}