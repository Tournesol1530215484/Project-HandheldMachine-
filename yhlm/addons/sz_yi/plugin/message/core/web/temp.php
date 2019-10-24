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
    $list=pdo_fetchall('select * from' .tablename('sz_yi_message'));
   // print_r($list);
    ca('message.temp.view');
} elseif ($operation == 'post') {
   $m_id = intval($_GPC['m_id']);
    if (empty($m_id)) {
        ca('message.temp.add');
    } else {
        ca('message.temp.view|message.temp.edit');
    }
    $datacount = 0;
    if (!empty($id)) {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_virtual_type') . ' WHERE id=:id and uniacid=:uniacid ', array(
            ':id' => $id,
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_virtual_data') . " where typeid=:typeid and uniacid=:uniacid and openid='' limit 1", array(
            ':typeid' => $id,
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
            'cate' => intval($_GPC['cate']),
            'title' => trim($_GPC['tp_title']),
            'fields' => iserializer($data)
        );
        if (empty($id)) {
            pdo_insert('sz_yi_virtual_type', $insert);
            $id = pdo_insertid();
            plog('message.temp.edit', "编辑模板 ID: {$id}");
        } else {
            pdo_update('sz_yi_virtual_type', $insert, array(
                'id' => $id
            ));
            plog('message.temp.edit', "编辑模板 ID: {$id}");
        }
        message('保存成功！', $this->createPluginWebUrl('message/temp'));
    }
} elseif ($operation == 'addtype') {
    ca('virtual.temp.edit|virtual.temp.add');
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
    ca('message.temp.delete');
    $m_id = $_GPC['m_id'];
    if (!empty($m_id)) {
        pdo_delete('sz_yi_message', array(
            'm_id' => $m_id
        ));
        plog('message.temp.delete', "删除留言 ID: {$m_id}");
        message('删除成功！', $this->createPluginWebUrl('message/temp'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('message/temp'), 'error');
    }
    exit;
}
$category = pdo_fetchall('select * from ' . tablename('sz_yi_virtual_category') . ' where uniacid=:uniacid order by id desc', array(
    ':uniacid' => $_W['uniacid']
), 'id');
load()->func('tpl');
include $this->template('temp');
