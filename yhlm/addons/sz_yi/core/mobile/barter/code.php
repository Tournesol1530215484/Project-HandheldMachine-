<?php



if (!defined('IN_IA')){



    exit('Access Denied');



}

global $_W,$_GPC;



$openid=m('user')->getOpenid(); 

$popenid=m('user')->islogin(); 

$openid = $openid?$openid:$popenid;

$uniacid=$_W['uniacid'];

if (empty($_GPC['merch_uid'])) {

	$goodsid=intval($_GPC['id']);  

	$merch_uid=pdo_fetchcolumn('select supplier_uid from '.tablename('sz_yi_goods').' where uniacid = :uniacid and id = :goodsid  and type = 8 ',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$goodsid)); 

}else{

	$merch_uid=intval($_GPC['merch_uid']);

}



$dm=p('bonus')->getMerch($openid,'deal'); 

//$openid="oSI4Lj_lAcxx5BSaa4wwfdOg02Rg";



$op=empty($_GPC['op'])?'display':$_GPC['op'];



if ($_W['isajax']) {

	if ($op == 'getinfo') {

		$info=[];  

		$info['credit3']=m('member')->getCredit($openid,'credit3');



		$info['freeze_credit3']=m('member')->getCredit($openid,'freeze_credit3'); 

		$info['currency_credit3']=m('member')->getCredit($openid,'currency_credit3'); 

		$info['total']['friend']['put']=0;

		$info['total']['friend']['put']=pdo_fetchcolumn('select sum(currency) from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 14 and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 



		$info['total']['friend']['get']=0;

		$tempget=pdo_fetchall('select currency from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type = 13 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 

		if (!empty($tempget)) { 

			foreach ($tempget as $key => $value) {

				$tempget[$key]['currency']=trim($value['currency'],'-'); 

				$info['total']['friend']['get']+=intval($tempget[$key]['currency']);	 

			}

		}      

		$info['total']['saler']=0;      

		$info['total']['saler']=pdo_fetchcolumn('select sum(currency) from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 2 and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));     





 		if (!$dm) {

 			$info['total']['refund']=0;      

 		}else{

			//$info['total']['refund']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' where uniacid = :uniacid and status = -1 and supplier_uid = :supplier_uid',array(':uniacid'=>$_W['uniacid'],':supplier_uid'=>$dm['uid'])); 
			$info['total']['refund']=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where uniacid = :uniacid and status = -1 and supplier_uid = :supplier_uid',array(':uniacid'=>$_W['uniacid'],':supplier_uid'=>$dm['uid'])); 	 		 	 	 

 		}		 	 	 	 	



 		$info['total']['refunduse']=pdo_fetchcolumn('select sum(goodsprice) from '.tablename('sz_yi_order').' where uniacid = :uniacid and openid = :openid and refundtime != 0 and isexchange = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

 		//获取所有的易货码充值记录

 		$info['total']['recharge']=pdo_fetchcolumn("select sum(money) from " . tablename('sz_yi_barter_log') . " log  where    uniacid = :uniacid and openid = :openid ",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

 		//$info['total']['recharge'] = pdo_fetchcolumn("select sum(money) from " . tablename('sz_yi_barter_log') . " log  where  uniacid = :uniacid and openid = :openid ",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));



		$info['total']['use']=0;  

		//$tempUse=pdo_fetchall('select currency from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 
		$info['total']['use']=pdo_fetchcolumn('select sum(goodsprice ) from '.tablename('sz_yi_order').' where status not in (-1,0) and uniacid =:uniacid and  openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

		// if (!empty($tempUse)) {

		// 	foreach ($tempUse as $key => $value) {

		// 		$value['currency']=trim($value['currency'],'-'); 

		// 		$info['total']['use']+=intval($value['currency']);		

		// 	}

		// }



		// $fans=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 2',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
		//计算红包
		$cash=pdo_fetchcolumn(' select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and bonustype = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));


   		 $code=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid  and bonustype = 2 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

		//$fans=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 1 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    //$info['fanscode']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 2 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));



        //$me=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and bonustype = 2 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        $me=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 2 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        //$fans=sprintf('%.3f',$cash +  $fans); 

    	$me=sprintf('%.3f',$code + $me); 

		$info['total']['fans']=floatval($fans)+floatval($me);	//粉丝购物奖励



		$info['openid']=$openid;

		show_json(1,array('info'=>$info));

	}else if ($op == 'post'){   



	} 

}

	

if ($op == 'detail') {

	$ac=$_GPC['ac'];

	if ($ac=='put') {

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;  

		if ($_W['isajax']) { 

			$list=pdo_fetchall('select tl.*,m.realname,m.nickname from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.sponsor_openid = m.openid where tl.uniacid = :uniacid and tl.recipient_openid = :openid order by tl.id desc limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$uniacid,':openid'=>$openid)); 

			if (!empty($list)) {  

				foreach ($list as $key => $value) {  

					$list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['transfertime']); 

				} 

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		}



	}else if($ac=='get'){  



		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;



		if ($_W['isajax']) { 

			$list=pdo_fetchall('select tl.*,m.realname,m.nickname from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.recipient_openid = m.openid where tl.uniacid = :uniacid and tl.sponsor_openid = :openid order by tl.id desc limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$uniacid,':openid'=>$openid)); 

			if (!empty($list)) {  

				foreach ($list as $key => $value) {  

					$list[$key]['time']=date('Y-m-d H:i:s',$list[$key]['transfertime']);  

				}  

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		}



	}else if($ac=='saler'){ 

		

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;   

		if ($_W['isajax']) {  

			$list=pdo_fetchall('select cl.* from '.tablename('sz_yi_barter_code_log').' cl where cl.uniacid = :uniacid and cl.type = 2 and cl.openid = :openid order by cl.id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));



			if (!empty($list)) {    

				foreach ($list as $key => $value) {

					$list[$key]['dealtime']=date('Y-m-d H:i:s',$list[$key]['dealtime']);

				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }    

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		} 

		 

	}else if($ac=='use'){   

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;   

		if ($_W['isajax']) {  



			$list=pdo_fetchall('select * from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type = 1 order by id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 



			if (!empty($list)) {    

				foreach ($list as $key => $value) {

					$list[$key]['dealtime']=date('Y-m-d H:i:s',$list[$key]['dealtime']);

				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }    

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		}

		 

	}else if($ac=='refund'){   			//面向商家

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;   	 	 	



		if ($_W['isajax']) {  



			$list=pdo_fetchall('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :supplier_uid and status = -1 order by id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':supplier_uid'=>$dm['uid'])); 



			if (!empty($list)) {    	 	 

				foreach ($list as $key => $value) {

					$tm=m('member')->getMember($value['openid']);

					$list[$key]['nickname']=$tm['nickname'];

					$list[$key]['refundtime']=date('Y-m-d H:i:s',$list[$key]['refundtime']);

				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }    

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		}

		

	}else if($ac=='refunduse'){   		//面向用户

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;   	 	 	



		if ($_W['isajax']) {  



			$list=pdo_fetchall('select o.*,m.nickname from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.uniacid = :uniacid and o.openid = :openid and o.status = -1 and o.isexchange = 1 order by o.refundtime desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));	 	 	 



			if (!empty($list)) {    	 	 

				foreach ($list as $key => $value) {

					$list[$key]['refundtime']=date('Y-m-d H:i:s',$list[$key]['refundtime']);

				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }    

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		}

		 

	}
	else if($ac=='recharge'){  

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;   

		   if ($_W['isajax']) {  

				$list=pdo_fetchall("select * from " . tablename('sz_yi_barter_log') . " log  where  uniacid = :uniacid and openid = :openid ",array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

			if (!empty($list)) {    

				foreach ($list as $key => $value) {

					$list[$key]['createtime']=date('Y-m-d H:i:s',$list[$key]['createtime']);

					 if ($list[$key]['rechargetype'] == 'system') {

		                $list[$key]['rechargetype'] = "后台";

		            } else if ($list[$key]['rechargetype'] == 'wechat') {

		                $list[$key]['rechargetype'] = "微信";

		            } else if ($list[$key]['rechargetype'] == 'alipay') {

		                $list[$key]['rechargetype'] = "支付宝";

		            } else if ($list[$key]['rechargetype'] == 'system1') {

		                $list[$key]['rechargetype'] = "后台扣款";

		            }


				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    } else{
		    	show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 
		    }   
		   }


	}
	
	
include $this -> template('barter/code_detail');  

exit; 

}else if($op == 'ad'){

	if ($_W['isajax']) {

        $status = $_GPC['status'];



        $pindex=max(1,intval($_GPC['page']));

        $psize=10;

        $params=array(

            ':uniacid'=>$_W['uniacid'],              

            ':oepnid'=>$openid

        );

        if ($status == 1) {

            $sql='select ob.ctime,ob.money,am.thumb,am.title,ob.openid from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.bonustype = 2 ';

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;

        }else if($status == 2){

            $sql='select ob.ctime,ob.money,am.thumb,am.title,ob.openid from '.tablename('sz_yi_ad_bonus_log').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.bonusType = 2';                          

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;

        }    

        $list=pdo_fetchall($sql,$params);        

        if ($list) {                         

            foreach ($list as $key => $value) {

                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

                $tthumb=unserialize($value['thumb']);

                $thumb=array();

                foreach ($tthumb as $k => $v) {

                    $thumb[]=tomedia($v);

                }                    

                $list[$key]['thumb']=$thumb;

            }

        show_json(1,array('list'=>$list,'pagesize'=>$psize));

        }

        show_json(0,array('list'=>array(),'pagesize'=>$psize));

    }



	include $this -> template('barter/codeAd');

	exit;       

}else if($op == 'other'){



	if ($_W['isajax']) {

        $status = $_GPC['status'];



        $pindex=max(1,intval($_GPC['page']));

        $psize=10;

        $params=array(

            ':uniacid'=>$_W['uniacid'],              

            ':oepnid'=>$openid

        );

        if ($status == 1) {

            // $sql=' ';

            // $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;

        	show_json(0,array('list'=>array(),'pagesize'=>$psize));

        }else if($status == 2){	 	 	

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_activity_bonus_log').' ob left join '.tablename('sz_yi_member').' am on am.openid = ob.consumers left join '.tablename('sz_yi_activity_recharge_log').' r on r.id = ob.logid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.cate = 1 and r.paytype = 3 ';                          

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;	 	 	

        }else if($status == 3){	 	 	

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_activity_bonus_log').' ob left join '.tablename('sz_yi_member').' am on am.openid = ob.consumers left join '.tablename('sz_yi_activity_recharge_log').' r on r.id = ob.logid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.cate = 2 and r.paytype = 3 '; 	 	                          

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;	 	 	

        }else if($status == 4){	 		 	  	 		 	

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_settop_bonus_log').' ob left join '.tablename('sz_yi_activity_settop_log').' r on r.id = ob.logid left join '.tablename('sz_yi_member').' am on am.openid = r.openid where ob.uniacid = :uniacid and ob.openid = :oepnid and r.paytype = 3 '; 			 	 		 		  	 		 	  	                          

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;	 	 		 	 		 	 	

        }else if($status == 5){	 		 	  	 		 	

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_settop_bonus_log').' ob left join '.tablename('sz_yi_activity_settop_log').' r on r.id = ob.logid left join '.tablename('sz_yi_member').' am on am.openid = r.openid where ob.uniacid = :uniacid and ob.openid = :oepnid and r.paytype = 3 '; 			 	 		 		  	 		 	  	                          

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;	 	 		 	 		 	 	

        }    

        $list=pdo_fetchall($sql,$params);        

        if ($list) {                         

            foreach ($list as $key => $value) {

                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

                $tthumb=unserialize($value['thumb']);

                $thumb=array();

                foreach ($tthumb as $k => $v) {

                    $thumb[]=tomedia($v);

                }                    

                $list[$key]['thumb']=$thumb;

            }

        show_json(1,array('list'=>$list,'pagesize'=>$psize));

        }

        show_json(0,array('list'=>array(),'pagesize'=>$psize));

    }



	include $this -> template('barter/codeOther');

	exit;       

}     



include $this -> template('barter/code');

