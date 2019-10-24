<?php

	//动态获取省份和城市

	global $_W, $_GPC;

	$type = empty($_GPC['type'])?0:intval($_GPC['type']);

	$list2=pdo_fetchall('select id ,name from '.tablename('sz_yi_category')." where uniacid = '{$_W['uniacid']}' and enabled = 1 and parentid = '{$type}'  ");

	return show_json(1,array('list2'=>$list2,'status'=>empty($list2)?false:true));