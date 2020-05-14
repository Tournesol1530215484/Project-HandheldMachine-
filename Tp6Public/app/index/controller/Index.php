<?php
namespace app\index\controller;
use think\facade\View;
class Index{
    public function  Index(){

        echo 111;
        exit;
        View::assign('data','Index');
        View::fetch('index');
    }
}
