<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$popenid        = m('user')->islogin();
$openid = m('user')->getOpenid();
$openid = $openid?$openid:$popenid;
// echo $openid;
$member         = m('member')->getInfo($openid);
$template_flag  = 0;
$diyform_plugin = p('diyform'); 

 empty($_GPC['merch']) && die('<script>alert("非法参数!");location.href="'.referer().'"</script>');

if ($_GPC['merch'] == 5) {
    $info=p('bonus')->getMerch($openid,'deal');
}else if ($_GPC['merch'] == 3) {
   $info=p('bonus')->getMerch($openid,'merch');
}else if ($_GPC['merch'] == 2) {
    $info=p('bonus')->getMerch($openid,'common');
}

$info['changed'] ==1 && die('<script>alert("你已经修改过该商家的地址，不可再修改");location.href="'.referer().'"</script>');

if ($_W['isajax']) {
    if ($_W['ispost']) {
        $memberdata = $_GPC['memberdata'];
            
            $data=array(
                'provance'=>$memberdata['province'],
                'city'=>$memberdata['city'],
                'area'=>$memberdata['area'],
                'changed'=>'1'
            );

            pdo_update('sz_yi_perm_user', $data, array(
                'openid' => $openid,
                'uniacid' => $_W['uniacid'],
                'id'=>$info['id']
            ));
        show_json(1);
        }
        show_json(1, array(
        'member' => $member,
        'info'=>$info
    ));
} 


include $this->template('editaddr');

