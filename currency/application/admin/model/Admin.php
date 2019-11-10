<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/7/29
 * Time: 8:44
 */
namespace app\admin\model;
use think\Model;

class Admin extends Model{
    //操作数据库进行管理员的添加
    public function AddAdmin($data){
        //进行加密传输
        $data['password']=md5($data['password']);
        $data['password_confirm']=md5($data['password_confirm']);
        $result=$this->insert($data);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }

    //操作数据库进行管理员的查找
    public function lisAdmin(){
        return $this::paginate(15,false,[
            'type'=>'bootstrap',
            'var_page' => 'page',
        ]);
    }

    //操作数据库进行管理员的修改
    public function editAdmin($data,$admin){
            if(!$data['user_name']){
                return 2;//名字不得为空
            }
            if(!$data['password']){
                $data['password']=$admin['password'];
            }else{
                $data['password']=md5($data['password']);
            }
        //    db('auth_group_access')->where(array('uid'=>$data['id']))->update(['group_id'=>$data['group_id']]);
            return $this::update(['user_name'=>$data['user_name'],'password'=>$data['password']],['id'=>$data['id']]);
    }


    //操作数据库进行管理员的删除
    public function delAdmin($dataid){
        if($this->where('id',$dataid)->delete()){
            return 1;
        }else{
            return 0;
        }
    }
}
