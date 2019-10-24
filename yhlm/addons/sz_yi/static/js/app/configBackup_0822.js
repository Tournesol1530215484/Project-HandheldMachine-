require.config({
    urlArgs: 'v=201510211500', 
    baseUrl: '../addons/sz_yi/static/js/app',
    paths: {
        'jquery': '../dist/jquery-1.11.1.min',
        'jquery.ui': '../dist/jquery-ui-1.10.3.min',
        'bootstrap': '../dist/bootstrap.min',
        'tpl':'../dist/tmodjs',
        'jquery.touchslider':'../dist/jquery.touchslider.min',
        'swipe':'../dist/swipe',
        'sweetalert':'../dist/sweetalert/sweetalert.min',
        'jquery.SuperSlide.2.1.1':'../dist/jquery.SuperSlide.2.1.1',
        'map': 'http://api.map.baidu.com/getscript?v=2.0&ak=F51571495f717ff1194de02366bb8da9&services=&t=20140530104353',
        'datetimepicker': '../dist/datetimepicker/jquery.datetimepicker',
        'jquery.zclip': '../dist/zclip/jquery.zclip.min'
    },
    shim: {
        'jquery.ui': {
            exports: "$",
            deps: ['jquery']
        },
        'bootstrap': {
            exports: "$",
            deps: ['jquery']
        },  
        'jquery.touchslider': {
            exports: "$",
            deps: ['jquery']
        },
        'sweetalert':{
            exports: "$",
            deps: ['css!../dist/sweetalert/sweetalert.css']
        },
        'jquery.SuperSlide.2.1.1': {
            deps: ['jquery'],
            exports: 'jQuery'
        },
        'map': {
            exports: 'BMap'
        },
        'datetimepicker': {
            exports: '$',
            deps: ['jquery', 'css!../dist/datetimepicker/jquery.datetimepicker.css']
        }
    }
});
