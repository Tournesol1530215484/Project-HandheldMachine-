<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class activityWeb extends Plugin
{
	protected $set = null;
 
	public function __construct() 
	{  
        global $_W;
        if ($_W['uid'] == 1) {
            message('你没有权限!',referer(),'warning');
        }
        parent::__construct('activity');
		$this->set = $this->getSet();
	}

	public function index()
	{
		global $_W;
		if (cv('activity.activity')) {
			header('location: ' . $this->createPluginWebUrl('activity/activity'));
			exit;
		} else if (cv('activity.activity_apply')) {
			header('location: ' . $this->createPluginWebUrl('activity/activity_apply'));
			exit;
		} else if (cv('activity.activity_finish')) {
			header('location: ' . $this->createPluginWebUrl('activity/activity_finish'));
			exit;
		} 
	}

    public function member(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function activity()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function article() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function art() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function info() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function signin() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function card() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function picture() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function matchs()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function match() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }



}
