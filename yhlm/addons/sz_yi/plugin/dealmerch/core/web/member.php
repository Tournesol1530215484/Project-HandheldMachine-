<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;

$op= !empty($_GPC['op'])?$_GPC['op']:'display';
if ($op == 'display'){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;
    $condition=' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list=pdo_fetchall('select * from '.tablename('sz_yi_bart_member').' where uniacid = :uniacid '.$condition,array(':uniacid'=>$_W['uniacid']));
    $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_bart_member').' where uniacid ='.$_W['uniacid']);
    $pager = pagination($total, $pindex, $psize);
}else if ($_W['ispost'] && $op == 'post'){
     $data=array(
        'uniacid'=>$_W['uniacid'],
        'title' => $_GPC['title'],                                   
        'cash' =>intval($_GPC['cash']) * 1000,               
        'currency' => intval($_GPC['currency']) * 10000,         
        'status' => $_GPC['status'],                        
        'ctime' =>  time()                           
    );
    if (intval($_GPC['id'])){
        $sure=pdo_update('sz_yi_bart_member',$data,array('id'=>$_GPC['id']));
    }else{
        $sure=pdo_insert('sz_yi_bart_member',$data);
    }
    $sure?message('修改成功!',$this->createPluginWebUrl('dealmerch/member'),'success'):message('修改失败!',$this->createPluginWebUrl('dealmerch/member'),'error');
    exit;
}else if ($op == 'edit'){
    $id=intval($_GPC['id']);
    $item=pdo_fetch('select * from '.tablename('sz_yi_bart_member').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
    $item['cash']/=1000;
    $item['currency']/=10000;     
}else if ($op == 'fenhong'){

    if($_W['isajax']){

        $data=$_POST;
        $logid=$data['logid'];
        //获取订单的所有信息

        $res=pdo_fetchall("select m.realname,m.nickname,m.mobile,v.level,v.vip,v.money,v.remark,v.ctime from hs_sz_yi_year_vip_log v left join hs_sz_yi_member m on m.openid = v.openid where v.ordersn = '$logid'");

         $rule=array(
                //1 推荐人 2员工 3区域代理 4市级代理 5省级代理
                '商家',    //0            
                '推荐',    //1
                '员工',    //2   
                '区代',    //3    
                '市代',   //4
                '省代',   //5
                '总部',    //6
                ''
            );

        if($res){
                foreach ($res as $key => $value) {
                $res[$key]['ctime']=date("Y-m-d-H-i",$value['ctime']);
                $res[$key]['level']=$rule[intval($value['level'])];

            }
            show_json(1,$res);
        }
        

      show_json(0);   
        

    }
    
}else if($op == 'log'){

    //$levels  = m('member')->getLevels();
    //获取会员等级
    $levels =pdo_fetchall("select id,title from hs_sz_yi_bart_member where status=1");

    $pindex = max(1,intval($_GPC['page']));
    $psize = 20;

    $condition=' and rm.uniacid = :uniacid ';




    $params=array(
        ':uniacid'=>$_W['uniacid']          
    );

    if (!empty($_GPC['realname'])) {

        $_GPC['realname'] = trim($_GPC['realname']);

        $condition .= ' and (m.realname like :realname or m.nickname like :realname or m.mobile like :realname)';

        $params[':realname'] = "%{$_GPC['realname']}%";

    }

     if (!empty($_GPC['logno'])) {

        $_GPC['logno'] = trim($_GPC['logno']);

        $condition .= ' and rm.ordersn like :ordersn';

        $params[':ordersn'] = "%{$_GPC['logno']}%";
        // $condition .= ' and rm.ordersn=' . intval($_GPC['logno']);

    }

     if (!empty($_GPC['level'])) {

        $condition .= ' and rm.level=' . intval($_GPC['level']);

    }

     if (!empty($_GPC['paytype'])) {

        $_GPC['paytype'] = trim($_GPC['paytype']);

        $condition            = ' AND rm.paytype='. intval($_GPC['paytype']);
       // $params[':paytype'] = trim($_GPC['paytype']);

    }

     if (!empty($_GPC['time'])) {

        $starttime = strtotime($_GPC['time']['start']);

        $endtime   = strtotime($_GPC['time']['end']);

        if ($_GPC['searchtime'] == '1') {

            $condition .= " AND rm.ctime >= :starttime AND rm.ctime <= :endtime ";

            $params[':starttime'] = $starttime;

            $params[':endtime']   = $endtime;

        }

    }



    $sql='select rm.*,m.nickname,m.realname,m.avatar,bm.title from '.tablename('sz_yi_recharge_member').' rm left join '.tablename('sz_yi_member').' m on m.openid =  rm.openid left join '.tablename('sz_yi_bart_member').' bm on bm.id = rm.level where 1 ';
    $sql.=$condition;
    $sql.=' order by rm.id desc ';

    $list=pdo_fetchall($sql,$params);



    //导出充值记录
    if ($_GPC['export'] == '8') {
         foreach ($list as $key => $value) {
           if($value['paytype']==1){
                $list[$key]['paytype']='后台';
           }else{
               $list[$key]['paytype']='微信';
           }
           $list[$key]['ctime']=date('Y-m-d H:i:s',$list[$key]['ctime']);
        }

    plog('member.member.export', '导出年会员购买记录');
    m('excel')->export($list, array('title' => '年会员购买记录-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '会员名', 'field' => 'realname', 'width' => 12),array('title' => '微信名', 'field' => 'nickname', 'width' => 12), array('title' => '支付方式', 'field' => 'paytype', 'width' => 12), array('title' => '订单号', 'field' => 'ordersn', 'width' => 12), array('title' => '购买时间', 'field' => 'ctime', 'width' => 12), array('title' => '会员等级', 'field' => 'title', 'width' => 12))));
}




    
    $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_recharge_member').' rm left join '.tablename('sz_yi_member').' m on m.openid =  rm.openid where 1 '.$condition,$pager);

    $pager = pagination($total, $pindex, $psize);

}        

load() -> func('tpl');
if ($op == 'code') {
    load()->classs('captcha');
    $captcha = new Captcha();
    $captcha->build2(250, 40,'火药青火药');        
    $captcha->output();
    exit;
}

include $this -> template('member');
exit;
 