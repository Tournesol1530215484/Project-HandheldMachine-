<?php
namespace  app\admin\controller;
use app\BaseController;
use think\facade\Config;//读取配置信息
use think\Loader;
use PHPExcel;   //打印用
use think\wenhainan\Auth;




class  adminBase extends BaseController{

    public function __construct()
    {
//        $module = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
        $auth       = Auth::instance();
        // 检测权限
//        if(!$auth->check($controller.'/'.$action,6)){// 第一个参数是规则名称,第二个参数是用户UID
//            //有显示操作按钮的权限
//            var_dump($controller.'/'.$action);
//            dump('您没有权限访问');
//
//        }

        if(!$auth->check($controller.'/'.$action,6)){// 第一个参数是规则名称,第二个参数是用户UID
            //有显示操作按钮的权限
            var_dump($controller.'/'.$action);
            dump('您没有权限访问');

        }
    }


    /**
     * 密码加密算法
     * @param $value 需要加密的值
     * @param $type  加密类型，默认为md5 （md5, hash）
     * @return mixed
     */
    public function  encryptionPsd($value){
        $value = sha1('blog_') . md5($value) . md5('_encrypt') . sha1($value);
        return sha1($value);
    }


    /**
     * 二位数组重新组合数据
     * @param $array
     * @param $key
     * @return array
     */
    public function array_format_key($array, $key)
    {
        $newArray = [];
        foreach ($array as $vo) {
            $newArray[$vo[$key]] = $vo;
        }
        return $newArray;
    }


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



    use \liliuwei\think\Jump;

}