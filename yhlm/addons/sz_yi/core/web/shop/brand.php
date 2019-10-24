<?php
/*=============================================================================
#     FileName: brand.php
#         Desc:  
#       Author: Yunzhong - http://www.yunzshop.com
#        Email: 913768135@qq.com
#     HomePage: http://www.yunzshop.com
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:39:14
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    ca('shop.brand.view');
    if (!empty($_GPC['displayorder'])) {
        ca('shop.brand.edit');
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update('sz_yi_brand', array(
                'displayorder' => $displayorder
            ), array(
                'id' => $id
            ));
        }
        plog('shop.brand.edit', '批量修改品牌的排序');
        message('分类排序更新成功！', $this->createWebUrl('shop/brand', array(
            'op' => 'display'
        )), 'success');
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_brand') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (empty($id)) {
        ca('shop.brand.add');
    } else {
        ca('shop.brand.edit|shop.brand.view');
    }
    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'brandname' => trim($_GPC['brandname']),
            'link' => trim($_GPC['link']),
            'enabled' => intval($_GPC['enabled']),
            'displayorder' => intval($_GPC['displayorder']),
            'thumb' => save_media($_GPC['thumb'])
        );
        if (!empty($id)) {
            pdo_update('sz_yi_brand', $data, array(
                'id' => $id
            ));
            plog('shop.brand.edit', "修改品牌 ID: {$id}");
        } else {
            pdo_insert('sz_yi_brand', $data);
            $id = pdo_insertid();
            plog('shop.brand.add', "添加品牌 ID: {$id}");
        }
        message('更新品牌成功！', $this->createWebUrl('shop/brand', array(
            'op' => 'display'
        )), 'success');
    }
    $item = pdo_fetch("select * from " . tablename('sz_yi_brand') . " where id=:id and uniacid=:uniacid limit 1", array(
        ":id" => $id,
        ":uniacid" => $_W['uniacid']
    ));
} elseif ($operation == 'delete') {
    ca('shop.brand.delete');
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,brandname FROM " . tablename('sz_yi_brand') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {
        message('抱歉，品牌不存在或是已经被删除！', $this->createWebUrl('shop/brand', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_brand', array(
        'id' => $id
    ));
    plog('shop.brand.delete', "删除品牌 ID: {$id} 标题: {$item['brandname']} ");
    message('品牌删除成功！', $this->createWebUrl('shop/brand', array(
        'op' => 'display'
    )), 'success');
}
load()->func('tpl');
include $this->template('web/shop/brand');
