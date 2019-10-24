<?php
if (!defined('IN_IA')){
   exit('Access Denied');
}

global $_W;


            $supplier_perms = 'agency,agency.goods';
 
 
        $result = pdo_fetchcolumn('select id from ' . tablename('sz_yi_plugin') . ' where identity=:identity', array(':identity' => 'agency'));
        if(empty($result)){

        

            $displayorder_max = pdo_fetchcolumn('select max(displayorder) from ' . tablename('sz_yi_plugin'));
            $displayorder = $displayorder_max + 1;

            $sql = 'INSERT INTO ' . tablename('sz_yi_plugin') . ' (`displayorder`,`identity`,`name`,`version`,`author`,`status`,`category`) VALUES(' . $displayorder . ',\'agency\',\'代理商操作台\',\'1.0\',\'me\',\'1\',\'tool\');';
            pdo_query($sql);

              

        }
            $gid = pdo_fetchcolumn('select id from '.tablename('sz_yi_perm_role')." where rolename like '%代理商%' and status1 = 1 and uniacid = '{$_W['uniacid']}' limit 1 ");
            if($gid){
                pdo_update('sz_yi_perm_role',array('perms'=>$supplier_perms,'status1'=>'1','status'=>'1'),array('id'=>$gid));
            }else{
                pdo_insert('sz_yi_perm_role',array('perms'=>$supplier_perms,'status1'=>'1','uniacid'=>$_W['uniacid'],'rolename'=>'代理商','status'=>'1'));
            }




        $fid = pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = 0 and name = 'agency' and title like '%代理商操作台%' limit 1 ");

        if(!$fid){
            pdo_insert('core_menu',array('pid'=>0,'title'=>'代理商操作台','name'=>'agency','is_display'=>1));
            $fid = pdo_insertid();
        }


        // $id = pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $fid  and title like '%店铺管理%' and name = 'agency' limit 1 ");

        // if(!$id){
        //     pdo_insert('core_menu',array('pid'=>$fid,'title'=>'店铺管理','name'=>'agency','is_display'=>1));
        //     $id = pdo_insertid(); 
        // }

            // var_dump($id);exit;

        // if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%商品管理%' and name = 'agency' and permission_name = 'agency_goods' limit 1 ") ){
        //     pdo_insert('core_menu',array('pid'=>$id,'title'=>'商品管理','name'=>'agency','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=goods&p=agency&do=plugin&m=sz_yi','permission_name'=>'agency_goods'));    
        // }

        // if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%订单管理%' and name = 'agency' and permission_name = 'agency_order' limit 1 ") ){
        //     pdo_insert('core_menu',array('pid'=>$id,'title'=>'订单管理','name'=>'agency','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=order&p=agency&do=plugin&m=sz_yi','permission_name'=>'agency_order')); 
        // }


        // if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%店铺装修%' and name = 'agency' and permission_name = 'agency_designer' limit 1 ") ){
        //     pdo_insert('core_menu',array('pid'=>$id,'title'=>'店铺装修','name'=>'agency','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=designer&p=agency&do=plugin&m=sz_yi','permission_name'=>'agency_designer')); 
        // }


        // if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%淘宝助手%' and name = 'agency' and permission_name = 'agency_taobao' limit 1 ") ){
        //     pdo_insert('core_menu',array('pid'=>$id,'title'=>'淘宝助手','name'=>'agency','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=taobao&p=agency&do=plugin&m=sz_yi','permission_name'=>'agency_taobao'));  
        // }


        // if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%快递助手%' and name = 'agency' and permission_name = 'agency_exhelper' limit 1 ") ){
        //     pdo_insert('core_menu',array('pid'=>$id,'title'=>'快递助手','name'=>'agency','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=exhelper&p=agency&do=plugin&m=sz_yi','permission_name'=>'agency_exhelper'));   
        // }



        // if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%店铺入口%' and name = 'agency' and permission_name = 'agency_index' limit 1 ") ){
        //     pdo_insert('core_menu',array('pid'=>$id,'title'=>'店铺入口','name'=>'agency','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=index&p=agency&do=plugin&m=sz_yi','permission_name'=>'agency_index','displayorder'=>1));   
        // }


 