<?php
 
global $_W, $_GPC;

$openid = m('user')->getOpenid();
$member = m('member')->getMember($openid);
$uid = pdo_fetchcolumn(' select uid from  '.tablename('sz_yi_perm_user')." where openid = '{$openid}' limit 1 ");
 
$op = empty($_GPC['op']) || !in_array($_GPC['op'], array('display','get','post','upload') )?'display': $_GPC['op'];
$type = empty($_GPC['type']) || !in_array($_GPC['type'], array(0,1 ) )?0:$_GPC['type'];

if($op == 'get' && $_W['isajax']){
    $psize = 5 ;
    $page = empty($_GPC['page'])?0:$_GPC['page']; 
    $goods = pdo_fetchall('select *  from  '.tablename('sz_yi_goods')." where  uniacid = '{$_W['uniacid']}'  and  supplier_uid = '{$uid}' and status = {$type}  order by createtime desc  limit   ".($page*$psize)." , {$psize}");

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

        if( $ac == 'get' ){
              
              if(!empty($goods)){
                   $goods['thumb'] = array( $goods['thumb'] , $_W['attachurl'].$goods['thumb'] );
                   $goods['thumb_url'] = unserialize($goods['thumb_url']);

                   foreach ($goods['thumb_url'] as $key => &$value) {
                       $value = array($value,$_W['attachurl'].$value );
                   }
                   unset($value);
              }
              show_json(1,array('goods'=>$goods,'status'=>empty($goods)?false:true));

        }elseif($ac == 'sub'){

 /*
            $count = count($_FILES['post']['name']);
            if($count>6||$count<1){
                 show_json(1,array('status'=>false,'msg'=>'上传文件数量在1~6'));
            }
            $img_arr = array();
            for ($i=0; $i < $count; $i++) { 
               $type = explode('/',$_FILES['post']['type'][$i]);
               if(!is_array($type)||count($type)!=2||$type[0]!='image'|| !in_array($type[1],array('png','jpg','jpeg') )|| $_FILES['post']['error'][$i] !=0   ){
                    show_json(1,array('status'=>false,'msg'=>'上传文件格式出错'));
               }
               $file = '/'.time().rand(100,10000).".{$type[1]}";
               move_uploaded_file($_FILES['post']['tmp_name'][$i],ATTACHMENT_ROOT.$file);
               $img_arr[] = $file;
            }
*/

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
               show_json(1,array('msg'=>'缺少原价','status'=>false));
            }
            if(empty($_GPC['productprice'])){
               show_json(1,array('msg'=>'缺少成本价','status'=>false));
            }





            $data = array(
              'uniacid' => intval($_W['uniacid']), 
              'title' => trim($_GPC['title']), 
              'pcate' => intval($_GPC['pcate']), 
              'ccate' => intval($_GPC['ccate']), 
             // 'tcate' => intval($_GPC['category']['thirdid']), 
              'thumb' => $_GPC['post'][0],  
              'type' => 1, 
              'createtime' => TIMESTAMP, 
              'marketprice' => floatval($_GPC['marketprice'])  , 
              'costprice' => floatval($_GPC['costprice']), 
              'productprice' => floatval($_GPC['productprice']),
              'content' =>  $_GPC['content'],
              'status' => empty($_GPC['status'])?0:1,
             // 'thumb_url' => serialize($_GPC['post']),
              'supplier_uid' =>$uid 
                );



            unset($_GPC['post'][0]);
            $data['thumb_url'] = serialize($_GPC['post']);



            if(empty($goods)){
              pdo_insert('sz_yi_goods',$data);
              show_json(1,array('id'=>pdo_insertid(),'status'=>true));
            }else{
              pdo_update('sz_yi_goods',$data,array('id'=>$id));
              show_json(1,array('id'=>$id,'status'=>true));
            }

 
 


        }

   }

  


	  include $this->template('goods_upload');
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






 
include $this->template('goods');
 