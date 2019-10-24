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
		/* add zyw 2018/4/27 qq:978949784  */
		if(!empty($_W['uid'])){
		    $item=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where uid = :uid and uniacid = :uniacid and merchid>0 limit 1',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
		    	
		    if(empty($item)){
		        $_W['merchid']=0;
		    }else{
		        $GLOBALS['frames'][0]['title']='商家管理';
		        $arr=array('id'=>0,'title'=>'基础设置','url'=>'./index.php?c=site&a=entry&method=merch&p=suppliermenu&do=plugin&m=sz_yi','permission_name' => 'suppliermenu_merch');
		        array_unshift($GLOBALS['frames'][0]['items'],$arr);
		        $_W['merchid']=$item['merchid'];
		    }
		
		}

	}
	public function index()
	{
        $this->_exec_plugin(__FUNCTION__);
	}
    public function match()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
	public function merch()
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

	public function info(){
		$this->_exec_plugin(__FUNCTION__);
	}

    public function dealmerch(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealmerch_add(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_send(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealmerch_activation(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealgoods(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function store(){
        $this->_exec_plugin(__FUNCTION__);
    }

 	public function staff(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealmerch_adminset(){
        $this->_exec_plugin(__FUNCTION__);
    }
 
    public function dealmerch_exchange(){
        $this->_exec_plugin(__FUNCTION__);
    } 

    public function consult(){ 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function local_order(){ 
        $this->_exec_plugin(__FUNCTION__);
    }
  	 
  	public function post_order(){ 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function barter_code_log(){ 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function barter_currency(){ 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealmerch_sales_statis(){ 
        $this->_exec_plugin(__FUNCTION__);
    }
    
    public function dealmerch_stockchange(){
        $this->_exec_plugin(__FUNCTION__); 
    }

    public function dealmerch_work_number(){
        $this->_exec_plugin(__FUNCTION__); 
    }

    public function ad_demo(){
        $this->_exec_plugin(__FUNCTION__); 
    }

    public function ad(){
        $this->_exec_plugin(__FUNCTION__); 
    }	 	 	 
}  