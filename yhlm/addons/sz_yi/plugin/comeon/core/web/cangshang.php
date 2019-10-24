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
    ca('comeon.cangshang.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));
    //$items  = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_cangshang') . ' WHERE uniacid=:uniacid',array(
     //   ':uniacid' => $_W['uniacid']
   // ));
   $items = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_cangshang') .
       " d " . " left join " . tablename('sz_yi_comeon_pinpai') . ' m on m.cat_id = d.cat_id and m.cat_id= d.cat_id
       '."where  d.uniacid=:uniacid",array(
     ':uniacid'=>$_W['uniacid']
    ));
    //print_r($items);
   // $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_comeon_cangshang') . " WHERE uniacid=:uniacid", array(
    //    ':uniacid' => $_W['uniacid'],
  //  ));
} elseif ($operation == 'post') {
    //print_r($_GPC);exit;
    $cs_id = intval($_GPC['cs_id']);
    

    // print_r($iteme);exit;
    if (empty($cs_id)) {
        ca('comeon.cangshang.add');
    } else {
        ca('comeon.cangshang.view|comeon.cangshang.edit');
    }
    $datacount = 0;
    if (!empty($cs_id)) {
        ///  print_r($cat_id);exit;
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_cangshang') . ' WHERE uniacid=:uniacid ', array(
            ':uniacid' => $_W['uniacid']
        ));
        // print_r($item);exit;
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_comeon_cangshang') . " where cs_id=:cs_id and uniacid=:uniacid ", array(
            ':cs_id' => $cs_id,
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
            'cs_name' => trim($_GPC['cs_name']),
            'cat_id'=>trim($_GPC['cat_id'])
        );

        // print_r($_GPC);exit;
        if (empty($cs_id)) {
            pdo_insert('sz_yi_comeon_cangshang', $insert);
            $cs_id = pdo_insertid();
            plog('comeon.cangshang.edit', "添加厂商ID: {$cs_id}");
        } else {
            pdo_update('sz_yi_comeon_cangshang', $insert, array(
                'cs_id' => $cs_id
            ));
            plog('comeon.temp.edit', "编辑厂商ID: {$cs_id}");
        }
        message('保存成功！', $this->createPluginWebUrl('comeon/cangshang'));
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
    ca('comeon.cangshang.delete');
   /// print_r($_GPC);
    $cs_id = $_GPC['cs_id'];
    if (!empty($cs_id)) {
        // print_r($cat_id);exit;
        pdo_delete('sz_yi_comeon_cangshang', array(
            'cs_id' =>$cs_id
        ));
        plog('comeon.cangshang.delete', "删除品牌 ID: {$cs_id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/cangshang'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/cangshang'), 'error');
    }
    exit;
}
$category = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_cangshang') ,array(
    ':uniacid' => $_W['uniacid']
), 'cs_id');
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid ',array(
    ':uniacid'=>$_W['uniacid']
));
load()->func('tpl');
include $this->template('cangshang');
