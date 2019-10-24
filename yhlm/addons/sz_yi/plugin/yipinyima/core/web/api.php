<?php
/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$id=$_GPC['id'];

$goodsid=$_GPC['goodsid'];
if ($operation == 'asds')
{
    $lise=$_GPC['keyword'];
    $lisee = pdo_fetchall("select * from" . tablename('sz_yi_member') . " where isagent=:isagent and uniacid=:uniacid and (id  like :lise or nickname like :lise)" , array(
        ':uniacid' => $_W['uniacid'],
        ':isagent'=>"1",
        ':lise' => "%{$lise}%"
    ));
    die(json_encode($lisee));
}
   /*$item = pdo_fetch("SELECT * FROM " . tablename('yipinyimapeizhi') . " d " .
            " left join " . tablename('yipinyima') . ' m on m.yipinp_id = d.id '.
            " where d.id=".$id." and d.goodsid=".$goodsid." and d.uniacid=".$_W['uniacid']."");*/

 $item=pdo_fetch('select * from'.tablename('yipinyimapeizhi') .'where id=:id and goodsid=:goodsid and uniacid=:uniacid',array(
     ':goodsid'=>$goodsid,
     ':id'=>$id,
     ':uniacid'=>$_W['uniacid']
 ));

load()->func('tpl');
include $this->template('api');
