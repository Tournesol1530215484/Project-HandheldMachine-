<?php
global $_W, $_GPC;
//check_shop_auth('http://120.26.212.219/api.php', $this->pluginname);
$apido = $_GPC['apido'];
if ($_W['isajax'] && $_W['ispost']) {
	if ($apido == 'delarticle') {
		ca('paihang.page.delete');
		$aid = intval($_GPC['aid']);
		$article = pdo_fetch("SELECT * FROM " . tablename('sz_yi_article') . " WHERE id=:aid and uniacid=:uniacid limit 1 ", array(':aid' => $aid, ':uniacid' => $_W['uniacid']));
		if (!empty($article)) {
			pdo_delete('sz_yi_article', array('id' => $aid));
			$keyword = pdo_fetch("SELECT * FROM " . tablename('rule_keyword') . " WHERE content=:content and module=:module and uniacid=:uniacid limit 1 ", array(':content' => $article['article_keyword'], ':module' => 'sz_yi', ':uniacid' => $_W['uniacid']));
			if (!empty($keyword)) {
				pdo_delete('rule_keyword', array('id' => $keyword['id']));
				pdo_delete('rule', array('id' => $keyword['rid']));
			}
			$article_log = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_article_log') . " WHERE aid=:aid and uniacid=:uniacid ", array(':aid' => $article['id'], ':uniacid' => $_W['uniacid']));
			if (!empty($article_log)) {
				pdo_delete('sz_yi_article_log', array('aid' => $article['id']));
			}
			$article_share = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_article_share') . " WHERE aid=:aid and uniacid=:uniacid ", array(':aid' => $article['id'], ':uniacid' => $_W['uniacid']));
			if (!empty($article_log)) {
				pdo_delete('sz_yi_article_share', array('aid' => $article['id']));
			}
			die(json_encode(array('result' => 'success')));
		} else {
			die(json_encode(array('result' => 'error')));
		}
	} elseif ($apido == 'delcategory') {
		ca('paihang.cate.delcate');
		$cid = intval($_GPC['cid']);
		$category = pdo_fetch("SELECT * FROM " . tablename('sz_yi_article_category') . " WHERE id=:cid and uniacid=:uniacid limit 1 ", array(':cid' => $cid, ':uniacid' => $_W['uniacid']));
		if (!empty($category)) {
			$articles = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_article') . " WHERE article_category=:cid and uniacid=:uniacid ", array(':cid' => $cid, ':uniacid' => $_W['uniacid']));
			if (!empty($articles)) {
				die(json_encode(array("result" => "error2")));
			} else {
				pdo_delete('sz_yi_article_category', array('id' => $cid));
				die(json_encode(array('result' => 'success')));
			}
		} else {
			die(json_encode(array('result' => 'error')));
		}
	} elseif ($apido == 'postcategory') {
		$cid = intval($_GPC['cid']);
		$cname = ($_GPC['cname']);
		if (!empty($cname)) {
			$cates = pdo_fetch("SELECT * FROM " . tablename('sz_yi_article_category') . " WHERE category_name=:cname and id<>:cid and uniacid=:uniacid limit 1 ", array(':cid' => $cid, ':cname' => $cname, ':uniacid' => $_W['uniacid']));
			if (!empty($cates)) {
				die(json_encode(array("result" => "error", "desc" => '分类名称已存在')));
			}
			$arr = array("category_name" => $cname, "uniacid" => $_W['uniacid']);
			if (empty($cid)) {
				ca('paihang.cate.addcate');
				pdo_insert('sz_yi_article_category', $arr);
				$insertid = pdo_insertid();
				die(json_encode(array('result' => 'success-add', 'cid' => $insertid, "cname" => $cname)));
			} else {
				ca('paihang.cate.editcate');
				pdo_update('sz_yi_article_category', $arr, array('id' => $cid));
				die(json_encode(array('result' => 'success-edit', 'cid' => $cid, "cname" => $cname)));
			}
		} else {
			die(json_encode(array('result' => 'error', 'desc' => '分类名称为空')));
		}
	} elseif ($apido == 'save') {
		$data = $_GPC['data'];
		// print_r($data);exit;
		$content = htmlspecialchars_decode($content);
		$content = m('common')->html_images($_GPC['content']);
		$content = htmlspecialchars($content);
		$product_advs_type = intval($_GPC['product_advs_type']);
		$product_advs_title = $_GPC['product_advs_title'];
		$product_advs_more = $_GPC['product_advs_more'];
		$product_advs_link = $_GPC['product_advs_link'];
		$product_advs = htmlspecialchars_decode($_GPC['product_advs']);
		$product_advs = json_decode($product_advs, true);
		foreach ($product_advs as $i => $v) {
			$product_advs[$i]['img'] = save_media($v['img']);
		}
		$product_advs = json_encode($product_advs);
		$product_advs = htmlspecialchars($product_advs);
		if (is_array($data)) {
			$arr = array();
			foreach ($data as $d) {
				$arr[$d['name']] = $d['value'];
			}
			// print_r($arr);exit;
			if (!empty($arr['id'])) {
				$articlekeyword = pdo_fetchcolumn("SELECT article_keyword FROM " . tablename('sz_yi_article') . " WHERE id=:aid and uniacid=:uniacid limit 1 ", array(':aid' => $arr['id'], ':uniacid' => $_W['uniacid']));
				if ($arr['article_keyword'] != $articlekeyword) {
					$keyword = pdo_fetch("SELECT * FROM " . tablename('rule_keyword') . " WHERE content=:content and uniacid=:uniacid limit 1 ", array(':content' => $arr['article_keyword'], ':uniacid' => $_W['uniacid']));
					if (!empty($keyword)) {
						die(json_encode(array('result' => 'error-key', 'desc' => '关键字已存在!')));
					}
				}
			} else {
				$keyword = pdo_fetch("SELECT * FROM " . tablename('rule_keyword') . " WHERE content=:content and uniacid=:uniacid limit 1 ", array(':content' => $arr['article_keyword'], ':uniacid' => $_W['uniacid']));
				if (!empty($keyword)) {
					die(json_encode(array('result' => 'error-key', 'desc' => '关键字已存在!')));
				}
			}
			$arr['page_set_option_nocopy'] = empty($arr['page_set_option_nocopy']) ? '0' : $arr['page_set_option_nocopy'];
			$arr['page_set_option_noshare_tl'] = empty($arr['page_set_option_noshare_tl']) ? '0' : $arr['page_set_option_noshare_tl'];
			$arr['page_set_option_noshare_msg'] = empty($arr['page_set_option_noshare_msg']) ? '0' : $arr['page_set_option_noshare_msg'];
			$arr['uniacid'] = $_W['uniacid'];
			$arr['article_content'] = $content;
			$arr['product_advs_type'] = $product_advs_type;
			$arr['product_advs_title'] = $product_advs_title;
			$arr['product_advs_more'] = $product_advs_more;
			$arr['product_advs_link'] = $product_advs_link;
			$arr['product_advs'] = $product_advs;
			$arr['resp_img'] = save_media($arr['resp_img']);
			if (empty($arr['id'])) {
				$arr['article_date'] = date('Y-m-d H:i:s');
				ca('paihang.page.add');
				pdo_insert('sz_yi_article', $arr);
				$aid = pdo_insertid();
				$desc = 'insert';
			} else {
				ca('paihang.page.edit');
				pdo_update('sz_yi_article', $arr, array('id' => $arr['id']));
				$aid = $arr['id'];
				$desc = 'update';
			}
			$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name  limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'sz_yi', ':name' => "sz_yi:article:" . $arr['id']));
			if (empty($rule)) {
				$rule_data = array('uniacid' => $_W['uniacid'], 'name' => 'sz_yi:article:' . $arr['id'], 'module' => 'sz_yi', 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule', $rule_data);
				$rid = pdo_insertid();
				$keyword_data = array('uniacid' => $_W['uniacid'], 'rid' => $rid, 'module' => 'sz_yi', 'content' => trim($arr['article_keyword']), 'type' => 1, 'displayorder' => 0, 'status' => 1);
				pdo_insert('rule_keyword', $keyword_data);
			} else {
				pdo_update('rule_keyword', array('content' => trim($arr['article_keyword'])), array('rid' => $rule['id']));
			}
			die(json_encode(array('result' => 'success', 'id' => $aid, 'desc' => $desc)));
		} else {
			die(json_encode(array('result' => 'error', 'desc' => '参数错误(not is array)')));
		}
	} elseif ($apido == 'selectgoods') {
		$kw = $_GPC['kw'];
		$goods = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb,hasoption FROM " . tablename('sz_yi_goods') . " WHERE uniacid= :uniacid and status=1 and deleted=0 AND title LIKE :title ", array(':title' => "%{$kw}%", ':uniacid' => $_W['uniacid']));
		$goods = set_medias($goods, 'thumb');
		die(json_encode($goods));
	} elseif ($apido == 'selectarticles') {
		$keyword = $_GPC['keyword'];
		$category = $_GPC['category'];
		$where = '';
		if (!empty($category)) {
			$where = "and a.article_category=" . $category;
		}
		$articles = pdo_fetchall("SELECT a.id,a.article_title,a.article_category,c.category_name FROM " . tablename('sz_yi_article') . " a left join " . tablename('sz_yi_article_category') . " c on c.id=a.article_category WHERE a.uniacid= :uniacid " . $where . " and a.article_title LIKE :title ", array(':title' => "%{$keyword}%", ':uniacid' => $_W['uniacid']));
		die(json_encode($articles));
	} elseif ($apido == 'savesys') {
		ca('paihang.page.otherset');
		$phstatus = $_GPC['phstatus'];
		$p_name = $_GPC['p_name'];
		$jifen = $_GPC['jifen'];
		$j_name = $_GPC['j_name'];
		$xiaofei = $_GPC['xiaofei'];
		$x_name = $_GPC['x_name'];
		$yongjin = $_GPC['yongjin'];
		$y_name = $_GPC['y_name'];
		$status = 0;

		
		$arr = array('phstatus' => $phstatus,'jifen' => $jifen, 'xiaofei' => $xiaofei,'yongjin' => $yongjin,'status' => $status);
		if(!empty($p_name)){
			$arr['p_name'] = $p_name;
		}
		if(!empty($x_name)){
			$arr['x_name'] = $x_name;
		}
		if(!empty($j_name)){
			$arr['j_name'] = $j_name;
		}
		if(!empty($y_name)){
			$arr['y_name'] = $y_name;
		}

		if (!empty($arr)) {
			$sys = pdo_fetch("select * from " . tablename('paihang') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
			if (empty($sys)) {
				$arr['uniacid'] = $_W['uniacid'];
				pdo_insert('paihang', $arr);
			} else {
				pdo_update('paihang', $arr, array('uniacid' => $_W['uniacid']));
			}
			die(json_encode(array('result' => 'success')));
		}
	} elseif ($apido == 'sta') {
		ca('paihang.page.otherset');
		$status = 1;
		$arr = array('status' => $status);
		if (!empty($arr)) {
			$sys = pdo_fetch("select * from " . tablename('paihang') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
			if (empty($sys)) {
				$arr['uniacid'] = $_W['uniacid'];
				pdo_insert('paihang', $arr);
			} else {
				pdo_update('paihang', $arr, array('uniacid' => $_W['uniacid']));
			}
			die(json_encode(array('result' => 'success')));
		}

	}
}
