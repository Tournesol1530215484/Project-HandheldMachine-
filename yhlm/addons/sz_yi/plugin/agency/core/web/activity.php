<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;

load() -> func('tpl');
$op=empty($_GPC['op'])?'member':$_GPC['op'];
$ac= $_GPC['ac'];

$psize=20;
$pindex=max(1,intval($_GPC['page']));

$exists=check_agent($_W['uid']);
if ($exists) {          //如果存在就是员工
    $staff=$exists;
    $belong_staffid=pdo_fetchcolumn('select id from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
}else{
    $staff=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and uid = :uid',array(':uid'=>$_W['uid'],':uniacid'=>$_W['uniacid']));
}
//获取代理商uid
$agency=p('bonus')->getMerch($staff['merchid']);
$agency=m('member')->getMember($agency['openid']);


if ($op == 'member') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;


    $condition=' and l.uniacid = :uniacid ';    

    if ($agency['bonus_area'] == 1) {                                        
        $condition.=' and m.province = :province ';
        $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
    }else if ($agency['bonus_area'] == 2) {
        $condition.=' and m.province = :province and m.city = :city ';
        $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
    }else if ($agency['bonus_area'] == 3) {
        $condition.=' and m.province = :province and m.city = :city and m.area = :district ';
        $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
    }  

    if ($_GPC['keyword']) {
        $condition .= ' and m.nickname like :keyword ';
        $condition .= ' and m.realname like :keyword ';
        $condition .= ' and m.mobile like :keyword ';
        $params[':keyword'] = "%{$_GPC['keyword']}%";
    }

                
    $sql='select l.*,m.realname,m.nickname from '.tablename('sz_yi_activity_recharge_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';

    $sql.=$condition.'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $sqls = 'SELECT COUNT(*) FROM ' .tablename('sz_yi_activity_recharge_log'). ' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ' . $condition;                          

    $total = pdo_fetchcolumn($sqls, $params);           
    $list  = pdo_fetchall($sql, $params);   
    if ($_GPC['debug']) {
        print_r($list);
        exit;            
    }  
    $pager = pagination($total, $pindex, $psize);
}else if($op == 'reward'){

    if ($_GPC['debug']) {
        /*$activity=pdo_fetchall('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and uid = 0 and openid <> "" ',array(':uniacid'=>$_W['uniacid']));               
        foreach ($activity as $key => $value) {
            if ($value['openid']) {
                $am=m('activity')->getMuser($value['openid']);
                if ($am) {
                    pdo_update('sz_yi_activity',array('uid'=>$am['uid']),array('id'=>$value['id']));
                }  
            }
        }

        $article=pdo_fetchall('select * from '.tablename('sz_yi_activity_article').' where uniacid = :uniacid and uid is null and openid <> "" ',array(':uniacid'=>$_W['uniacid']));                          
        foreach ($article as $key => $value) {      
            if ($value['openid']) {              
                $am=m('activity')->getMuser($value['openid']);
                if ($am) {
                    pdo_update('sz_yi_activity_article',array('uid'=>$am['uid']),array('id'=>$value['id']));
                }  
            }
        }
        exit;*/
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;

    

    $condition=' and m.uniacid = :uniacid ';    

    if ($agency['bonus_area'] == 1) {
        $condition.=' and pu.provance = :province ';
        $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
    }else if ($agency['bonus_area'] == 2) {
        $condition.=' and pu.provance = :province and pu.city = :city ';
        $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
    }else if ($agency['bonus_area'] == 3) {
        $condition.=' and pu.provance = :province and pu.city = :city and pu.area = :district ';
        $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
    } 

    // if ($_GPC['type']) {
    //     $condition .= ' and m.adsn like :adsn ';
    //     $params[':adsn'] = "%{$_GPC['adsn']}%";
    // }

    if ($_GPC['title']) {
        $condition .= ' and m.title like :title ';
        $params[':title'] = "%{$_GPC['title']}%";     
    }
    

    if ($_GPC['type'] == 1) {

        $condition=' and m.uniacid = :uniacid ';    
        if ($agency['bonus_area'] == 1) {
            $condition.=' and pu.province = :province ';
            $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 2) {
            $condition.=' and pu.province = :province and pu.city = :city ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 3) {
            $condition.=' and pu.province = :province and pu.city = :city and pu.area = :district ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
        } 

        $sql='select l.*,m.nickname,m.realname,IFNULL(a.title,"活动不存在") title from '.tablename('sz_yi_activity_reward').' l left join '.tablename('sz_yi_activity').' a on a.id = l.atid left join '.tablename('sz_yi_member_user').' pu on pu.openid = a.openid left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';
        $sql.=$condition;
        $sql.=' order by l.id desc ';
        $sql.=' limit '.($pindex - 1) * $psize.' ,'.$psize;
        $list=pdo_fetchall($sql,$params);
            
        $sqls = 'select count(*) from '.tablename('sz_yi_activity_reward').' l left join '.tablename('sz_yi_activity').' a on a.id = l.atid left join '.tablename('sz_yi_member_user').' pu on pu.openid = a.openid left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';
        $sqls.=$condition;
        $total = pdo_fetchcolumn($sqls, $params);           
        $list  = pdo_fetchall($sql, $params);   
        $pager = pagination($total, $pindex, $psize);

    }else if ($_GPC['type'] == 2) {

        $condition=' and m.uniacid = :uniacid ';    
        if ($agency['bonus_area'] == 1) {
            $condition.=' and pu.province = :province ';
            $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 2) {
            $condition.=' and pu.province = :province and pu.city = :city ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 3) {
            $condition.=' and pu.province = :province and pu.city = :city and pu.area = :district ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
        } 

        $sql='select l.*,m.nickname,m.realname,IFNULL(a.title,"文章不存在") title from '.tablename('sz_yi_activity_reward').' l left join '.tablename('sz_yi_activity_article').' a on a.id = l.atid left join '.tablename('sz_yi_member_user').' pu on pu.openid = a.openid left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';
        $sql.=$condition;
        $sql.=' order by l.id desc ';                           
        $sql.=' limit '.($pindex - 1) * $psize.' ,'.$psize;
        $list=pdo_fetchall($sql,$params);

        $sqls = 'select count(*) from '.tablename('sz_yi_activity_reward').' l left join '.tablename('sz_yi_activity_article').' a on a.id = l.atid left join '.tablename('sz_yi_member_user').' pu on pu.openid = a.openid left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';
        $sqls.=$condition;
        $total = pdo_fetchcolumn($sqls, $params);           
        $list  = pdo_fetchall($sql, $params);   
              
        $pager = pagination($total, $pindex, $psize);

    }else if($_GPC['type'] == 3){

        $condition=' and l.uniacid = :uniacid and l.type = 3 ';    
        if ($agency['bonus_area'] == 1) {
            $condition.=' and pu.province = :province ';
            $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 2) {
            $condition.=' and pu.province = :province and pu.city = :city ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 3) {
            $condition.=' and pu.province = :province and pu.city = :city and pu.area = :district ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
        } 

        $sql='select l.*,pu.nickname,pu.realname,m.nickname nname,m.realname rname from '.tablename('sz_yi_activity_reward').' l left join '.tablename('sz_yi_member').' pu on pu .id = l.atid left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';                             
        $sql.=$condition;                
        $sql.=' order by l.id desc ';                           
        $sql.=' limit '.($pindex - 1) * $psize.' ,'.$psize;
        $list=pdo_fetchall($sql,$params);


        $sqls = 'select count(*) from '.tablename('sz_yi_activity_reward').' l left join '.tablename('sz_yi_member').' pu on pu .id = l.atid left join '.tablename('sz_yi_member').' m on m.openid = l.openid where 1 ';
        $sqls.=$condition;
        $total = pdo_fetchcolumn($sqls, $params);           
        $list  = pdo_fetchall($sql, $params);   
        $pager = pagination($total, $pindex, $psize);
    }
}else if($op == 'settop'){

	

    if ($_GPC['type'] == 1) {

        $condition=' and l.uniacid = :uniacid and l.type = 1 '; 
           	 		 		 	
        if ($agency['bonus_area'] == 1) {
            $condition.=' and pu.province = :province ';
            $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 2) {
            $condition.=' and pu.province = :province and pu.city = :city ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 3) {
            $condition.=' and pu.province = :province and pu.city = :city and pu.area = :district ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
        } 

        $sql='select l.*,pu.nickname,pu.realname,IFNULL(a.title,"活动不存在") title from '.tablename('sz_yi_activity_settop_log').' l left join '.tablename('sz_yi_activity').' a on a.id = l.actid left join '.tablename('sz_yi_member').' pu on pu.openid = l.openid where 1 ';
        $sql.=$condition;	 	 	 		 	 
        $sql.=' order by l.id desc ';
        $sql.=' limit '.($pindex - 1) * $psize.' ,'.$psize; 		 	
        $list=pdo_fetchall($sql,$params);

        $sqls = 'select count(*) from '.tablename('sz_yi_activity_settop_log').' l left join '.tablename('sz_yi_activity').' a on a.id = l.actid left join '.tablename('sz_yi_member').' pu on pu.openid = l.openid where 1 ';
        $sqls.=$condition;	 	 	
        $total = pdo_fetchcolumn($sqls, $params);           
        $list  = pdo_fetchall($sql, $params);   
        $pager = pagination($total, $pindex, $psize);

    }else if ($_GPC['type'] == 2) {

    	$condition=' and l.uniacid = :uniacid and l.type = 2 '; 
           	 		 		 	
        if ($agency['bonus_area'] == 1) {	 	 
            $condition.=' and pu.province = :province ';
            $params=array(':province'=>$agency['bonus_province'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 2) {
            $condition.=' and pu.province = :province and pu.city = :city ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':uniacid'=>$_W['uniacid']);
        }else if ($agency['bonus_area'] == 3) {
            $condition.=' and pu.province = :province and pu.city = :city and pu.area = :district ';
            $params=array(':province'=>$agency['bonus_province'],':city'=>$agency['city'],':district'=>$agency['bonus_district'],':uniacid'=>$_W['uniacid']);
        } 

        $sql='select l.*,pu.nickname,pu.realname,IFNULL(a.title,"活动不存在") title from '.tablename('sz_yi_activity_settop_log').' l left join '.tablename('sz_yi_activity_article').' a on a.id = l.actid left join '.tablename('sz_yi_member').' pu on pu.openid = l.openid where 1 ';
        $sql.=$condition;	 	 	 		 	 
        $sql.=' order by l.id desc ';
        $sql.=' limit '.($pindex - 1) * $psize.' ,'.$psize; 		 	
        $list=pdo_fetchall($sql,$params);

        $sqls = 'select count(*) from '.tablename('sz_yi_activity_settop_log').' l left join '.tablename('sz_yi_activity').' a on a.id = l.actid left join '.tablename('sz_yi_member').' pu on pu.openid = l.openid where 1 ';
        $sqls.=$condition;	 	 	
        $total = pdo_fetchcolumn($sqls, $params);           
        $list  = pdo_fetchall($sql, $params);   
        $pager = pagination($total, $pindex, $psize);	 	 		 	 	 		 	 	

    }

}else if ($op == 'getlist'){

    $pindex=max(1,$_GPC['page']);
    $psize=20;
    $type=$_GPC['type'];
    $lid=$_GPC['lid'];
    if ($type == 'member') {
        
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':lid'=>$lid,
        );
        $list=pdo_fetchall('select l.*,m.nickname,m.realname from '.tablename('sz_yi_activity_bonus_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.uniacid = :uniacid and l.logid = :lid and l.cate = 1',$params);

        if ($list) {
            foreach ($list as $key => $value) {             
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            }                
            show_json(1,$list);
        }
        show_json(0,'无这条记录');
    }else if($type == 'reward'){
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':lid'=>$lid,
        );

        $list=pdo_fetchall('select l.*,m.nickname,m.realname from '.tablename('sz_yi_activity_bonus_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.uniacid = :uniacid and l.logid = :lid and l.cate = 2',$params);

        if ($list) {            
            foreach ($list as $key => $value) {             
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            }                
            show_json(1,$list);
        }
        show_json(0,'无这条记录');
    }else if($type == 'settop'){
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':lid'=>$lid,
        );

        $list=pdo_fetchall('select l.*,m.nickname,m.realname from '.tablename('sz_yi_settop_bonus_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.uniacid = :uniacid and l.logid = :lid ',$params);

        if ($list) {            
            foreach ($list as $key => $value) {             
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            }                
            show_json(1,$list);
        }
        show_json(0,'无这条记录');
    }        

}


// $dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
// $pindex = max(1, intval($_GPC['page']));
// $psize = 20;
// $condition = ' and o.uniacid=:uniacid and isexchange = 1 and addressid = 0 ';
// $params = array(':uniacid' => $_W['uniacid']);
// if (!empty($_GPC['status'])){
//     $condition .= ' and o.status =:status ';
//     $params[':status']=$_GPC['status'];
// }
// if (p('supplier')){         //如果是供应商
//     $condition.=' and o.supplier_uid = :uid ';
//     $params[':uid'] = $_W['uid'];
// }

// $sql = 'select o.id,o.isverify,o.verified,o.status,o.ordersn,o.price,o.paytime,o.goodsprice, o.dispatchprice,o.createtime, o.paytype, a.title , a.mobile as exchangeAddrTel,m.mobile, m.realname from ' . tablename('sz_yi_order') . ' o  left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id left join ' . tablename('sz_yi_member') . ' m on o.openid = m.openid left join ' . tablename('sz_yi_exchange_address') . " a on a.id = o.storeid where 1 {$condition} ";
// $sql .= ' ORDER BY o.id DESC ';
// if (empty($_GPC['export'])){
//     $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
// }
// $list = pdo_fetchall($sql, $params);
// foreach ($list as & $row){
//     $row['ordersn'] = $row['ordersn'] . ' ';
//     $row['goods'] = pdo_fetchall('SELECT g.goodssn,g.title as goodsname ,g.thumb,og.price,og.total,og.realprice,g.title,og.optionname from ' . tablename('sz_yi_order_goods') . ' og' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid  ' . ' where og.uniacid = :uniacid and og.orderid=:orderid order by og.createtime  desc ', array(':uniacid' => $_W['uniacid'], ':orderid' => $row['id']));
//     $totalmoney += $row['price'];
// }
// if (empty($totalmoney)){
//     $totalmoney = 0;
// }
// unset($row);
// $totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' o ' . ' left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id left join ' . tablename('sz_yi_member') . ' m on o.openid = m.openid ' . ' left join ' . tablename('sz_yi_exchange_address') . ' a on a.id = o.storeid ' . " where 1 {$condition}", $params);
// $pager = pagination($total, $pindex, $psize);
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

include $this -> template('activity');
exit;
