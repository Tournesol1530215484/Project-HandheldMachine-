<?php
/*=============================================================================
#     FileName: adv.php
#         Desc:
#       Author: Yunzhong - http://www.yunzshop.com
#        Email: 1084070868@qq.com
#     HomePage: http://www.yunzshop.com
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:39:14
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
// ca('bartact.slide');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'sort') {

    $displayorder=$_GPC['displayorder'];
    $num=0;
    foreach ($displayorder as $key => $value) {
        pdo_update('sz_yi_activity_adv',array('displayorder'=>$value),array('id'=>$key,'uniacid'=>$_W['uniacid']));
        $num++;
    }

    if ($num) {
        message('修改成功!','','success');
    }else{
        message('修改失败!','','error');
    }
}
if ($operation == 'display') {

    $pindex=max(1,intval($_GPC['page']));
    $psize=20;
    $sql="SELECT * FROM " . tablename('sz_yi_right') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC ";
    $sql.=' limit '.($pindex -1 ) * $psize .' , '.$psize;
    $list = pdo_fetchall($sql);

    $total=pdo_fetchcolumn("SELECT count(*) FROM " . tablename('sz_yi_right') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC ");
    $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if (checksubmit('submit')) {

        $data = array(
            'uniacid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'url' => trim($_GPC['url']),
            'img'=>$_GPC['thumb']
        );

        if (!empty($id)) {
            pdo_update('sz_yi_right', $data, array(
                'id' => $id
            ));
            plog('shop.adv.edit', "修改右侧导航栏 ID: {$id}");
        } else {
            pdo_insert('sz_yi_right', $data);
            $id = pdo_insertid();
            plog('shop.adv.add', "添加右侧导航栏 ID: {$id}");
        }
        message('更新成功！', $this->createWebUrl('sysset/right'), 'success');
    }
    $item = pdo_fetch("select * from " . tablename('sz_yi_right') . " where id=:id and uniacid=:uniacid limit 1", array(
        ":id" => $id,
        ":uniacid" => $_W['uniacid']
    ));

} elseif ($operation == 'delete') {

    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename('sz_yi_right') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {
        message('抱歉，航栏不存在或是已经被删除！', $this->createWebUrl('sysset/right', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_right', array(
        'id' => $id
    ));
    message('导航栏删除成功！', $this->createWebUrl('sysset/right', array(
        'op' => 'display'
    )), 'success');
}

load()->func('tpl');
include $this->template('web/sysset/right');

