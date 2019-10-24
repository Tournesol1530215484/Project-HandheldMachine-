<?php
global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if ($operation == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 15;

	$condition = 'uniacid = :uniacid ';
	$where = array(':uniacid' => $_W['uniacid']);
	if (!empty($_GPC['storename'])) {
		$condition .= ' and storename like "'.$_GPC['storename'].'%"';
	}
	if (!empty($_GPC['isopen'])) {
		$condition .= ' and isopen = :isopen';
		if ($_GPC['isopen'] == -1) {
			$where[':isopen'] = 0;
		} elseif ($_GPC['isopen'] == 1) {
			$where[':isopen'] = 1;
		}
	}

	$totalSql = 'select count(id) from'.tablename('sz_yi_store_data').'where '.$condition;
	$total = pdo_fetchcolumn($totalSql, $where);
	$pager = pagination($total, $pindex, $psize);

	$sql  = 'select * from'.tablename('sz_yi_store_data').'where '.$condition.' order by id desc';
	$sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	$stores = pdo_fetchall($sql, $where);
} elseif ($operation == 'switch') {
	$sql = 'select type from'.tablename('sz_yi_perm_user').'where uniacid = :uniacid and uid = :storeid';
	$bool = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':storeid' => $_GPC['storeid']));
	if ($bool == 1) {
		message('操作失败,店铺主人不是商家!', '', 'error');
	}

	$result = pdo_update('sz_yi_store_data', array('isopen' => $_GPC['status']), array('storeid' => $_GPC['storeid']));
	if (!empty($result)){
	    message('设置保存成功!', referer(), 'success');
	}
}

include $this -> template('store');
