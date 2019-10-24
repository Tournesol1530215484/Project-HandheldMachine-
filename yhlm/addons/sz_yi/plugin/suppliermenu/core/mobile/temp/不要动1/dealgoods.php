<?php



global $_W, $_GPC;



$popenid        = m('user')->islogin();

$openid = m('user')->getOpenid();

$openid = $openid?$openid:$popenid;

$member = m('member')->getMember($openid);

$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' and dealmerchid > 0 limit 1 ");

if (!$uid) {

    m('tools')->tips('你还不是换货商家请先成为换货商家!',$this->createPluginMobileUrl('supplier/af_supplier',array('merch'=>5)));

}

$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('display','get','post','upload','getstore') )?'display': $_GPC['op'];

$type = empty($_GPC['type']) || !in_array($_GPC['type'], array(0,1 ) )?0:$_GPC['type'];



if ($op == 'getstore' && $_W['isajax']){

    $list=pdo_fetchall('select id,address,mobile from '.tablename('sz_yi_exchange_address').' where status = 1');

    show_json(0,$list);

}

$dispatch_data = pdo_fetchall('select * from' . tablename('sz_yi_dispatch') . 'where uniacid =:uniacid and enabled = 1 order by displayorder desc', array(':uniacid' => $_W['uniacid']));

if($op == 'get' && $_W['isajax']){

    $psize = 5 ;

    $page = empty($_GPC['page'])?0:$_GPC['page'];

    $conditon='';

    if ($_GPC['merch'] == 5){

        $conditon.=' and type = 8 ';

    }

    $goods = pdo_fetchall('select *  from  '.tablename('sz_yi_goods')." where  uniacid = '{$_W['uniacid']}'  and  supplier_uid = '{$uid}' and status = {$type} {$conditon} order by createtime desc  limit   ".($page*$psize)." , {$psize}");



    foreach ($goods as $key => &$value) {

        $value['thumb'] = tomedia($value['thumb']);

    }



    unset($value);

    show_json(1,array('goods'=>$goods,'status'=>count($goods)<$psize?false:true));



}elseif($op == 'post'){



    $id = empty($_GPC['id'])?false:$_GPC['id'];

    if($_W['ispost'] ){



        $ac = empty($_GPC['ac'])||!in_array( $_GPC['ac'] , array('get','sub') )?'get':$_GPC['ac'];

        $goods = $id?pdo_fetch(' select * from '.tablename('sz_yi_goods')." where id = '{$id}' and uniacid = '{$_W['uniacid']}' and supplier_uid = '{$uid}' limit 1 "):null;



        if( $ac == 'get' ){ //获取修改数据



            if(!empty($goods)){

                $goods['thumb'] = array( $goods['thumb'] , $_W['attachurl'].$goods['thumb'] );

                $goods['thumb_url'] = unserialize($goods['thumb_url']);

                $goods['option']=pdo_fetchall(' select * from '.tablename('sz_yi_goods_option').' where goodsid = :goodsid and uniacid = :uniacid order by id asc ',array(':goodsid'=>$goods['id'],':uniacid'=>$_W['uniacid']));



                foreach ($goods['thumb_url'] as $key => &$value) {

                    $value = array($value,$_W['attachurl'].$value );

                }

                unset($value);

            }

            show_json(1,array('goods'=>$goods,'status'=>empty($goods)?false:true));



        }elseif($ac == 'sub'){

            // $temp=explode(',',$_GPC['option'][0]);



            if(empty($_GPC['title'])){

                show_json(1,array('msg'=>'缺少商品标题','status'=>false));

            }



            if(empty($_GPC['pcate'])){

                show_json(1,array('msg'=>'缺少一级分类','status'=>false));

            }



            if(empty($_GPC['ccate'])){

                show_json(1,array('msg'=>'缺少二级分类','status'=>false));

            }



            if(empty($_GPC['post'])){

                show_json(1,array('msg'=>'缺少图片','status'=>false));

            }

            //修改地址

            if(empty($_GPC['city'])){

                show_json(1,array('msg'=>'缺少发货地址','status'=>false));

            }



            $data = array(

                'uniacid'      => intval($_W['uniacid']),

                'title'        => trim($_GPC['title']),

                'city'        => trim($_GPC['city']),   //修改

                'pcate'        => intval($_GPC['pcate']),

                'ccate'        => intval($_GPC['ccate']),

                'thumb'        => $_GPC['post'][0],

                'totalcnf'     => 1,

                'maxbuy'       => intval($_GPC['maxbuy']),

                'usermaxbuy'   => intval($_GPC['usermaxbuy']),

                // 'tcate'     => intval($_GPC['category']['thirdid']),

                'type'         => 8,

                'createtime'   => TIMESTAMP,

                // 'marketprice'  => floatval($_GPC['marketprice']),

                // 'costprice'    => floatval($_GPC['costprice']),

                // 'productprice' => floatval($_GPC['productprice']),

                'content'      => htmlspecialchars_decode($_GPC['content']),

                'status'       => 0,

                'supplier_uid' => $uid,

                // 'total'        => floatval($_GPC['total']),

                // 'weight'       => floatval($_GPC['weight']),

                'PostFlag'     => 1,

                'isCheck'      => 0,

                'shelves'      =>1,

                'dispatchtype' =>intval($_GPC['dispatchtype']),

                'dispatchprice'=>intval($_GPC['dispatchprice']),

                'dispatchid'   =>intval($_GPC['dispatchid'])

            );

            if (empty($data['supplier_uid'])) {

                show_json(0,'你还不是易货商家');

            }

            if ( $data['status'] == 1 ) {

                // 判断是否有上架权限

                $set = p('supplier')->getSet();

                if (!in_array('suppliermenu.shelves', $set['power'])) {

                    show_json(1, array('msg'=>'您没有权限，请放入库存！', 'status'=>false));

                }

            }







            $logid=pdo_fetchcolumn('select id from '.tablename('sz_yi_goods_log').' where uniacid = :uniacid and goodsid = :goodsid and status = 0',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$id));

            $goodslog=[

                'uniacid'=>$_W['uniacid'],

                'uid'=>$_W['uid'],

                'goodsid'=>$id,

                'sub_time'=>time(),

                'status'=>0

            ];











            unset($_GPC['post'][0]);

            $data['thumb_url'] = serialize($_GPC['post']);

            $data['uptime']=time();



            if(empty($goods) && empty($logid)){

                pdo_insert('sz_yi_goods',$data);

                $goodsid=pdo_insertid();



                $goodslog['goodsid']=$goodsid;

                pdo_insert('sz_yi_goods_log',$goodslog);

                plog('shop.goods.edit', "添加商品 ID: {$id}");



                if (!empty($_GPC['option'])) {

                    $spec=[

                        'uniacid'=>$_W['uniacid'],

                        'goodsid'=>$goodsid,

                        'title'  =>'规格'

                    ];

                    pdo_insert('sz_yi_goods_spec',$spec);

                    $specid=pdo_insertid();

                    $option=[];

                    $spec_item=[];

                    $total=intval(0);

                    foreach ($_GPC['option'] as $key => $val) {

                        $temp=explode(',',$_GPC['option'][$key]);

                        $total+=intval($temp[2]);

                        $option[$key]=[

                            'goodsid'=>$goodsid,

                            'productprice'=>$temp[0],

                            'marketprice'=>$temp[1],

                            'costprice'=>$temp[1],

                            'stock'=>$temp[2],

                            'title'=>$temp[3],

                            //'city'=>$temp[4],

                            'uniacid'=>$_W['uniacid']

                        ];

                        $spec_item[$key]=[

                            'uniacid'=>$_W['uniacid'],

                            'specid' =>$specid,

                            'title' =>$temp[3],

                            'show'  =>1,

                            'displayorder'=>$k

                        ];

                    }

                    $specids=[];



                    for ($i=0; $i <count($spec_item); $i++) {

                        pdo_insert('sz_yi_goods_spec_item',$spec_item[$i]);

                        $tempid=pdo_insertid();

                        $specids[]=$tempid;

                        $option[$i]['specs'] = $tempid;

                        pdo_insert('sz_yi_goods_option',$option[$i]);

                    }

                    pdo_update('sz_yi_goods',array('total'=>$total,'hasoption'=>1),array('id'=>$goodsid,'uniacid'=>$_W['uniacid']));

                    pdo_update('sz_yi_goods_spec',array('content' =>serialize($specids)),array('id'=>$specid,'uniacid'=>$_W['uniacid']));



                }







                show_json(1,array('id'=>pdo_insertid(),'status'=>true));

            }else{   //如果存在商品









                if (!empty($_GPC['option'])) {

                    $option=[];

                    $spec_item=[];

                    $temp=intval(0);

                    $specid=pdo_fetchcolumn('select id from '.tablename('sz_yi_goods_spec').' where uniacid = :uniacid and goodsid = :goodsid ',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$id));

                    $oldspecids=pdo_fetchcolumn('select content from '.tablename('sz_yi_goods_spec').' where uniacid = :uniacid and goodsid = :goodsid ',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$id));

                    $total=0;

                    foreach ($_GPC['option'] as $key => $val) {

                        $temp=explode(',',$_GPC['option'][$key]);

                        $total+=intval($temp[2]);

                        $option[$key]=[

                            'goodsid'=>$id,

                            'productprice'=>$temp[0],

                            'marketprice'=>$temp[1],

                            'costprice'=>$temp[1],

                            'stock'=>$temp[2],

                            'title'=>$temp[3],

                           // 'city'=>$temp[4],//修改

                            'uniacid'=>$_W['uniacid']

                        ];

                        $spec_item[$key]=[

                            'uniacid'=>$_W['uniacid'],

                            'specid' =>$specid,

                            'title' =>$temp[3],

                            'show'  =>1,

                            'displayorder'=>$k

                        ];

                    }

                    $specids=[];

                    $optionids=[];

                    for ($i=0; $i <count($spec_item); $i++) {

                        pdo_insert('sz_yi_goods_spec_item',$spec_item[$i]);

                        $tempid=pdo_insertid();

                        $specids[]=$tempid;

                        $option[$i]['specs'] = $tempid;

                        pdo_insert('sz_yi_goods_option',$option[$i]);

                        $tempoptionid=pdo_insertid();

                        $optionids[]=$tempoptionid;

                    }

                    $oldspecids=unserialize($oldspecids);



                    pdo_query('delete from ' . tablename('sz_yi_goods_spec_item') . " where specid=$specid and id in ( " . implode(',', $oldspecids) . ')');

                    pdo_query('delete from ' . tablename('sz_yi_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ')');

                    pdo_update('sz_yi_goods',array('total'=>$total,'hasoption'=>1),array('id'=>$id,'uniacid'=>$_W['uniacid']));

                    pdo_update('sz_yi_goods_spec',array('content' =>serialize($specids)),array('id'=>$specid,'uniacid'=>$_W['uniacid']));

                }



                pdo_update('sz_yi_goods',$data,array('id'=>$id));

                if ($logid) {

                    pdo_update('sz_yi_goods_log',$goodslog,array('id'=>$logid));

                }else{

                    pdo_insert('sz_yi_goods_log',$goodslog);

                }



                plog('dealmerch.dealgoods.edit', "编辑商品 ID: {$id}");

                show_json(1,array('id'=>$id,'status'=>true));

            }





        }



    }





    /**

     * 对象 转 数组

     *

     * @param object $obj 对象

     * @return array

     */

    function object_to_array($obj) {



        $obj = (array)$obj;

        return  $obj;

        foreach ($obj as $k => $v) {

            if (gettype($v) == 'resource') {

                return;

            }

            if (gettype($v) == 'object' || gettype($v) == 'array') {

                $obj[$k] = (array)object_to_array($v);

            }

        }



        return $obj;

    }
    if(isset($_GPC['to']) && $_GPC['to']=='test' ){
        include $this->template('dealgoods_upload2');

        exit;
    }
    include $this->template('dealgoods_upload');

    exit;

}elseif($op == 'upload'){



    if(!empty($_FILES['files'])){

        $count = count($_FILES['files']['name']);

        if($count>6||$count<1){

            show_json(1,array('status'=>false,'msg'=>'上传文件数量在1~6'));

        }

        $img_arr = array();

        for ($i=0; $i < $count; $i++) {

            $type = explode('/',$_FILES['files']['type'][$i]);

            if(!is_array($type)||count($type)!=2||$type[0]!='image'|| !in_array($type[1],array('png','jpg','jpeg') )|| $_FILES['files']['error'][$i] !=0   ){

                show_json(1,array('status'=>false,'msg'=>'上传文件格式出错'));

            }

            $file =   time().rand(100,10000).".{$type[1]}";

            move_uploaded_file($_FILES['files']['tmp_name'][$i],ATTACHMENT_ROOT.$file);

            $img_arr[] = array($file,$_W['attachurl'].$file) ;

        }

        // $img_arr = set_medias($img_arr);

        show_json(1,array('url'=>$img_arr));

    }

    show_json(1,array('status'=>false,'msg'=>''));

}



include $this->template('dealgoods');

