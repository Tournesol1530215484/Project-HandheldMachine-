<?php
    //引入权限类

    require_once(IA_ROOT . '/framework/library/qiniu/autoload.php');    

    //引入文件上传类
    require_once(IA_ROOT . '/framework/library/qiniu/src/Qiniu/Storage/UploadManager.php'); 

    //这里的是如果静态处理的话就是
    //  $bucket = 'yhlm';
    //  $accessKey = 'XLTpD9g6brmqlobhg3ViXr0mluwya4y0w2fVfn36';
    //  $secretKey = 'wFRTM6Xy-3BHeuvjvA1MBF347_RYJWjzRNL5ZCss';
    //  $auth = new Auth($accessKey, $secretKey);   

    //这里就是获取系统配置信息，跟上面是一个作用
    $auth = new Qiniu\Auth($_W['setting']['remote']['qiniu']['accesskey'],
                           $_W['setting']['remote']['qiniu']['secretkey']);

    //创建文件上传对象
    $uploadmgr = new Qiniu\Storage\UploadManager();

    //这里是配置回调地址


    ////这个文件位置是自己的域名可以直接访问的,文件内容见下面
    $policy = array(
    'callbackUrl' => 'http://jhzh66.com/addons/sz_yi/plugin/qiniu/upload_verify_callback.php',  

    'callbackBody' => 'filename=$(fname)&filesize=$(fsize)'
  );

    //这样一来，$uploadtoken就可以获取到了，到这里直接把这个给前端，后台工作就完成了，
    //如果是继续用php上传，就看下面的代码
     $uploadtoken = $auth->uploadToken($_W['setting']['remote']['qiniu']['bucket'], $filename, 3600, $policy);





 if(!empty($_FILES['files'])){

          ////获取文件上传数量

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

             //$file=$this->resize($file,750,750); //修改分辨率
             
            $file=resize($file,640,200);    //这里后面会附带一个压缩文件代码，不压缩的这个可以直接注释掉


            //这个代码很关键，这是上传图片到本地服务器， ATTACHMENT_ROOT  系统文件目录

             move_uploaded_file($_FILES['files']['tmp_name'][$i],ATTACHMENT_ROOT.$file); 

             //把文件上传到七牛元 

            list($ret, $err) = $uploadmgr->putFile($uploadtoken, $file, ATTACHMENT_ROOT. '/'.$file);

            //删除原来服务器上面的图片
              if (file_exists(ATTACHMENT_ROOT . '/' . $file)) {

                @unlink(ATTACHMENT_ROOT . '/' . $file);

              }

              //获取图片的显示地址  $_W['attachurl']  就是之前配置的七牛云用户自定义url，http://q.jhzh66.com 
             $img_arr[] = array($file,$_W['attachurl'].$file) ;

          }

          show_json(1,array('url'=>$img_arr));

      }

      show_json(1,array('status'=>false,'msg'=>'empty'));
