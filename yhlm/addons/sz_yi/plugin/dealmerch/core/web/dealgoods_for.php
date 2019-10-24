<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and g.uniacid=:uniacid and g.type = 8 and g.ischeck = 0 and l.status = 0 ';
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
            $sure?message('审核成功!',$this->createPluginWebUrl('dealmerch/dealgoods_for'),'success'):message('审核失败!',$this->createPluginWebUrl('dealmerch/dealgoods_for'),'error');   
            exit;
        }else if ($ischeck == 2){  
        	$data['status']=0;  
            $sure=pdo_update('sz_yi_goods',$data,array('id'=>$logid)); 
            pdo_update('sz_yi_goods_log',$log,array('id'=>$id));
            $sure?message('驳回成功!',$this->createPluginWebUrl('dealmerch/dealgoods_for'),'success'):message('驳回失败!',$this->createPluginWebUrl('dealmerch/dealgoods_for'),'error');
            exit;
        }
              
    }
     message('操作失败!',$this->createPluginWebUrl('dealmerch/dealgoods_for'),'error'); 
        exit; 
}
 
if (!empty($_GPC['title'])) {
	$_GPC['title'] = trim($_GPC['title']);
	$condition .= ' and g.title like :title';
	$params[':title'] = "%{$_GPC['title']}%";
} 

//$sql = 'select l.id as lid,g.* from ' . tablename('sz_yi_goods_log') . " l left join ".tablename('sz_yi_goods')." g on l.goodsid=g.id where 1 {$condition}";

//$sql = 'select l.id as lid,g.* ,m.* from ' .(tablename('sz_yi_goods_log') . " l left join ".tablename('sz_yi_goods')." g  on l.goodsid=g.id ) left join ".tablename('hs_sz_yi_member')." m on l.uid=m.id   where 1 {$condition}";

//$sql="select l.id as lid,g.*,m.mobile as mopenid from (hs_sz_yi_goods_log l left join hs_sz_yi_goods  g on l.goodsid=g.id )  left join hs_sz_yi_member_user  m on l.uid=m.id where 1 {$condition}";

$sql="select l.id as lid,g.*, pu.username as pusername from ((hs_sz_yi_goods_log l left join hs_sz_yi_goods  g on l.goodsid=g.id )  left join hs_sz_yi_member_user  m on l.uid=m.id) left join hs_sz_yi_perm_user pu on m.openid = pu.openid where 1 {$condition}";

//$sql="select l.id as lid,g.*, f.realname as frealname from (((hs_sz_yi_goods_log l left join hs_sz_yi_goods  g on l.goodsid=g.id )  left join hs_sz_yi_member_user  m on l.uid=m.id) left join hs_sz_yi_perm_user pu on m.openid = pu.openid) left join hs_sz_yi_af_supplier f on m.openid =f.openid where 1 {$condition}";


if (empty($_GPC['export'])) {
	$sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
}

$list = pdo_fetchall($sql, $params);


// foreach ($list as $key => $value) {
//         $list[$key]['selmerch']=p('bonus')->getmerch($value['supplier_uid']);
//         // //获取商家手机号 
//         // $realname =getUpname($list[$key]['selmerch']['openid']);
        
//         // $list[$key]['realname']=$realname;                                                                 
// }


//获取商家信息
foreach ($list as $key => $value) {
        $list[$key]['selmerch']=p('bonus')->getmerch($value['supplier_uid']);
        //获取商家手机号 
        $realname =getUpname($list[$key]['selmerch']['openid']);
        $list[$key]['realname']=$realname;
        //var_dump($list[$key]['realname']);                                                                 
}

//获取商家的上一级商家

function getUpname($openid){
    //$items=pdo_fetch("select s.*  from hs_sz_yi_perm_user s where s.openid = '$openid'"); 
     $tmem=m('member')->getMember($openid);
    $items=m('member')->getMember($tmem['agentid']);
    return $items['nickname'];

}
// foreach ($list as $key => $value) {
//          //foreach ($list[$key]['selmerch'] as $k1 => $v1) {
//             // $item = pdo_fetch("select s.*  from hs_sz_yi_af_supplier s where s.openid = $value['selmerch']['openid']");   
//             //  $list[$key]['selmerch']['realname']=$item[0]['realname'];

//            // var_dump($list[$key]['selmerch']['openid']);
//        //}

//     //var_dump($value['selmerch']['openid']);
// }








foreach ($list as $k => &$v){
    $v['thumb_url']=unserialize($v['thumb_url']);


}

$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_goods_log').' l left join '.tablename('sz_yi_goods').' g on g.id=l.goodsid where 1 '.$condition,$params);
$pager = pagination($total, $pindex, $psize);
load()->func('tpl'); 
include $this->template('dealgoods_for');