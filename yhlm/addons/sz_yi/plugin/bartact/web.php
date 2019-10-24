<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class bartactWeb extends Plugin
{
	protected $set = null;
 
	public function __construct() 
	{ 
        parent::__construct('bartact');
		$this->set = $this->getSet();
	}

	public function index()
	{
		global $_W;
		if (cv('bartact.bartact')) {
			header('location: ' . $this->createPluginWebUrl('bartact/bartact'));
			exit;
		} else if (cv('bartact.bartact_apply')) {
			header('location: ' . $this->createPluginWebUrl('bartact/bartact_apply'));
			exit;
		} else if (cv('bartact.bartact_finish')) {
			header('location: ' . $this->createPluginWebUrl('bartact/bartact_finish'));
			exit;
		} 
	}
    public function forum()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function forum_for()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function forum_type()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function slide() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }
    public function right()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function news()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function order()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function button()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function send()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function ready()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function sale()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function shopping()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function vip()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function poster() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }
    public function match()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function one()
    {
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

    public function picture() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function report_type() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function bloc() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function member_for()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function member_for_resu()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function activity_type()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function match_type()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }
             
    public function activity_for()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function article_type()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function picture_type()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function picture_title()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function merch()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function merch_add()        
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function member(){       
        $this->_exec_plugin(__FUNCTION__);
    }

    public function set()        
    {        
        $this->_exec_plugin(__FUNCTION__);
    }

}
