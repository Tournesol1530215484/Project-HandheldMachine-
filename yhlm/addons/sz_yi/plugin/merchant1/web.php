<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class merchantWeb extends Plugin
{
	protected $set = null;

	public function __construct()
	{
		parent::__construct('merchant');
		$this->set = $this->getSet();
	}

	public function index()
	{
		global $_W;
		if (cv('merchant')) {
			header('location: ' . $this->createPluginWebUrl('merchant/merchant'));
			exit;
		} else if (cv('merchant')) {
			header('location: ' . $this->createPluginWebUrl('merchant/merchant_apply'));
			exit;
		} else if (cv('merchant')) {
			header('location: ' . $this->createPluginWebUrl('merchant/merchant_finish'));
			exit;
		}
	}

	public function applyfor(){
	    $this->_exec_plugin(__FUNCTION__);
    }
    public function enter(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function upgrade()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant_apply()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant_finish()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant_for()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant_list()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant_add()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function notice()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function set()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function merchant_for_resu()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
}
