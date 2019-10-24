<?php
global $_W, $_GPC;
//check_shop_auth('http://120.26.212.219/api.php', $this->pluginname);
load()->func('tpl');
$id=trim($_GPC['id']);
$list=pdo_fetch('select * from' .tablename('sz_yi_comeon_xitong').'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
//查询品牌
$liste=pdo_fetchall('select * from' . tablename('sz_yi_comeon_pinpai') .'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
$items = pdo_fetch("SELECT * FROM " . tablename('sz_yi_comeon') . " d " .
    " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id '.
    " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id ' .
    " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id ' .
    " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id ' .
    " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id ' .
    " where d.id=".$id." and d.uniacid=".$_W['uniacid']."");
//print_r($items);

include $this->template('lise');
