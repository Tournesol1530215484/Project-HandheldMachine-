<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}
require_once 'model.php';

class SuppliermenuWeb extends Plugin
{
	public function __construct()
	{
		global $_W;


		parent::__construct('suppliermenu');
		
 
	 	 
        include dirname(__FILE__).'/init.php';

       
      
        ca('suppliermenu');
    

        if($_W['isfounder']||!pdo_fetch('select id from '.tablename('sz_yi_perm_user')." where uid = '{$_W['uid']}' and uniacid = '{$_W['uniacid']}' limit 1")){
			message('不能进入操作台');
		}

	}
	public function index()
	{
        $this->_exec_plugin(__FUNCTION__);
	}

	public function goods()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function designer(){
		 $this->_exec_plugin(__FUNCTION__);
	}

	public function menu(){
		$this->_exec_plugin(__FUNCTION__);
	}

	public function order(){
	    $this->_exec_plugin(__FUNCTION__);	
	}

	public function taobao(){
		$this->_exec_plugin(__FUNCTION__);
	}

	public function exhelper(){
		$this->_exec_plugin(__FUNCTION__);
	}

}