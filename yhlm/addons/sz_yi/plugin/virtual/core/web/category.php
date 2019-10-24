<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    ca('virtual.category.view');
    if (!empty($_GPC['catname'])) {
        ca('virtual.category.edit|virtual.category.add');
        foreach ($_GPC['catname'] as $id => $catname) {
            if ($id == 'new') {
                ca('virtual.category.add');
                pdo_insert('sz_yi_virtual_category', array(
                    'name' => $catname,
                    'uniacid' => $_W['uniacid']
                ));
                $insert_id = pdo_insertid();
                plog('virtual.category.add', "添加分类 ID: {$insert_id}");
            } else {
                pdo_update('sz_yi_virtual_category', array(
                    'name' => $catname
                ), array(
                    'id' => $id
                ));
                plog('virtual.category.edit', "修改分类 ID: {$id}");
            }
        }
        plog('virtual.category.edit', '批量修改分类');
        message('分类更新成功！', $this->createPluginWebUrl('virtual/category', array(
            'op' => 'display'
        )), 'success');
    }
    $list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_virtual_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC");
} elseif ($operation == 'delete') {
    ca('virtual.category.delete');
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,name FROM " . tablename('sz_yi_virtual_category') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createPluginWebUrl('virtual/category', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_virtual_category', array(
        'id' => $id
    ));
    plog('virtual.category.delete', "删除分类 ID: {$id} 标题: {$item['name']} ");
    message('分类删除成功！', $this->createPluginWebUrl('virtual/category', array(
        'op' => 'display'
    )), 'success');
}
load()->func('tpl');
include $this->template('category');
