﻿<?php
 global $_W;
$sql = '
CREATE TABLE IF NOT EXISTS `ims_sz_yi_af_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) CHARACTER SET utf8 NOT NULL,
  `uniacid` int(11) NOT NULL,
  `realname` varchar(55) CHARACTER SET utf8 NOT NULL,
  `mobile` varchar(255) CHARACTER SET utf8 NOT NULL,
  `weixin` varchar(255) CHARACTER SET utf8 NOT NULL,
  `productname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(3) NOT NULL COMMENT \'1审核成功2驳回\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `ims_sz_yi_merchant_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT \'商家id\',
  `uniacid` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT \'1手动2微信\',
  `applysn` varchar(255) NOT NULL COMMENT \'提现单号\',
  `apply_money` int(11) NOT NULL COMMENT \'申请金额\',
  `apply_time` int(11) NOT NULL COMMENT \'申请时间\',
  `status` tinyint(3) NOT NULL COMMENT \'0为申请状态1为完成状态\',
  `finish_time` int(11) NOT NULL COMMENT \'完成时间\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;';
pdo_query($sql);
if(!pdo_fieldexists('sz_yi_perm_user', 'banknumber')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_user') . ' ADD `banknumber` varchar(255) NOT NULL COMMENT \'银行卡号\';');
}
if(!pdo_fieldexists('sz_yi_perm_user', 'accountname')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_user') . ' ADD `accountname` varchar(255) NOT NULL COMMENT \'开户名\';');
}
if(!pdo_fieldexists('sz_yi_perm_user', 'accountbank')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_user') . ' ADD `accountbank` varchar(255) NOT NULL COMMENT \'开户行\';');
}
if(!pdo_fieldexists('sz_yi_goods', 'merchant_uid')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_goods') . ' ADD `merchant_uid` INT NOT NULL COMMENT \'商家ID\';');
}
if(!pdo_fieldexists('sz_yi_order', 'merchant_uid')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_order') . ' ADD `merchant_uid` INT NOT NULL COMMENT \'商家ID\';');
}
if(!pdo_fieldexists('sz_yi_order_goods', 'merchant_uid')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_order_goods') . ' ADD `merchant_uid` INT NOT NULL COMMENT \'商家ID\';');
}
if(!pdo_fieldexists('sz_yi_order_goods', 'merchant_apply_status')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_order_goods') . ' ADD `merchant_apply_status` tinyint(4) NOT NULL COMMENT \'1为商家已提现\';');
}
if(!pdo_fieldexists('sz_yi_af_merchant', 'id')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_af_merchant') . ' ADD PRIMARY KEY (`id`);');
}
if(!pdo_fieldexists('sz_yi_merchant_apply', 'id')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_merchant_apply') . ' ADD PRIMARY KEY (`id`);');
}
if(!pdo_fieldexists('sz_yi_af_merchant', 'id')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_af_merchant') . ' MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;');
}
if(!pdo_fieldexists('sz_yi_merchant_apply', 'id')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_merchant_apply') . ' MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}
if(!pdo_fieldexists('sz_yi_perm_role', 'status1')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_role') . ' ADD `status1` tinyint(3) NOT NULL COMMENT \'1：商家开启\';');
}
if(!pdo_fieldexists('sz_yi_perm_user', 'openid')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_user') . ' ADD `openid` VARCHAR( 255 ) NOT NULL;');
}
if(!pdo_fieldexists('sz_yi_perm_user', 'username')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_user') . ' ADD `username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
}
if(!pdo_fieldexists('sz_yi_perm_user', 'password')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_perm_user') . ' ADD `username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
}
$info = pdo_fetch('select * from ' . tablename('sz_yi_plugin') . ' where identity= "merchant"  order by id desc limit 1');
if(!$info){
    $sql = 'INSERT INTO ' . tablename('sz_yi_plugin') . ' (`displayorder`, `identity`, `name`, `version`, `author`, `status`, `category`) VALUES(0, \'merchant\', \'商家\', \'1.0\', \'官方\', 1, \'biz\');';
    pdo_query($sql);
}
if(!pdo_fieldexists('sz_yi_af_merchant', 'status')){
    pdo_query('ALTER TABLE ' . tablename('sz_yi_af_merchant') . ' ADD `status` TINYINT( 3 ) NOT NULL COMMENT \'0申请1驳回2通过\' AFTER `productname`;');
}
$result = pdo_fetch('select * from ' . tablename('sz_yi_perm_role') . ' where status1=1');
if(empty($result)){
    $sql = '
INSERT INTO ' . tablename('sz_yi_perm_role') . ' (`rolename`, `status`, `status1`, `perms`, `deleted`,`uniacid`) VALUES
(\'商家\', 1, 1, \'shop,shop.goods,shop.goods.view,shop.goods.add,shop.goods.edit,shop.goods.delete,order,order.view,order.view.status_1,order.view.status0,order.view.status1,order.view.status2,order.view.status3,order.view.status4,order.view.status5,order.view.status9,order.op,order.op.pay,order.op.send,order.op.sendcancel,order.op.finish,order.op.verify,order.op.fetch,order.op.close,order.op.refund,order.op.export,order.op.changeprice,exhelper,exhelper.print,exhelper.print.single,exhelper.print.more,exhelper.exptemp1,exhelper.exptemp1.view,exhelper.exptemp1.add,exhelper.exptemp1.edit,exhelper.exptemp1.delete,exhelper.exptemp1.setdefault,exhelper.exptemp2,exhelper.exptemp2.view,exhelper.exptemp2.add,exhelper.exptemp2.edit,exhelper.exptemp2.delete,exhelper.exptemp2.setdefault,exhelper.senduser,exhelper.senduser.view,exhelper.senduser.add,exhelper.senduser.edit,exhelper.senduser.delete,exhelper.senduser.setdefault,exhelper.short,exhelper.short.view,exhelper.short.save,exhelper.printset,exhelper.printset.view,exhelper.printset.save,exhelper.dosen,taobao,taobao.fetch\', 0,'.$_W['uniacid'].');';
    pdo_query($sql);
}else{
    $gysdata = array('perms' => 'shop,shop.goods,shop.goods.view,shop.goods.add,shop.goods.edit,shop.goods.delete,order,order.view,order.view.status_1,order.view.status0,order.view.status1,order.view.status2,order.view.status3,order.view.status4,order.view.status5,order.view.status9,order.op,order.op.pay,order.op.send,order.op.sendcancel,order.op.finish,order.op.verify,order.op.fetch,order.op.close,order.op.refund,order.op.export,order.op.changeprice,exhelper,exhelper.print,exhelper.print.single,exhelper.print.more,exhelper.exptemp1,exhelper.exptemp1.view,exhelper.exptemp1.add,exhelper.exptemp1.edit,exhelper.exptemp1.delete,exhelper.exptemp1.setdefault,exhelper.exptemp2,exhelper.exptemp2.view,exhelper.exptemp2.add,exhelper.exptemp2.edit,exhelper.exptemp2.delete,exhelper.exptemp2.setdefault,exhelper.senduser,exhelper.senduser.view,exhelper.senduser.add,exhelper.senduser.edit,exhelper.senduser.delete,exhelper.senduser.setdefault,exhelper.short,exhelper.short.view,exhelper.short.save,exhelper.printset,exhelper.printset.view,exhelper.printset.save,exhelper.dosen,taobao,taobao.fetch');
    pdo_update('sz_yi_perm_role', $gysdata, array('rolename' => '商家','uniacid'=>$_W['uniacid']));
}
message('商家插件安装成功', $this -> createPluginWebUrl('merchant/merchant'), 'success');
