<?php

global $_W, $_GPC;
$apido = $_GPC['apido'];
if ($_W['isajax'] && $_W['ispost']) {
    if ($apido == 'selectlike') {
        $cat_id = intval($_GPC['brandid']);
        //通过brandid查询厂商
        $lisc      = pdo_fetchall('select * from ' . tablename('sz_yi_comeon_cangshang') . " where cat_id=:cat_id and uniacid=:uniacid", array(
            ':cat_id' => $cat_id,
            ':uniacid' => $_W['uniacid']
        ));
        foreach($lisc as $key => $value){
            if($value['cat_id']==$cat_id){
                $lisc[$key]['see'] ="selected";
            }

        }

        //  print_r($lisc);
        $result=echo_json(1,0,$lisc);
    }elseif($apido == 'selectliee'){
     //   print_r($_GPC);
        $cs_id = intval($_GPC['typeid']);
       // print_r($cs_id);

        $lsec=pdo_fetchall('select * from' . tablename('sz_yi_comeon_chexing') ."where cs_id=:cs_id and uniacid=:uniacid",array(
            ':cs_id' => $cs_id,
            ':uniacid' => $_W['uniacid']
        ));
        $result=echo_json(1,0,$lsec);
        //print_r($lsec);
    }elseif($apido  == 'selectlieoe'){
        $x_id = intval($_GPC['mlid']);
        $lsecw=pdo_fetchall('select * from' . tablename('sz_yi_comeon_pailiang') ."where x_id=:x_id and uniacid=:uniacid",array(
            ':x_id' => $x_id,
            ':uniacid' => $_W['uniacid']
        ));
        $result=echo_json(1,0,$lsecw);
    }
}