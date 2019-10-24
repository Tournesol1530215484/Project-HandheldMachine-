<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;


$op=empty($_GPC['op'])?'display':$_GPC['op'];
if ($_W['isajax']) {
    $openid=$_GPC['openid'];
    $merch_uid=$_W['uid'];

    if ($op == 'message') {
        // $pindex = max(1, intval($_GPC['page']));
        $psize = 5; 
        
        $condition = ' and uniacid = :uniacid and openid=:openid and merch_uid= :merch_uid ';
        $params = array(':uniacid' => $_W['uniacid'], ':openid' => $openid,':merch_uid'=>$merch_uid);
        $sql = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_barter_consult') . " where 1 {$condition}";
        $total = pdo_fetchcolumn($sql, $params);
        
        $pindex =empty($_GPC['page']) ? ceil($total/$psize) : intval($_GPC['page']);  //最大页数
        $list = array(); 
        if (!empty($total)) {
            $sql = 'SELECT id,content,`time`,`type` FROM ' . tablename('sz_yi_barter_consult') . ' where 1 ' . $condition . ' ORDER BY `id` ASC LIMIT ' . ($pindex -1)* $psize . ',' . $psize;
            $list = pdo_fetchall($sql, $params);   
            foreach ($list as $key => $value) {
                $list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['time']);
            } 
        }  
        show_json(1, array('page'=>$pindex-1,'total' => $total, 'list' => $list, 'pagesize' => $psize));

    }else if ($op == 'send'){
        
        $exists=pdo_fetchcolumn('select max(id) from '.tablename('sz_yi_barter_consult').' where merch_uid = :merch_uid and openid = :openid and uniacid = :uniacid',array(':merch_uid'=>$merch_uid,':openid'=>$openid,':uniacid'=>$_W['uniacid']));
        
        $data = array(  
                'pid'=>0,
                'uniacid'=>$_W['uniacid'],
                'openid'=>$openid,
                'merch_uid'=>$merch_uid,
                'content'=>trim($_GPC['content']),
                'type'=>1,
                'time'=>time() 
            ); 

        $exists && $data['pid']=$exists;  
        pdo_insert('sz_yi_barter_consult',$data);
        $id=pdo_insertid();  
        $id?show_json(1,$id):show_json(0,'消息提交失败!'); 
    }
}

$pindex = max(1, intval($_GPC['page']));
$psize = 20; 
$condition = ' and bc.pid = 0  and bc.uniacid = :uniacid ';
$params = array(':uniacid' => $_W['uniacid']); 
if (!empty($_GPC['status'])){ 
    $condition .= ' and bc.status =:status '; 
    $params[':status']=$_GPC['status'];
}
if (p('supplier')){         //如果是供应商
    $condition.=' and bc.merch_uid = :uid ';
    $params[':uid'] = $_W['uid'];
}
 
$sql = 'select bc.id,bc.content,bc.time,bc.openid,bc.merch_uid,m.mobile,m.realname,m.nickname from ' . tablename('sz_yi_barter_consult') . ' bc  left join ' . tablename('sz_yi_member') . ' m on bc.openid = m.openid ' ." where 1 {$condition} ";
$sql .= ' ORDER BY bc.id ASC ';   
if (empty($_GPC['export'])){
    $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
} 
$list = pdo_fetchall($sql, $params);  

foreach ($list as $key => $value) { 
    $list[$key]['content']=pdo_fetchcolumn('select content from '.tablename('sz_yi_barter_consult').' where uniacid  = :uniacid and openid = :openid and merch_uid = :merch_uid and type = 2 order by id desc',array(':uniacid'=>$_W['uniacid'],':openid'=>$value['openid'],':merch_uid'=>$value['merch_uid']));

    $arr=pdo_fetchall('select mobile,qq from '.tablename('sz_yi_perm_user'). ' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$list[$key]['openid']) );
    foreach ($arr as $k => $v) { 
        if (!empty($v['mobile'])) {
            $list[$key]['pmobile']=$v['mobile'];
        }
        if (!empty($v['qq'])) {
           $list[$key]['qq']=$v['qq'];     
        }
    }
}
 
$totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_barter_consult') . ' bc  left join ' . tablename('sz_yi_member') . ' m on bc.openid = m.openid ' ." where 1 {$condition} ", $params);

$pager = pagination($total, $pindex, $psize);   

// if ($_GPC['export'] == 1){
//     plog('statistics.export.order', '导出订单统计');
//     $list[] = array('data' => '订单总计', 'count' => $totalcount);
//     $list[] = array('data' => '金额总计', 'count' => $totalmoney);
//     foreach ($list as & $row){
//         if ($row['paytype'] == 1){
//             $row['paytype'] = '余额支付';
//         }else if ($row['paytype'] == 11){
//             $row['paytype'] = '后台付款';
//         }else if ($row['paytype'] == 21){
//             $row['paytype'] = '微信支付';
//         }else if ($row['paytype'] == 22){
//             $row['paytype'] = '支付宝支付';
//         }else if ($row['paytype'] == 23){
//             $row['paytype'] = '银联支付';
//         }else if ($row['paytype'] == 3){
//             $row['paytype'] = '货到付款';
//         }
//         $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
//     }
//     unset($row);

//     foreach ($list as $k => &$v){
//         $v['createtime']=date('Y-m-d H:i:s',$v['createtime']);
//         $v['paytime']=date('Y-m-d H:i:s',$v['paytime']);
//     }
//     m('excel') -> export($list, array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '买家会员名称', 'field' => 'realname', 'width' => 12), array('title' => '买家电话', 'field' => 'mobile', 'width' => 12), array('title' => '兑换点名称', 'field' => 'title', 'width' => 12), array('title' => '兑换点电话', 'field' => 'exchangeAddrTel', 'width' => 12), array('title' => '下单时间', 'field' => 'cretetime', 'width' => 24), array('title' => '总金额(易货码)', 'field' => 'price', 'width' => 12), array('title' => '交易日期', 'field' => 'paytime', 'width' => 24), array('title' => '付款方式', 'field' => 'paytype', 'width' => 24))));
// }



//最新消息的id
// $tempids=pdo_fetchall('select max(id) as id from ' .tablename('sz_yi_barter_consult'). ' where uniacid = 8 and merch_uid = 581 and type = 2 group by openid LIMIT ' . ($pindex - 1) * $psize . ',' . $psize); 
// foreach ($tempids as $key => $value) {
//     $nowids[]=$value['id'];
// }

// $list=pdo_fetchall('select * from '.tablename('sz_yi_barter_consult').' where id in ('.implode(',',$nowids).' )');
load() -> func('tpl');  

include $this -> template('consult');

exit;
