<?php
/**
 * Created by PhpStorm.
 * User: 王彬
 * Date: 2018/8/8
 * Time: 23:22
 */
namespace app\admin\controller;
use Catetree\Catetree;
use think\Controller;
use app\admin\model\Conf as ConfModel;
use think\Session;
use  think\Loader;
use app\admin\controller\Auth;
use PHPExcel;
use PHPExcel_IOFactory;

Loader::import('PHPExcel.Classes.PHPExcel');
Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');

class Coom extends Controller
{
	 function  _initialize()
    {
         if(!Session('auth.region')){
             $this->error('您尚未登录系统',url('login/index'));
         }

         //获取所有配置信息
          $this->getConf();
         //获取我的权限
//         $Catetree=new Catetree();
//         $myrule=db('auth_group')->where('id',SESSION('auth.userrule'))->value('rules');
//         $myrule=explode(',',$myrule);
//         $myrule=db('auth_rule')->where(['id'=>['in',$myrule]])->select();
//         $myrule=$Catetree->ListToTreeMu($myrule,0,'id','pid','child');
//         $module = strtolower(request()->module());
//         $controller = strtolower(request()->controller());
//         $action = strtolower(request()->action());
//         $nowUrl = $module.'/'.$controller.'/'.$action;
//         $this->assign('mymenus',$myrule);
//         $this->assign('module',$module);
//         $this->assign('controller',$controller);
//         $this->assign('action',$action);
//         $this->assign('nowUrl',$nowUrl);

//         Loader::import("org/Auth", EXTEND_PATH);
//         $auth=new \Auth();
//         $this->current_action = request()->module().'/'.request()->controller().'/'.lcfirst(request()->action());
//         $result = $auth->check($this->current_action,session('auth.id'));
////         if(!$result){
////             $this->error("对不起，您没有权限操作");
////         }

         Loader::import("Auth/Auth", EXTEND_PATH);
         $auth=new \Auth();
        // $this->current_action = request()->module().'/'.request()->controller().'/'.lcfirst(request()->action());
         $this->current_action = request()->module().'/'.request()->controller();
	       $result = $auth->check($this->current_action,session('auth.id'));
         if(!$result){
             $this->error('对不起，您没有查看权限',url('Index/index'));
         }

    }



  	//获取所有的配置项信息
  	public function getConf(){
  		$ConfModel=new ConfModel;
  		$AllConf=$ConfModel->GetConf();
      $this->assign('AllConf',$AllConf);
  	}

    //后台获取所有的参数
    public function getadminConf(){
        $ConfModel=new ConfModel;
        $AllConf=$ConfModel->GetConf();
        return $AllConf;
    }

    //js获取所有的配置参数
    public function getJsConf(){
        $ConfModel=new ConfModel;
        $AllConf=$ConfModel->GetConf();
        return $this->ajax_return(1,$AllConf);
    }




//    //获取我的权限
//    private function MyRule(){
//        $Catetree=new Catetree();
//        $myrule=db('auth_group')->where('id',SESSION('auth.userrule'))->value('rules');
//        $myrule=explode(',',$myrule);
//        $myrule=db('auth_rule')->where(['id'=>['in',$myrule]])->select();
//        $myrule=$Catetree->ListToTreeMu($myrule,0,'id','pid','child');
//        $module = strtolower(request()->module());
//        $controller = strtolower(request()->controller());
//        $action = strtolower(request()->action());
//        $nowUrl = $module.'/'.$controller.'/'.$action;
//        $this->assign('mymenus',$myrule);
//        $this->assign('module',$module);
//        $this->assign('controller',$controller);
//        $this->assign('action',$action);
//        $this->assign('nowUrl',$nowUrl);
//    }


         /**
      * code 状态码
      * msg  提示信息
      *   data 要返回的数据
      * 返回ajax格式
      */
    public function ajax_return($code='',$msg='',$data=''){
        error_reporting("E_ALL"); //可以避免范文报500的错误
            ini_set("display_errors", 1);
        $return_msg['code']=$code;
            $return_msg['msg']=$msg;
            $return_msg['data']=$data;
            echo json_encode($return_msg);die;

    }


    /**
     * 导出excel
     * @param $strTable 表格内容
     * @param $filename 文件名
     */
    public function downloadExcel($strTable,$filename)
    {
      header("Content-type: application/vnd.ms-excel");
      header("Content-Type: application/force-download");
      header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
      header('Expires:0');
      header('Pragma:public');
      echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
    }


    //封装方法，进行数据导出
    /**
     * 导出excel
     * @param $title 导出文件A2--Z2标题
     * @param $data 要导出的数据
     * @param $fileName 要导出的文件名称
     * @param $savePath 保存路径，可填空
     * @param $isDown 是否网页下载，选true
     */
  function exportExcel($title=array(), $data=array(), $fileName, $savePath, $isDown){
      //手动引入PHPExcel.php
      Loader::import('PHPExcel.Classes.PHPExcel');
      //引入IOFactory.php 文件里面的PHPExcel_IOFactory这个类
      Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
      $obj = new \PHPExcel();
      //横向单元格标识
      $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
      $obj->getActiveSheet(0)->setTitle('gaikuang_workpiece');   //设置sheet名称
      $_row = 1;   //设置纵向单元格标识
      $_len=1;    //设置横向单元格标识
      if($title){
          $_cnt = count($title);
          //$obj->getActiveSheet(0)->mergeCells('i'.$_row.':'.'k'.$_row);   //合并单元格
          $endnum=1;
          $end=$cellName[$_cnt-1].$endnum;
          $obj->getActiveSheet(0)->mergeCells('A1:'.$end); //合并单元格
          $obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, $fileName);  //设置合并后的单元格内容
          $_row++;
          $i = 0;
          //设置列标题开始的位置
          foreach($title AS $v){
            $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);
              $i++;
          }
          $_row++;
      }
   
   
      //填写数据
      if($data){
          $i = 0;
          foreach($data AS $_v){
              $j = 0;
              foreach($_v AS $_cell){
                  $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);
                  $j++;
              }
              $i++;
          }
      }
   
      //文件名处理
      if(!$fileName){
          $fileName = uniqid(time(),true);
      }
   
      $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
   
      if($isDown){   //网页下载
          header('pragma:public');
          ob_end_clean();//(这个地方及其要注意！！！！！)清除缓冲区,避免乱码
          header('Content-Type: application/vnd.ms-excel');
          header("Content-Disposition:attachment;filename=$fileName.xlsx");
          $objWrite->save('php://output');exit;
      }
   
      $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码
      $_savePath = $savePath.$_fileName.'.xlsx';
      $objWrite->save($_savePath);
   
      return $savePath.$fileName.'.xlsx';
  }
  
  function doimportExecl(){
      $file=input('post.');
      $this->importExecl($file);
   
  }




    





	
}
