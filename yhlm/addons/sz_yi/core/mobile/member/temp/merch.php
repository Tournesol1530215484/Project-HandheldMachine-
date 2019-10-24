<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user') -> getOpenid();
$uniacid = $_W['uniacid'];

$city=pdo_fetchall('select * from '.tablename('sz_yi_city').' where uniacid = :uniacid and status = 1 order by display ',array(':uniacid'=>$_W['uniacid']));
if($op=='display'){

    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    $set = unserialize($setdata['sets']);
    $sql='select * from '.tablename('sz_yi_merch_type')." where uniacid=:uniacid and status=1 order by display desc limit 10";
    $type=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid']));      

    
    if ($_GPC['debug']) {                                
        include $this -> template('member/merch_debug');    
        exit;             
    }                
    include $this -> template('member/merch');

} else if ($op == 'set') {      //收藏
    $uid = intval($_GPC['sid']); //店铺ID
    $sure=m('tools')->favoriteStore($openid,$uid);
    if ($sure) {
        show_json(1, array('isfavorite' =>true));
    }else{      
        show_json(1, array('isfavorite' => false));
    }
    // $puinfo=p('bonus')->getMerch($id);

    // if (empty($puinfo || $merchant)) {
    //     show_json(0,'商家未找到');
    // }
    
    // $data = pdo_fetch('select id,deleted from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and merchid=:merchid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':merchid' => $id));

    // if (empty($data)) { //如果没有收藏过
    //     $data = array('uniacid' => $_W['uniacid'], 'openid' => $openid, 'merchid' => $id, 'createtime' => time());
    //     pdo_insert('sz_yi_member_favorite', $data);
    //     show_json(1, array('isfavorite' => true));
    // } else {    //如果收藏过
    //     if (empty($data['deleted'])) {
    //         pdo_update('sz_yi_member_favorite', array('deleted' => 1), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
    //         show_json(1, array('isfavorite' => false));
    //     } else {
    //         pdo_update('sz_yi_member_favorite', array('deleted' => 0), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
    //         show_json(1, array('isfavorite' => true));
    //     }
    // }
}else if($op=='near'){

     include $this->template('member/near');
     exit;
}else if($op=='detail'){
     $id = intval($_GPC['id']);

     $sql='select * from '.tablename('sz_yi_merch_user').' where uniacid=:uniacid and id=:id and status=1';
     $merch=pdo_fetch($sql,array(':uniacid'=>$_W['uniacid'],':id'=>$id));
     $uid=$merch['uid'];
     $merch['details']=html_entity_decode($merch['details']);
     $fcount = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and openid=:openid and merchid=:merchid and deleted=0 ', array(
         ':uniacid' => $uniacid,
         ':openid' => $openid,
         ':merchid' => $merch['uid']
     ));
     $merch['isfavorite']=$fcount > 0;
     if(empty($merch)){

         message('商家已关闭，请联系管理员！', '', 'error');
     }
     $num=empty($merch['num'])?0:$merch['num'];
     $sql='select * from '.tablename('sz_yi_goods')." where uniacid=:uniacid and front=1 and supplier_uid=:supplier_uid and status=1 order by displayorder desc limit {$num}";
     $goods=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'],':supplier_uid'=>$merch['uid']));
     foreach ($goods as  $k => $v){
         $goods[$k]['thumb']=tomedia($v['thumb']);
     }
     include $this -> template('member/merchdetail');
}else if($op=='ajaxList'){
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 10;
    $condition = "and  uniacid=:uniacid  and status=1";  
    $params    = array(
        ':uniacid' => $uniacid
    );
    if (!empty($_GPC['keyword'])) {
    
        $condition .= ' AND `merchname` LIKE :kerword';
        $params[':kerword'] = '%' . trim($_GPC['keyword']) . '%';
    }
    if(!empty($_GPC['typeid'])){
        $condition .= ' AND typeid =:typeid';
        $params[':typeid'] = intval($_GPC['typeid']);
    }
if (!empty($_GPC['city'])) {
    
        $condition .= ' AND `city` LIKE :city';
        $params[':city'] = '%' . trim($_GPC['city']) . '%';
    }
    
    $lng = $_GPC['lng'];
    $lat = $_GPC['lat'];
    if(empty($lng) || empty($lat)){
        $arr  =  file_get_contents('http://api.map.baidu.com/location/ip?ak=2SxShLokVzpxylYYQXNvr4WEQnO5wD8E&coor=bd09ll');
        $p =  (array)json_decode($arr);
        $p = (array) $p['content'];
        foreach($p as $k=>$v){
            if(is_object($v)){
                $p[$k] = (array)$v;
            }
        }
        $lat=$p['point']['x'];
        $lng=$p['point']['y'];
       
    }
    
    $sql="SELECT *, ROUND( 6367000*2 * ASIN( SQRT( POW( SIN( ( ".$lat." * PI( ) /180 - lat * PI( ) /180 ) /2 ) , 2 ) +
        COS( ".$lat." * PI( ) /180 ) * COS( lat * PI( ) /180 ) * POW( SIN( ( ".$lng." * PI( ) /180 - lng * PI( ) /180 ) /2 ) , 2 ) ) ) *1000 )
        AS juli FROM ".tablename('sz_yi_merch_user')." where 1 {$condition}  order by display desc,juli asc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;

    $list    = pdo_fetchall($sql, $params);
    
    foreach ($list as $k=>$v){                                                               
        if (!empty($v['lng']) && !empty($v['lat'])) {
            $list[$k]['distance']=m('user')->GetDistance($lat, $lng, $v['lat'], $v['lng'], 2);
        }else{
            $list[$k]['distance']='未选择位置';

        }
        $list[$k]['url'] = $this->createMobileUrl('member/merch',array('op'=>'detail','id'=>$v['id']));
        $list[$k]['img']=tomedia($v['logo']);
    }
   $total     = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_merch_user') . " where 1 {$condition}", $params);
   
  show_json(1, array(
        'total' => $total,
        'list' => $list,
        'pagesize' => $psize
    ));
}

