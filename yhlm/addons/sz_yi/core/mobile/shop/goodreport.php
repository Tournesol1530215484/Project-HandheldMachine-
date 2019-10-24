<?php
if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W,$_GPC;


$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$openid    = m('user')->getOpenid();

$trade     = m('common')->getSysset('trade');


if($operation == 'sign' || $_W['isajax'] ) {
    $openid=$_GPC['uid'];

    if($_GPC['goodsid'] && $openid ){
        
        $res=pdo_fetch('select * from '.tablename('sz_yi_goods_report_log')."where goodsid=:goodsid and openid= :openid",array(':goodsid' => $_GPC['goodsid'],':openid' => $openid));
        if(!empty($res)){
            $json=array('status'=>'-1','msg'=>'您已经举报过了');
            echo json_encode($json);
            exit;
        }else{
            $member    = m('member')->getMember($openid);
            $data['checkbox1']=$_GPC['checkbox1'];
            $data['checkbox1']=implode(',',$data['checkbox1']);

            $data['uid']=$member['id'];
            $data['uniacid']=$member['uniacid'];
            $data['remark']=$_GPC['remark'];
            $data['goodsid']=$_GPC['goodsid'];
            $data['openid']=$openid;
            $data['ctime']=time();
            $res=array(
                        'type' => $data['checkbox1'],
                        'uid' =>$data['uid'],
                        'uniacid' =>$data['uniacid'],
                        'remark' => $data['remark'],
                        'goodsid' => $data['goodsid'],
                        'openid' => $data['openid'],
                        'ctime'=>$data['ctime'],
                    );
            pdo_insert('sz_yi_goods_report_log', $res);
            $id = pdo_insertid();
            if($id){
                 $json=array('status'=>'1','msg'=>'举报成功');
                 echo json_encode($json);
            }else{
                $json=array('status'=>'0','msg'=>'举报失败');
                echo json_encode($json);
            }
        }
    }

    $json=array('status'=>'-2','msg'=>'举报失败' );
    echo json_encode($json);
   

    
    
    
 }else if($operation=='display'){

    $list=pdo_fetchall('select * from '.tablename('sz_yi_goods_report_type').' where uniacid = :uniacid and status = 1 order by display desc ',array(':uniacid'=>$_W['uniacid']));
     $openid=$_GPC['openid']?$_GPC['openid']:$_GPC['popenid'];
    include $this->template('shop/goodsreport');
 }


// $uniacid   = $_W['uniacid'];
//print_r($openid)；



