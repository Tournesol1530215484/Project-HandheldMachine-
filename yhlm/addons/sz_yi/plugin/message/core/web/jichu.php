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
if ($operation == 'display') {
    //查询出留言版内容
    $list=pdo_fetchall('select * from' .tablename('sz_yi_message_reply'));
    // print_r($list);
    ca('message.liuyan.view');
}elseif ($operation == 'delete') {
   // print_r($_GPC);exit;
    ca('message.jichu.delete');
    $re_id = $_GPC['re_id'];
    if (!empty($re_id)) {
        pdo_delete('sz_yi_message_reply', array(
            're_id' => $re_id
        ));
        plog('message.jichu.delete', "删除留言 ID: {$re_id}");
        message('删除成功！', $this->createPluginWebUrl('message/jichu'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('message/jichu'), 'error');
    }
    exit;
}

load()->func('tpl');
include $this->template('liuyan');
