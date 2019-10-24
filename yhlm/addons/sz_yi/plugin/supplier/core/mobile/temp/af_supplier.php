<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;

$set =$this->model->getset();
$openid = m('user')->getOpenid();
$popenid        = m('user')->islogin();
$openid = $openid?$openid:$popenid;
$member = m('member')->getInfo($openid);
$ca = empty($_GPC['op']) ? '' : $_GPC['op'];

if ($ca=='confirm') {
	if ($_GPC['mid']){
	    $mid=trim($_GPC['mid']);
	    if (strlen($mid)==11){
	        $sql='select * from '.tablename('sz_yi_member').' where mobile=:mobile and uniacid=:uniacid limit 1';
	        $param=array(':mobile' => $mid,':uniacid'=>$_W['uniacid']);
	    }else{
	        $sql='select * from '.tablename('sz_yi_member').' where id=:id and uniacid=:uniacid limit 1';
	        $param=array(':id' => $mid,':uniacid'=>$_W['uniacid']);
	    }
	    $user=pdo_fetch($sql,$param);
	    if(empty($user['nickname'])){
	        $user['nickname']=$user['realname'];
	    }
	    show_json(1,$user);
	}
}


if ($ca == 'update') {      //修改
	$to = $_GPC['to'];
	$data = array();
	// 1是正面，2是反面, 3是营业执照
	if ($to == 1) {
		$data['idimg1'] = $_GPC['url'];
	} elseif ($to == 2) {
		$data['idimg2'] = $_GPC['url'];
	} elseif ($to == 3) {
		$data['permit'] = $_GPC['url'];
	}

	$a = pdo_fetch('select id from'.tablename('sz_yi_supplier_idimages').'where uniacid='.$_W['uniacid'].' and openid='."'$openid'");

	$data['uniacid'] = $_W['uniacid'];
	$data['openid'] = $openid;

	if (empty($a)) {
		// insert
		pdo_insert('sz_yi_supplier_idimages', $data);
	} else {
		// update
		pdo_update('sz_yi_supplier_idimages', $data, array('openid' => $openid));
	}
}
$type=intval($_GPC['merch']);
if ($type == 2){
    $af_supplier = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where openid='{$openid}' and merch = 0 and dealmerchid = 0 and uniacid={$_W['uniacid']} and type = 2");
    $supplier=pdo_fetch('select * from '.tablename('sz_yi_perm_user')." where openid='{$openid}' and merchid =0 and dealmerchid = 0 and uniacid={$_W['uniacid']}");
    // $af_supplier && $supplier && m('tools')->tip('你已经注册过了,不能重复注册!');
}else if($type == 3){ 			 
    $af_supplier = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where openid='{$openid}' and merch = 1 and uniacid={$_W['uniacid']}"); 	 
    $supplier=pdo_fetch('select * from '.tablename('sz_yi_perm_user')." where openid='{$openid}' and merchid > 0 and uniacid={$_W['uniacid']}");
    // $af_supplier && $supplier && m('tools')->tip('你已经注册过了,不能重复注册!');
}else if ($type == 5){
    $af_supplier = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where openid='{$openid}' and dealmerchid = 1 and uniacid={$_W['uniacid']}");
    $supplier=pdo_fetch('select * from '.tablename('sz_yi_perm_user')." where openid='{$openid}' and dealmerchid > 0 and uniacid={$_W['uniacid']}"); 	 	 	
    // $af_supplier && $supplier && m('tools')->tip('你已经注册过了,不能重复注册!');
}else if ($type == 6){
    $af_supplier = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where openid='{$openid}' and member = 1 and uniacid={$_W['uniacid']}"); 
    $supplier=pdo_fetch('select * from '.tablename('sz_yi_perm_user')." where openid='{$openid}' and muserid > 0 and uniacid={$_W['uniacid']}");
    
    if ($af_supplier) {	 				 	  		 	 	 	

    	if ($supplier) {
    		m('tools')->tip('你已经注册过了,不能重复注册!');
    	}else{	 	 	
    		m('activity')->auditSuppleir(2,$af_supplier['id']); 		 	
    	}
    }
}

$diyform_plugin = p('diyform');  
$order_formInfo = false; 
if ($diyform_plugin) {
	$diyform_set = $diyform_plugin->getSet();
	if (!empty($diyform_set['supplier_diyform_open'])) {
		$supplierdiyformid = intval($diyform_set['supplier_diyform']);
		if (!empty($supplierdiyformid)) {
			$supplier_formInfo = $diyform_plugin->getDiyformInfo($supplierdiyformid);
			$fields = $supplier_formInfo['fields'];
			$f_data = $diyform_plugin->getLastOrderData($supplierdiyformid, $member);
		}
	}
}
// 查询商品分类 (行业类别)
$sql = 'select name from'.tablename('sz_yi_category').'where uniacid = :uniacid and parentid = 0 and enabled = 1';
$category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
$protocol=pdo_fetch('select sets from '.tablename('sz_yi_sysset').' where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
$protocol=unserialize($protocol['sets']);
$protocol=($protocol['shop']['protocol']);
if ($_W['isajax']) {
	if ($ca=='submit') { 

		$memberdata = array(
			'realname'    => $_GPC['memberdata']['realname'], 
			'mobile'      => $_GPC['memberdata']['mobile'], 
			'weixin'      => $_GPC['memberdata']['weixin'],
			'qq'          => $_GPC['memberdata']['qq'],
			'productname' => $_GPC['memberdata']['productname'],
			'password'    => $_GPC['memberdata']['password'],
			'province'    => $_GPC['memberdata']['provance'],		//省
			'city'        => $_GPC['memberdata']['city'],			//市
			'district'    => $_GPC['memberdata']['area'],			//区
			'openid'      => $openid,
			'uniacid'     => $_W['uniacid'], 
			'type'        => $_GPC['memberdata']['applytype']       //本地商家
		);  
		// show_json(0,$memberdata);	 		 	
		if ($_GPC['memberdata']['uid']){       
            $sql='select openid from '.tablename('sz_yi_member').' where id=:id and uniacid=:uniacid';
            $param=array(':id' => $_GPC['mid'],':uniacid'=>$_W['uniacid']);     //推荐人id
            $userOpenid=pdo_fetchcolumn($sql,$param);
            $memberdata['recommendopenid']=$userOpenid;   //全国  2
        }    
		    
        //type=1供应商 2全国商家 3本地商家 4本地+全国 5 易货商家 
        if(intval($_GPC['memberdata']['applytype'])==3){      //type=3 本地商家
            $_GPC['memberdata']['username']=$_GPC['memberdata']['mobile'];
		    $memberdata['merch']=1; 
		    $memberdata['type']=0; 
		    $memberdata['username']='2-'.trim($_GPC['memberdata']['username']); 
		}else if(intval($_GPC['memberdata']['applytype'])==4){ //本地+全国
            $memberdata['merch']=1;
            $memberdata['type']=2;
            $memberdata['username']='2-'.trim($_GPC['memberdata']['username']);
        }else if(intval($_GPC['memberdata']['applytype']==5)){ //易货
            $memberdata['dealmerchid']=1;
            $_GPC['memberdata']['username']=$_GPC['memberdata']['mobile'];
            $memberdata['username']='1-'.trim($_GPC['memberdata']['username']);
        }else if(intval($_GPC['memberdata']['applytype']==6)){ //用户后台
            $memberdata['member']=1;	 
            $_GPC['memberdata']['username']=$_GPC['memberdata']['mobile'];
            $memberdata['username']='6-'.trim($_GPC['memberdata']['username']);
        }else{  	 				
        	$memberdata['username']='8-'.trim($_GPC['memberdata']['mobile']);
        }

		$result = pdo_fetch('select * from ' . tablename('users') . ' where username=\'' . $memberdata['username'] . '\'');

        if (!empty($result)) {

			show_json(2);
		}

		if (empty($af_supplier)) {
			$res = pdo_insert('sz_yi_af_supplier', $memberdata);
			$res && show_json(1);
		} else {

			$memberdata['status'] = 0;
			$memberdata['account'] = '';
			unset($memberdata['username']); // 不允许修改
            if ($type == 2){
                $res = pdo_update('sz_yi_af_supplier', $memberdata, array('openid' => $openid,'dealmerchid'=>0,'merch'=>0,'member'=>0));
            }else if($type == 3){ 
                $res = pdo_update('sz_yi_af_supplier', $memberdata, array('openid' => $openid,'merch'=>1));
            }else if ($type == 5){
                $res = pdo_update('sz_yi_af_supplier', $memberdata, array('openid' => $openid,'dealmerchid'=>1));
            }else if ($type == 6){
                $res = pdo_update('sz_yi_af_supplier', $memberdata, array('openid' => $openid,'member'=>1));
            }

			$res && show_json(1);
		}
	}
	show_json(1, array('member' => $member));
}
if ($ca == 'protocol'){
    $detail=null;
    foreach ($protocol as  $k =>$v){
        if ($_GPC['num']==$k){
            $v['content']=html_entity_decode($v['content']);
            $detail=$v;
            include $this->template('protocol');
            exit;
        }
    }
}

include $this->template('af_supplier');