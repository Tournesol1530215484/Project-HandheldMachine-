<?php

//多级分销商城 QQ:1084070868

global $_W, $_GPC;

$popenid        = m('user')->islogin();

$openid = m('user')->getOpenid();

$openid = $openid?$openid:$popenid;

$uniacid=$_W['uniacid'];

$op=!empty($_GPC['op'])?$_GPC['op']:'display';

$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where dealmerchid > 0 and uniacid = :uniacid and openid = :openid',array(':uniacid'=>$uniacid,':openid'=>$openid));

if($op == 'display'){

	$Info=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$uniacid,':uid'=>$uid));

	$Info['BusinessLicensePic']=unserialize($Info['BusinessLicensePic']);

	$Info['ImageDetailFile']=unserialize($Info['ImageDetailFile']);

	$Info['cate']=pdo_fetchcolumn('select title from '.tablename('sz_yi_merch_type').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$uniacid,':id'=>$Info['typeid']));

	include $this->template('merchinfo');

}else if($op == 'merched'){

	$cate=pdo_fetchall('select * from '.tablename('sz_yi_merch_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$uniacid));

//	var_dump($cate); 	 	 		 

	if($_W['isajax']){

//		show_json(0,$uid);

		$info=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid=:uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));

		if($info){

			$info['licenseoverdue']=date('Y-m-d H:i:s',$info['licenseoverdue']);

			$info['BusinessLicensePic']=unserialize($info['BusinessLicensePic']);

			$info['ImageDetailFile']=unserialize($info['ImageDetailFile']);

			if($info['BusinessLicensePic']){

				foreach($info['BusinessLicensePic'] as $k =>$v){

					$info['BusinessLicensePic'][$k]=tomedia($v);

				}

			}

			if($info['ImageDetailFile']){

				foreach($info['ImageDetailFile'] as $k =>$v){

					$info['ImageDetailFile'][$k]=tomedia($v);

				}

			}			  

			show_json(1,$info);

		}

		show_json(0,'没有找到该易货商家');

	}



	include $this->template('merched');

}else if($op == 'post'){

	$data = array(

            'uniacid' => $_W['uniacid'],

            'merchname'=>$_GPC['merchname'],

            'uid'     =>$uid,

            'lat'=>$_GPC['lat'],

            'lng'=>$_GPC['lng'],

            'mobile'=>$_GPC['mobile'],

            'BusinessLicensePic'=>serialize($_GPC['post1']),

            'ImageDetailFile'=>serialize($_GPC['post2']),

            'address'=>$_GPC['address'],

            'typeid'=>$_GPC['typeid'],

            'merchsite'=>$_GPC['merchsite'],

            'operat'=>trim($_GPC['operat']),

            'operatmobile'=>trim($_GPC['operatmobile']),

            'licenseoverdue'=>strtotime($_GPC['licenseoverdue']),

            'businessLicenseNo'=>trim($_GPC['businessLicenseNo']),

            'details'=>$_GPC['details'],

            'special'=>$_GPC['special'],

            'contact'=>trim($_GPC['contact']),

            'province'=>trim($_GPC['province']),

            'city'=>trim($_GPC['city']),

            'district'=>trim($_GPC['district']),

        );



        $log=array(

            'uniacid'=>$_W['uniacid'], 

            'uid'=>$uid,

            'openid'=>$openid,

            'sub_time'=>time(),

            'status' => 0               //0审核中 1成功 2失败

        );

        

        $virtual=pdo_fetch('select id,virtualid from '.tablename('sz_yi_virtual_log').' where uniacid = :uniacid and uid = :uid and status = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));

        if (!empty($virtual)){

            pdo_update('sz_yi_virtual_dealmerch_user',$data,array('id'=>$virtual['virtualid']));

            pdo_update('sz_yi_virtual_log',$log,array('id'=>$virtual['id']));

            show_json(1,'修改成功');

        }else{

            //virtual_dealmerch_user表中只会出现一条该用户未审核的记录

            $id=pdo_fetchcolumn('select id from '.tablename('sz_yi_virtual_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));

            if ($id){ 

                pdo_update('sz_yi_virtual_dealmerch_user',$data,array('id'=>$id)); 

            }else{ 

                pdo_insert('sz_yi_virtual_dealmerch_user',$data); 

                $id=pdo_insertid(); 

            }

            $log['virtualid']=$id;

            pdo_insert('sz_yi_virtual_log',$log);

            show_json(1,'修改成功2');

        }

} 		 		 