<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and uniacid = :uniacid';
    
    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_ad_model').' where 1 '.$condition;
    $sql.=' order by id desc ';
    // $sql.=' limit '.($pindex -1 )* $psize.','.$psize;
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} ", $params);
    
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $list = pdo_fetchall($sql, $params);
    foreach ($list as $key => $value) {          
        if ($value['stime'] > time()) {
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    }
    $pager = pagination($totals, $pindex, $psize);
}else if ($op == 'show'){
    $id=intval($_GPC['id']);

    $ad=pdo_fetch('select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));               

    $ad['thumb']=unserialize($ad['thumb']);
    $ad['stime']=date('Y-m-d',$ad['stime']);

    if ($ad['stime'] > time()) {
       $time=$ad['etime'] - $ad['stime'];
    }else{
        $time=$ad['etime'] - time();
    }
    $ad['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    $ad['desc']=html_entity_decode($ad['desc']);               

    foreach ($ad['thumb'] as $key => $value) {
        $ad['thumb'][$key]=tomedia($value);
    }           
    if ($ad['putInType'] == 1) {       
        $ad['putInType']='现金红包广告';
    }else if ($ad['putInType'] == 2){
        $ad['putInType']='易货码红包广告';
    }
    $product=null;
    if ($ad['goodsid']) {
        $product=pdo_fetchall('select g.id,g.title,c.name ccate from '.tablename('sz_yi_goods').' g left join '.tablename('sz_yi_category').' c on c.id=g.ccate where g.uniacid = :uniacid and g.id in ('.$ad['goodsid'].')',array(':uniacid'=>$_W['uniacid']));

        foreach ($product as $key => $value) {
            $price=pdo_fetchcolumn('select max(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid = :uniacid and goodsid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));
            $product[$key]['marketprice']=$price;                
        }
    }

    $ad['age']=m('tools')->getsure(intval($ad['minage']),intval($ad['maxage']),'年龄');
    $ad['earning']=m('tools')->getsure(intval($ad['minimum']),intval($ad['maximum']),'收入');
    if (empty($ad['gender'])) {
        $ad['gender'] = '无限制';
    }else{
        $ad['gender']= $ad['gender'] == 1 ? '男' : '女' ;
    }

    if ($ad['national']) {
        $ad['area']='全国';
    }else{
        $ad['area']=$ad['province'].$ad['city'].$ad['area'];
    }

    $log=pdo_fetchall('select * from '.tablename('sz_yi_ad_for_log').' where uniacid = :uniacid and ad_id= :id order by id desc',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
    foreach ($log as $key => $value) {
        $log[$key]['audit_time']=date('Y-m-d H:i:s',$log[$key]['audit_time']);
        $log[$key]['sub_time']=date('Y-m-d H:i:s',$log[$key]['sub_time']);
    }
    show_json(1,array('ad'=>$ad,'product'=>$product,'log'=>$log));
}
include $this -> template('ad');