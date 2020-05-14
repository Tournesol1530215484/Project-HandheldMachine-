<?php /*a:1:{s:67:"E:\phpstudy\PHPTutorial\WWW\Tp6Public\view\admin\admin\Roledit.html";i:1589353727;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/xadmin.css">
    <script type="text/javascript" src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form action="" method="post" class="layui-form layui-form-pane" >
<!--                <input name="action" value="editruleinfo" hidden>-->
                <input name="id" value="<?php echo htmlentities($RoleInfo['id']); ?>" hidden>

                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>角色名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" value="<?php echo htmlentities($RoleInfo['title']); ?>" required="" lay-verify="required"
                        autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        拥有权限
                    </label>
                    <table  class="layui-table layui-input-block">
                        <tbody>
                            <?php if(is_array($Rule) || $Rule instanceof \think\Collection || $Rule instanceof \think\Paginator): $i = 0; $__LIST__ = $Rule;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$Ruleres): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="<?php echo htmlentities($Ruleres['catename']); ?>" value="0"  lay-skin="primary" lay-filter="father" title="<?php echo htmlentities($Ruleres['catename']); ?>">
                                </td>
                                <td>
                                    <div class="layui-input-block">
                                        <?php if(is_array($Ruleres['child']) || $Ruleres['child'] instanceof \think\Collection || $Ruleres['child'] instanceof \think\Paginator): $i = 0; $__LIST__ = $Ruleres['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$Rulereschild): $mod = ($i % 2 );++$i;?>
                                        <input name="ids"  value=<?php echo htmlentities($Rulereschild['id']); ?> lay-skin="primary" <?php if((in_array( $Rulereschild['id'],explode(',',$RoleInfo['rules'])))): ?> checked="checked" <?php endif; ?> type="checkbox" title="<?php echo htmlentities($Rulereschild['title']); ?>" value="2">
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="desc" class="layui-form-label">
                        描述
                    </label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="desc" name="desc" class="layui-textarea"><?php echo htmlentities($RoleInfo['desc']); ?> </textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">修改</button>
              </div>
            </form>
        </div>
    </div>
    <script>
        layui.use(['form','layer'], function(){
            $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
        
          //自定义验证规则
          form.verify({
            nikename: function(value){
              if(value.length < 5){
                return '昵称至少得5个字符啊';
              }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
          });

          //监听提交
          form.on('submit(add)', function(data){
           // console.log(data);
              var id = [];
              // 获取选中的id
              $('tbody input').each(function(index, el) {
                  if($(this).prop('checked')){
                      id.push($(this).val())
                  }
              });
              console.log(id)
              var res=data.field;
              res['rid']=id
            //发异步，把数据提交给php
              var dates=res;
              console.log(dates)
              //王彬开始添加数据
              $.ajax({
                  url:"<?php echo url('admin/Adminrole'); ?>",
                  data:{'data':dates,'action':'editruleinfo'},
                  type:"get",
                  async:true,
                  dataType:"json",

                  success:function(data){

                      if(data.msg=='ok'){
                          layer.msg('添加成功!',{icon: 2,time:1000});
                      }else{
                          layer.msg('添加失败!',{icon: 3,time:1000});
                      }

                  },
                  error:function(data){
                      console.log(22211111111111)
                      console.log(data)
                      layer.msg(data.msg,{icon: 3,time:1000});
                  }
              });
              //王彬修改结束
            // layer.alert("增加成功", {icon: 6},function () {
            //     // 获得frame索引
            //     var index = parent.layer.getFrameIndex(window.name);
            //     //关闭当前frame
            //     parent.layer.close(index);
            // });

            return false;
          });


        form.on('checkbox(father)', function(data){

            if(data.elem.checked){
                $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                form.render(); 
            }else{
               $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                form.render();  
            }
        });
          
          
        });
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>

</html>