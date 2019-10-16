<?php
namespace app\handheld\controller;
use think\Request;
use think\Db;
use think\Loader;
use PHPZip\PHPZip;
use PHPZip\PHPZip1;

class User extends Base
{
	//登录接口
    public function logo_on(){
    	if(request()->isPost()){
    		$data=input('post.');
    		//Inspection_account,巡检系统用户表
    		if($data['user']=='great'){	  //超级用户
    			while ($data['password']==123456) {
    				$this->get_duty_finished('1','登录成功');	
    			}	
                $this->ajax_return('-1','账号密码不匹配');
    		}else{    //普通用户
    			$res=db('inspection_account')->where(['user'=>$data['user']])->limit(1)->select();
    			if(!empty($res)){
    				while ($res[0]['password']==md5($data['password'])) {
                        $returndata['user']=$res[0]['user'];
                        $returndata['workerID']=$res[0]['workerID'];

                        //账户信息存到session中,有效期为1天
                        //session('user',$returndata['user'],3600*24);
                        //session('workerID',$returndata['workerID'],3600*24);

                        $this->ajax_return('1','登录成功',$returndata);
    				}
    				$this->ajax_return('1','账号密码不匹配');
    			}else{
                    $this->ajax_return('-1','用户名不正确');

    			}
    		}

    	}
    }


    //获取主界面,获取当天的巡检计划以及
    public function get_duty(){
        if(request()->isPost()){
            //根据传过来的workerID获取巡检班组的id
            $data=input('post.');
            $workerID=$data['workerID'];
            $today = strtotime(date("Y-m-d"),time());   //获取当日零点时间戳
            $todayEnd = $today+60*60*24;                //获取当日24点时间戳
            $map['d.workerID']=array('eq',$workerID);
            $map['d.state']=array('eq',1);
            $map['I.state']=array('eq',1);
            $duty=db('inspection_duty')
                ->alias('d')
                ->field('d.planID,d.workerID,d.state as duty_status,d.create_date,I.*')
                ->join('inspection_plan I','d.PlanID=I.list')
                ->where(['d.workerID'=>$workerID])
                ->order('d.planID' ,'desc')
                ->select();

             //var_dump($duty);   
            foreach ($duty as $key => $value) {
                //这一段代码很傻逼，但是没想到其他的方法了，先转成时间戳作比较，然后在转回去
                // $duty[$key]['start_date']=strtotime($duty[$key]['start_date']);//转换成时间戳
                // if($duty[$key]['start_date']<$today || $duty[$key]['start_date']>$todayEnd){
                //     unset($duty[$key]);
                //     continue;
                // }
                // $duty[$key]['start_date']=date("Y-m-d H:i:s",$duty[$key]['start_date']);
                //获取完成状态
                $duty[$key]['duty_status']=$duty[$key]['duty_status']==1?'已完成':'未完成';       
            }

           

            if(!empty($duty[0])){
                $this->ajax_return('1','获取巡检计划成功',$duty);
            }else{
                 $this->ajax_return('1','没有该人员的巡检');
            }

        }else{
             $this->ajax_return('0','请求参数异常');
        }

    }

 

    //获取巡检界获取标点
    public function get_duty_points(){
        if(request()->isPost()){
            $data=input('post.');
            $routeID=$data['routeID'];//巡检路径id
            //如果是未完成状态
            session('mapstatus','false');
            $duty_route=db('inspection_route')->where(['list'=>$routeID])->select();
            //获取list的值
            $routelist=$duty_route[0]['list'];//用于获取对应点的地址
            //数组重组，改造数组,默认的最高100条
            $templist=array();
            for($i=1;$i<99;$i++){
                if(!empty($duty_route[0]['point'.$i])){
                    $templist[$i]['point']=$i;
                    $templist[$i]['arrive_time']=$duty_route[0]['arrive_time'.$i];
                    $templist[$i]['leave_time']=$duty_route[0]['leave_time'.$i];
                    $templist[$i]['routeurl']=$this->get_routeurl($routelist,$i);
                    if(empty($templist[$i]['routeurl'])){
                        $templist[$i]['routeurl']='';
                    }
                    unset($duty_route[0]['point'.$i]);
                    unset($duty_route[0]['arrive_time'.$i]);
                    unset($duty_route[0]['leave_time'.$i]);

                }else{
                    unset($duty_route[0]['point'.$i]);
                    unset($duty_route[0]['arrive_time'.$i]);
                    unset($duty_route[0]['leave_time'.$i]);
                }

            }
            $templist=array_values($templist);  //去除数组的建
            $returnlist=$duty_route[0];
            $returnlist['pointList']=$templist; //合并数组
            if($duty_route){
                 session('mapstatus','true');
                $this->ajax_return('1','获取巡检路线点成功',$returnlist);
            }else{

                $this->ajax_return('1','没有该路线点');
            }

        }else{
             $this->ajax_return('0','请求参数异常');
        }

    }

    //获取巡检界面巡检点列表，获取地图 返回下载地址
    public function get_duty_map(){


    

    } 


    //获取所有的地图
    public function get_all_routeurl(){
        if(request()->isPost()){
            $data=input('post.');
            $List=$data['list'];
            $res=$this->get_routeurl($List);
            if(empty($res)){
                 $this->ajax_return('1','该巡检路线图为空');
            }else{
                 $this->ajax_return('1','获取所有的地图成功',$res);
            }
        }else{
            $this->ajax_return('1','参数异常,无法获取数据');
        }
    }



    //开始巡检任务（）
    public function confirm_duty(){
        //任务序号，开始巡检日期
        if(request()->isPost()){
            $data=input('post.');
            $status=0;
            $dutyID=$data['dutyID'];//巡检任务id
            $begintime=date("Y-m-d H:i:s",time());
            $res= db('inspection_carry_out')->insert(['dutyID'=>$dutyID,'carry_out_datetime'=>$begintime]);
            //更新巡检状态为巡检中
            $resss=db('inspection_duty')->where('list',$dutyID)->update(['state'=>2]);
            $status=db('inspection_duty')->where(['list'=>$dutyID])->find();
           if($res){
                $this->ajax_return('1','开始巡检任务',$status['state']);
           }else{
                $this->ajax_return('1','参数异常，无法开始巡检');
           }

        }else{
             $this->ajax_return('0','请求参数异常');
        }
    }

    //获取巡检点检查信息，根据IC卡号进行获取
    public function get_point_details(){
        if(request()->isPost()){
            $data=input('post.');
            $IC=$data['IC'];    //获取传过来的ic卡
            $point_details=db('inspection_point')->where(['IC'=>$IC])->select();
            //重组数组
                $templist=array();
                for($i=1;$i<21;$i++){

                    if($point_details[0]['check'.$i]==''){

                        unset($point_details[0]['process'.$i]);
                        unset($point_details[0]['check'.$i]);
                        unset($point_details[0]['range'.$i]);
                        unset($point_details[0]['update'.$i]);
                        unset($point_details[0]['state'.$i]);
                    }else{
                        $templist[$i]['process']=$point_details[0]['process'.$i];
                        $templist[$i]['check']=$point_details[0]['check'.$i];
                        $templist[$i]['range']=$point_details[0]['range'.$i];
                        $templist[$i]['update']=$point_details[0]['update'.$i];
                         $templist[$i]['state']=$point_details[0]['state'.$i];
                        unset($point_details[0]['process'.$i]);
                        unset($point_details[0]['check'.$i]);
                        unset($point_details[0]['range'.$i]);
                        unset($point_details[0]['update'.$i]);
                        unset($point_details[0]['state'.$i]);
                    }

                }
                $templist=array_values($templist);  //去除数组的建
                $returnlist=$point_details[0];
                $returnlist['pointList']=$templist; //合并数组

            if($point_details){
                if($point_details[0]['point_state']==0){
                    $this->ajax_return('-1','该巡检点已被弃用',$returnlist);
                }

                $this->ajax_return('1','获取巡检点信息成功',$point_details[0]);
           }else{
                $this->ajax_return('1','数据异常，无法获取巡检点信息');
           }
        }else{
             $this->ajax_return('0','请求参数异常');
        }
    }


    //巡检完毕后对巡检结果进行提交
    public function upload_duty_info(){
        if(request()->isPost()){
            //PointID 巡检id，
            $data=input('post.');
            //判断是新增还是更新
            $res=db('inspection_report')->where(['pointID'=>$data['pointID']])->select();
            $data['report_date']=date("Y-m-d H:i:s",time());    //完成时间
            if(!empty($res)){
                 $data['list']=$res[0]['list'];
                 $res=db('inspection_report')->update($data);
                 $ids=$data['list'];
            }else{
                //生成巡检报告inspection_report
                //$ids=Db::name('inspection_report')->insert($data)->getLastInsID();
                $ids=Db::name('inspection_report')->insertGetId($data);
            }
            $id['id']=$ids;
            if($ids){
                  $this->ajax_return('1','巡检点上报完成',$id);
            }else{
                 $this->ajax_return('1','参数异常，巡检点上报失败');
            }
  
        }else{
             $this->ajax_return('0','请求参数异常');
        }
    
    }

    //对所有的结果进行提交
    public function upload_all_info(){
        //更新inspection_report
        if(request()->isPost()){

            $data=input('post.');
            $data['carry_out_datetime']=date("Y-m-d H:i:s");
            $list=db('inspection_report')->where(['list'=>$data['dutyID']])->select();
            $res=db('inspection_carry_out')->where(['list'=>$list[0]['list']])->update($data);
            //更新巡检状态为巡检中
            $resss=db('inspection_duty')->where('list',$dutyID)->update(['state'=>1]);
            $status=db('inspection_duty')->where(['list'=>$dutyID])->find();
            if($res){
                $this->ajax_return('1','成功提交',$status['state']);
            }else{
                 $this->ajax_return('1','提交失败');
            }
        }else{
             $this->ajax_return('0','请求参数异常');
        }
    }



    //获取已经完成的巡检点信息,根据任务id dutyID 获取任务完成的详情
    public function get_duty_finished(){
        if(request()->isPost()){
            $data=input('post.');
            $dutyID=$data['dutyID'];
            $duty_finished=db('inspection_carry_out')
                ->alias('c')
                ->field('c.accountID ,c.carry_out_datetime,c.remark,c.state,r.*')
                ->join('inspection_report r','c.list=r.carry_outID')
                ->where('c.dutyID','=',$dutyID)
                ->select();
            if($duty_finished){
                //数组重组
                $templist=array();
                for($i=1;$i<21;$i++){
                    if($duty_finished[0]['result'.$i]==''){

                        unset($duty_finished[0]['result'.$i]);
                        unset($duty_finished[0]['memo'.$i]);
                        unset($duty_finished[0]['photo'.$i]);
                       
                    }else{
                        $templist[$i]['result']=$duty_finished[0]['result'.$i];
                        $templist[$i]['memo']=$duty_finished[0]['memo'.$i];
                        $templist[$i]['photo']=$duty_finished[0]['photo'.$i];
                        unset($duty_finished[0]['result'.$i]);
                        unset($duty_finished[0]['memo'.$i]);
                        unset($duty_finished[0]['photo'.$i]);
                    }
                }
                 $templist=array_values($templist);  //去除数组的建
                $returnlist=$duty_finished[0];
                $returnlist['pointList']=$templist; //合并数组

                $this->ajax_return('1','获取巡检结果成功',$duty_finished[0]);
            }else{
                $this->ajax_return('1','获取巡检结果失败');
            }

        }else{
             $this->ajax_return('0','请求参数异常');
        }
    }

    // 巡检执行异常记录，记录巡检人员巡检计划执行情况  inspection_carry_out_erro
    public function upload_carryout_erro(){
        if(request()->isPost()){
            $data=input('post.');
            $data['create_datetime']=date("Y-m-d H:i:s",time());
            $data['carry_outID']=$data['carry_outID'];  //发现异常的巡检执行ID
            $data['pointID']=$data['pointID'];      //异常所属巡检点ID
            $data['reportID']=$data['reportID'];    //upload_duty_info接口返回id
            $data['arrive_time']=$data['arrive_time'];//实际开始时间
            $data['leave_time']=$data['leave_time'];
            $data['describe']=$data['describe'];

            $res=db('inspection_carry_out_erro')->insert($data);
            if($res){
                $this->ajax_return('1','巡检人员巡检计划异常上报成功');
            }else{
                $this->ajax_return('1','巡检人员巡检计划异常上报失败');
            }

        }else{
             $this->ajax_return('0','请求参数异常');
        }

    }


    //巡检异常表，记录巡检中发现的异常情况；

    public function upload_check_erro(){
        if(request()->isPost()){
            $data=input('post.');
            $data['save_datetime']=date("Y-m-d H:i:s",time());
            $data['carry_outID']=$data['carry_outID'];  //发现异常的巡检执行ID
            $data['pointID']=$data['pointID'];      //异常所属巡检点ID
            $data['itemID']=$data['itemID'];    //itemID
            $data['describe']=$data['describe'];

            $res=db('inspection_check_erro')->insert($data);
            if($res){
                $this->ajax_return('1','巡检异常上报成功');
            }else{
                $this->ajax_return('1','巡检异常上报失败');
            }

        }else{
             $this->ajax_return('0','请求参数异常');
        }

    }






    function get_routeurl($list='',$id=''){
        $imgurl=array();
        if($id==''&&$list!=''){
            //获取某个文件下所有的地图信息
            $dir=ROOT_PATH.'/public/map/List'.$list;
            $sitepath='http://139.224.8.92/zhaowei/HandheldMachine/public/map/List'.$list.'\\';
            //遍历文件夹下所有文件
            if (false != ($handle = opendir ( $dir ))) {
                $i = 0;
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && !is_dir($dir.'/'.$file)) {
                        $imgurl[]=$sitepath . $file ;

                    }
                }
                closedir($handle);
            }
           
            
        }else{  //获取某个文件夹下的指定文件
                //只做常见的三种处理
            //http://139.224.8.92/zhaowei/HandheldMachine/public/map/List1/img1.png
            $dir='http://139.224.8.92/zhaowei/HandheldMachine/public/map/List'.$list.'/'.'img'.$id.'.png';
            $dir1='http://139.224.8.92/zhaowei/HandheldMachine/public/map/List'.$list.'/'.'img'.$id.'.jpeg';
            $dir2='http://139.224.8.92/zhaowei/HandheldMachine/public/map/List'.$list.'/'.'img'.$id.'.gif';
            if(@fopen($dir,'r')){

                $imgurl=$dir;

            } 
           if(@fopen($dir1,'r')){
                
                $imgurl=$dir1;

            } 

            if(@fopen($dir2,'r')){
                
                $imgurl=$dir2;

            } 
            

        }

           // print_r($dir);

        return $imgurl;


    }





    
}
