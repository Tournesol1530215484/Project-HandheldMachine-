<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W, $_GPC;
function upload_cert($dephp_0){
    global $_W;
    $dephp_1 = IA_ROOT . '/addons/sz_yi/cert';
    load() -> func('file');
    mkdirs($dephp_1, '0777');
    $dephp_2 = $dephp_0 . '_' . $_W['uniacid'] . '.pem';
    $dephp_3 = $dephp_1 . '/' . $dephp_2;
    $dephp_4 = $_FILES[$dephp_0]['name'];
    $dephp_5 = $_FILES[$dephp_0]['tmp_name'];
    if (!empty($dephp_4) && !empty($dephp_5)){
        $dephp_6 = strtolower(substr($dephp_4, strrpos($dephp_4, '.')));
        if ($dephp_6 != '.pem'){
            $dephp_7 = "";
            if ($dephp_0 == 'weixin_cert_file'){
                $dephp_7 = 'CERT文件格式错误';
            }else if ($dephp_0 == 'weixin_key_file'){
                $dephp_7 = 'KEY文件格式错误';
            }else if ($dephp_0 == 'weixin_root_file'){
                $dephp_7 = 'ROOT文件格式错误';
            }
            message($dephp_7 . ',请重新上传!', '', 'error');
        }
        return file_get_contents($dephp_5);
    }
    return "";
}
$op = empty($_GPC['op']) ? 'shop' : trim($_GPC['op']);
if ($op == 'datamove'){
    $up = m('common') -> dataMove();
    exit('迁移成功');
}
$setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
//print_r($setdata);exit;
$set = unserialize($setdata['sets']);
//print_r($set['shop']);exit;
$oldset = unserialize($setdata['sets']);
if ($op == 'template'){
    $styles = array();
    $dir = IA_ROOT . '/addons/sz_yi/template/mobile/';
    if ($handle = opendir($dir)){
        while (($file = readdir($handle)) !== false){
            if ($file != '..' && $file != '.'){
                if (is_dir($dir . '/' . $file)){
                    $styles[] = $file;
                }
            }
        }
        closedir($handle);
    }
}else if ($op == 'notice'){
    $salers = array();
    if (isset($set['notice']['openid'])){
        if (!empty($set['notice']['openid'])){
            $openids = array();
            $strsopenids = explode(',', $set['notice']['openid']);
            foreach ($strsopenids as $openid){
                $openids[] = '\'' . $openid . '\'';
            }
            $salers = pdo_fetchall('select id,nickname,avatar,openid from ' . tablename('sz_yi_member') . ' where openid in (' . implode(',', $openids) . ") and uniacid={$_W['uniacid']}");
        }
    }
    $newtype = explode(',', $set['notice']['newtype']);
}else if ($op == 'pay'){
    $sec = m('common') -> getSec();

    $sec = iunserializer($sec['sec']);

}else if($op == 'pcset'){
    $designer = p('designer');
    $categorys = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_article_category') . ' WHERE uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));
    if ($designer){
        $diypages = pdo_fetchall('SELECT id,pagetype,setdefault,pagename FROM ' . tablename('sz_yi_designer') . ' WHERE uniacid=:uniacid order by setdefault desc  ', array(':uniacid' => $_W['uniacid']));
    }
    $article_sys = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_article_sys') . ' WHERE uniacid=:uniacid limit 1 ', array(':uniacid' => $_W['uniacid']));
    $article_sys['article_area'] = json_decode($article_sys['article_area'], true);
    $area_count = sizeof($article_sys['article_area']);
    if ($area_count == 0){
        $article_sys['article_area'][0]['province'] = '';
        $article_sys['article_area'][0]['city'] = '';
        $area_count = 1;
    }
    $goodcates = pdo_fetchall('SELECT id,name,parentid FROM ' . tablename('sz_yi_category') . ' WHERE enabled=:enabled and uniacid= :uniacid  ', array(':uniacid' => $_W['uniacid'], ':enabled' => '1'));
    if(empty($set['shop']['hmenu_name'])){
        $set['shop']['hmenu_name'] = array('首页', '全部商品', '店铺公告', '成为分销商', '会员中心');
        $set['shop']['hmenu_url'] = array($this -> createMobileUrl('shop/index'), $this -> createMobileUrl('shop/list', array('order' => 'sales', 'by' => 'desc')), $this -> createMobileUrl('shop/notice'), $this -> createPluginMobileUrl('commission'), $this -> createMobileUrl('member/info'));
        $set['shop']['hmenu_id'] = array('yz01', 'yz02', 'yz03', 'yz04', 'yz05');
    }else{
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
    }

//    print_r($set['shop']);exit;
}else if($op == 'oks'){
  //拿出两个优惠券的ID
  $did=$set['shop']['reccouponid'];
  $reccouponid=pdo_fetch('select * from' .tablename('sz_yi_coupon') .' where id=:id and uniacid=:uniacid',array(
  ':id'=>$did,
  ':uniacid'=>$_W['uniacid']
  ));
  $rid=$set['shop']['subcouponid'];
   $subcoupon=pdo_fetch('select * from' .tablename('sz_yi_coupon') .' where id=:id and uniacid=:uniacid',array(
  ':id'=>$rid,
  ':uniacid'=>$_W['uniacid']
  ));
}else if($op == 'topmenu'){
  pdo_delete('sz_yi_topmenu',array('id'=>$_GPC['id']));
    message('更新删除成功！', $this->createWebUrl('sysset',array('op'=>'pcset')), 'success');
}
if (checksubmit()){
    if ($op == 'shop'){
        $shop = is_array($_GPC['shop']) ? $_GPC['shop'] : array();
        $set['shop']['name'] = trim($shop['name']);
        $set['shop']['cservice'] = trim($shop['cservice']);
        $set['shop']['img'] = save_media($shop['img']);
        $set['shop']['logo'] = save_media($shop['logo']);
        $set['shop']['signimg'] = save_media($shop['signimg']);
        $set['shop']['diycode'] = trim($shop['diycode']);
        $set['shop']['copyright'] = trim($shop['copyright']);
        plog('sysset.save.shop', '修改系统设置-商城设置');
    }elseif ($op == 'pcset'){
        $custom = is_array($_GPC['pcset']) ? $_GPC['pcset'] : array();
//        print_r($custom);exit;
        foreach($custom['hmenu_name'] as $k=>$v){

            if(mb_strlen($k)>=2){

                $data['parentid']=substr($k,0,1);
                $data['uniacid']=$_W['uniacid'];
                $data['name']=$custom['hmenu_name'][$k];
                $data['url']=$custom['hmenu_url'][$k];
                $data['ids']=$custom['hmenu_id'][$k];

                pdo_insert('sz_yi_topmenu',$data);
                unset($custom['hmenu_name'][$k]);
                unset($custom['hmenu_url'][$k]);
                unset($custom['hmenu_id'][$k]);
                break;
            }
        }
//        print_r($custom);exit;
        $set['shop']['ispc'] = trim($custom['ispc']);
		$set['shop']['isreferral'] = trim($custom['isreferral']);
		$set['shop']['paytype'] = trim($custom['paytype']);
        $set['shop']['pctitle'] = trim($custom['pctitle']);
        $set['shop']['pckeywords'] = trim($custom['pckeywords']);
        $set['shop']['pcdesc'] = trim($custom['pcdesc']);
        $set['shop']['pccopyright'] = trim($custom['pccopyright']);
        $set['shop']['index'] = $custom['index'];
        $set['shop']['pclogo'] = save_media($custom['pclogo']);
        $set['shop']['reglogo'] = save_media($custom['reglogo']);
        $set['shop']['hmenu_name'] = $custom['hmenu_name'];
        $set['shop']['parentid'] = $custom['parentid'];
        $set['shop']['hmenu_url'] = $custom['hmenu_url'];
        $set['shop']['hmenu_id'] = $custom['hmenu_id'];
        $set['shop']['fmenu_name'] = $custom['fmenu_name'];
        $set['shop']['fmenu_url'] = $custom['fmenu_url'];
        $set['shop']['fmenu_id'] = $custom['fmenu_id'];

        plog('sysset.save.sms', '修改系统设置-PC设置');
    }elseif($op =='oks'){

		$okl = is_array($_GPC['oks']) ? $_GPC['oks'] : array();
		 $set['shop']['yaoqing'] = trim($okl['yaoqing']);
		 $set['shop']['guanggao'] = trim($okl['guanggao']);
		 $set['shop']['jiangli'] = trim($okl['jiangli']);
		 $set['shop']['reccredit'] = trim($okl['reccredit']);
		 $set['shop']['recmoney'] = trim($okl['recmoney']);
		 $set['shop']['reccouponid'] = trim($okl['reccouponid']);
		 $set['shop']['reccouponnum'] = trim($okl['reccouponnum']);
		 $set['shop']['subcredit'] = trim($okl['subcredit']);
		 $set['shop']['submoney'] = trim($okl['submoney']);
		 $set['shop']['subcouponid'] = trim($okl['subcouponid']);
		 $set['shop']['subcouponnum'] = trim($okl['subcouponnum']);
		 $set['shop']['paytype'] = trim($okl['paytype']);
		 $set['shop']['templateidd'] = trim($okl['templateidd']);
		 $set['shop']['subtextd'] = trim($okl['subtextd']);
		 $set['shop']['entrytextd'] = trim($okl['entrytextd']);
		 $set['shop']['subpaycontentd'] = trim($okl['subpaycontentd']);
		 $set['shop']['recpaycontentd'] = trim($okl['recpaycontentd']);

		 plog('sysset.save.sms', '修改系统设置-会员设置');
	}elseif ($op == 'sms'){
        $sms = is_array($_GPC['sms']) ? $_GPC['sms'] : array();
        $set['sms']['type'] = $sms['type'];
        $set['sms']['account'] = $sms['account'];
        $set['sms']['password'] = $sms['password'];
        $set['sms']['userid'] = $sms['userid'];
        $set['sms']['appkey'] = $sms['appkey'];
        $set['sms']['secret'] = $sms['secret'];
        $set['sms']['signname'] = $sms['signname'];
        $set['sms']['product'] = $sms['product'];
        $set['sms']['templateCode'] = $sms['templateCode'];
        $set['sms']['templateCodeForget'] = $sms['templateCodeForget'];
        plog('sysset.save.sms', '修改系统设置-短信设置');
    }elseif ($op == 'follow'){
        $set['share'] = is_array($_GPC['share']) ? $_GPC['share'] : array();
        $set['share']['icon'] = save_media($set['share']['icon']);
        plog('sysset.save.follow', '修改系统设置-分享及关注设置');
    }else if ($op == 'notice'){
        $set['notice'] = is_array($_GPC['notice']) ? $_GPC['notice'] : array();
        if (is_array($_GPC['openids'])){
            $set['notice']['openid'] = implode(',', $_GPC['openids']);
        }
        $set['notice']['newtype'] = $_GPC['notice']['newtype'];
        if (is_array($set['notice']['newtype'])){
            $set['notice']['newtype'] = implode(',', $set['notice']['newtype']);
        }
        plog('sysset.save.notice', '修改系统设置-模板消息通知设置');
    }elseif ($op == 'trade'){
        $set['trade'] = is_array($_GPC['trade']) ? $_GPC['trade'] : array();
        if (!$_W['isfounder']){
            unset($set['trade']['receivetime']);
            unset($set['trade']['closordertime']);
            unset($set['trade']['paylog']);
        }else{
            m('cache') -> set('receive_time', $set['trade']['receivetime'], 'global');
            m('cache') -> set('closeorder_time', $set['trade']['closordertime'], 'global');
            m('cache') -> set('paylog', $set['trade']['paylog'], 'global');
        }
        plog('sysset.save.trade', '修改系统设置-交易设置');
    }elseif ($op == 'pay'){
        $pluginy = p('yunpay');
        if($pluginy){
            $pay = $set['pay']['yunpay'];
        }
        $set['pay'] = is_array($_GPC['pay']) ? $_GPC['pay'] : array();
        if($pluginy){
            $set['pay']['yunpay'] = $pay;
        }
        if ($_FILES['weixin_cert_file']['name']){
            $sec['cert'] = upload_cert('weixin_cert_file');
        }
        if ($_FILES['weixin_key_file']['name']){
            $sec['key'] = upload_cert('weixin_key_file');
        }
        if ($_FILES['weixin_root_file']['name']){
            $sec['root'] = upload_cert('weixin_root_file');
        }
        if (empty($sec['cert']) || empty($sec['key']) || empty($sec['root'])){
        }
        pdo_update('sz_yi_sysset', array('sec' => iserializer($sec)), array('uniacid' => $_W['uniacid']));
        plog('sysset.save.pay', '修改系统设置-支付设置');
    }elseif ($op == 'template'){
        $shop = is_array($_GPC['shop']) ? $_GPC['shop'] : array();
        $set['shop']['style'] = save_media($shop['style']);
        $set['shop']['theme'] = trim($shop['theme']);
        $set['shop']['open'] = trim($shop['open']);
        m('cache') -> set('template_shop', $set['shop']['style']);
        m('cache') -> set('theme_shop', $set['shop']['theme']);
        plog('sysset.save.template', '修改系统设置-模板设置');
    }elseif ($op == 'member'){
        $shop = is_array($_GPC['shop']) ? $_GPC['shop'] : array();
        $set['shop']['levelname'] = trim($shop['levelname']);
        $set['shop']['levelurl'] = trim($shop['levelurl']);
        plog('sysset.save.member', '修改系统设置-会员设置');
        $set['shop']['isbindmobile'] = intval($shop['isbindmobile']);
		$set['shop']['leveltype'] = intval($shop['leveltype']);
		$set['shop']['isreferrer'] = intval($shop['isreferrer']);
    }elseif ($op == 'category'){
        $shop = is_array($_GPC['shop']) ? $_GPC['shop'] : array();
        $set['shop']['catlevel'] = trim($shop['catlevel']);
        $set['shop']['catshow'] = intval($shop['catshow']);
        $set['shop']['catadvimg'] = save_media($shop['catadvimg']);
        $set['shop']['catadvurl'] = trim($shop['catadvurl']);
        plog('sysset.save.category', '修改系统设置-分类层级设置');
    }elseif ($op == 'contact'){
        $shop = is_array($_GPC['shop']) ? $_GPC['shop'] : array();
        $set['shop']['qq'] = trim($shop['qq']);
        $set['shop']['address'] = trim($shop['address']);
        $set['shop']['phone'] = trim($shop['phone']);
        $set['shop']['description'] = trim($shop['description']);
        plog('sysset.save.contact', '修改系统设置-联系方式设置');
    }
    $data = array('uniacid' => $_W['uniacid'], 'sets' => iserializer($set));
    if (empty($setdata)){
        pdo_insert('sz_yi_sysset', $data);
    }else{
        pdo_update('sz_yi_sysset', $data, array('uniacid' => $_W['uniacid']));
    }
    $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    m('cache') -> set('sysset', $setdata);
    message('设置保存成功!', $this -> createWebUrl('sysset', array('op' => $op)), 'success');
}
load() -> func('tpl');
include $this -> template('web/sysset/' . $op);
exit;
