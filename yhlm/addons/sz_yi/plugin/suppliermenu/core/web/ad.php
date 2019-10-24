<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;

load() -> func('tpl');
$op=empty($_GPC['op'])?'list':$_GPC['op'];
$ac=$_GPC['ac'];
//print_r($op);exit;
$rule=array(         
    '自己',
    '1级',
    '2级',
    '区代',
    '市代',
    '省代',
    '经纪人',
    '平台'             
);


if ($_GPC['debug']) {
    $sql='select * from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid';
    $list=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid']));
    $num=0;
    foreach ($list as $key => $value) {
        if (empty($value['date'])) {
            pdo_update('sz_yi_obtain_bonus',array('date'=>date('Ymd',$value['ctime'])),array('id'=>$value['id'],'uniacid'=>$_W['uniacid']));
            $num+=1;
        }                
    }                
    die('一共修改了'.$num.'条数据!');        
}

if ($op == 'show') {
    $id=intval($_GPC['id']);                 
    $uid=intval($_GPC['uid']);               

    $ad=pdo_fetch('select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and uid= :uid and id = :id',array(':uid'=>$uid,':uniacid'=>$_W['uniacid'],':id'=>$id));               

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
        $ad['putInType']='换货码红包广告';
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
        $ad['gender'] = '0';
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
if ($op  == 'post') {

    $id = intval($_GPC['id']);

    if ($id) {
        $su_info = pdo_fetch('select * from ' . tablename('sz_yi_ad_model') . ' where uniacid = :uniacid and id = :id ', array(':uniacid' => $_W['uniacid'], ':id' => $id));
//        $su_info['thumb']=unserialize($su_info['thumb']);
//        foreach($su_info['thumb'] as $k=>$v){
//            if($k==0){
//                $img=$su_info['thumb'][$k];
//                unset($su_info['thumb'][$k]);
//            }
//        }
//        print_r($su_info['thumb']);exit;
        if (!$su_info || $su_info['uid'] != $_W['uid']) {
            message('没有这个广告!', '', 'error');
        }
        if ($su_info) {
            $su_info['thumb'] = unserialize($su_info['thumb']);
            if(!empty($su_info['goodsid'])){
                $stores = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and  id in (' . trim($su_info['goodsid'], ',') . ')', array(':uniacid' => $_W['uniacid']));
            }
        }
    }

    $merch = p('bonus')->getMerch($_W['uid']);
    $member = m('member')->getMember($merch['openid']);
    $cate = pdo_fetchall('select * from ' . tablename('sz_yi_ad_type') . ' where uniacid = :uniacid and status = 1', array(':uniacid' => $_W['uniacid']));
    foreach ($cate as $key => $value) {
        if ($value['title'] == '推荐' || $value['title'] == '附近' || $value['title'] == '最新') {
            unset($cate[$key]);
        }
    }

    $merchall = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . ' where uniacid = :uniacid and openid = :openid and status = 1 and muserid = 0 ', array(':uniacid' => $_W['uniacid'], ':openid' => $merch['openid']));
    if ($ac == 'submit') {

        empty($_GPC['title']) && message('广告标题不能为空!', referer(), 'error');
        empty($_GPC['core']) && message('核心关键词不能为空!', referer(), 'error');
        empty($_GPC['thumb']) && message('轮播图不能为空!', referer(), 'error');
        empty($_GPC['mobile']) && message('联系电话不能为空!', referer(), 'error');
        empty($_GPC['desc']) && message('描述不能为空!', referer(), 'error');
        $_GPC['thumb']['1'] = $_GPC['thumbs']['0'];
        $_GPC['thumb']['2'] = $_GPC['thumbs']['1'];
        $_GPC['thumb']['3'] = $_GPC['thumbs']['2'];

        $data = array(
            'recuid' => $_GPC['uid'],
            'uid' => $_W['uid'],
            'uniacid' => $_W['uniacid'],
            'title' => $_GPC['title'],
            'core' => $_GPC['core'],
            'thumb' => serialize($_GPC['thumb']),
            'mobile' => $_GPC['mobile'],
            'desc' => $_GPC['desc'],
            'video' => trim($_GPC['video']),
            'link' => trim($_GPC['link']),
            'type' => $member['default_ad_model'],
            'ctime' => time(),
            'putInType' => $_GPC['putInType'],       //投放设置
            'goodsid' => trim($_GPC['goodsids'], ','),
            'money' => floatval(floatval($_GPC['bonus']) * 0.3),
            'bonus' => floatval($_GPC['bonus']),
            'usermax' => floatval($_GPC['usermax']),
            'daymax' => floatval($_GPC['daymax']),
            'residual' => floatval($_GPC['bonus']),
            'gender' => intval($_GPC['gender']),
            'minimum' => floatval($_GPC['minimum']),
            'maximum' => floatval($_GPC['maximum']),
            'minage' => intval($_GPC['minage']),
            'maxage' => intval($_GPC['maxage']),
            'stime' => strtotime($_GPC['stime']),
            'etime' => strtotime($_GPC['etime']),
        );

        // var_dump($data);
        // exit;
        // if ($member['default_ad_model'] == 2) {
        //     if (strexists(trim($_GPC['video']),'.swf')) {
        //         $data['video']=trim($_GPC['video']);
        //     }else{
        //         message('视频链接不正确','','');
        //     }
        // }else if ($member['default_ad_model'] == 3){
        //     $data['outside']=trim($_GPC['outside']);

        // }

        if (isset($_GPC['setarea'])) {
            if (isset($_GPC['national'])) {
                $data['national'] = $_GPC['national'];
                $data['province'] = '';
                $data['city'] = '';
                $data['area'] = '';
            } else {
                $data['province'] = $_GPC['address']['province'];
                $data['city'] = $_GPC['address']['city'];
                $data['area'] = $_GPC['address']['district'];
                $data['national'] = 0;
            }
        } else {
            $data['national'] = '1';
            $data['province'] = '';
            $data['city'] = '';
            $data['area'] = '';
        }

        if (isset($_GPC['status'])) {
            $data['status'] = $_GPC['status'];  // 3 draft
        }

        $merch = p('bonus')->getMerch($_W['uid']);
        $str = '';
        if ($data['putInType'] == '1') {
            $money = m('member')->getCredit($merch['openid'], 'credit2');
            $str = 'credit2';
        } else if ($data['putInType'] == '2') {
            $money = m('member')->getCredit($merch['openid'], 'credit3');
            $str = 'credit3';
        } else {
            message('添加失败', $this->crteatePluginWebUrl('suppliermenu/ad'), 'error');
        }
        $fee = floatval($data['money']);
        if ($fee > $money) {
            message('添加失败，你的余额不足发布广告', $this->crteatePluginWebUrl('suppliermenu/ad'), 'error');
        }
        // $money=m('member')->setCredit($merch['openid'],$str,-$fee);       扣除

        if ($id) {
            pdo_update('sz_yi_ad_model', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
        } else {
            pdo_insert('sz_yi_ad_model', $data);
            $id = pdo_insertid();
        }

        $log = [
            'uniacid' => $_W['uniacid'],
            'ad_id' => $id,
            'sub_time' => time()
        ];


        if ($id) {
            $adsn = '31';         //获取id 设置编号
            for ($i = 0; $i < 7 - strlen($id); $i++) {
                $adsn .= '0';
            }
            $adsn .= $id;
            pdo_update('sz_yi_ad_model', array('adsn' => $adsn), array('id' => $id));
            m('log')->putAdLog($log);  //记录日志
            message('添加成功!', $this->crteatePluginWebUrl('suppliermenu/ad'), 'success');
        } else {
            message('添加失败', $this->crteatePluginWebUrl('suppliermenu/ad'), 'error');
        }
    }
    foreach($su_info['thumb'] as $k=>$v){
        if($k==0){
            $img=$su_info['thumb'][$k];
            unset($su_info['thumb'][$k]);
        }
    }
    $su_info['thumbs']=$su_info['thumb'];

    include $this->template('addAd');
    exit;
}else if($op=='delete'){
    $su_info = pdo_fetch('select * from ' . tablename('sz_yi_ad_model') . ' where uniacid = :uniacid and id = :id ', array(':uniacid' => $_W['uniacid'], ':id' => $_GPC['id']));
    if (!$su_info || $su_info['uid'] != $_W['uid']) {
        message('没有这个广告!', '', 'error');
    }
    pdo_delete('sz_yi_ad_model',array('id'=>$_GPC['id']));
    message('删除成功!', $this->crteatePluginWebUrl('suppliermenu/ad'), 'success');
}else if($op=='draft'){

    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':uid'=>$_W['uid']);
    $condition=' and uniacid = :uniacid and uid = :uid';
    if ($_GPC['adsn']) {
       $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }

    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} and status = 3 ", $params);

    $sql='select * from '.tablename('sz_yi_ad_model').' where 1 '.$condition.' and status = 3 ';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, $params);
    $pager = pagination($totals, $pindex, $psize);
}else if ($op=='list'){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':uid'=>$_W['uid']);
    $condition=' and uniacid = :uniacid and uid = :uid ';

    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }

    switch ($_GPC['status']) {

        case '0':
            $conf.=' and status = 1 ';
            break;

        case '1':
            $conf.=' and status = 0 ';
            break;

        case '2':
            $conf.=' and status = 2 ';
            break;

        case '3':
            $conf.=' and status = 4 ';
            break;
        default:
            break;
    }

    $sql='select * from '.tablename('sz_yi_ad_model').' where 1 '.$condition.' and status <> 3 '.$conf;
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} and status <> 3 ", $params);

    $total['status1'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} and status = 1 ", $params);
   
    $total['status2'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} and status = 0 ", $params);
 
    $total['status3'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} and status = 2 ", $params);

    $total['status4'] = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_model'). " where 1 {$condition} and status = -1 ", $params);

    $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, $params);
    $pager = pagination($totals, $pindex, $psize);

}else if ($op=='code' || $op=='bonus' ){
          
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':uid'=>$_W['uid']);
    $condition=' and uniacid = :uniacid and uid = :uid ';

    if ($op == 'bonus') {
        $condition.=' and putInType = 1 '; //现金红包
    }else{
        $condition.=' and putInType = 2 '; //换货码红包
    }

    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }

    $sql='select * from '.tablename('sz_yi_ad_model').' where 1 '.$condition;

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
}else if($op == 'more'){
    $id=$_GPC['id'];

    if ($_W['isajax'] && $ac == 'getlist') {
        
        $list=pdo_fetchall('select b.*,m.realname,m.nickname from '.tablename('sz_yi_ad_bonus_log').' b left join '.tablename('sz_yi_member').' m on m.openid = b.openid where b.uniacid = :uniacid and b.obid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        
        if ($list) {                               
            foreach ($list as $key => $value) {     
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
                $list[$key]['rule']=$rule[$value['level']];
                $list[$key]['bonustype']=$value['bonusType'] ==1?'现金红包':'换货码红包';
            }            
            show_json(1,$list);  
         }       
        show_json(0,array());
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':id'=>$id);
    $condition=' and ob.uniacid = :uniacid and ob.adid = :id ';        

    $ad=m('tools')->getAd($id);      

    if ($_GPC['keyword']) {             
        $condition .= ' and realname like :keyword or m.mobile like :keyword or m.nickname like :keyword ';
        $params[':keyword'] = "%{$_GPC['keyword']}%";           
    }       

    $sql='select ob.*,m.realname,m.nickname from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_member').' m on m.openid = ob.openid where 1 '.$condition;                       
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;                                                       

    
    $totals = pdo_fetchcolumn("select count(*) from " .tablename('sz_yi_obtain_bonus')." ob left join ".tablename('sz_yi_member')." m on m.openid = ob.openid where 1  {$condition} ", $params);

    $list = pdo_fetchall($sql, $params);
             
    $pager = pagination($totals, $pindex, $psize); 

}else if($op == 'palyDetail'){
    $id=intval($_GPC['id']);

    if ($_W['isajax']) {
        if ($ac == 'getlist') {
            $pindex=max(1,intval($_GPC['page']));        
            $psize=20;       
            $params = array(':uniacid' => $_W['uniacid'],':id'=>$id,':date'=>$_GPC['date']);       
            $condition=' and ob.uniacid = :uniacid and ob.adid = :id and ob.date = :date';
            $sql='select ob.*,m.nickname,m.realname from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_member').' m on m.openid = ob.openid where 1 ';
            $sql.=$condition;
            $sql.=' limit '.($pindex - 1 )* $psize.' , '.$psize;
            $list=pdo_fetchall($sql,$params);
            if ($list) {
                foreach ($list as $key => $value) {
                    $child=pdo_fetchall('select bl.*,m.nickname,m.realname from '.tablename('sz_yi_ad_bonus_log').' bl left join '.tablename('sz_yi_member').' m on m.openid = bl.openid where obid = :obid ',array(':obid'=>$value['id']));
                    foreach ($child as $k => $v) {                       
                        $child[$k]['ctime']=date('Y-m-d H:i:s',$v['ctime']);
                        $child[$k]['rule']=$rule[$v['level']];
                    }            
                    $list[$key]['child']=$child;                 
                    $list[$key]['rule']='拆红包人';                 
                    $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);        
                }               
                show_json(1,array('list'=>$list,'pagesize'=>$psize));                                                  
            }                
            show_json(1,array('list'=>array(),'pagesize'=>$psize));                                                  
        }
    }

    $pindex=max(1,intval($_GPC['page']));        
    $psize=20;       
    $params = array(':uniacid' => $_W['uniacid'],':id'=>$id);       
    $condition=' and ob.uniacid = :uniacid and ob.adid = :id ';               

    $ad=m('tools')->getAd($id);     

    $sql='select count(*) pnum,date from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and adid = :id  group by date order by date desc ';               

    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;               
    $list=pdo_fetchall($sql,$params);        
    if ($list) {
        foreach ($list as $key => $value) {      
            $list[$key]['day']=date('Y-m-d',strtotime($value['date']));
            $list[$key]['adid']=$id;

        }            
    }                                                         
                  
    $totals = pdo_fetchall('select count(*) pnum,date from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and adid = :id  group by date order by date desc ', $params);
    $totals=count($totals);             
    $pager = pagination($totals, $pindex, $psize);                                           

}else if ($op == 'set'){         
    $id=$_GPC['id'];         
    if ($id == 1) {
        $id=2;       
    }else if($id == 2){     
        $id=3;
    }else if($id == 3){     
        $id=1;
    }                                              
    $merch=p('bonus')->getMerch($_W['uid']);            
    $sure=pdo_update('sz_yi_member',array('default_ad_model'=>$id),array('openid'=>$merch['openid']));
    if ($sure) {          
        show_json(1);       
    }else{                                       
        show_json(0);
    }
}      
// $dealmerchs = pdo_fetchall('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and merchid>0 and roleid = (select id from " . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid = '.$_W['uniacid'].' LIMIT 1)');
// $pindex = max(1, intval($_GPC['page']));
// $psize = 20;
// $condition = ' and uniacid=:uniacid and isexchange = 1 and addressid = 0 ';
// $params = array(':uniacid' => $_W['uniacid']);
// $params = array(':uniacid' => $_W['uniacid']);
// if (!empty($_GPC['status'])){
//     $condition .= ' and status =:status ';
//     $params[':status']=$_GPC['status'];
// }
// if (p('supplier')){         //如果是供应商
//     $condition.=' and supplier_uid = :uid ';
//     $params[':uid'] = $_W['uid'];
// }

// $sql = 'select id,isverify,verified,status,ordersn,price,paytime,goodsprice, dispatchprice,createtime, paytype, a.title , a.mobile as exchangeAddrTel,m.mobile, m.realname from ' . tablename('sz_yi_order') . ' o  left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=id left join ' . tablename('sz_yi_member') . ' m on openid = m.openid left join ' . tablename('sz_yi_exchange_address') . " a on a.id = storeid where 1 {$condition} ";
// $sql .= ' ORDER BY id DESC ';
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
// $totalcount = $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' o ' . ' left join ' . tablename('sz_yi_order_goods') . ' og on og.orderid=id left join ' . tablename('sz_yi_member') . ' m on openid = m.openid ' . ' left join ' . tablename('sz_yi_exchange_address') . ' a on a.id = storeid ' . " where 1 {$condition}", $params);
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
//     m('excel') -> export($list, array('title' => '订单报告-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '订单号', 'field' => 'ordersn', 'width' => 24), array('title' => '买家会员名称', 'field' => 'realname', 'width' => 12), array('title' => '买家电话', 'field' => 'mobile', 'width' => 12), array('title' => '兑换点名称', 'field' => 'title', 'width' => 12), array('title' => '兑换点电话', 'field' => 'exchangeAddrTel', 'width' => 12), array('title' => '下单时间', 'field' => 'cretetime', 'width' => 24), array('title' => '总金额(换货码)', 'field' => 'price', 'width' => 12), array('title' => '交易日期', 'field' => 'paytime', 'width' => 24), array('title' => '付款方式', 'field' => 'paytype', 'width' => 24))));
// }

include $this -> template('ad');
exit;
