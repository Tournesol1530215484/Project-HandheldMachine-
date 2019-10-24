<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$plugin_diyform = p('diyform');
$totals = array();
if ($op == 'display') {

    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;

    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and f.uniacid = :uniacid ';

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }

    if (!empty($_GPC['realname'])){
        $_GPC['realname'] = trim($_GPC['realname']);
        $condition .= ' and ( mu.realname like :realname or mu.mobile like :realname)';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }


    $sql='select f.*,t.title as typetitle,m.realname,m.nickname from '.tablename('sz_yi_forum').'f left join'.tablename('sz_yi_forum_type').' t on f.type=t.id left join '.tablename('sz_yi_member').'m on f.openid=m.openid where 1 '.$condition;
    $sql.=' order by f.id desc ';

    $totals = pdo_fetchcolumn('select count(f.id) from '.tablename('sz_yi_forum').'f left join'.tablename('sz_yi_forum_type').' t on f.type=t.id left join '.tablename('sz_yi_member').'m on f.openid=m.openid where 1 '.$condition, $params);

    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

    $list = pdo_fetchall($sql, $params);

}else if($op == 'delete'){
    $id=intval($_GPC['id']);

    $re=pdo_delete('sz_yi_forum',array('id'=>$id,'uniacid'=>$_W['uniacid']));

    message('论坛删除成功！', $this->createPluginWebUrl('bartact/forum', array(
        'op' => 'display'
    )), 'success');

}else if($op == 'add'){
    $id=intval($_GPC['id']);
    $type=pdo_fetchall('select * from '.tablename('sz_yi_forum_type').' where uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));
    if ($_W['isajax']) {
        $data=$_GPC['data'];
        $reside=$_GPC['reside'];
        $data['uniacid']=$_W['uniacid'];
        $data['ctime']=time();
        $data['pro']=$reside['province'];
        $data['city']=$reside['city'];
        $data['area']=$reside['area'];
        $data['content']=$data['desc'];
        $data['desc']=$_GPC[data]['content'];
        pdo_insert('sz_yi_forum',$data);
        show_json(1);
    }
    include $this -> template('forum_add');
    exit;
}else if($op == 'team'){
    $id=intval($_GPC['id']);
    $act=m('activity')->getact($id);
    $muser=m('activity')->getMuser($act['openid']);

    $url=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$id));
    $oplist = pdo_fetchall('select openid from '.tablename('sz_yi_activity_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$muser['uid']));
    $url=$this->createPluginMobileUrl('activity/activity');
    // $oplist=pdo_fetchall('select * from '.tablename('sz_yi_activity_').);


    $msg = array(
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(
            'title' => '您关注的机构发布新文章了 ',
            'value' => $act['title'],
            "color" => "#4a5077"
        ),
        'keyword2' => array(
            'title' => '消息类型',
            'value' =>  '活动订阅',
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


    $ntc = array(
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(
            'title' => '消息类型',
            'value' =>  '活动群发',
            "color" => "#4a5077"
        ),
        'keyword2' => array(
            'title' => '提醒内容 ',
            'value' => $act['title'].'活动已经通过'.$_W['setting']['copyright']['sitetitle'].'发送给你的'.count($oplist).'位粉丝',
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

    foreach ($oplist as $key => $value) {
        $ret = m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);
    }
    m('message')->sendTplNotice($muser['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$ntc, $url);
    if ($ret['errcode'] == 0) {
        show_json(1,'发送成功！');
    }else{
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }

}else if($op == 'audit'){
    $id=intval($_GPC['id']);
    $data=array(
        'status'=>$_GPC['check']
    );

    $re=pdo_update('sz_yi_activity_comment',$data,array('uniacid'=>$_W['uniacid'],'id'=>$id,'type'=>$_GPC['type']));

    if ($re) {
        message('审核成功!',referer(),'success');
    }
    message('审核失败!',referer(),'error');
}else if($op == 'preview'){
    $id=intval($_GPC['atid']);
    $mobile=trim($_GPC['mobile']);

    if (empty($id) || empty($mobile)) {
        show_json(0,'非法参数!');
    }

    $member=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and mobile  = :mobile ',array(':uniacid'=>$_W['uniacid'],':mobile'=>$mobile));
    $act=m('activity')->getact($id);
    !$member && show_json(0,'找不到该手机所属会员');

    // $template = array(
    //         array(
    //             'title' => '客服提醒通知',
    //             'value' =>'活动预览'
    //         ),
    //         array(
    //             'title' => '客户名称 ',
    //             'value' =>$member['nickname']
    //         ),
    //         array(
    //             'title' => '通知时间',
    //             'value' =>date('Y-m-d H:i:s')
    //         ),
    //         array(
    //             'title' => '摘要 ',
    //             'value' =>$act['desc']
    //         ),
    //     );

    $msg = array(
        'first' => array(
            'value' => "通知提醒",
            "color" => "#4a5077"
        ),
        'keyword1' => array(
            'title' => '活动预览 ',
            'value' => $act['title'],
            "color" => "#4a5077"
        ),
        'keyword2' => array(
            'title' => '客户名称',
            'value' => $member['nickname'],
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
    $re=m('message')->sendTplNotice($member['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);

    if ($re['errcode'] == 0) {
        show_json(1,'发送成功！');
    }else{
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }
}else if($op == 'notice'){
    $id=intval($_GPC['atid']);
    $content=trim($_GPC['content']);

    if (empty($id) || empty($content)) {
        show_json(0,'非法参数!');
    }

    $activity=m('activity')->getact($id);

    // $template = array(
    //         array(
    //             'title' => '活动变更提醒',
    //             'value' =>''
    //         ),
    //         array(
    //             'title' => '活动标题',
    //             'value' =>$activity['title']
    //         ),
    //         array(
    //             'title' => '变更内容 ',
    //             'value' =>$content
    //         ),
    //         array(
    //             'title' => '通知时间',
    //             'value' =>date('Y-m-d H:i:s')
    //         ),
    //     );

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
        // $re=m('message')->sendCustomNotice($value['openid'], $template,$url);
        $re=m('message')->sendTplNotice($value['openid'],'5PBUSIaFmSQSHXUpBGQnnReStFuNpLoUlwD4DNpy46o',$msg, $url);
    }
    if ($re['errcode'] == 0) {
        show_json(1,'发送成功！');
    }else{
        show_json(0,'发送失败!错误代码'.$re['errcode'].':'.$re['errmsg']);
    }
}else if($op == 'sinotice'){
    $id=intval($_GPC['id']);
    $mobile=trim($_GPC['mobile']);
    empty($id) && show_json(0,'非法参数');

    $act=m('activity')->getact($id);
    empty($act) && show_json(0,'没有这条活动!');
    $noticeList=unserialize($act['noticeList']);

    if ($ac == 'add') {
        foreach ($noticeList as $key => $value) {
            if ($value == $mobile) {
                show_json(0,'该提醒手机号码已经存在');
            }
        }
        $checkM=pdo_fetch('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and mobile = :mobile',array(':uniacid'=>$_W['uniacid'],':mobile'=>$mobile));
        empty($checkM) && show_json(0,'没有该手机号码!');

        $checkMu=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$checkM['openid']));
        empty($checkMu) && show_json(0,'该手机号码未注册易活动');

        $noticeList[]=$mobile;
        $data=array(
            'noticeList'=>serialize($noticeList)
        );
        $release=m('member')->getMember($act['openid']);
        $re=pdo_update('sz_yi_activity',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
        $str='添加';

    }else if($ac == 'del'){
        foreach ($noticeList as $key => $value) {
            if ($value == $mobile) {
                unset($noticeList[$key]);
            }
        }
        $data=array(
            'noticeList'=>serialize($noticeList)
        );
        $re=pdo_update('sz_yi_activity',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
        $str='删除';
    }

    if ($re) {
        show_json(1,$str.'成功!');
    }

    show_json(0,$str.'失败!');
}else if($op == 'getlist'){
    $id=intval($_GPC['id']);
    $act=m('activity')->getact($id);
    $act['noticeList']=unserialize($act['noticeList']);
    $html='<tr>';
    foreach ($act['noticeList'] as $key => $value) {
        $html.='<td>'.$value.'</td><td><label data-mobile="'.$value.'" data-id="'.$id.'" class="sgdel label label-danger">删除</label></td>';
    }
    $html.='</tr>';
    show_json(1,$html);
}

include $this -> template('forum');