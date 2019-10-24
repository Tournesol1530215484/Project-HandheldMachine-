<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$openid         = m('user')->getOpenid();
$popenid        = m('user')->islogin();
$openid         = $openid?$openid:$popenid;
$member         = m('member')->getMember($openid);
$uniacid        = $_W['uniacid'];
$goodsid        = intval($_GPC['id']);
$lat = $_SESSION['lat'];
$lng = $_SESSION['lng'];
$goods          = pdo_fetch("SELECT * FROM " . tablename('sz_yi_goods') . " WHERE id = :id limit 1", array(
    ':id' => $goodsid
));
if($goods['type']==4){
  header("Location:".$this->createMobileUrl('order/confirm',array('id'=>$_GPC['id'],'total'=>1)) );
  exit;
}



$shop           = set_medias(m('common')->getSysset('shop'), 'logo');
$shop['url']    = $this->createMobileUrl('shop');
$mid            = intval($_GPC['mid']);
$shopset = m('common')->getSysset('shop');
$opencommission = false;
if (p('commission')) {
    if (empty($member['agentblack'])) {
        $cset           = p('commission')->getSet();
        $opencommission = intval($cset['level']) > 0;
        if ($opencommission) {
            if (empty($mid)) {
                if ($member['isagent'] == 1 && $member['status'] == 1) {
                    $mid = $member['id'];
                }
            }
            if (!empty($mid)) {
                if (empty($cset['closemyshop'])) {
                    $shop        = set_medias(p('commission')->getShop($mid), 'logo');
                    $shop['url'] = $this->createPluginMobileUrl('commission/myshop', array(
                        'mid' => $mid
                    ));
                }
            }
            $commission_text = empty($cset['buttontext']) ? '我要分销' : $cset['buttontext'];
        }
    }
}
$showdiyform    = 0;
$diyform_plugin = p('diyform');
if ($diyform_plugin) {
    $diyformtype = $goods['diyformtype'];
    $diyformid   = $goods['diyformid'];
    $diymode     = $goods['diymode'];
    if (!empty($diyformtype) && !empty($diyformid)) {
        $formInfo = $diyform_plugin->getDiyformInfo($diyformid);
        $fields   = $formInfo['fields'];
        $f_data   = $diyform_plugin->getLastData(3, $diymode, $diyformid, $goodsid, $fields, $member);
    }
    if ($_W['isajax'] && $_GPC['op'] == 'create') {
        $insert_data = $diyform_plugin->getInsertData($fields, $_GPC['diydata']);
        $idata       = $insert_data['data'];
        $goods_temp  = $diyform_plugin->getGoodsTemp($goodsid, $diyformid, $openid);
        $insert      = array(
            'cid' => $goodsid,
            'openid' => $openid,
            'diyformid' => $diyformid,
            'type' => 3,
            'diyformfields' => iserializer($fields),
            'diyformdata' => $idata,
            'uniacid' => $_W['uniacid']
        );
        if (empty($goods_temp)) {
            pdo_insert('sz_yi_diyform_temp', $insert);
            $gdid = pdo_insertid();
        } else {
            pdo_update('sz_yi_diyform_temp', $insert, array(
                'id' => $goods_temp['id']
            ));
            $gdid = $goods_temp['id'];
        }
        show_json(1, array(
            'goods_data_id' => $gdid
        ));
    }
}
$html = $goods['content'];
preg_match_all("/<img.*?src=[\'| \"](.*?(?:[\.gif|\.jpg]?))[\'|\"].*?[\/]?>/", $html, $imgs);
if (isset($imgs[1])) {
    foreach ($imgs[1] as $img) {
        $im       = array(
            "old" => $img,
            "new" => tomedia($img)
        );
        $images[] = $im;
    }
    if (isset($images)) {
        foreach ($images as $img) {
            $html = str_replace($img['old'], $img['new'], $html);
        }
    }
    $goods['content'] = $html;
}
$levelid           = $member['level'];
$groupid           = $member['groupid'];
// echo '<pre>';
//     print_r($goods);
// echo '</pre>';
// if(is_weixin()){
    //禁止浏览的商品
    if ($goods['showlevels'] != '') {
        $showlevels = explode(',', $goods['showlevels']);
        if (!in_array($levelid, $showlevels)) {
            message('当前商品禁止访问，请联系客服……', $this->createMobileUrl('shop/index'), 'error');
        }
    }
    if ($goods['showgroups'] != '') {
        $showgroups = explode(',', $goods['showgroups']);
        if (!in_array($groupid, $showgroups)) {
            message('当前商品禁止访问，请联系客服……', $this->createMobileUrl('shop/index'), 'error');
        }
    }
// }
if ($_W['isajax']) {
    if (empty($goods)) {
        show_json(0);
    }
    $goods              = set_medias($goods, 'thumb');
    $goods['canbuy']    = !empty($goods['status']) && empty($goods['deleted']);
    $goods['timestate'] = '';
    $goods['userbuy']   = '1';
    if ($goods['usermaxbuy'] > 0) {
        $order_goodscount = pdo_fetchcolumn('select ifnull(sum(og.total),0)  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where og.goodsid=:goodsid and  o.status>=1 and o.openid=:openid  and og.uniacid=:uniacid ', array(
            ':goodsid' => $goodsid,
            ':uniacid' => $uniacid,
            ':openid' => $openid
        ));
        if ($order_goodscount >= $goods['usermaxbuy']) {
            $goods['userbuy'] = 0;
        }
    }
    

    $goods['levelbuy'] = '1';
    if ($goods['buylevels'] != '') {
        $buylevels = explode(',', $goods['buylevels']);
        if (!in_array($levelid, $buylevels)) {
            $goods['levelbuy'] = 0;
        }
    }
    $goods['groupbuy'] = '1';
    if ($goods['buygroups'] != '') {
        $buygroups = explode(',', $goods['buygroups']);
        if (!in_array($groupid, $buygroups)) {
            $goods['groupbuy'] = 0;
        }
    }
    $goods['timebuy'] = '0';
    if ($goods['istime'] == 1) {
        if (time() < $goods['timestart']) {
            $goods['timebuy']   = '-1';
            $goods['timestate'] = "before";
            $goods['buymsg']    = "限时购活动未开始";
        } else if (time() > $goods['timeend']) {
            $goods['timebuy'] = '1';
            $goods['buymsg']  = '限时购活动已经结束';
        } else {
            $goods['timestate'] = 'after';
        }
    }
    $goods['canaddcart'] = true;
    if ($goods['isverify'] == 2 || $goods['type'] == 2 || $goods['type'] == 3 || $goods['type'] == 7) {
        $goods['canaddcart'] = false;
    }
    $pics     = array(
        $goods['thumb']
    );
    $thumburl = unserialize($goods['thumb_url']);
    if (is_array($thumburl)) {
        $pics = array_merge($pics, $thumburl);
    }
    unset($thumburl);
    $pics         = set_medias($pics);
    $marketprice  = $goods['marketprice'];
    $productprice = $goods['productprice'];
    $maxprice     = $marketprice;
    $minprice     = $marketprice;
    $stock        = $goods['total'];
    $allspecs     = array();
    if (!empty($goods['hasoption'])) {
        $allspecs = pdo_fetchall("select * from " . tablename('sz_yi_goods_spec') . " where goodsid=:id order by displayorder asc", array(
            ':id' => $goodsid
        ));
        foreach ($allspecs as &$s) {
            $items      = pdo_fetchall("select * from " . tablename('sz_yi_goods_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(
                ":specid" => $s['id']
            ));
            $s['items'] = set_medias($items, 'thumb');
        }
        unset($s);
    }
    $options = array();
    if (!empty($goods['hasoption'])) {
        $options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('sz_yi_goods_option') . " where goodsid=:id order by id asc", array(
            ':id' => $goodsid
        ));
        $options = set_medias($options, 'thumb');
        foreach ($options as $o) {
            if ($maxprice < $o['marketprice']) {
                $maxprice = $o['marketprice'];
            }
            if ($minprice > $o['marketprice'] && $o['marketprice'] > 0) {
                $minprice = $o['marketprice'];
            }
        }
        $goods['maxprice'] = $maxprice;
        $goods['minprice'] = $minprice;
    }
    $specs  = $allspecs;
    $haveSpecs=$specs?1:0;
    $params = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_goods_param') . " WHERE uniacid=:uniacid and goodsid=:goodsid order by displayorder asc", array(
        ':uniacid' => $uniacid,
        ":goodsid" => $goods['id']
    ));
    $fcount = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and openid=:openid and goodsid=:goodsid and deleted=0 ', array(
        ':uniacid' => $uniacid,
        ':openid' => $openid,
        ':goodsid' => $goods['id']
    ));
    pdo_query('update ' . tablename('sz_yi_goods') . " set viewcount=viewcount+1 where id=:id and uniacid='{$uniacid}' ", array(
        ":id" => $goodsid
    ));
    $history = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member_history') . ' where goodsid=:goodsid and uniacid=:uniacid and openid=:openid and deleted=0 limit 1', array(
        ':goodsid' => $goodsid,
        ':uniacid' => $uniacid,
        ':openid' => $openid
    ));
    if ($history <= 0) {
        $history = array(
            'uniacid' => $uniacid,
            'openid' => $openid,
            'goodsid' => $goodsid,
            'deleted' => 0,
            'createtime' => time()
        );
        pdo_insert('sz_yi_member_history', $history);
    }


   $level     = m('member')->getLevel($openid);
   $member_level1=$level['level'];

if(!empty($level['level'])){
    $level1   =pdo_fetchall("SELECT * FROM " . tablename('sz_yi_member_level') . " WHERE uniacid=:uniacid order by level asc LIMIT {$member_level1}", array(
        ':uniacid' => $uniacid,
    ));
}

    $commission1=pdo_fetchall('select levelname,commission1 from '.tablename('sz_yi_commission_level').' where uniacid=:uniacid order by commission1 asc ',array(':uniacid'=>$uniacid));
    foreach ($commission1 as $k=> $v){
        $v['commission1']=intval($v['commission1']);
    }
    $member_level   =pdo_fetch("SELECT max(level) as level2 FROM " . tablename('sz_yi_member_level') . " WHERE uniacid=:uniacid", array(
        ':uniacid' => $uniacid,
       
    ));
    $member_level=$member_level['level2'];

    $discounts = json_decode($goods['discounts'], true);
  //file_put_contents(dirname(__FILE__).'/dasdsadsa000',$discounts);   
     if(empty($level['level'])){
        foreach ($level1 as &$val) {
            $val['levelname'] =$val['levelname'];
           $val['realprice']     =$discounts['default'];
        }

     }else{
        foreach ($level1 as &$val) {
             $val['levelname'] =$val['levelname'];
           $val['realprice']     =$discounts['level'.$val['id']];
        }
}


    $stores = array();
    if ($goods['isverify'] == 2) {
        $storeids = array();
        if (!empty($goods['storeids'])) {
            $storeids = array_merge(explode(',', $goods['storeids']), $storeids);
        }
        if (empty($storeids)) {
            $stores = pdo_fetchall('select * from ' . tablename('sz_yi_store') . ' where  uniacid=:uniacid and status=1', array(
                ':uniacid' => $_W['uniacid']
            ));
        } else {
            $stores = pdo_fetchall('select * from ' . tablename('sz_yi_store') . ' where id in (' . implode(',', $storeids) . ') and uniacid=:uniacid and status=1', array(
                ':uniacid' => $_W['uniacid']
            ));
        }
    }
    //计算距离
    foreach ($stores as $key => $value) {
        $R  = 6366000;
        $pk = doubleval(180 / 3.14169);

        $a1 = doubleval($lat / $pk);
        $a2 = doubleval($lng / $pk);
        $b1 = doubleval($value['lat'] / $pk);
        $b2 = doubleval($value['lng'] / $pk);

        $t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
        $t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
        $t3 = doubleval(sin($a1) * sin($b1));
        $tt = doubleval(acos($t1 + $t2 + $t3));
        $jl = round($R * $tt);
        
        $stores[$key]['disdance'] = $jl;
        $a = $stores[$key];
        if($stores[$key-1]){
            if( $stores[$key]['disdance']< $stores[$key-1]['disdance']){  
                $stores[$key] = $stores[$key-1];
                $stores[$key-1] = $a;
            }
        }
        
    
    }
    $followed    = m('user')->followed($openid);
    $followurl   = empty($goods['followurl']) ? $shop['followurl'] : $goods['followurl'];
    $followtip   = empty($goods['followtip']) ? '如果您想要购买此商品，需要您关注我们的公众号，点击【确定】关注后再来购买吧~' : $goods['followtip'];
    $sale_plugin = p('sale');
    $saleset     = false;
    if ($sale_plugin) {
        $saleset            = $sale_plugin->getSet();
        $saleset['enoughs'] = $sale_plugin->getEnoughs();
    }
    foreach($pics as $k=>$v){
        if (strpos($v,'attachment//')){
            $pics[$k]=preg_replace('/attachment\/\//','',$v);
        }
    }


    $ret        = array(
        'goods' => $goods,
        'followed' => $followed ? 1 : 0,
        'followurl' => $followurl,
        'followtip' => $followtip,
        'saleset' => $saleset,
        'shopset' => $shopset,
        'pics' => $pics,
        'options' => $options,
        'specs' => $specs,
        'haveSpecs'=>$haveSpecs,
        'params' => $params,
        'commission' => $opencommission,
        'commission_text' => $commission_text,
        'level' => $level,
        'commission1'=>$commission1,
        'level1' => $level1,
        'member_level' => $member_level,
        'member_level1' => $member_level1,
        'member_level.level' => $member_level2,
        'shop' => $shop,
        'goodscount' => pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_goods') . ' where uniacid=:uniacid and status=1 and deleted=0 ', array(
            ':uniacid' => $uniacid
        )),
        'cartcount' => pdo_fetchcolumn('select sum(total) from ' . tablename('sz_yi_member_cart') . ' where uniacid=:uniacid and openid=:openid and deleted=0 ', array(
            ':uniacid' => $uniacid,
            ':openid' => $openid
        )),
        'isfavorite' => $fcount > 0,
        'stores' => $stores
    );
    $commission = p('commission');
    if ($commission) {
        $shopid = $shop['mid'];
        if (!empty($shopid)) {
            $myshop = set_medias($commission->getShop($shopid), array(
                'img',
                'logo'
            ));
        }
    }
    if (!empty($myshop['selectgoods']) && !empty($myshop['goodsids'])) {
        $ret['goodscount'] = count(explode(",", $myshop['goodsids']));
    }
    $ret['detail'] = array(
        'logo' => !empty($goods['detail_logo']) ? tomedia($goods['detail_logo']) : $shop['logo'],
        'shopname' => !empty($goods['detail_shopname']) ? $goods['detail_shopname'] : $shop['name'],
        'totaltitle' => trim($goods['detail_totaltitle']),
        'btntext1' => trim($goods['detail_btntext1']),
        'btnurl1' => !empty($goods['detail_btnurl1']) ? $goods['detail_btnurl1'] : $this->createMobileUrl('shop/list'),
        'btntext2' => trim($goods['detail_btntext2']) ,
        'btnurl2' => !empty($goods['detail_btnurl2']) ? $goods['detail_btnurl2']."&supplier={$goods['supplier_uid']}" : $shop['url']."&supplier={$goods['supplier_uid']}"
    );

    // 供应商QQ
    $sql = 'SELECT qq FROM'.tablename('sz_yi_perm_user').'WHERE uniacid = :uniacid and uid = :uid';
    $res = pdo_fetch($sql, array(':uniacid' => $_W['uniacid'], ':uid' => $goods['supplier_uid']));
    $ret['supplier_uid'] = $res['qq'];

    show_json(1, $ret);

}
$_W['shopshare'] = array(
    'title' => !empty($goods['share_title']) ? $goods['share_title'] : $goods['title'],
    'imgUrl' => !empty($goods['share_icon']) ? tomedia($goods['share_icon']) : tomedia($goods['thumb']),
    'desc' => !empty($goods['description']) ? $goods['description'] : $shop['name'],
    'link' => $this->createMobileUrl('shop/detail', array(
        'id' => $goods['id']
    ))
);
$com             = p('commission');
if ($com) {
    $cset = $com->getSet();
    if (!empty($cset)) {
        if ($member['isagent'] == 1 && $member['status'] == 1) {
            $_W['shopshare']['link'] = $this->createMobileUrl('shop/detail', array(
                'id' => $goods['id'],
                'mid' => $member['id']
            ));
            if (empty($cset['become_reg']) && (empty($member['realname']) || empty($member['mobile']))) {
                $trigger = true;
            }
        } else if (!empty($_GPC['mid'])) {
            $_W['shopshare']['link'] = $this->createMobileUrl('shop/detail', array(
                'id' => $goods['id'],
                'mid' => $_GPC['mid']
            ));
        }
    }
}
$this->setHeader();
include $this->template('shop/detail');
