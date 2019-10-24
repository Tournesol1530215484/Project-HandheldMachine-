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
    ca('comeon.temp.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));
    $psize  = 12;
    $kw     = empty($_GPC['keyword']) ? "" : $_GPC['keyword'];
//    $items  = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_virtual_type') . ' WHERE uniacid=:uniacid and title like :name order by id desc limit ' . ($pindex - 1) * $psize . ',' . $psize, array(
    //    ':name' => "%{$kw}%",
 //       ':uniacid' => $_W['uniacid']
   // ));

    $items = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon') . " d " .
        " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id '.
        " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id ' .
        " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id ' .
        " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id ' .
        " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id ' .
        " where  d.uniacid=".$_W['uniacid']."");
} elseif ($operation == 'post') {
 //   print_r($_GPC);exit;
    $id = intval($_GPC['id']);
    if (empty($id)) {
        ca('comeon.temp.add');
    } else {
        ca('comeon.temp.view|comeon.temp.edit');
    }
    $datacount = 0;
    if (!empty($id)) {
        $lise=pdo_fetch("select * from" . tablename('sz_yi_comeon_pinpai') . ' where  uniacid=:uniacid',array(
            ':uniacid'=>$_W['uniacid']
        ));
        $liss=pdo_fetch("select * from" . tablename('sz_yi_comeon_cangshang') . ' where uniacid=:uniacid',array(
            ':uniacid'=>$_W['uniacid']
        ));
        print_r($liss);
        $item = pdo_fetch("SELECT * FROM " . tablename('sz_yi_comeon') . " d " .
            " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id '.
            " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id ' .
            " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id ' .
            " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id ' .
            " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id ' .
            " where d.id=".$id." and d.uniacid=".$_W['uniacid']."");
        $itesm = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon') . " d " .
            " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id '.
            " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id ' .
            " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id ' .
            " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id ' .
            " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id ' .
            " where d.id=".$id." and d.uniacid=".$_W['uniacid']."");
        print_r($itesm);
        $catid = $lise['cat_id'];
        $cslist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_cangshang') . ' where cat_id=:cat',array(
            ':cat'=>$catid
        ));
        $csid = $itesm[0]['cs_id'];
        $cxlist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_chexing') . ' where cs_id=:cs',array(
            ':cs'=>$csid
        ));
        $xid = $itesm[0]['x_id'];
        $plist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_pailiang') . ' where x_id=:x',array(
            ':x'=>$xid
        ));
    }else{
        $lise=pdo_fetch("select * from" . tablename('sz_yi_comeon_pinpai') . ' where  uniacid=:uniacid',array(
            ':uniacid'=>$_W['uniacid']
        ));
        $catid = $lise['cat_id'];
        $cslist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_cangshang') . ' where cat_id=:cat',array(
            ':cat'=>$catid
        ));
        $csid = $cslist[0]['cs_id'];
        $cxlist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_chexing') . ' where cs_id=:cs',array(
            ':cs'=>$csid
        ));
        $xid = $cxlist[0]['x_id'];
        $plist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_pailiang') . ' where x_id=:x',array(
            ':x'=>$xid
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
            'cat_id' => trim($_GPC['cat_id']),
            'x_id' => trim($_GPC['x_id']),
            'cs_id' => trim($_GPC['cs_id']),
            'pai_id' => trim($_GPC['pai_id']),
            'ri_id' => trim($_GPC['ri_id']),
            'box' => trim($_GPC['box']),
            'amount' => trim($_GPC['amount']),
            'xinghao' => trim($_GPC['xinghao']),
            'amountg' => trim($_GPC['amountg']),
            'xinghao' => trim($_GPC['xinghao']),
            'biaozhun' => trim($_GPC['biaozhun']),
            'mileage' => trim($_GPC['mileage']),
            'mileages' => trim($_GPC['mileages']),
            'link' => trim($_GPC['link']),
            'ulogo'=>save_media($_GPC['ulogo']),
            'glogo'=>save_media($_GPC['glogo'])
        );
        if (empty($id)) {

            pdo_insert('sz_yi_comeon', $insert);
            $id = pdo_insertid();
            plog('comeon.temp.edit', "添加助手 ID: {$id}");
        } else {
            pdo_update('sz_yi_comeon', $insert, array(
                'id' => $id
            ));
            plog('comeon.temp.edit', "编辑助手 ID: {$id}");
        }
        message('保存成功！', $this->createPluginWebUrl('comeon/temp'));

    }
} elseif ($operation == 'addtype') {

    ca('comeon.temp.edit|comeon.temp.add');
    $addt = $_GPC['addt'];
    $kw   = $_GPC['kw'];
    if ($addt == 'type') {
        include $this->template('tp_type');
    } elseif ($addt == 'data') {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('') . ' WHERE id=:id and uniacid=:uniacid ', array(
            ':id' => $_GPC['typeid'],
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $num            = $_GPC['numlist'];
        include $this->template('tp_data');
    }
    exit;
} elseif ($operation == 'delete') {
    //print_r($_GPC);exit;
    ca('comeon.temp.delete');
    $id = $_GPC['id'];
    if (!empty($id)) {
        pdo_delete('sz_yi_comeon', array(
            'id' => $id
        ));
        plog('comeon.temp.delete', "删除模板 ID: {$id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/temp'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/temp'), 'error');
    }
    exit;
}
//拿出生产日期表
$scri=pdo_fetchall('SELECT * FROM' .tablename('sz_yi_comeon_riqi') . 'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
//拿出排量表
$pailiang=pdo_fetchall('select * from' . tablename('sz_yi_comeon_pailiang') . 'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
//拿出车型表
$chexing=pdo_fetchall('select * from' . tablename('sz_yi_comeon_chexing')  . 'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
//拿出厂商表
$cangshang=pdo_fetchall('select * from' . tablename('sz_yi_comeon_cangshang') . 'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
//拿出品牌表
$pinpai=pdo_fetchall('select * from' .tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));

//print_r($chexing);exit;
load()->func('tpl');
include $this->template('temp');
