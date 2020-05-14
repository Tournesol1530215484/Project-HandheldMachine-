<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Facade\Db;

class  AccessToken extends Base{

    public  function getToken(){

        $data=[
            'token'=>uniqid(),
            'time'=>time()
        ];
        $res=Db::name('jan_feng_ye_token')->insert($data);
        if($res){
            $this->return_msg(200,"ok",$data);
        }else{
            $this->return_msg(400,"error");
        }



    }

    public function checkToken($token){
        if(isset($token)&&!empty($token)){
            $tokenarray=explode('-',$token);
            if(count($tokenarray)!=2){
                $this->return_msg(400,"token格式不正确");
            }

            $res=Db::name("jan_feng_ye_token")->where('token', $tokenarray[1])->find();

            if(!res){
                $this->return_msg(400,"token匹配失败");
            }
            Db::table('jan_feng_ye_token')->where('token',$res['id'])->delete();
            $this->return_msg(200,"ok");



        }else{
            $this->return_msg(400,"token值不存在");
        }
    }

}