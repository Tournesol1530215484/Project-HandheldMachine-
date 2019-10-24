<?php


global $_W, $_GPC;

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
    $sql="SELECT * FROM " . tablename('sz_yi_button') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC ";
    $sql.=' limit '.($pindex -1 ) * $psize .' , '.$psize;
    $list = pdo_fetchall($sql);

    $total=pdo_fetchcolumn("SELECT count(*) FROM " . tablename('sz_yi_button') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY id DESC ");
    $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);

    if (checksubmit('submit')) {

        $wenti=array($_GPC['ask'],$_GPC['ask1'],$_GPC['ask3'],$_GPC['ask5'],$_GPC['ask7'],$_GPC['ask9'],$_GPC['ask11'],$_GPC['ask13'],$_GPC['ask15']);
        $daan=array($_GPC['an'],$_GPC['an1'],$_GPC['an3'],$_GPC['an5'],$_GPC['an7'],$_GPC['an9'],$_GPC['an11'],$_GPC['an13'],$_GPC['an15']);

        $wenti=iserializer($wenti);
        $daan=iserializer($daan);

        $pic=iserializer($_GPC['thumbs']);

        $data = array(
            'uniacid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'type' => trim($_GPC['type']),
            'wenti' => $wenti,
            'daan' => $daan,
            'pic' => $pic,
            'desc' =>  trim($_GPC['desc'])
        );

        if (!empty($id)) {
            pdo_update('sz_yi_button', $data, array(
                'id' => $id
            ));
            plog('shop.adv.edit', "修改底部导航栏 ID: {$id}");
        } else {
            pdo_insert('sz_yi_button', $data);
            $id = pdo_insertid();
            plog('shop.adv.add', "添加底部导航栏 ID: {$id}");
        }
        message('更新幻灯片成功！', $this->createWebUrl('sysset/button'), 'success');
    }
    $item = pdo_fetch("select * from " . tablename('sz_yi_button') . " where id=:id and uniacid=:uniacid limit 1", array(
        ":id" => $id,
        ":uniacid" => $_W['uniacid']
    ));

    $wen=iunserializer($item['wenti']);
    $da=iunserializer($item['daan']);
    $pic=iunserializer($item['pic']);
    $list=[];

    foreach($wen as $ke=>$v){
        $list[$ke]['wen']=$v;
    }
    foreach($da as $ke=>$v){
        $list[$ke]['da']=$v;
    }
    foreach($pic as $ke=>$v) {
        $list[$ke]['pic'] = $v;
    }
    foreach($pic as $ke=>$v) {
        $pic[$ke] = $v;
    }
//    print_r($list);exit;
} elseif ($operation == 'delete') {

    $id   = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id FROM " . tablename('sz_yi_button') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($item)) {
        message('抱歉，底部航栏不存在或是已经被删除！', $this->createWebUrl('sysset/button', array(
            'op' => 'display'
        )), 'error');
    }
    pdo_delete('sz_yi_button', array(
        'id' => $id
    ));
    message('底部导航栏删除成功！', $this->createWebUrl('sysset/button', array(
        'op' => 'display'
    )), 'success');
}
load()->func('tpl');
include $this->template('web/sysset/button');

