<?php

if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC;
//分类控制器  id、uniacid、display、title、logo、status

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$id = empty($_GPC['id']) ? "" : $_GPC['id'];

if($operation == 'post' ) {
	//添加或编辑
	if(!empty($id)){
		$item = pdo_fetch('SELECT * FROM' . tablename('sz_yi_match_type') .  ' WHERE uniacid='.$_W['uniacid'].' AND id ='.$id);
	}
	
	if (checksubmit('submit')) {
		$data = array(
			'uniacid'   => $_W['uniacid'],
			'display'   => intval($_GPC['display']),
			'title'     => trim($_GPC['title']),
			'status'    => intval($_GPC['status'])
		); 	 		 	 	 	
		if(empty($id)){													//添加	
			if(empty($_GPC['title'])){
				message('请输入分类名称!');
			}
			pdo_insert('sz_yi_match_type', $data);
			$id = pdo_insertid();
			message('分类添加成功!',$this->createPluginWebUrl('bartact/match_type'), 'success');
		}else{
			pdo_update('sz_yi_match_type', $data, array('id' => $id));
			message('分类编辑成功!',$this->createPluginWebUrl('bartact/match_type'), 'success');
		}
	}
}
else if($operation == 'display'){	 		 		 	 		 	 
	//列表
	$page     = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex   = max(1, intval($page));
    $psize    = 10;
    $kw       = empty($_GPC['keyword']) ? "" : $_GPC['keyword'];
    $type    = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_match_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND title LIKE :name ' . ' ORDER BY display DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(
        ':name' => "%{$kw}%"
    ));
    
    $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_match_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND title LIKE :name ' . 'ORDER BY display DESC ', array(
        ':name' => "%{$kw}%"
    ));
    $pager    = pagination($total, $pindex, $psize);
}
else if($operation == 'delete'){
	//删除
	if (empty($id)) {
        die('参数错误!');
    }
    $type = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_match_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND id=:id', array(
        ':id' => $id
    ));
    if (empty($type)) {
        die('该分类未找到!');
    }
    pdo_delete('sz_yi_match_type', array(
        'id' => $id,
    ));
	message('删除成功！', referer(), 'success');
}
load()->func('tpl');
include $this->template('match_type');