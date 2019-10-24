<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}
include (dirname(__FILE__).'/phpqrcode.php');
if (!class_exists('FtfpayModel')) {
	class FtfpayModel extends PluginModel
	{
		public function createCode()
		{
			global $_W;
			// 收款人的openid (生成二维码的人的openid)
			$payeeopenid = m('user')->getOpenid();
			$data = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=ftfpay&op=pay&payeeopenid=' . $payeeopenid;
			// echo($data);exit;

			// 纠错级别：L、M、Q、H
			$level = 'L';
			// 点的大小：1到10,用于手机端4就可以了
			$size = 4;

			QRcode::png($data, false, $level, $size);
		}
	}
}
