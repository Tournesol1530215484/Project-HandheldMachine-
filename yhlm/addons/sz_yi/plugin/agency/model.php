<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
	exit('Access Denied');
}
if (!class_exists('AgencyModel')) {


	class AgencyModel extends PluginModel
	{
 

		function perms()
		{
			return array(
				'agency' => array('text' => $this->getName(), 'isplugin' => true, 'child' => array('goods' => array('add'=>'添加商品')
			    )  
			  )  
			);
		}

		public function getStaff($uid=0){
			global $_W;
			$params=array(
				':uniacid'=>$_W['uniacid'],
				':uid'=>$uid
			);
			return pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid=:uniacid and :uid = uid',$params);
		}

		public function getMStaff($mid=0){
			global $_W;
			$params=array(
				':uniacid'=>$_W['uniacid'],
				':mid'=>$mid
			);
			return pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid=:uniacid and :mid = mid',$params);
		}


	}


}
