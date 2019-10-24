<?php

if (!defined('IN_IA')) {
    exit('Access Denied');  
}
class DisWeb extends Plugin
{
	protected $set = null;

	public function __construct()
	{
		parent::__construct('dis');   
		$this->set = $this->getSet();
	}

	public function index()
	{
		global $_W;
		 
		if (cv('dis.agent')) {
			header('location: ' . $this->createPluginWebUrl('dis/agent'));
			exit;
		} else if (cv('dis.level')) {
			header('location: ' . $this->createPluginWebUrl('dis/level'));
			exit;
		} else if (cv('dis.apply.view1')) {
			header('location: ' . $this->createPluginWebUrl('dis/apply', array('status' => 1)));
			exit;
		} else if (cv('dis.apply.view2')) {
			header('location: ' . $this->createPluginWebUrl('dis/apply', array('status' => 2)));
			exit;
		} else if (cv('dis.apply.view3')) {
			header('location: ' . $this->createPluginWebUrl('dis/apply', array('status' => 3)));
			exit;
		} else if (cv('dis.apply.view_1')) {
			header('location: ' . $this->createPluginWebUrl('dis/apply', array('status' => -1)));
			exit;
		} else if (cv('dis.increase')) {
			header('location: ' . $this->createPluginWebUrl('dis/increase'));
			exit;
		} else if (cv('dis.notice')) {
			header('location: ' . $this->createPluginWebUrl('dis/notice', array( 'op' => 'post')));
			exit;
		} else if (cv('dis.cover')) {
			header('location: ' . $this->createPluginWebUrl('dis/cover'));
			exit;
		} else if (cv('dis.set')) {
			header('location: ' . $this->createPluginWebUrl('dis/set'));
			exit;
		}else if (cv('dis.set')) {
			header('location: ' . $this->createPluginWebUrl('dis/surface'));
			exit;
		}
	}

	public function cover()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

public function surface()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function agent()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function level()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function notice()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function increase()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function apply()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function set()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}
