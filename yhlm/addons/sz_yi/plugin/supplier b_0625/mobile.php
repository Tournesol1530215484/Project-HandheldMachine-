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
class SupplierMobile extends Plugin{

    protected $set = null;
    public function __construct(){
        parent :: __construct('supplier');
        $this -> set = $this -> getSet();
        global $_GPC;
    }
    public function af_supplier(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }

    /**
     * 商家
     */
    public function store(){
        $this -> _exec_plugin(__FUNCTION__, false);
    }
}
