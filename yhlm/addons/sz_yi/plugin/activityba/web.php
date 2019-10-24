<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class activitybaWeb extends Plugin
{
	protected $set = null;
 
	public function __construct() 
	{  
        global $_W;
        if ($_W['uid'] == 1) {
            message('你没有权限!',referer(),'warning');
        }
        parent::__construct('activityba');               
		$this->set = $this->getSet();
	}                                           

	public function index()
	{
		global $_W;
		if (cv('activityba.activity')) {
			header('location: ' . $this->createPluginWebUrl('activityba/activity'));
			exit;
		} else if (cv('activityba.activity_apply')) {
			header('location: ' . $this->createPluginWebUrl('activityba/control'));
			exit;
		} else if (cv('activityba.activity_finish')) {
			header('location: ' . $this->createPluginWebUrl('activityba/interact'));
			exit;
		} 
	}


    public function activity()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function interact() 
    {                    
        $this->_exec_plugin(__FUNCTION__);
    }

    public function control()
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function wall()
    {           
        $this->_exec_plugin(__FUNCTION__);
    }


}
