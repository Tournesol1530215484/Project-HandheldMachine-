<?php
global $_W, $_GPC;
//check_shop_auth('http://120.26.212.219/api.php', $this->pluginname);
load()->func('tpl');
$openid         = m('user')->getOpenid();
$member         = m('member')->getInfo($openid);
$apido = $_GPC['apido'];
if($apido == 'add'){
   //准备数据
    $data = array(
        'm_time'=>TIMESTAMP,
        'm_neirong'=>trim($_GPC['braneq']),
        'm_lianxi'=>trim($_GPC['branwq']),
        'm_name'=>trim($_GPC['brand']),
        'openid'=>trim($_GPC['brane']),
        'uid'=>trim($_GPC['branq']),
        'uniacid'=>trim($_W['uniacid'])
    );
   //写入数据表
    $lise=pdo_insert('sz_yi_message',$data);
    $result=echo_json(1,0,$lise);
}elseif($apido == 'adde'){
    //准备数据
    print_r($_GPC);exit;
    $date = array(
        're_time'=>TIMESTAMP,
        're_neirong'=>trim($_GPC['reneirong']),
        'm_id'=>trim($_GPC['mid']),
    );
    $list=pdo_insert('sz_yi_message_reply',$date);
    $result=echo_json(1,0,$list);
}elseif($apido ==  'dispya'){
    $lsec = pdo_fetchalls("select * FROM " . tablename('sz_yi_message') . " d " .
        " left join " . tablename('sz_yi_message_reply') . ' m on m.m_id = d.m_id '.
       "");

    $result=echo_json(1,0,$lisc);
}
$lisc      = pdo_fetchall('select * from ' . tablename('sz_yi_message') . " where uniacid=:uniacid and m_status = 1", array(
    ':uniacid' => $_W['uniacid']
));
$lsec = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_message') . " d " .
    " left join " . tablename('sz_yi_message_reply') . ' m on m.m_id = d.m_id '.
    " where d.m_status = 1 and m.re_status = 1 and d.uniacid=".$_W['uniacid']."");
print_r($lsec);
//查询出发言表
include $this->template('index');


