<?php defined('IN_IA') or exit('Access Denied');?><div class="table-responsive" style="padding:0px;">
    <table class="table">
        <thead>
            <tr>
                <th style='width:76px;'></th>
                <th>属性名称</th>
                <th>属性值</th>
            </tr>
        </thead>
        <tbody id="param-items">
            <?php  if(is_array($params)) { foreach($params as $p) { ?>
            <tr>
                <td>
                   <?php if( ce('shop.goods' ,$item) ) { ?>
                    <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序"><i class="fa fa-arrows"></i></a>&nbsp;
                    <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;" title="删除"><i class='fa fa-remove'></i></a>
                    <?php  } ?>
                </td>
                <td>
                      <?php if( ce('shop.goods' ,$item) ) { ?>
                    <input name="param_title[]" type="text" class="form-control param_title" value="<?php  echo $p['title'];?>"/>
                    <?php  } else { ?>
                      <?php  echo $p['title'];?>
                    <?php  } ?>
                    <input name="param_id[]" type="hidden" class="form-control" value="<?php  echo $p['id'];?>"/>
                </td>
                <td>
                       <?php if( ce('shop.goods' ,$item) ) { ?>
                    <input name="param_value[]" type="text" class="form-control param_value" value="<?php  echo $p['value'];?>"/>
                <?php  } else { ?>   <?php  echo $p['value'];?>
                    <?php  } ?>
                </td>
            </tr>
            <?php  } } ?>
        </tbody>
           <?php if( ce('shop.goods' ,$item) ) { ?>
        <tbody>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2">
                    <a href="javascript:;" id='add-param' onclick="addParam()" style="margin-top:10px;" class="btn btn-primary"  title="添加属性"><i class='fa fa-plus'></i> 添加属性</a>
                </td>
            </tr>
        </tbody>
        <?php  } ?>
    </table>
</div>

<script language="javascript">
    $(function() {
        $("#param-items").sortable({handle: '.fa-move'});
        $("#chkoption").click(function() {
            var obj = $(this);
            if (obj.get(0).checked) {
                $("#tboption").show();
                $(".trp").hide();
            }
            else {
                $("#tboption").hide();
                $(".trp").show();
            }
        });
    })
    function addParam() {
        var url = "<?php  echo $this->createWebUrl('shop/tpl',array('tpl'=>'param'))?>";
        $.ajax({
            "url": url,
            success: function(data) {
                $('#param-items').append(data);
            }
        });
        return;
    }
    function deleteParam(o) {
        $(o).parent().parent().remove();
    }
</script>