<?php

global $_W, $_GPC;
$apido = $_GPC['apido'];
if ($_W['isajax'] && $_W['ispost']) {
    if ($apido == 'selectlike') {
        $cat_id = intval($_GPC['brandid']);
        // print_r($cs_id);
        $lsec = pdo_fetchall('select * from' . tablename('sz_yi_comeon_chexing') . "where cat_id=:cat_id and uniacid=:uniacid", array(
            ':cat_id' => $cat_id,
            ':uniacid' => $_W['uniacid']
        ));
        $result = echo_json(1, 0, $lsec);
        $cs_id = intval($_GPC['brandid']);
    } elseif ($apido == 'selectliee') {
        //   print_r($_GPC);
        $x_id = intval($_GPC['typeid']);
        // print_r($cs_id);
        $lsec = pdo_fetchall('select * from' . tablename('sz_yi_comeon_pailiang') . "where x_id=:x_id and uniacid=:uniacid", array(
            ':x_id' => $x_id,
            ':uniacid' => $_W['uniacid']
        ));
        $result = echo_json(1, 0, $lsec);
        //print_r($lsec);
    } elseif ($apido == 'selectlieoe') {
        $pai_id = intval($_GPC['mlid']);
        $lsec = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_comeon') . " d " .
            " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id '.
            " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id ' .
            " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id ' .
            " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id ' .
            " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id ' .
            " where d.pai_id=".$pai_id." and d.uniacid=".$_W['uniacid']."");

       // $lsec = pdo_fetchall('select * from' . tablename('sz_yi_comeon') . "where pai_id=:pai_id and uniacid=:uniacid", array(
      //      ':pai_id' => $pai_id,
      //      ':uniacid' => $_W['uniacid']
    //    ));

        $result = echo_json(1, 0, $lsec);
    } 
}
