<?php

global $_W, $_GPC;
$type = empty($_GPC['type'])?0:intval($_GPC['type']);
$list = pdo_fetchall('select id ,name from '.tablename('sz_yi_category')." where uniacid = '{$_W['uniacid']}' and enabled = 1 and parentid = '{$type}'  ");

show_json(1,array('list'=>$list,'status'=>empty($list)?false:true));

