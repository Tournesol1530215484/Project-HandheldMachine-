<?php
global $_W, $_GPC;
$openid   = m('user')->getOpenid();
if($_GET['op'] == 'liuyan'){
	$user = m('member')->getMember($openid);
	$user2 = m('member')->getMember($_GET['id']);
	$sender = $_GET['sender'];
	if(empty($user) or empty($user2) or empty($sender) or $user['id'] == $_GET['id']){
		die('参数错误，请联系管理员');
	}
	if($sender == 'superior'){
		$superior_id  = $user['id'];
		$lower_id     = $_GET['id'];
		$superior_img = $user['avatar'];
		$lower_img    = $user2['avatar'];
		$msg_sender   = 'lower';
		$bzd          = '上级';
	}elseif($sender == 'lower'){
		$superior_id  = $_GET['id'];
		$lower_id     = $user['id'];
		$superior_img = $user2['avatar'];
		$lower_img    = $user['avatar'];
		$msg_sender   = 'superior';
		$bzd          = '下级';
	}
	$liuyan = pdo_fetchall('select * from ' . tablename('sz_yi_liuyan') . 
				' where weid=:weid  and superior_id=:superior_id and lower_id=:lower_id order by time asc', array(
				':weid'     => $_W['uniacid'],
				':superior_id' => $superior_id,
				':lower_id'    => $lower_id
	));
	$iNow =  sizeof($liuyan) -1;
	if($_POST['msg']){
		$data = array(
			'sender'      => $sender,
			'weid'        => $_W['uniacid'], 
			'superior_id' => $superior_id, 
			'lower_id'    => $lower_id, 
			'content'     => $_POST['msg'],
			'time'        => time()
		);
		pdo_insert('sz_yi_liuyan', $data);
		$template = array(
			'keyword1' => array(
				'title' => '您有新的留言', 
				'value' => '您有来自'.$bzd.'['.$user['nickname'].']的未读消息'
			)
		);
		$url = $this->createPluginMobileUrl('commission/team',array('op'=>'liuyan','id'=>$user['id'],'sender'=>$msg_sender ));
		m('message')->sendCustomNotice($user2['openid'], $template, $url);
			
		$msg = array('msg' => 'ok'); 
		echo json_encode($msg);
		exit;
	}
	include $this->template('liuyan');
	exit;
}

$tabwidth = "50";
$level = $this->set['level'];
if ($this->set['level'] >= 1) {
    $tabwidth = 100;
}
if ($this->set['level'] >= 2) {
    $tabwidth = 50;
}
if ($this->set['level'] >= 3) {
    $tabwidth = 33.3;
}
if ($this->set['level'] >= 4) {
    $tabwidth = 25;//33.3;
}
if ($this->set['level'] >= 5) {
    $tabwidth = 20;//33.3;
}
$member = $this->model->getInfo($openid);
$total  = $member['agentcount'];
$level  = intval($_GPC['level']);
($level > 15 || $level <= 0) && $level = 1;
$condition = '';
$level1    = $member['level1'];
$level2    = $member['level2'];
$level3    = $member['level3'];
$level4    = $member['level4'];
$level5    = $member['level5'];
$level6    = $member['level6'];
$level7    = $member['level7'];
$level8    = $member['level8'];
$level9    = $member['level9'];
$level10    = $member['level10'];
$level11    = $member['level11'];
$level12    = $member['level12'];
$level13    = $member['level13'];
$level14    = $member['level14'];
$level15    = $member['level15'];
$hasangent = false;
if ($level == 1) {
    if ($level1 > 0) {
        $condition = " and agentid={$member['id']}";
        $hasangent = true;
    }
}elseif($level>1){
	$j= $level-1;
	if ($member['level'.$j] > 0) {
		$condition = " and agentid in( " . implode(',', array_keys($member['level'.$j.'_agentids'])) . ")";
		$hasangent = true;
	}
} 
/*
else if ($level == 2) {
    if ($level2 > 0) {
        $condition = " and agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")";
        $hasangent = true;
    }
} else if ($level == 3) {
    if ($level3 > 0) {
        $condition = " and agentid in( " . implode(',', array_keys($member['level2_agentids'])) . ")";
        $hasangent = true;
    }
}
*/
if ($_W['isajax']) {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$list = array();
	if ($hasangent) {
		$list = pdo_fetchall("select * from " . tablename('sz_yi_member') . " where isagent =1 and status=1 and uniacid = " . $_W['uniacid'] . " {$condition}  ORDER BY agenttime desc limit " . ($pindex - 1) * $psize . ',' . $psize);
		foreach ($list as &$row) {
			$info = $this->model->getInfo($row['openid'], array('total'));
			$row['commission_total'] = $info['commission_total'];
			$row['agentcount'] = $info['agentcount'];
			$row['agenttime'] = date('Y-m-d H:i', $row['agenttime']);
		}
	}
	unset($row);
	show_json(1, array('list' => $list, 'pagesize' => $psize));
}

include $this->template('team');
