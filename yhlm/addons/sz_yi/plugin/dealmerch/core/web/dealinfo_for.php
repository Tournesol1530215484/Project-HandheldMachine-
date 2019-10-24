<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and v.uniacid=:uniacid and v.status = 0 ';
$params = array(':uniacid' => $_W['uniacid']);


   
if ($operation == 'check') {
    $id=intval($_GPC['id']);
    $status=intval($_GPC['status']);


    if ($id>0){

        $log=array(
            'status'=>$status,
            'audit_time'=>time()
        );

        $vir_log=pdo_fetch('select * from '.tablename('sz_yi_virtual_log').' where id = :id and uniacid = :uniacid ',array(':id'=>$id,':uniacid'=>$_W['uniacid']));
        if ($status == 1){
            $barterInfo=pdo_fetch('select * from '.tablename('sz_yi_virtual_dealmerch_user').' where id = :id and uniacid = :uniacid ',array(':id'=>$vir_log['virtualid'],':uniacid'=>$_W['uniacid']));
            unset($barterInfo['id']);
            unset($barterInfo['uid']);
            // $data=array(); 
            // foreach ($barterInfo as $k => $v){
            //     if ($k == 'BusinessLicensePic' or $k =='ImageDetailFile'){
            //         $v=unserialize($v);
            //         if (!empty($v)){
            //             $data[$k]=serialize($v);
            //         }
            //     }else{
            //         if (!empty($v)){
            //             $data[$k]=$v;
            //         }
            //     }
            // }    
               
            $sure=pdo_update('sz_yi_dealmerch_user',$barterInfo,array('uniacid'=>$_W['uniacid'],'uid'=>$vir_log['uid']));  
            pdo_update('sz_yi_virtual_log',$log,array('id'=>$id));   
            message('审核成功!',$this->createPluginWebUrl('dealmerch/dealinfo_for'),'success');
            exit; 
        }else if ($status == 2){

            $log['note']=trim($_GPC['note']);
            $sure=pdo_update('sz_yi_virtual_log',$log,array('id'=>$id));
            show_json(1,'驳回审核成功!');
        }

    }

    show_json(0,'非法参数!');
}

//if (!empty($_GPC['title'])) {
//	$_GPC['title'] = trim($_GPC['title']);
//	$condition .= ' and title like :title';
//	$params[':title'] = "%{$_GPC['title']}%";
//}

$field=',vd.contact,vd.mobile,vd.merchname,vd.address,vd.merchsite,vd.worknumber,vd.operat,vd.operatmobile,vd.BusinessLicensePic,vd.ImageDetailFile,vd.licenseoverdue,vd.businessLicenseNo ';
$sql = 'select v.id as vid,v.uid as vuid,v.sub_time '.$field.' from '. tablename('sz_yi_virtual_log') ." v left join ".tablename('sz_yi_virtual_dealmerch_user')." vd on vd.id=v.virtualid where 1 {$condition}";

if (empty($_GPC['export'])) {
	$sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}

$list = pdo_fetchall($sql, $params);  

foreach ($list as $k => &$v){
    $v['BusinessLicensePic']=unserialize($v['BusinessLicensePic']);
    $v['ImageDetailFile']=unserialize($v['ImageDetailFile']);
}

//if ($_GPC['export1'] == '1') {
//	plog('member.member.export', '导出会员数据');
//	m('excel')->export($list, array('title' => '会员数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员ID', 'field' => 'id', 'width' => 12), array('title' => '会员姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号码', 'field' => 'mobile', 'width' => 12), array('title' => '产品名称', 'field' => 'weixin', 'width' => 12), array('title' => '产品名称', 'field' => 'productname', 'width' => 12), array('title' => '用户名', 'field' => 'productname', 'width' => 12), array('title' => '密码', 'field' => 'productname', 'width' => 12))));
//}

$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_virtual_log')." v left join ".tablename('sz_yi_virtual_dealmerch_user')." vd on vd.id=v.virtualid where 1 ".$condition,$params);
$pager = pagination($total, $pindex, $psize);
load()->func('tpl');
include $this->template('dealinfo_for');