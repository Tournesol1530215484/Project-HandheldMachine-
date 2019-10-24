<?php



if (!defined('IN_IA')){



    exit('Access Denied');



}

global $_W,$_GPC;



$openid=m('user')->getOpenid(); 

$popenid=m('user')->islogin(); 

$openid = $openid?$openid:$popenid;

$stime=strtotime(date('Y-m-d'));

$etime=strtotime(date('Ymd',strtotime("+1day")));

$op=empty($_GPC['op'])?'display':$_GPC['op'];

$ac=$_GPC['ac'];

if ($op == 'detail') {          //广告页详情

	$id=intval($_GPC['id']);

	$ad=m('tools')->getAd($id);



	if ($ac == 'getthumb') {

		empty($id) && die('非法参数');

		load()->classs('captcha');

		$captcha = new Captcha();



		$total=mb_strlen($ad['core'],'utf8');

		if ($total!= 6 ) {

			$num=6-$total;

			$a=m('tools')->expStr($ad['core']);

			if ($num == 5) {

				$ad['core']='   '.$ad['core'].'  ';

			}else if($num == 4){

				$ad['core']=' '.$a[0].'  '.$a[1].' ';

			}else if($num == 3){

				$ad['core']=$a[0].' '.$a[1].' '.$a[2].' ';

			}else if($num == 2){

				$ad['core']=' '.$ad['core'].' ';

			}else if($num == 1){

				$ad['core']=' '.$ad['core'];

			}

		}	 		  	 



		$captcha->build2(250, 60,$ad['core']);

		$captcha->output();

		exit;

	}



	if ($_W['isajax']) {

	    $ad['desc']=strip_tags(html_entity_decode($ad['desc']));

	    $ad['thumb']=unserialize($ad['thumb']);

	    foreach ($ad['thumb'] as $key => $value) {

	    	$ad['thumb'][$key]=tomedia($value);

	    }

	    $pm=p('bonus')->getMerch($ad['uid']); 		 	 		 	 	 	 	

	    // $member=m('member')->getMember($openid); //mobile

	    $sMember=m('member')->getMember($pm['openid']);

	    $merch=array();

	    if (!empty($pm['merchid'])) {

	        $temp=pdo_fetch('select * from '.tablename('sz_yi_merch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));

	        $merch['logo']=$temp['logo']?:$temp['img'];

	        $merch['logo']=tomedia($merch['logo'])?:$sMember['avatar'];

	        $merch['merchname']=$temp['merchname'];

	        // http://jhzh66.com/app/index.php?i=8&c=entry&p=merch&op=detail&id=70&merch=3&do=member&m=sz_yi

	        $merch['url']=$this->crteateMobileUrll('member/merchi',array('op'=>'detail','merch'=>'3','id'=>$temp['id']));

	    }else if (!empty($pm['dealmerchid'])){

	        $temp=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));

	        $merch['logo']=$temp['logo']?:$temp['img'];

	        $merch['logo']=tomedia($merch['logo'])?:$sMember['avatar'];

	        $merch['merchname']=$temp['merchname'];

	        $merch['url']=$this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>'5' ,'storeid' =>$temp['uid']));

	    }else{

	        $temp=pdo_fetch('select * from '.tablename('sz_yi_store_data').' where uniacid = :uniacid and storeid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));

	        $merch['logo']=$temp['logo']?:$temp['signboard'];

	        $merch['logo']=tomedia($merch['logo'])?:$sMember['avatar'];

	        $merch['merchname']=$temp['storename'];   

	        $merch['url']=$this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>'2' ,'storeid' =>$temp['storeid']));

	    }

	    $merch['favorite']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));



	    $ad['totalgoods']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_goods').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));

	    $ad['merch']=$merch;

	    $ad['goodsid']=$ad['goodsid']?:0;

	    $ad['goods']=pdo_fetchall('select * from '.tablename('sz_yi_goods').' where uniacid = :uniacid and id in ('.$ad['goodsid'].')',array(':uniacid'=>$_W['uniacid']));

	    foreach ($ad['goods'] as $key => $value) {

	        $ad['goods'][$key]['thumb']=tomedia($value['thumb']);

	        $ad['goods'][$key]['url']=$this->createMobileUrl('shop/detail',array('id'=>$value['id']));

	        if ($value['type'] == 8) {               

	            $option=pdo_fetch('select * from '.tablename('sz_yi_goods_option').' where uniacid = :uniacid and goodsid  = :id order by marketprice',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));

	            $ad['goods'][$key]['marketprice']=$option['marketprice'];

	            $ad['goods'][$key]['shopprice']=$option['productprice'];

	        }

	        

	    }	

	    show_json(1,array('ad'=>$ad));

	}	 	 

	$core=pdo_fetchcolumn('select core from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	$length=mb_strlen($core,'utf8');

	$str=m('tools')->getChar(6-$length);

	$full=$str.$core;

	$full=m('tools')->expStr($full);

	$corearr=m('tools')->expStr($core);

	shuffle($full);

	$tempad=m('tools')->getAd($id);

	$tmp=pdo_fetch('select * from '.tablename('sz_yi_member_favorite').' where uniacid = :uniacid and openid = :openid and merchid = :uid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':uid'=>$tempad['uid']));

	$isfavorite=$tmp['deleted']==1?false:true;

	

	$sMerch=p('bonus')->getMerch($tempad['uid']);

	$sMember=m('member')->getMember($sMerch['openid']);

	$consult=$this->createPluginMobileUrl('commission/team',array('op'=>'zixun','sender'=>'superior','id'=>$sMember['id']));

	$sure=true; 	 	 

	$tempad['jump']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and ctime > :stime and ctime < :etime and status = 0 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$tempad['id'],':stime'=>$stime,':etime'=>$etime,':version'=>$tempad['version']));

		$tempad['daydraw']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and ctime > :stime and ctime < :etime and status = 1 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$tempad['id'],':stime'=>$stime,':etime'=>$etime,':version'=>$tempad['version']));

		$tempad['alldraw']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and status = 1 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$tempad['id'],':version'=>$tempad['version']));

		if ($tempad['alldraw'] >= $tempad['usermax']) {	//最大领取数量

			$sure=false;	

		}else if($tempad['daydraw'] >= $tempad['daymax']){	//每天最大领取数量

			$sure=false;	

		}else if(intval($tempad['stime']) > time() || intval($tempad['etime']) < time()){	//time

			$sure=false;	//未开始或已结束

		}else if($tempad['status'] != 1 || $tempad['now'] != 1){

			$sure=false;

		}

	$reprot_type=pdo_fetchall('select * from '.tablename('sz_yi_report_type').' where uniacid = :uniacid and status = 1 order by display desc ',array(':uniacid'=>$_W['uniacid']));

include $this -> template('barter/detailAd');

exit;

}else if($op == 'display'){



if ($ac == 'getcate') {		//展示面板

	$destory=array();

	$cate=pdo_fetchall('select * from '.tablename('sz_yi_member_ad_cate').' where uniacid = :uniacid and openid = :openid and is_del = 1',array('uniacid'=>$_W['uniacid'],':openid'=>$openid));

	$enable=pdo_fetchall('select * from '.tablename('sz_yi_ad_type').' where uniacid = :uniacid and status = 1',array(':uniacid'=>$_W['uniacid']));

	foreach ($enable as $key => $value) {

		foreach ($cate as $k => $v) {

			if ($value['id'] == $v['cate']) {

				$destory[]=$enable[$key];

				unset($enable[$key]);		

			}		

		}		

	}

	show_json(1,array('enable'=>$enable,'destory'=>$destory));



}else if($ac == 'doad') {		//添加或删除

	$cate=explode(',',$_GPC['adcate']);

	$sure=!empty($_GPC['what'])?true:false;

	$fail=array(); 	 

	if ($cate) {

		foreach ($cate as $key => $value) {		//数组

			$re=m('tools')->memberAd($openid,$value,$sure);

			if (!$re) {

				$fail[]=$value;

			}

		}

	}else{

		$cate=intval($_GPC['adcate']);

		$re=m('tools')->memberAd($openid,$cate,$sure);

		if (!$re) {

			$fail[]=$cate;

		}

	}

	if ($fail) {

		show_json(0);

	}

	show_json(1);

}else if($ac == 'getad'){	//获取分类广告

	$cate=intval($_GPC['adcate']);

	$userinfo=m('member')->getMember($openid);

	empty($cate) && show_json(0,'非法参数');

	// $ad=pdo_fetchall('select am.*,ob.status ostatus from '.tablename('sz_yi_ad_model').' am left join '.tablename('sz_yi_obtain_bonus').' ob on ob.adid = am.id and ob.version = am.version where am.uniacid = :uniacid and am.cate  = :cate and am.now = 1 and am.status = 1',array(':uniacid'=>$_W['uniacid'],':cate'=>$cate));

	if (true) { 	 	

		$timed=pdo_fetchall('select * from '.tablename('sz_yi_ad_model').' where uniacid  = :uniacid and status = 1 and now = 1 and etime < :time ',array(':uniacid' => $_W['uniacid'],':time'=>time()));

		foreach ($timed as $key => $value) {

			pdo_update('sz_yi_ad_model',array('status'=>4,'now'=>0),array('id'=>$value['id'],'uniacid'=>$_W['uniacid']));

			if ($value['residual'] > 0) {

				$smerch=p('bonus')->getMerch($value['uid']);

				switch ($value['putInType']) {

					case '1':

						$str='credit2';

						break;

					default:

					$str='credit3';

						break;

				}

				m('member')->setCredit($smerch['openid'],$str,floatval($value['residual'] * 0.3));

			}

		}

	}

	$jumpad=array();

	$adarr=array();

	switch ($cate) {

		case '1':

			break;

 

		case '2':

			$ad=pdo_fetchall('select id,thumb,mainmap,version,usermax,daymax,national,province,city,area from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and now = 1 and status = 1  and stime < :time and etime > :time order by id desc ',array(':uniacid'=>$_W['uniacid'],':time'=>time()));

			break;	 



		case '3':

			$ad=pdo_fetchall('select id,thumb,mainmap,version,usermax,daymax,national,province,city,area from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and now = 1 and status = 1  and stime < :time and etime > :time ',array(':uniacid'=>$_W['uniacid'],':time'=>time()));

			break;

		default:

			$ad=pdo_fetchall('select id,thumb,version,usermax,daymax,national,province,city,area from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and cate  = :cate and now = 1 and status = 1  and stime < :time and etime > :time ',array(':uniacid'=>$_W['uniacid'],':cate'=>$cate,':time'=>time()));

			break;

	}

	 



	foreach ($ad as $key => $value) {

		$ad[$key]['jump']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and ctime > :stime and ctime < :etime and status = 0 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$value['id'],':stime'=>$stime,':etime'=>$etime,':version'=>$value['version']));

		$ad[$key]['daydraw']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and ctime > :stime and ctime < :etime and status = 1 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$value['id'],':stime'=>$stime,':etime'=>$etime,':version'=>$value['version']));

		$ad[$key]['alldraw']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and status = 1 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$value['id'],':version'=>$value['version']));

		if ($ad[$key]['alldraw'] >= $value['usermax']) {	//最大领取数量

			unset($ad[$key]);	

		}elseif($ad[$key]['daydraw'] >= $value['daymax']){	//每天最大领取数量

			unset($ad[$key]);	

		}elseif(!empty($ad[$key]['jump'])){

			$jumpad[]=$value;

			unset($ad[$key]);

		}elseif($value['national'] == 0){

			if ($value['province'] && $userinfo['province']) {

				if ($value['province'] != $userinfo['province']) {

					$jumpad[]=$value;

					unset($ad[$key]);

				}elseif ($value['city'] && $userinfo['city']) {



					if ($value['city'] != $userinfo['city']) {

						$jumpad[]=$value;

						unset($ad[$key]);

					}elseif ($value['area'] && $userinfo['area']) {

						if ($value['area'] != $userinfo['area']) {

							$jumpad[]=$value;

							unset($ad[$key]); 		

						}else{

							$adarr[]=$value;

							break;

						}

					}else{

						$adarr[]=$value;

						break;

					}

				}else{

					$adarr[]=$value;

					break;

				}

			}

		}else{

			$adarr[]=$value;

			break;

		}

	}

	$thisad=$adarr[0];

	if (empty($thisad)) {

		$thisad=$jumpad[0];

 	} 	 	



	if ($thisad) {

		$thisad['url']=$this->createMobileUrl('barter/ad',array('op'=>'detail','id'=>$thisad['id']));

		$thisad['thumb']=unserialize($thisad['thumb']);

		$thisad['mainmap']=unserialize($thisad['mainmap']);

		foreach ($thisad['thumb'] as $key => $value) {

			$thisad['thumb'][$key]=tomedia($value);

		}

		foreach ($thisad['mainmap'] as $key => $value) {

			$thisad['mainmap'][$key]=tomedia($value);

		}

		show_json(1,array('ad'=>$thisad));

	}

	show_json(0,array());

}

		 	 

$adcate=pdo_fetchall('select * from '.tablename('sz_yi_ad_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));

$cate=pdo_fetchall('select * from '.tablename('sz_yi_member_ad_cate').' where uniacid = :uniacid and openid = :openid and is_del = 1',array('uniacid'=>$_W['uniacid'],':openid'=>$openid));

	foreach ($adcate as $key => $value) {

		foreach ($cate as $k => $v) {

			if ($value['id'] == $v['cate']) {

				unset($adcate[$key]);		

			}		

		}		

	}

include $this -> template('barter/adcate');   

}else if($op == 'report'){

	$ad=m('tools')->getAd($_GPC['id']);

	$log=array(

		'uniacid'=>$_W['uniacid'],

		'openid'=>$openid,

		'adid'   =>$_GPC['id'],

		'version'   =>$ad['version'],

		'uid'    =>$_GPC['uid'],

		'type'   =>$_GPC['report_type'],

		'remark' =>trim($_GPC['remark']),

		'ctime'  =>time()

	);

	$params=array(

		':uniacid'=>$_W['uniacid'],

		':openid'=>$openid,

		':stime'=>$stime,

		':etime'=>$etime

	);		



	$repid=pdo_fetchcolumn('select id from '.tablename('sz_yi_report_log'). ' where uniacid = :uniacid and openid = :oepnid and ctime > :stime and ctime < :etime',$params); 	 	 	

	if ($repid) {

		show_json(0,'举报失败，你今天已经举报过该广告。');	

	}

	pdo_insert('sz_yi_report_log',$log);

	if (pdo_insertid()) {

		show_json(1,'举报成功!');

	}else{

		show_json(0,'举报失败!');

	}

}

