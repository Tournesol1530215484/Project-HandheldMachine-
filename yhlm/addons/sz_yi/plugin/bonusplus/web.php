<?php

/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}
class BonusplusWeb extends Plugin
{
	protected $set = null;

	public function __construct()
	{
		parent::__construct('bonusplus');
		$this->set = $this->getSet();
	}

	public function index()
	{
		global $_W;
		if (cv('bonusplus.agent')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/agent'));
			exit;
		} else if (cv('bonusplus.notice')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/set'));
			exit;
		} else if (cv('bonusplus.set')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/set'));
			exit;
		} else if (cv('bonusplus.level')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/level'));
			exit;
		} else if (cv('bonusplus.cover')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/cover'));
			exit;
		} else if (cv('bonusplus.send')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/send'));
			exit;
		} else if (cv('bonusplus.sendall')) {
			header('location: ' . $this->createPluginWebUrl('bonusplus/sendall'));
			exit;
		}
	}

	public function upgrade()
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

	public function send()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function sendall()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function notice()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function cover()
	{
		$this->_exec_plugin(__FUNCTION__);
	}

	public function set()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
	public function detail()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}
