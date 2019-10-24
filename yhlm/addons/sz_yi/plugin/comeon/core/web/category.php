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

if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
 // print_r($_GPC);exit;
    ca('category.category.view');
    if ($_W['ispost']) {
        ca('category.pinpai.edit|category.pinpai.add');
       // print_r($_GPC);exit;
         //  print_r($_GPC);exit;
        $insert=array(
            'cat_name'=>trim($_GPC['cat_name']),
            'cat_biaoshi'=>trim($_GPC['cat_biaoshi'])
        );
      //  print_r($insert);exit;
            if (empty($id)) {
                ca('category.category.add');
                pdo_insert('sz_yi_comeon_pinpai', $insert);
                $insert_id = pdo_insertid();
                plog('category.category.add', "添加分类 ID: {$insert_id}");
            } else {
                pdo_update('sz_yi_comeon_pinpai',$insert,array(
                    'id'=>$id
                ));
                plog('category.category.edit', "修改分类 ID: {$id}");
            }

        plog('category.category.edit', '批量修改分类');
        message('保存成功！', $this->createPluginWebUrl('comeon/category'));
    }
    //$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_category'));
    //print_r($list);exit;
    //$this->model->catTree($list);
    //$this->model->getSubIds();
   // $list = $this->model->catTree($list);
    //print_r($list);exit;
} elseif ($operation == 'delete') {
    ca('category.category.delete');
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,name FROM " . tablename('sz_yi_virtual_category') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createPluginWebUrl('category/category', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_virtual_category', array(
        'id' => $id
    ));
    plog('category.category.delete', "删除分类 ID: {$id} 标题: {$item['name']} ");
    message('分类删除成功！', $this->createPluginWebUrl('category/category', array(
        'op' => 'display'
    )), 'success');
}
load()->func('tpl');
include $this->template('category');
