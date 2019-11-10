<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/29
 * Time: 14:20
 */
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Login as LoginModel;
use think\Validate;

class Login extends Controller{
    public function index(){
        return view('Login/login');
    }

    // 登录验证
    public function login(){
        if(request()->isPost()){
            $msg=[
                'user_name.require'=>'用户名必填',
                'user_name.max'=>'用户名请小于25个字段',
                'password.require'=>'密码必填',
                'password.max'=>'密码请保持在6-12个字符内',
                'password.min'=>'密码请保持在6-12个字符内',
                'code.require'   => '验证码必须',
                'code.captcha'   => '验证码错误',

            ];
            //定义验证规则
            $rule=[
                'user_name'=>['require','max'=>'25'],
                'password'=>['require','max'=>'12','min'=>'6'],
                'code'=>['require','captcha'],
            ];
            //接收前台传过来的数据
            $data=[
                'user_name'=>input('user_name','','trim'),
                'password'=>input('password','','trim'),
                'code'=>input('code','','trim'),
            ];
            $Validate=new Validate($rule,$msg);
            $result=$Validate->check($data);

            if(!$result){
                $error=$Validate->getError();
                $this->error("$error",url('login'));
            }

            $login=$this->Logins($data['user_name'],$data['password']);
            if(0<$login){
                $this->success('正在登录',url('Index/index'));
            }else{
                switch ($login){
                    case -1:
                        $this->error('账号密码不匹配',url('login'));
                        break;
                    case -2:
                        $this->error('用户名不存在',url('login'));
                        break;
                    case -3:
                        $this->error('用户组不存在或被禁用',url('login'));
                        break;
                    default:
                        $this->error('未知错误',url('login'));
                        break;
                }
            }

        }
       return view('login');

    }

    /**
     * 用户登陆判断
     * @return int
     */
    protected function  Logins($username,$psd){
        //用户
        $admin=db('admin')->where('user_name',$username)->find();
        //用户组
        $group=db('auth_group_access')
                ->alias('a')
                ->field('b.*')
                ->join('__AUTH_GROUP__ b','a.group_id=b.id')
                ->where('a.uid',$admin['id'])
                ->find();
        if(empty($group)||$group['status']!=1){
            return -3;
        }

        if(is_array($admin)&&$admin['isEnabled']!=0){
            if(Md5($psd)==$admin['password']){
                //成功登陆，记录操作
//                session('id',$admin['id']);
//                session('user_name',$admin['user_name']);
//                session('user_uid',$group['id']);
                $auth=[
                    'id'=>$admin['id'],
                    'username'=>$admin['user_name'],
                    'userrule'=>$group['id'],
                    'region'=>1
                ];
                session('auth',$auth);

                //更新用户信息
                $loginTime=date("Y-m-d H:i:s");
                $loginIp=$this->getIp();
                db('admin')->where('id',$admin['id'])->update(['loginTime'=>$loginTime,'loginIp'=>$loginIp]);
                return $admin['id'];
            }else{
                return -1;
            }
        }else{
            return -2;
        }

    }



    /**
     * [getIp 获取注册Ip]
     * @return string
     */
    public function getIp(){
        //strcasecmp 比较两个字符串，不区分大小写。返回0，<0,>0.
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
            $ip=getenv('HTTP_CLIENT_IP');
        }elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
            $ip=getenv('HTTP_X_FORWARDED_FOR');
        }elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown')){
            $ip=getenv('REMOTE_ADDR');
        }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
        return $res;
    }


    /**
     * @return \think\response\View
     */

    public function livelog(){
        //清除所有的session
        session(null);
        return view('Login/login');
    }
}