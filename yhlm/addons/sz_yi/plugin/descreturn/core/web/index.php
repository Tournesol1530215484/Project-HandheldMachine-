<?php
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'test') {
	/*$openid = 'oKwkYw5Hv5-mrEGrZ572fv2JrB_s';
	echo m('member')->getCredit($openid, 'credit2');//余额
	$credit1 = 4329.60;
	// m('member')->setCredit($openid, 'credit2', 36631.04);//余额
	echo "string";
	echo m('member')->getCredit($openid, 'credit2');//余额*/

} elseif ($operation == 'set') { // 全返设置
	p('descreturn')->promptly(1);
	$sql = 'select * from '.tablename('sz_yi_descreturn_set').' where uniacid=:uniacid';
	$set = pdo_fetch($sql, array(':uniacid'=>$_W['uniacid']));

	if (checksubmit('submit')) {
		$data = $_GPC['setdata'];
		$data['uniacid'] = $_W['uniacid'];
		if ($set) {
			$result = pdo_update('sz_yi_descreturn_set', $data, array('uniacid' => $_W['uniacid']));
			if (!empty($result)) {
			    message('设置成功!', referer(), 'success');
			}
		} else {
			$result = pdo_insert('sz_yi_descreturn_set', $data);
			if (!empty($result)) {
			    message('设置保存成功!', referer(), 'success');
			}
		}
	}
} elseif ($operation == 'order') { // 全返订单

	$paras = array();
	$condition = '';

	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$condition .= ' AND do.createtime >= :starttime AND do.createtime <= :endtime ';
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if (!empty($_GPC['member'])) {
		$_GPC['member'] = trim($_GPC['member']);
		$condition .= " AND (m.realname LIKE '%{$_GPC['member']}%' or m.mobile LIKE '%{$_GPC['member']}%' or m.nickname LIKE '%{$_GPC['member']}%') ";
	}

	$condition .= 'and do.uniacid=:uniacid and do.openid=m.openid';

	$pindex = max(1, intval($_GPC['page']));
	$psize  = 20;

	// $sql = 'select do.id,do.openid,sum(do.price) as price,do.createtime,do.status,m.realname,m.nickname,m.avatar,m.id as mid from'.tablename('sz_yi_descreturn_order').' as do,'.tablename('sz_yi_member').' as m where 1 '.$condition.' group by do.openid order by do.id desc limit '.($pindex - 1) * $psize.' , '.$psize;
	$sql = 'select do.id,do.openid,do.createtime,do.status,m.realname,m.nickname,m.avatar,m.id as mid from'.tablename('sz_yi_descreturn_order').' as do left join '.tablename('sz_yi_member').' as m on do.openid=m.openid where 1 '.$condition.' group by do.openid order by do.id desc limit '.($pindex - 1) * $psize.' , '.$psize;

	$paras[':uniacid'] = $_W['uniacid'];
	// 按 sz_yi_descreturn_list 的总数分页
	$total = pdo_fetchcolumn('select count(do.id) from'.tablename('sz_yi_descreturn_list').' as do,'.tablename('sz_yi_member').' as m where 1 '.$condition, $paras);
	$list = pdo_fetchall($sql, $paras);

	if (!empty($_GPC['gname'])) {
		$_GPC['gname'] = trim($_GPC['gname']);
		$gnameSearch = " AND  g.title LIKE '%{$_GPC['gname']}%' ";
	}
	$sql = 'select do.goodsid,do.price,do.createtime,g.title,g.thumb from'.tablename('sz_yi_descreturn_order').' as do ,'.tablename('sz_yi_goods').'as g where do.uniacid=:uniacid and do.openid=:openid and do.goodsid=g.id '.$gnameSearch;
	foreach ($list as & $value) {
		$value['goodslist']  = pdo_fetchall($sql, array(':uniacid'=>$_W['uniacid'], ':openid'=>$value['openid']));
	}
	unset($value);
	if ($_SERVER['REMOTE_ADDR'] == '120.197.55.24') {
	    // print_r($list);
	}
	// var_dump($list);

} elseif ($operation == 'detail') { // 全返明细
	// 删除记录
	if (!empty($_GPC['action']) && $_GPC['action'] == 'del') {
		$id = $_GPC['id'];
		$result = pdo_delete('sz_yi_descreturn_log', array('id'=>$id));
		if (!empty($result)) {
		    message('删除成功！', referer(), 'success');
		}
	}
	$paras = array();
	$condition = '';
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$condition .= ' AND l.createtime >= :starttime AND l.createtime <= :endtime ';
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if (!empty($_GPC['member'])) {
		$_GPC['member'] = trim($_GPC['member']);
		$condition .= " AND (m.realname LIKE '%{$_GPC['member']}%' or m.mobile LIKE '%{$_GPC['member']}%' or m.nickname LIKE '%{$_GPC['member']}%') ";
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize  = 20;

	// $condition .= 'and l.uniacid=:uniacid and l.openid=m.openid';
	$condition .= 'and l.uniacid=:uniacid and l.openid=m.openid and l.this_price > 0';
	$sql = 'select l.*,m.realname,m.nickname,m.avatar,m.id as mid from'.tablename('sz_yi_descreturn_log').' as l,'.tablename('sz_yi_member').' as m where 1 '.$condition.' order by l.id desc limit '.($pindex - 1) * $psize.' , '.$psize;
	$paras[':uniacid'] = $_W['uniacid'];
	$list = pdo_fetchall($sql, $paras);

	$total = pdo_fetchcolumn('select count(*) from'.tablename('sz_yi_descreturn_log').' as l,'.tablename('sz_yi_member').' as m where 1 '.$condition, $paras);
} elseif ($operation == 'promptlyorder') {
	$paras = array(':uniacid' => $_W['uniacid']);
	$condition = '';
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$condition .= ' AND pl.createtime >= :starttime AND pl.createtime <= :endtime ';
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if (!empty($_GPC['member'])) {
		$_GPC['member'] = trim($_GPC['member']);
		$condition .= " AND (m.realname LIKE '%{$_GPC['member']}%' or m.mobile LIKE '%{$_GPC['member']}%' or m.nickname LIKE '%{$_GPC['member']}%') ";
	}

	$sql = 'select pl.*,m.nickname,m.realname,m.avatar,m.id as mid from'.tablename('sz_yi_promptly_list').' as pl left join'.tablename('sz_yi_member').' as m on m.openid=pl.openid where pl.uniacid=:uniacid '.$condition.' order by id desc';
	$prolist = pdo_fetchall($sql, $paras);

	// 供应商资料
	$sql = 'select id,realname,realname,nickname,avatar from'.tablename('sz_yi_member').'where openid=:openid';
	foreach ($prolist as & $value) {
		$value['supplier_openid'] = pdo_fetch($sql, array(':openid'=>$value['supplier_openid']));
	}
	// print_r($prolist);
} elseif ($operation == 'promptlyd') {
	$paras = array();
	$condition = '';
	if (empty($starttime) || empty($endtime)) {
		$starttime = strtotime('-1 month');
		$endtime = time();
	}
	if (!empty($_GPC['time'])) {
		$starttime = strtotime($_GPC['time']['start']);
		$endtime = strtotime($_GPC['time']['end']);
		$condition .= ' AND l.createtime >= :starttime AND l.createtime <= :endtime ';
		$paras[':starttime'] = $starttime;
		$paras[':endtime'] = $endtime;
	}
	if (!empty($_GPC['member'])) {
		$_GPC['member'] = trim($_GPC['member']);
		$condition .= " AND (m.realname LIKE '%{$_GPC['member']}%' or m.mobile LIKE '%{$_GPC['member']}%' or m.nickname LIKE '%{$_GPC['member']}%') ";
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize  = 20;

	$condition .= 'and l.uniacid=:uniacid and l.this_price > 0';
	$sql = 'select l.*,m.realname,m.nickname,m.avatar,m.id as mid from'.tablename('sz_yi_promptly_log').' as l left join '.tablename('sz_yi_member').' as m on m.openid=l.openid where 1 '.$condition.' order by l.id desc limit '.($pindex - 1) * $psize.' , '.$psize;
	$paras[':uniacid'] = $_W['uniacid'];
	$list = pdo_fetchall($sql, $paras);

	$total = pdo_fetchcolumn('select count(*) from'.tablename('sz_yi_promptly_log').' as l left join '.tablename('sz_yi_member').' as m on m.openid=l.openid where 1 '.$condition, $paras);
}
$pager = pagination($total, $pindex, $psize);
include $this->template('index');