<?php
// use Grafika\Grafika; // Import package 				 		
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$op = empty($_GPC['op']) ? 'display': $_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid);
$member=m('member')->getMember($openid);

if ($_GPC['id']) {		//评选默认分享数据
	$sharedata=m('activity')->getact($_GPC['id'],18);
 	 				
	$_W['shopshare']=array(
		'link'=>$this->creatrePluginMobileUrl('match/match',array('op'=>'signupdetail','id'=>$actid,'sgid'=>$sgid,'mid'=>$member['id'])),
		'desc'=>$sharedata['content'],
		'imgUrl'=>tomedia($sharedata['cover']),
		'title'=>$sharedata['title'],

	);	 	
}

if($op == 'delete'){	 		 			 							 	
	$id=intval($_GPC['id']);	 		

	$match=m('activity')->getact($id,18);

	if ($match['openid'] != $openid) {
		show_json(0,'没有该比赛');
	}		

	$re=pdo_delete('sz_yi_match',array('id'=>$id,'openid'=>$openid));

	pdo_update('sz_yi_match_signup',array('deleted'=>1),array('actid'=>$id));	 		 	

	if ($re) {
		show_json(1,'删除成功!');	 		  		 		
	}
	show_json(0,'删除失败!');	 		

}		 			 		 
	 		
if ($op == 'display') {
	$cate=pdo_fetchall('select * from '.tablename('sz_yi_match_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));
	$banner=pdo_fetchall('select * from '.tablename('sz_yi_match_adv').' where uniacid = :uniacid and status = 1 and place = 0 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));
	foreach ($banner as $key => $value) {
		$banner[$key]['thumb']=tomedia($value['thumb']); 	 	 		 	 	
	}

	if ($ac == 'get') {	 	 	
		$pindex=max(1,intval($_GPC['page']));
		$psize=5;
			 		 		
		$params=array(	 	 			 
			':uniacid'=>$_W['uniacid']
		);

		$condition=' and uniacid = :uniacid ';

		if ($_GPC['cate']) {
			$params[':cate']=trim($_GPC['cate']);
			$condition.='  and cate = :cate';
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
			$condition .= " AND title LIKE '%{$_GPC['keywords']}%' ";
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

		$sql='select * from '.tablename('sz_yi_match').' where 1 ';	
		$sql.=$condition;	 
		$sql.=' order by is_top desc ,toptime asc ,id desc ';	 	 		 		 		 	 
		$sql.=' limit '.($pindex -1) * $psize .','.$psize;			 	 	  	 	 	 		  	
		$list=pdo_fetchall($sql,$params);
		if ($list) {
			foreach ($list as $key => $value) {
			if ($value['cover'] && $value['cover'] != 'undefined') {
				$list[$key]['cover']=tomedia($value['cover']);
			}	 		 	 	 	 	 	 	 	 	 
			$list[$key]['wechatCode']=tomedia($value['wechatCode']);
			$list[$key]['stime']=date('Y-m-d H:i:s',$value['stime']);
			$list[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);
			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
			$list[$key]['link']=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$value['id']));
		}
//            print_r($list);exit;
			show_json(1,array('list'=>$list)); 
		}

		show_json(0,array('list'=>$list)); 	 		 

	}
}else if($op == 'add'){

	

	$cate=pdo_fetchall('select * from '.tablename('sz_yi_match_type').' where uniacid = :uniacid and status = 1',array(':uniacid'=>$_W['uniacid']));	 		
	
	if (!$muser) {	 		
		m('tools')->tips('你还不是活动商家，请先成为活动商家',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>6)));	 		
	}

	if ($ac == 'get') {
		$id=intval($_GPC['id']); 	 	
		$list=array();
		$list=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		if ($list) {
			$list['thumbs']=unserialize($list['thumbs']);
			$tempthumbs=array();
			foreach ($list['thumbs'] as $key => $value) {
				$tempthumbs[$key]=[$value,tomedia($value)];
			}	 		 		
			$list['thumbs']=$tempthumbs;	 		 					 		 		 					
			$list['stime']=date('Y-m-d H:i',$list['stime']); 		
			$list['etime']=date('Y-m-d H:i',$list['etime']); 	 	
			show_json(1,$list);	 	 	 	 	 
		}
			show_json(0,array('province'=>'','city'=>''));	 
	}

	if (isset($_GPC['id']) && !empty($_GPC['id']) ) {
		$temp=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and openid = :openid and id = :id ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));
		!$temp && m('tools')->tip('找不到该活动!'); 	 		 
	}
	
	 		
	if ($ac == 'sub') {	  	

		$field=array(
			'name'=>['title'=>'姓名/名称','num'=>'1','must'=>'1',],
			'mobile'=>['title'=>'手机号码','num'=>'2','must'=>'1',],
			'slogan'=>['title'=>'口号','num'=>'3','must'=>'1',],
			'desc'=>['title'=>'简介','num'=>'4','must'=>'1',],	 		
		);

		 		
        $id = intval($_GPC['id']);	 	

 		$data=array(
			'uniacid'=>$_W['uniacid'],
			'openid'=>$openid,	 
			'uid'=>$muser['uid'],
			'title'=>trim($_GPC['title']),
			'field'=>serialize($field),	 	 	
			'stime'=>strtotime($_GPC['stime']),
			'etime'=>strtotime($_GPC['etime']),
			'cate'=>trim($_GPC['type']),	 		
			'province'=>trim($_GPC['province']),
			'city'=>trim($_GPC['city']),	 		
			'contact'=>trim($_GPC['desc']),	 	 	 		 				 		 	 		
			'content'=>trim($_GPC['content']),	 	 	 		 				 		 	 		
			'cover'=>trim($_GPC['post1'][0]),	 	 	 		 		 	 				 		 	 		
			'thumbs'=>serialize($_GPC['post1']),
		);
 		
		if (empty($id)) {
			$data['ctime']=time(); 		
        	pdo_insert('sz_yi_match',$data);
        	$id=pdo_insertid();
        	
        	if ($id) {	 	 		 	 	 	
        		show_json(1,'添加成功');	 	 	 	 
        	}	 		
        		show_json(0,'添加失败');	 	 
        }else{	 	 
        	unset($data['field']);	 		 			 	 		 	 	
        	$re=pdo_update('sz_yi_match',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
        	if ($re) {	 	 	
        		show_json(1,'更新成功!');
        	}	 		 	 	
        		show_json(0,'更新失败');
        } 	 	 		
	}

	include $this->template('matAdd');	//	发布活动
	exit;
}else if($op == 'detail'){

	$id=intval($_GPC['id']);	 	 	
	$match=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid  = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	$match['ctime']=date('Y-m-d H:i:s',$match['ctime']);
	$match['etime']=date('Y-m-d H:i:s',$match['etime']);
	$banner=unserialize($match['thumbs']);
//    print_r($match);exit;
	if ($ac == 'get') {

		$type = intval($_GPC['type']);
		$pindex=max(1,intval($_GPC['page']));
		$psize=10;

		$condition=' and uniacid = :uniacid ';
		$params=array(	 		
			':uniacid'=>$_W['uniacid'],
			':id'=>$id,
		);
		$sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0 and status = 1 ';

		if ($_GPC['keywords']) {	
			if (is_numeric($_GPC['keywords'])) {
				$condition .= " AND sgno = :keywords ";
				$params[':keywords']=trim($_GPC['keywords']);	 		
			}else{
				$condition .= " AND (data LIKE :keywords) ";
				$params[':keywords']="%{$_GPC['keywords']}%";	 			
			} 	
		}	 		 

		$sql.=$condition;	 	 		
		if ($type) {
			$orderby =' order by vote desc '; 		 	 			 	 			
		}else{	 			 					 		 		 		
			$orderby =' order by ctime desc '; 
		}	 		

		$sql.=$orderby;
		$sql.=' limit '.($pindex -1) * $psize .','.$psize;

		$list=pdo_fetchall($sql,$params);
//        $datas=$list[0]['data'];
//        $data=unserialize($datas);
//        $data1=$list[0]['thumbs'];
//        $data2=unserialize($data1);
//        print_r($datas);exit;

		if ($list) {

			foreach ($list as $key => $value) {
                $members=m('member')->getMember($value['openid']);

                $list[$key]['data']=unserialize($value['data']);
                $list[$key]['realname']=$members['realname'];
//				$list[$key]['data']=unserialize($value['data']);
				$list[$key]['link']=$this->createPluginMobileUrl('match/match',array('op'=>'signupdetail','id'=>$value['actid'],'sgid'=>$value['id']));
				$tempthumbs=unserialize($value['thumbs']);
				$thumbs=array(); 		 
				foreach ($tempthumbs as $k => $v) {
					$thumbs[]=tomedia($v);	 			 		 				
				}	 							 			 				 		  	 	
				$list[$key]['thumbs']=$thumbs;
			}
//        print_r($list);exit;
		show_json(1,array('list'=>$list));
		}	 				 			
		show_json(0,array('list'=>array()));
	}	

	/*
	$mid=$_GPC['mid'];
	if (!empty($mid)) {
		$mmember=m('member')->getMember($mid);	 
	}	 	 	 	 	 
	$ptid=pdo_fetchcolumn('select id from '.tablename('sz_yi_match_poster').' where uniacid = :uniacid and enabled = 1 and type = 2 order by displayorder desc ',array(':uniacid'=>$_W['uniacid']));

	if ($match['cost'] == 1) {
		if ($match['money'] == 0) {
			$match['money']=pdo_fetchcolumn('select money from '.tablename('sz_yi_match_payitem').' where uniacid = :uniacid and atid = :id and enabled = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		}	 	 
	}
	
	foreach ($banner as $key => $value) {
		$banner[$key]['thumb']=tomedia($value['thumb']); 	 	 		 	 	
	}
	
	$reward=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ar.money,ar.remark from '.tablename('sz_yi_match_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where ar.uniacid = :uniacid and ar.atid = :id and ar.type = 1 order by ar.id desc ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 	
	// var_dump($reware);	 	
	$comment=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ac.content from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 1 and ac.status = 1 order by ac.id desc limit 0,5 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	$tac=m('activity')->getact($id);	 	 
	$tmu=m('match')->getMuser($tac['openid']);
	if ($tmu) {	 			 	 	 	 	 
		$isfavorite=m('tools')->checkFavorite($openid,$tmu['uid']);
	}else{
		$isfavorite=false;
	}
	$match['comment']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id and ac.type = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 	  	 		 
	$favorite=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_favorite').' where merchid = :uid and uniacid = :uniacid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$tmu['uid']));
	$favorite=$favorite?:0;

	$totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match').' where uniacid = :uniacid and openid = :openid',array('openid'=>$match['openid'],':uniacid'=>$_W['uniacid']));
	
	$like=pdo_fetchall('select m.avatar from '.tablename('sz_yi_match_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.atid = :id and al.type = 1 order by al.id desc limit 0,32 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 

	$total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id)); 	
	$total=$total?:0;
	$match['stime']=date('Y.m.d H:i',$match['stime']);
	$match['etime']=date('Y.m.d H:i',$match['etime']);
	$match['ctime']=date('Y.m.d H:i',$match['ctime']);

	$signup=false;
	$exists=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and deleted = 0 and actid = :id and (status = 0 or status = 1) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
	if ($exists) {
		$signup=true;
	}

	$timed=false;
	if ($match['afterTheStart'] == 1) {
		if ($match['stime'] <= time()) {
			$timed=true;
		}	
	}

	$_W['shopshare']=array(
		'title'=> $match['title'],
	    'imgUrl'=>tomedia($match['icon']),
	    'desc'=>$match['desc'],
	    'link'=>$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$match['id'],'mid'=>$member['id']))
	);

	if ($ac == 'getlist') {
		$id=intval($_GPC['id']);
		$pindex=max(1,intval($_GPC['page']));
		$psize=5;
		$params=array(
			':uniacid'=>$_W['uniacid'],
			':id'=>$id
		);

		$sql='select s.* from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_member').' m on m.openid = s.openid where s.uniacid = :uniacid and s.actid = :id and s.deleted = 0 order by ctime desc ';
		if ($pindex == 1) {
			$psize=9;
			$sql.=' limit '.($pindex -1) * $psize.','.$psize;
		}else{
			$sql.=' limit '.(($pindex -1) * $psize + 4).','.$psize;
		}
		$exist=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));		 		 	 	 
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
						$link='location.href="'.$this->createPluginMobileUrl('match/card',array('tid'=>$tmember['id'],'actid'=>$id)).'"';	 	 
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

	}*/

	include $this->template('matDetail');	//活动详情页
	
	exit;
}else if($op == 'notice'){


	$msg = array(                
        'first' => array(
            'value' => $sstr,       
            "color" => "#4a5077"        
        ),
        'keyword1' => array(
            'value' =>  $match['title'],                                             
            "color" => "#4a5077"                                 
        ),           
        'keyword2' => array(                         
            'value' =>date('Y年m月d日 H:i',$match['stime']),      
            "color" => "#4a5077"                                         
        ),
        'keyword3' => array(            
            'value' =>$actitem['title'],                    
            "color" => "#4a5077"             
        ),                                                           
        'keyword4' => array(            
            'value' =>'你是第'.$thisno.'个签到的人',                    
            "color" => "#4a5077"             
        ),
        'keyword5' => array(            
            'value' =>date('Y年m月d日 H:i',$sgtime),                    
            "color" => "#4a5077"             
        ),      
        'remark' => array(                           
            'value' => "\r".$act['desc'],                   
            "color" => "#4a5077"            
        )
    );                                       

    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$_GPC['id']));
    $re = m('message')->sendTplNotice($openid,'d873CYt1NI68xWB2on9JepLcVJLzGCxPxlsoou3LWOg',$msg, $url);

	exit;
}else if($op == 'mymatch'){



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
				$sql="select * from ".tablename('sz_yi_match').' where uniacid = :uniacid and openid = :openid ';
				$sql.=' order by id desc '; 	 	 		 		 	
			}else if($type == '2'){	 			
				$sql="select a.*,s.id sgid from ".tablename('sz_yi_match').' a left join '.tablename('sz_yi_match_signup').' s on a.id=s.actid where s.uniacid = :uniacid and s.openid = :openid and s.deleted = 0';	 		
				$sql.=' order by s.id desc ';
			}else if($type == '3'){ 	 		 		 	 		 		 	  	 		 	 	 	 	 	 	 	 
				/*$sql='select a.id,a.title,s.ctime,a.stime from '.tablename('sz_yi_match').' a left join '.tablename('sz_yi_match_share').' s on a.id = s.actid where s.uniacid = :uniacid and s.openid = :openid and s.type = 1 group by s.actid ';	 	 
				$sql.=' order by s.id desc ';*/	 		 	 	 	
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
	 		 	 	if ($type == 1) {
						$list[$key]['url']=$this->createPluginMobileUrl('match/match',array('op'=>'adminMat','id'=>$value['id'],'type'=>$type));	 	 	 	 	 	 
	 		 	 	}else{	 			 	 	 	 	 	 	 	 	 	 	 	  	 	 	 
						$list[$key]['url']=$this->createPluginMobileUrl('match/match',array('op'=>'adminMat','sgid'=>$value['sgid'],'id'=>$value['id'],'type'=>$type));	 	 	 	 	 	 
	 		 	 	}	 		 	 	  	 			
				}	 		
				show_json(1,array('list'=>$list,'pagesize'=>count($list)));
			}
		$list=array(); 			 			
		show_json(0,array('list'=>$list,'pagesize'=>count($list)));
		} 			
	}

	include $this->template('matMy');		//我发布的活动
	exit;
}else if($op == 'adminMat'){		//管理
	
	$id=intval($_GPC['id']);
	$sgid=intval($_GPC['sgid']);

	$type=intval($_GPC['type']); 

	if ($type == 2) {
		$sginfo=m('match')->getMatchPlayer($id,$openid);
	}

	$match=m('activity')->getact($id,18);

			 	
	$comment=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.atid = :id order by ac.id desc limit 0,5 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));	 		


	/*	 		

	if ($ac == 'setpublic') {
		$sginfo=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and actid = :id',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
		if ($sginfo['public'] == 0) {
			$data=array('public'=>1);
			$str='公开名片';	 	 
		}else{	 	 
			$data=array('public'=>0);
			$str='不公开名片';
		}	 	 	 	 		 	 
		$tre=pdo_update('sz_yi_match_signup',$data,array('id'=>$sginfo['id'],'uniacid'=>$_W['uniacid']));
		if ($tre) {
			show_json(1,'已设置'.$str); 	 	  		 	 	 	
		} 			 		 	 
		show_json(0,'设置失败!'); 		
	}
	empty($id) && m('tools')->tip('非法参数!');	  			 
	empty($type) && m('tools')->tip('非法参数!');  		 		 

	if ($_GPC['type']==3) {
		$temp=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid  = :uniacid and openid = :openid and id = :id',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));
		!$temp && m('tools')->tip('找不到该活动');
	}

	if ($_GPC['type'] ==1) {
		$sginfo=m('match')->getSignUp($id,$openid);
	}

	$iePoster=pdo_fetchall('select * from '.tablename('sz_yi_match_poster').' where uniacid = :uniacid and type = 2 and enabled = 1 order by displayorder desc limit 0,2',array(':uniacid'=>$_W['uniacid']));
 		 
	$sgPoster=pdo_fetchall('select * from '.tablename('sz_yi_match_poster').' where uniacid = :uniacid and type = 1 and enabled = 1 order by displayorder desc limit 0,2',array(':uniacid'=>$_W['uniacid']));
	$match=m('activity')->getact($id,1); 	 			 		 	 			
	$match['signup']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	$match['sign']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and status = 1 and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));*/
	
		// $match['shorturl']=$re['short_url']; 	 				 	 	 
	include $this->template('matAdmin');	//活动管理
	exit;
}else if($op == 'trurl'){	 		
		$id = intval($_GPC['id']);
		$re=m('tools')->long2short($this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id,'mid'=>$member['id']))); 			//mid 若无上级锁定上级 
		if ($re['errcode'] == 0) {
			show_json(1,$re['short_url']);
 		}	 	 	 	
		show_json(0,'短链接获取失败,请重新获取'); 		 
}else if($op == 'draw'){
	
	/*$id=intval($_GPC['id']);
	$type=intval($_GPC['type']);
 		 	 	 
	empty($id) && m('tools')->tip('非法参数!');
	empty($type) && m('tools')->tip('非法参数!');*/

	include $this->template('matWithdraw');		//活动提现
	exit;
}else if($op == 'forwarding'){
	/*$mid=$_GPC['mid']?:0;
	$id=intval($_GPC['atid']);
	$type=intval($_GPC['type']);	 

	$match=m('activity')->getact($id,$type);
	if ($type == 1) {
		$link=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$match['id'],'mid'=>$member['id']));
	}else{
		$link=$this->createPluginMobileUrl('match/article',array('op'=>'detail','id'=>$match['id'],'mid'=>$member['id']));
	}	 	 		 	 	 	 	 	 	 	 
	$fd=array(	 		 	
		'title'=> $match['title'],
	    'imgUrl'=>$member['avatar'],
	    'desc'=>$match['desc'],
	    'link'=>$link	 
	);	 	

	$data=array(	 	 
		'uniacid'=>$_W['uniacid'],
		'atid'=>$id,
		'openid'=>$openid,
		'type'=>$type,
		'pid'=>$mid, 				 	 	
		'ctime'=>time()
	);		 	 	 		 	

	show_json(1,json_encode($fd)); */		 	 	 

}else if($op == 'apply'){  	
	

	$id=intval($_GPC['id']);
	$match=m('activity')->getact($id,18);
	$field=unserialize($match['field']);
	$sginfo=m('match')->getMatchPlayer($id,$openid);		 		 		
	
	$signup=false;
	// $exists=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and deleted = 0 and actid = :id ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
	if ($sginfo) {
		$signup=true;	 			
	} 

	$timed=false;	 			
	if ($match['ctime'] > time() || $match['etime'] < time()) {
			$timed=true;
	}	 				 			

	empty($id) && m('tools')->tip('非法参数!');
/*
		 		

	if ($match['cost'] == 1) {
		$payitem=pdo_fetchall('select * from '.tablename('sz_yi_match_payitem').' where uniacid = :uniacid and atid = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
	}
	$field=unserialize($match['field']); 	 		 		 

	$timed=false;	 	 	 	 	 	 	 	 	 	
	$signup=false;	 	 
	$exists=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and deleted = 0 and actid = :id and (status = 0 or status = 1) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
	if ($exists) {
		$signup=true;
	} 	 	
	if ($match['afterTheStart'] == 1) {
		if ($match['stime'] <= time()) {
			$timed=true; 			 		 	 		 	 	
		}	
	}*/	 	 	 	 	 	 	 	 	 	 		 	 	 

	// if ($_W['isajax']) {
		if ($ac == 'sub') {

			// $mid=intval($_GPC['mid']);	 	 		
			$tact=m('activity')->getact($id,18);

			if ($tact['ctime'] > time()) { 
				show_json(0,'比赛开始时间未到!');
			}

			if ($tact['etime'] < time()) {
				show_json(0,'比赛已结束!');
			}

			empty($_GPC['post1'][0]) && show_json(0,'请上传图片');
			$tempdata=$_GPC['postdata'];
			$postdata=array();
//            print_r($tempdata);exit;
			foreach ($tempdata as $key => $value) {
				$temp=m('match')->str2arr1($value);	 			 			
				$postdata[$temp['field']]=$temp;	 		 
			}

			$data=array(	 		 	 	 				 				 		 		  		 	 					 	 				
				'uniacid'=>$_W['uniacid'],	 			 			
				'openid'=>$openid,	 			
				'actid'=>$_GPC['id'], 	 	 	 		
				'thumbs'=>serialize($_GPC['post1']),
				'picdesc'=>$_GPC['picdesc'],
				'data'=>serialize($postdata),
				'ctime'=>time()	 			 		 					
			);
//            print_r($data);exit;
        	$data=m('tools')->batch_filter_Emoji($data);

			if ($tact['isAudit'] > 0) {	//需要审核
				$data['status']=0;		  		
			}else{
				$data['status']=1;		  		
			}			 				

			// $exists
			if ($signup) {
				$noticestr='修改报名信息';	 	 
				$data['sgno']=$sginfo['sgno'];	 			
				$re=pdo_update('sz_yi_match_signup',$data,array('id'=>$sginfo['id'],'uniacid'=>$_W['uniacid']));	
				if ($re) {
					$id=$sginfo['id'];	
	 			} 				 			 			
			}else{	 
				$noticestr='报名';	 	 
				$sgno=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
				$data['sgno']=$sgno?$sgno+1:1;		//序号	 			 			
				pdo_insert('sz_yi_match_signup',$data);
				$id=pdo_insertid();	 	 	 	 				 
			}

			if ($id) {	 		
				//发送

				$member=m('member')->getMember($openid);

				$msg = array(                 			 	 
			        'first' => array(
			            'value' => "你好,{$member['nickname']}，你已经成功".$noticestr."!",
			            "color" => "#4a5077"
			        ),
			        'keyword1' => array(            
			            'value' => $member['nickname'],
			            "color" => "#4a5077"
			        ),                              
			        'keyword2' => array(
			            'value' => $match['title'],                                             
			            "color" => "#4a5077"
			        ),
			        'keyword3' => array(            
			            'value' =>  date('Y年m月d日 H:i',$match['stime']),         
			            "color" => "#4a5077"             
			        ),
			        'keyword4' => array(            
			            'value' =>  date('Y年m月d日 H:i',$match['etime']),         
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

 	 		 	 	  		 	 	 	 	 	 	 
				$url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$match['id']));
    			$re = m('message')->sendTplNotice($openid,'FdTFrg_K8PCXXtOjNUkQMMJOPudSBfpZw5wnTqE1bds',$msg, $url);

				show_json(1,$noticestr.'成功!!');
			}						//暂时没有付费加入
			show_json(0,$noticestr.'失败,请重试');
		}
	// }	 		 
	 	
	include $this->template('matApply');	//	报名
	exit;
}else if($op == 'delete'){
	
	/*$id=intval($_GPC['id']);
	empty($id) && show_json(0,'非法参数!');

	$match=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and id = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));

	if (!$match) {
		show_json(0,'找不到该活动!');
	}

	$sglist=pdo_fetchall('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	if ($sglist) {
		show_json(0,'该活动已经有人报名，请先取消报名再删除活动');
	}

	$re=pdo_delete('sz_yi_match',array('id'=>$id,'uniacid'=>$_W['uniacid'],'openid'=>$openid));

	if ($re) {
		show_json(1,'删除成功');
	}
	show_json(0,'删除失败');*/

}else if($op == 'clear'){
	
	/*$id=intval($_GPC['id']);
	empty($id) && show_json(0,'非法参数!');

	$match=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and id = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));

	if (!$match) {
		show_json(0,'找不到该活动!');
	}

	$sglist=pdo_fetchall('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	foreach ($sglist as $key => $value) {
		if ($value['money'] > 0 && $value['paystatus'] == 1) {
			m('member')->setCredit($value['openid'],'credit2',$value['money']);
			pdo_update('sz_yi_match_signup',array('paystatus'=>2,'refundtime'=>time(),'refunded'=>1,'deleted'=>1),array('uniacid'=>$_W['uniacid'],'id'=>$value['id']));	 	 
		}else{	 		 		 	 	 	 	 	 	 	 
			pdo_update('sz_yi_match_signup',array('deleted'=>1),array('uniacid'=>$_W['uniacid'],'id'=>$value['id']));
		}	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 
	}	 	 		 

	$sglist=pdo_fetchall('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

	if (!$sglist) {
		show_json(1,'成功取消所有报名');
	}
 		 	
	show_json(0,'取消报名失败');*/

}else if($op == 'paper'){
// 	$id=intval($_GPC['actid']);

// 	$act=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid  = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));	
			 	
// 	$info=pdo_fetch('select a.title,a.desc,a.stime,a.etime,a.province,a.city,a.area,a.address,s.data from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on a.id = s.actid where s.uniacid = :uniacid and s.openid = :openid and s.actid = :id and s.status = 1 and s.deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
// 	empty($info) && m('tools')->tip('你还没有报名或报名审核未通过!');
// 	$info['data']=unserialize($info['data']);	
	
// 	$re=m('tools')->createActivityPoster($openid,7,$info);
// 	die('<img width="100%" height="auto" src="'.$re.'" />');

	

// }else if($op == 'test'){
/*	$id=$_GPC['actid']; 	 	 	 	 
	$_GPC['act_id']=$id;		 	 	 	 	 	 		 		                                               
    $minfo=m('member')->getMember($openid);         	 	 	 	 
        $posterid=7;        	 	 		 	             	 	 		
        $pt=m('tools')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('match/center',array('op'=>'signin','id'=>$id,'mid'=>$minfo['id']))); 		   	 	 	                             
    exit(html_entity_decode('<img src="'.$pt.'" width="100%" height="auto" />'));*/	 
}else if ($op == 'change'){	 	

	/*$id=intval($_GPC['id']);	 	
	$act=m('activity')->getact($id);	 	 	 	

	if ($ac == 'sub') {
		$id=intval($_GPC['id']);
	    $content=trim($_GPC['message']);
 			 		 	 
	    if (empty($id) || empty($content)) {
	        show_json(0,'非法参数!');   	 	                  
	    }
	    $muser=m('match')->
	    ($openid);	   	 		 	 
		$muser['msgnum'] <= 0 && show_json(0,'你的短信数量不足,无法发送');
	    $match=m('activity')->getact($id);	 	 	

	    $msg = array(                 			 	 
	        'first' => array(
	            'value' => "活动变更提醒",
	            "color" => "#4a5077"
	        ),
	        'keyword1' => array(            
	            'title' => '活动标题 ',
	            'value' => $match['title'],
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

	    $url=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$id));

	    $list=pdo_fetchall('select openid from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and status = 1 and deleted = 0',array(':uniacid'=>$_W['uniacid'],':id'=>$id));           
	    foreach ($list as $key => $value) {                              
	        $re=m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);                            
	    }        
	    if ($re['errcode'] == 0) {         
        pdo_update('sz_yi_member_user',array('msgnum'=>$muser['msgnum']-1),array('id'=>$muser['id']));   
	        show_json(1,'发送成功！');            
	    }else{               
	        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
	    }

	}*/
	
	include $this->template('change');
	exit;
}else if ($op == 'withdraw'){

	/*$id=intval($_GPC['id']);

	if (empty($id)) {
		!$muser && m('tools')->tip('你还不是活动商家!');

		$expect=pdo_fetchcolumn('select sum(s.money) from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on a.id = s.actid where s.uniacid = :uniacid and a.openid = :openid and s.withdraw = 0 and s.paystatus = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));	 	 	 	

		$real=pdo_fetchcolumn('select sum(s.money) from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on a.id = s.actid where s.uniacid = :uniacid and s.withdraw = 0 and s.paystatus = 1 and a.etime >= :time and a.openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':time'=>time()));
	}else{
		
		$act=m('activity')->getact($id); 		 		 	 	 	 	 	 	 	 		 	 	 	 	 	 	 	 	 

		$expect=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and withdraw = 0 and paystatus = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		$real=pdo_fetchcolumn('select sum(s.money) from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on a.id = s.actid where s.uniacid = :uniacid and s.actid = :id and s.withdraw = 0 and s.paystatus = 1 and a.etime >= :time',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':time'=>time()));	  		 	 		 	   		 		 	 	 	  	
	}


	// 提现申请 		 
    $applytype = intval($_GPC['applytype']);  	 	 	 		 		  	 	 	 			 	 	 		 	  	 
    if (!empty($applytype)) { 	  	 			  	

    	$set = p('supplier')->getSet();		 			 	 	 	  	  		 	 	 
        $authority = $set['storepower']; 	 		 	 	 	 		 	  	 

        $aurl = $this->createPluginMobileUrl('match/match', array('op' => 'withdraw')); 		 
        if ($applytype == 2) { // 提现到微信 	  	 		 
            if (!in_array('suppliermenu.wechat', $authority)) { 			 	
                exit('<script>alert("微信提现未开放！");location.href="'.$aurl.'"</script>');
            }
        } elseif ($applytype == 3) { 		 		 	 
            if (!in_array('suppliermenu.balance', $authority)) {
                exit('<script>alert("余额提现未开放！");location.href="'.$aurl.'"</script>');
            }
        } 	 	 	 	 		 	 	 	 

        $url = $this->createPluginMobileUrl('match/match');
        if ($real <= 0) {
            exit('<script>alert("没有可提现的金额!");location.href="'.$url.'"</script>');
        } 
 	
 		if (empty($id)) {
        	$sglist = pdo_fetchall('select s.id from ' . tablename('sz_yi_match_signup') . ' s left join '.tablename('sz_yi_match').' a on a.id  = s.actid where a.uniacid = :uniacid and a.openid= :openid and s.status = 1 and s.paystatus = 1 and s.withdraw = 0 and a.etime >= :time', array(':uniacid' => $_W['uniacid'],':openid'=>$openid,':time'=>time()));	 	 	 	 		 	 			 	 	 	 	 	 	 	  	 	 	 	 	 	 	 	 	 
 		}else{	 		 	 
        	$sglist = pdo_fetchall('select s.id from ' . tablename('sz_yi_match_signup') . ' s left join '.tablename('sz_yi_match').' a on a.id  = s.actid where a.uniacid = :uniacid and a.id= :id and s.status = 1 and s.paystatus = 1 and s.withdraw = 0 and a.etime >= :time', array(':uniacid' => $_W['uniacid'],':id'=>$id,':time'=>time()));	 	 	 	 		 	 			 	 	 	 	 	 	 	  	 	 	 	 	 
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
                pdo_update('sz_yi_match_signup', $arr, array('id' => $ids['id']));
            } 			 	 	 	 	 
        } 				 	 			 	 	 
        $url2 = $this->createPluginMobileUrl('suppliermenu/order', array('op' => 'withdrawlist'));
        exit('<script>alert("提现申请已提交，请耐心等待!");location.href="'.$url2.'"</script>');
    }*/

	include $this->template('withdraw');
	exit;
}else if ($op == 'refund'){ 	 	 	 

	/*if ($_W['isajax']) {
		if ($ac == 'getRefund') {
			$id=$_GPC['id'];
			$pindex=max(1,intval($_GPC['page']));
			$psize=5;
			$sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and paystatus = 2';
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
	}*/

	include $this->template('refund');
	exit;
}else if($op == 'order'){	 
	/*$id=intval($_GPC['id']);
	empty($id) && m('tools')->tip('非法参数!');	  			 
	$act=m('activity')->getact($id);

	if ($_W['isajax']) {
		if ($ac == 'get') {	 	 	 	 
			$pindex=max(1,intval($_GPC['page']));
			$psize=5;

			$sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and actid = :id ';

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
			$exists=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
			!$exists && show_json(0,'没有这条报名名单');	 	 		 

			$exists['etime'] < time() && show_json(0,'活动已结束，不可退款');

			$re=pdo_update('sz_yi_match_signup',array('paystatus'=>2,'refundtime'=>time(),'status'=>2),array('id'=>$exists['id'],'uniacid'=>$_W['uniacid']));		 	  	

			if ($re) {	 		 	 	 		 		 	 
				show_json(1,'取消报名成功,等待退款');	 	 	 	 	 	 	
			}	 		 	 
			show_json(0,'取消报名失败!');	 	 	 	 	 	 	 		 		  	 
		}else if($ac == 'withdraw'){
			$exists=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));
			!$exists && show_json(0,'没有这条报名名单');		 		 	 
			$exists['paystatus'] != 2 && show_json(0,'报名还未取消,无法退款'); 
			empty($exists['money']) && show_json(0,'活动未收取报名,费无需退款'); 		 	  	 
			!empty($exists['refunded']) && show_json(0,'报名费已经退款到余额钱包,无需重复操作'); 	 	 	 
			m('member')->setCredit($openid,'credit2',floatval($exists['money']));	 	
			pdo_update('sz_yi_match_signup',array('refunded'=>1),array('id'=>$exists['id']));  	
			show_json(1,'退款成功,报名费已退还到余额钱包');
		}	 	 	 		 	 	 	 	 	 	 		 	 		 	 	 	 	 	 	 	 	 
	}*/	 		 	 	 	 	 	 	 	 	 		 
	include $this->template('order');		 	 		 	 	 	 	 	 	  	  	
	exit;	 	 	 	
}else if($op =='getmuser'){
	$id=intval($_GPC['id']);	 			
	$sgdata=pdo_fetch('select data,thumbs,picdesc from '.tablename('sz_yi_match_signup').' where actid = :id and uniacid = :uniacid and openid = :openid and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id));
	if ($sgdata) {	 		
		$sgdata['data']=unserialize($sgdata['data']);	 		 
		$sgdata['thumbs']=unserialize($sgdata['thumbs']);
		if ($sgdata) {	 	 	 	 		 			 	 				 	 	 		 	 								 	 		 
			foreach ($sgdata['thumbs'] as $key => $value) {
				$sgdata['thumbs'][$key]=[tomedia($value),$value];	 				
			}
			show_json(1,$sgdata);	 	 	 		 
		}	 	 	 	 	 		 		 				 	 			 	 	 	 			
	}	 			 				 			 		 
	show_json(0,array());	
	exit; 	 
}else if($op == 'org'){

	/*$topenid=$_GPC['openid'];
	$tmuser=m('match')->getMuser($topenid);	 		 	 	 	 	 		  
	if ($_W['isajax']) {

		$type = $_GPC['type'];

		$pindex=max(1,intval($_GPC['page']));
		$psize=5;
		$params=array(
			':uniacid'=>$_W['uniacid'],
			':openid'=>$topenid
		);
		$condition=' and uniacid = :uniacid and openid = :openid ';

		if ($type == 'act') {	 	 
			$table='sz_yi_match';
		}else if($type == 'art'){	 	 
			$table='sz_yi_match_article';
		}
		$sql.='select * from '.tablename($table).' where 1 ';
		$sql.=$condition;	 	 			 
		$slq.=' order by id desc ';	 		 	 
		$sql.=' limit '.($pindex - 1) * $psize.','.$psize;
		$list=pdo_fetchall($sql,$params);
		if ($list) {
			foreach ($list as $key => $value) {
				$list[$key]['icon']= $value['icon'] == 'undefined'?'undefined':tomedia($value['icon']);
				$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);	 		 	 
				if ($type == 'act') {		 	 	 	 	 	 	 	 
					$list[$key]['link']=$this->createPluginMobileUrl('match/match',array('op'=>'detail','id'=>$value['id']));	 	 		 	 	 	 
				}else if($type == 'art'){		 	 	  	
					$list[$key]['link']=$this->createPluginMobileUrl('match/article',array('op'=>'detail','id'=>$value['id']));	 	 
				}	 	 	 	 
			}
			show_json(1,array('list'=>$list));
		}
		show_json(0,array('list'=>array()));	 	 	 	 
	}*/	 	 

	include $this->template('org');		//活动导航页 
	exit;	 		 	 	 			 	 	
}else if($op == 'sendmsg'){

	/*$id =$_GPC['id'];	 	 		  	
	$act=m('activity')->getact($id);
	!$act && show_json(0,'活动不存在!');

	$params=array(
		":uniacid"=>$_W['uniacid'],
		":id"=>$id,
	);
	$list=pdo_fetchall('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and status = 1',$params);
	
	$_var_7 = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

    $dephp_4 = unserialize($_var_7['sets']);

    $name = $dephp_4['shop']['name'];
    $short=m('tools')->long2short($this->createPluginMobileUrl('match/match',array('op'=>'myAct','status'=>1)));
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
    }*/

}else if($op == 'signupdetail'){

	$actid=intval($_GPC['id']);
	$sgid=intval($_GPC['sgid']);


	$act=m('activity')->getact($actid,11);//原数据5 

	$info=m('match')->getMatchPlayer($actid,$sgid);


	$comment=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ac.content from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid where ac.uniacid = :uniacid and ac.status = 1 and ac.sgid = :sgid and ac.atid = :id order by ac.id desc limit 0,5 ',array(':uniacid'=>$_W['uniacid'],':id'=>$actid,':sgid'=>$sgid));


	$like=pdo_fetchall('select m.avatar from '.tablename('sz_yi_match_like').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid where al.uniacid = :uniacid and al.sgid = :sgid and al.atid = :id order by al.id desc limit 0,32 ',array(':uniacid'=>$_W['uniacid'],':id'=>$actid,':sgid'=>$sgid)); 

	$reward=pdo_fetchall('select m.avatar,m.mobile,m.nickname,ar.money,ar.remark from '.tablename('sz_yi_match_reward').' ar left join '.tablename('sz_yi_member').' m on m.openid = ar.openid where ar.uniacid = :uniacid and ar.sgid = :sgid and ar.atid = :id and ar.type = 6 order by ar.id desc ',array(':uniacid'=>$_W['uniacid'],':id'=>$actid,':sgid'=>$sgid)); 	


	$info['data']=unserialize($info['data']);	 		
	$info['thumbs']=unserialize($info['thumbs']);	 	
	if ($_GPC['debug']) {
		var_dump($info);
		exit; 
	}		 	 	
	foreach ($info['thumbs'] as $key => $value) {	 	 	 
		$info['thumbs'][$key]=tomedia($value);	 	 	
	}	 			 				 			 	 	
	$banner=$info['thumbs'];		 	 		 	 	 	
	 					
	$_W['shopshare']['link']=$this->createPluginMobileUrl('match/match',array('op'=>'signupdetail','id'=>$actid,'sgid'=>$sgid,'mid'=>$member['id']));
	$_W['shopshare']['desc']=$info['data']['slogan']['data'];
	$_W['shopshare']['imgUrl']=$member['avatar'];	 			 		
	$_W['shopshare']['title']=$act['title'];	 			 	
	include $this->template('signupdetail');		//	 活动导航页 	 	 	
	exit;
}else if($op == 'signup'){	 								 			 		

	$id=$_GPC['id'];
	$sgid=$_GPC['sgid'];

	if ($ac == 'get') {		 				

		if ($_GPC['status'] == 2) {
		$player=pdo_fetchall('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':openid'=>$openid));		
			 				
		}
		if ($player) {
			foreach ($player as $key => $value) {
					$tempdata=unserialize($value['data']);		 		
					$player[$key]['data']=$tempdata;	 	 		
					$player[$key]['info']=json_encode(array('info'=>$tempdata));	 
			}
			show_json(1,array('list'=>$player));				 					 					 		 		 				 								 
		}		 						 				
		show_json(0,array('list'=>array()));		 		 		
	}	 		


	include $this->template('matSignup');		//活动导航页 	 	 	
	exit;
}else if($op == 'sort'){

	$pindex=max(1,intval($_GPC['page']));
	$psize=20;
	$id = intval($_GPC['id']);

	empty($id)  && show_json(0);

	/*$pindex=max(1,intval($_GPC['page']));
	$psize=10;*/

	$condition=' and uniacid = :uniacid ';
	$params=array(	 		
		':uniacid'=>$_W['uniacid'],
		':id'=>$id,
	);
	$sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and deleted = 0 and status = 1 ';
	

	if ($_GPC['keywords']) {	
		if (is_numeric($_GPC['keywords'])) {
			$condition .= " AND sgno = :keywords ";
			$params[':keywords']=trim($_GPC['keywords']);	 		
		}else{
			$condition .= " AND (data LIKE :keywords) ";
			$params[':keywords']="%{$_GPC['keywords']}%";	 			
		} 	
	}	 		 

	$orderby =' order by vote desc '; 		 	 	 			
	$sql.=$condition;	 	 		
	$sql.=$orderby;	 	 		

	$sql.=' limit '.($pindex -1) * $psize .','.$psize;
	

	$list=pdo_fetchall($sql,$params);
//    print_r($list);exit;
	if ($list) {	 	 	
		$no=1;
		foreach ($list as $key => $value) {
            $members=m('member')->getMember($value['openid']);
            if(empty($members['realname'])){
                $list[$key]['realname']=$members['nickname'];
            }else{
                $list[$key]['realname']=$members['realname'];
            }


			$list[$key]['data']=unserialize($value['data']);
			$list[$key]['link']=$this->createPluginMobileUrl('match/match',array('op'=>'signupdetail','id'=>$value['actid'],'sgid'=>$value['id']));
			$list[$key]['no']=($pindex-1) * $psize +$no;
			$no++;	 		
			$tempthumbs=unserialize($value['thumbs']);
			$thumbs=array(); 		 	 		
			foreach ($tempthumbs as $k => $v) {	 			 			
				$thumbs[]=tomedia($v);	 			 	 	 			 				
			}	 							 			 				 		  	 	
			$list[$key]['thumbs']=$thumbs;				
		}
//        print_r($list);exit;
	show_json(1,array('list'=>$list,'pagesize'=>$psize));	 	 	
	}	 	
	show_json(0,array('list'=>$list,'pagesize'=>$psize));
}else if($op == 'vote'){
	$id=$_GPC['id'];
	$sgid=$_GPC['sgid'];

	empty($id) && shop_json(0,'非法参数！');
	empty($sgid) && shop_json(0,'非法参数！');	 	
	$act=m('activity')->getact($id,18);

	$sginfo=m('match')->getMatchPlayer($id,$sgid);

	$data=array(
		'uniacid'=>$_W['uniacid'],
		'atid'=>$id,
		'sgid'=>$sgid,	 		
		'openid'=>$openid,
		'money'=>floatval($_GPC['money']),
		'number'=>1,
		'type'=>$_GPC['type'],
		'ctime'=>time(),
	);

	$time=m('time')->today();
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':openid'=>$openid,
		':id'=>$id,
		':stime'=>$time[0],
		':etime'=>$time[1],	 		
	);

	$exists=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_vote').' where uniacid = :uniacid and openid = :openid and atid = :id and ctime > :stime and ctime < :etime ',$params);

    if ($act['votetype'] == 1 && $exists > 0 ) {
		show_json(0,'你今天已经投过票了!');
	}
	 		
	if ($act['votetype'] == 2) {
		if ($act['votenum'] <= $exists) {
			show_json(0,'你今天的投票次数已经使用完毕！');
		}
	}

	pdo_insert('sz_yi_match_vote',$data);
	$re=pdo_insertid();	 		
	if ($re) {
		pdo_update('sz_yi_match_signup',array('vote'=>intval($sginfo['vote'])+1),array('id'=>$sgid,'uniacid'=>$_W['uniacid']));
		$newinfo=m('match')->getMatchPlayer($id,$sgid);	 		 	
		show_json(1,array('vote'=>$newinfo['vote'],'no'=>$newinfo['no']));
	}		 			 		
	 	 					 			 	
	show_json(0,'投票失败');	 	 	
}else if($op == 'getposter'){

	$id=$_GPC['id'];
	$sgid=$_GPC['sgid'];  
		 	                                           
    $minfo=m('member')->getMember($openid);         

    $posterid=11;   //元数据 11                  

    $pt=m('match')->createCardPoster($minfo['openid'],$posterid,$this->createPluginMobileUrl('match/match',array('op'=>'signupdetail','id'=>$id,'mid'=>$minfo['id'],'sgid'=>$sgid)));
    
    //$pts= m('match')->createCardPoster($minfo['openid'],$posterid);         
	show_json(1,$pt);
}
	include $this->template('match');		//活动导航页 	 	 	
