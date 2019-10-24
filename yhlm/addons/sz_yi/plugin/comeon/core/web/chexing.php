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
    ca('comeon.chexing.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));

            $items = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_chexing') . " d " .
                " left join " . tablename('sz_yi_comeon_pinpai') . ' m on m.cat_id = d.cat_id '.
                " left join " . tablename('sz_yi_comeon_cangshang') . ' o on o.cs_id = d.cs_id ' .
                " where  d.uniacid=".$_W['uniacid']."");
   // print_r($items);exit;
    $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_comeon_chexing') . " WHERE uniacid=:uniacid", array(
        ':uniacid' => $_W['uniacid'],
    ));
} elseif ($operation == 'post') {
    //print_r($_GPC);exit;
    $x_id = intval($_GPC['x_id']);
   //  print_r($x_id);exit;
    if (empty($x_id)) {
     //   print_r($x_id);exit;
        ca('comeon.chexing.add');
    } else {
        ca('comeon.chexing.view|comeon.chexing.edit');
    }
    $datacount = 0;
    if (!empty($x_id)) {
         //print_r($x_id);exit;
   //     $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_chexing') . ' WHERE uniacid=:uniacid ', array(
    //        ':uniacid' => $_W['uniacid']
    //    ));
     //   $item = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_cangshang') . " d " . " left join " . tablename('sz_yi_comeon_pinpai') . ' m on m.cat_id = d.cat_id and m.cat_id= d.cat_id and m.uniacid=d.uniacid '."where  d.uniacid=m.uniacid",array(
      //      ':uniacid'=>$_W['uniacid']
      //  ));
    //    $item=pdo_fetchall('select * from' . tablename('sz_yi_comeon_chexing') . " d " . " left join " . tablename("sz_yi_comeon_cangshang") . "  m "  . " left join " . tablename('sz_yi_comeon_pinpai') . 'g on g.cat_id=d.cat_id and m on m.cs_id=d.cs_id' . "where g.uniacid=:uniacid and m.uniacid=:uniacid and d.:uniacid=:uniacid",array(
       //     ':uniacid'=>$_W['uniacid']
     //   ));
        $item = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_chexing') . " d " . " left join " . tablename('sz_yi_comeon_pinpai') . ' m on m.cat_id = d.cat_id and m.uniacid=d.uniacid ' . " left join " . tablename('sz_yi_comeon_cangshang') . ' o on o.cs_id = d.cs_id and o.uniacid=d.uniacid',array(
        ));
       //  print_r($item);exit;
       //  print_r($item);exit;
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_comeon_chexing') . " where x_id=:x_id and uniacid=:uniacid ", array(
            ':x_id' => $x_id,
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
            'x_name' => trim($_GPC['x_name']),
            'cat_id'=>trim($_GPC['cat_id']),
            'cs_id'=>trim($_GPC['cs_id'])
        );
      //   print_r($_GPC);exit;
        if (empty($x_id)) {
            pdo_insert('sz_yi_comeon_chexing', $insert);
            $x_id = pdo_insertid();
            plog('comeon.chexing.edit', "添加车型ID: {$x_id}");
        } else {
            pdo_update('sz_yi_comeon_chexing', $insert, array(
                'x_id' => $x_id
            ));
        plog('comeon.chexing.edit', "编辑车型ID: {$x_id}");
    }
        message('保存成功！', $this->createPluginWebUrl('comeon/chexing'));
    }
} elseif ($operation == 'addtype') {
    ca('comeon.chexing.edit|comeon.chexing.add');
    $addt = $_GPC['addt'];
    $kw   = $_GPC['kw'];
    if ($addt == 'type') {
        include $this->template('tp_type');
    } elseif ($addt == 'data') {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_chexing') . ' WHERE id=:id and uniacid=:uniacid ', array(
            ':id' => $_GPC['typeid'],
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $num            = $_GPC['numlist'];
        include $this->template('tp_data');
    }
    exit;
} elseif ($operation == 'delete') {
    ca('comeon.chexing.delete');
    $x_id = $_GPC['x_id'];
   // print_r($_GPC);
   // print_r($x_id);exit;
    if (!empty($x_id)) {
       // print_r($x_id);exit;
        // print_r($cat_id);exit;
        pdo_delete('sz_yi_comeon_chexing', array(
            'x_id' =>$x_id
        ));
        plog('comeon.chexing.delete', "删除品牌 ID: {$x_id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/chexing'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/chexing'), 'error');
    }
    exit;
}
$category = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_pinpai') ,array(
    ':uniacid' => $_W['uniacid']
), 'x_id');
//$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_pinpai'));
$list = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_comeon_pinpai') .
    " d " . " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cat_id = d.cat_id  '."where  d.uniacid=:uniacid",array(
    ':uniacid'=>$_W['uniacid']
));
//print_r($items);exit;
//查询厂商以及品牌ID
$emw=pdo_fetchall('select * from'. tablename('sz_yi_comeon_cangshang') .'where uniacid=:uniacid',array(
    ';uniacid'=>$_W['uniacid']
));
load()->func('tpl');
include $this->template('chexing');
