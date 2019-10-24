<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class activitybaMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('activity');
	}

	public function index()
	{ 	 	
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function article()
	{ 	 	
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function center()
	{ 	 	
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function activity(){          
        $this->_exec_plugin(__FUNCTION__, false);
    }

    public function card(){          
        $this->_exec_plugin(__FUNCTION__, false);
    }
      	
    public function wall(){          
        $this->_exec_plugin(__FUNCTION__, false);
    }



}