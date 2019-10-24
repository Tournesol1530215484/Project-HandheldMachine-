<?php
 global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if ($operation == 'display'){
    $where = '';
    $pindex = max(1,intval($_GPC['page']));
    $psize = 20;
    if(!empty($_GPC['uid'])){
        $where .= ' u.uid=' . $_GPC['uid'];
    }
    if(!empty($_GPC['applysn'])){
        $where .= ' and a.applysn=' . $_GPC['applysn'];
    }
    if(!empty($_GPC['realname'])){
         $_GPC['realname'] = trim($_GPC['realname']);
         $where.=' and m.realname like '."'%{$_GPC['realname']}%'";
    }

    if(!empty($_GPC['mobile'])){
         $where .= ' and m.mobile=' . $_GPC['mobile'];
    }

     if(!empty($_GPC['type'])){
         $where .= ' and a.type=' . $_GPC['type'];
    }

    if (!empty($_GPC['time'])) {

        $starttime = strtotime($_GPC['time']['start']);

        $endtime   = strtotime($_GPC['time']['end']);

        if ($_GPC['searchtime'] == '1') {

            $condition .= " AND a.finish_time >= :starttime AND .finish_time <= :endtime ";

            $params[':starttime'] = $starttime;

            $params[':endtime']   = $endtime;

        }

    }


    $list = pdo_fetchall('select a.*,p.*,a.status appstatus,a.type atype,m.realname,m.avatar from ' . tablename('sz_yi_supplier_apply') . ' a left join ' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid left join '.tablename('sz_yi_member').'m on p.openid=m.openid  where a.status != 0 and p.uniacid=' . $_W['uniacid'] . $where.' order by a.id desc limit '.($pindex -1) * $psize .','.$psize);

   

    //数据导出

    if ($_GPC['export'] == '1') {

        $lists = pdo_fetchall('select a.*,p.*,a.status appstatus,a.type atype,m.realname,m.avatar from ' . tablename('sz_yi_supplier_apply') . ' a left join ' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid left join '.tablename('sz_yi_member').'m on p.openid=m.openid  where a.status != 0 and p.uniacid=' . $_W['uniacid'] . $where.' order by a.id desc ');

        foreach ($lists as $key => $value) {  
            //提现方式  
            if($value['atype']==1){
                $lists[$key]['atype']='平台余额';
            }else if($value['atype']==2){
                $lists[$key]['atype']='微信';
            }else{
                $lists[$key]['atype']='手动';
            }

            //提现时间
            $lists[$key]['finish_time']=date("Y-m-d H:i:s",$value['finish_time']);

            //状态结果

            if($lists[$key]['appstatus']==1){
                $lists[$key]['appstatus']="已完成";
            }else{
                $lists[$key]['appstatus']='失败';
            }
        }



        plog('member.member.export', '供货商提现完成记录导出');

        m('excel')->export($lists, array('title' => '供货商提现完成记录-' . date('Y-m-d-H-i', time()), 'columns' => array(array('title' => '供应商ID', 'field' => 'uid', 'width' => 12), array('title' => '提现单号', 'field' => 'applysn', 'width' => 12), array('title' => '用户名', 'field' => 'realname', 'width' => 12), array('title' => '电话', 'field' => 'mobile', 'width' => 12),   array('title' => '实际提现金额', 'field' => 'apply_money', 'width' => 12), array('title' => '完成时间', 'field' => 'finish_time', 'width' => 12), array('title' => '提现方式', 'field' => 'atype', 'width' => 12), array('title' => 'appstatus', 'field' => 'appstatus', 'width' => 12))));

    }


    $total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_supplier_apply') . ' a left join ' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid  where a.status != 0  and p.uniacid=' . $_W['uniacid'] );	 		 		 
	$pager = pagination($total, $pindex, $psize);

}
load() -> func('tpl');
include $this -> template('supplier_finish');
	 			