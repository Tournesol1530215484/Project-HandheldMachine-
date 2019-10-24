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
    ca('comeon.pinpai.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));
    $items  = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_pinpai') . ' WHERE uniacid=:uniacid',array(
        ':uniacid' => $_W['uniacid']
    ));
    $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_comeon_pinpai') . " WHERE uniacid=:uniacid", array(
        ':uniacid' => $_W['uniacid'],
    ));
} elseif ($operation == 'post') {
    //print_r($_GPC);exit;
   $cat_id = intval($_GPC['cat_id']);
   // print_r($cat_id);exit;
    if (empty($cat_id)) {
        ca('comeon.pinpai.add');
    } else {
        ca('comeon.pinpai.view|comeon.pinpai.edit');
    }
    $datacount = 0;
    if (!empty($cat_id)) {
      ///  print_r($cat_id);exit;
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_pinpai') . ' WHERE uniacid=:uniacid ', array(
            ':uniacid' => $_W['uniacid']
        ));
       // print_r($item);exit;
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_comeon') . " where cat_id=:$cat_id and uniacid=:uniacid ", array(
            ':cat_id' => $cat_id,
            ':uniacid' => $_W['uniacid']
        ));
    }
    if ($_W['ispost']) {
        $keywords = $_GPC['tp_kw'];
        $names    = $_GPC['tp_name'];
        if (!empty($keywords)) {
            $data = array();
            foreach ($keywords as $key => $val) {
                $data[$keywords[$key]] = $names[$key];
            }
        }
        $insert = array(
            'uniacid' => $_W['uniacid'],
            'cat_name' => trim($_GPC['cat_name']),
            'cat_biaoshi'=>trim($_GPC['cat_biaoshi'])
        );
       // print_r($_GPC);exit;
        if (empty($cat_id)) {
            pdo_insert('sz_yi_comeon_pinpai', $insert);
            $cat_id = pdo_insertid();
            plog('comeon.pinpai.edit', "添加品牌ID: {$cat_id}");
        } else {
            pdo_update('sz_yi_comeon_pinpai', $insert, array(
                'cat_id' => $cat_id
            ));
            plog('comeon.temp.edit', "编辑品牌ID: {$cat_id}");
        }
        message('保存成功！', $this->createPluginWebUrl('comeon/pinpai'));
    }
} elseif ($operation == 'addtype') {
    ca('comeon.temp.edit|comeon.temp.add');
    $addt = $_GPC['addt'];
    $kw   = $_GPC['kw'];
    if ($addt == 'type') {
        include $this->template('tp_type');
    } elseif ($addt == 'data') {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_virtual_type') . ' WHERE id=:id and uniacid=:uniacid ', array(
            ':id' => $_GPC['typeid'],
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $num            = $_GPC['numlist'];
        include $this->template('tp_data');
    }
    exit;
} elseif ($operation == 'delete') {
    ca('comeon.pinpai.delete');
    $cat_id = $_GPC['cat_id'];
    if (!empty($cat_id)) {
       // print_r($cat_id);exit;
        pdo_delete('sz_yi_comeon_pinpai', array(
            'cat_id' =>$cat_id
        ));
        plog('comeon.pinpai.delete', "删除品牌 ID: {$cat_id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/pinpai'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/pinpai'), 'error');
    }
    exit;
}
$category = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_pinpai') ,array(
    ':uniacid' => $_W['uniacid']
), 'cat_id');
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid',array(
    ':uniacid' => $_W['uniacid']
));
load()->func('tpl');
include $this->template('pinpai');
