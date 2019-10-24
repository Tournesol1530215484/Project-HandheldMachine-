<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class matchMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('match');
	}

	public function index()
	{ 	 	
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function picture()
	{ 	 		 	 	
		$this->_exec_plugin(__FUNCTION__, false);
	}	 		 

	public function center()
	{ 	 	
		$this->_exec_plugin(__FUNCTION__, false);	  	
	}

	public function match(){          
        $this->_exec_plugin(__FUNCTION__, false);
    }

    public function card(){          
        $this->_exec_plugin(__FUNCTION__, false);
    }

    


	


}