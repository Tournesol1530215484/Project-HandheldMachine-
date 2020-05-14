<?php
namespace app\api\controller;
use app\api\controller\Base;
use think\Facade\Db;

class Member extends Base{

    /**
     * 客户端，H5微信登录接口
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function weixinLogin(){
        $data=input();
        $userInfo=Db::table('jan_feng_ye_member')->where('openid',$data['openId'])->find();
        if($userInfo){//登录
            $this->write_log($data['openId'].'微信登录');
            $this->return_msg(200,'ok',$userInfo);
        }else{//注册
            $insertInfo=[
                'openid'=>$data['openId'],
                'nickname'=>$data['nickName'],
                'avatar'=>$data['avatarUrl'],
                'unionid'=>$data['unionid'],
                'city'=>$data['city'],
                'province'=>$data['province'],
                'city'=>$data['country'],
            ];
            $userId = Db::name('jan_feng_ye_member')->insertGetId($insertInfo);
            if($userId){
                $insertInfo['id']=$userId;
                $this->write_log($insertInfo['openid'].'微信注册');
                $this->return_msg('200','ok',$insertInfo);
            }else{
                $insertInfo['id']=$userId;
                $this->return_msg('400','error',"注册失败");
            }
        }

    }


    public function weixin_Xcx_Login(){

    }
}