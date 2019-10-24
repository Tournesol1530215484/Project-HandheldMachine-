<?php
// use Grafika\Grafika; // Import package
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$op = empty($_GPC['op']) ? 'display': $_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid);
$member=m('member')->getMember($openid);





include $this->template('forum-info');
