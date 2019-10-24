<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}
require IA_ROOT . '/addons/sz_yi/defines.php';
require SZ_YI_INC . 'plugin/plugin_processor.php';

class SuppliermenuProcessor extends PluginProcessor
{
	public function __construct()
	{
		parent::__construct('suppliermenu');
	}

 
}
