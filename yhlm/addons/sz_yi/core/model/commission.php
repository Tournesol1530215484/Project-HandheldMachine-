<?php
/**
 * 分销类
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
 
if (!defined('IN_IA')){
    exit('Access Denied');
}
class Sz_DYi_Commission{
    private $sessionid;
    public function __construct(){
        global $_W;
        //$this -> sessionid = "__cookie_sz_yi_201507200000_{$_W['uniacid']}";
    }
	
	/**
	 * 分销权限
	 *
	 * @param  $extract_commission 佣金提现
	 * @param  $spread_qrcode 推广二维码
	 * @param  $closemyshop 我的小店
	 * @param  $select_goods 分销商自选商品
	 * @param  $openorderdetail 分销订单商品详情
	 * @param  $openorderbuyer 分销订单购买者详情
	 * @param  $remind_message 开启三级消息提醒
	 * @param  $liuyan 开启留言
	 * @return array
	 */
    function getAuthority( $type, $status ){
        global $_W, $_GPC;		
		//openorderbuyer
		$url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		$array = explode('/web/', $url);
		if( count($array) == 1){
			$openid = m('user')->getOpenid();
			$member = pdo_fetch("SELECT agentlevel FROM " . tablename('sz_yi_member') . " WHERE openid = '".$openid."'");
			if( false != $member['agentlevel'] ){
				//设置分销等级
				$commission_level = pdo_fetch("SELECT authority FROM " . tablename('sz_yi_commission_level') . 
									" WHERE id = ". $member['agentlevel']);
				$authority = unserialize($commission_level['authority']);
				return $authority[$type];
			}else{
				//默认分销等级		
				return $status;				
			}
		}
    }
}
