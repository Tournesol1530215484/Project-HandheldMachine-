<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>海生科技</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
<meta name="format-detection" content="telephone=no" />
<style type="text/css">
    *{margin: 0;padding: 0;}
    #container {
        position: absolute;
        height: 100%;
        width: 100%;
    }
    #output {
        width: 100%;
        height: 100%;
    }
</style>
<script type="text/javascript" src="../addons/sz_yi/template/mobile/style1/static/js/vector.js"></script>
</head>
<body>
<div id="container"><div id="output"></div></div>
<script>
window.onload = function(){
	//配色方案
	/*var theme = [
		["#002c4a", "#005584"],
		["#35ac03", "#3f4303"],
		["#ac0908", "#cd5726"],
		["#18bbff", "#00486b"]
	]*/
    // 初始化 传入dom id
	var victor = new Victor("container", "output");
	victor(["#000000", "#282f31"]).set();
};
</script> 
</body>
</html>