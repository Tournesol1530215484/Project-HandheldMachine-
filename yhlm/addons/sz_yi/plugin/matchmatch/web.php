<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class matchWeb extends Plugin
{
	protected $set = null;
 
	public function __construct() 
	{  
        global $_W;
        if ($_W['uid'] == 1) {
            message('你没有权限!',referer(),'warning');
        }
        parent::__construct('match');
		$this->set = $this->getSet();
	}
        
	public function index()
	{
		global $_W;
		if (cv('match.match')) {
			header('location: ' . $this->createPluginWebUrl('match/match'));
			exit;
		} else if (cv('match.match_apply')) {
			header('location: ' . $this->createPluginWebUrl('match/match_apply'));
			exit;
		} else if (cv('match.match_finish')) {
			header('location: ' . $this->createPluginWebUrl('match/match_finish'));
			exit;
		} 
	}

    /*public function member(){
        $this->_exec_plugin(__FUNCTION__);
    }*/

    public function match()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function picture() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    /*public function art() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }*/

    /*public function info() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }*/

    /*public function signin() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function card() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }*/


}
