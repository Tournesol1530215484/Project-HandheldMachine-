<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$pluginbonus = p('bonus');
$bonus = 0;
$level = $this->model->getLevel($openid);

$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and name=:name limit 1', array(':uniacid' => $_W['uniacid'], ':name' => "sz_yi经销商中心入口设置"));
$cover = pdo_fetch("select * from " . tablename('cover_reply') . ' where uniacid=:uniacid and rid=:rid limit 1', array(':uniacid' => $_W['uniacid'], ':rid' => $rule['id']));//图片表



include $this->template('index');