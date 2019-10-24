<?php

global $_W, $_GPC;  
 
$agentlevels = $this->model->getLevels();  
$id1 = intval($_GPC['id']);
$uniacid = $agentlevels[0]['uniacid'];

//经销商证件
$dis_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商表的数据

//分销的
$clevel = pdo_fetch("select l.id,l.uid,dm.agentlevel,dm.nickname,l.thumb,l.commission_level from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_dis_clevel') . " l on l.uid = dm.id" . " where dm.agentlevel= l.commission_level and dm.id = " . $id1 . "  and  dm.uniacid = " . $_W['uniacid'] . "    and  l.uniacid = " . $_W['uniacid'] . "  ORDER BY dm.agenttime asc");


//分红
$blevel = pdo_fetch("select l.id,l.uid,dm.agentlevel,dm.bonuslevel,dm.nickname,l.thumb,l.bonus_level from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_dis_blevel') . " l on l.uid = dm.id" . " where dm.bonuslevel= l.bonus_level and dm.id = " . $id1 . "  and  dm.uniacid = " . $_W['uniacid'] . "    and  l.uniacid = " . $_W['uniacid'] . "  ORDER BY dm.agenttime asc");
/* print_r($blevel);  */


/* //报单
$bmlevel = pdo_fetch("select l.id,l.uid,dm.agentlevel,dm.bonuslevel,dm.nickname,l.thumb,l.bonus_level from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('bd_member') . " bd on bd.openid = dm.openid" . " left join " . tablename('sz_yi_dis_level') . " l on l.uid = dm.id" . " where l.bd_level= bd.level and dm.id = " . $id1 . "  and  dm.uniacid = " . $_W['uniacid'] . "    and  l.uniacid = " . $_W['uniacid'] . "  ORDER BY dm.agenttime asc");

$bd_name = pdo_fetch("select l.id,ml.levelname,dm.nickname,dm.realname from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('bd_member') . " l on l.openid = dm.openid" . " left join " . tablename("bd_level") . " ml on ml.id = l.level" . " where dm.openid= l.openid  and dm.id = " . $id1 . "   and  dm.uniacid = " . $_W['uniacid'] . "   and  l.uniacid = " . $_W['uniacid'] . "   and  ml.uniacid = " . $_W['uniacid'] . "  ORDER BY l.id asc"); */



$level_name = pdo_fetchall("select dm.agentlevel,dm.nickname,l.id,l.levelname from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_commission_level') . " l on l.id = dm.agentlevel" . " where dm.agentlevel= l.id and dm.id = " . $id1 . "  and  dm.uniacid = " . $_W['uniacid'] . "  ORDER BY dm.agenttime desc");

$bonus_name = pdo_fetchall("select dm.bonuslevel,dm.nickname,l.id,l.levelname from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_bonus_level') . " l on l.id = dm.bonuslevel" . " where dm.bonuslevel= l.id   and dm.id = " . $id1 . "   and  dm.uniacid = " . $_W['uniacid'] . "  ORDER BY dm.agenttime desc");
/*  print_r('<pre>'); 
print_r($bonus_name);   */


$operation   = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if ($operation == 'display') {
    ca('dis.agent.view');
    $level     = $this->set['level'];
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 8;
    $params    = array();
    $condition = '';
    if (!empty($_GPC['mid'])) {
        $condition .= ' and dm.id=:mid';
        $params[':mid'] = intval($_GPC['mid']);
    }
    if (!empty($_GPC['realname'])) {
        $_GPC['realname'] = trim($_GPC['realname']);
        $condition .= ' and ( dm.realname like :realname or dm.nickname like :realname or dm.mobile like :realname)';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }
    if ($_GPC['parentid'] == '0') {
        $condition .= ' and dm.agentid=0';
    } else if (!empty($_GPC['parentname'])) {
        $_GPC['parentname'] = trim($_GPC['parentname']);
        $condition .= ' and ( p.mobile like :parentname or p.nickname like :parentname or p.realname like :parentname)';
        $params[':parentname'] = "%{$_GPC['parentname']}%";
    }
    if ($_GPC['followed'] != '') {
        if ($_GPC['followed'] == 2) {
            $condition .= ' and f.follow=0 and dm.uid<>0';
        } else {
            $condition .= ' and f.follow=' . intval($_GPC['followed']);
        }
    }
    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime   = time();
    }
    if (!empty($_GPC['time'])) {
        $starttime = strtotime($_GPC['time']['start']);
        $endtime   = strtotime($_GPC['time']['end']);
        if ($_GPC['searchtime'] == '1') {
            $condition .= " AND dm.agenttime >= :starttime AND dm.agenttime <= :endtime ";
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }
    }
    if (!empty($_GPC['agentlevel'])) {
        $condition .= ' and dm.agentlevel=' . intval($_GPC['agentlevel']);
    }
    if ($_GPC['status'] != '') {
        $condition .= ' and dm.status=' . intval($_GPC['status']);
    }
    if ($_GPC['agentblack'] != '') {
        $condition .= ' and dm.agentblack=' . intval($_GPC['agentblack']);
    }
	
	//显示分销会员
    $sql = "select dm.*,dm.nickname,dm.avatar,l.levelname,p.nickname as parentname,p.avatar as parentavatar from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_member') . " p on p.id = dm.agentid " . " left join " . tablename('sz_yi_commission_level') . " l on l.id = dm.agentlevel" . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid and f.uniacid={$_W['uniacid']}" . " where dm.uniacid = " . $_W['uniacid'] . " and dm.isagent =1  {$condition} ORDER BY dm.agenttime desc";
    if (empty($_GPC['export'])) {
        $sql .= " limit " . ($pindex - 1) * $psize . ',' . $psize;
    }
    $list  = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn("select count(dm.id) from" . tablename('sz_yi_member') . " dm  " . " left join " . tablename('sz_yi_member') . " p on p.id = dm.agentid " . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid" . " where dm.uniacid =" . $_W['uniacid'] . " and dm.isagent =1 {$condition}", $params);
	
    foreach ($list as &$row) {
        $info              = $this->model->getInfo($row['openid'], array(
            'total',
            'pay'
        ));
		//var_dump($info);exit;
        $row['levelcount'] = $info['agentcount'];
        if ($level >= 1) {
            $row['level1'] = $info['level1'];
        }
        if ($level >= 2) {
            $row['level2'] = $info['level2'];
        }
        if ($level >= 3) {
            $row['level3'] = $info['level3'];
        }
		if ($level >= 4) {
            $row['level4'] = $info['level4'];
        }
		if ($level >= 5) {
            $row['level5'] = $info['level5'];
        }
		if ($level >= 6) {
            $row['level6'] = $info['level6'];
        }
		if ($level >= 7) {
            $row['level7'] = $info['level7'];
        }
		if ($level >= 8) {
            $row['level8'] = $info['level8'];
        }
		if ($level >= 9) {
            $row['level9'] = $info['level9'];
        }
		if ($level >= 10) {
            $row['level10'] = $info['level10'];
        }
		if ($level >= 11) {
            $row['level11'] = $info['level11'];
        }
		if ($level >= 12) {
            $row['level12'] = $info['level12'];
        }
		if ($level >= 13) {
            $row['level13'] = $info['level13'];
        }
		if ($level >= 14) {
            $row['level14'] = $info['level14'];
        }
		if ($level >= 15) {
            $row['level15'] = $info['level15'];
        }
        $row['credit1']          = m('member')->getCredit($row['openid'], 'credit1');
        $row['credit2']          = m('member')->getCredit($row['openid'], 'credit2');
        $row['commission_total'] = $info['commission_total'];
        $row['commission_pay']   = $info['commission_pay'];
        $row['followed']         = m('user')->followed($row['openid']);
    }
    unset($row);
    if ($_GPC['export'] == '1') {
        ca('dis.agent.export');
        plog('dis.agent.export', '导出分销商数据');
        foreach ($list as &$row) {
            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
            $row['agentime']   = empty($row['agenttime']) ? '' : date('Y-m-d H:i', $row['agentime']);
            $row['groupname']  = empty($row['groupname']) ? '无分组' : $row['groupname'];
            $row['levelname']  = empty($row['levelname']) ? '普通等级' : $row['levelname'];
            $row['parentname'] = empty($row['parentname']) ? '总店' : "[" . $row['agentid'] . "]" . $row['parentname'];
            $row['statusstr']  = empty($row['status']) ? '' : "通过";
            $row['followstr']  = empty($row['followed']) ? '' : "已关注";
        }
        unset($row);
        m('excel')->export($list, array(
            "title" => "分销商数据-" . date('Y-m-d-H-i', time()),
            "columns" => array(
                array(
                    'title' => 'ID',
                    'field' => 'id',
                    'width' => 12
                ),
                array(
                    'title' => '昵称',
                    'field' => 'nickname',
                    'width' => 12
                ),
                array(
                    'title' => '姓名',
                    'field' => 'realname',
                    'width' => 12
                ),
                array(
                    'title' => '手机号',
                    'field' => 'mobile',
                    'width' => 12
                ),
                array(
                    'title' => '微信号',
                    'field' => 'weixin',
                    'width' => 12
                ),
                array(
                    'title' => '推荐人',
                    'field' => 'parentname',
                    'width' => 12
                ),
                array(
                    'title' => '分销商等级',
                    'field' => 'levelname',
                    'width' => 12
                ),
                array(
                    'title' => '点击数',
                    'field' => 'clickcount',
                    'width' => 12
                ),
                array(
                    'title' => '下线分销商总数',
                    'field' => 'levelcount',
                    'width' => 12
                ),
                array(
                    'title' => '一级下线分销商数',
                    'field' => 'level1',
                    'width' => 12
                ),
                array(
                    'title' => '二级下线分销商数',
                    'field' => 'level2',
                    'width' => 12
                ),
                array(
                    'title' => '三级下线分销商数',
                    'field' => 'level3',
                    'width' => 12
                ),
                array(
                    'title' => '累计佣金',
                    'field' => 'commission_total',
                    'width' => 12
                ),
                array(
                    'title' => '打款佣金',
                    'field' => 'commission_pay',
                    'width' => 12
                ),
                array(
                    'title' => '注册时间',
                    'field' => 'createtime',
                    'width' => 12
                ),
                array(
                    'title' => '成为分销商时间',
                    'field' => 'createtime',
                    'width' => 12
                ),
                array(
                    'title' => '审核状态',
                    'field' => 'createtime',
                    'width' => 12
                ),
                array(
                    'title' => '是否关注',
                    'field' => 'followstr',
                    'width' => 12
                )
            )
        ));
    }
    $pager = pagination($total, $pindex, $psize);
} else if ($operation == 'detail') {
    ca('dis.agent.view');
    $id     = intval($_GPC['id']);
    $member = $this->model->getInfo($id, array('total','pay' ));
	$plugin_coupon = p('coupon');
    if (empty($id)) {
        ca('poster.add');
    } else {
        ca('poster.edit|poster.view');
    }

    /*$item = pdo_fetch("SELECT * FROM " . tablename('sz_yi_dis_poster') . " WHERE  uniacid=:uniacid and uid=:uid limit 1", array( ':uniacid' => $_W['uniacid'],':uid' => $id));
	
    if (!empty($item)) {
        $data = json_decode(str_replace('&quot;', "'", $item['data']), true);
     
    }
		
    if (checksubmit('submit')) {
        load()->model('account');
        
        $data = array(
            'uniacid' => $_W['uniacid'],
            'uid' => $id,
            'title' => trim($_GPC['realname']),
            'type' => $_GPC['mobile'],
            'bg' => $_GPC['photo'],
            'keyword' => trim($_GPC['weixin']),
            // 'bg' => save_media($_GPC['bg']),
            'data' => htmlspecialchars_decode($_GPC['data']),
            'createtime' => time(),
        );	
		// print_r($data);
    
        if (!empty($item['id'])) {
            pdo_update('sz_yi_dis_poster', $data, array('id' => $item['id'], 'uniacid' => $_W['uniacid'] ));
            plog('poster.edit', "修改证件成功 ID: {$id}");
        } else {
            pdo_insert('sz_yi_dis_poster', $data);
            $id = pdo_insertid();
            plog('poster.add', "添加证件 ID: {$id}");
        }
		
		
	
		
		if (empty($item)){
			
			$data11 = json_decode(str_replace('&quot;', "'", $item['data']), true);
			print_r($data11);
			$ychar="1,2,3,4,5,6,7,8,9,0,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
			$list=explode(",",$ychar);
			for($i=0;$i<16;$i++){
			$randnum=rand(0,36); // 10+26;
			$authnum.=$list[$randnum];
			}
			 $dst_path = ATTACHMENT_ROOT.$item['bg'];
			//创建图片的实例
			 $dst = imagecreatefromstring(file_get_contents($dst_path));
			//打上文字
			$font = dirname(__FILE__).'\simsun.ttc';//字体
			list($dst_w, $dst_h) = getimagesize($dst_path);
			$left1 = $dst_w * $data11[0]['left']/318;
			$top1 = $dst_h * $data11[0]['top']/450;
			$left2 = $dst_w * $data11[1]['left']/318;
			$top2 = $dst_h * $data11[1]['top']/460;
			$left3 = $dst_w * $data11[2]['left']/318;
			$top3 = $dst_h * $data11[2]['top']/470;
			
			$black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色
			imagefttext($dst, $data11[0]['size'], 0, $left1, $top1, $black, $font, $data11[0]['name']);
			imagefttext($dst, $data11[1]['size'], 0, $left2, $top2, $black, $font, $data11[1]['name']);
			imagefttext($dst, $data11[2]['size'], 0, $left3, $top3, $black, $font, $data11[2]['name']);
			//输出图片
			list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
			$thume = "dis_photo/". $data['mobile'].$authnum;
			$user_photo = ATTACHMENT_ROOT.$thume;
			
			switch ($dst_type) {
				case 1://GIF
					imagegif($dst,$user_photo.".gif");
					$a = ".gif";
					break;
				case 2://JPG
					imagejpeg($dst,$user_photo.".jpg");
					$a = ".jpg";
					break;
				case 3://PNG
					imagepng($dst,$user_photo.".png");
					$a = ".png";
					break;
				default:
					break;
				  }

		
		}
		
		
		 message('证件设计成功！', $this->createPluginWebUrl('dis', array('op' => 'detail' )), 'success'); 
    }
    $imgroot = $_W['attachurl'];
    if (empty($_W['setting']['remote'])) {
        setting_load('remote');
    }
    if (!empty($_W['setting']['remote']['type'])) {
        $imgroot = $_W['attachurl_remote'];
    }
	if ($plugin_coupon) {
		if (!empty($item['subcouponid'])) {
			$subcoupon = $plugin_coupon->getCoupon($item['subcouponid']);
		}
		if (!empty($item['reccouponid'])) {
			$reccoupon = $plugin_coupon->getCoupon($item['reccouponid']);
		}
	}
	*/
	
	/* 
    if (checksubmit('submit')) {
        ca('dis.agent.edit|dis.agent.check|dis.agent.agentblack');
        $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
       
        pdo_update('sz_yi_member', $data, array(
            'id' => $id,
            'uniacid' => $_W['uniacid']
        ));
        
        message('保存成功!', $this->createPluginWebUrl('dis/agent'), 'success');
    } */
   
} else if ($operation == 'delete') {
    ca('dis.agent.delete');
    $id     = intval($_GPC['id']);
    $member = pdo_fetch("select * from " . tablename('sz_yi_member') . " where uniacid=:uniacid and id=:id limit 1 ", array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    if (empty($member)) {
        message('会员不存在，无法取消分销商资格!', $this->createPluginWebUrl('dis/agent'), 'error');
    }
    $agentcount = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . ' where  uniacid=:uniacid and agentid=:agentid limit 1 ', array(
        ':uniacid' => $_W['uniacid'],
        ':agentid' => $id
    ));
    if ($agentcount > 0) {
        message('此会员有下线存在，无法取消分销商资格!', '', 'error');
    }
    pdo_update('sz_yi_member', array(
        'isagent' => 0,
        'status' => 0
    ), array(
        'id' => $_GPC['id']
    ));
    plog('dis.agent.delete', "取消分销商资格 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
    message('删除成功！', $this->createPluginWebUrl('dis/agent'), 'success');
} else if ($operation == 'agentblack') {
    ca('dis.agent.agentblack');
    $id     = intval($_GPC['id']);
    $member = pdo_fetch("select * from " . tablename('sz_yi_member') . " where uniacid=:uniacid and id=:id limit 1 ", array(
        ':uniacid' => $_W['uniacid'],
        ':id' => $id
    ));
    if (empty($member)) {
        message('会员不存在，无法设置黑名单!', $this->createPluginWebUrl('dis/agent'), 'error');
    }
    $black = intval($_GPC['black']);
    if (!empty($black)) {
        pdo_update('sz_yi_member', array(
            'isagent' => 1,
            'status' => 0,
            'agentblack' => 1
        ), array(
            'id' => $_GPC['id']
        ));
        plog('dis.agent.agentblack', "设置黑名单 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        message('设置黑名单成功！', $this->createPluginWebUrl('dis/agent'), 'success');
    } else {
        pdo_update('sz_yi_member', array(
            'isagent' => 1,
            'status' => 1,
            'agentblack' => 0
        ), array(
            'id' => $_GPC['id']
        ));
        plog('dis.agent.agentblack', "取消黑名单 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        message('取消黑名单成功！', $this->createPluginWebUrl('dis/agent'), 'success');
    }
} else if ($operation == 'user') {
    ca('dis.agent.user');
    $level     = intval($_GPC['level']);
    $agentid   = intval($_GPC['id']);
    $member    = $this->model->getInfo($agentid);
    $total     = $member['agentcount'];
    $level1    = $member['level1'];
    $level2    = $member['level2'];
    $level3    = $member['level3'];
    $level11   = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . ' where isagent=0 and agentid=:agentid and uniacid=:uniacid limit 1', array(
        ':agentid' => $agentid,
        ':uniacid' => $_W['uniacid']
    ));
    $condition = '';
    $params    = array();
    if (empty($level)) {
        $condition = " and ( dm.agentid={$member['id']}";
        if ($level1 > 0) {
            $condition .= " or  dm.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")";
        }
        if ($level2 > 0) {
            $condition .= " or  dm.agentid in( " . implode(',', array_keys($member['level2_agentids'])) . ")";
        }
        $condition .= ' )';
        $hasagent = true;
    } else if ($level == 1) {
        if ($level1 > 0) {
            $condition = " and dm.agentid={$member['id']}";
            $hasagent  = true;
        }
    } else if ($level == 2) {
        if ($level2 > 0) {
            $condition = " and dm.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")";
            $hasagent  = true;
        }
    } else if ($level == 3) {
        if ($level3 > 0) {
            $condition = " and dm.agentid in( " . implode(',', array_keys($member['level2_agentids'])) . ")";
            $hasagent  = true;
        }
    }
    if (!empty($_GPC['mid'])) {
        $condition .= ' and dm.id=:mid';
        $params[':mid'] = intval($_GPC['mid']);
    }
    if (!empty($_GPC['realname'])) {
        $_GPC['realname'] = trim($_GPC['realname']);
        $condition .= ' and ( dm.realname like :realname or dm.nickname like :realname or dm.mobile like :realname)';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }
    if ($_GPC['isagent'] != '') {
        $condition .= ' and dm.isagent=' . intval($_GPC['isagent']);
    }
    if ($_GPC['status'] != '') {
        $condition .= ' and dm.status=' . intval($_GPC['status']);
    }
    if (empty($starttime) || empty($endtime)) {
        $starttime = strtotime('-1 month');
        $endtime   = time();
    }
    if (!empty($_GPC['agentlevel'])) {
        $condition .= ' and dm.agentlevel=' . intval($_GPC['agentlevel']);
    }
    if ($_GPC['parentid'] == '0') {
        $condition .= ' and dm.agentid=0';
    } else if (!empty($_GPC['parentname'])) {
        $_GPC['parentname'] = trim($_GPC['parentname']);
        $condition .= ' and ( p.mobile like :parentname or p.nickname like :parentname or p.realname like :parentname)';
        $params[':parentname'] = "%{$_GPC['parentname']}%";
    }
    if ($_GPC['followed'] != '') {
        if ($_GPC['followed'] == 2) {
            $condition .= ' and f.follow=0 and dm.uid<>0';
        } else {
            $condition .= ' and f.follow=' . intval($_GPC['followed']);
        }
    }
    if ($_GPC['agentblack'] != '') {
        $condition .= ' and dm.agentblack=' . intval($_GPC['agentblack']);
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize  = 20;
    $list   = array();
    if ($hasagent) {
        $total = pdo_fetchcolumn("select count(dm.id) from" . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_member') . " p on p.id = dm.agentid " . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid" . " where dm.uniacid =" . $_W['uniacid'] . "  {$condition}", $params);
        $list  = pdo_fetchall("select dm.*,p.nickname as parentname,p.avatar as parentavatar  from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_member') . " p on p.id = dm.agentid " . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid  and f.uniacid={$_W['uniacid']}" . " where dm.uniacid = " . $_W['uniacid'] . "  {$condition}   ORDER BY dm.agenttime desc limit " . ($pindex - 1) * $psize . ',' . $psize, $params);
        $pager = pagination($total, $pindex, $psize);
        foreach ($list as &$row) {
            $info              = $this->model->getInfo($row['openid'], array(
                'total',
                'pay'
            ));
            $row['levelcount'] = $info['agentcount'];
            if ($this->set['level'] >= 1) {
                $row['level1'] = $info['level1'];
            }
            if ($this->set['level'] >= 2) {
                $row['level2'] = $info['level2'];
            }
            if ($this->set['level'] >= 3) {
                $row['level3'] = $info['level3'];
            }
            $row['credit1']          = m('member')->getCredit($row['openid'], 'credit1');
            $row['credit2']          = m('member')->getCredit($row['openid'], 'credit2');
            $row['commission_total'] = $info['commission_total'];
            $row['commission_pay']   = $info['commission_pay'];
            $row['followed']         = m('user')->followed($row['openid']);
            if ($row['agentid'] == $member['id']) {
                $row['level'] = 1;
            } else if (in_array($row['agentid'], array_keys($member['level1_agentids']))) {
                $row['level'] = 2;
            } else if (in_array($row['agentid'], array_keys($member['level2_agentids']))) {
                $row['level'] = 3;
            }
        }
    }
    unset($row);
    load()->func('tpl');
    include $this->template('agent_user');
    exit;
} else if ($operation == 'query') {
    $kwd      = trim($_GPC['keyword']);
    $wechatid = intval($_GPC['wechatid']);
    if (empty($wechatid)) {
        $wechatid = $_W['uniacid'];
    }
    $params             = array();
    $params[':uniacid'] = $wechatid;
    $condition          = " and uniacid=:uniacid and isagent=1 and status=1";
    if (!empty($kwd)) {
        $condition .= " AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )";
        $params[':keyword'] = "%{$kwd}%";
    }
    if (!empty($_GPC['selfid'])) {
        $condition .= " and id<>" . intval($_GPC['selfid']);
    }
    $ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename('sz_yi_member') . " WHERE 1 {$condition} order by createtime desc", $params);
    include $this->template('query');
    exit;
} else if ($operation == 'check') {
    ca('dis.agent.check');
    $id     = intval($_GPC['id']);
    $member = $this->model->getInfo($id, array(
        'total',
        'pay'
    ));
    if (empty($member)) {
        message('未找到会员信息，无法进行审核', '', 'error');
    }
    if ($member['isagent'] == 1 && $member['status'] == 1) {
        message('此分销商已经审核通过，无需重复审核!', '', 'error');
    }
    $time = time();
    pdo_update('sz_yi_member', array(
        'status' => 1,
        'agenttime' => $time
    ), array(
        'id' => $member['id'],
        'uniacid' => $_W['uniacid']
    ));
    $this->model->sendMessage($member['openid'], array(
        'nickname' => $member['nickname'],
        'agenttime' => $time
    ), TM_COMMISSION_BECOME);
    if (!empty($member['agentid'])) {
        $this->model->upgradeLevelByAgent($member['agentid']);
    }
    plog('dis.agent.check', "审核分销商 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
    message('审核分销商成功!', $this->createPluginWebUrl('dis/agent'), 'success');
}
load()->func('tpl');
include $this->template('agent');

