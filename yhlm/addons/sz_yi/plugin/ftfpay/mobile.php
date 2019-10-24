<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class FtfpayMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('ftfpay');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
}