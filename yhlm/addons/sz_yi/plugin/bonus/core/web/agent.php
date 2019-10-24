<?php
 global $_W, $_GPC;
$this -> model -> calculate(76);
$agentlevels = $this -> model -> getLevels();
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if ($operation == 'display'){
    ca('bonus.agent.view');
    $level = $this -> set['level'];
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array();
    $condition = '';
    if (!empty($_GPC['mid'])){
        $condition .= ' and dm.id=:mid';
        $params[':mid'] = intval($_GPC['mid']);
    }
    if (!empty($_GPC['realname'])){
        $_GPC['realname'] = trim($_GPC['realname']);
        $condition .= ' and ( dm.realname like :realname or dm.nickname like :realname or dm.mobile like :realname)';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }
    if ($_GPC['parentid'] == '0'){
        $condition .= ' and dm.agentid=0';
    }else if (!empty($_GPC['parentname'])){
        $_GPC['parentname'] = trim($_GPC['parentname']);
        $condition .= ' and ( p.mobile like :parentname or p.nickname like :parentname or p.realname like :parentname)';
        $params[':parentname'] = "%{$_GPC['parentname']}%";
    }
    if ($_GPC['followed'] != ''){
        if ($_GPC['followed'] == 2){
            $condition .= ' and f.follow=0 and dm.uid<>0';
        }else{
            $condition .= ' and f.follow=' . intval($_GPC['followed']);
        }
    }
    if($_GPC['bonus_area'] != ''){
        if($_GPC['bonus_area'] == 1){
            $condition .= ' and dm.bonus_area=1';
        }else if($_GPC['bonus_area'] == 2){
            $condition .= ' and dm.bonus_area=2';
        }else if($_GPC['bonus_area'] == 3){
            $condition .= ' and dm.bonus_area=3';
        }
    }
    if($_GPC['reside']['province'] != ""){
        $condition .= ' and dm.bonus_province=\'' . $_GPC['reside']['province'] . '\'';
    }
    if($_GPC['reside']['city'] != ""){
        $condition .= 'and dm.bonus_city=\'' . $_GPC['reside']['city'] . '\'';
    }
    if($_GPC['reside']['district'] != ""){
        $condition .= 'and dm.bonus_district=\'' . $_GPC['reside']['district'] . '\'';
    }
    if (empty($starttime) || empty($endtime)){
        $starttime = strtotime('-1 month');
        $endtime = time();
    }
    if (!empty($_GPC['time'])){
        $starttime = strtotime($_GPC['time']['start']);
        $endtime = strtotime($_GPC['time']['end']);
        if ($_GPC['searchtime'] == '1'){
            $condition .= ' AND dm.agenttime >= :starttime AND dm.agenttime <= :endtime ';
            $params[':starttime'] = $starttime;
            $params[':endtime'] = $endtime;
        }
    }
    if (!empty($_GPC['agentlevel'])){
        $condition .= ' and dm.bonuslevel=' . intval($_GPC['agentlevel']);
    }
    if ($_GPC['status'] != ''){
        $condition .= ' and dm.status=' . intval($_GPC['status']);
    }
    if ($_GPC['agentblack'] != ''){
        $condition .= ' and dm.agentblack=' . intval($_GPC['agentblack']);
    }
    $sql = 'select dm.*,dm.nickname,dm.avatar,l.levelname,p.nickname as parentname,p.avatar as parentavatar from ' . tablename('sz_yi_member') . ' dm ' . ' left join ' . tablename('sz_yi_member') . ' p on p.id = dm.agentid ' . ' left join ' . tablename('sz_yi_bonus_level') . ' l on l.id = dm.bonuslevel' . ' left join ' . tablename('mc_mapping_fans') . "f on f.openid=dm.openid and f.uniacid={$_W['uniacid']}" . ' where dm.uniacid = ' . $_W['uniacid'] . " and dm.isagent =1  {$condition} ORDER BY dm.agenttime desc";
    if (empty($_GPC['export'])){
        $sql .= ' limit ' . ($pindex - 1) * $psize . ',' . $psize;
    }
    $list = pdo_fetchall($sql, $params);
    $total = pdo_fetchcolumn('select count(dm.id) from' . tablename('sz_yi_member') . ' dm  ' . ' left join ' . tablename('sz_yi_member') . ' p on p.id = dm.agentid ' . ' left join ' . tablename('mc_mapping_fans') . 'f on f.openid=dm.openid' . ' where dm.uniacid =' . $_W['uniacid'] . " and dm.isagent =1 {$condition}", $params);
    foreach ($list as & $row){
        $info = p('commission') -> getInfo($row['openid'], array('total', 'pay'));
        $row['levelcount'] = $info['agentcount'];
        if ($level >= 1){
            $row['level1'] = $info['level1'];
        }
        if ($level >= 2){
            $row['level2'] = $info['level2'];
        }
        if ($level >= 3){
            $row['level3'] = $info['level3'];
        }
        $row['credit1'] = m('member') -> getCredit($row['openid'], 'credit1');
        $row['credit2'] = m('member') -> getCredit($row['openid'], 'credit2');
        $row['commission_total'] = $info['commission_total'];
        $row['commission_pay'] = $info['commission_pay'];
        $row['followed'] = m('user') -> followed($row['openid']);
    }
    unset($row);
    if ($_GPC['export'] == '1'){
        ca('commission.agent.export');
        plog('commission.agent.export', '导出代理商数据');
        foreach ($list as & $row){
            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
            $row['agentime'] = empty($row['agenttime']) ? '' : date('Y-m-d H:i', $row['agentime']);
            $row['groupname'] = empty($row['groupname']) ? '无分组' : $row['groupname'];
            $row['levelname'] = empty($row['levelname']) ? '普通等级' : $row['levelname'];
            $row['parentname'] = empty($row['parentname']) ? '总店' : '[' . $row['agentid'] . ']' . $row['parentname'];
            $row['statusstr'] = empty($row['status']) ? '' : '通过';
            $row['followstr'] = empty($row['followed']) ? '' : '已关注';
        }
        unset($row);
        m('excel') -> export($list, array('title' => '代理商数据-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => 'ID', 'field' => 'id', 'width' => 12), array('title' => '昵称', 'field' => 'nickname', 'width' => 12), array('title' => '姓名', 'field' => 'realname', 'width' => 12), array('title' => '手机号', 'field' => 'mobile', 'width' => 12), array('title' => '微信号', 'field' => 'weixin', 'width' => 12), array('title' => '推荐人', 'field' => 'parentname', 'width' => 12), array('title' => '代理商等级', 'field' => 'levelname', 'width' => 12), array('title' => '点击数', 'field' => 'clickcount', 'width' => 12), array('title' => '下线分销商总数', 'field' => 'levelcount', 'width' => 12), array('title' => '一级下线分销商数', 'field' => 'level1', 'width' => 12), array('title' => '二级下线分销商数', 'field' => 'level2', 'width' => 12), array('title' => '三级下线分销商数', 'field' => 'level3', 'width' => 12), array('title' => '累计佣金', 'field' => 'commission_total', 'width' => 12), array('title' => '打款佣金', 'field' => 'commission_pay', 'width' => 12), array('title' => '注册时间', 'field' => 'createtime', 'width' => 12), array('title' => '成为分销商时间', 'field' => 'createtime', 'width' => 12), array('title' => '审核状态', 'field' => 'createtime', 'width' => 12), array('title' => '是否关注', 'field' => 'followstr', 'width' => 12))));
    }
    $pager = pagination($total, $pindex, $psize);
}else if ($operation == 'detail'){
    ca('bonus.agent.view');
    $id = intval($_GPC['id']);
    $member = $this ->model->getInfo($id, array('total', 'pay'));
    $staff=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and mid = :mid',array(':uniacid'=>$_W['uniacid'],':mid'=>$member['id']));       

    
    if (checksubmit('submit')){

        ca('bonus.agent.edit|bonus.agent.check|bonus.agent.agentblack');
        $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
        if (empty($_GPC['oldstatus']) && $data['status'] == 1){
            $time = time();
            $data['agenttime'] = time();
            $this -> model -> sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $time), TM_COMMISSION_BECOME);
            plog('bonus.agent.check', "审核分销商 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        }
        if (empty($_GPC['oldagentblack']) && $data['agentblack'] == 1){
            $data['agentblack'] = 1;
            $data['status'] = 0;
            $data['isagent'] = 1;
        }
        $reside = $_GPC['reside'];

        if(!empty($data['bonus_area'])){
            
            if($data['bonus_area'] == 1){
                if(empty($reside['province'])){
                    message('请选择代理的省', '', 'error');
                }
            }else if($data['bonus_area'] == 2){
                if(empty($reside['city'])){
                    message('请选择代理的市', '', 'error');
                }
            }else if($data['bonus_area'] == 3){
                if(empty($reside['district'])){
                    message('请选择代理的区', '', 'error');
                }
            }

            empty($_GPC['agname']) && message('请输入代理商名字', '', 'error');
            empty($_GPC['agmobile']) && message('请输入代理商手机', '', 'error');
            $data['bonus_province'] = $reside['province'];
            $data['bonus_city'] = $reside['city'];
            $data['bonus_district'] = $reside['district'];

            $minfo=m('member')->getMember($id);
            $mmerch=p('bonus')->getMerch($minfo['openid'],'deal');
            

            $exists=p('agency')->getMStaff($id);
            
            $sid=$exists['id'];

            if (!$mmerch) {
                message('只有易货商家才能成为代理商，该商家不是易货商家', $this -> createPluginWebUrl('bonus/agent'), 'error');
            }
            empty($sid) &&  empty($_GPC['agpwd']) && message('请输入代理商密码', '', 'error');

            $workinfo = array( 	 	

            'name'=>$_GPC['agname'],

            'merchid'=>$mmerch['uid'],
                
            'mid'   =>$id,          //代理商的member id
                 
            'mobile'=>$_GPC['agmobile'],

            'isagent'=>1,
            
            'status'=>1,

            'uniacid'=>$_W['uniacid'],
            
            'ctime'=>time()
        );  
            	 	 	 

            if (empty($sid)){ 	 	 	

                pdo_insert('sz_yi_staff',$workinfo);
                $insid = pdo_insertid();
                if (empty($insid)) {

                    message('添加失败',$this->createPluginWebUrl('bonus/agent'),'error');

                } else {
                    unset($workinfo);
                    $adata=array();
                    $work=array();
                    $work['worknumber']='B';
                    for ($i=0; $i <  6-strlen($insid); $i++) { 
						$work['worknumber'].='0';
                    }
                    $work['worknumber'].=$insid;
                    $tempuid= user_register(array('username' =>$work['worknumber'], 'password' => $_GPC['agpwd']));
                    $pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $tempuid));
                    $work['uid']=$tempuid;
                    pdo_update('sz_yi_staff',$work,array('id'=>$insid));
                    pdo_insert('uni_account_users', array('uid' => $tempuid, 'uniacid' => $_W['uniacid'], 'role' => 'operator'));
                    $adata['staffid']  = $insid; 
                    $adata['password'] = $pwd['agpwd'];
                    $adata['username'] = $work['worknumber'];
                    $adata['roleid'] = 47;
                    $adata['status'] = 1;
                    $adata['uid']    = $tempuid;     
                    $adata['uniacid'] = $_W['uniacid'];
                    pdo_insert('sz_yi_perm_user', $adata);
                    $sid = pdo_insertid();
                    plog('perm.user.add', "添加员工 ID: {$id} 用户名: {$adata['username']} ");
                    message('添加成功',$this->createPluginWebUrl('bonus/agent'),'success');
                }
            }else{
                if (!empty($_GPC['agpwd'])) {
                    $info=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$sid));
                    $salt=pdo_fetchcolumn('select salt from '.tablename('users').' where uid = :uid ',array(':uid'=>$info['uid']));         
                    user_update(array('username' =>$info['worknumber'], 'password' => $_GPC['agpwd'],'salt'=>$salt,'uid'=>$info['uid']));
                    
                    $pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $info['uid']));
                    pdo_update('sz_yi_perm_user',array('password'=>$pwd['agpwd']),array('uid'=>$info['uid']));
                }
                unset($workinfo['ctime']);	
                if($exists['isagent'] == 0){		//员工成为代理商清除所属商家
                	pdo_update('sz_yi_perm_user',array('belong_staffid'=>0),array('belong_staffid'=>$exists['id']));
                }   			 		 		 
                $updid=pdo_update('sz_yi_staff',$workinfo,array('id'=>$sid,'uniacid'=>$_W['uniacid']));

                // if (empty($updid)) {

                //     message('修改失败',$this->createPluginWebUrl('bonus/agent'),'error');

                // } else {
                //     message('修改成功',$this->createPluginWebUrl('bonus/agent'),'success');
                // }
            }

        }
        plog('bonus.agent.edit', "修改分销商 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        if(!empty($data['bonuslevel'])){
            $data['bonus_status'] = 1;
        }
        pdo_update('sz_yi_member', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
        if (empty($_GPC['oldstatus']) && $data['status'] == 1){
            if (!empty($member['agentid'])){
                $this -> model -> upgradeLevelByAgent($member['agentid']);
            }
        }
        message('保存成功!', $this -> createPluginWebUrl('bonus/agent'), 'success');
    }
    $diyform_flag = 0;
    $diyform_plugin = p('diyform');
    if ($diyform_plugin){
        if (!empty($member['diycommissiondata'])){
            $diyform_flag = 1;
            $fields = iunserializer($member['diycommissionfields']);
        }
    }
}else if ($operation == 'delete'){
    ca('bonus.agent.delete');
    $id = intval($_GPC['id']);
    $member = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and id=:id limit 1 ', array(':uniacid' => $_W['uniacid'], ':id' => $id));
    if (empty($member)){
        message('会员不存在，无法取消分销商资格!', $this -> createPluginWebUrl('bonus/agent'), 'error');
    }
    $agentcount = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . ' where  uniacid=:uniacid and agentid=:agentid limit 1 ', array(':uniacid' => $_W['uniacid'], ':agentid' => $id));
    if ($agentcount > 0){
        message('此会员有下线存在，无法取消分销商资格!', '', 'error');
    }
    pdo_update('sz_yi_member', array('isagent' => 0, 'status' => 0), array('id' => $_GPC['id']));
    plog('bonus.agent.delete', "取消分销商资格 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
    message('删除成功！', $this -> createPluginWebUrl('bonus/agent'), 'success');
}else if ($operation == 'agentblack'){
    ca('bonus.agent.agentblack');
    $id = intval($_GPC['id']);
    $member = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and id=:id limit 1 ', array(':uniacid' => $_W['uniacid'], ':id' => $id));
    if (empty($member)){
        message('会员不存在，无法设置黑名单!', $this -> createPluginWebUrl('bonus/agent'), 'error');
    }
    $black = intval($_GPC['black']);
    if (!empty($black)){
        pdo_update('sz_yi_member', array('isagent' => 1, 'status' => 0, 'agentblack' => 1), array('id' => $_GPC['id']));
        plog('bonus.agent.agentblack', "设置黑名单 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        message('设置黑名单成功！', $this -> createPluginWebUrl('bonus/agent'), 'success');
    }else{
        pdo_update('sz_yi_member', array('isagent' => 1, 'status' => 1, 'agentblack' => 0), array('id' => $_GPC['id']));
        plog('bonus.agent.agentblack', "取消黑名单 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        message('取消黑名单成功！', $this -> createPluginWebUrl('bonus/agent'), 'success');
    }
}else if ($operation == 'user'){
    ca('bonus.agent.user');
    $level = intval($_GPC['level']);
    $agentid = intval($_GPC['id']);
    $member = $this -> model -> getInfo($agentid);
    $total = $member['agentcount'];
    $condition = '';
    $params = array();
    if($total > 0){
        $inagents = implode(',', $member['agentids']);
        $condition .= ' and dm.id in( ' . $inagents . ')';
    }else{
        $condition .= ' and dm.agentid=' . $member['id'] . ' and dm.status=1';
    }
    $hasagent = true;
    if (!empty($_GPC['mid'])){
        $condition .= ' and dm.id=:mid';
        $params[':mid'] = intval($_GPC['mid']);
    }
    if (!empty($_GPC['realname'])){
        $_GPC['realname'] = trim($_GPC['realname']);
        $condition .= ' and ( dm.realname like :realname or dm.nickname like :realname or dm.mobile like :realname)';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }
    if ($_GPC['isagent'] != ''){
        $condition .= ' and dm.isagent=' . intval($_GPC['isagent']);
    }
    if ($_GPC['status'] != ''){
        $condition .= ' and dm.status=' . intval($_GPC['status']);
    }
    if (empty($starttime) || empty($endtime)){
        $starttime = strtotime('-1 month');
        $endtime = time();
    }
    if (!empty($_GPC['agentlevel'])){
        $condition .= ' and dm.agentlevel=' . intval($_GPC['agentlevel']);
    }
    if ($_GPC['parentid'] == '0'){
        $condition .= ' and dm.agentid=0';
    }else if (!empty($_GPC['parentname'])){
        $_GPC['parentname'] = trim($_GPC['parentname']);
        $condition .= ' and ( p.mobile like :parentname or p.nickname like :parentname or p.realname like :parentname)';
        $params[':parentname'] = "%{$_GPC['parentname']}%";
    }
    if ($_GPC['followed'] != ''){
        if ($_GPC['followed'] == 2){
            $condition .= ' and f.follow=0 and dm.uid<>0';
        }else{
            $condition .= ' and f.follow=' . intval($_GPC['followed']);
        }
    }
    if ($_GPC['agentblack'] != ''){
        $condition .= ' and dm.agentblack=' . intval($_GPC['agentblack']);
    }
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $list = array();
    if ($hasagent){
        $total = pdo_fetchcolumn('select count(dm.id) from' . tablename('sz_yi_member') . ' dm ' . ' left join ' . tablename('sz_yi_member') . ' p on p.id = dm.agentid ' . ' left join ' . tablename('mc_mapping_fans') . 'f on f.openid=dm.openid' . ' where dm.uniacid =' . $_W['uniacid'] . "  {$condition}", $params);
        $list = pdo_fetchall('select dm.*,p.nickname as parentname,p.avatar as parentavatar  from ' . tablename('sz_yi_member') . ' dm ' . ' left join ' . tablename('sz_yi_member') . ' p on p.id = dm.agentid ' . ' left join ' . tablename('mc_mapping_fans') . "f on f.openid=dm.openid  and f.uniacid={$_W['uniacid']}" . ' where dm.uniacid = ' . $_W['uniacid'] . "  {$condition}   ORDER BY dm.agenttime desc limit " . ($pindex - 1) * $psize . ',' . $psize, $params);
        $pager = pagination($total, $pindex, $psize);
        foreach ($list as & $row){
            $info = $this -> model -> getInfo($row['openid'], array('total', 'pay'));
            $row['levelcount'] = $info['agentcount'];
            if ($this -> set['level'] >= 1){
                $row['level1'] = $info['level1'];
            }
            if ($this -> set['level'] >= 2){
                $row['level2'] = $info['level2'];
            }
            if ($this -> set['level'] >= 3){
                $row['level3'] = $info['level3'];
            }
            $row['credit1'] = m('member') -> getCredit($row['openid'], 'credit1');
            $row['credit2'] = m('member') -> getCredit($row['openid'], 'credit2');
            $row['commission_total'] = $info['commission_total'];
            $row['commission_pay'] = $info['commission_pay'];
            $row['followed'] = m('user') -> followed($row['openid']);
            if ($row['agentid'] == $member['id']){
                $row['level'] = 1;
            }else if (in_array($row['agentid'], array_keys($member['level1_agentids']))){
                $row['level'] = 2;
            }else if (in_array($row['agentid'], array_keys($member['level2_agentids']))){
                $row['level'] = 3;
            }
        }
    }
    unset($row);
    load() -> func('tpl');
    include $this -> template('agent_user');
    exit;
}else if ($operation == 'query'){
    $kwd = trim($_GPC['keyword']);
    $wechatid = intval($_GPC['wechatid']);
    if (empty($wechatid)){
        $wechatid = $_W['uniacid'];
    }
    $params = array();
    $params[':uniacid'] = $wechatid;
    $condition = ' and uniacid=:uniacid and isagent=1 and status=1';
    if (!empty($kwd)){
        $condition .= ' AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )';
        $params[':keyword'] = "%{$kwd}%";
    }
    if (!empty($_GPC['selfid'])){
        $condition .= ' and id<>' . intval($_GPC['selfid']);
    }
    $ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename('sz_yi_member') . " WHERE 1 {$condition} order by createtime desc", $params);
    include $this -> template('query');
    exit;
}else if ($operation == 'check'){
    ca('bonus.agent.check');
    $id = intval($_GPC['id']);
    $member = $this -> model -> getInfo($id, array('total', 'pay'));
    if (empty($member)){
        message('未找到会员信息，无法进行审核', '', 'error');
    }
    if ($member['isagent'] == 1 && $member['status'] == 1){
        message('此分销商已经审核通过，无需重复审核!', '', 'error');
    }
    $time = time();
    pdo_update('sz_yi_member', array('status' => 1, 'agenttime' => $time), array('id' => $member['id'], 'uniacid' => $_W['uniacid']));
    $this -> model -> sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $time), TM_COMMISSION_BECOME);
    if (!empty($member['agentid'])){
        $this -> model -> upgradeLevelByAgent($member['agentid']);
    }
    plog('bonus.agent.check', "审核分销商 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
    message('审核分销商成功!', $this -> createPluginWebUrl('bonus/agent'), 'success');
}
load() -> func('tpl');

 

include $this -> template('agent');
