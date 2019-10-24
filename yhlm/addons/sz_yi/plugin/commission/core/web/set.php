<?php
global $_W, $_GPC;

ca('commission.set');
$set = $this->getSet();
if (checksubmit('submit')) {
    $data = is_array($_GPC['setdata']) ? array_merge($set, $_GPC['setdata']) : array();
	
/*	$data['selfbuy']         = $_GPC['selfbuy'];
	$data['upgrade_by_good'] = $_GPC['upgrade_by_good'];
	$data['become_order']    = $_GPC['become_order'];
	$data['become_reg']      = $_GPC['become_reg'];
	$data['become_check']    = $_GPC['become_check'];
	
	$data['closetocredit']    = $_GPC['closetocredit'];//开启提现到余额
	$data['select_goods']    = $_GPC['select_goods'];//分销商自选商品
	$data['closemyshop']    = $_GPC['closemyshop'];//是否关闭"我的小店"功能
	$data['openorderdetail']    = $_GPC['openorderdetail'];//分销订单商品详情
	$data['openorderbuyer']    = $_GPC['openorderbuyer'];//分销订单购买者详情
	$data['remind_message']    = $_GPC['remind_message'];//开启三级消息提醒
	$data['liuyan']    = $_GPC['liuyan'];//开启留言*/

    $data['texts'] = is_array($_GPC['texts']) ? $_GPC['texts'] : array();
    $data['culate_method'] = $_GPC['culate_method'];
	
    $this->updateSet($data);
    m('cache')->set('template_' . $this->pluginname, $data['style']);
    plog('commission.set', '修改基本设置');
    message('设置保存成功!', referer(), 'success');
}
$styles = array();
$dir    = IA_ROOT . "/addons/sz_yi/plugin/" . $this->pluginname . "/template/mobile/";
if ($handle = opendir($dir)) {
    while (($file = readdir($handle)) !== false) {
        if ($file != ".." && $file != ".") {
            if (is_dir($dir . "/" . $file)) {
                $styles[] = $file;
            }
        }
    }
    closedir($handle);
}
//Author:Y.yang Date:2016-04-08 Content:成为分销商条件（购买条件）
$goods = false;
if (!empty($set['become_goodsid'])) {
    $goods = pdo_fetch('select id,title from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1 ', array(
        ':id' => $set['become_goodsid'],
        ':uniacid' => $_W['uniacid']
    ));
}
// END
load()->func('tpl');
include $this->template('set');
