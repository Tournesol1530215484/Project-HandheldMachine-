<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();
$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
//$seller="oSI4Lj89_NloJ76nBtV8zwUIi4-A";
//$cre=m('member')->getCredit($seller, 'credit3');
//print_r($cre);exit;

$set = unserialize($setdata['sets']);
$receive=$set['trade']['receive'];
if ($operation == 'display') {

	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$status = $_GPC['status'];
	$condition = ' uniacid = :uniacid ';
	$paras = $paras1 = array(':uniacid' => $_W['uniacid']); 
	if (empty($starttime) || empty($endtime)) { 
		$starttime = strtotime('-1 month'); 
		$endtime = time();
	}

	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		if ($_GPC['searchtime'] == '1') { 
			$condition .= ' AND createtime >= :starttime AND createtime <= :endtime ';
			$paras[':starttime'] = $starttime;
			$paras[':endtime'] = $endtime;
		}
	}


	if (!empty($_GPC['keyword'])) {
		$_GPC['keyword'] = trim($_GPC['keyword']);
		$condition .= " AND dealsn LIKE '%{$_GPC['keyword']}%'";
	}

    if (!empty($_GPC['sta'])) {
        $_GPC['sta'] = trim($_GPC['sta']);
        if($_GPC['sta']==10){
            $_GPC['sta']=0;
        }
        $condition .= ' AND o.status=' . $_GPC['sta'];
    }

	$sql ='select * from '.tablename('sz_yi_barter_code_log')."where $condition ";

    if (empty($_GPC['export'])) {
		$sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
	}

	$list = pdo_fetchall($sql, $paras);

	// 7天自动收货

	unset($value);
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_barter_code_log').  " WHERE $condition" );
	$pager = pagination($total, $pindex, $psize);
	$stores = pdo_fetchall('select id,storename from ' . tablename('sz_yi_store') . ' where uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));
	load()->func('tpl');
	// print_r($list); 
	include $this->template('order');
	exit; 
}

