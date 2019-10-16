<?php
namespace app\handheld\controller;
use think\Controller;
use think\Request;
use app\common\Common;
use think\File;
use think\Validate;
class Base extends Controller
{

      /**
		*	code 状态码
		*	msg  提示信息
		*   data 要返回的数据
		*	返回ajax格式
		*/
	public function ajax_return($code='',$msg='',$data=''){
			error_reporting("E_ALL");	//可以避免范文报500的错误
	        ini_set("display_errors", 1);
		  $return_msg['code']=$code;
          $return_msg['msg']=$msg;
          $return_msg['data']=$data;
          echo json_encode($return_msg);die;

	}


	//图片上传统一接口
    public function uploaded_image(){
            $file = $this->request->file('file');
            $photo=$this->uploaded_images($file);
            $url="http://139.224.8.92/zhaowei/HandheldMachine/public/uploads";
            if($photo){
                $this->ajax_return('1','图片上传成功', $url.'/'.$photo);
            }else{
                 $this->ajax_return('0','图片上传失败');
            }
    }


	  /**
		*	imgName 文件名
		*	返回ajax格式
		*/
	public function uploaded_images($imgName){
			$file = $imgName;
		 	$nowtime=date("Y-m-d H:i:s",time());
		 	$nowtime=str_replace("-", "", $nowtime);
		 	$nowtime=str_replace(" ", "", $nowtime);
		 	$nowtime=str_replace(":", "", $nowtime);
		 	$tempname=$nowtime;
		 	$info = $file->validate(['size'=>1048576,'ext'=>'jpg,png,gif'])->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads',$tempname);
		 if($info){
		 	$tempname=$info->getFilename();
		 	return $tempname;
		 }else{
		 return '';
		}
	}

	 function get_routeurl($list='',$id=''){
        $imgurl=array();
        if($id==''&&$list!=''){
            //获取某个文件下所有的地图信息
            $dir='http://139.224.8.92/public'.'\\'.'map'.'\\'.'List'.$list;
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
            return 111;
        }else{  //获取某个文件夹下的指定文件
                //只做常见的三种处理
            $dir='http://139.224.8.92/zhaowei/HandheldMachine/public'.'\\'.'map'.'\\'.'List'.$list.'\\'.'img'.$id.'.png';
            $dir1='http://139.224.8.92/zhaowei/HandheldMachine/public'.'\\'.'map'.'\\'.'List'.$list.'\\'.'img'.$id.'.jpeg';
            $dir2='http://139.224.8.92/zhaowei/HandheldMachine/public'.'\\'.'map'.'\\'.'List'.$list.'\\'.'img'.$id.'.gif';
            if(file_exists($dir)){

                $imgurl=$dir;
            }
            if(file_exists($dir1)){

                $imgurl=$dir1;

            }
            if(file_exists($dir2)) {

                $imgurl = $dir2;

            }
            return 222;

        }

           // print_r($dir);

        //return $imgurl;


    }



}
