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
    ca('yipinyima.dayin.view');
    $id = $_GPC['id'];
    //查询二维码
        $item_desc = pdo_fetchall("select * from" . tablename('yipinyimaerwei') . ' where yipin_id=:yipin_id and  uniacid=:uniacid', array(
            ':uniacid' => $_W['uniacid'],
            ':yipin_id'=>$id
        ));
}
include $this->template('dayin');
