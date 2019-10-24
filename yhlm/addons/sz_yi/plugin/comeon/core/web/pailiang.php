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
$category = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_pailiang') ,array(
    ':uniacid' => $_W['uniacid']
), 'pai_id');
$list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_pinpai') . 'where uniacid=:uniacid ',array(
    'uniacid'=>$_W['uniacid']
));
if ($operation == 'display') {
    ca('comeon.pailiang.view');
    $page   = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex = max(1, intval($page));

    $items = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon_pailiang') . " d " .
        " left join " . tablename('sz_yi_comeon_pinpai') . ' m on m.cat_id = d.cat_id '.
        " left join " . tablename('sz_yi_comeon_cangshang') . ' o on o.cs_id = d.cs_id ' .
        " left join"  . tablename('sz_yi_comeon_chexing') . 'g on g.x_id =d.x_id' .
        " where  d.uniacid=".$_W['uniacid']."");

   // print_r($items);
    $total  = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_comeon_pailiang') . " WHERE uniacid=:uniacid", array(
        ':uniacid' => $_W['uniacid'],
    ));
} elseif ($operation == 'post') {
   //print_r($_GPC);exit;
    $pai_id = intval($_GPC['pai_id']);
    // print_r($cat_id);exit;
    if (empty($pai_id)) {
        ca('comeon.pailiang.add');
    } else {
        ca('comeon.pailiang.view|comeon.pailiang.edit');
    }
    $datacount = 0;
    if (!empty($pai_id)) {
        // print_r($cat_id);exit;
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_pailiang') . ' WHERE uniacid=:uniacid and pai_id=:pai_id ', array(
            ':uniacid' => $_W['uniacid'],
            ':pai_id'=>$pai_id
        ));

     //    print_r($item);exit;
        $item['fields'] = iunserializer($item['fields']);
        $datacount      = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_comeon_pailiang') . " where pai_id=:pai_id and uniacid=:uniacid ", array(
            ':pai_id' => $pai_id,
            ':uniacid' => $_W['uniacid']
        ));
        $catid = $item['cat_id'];
        $cslist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_cangshang') . ' where cat_id=:cat',array(
            ':cat'=>$catid
        ));
        $csid = $item['cs_id'];
        $cxlist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_chexing') . ' where cs_id=:cs',array(
            ':cs'=>$csid
        ));
    }else{
        $catid = $list[0]['cat_id'];
        $cslist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_cangshang') . ' where cat_id=:cat',array(
                    ':cat'=>$catid
        ));

        $csid = $cslist[0]['cs_id'];
        $cxlist = pdo_fetchall("select * from" . tablename('sz_yi_comeon_chexing') . ' where cs_id=:cs',array(
                    ':cs'=>$csid
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
            'pai_name' => trim($_GPC['pai_name']),
            'cat_id'=>trim($_GPC['cat_id']),
            'cs_id'=>trim($_GPC['cs_id']),
            'x_id'=>trim($_GPC['x_id'])
        );
        //print_r($_GPC);exit;
        if (empty($pai_id)) {
          //  print_r($pai_id);exit;
            pdo_insert('sz_yi_comeon_pailiang', $insert);
            $pai_id = pdo_insertid();
            plog('comeon.pailiang.edit', "添加排量ID: {$pai_id}");
        } else {
            pdo_update('sz_yi_comeon_pailiang', $insert, array(
                'pai_id' => $pai_id
            ));
            plog('comeon.pailiang.edit', "编辑排量ID: {$pai_id}");
        }
        message('保存成功！', $this->createPluginWebUrl('comeon/pailiang'));
    }
} elseif ($operation == 'addtype') {
    ca('comeon.pailiang.edit|comeon.pailiang.add');
    $addt = $_GPC['addt'];
    $kw   = $_GPC['kw'];
    if ($addt == 'type') {
        include $this->template('tp_type');
    } elseif ($addt == 'data') {
        $item           = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_comeon_pailiang') . ' WHERE pai_id=:pai_id and uniacid=:uniacid ', array(
            ':pai_id' => $_GPC['pai_id'],
            ':uniacid' => $_W['uniacid']
        ));
        $item['fields'] = iunserializer($item['fields']);
        $num            = $_GPC['numlist'];
        include $this->template('tp_data');
    }
    exit;
} elseif ($operation == 'delete') {
    ca('comeon.pailiang.delete');
    $pai_id = $_GPC['pai_id'];
    if (!empty($pai_id)) {
        // print_r($cat_id);exit;
        pdo_delete('sz_yi_comeon_pailiang', array(
            'pai_id' =>$pai_id
        ));
        plog('comeon.pinpai.delete', "删除品牌 ID: {$pai_id}");
        message('删除成功！', $this->createPluginWebUrl('comeon/pailiang'));
    } else {
        message('Url参数错误！请重试！', $this->createPluginWebUrl('comeon/pailiang'), 'error');
    }
    exit;
}


load()->func('tpl');
include $this->template('pailiang');
