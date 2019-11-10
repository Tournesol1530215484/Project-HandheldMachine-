<?php
namespace app\admin\controller;
use app\admin\controller\Coom;
use Catetree\Catetree;
use think\Db;
use think\paginator\driver\Bootstrap;

class Article extends Coom{

	//文章列表
	public function Articlelist(){
		$AllConf=$this->getadminConf();
		$pageSize=$AllConf['page'];
	    $currPage=input('page',1);
	    $list = Db::query("select a.* ,c.cat_name from wb_article a left join wb_article_cat c on a.cat_id=c.cat_id limit ?,?",[($currPage-1)*$pageSize,$pageSize]); 
		$total = Db::query("select count(*) cat_id from wb_article")[0]['cat_id'];
		$Article = Bootstrap::make($list,$pageSize,$currPage,$total,false,['path'=>Bootstrap::getCurrentPath(),'query'=>request()->param()]);
		$page = $Article->render();

		$this->assign([
          'Article'=>$Article,
          'page'=>$page,
        ]);
		return view('Articlelist');

	}

	//点击筛选文章信息
	public function ArticleSelect(){
		$AllConf=$this->getadminConf();	//获取配置信息
		$pageSize=$AllConf['page'];//获取配置项的页码
		//接收参数信息
	    $currPage=input('page',1);
	    $article_id=input('article_id',1);
	    $title=input('title',1);
	    $is_open=input('is_open',1);
	    $opentime=input('opentime',1);
	    $startTime=input('startTime',1);
	    $endTime=input('endTime',1);
	    $sql='';
	    //筛选id 
	    if(!empty($article_id)){
	    	$sql.=" and a.article_id = ".$article_id;
	    }
	    //筛选标题
	    if(!empty($title)){
	    	$sql.=" and a.title like '%".$title."%'";
	    }
	    //筛选显示
	    if(strlen($is_open)){
	    	$sql.=" and a.is_open = ".$is_open;
	    }

	    //判断是都开启时间查询
	    if ($opentime=='on') {
	    	if(!empty($startTime)){
		    	$startTime=strtotime(trim($startTime));	
		    	$sql.=' and a.publish_time > '.$startTime;
		    }
		    if(!empty($endTime)){
		    	$endTime=strtotime(trim($endTime));
		    	$sql.=' and a.publish_time < '.$endTime;
		    }
		}

	    $list = Db::query("select a.* ,c.cat_name from wb_article a left join wb_article_cat c on a.cat_id=c.cat_id where 1 ".$sql."  limit ?,?",[($currPage-1)*$pageSize,$pageSize]);
		    
		$total = Db::query("select count(*) cat_id from wb_article ")[0]['cat_id'];
		    
		$Article = Bootstrap::make($list,$pageSize,$currPage,$total,false,['path'=>Bootstrap::getCurrentPath(),'query'=>request()->param()]);	//渲染到数据

		$page = $Article->render();	//这个一定要在items()前面

		$Article =$Article ->items();	//为了重构数组
		foreach ($Article as $key => $value) {
				$Article[$key]['publish_time']=date("Y-m-d",$value['publish_time']);
		}
		$this->assign([
          'Article'=>$Article,
          'page'=>$page,
        ]);
		return view('Articlelist');
	}



	//点击修改文章信息
	public function editArticleinfo(){
		if (request()->isPost()) {
			$data=input('post.');
			if ($data['fun']=='editArticlestatus') {	//点击修改是否显示
				$data['statu']=$data['statu']==1?0:1;
                $sql="update wb_article set is_open = ".$data['statu']. " where article_id =" .$data['atid'];
                $Info = Db::query($sql);
    			if($Info!==false){
                    $this->ajax_return(1,"success",$data['statu']);
                }else{
                     $this->ajax_return(0,"error",$data['statu']);
                }
			}elseif($data['fun']=='editArticleclick'){	//点击修改点击率

				$sql="update wb_article set click = ".$data['num']. " where article_id =" .$data['atid'];
                $Info = Db::query($sql);
    			if($Info!==false){
                    $this->ajax_return(1,"success",$data['num']);
                }else{
                     $this->ajax_return(0,"error",$data['num']);
                }
			}elseif($data['fun']=='editArticlescort'){	//点击修改排序
				$sql="update wb_article set scort = ".$data['num']. " where article_id =" .$data['atid'];
                $Info = Db::query($sql);
    			if($Info!==false){
                    $this->ajax_return(1,"success",$data['num']);
                }else{
                     $this->ajax_return(0,"error",$data['num']);
                }
			}


		}
	}

	//文章详情
	public function screenArticleinfo(){
		if (request()->isPost()) {
			$data=input('post.');
			$items= db('article')->alias('a')->field('a.*,c.cat_name')->join('article_cat c','a.cat_id=c.cat_id')->paginate( $AllConf['page']);
			$Article = $items->items();
			foreach ($Article as $key => $value) {
				$Article[$key]['article_type']=$Article[$key]['article_type']==1?'置顶':'普通';
				$Article[$key]['add_time']=date('Y-m-d H:i:s', $Article[$key]['add_time']);
				$Article[$key]['publish_time']=date("Y-m-d H:i:s",$Article[$key]['publish_time']);
			}
	 		$this->assign([
	          'Article'=>$Article,
	          'items'=>$items
	        ]);

	        return view("admin/Article/Articlelist");

		}
	}

	//文章数据导出
    public function downExcel2() {
    
                $strTable = '<table width="500" border="1">';
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;width:120px;">文章id</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="100">文章标题</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="100">文章分类</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">发布时间</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">点击率</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">排序</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;" width="*">是否显示</td>';
                $strTable .= '</tr>';
                // $user_ids = I('user_ids');
                // if ($user_ids) {
                //     $condition['user_id'] = ['in', $user_ids];
                // } else {
                //     $mobile = I('mobile');
                //     $email = I('email');
                //     $mobile ? $condition['mobile'] = $mobile : false;
                //     $email ? $condition['email'] = $email : false;
                // };
                $count = DB::name('article')->count();
                $p = ceil($count / 5000);
                for ($i = 0; $i < $p; $i++) {
                    $start = $i * 5000;
                    $end = ($i + 1) * 5000;
                    $userList = DB::name('article')
                    			->alias('a')
                    			->field('a.*,c.cat_name')
                    			->join('article_cat c a.cat_id=c.cat_id')
                    			->order('article_id')->limit($start,5000)->select();
                    if (is_array($userList)) {
                        foreach ($userList as $k => $val) {
                            if ($val['is_open']==1) {
                                $val['is_open']="显示";
                            }else{
                                 $val['is_open']="未显示";
                            }
                            $val['publish_time']=date("Y-m-d",$val['publish_time']);

                            $strTable .= '<tr>';
                            $strTable .= '<td style="text-align:center;font-size:12px;">' . $val['article_id'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['title'] . ' </td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['cat_name'] . ' </td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['publish_time'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['click'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['scort'] . '</td>';
                            $strTable .= '<td style="text-align:left;font-size:12px;">' . $val['is_open'] . '</td>';
                            // $strTable .= '<td style="text-align:left;font-size:12px;">' . date('Y-m-d H:i', $val['reg_time']) . '</td>';
                           
                        }
                        unset($userList);
                    }
                }
                $strTable .= '</table>';
                $this->downloadExcel($strTable, '用户信息导出表' . $i);
                exit();
            
    }

    public function downExcel(){
    	//数据导出
    	$title=array('文章id','标题','是否显示','发布时间','所属栏目');
	    $count = DB::name('article')->count();
	    $userList=db::query('select a.article_id,a.title,a.is_open,publish_time ,c.cat_name from wb_article a left join wb_article_cat c on a.cat_id=c.cat_id');
            if (is_array($userList)) {
                foreach ($userList as $k => $val) {
                    if ($val['is_open']==1) {
                        $userList[$k]['is_open']="显示";
                    }else{
                         $userList[$k]['is_open']="未显示";
                    }
                    $userList[$k]['publish_time']=date("Y-m-d",$val['publish_time']);
                }
            }
        $data=$userList;
	    $savePath='';
	    $isDown=true;
	    $fileName="数据导出测试";
    	$this->exportExcel($title, $data, $fileName, $savePath, $isDown);
    }







	//文章分类
	public function articlecat(){
		 $cate=new Catetree();
        $date=db('article_cat')->select();
        $Cate=$cate->ChildTree2($date);
        $this->assign('Cate',$Cate);
        return view('Article/CateList');
	}

}