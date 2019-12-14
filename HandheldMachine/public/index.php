<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//定义图片目录
define('PICTURE', __DIR__ . '/../public/static/uploads/');

//定义地图目录
define('MAP', __DIR__ . '/../public/static/uploads');
//定义地图目录
define('MAPList', __DIR__ . '/../public/map/List');

//定义图片上传地址
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
