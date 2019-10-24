<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/15
 * Time: 19:23
 */

if (!defined('IN_IA')){

    exit('Access Denied');

}

global $_W, $_GPC;

include $this->template('member/login');