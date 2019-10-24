<?php
// use Grafika\Grafika; // Import package
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$op = empty($_GPC['op']) ? 'display': $_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid);
$member=m('member')->getMember($openid);
$actset=m('activity')->getSet()['bartact'];

if ($op == 'display') {
	$cate=pdo_fetchall('select * from '.tablename('sz_yi_activity_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));
	$banner=pdo_fetchall('select * from '.tablename('sz_yi_activity_adv').' where uniacid = :uniacid and status = 1 and place = 0 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));
	foreach ($banner as $key => $value) {
		$banner[$key]['thumb']=tomedia($value['thumb']);
	}

	if ($ac == 'get') {
		$pindex=max(1,intval($_GPC['page']));
		$psize=5;
//        print_r($_GPC);exit;
		$params=array(
			':uniacid'=>$_W['uniacid']
		);

		$condition=' and uniacid = :uniacid and status = 1 ';

		if ($_GPC['cate']) {
			$params[':cate']=trim($_GPC['cate']);
			$condition.='  and cate = :cate';
		}

		if ($_GPC['fee']) {
			$params[':fee']=trim($_GPC['fee']);
			$condition.='  and cost = :fee';
		}

		if ($_GPC['province']) {
			$params[':province']=trim($_GPC['province']);
			$condition.='  and province = :province';
		}

		if ($_GPC['city']) {
			$params[':city']=trim($_GPC['city']);
			$condition.='  and city = :city';
		}

		if (!empty($_GPC['keywords'])) {
			$_GPC['keywords'] = trim($_GPC['keywords']);
			$condition .= " AND (title LIKE '%{$_GPC['keywords']}%' or relOrg LIKE '%{$_GPC['keywords']}%') ";
		}

		$etime=time();
		switch (intval($_GPC['time'])) {

			case '1':

					break;

				case '2':
					$time=m('time')->today();
					$params[':stime']=$time[0];
					$params[':etime']=$etime;
					$condition.=' and stime < :stime and etime > :etime ';
					break;

				case '3':
					$time=m('time')->tomorrow();
					$params[':stime']=$time[0];
					$params[':etime']=$etime;
					$condition.=' and stime < :stime and etime > :etime ';
					break;

				case '4':
					$time=m('time')->week();
					$params[':stime']=$time[0];
					$params[':etime']=$etime;
					$condition.=' and stime < :stime and etime > :etime ';
					break;

				case '5':
					$time=m('time')->endWeek();
					$params[':stime']=$time[0];
					$params[':etime']=$etime;
					$condition.=' and stime < :stime and etime > :etime ';
					break;

				case '6':
					$time=m('time')->month();
					$params[':stime']=$time[0];
					$params[':etime']=$etime;
					$condition.=' and stime < :stime and etime > :etime ';
					break;

				default:
					if (!empty($_GPC['time'])) {

						$time=array(
							strtotime($_GPC['time']),
							strtotime($_GPC['etime'])
						);

						$params[':stime']=$time[0];
						$params[':etime']=$time[1];
						$condition.=' and stime < :stime and etime > :etime ';
					}

					break;
			}
		//添加一个处理，进行修改个人活动。	
		//$openid = m('user')->getOpenid();//获取个人的openid
		//$tmu=m('activity')->getMuser($openid);//获取发布活动的人的名称

		$sql='select * from '.tablename('sz_yi_activity').' where 1 ';
		$sql.=$condition;
		$sql.=' order by is_top desc, stime desc ';
		//$sql.=' order by is_top desc ,toptime asc ,id desc ';
		//$sql.='where article_author ='.$tmu['realname'];	//修改
		$sql.=' limit '.($pindex -1) * $psize .','.$psize;
		$list=pdo_fetchall($sql,$params);
//        print_r($list);exit;
		if ($list) {
			foreach ($list as $key => $value) {
			if ($value['icon'] && $value['icon'] != 'undefined') {
				$list[$key]['icon']=tomedia($value['icon']);
			}
			$list[$key]['wechatCode']=tomedia($value['wechatCode']);
			$list[$key]['stime']=date('Y-m-d H:i:s',$value['stime']);
			$list[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);
			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
			$list[$key]['link']=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$value['id']));
		}
			show_json(1,array('list'=>$list));
		}
			show_json(0,array('list'=>$list));

	}
}else if($op == 'add'){
	if (!$muser) {
		m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));
	}
	$cate=pdo_fetchall('select * from '.tablename('sz_yi_activity_type').' where uniacid = :uniacid and status = 1',array(':uniacid'=>$_W['uniacid']));
	if (isset($_GPC['id']) && !empty($_GPC['id']) ) {
		$temp=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and openid = :openid and id = :id ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));
		!$temp && m('tools')->tip('找不到该活动!');
	}
	if ($ac == 'get') {
		$id=intval($_GPC['id']);
		if (empty($id)) {
			$id=intval($_GPC['copyid']);
		}
		$list=array();
		$list=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		if ($list) {
			$py=m('activity')->getPayitem($id);
			// $list['money']=$py?$py['money']:0;
			$list['icon1']=tomedia($list['icon']);
			$list['stime']=date('Y-m-d H:i',$list['stime']);
			$list['etime']=date('Y-m-d H:i',$list['etime']);
			show_json(1,array('list'=>$list));
		}
			show_json(0,array('list'=>$list));
	}


	if ($ac == 'sub') {

		$arr=array(
            'realname'=>['title'=>'姓名','num'=>1,'must'=>1],
            'mobile'=>['title'=>'手机','num'=>2,'must'=>1],
            'unit'=>['title'=>'单位','num'=>3,'must'=>1]
        );

        $id = intval($_GPC['id']);



        $info=m('tools')->getAccountInfo($openid);
        if ($_GPC['status'] == 1) {				//修改基本信息
	 		$data=array(
				'title'=>trim($_GPC['title']),
				'uniacid'=>$_W['uniacid'],
				'openid'=>$openid,
				'province'=>trim($_GPC['province']),
				'city'=>trim($_GPC['city']),
				'area'=>trim($_GPC['area']),
				'field'=>serialize($arr),
				'desc'=>trim($_GPC['desc']),
				'stime'=>strtotime($_GPC['stime']),
				'etime'=>strtotime($_GPC['etime']),
				'teamModel'=>trim($_GPC['teamModel']),
				// 'relOrg'=>trim($_GPC['relOrg']),
				// 'descOrg'=>trim($_GPC['descOrg']),
				// 'mobileOrg'=>trim($_GPC['mobileOrg']),
				// 'ContactOrg'=>trim($_GPC['ContactOrg']),
				'status'=>0,
				'relOrg'=>$info['orgName'],
            	'ContactOrg'=>$info['realname'],
            	'mobileOrg'=>$info['mobile'],
            	'descOrg'=>$info['orgDesc'],
				'refund'=>trim($_GPC['refund']),
				'public'=>trim($_GPC['public']),
				'hideList'=>trim($_GPC['hideList']),
				'cost'=>$_GPC['cost'],
				'money'=>floatval($_GPC['money']),
				'agent1'=>intval($_GPC['turnrate1']),
				'agent2'=>intval($_GPC['turnrate2']),
				'paytype'=>1,
				'cate'=>trim($_GPC['cate']),
				'afterTheStart'=>trim($_GPC['afterTheStart']),
				'address'=>trim($_GPC['address']),
				'isAudit'=>trim($_GPC['isAudit']),
				'icon'=>trim($_GPC['post1'][0])
			);

				$payitem=array(
		        	'uniacid'=>$_W['uniacid'],
		        	'title'=>'全部',
		        	'limit'=>-1,
		        	'money'=>floatval($_GPC['money']),
		        	'ctime'=>time(),
		        	'enabled'=>1,
		        	'ismobile'=>1
		        );


	 	}else if($_GPC['status'] == 2){			//修改内容
	 		$data=array(
				'content'=>trim($_GPC['details']),
				'status'=>0
			);
	 	}else{									//添加
	 		$data=array(
				'title'=>trim($_GPC['title']),
				'uniacid'=>$_W['uniacid'],
				'openid'=>$openid,
				'province'=>trim($_GPC['province']),
				'city'=>trim($_GPC['city']),
				'area'=>trim($_GPC['area']),
				'field'=>serialize($arr),
				'desc'=>trim($_GPC['desc']),
				'content'=>trim($_GPC['details']),
				'stime'=>strtotime($_GPC['stime']),
				'etime'=>strtotime($_GPC['etime']),
				'teamModel'=>trim($_GPC['teamModel']),
				// 'relOrg'=>trim($_GPC['relOrg']),
				// 'descOrg'=>trim($_GPC['descOrg']),
				// 'mobileOrg'=>trim($_GPC['mobileOrg']),
				// 'ContactOrg'=>trim($_GPC['ContactOrg'])
				'relOrg'=>$info['orgName'],
				'status'=>0,
            	'ContactOrg'=>$info['realname'],
            	'mobileOrg'=>$info['mobile'],
            	'descOrg'=>$info['orgDesc'],
				'refund'=>trim($_GPC['refund']),
				'public'=>trim($_GPC['public']),
				'hideList'=>trim($_GPC['hideList']),
				'cost'=>empty($_GPC['cost']),
				'money'=>floatval($_GPC['money']),
				'cate'=>trim($_GPC['cate']),
				'agent1'=>intval($_GPC['turnrate1']),
				'agent2'=>intval($_GPC['turnrate2']),
				'afterTheStart'=>trim($_GPC['afterTheStart']),
				'address'=>trim($_GPC['address']),
				'isAudit'=>trim($_GPC['isAudit']),
				'icon'=>trim($_GPC['post1'][0])
			);
				$payitem=array(
		        	'uniacid'=>$_W['uniacid'],
		        	'title'=>'全部',
		        	'limit'=>-1,
		        	'money'=>floatval($_GPC['money']),
		        	'ctime'=>time(),
		        	'enabled'=>1,
		        	'ismobile'=>1
		        );
	 	}


	 	//审核
	 	$logid=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_log').' where uniacid = :uniacid and actid = :actid and status = 0',array(':uniacid'=>$_W['uniacid'],':actid'=>$id));

        $goodslog=[
            'uniacid'=>$_W['uniacid'],
            'uid'=>$muser['uid'],
            'actid'=>$_GPC['id'],
            'sub_time'=>time(),
            'status'=>0
        ];
        //
		if (empty($id)) {
			$data['ctime']=time();
			if ($muser['level'] == 3) {
				$data['is_top']=2;
				$data['toptime']=time();
	 		}
        	pdo_insert('sz_yi_activity',$data);
        	$id=pdo_insertid();

        	//审核
        	$goodslog['actid']=$id;
            pdo_insert('sz_yi_activity_log',$goodslog);
            if (empty($actset['audit'])) {     //如果需要审核
                m('activity')->auditActivity(pdo_insertid());
            }
            //

        	if ($data['cost'] == 1) {
        		$payitem['atid']=$id;
        		pdo_insert('sz_yi_activity_payitem',$payitem);
        	}
        	if ($id) {
        		show_json(1);
        	}
        		show_json(0,'添加失败');
        }else{
        	$oldact=m('activity')->getact($id);
        	if ($muser['level'] == 3) {
        		if ($oldact['is_top'] <= 2) {
        			$data['is_top']=2;
					$data['toptime']=time();
        		}
        	}
        	$re=pdo_update('sz_yi_activity',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));

        	if ($data['cost'] == 1) {
        		$py=pdo_fetch('select * from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and atid = :id and ismobile = 1 and enabled = 1',array('uniacid'=>$_W['uniacid'],':id'=>$id));
        		if ($py) {
	        		pdo_update('sz_yi_activity_payitem',$payitem,array('id'=>$py['id'],'uniacid'=>$_W['uniacid']));
	        	}else{
	        		$payitem['atid']=$id;
	        		pdo_insert('sz_yi_activity_payitem',$payitem);
	        	}
        	}

        	//修改审核
        	if ($logid) {
                pdo_update('sz_yi_activity_log',$goodslog,array('id'=>$logid));
            }else{
                pdo_insert('sz_yi_activity_log',$goodslog);
                $logid=pdo_insertid();
            }

            if (empty($actset['audit'])) {     //如果需要审核
                m('activity')->auditActivity($logid);
            }
            //end

        	if ($re) {
        		show_json(1,'更新成功!');
        	}
        		show_json(0,'更新失败');
        }
	}

	include $this->template('actAdd');	//	发布活动
	exit;
}else if($op == 'detail'){

	$id=intval($_GPC['id']);
	$mid=$_GPC['mid'];
	if (!empty($mid)) {
		$mmember=m('member')->getMember($id);
	}
	$ptid=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and enabled = 1 and type = 2 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));
	$activity=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid  = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));


	//获取发布者的信息
	//微信用户发布的文章

	//$postInfo=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$activity['openid']));

	$accountinfo=pdo_fetch('select * from '.tablename('sz_yi_activity_account_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$activity['openid']));

	

	//微信模块
	//$postInfoWeChat=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$activity['openid']));



	if ($activity['cost'] == 1) {
		if ($activity['money'] == 0) {
			$activity['money']=pdo_fetchcolumn('select money from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and atid = :id and enabled = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		}
	}
	$banner=pdo_fetchall('select * from '.tablename('sz_yi_activity_adv').' where uniacid = :uniacid and status = 1 and place = 1 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));
	foreach ($banner as $key => $value) {
		$banner[$key]['thumb']=tomedia($value['thumb']);
	}

	$reward=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ar.money,ar.remark from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where ar.uniacid = :uniacid and ar.atid = :id and ar.type = 1 order by ar.id desc ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	// var_dump($reware);
	$comment=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ac.content from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 1 and ac.status = 1 order by ac.id desc limit 0,5 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	$tac=m('activity')->getact($id);	//获取活动信息

	$tmu=m('activity')->getMuser($activity['openid']);	//获取活动发布者
	
   // $tmu = m('activity')->getAccountInfo($tac['openid']);
	if ($tmu) {
		$isfavorite=m('tools')->checkFavorite($openid,$tmu['uid']);
	}else{
		$isfavorite=false;
	}
	$activity['comment']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	$favorite=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_favorite').' where merchid = :uid and uniacid = :uniacid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$tmu['uid']));
	$favorite=$favorite?:0;

	$totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity').' where uniacid = :uniacid and openid = :openid',array('openid'=>$activity['openid'],':uniacid'=>$_W['uniacid']));

	$like=pdo_fetchall('select m.avatar from '.tablename('sz_yi_activity_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.atid = :id and al.type = 1 order by al.id desc limit 0,50 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	$total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	$total=$total?:0;
	$activity['stime']=date('Y.m.d H:i',$activity['stime']);
	$activity['etime']=date('Y.m.d H:i',$activity['etime']);
	$activity['ctime']=date('Y.m.d H:i',$activity['ctime']);

	$signup=false;
	$exists=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and deleted = 0 and actid = :id and (status = 0 or status = 1) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
	if ($exists) {
		$signup=true;
	}

	$timed=false;
	if ($activity['afterTheStart'] == 1) {
		if ($activity['stime'] <= time()) {
			$timed=true;
		}
	}

	$_W['shopshare']=array(
		'title'=> $activity['title'],
	    'imgUrl'=>tomedia($activity['icon']),
	    'desc'=>$activity['desc'],
	    'link'=>$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$activity['id'],'mid'=>$member['id']))
	);

	if ($ac == 'getlist') {
		$id=intval($_GPC['id']);
		$pindex=max(1,intval($_GPC['page']));
		$psize=5;
		$params=array(
			':uniacid'=>$_W['uniacid'],
			':id'=>$id
		);

		$sql='select s.* from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.actid = :id and s.deleted = 0 order by ctime desc ';
		if ($pindex == 1) {
			$psize=9;
			$sql.=' limit '.($pindex -1) * $psize.','.$psize;
		}else{
			$sql.=' limit '.(($pindex -1) * $psize + 4).','.$psize;
		}
		$exist=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		$act=m('activity')->getact($id);
		if ($act['hideList']) {
			$list=array();
			show_json(1,array('list'=>$list,'pagesize'=>$psize));
		}else{
			$list=pdo_fetchall($sql,$params);
		}
		if ($list) {
			foreach ($list as $key => $value) {
				$tmember=m('member')->getMember($value['openid']);
				$tinfo=unserialize($value['data']);
				$list[$key]['realname']=$tinfo['realname']['data'];
				$list[$key]['ctime']=date('Y-m-d',$value['ctime']);
				if ($exists) {
					if ($value['public']) {
						$link='location.href="'.$this->createPluginMobileUrl('activity/card',array('tid'=>$tmember['id'],'actid'=>$id)).'"';
		 		 	}else{
		 		 		$link='alert("对方未公开信息");';
		 		 	}
				}else{
	 		 		$link='alert("你还没有报名,不可查看信息");';
	 		 	}
	 		 	$list[$key]['link']=$link;
			}
			show_json(1,array('list'=>$list,'pagesize'=>$psize));
		}
		show_json(0,array('list'=>$list,'pagesize'=>$psize));

	}


	$iePoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 2 and enabled = 1 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));

	if ($_GPC['debug']) {

		include $this->template('actDetail_debug');	//活动详情页
	}else{

		include $this->template('actDetail');	//活动详情页
	}
	exit;
}else if($op == 'notice'){

	include $this->template('actNotice');	//发活动前的提示页面
	exit;
}else if($op == 'myAct'){

	if ($_W['isajax']) {
		if ($ac == 'get') {
			$pindex=max(1,intval($_GPC['page']));
			$psize=5;
			$type=intval($_GPC['status']);
			$params=array(
				':uniacid'=>$_W['uniacid'],
				':openid'=>$openid
			);

			if ($type == '1') {
				$sql="select a.* from ".tablename('sz_yi_activity').' a left join '.tablename('sz_yi_activity_signup').' s on a.id=s.actid where s.uniacid = :uniacid and s.openid = :openid and s.deleted = 0';
				$sql.=' order by s.ctime desc ';
			}else if($type == '2'){
				$sql='select a.id,a.title,s.ctime,a.stime from '.tablename('sz_yi_activity').' a left join '.tablename('sz_yi_activity_share').' s on a.id = s.actid where s.uniacid = :uniacid and s.openid = :openid and s.type = 1 group by s.actid ';
				$sql.=' order by s.ctime desc ';
			}else if($type == '3'){
				$sql="select * from ".tablename('sz_yi_activity').' where uniacid = :uniacid and openid = :openid ';
				$sql.=' order by ctime desc ';
			}
			$sql.=' limit '.($pindex - 1) * $psize.' , '.$psize;
			$list=pdo_fetchall($sql,$params);
			if ($list) {
				foreach ($list as $key => $value) {
					if ($type == 3) {
						$list[$key]['ctime']=date('Y-m-d',$value['ctime']);
	 		 	 	}else{
						$list[$key]['ctime']=date('Y-m-d',$value['stime']);
	 		 	 	}
					$list[$key]['url']=$this->createPluginMobileUrl('activity/activity',array('op'=>'adminAct','id'=>$value['id'],'type'=>$type));
				}
				show_json(1,array('list'=>$list,'pagesize'=>count($list)));
			}
		$list=array();
		show_json(0,array('list'=>$list,'pagesize'=>count($list)));
		}
	}

	include $this->template('actMy');		//我发布的活动
	exit;
}else if($op == 'adminAct'){		//管理
	$id=intval($_GPC['id']);
	$type=intval($_GPC['type']);

	if ($ac == 'setpublic') {
		$sginfo=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and actid = :id',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
		if ($sginfo['public'] == 0) {
			$data=array('public'=>1);
			$str='公开名片';
		}else{
			$data=array('public'=>0);
			$str='不公开名片';
		}
		$tre=pdo_update('sz_yi_activity_signup',$data,array('id'=>$sginfo['id'],'uniacid'=>$_W['uniacid']));
		if ($tre) {
			show_json(1,'已设置'.$str);
		}
		show_json(0,'设置失败!');
	}
	empty($id) && m('tools')->tip('非法参数!');
	empty($type) && m('tools')->tip('非法参数!');

	if ($_GPC['type']==3) {
		$temp=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid  = :uniacid and openid = :openid and id = :id',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));
		!$temp && m('tools')->tip('找不到该活动');
	}

	if ($_GPC['type'] ==1) {
		$sginfo=m('activity')->getSignUp($id,$openid);
	}

	$iePoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 2 and enabled = 1 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));

	$sgPoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 1 and enabled = 1 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));
	$activity=m('activity')->getact($id,1);
	$activity['signup']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	$activity['sign']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and status = 1 and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

		// $activity['shorturl']=$re['short_url'];
	include $this->template('actAdmin');	//活动管理
	exit;
}else if($op == 'trurl'){
		$id = intval($_GPC['id']);
		$re=m('tools')->long2short($this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$id,'mid'=>$member['id']))); 			//mid 若无上级锁定上级
		if ($re['errcode'] == 0) {
			show_json(1,$re['short_url']);
 		}
		show_json(0,'短链接获取失败,请重新获取');
}else if($op == 'draw'){

	$id=intval($_GPC['id']);
	$type=intval($_GPC['type']);

	empty($id) && m('tools')->tip('非法参数!');
	empty($type) && m('tools')->tip('非法参数!');

	include $this->template('actWithdraw');		//活动提现
	exit;
}else if($op == 'forwarding'){
	$mid=$_GPC['mid']?:0;
	$id=intval($_GPC['atid']);
	$type=intval($_GPC['type']);

	$activity=m('activity')->getact($id,$type);		//4图片 5比赛

	if ($type == 4) {
		$link=$this->createPluginMobileUrl('match/picture',array('op'=>'detail','id'=>$activity['id'],'mid'=>$member['id']));
	}else if ($type == 5) {
		$link=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$activity['id'],'mid'=>$member['id']));
    }else if ($type == 10) {
        $link=$this->createPluginMobileUrl('bartact/forum',array('op'=>'detail','id'=>$id,'mid'=>$member['id']));
	}else if ($type == 1) {
		$link=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$activity['id'],'mid'=>$member['id']));
	}else{
		$link=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$activity['id'],'mid'=>$member['id']));
	}
	$fd=array(
		'title'=> $activity['title'],
	    'imgUrl'=>$member['avatar'],
	    'desc'=>$activity['desc'],
	    'link'=>$link
	);

    if($type==10){
        $forum=pdo_fetch('select * from '.tablename('sz_yi_forum').'where uniacid=:uniacid and id=:id',array(':id'=>$id,
            ':uniacid'=>$_W['uniacid']));
        $m=pdo_fetch('select avatar from '.tablename('sz_yi_member').'where uniacid=:uniacid and openid=:openid',array(':openid'=>$forum['openid'],
            ':uniacid'=>$_W['uniacid']));

        $fd=array(
            'title'=> $forum['title'],
            'imgUrl'=>$m['avatar'],
            'desc'=>$forum['content'],
            'link'=>$link
        );
    }

	$data=array(
		'uniacid'=>$_W['uniacid'],
		'atid'=>$id,
		'openid'=>$openid,
		'type'=>$type,
		'pid'=>$mid,
		'ctime'=>time()
	);

	show_json(1,json_encode($fd));

}else if($op == 'apply'){
	$id=intval($_GPC['id']);
	if ($_GPC['debug']) {
		var_dump(123123);
		exit;
	}
	empty($id) && m('tools')->tip('非法参数!');

	$activity=m('activity')->getact($id,1);


	if ($activity['cost'] == 1) {
		$payitem=pdo_fetchall('select * from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and atid = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	}
	$field=unserialize($activity['field']);

	$timed=false;
	$signup=false;
	$exists=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and deleted = 0 and actid = :id and (status = 0 or status = 1) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
	if ($exists) {
		$signup=true;
	}
	if ($activity['afterTheStart'] == 1) {
		if ($activity['stime'] <= time()) {
			$timed=true;
		}
	}

	if ($_W['isajax']) {
		if ($ac == 'check') {
			if ($activity['cost'] == 1) {
				if (empty($_GPC['fee'])) {
					show_json(0,'请选择项目!');
				}else{
					$num=pdo_fetchcolumn('select `limit` from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and id = :id and enabled  = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['fee']));
					if ($num==0 || !$num) {
						show_json(0,'该报名项目已经满人!');
					}
				}
			}
			show_json(1);
		}
		if ($ac == 'sub') {
			$mid=intval($_GPC['mid']);
			$tact=m('activity')->getact($_GPC['id']);


			$data=array(
				'uniacid'=>$_W['uniacid'],
				'data'=>serialize($_GPC['postdata']),
				'openid'=>$openid,
				'public'=>intval($_GPC['ispublic']),
				'like'=>intval($_GPC['isattentd']),
				'actid'=>$_GPC['id'],
				'paystatus'=>intval($_GPC['paystatus']),
				'ctime'=>time()
			);

			if (!empty($mid)) {
				$mmember=m('member')->getMember($mid);

				$pid=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and actid = :id',array(':uniacid'=>$_W['uniacid'],':openid'=>$mmember['openid'],':id'=>$_GPC['id']));
			 		$data['fmid']=$mid;
			 		$data['channel']=1;		//0 活动广场 1转发
				if ($pid) {
			 		$data['pid']=$pid;
			 	}
			}

			if (!empty($_GPC['fee'])) {
				$data['item']=$_GPC['fee'];
				$data['paytime']=time();
			}
			if (empty($tact['isAudit'])) {
				$data['status'] = 1;
			}

			if ($data['liek'] == 1) {		//关注
				$tmuser=m('activity')->getMuser($tact['openid']);
				$tre=pdo_fetch('select * from '.tablename('sz_yi_activity_favorite').' where uniacid = :uniacid and openid = :openid and merchid = :uid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':uid'=>$tmuser['uid']));
				if ($tre) {
					if (!empty($tre['deleted'])) {
						pdo_update('sz_yi_activity_favorite',array('deleted'=>0),array('id'=>$tre['id'],'uniacid'=>$_W['uniacid']));
					}
				}else{
					$tfadata=array(
						'uniacid'=>$_W['uniacid'],
						'merchid'=>$tmuser['uid'],
						'openid'=>$openid,
						'ctime'=>time()
					);
					pdo_insert('sz_yi_activity_favorite',$tfadata);
				}
			}

			//转介绍比例
			if (floatval($_GPC['fee']) > 0 && $data['paystatus'] == 1) {
				$pt=pdo_fetch('select * from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and id = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['fee']));
				empty($pt) && show_json(0,'找不到所选择的活动项目，请刷新后重试。');
				$data['money']=$pt['money'];

				if ($tact['teamModel'] == 1 && floatval($data['money']) > 0) {			//开启团队模式
					m('activity')->calcTeamModelBonus($openid,$data);
				}
			}

			pdo_insert('sz_yi_activity_signup',$data);
			$id=pdo_insertid();
			if ($pt['limit']!= -1) {	 	 //报名成功后人数-1
				pdo_update('sz_yi_activity_payitem',array('limit'=>$pt['limit']-1),array('id'=>$pt['id']));
			}
			if ($id) {
				//发送

				$member=m('member')->getMember($openid);
				$paytypearr=array(
					0=>'免费',
					1=>'在线支付',
					2=>'现场AA制',
					3=>'现场面议'
				);

				if ($_GPC['fee']) {
					$itemtitle=$pt['title'];
					$itemmoney=$pt['money'];
					$paystatus=$_GPC['paystatus']?'已付款':'未付款';
					$paytype=$paytypearr[$activity['cost']];
				}else{
					$itemtitle='全部';
					$itemmoney=0;
					$paystatus='';
					$paytype=$paytypearr[$activity['cost']];
				}
				$msg = array(
			        'first' => array(
			            'value' => "你好,{$member['nickname']}，你已经报名成功!",
			            "color" => "#4a5077"
			        ),
			        'keyword1' => array(
			            'value' => $member['nickname'],
			            "color" => "#4a5077"
			        ),
			        'keyword2' => array(
			            'value' => $activity['title'],
			            "color" => "#4a5077"
			        ),
			        'keyword3' => array(
			            'value' =>  date('Y年m月d日 H:i',$activity['stime']),
			            "color" => "#4a5077"
			        ),
			        'keyword4' => array(
			            'value' =>  date('Y年m月d日 H:i',$activity['etime']),
			            "color" => "#4a5077"
			        ),
			        'keyword5' => array(
			            'value' => $itemtitle.' '.$itemmoney.' '.$paytype.' '.$paystatus,
			            "color" => "#4a5077"
			        ),
			        'remark' => array(
			            'value' => '详情',
			            "color" => "#4a5077"
			        )
			    );


				$url=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$activity['id']));
    			$re = m('message')->sendTplNotice($openid,'FdTFrg_K8PCXXtOjNUkQMMJOPudSBfpZw5wnTqE1bds',$msg, $url);



				show_json(1,'报名成功!!');
			}						//暂时没有付费加入
			show_json(0,'报名失败,请重试');
		}
	}

	include $this->template('actApply');	//	报名
	exit;
}else if($op == 'delete'){

	$id=intval($_GPC['id']);
	empty($id) && show_json(0,'非法参数!');

	$activity=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));

	if (!$activity) {
		show_json(0,'找不到该活动!');
	}

	$sglist=pdo_fetchall('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	if ($sglist) {
		show_json(0,'该活动已经有人报名，请先取消报名再删除活动');
	}

	$re=pdo_delete('sz_yi_activity',array('id'=>$id,'uniacid'=>$_W['uniacid'],'openid'=>$openid));

	if ($re) {
		show_json(1,'删除成功');
	}
	show_json(0,'删除失败');

}else if($op == 'clear'){

	$id=intval($_GPC['id']);
	empty($id) && show_json(0,'非法参数!');

	$activity=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));

	if (!$activity) {
		show_json(0,'找不到该活动!');
	}

	$sglist=pdo_fetchall('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	foreach ($sglist as $key => $value) {
		if ($value['money'] > 0 && $value['paystatus'] == 1) {
			m('member')->setCredit($value['openid'],'credit2',$value['money']);
			pdo_update('sz_yi_activity_signup',array('paystatus'=>2,'refundtime'=>time(),'refunded'=>1,'deleted'=>1),array('uniacid'=>$_W['uniacid'],'id'=>$value['id']));
		}else{
			pdo_update('sz_yi_activity_signup',array('deleted'=>1),array('uniacid'=>$_W['uniacid'],'id'=>$value['id']));
		}
	}

	$sglist=pdo_fetchall('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	if (!$sglist) {
		show_json(1,'成功取消所有报名');
	}

	show_json(0,'取消报名失败');

}else if($op == 'paper'){
// 	$id=intval($_GPC['actid']);

// 	$act=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid  = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

// 	$info=pdo_fetch('select a.title,a.desc,a.stime,a.etime,a.province,a.city,a.area,a.address,s.data from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on a.id = s.actid where s.uniacid = :uniacid and s.openid = :openid and s.actid = :id and s.status = 1 and s.deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
// 	empty($info) && m('tools')->tip('你还没有报名或报名审核未通过!');
// 	$info['data']=unserialize($info['data']);

// 	$re=m('tools')->createActivityPoster($openid,7,$info);
// 	die('<img width="100%" height="auto" src="'.$re.'" />');



// }else if($op == 'test'){
	$id=$_GPC['actid'];
	$_GPC['act_id']=$id;
    $minfo=m('member')->getMember($openid);
        $posterid=7;
        $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('activity/center',array('op'=>'signin','id'=>$id,'mid'=>$minfo['id'])));
    exit(html_entity_decode('<img src="'.$pt.'" width="100%" height="auto" />'));
}else if ($op == 'change'){

	$id=intval($_GPC['id']);
	$act=m('activity')->getact($id);

	if ($ac == 'sub') {
		$id=intval($_GPC['id']);
	    $content=trim($_GPC['message']);

	    if (empty($id) || empty($content)) {
	        show_json(0,'非法参数!');
	    }
	    $muser=m('activity')->getMuser($openid);
		$muser['msgnum'] <= 0 && show_json(0,'你的短信数量不足,无法发送');
	    $activity=m('activity')->getact($id);

	    $msg = array(
	        'first' => array(
	            'value' => "活动变更提醒",
	            "color" => "#4a5077"
	        ),
	        'keyword1' => array(
	            'title' => '活动标题 ',
	            'value' => $activity['title'],
	            "color" => "#4a5077"
	        ),
	        'keyword2' => array(
	            'title' => '变更内容',
	            'value' => $content,
	            "color" => "#4a5077"
	        ),
	        'keyword3' => array(
	            'title' => '通知时间',
	            'value' =>  date('Y-m-d H:i:s'),
	            "color" => "#4a5077"
	        ),
	        'remark' => array(
	            'value' => "\r".$act['desc'],
	            "color" => "#4a5077"
	        )
	    );

	    $url=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$id));

	    $list=pdo_fetchall('select openid from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and status = 1 and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	    foreach ($list as $key => $value) {
	        $re=m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);
	    }
	    if ($re['errcode'] == 0) {
        pdo_update('sz_yi_member_user',array('msgnum'=>$muser['msgnum']-1),array('id'=>$muser['id']));
	        show_json(1,'发送成功！');
	    }else{
	        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
	    }

	}

	include $this->template('change');
	exit;
}else if ($op == 'withdraw'){

	$id=intval($_GPC['id']);

	if (empty($id)) {
		!$muser && m('tools')->tip('你还不是活动商家!');

		$expect=pdo_fetchcolumn('select sum(s.money) from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on a.id = s.actid where s.uniacid = :uniacid and a.openid = :openid and s.withdraw = 0 and s.paystatus = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

		$real=pdo_fetchcolumn('select sum(s.money) from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on a.id = s.actid where s.uniacid = :uniacid and s.withdraw = 0 and s.paystatus = 1 and a.etime >= :time and a.openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':time'=>time()));
	}else{

		$act=m('activity')->getact($id);

		$expect=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and withdraw = 0 and paystatus = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

		$expect=$expect-$expect*0.02;//扣除2%的手续费

		$real=pdo_fetchcolumn('select sum(s.money) from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on a.id = s.actid where s.uniacid = :uniacid and s.actid = :id and s.withdraw = 0 and s.paystatus = 1 and a.etime >= :time',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':time'=>time()));

		$real=$real-$real*0.02;//扣除2%的手续费

	}


	// 提现申请
    $applytype = intval($_GPC['applytype']);
    if (!empty($applytype)) {

    	$set = p('supplier')->getSet();
        $authority = $set['storepower'];

        $aurl = $this->createPluginMobileUrl('activity/activity', array('op' => 'withdraw'));
        if ($applytype == 2) { // 提现到微信
            if (!in_array('suppliermenu.wechat', $authority)) {
                exit('<script>alert("微信提现未开放！");location.href="'.$aurl.'"</script>');
            }
        } elseif ($applytype == 3) {
            if (!in_array('suppliermenu.balance', $authority)) {
                exit('<script>alert("余额提现未开放！");location.href="'.$aurl.'"</script>');
            }
        }

        $url = $this->createPluginMobileUrl('activity/activity');
        if ($real <= 0) {
            exit('<script>alert("没有可提现的金额!");location.href="'.$url.'"</script>');
        }

 		if (empty($id)) {
        	$sglist = pdo_fetchall('select s.id from ' . tablename('sz_yi_activity_signup') . ' s left join '.tablename('sz_yi_activity').' a on a.id  = s.actid where a.uniacid = :uniacid and a.openid= :openid and s.status = 1 and s.paystatus = 1 and s.withdraw = 0 and a.etime >= :time', array(':uniacid' => $_W['uniacid'],':openid'=>$openid,':time'=>time()));
 		}else{
        	$sglist = pdo_fetchall('select s.id from ' . tablename('sz_yi_activity_signup') . ' s left join '.tablename('sz_yi_activity').' a on a.id  = s.actid where a.uniacid = :uniacid and a.id= :id and s.status = 1 and s.paystatus = 1 and s.withdraw = 0 and a.etime >= :time', array(':uniacid' => $_W['uniacid'],':id'=>$id,':time'=>time()));
 		}
        if (empty($sglist)) {
            exit('<script>alert("没有可提现的订单!");location.href="'.$url.'"</script>');
        }

        $applysn = m('common')->createNO('supplier_apply', 'applysn', 'CA');

        $data = array(
            'uid' => $muser['uid'],
            'uniacid' => $_W['uniacid'],
            'apply_money' => $real,
            'apply_time' => time(),
            'status' => 0,
            'muser' => 1,
            'type' => $applytype,
            'applysn' => $applysn
         );

        if ($real > 0) {
            $res = pdo_insert('sz_yi_supplier_apply', $data);
        }
        if (!empty($res)) {
            foreach ($sglist as $ids) {
                $arr = array('withdraw' => 1);
                pdo_update('sz_yi_activity_signup', $arr, array('id' => $ids['id']));
            }
        }
        $url2 = $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdrawlist'));
        exit('<script>alert("提现申请已提交，请耐心等待!");location.href="'.$url2.'"</script>');
    }

	include $this->template('withdraw');
	exit;
}else if ($op == 'refund'){

	if ($_W['isajax']) {
		if ($ac == 'getRefund') {
			$id=$_GPC['id'];
			$pindex=max(1,intval($_GPC['page']));
			$psize=5;
			$sql='select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and paystatus = 2';
			$sql.=' limit '.($pindex -1) * $psize.','.$psize;
			$params=array(
				':uniacid'=>$_W['uniacid'],
				':id'=>$id
			);
			$list=pdo_fetchall($sql,$params);
			if($list){
				foreach ($list as $key => $value) {
					$list[$key]['refundtime']=date('Y-m-d H:i:s',$value['refundtime']);
					$list[$key]['info']=unserialize($value['data']);
				}
			}
			show_json(1,array('list'=>$list,'pagesize'=>$psize));
		}
		show_json(0,array('list'=>array(),'pagesize'=>$psize));
	}

	include $this->template('refund');
	exit;
}else if($op == 'order'){
	$id=intval($_GPC['id']);
	empty($id) && m('tools')->tip('非法参数!');
	$act=m('activity')->getact($id);

	if ($_W['isajax']) {
		if ($ac == 'get') {
			$pindex=max(1,intval($_GPC['page']));
			$psize=5;

			$sql='select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and actid = :id ';

			$sql.=' limit '.($pindex - 1) * $psize.','.$psize;
			$params=array(
				':uniacid'=>$_W['uniacid'],
				':openid'=>$openid,
				':id'=>$id
			);
			$list=pdo_fetchall($sql,$params);
			if ($list) {
				foreach ($list as $key => $value) {
					$list[$key]['paytime']=date('Y-m-d H:i:s',$value['paytime']);
				}
			show_json(1,array('list'=>$list));
			}
			show_json(0,array('list'=>array()));
		}else if($ac == 'refund'){
			$act['refund'] == 1 && show_json(0,'该活动不允许退款!');
			$exists=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
			!$exists && show_json(0,'没有这条报名名单');

			$exists['etime'] < time() && show_json(0,'活动已结束，不可退款');

			$re=pdo_update('sz_yi_activity_signup',array('paystatus'=>2,'refundtime'=>time(),'status'=>2),array('id'=>$exists['id'],'uniacid'=>$_W['uniacid']));

			if ($re) {
				show_json(1,'取消报名成功,等待退款');
			}
			show_json(0,'取消报名失败!');
		}else if($ac == 'withdraw'){
			$exists=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));
			!$exists && show_json(0,'没有这条报名名单');
			$exists['paystatus'] != 2 && show_json(0,'报名还未取消,无法退款');
			empty($exists['money']) && show_json(0,'活动未收取报名,费无需退款');
			!empty($exists['refunded']) && show_json(0,'报名费已经退款到余额钱包,无需重复操作');
			m('member')->setCredit($openid,'credit2',floatval($exists['money']));
			pdo_update('sz_yi_activity_signup',array('refunded'=>1),array('id'=>$exists['id']));
			show_json(1,'退款成功,报名费已退还到余额钱包');
		}
	}
	include $this->template('order');
	exit;
}else if($op =='getmuser'){
	$sgdata=pdo_fetchcolumn('select data from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and openid = :openid order by id desc ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
	$sgdata=unserialize($sgdata);
	foreach ($sgdata as $key => $value) {
		$sgdata[$key]['data']=trim($value['data']);
	}
	if ($sgdata) {
		show_json(1,$sgdata);
	}
		show_json(1,array());
	exit;
}else if($op == 'org'){

	$openid=$_GPC['openid'];
	//$openid='oSI4Lj0ZbzrlmWLszYBR-WiIWrjc';
	//$tmuser=m('activity')->getMuser($topenid);
	$accountinfo=pdo_fetch('select * from '.tablename('sz_yi_activity_account_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
	if ($_W['isajax']) {

		$type = $_GPC['type'];

		$pindex=max(1,intval($_GPC['page']));
		$psize=5;
		$params=array(
			':uniacid'=>$_W['uniacid'],
			':openid'=>$openid 		//王彬修改
		);
		$condition=' and uniacid = :uniacid and openid = :openid ';
		if ($type == 'act') {
			$table='sz_yi_activity';
		}else if($type == 'art'){
			$table='sz_yi_activity_article';
		}else if($type == 'mac'){

			//根据openid进行获取数据
			$table='sz_yi_match';
			$sqls.='select * from '.tablename($table).' where 1 ';
			$sqls.=$condition;
			//$sql.='where article_author ='.$tmu['realname'];//修改
			$sqls.=' order by id desc ';
			$sqls.=' limit '.($pindex - 1) * $psize.','.$psize;
			$lists=pdo_fetchall($sqls,$params);
		}

		$sql.='select * from '.tablename($table).' where 1 ';
		$sql.=$condition;
		//$sql.='where article_author ='.$tmu['realname'];//修改
		$sql.=' order by id desc ';
		$sql.=' limit '.($pindex - 1) * $psize.','.$psize;
		$list=pdo_fetchall($sql,$params);

		if ($list || $lists) {
			foreach ($list as $key => $value) {
				$list[$key]['icon']= $value['icon'] == 'undefined'?'undefined':tomedia($value['icon']);
				$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
				if ($type == 'act') {
					$list[$key]['link']=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$value['id']));
				}else if($type == 'art'){
					$list[$key]['link']=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$value['id']));
				}else if($type == 'mac'){
					$lists[$key]['link']=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$value['id']));
				}
			}
			show_json(1,array('list'=>$list,'lists'=>$lists));
			//show_json(1,array('list'=>$list));
		//}
	  }

		
		show_json(0,array('list'=>array()));
	}
   // if( isset($_GPC['to']) &&  $_GPC['to'] == 'to' ){
   //     include $this->template('org2');
   //     exit;
   // }
	include $this->template('org2');		//活动导航页
	exit;
}else if($op == 'sendmsg'){

	$id =$_GPC['id'];
	$act=m('activity')->getact($id);
	!$act && show_json(0,'活动不存在!');

	$params=array(
		":uniacid"=>$_W['uniacid'],
		":id"=>$id,
	);
	$list=pdo_fetchall('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and status = 1',$params);

	$_var_7 = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

    $dephp_4 = unserialize($_var_7['sets']);

    $name = $dephp_4['shop']['name'];
    $short=m('tools')->long2short($this->createPluginMobileUrl('activity/activity',array('op'=>'myAct','status'=>1)));
    $name = pdo_fetchcolumn("select name from hs_uni_account where uniacid = {$_W['uniacid']}");
	$total=count($list);
	$msgnum=intval($muser['msgnum']);
	$real=0;

	foreach ($list as $key => $value) {
		if ($msgnum <= 0) {
			show_json(0,'你的短信数量不足,发送终止');
		}
		$temp=unserialize($value['data']);
		foreach ($temp as $k => $v) {
			$temp[$k]['data']=trim($v['data']);
		}

	    $message = '【'.$name.'】尊敬的'.$temp['realname']['data'].',您参加的活动'.$act['title'].',将于'.date('Y-m-d H:i',$act['stime']).'在'.$act['province'].$act['city'].$act['area'].$act['address'].'开始,请提前做好准备,点击链接查看我报名的活动'.$short['short_url'];

	    $re=send_zhangjun($temp['mobile']['data'],$message);
	    if ($re['returnsms']['message'] == 'ok') {
	    	$real++;
	    	$msgnum--;
	    	//发送成功+1
	    }
	}


    if($real > 0){
    	pdo_update('sz_yi_member_user',array('msgnum'=>$msgnum),array('id'=>$muser['id']));
        show_json(1,'群发成功!');
    }else{
        show_json(0,'群发失败');
    }

}

include $this->template('activity');		//活动导航页