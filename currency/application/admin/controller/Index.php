<?php
namespace app\admin\controller;
use app\admin\controller\Coom;
use Catetree\Catetree;
use think\Db;
class Index extends Coom
{



    public function index()
    {
         $Info= Db::query('select * from wb_fz_member'); 

        $this->assign([
          'Info'=>$Info
        ]);


       	return view();
       
    }




    public function addadmin(){
       // echo "add";
    }


    public function editadmin(){
    	if(request()->isPost()){
    		$data=input('post.');

            print_r($data);
            die;
    		// if ($data['fun']=="clickedit") {	//根据传过来的id进行修改数据某一个状态
      //           $data['statu']=$data['statu']==1?0:1;
      //           $sql="update wb_fz_member set status = ".$data['statu']. " where id =" .$data['atid'];
      //           $Info = Db::query($sql);
    		// 	if($Info!==false){
      //               $this->ajax_return(1,"success",$data['statu']);
      //           }else{
      //                $this->ajax_return(0,"error",$data['statu']);
      //           }

    		// }elseif ($data['fun']=="editinfo") {	//根据id修改所有的数据
    		// 	//在这里修改所有的数据
    			
    		// 	$this->success("根据id修改所有的数据",'admin/Index/Index');

    		// }else{	//其他的修改

    		// 	$this->ajax_return(0,"error");
    		// }

    			
    	}

        $cate=new Catetree();
        $date=db('article_cat')->field('cat_id,cat_name,pid')->select();
        $Cate=$cate->ChildTree2($date);
        
    	$id=input('id');	//获取要修改的数据信息
        $Article=db('article')->find($id);
        $this->assign([
            'Cate'=>$Cate,
            'Article'=>$Article
        ]);

    	return view("/index/editadmin");
    }


    public function Deladmin(){
    	$id=input('id');	//获取要修改的数据信息
    	$this->success("删除数据成功",'admin/Index/Index');
    }


    //数据导出
    public function downExcel() {
    
                $strTable = '<table width="500" border="1">';
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">ID</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="100">真实名称</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">银行信息</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">手机号</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">微信</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">是否启用</td>';
                $strTable .= '</tr>';
                // $user_ids = I('user_ids');
                // if ($user_ids) {
                //     $condition['user_id'] = ['in', $user_ids];
                // } else {
                //     $mobile = I('mobile');
                //     $email = I('email');
                //     $mobile ? $condition['mobile'] = $mobile : false;
                //     $email ? $condition['email'] = $email : false;
                // };
                $count = DB::name('fz_member')->count();
                $p = ceil($count / 5000);
                for ($i = 0; $i < $p; $i++) {
                    $start = $i * 5000;
                    $end = ($i + 1) * 5000;
                    $userList = DB::name('fz_member')->order('id')->limit($start,5000)->select();
                    if (is_array($userList)) {
                        foreach ($userList as $k => $val) {
                            if ($val['status']==1) {
                                $val['status']="启用";
                            }else{
                                 $val['status']="禁用";
                            }
                            $strTable .= '<tr>';
                            $strTable .= '<td style="text-align:center;font-size:12px;">' . $val['id'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['realname'] . ' </td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['bank'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['mobile'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['weixin'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['status'] . '</td>';
                            // $strTable .= '<td style="text-align:left;font-size:12px;">' . date('Y-m-d H:i', $val['reg_time']) . '</td>';
                           
                        }
                        unset($userList);
                    }
                }
                $strTable .= '</table>';
                $this->downloadExcel($strTable, '用户信息导出表' . $i);
                exit();
            
    }



    public function selectInfo(){

        $Info= Db::query('select * from wb_fz_member '); 


        $this->assign([
          'Info'=>$Info
        ]);


        return view("Index");

    }





}
