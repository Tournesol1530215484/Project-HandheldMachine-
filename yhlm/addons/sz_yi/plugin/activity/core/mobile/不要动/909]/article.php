<?php



global $_W, $_GPC;

$openid = m('user')->getOpenid();

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];

$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];

$muser=m('tools')->getMuser($openid);

$member=m('member')->getMember($openid);

if (!$muser) {

	// m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));	 		

}



// echo '<video src="http://music.163.com/song/media/outer/url?id=3950546.mp3" controls="controls">

// your browser does not support the video tag

// </video>'; 		 

if ($op == 'display') {

	$cate=pdo_fetchall('select * from '.tablename('sz_yi_activity_article_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));

	$banner=pdo_fetchall('select * from '.tablename('sz_yi_activity_adv').' where uniacid = :uniacid and status = 1 and place = 0 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));

	foreach ($banner as $key => $value) {

		$banner[$key]['thumb']=tomedia($value['thumb']); 	 	 		 	 	

	}



	if ($ac == 'get') {

		$pindex=max(1,intval($_GPC['page']));

		$psize=5;

		

		$params=array(

			':uniacid'=>$_W['uniacid']

		);



		$condition=' and uniacid = :uniacid and status = 1';



		if ($_GPC['cate']) {

			$params[':cate']=trim($_GPC['cate']);

			$condition.='  and type = :cate';

		}

		 	

		if ($_GPC['province']) {

			$params[':province']=trim($_GPC['province']);

			$condition.='  and province = :province ';

		}



		if ($_GPC['city']) { 	 	 	 

			$params[':city']=trim($_GPC['city']);

			$condition.='  and city = :city ';

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

					$condition.=' and ctime >= :stime and ctime <= :etime ';

					break;



				case '3':

					$time=m('time')->tomorrow();

					$params[':stime']=$time[0];	 	 

					$params[':etime']=$etime; 		 	 

					$condition.=' and ctime >= :stime and ctime <= :etime ';

					break;



				case '4': 		

					$time=m('time')->week();

					$params[':stime']=$time[0];	 	 

					$params[':etime']=$etime; 		 	 

					$condition.=' and ctime >= :stime and ctime <= :etime ';

					break;



				case '5': 		

					$time=m('time')->endWeek();

					$params[':stime']=$time[0];	 	 

					$params[':etime']=$etime; 		 	 

					$condition.=' and ctime >= :stime and ctime <= :etime ';

					break;



				case '6': 		

					$time=m('time')->month();

					$params[':stime']=$time[0];	 	 

					$params[':etime']=$etime; 		 	 

					$condition.=' and ctime >= :stime and ctime <= :etime ';

					break;

				

				default:

					if (!empty($_GPC['time'])) {



						$time=array(

							strtotime($_GPC['time']),

							strtotime($_GPC['etime'])

						);



						$params[':stime']=$time[0];	 	 

						$params[':etime']=$time[1]; 		 	 

						$condition.=' and ctime >= :stime and ctime <= :etime ';

					}



					break;

			}	



		$sql='select * from '.tablename('sz_yi_activity_article').' where 1 ';	

		$sql.=$condition;	 

		$sql.=' order by is_top desc ,toptime asc ,id desc';	 		  

		$sql.=' limit '.($pindex -1) * $psize .','.$psize;		  	 	 	 	 	  	 	

		$list=pdo_fetchall($sql,$params);	 		 		 	 	 

		if ($list) {	 

			foreach ($list as $key => $value) {	 	 

			$list[$key]['icon']=tomedia($value['icon']);

			$list[$key]['wechatCode']=tomedia($value['wechatCode']);	 

			$list[$key]['stime']=date('Y-m-d H:i:s',$value['stime']);	 

			$list[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);	 

			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

			$list[$key]['link']=$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$value['id']));

		}

			show_json(1,array('list'=>$list)); 

		}

			show_json(0,array('list'=>$list)); 

	}



}else if($op == 'add'){ 

	if (!$muser) {

		m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));	 		

	}

	$cate=pdo_fetchall('select * from '.tablename('sz_yi_activity_article_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));

	if (isset($_GPC['id']) && !empty($_GPC['id']) ) {	 	 	

		$temp=pdo_fetch('select * from '.tablename('sz_yi_activity_article').' where uniacid = :uniacid and openid = :openid and id = :id ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));

		!$temp && m('tools')->tip('找不到该文章!'); 	 		 

	}

	if ($ac == 'get') {

		$id=intval($_GPC['id']); 	 	

		if (empty($id)) {

			$id=intval($_GPC['copyid']);

		} 		 

		$list=array();

		$list=pdo_fetch('select * from '.tablename('sz_yi_activity_article').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

		if ($list) {

			$list['stime']=date('Y-m-d H:i',$list['stime']); 		

			$list['etime']=date('Y-m-d H:i',$list['etime']); 	 	

			show_json(1,$list);

		} 	 	 	

			show_json(0,array(0));

	}



	if ($ac == 'sub') {	  

        $id = intval($_GPC['id']);



        	if ($_GPC['what'] == 1) {

        	empty($_GPC['province']) && show_json(0,'请选择省份!');

        	empty($_GPC['city']) && show_json(0,'请选择城市!');

        	empty($_GPC['area']) && show_json(0,'请选择区域!');

        	$info=m('tools')->getAccountInfo($openid);

        		$data=array(

					'title'=>trim($_GPC['title']),

					'uniacid'=>$_W['uniacid'],

					'openid'=>$openid,

					'desc'=>trim($_GPC['desc']),

					'province'=>trim($_GPC['province']),

					'city'=>trim($_GPC['city']),

					'area'=>trim($_GPC['area']),

					'teamModel'=>trim($_GPC['teamModel']),

					'type'=>trim($_GPC['type']), 	 	

					'relOrg'=>trim($info['orgName']),

					'mobileOrg'=>trim($info['mobile']),

					'descOrg'=>trim($info['orgDesc']),

					'status'=>intval($_GPC['status'])	//发布状态

				);

        	}elseif ($_GPC['what'] == 2) {

        		$data=array(	 	 		 				 		 	 		

					'content'=>trim($_GPC['content'])

				);

        	}else{ 	  	

        		empty($_GPC['province']) && show_json(0,'请选择省份!');

        		empty($_GPC['city']) && show_json(0,'请选择城市!');

        		empty($_GPC['area']) && show_json(0,'请选择区域!');

        		$data=array(	 		 	 	 	 	 

					'title'=>trim($_GPC['title']),

					'uniacid'=>$_W['uniacid'],

					'openid'=>$openid,

					'desc'=>trim($_GPC['desc']),

					'province'=>trim($_GPC['province']),

					'city'=>trim($_GPC['city']),

					'area'=>trim($_GPC['area']),	 	 	 		 				 		 	 		

					'content'=>trim($_GPC['content']),	 	 	 		 				 		 	 		

					'teamModel'=>trim($_GPC['teamModel']),

					'type'=>trim($_GPC['type']), 	 	

					'relOrg'=>trim($info['orgName']),

					'mobileOrg'=>trim($info['mobile']),

					'descOrg'=>trim($info['orgDesc']),

					'status'=>intval($_GPC['status'])	//发布状态

				);

        	}

	 		

        			 	 	 			

		if (empty($id)) {

			$data['ctime']=time();

			// $oldart=m('activity')->getact($id,2);

            if ($muser['level'] == 3) {

                // if ($oldart['is_top'] <= 2) {

                    $data['is_top']=2;

                    $data['toptime']=time();

                // }

            }



        	pdo_insert('sz_yi_activity_article',$data);

        	$id=pdo_insertid();	 	 

        	if ($id) {

        		show_json(1);	 	 

        	}

        		show_json(0,'添加失败');

        }else{

        	//置顶-优先

        	$oldart=m('activity')->getact($id,2);

            if ($muser['level'] == 3) {

                if ($oldart['is_top'] <= 2) {

                    $data['is_top']=2;

                    $data['toptime']=time();

                }

            }



        	$re=pdo_update('sz_yi_activity_article',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));

        	if ($re) {	 	 

        		show_json(1,'更新成功!');	 	

        	}	 	

        		show_json(0,'更新失败');	 	 

        }  	 



	}



	include $this->template('artAdd');	//	发布文章

	exit;

}else if($op == 'detail'){



	$id=intval($_GPC['id']);
	$mid=$_GPC['mid'];
	if (!empty($mid)) {
		$mmember=m('member')->getMember($mid);
	}	 

	//print_r($mmember);	 		 	  	 	 	 		 		 	  		 	 

	$ptid=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and enabled = 1 and type = 2 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));	


	//获取发布者的所有信息
	//$postInfo=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid  = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],'uid'=>$ptid));
	

	//



	$activity=pdo_fetch('select * from '.tablename('sz_yi_activity_article').' where uniacid  = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));	//文章详情

	//发布者的详细信息

	//微信用户发布的文章

	$postInfo=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$activity['openid']));

	//微信模块
	$postInfoWeChat=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$activity['openid']));

	$accountinfo=pdo_fetch('select * from '.tablename('sz_yi_activity_account_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$activity['openid']));

	//print_r($accountinfo);
	 //$postInfo=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$activity['uid']));



	// pdo_fetchall('select m.avatar from '.tablename('sz_yi_activity_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.atid = :id and al.type = 2 order by al.id desc limit 0,50 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 

	//手机用户发布的文章


 	

 	$banner=pdo_fetchall('select * from '.tablename('sz_yi_activity_adv').' where uniacid = :uniacid and status = 1 and place = 1 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));

	foreach ($banner as $key => $value) {

		$banner[$key]['thumb']=tomedia($value['thumb']); 	 	 		 	 	

	}



	$reward=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ar.money,ar.remark from '.tablename('sz_yi_activity_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where ar.uniacid = :uniacid and ar.atid = :id and ar.type = 2 order by ar.id desc limit 0,3 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 	

	// var_dump($reware);

	$comment=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ac.content from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 2 and ac.status = 1 order by ac.id desc limit 0,3 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 



	$activity['comment']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 2 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));	 	

	$tmu=m('activity')->getMuser($activity['openid']);

	if ($tmu) {

		$isfavorite=m('tools')->checkFavorite($openid,$tmu['uid']);

	}else{

		$isfavorite=false; 		 		 	 	

	}	 		 	 	 	

	//文章粉丝
	$favorite=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_favorite').' where merchid = :uid and uniacid = :uniacid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$tmu['uid']));	 

	$favorite=$favorite?:0;

	//活动粉丝




	$totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_article').' where uniacid = :uniacid and openid = :openid',array('openid'=>$activity['openid'],':uniacid'=>$_W['uniacid']));	 	 	 	 	 	 	 	 	 	



	$like=pdo_fetchall('select m.avatar from '.tablename('sz_yi_activity_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.atid = :id and al.type = 2 order by al.id desc limit 0,50 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 



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

	$iePoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 3 and enabled = 1 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));

	$timed=false;

	if ($activity['afterTheStart'] == 1) {

		if ($activity['stime'] >= time()) {

			$timed=true;

		}	

	}



	$artmu=m('activity')->getMuser($activity['openid']);

		

	if ($artmu['logo']) {

		$imgUrl=tomedia($artmu['logo']);

	}else{

		$imgUrl=tomedia($_W['setting']['copyright']['sitelogo']);

	}



	$_W['shopshare']=array(

		'title'=> $activity['title'],

	    'imgUrl'=>$imgUrl,

	    'desc'=>$activity['desc'],

	    'link'=>$this->createPluginMobileUrl('activity/article',array('op'=>'detail','id'=>$activity['id'],'mid'=>$member['id']))

	);



	if ($ac == 'getlist') {

		$pindex=max(1,intval($_GPC['page']));

		$psize=5;

		$list=pdo_fetchall('select m.nickname,s.* from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.actid = :id and s.deleted = 0');

		if ($list) {

			foreach ($list as $key => $value) {

				$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

			}

			show_json(1,array('list'=>$list));

		}

		show_json(0,array('list'=>array())); 	



	}

 			 	


	include $this->template('artDetail');	//文章详情页

	exit;

}else if($op == 'notice'){



	include $this->template('artNotice');	//发文章前的提示页面

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

				$sql="select * from ".tablename('sz_yi_activity_article').' where uniacid = :uniacid and openid = :openid ';

				$sql.=' order by id desc '; 	 	

			}else if($type == '2'){

				$sql='select a.id,a.title,s.ctime,a.relOrg,a.browse from '.tablename('sz_yi_activity_share').' s left join '.tablename('sz_yi_activity_article').' a on a.id = s.actid where s.uniacid = :uniacid and s.openid = :openid and s.type = 2 group by s.actid ';

				$sql.=' order by s.id desc ';

			}



			$sql.=' limit '.($pindex - 1) * $psize.' , '.$psize;

			$list=pdo_fetchall($sql,$params);

			if ($list) {

				foreach ($list as $key => $value) {

					$list[$key]['ctime']=date('Y-m-d H:i',$value['ctime']);

					$list[$key]['url']=$this->createPluginMobileUrl('activity/article',array('op'=>'adminAct','id'=>$value['id'],'type'=>$type));

				}

				show_json(1,array('list'=>$list,'pagesize'=>count($list))); 			

			}

		$list=array(); 		

		show_json(0,array('list'=>$list,'pagesize'=>count($list)));

		} 			

	}





	include $this->template('artMy');		//我发布的文章

	exit;

}else if($op == 'adminAct'){





	$id=intval($_GPC['id']);

	$type=intval($_GPC['type']);



	empty($id) && m('tools')->tip('非法参数!');

	empty($type) && m('tools')->tip('非法参数!');



	if ($_GPC['type']==3) {

		$temp=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid  = :uniacid and openid = :openid and id = :id',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));

		!$temp && m('tools')->tip('找不到该活动');

	}



	$iePoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 2 and enabled = 1 order by displayorder desc limit 0,2',array(':uniacid'=>$_W['uniacid']));

	$share=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_share').' where uniacid = :uniacid and type = 2 and actid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));



	$comment=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 2 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));



	$sgPoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 1 and enabled = 1 order by displayorder desc limit 0,2',array(':uniacid'=>$_W['uniacid']));

	$activity=m('activity')->getact($id,2);



	$activity['signup']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));



	$activity['sign']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and status = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));





	include $this->template('artAdmin');	//文章管理

	exit;

}else if($op == 'settop'){



	$id=$_GPC['id'];

	$act=m('activity')->getact($id,2);



	if($ac == 'set'){



		show_json(0);

	}



	include $this->template('settop');

	exit;

}else if ($op == 'delete') {

	$id=intval($_GPC['id']);

	$exists=m('activity')->getact($id,2);	



	if ($exists['openid'] != $openid) {

		show_json(0,'没有该文章！');

	}

	 				 

	$re=pdo_delete('sz_yi_activity_article',array('id'=>$id,'uniacid'=>$_W['uniacid']));



	if ($re) {	 			

		show_json(1,'删除成功!');	 									

	}

	show_json(0,'删除失败!');

} 	 		 

include $this->template('article');		//文章导航页

