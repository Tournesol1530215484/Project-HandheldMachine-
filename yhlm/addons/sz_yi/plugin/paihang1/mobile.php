<?php

/*=============================================================================
#         Desc: 专业承接微信分销商城二次开发及相关微信功能模块的开发与定制
#       Author: Man.Dan - http://www.jzwshop.com
#        Email: 82089092@qq.com
#     HomePage: http://www.jzwshop.com
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:08:51
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class PaihangMobile extends Plugin
{
	public function __construct()
	{
		parent::__construct('paihang');
	}

	public function index()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}

	public function api()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
	public function paihang()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}


	public function report()
	{
		$this->_exec_plugin(__FUNCTION__, false);
	}
}