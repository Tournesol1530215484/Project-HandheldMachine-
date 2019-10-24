<?php
/**
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $goodsid = $_GPC['goodsid'];
    $items = pdo_fetchall("SELECT id,yi_goodsid,shuliang,pihao,roleid,commjssionid,jifen,uniacid,start,end,yi_shengyu,yipin_shengyu FROM " . tablename('yipinyima') .
 " where  yi_goodsid=".$_GPC['goodsid']." and  uniacid=".$_W['uniacid']."");
   /*$umss = pdo_fetchall("SELECT * FROM " . tablename('yipinyima') . " d " .
        " left join " . tablename('sz_yi_member') . ' m on m.id = d.commjssionid '.
        " where  d.yi_goodsid=".$_GPC['goodsid']." and  d.uniacid=".$_W['uniacid']."");*/
	foreach($items as $k=>$v){
		$mels = pdo_fetch("select id,nickname from ".tablename('sz_yi_member'). " where id='".$v['commjssionid']."' and  uniacid='".$_W['uniacid']."'");	
		$melsf = pdo_fetch("select id,yipin_status,yipin_id,uniacid from ".tablename('yipinyimaerwei'). " where yipin_id='".$v['id']."' and  uniacid='".$_W['uniacid']."'");
		if($melsf['yipin_status']>=0){
			
		}else{
			
		}
		
		$items[$k]['commjssionid'] = $mels['nickname'];		
		$roleid_item = unserialize($v['jifen']);
		$commision_level_name = '';
		foreach($roleid_item as $key => $value ){
			$commision_level = pdo_fetch("select id,uniacid,levelname from ".tablename('sz_yi_commission_level'). " where id='".$value['itemid']."'");	
			$commision_level_name .= empty($commision_level_name)? $commision_level['levelname'] : ','.$commision_level['levelname'];
			if($value['id']==0){
				$commision_level_name .= "全部会员";
			}
		}
		$items[$k]['commision_level_name'] = $commision_level_name;
		$items[$k]['start_time'] = date('Y/m/d', $v['start']);
		$items[$k]['end_time']   = date('Y/m/d', $v['end']);	
	}
    $item_desc = pdo_fetch("select * from" . tablename('yipinyimapeizhi') . ' where goodsid=:goodsid and  uniacid=:uniacid', array(
        ':uniacid' => $_W['uniacid'],
        ':goodsid'=>$goodsid
    ));

} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
	
	$lisee['ismnes']=1;
	$lisee['isbind']=1;
	$lisee['jifenzt']=1;
    //print_r($_GPC);exit;
    if (empty($id)) {
        ca('yipinyima.temp.add');
    } else {
        ca('yipinyima.temp.view|comeon.temp.edit');
    }
    if (!empty($id)) {
        $lisee = pdo_fetch("select * from" . tablename('yipinyima') . ' where  id=:id and uniacid=:uniacid', array(
            ':uniacid' => $_W['uniacid'],
            ':id'=>$id
        ));
		
		$idms=$lisee['yipinp_id'];

        $ms=unserialize($lisee['roleid']);
		$list2=unserialize($lisee['jifen']);
		foreach($list2 as $key=>$value){
			$level = pdo_fetch("select levelname from" . tablename('sz_yi_commission_level') . ' where id=:id and  uniacid=:uniacid', array(
				':uniacid' => $_W['uniacid'],
				':id'=>$value['itemid']
			));
			$melsfds = pdo_fetch("select id,couponname from".tablename('sz_yi_coupon'). " where id='".$value['subcouponid']."' and  uniacid='".$_W['uniacid']."'");
			if($value['itemid']==0){
				$list2[$key]['name'] = "全部会员";
			}
			$list2[$key]['levelname'] = $level['levelname'];
			$list2[$key]['couponname'] = $melsfds['couponname'];
			
			$item .= $item? ','.$value['id'] : $value['id'];
			
		}

		$items = explode(',', $item);
        $lsec = pdo_fetch("SELECT * FROM " . tablename('yipinyima') . " d " .
            " left join " . tablename('sz_yi_member') . ' m on m.id = d.commjssionid '.
            " where d.id=".$id." and d.uniacid=".$_W['uniacid']."");
			
			
    } /*else {
        $lisee = pdo_fetch("select * from" . tablename('yipinyima') . ' where id=:id and uniacid=:uniacid', array(
            ':uniacid' => $_W['uniacid'],
            ':id'=>$id
        ));
		$idms=$lisee['yipinp_id'];
        $ms=unserialize($lisee['roleid']);
        $lsec = pdo_fetch("SELECT * FROM " . tablename('yipinyima') . " d " .
            " left join " . tablename('sz_yi_member') . ' m on m.id = d.commjssionid '.
            " where d.id=".$id." and d.uniacid=".$_W['uniacid']."");
			print_r($lsec);exit;
    }*/

    $lisr = pdo_fetchall("select * from" . tablename('sz_yi_commission_level') . ' where  uniacid=:uniacid', array(
        ':uniacid' => $_W['uniacid']
    ));

    //重组新的数组s
    $list = array();
	//所有会员
	$huiyuan=0;
    foreach ($lisr as $key => $value) {
		
		$value['jifen'] = $mss['shop']['jifen'][$key];
		$value['xianjin'] = $mss['shop']['xianjin'][$key];
		$value['subcouponnum'] = $mss['shop']['subcouponnum'][$key];
		$value['subcouponid'] = $mss['shop']['subcouponid'][$key];		
		$value['fenxiao']=$melsfds;
        $list[] = $value;
		
    }
	
} elseif ($operation == 'postadd') {
    $id = intval($_GPC['id']);
    if (empty($id)) {
        ca('yipinyima.temp.add');
    } else {
        ca('yipinyima.temp.view|comeon.temp.edit');
    }
    if (!empty($id)) {
        $lise = pdo_fetch("select * from" . tablename('yipinyimapeizhi') . ' where  uniacid=:uniacid', array(
            ':uniacid' => $_W['uniacid']
        ));
    } else {
        $lise = pdo_fetch("select * from" . tablename('yipinyimapeizhi') . ' where  uniacid=:uniacid', array(
            ':uniacid' => $_W['uniacid']
        ));
    }
    $insert = array(
        'uniacid' => $_W['uniacid'],
        'goodsid' => trim($_GPC['goodsid']),
        'first_desc' => trim($_GPC['first_desc']),
        'score_desc' => trim($_GPC['score_desc']),
        'second_desc' => trim($_GPC['second_desc']),
        'oldtime_desc' => trim($_GPC['oldtime_desc']),
        'linkurl' => trim($_GPC['linkurl']),
        'bgColor' => trim($_GPC['bgColor']),
        'headthumb' => save_media($_GPC['headthumb']),
        'adthumb' => save_media($_GPC['adthumb']),
        'score_xianjin'=>trim($_GPC['score_xianjin']),
        'score_youhuijuan'=>trim($_GPC['score_youhuijuan'])
    );

    if (empty($id)) {
        pdo_insert('yipinyimapeizhi', $insert);
        $id = pdo_insertid();
        plog('yipinyima.temp.edit', "添加一品一码配置 ID: {$id}");
    } else {
        pdo_update('yipinyimapeizhi', $insert, array(
            'id' => $id
        ));
        plog('yipinyima.temp.edit', "编辑一品一码配置 ID: {$id}");
    }
    message('保存成功！', $this->createPluginWebUrl('yipinyima/temp', array('op' => 'display','goodsid' => $_GPC['goodsid'])));
}elseif($operation ='postadms'){

    $id = intval($_GPC['id']);
    $goodsid = intval($_GPC['goodsid']);
    if (empty($id)) {
        ca('yipinyima.temp.add');
    } else {
        ca('yipinyima.temp.view|comeon.temp.edit');
    }
	//print_r($_GPC['shop']);exit;
	for($k=0; $k<count($_GPC['shop']['itemid']); $k++){
		$item[$k] = array( 
			'id' => $_GPC['shop']['itemid'][$k],
			'itemid' => $_GPC['shop']['itemid'][$k],
			'jifen' => $_GPC['shop']['jifen'][$k],
			'xianjin' => $_GPC['shop']['xianjin'][$k],
			'subcouponid' => $_GPC['shop']['subcouponid'][$k],
			'subcouponnum' => $_GPC['shop']['subcouponnum'][$k]
		);
	}
	$items = iserializer($item);
    $insert = array(
        'uniacid' => $_W['uniacid'],
        'pihao' => trim($_GPC['pihao']),
        'shuliang' => trim($_GPC['shuliang']),
        'isbind' => trim($_GPC['isbind']),
		'jifen'=>$items,
		'shiyongdengji'=>iserializer($set['shop']['itemid']),
        'jifenzt' => trim($_GPC['jifenzt']),
        'commjssionid'=>trim($_GPC['commjssionid']),
        'groupid'=>trim($_GPC['groupid']),
        'paytype' => trim($_GPC['paytype']),
        'description'=>trim($_GPC['description']),
        'roleid'=>iserializer($set['shop']['itemid']),
        'ulogo' => save_media($_GPC['ulogo']),
        'yipinp_id'=>trim($_GPC['yipinp_id']),
        'ismnes'=>trim($_GPC['ismnes']),
        'yi_goodsid'=>trim($_GPC['yi_goodsid']),
		'yi_shengyu'=>trim($_GPC['shuliang'])
    );

    if (empty($id)) {
        pdo_insert('yipinyima', $insert);
        $id = pdo_insertid();
        $shijian=$_GPC['shijian'];
        $shijian=array(
            'start'=>strtotime($shijian['start']),
            'end'=>strtotime($shijian['end'])
        );
        $birth=$_GPC['birth'];
        $birth=array(
            'province'=>$birth['province'],
            'city'=>$birth['city'],
            'district'=>$birth['district']
        );
        pdo_update('yipinyima', $shijian, array(
            'id' => $id
        ));
        pdo_update('yipinyima', $birth, array(
            'id' => $id
        ));
		
            if(!empty($_GPC['ulogo'])){
                $logo  =  "/attachment/".$_GPC['ulogo'].'';
            }else{
                $logo  = "";
            }
			$mem=$_GPC['shuliang'];
            for($x=0;$x<$mem;$x++){
			
                $erweibianhao=$this->model->get_sn();
                $filename=  "/attachment/images/qrcode/".$erweibianhao.".png";
                $insert=array(
                    'erweibianhao'=>$erweibianhao,
                    'erweiurl'=>$filename,
                    'yipin_status'=>"0",
                    'yipin_id'=>$id,
                    'uniacid'=>$_W['uniacid']
                );
                pdo_insert('yipinyimaerwei',$insert);
                $gid = pdo_insertid();
                $data = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=yipinyima&yiid='.$_GPC['yipinp_id'].'&mid='.$gid;
                // $data=$_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=yipinyima&method=index';

                $this->model->urlcode($data,$filename,$logo);
            }
		plog('yipinyima.temp.add', "添加一品一码 ID: {$id}");
    } else {
		
        pdo_update('yipinyima', $insert, array(
            'id' => $id
        ));
			if(checksubmit('submit')){
		       $mem=$_GPC['shuliang'];
            if(!empty($_GPC['ulogo'])){
                $logo  =  "/attachment/".$_GPC['ulogo'].'';
            }else{
                $logo  = "";
            }
            for($x=0;$x<$mem;$x++){
                $erweibianhao=$this->model->get_sn();
                $filename=  "/attachment/images/qrcode/".$erweibianhao.".png";
                $insert=array(
                    'erweibianhao'=>$erweibianhao,
                    'erweiurl'=>$filename,
                    'yipin_status'=>"0",
                    'yipin_id'=>$id,
                    'uniacid'=>$_W['uniacid']
                );
                pdo_insert('yipinyimaerwei',$insert);
                $gid = pdo_insertid();
                $data = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=yipinyima&yiid='.$_GPC['yipinp_id'].'&mid='.$gid;
                // $data=$_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=yipinyima&method=index';

                $this->model->urlcode($data,$filename,$logo);
            }
			plog('yipinyima.temp.edit', "编辑一品一码 ID: {$id}");
		}
        message('保存成功！', $this->createPluginWebUrl('yipinyima/temp', array('op' => 'display','goodsid' => $_GPC['yi_goodsid'])));
    }
message('保存成功！', $this->createPluginWebUrl('yipinyima/temp', array('op' => 'display','goodsid' => $_GPC['yi_goodsid'])));
}elseif($operation='list'){
    echo "dd";exit;
    $id = intval($_GPC['id']);
    if (empty($id)) {
        ca('yipinyima.temp.list');
    } else {
        ca('yipinyima.temp.view|comeon.temp.edit');
    }

}
load()->func('tpl');
include $this->template('temp');
