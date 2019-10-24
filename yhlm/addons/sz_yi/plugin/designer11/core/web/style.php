<?php
//多级分销商城 QQ:1084070868
global $_W, $_GPC;
load()->func('tpl');
$op     = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$tempdo = empty($_GPC['tempdo']) ? "" : $_GPC['tempdo'];
$menuid = empty($_GPC['menuid']) ? "" : $_GPC['menuid'];
$apido  = empty($_GPC['apido']) ? "" : $_GPC['apido'];
if ($op == 'display') {
    ca('designer.menu.view');
    $page     = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex   = max(1, intval($page));
    $psize    = 10;
    $kw       = empty($_GPC['keyword']) ? "" : $_GPC['keyword'];
    $menus    = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_member_style') . " WHERE uniacid= :uniacid ",array(':uniacid'=>$_W['uniacid']));
    $menusnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_designer_menu') . " WHERE uniacid= :uniacid " . "ORDER BY createtime DESC ", array(
        ':uniacid' => $_W['uniacid']
    ));
    $total    = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('sz_yi_designer_menu') . " WHERE uniacid= :uniacid and menuname LIKE :name " . "ORDER BY createtime DESC ", array(
        ':uniacid' => $_W['uniacid'],
        ':name' => "%{$kw}%"
    ));
    $pager    = pagination($total, $pindex, $psize);
} elseif ($op == 'post') {
    
} elseif ($op == 'delete') {
    ca('designer.menu.delete');
    if (empty($menuid)) {
        die('参数错误!');
    }
    $menu = pdo_fetch("SELECT * FROM " . tablename('sz_yi_designer_menu') . " WHERE uniacid= :uniacid and id=:id", array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $menuid
    ));
    if (empty($menu)) {
        die('菜单未找到!');
    }
    pdo_delete('sz_yi_designer_menu', array(
        'id' => $menuid,
        'uniacid' => $_W['uniacid']
    ));
    die('success');
} elseif ($op == 'setdefault') {
    if ($_GPC['d'] == 'on') {
        pdo_update('sz_yi_member_style', array(
            'style' => 0
        ));
        pdo_update('sz_yi_member_style', array(
            'style' => 1
        ), array(
            'id' => $menuid,
        ));
    } else {
        pdo_update('sz_yi_member_style', array(
            'style' => 0
        ), array(
            'id' => $menuid,
        ));
    }
    die('success');
}

include $this->template('style');
