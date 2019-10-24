<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user')->isLogin();
$uniacid = $_W['uniacid'];
$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where dealmerchid > 0 and uniacid = :uniacid and openid = :openid',array(':uniacid'=>$uniacid,':openid'=>$openid));

// End 余额提现手续费
if ($operation == 'exchange' || $operation == 'exchangeEdit'){
    if($operation == 'exchange'){	//异步获取
    	if($_W['isajax']){
    		$pindex=max(1,intval($_GPC['page']));
    		$psize=10;

    		$sql='select * from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid and merch_uid = :uid';
    		$sql.=' limit '.($pindex - 1) * $psize .','.$psize;
    		$list=pdo_fetchall($sql,array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
//  		show_json(0,$uid);
    		if($list){
    			show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize));
    		}
    		show_json(0,'你没有更多的兑换点');
    	}
    }
    if($operation == 'exchangeEdit'){
    	$id=$_GPC['id'];
    	if($id){
			$addrInfo=pdo_fetch('select * from '.tablename('sz_yi_exchange_address').' where uniacid = :uniacid and id=:id order by id asc ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));    		

				$addrInfo['exchangeDate'] =explode(',', $addrInfo['exchangeDate']);				

    	}

    }
    if($_GPC['ac'] == 'exdosave'){
    	$id = intval($_GPC['id']);
		
		$data = array(
			'merch_uid'=>$uid,
				
			'title'=>$_GPC['title'],

			'address'=>$_GPC['address'],
	
			'mobile'=>$_GPC['mobile'],

			'uniacid'=>$_W['uniacid'],
			//经度
			'lng'=>$_GPC['lng'],
			//纬度
			'lat'=>$_GPC['lat'],

			'exchangeTime'=>$_GPC['exchangeTime'],

			'exchangeDate'=>implode(',', $_GPC['exchangeDate']),

			'status'=>intval($_GPC['status'])

		);

//		show_json(0,$data);
		if (!$id) {

			pdo_insert('sz_yi_exchange_address',$data);

			$insid = pdo_insertid();

			if (empty($insid)) {
				
				show_json(0,'添加失败');  exit; 
			
			} else {
				
				show_json(1,$this->createMobileUrl('member/points',array('op'=>'exchange'))); exit; 
			
			}

		}	else {
			
			$res = pdo_update('sz_yi_exchange_address', $data, array('id'=>$id));
			// show_json($res);
			if (!empty($res)) {
				
				show_json(1,$this->createMobileUrl('member/points',array('op'=>'exchange'))); exit; 
	 
			} else {
				
				show_json(0,'修改失败'); exit; 
			}
		}
    }
    
	include $this -> template('member/exchange');
	exit;
}else if ($operation == 'admin'){
	
	$params=[
		':uniacid'=>$uniacid,
		':openid'=>$openid
	];
	$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and dealmerchid > 0',$params);
	$list=pdo_fetchall('select s.id,s.mobile,s.status,s.salername,ea.title storename,m.realname  from '.tablename('sz_yi_exchange_address').' ea left join '.tablename('sz_yi_saler').' s on s.storeid = ea.id left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.isexchange = 1 and ea.uniacid = :uniacid and ea.merch_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
	
}else if($operation == 'detail'){
	$id=$_GPC['id'];
	if($id){
		$item = pdo_fetch("SELECT * FROM " . tablename('sz_yi_saler') . " WHERE id =:id and isexchange=1 and uniacid=:uniacid limit 1", array( 
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    	));
	    if (!empty($item)) {
	        $saler = m('member')->getMember($item['openid']);
	        $store = pdo_fetch("SELECT * FROM " . tablename('sz_yi_exchange_address') . " WHERE id =:id and uniacid=:uniacid limit 1", array( 
	            ':uniacid' => $_W['uniacid'],
	            ':id' => $item['storeid']
	        ));
	    }
	} 
	
	if($_GPC['ac'] == 'dosave'){
		$msg='添加成功!';
        $data = array(
            'uniacid' => $_W['uniacid'], 
            'storeid' => intval($_GPC['storeid']),
            'openid' => trim($_GPC['openid']),
            'status' => intval($_GPC['status']),
            'isexchange' => 1,
            'salername' => trim($_GPC['salername']),
            'mobile' => trim($_GPC['mobile']),
        );
		
        $m= m('member')->getMember($data['openid']);
        if (!empty($id)) {
        	$msg='修改成功!';
            pdo_update('sz_yi_saler', $data, array(
                'id' => $id,
                'uniacid' => $_W['uniacid']
            ));
            plog('verify.saler.edit', "编辑核销员 ID: {$id} <br/>核销员信息: ID: {$m['id']} / {$m['openid']}/{$m['nickname']}/{$m['realname']}/{$m['mobile']} ");
        } else {
            $scount = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('sz_yi_saler') . '  WHERE isexchange = 1 and openid =:openid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $data['openid']));
            if ($scount > 0) {
            	$msg='此会员已经成为核销员,没法重复添加!';
            	$TAG=<<<EOT
				<script>
					alert("{$msg}");
					location.href="{$this->createMobileUrl("member/points",array("op"=>"admin"))}";
					</script>
EOT;
		die($TAG);
//          	echo '<script>alert("此会员已经成为核销员,没法重复添加");location.href=".$this->createMobileUrl("member/points",array("op"=>"admin"))."'</script>'
            }
            pdo_insert('sz_yi_saler', $data);
            $id = pdo_insertid();
            plog('verify.saler.add', "添加核销员 ID: {$id}  <br/>核销员信息: ID: {$m['id']} / {$m['openid']}/{$m['nickname']}/{$m['realname']}/{$m['mobile']} ");
        }
		
$TAG=<<<EOT
				<script>
					alert("{$msg}");
					location.href="{$this->createMobileUrl("member/points",array("op"=>"admin"))}";
					</script>
EOT;
		die($TAG);


	}
}else if($operation == 'search'){
	
	$kwd = trim($_GPC['keyword']);
	$params = array();
	$params[':uniacid'] = $_W['uniacid'];
	$condition = " and uniacid=:uniacid";
	if (!empty($kwd)) {
		$condition .= " AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )";
		$params[':keyword'] = "%{$kwd}%";
	}
	$ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename('sz_yi_member') . " WHERE 1 {$condition} order by createtime desc", $params);
	show_json(1,array('list'=>$ds));
}else if($operation == 'query') {                //查询兑换点
    $kwd                = trim($_GPC['keyword']);
    $uid                = trim($_GPC['uid']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];       //只能选择自己的兑换点
		$condition='uniacid = :uniacid ';
    if (!empty($kwd)) {
        $condition .= " AND `title` LIKE :keyword "; 
        $condition .= " or `address` LIKE :keyword "; 
        $condition .= " or `mobile` LIKE :keyword ";
        $params[':keyword'] = "%{$kwd}%"; 
    }

    $ds = pdo_fetchall('SELECT id,title,address,mobile,exchangeDate,exchangeTime FROM ' . tablename('sz_yi_exchange_address') . " WHERE {$condition} order by id asc", $params);
	show_json(1,array('list'=>$ds));
}else if ($operation == 'del'){
	$id=intval($_GPC['id']);
	if(intval($_GPC['type']) == 1 && $id){
		$result = pdo_delete('sz_yi_exchange_address', array('id' => $id));
		
		$TAG=<<<EOT
				<script>
					alert("删除成功");
					location.href="{$this->createMobileUrl("member/points",array("op"=>"exchange"))}";
					</script>
EOT;
		die($TAG);
		
		
	}else if(intval($_GPC['type']) == 2 && $id){
		$result = pdo_delete('sz_yi_saler', array('id' => $id));
		
		$TAG=<<<EOT
				<script>
					alert("删除成功");
					location.href="{$this->createMobileUrl("member/points",array("op"=>"admin"))}";
					</script>
EOT;
		die($TAG);
	}
	
	$TAG=<<<EOT
				<script>
					alert("非法参数");
					history.back();
					</script>
EOT;
		die($TAG);

}

include $this -> template('member/admin');
