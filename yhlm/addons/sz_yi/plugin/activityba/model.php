<?php

if (!defined('IN_IA')){
    exit('Access Denied');
}
define('TM_activity_PAY', 'activity_pay');
if (!class_exists('activitybaModel')){
    class activitybaModel extends PluginModel{
        public $parentAgents = "";                             

       function perms(){
            return array(
    				'activityba' => array(
    					'text' => $this -> getName(), 
    					'isplugin' => true,  
    					'child' => array(      
    						'activity' => array('text' => '活动设置'),
                            'interact' => array('text' => '我发布的活动'),
                            'control' => array('text' => '大屏幕控制台'),
                        ),       
    				)                
    		); 
        } 
        
    }
}
         
