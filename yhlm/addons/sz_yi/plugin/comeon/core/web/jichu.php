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
    ca('comeon.jichu.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));
    $items  = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_xitong') . ' WHERE uniacid=:uniacid',array(
        ':uniacid' => $_W['uniacid']
    ));
    $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_comeon_xitong') . " WHERE uniacid=:uniacid", array(
        ':uniacid' => $_W['uniacid'],
    ));
} elseif ($operation == 'post') {
    // print_r($_GPC);exit;
    $xi_id = intval($_GPC['xi_id']);
    // print_r($cat_id);exit;
    if (empty($xi_id)) {
        ca('comeon.jichu.add');
    } else {
        ca('comeon.jichu.view|comeon.jichu.edit');
    }
    $datacount = 0;
    if (!empty($xi_id)) {
        // print_r($cat_id);exit;
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_xitong') . ' WHERE uniacid=:uniacid and xi_id=:xi_id ', array(
            ':uniacid' => $_W['uniacid'],
            ':xi_id'=>$xi_id
        ));

        //    print_r($item);exit;
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_comeon_xitong') . " where xi_id=:xi_id and uniacid=:uniacid ", array(
            ':xi_id' => $xi_id,
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
            'xi_logo' => save_media($_GPC['xi_logo']),
            'xi_phone'=>trim($_GPC['xi_phone']),
            'prompt'=>trim($_GPC['prompt']),
            'xi_name'=>trim($_GPC['xi_name']),
            'zhuyi'=>trim($_GPC['zhuyi'])
        );
        //print_r($_GPC);exit;
        if (empty($xi_id)) {
            //  print_r($pai_id);exit;
            pdo_insert('sz_yi_comeon_xitong', $insert);
            $xi_id = pdo_insertid();
            plog('comeon.jichu.edit', "添加系统ID: {$xi_id}");
        } else {
            pdo_update('sz_yi_comeon_xitong', $insert, array(
                'xi_id' => $xi_id
            ));
            plog('comeon.jichu.edit', "编辑系统ID: {$xi_id}");
        }
        message('保存成功！', $this->createPluginWebUrl('comeon/jichu'));
    }
} elseif ($operation == 'addtype') {
    ca('comeon.jichu.edit|comeon.jichu.add');
    $addt = $_GPC['addt'];
    $kw   = $_GPC['kw'];
    if ($addt == 'type') {
        include $this->template('tp_type');
    } elseif ($addt == 'data') {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_xitong') . ' WHERE xi_id=:xi_id and uniacid=:uniacid ', array(
            ':xi_id' => $_GPC['xi_id'],
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $num            = $_GPC['numlist'];
        include $this->template('tp_data');
    }
    exit;
} elseif ($operation == 'delete') {
    ca('comeon.jichu.delete');
    $xi_id = $_GPC['xi_id'];
    if (!empty($xi_id)) {
        // print_r($cat_id);exit;
        pdo_delete('sz_yi_comeon_xitong', array(
            'xi_id' =>$xi_id
        ));
        plog('comeon.scri.delete', "删除系统 ID: {$ri_id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/jichu'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/jichu'), 'error');
    }
    exit;
}
$category = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_xitong') ,array(
    ':uniacid' => $_W['uniacid']
), 'xi_id');
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_xitong'));
load()->func('tpl');
include $this->template('jichu');
