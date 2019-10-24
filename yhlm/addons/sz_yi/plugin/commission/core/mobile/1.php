<?php
/**
 * 分销中心
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */
 
global $_W, $_GPC;
$openid = m('user')->getOpenid();
$pluginbonus = p("bonus");
$bonus = 0;
$aaa = $this->model->checkAgent();
print_r($aaa);exit;
