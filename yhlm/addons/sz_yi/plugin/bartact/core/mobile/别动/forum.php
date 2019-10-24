<?php
// use Grafika\Grafika; // Import package
if (!defined('IN_IA')){
    exit('Access Denied');
}

global $_W, $_GPC;
$openid = m('user')->getOpenid();
$popenid        = m('user')->islogin();
$openid = m('user')->getOpenid();
$openid = $openid?$openid:$popenid;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];
$ac = empty($_GPC['ac']) ? '': $_GPC['ac'];
$muser=m('tools')->getMuser($openid);
$member=m('member')->getMember($openid);
$_GPC['mid']=$member['id'];

$cate=pdo_fetchall('select * from '.tablename('sz_yi_forum_type').'where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));

if($op == 'delete'){

}

if ($op == 'display') {
        $forumtotal=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_forum').'where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
        $pingluntotal=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_forum_pinglun').'where uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));

//    if ($_W['isajax']) {
            $pindex = max(1, intval($_GPC['page']));
            $psize = 5;
            $type = intval($_GPC['type']);
//    print_r($_GPC);exit;
            $condition = ' and f.uniacid = :uniacid ';
            $params = array(
                ':uniacid' => $_W['uniacid'],
            );

            if($_GPC['key']){
                $condition .= ' and f.title like :title ';
                $params[':title'] = "%{$_GPC['title']}%";
            }
            if($type !=0){
                $condition .= ' and f.type=:type ';
                $params = array(
                    ':uniacid' => $_W['uniacid'],
                    ':type' =>  $type,
                );
            }

//    }

//    $sql = 'SELECT f.id,f.goodsid,g.title,g.thumb,g.marketprice,g.productprice FROM ' . tablename('sz_yi_member_history') . ' f ' . ' left join ' . tablename('sz_yi_goods') . ' g on f.goodsid = g.id ' . ' where 1 ' . $condition . ' ORDER BY `id` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

    $sql = 'select f.*,t.title as typetitle,m.realname,m.nickname,m.avatar from '.tablename('sz_yi_forum').'f left join'.tablename('sz_yi_forum_type').' t on f.type=t.id left join '.tablename('sz_yi_member').'m on f.openid=m.openid where 1'. $condition .'order by f.id desc limit ' . ($pindex - 1) * $psize .','.$psize;
//    print_r($sql);
    $list=pdo_fetchall($sql, $params);
//    print_r($list);exit;
    foreach($list as $k=>&$v){
        $exist=pdo_fetch('select id from '.tablename('sz_yi_forum_like').'where uniacid=:uniacid and openid=:openid and forumid=:id'
        ,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$v['id']));
        if(empty($exist['id'])){
            $v['exist']="";
        }else{
            $v['exist']=1;
        }
    }
//    print_r($list);exit;


}else if($op == 'add'){

    if($_W['isajax']){
        $data['uniacid']=$_W['uniacid'];
        $data['title']=$_GPC['title'];
        $data['status']=$_GPC['status'];
        $data['type']=$_GPC['type'];
        $data['content']=$_GPC['content'];
        $data['openid']=$openid;
        $data['ctime']=time();
        $data['pro']=$_GPC['content'];
        $data['city']=$_GPC['city'];
        $data['area']=$_GPC['area'];
        pdo_insert('sz_yi_forum',$data);
        show_json(1);
    }
    include $this->template('forumAdd');
    exit;
}else if($op == 'detail'){

    $detail=pdo_fetch('select f.*,t.title as typetitle from'.tablename('sz_yi_forum').'f left join '.tablename('sz_yi_forum_type'). 't on f.type=t.id where f.uniacid=:uniacid and f.id=:id',array(':id'=>$_GPC['id'],':uniacid'=>$_W['uniacid']));
    $m=pdo_fetch('select * from '.tablename('sz_yi_member').'where uniacid=:uniacid and openid=:openid',
        array(':openid'=>$detail['openid'],':uniacid'=>$_W['uniacid']));
    $_GPC['id']=$detail['id'];
    $list=pdo_fetchall('select p.*,m.realname,m.nickname,m.avatar from '.tablename('sz_yi_forum_pinglun').'p left join '.tablename('sz_yi_member').'m on m.openid= p.openid where p.uniacid=:uniacid and p.forumid=:forumid order by p.ctime desc',array(':forumid'=>$detail['id'],':uniacid'=>$_W['uniacid']));
//    print_r($list);exit;
    include $this->template('forum-info');
    exit;
}else if($op == 'pinglun'){
    if($_W['isajax']){
        $data['uniacid']=$_W['uniacid'];
        $data['content']=$_GPC['content'];
        $data['forumid']=$_GPC['forumid'];
        $data['openid']=$openid;
        $data['ctime']=time();
        pdo_insert('sz_yi_forum_pinglun',$data);

        show_json(1);
    }
}else if($op == 'like'){

    $exsit=pdo_fetch('select id from'.tablename('sz_yi_forum_like').'where uniacid=:uniacid and openid=:openid and forumid=:id',
        array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$_GPC['id']));

    if(!empty($exsit['id'])){
        show_json(0);
    }
    $data['uniacid']=$_W['uniacid'];
    $data['openid']=$openid;
    $data['ctime']=time();
    $data['forumid']=$_GPC['id'];
    pdo_insert('sz_yi_forum_like',$data);
    show_json(0);
} else if ($op == 'getinfo' && $_W['isajax']) {
    $set = m('common')->getSysset(array(
        'shop',
        'pay',
        'trade'
    ));

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
        'title' => $set['shop']['name'] . "会员打赏",
        'openid' => $openid,
        'type' => 0,
        'createtime' => time(),
        'status' => 0
    );

    pdo_insert('sz_yi_member_log', $log);
    $logid  = pdo_insertid();
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

    show_json(1, array(
        'set' => $set,
        'logid' => $logid,
        'isweixin' => is_weixin(),
        'wechat' => $wechat,
    ));
}else if($op == 'reward'){
    $id=intval($_GPC['id']);
    $type=intval($_GPC['type']);
    $at=pdo_fetch('select * from '.tablename('sz_yi_forum').'where uniacid=:uniacid and id=:id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
//    print_r($at);exit;
    if ($ac == 'sub') {
        $data=array(
            'uniacid'=>$_W['uniacid'],
            'openid'=>$openid,
            'type'=>$type,
            'paytype'=>2,
            'atid'=>$id, //member id
            'money'=>floatval($_GPC['money']),
            'uniacid'=>$_W['uniacid'],
            'remark'=>$_GPC['message'],
            'ctime'=>time()
        );

        pdo_insert('sz_yi_forum_reward',$data);

        $id=pdo_insertid();
        if ($id) {
//            p('commission')->calcReward($openid,$type,$at['id'],$data['money'],$id);
            m('member')->setCredit($openid,'credit2',-$data['money']);
            show_json(1,'打赏成功!');
        }
        show_json(0,'打赏失败');

    }
    include $this->template('reward');	//	打赏
    exit;
}

include $this->template('forum');
