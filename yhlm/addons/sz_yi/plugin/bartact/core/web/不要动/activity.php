<?php

//decode by QQ:270656184 http://www.yunlu99.com/

global $_W, $_GPC;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';

$plugin_diyform = p('diyform');

$totals = array();

if ($op == 'display') {

    ca('bartact.activity');

    $pindex = max(1, intval($_GPC['page']));

    $psize = 20;

    if ($_W['isajax']) {

        if ($ac == 'settop') {

            $id=intval($_GPC['id']);

            $type=intval($_GPC['type']);



            if ($type == 1) {

                $table='sz_yi_activity';

            }else if($type == 2){

                $table='sz_yi_activity_article';

            }

            $data=array(

                'is_top'=>$_GPC['is_top'],

                'toptime'=>time(),

            );

            if (!$_GPC['is_top']) {

                $data['toptime']=0;

            }



            $re=pdo_update($table,$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));



            if ($re) {

                show_json(1,'置顶成功！');

            }

            show_json(0,'置顶失败！');

        }

    }

    $params = array(':uniacid' => $_W['uniacid']);

    $condition=' and aa.uniacid = :uniacid ';



    if ($_GPC['title']) {

        $condition .= ' and aa.title like :title';

        $params[':title'] = "%{$_GPC['title']}%";

    }



    if (!empty($_GPC['realname'])){

        $_GPC['realname'] = trim($_GPC['realname']);

        $condition .= ' and ( mu.realname like :realname or mu.mobile like :realname)';

        $params[':realname'] = "%{$_GPC['realname']}%";

    }



    if (!empty($_GPC['searchtime'])){

        $condition .= ' and stime > :starttime and stime < :endtime ';

        $params[':endtime'] = strtotime($_GPC['time']['end']);

        $params[':starttime'] = strtotime($_GPC['time']['start']);

    }



    if ($_GPC['level']) {

        $condition .= ' and mu.level = :level';

        $params[':level'] = intval($_GPC['level']) - 1;

    }



    if($_GPC['is_top'] != ''){

        if($_GPC['is_top'] == 1){

            $condition .= ' and aa.is_top=1';

        }else if($_GPC['is_top'] == 2){

            $condition .= ' and aa.is_top=2';

        }else if($_GPC['is_top'] == 3){

            $condition .= ' and aa.is_top=3';

        }else if($_GPC['is_top'] == 4){

            $condition .= ' and aa.is_top=4';

        }

    }



    if($_GPC['reside']['province'] != ""){

        $condition .= ' and aa.province=\'' . $_GPC['reside']['province'] . '\'';

    }

    if($_GPC['reside']['city'] != ""){

        $condition .= 'and aa.city=\'' . $_GPC['reside']['city'] . '\'';

    }

    if($_GPC['reside']['district'] != ""){

        $condition .= 'and aa.district=\'' . $_GPC['reside']['district'] . '\'';

    }



    $sql='select aa.* from '.tablename('sz_yi_activity').' aa left join '.tablename('sz_yi_member_user').' mu on mu.openid = aa.openid where 1 '.$condition;

    $sql.=' order by aa.id desc ';



    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity'). " aa left join ".tablename('sz_yi_member_user')." mu on mu.openid = aa.openid where 1 {$condition} ", $params);



    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

    $list = pdo_fetchall($sql, $params);

    foreach ($list as $key => $value) {

        $list[$key]['signup']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and status <> 2',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));

        $list[$key]['noticeList']=unserialize($value['noticeList']);

        $temparr=m('activity')->trArrayKey($list[$key]['noticeList']);

        $list[$key]['info']=json_encode($temparr);

        if ($value['stime'] > time()) {

            $time=$value['etime'] - $value['stime'];

        }else{

            $time= $value['etime'] - time();

        }

        $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);

    }

    $pager = pagination($totals, $pindex, $psize);



    $sgPoster=pdo_fetchall('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and type = 1 and enabled = 1 order by displayorder desc limit 0,2',array(':uniacid'=>$_W['uniacid']));



}else if($op == 'readonly'){

    $id=intval($_GPC['id']);

    $info=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$_GPC['openid']));

    $activity=m('activity')->getact($id,1);

    $activity['payitem']=pdo_fetchall('select * from '.tablename('sz_yi_activity_payitem').' where enabled = 1 and  uniacid = :uniacid and atid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    $cate=pdo_fetchall('select * from '.tablename('sz_yi_activity_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));



}else if ($op == 'add'){

    $id=intval($_GPC['id']);

    $info=pdo_fetch('select * from '.tablename('sz_yi_member_user').' where uniacid = :uniacid and openid = :openid',array(':uniacid'=>$_W['uniacid'],':openid'=>$_GPC['openid']));

    $activity=m('activity')->getact($id,1);

    $activity['payitem']=pdo_fetchall('select * from '.tablename('sz_yi_activity_payitem').' where enabled = 1 and  uniacid = :uniacid and atid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

    $cate=pdo_fetchall('select * from '.tablename('sz_yi_activity_type').' where uniacid = :uniacid and status = 1 order by display desc',array(':uniacid'=>$_W['uniacid']));

    $audit_log=pdo_fetchall('select * from '.tablename('sz_yi_activity_log').' where uniacid = :uniacid and actid = :id order by id desc ',array(':id'=>$id,':uniacid'=>$_W['uniacid']));



    if ($_W['isajax']) {

        $data=$_GPC['data'];

        if ($_GPC['editType'] == 1 || empty($_GPC['editType']) && $_GPC['ac'] != 'live') {

            $data['field']=serialize($_GPC['diy']);

            $data['province']=$_GPC['reside']['province'];

            $data['city']=$_GPC['reside']['city'];

            $data['afterTheStart']=!empty($data['afterTheStart'])?$data['afterTheStart']:0;

            $data['area']=$_GPC['reside']['district'];

            $data['stime']=strtotime($_GPC['time']['start']);

            $data['etime']=strtotime($_GPC['time']['end']);

            $data=array_merge($data,$_GPC['orther']);

            $data['uid']=$activity['uid'];

            $data['openid']=$activity['openid'];

            $data['uniacid']=$_W['uniacid'];

            $data['relOrg']=$info['orgName'];

            $data['ContactOrg']=$info['realname'];

            $data['mobileOrg']=$info['mobile'];

            $data['descOrg']=$info['orgDesc'];

            $payitem=$_GPC['payitem'];

            foreach ($payitem as $key => $value) {

                $payitem[$key]['uniacid']=$_W['uniacid'];

                $payitem[$key]['ctime']=time();

                $payitem[$key]['enabled']=1;

            }

            if ($data['teamModel'] == 0) {

                unset($data['agent1']);

                unset($data['agent2']);

            }

        }

        if ($_GPC['ac'] == 'live') {

            $data['screen_stime']=$data['start'];

            $data['screen_etime']=$data['end'];

            unset($data['start']);

            unset($data['end']);

        }





        $logid=pdo_fetchcolumn('select id from '.tablename('sz_yi_goods_log').' where uniacid = :uniacid and goodsid = :goodsid and status = 0',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$id));



        $goodslog=[

            'uniacid'=>$_W['uniacid'],

            'uid'=>$_W['uid'],

            'actid'=>$_GPC['id'],

            'sub_time'=>time(),

            'status'=>0

        ];







        if (empty($id)) {



            $data['ctime']=time();

            // $author=m('activity')->getMuser($GPC['openid']);

            $muser=m('member')->getMember($_GPC['openid']);

            $data['noticeList']=serialize(array($muser['mobile']));

            pdo_insert('sz_yi_activity',$data);



            $id=pdo_insertid();



            $goodslog['actsid']=$id;

            pdo_insert('sz_yi_activity_log',$goodslog);



            if ($id) {

                foreach ($payitem as $key => $value) {

                    $payitem[$key]['atid']=$id;

                }



                foreach ($payitem as $key => $value) {

                    pdo_insert('sz_yi_activity_payitem',$value);

                }

                show_json(1,'添加成功');

            }

            show_json(0,'添加失败');

        }else{



            if ($_GPC['ac'] == 'clone') {

                $data['ctime']=time();



                pdo_insert('sz_yi_activity',$data);

                $id=pdo_insertid();



                $goodslog['actsid']=$id;

                pdo_insert('sz_yi_activity_log',$goodslog); //记录日志





                if ($id) {

                    foreach ($payitem as $key => $value) {

                        $payitem[$key]['atid']=$id;

                    }



                    foreach ($payitem as $key => $value) {

                        pdo_insert('sz_yi_activity_payitem',$value);

                    }

                    show_json(1,'添加成功');

                }

                show_json(0,'添加失败');

            }else{



                $pys=pdo_fetchall('select id from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and atid = :id and enabled = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

                $pys=m('tools')->trarr($pys,'id');



                foreach ($payitem as $key => $value) {



                    if ($value['id']) {

                        $sure=array_search($value['id'],$pys);

                        if ($sure >= 0) {

                            unset($pys[$sure]);

                        }



                        $tdata=array(

                            'title'=>$value['title'],

                            'money'=>$value['money'],

                            'limit'=>$value['limit']

                        );

                        pdo_update('sz_yi_activity_payitem',$tdata,array('id'=>$value['id']));

                    }else{

                        $tdata=array(

                            'uniacid'=>$_W['uniacid'],

                            'title'=>$value['title'],

                            'money'=>$value['money'],

                            'limit'=>$value['limit'],

                            'enabled'=>1,

                            'atid'=>$id,

                            'ctime'=>time()

                        );

                        pdo_insert('sz_yi_activity_payitem',$tdata);

                    }

                }



                foreach ($pys as $key => $value) {

                    pdo_update('sz_yi_activity_payitem',array('enabled'=>0),array('id'=>$value,'uniacid'=>$_W['uniacid']));

                }

                $re=pdo_update('sz_yi_activity',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));



                if ($logid) {

                    pdo_update('sz_yi_activity_log',$goodslog,array('id'=>$logid));

                }else{

                    pdo_insert('sz_yi_activity_log',$goodslog);

                }





                if ($re) {

                    show_json(1,array('修改成功!'));

                }

                show_json(0,array('修改失败!'));



            }



        }

    }



}else if($op == 'refund'){

    $id=$_GPC['id'];

    $pindex=max(1,intval($_GPC['page']));

    $psize=20;



    $condition='';

    $sql='select s.*,a.title from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.paystatus = 2';

    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

    $params=array(

        ':uniacid'=>$_W['uniacid'],

        ':id'=>$id

    );



    $list=pdo_fetchall($sql,$params);



    foreach ($list as $key => $value) {

        $tinfo=unserialize($value['data']);

        $list[$key]=array_merge($value,$tinfo);

    }



    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.paystatus = 2',$params);



    $pager = pagination($totals, $pindex, $psize);



}else if($op == 'signup'){

    // $muser=m('activity')->getMuser($_W['uid']);

    // !$muser && message('你还没有注册!请先注册!',referer(),'error');

    if ($ac == 'audit') {

        $id=intval($_GPC['id']);

        $data=array(

            'status'=>$_GPC['check']

        );



        $re=pdo_update('sz_yi_activity_signup',$data,array('uniacid'=>$_W['uniacid'],'id'=>$id));



        if ($re) {

            message('审核成功!',referer(),'success');

        }

        message('审核失败!',referer(),'error');

    }

    $id=$_GPC['id'];

    $pindex=max(1,intval($_GPC['page']));

    $psize=20;



    $condition='';

    $params=array(

        ':uniacid'=>$_W['uniacid'],

        ':id'=>$id

    );



    if ($_GPC['realname']) {

        $condition.=' and s.data like :realname ';

        $params[':realname']='%'.$_GPC['realname'].'%';

    }



    if ($_GPC['mobile']) {

        $condition.=' and s.data like :mobile ';

        $params[':mobile']='%'.$_GPC['mobile'].'%';

    }



    if ($_GPC['status']) {

        $condition.=' and s.status = :status ';

        $params[':status']=intval($_GPC['status'])-1;

    }



    if ($_GPC['signin']) {

        if ($_GPC['signin'] == 1) {

            $condition.=' and s.signin > 0 ';

        }else{

            $condition.=' and s.signin = 0 ';

        }

    }





    $sql='select s.*,a.title from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id';

    $sql.=$condition;

    $sql.=' order by s.id desc ';

    if ($_GPC['export'] ==1) {



    }else{

        $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

    }





    $list=pdo_fetchall($sql,$params);

    $columns = array(

        array('title' => 'id', 'field' => 'id','width' => 12),

        array('title' => '姓名', 'field' => 'realname', 'width' => 12),

        array('title' => '联系方式', 'field' => 'mobile', 'width' => 12),

        array('title' => '单位', 'field' => 'unit', 'width' => 18),

        array('title' => '报名项目', 'field' => 'title', 'width' => 50),

        array('title' => '报名费', 'field' => 'money', 'width' => 12),

        array('title' => '报名时间', 'field' => 'ctime', 'width' => 18),

        array('title' => '状态', 'field' => 'status', 'width' => 12),

        array('title' => '签到', 'field' => 'signin', 'width' => 12),

    );


    $openIdArray = array();
    foreach ($list as $key =>&$value) {

        $tinfo=unserialize($value['data']);

        if ($_GPC['export'] ==1) {

            $list[$key]['realname']=$tinfo['realname']['data'];

            $list[$key]['mobile']=$tinfo['mobile']['data'];

            $list[$key]['unit']=$tinfo['unit']['data'];

//            数据长度不是固定的
            foreach ($tinfo as $index =>&$value2){
                if(is_numeric($index)){
                    $field = $value2['field'];
                    $list[$key][$field] = $value2['data'];
                    if($key==10){
                        $columns[] = array('title'=>$value2['title'],'field'=>$field,'width'=>15);
                    }
                }
            }


        }else{
            $list[$key]=array_merge($value,$tinfo);
        }

        $openIdArray[] = $value['openid'];

    }

    $activity=m('activity')->getact($id);

    if ($_GPC['export'] == '1') {

        foreach ($list as $key => $value) {

            $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

            if ($value['status'] == 0){

                $list[$key]['status']='待审核';

            }else if ($value['status'] == 1){

                $list[$key]['status']='通过';

            }else if ($value['status'] == 2){

                $list[$key]['status']='驳回';

            }



            if ($value['signin'] > 0) {

                $list[$key]['signin']='已签到';

            }else{

                $list[$key]['signin']='未签到';

            }



        }

//    var_dump($columns);
//        var_dump($list);
//        die();
        m('excel')->export(

            $list, array(

                'title' => '报名数据',

                'columns' => $columns

            )

        );

        exit;

    }





    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id '.$condition,$params);



    $pager = pagination($totals, $pindex, $psize);



}else if($op == 'signin'){

    // $muser=m('activity')->getMuser($_W['uid']);

    // !$muser && message('你还没有注册!请先注册!',referer(),'error');



    $id=$_GPC['id'];

    $pindex=max(1,intval($_GPC['page']));

    $psize=20;



    $condition='';

    $sql='select s.*,a.title from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.signin > 0';

    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;

    $params=array(

        ':uniacid'=>$_W['uniacid'],

        ':id'=>$id

    );



    $list=pdo_fetchall($sql,$params);



    foreach ($list as $key => $value) {

        $tinfo=unserialize($value['data']);

        $list[$key]=array_merge($value,$tinfo);

    }



    $totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' s left join '.tablename('sz_yi_activity').' a on s.actid = a.id where a.uniacid = :uniacid and a.id = :id and s.signin > 0',$params);



    $pager = pagination($totals, $pindex, $psize);



}else if($op == 'delete'){

    $id=intval($_GPC['id']);



    $act=m('activity')->getact($id);



    $re=pdo_delete('sz_yi_activity',array('id'=>$id,'uniacid'=>$_W['uniacid']));



    if ($re) {

        show_json(1,'删除成功');

    }



    show_json(1,'删除失败');

}else if($op == 'netmap'){

    $id=intval($_GPC['id']);

    $act=m('activity')->getact($id);

    $muser=m('activity')->getMuser($act['openid']);



    if ($ac == 'netmap') {

        $tree=pdo_fetchall('select id,pid pId,data from '.tablename('sz_yi_activity_signup').' s where uniacid = :uniacid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));

        foreach ($tree as $key => $value) {

            $tdate=unserialize($value['data']);

            unset($tree[$key]['data']);

            $tree[$key]['name']=$tdate['realname']['data'].'('.m('activity')->countChild($tree,$value['id']).')';

            $tree[$key]['title']='';

        }



        $tree=m('activity')->reSort($tree);

        $tinfo=array(

            'children_num'=>m('activity')->countChild($tree,0),

            'parent_num'=>0,

            'sibling_num'=>0

        );

        $org=array(

            'name'=>$muser['orgName'],

            'title'=>'',

            'relationship'=>$tinfo,

            'children'=>$tree

        );

    }else{      //树状



        $tree=pdo_fetchall('select id,pid pId,channel,data from '.tablename('sz_yi_activity_signup').' s where uniacid = :uniacid and actid = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));



        $tcount=m('activity')->countChild($tree,0);



        $tarr=array(

            'id'=>0,

            'pId'=>0,

            'open'=>true,

            'name'=>$muser['orgName'].'('.$tcount.')',

            'isParent'=>$tcount >0 ?true:false

        );



        foreach ($tree as $key => $value) {

            $tdata=unserialize($value['data']);

            unset($tree[$key]['data']);

            $count=m('activity')->countChild($tree,$value['id']);

            $tree[$key]['open']=false;

            $tree[$key]['isParent']=$count >0 ?true:false;

            if ($value['channel'] == 0 && $value['pid'] == 0) {

                $tree[$key]['name']=$tdata['realname']['data'].'(活动广场'.$count.')';

            }else{

                $tree[$key]['name']=$tdata['realname']['data'].'(转发'.$count.')';

            }

        }

        array_push($tree,$tarr);

    }

}else if($op == 'live'){

    $id=intval($_GPC['id']);



    $act=m('activity')->getact($id);

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



include $this -> template('activity');