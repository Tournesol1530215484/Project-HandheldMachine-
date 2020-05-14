<?php
    namespace app\api\controller;
    use app\BaseController;

    class Base extends BaseController{
        /**
         *	code 状态码
         *	msg  提示信息
         *   data 要返回的数据
         *	返回ajax格式
         */
        public function return_msg($code='',$msg='',$data=''){
            error_reporting("E_ALL");	//可以避免范文报500的错误
            ini_set("display_errors", 1);
            $return_msg['code']=$code;
            $return_msg['msg']=$msg;
            $return_msg['data']=$data;
            echo json_encode($return_msg);die;
        }


        /**
         * 日志写入文件
         * @param string $msg   提示信息
         * @param string $data  数据写入
         */
        public function write_log($msg='',$data=''){
            $years = date('Y-m');
            $url = $_SERVER["DOCUMENT_ROOT"].'/logs/'.$years.'/'.date('Ymd').'.txt';
            $dir_name=dirname($url);
            if(!file_exists($dir_name)){
                //iconv防止中文名乱码
                mkdir(iconv("UTF-8", "utf-8", $dir_name),700,true);
//                chmod($dir_name,0777);
            }
            $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建
            date_default_timezone_set('PRC');
            flock($fp, LOCK_EX);

            fwrite($fp,date("Y-m-d H:i:s")."---".$msg."--".var_export($data,true)."\r\n");//写入文件
            flock($fp, LOCK_UN);

            fclose($fp);//关闭资源通道
        }






    }