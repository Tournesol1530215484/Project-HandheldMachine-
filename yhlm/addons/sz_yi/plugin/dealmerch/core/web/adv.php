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

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'sort') {
    $id=intval($_GPC['id']); 
    $num=intval($_GPC['num']); 
    $sure=pdo_update('sz_yi_adv',array('displayorder'=>$num),array('id'=>$id,'uniacid'=>$_W['uniacid'],'isbart'=>1));
    if ($sure) {
        show_json(1,'修改成功!');
    }else{
        show_json(0,'修改失败');
    }
}
if ($operation == 'display') {

    $list = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_adv') . " WHERE uniacid = '{$_W['uniacid']}' and isbart = 1 and enabled = 0 ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (empty($id)) {
        ca('shop.adv.add');
    } else { 
        ca('shop.adv.edit|shop.adv.view'); 
    }
    if (checksubmit('submit')) {
        //print_r($_GPC);exit;
        $data = array(
            'uniacid' => $_W['uniacid'],
            'advname' => trim($_GPC['advname']),
            'link' => trim($_GPC['link']),
            'status' => intval($_GPC['status']), 
            'displayorder' => intval($_GPC['displayorder']),
            'thumb' => save_media($_GPC['thumb']),
            'isbart' =>1
        );  
        if (!empty($id)) {
            pdo_update('sz_yi_adv', $data, array(
                'id' => $id
            ));
            plog('shop.adv.edit', "修改幻灯片 ID: {$id}");
        } else {
            pdo_insert('sz_yi_adv', $data);
            $id = pdo_insertid();
            plog('shop.adv.add', "添加幻灯片 ID: {$id}");
        }
        message('更新幻灯片成功！', $this->createPluginWebUrl('dealmerch/set', array(
            'op' => 'display'
        )), 'success');
    }
    $item = pdo_fetch("select * from " . tablename('sz_yi_adv') . " where id=:id and uniacid=:uniacid limit 1", array(
        ":id" => $id,
        ":uniacid" => $_W['uniacid']
    ));
} elseif ($operation == 'delete') {
    ca('shop.adv.delete');
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,advname FROM " . tablename('sz_yi_adv') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {
        message('抱歉，幻灯片不存在或是已经被删除！', $this->createPluginWebUrl('dealmerch/set', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_adv', array(
        'id' => $id 
    ));
    plog('shop.adv.delete', "删除幻灯片 ID: {$id} 标题: {$item['advname']} ");
    message('幻灯片删除成功！', $this->createPluginWebUrl('dealmerch/set', array(
        'op' => 'display'
    )), 'success');
}
load()->func('tpl');  
include $this->template('adv');
