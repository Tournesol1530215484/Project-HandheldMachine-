<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}
if (!class_exists('SuppliermenuModel')) {


	class SuppliermenuModel extends PluginModel
	{
 

		function perms()
		{
			return array('suppliermenu' => array('text' => $this->getName(), 'isplugin' => true, 'child' => array('goods' => array('add'=>'添加商品')



			    )  
			  )  
			);

		}
	}


}
