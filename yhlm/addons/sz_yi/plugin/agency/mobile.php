<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class AgencyMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('agency');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function indexDemo()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function order(){
		$this->_exec_plugin(__FUNCTION__, false);

	}
    public function order_detail(){
        $this->_exec_plugin(__FUNCTION__, false);

    }

	public function goods(){
		$this->_exec_plugin(__FUNCTION__, false);
	}

    public function dealgoods(){
        $this->_exec_plugin(__FUNCTION__, false);
    }

	public function cate(){
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function info(){
		$this->_exec_plugin(__FUNCTION__, false);
	}

    public function myorder_detail(){
        $this->_exec_plugin(__FUNCTION__, false);
    }

    public function transfer(){
        $this->_exec_plugin(__FUNCTION__, false);
    }

    public function activa(){
        $this->_exec_plugin(__FUNCTION__, false);
    }

    public function merchinfo(){
        $this->_exec_plugin(__FUNCTION__, false);
    }
    
    public function indexOld(){
        $this->_exec_plugin(__FUNCTION__, false);
    }

}