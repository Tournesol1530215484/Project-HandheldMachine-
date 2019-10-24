<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/26 0026
 * Time: 17:01
 */

global $_W,$_GPC;
$op=$_GPC['method'];
if ($op='display'){

}

load()->func('tpl');
include $this->template('applyfor');