<?php
//多级分销商城 QQ:1084070868
global $_W, $_GPC;

ca('perm.set');
$type = m('cache')->getString('permset', 'global');
$set  = array(
    'type' => intval($type)
);
if (checksubmit('submit')) {
    m('cache')->set('permset', intval($_GPC['data']['type']), 'global');
    message('设置成功!', referer(), 'success');
}
load()->func('tpl');
include $this->template('index');