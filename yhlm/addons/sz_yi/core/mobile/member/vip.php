<?php

global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'dispaly' : trim($_GPC['op']);
$but=pdo_fetch('select * from'.tablename('sz_yi_button').'where uniacid=:uniacid and title=:title',array(':uniacid'=>$_W['uniacid'],'title'=>$_GPC['title']));
$wen=iunserializer($but['wenti']);
$da=iunserializer($but['daan']);
$pic=iunserializer($but['pic']);
$list=[];

foreach($wen as $ke=>$v){
    $list[$ke]['wen']=$v;
}
foreach($da as $ke=>$v){
    $list[$ke]['da']=$v;
}
foreach($pic as $ke=>$v) {
    $list[$ke]['pic'] = $v;
}
include $this->template('member/vip');
