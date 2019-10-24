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
    $city=pdo_fetchall('select * from '.tablename('sz_yi_city').' where uniacid = :uniacid and status = 1 order by display ',array(':uniacid'=>$_W['uniacid']));      
   

    //获取所有的商家分类
    $category=pdo_fetchall("select id ,parentid,name from hs_sz_yi_category where uniacid={$_W['uniacid']} and level =1 order by isrecommand desc, displayorder DESC");    

    if ($_GPC['debug'] == 1) {

        include $this -> template('barter/merch_debug');        

    }else{

      

      include $this -> template('barter/merch');       

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

}else if($op=='ajaxCate'){

     // $id = intval($_GPC['id']);
     $pindex    = max(1, intval($_GPC['page']));

      $psize     = 10;

      $condition = "and  uniacid=:uniacid ";  

      $params    = array( 

          ':uniacid' => $uniacid

      );
      if (!empty($_GPC['city'])) {

        $condition .= ' AND `city` LIKE :city';

        $params[':city'] = '%' . trim($_GPC['city']) . '%';

    }
     if (!empty($_GPC['cate'])) {
        //无限极分类，找出所有栏目下的子id
        $data=pdo_fetch("select * from hs_sz_yi_category where uniacid={$_W['uniacid']} and id = $_GPC");
        $ids=self::Parenttree($_GPC['cate'],$data);

        //print_r($ids);
        // $condition .= ' AND `city` LIKE :city';

        // $params[':city'] = '%' . trim($_GPC['city']) . '%';

    }

     $sql="SELECT * FROM ".tablename('sz_yi_dealmerch_user')." where 1 {$condition}  order by display desc,juli asc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;



    $list    = pdo_fetchall($sql, $params); 

     

    foreach ($list as $k=>$v){

        if (empty($list[$k]['merchname'])) {

            // unset($list[$k]);

            // continue;

            $list[$k]['merchname']='店家';

        }

        if (!empty($v['lng']) && !empty($v['lat'])) {

          $list[$k]['distance']=m('user')->GetDistance($lat, $lng, $v['lat'], $v['lng'], 2);



        }else{

          $list[$k]['distance']='未选择位置';

        }

        $list[$k]['url'] = $this->createMobileUrl('member/merch',array('op'=>'detail','id'=>$v['id']));

        $list[$k]['img']=tomedia($v['logo']);   

        $list[$k]['shopurl']=$this->createPLuginMobileUrl('supplier/store',array('op'=>'skip','storeid'=>$list[$k]['uid'],'merch'=>5));

        $temp=pdo_fetchall('select id,type,title,thumb_url from '.tablename('sz_yi_goods').' where uniacid = :uniacid and supplier_uid = :uid and type = 8 and status = 1 and ischeck = 1 order by id desc limit 0,2',array(':uniacid'=>$_W['uniacid'],':uid'=>$list[$k]['uid']));    

        $arr=[];  

        foreach ($temp as $key => $value) {

                $temp[$key]['thumb_url']=unserialize($temp[$key]['thumb_url']);


        }

        

        foreach ($temp as $key => $value) {

            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) { 

                    $arr[$key2]['img']=tomedia($value2);
                    //pdo_fetch("select marketprice from hs_sz_yi_goods where id =$value['id']")
                    $peice=pdo_fetch("select marketprice from hs_sz_yi_goods_option  where goodsid ={$value['id']}");
                    //$arr[$key2]['marketprice']=$peice['marketprice'];
                    $arr[$key2]['marketprice']=$peice['marketprice'];
                    $arr[$key2]['title']=$value['title'];
                    $arr[$key2]['url']=$this->createMobileUrl('shop/detail',array('id'=>$value['id']));   

                }                

            }             

        }

        $list[$k]['album']=$arr;        

    }


    // foreach ($list as $key => $value) {
    //     if(empty($list[$key]['album'])){
    //       unset($list[$key]);
    //     }
    // }

    //修改有图片的优先显示
    $havathumb=array();
    $nothavrthumb=array();
    foreach($list as $key=>$value){
      if(empty($list[$key]['album'])){
        $nothavrthumb[]=$list[$key];
        //unset($list[$key]);
      }else{
        $havathumb[]=$list[$key];
       // unset($list[$key]);
      }
    }
    unset($list);

    $list=$havathumb+$nothavrthumb;

   $total     = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_dealmerch_user') . " where 1 {$condition}", $params);

    

  show_json(1, array(

        'total' => $total,

        'list' => $list,

        'pagesize' => $psize

    ));

}else if($op=='ajaxList'){

    $pindex    = max(1, intval($_GPC['page']));

    $psize     = 10;

    $condition = "and  uniacid=:uniacid ";  

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

//    print_r($lat);exit;

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

    

    $sql="SELECT * , ROUND( 6367000*2 * ASIN( SQRT( POW( SIN( ( ".$lat." * PI( ) /180 - lat * PI( ) /180 ) /2 ) , 2 ) +

        COS( ".$lat." * PI( ) /180 ) * COS( lat * PI( ) /180 ) * POW( SIN( ( ".$lng." * PI( ) /180 - lng * PI( ) /180 ) /2 ) , 2 ) ) ) *1000 )

        AS juli FROM ".tablename('sz_yi_dealmerch_user')." where 1 {$condition}  order by display desc,juli asc LIMIT " . ($pindex - 1) * $psize . ',' . $psize;



    $list    = pdo_fetchall($sql, $params); 

     

    foreach ($list as $k=>$v){

     

        if (empty($list[$k]['merchname'])) {

            // unset($list[$k]);

            // continue;

            $list[$k]['merchname']='店家';

        }

        if (!empty($v['lng']) && !empty($v['lat'])) {

          $list[$k]['distance']=m('user')->GetDistance($lat, $lng, $v['lat'], $v['lng'], 2);



        }else{

          $list[$k]['distance']='未选择位置';

        }

        $list[$k]['url'] = $this->createMobileUrl('member/merch',array('op'=>'detail','id'=>$v['id']));

        $list[$k]['img']=tomedia($v['logo']);   

        $list[$k]['shopurl']=$this->createPLuginMobileUrl('supplier/store',array('op'=>'skip','storeid'=>$list[$k]['uid'],'merch'=>5));

        $temp=pdo_fetchall('select id,type,title,thumb_url from '.tablename('sz_yi_goods').' where uniacid = :uniacid and supplier_uid = :uid and type = 8 and status = 1 and ischeck = 1 order by id desc limit 0,2',array(':uniacid'=>$_W['uniacid'],':uid'=>$list[$k]['uid']));   

        // $list[$k]['sure']=$temp; 
        //print_r($temp);   

        $arr=[];  

        foreach ($temp as $key => $value) {

                $temp[$key]['thumb_url']=unserialize($temp[$key]['thumb_url']);

            // $arr[$key]['img']=tomedia($value);

            // $arr[$key]['url']='#';

        }

        

        foreach ($temp as $key => $value) {

            foreach ($value as $key1 => $value1) {

                foreach ($value1 as $key2 => $value2) { 

                    $arr[$key2]['img']=tomedia($value2);
                    //pdo_fetch("select marketprice from hs_sz_yi_goods where id =$value['id']")
                    $peice=pdo_fetch("select marketprice from hs_sz_yi_goods_option  where goodsid ={$value['id']}");
                    //$arr[$key2]['marketprice']=$peice['marketprice'];
                    $arr[$key2]['marketprice']=$peice['marketprice'];
                    $arr[$key2]['title']=$value['title'];
                    $arr[$key2]['url']=$this->createMobileUrl('shop/detail',array('id'=>$value['id']));   

                }                

            }             

        }

        $list[$k]['album']=$arr;        

    }
     //修改有图片的优先显示
    $havathumb=array();
    $nothavrthumb=array();
    foreach($list as $key=>$value){
      if(empty($list[$key]['album'])){
        $nothavrthumb[]=$list[$key];
        //unset($list[$key]);
      }else{
        $havathumb[]=$list[$key];
       // unset($list[$key]);
      }
    }
    unset($list);

    $list=$havathumb+$nothavrthumb;



   $total     = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_dealmerch_user') . " where 1 {$condition}", $params);

    //获取当前用户信息，如果是商家，查询出他的商家信息是否完善
    //$bussess=pdo_fetch("select * from hs_sz_yi_perm_user where uniacid={$_W['uniacid']} and openid='{$openid}'");
   $sqls="select realname,mobile,provance,city,area from hs_sz_yi_perm_user where uniacid={$_W['uniacid']} and openid='{$openid} and type=3'"; //type=3 是换货商家
   $bussess=array();
   $messageinfo=pdo_fetch("select uid from hs_sz_yi_perm_user where uniacid={$_W['uniacid']} and openid='{$openid}' and type=3");

   if($messageinfo['uid']){
        $uid=$messageinfo['uid'];
     $bussess=pdo_fetch("select * from hs_sz_yi_dealmerch_user where uniacid={$_W['uniacid']} and uid=$uid");
    }

$cate=pdo_fetchall('select * from '.tablename('sz_yi_merch_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$uniacid));

  show_json(1, array(

        'messageinfo'=>$messageinfo['uid'],

        'bussess'=>$bussess,

        'total' => $total,

        'list' => $list,

        'pagesize' => $psize,

        'cate'=>$cate,

        'category'=>8888,

    ));

}





