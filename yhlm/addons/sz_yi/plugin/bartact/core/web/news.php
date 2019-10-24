<?php

global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'dispaly' : trim($_GPC['op']);

$article=pdo_fetch('select * from'.tablename('site_article').'where uniacid=:uniacid and id=:id',array(':uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
//print_r($article);exit;

include $this->template('news');
