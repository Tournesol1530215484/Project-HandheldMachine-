<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$ac =$_GPC['ac'];
    
    if ($_GPC['debug']) {
        $openid='oSI4Lj7AvG7Eut3YBoluph2ur7FI';      
        $uid=5730;
        $credit2=m('member')->getCredit($openid,'credit2');
        $credit3=m('member')->getCredit($openid,'credit3');
        $recodecredit2=pdo_fetchcolumn('select sum(num) from '.tablename('mc_credits_record').' where uid = 5730 and uniacid = 8 and credittype = "credit2" ');
        $recodecredit3=pdo_fetchcolumn('select sum(num) from '.tablename('mc_credits_record').' where uid = 5730 and uniacid = 8 and credittype = "credit3" ');        


        $recodecreditbonus=pdo_fetchcolumn('select sum(num) from '.tablename('mc_credits_record').' where uid = 5730 and uniacid = 8 and credittype = "credit2" and remark = "未记录" ');

        print_r('当前余额:'.$credit2);
        print_r('当前换货码:'.$credit3);
        echo '<br/>';
        print_r('记录表当前AD分红:'.$recodecreditbonus);
        print_r('记录表余额:'.$recodecredit2);
        print_r('记录表当前换货码:'.$recodecredit3);


        $oldcredit2=pdo_fetchcolumn('select sum(num) from '.tablename('mc_credits_record').' where uid = 5730 and uniacid = 8 and credittype = "credit2" and createtime < 1543147430 ');

        $oldcredit3=pdo_fetchcolumn('select sum(num) from '.tablename('mc_credits_record').' where uid = 5730 and uniacid = 8 and credittype = "credit3" and createtime < 1543147430 ');

        echo '<br/>';

        print_r('在购买之前余额:'.$oldcredit2);
        print_r('在购买之前换货码:'.$oldcredit3);

        exit;
    }
if ($operation == 'display') {
    
    

    if ($ac == 'query') {
		    isetcookie('queryopenid',$_GPC['noticeopenid']);
    }


    $openid=$_GPC['queryopenid']?:$_GPC['noticeopenid'];

    $saler=m('member')->getMember($openid);             //个人信息     

    $credit1=m('member')->getCredit($openid,'credit1');
    $credit2=m('member')->getCredit($openid,'credit2');
    $credit3=m('member')->getCredit($openid,'credit3');
    $freeze_credit3=m('member')->getCredit($openid,'freeze_credit3');
    $currency_credit3=m('member')->getCredit($openid,'currency_credit3');

    if ($openid) {
        $cash=m('tools')->calcCash($openid);
        $code=m('tools')->calcCode($openid);
    }

}else if($operation == 'query'){



    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;

    !$_GPC['queryopenid'] && message('请先选择用户再进行查询');
    $openid=$_GPC['queryopenid'];

    if ($ac == 'recharge') {    //充值记录
        $list=pdo_fetchall('select l.*,m.nickname from '.tablename('sz_yi_member_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.type = 0 and l.uniacid = :uniacid and l.openid = :openid order by l.id desc limit '.(intval($pindex)-1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.type = 0 and l.uniacid = :uniacid and l.openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    }else if($ac == 'withdraw'){        //提现记录

        $list=pdo_fetchall('select l.*,m.nickname from '.tablename('sz_yi_member_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.type = 1 and l.uniacid = :uniacid and l.openid = :openid order by l.id desc limit '.(intval($pindex)-1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));   

        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member_log').' l left join '.tablename('sz_yi_member').' m on m.openid = l.openid where l.type = 1 and l.uniacid = :uniacid and l.openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    }else if($ac == 'fans'){        //拆红包、推广奖励 
        $type = $_GPC['type'];  // cash or code
        $status=intval($_GPC['status']);    //me or friend

        $status > 2 || $status == 0 && message('非法参数!','','warning');
        $type > 2 || $type == 0 && message('非法参数!','','warning');

        if ($status == 1) {
            $sql='select ob.ctime,ob.money,am.thumb,am.title,m.realname,m.nickname from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid left join '.tablename('sz_yi_member').' m on m.openid = ob.openid where ob.uniacid = :uniacid and ob.openid = :openid and ob.bonustype = '.$type;
            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;
        }else if($status == 2){
            $sql='select ob.ctime,ob.money,am.thumb,am.title,m.nickname,m.realname from '.tablename('sz_yi_ad_bonus_log').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid left join '.tablename('sz_yi_obtain_bonus').' b on b.id = ob.obid left join '.tablename('sz_yi_member').' m on m.openid = b.openid where ob.uniacid = :uniacid and ob.openid = :openid and ob.bonusType = '.$type;                          
            $sql.=' order by ob.id desc limit '.($pindex -1)* $psize.','.$psize;
        }    

        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':openid'=>$openid
        ); 
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
        }
        $total=0;
    }else if($ac == 'shop'){        //购物消费记录

        $list=pdo_fetchall('select m.nickname,o.ordersn,g.title,og.price money,o.createtime,o.status from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on o.id = og.orderid left join '.tablename('sz_yi_goods').' g on og.goodsid = g.id left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.isexchange = 0 and o.uniacid = :uniacid and o.status > 0 and o.openid = :openid order by o.id desc limit '.(intval($pindex)-1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on o.id = og.orderid left join '.tablename('sz_yi_goods').' g on og.goodsid = g.id left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.isexchange = 0 and o.uniacid = :uniacid and o.status > 0 and o.openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    }else if($ac == 'put'){     //好友转入记录
        
        $list=pdo_fetchall('select tl.*,m.realname,m.nickname from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.sponsor_openid = m.openid where tl.uniacid = :uniacid and tl.recipient_openid = :openid order by tl.id desc limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 

        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.sponsor_openid = m.openid where tl.uniacid = :uniacid and tl.recipient_openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    }else if($ac == 'saler'){       //销售获得记录
        
        $list=pdo_fetchall('select cl.* from '.tablename('sz_yi_barter_code_log').' cl where cl.uniacid = :uniacid and cl.type = 2 and cl.openid = :openid order by cl.id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_barter_code_log').' cl where cl.uniacid = :uniacid and cl.type = 2 and cl.openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 

    }else if($ac == 'refund'){      //退单退款记录商家
            
        $dm=p('bonus')->getMerch($openid,'deal'); 
        $list=pdo_fetchall('select o.*,m.nickname from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.uniacid = :uniacid and o.supplier_uid = :supplier_uid and o.status = -1 order by o.id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':supplier_uid'=>$dm['uid'])); 
        
        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :supplier_uid and status = -1 ',array(':uniacid'=>$_W['uniacid'],':supplier_uid'=>$dm['uid']));
    }else if($ac == 'refunduse'){      //退单退款记录用户
            
        $list=pdo_fetchall('select o.*,m.nickname from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_member').' m on m.openid = o.openid where o.uniacid = :uniacid and o.openid = :openid and o.status = -1 order by o.id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid)); 
       
        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_order').' where uniacid = :uniacid and openid = :openid and status = -1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));      

    }else if($ac == 'use'){         //换货消费记录

        $list=pdo_fetchall('select l.*,o.id orderid from '.tablename('sz_yi_barter_code_log').' l  left join '.tablename('sz_yi_order').' o on o.ordersn = l.dealsn where l.uniacid = :uniacid and l.openid = :openid and l.type = 1 order by l.id desc limit '.($pindex-1)*$psize.','.$psize ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

        foreach ($list as $key => $value) {
            $goods=pdo_fetchall('select g.title,g.id from '.tablename('sz_yi_order_goods').' og left join '.tablename('sz_yi_goods').' g on g.id = og.goodsid where og.uniacid = :uniacid and og.orderid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['orderid']));
            $list[$key]['goods']=$goods;
        }

        $total=pdo_fetchcolumn('select * from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and openid = :openid and type = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    }else if($ac == 'get'){         //好友转出记录
        
        $list=pdo_fetchall('select tl.*,m.realname,m.nickname from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.sponsor_openid = m.openid where tl.uniacid = :uniacid and tl.sponsor_openid = :openid order by tl.id desc limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));


        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_transfer_log').' tl left join '.tablename('sz_yi_member').' m on tl.recipient_openid = m.openid where tl.uniacid = :uniacid and tl.sponsor_openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
                                     
    }else if($ac == 'others'){      //其他记录
        $list=array();
        $total=0;   
    }


}

$pager = pagination($total, $pindex, $psize);
load()->func('tpl');
include $this->template('query');