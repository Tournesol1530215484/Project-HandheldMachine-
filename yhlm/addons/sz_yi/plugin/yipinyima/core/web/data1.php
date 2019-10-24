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
load()->func('tpl');
if ($operation == 'display')
    {
        //print_r($_GPC);exit;
    ca('yipinyima.data.view');
    $id = $_GPC['id'];
     $goodsid=$_GPC['goodsid'];

  /*  $item = pdo_fetchall("select * from" . tablename('yipinyimaerwei') . ' where  uniacid=:uniacid and yipin_id=:yipin_id', array(
            ':uniacid' => $_W['uniacid'],
            ':yipin_id'=>$id
        ));*/

       $item = pdo_fetchall("SELECT * FROM " . tablename('yipinyimaerwei') . " d " .
            " left join " . tablename('yipinyima') . ' g on g.id = d.yipin_id ' .
            " where  d.id=" . $id . " and d.uniacid=" . $_W['uniacid'] . "");

}
include $this->template('data');
