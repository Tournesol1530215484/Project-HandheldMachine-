<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class SuppliermenuMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('suppliermenu');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function order(){
		$this->_exec_plugin(__FUNCTION__, false);

	}

	public function goods(){
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function cate(){
		$this->_exec_plugin(__FUNCTION__, false);
	}

 
}