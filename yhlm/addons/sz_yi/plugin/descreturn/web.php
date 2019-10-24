<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class DescreturnWeb extends Plugin
{
	public function __construct()
	{
		parent::__construct('descreturn');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__);
	}
}
