<?php



if (!defined('IN_IA')){



    exit('Access Denied');



}





global $_W,$_GPC;



if ($_GPC['op'] == 'sort') {

    if ($_W['isajax']) {

        $pindex=max(1,intval($_GPC['page']));

        $day=intval($_GPC['time']);

        $timed=strtotime('-'.$day .'days');

        $psize=100;

        $num=($pindex-1) * 10 + 1;

        if ($day) {

            $condition='  o.uniacid = :uniacid and o.isexchange = 1 and g.status = 1 and o.createtime > :stime';

            $params=[

                ':uniacid'=>$_W['uniacid'],

                ':stime'=>$timed,

            ];

        }

        $sql='select g.title,g.id,g.thumb,g.supplier_uid,sum(og.total) as total,sum(og.price) as price from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on og.orderid = o.id left join '.tablename('sz_yi_goods').' g on og.goodsid = g.id where '.$condition.' group by og.goodsid order by total desc';

        $sql.=' limit '.($pindex-1) * $psize .','.$psize;

        $list=pdo_fetchall($sql,$params);



        if ($list) {

            foreach ($list as $key => $value) {

                $list[$key]['thumb']=tomedia($list[$key]['thumb']);

                //$list[$key]['mainmap']=tomedia($list[$key]['mainmap']); //广告主图

                $list[$key]['num']=$num;

                $list[$key]['goodsurl']=$this->createMobileUrl('shop/detail',array('id'=>$list[$key]['id']));

                $list[$key]['merchurl']=$this->createPluginMobileUrl('supplier/store',array('merch'=>5,'op'=>'skip','storeid'=>$list[$key]['supplier_uid']));

                $num++;

            }

            show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize));

        }else{

            show_json(0,'暂无更多');

        }

    }

    include $this -> template('barter/sort');

    exit;

}



if ($_GPC['op'] == 'sortBack') {

    if ($_W['isajax']) {

        $pindex=max(1,intval($_GPC['page']));

        $day=intval($_GPC['time']);

        $timed=strtotime('-'.$day .'days');

        $psize=10;

        $num=($pindex-1) * 10 + 1;

        if ($day) {

            $condition='  o.uniacid = :uniacid and o.isexchange = 1 and g.status = 1 and o.createtime > :stime';

            $params=[

                ':uniacid'=>$_W['uniacid'],

                ':stime'=>$timed,

            ];

        }

        $sql='select g.title,g.id,g.thumb,g.mainmap,g.supplier_uid,sum(og.total) as total,sum(og.price) as price from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on og.orderid = o.id left join '.tablename('sz_yi_goods').' g on og.goodsid = g.id where '.$condition.' group by og.goodsid order by total desc';

        $sql.=' limit '.($pindex-1) * $psize .','.$psize;

        $list=pdo_fetchall($sql,$params);



        if ($list) {

            foreach ($list as $key => $value) {

                $list[$key]['thumb']=tomedia($list[$key]['thumb']);

                $list[$key]['mainmap']=tomedia($list[$key]['mainmap']); //广告主图

                $list[$key]['num']=$num;

                $list[$key]['goodsurl']=$this->createMobileUrl('shop/detail',array('id'=>$list[$key]['id']));

                $list[$key]['merchurl']=$this->createPluginMobileUrl('supplier/store',array('merch'=>5,'op'=>'skip','storeid'=>$list[$key]['supplier_uid']));

                $num++;

            }

            show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize));

        }else{

            show_json(0,'暂无更多');

        }

    }

    include $this -> template('barter/sortBack');

    exit;

}





$set=pdo_fetch('select * from '.tablename('sz_yi_sysset').' where uniacid = '.$_W['uniacid']);

$set=unserialize($set['sets']);

$banner=pdo_fetchall('select thumb,link from '.tablename('sz_yi_adv').' where uniacid = :uniacid  and enabled = 0 and status = 1 and isbart = 1 ',array(':uniacid' =>$_W['uniacid']));



$_W['shopshare'] = array(

    'title' => $set['bart']['title'],

    'imgUrl' =>tomedia($set['bart']['thumb']) ,

    'desc' => $set['bart']['share'],

    'link' => $this->createMobileUrl('barter/index')

);



$menu=pdo_fetchall('select * from '.tablename('sz_yi_barter_menu').' where uniacid = :uniacid and isdisplay = 1 order by displayorder desc ',array(':uniacid' =>$_W['uniacid']));



$condition="uniacid = {$_W['uniacid']} and type = 8 and status = 1 and ischeck= 1 ";

//去掉所有关键字是，银，珠宝，翡翠，鞋，衣服，玛瑙，玉的标题
$condition.="and title not  REGEXP '^银|珠宝|翡翠|鞋|衣服|玛瑙|玉|墨翠|纯银'";

$condition.=' order by uptime desc,id desc limit 0,50';



$list=pdo_fetchall('select * from '.tablename('sz_yi_goods').' where '.$condition);  //过审核的易货商品



foreach ($list as $key  => &$val) {

    if ($val['dispatchtype'] == 1  ) {

        $val['isdispatch'] = doubleval($val['dispatchprice']) > 0 ? 1: 0;

    }elseif ($val['dispatchtype'] == 0) {

        if($val['dispatchid'] > 0){

            $dispathInfo=pdo_fetchcolumn('select firstprice from '.tablename('sz_yi_dispatch').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$val['dispatchid']));

            if (doubleval($dispathInfo) > 0) {

                $val['isdispatch'] = 0;

            }else{

                $val['isdispatch'] = 1;

            }

        }else{

            $val['isdispatch'] = 1;

        }

    }



    $val['minprice']=pdo_fetchcolumn('select min(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid='.$_W['uniacid'].' and goodsid='.$val['id']);

    $val['maxprice']=pdo_fetchcolumn('select max(marketprice) from '.tablename('sz_yi_goods_option').' where uniacid='.$_W['uniacid'].' and goodsid='.$val['id']);

    $val['thumb']   =tomedia($val['thumb']);

    $val['mainmap']   =tomedia($val['mainmap']);    //广告主图

    if ($val['dispatchid']  != 0){

        $val['dispatchprice']= pdo_fetchcolumn('select firstprice from '.tablename('sz_yi_dispatch').' where id = '.$val['dispatchid']);

    }



}

unset($val);



if ($_GPC['op']=='demo') {



    include $this -> template('barter/indexBackup');

}else{



    include $this -> template('barter/index');

}

