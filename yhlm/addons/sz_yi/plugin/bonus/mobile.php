<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
function sortByCreateTime($dephp_0, $dephp_1){
    if ($dephp_0['createtime'] == $dephp_1['createtime']){
        return 0;
    }else{
        return ($dephp_0['createtime'] < $dephp_1['createtime']) ? 1 : -1;
    }
}
class BonusMobile extends Plugin{
    protected $set = null;
    public function __construct(){
        parent :: __construct('bonus');
        $this -> set = $this -> getSet();
    }
    public function index(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function team(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function customer(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function order(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function order_area(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function withdraw(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function apply(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function shares(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function register(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function myshop(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function log(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function api(){
         $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function ad(){
         $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function barter(){
         $this -> _exec_plugin(__FUNCTION__, false);
    }
    public function activity(){
         $this -> _exec_plugin(__FUNCTION__, false);
    }
    
}
