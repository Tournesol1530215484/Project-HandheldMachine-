<?php



global $_W, $_GPC;



$op = empty($_GPC['op']) ? 'display': $_GPC['op'];



$popenid        = m('user')->islogin();

$openid = m('user')->getOpenid();

$openid = $openid?$openid:$popenid;



if($op == 'edit'){

	if ($_W['isajax']) {

		$data = array();

		$data['realname']    = $_GPC['realname'];

		$data['mobile']      = $_GPC['mobile'];

		$data['banknumber']  = $_GPC['banknumber'];

		$data['accountbank'] = $_GPC['accountbank'];

		$data['accountname'] = $_GPC['accountname'];

 

		$condition='';

		if($_GPC['role']==2 ){ 

	        $where['dealmerchid']= ' and dealmerchid = 0 and merchid = 0 ';



	    }else if($_GPC['role']==3){

	        $where  = ' and merchid > 0 '; 



	    }else if($_GPC['role']==5 ){ 

	 

	        $where  = ' and dealmerchid > 0 ';

	    }



	    $id=pdo_fetchcolumn('select id from '.tablename('sz_yi_perm_user').' where openid = :openid and uniacid = :uniacid '.$condition,array(':openid'=>$openid,':uniacid'=>$_W['uniacid']));



		$where=array(  

			'openid' => $openid,

			'uniacid'=>$_W['uniacid'],

			'id'	 =>$id

		); 



		$result = pdo_update('sz_yi_perm_user', $data, $where);

		if (!empty($result)) {

			show_json(1);

		} else {

			show_json(0, '修改失败！请重试');

		}

	}



	$sql = 'select username,realname,mobile,banknumber,accountbank,accountname from'.tablename('sz_yi_perm_user').'where openid = :openid';

	$info = pdo_fetch($sql, array(':openid' => $openid));

	if (empty($info)) {

		header('Location:'.$this->createPluginMobileUrl('suppliermenu/index'));

	}

}elseif ($op == 'postinfo') {
		

		header('Location:'.$this->createPluginMobileUrl('suppliermenu/poster'));


} elseif ($op == 'editpwd') { 



	// 查询uid

	if ($_W['isajax']) {

		// 验证为不能空



		$_GPC['oldpwd']  || show_json(0, '旧密码不能为空!');

		$_GPC['newpwd1'] || show_json(0, '新密码不能为空!');

		$_GPC['newpwd2'] || show_json(0, '新密码不能为空!');

 

		$condition='';

		if($_GPC['role']==2 ){ 

	        $condition  = ' dealmerchid = 0 and merchid = 0 ';



	    }else if($_GPC['role']==3){

	        $condition  = ' merchid > 0 '; 



	    }else if($_GPC['role']==5 ){ 

	 

	        $condition  = ' dealmerchid > 0 '; 

	    } 



		$sql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid and '.$condition; 

		$info = pdo_fetch($sql, array(':openid' => $openid));



		$sql  = 'SELECT username, password, salt, groupid, starttime, endtime FROM ' . tablename('users') . ' WHERE `uid` = :uid';

		$user = pdo_fetch($sql, array(':uid' => $info['uid']));

		$user || show_json(0, '抱歉，请重新登录！');



		$password_old = user_hash($_GPC['oldpwd'], $user['salt']);			//加salt加密



		$user['password'] != $password_old && show_json(0, '旧密码错误！');



		$_GPC['newpwd1'] != $_GPC['newpwd2'] && show_json(0, '两次密码不一致！');

		$members = array(

			'password' => user_hash($_GPC['newpwd1'], $user['salt'])

		);  

		$result = pdo_update('users', $members, array('uid' => $info['uid']));

		if (empty($result)) { 

			show_json(0, '网络不给力，请重试！');

		} else {

			show_json(1);

		}

	}

} elseif ($op == 'editstore') {

	$_GPC['role']=$_GPC['merch'];

    if($_GPC['role']==2 ){

        $isql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid and uniacid = :uniacid and dealmerchid=0 and merchid=0 limit 1';

        $uid = pdo_fetchcolumn($isql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));

        $sdsql = 'select * from '.tablename('sz_yi_store_data').' where storeid = :uid and uniacid = :uniacid';

        $storeData = pdo_fetch($sdsql, array(':uid' => $uid, ':uniacid' => $_W['uniacid']));



    }else if($_GPC['role']==3){

        $isql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid and uniacid = :uniacid and merchid>0 limit 1';

        $uid = pdo_fetch($isql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));



        $sdsql = 'select * from '.tablename('sz_yi_merch_user').' where uid = :uid and uniacid = :uniacid';

        $storeData = pdo_fetch($sdsql, array(':uid' => $uid['uid'], ':uniacid' => $_W['uniacid']));

//        print_r($storeData);exit;

//        $storeData['merchname'] =$storeData['storename'];

        $storeData['description']=$storeData['details'];

        $storeData['signboard']=$storeData['img'];

        $storeData['storename'] = $storeData['merchname'];



    }else if($_GPC['role']==5 ){ 

 

        $isql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid and uniacid = :uniacid and dealmerchid>0 limit 1';

        $uid = pdo_fetchcolumn($isql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));

        $sdsql = 'select * from '.tablename('sz_yi_dealmerch_user').' where uid = :uid and uniacid = :uniacid';

        $storeData = pdo_fetch($sdsql, array(':uid' => $uid, ':uniacid' => $_W['uniacid']));

        $storeData['description']=$storeData['details'];

        $storeData['signboard']=$storeData['img'];

        $storeData['storename'] = $storeData['merchname'];

    }

//    print_r($storeData);

//	$isql  = 'select uid from'.tablename('sz_yi_perm_user').'where openid = :openid and uniacid = :uniacid limit 1';

//	$uid = pdo_fetchcolumn($isql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));

//	$sdsql = 'select * from '.tablename('sz_yi_store_data').' where storeid = :uid and uniacid = :uniacid';

//	$storeData = pdo_fetch($sdsql, array(':uid' => $uid, ':uniacid' => $_W['uniacid']));

	// var_dump($storeData);

	if ($_W['isajax']) {

		$logo      = $_GPC['logo'];

		$signboard = $_GPC['signboard'];

		$description = $_GPC['description'];

		$storename = $_GPC['storename'];

        if($_GPC['role']==2){

            $sql = 'select uid,dealmerchid,merchid from'.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and dealmerchid=0 and merchid=0';

            $merch = pdo_fetch($sql, array(':openid' => $openid,':uniacid'=>$_W['uniacid']));



            $data = array('logo' => $logo, 'signboard' => $signboard, 'description' => $description, 'storename' => $storename);

            $res = pdo_update('sz_yi_store_data', $data, array('storeid' => $merch['uid']));



        } 



        if($_GPC['role']==3){

            

            $sql = 'select uid,dealmerchid,merchid from'.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and merchid>0';

            $merch = pdo_fetch($sql, array(':openid' => $openid,':uniacid'=>$_W['uniacid']));



            $data = array('logo' => $logo, 'img' => $signboard, 'details' => $description, 'merchname' => $storename);

            $res = pdo_update('sz_yi_merch_user', $data, array('uid' => $merch['uid'],'uniacid'=>$_W['uniacid']));

//            print_r($res);exit;  

        }



        if($_GPC['role']==5){

            $sql = 'select uid,dealmerchid,merchid from'.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and dealmerchid>0';

            $merch = pdo_fetch($sql, array(':openid' => $openid,':uniacid'=>$_W['uniacid']));



            $data = array('logo' => $logo, 'img' => $signboard, 'details' => $description, 'merchname' => $storename);

            $res = pdo_update('sz_yi_dealmerch_user', $data, array('uniacid'=>$_W['uniacid'],'uid' => $merch['uid']));

        }



		$sql = 'select uid,dealmerchid,merchid from'.tablename('sz_yi_perm_user').' where openid = :openid';

		$merchs = pdo_fetchall($sql, array(':openid' => $openid)); 



//        $storeid=$merch['id'];

//        $exist = pdo_fetch("select id from ".tablename('sz_yi_store_data').'where storeid=:storeid',array(':storeid'=>$storeid));





//            $data = array('logo' => $logo, 'signboard' => $signboard, 'description' => $description, 'storename' => $storename);

//

//            if ($_GPC['role']==2) {

//                $exist = pdo_fetch("select id from " . tablename('sz_yi_store_data') . 'where storeid=:storeid', array(':storeid' => $storeid));

//

//                if (!empty($exist)) {

//                    $res = pdo_update('sz_yi_store_data', $data, array('storeid' => $storeid));

//                } else {

//                    $data['uniacid'] = $_W['uniacid'];

//                    $data['storeid'] = $storeid;

//                    pdo_insert('sz_yi_store_data', $data);

//                    $res = pdo_insertid();

//                }

//                //修改易货店铺

//            } elseif ($_GPC['role']==3) {

//                $data = array('logo' => $logo, 'img' => $signboard, 'details' => $description, 'merchname' => $storename);

////                print_r($data);exit;

//

//                $exist = pdo_fetch("select id from " . tablename('sz_yi_merch_user') . 'where uid=:uid', array(':uid' => $storeid));

//

//                if (!empty($exist)) {

//

//                    $res = pdo_update('sz_yi_merch_user', $data, array('uid' => $storeid));

//

//                } else {

//                    $data['uniacid'] = $_W['uniacid'];

//                    $data['uid'] = $storeid;

//                    pdo_insert('sz_yi_merch_user', $data);

//                    $res = pdo_insertid();

//                }

////            $data = array('logo' => $logo, 'img' => $signboard, 'details' => $description, 'merchname' => $storename);

////

////            $res = pdo_update('sz_yi_merch_user', $data, array('uid' => $storeid));

////

////            exit;

//                //修改本地店铺

//            } else {

//                $exist = pdo_fetch("select id from " . tablename('sz_yi_store_data') . 'where storeid=:storeid', array(':storeid' => $storeid));

//

//                if (!empty($exist)) {

//                    $res = pdo_update('sz_yi_store_data', $data, array('storeid' => $storeid));

//                } else {

//                    $data['uniacid'] = $_W['uniacid'];

//                    $data['storeid'] = $storeid;

//                    pdo_insert('sz_yi_store_data', $data);

//                    $res = pdo_insertid();

//                }

//            }



//        if(!empty($exist)){

//             $res = pdo_update('sz_yi_store_data', $data, array('storeid' => $storeid));

//        }else{

//            $data['uniacid']=$_W['uniacid'];

//            $data['storeid']=$storeid;

//            pdo_insert('sz_yi_store_data',$data);

//            $res=pdo_insertid();

//        }



		if (!empty($res)) {

			show_json(1, '保存成功！');

		} else {

			show_json(0, '没有任何改变！');

		}

	}

}



include $this->template('info');

