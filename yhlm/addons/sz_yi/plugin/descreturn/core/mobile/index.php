<?php
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user')->getOpenid();
if ($operation == 'desclog') {
	if ($_W['isajax']) {
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 10;
        $condition = " and openid=:openid and uniacid=:uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
            ':openid'  => $openid,
        );
		// 递减全返明细
	    $list = pdo_fetchall("select * from " . tablename('sz_yi_descreturn_log') . " where 1 {$condition} order by id desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

        $total     = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_descreturn_log') . " where 1 {$condition}", $params);
        foreach ($list as &$row) {
            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
        }
        unset($row);
        show_json(1, array(
            'total' => $total,
            'list' => $list,
            'pagesize' => $psize
        ));
    } else {
		include $this->template('desclog');
    }
} elseif ($operation == 'consume') {
	if ($_W['isajax']) {
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 10;
        $condition = " and openid=:openid and uniacid=:uniacid";
        $params    = array(
            ':uniacid' => $_W['uniacid'],
            ':openid' => $openid,
        );
        // 递减全返明细
	    $list = pdo_fetchall("select * from " . tablename('sz_yi_promptly_log') . " where 1 {$condition} order by id desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $params);

        $total     = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_promptly_log') . " where 1 {$condition}", $params);
        foreach ($list as &$row) {
            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
        }
        unset($row);
        show_json(1, array(
            'total' => $total,
            'list' => $list,
            'pagesize' => $psize
        ));
    } else {
		include $this->template('consume');
    }
}
