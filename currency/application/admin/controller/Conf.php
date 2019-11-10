<?php
namespace app\admin\controller;
use app\admin\model\Conf as ConfModel;
use think\Cache;

class Conf extends Coom{

    //清除缓存
    public function ClearCache(){
       $res=Cache::clear();
       //删除模板信息
       array_map('unlink', glob(TEMP_PATH . '/*.php'));
       rmdir(TEMP_PATH);
       if($res){
        $this->success('清除缓存成功');
       }else{
        $this->error('清除缓存失败');
       }
       
    }

    //配置项列表
    public function ConfList(){
       $Conf= db('conf')->paginate(10);
        $this->assign('Conf',$Conf);
        return view('Conf/ConfList');
    }

    //添加配置项
    public function  ConfAdd(){
        if(request()->isPost()){
            $date=input('post.');
            //对可选值的中英文进行逗号处理
            if(isset($date['values'])){
                $date['values'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$date['values']);
            }
            
         $res=db('conf')->insert($date);
            if($res){
                $this->success('添加数据库成功','ConfList');
            }else{
                $this->error('添加配置项失败');
            }
        }
        return view('Conf/ConfAdd');
    }

    //修改配置项
    public  function ConfEdit(){
        if(request()->isPost()){
            $data=input('post.');
            if(isset($date['values'])){
                $date['values'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$date['values']);
            }
            
            $res=db('conf')->update($data);
            if($res!==false){
                $this->success('修改配置成功','ConfList');
            }else{
                $this->error('修改配置失败');
            }
        }
        $id=input('id');
        $Conf=db('conf')->find($id);
        $this->assign('Conf',$Conf);
        return view('Conf/ConfEdit');
    }

    //删除配置项
    public function ConfDel(){
        $id=input('id');
        $res=db('conf')->delete($id);
        if($res){
            $this->success('删除配置成功','ConfList');
        }else{
            $this->error('删除配置失败');
        }
    }


    public  function Clist(){
        if(request()->isPost()){
            $data=input('post.');
            //操作复选框操作为空的情况
            $ckeckeds2D=db('conf')->field('ename')->where(array('from_type'=>'checked'))->select();//获取所有checked类似值
            $ckeckeds=array();//把二维数组进转换成一维数组
            if(isset($ckeckeds2D)){
                foreach($ckeckeds2D as $key => $value){
                    $ckeckeds[]=$value['ename'];
                }
            }
            //处理文字
            foreach($data as $key => $value){
                $alliedls[]=$key;
                if(is_array($value)){
                   $v= implode(',',$value);
                    db('conf')->where(array('ename'=>$key))->update(['value'=>$v]);
                }else{
                    db('conf')->where(array('ename'=>$key))->update(['value'=>$value]);
                }
            }
            foreach($ckeckeds as $k => $v){
                if(!in_array($v,$alliedls)){
                    db('conf')->where(array('ename'=>$v))->update(['value'=>'']);
                }
            }

            //处理多文件上传
            
        }
        $ShopConf= db('conf')->where('conf_type=1')->select();//店铺属性
        $GoodsConf= db('conf')->where('conf_type=2')->select();//店铺属性


        //对数据进行重组，把values和value进行数组重组
        foreach($ShopConf as $key=>$value){
            $ShopConf[$key]['values']=explode(',',$value['values']);
            //$ShopConf[$key]['value']=explode(',',$value['value']);
        }
        foreach($GoodsConf as $key=>$value){
            $GoodsConf[$key]['values']=explode(',',$value['values']);
        }
        $this->assign('ShopConf',$ShopConf);
        $this->assign('GoodsConf',$GoodsConf);
        return view('Conf/Clist');
    }
}
