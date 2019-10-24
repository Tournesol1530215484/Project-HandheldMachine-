<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and g.uniacid=:uniacid and g.type != 8 and g.ischeck = 0 and l.status = 0 ';
$params = array(':uniacid' => $_W['uniacid']); 

if ($operation == 'check') { 
    $id=intval($_GPC['logid']); 
    $ischeck=intval($_GPC['ischeck']);          
    if ($id>0){  
        $log=[
            'status'=>$ischeck,
            'note'  => trim($_GPC['note']),             
            'audit_time'=>time(),
        ];
        $data=array(
            'isCheck'=>$ischeck,            
            'status'=>1 
        );
        
        $logid=pdo_fetchcolumn('select goodsid from '.tablename('sz_yi_goods_log').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        if ($ischeck == 1) { 
            $data['status'] = 1; 
            $sure=pdo_update('sz_yi_goods',$data,array('id'=>$logid)); 
            pdo_update('sz_yi_goods_log',$log,array('id'=>$id)); 
            $sure?message('审核成功!',$this->createWebUrl('shop/goods_for'),'success'):message('审核失败!',$this->createWebUrl('shop/goods_for'),'error');   
            exit;
        }else if ($ischeck == 2){  
        	$data['status']=0;  
            $sure=pdo_update('sz_yi_goods',$data,array('id'=>$logid)); 
            pdo_update('sz_yi_goods_log',$log,array('id'=>$id));
            $sure?message('驳回成功!',$this->createWebUrl('shop/goods_for'),'success'):message('驳回失败!',$this->createWebUrl('shop/goods_for'),'error');
            exit;
        }
              
    }
     message('操作失败!',$this->createWebUrl('shop/goods_for'),'error'); 
        exit; 
}
 
if (!empty($_GPC['title'])) {
	$_GPC['title'] = trim($_GPC['title']);
	$condition .= ' and g.title like :title';
	$params[':title'] = "%{$_GPC['title']}%";
} 
$sql = 'select l.id as lid,g.* from ' . tablename('sz_yi_goods_log') . " l left join ".tablename('sz_yi_goods')." g on l.goodsid=g.id where 1 {$condition}";

if (empty($_GPC['export'])) {
	$sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}

$list = pdo_fetchall($sql, $params);

foreach ($list as $k => &$v){
    $v['thumb_url']=unserialize($v['thumb_url']);
}

$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_goods_log').' l left join '.tablename('sz_yi_goods').' g on g.id=l.goodsid where 1 '.$condition,$params);
$pager = pagination($total, $pindex, $psize);
load()->func('tpl'); 
include $this->template('web/shop/goods_for');