<?php
 namespace app\admin\model;
 use think\Model;
 class Conf extends Model{
 	//获取所有的配置项
 	public function GetConf(){
 		$AllConf=db('conf')->field('ename,value')->select();
 		$Confs=array();
    foreach ($AllConf as $key => $value) {
          $Confs[$value['ename']]=$value['value'];
     }
 		return $Confs;
  	}
 }