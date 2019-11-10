<?php
namespace app\admin\controller;
use think\Controller;

//这个不用验证，可用于
class Base extends Controller{

	public function Imgsuploade(){
      $typeArr = array("jpg", "png", "gif","ico");
      //允许上传文件格式
      $path = ROOT_PATH . 'public' . DS .'static'. DS . 'uploads';
      //上传路径

      //if (!file_exists($path)) {
      //  mkdir($path);
      //}

      if (isset($_POST)) {
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $name_tmp = $_FILES['file']['tmp_name'];
        if (empty($name)) {
          echo json_encode(array("error" => "您还未选择图片"));
          exit ;
        }
        $type = strtolower(substr(strrchr($name, '.'), 1));
        //获取文件类型

        if (!in_array($type, $typeArr)) {
          echo json_encode(array("error" => "清上传jpg,png或gif类型的图片！"));
          exit ;
        }
        if ($size > (500 * 1024)) {
          echo json_encode(array("error" => "图片大小已超过500KB！"));
          exit ;
        }

        $pic_name = time() . rand(10000, 99999) . "." . $type;
        //图片名称
        $pic_url  = $path .'/'. $pic_name;
        //上传后图片路径+名称
        if (move_uploaded_file($name_tmp, $pic_url)) {//临时文件转移到目标文件夹
          echo json_encode(array("error" => "0", "pic" => $pic_url, "name" => $pic_name));
        } else {
          echo json_encode(array("error" => "上传有误，清检查服务器配置！"));
        }
      }
    }

    public function upload(){
      $typeArr = array("jpg", "png", "gif", "jpeg");
//上传路径
$path = "uploads/";

if (isset($_POST)) {
    $name = $_FILES['file_upload']['name'];
    $size = $_FILES['file_upload']['size'];
    $name_tmp = $_FILES['file_upload']['tmp_name'];
    if (empty($name)) {
        echo json_encode(array("error" => "您还未选择图片"));
        exit;
    }
    //获取文件类型
    $type = strtolower(substr(strrchr($name, '.'), 1));

    if (!in_array($type, $typeArr)) {
        echo json_encode(array("error" => "清上传jpg,png或gif类型的图片！"));
        exit;
    }
    //上传大小
    if ($size > 5 * 1024 * 1024) {
        echo json_encode(array("message" => "图片大小已超过5m！"));
        exit;
    }
    $time_str = time() . rand(10000, 99999);
    //图片名称
    $pic_name = $time_str . "." . $type;
    //上传后图片路径+名称
    $pic_url = $path . $pic_name;
    //临时文件转移到目标文件夹
    if (move_uploaded_file($name_tmp, $pic_url)) {
        //这些数据可根据需要进行返回，字段如果修改需要和前端保持一致
        $ret = array(
            'file_id' => $time_str,
            'file_name' => $pic_url,
            'origin_file_name' => $name,
            'file_path' => $pic_url,
            'state' => '1',
        );
        echo json_encode($ret);
    } else {
        $ret = array(
            'message' => "图片上传失败",
            'origin_file_name' => $name,
            'state' => '0',
        );
        echo json_encode($ret);
    }
}
    }

}