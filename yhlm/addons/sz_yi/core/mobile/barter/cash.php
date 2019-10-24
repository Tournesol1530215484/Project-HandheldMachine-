<?php



if (!defined('IN_IA')){



    exit('Access Denied');



}

global $_W,$_GPC;



$openid=m('user')->getOpenid(); 

$popenid=m('user')->islogin(); 

$openid = $openid?$openid:$popenid;

$set=pdo_fetch('select sets from '.tablename('sz_yi_sysset').' where uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));

$set=unserialize($set['sets']);

$ratio=floatval($set['bart']['withdraw'] / 100); 	  	 	 	

$op=empty($_GPC['op'])?'display':$_GPC['op'];

if ($_W['isajax']) {

	if ($op == 'get') {

		$info['credit2']=m('member')->getCredit($openid,'credit2'); 

		$info['total']['recharge']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_member_log').' where type = 0 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

		// $uids=pdo_fetchall('select uid from '.tablename('sz_yi_perm_user').' where openid = :openid and uniacid = :uniacid',array(':openid'=>$openid,':uniacid'=>$_W['uniacid']));

		// if (!empty($uids)) {

		// 	foreach ($uids as $key => $value) {

		// 			$temp[]=$value['uid'];

		// 	}

		// }else{

		// 	$temp[]=0;

		// }



		// $uidStr=implode(',', $temp);  

		$info['total']['withdraw']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_member_log').' where type = 1 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));   

		$info['total']['shop']=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where isexchange = 0 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 

        $info['total']['olddispatchprice']=pdo_fetchcolumn('select sum(olddispatchprice) from '.tablename('sz_yi_order').' where isexchange = 0 and uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));  

 		$info['total']['refunduse']=pdo_fetchcolumn('select sum(price) from '.tablename('sz_yi_order').' where uniacid = :uniacid and openid = :openid and refundtime != 0 and isexchange = 0 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

		       



        $info['total']['goods']=0;      //货款 

        $info['total']['other']=0;      //其他 

        $info['openid']=$openid;

        // $fans=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 1',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));



        //$me=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and bonustype = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        // show_json(0,'123');

        //计算红包
        $cash=pdo_fetchcolumn(' select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and bonustype = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));


         $code=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid  and bonustype = 2 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $fans=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 1 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $me=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 2 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $fans=sprintf('%.3f',$cash +  $fans); 

        $me=sprintf('%.3f',$code + $me); 

		$info['total']['fans']=floatval($fans)+floatval($me);	//粉丝购物奖励 



		$set = m('common')->getSysset(array(

        'shop',

        'pay',

        'trade'

    ));

    if (!empty($set['trade']['closerecharge'])) {

        show_json(-1, '系统未开启账户充值!');

    }

    pdo_delete('sz_yi_member_log', array(

        'openid' => $openid,

        'status' => 0,

        'type' => 0,

        'uniacid' => $_W['uniacid']

    ));

    $logno = m('common')->createNO('member_log', 'logno', 'RC');

    $log   = array(

        'uniacid' => $_W['uniacid'],

        'logno' => $logno,

        'title' => $set['shop']['name'] . "会员充值",

        'openid' => $openid,

        'type' => 0,

        'createtime' => time(),

        'status' => 0

    );

    pdo_insert('sz_yi_member_log', $log);

    $logid  = pdo_insertid();

    $credit = m('member')->getCredit($openid, 'credit2');

    $wechat = array(

        'success' => false

    );

    if (is_weixin()) {

        if (isset($set['pay']) && $set['pay']['weixin'] == 1) {

            load()->model('payment');

            $setting = uni_setting($_W['uniacid'], array(

                'payment'

            ));

            if (is_array($setting['payment']['wechat']) && $setting['payment']['wechat']['switch']) {

                $wechat['success'] = true;

            }

        }

    }

    $alipay = array(

        'success' => false

    );

    if (isset($set['pay']['alipay']) && $set['pay']['alipay'] == 1) {

        load()->model('payment');

        $setting = uni_setting($_W['uniacid'], array(

            'payment'

        ));

        if (is_array($setting['payment']['alipay']) && $setting['payment']['alipay']['switch']) {

            $alipay['success'] = true;

        }

    }



    $pluginy = p('yunpay');

    $yunpay = array(

        'success' => false

    );

    if ($pluginy) {

        $yunpayinfo = $pluginy->getYunpay();



        if (isset($yunpayinfo) && @$yunpayinfo['switch']) {

            $yunpay['success'] = true;

        }

    }



    show_json(1, array(

        'set' => $set,

        'logid' => $logid,

        'isweixin' => is_weixin(),

        'wechat' => $wechat,

        'alipay' => $alipay,

        'credit' => $credit,

        'yunpay' => $yunpay,

        'info'=>$info

    ));





		// show_json(1,array('info'=>$info));	

	} 

}

if ($op == 'detail') {

	$ac=$_GPC['ac'];

	$pindex=max(1,intval($_GPC['page']));

	$psize=10; 

	if ($ac == 'recharge') {



		if ($_W['isajax']) {  

			$pindex = max(1, intval($_GPC['page'])); 

			$psize = 10; 

            $sqltype=intval($_GPC['logtype'])?:0;

            

			$list=pdo_fetchall('select * from '.tablename('sz_yi_member_log').' where type = '.$sqltype.' and uniacid = :uniacid and openid = :openid order by id desc limit '.(intval($pindex)-1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

            

			if (!empty($list)) {    

				foreach ($list as $key => $value) {

					$list[$key]['createtime']=date('Y-m-d H:i:s',$list[$key]['createtime']);

				} 

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }    

            show_json(1,array('list'=>array(),'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		} 



	}else if ($ac == 'withdrawal'){

 

		if ($_W['isajax']) {  



			$pindex = max(1, intval($_GPC['page'])); 

			$psize = 10;    



			$list=pdo_fetchall('select * from '.tablename('sz_yi_member_log').' where type = 1 and uniacid = :uniacid and openid = :openid order by id desc limit '.(intval($pindex)-1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));   

 					 

			if (!empty($list)) {    

				foreach ($list as $key => $value) { 

					$list[$key]['createtime']=date('Y-m-d H:i:s',$list[$key]['createtime']);

				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }     	

            show_json(1,array('list'=>array(),'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		           

		} 

	}else if ($ac == 'shop'){

 

		if ($_W['isajax']) {  



			$pindex = max(1, intval($_GPC['page'])); 

			$psize = 10;    



			$list=pdo_fetchall('select o.ordersn,g.title,o.paytype rechargetype,og.price money,o.createtime,o.status from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on o.id = og.orderid left join '.tablename('sz_yi_goods').' g on og.goodsid = g.id where o.isexchange = 0 and o.uniacid = :uniacid and o.status > 0 and o.openid = :openid order by o.id desc limit '.(intval($pindex)-1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));   

 					  

				if (!empty($list)) {     

				foreach ($list as $key => $value) { 

						$list[$key]['createtime']=date('Y-m-d H:i:s',$list[$key]['createtime']);

				} 

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }     	

            show_json(1,array('list'=>array(),'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		             

		}



	}else if($ac=='refunduse'){   		//面向用户

		$pindex = max(1, intval($_GPC['page'])); 

		$psize = 10;   	 	 	



		if ($_W['isajax']) {  



			$list=pdo_fetchall('select o.*,m.nickname from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.uniacid = :uniacid and o.openid = :openid and o.status = -1 and o.isexchange = 0 order by o.refundtime desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));	 	 	 



			if (!empty($list)) {    	 	 

				foreach ($list as $key => $value) {

					$list[$key]['refundtime']=date('Y-m-d H:i:s',$list[$key]['refundtime']);

				}

				show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

		    }    

		    show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

		}

		 

	}

    else if($ac=='otherzhichu'){          //其他支出

        $pindex = max(1, intval($_GPC['page'])); 

        $psize = 10;            



        if ($_W['isajax']) {  



            $list=pdo_fetchall('select o.*,m.nickname from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.uniacid = :uniacid and o.openid = :openid and o.status = -1 and o.isexchange = 0 order by o.refundtime desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));             



            if (!empty($list)) {             

                foreach ($list as $key => $value) {

                    $list[$key]['refundtime']=date('Y-m-d H:i:s',$list[$key]['refundtime']);

                }

                show_json(1,array('list'=>$list,'count'=>count($list),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac)); 

            }    

            show_json(0,array('list'=>array(),'count'=>count(array()),'pageNum'=>$psize,'openid'=>$openid,'ac'=>$ac));      

        }

         

    }



include $this -> template('barter/cash_detail');

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

            $sql='select ob.ctime,ob.money,am.thumb,am.title,ob.openid from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.bonustype = 1 ';

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;

        }else if($status == 2){

            $sql='select ob.ctime,ob.money,am.thumb,am.title,ob.openid from '.tablename('sz_yi_ad_bonus_log').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.bonusType = 1';                          

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

    include $this -> template('barter/cashAd');                        

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

            $status=3;

            $page=max(1,intval($_GPC['page']));                                     

            m('demo')->getCommissionList($openid,$status,$page);

            show_json(0,array('list'=>array(),'pagesize'=>$psize));

        }else if($status == 2){         

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_activity_bonus_log').' ob left join '.tablename('sz_yi_member').' am on am.openid = ob.consumers left join '.tablename('sz_yi_activity_recharge_log').' r on r.id = ob.logid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.cate = 1 and r.paytype != 3 ';                          

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;            

        }else if($status == 3){         

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_activity_bonus_log').' ob left join '.tablename('sz_yi_member').' am on am.openid = ob.consumers left join '.tablename('sz_yi_activity_recharge_log').' r on r.id = ob.logid where ob.uniacid = :uniacid and ob.openid = :oepnid and ob.cate = 2 and r.paytype != 3 ';                                 

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;            

        }else if($status == 4){                             

            $sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_settop_bonus_log').' ob left join '.tablename('sz_yi_activity_settop_log').' r on r.id = ob.logid left join '.tablename('sz_yi_member').' am on am.openid = r.openid where ob.uniacid = :uniacid and ob.openid = :oepnid and r.paytype != 3 ';                                                                             

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;                                    

        }else if($status == 5){                             

            /*$sql='select ob.*,am.realname,am.nickname,am.avatar from '.tablename('sz_yi_settop_bonus_log').' ob left join '.tablename('sz_yi_activity_settop_log').' r on r.id = ob.logid left join '.tablename('sz_yi_member').' am on am.openid = r.openid where ob.uniacid = :uniacid and ob.openid = :oepnid and r.paytype != 3 ';                                                                             

            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize; */

            show_json(0,array('list'=>array(),'pagesize'=>$psize));                                   

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



    include $this -> template('barter/cashOther');

    exit;       

}          



include $this -> template('barter/cash');

