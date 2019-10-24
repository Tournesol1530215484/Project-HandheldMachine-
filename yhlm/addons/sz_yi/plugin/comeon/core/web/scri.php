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
    ca('comeon.scri.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));
    $items  = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_riqi') . ' WHERE uniacid=:uniacid',array(
        ':uniacid' => $_W['uniacid']
    ));
    $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_comeon_riqi') . " WHERE uniacid=:uniacid", array(
        ':uniacid' => $_W['uniacid'],
    ));
} elseif ($operation == 'post') {
    // print_r($_GPC);exit;
    $ri_id = intval($_GPC['ri_id']);
    // print_r($cat_id);exit;
    if (empty($ri_id)) {
        ca('comeon.scri.add');
    } else {
        ca('comeon.scri.view|comeon.scri.edit');
    }
    $datacount = 0;
    if (!empty($ri_id)) {
        // print_r($cat_id);exit;
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_riqi') . ' WHERE uniacid=:uniacid and ri_id=:ri_id ', array(
            ':uniacid' => $_W['uniacid'],
            ':ri_id'=>$ri_id
        ));

        //    print_r($item);exit;
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_comeon_riqi') . " where ri_id=:ri_id and uniacid=:uniacid ", array(
            ':ri_id' => $ri_id,
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
            'ri_name' => trim($_GPC['ri_name'])
        );
        //print_r($_GPC);exit;
        if (empty($ri_id)) {
            //  print_r($pai_id);exit;
            pdo_insert('sz_yi_comeon_riqi', $insert);
            $ri_id = pdo_insertid();
            plog('comeon.scri.edit', "添加排量ID: {$ri_id}");
        } else {
            pdo_update('sz_yi_comeon_riqi', $insert, array(
                'ri_id' => $ri_id
            ));
            plog('comeon.scri.edit', "编辑排量ID: {$ri_id}");
        }
        message('保存成功！', $this->createPluginWebUrl('comeon/scri'));
    }
} elseif ($operation == 'addtype') {
    ca('comeon.scri.edit|comeon.scri.add');
    $addt = $_GPC['addt'];
    $kw   = $_GPC['kw'];
    if ($addt == 'type') {
        include $this->template('tp_type');
    } elseif ($addt == 'data') {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_riqi') . ' WHERE ri_id=:ri_id and uniacid=:uniacid ', array(
            ':ri_id' => $_GPC['ri_id'],
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $num            = $_GPC['numlist'];
        include $this->template('tp_data');
    }
    exit;
} elseif ($operation == 'delete') {
    ca('comeon.scri.delete');
   $ri_id = $_GPC['ri_id'];
    if (!empty($ri_id)) {
        // print_r($cat_id);exit;
        pdo_delete('sz_yi_comeon_riqi', array(
            'ri_id' =>$ri_id
        ));
        plog('comeon.scri.delete', "删除品牌 ID: {$ri_id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/scri'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/scri'), 'error');
    }
    exit;
}
$category = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_riqi') ,array(
    ':uniacid' => $_W['uniacid']
), 'ri_id');
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_riqi'),' where uniacid=:uniacid ',array(
    ';uniacid'=>$_W['uniacid']
));
load()->func('tpl');
include $this->template('scri');
