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
    $sql="SELECT * FROM " . tablename('sz_yi_activity_adv') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC ";
    $sql.=' limit '.($pindex -1 ) * $psize .' , '.$psize;
    $list = pdo_fetchall($sql);
    $total=pdo_fetchcolumn("SELECT count(*) FROM " . tablename('sz_yi_activity_adv') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC ");
    $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if (checksubmit('submit')) {

        $data = array(
            'uniacid' => $_W['uniacid'],
            'advname' => trim($_GPC['advname']),
            'link' => trim($_GPC['link']),              
            'status' => intval($_GPC['status']), 
            'displayorder' => intval($_GPC['displayorder']),
            'thumb' => $_GPC['thumb'],
            'place' =>  trim($_GPC['place'])                    
        );           
        if (!empty($id)) {
            pdo_update('sz_yi_activity_adv', $data, array(
                'id' => $id
            ));
            plog('shop.adv.edit', "修改幻灯片 ID: {$id}");
        } else {
            pdo_insert('sz_yi_activity_adv', $data);
            $id = pdo_insertid();
            plog('shop.adv.add', "添加幻灯片 ID: {$id}");
        }
        message('更新幻灯片成功！', $this->createPluginWebUrl('bartact/slide'), 'success');
    }                            
    $item = pdo_fetch("select * from " . tablename('sz_yi_activity_adv') . " where id=:id and uniacid=:uniacid limit 1", array(
        ":id" => $id,
        ":uniacid" => $_W['uniacid']
    ));     
   } elseif ($operation == 'delete') {
    
    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id,advname FROM " . tablename('sz_yi_activity_adv') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {          
        message('抱歉，幻灯片不存在或是已经被删除！', $this->createPluginWebUrl('bartact/slide', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_activity_adv', array(
        'id' => $id 
    ));
    message('幻灯片删除成功！', $this->createPluginWebUrl('bartact/slide', array(
        'op' => 'display'
    )), 'success');
}
load()->func('tpl');  
include $this->template('slide');

         