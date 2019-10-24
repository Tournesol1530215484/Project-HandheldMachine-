<?php



global $_W, $_GPC;



$popenid        = m('user')->islogin();

$openid = m('user')->getOpenid();

$openid = $openid?$openid:$popenid;

$member = m('member')->getMember($openid);





if ($_GPC['merch'] == 5){

        $uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' and dealmerchid >0 limit 1 ");

}else if ($_GPC['merch'] == 3){

        $uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' and merchid > 0 limit 1 "); 

}else{ 

        $uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' and dealmerchid = 0 and merchid = 0 limit 1 ");



}

 

$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('display','get','post','upload') )?'display': $_GPC['op'];

$type = empty($_GPC['type']) || !in_array($_GPC['type'], array(0,1 ) )?0:$_GPC['type'];



if($op == 'get' && $_W['isajax']){

    $psize = 5 ; 

    $page = empty($_GPC['page'])?0:$_GPC['page']; 

    $conditon='';

    if ($_GPC['merch'] == 5){

        $conditon.=' and type = 8 ';

    }

    $goods = pdo_fetchall('select *  from  '.tablename('sz_yi_goods')." where  uniacid = '{$_W['uniacid']}' and deleted = 0  and  supplier_uid = '{$uid}' and status = {$type} {$conditon} order by createtime desc limit ".($page*$psize)." , {$psize}"); 

    foreach ($goods as $key => &$value) { 

       $value['thumb'] = tomedia($value['thumb']);

       $value['mainmap'] = tomedia($value['mainmap']);

       if ($_GPC['merch'] == 5) {

          $temp=pdo_fetchall('select marketprice from '.tablename('sz_yi_goods_option').' where uniacid = :uniacid and goodsid = :goodsid',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$value['id']));

         if (!empty($temp)){

            $maxlen=-1;

            foreach ($temp as $k2 => $v2) {

              if ($v2) { 

                $maxlen=$k2; 

              }

            }

            $maxlen+=1;   

            $goods[$key]['how']=$maxlen;  

              if ($maxlen == 1){   

                  $goods[$key]['marketprice']=$temp[0]['marketprice']; 

              }else if ($maxlen > 1){     

                  $len = $maxlen; 

                  for($i=1;$i<$len;$i++){ //该层循环用来控制每轮 冒出一个数 需要比较的次数

                      for($k=0;$k<$len-$i;$k++){ 

                          if(doubleval($temp[$k]['marketprice'])>doubleval($temp[$k+1]['marketprice'])){

                              $tmp=$temp[$k+1]; 

                              $temp[$k+1]=$temp[$k]; 

                              $temp[$k]=$tmp;

                          } 

                      }  

                  }

                  $goods[$key]['minmarketprice']=$temp[0]['marketprice'];  

                  $goods[$key]['maxmarketprice']=$temp[$len-1]['marketprice'];

                  if (doubleval($goods[$key]['minmarketprice']) == doubleval($goods[$key]['maxmarketprice'])) {

                    $goods[$key]['marketprice']=$goods[$key]['minmarketprice'];

                  } 

              }

          }

          $goods[$key]['options']=$temp;

       }



    }

 

    unset($value);

    show_json(1,array('goods'=>$goods,'status'=>count($goods)<$psize?false:true));



}elseif($op == 'post'){



   $id = empty($_GPC['id'])?false:$_GPC['id'];



   if($_W['ispost'] ){ 



        $ac = empty($_GPC['ac'])||!in_array( $_GPC['ac'] , array('get','sub') )?'get':$_GPC['ac'];

        $goods = $id?pdo_fetch(' select * from '.tablename('sz_yi_goods')." where id ={$id} and uniacid ={$_W['uniacid']} and supplier_uid ={$uid} limit 1 "):null; 

        

        if( $ac == 'get' ){  

              if(!empty($goods)){

                   $goods['thumb'] = array( $goods['thumb'] , $_W['attachurl'].$goods['thumb'] );

                  $goods['mainmap'] = array( $goods['mainmap'] , $_W['attachurl'].$goods['mainmap'] );

                   $goods['thumb_url'] = unserialize($goods['thumb_url']);



                   foreach ($goods['thumb_url'] as $key => &$value) {

                       $value = array($value,$_W['attachurl'].$value );

                   }

                   unset($value);

              } 

                     

              show_json(1,array('goods'=>$goods,'status'=>empty($goods)?false:true,'uid'=>$uid));



        }elseif($ac == 'sub'){ 

 

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



            if(empty($_GPC['marketprice'])){

               show_json(1,array('msg'=>'缺少市场价','status'=>false));

            }

            if(empty($_GPC['costprice'])){

               // show_json(1,array('msg'=>'缺少原价','status'=>false));

            }

            if(empty($_GPC['productprice'])){

               // (1,array('msg'=>'缺少成本价','status'=>false));

            }



            $data = array(

              'uniacid'      => intval($_W['uniacid']),

              'title'        => trim($_GPC['title']),

              'pcate'        => intval($_GPC['pcate']),

              'ccate'        => intval($_GPC['ccate']),

              // 'thumb_url' => serialize($_GPC['post']),

              // 'tcate'     => intval($_GPC['category']['thirdid']),

              'thumb'        => $_GPC['post'][0],

             'mainmap'        => $_GPC['post'][1],

              'type'         => 1,

              'createtime'   => TIMESTAMP,

              'marketprice'  => floatval($_GPC['marketprice']),

              'costprice'    => floatval($_GPC['costprice']),

              'productprice' => floatval($_GPC['productprice']),

              'content'      => htmlspecialchars_decode($_GPC['content']),

              'status'       => 0,

              'isCheck'      => 0,

              'supplier_uid' => $uid,

              'total'        => floatval($_GPC['total']),

              'weight'       => floatval($_GPC['weight']),

                );



            // show_json(0, array('data' => $data));



            if ( $data['status'] == 1 ) {

                // 判断是否有上架权限

                $set = p('supplier')->getSet();

                if (!in_array('suppliermenu.shelves', $set['power'])) {

                   show_json(1, array('msg'=>'您没有权限，请放入库存！', 'status'=>false));

                }

            }



            unset($_GPC['post'][0]);

            $data['thumb_url'] = serialize($_GPC['post']);





            $logid=pdo_fetchcolumn('select id from '.tablename('sz_yi_goods_log').' where uniacid = :uniacid and goodsid = :goodsid and status = 0',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$id)); 



            $goodslog=[ 

              'uniacid'=>$_W['uniacid'],  

              'uid'=>$_W['uid'],

              'goodsid'=>$id, 

              'sub_time'=>time(),

              'status'=>0

            ]; 



            if (empty($id)) {  

              pdo_insert('sz_yi_goods', $data);

              $id = pdo_insertid();  

              $goodslog['goodsid']=$id;   

              pdo_insert('sz_yi_goods_log',$goodslog);  

              plog('dealmerch.dealgoods.add', "手机添加商品 ID: {$id}");  

              show_json(1,array('id'=>pdo_insertid(),'status'=>true));

            } else {  

              pdo_update('sz_yi_goods', $data, array('id' => $id));

              if ($logid) { 

                pdo_update('sz_yi_goods_log',$goodslog,array('id'=>$logid));

              }else{  

                pdo_insert('sz_yi_goods_log',$goodslog);

              }              

              plog('dealmerch.dealgoods.edit', "手机编辑商品 ID: {$id}");

              show_json(1,array('id'=>$id,'status'=>true));

            }



            // if(empty($goods)){

            //   pdo_insert('sz_yi_goods',$data);

              

            // }else{

            //   pdo_update('sz_yi_goods',$data,array('id'=>$id));

              

            // }

        }



   }



	  include $this->template('goods_upload');

    exit;

}elseif($op == 'upload'){

    if(isset( $_GPC['ggggtttt'])){

        if(!empty($_FILES['filed'])){

          $count = count($_FILES['filed']['name']);

          if($count>6||$count<1){

               show_json(1,array('filed'=>false,'msg'=>'上传文件数量在1~6'));

          }

          $img_arr = array();

          for ($i=0; $i < $count; $i++) { 

             $type = explode('/',$_FILES['filed']['type'][$i]);

             if(!is_array($type)||count($type)!=2||$type[0]!='image'|| !in_array($type[1],array('png','jpg','jpeg') )|| $_FILES['filed']['error'][$i] !=0   ){

                  show_json(1,array('status'=>false,'msg'=>'上传文件格式出错'));

             }

             $fileds =   time().rand(100,10000).".{$type[1]}";

             move_uploaded_file($_FILES['filed']['tmp_name'][$i],ATTACHMENT_ROOT.$fileds);

             $img_arr[] = array($fileds,$_W['attachurl'].$fileds) ;

          }

         // $img_arr = set_medias($img_arr);

          show_json(1,array('url'=>$img_arr));

      }

      show_json(1,array('status'=>false,'msg'=>'empty'));

    }else{

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

      show_json(1,array('status'=>false,'msg'=>'empty'));

    }

      

}




include $this->template('goods');

 