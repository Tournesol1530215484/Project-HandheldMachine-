<?php
 if (!defined('IN_IA')){
    print 'Access Denied';
}
global $_W,$_GPC;

$action = array('doprint','express','printset','senduser','short'); 

$action = !empty($_GPC['ac'])&&in_array($_GPC['ac'],$action)?$_GPC['ac']:'doprint';




include dirname(__FILE__)."/exhelper/{$action}.php";




