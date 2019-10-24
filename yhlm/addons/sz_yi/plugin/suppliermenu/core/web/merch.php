<?php

 if (!defined('IN_IA')){

    print 'Access Denied';

}

global $_W,$_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';         //默认display
$id        = empty($_GPC['id']) ? "" : $_GPC['id'];

if($operation == 'display'){  
    
	  if(empty($_W['merchid'])){

	      message('您不是商家', '', 'error');

	  }

	$sql='select * from '.tablename('sz_yi_merch_user')." where id=:merchid and uniacid=:uniacid ";
	$item=pdo_fetch($sql,array(':merchid'=>$_W['merchid'],':uniacid'=>$_W['uniacid']));

	$item['content'] =$item['details'];

    $type=pdo_fetchall('select `id`,`title`  from '.tablename('sz_yi_merch_type')." where uniacid=:uniacid and status=1",array(':uniacid'=>$_W['uniacid']));
} 
else if($operation == 'post'){

	if (checksubmit('submit')) {
        $content = htmlspecialchars_decode($_GPC['content']);

        preg_match_all('/<img.*?src=[\\\'| "](.*?(?:[\\.gif|\\.jpg|\\.png|\\.jpeg]?))[\\\'|"].*?[\\/]?>/', $content, $imgs);

        $images = array();

        if (isset($imgs[1])) {

            foreach ($imgs[1] as $img) {
                $im = array('old' => $img, 'new' => save_media($img));
                $images[] = $im;

            }

        }

        foreach ($images as $img) {
            $content = str_replace($img['old'], $img['new'], $content);
        }
		$data = array(
			'merchname'      => trim($_GPC['merchname']),
			'img'   	     => save_media($_GPC['img']),
			'logo' 		     => save_media($_GPC['logo']),
			'lat'            => trim($_GPC['map']['lat']),
			'lng'            => trim($_GPC['map']['lng']),
			'mobile'         => intval($_GPC['mobile']),
			'address'        => trim($_GPC['address']),
			'title'	         => trim($_GPC['title']),
			'average'	     => trim($_GPC['average']),
            'typeid'         => intval($_GPC['typeid']),
            'details'        => trim($content),
            'province'       => trim($_GPC['reside']['province']),
            'city'           => trim($_GPC['reside']['city']),
            'district'       => trim($_GPC['reside']['district']),
            'hours'          => trim($_GPC['hours']),
		    'num'            => intval($_GPC['num']),
		);
		pdo_update('sz_yi_merch_user', $data, array('id' => $id));
		message('保存成功!',$this->createPluginWebUrl('suppliermenu/merch' ), 'success');
	}
}

load()->func('tpl');
include $this->template('merch');