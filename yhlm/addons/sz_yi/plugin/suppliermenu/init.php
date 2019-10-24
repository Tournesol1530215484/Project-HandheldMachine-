<?php
if (!defined('IN_IA')){
   exit('Access Denied');
}

global $_W;


            $supplier_perms = 'shop,shop.goods,shop.goods.view,shop.goods.add,shop.goods.edit,shop.goods.delete,order,order.view,order.view.status_1,order.view.status0,order.view.status1,order.view.status2,order.view.status3,order.view.status4,order.view.status5,order.view.status9,order.op,order.op.pay,order.op.send,order.op.sendcancel,order.op.finish,order.op.verify,order.op.fetch,order.op.close,order.op.refund,order.op.export,order.op.changeprice,exhelper,exhelper.print,exhelper.print.single,exhelper.print.more,exhelper.exptemp1,exhelper.exptemp1.view,exhelper.exptemp1.add,exhelper.exptemp1.edit,exhelper.exptemp1.delete,exhelper.exptemp1.setdefault,exhelper.exptemp2,exhelper.exptemp2.view,exhelper.exptemp2.add,exhelper.exptemp2.edit,exhelper.exptemp2.delete,exhelper.exptemp2.setdefault,exhelper.senduser,exhelper.senduser.view,exhelper.senduser.add,exhelper.senduser.edit,exhelper.senduser.delete,exhelper.senduser.setdefault,exhelper.short,exhelper.short.view,exhelper.short.save,exhelper.printset,exhelper.printset.view,exhelper.printset.save,exhelper.dosen,taobao,taobao.fetch,suppliermenu,suppliermenu.goods';
 
 
        $result = pdo_fetchcolumn('select id from ' . tablename('sz_yi_plugin') . ' where identity=:identity', array(':identity' => 'suppliermenu'));
        if(empty($result)){

        

 
        

            $displayorder_max = pdo_fetchcolumn('select max(displayorder) from ' . tablename('sz_yi_plugin'));
            $displayorder = $displayorder_max + 1;
            $sql = 'INSERT INTO ' . tablename('sz_yi_plugin') . ' (`displayorder`,`identity`,`name`,`version`,`author`,`status`,`category`) VALUES(' . $displayorder . ',\'suppliermenu\',\'供应商操作台\',\'1.0\',\'me\',\'1\',\'tool\');';
            pdo_query($sql);

              

        }

            $gid = pdo_fetchcolumn('select id from '.tablename('sz_yi_perm_role')." where rolename like '%供应商%' and status1 = 1 and uniacid = '{$_W['uniacid']}' limit 1 ");
            if($gid){
                pdo_update('sz_yi_perm_role',array('perms'=>$supplier_perms,'status1'=>'1','status'=>'1'),array('id'=>$gid));
            }else{
                pdo_insert('sz_yi_perm_role',array('perms'=>$supplier_perms,'status1'=>'1','uniacid'=>$_W['uniacid'],'rolename'=>'供应商','status'=>'1'));
            }




        $fid = pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = 0 and name = 'suppliermenu' and title like '%供应商操作台%' limit 1 ");

        if(!$fid){
            pdo_insert('core_menu',array('pid'=>0,'title'=>'供应商操作台','name'=>'suppliermenu','is_display'=>1));
            $fid = pdo_insertid();
        }


        $id = pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $fid  and title like '%店铺管理%' and name = 'suppliermenu' limit 1 ");

        if(!$id){
            pdo_insert('core_menu',array('pid'=>$fid,'title'=>'店铺管理','name'=>'suppliermenu','is_display'=>1));
            $id = pdo_insertid(); 
        }


        if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%商品管理%' and name = 'suppliermenu' and permission_name = 'suppliermenu_goods' limit 1 ") ){
            pdo_insert('core_menu',array('pid'=>$id,'title'=>'商品管理','name'=>'suppliermenu','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=goods&p=suppliermenu&do=plugin&m=sz_yi','permission_name'=>'suppliermenu_goods'));    
        }

        if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%订单管理%' and name = 'suppliermenu' and permission_name = 'suppliermenu_order' limit 1 ") ){
            pdo_insert('core_menu',array('pid'=>$id,'title'=>'订单管理','name'=>'suppliermenu','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=order&p=suppliermenu&do=plugin&m=sz_yi','permission_name'=>'suppliermenu_order')); 
        }


        if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%店铺装修%' and name = 'suppliermenu' and permission_name = 'suppliermenu_designer' limit 1 ") ){
            pdo_insert('core_menu',array('pid'=>$id,'title'=>'店铺装修','name'=>'suppliermenu','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=designer&p=suppliermenu&do=plugin&m=sz_yi','permission_name'=>'suppliermenu_designer')); 
        }


        if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%淘宝助手%' and name = 'suppliermenu' and permission_name = 'suppliermenu_taobao' limit 1 ") ){
            pdo_insert('core_menu',array('pid'=>$id,'title'=>'淘宝助手','name'=>'suppliermenu','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=taobao&p=suppliermenu&do=plugin&m=sz_yi','permission_name'=>'suppliermenu_taobao'));  
        }


        if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%快递助手%' and name = 'suppliermenu' and permission_name = 'suppliermenu_exhelper' limit 1 ") ){
            pdo_insert('core_menu',array('pid'=>$id,'title'=>'快递助手','name'=>'suppliermenu','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=exhelper&p=suppliermenu&do=plugin&m=sz_yi','permission_name'=>'suppliermenu_exhelper'));   
        }



        if( !pdo_fetchcolumn(' select id from '.tablename('core_menu')." where pid = $id  and title like '%店铺入口%' and name = 'suppliermenu' and permission_name = 'suppliermenu_index' limit 1 ") ){
            pdo_insert('core_menu',array('pid'=>$id,'title'=>'店铺入口','name'=>'suppliermenu','is_display'=>1,'type'=>'url','url'=>'./index.php?c=site&a=entry&method=index&p=suppliermenu&do=plugin&m=sz_yi','permission_name'=>'suppliermenu_index','displayorder'=>1));   
        }


 