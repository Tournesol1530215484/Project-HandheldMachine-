<?php
namespace app\common\controller;
/**
* 公共类，里面的方法被继承后可适用于所有的模块
*/
use think\Controller;
class Base extends controller
{
	// 	parent::__construct();
		
	// public function __construct(){
	// 	// if(empty(session::get('username'))){
	// 	// 	$this->error("您尚未登录，请先登录","/admin/login/login");
	// 	// }
	// }

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
		 * 导出excel
		 * @param $strTable	表格内容
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
}