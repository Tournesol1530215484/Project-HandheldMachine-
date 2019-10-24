<?php
 global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if ($operation == 'display'){
    $where = '';
    $pindex = max(1,intval($_GPC['page']));
    $psize = 20;
    if(!empty($_GPC['uid'])){
        $where .= ' u.uid=' . $_GPC['uid'];
    }
    if(!empty($_GPC['applysn'])){
        $where .= ' and a.applysn=' . $_GPC['applysn'];
    }
    $list = pdo_fetchall('select a.*,p.*,a.status appstatus from ' . tablename('sz_yi_supplier_apply') . ' a left join ' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid where a.status != 0 and p.uniacid=' . $_W['uniacid'] . $where.' order by a.id desc limit '.($pindex -1) * $psize .','.$psize);
    // $total = count($list);	 		 
    $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_supplier_apply') . ' a left join ' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid where a.status != 0  and p.uniacid=' . $_W['uniacid'] . $where);	 		 		 
	$pager = pagination($total, $pindex, $psize);

}
load() -> func('tpl');
include $this -> template('supplier_finish');
	 			