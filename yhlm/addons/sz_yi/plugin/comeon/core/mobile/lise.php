<?php
global $_W, $_GPC;
//check_shop_auth('http://120.26.212.219/api.php', $this->pluginname);
load()->func('tpl');
//print_r($_GPC);
//查询顶部图片
$list=pdo_fetch('select * from' .tablename('sz_yi_comeon_xitong').'where uniacid=:uniacid',array(
    ':uniacid'=>$_W['uniacid']
));
$items = pdo_fetch("SELECT * FROM " . tablename('sz_yi_comeon') . " d " . " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id and m.uniacid=d.uniacid ' . " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id and o.uniacid=d.uniacid' .  " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id and g.uniacid = d.uniacid' . " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id and e.uniacid=d.uniacid' . " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id and f.uniacid=d.uniacid');
//print_r($items);
include $this->template('lise');


