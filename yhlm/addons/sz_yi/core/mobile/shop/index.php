<?php

if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';

$openid = m('user') -> getOpenid();

$uniacid = $_W['uniacid'];

$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

$set = unserialize($setdata['sets']);

$designer = p('designer');

$supplier = !empty($_GPC['supplier'])?$_GPC['supplier']:0;

if($supplier!=0){

 $supplier =  pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user')." where uid = '{$supplier}' and status = 1 limit 1 ");

 $supplier = empty($supplier)?0:$supplier;

}

if(empty($this ->yzShopSet['ispc']) || isMobile()){

    if ($designer){

        $pagedata = $designer -> getPage(1,$supplier);
 
        if ($pagedata){ 

            extract($pagedata);

            $guide = $designer -> getGuide($system, $pageinfo);

            $_W['shopshare'] = array('title' => $share['title'], 'imgUrl' => $share['imgUrl'], 'desc' => $share['desc'], 'link' => $this -> createMobileUrl('shop'));
            if($openid=='oSI4LjzP3HyhUqIG-CmdgTLIjRgI'){
                //print_r($goods);die;
            }
            if (p('commission')){

                $set = p('commission') -> getSet();

                if (!empty($set['level'])){

                    $member = m('member') -> getMember($openid);

                    if (!empty($member) && $member['status'] == 1 && $member['isagent'] == 1){

                        $_W['shopshare']['link'] = $this -> createMobileUrl('shop', array('mid' => $member['id']));

                        if (empty($set['become_reg']) && (empty($member['realname']) || empty($member['mobile']))){
                            $trigger = true;
                        }
                    }else if (!empty($_GPC['mid'])){

                        $_W['shopshare']['link'] = $this -> createMobileUrl('shop', array('mid' => $_GPC['mid']));

                    }

                }

            }
            if ($operation == 'demo') {
                include $this -> template('shop/myindex_diy');
            }else{      
                include $this -> template('shop/index_diy');
            }
            exit;

        }

    }

}
if ($operation == 'index'){

    foreach($set['shop']['hmenu_name'] as $k=>$v){
        $ks=$k;
        if(strlen($k)>1){
            $ks=substr($k,-1);
        }
        $vlist=pdo_fetchall('select * from'.tablename('sz_yi_topmenu').'where uniacid=:uniacid and parentid=:pid',array(':pid'=>$ks,':uniacid'=>$_W['uniacid']));
        $arr=array();
        $arr['name']=$v;
        $arr['vlist']=$vlist;
        $set['shop']['hmenu_name'][$k]=$arr;
    }

    $advs = pdo_fetchall('select id,advname,link,thumb,thumb_pc from ' . tablename('sz_yi_adv') . ' where uniacid=:uniacid and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));

    foreach($advs as $key => $adv){

        if(!empty($advs[$key]['thumb'])){

            $adv[] = $advs[$key];

        }

        if(!empty($advs[$key]['thumb_pc'])){

            $adv_pc[] = $advs[$key];

        }

    }

    $advs = set_medias($advs, 'thumb,thumb_pc');

    $advs_pc = set_medias($adv_pc, 'thumb,thumb_pc');

    $category = pdo_fetchall('select id,name,thumb,parentid,level from ' . tablename('sz_yi_category') . ' where  uniacid=:uniacid and ishome=1 and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));

    $category = set_medias($category, 'thumb');

    $index_name = array('isrecommand' => '精品推荐', 'isnew' => '新上商品', 'ishot' => '热卖商品', 'isdiscount' => '促销商品', 'issendfree' => '包邮商品', 'istime' => '限时特价');

    foreach ($category as & $c){

        $c['thumb'] = tomedia($c['thumb']);

        if ($c['level'] == 3){

            $c['url'] = $this -> createMobileUrl('shop/list', array('tcate' => $c['id']));

        }else if ($c['level'] == 2){

            $c['url'] = $this -> createMobileUrl('shop/list', array('ccate' => $c['id']));

        }

    }

    $ads_pc = array();

    $goods_pc = array();

    if(!empty($this -> yzShopSet['index']['isrecommand']) && !empty($this -> yzShopSet['ispc'])){

        $ads_pc['isrecommand'] = pdo_fetchall('select * from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'isrecommand\'', array(':uniacid' => $uniacid));

        $goods_pc['isrecommand'] = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and status = 1 and deleted = 0 and isrecommand=1 order by displayorder desc limit 4', array(':uniacid' => $uniacid));

        $ads_pc['isrecommand'] = set_medias($ads_pc['isrecommand'], 'thumb');

        $goods_pc['isrecommand'] = set_medias($goods_pc['isrecommand'], 'thumb');

    }

    if(!empty($this -> yzShopSet['index']['isnew']) && !empty($this -> yzShopSet['ispc'])){

        $ads_pc['isnew'] = pdo_fetchall('select * from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'isnew\'', array(':uniacid' => $uniacid));

        $goods_pc['isnew'] = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and status = 1 and deleted = 0 and isnew=1 order by displayorder desc limit 4', array(':uniacid' => $uniacid));

        $ads_pc['isnew'] = set_medias($ads_pc['isnew'], 'thumb');

        $goods_pc['isnew'] = set_medias($goods_pc['isnew'], 'thumb');

    }

    if(!empty($this -> yzShopSet['index']['ishot']) && !empty($this -> yzShopSet['ispc'])){

        $ads_pc['ishot'] = pdo_fetchall('select * from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'ishot\'', array(':uniacid' => $uniacid));

        $goods_pc['ishot'] = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and status = 1 and deleted = 0 and ishot=1 order by displayorder desc limit 4', array(':uniacid' => $uniacid));

        $ads_pc['ishot'] = set_medias($ads_pc['ishot'], 'thumb');

        $goods_pc['ishot'] = set_medias($goods_pc['ishot'], 'thumb');

    }

    if(!empty($this -> yzShopSet['index']['isdiscount']) && !empty($this -> yzShopSet['ispc'])){

        $ads_pc['isdiscount'] = pdo_fetchall('select * from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'isdiscount\'', array(':uniacid' => $uniacid));

        $goods_pc['isdiscount'] = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and status = 1 and deleted = 0 and isdiscount=1 order by displayorder desc limit 4', array(':uniacid' => $uniacid));

        $ads_pc['isdiscount'] = set_medias($ads_pc['isdiscount'], 'thumb');

        $goods_pc['isdiscount'] = set_medias($goods_pc['isdiscount'], 'thumb');

    }

    if(!empty($this -> yzShopSet['index']['issendfree']) && !empty($this -> yzShopSet['ispc'])){

        $ads_pc['issendfree'] = pdo_fetchall('select * from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'issendfree\'', array(':uniacid' => $uniacid));

        $goods_pc['issendfree'] = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and status = 1 and deleted = 0 and issendfree=1 order by displayorder desc limit 4', array(':uniacid' => $uniacid));

        $ads_pc['issendfree'] = set_medias($ads_pc['issendfree'], 'thumb');

        $goods_pc['issendfree'] = set_medias($goods_pc['issendfree'], 'thumb');

    }

    if(!empty($this -> yzShopSet['index']['istime']) && !empty($this -> yzShopSet['ispc'])){

        $ads_pc['istime'] = pdo_fetchall('select * from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'istime\'', array(':uniacid' => $uniacid));

        $goods_pc['istime'] = pdo_fetchall('select * from ' . tablename('sz_yi_goods') . ' where uniacid = :uniacid and status = 1 and deleted = 0 and istime=1 order by displayorder desc limit 4', array(':uniacid' => $uniacid));

        $ads_pc['istime'] = set_medias($ads_pc['istime'], 'thumb');

        $goods_pc['istime'] = set_medias($goods_pc['istime'], 'thumb');

    }

    $ads_pc['bottom_ad'] = pdo_fetch('select link,thumb from ' . tablename('sz_yi_adpc') . ' where uniacid=:uniacid and location=\'bottom_ad\'', array(':uniacid' => $uniacid));

    if(!empty($ads_pc['bottom_ad'])){

        $ads_pc['bottom_ad'] = set_medias($ads_pc['bottom_ad'], 'thumb');

    }

    unset($c);
//    one
    $category  = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }
    $catelist= pdo_fetchall("SELECT * FROM " . tablename('sz_yi_category') . " WHERE uniacid = '{$_W['uniacid']}' and parentid=1061 ORDER BY id ASC, displayorder DESC");

    $catelists=$catelist;

    foreach($catelists as $ke=>&$v){
        $v['catelist']=pdo_fetchall('SELECT * FROM'.tablename('sz_yi_category').'WHERE uniacid=:uniacid AND parentid=:parentid',array(':parentid'=>$v['id'],':uniacid'=>$_W['uniacid']));
        $v['rank']=$ke+1;

        $v['goodslist'] =pdo_fetchall ("SELECT g.showgroups,g.showlevels,g.PostFlag,g.LocalFlag,g.id,g.title,g.thumb,g.sales,g.total,g.description,g.unit,g.type,op.marketprice,op.productprice,op.costprice FROM " .
            tablename('sz_yi_goods') . " g left join (select goodsid,productprice,max(marketprice) marketprice,costprice from ".
            tablename('sz_yi_goods_option')." where uniacid = 8 group by goodsid) op on g.id = op.goodsid
            where  g.type=8 and g.deleted=0 and g.status=1 and g.ischeck and g.ccate='{$v['id']}' ORDER BY g.displayorder desc LIMIT 10");

        $rd=mt_rand(0,9);

        $v['goods']=$v['goodslist'][$rd];

        $co=count($v['goodslist']);

        if($co<8){
            unset($catelists[$ke]);
        }
    }
    unset($v);
    $goodslist= pdo_fetchall("SELECT title,thumb,marketprice,productprice FROM " . tablename('sz_yi_goods') . " WHERE uniacid = '{$_W['uniacid']}' and status=1 and total>0 and type<8 and deleted=0 and ccate=1062 ORDER BY id DESC LIMIT 10");

    $articlelist2 = pdo_fetchall("SELECT * FROM ".tablename('site_article')." WHERE uniacid = '{$_W['uniacid']}' and pcate=2 ORDER BY displayorder DESC, id DESC LIMIT 7");
    $articlelist3 = pdo_fetchall("SELECT * FROM ".tablename('site_article')." WHERE uniacid = '{$_W['uniacid']}' and pcate=3 ORDER BY displayorder DESC, id DESC LIMIT 7");
    $articlelist4 = pdo_fetchall("SELECT * FROM ".tablename('site_article')." WHERE uniacid = '{$_W['uniacid']}' and pcate=4 ORDER BY displayorder DESC, id DESC LIMIT 7");

    $advlist = pdo_fetchall("SELECT * FROM ".tablename('sz_yi_adv')." WHERE uniacid = '{$_W['uniacid']}' and  enabled=1 ORDER BY displayorder DESC, id DESC LIMIT 4");

    $rlist=pdo_fetchall('select * from'.tablename('sz_yi_right').'where uniacid=:uniacid ',array(':uniacid'=>$_W['uniacid']));
    $listlogo=pdo_fetchall('select * from'.tablename('sz_yi_rightlogo').'where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));

}else if ($operation == 'goods'){

    $type = $_GPC['type'];

    $args = array('page' => $_GPC['page'], 'pagesize' => 6, 'isrecommand' => 1, 'order' => 'displayorder desc,createtime desc', 'by' => '','supplier_uid'=>$supplier);


    $type = array('1'=>'isnew','2'=>'ishot','3'=>'isrecommand','4'=>'isdiscount','5'=>'istime');

    if(empty($_GPC['type'])||!array_key_exists($_GPC['type'],$type)){
        $args['isnew'] = 1 ;
    } else{
        $args[$type[$_GPC['type']]] = 1;
    }



    $goods = m('goods') -> getList($args);
    foreach ($goods as &$v) {
        $ratio = $v['sales'] / $v['total'];
        $v['ratio'] = intval($ratio * 100);
        $goodss[] = $v; 
    }
    $goods = $goodss;

}

if ($_W['isajax']){

    if ($operation == 'index'){

        show_json(1, array('set' => $set, 'advs' => $advs, 'category' => $category));

    }else if ($operation == 'goods'){

        $type = $_GPC['type'];

        show_json(1, array('goods' => $goods, 'pagesize' => $args['pagesize']));

    }

}


$this -> setHeader();

include $this -> template('shop/index');

