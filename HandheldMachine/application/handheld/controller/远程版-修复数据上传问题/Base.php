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


    /**
     * @param string $msg   提示信息
     * @param string $data  日志数据
     */
    public function log($msg='',$data=''){

        $years = date('Y-m');

        //设置路径目录信息
        //$_SERVER["DOCUMENT_ROOT"]  我这里是TP5.0  所以这个值是 E:/phpstudy/PHPTutorial/WWW/tp6.0/public/ 其他的自行写入
        $url = ROOT_PATH . 'runtime' . DS .$years.'/'.date('Ymd').'.txt';  //路由很重要
        $dir_name=dirname($url);
        //目录不存在就创建
        if(!file_exists($dir_name)){
            //iconv防止中文名乱码
            mkdir(iconv("UTF-8", "utf-8", $dir_name));
            chmod($dir_name,0777);
        }

        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建

        date_default_timezone_set('PRC');

        flock($fp, LOCK_EX);

        fwrite($fp,date("Y-m-d H:i:s")."---".$msg."--".var_export($data,true)."\r\n");//写入文件

        flock($fp, LOCK_UN);

        fclose($fp);//关闭资源通道

    }




	//图片上传统一接口
    public function uploaded_image2(){
            $file = $this->request->file('file');
            $photo=$this->uploaded_images($file);
            //$url="http://139.224.8.92/zhaowei/HandheldMachine/public/uploads";
            $url="http://172.16.22.102/uploads";
            if($photo){
                $this->ajax_return('1','图片上传成功', $url.'/'.$photo);
            }else{
                 $this->ajax_return('0','图片上传失败');
            }
    }




    //  public function uploaded_image(){
    //         $file = $this->request->file('file');
    //         $photo=$this->uploaded_images($file);
    //         //$url="http://139.224.8.92/zhaowei/HandheldMachine/public/uploads";
    //         $url="http://172.16.22.102/uploads";
    //         $data['url']=$url.'/'.$photo;
    //         $data['photo']=$photo;
    //         if($photo){
    //            // $this->ajax_return('1','图片上传成功', $url.'/'.$photo,$photo);
    //             $this->ajax_return('1','图片上传成功', $data);
    //         }else{
    //              $this->ajax_return('0','图片上传失败');
    //         }
    // }

    //把原先图片验证这一块都给删除了
    public function uploaded_image(){
            $file = $this->request->file('file');

            $nowtime=date("Y-m-d H:i:s",time());
            $nowtime=str_replace("-", "", $nowtime);
            $nowtime=str_replace(" ", "", $nowtime);
            $nowtime=str_replace(":", "", $nowtime);
            $tempname=$nowtime;
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads',$tempname);
            $url="http://172.16.22.102/uploads";
            if($info){
                 $tempname=$info->getFilename();
                  $data['url']=$url.'/'.$tempname;
                 $data['photo']=$tempname;
                 $this->ajax_return('1','图片上传成功',  $data);
             }else{
                $this->ajax_return('0','图片上传失败',$file->getError());
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
            //$dir='http://139.224.8.92/public'.'\\'.'map'.'\\'.'List'.$list;
            //$sitepath='http://139.224.8.92/zhaowei/HandheldMachine/public/map/List'.$list.'\\';
            $dir=MAP.'\\'.'map'.'\\'.'List'.$list;
            $sitepath=MAPList.$list.'\\';

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
            //$dir='http://139.224.8.92/zhaowei/HandheldMachine/public'.'\\'.'map'.'\\'.'List'.$list.'\\'.'img'.$id.'.png';
            //$dir1='http://139.224.8.92/zhaowei/HandheldMachine/public'.'\\'.'map'.'\\'.'List'.$list.'\\'.'img'.$id.'.jpeg';
            //$dir2='http://139.224.8.92/zhaowei/HandheldMachine/public'.'\\'.'map'.'\\'.'List'.$list.'\\'.'img'.$id.'.gif';
            $dir=MAPList.$list.'\\'.'img'.$id.'.png';
            $dir1=MAPList.$list.'\\'.'img'.$id.'.jpeg';
            $dir2=MAPList.$list.'\\'.'img'.$id.'.gif';
            if(file_exists($dir)){

                $imgurl=$dir;
            }
            if(file_exists($dir1)){

                $imgurl=$dir1;

            }
            if(file_exists($dir2)) {

                $imgurl = $dir2;

            }
            

        }

           // print_r($dir);

        return $imgurl;


    }


    public function getuploadimg(){
        if(request()->isPost()){
            $data=input('post.');
            $res=db('inspection_report')
                ->field('photo1,photo2,photo3,photo4,photo5,photo6,photo7,photo8,photo9,photo10,photo11,photo12,photo6,photo13,photo14,photo15,photo16,photo17,photo18,photo19,photo20')
                ->where(['carry_outID'=>$data['carry_outID'],'pointID'=>$data['pointID']])
                ->select();
           //var_dump($res[0]);
                //$href="http://139.224.8.92/zhaowei/HandheldMachine/public/uploads/";
                $href="http://172.16.22.102/uploads/";
                $url=array_filter($res[0]);
                if(!empty($url)){
                    foreach ($url as $key => $value) {
                    $url[$key]=$href.$value;
                    
                    }
                    $this->ajax_return('1','图片获取成功',$url);

                }
                
                $this->ajax_return('0','暂无图片');
                
        }   
       


    }



}
