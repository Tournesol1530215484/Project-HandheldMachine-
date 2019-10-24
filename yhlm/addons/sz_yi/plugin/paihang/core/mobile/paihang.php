<?php
/**
 * 排行榜
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
global $_W, $_GPC;
$apido = $_GPC['apido'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid    = m('user')->getOpenid();
$uniacid   = $_W['uniacid'];
$trade     = m('common')->getSysset('trade');

$paihang = pdo_fetch("SELECT * FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
$bool = pdo_fetch("SELECT y_name,j_name,x_name FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 2;
    $condition = ' WHERE `uniacid` = :uniacid AND `credit1` != :credit1';
    $params    = array(
        ':uniacid' => $_W['uniacid'],
        ':credit1 ' => 0
    );

		$sql = 'SELECT credit1, realname FROM ' . tablename('sz_yi_member') . $condition . ' ORDER BY `credit1` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_member') . $condition;
		$total = pdo_fetchcolumn($sqls, $params);
    
    $list  = pdo_fetchall($sql, $params);
    $pager = pagination($total, $pindex, $psize);
    $items = pdo_fetchall("SELECT credit1, realname FROM " . tablename('sz_yi_member') . " WHERE uniacid = :uniacid AND `credit1` != :credit1 ORDER BY `credit1` DESC LIMIT 0,10", array(
        ':uniacid' => $_W['uniacid'],
        ':credit1' => 0
    ));

    if($apido == 'selectlist'){
    	$name = $_GPC['b_name'];
    	$bool = pdo_fetch("SELECT y_name,j_name,x_name FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
    	if($name == $bool['j_name']){
    		$item = pdo_fetchall("SELECT credit1, realname,avatar FROM " . tablename('sz_yi_member') . " WHERE uniacid = :uniacid AND `credit1` != :credit1 ORDER BY `credit1` DESC LIMIT 0,10", array(
    		    ':uniacid' => $_W['uniacid'],
    		    ':credit1' => 0
    		));
        $result=echo_json(1,0,$item);
    	}else if($name == $bool['x_name']){

            $item = pdo_fetchall("select openid,SUM(oldprice) from " . tablename('sz_yi_order') . "   where uniacid ={$_W['uniacid']} and oldprice != 0 group by openid order by SUM(oldprice) desc limit 0,10");
            foreach ($item as $key => $value) {
                    $arr = pdo_fetch("select realname,avatar from " . tablename('sz_yi_member') . " where openid = :openid ",array(
                        ':openid' => $value['openid']
                    ));
                    $item[$key]['realname'] = $arr['realname'];
                    $item[$key]['avatar'] = $arr['avatar'];
                }
        $result=echo_json(2,0,$item);
    	}else if($name == $bool['y_name']){
    		$item = pdo_fetchall("SELECT realname,commission,avatar FROM " . tablename('sz_yi_commission_apply') . " d " .
		        " left join " . tablename('sz_yi_member') . ' m on m.id = d.mid '.
		        " WHERE d.uniacid = ".$_W['uniacid'] . " AND d.commission != 0 ORDER BY `commission` DESC LIMIT 0,10");
        $result=echo_json(3,0,$item);
    	}
   		
    }
    



include $this->template('member/paihang');
