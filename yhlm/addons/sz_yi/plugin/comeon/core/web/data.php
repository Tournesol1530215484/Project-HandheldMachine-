<?php
/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
load()->func('tpl');
if ($operation == 'display')
    {
        //print_r($_GPC);exit;
    ca('comeon.data.view');
    $id = $_GPC['id'];
    if (empty($id)) {
        message("Url参数错误！请重试！", $this->createPluginWebUrl('comeon/temp'), 'error');
        exit;
    }
    //查询助手产品
        $type = pdo_fetch("SELECT * FROM " . tablename('sz_yi_comeon') . " d " .
            " left join " . tablename('sz_yi_comeon_cangshang') . ' m on m.cs_id = d.cs_id '.
            " left join " . tablename('sz_yi_comeon_chexing') . ' o on o.x_id = d.x_id ' .
            " left join " . tablename('sz_yi_comeon_pailiang') . ' g on g.pai_id = d.pai_id ' .
            " left join " . tablename('sz_yi_comeon_pinpai') . ' e on e.cat_id=d.cat_id ' .
            " left join " . tablename('sz_yi_comeon_riqi') . 'f on f.ri_id=d.ri_id ' .
            " where d.id=".$id." and d.uniacid=".$_W['uniacid']."");

       //print_r($type);
    $type['fields'] = iunserializer($type['fields']);
    //查询分类信息
      //  print_r($_GPC);
        $list = pdo_fetchall("SELECT * FROM ".tablename('sz_yi_comeon_category'),'where cat_id=:cat_id', array(
            'cat_id'=>$cat_id
        ), 'cat_id');
   // print_r($list);exit;


}
include $this->template('data');
