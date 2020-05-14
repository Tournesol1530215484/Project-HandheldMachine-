<?php
namespace  app\admin\controller;

use think\facade\View;
use think\Facade\Db;

class Admin extends adminBase{

    public function Adminlist(){
        $action='';
        $insert=array();
        $where=array();
        $data=request()->param();
        $action=isset($data['action'])?$data['action']:'display';
        if($action=='adminadd'){
            $group=Db::table('jan_feng_ye_auth_group')->select();
            View::assign([
                'Group'=>$group,
            ]);
            return View::fetch('Adminadd');
        }elseif ($action=='addadmininfo'){
               $msg=$data['data'];
               $insertdata['username']=trim($msg['username']);
               $RoleInfo=Db::table('jan_feng_ye_admin')->where('username',$insertdata['username'])->find();
               if($RoleInfo){
                   $this->return_msg(200,'用户已存在');
               }
               $insertdata['email']=trim($msg['email']);
               $insertdata['psd']=trim($msg['pass']);
               $insertdata['regTime']=time();
               $id=Db::table('jan_feng_ye_admin')->insertGetId($insertdata);
               if($id){
                   $insert['uid']=$id;
                   $insert['group_id']=$msg['group_id'];
                   Db::table('jan_feng_ye_auth_group_access')->insertGetId($insert);
                  $this->return_msg(200,'ok',$id);
               }else{
                   $this->return_msg(200,'error',$id);
               }
        }elseif ($action=='del_all'){
            $id=implode(',',$data['ids']);
            $this->return_msg(200,'ok',$id);
        }elseif ($action=='admin_del'){
            $id=$data['id'];
            $this->return_msg(200,'ok',$id);
        }elseif($action=='admin_edit'){
            $id=$data['id'];
            $Admin=db::table('jan_feng_ye_admin')
                    ->field('a.*,g.group_id')
                    ->alias('a')
                    ->join('jan_feng_ye_auth_group_access g','a.id=g.uid')
                    ->where('id',$id)->find();
            $group=Db::table('jan_feng_ye_auth_group')->select();

            View::assign([
                'Group'=>$group,
                'Admin'=>$Admin
            ]);

            return View::fetch('Adminedit');
        }elseif ($action=='editadmininfo'){
            $msg=$data['data'];
            $insertdatas=array();
            $insertdata['id']=trim($msg['id']);
            $insertdata['username']=trim($msg['username']);
            $insertdata['email']=trim($msg['email']);
            $insertdata['psd']=trim($msg['pass']);
            $red=Db::table('jan_feng_ye_admin')->update($insertdata);
            if($red){
                $insertdatas['group_id']=trim($msg['group_id']);
                Db::table('jan_feng_ye_auth_group_access')->where('uid',$insertdata['id'])->update($insertdatas);
                $this->return_msg(200,'ok',$msg);
            }else{
                $this->return_msg(200,'error',$msg);
            }

        }elseif ($action=='upstatus'){
            $changeInfo['status']=$data['status']==1?0:1;
            $res=Db::table('jan_feng_ye_admin')->where('id',$data['id'])->update($changeInfo);
            if($res){
                $this->return_msg(200,'ok');
            }else{
                $this->return_msg(200,'error');
            }
        }elseif ($action=='searchinfo'){
            if(!empty($data['start'])){
                $where[] = ['regTime','>',strtotime($data['start'])];
            }
            if(!empty($data['end'])){
                $where[] = ['regTime','<',strtotime($data['end'])];
            }
            if(!empty($data['username'])){
                $where[] = ['title','like',$data['username']];
            }


        }
        $Admins=Db::table('jan_feng_ye_admin')
            ->field('a.*,g.title')
            ->alias('a')
            ->join('jan_feng_ye_auth_group_access ga','ga.uid=a.id')
            ->join('jan_feng_ye_auth_group g','ga.group_id=g.id')
            ->where($where)->paginate(5,false,['query'=>request()->param()]);
        $Admin = $Admins->items();
        foreach ($Admin as $k => $v){
            $Admin[$k]['regTime'] = date("Y-m-d",$v['regTime']);
        }
        View::assign([
            'Admins'=>$Admins,
            'Admin'=>$Admin,

        ]);
        return View::fetch('Adminlist');
    }

   //角色管理--用户管理
   public function Adminrole(){
       $action='';
       $insert=array();
       $where=array();
       $data=request()->param();
       $action=isset($data['action'])?$data['action']:'display';
       if($action=="adminroleadd"){
           $Cates=Db::table('jan_feng_ye_auth_cate')->select();
           foreach ($Cates as $k=>$v){
               $Rule[$v['id']]=$v;
               $Rule[$v['id']]['child']=Db::table('jan_feng_ye_auth_rule')->where('pid',$v['id'])->select();
           }
           View::assign([
               'Rule'=>$Rule,
               'Cate'=>$Cates
           ]);
           return View::fetch('Roleadd');

       }elseif ($action=='addrule'){
           $msg=$data['data'];
           $insertdata['title']=$msg['name'];
           $res=Db::table('jan_feng_ye_auth_group')->where('title',$insertdata['title'])->select();
           if($res){
               $this->return_msg(200,'error');
           }
           $temparray=array_unique($msg['id']);
//           unset($temparray[0]);
           $insertdata['rules']=implode(',',$temparray);
           $insertdata['desc']=$msg['desc'];
           $red=Db::table('jan_feng_ye_auth_group')->insert($insertdata);
           if($red){
               $this->return_msg(200,'ok',$msg);
           }else{
               $this->return_msg(200,'error',$msg);
           }
       }elseif ($action=='del_all'){
           $id=implode(',',$data['ids']);
           $this->return_msg(200,'ok',$id);
       }elseif ($action=='searchinfo'){
            if(!empty($data['username'])){
                $where[] = ['title','like',$data['username']];
            }
       }elseif ($action=='upstatus'){
           $changeInfo['status']=$data['status']==1?0:1;
           $res=Db::table('jan_feng_ye_auth_group')->where('id',$data['id'])->update($changeInfo);
           if($res){
               $this->return_msg(200,'ok');
           }else{
               $this->return_msg(402,'error');
           }
       }elseif ($action=='role_del'){
           $id=$data['id'];
           $this->return_msg(200,'ok',$id);
       }elseif ($action=='rol_edit'){
           $id=$data['id'];
           $RoleInfo=Db::table('jan_feng_ye_auth_group')->where('id',$id)->find();
           $Cates=Db::table('jan_feng_ye_auth_cate')->select();
           foreach ($Cates as $k=>$v){
               $Rule[$v['id']]=$v;
               $Rule[$v['id']]['child']=Db::table('jan_feng_ye_auth_rule')->where('pid',$v['id'])->select();
           }
           View::assign([
               'Rule'=>$Rule,
               'Cate'=>$Cates,
               'RoleInfo'=>$RoleInfo
           ]);

           return View::fetch('Roledit');
       }elseif ($action=='editruleinfo'){
           $msg=$data['data'];
           $insertdata['title']=$msg['name'];
           $insertdata['id']=$msg['id'];
           $temparray=array_unique($msg['rid']);
//           unset($temparray[0]);
           $insertdata['rules']=implode(',',$temparray);
           $insertdata['desc']=$msg['desc'];
           $red=Db::table('jan_feng_ye_auth_group')->update($insertdata);
           if($red){
               $this->return_msg(200,'ok',$msg);
           }else{
               $this->return_msg(200,'error',$msg);
           }
       }
       $group=Db::table('jan_feng_ye_auth_group')->where($where)->paginate(5,false,['query'=>request()->param()]);
       View::assign([
           'Group'=>$group,
       ]);
       return View::fetch('Adminrole');
   }


    //权限分类栏目
   public function Admincate(){
        $action='';
        $insert=array();
        $data=request()->param();
        $action=isset($data['action'])?$data['action']:'display';
        if($action=='cateadd'){
            if(!empty($data['catename'])){
                $id=Db::query("select  id from jan_feng_ye_auth_cate where  catename = '".$data['catename']."'");
                if(!$id){
                    $insert['catename']=$data['catename'];
                    Db::table('jan_feng_ye_auth_cate')->insert($insert);
                }
            }
        }elseif ($action=='del_all'){   //全部删除
              $id=implode(',',$data['ids']);
              $this->return_msg(200,'ok',$id);

        }elseif ($action=='del'){   //删除单个数据    ---记得吧所有的子数据全部删除
            $id=$data['id'];
            $this->return_msg(200,'ok',$id);
        }elseif ($action=='admincateedit'){
            $id=$data['id'];
            $res=Db::query("select  * from jan_feng_ye_auth_cate where  id = ".$data['id']);
            $Cate=$res[0];
            View::assign([
                'Cate'=>$Cate,
            ]);
            return View::fetch('Admincatedit');
        }elseif ($action=='admincateeditinfo'){
            $updateinfo['id']=$data['id'];
            $updateinfo['catename']=$data['catename'];
            Db::table('jan_feng_ye_auth_cate')->update($updateinfo);
        }
       $Cate=$Allmembers= Db::table('jan_feng_ye_auth_cate')->paginate(5,false,['query'=>request()->param()]);
       View::assign([
           'Cate'=>$Cate,
       ]);

       return View::fetch('Admincate',['Cate'=>$Cate]);
    }


    //权限管理
    public function Adminrule(){
        $action='';
        $insert=array();
        $data=request()->param();
        $action=isset($data['action'])?$data['action']:'display';
        if($action=='adminruleinsert'){
            if((!empty($data['cateid']))&&(!empty($data['actionsinfo']))&&(!empty($data['title']))){
                $insert['pid']=$data['cateid'];
                $insert['name']=$data['actionsinfo'];
                $insert['title']=$data['title'];
                Db::table('jan_feng_ye_auth_rule')->insert($insert);
            }
        }elseif ($action=='del_all'){
            $id=implode(',',$data['ids']);
            $this->return_msg(200,'ok',$id);
        }elseif ($action=='del'){
            $id=$data['id'];
            $this->return_msg(200,'ok',$id);
        }elseif ($action=='adminruleedit'){
            $id=$data['id'];
            $res=Db::query("select  * from jan_feng_ye_auth_rule where  id = ".$data['id']);
            $Rule=$res[0];
            $Cate= Db::table('jan_feng_ye_auth_cate')->select();
            View::assign([
                'Cate'=>$Cate,
                'Rule'=>$Rule,
            ]);
            return View::fetch('Adminruledit');
        }elseif ($action=='adminruleeditinfo'){
            $insert['pid']=$data['pid'];
            $insert['id']=$data['id'];
            $insert['name']=$data['name'];
            $insert['title']=$data['title'];
            Db::table("jan_feng_ye_auth_rule")->update($insert);
        }
        $Rule= Db::table('jan_feng_ye_auth_rule')
                ->alias('r')
                ->leftJoin('jan_feng_ye_auth_cate c ','c.id = r.pid')
                ->field('r.*,c.catename')
                ->paginate(5,false,['query'=>request()->param()]);
        $Cate= Db::table('jan_feng_ye_auth_cate')->select();

        View::assign([
            'Cate'=>$Cate,
            'Rule'=>$Rule
        ]);

        return View::fetch('Adminrule',['Rule'=>$Rule]);
    }


//    public function MemberAdd(){
//        $group=Db::table('jan_feng_ye_auth_group')->select();
//        View::assign([
//            'Group'=>$group,
//        ]);
//        return View::fetch('Adminadd');
//    }

//    public function Roleadd(){
//            $Cates=Db::table('jan_feng_ye_auth_cate')->select();
//            foreach ($Cates as $k=>$v){
//                $Rule[$v['id']]=$v;
//                $Rule[$v['id']]['child']=Db::table('jan_feng_ye_auth_rule')->where('pid',$v['id'])->select();
//            }
//
//        View::assign([
//            'Rule'=>$Rule
//        ]);
//        return View::fetch('Roleadd');
//    }

//    /**
//     * 异步处理数据
//     */
//    public function AjaxRoleadd(){
//        $data = request()->param();
//        if(!empty($data) && count($data)>0){
//
//           if($data['action']=='addrule'){
//               $msg=($data);
//
//               $insertdata['title']=$msg['name'];
//               $insertdata['rules']=implode(',',$msg['ids']);
//               $insertdata['desc']=$msg['desc'];
//               $red=Db::table('jan_feng_ye_auth_group')->insert($insertdata);
//
//               if($red){
//                   return $this->Admincate();
//                   //return $this->success('success','/Admin/Adminrole');
////                   redirect('/Admin/Roleadd')->send();
//               }else{
//                   return $this->Admincate();
//               }
//           }elseif ($data['action']=='addadmin'){
//               $msg=$data['data'];
//               $insertdata['username']=$msg['username'];
//               $insertdata['email']=$msg['email'];
//               $insertdata['psd']=$msg['pass'];
//               $insertdata['regTime']=time();
//               $id=Db::table('jan_feng_ye_admin')->insertGetId($insertdata);
//               if($id){
//                   $insert['uid']=$id;
//                   $insert['group_id']=$msg['group_id'];
//                   Db::table('jan_feng_ye_auth_group_access')->insertGetId($insert);
//                   return $this->success('ok','/Admin/Roleadd');
//               }else{
//                   return $this->success('error','/Admin/Roleadd');
//               }
//
//
//           }
//
//        }
//        return $this->return_msg(200,'error');
//    }

}
