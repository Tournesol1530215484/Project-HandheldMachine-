<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-8-9
 * Time: 下午9:19
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$arr=$this->model->chasx();
$shijian=TIMESTAMP;

$list = array();
foreach($arr as $key=>$val){
    if($val['end']<$shijian){
       $arr[] =  $val['id'];
    }
}
print_r($arr);
