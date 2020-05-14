<?php
namespace app\admin\controller;
use app\admin\controller\adminBase;
use app\api\controller\ExcelExport;
use think\App;
use think\Facade\Db;
use think\Request;
use PHPExcel;


use think\facade\View;
class Index extends adminBase {

    public  function Index(){
        return View::fetch('Index');
    }

    public function Statistics(){

        return View::fetch('Statistics');

    }

    //动态会员
    public  function  MemberDynamic(){
        return View::fetch('MemberDynamic');
    }

    //静态会员
    public  function  MemberStatic(){
        $condition=1;
        $Allmembers=Db::table('jan_feng_ye_member')
                    ->alias('m')
                    ->field('m.*')
                    ->where($condition)
                    ->paginate(5,false,['query'=>request()->param()]);
        View::assign([
            'Allmember'=>$Allmembers,
        ]);
        return View::fetch('MemberStatic',['Allmembers'=>$Allmembers]);
    }

    //静态会员
    public  function  MemberSeacher(){
        $data = request()->param();
        //$param["searchword"] = $searchword;
        $condition=1;
        if(!empty($data['start'])){
            $data['start']=strtotime($data['start']);
            $condition.=' and m.createtime >'.$data['start'];
        }
        if(!empty($data['end'])){
            $data['end']=strtotime($data['end']);
            $condition.=' and m.createtime <'.$data['end'];
        }
        if(!empty($data['username'])){
            $condition.=" and m.nickname like '%".$data['username']."%' or m.realname like '%".$data['username']."%' or m.mobile like '%".$data['username']."%'";
        }

        $Allmembers=Db::table('jan_feng_ye_member')
            ->alias('m')
            ->field('m.*')
            ->where($condition)
            ->paginate(['list_rows'=> 1,'query' => $data]);

        //打印数据
        if(!empty($data['satus'])){
            $excel = new ExcelExport($this->app);
            //$excel=new ExcelExport();     如果只是这个，会报错，因为initialize  可尝试一下
            $name='用户数据表';
            $header=['id','会员名称','价格'];
            $newdata = [];
            $data=$Allmembers=Db::table('jan_feng_ye_member')
                ->alias('m')
                ->field('m.*')
                ->where($condition)
                ->select();
            foreach ($data as $key=>$value){
                $newdata[$key]['id'] = $value['id'];
                $newdata[$key]['nickname'] = $value['nickname'];
                $newdata[$key]['mobile'] = $value['mobile'];
            }
            $excel->excelExport($name,$header,$newdata);
        }
        return View::fetch('MemberStatic',['Allmembers'=>$Allmembers]);
    }

    /**
     * 会员删除
     * @return \think\response\View
     */
    public  function  MemberDel(){
        $data=input();
        if($data['action']=='member_del'){
            //操作数据库
            $this->return_msg(200,'error');
        }elseif ($data['action']=='del_all'){
            $this->return_msg(402,'error');
        }

        //return \view('MemberDel');
    }

    //会员列表
    public function MemberList(){
        return \view('MemberList');
    }

    /**
     * 会员修改
     * @throws \think\db\exception\DbException
     */
    public function MemberEdit(){
        $data=input();
        if($data['action']=='upstatus'){    //修改状态
            $changeInfo['status']=$data['status']==1?0:1;
            $res=Db::table('jan_feng_ye_member')->where('id',$data['id'])->update($changeInfo);
            if($res){
                $this->return_msg(200,'ok');
            }else{
                $this->return_msg(402,'error');
            }

        }

    }




    public function MemberEdits(){
        $data=request()->param();

        $Member=Db::table('jan_feng_ye_member')->where('id',$data['id'])->find();
        View::assign([
            'Member'=>$Member,
        ]);
        return \view('MemberEdits');
    }

    public function MemberPassword(){
        if(request()->isPost()){
            var_dump(input('post.'));
        }
        return \view('MemberPassword');
    }




    /**
     * 会员添加
     */
    public function member_add(){

        return \view('MemberAdd');

    }

    public function Memberadd(){

        return $this->return_msg(200,'ok');
    }

    }