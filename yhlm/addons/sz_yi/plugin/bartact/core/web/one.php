<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$plugin_diyform = p('diyform');             
$muser=m('match')->getMuser($_W['uid']);

$totals = array();
if ($op == 'display') {
//    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
//
//    $set = unserialize($setdata['sets']);
    //头部菜单遍历
//    foreach($set['shop']['hmenu_name'] as $k=>$v){
//        $vlist=pdo_fetchall('select * from'.tablename('sz_yi_topmenu').'where uniacid=:uniacid and parentid=:pid',array(':pid'=>$k,':uniacid'=>$_W['uniacid']));
////        print_r($vlist);exit;
//        $arr=array();
//        $arr['name']=$v;
//        $arr['vlist']=$vlist;
//        $set['shop']['hmenu_name'][$k]=$arr;
//    }
    $rlist=pdo_fetchall('select * from'.tablename('sz_yi_right').'where uniacid=:uniacid ',array(':uniacid'=>$_W['uniacid']));
    $listlogo=pdo_fetchall('select * from'.tablename('sz_yi_rightlogo').'where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));

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

//    print_r($set['shop']['hmenu_name']);exit;
}                                   
     
include $this -> template('one');
