<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/29
 * Time: 8:41
 */
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
use app\admin\model\AuthGroup as AuthGroupModel;
use think\Validate;
use think\Db;
use Catetree\Catetree;

class Admin extends Coom{

    /**
     * [addAdmin 添加管理员]
     * @return string
     */
    public function addAdmin(){

        //所属用户组
        $Auth_group=db('auth_group')->where('status',1)->select();
        $this->assign('Auth_group',$Auth_group);
        return view('Admin/addAdmin');
    }


    /**
     * [lisAdmin 管理员列表]
     * @return string
     */
    public function lisAdmin(){
       $AdminRes=db('admin')->select();
        //连表查询用户信息以及所属分组
        foreach($AdminRes as $key =>$value){
            $res=db('auth_group_access')
                ->alias('a')
                ->join('__AUTH_GROUP__ b','a.group_id=b.id')
                ->where('a.uid',$value['id'])
                ->value('title');
            $AdminRes[$key]['group']=$res;
        }
        $this->assign('AdminRes',$AdminRes);
        return view('Admin/lisAdmin');
    }

    /**
     * [editAdmin 修改管理员]
     * @return string
     */

    public function editAdmin(){
//        //找到要修改的管理员
//        $admin=db('admin')->find($id);
//        //判断修改后和修改前的差别
//        if(request()->isPost()){
//            $date=input('post.');
//            $Admin=new AdminModel();
//            $res=$Admin->editAdmin($date,$admin);
//            if($res=='2'){
//                $this->error('管理员名字不得为空');
//            }else if($res!==false){
//                $this->success('修改管理员成功',url('Admin/lisAdmin'));
//            }else{
//                $this->error('修改管理员失败');
//            }
//            return ;
//        }
//        if(!$admin){
//            $this->error('该管理员不存在');
//        }
//        else{
//            $this->assign('AdminRes',$admin);
//
//        }
//
        //找到这个用户信息
        $id=input('id','0','int');
        if($id==0){
            $this->error('参数错误');
        }else{
            //获取用户的所属用户组
            $AdminRes=db('admin')->find($id);
            $res=db("auth_group_access")
                ->alias("a")
                ->join("__AUTH_GROUP__ b","a.group_id=b.id")
                ->where('a.uid',$id)
                ->value('title');
            $AdminRes['group_id']=$res;
        }
        $Auth_group=db('auth_group')->select();
        $this->assign([
            'Auth_group'=>$Auth_group,
            'AdminRes'=>$AdminRes,
        ]);

        return view('Admin/editAdmin');
    }
    /**
     * [SaveAdmin 保存管理员]
     * @return string
     */

    public  function SaveAdmin(){
        if(request()->isPost()){
            //验证规则
            $rule=[
                'group_id'=>['require','gt'=>'0'],
                'user_name'=>['require','max'=>'25'],
                'email'=>['email'],
                'password'=>['require','max'=>'12','min'=>'6'],
                'password_confirm'=>['require']

            ];
            //提示信息
            $msg=[
                'group_id'=>'所属用户组必须',
                'user_name.require'=>'用户名必须',
                'user_name.max'=>'用户名不得超过25个字符',
                'email.email'=>'邮箱格式不正确',
                'password.require'=>'密码必须',
                'password.max'=>'密码长度请保持在6-12位',
                'password.min'=>'密码长度请保持在6-12位',
                'password_confirm.require'=>'确认密码必须'
            ];

            //接收数据
            $date=[
                'group_id'=>input('Auth_group_id','','trim'),
                'user_name'=>input('user_name','','trim'),
                'email'=>input('email','','trim'),
                'password'=>input('password','','trim'),
                'password_confirm'=>input('password_confirm','','trim')

            ];

            $Validate=new Validate($rule,$msg);
            $result=$Validate->check($date);
            if(!$result){
                $error=$Validate->getError();
                $this->error("$error");
            }

            //判断用户是否存在
            $id=input('id','0','int');
            $AdminModel=new AdminModel();
            if($id){//有值
                if($this->CheckAdmin($date['user_name'])&&$id !=$this->CheckAdmin($date['user_name'])){
                    $this->error('该用户已经存在');
        }
         $date['password']=MD5($date['password']);
                $date['password_confirm']=MD5($date['password_confirm']);
                $res=$AdminModel->allowField(true)->save($date,['id'=>$id]);
            }else{
                if($this->CheckAdmin($date['user_name'])){
                    $this->error('改用户已经存在');
                }
                //添加用户
                $date['regTime']=date("Y-m-d H:i:s");
                $date['regIp']=$this->getIp();
                $date['password']=MD5($date['password']);
                $date['password_confirm']=MD5($date['password_confirm']);
                $res=$AdminModel->allowField(true)->save($date);
            }
            if($res==false){
                $this->error('操作失败');
            }

            if(!$id){
                $id=Db::name('admin')->getLastInsID();;
            }
            //角色更新
            $group_id=db('auth_group_access')->where('uid',$id)->value('group_id');
            if(empty($group_id)){
                db('auth_group_access')->insert(['group_id'=>$date['group_id'],'uid'=>$id]);
            }else if($date['group_id']!=$group_id){
                db('auth_group_access')->where('uid',$id)->update(['group_id'=>$date['group_id']]);
            }
            $this->success('添加管理员成功','Admin/lisAdmin');
        }

    }

    /**
     * [delAdmin 删除管理员]
     * @return string
     */
    public function delAdmin(){
        $id=input('id','0','int');
        if($id==0){
            $this->error('参数错误');
        }else{
            db('auth_group_access')->where('uid',$id)->delete();
            if(db('admin')->where('id',$id)->delete()){
                $this->success('删除管理员成功','Admin/lisAdmin');
            }else{
                $this->error('删除管理员失败');
            }
        }
    }


    /**
     * [getIp 获取注册Ip]
     * @return string
     */
    private function getIp(){
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
     * [CheckAdmin 检测用户名是否存在]
     * @return string
     *
     */

    protected  function CheckAdmin($name){
        $id=db('admin')->where('user_name',$name)->value('id');
        if(empty($id)){
            return 0;
        }else{
            return $id;
        }
    }

/*==============================================================================*/

    /**
     *
     * [lisRule 权限列表]
     * @return string
     *
     */

    public function  lisRule(){
        $Catetree=new Catetree();
        $rules=db('auth_rule')->select();
        $Rules=$Catetree->ChildTree($rules);
        $this->assign('Rules',$Rules);
        return view('Admin/lisRule');
    }

    /**
     *
     * [addRule 添加权限]
     * @return string
     *
     */

    public function addRule(){
        $Catetree=new Catetree();
        $rules=db('auth_rule')->select();
        $Rules=$Catetree->ChildTree($rules);
        $this->assign('Rules',$Rules);
        return view('Admin/addRule');
    }

    /**
     *
     * [editRule 编辑权限]
     * @return string
     *
     */

    public function editRule(){
        if(request()->isPost()){
            $res=db('auth_rule')->update(input('post.'));
            if($res){
                $this->success('修改成功',url('lisRule'));
            }else{
                $this->error('修改失败');
            }
        }
        $id=input('id','0','int');
        $rule=db('auth_rule')->where('id',$id)->find();
        $rules=db('auth_rule')->select();
        $Catetree=new Catetree();
        $Rules=$Catetree->ChildTree($rules);
        $this->assign([
            'Rules'=>$Rules,
            'rule'=>$rule
            ]
        );
        return view('Admin/editRule');
    }


    public function saveRule(){
        if(request()->isPost()){
            $date=input('post.');
            $res=db('auth_rule')->insert($date);
            if($res){
                $this->success('添加成功',url('lisRule'));
            }else{
                $this->error('添加失败');
            }

        }
    }


    /**
     *
     * [delRule 删除权限]
     * @return string
     *
     */
    public function delRule(){
        $id=input('id','0','int');
        $rules=db('auth_rule')->select();
        $Catetree=new Catetree();
        $Rules=$Catetree->ChildTree($rules,$id);
        $ids='';
        foreach($Rules as $key=>$value){
            $ids[]=$value['id'];
        }
        $ids[]=$id;
        $count=0;
        for($i=0,$len=count($ids);$i<$len;$i++){
            $res=db('auth_rule')->where('id',$ids[$i])->delete();
            if(!$res){
                $count++;
            }
        }
        if($count){
            $this->error('删除失败');
        }else{
            $this->success('删除成功',url('lisRule'));

        }
    }

/*==============================================================================*/

    /**
     * [lisGroup 角色列表]
     * @return string
     *
     */

    public function  lisGroup(){
        $Group=db('auth_group')->select();
        $this->assign('Group',$Group);
        return view('Admin/lisGroup');
    }

    /**
     * [AddGroup 角色添加]
     * @return string
     *
     */

    public function  addGroup(){
      if(request()->isPost()){
          $date=input('post.');
          $date['rules']=implode(',',$date['rules']);
          $date['status']=1;
          $res=db('auth_group')->insert($date);
            if($res){
                $this->success('添加用户组成功',url('lisGroup'));
            }else{
                $this->error('添加用户组失败');
          }
      }
        $Catetree=new Catetree();
        $Rules=db('auth_rule')->select();
        $Rules=$Catetree->ListToTreeMu($Rules ,0, 'id', 'pid', 'child');
        $this->assign([
            'Rules'=>$Rules
        ]);
        return view('Admin/addGroup');
    }


    /**
     * [EditGroup 角色修改]
     * @return string
     *
     */

    public function  EditGroup(){

        if(request()->isPost()){
            $date=input('post.');
            $date['rules']=implode(',',$date['rules']);
            $res=db('auth_group')->update($date);
            if($res){
                $this->success('修改成功',url('lisGroup'));
            }else{
                $this->error('修改失败');
            }
        }

        $id=input('id','0','int');
        $Rule=db('auth_group')->where('id',$id)->find();
        $Rule['rules']=explode(',',$Rule['rules']);
        $Catetree=new Catetree();
        $Rules=db('auth_rule')->select();
        $Rules=$Catetree->ListToTreeMu($Rules ,0, 'id', 'pid', 'child');
        $this->assign([
            // 'Group'=>$Group,
            'Rules'=>$Rules,
            'Rule'=>$Rule
        ]);
        return view('Admin/editGroup');
    }

    /**
     * [DelGroup 角色删除]
     * @return string
     *
     */

    public function  DelGroup(){
       input('id','0','int');
        $res=db('auth_group')->where('id',input('id'))->delete();
        if($res){
            $this->success('删除成功',url('lisGroup'));
        }else{
            $this->error('删除失败');
        }
    }

}
