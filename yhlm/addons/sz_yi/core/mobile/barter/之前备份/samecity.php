<?php

 if (!defined('IN_IA')){

    exit('Access Denied');

}

global $_W, $_GPC;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$openid = m('user') -> getOpenid();

$uniacid = $_W['uniacid'];



       

 

if($op=='display'){

  //获取同城商品

    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    $set = unserialize($setdata['sets']);


    $city=pdo_fetchall('select * from '.tablename('sz_yi_city').' where uniacid = :uniacid and status = 1 order by display ',array(':uniacid'=>$_W['uniacid']));

     

    if ($_GPC['debug']) {

    }

    // $sql='select * from '.tablename('sz_yi_merch_type')." where uniacid=:uniacid and status=1 order by display desc limit 10";

    // $type=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'])); 

    

    if ($_GPC['debug'] == 1) {

        include $this -> template('barter/samecity_debug');          

    }else{

      include $this -> template('barter/samecity');

    }


} else if ($op == 'set') {

    $id = intval($_GPC['sid']); //店铺ID



    $merchant= pdo_fetch('select id from ' . tablename('sz_yi_merch_user') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));

    if (empty($merchant)) {

        show_json(0,'商家未找到');

    }

    $data = pdo_fetch('select id,deleted from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and merchid=:merchid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':merchid' => $id));

    if (empty($data)) { //如果没有收藏过

        $data = array('uniacid' => $_W['uniacid'], 'openid' => $openid, 'merchid' => $id, 'createtime' => time());

        pdo_insert('sz_yi_member_favorite', $data);

        show_json(1, array('isfavorite' => true));

    } else {    //如果收藏过

        if (empty($data['deleted'])) {

            pdo_update('sz_yi_member_favorite', array('deleted' => 1), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));

            show_json(1, array('isfavorite' => false));

        } else {

            pdo_update('sz_yi_member_favorite', array('deleted' => 0), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));

            show_json(1, array('isfavorite' => true));

        }

    }

}else if($op=='near'){



     include $this->template('member/near');

     exit;

}else if($op=='detail'){

     $id = intval($_GPC['id']);



     $sql='select * from '.tablename('sz_yi_merch_user').' where uniacid=:uniacid and id=:id and status=1';

     $merch=pdo_fetch($sql,array(':uniacid'=>$_W['uniacid'],':id'=>$id));

     $merch['details']=html_entity_decode($merch['details']);

     $fcount = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and openid=:openid and merchid=:merchid and deleted=0 ', array(

         ':uniacid' => $uniacid,

         ':openid' => $openid,

         ':merchid' => $id

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

    $condition = "and  uniacid=:uniacid ";  

    $params    = array( 

        ':uniacid' => $uniacid

    );



    if (!empty($_GPC['keyword'])) {

        //$condition .= ' AND `merchname` LIKE :kerword';

        $condition .= ' AND `city` LIKE :kerword';

        $params[':kerword'] = '%' . trim($_GPC['keyword']) . '%';

    }

if (!empty($_GPC['city'])) {

        $condition .= ' AND `city` LIKE :city';

        $params[':city'] = '%' . trim($_GPC['city']) . '%';

    }

    //获取同城商品
    $sql="SELECT * FROM ".tablename('sz_yi_goods')."where 1 {$condition} and shelves=1  and status=1 and  ischeck=1 order  by displayorder desc LIMIT ". ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, $params); 

    foreach ($list as $key => $value) {
        $list[$key]['minprice']=pdo_fetchcolumn('select min(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid='.$_W['uniacid'].' and goodsid='.$list[$key]['id']);
        $list[$key]['maxprice']=pdo_fetchcolumn('select max(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid='.$_W['uniacid'].' and goodsid='.$list[$key]['id']);
        $list[$key]['thumb']=tomedia($list[$key]['thumb']);

        // $temp=mb_substr($list[$key]['title'], 0,16);

        // $list[$key]['title']=mb_strlen($temp)>mb_strlen($list[$key]['title'])?$list[$key]['title']:$temp.'...';             //截取标题显示
    }

   $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_goods') . " where 1 {$condition}", $params);

   
  show_json(1, array(

        'total' => $sql,

         'list' => $list,

         'pagesize' => $psize

    ));

}



