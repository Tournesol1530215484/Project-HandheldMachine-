<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
  <?php if(cv('commission.agent')) { ?>
  <li <?php  if($_GPC['method']=='agent') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/agent')?>">分销商管理</a></li>
  <?php  } ?>
</ul>
<style>
    .sx{margin-bottom: 15px;}
    .form-horizontal .form-group{margin-bottom: 0px;}
    @media (min-width:1190px)and (max-width:1420px){
        .col-lg-2, .col-lg-3,.col-lg-6{
            padding-right:  0px;
        }
        .form-horizontal .form-group{margin-left:-30px;}
    }
</style>
<?php  if($operation=='display') { ?>
<div class="panel panel-info">
  <div class="panel-heading">筛选</div>
  <div class="panel-body">
    <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
      <input type="hidden" name="c" value="site" />
      <input type="hidden" name="a" value="entry" />
      <input type="hidden" name="m" value="sz_yi" />
      <input type="hidden" name="do" value="plugin" />
      <input type="hidden" name="p" value="commission" />
      <input type="hidden" name="method" value="agent" />
      <input type="hidden" name="op" value="display" />
      <div class="form-group">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 sx">
          <div class='input-group'>
            <div class='input-group-addon'>成为分销商时间
              <label class='radio-inline' style='margin-top:-7px;'>
                <input type='radio' value='0' name='searchtime' <?php  if($_GPC['searchtime']=='0') { ?>checked<?php  } ?> checked>
                不搜索</label>
              <label class='radio-inline' style='margin-top:-7px;'>
                <input type='radio' value='1' name='searchtime' <?php  if($_GPC['searchtime']=='1') { ?>checked<?php  } ?>>
                搜索</label>
            </div>
            <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d  H:i', $endtime)),true);?> </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 sx">
          <div class='input-group'>
            <div class='input-group-addon'>ID</div>
            <input type="text" class="form-control"  name="mid" value="<?php  echo $_GPC['mid'];?>"/>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 sx">
          <div class='input-group'>
            <div class='input-group-addon'>会员信息</div>
            <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder='可搜索昵称/名称/手机号'/>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 sx">
          <div class='input-group'>
            <div class='input-group-addon'>是否关注</div>
            <select name='followed' class='form-control'>
              <option value=''></option>
              <option value='0' <?php  if($_GPC['followed']=='0') { ?>selected<?php  } ?>>未关注</option>
              <option value='1' <?php  if($_GPC['followed']=='1') { ?>selected<?php  } ?>>已关注</option>
              <option value='2' <?php  if($_GPC['followed']=='2') { ?>selected<?php  } ?>>取消关注</option>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 sx">
          <div class='input-group'>
            <div class='input-group-addon'>分销商等级</div>
            <select name='agentlevel' class='form-control'>
              <option value=''></option>
              
                            <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                            
              <option value='<?php  echo $level['id'];?>' <?php  if($_GPC['agentlevel']==$level['id']) { ?>selected<?php  } ?>>
              <?php  echo $level['levelname'];?>
              </option>
              
                            <?php  } } ?>
                        
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 sx">
          <div class='input-group'>
            <div class='input-group-addon'>状态</div>
            <select name='status' class='form-control'>
              <option value=''>审核状态</option>
              <option value='0' <?php  if($_GPC['status']=='0') { ?>selected<?php  } ?>>未审核</option>
              <option value='1' <?php  if($_GPC['status']=='1') { ?>selected<?php  } ?>>已审核</option>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 sx">
          <div class='input-group'>
            <select name='agentblack' class='form-control'>
              <option value=''>黑名单状态</option>
              <option value='0' <?php  if($_GPC['agentblack']=='0') { ?>selected<?php  } ?>>否</option>
              <option value='1' <?php  if($_GPC['agentblack']=='1') { ?>selected<?php  } ?>>是</option>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 sx">
          <div class='input-group'>
            <div class='input-group-addon'>推荐人</div>
            <select name='parentid' class='form-control'>
              <option value=''></option>
              <option value='0' <?php  if($_GPC['parentid']=='0') { ?>selected<?php  } ?>>总店</option>
            </select>
            <div class='input-group-addon' style="width: 0px;padding: 0px; margin: 0px; border: 0px;"></div>
            <input type="text"  class="form-control" name="parentname" value="<?php  echo $_GPC['parentname'];?>" placeholder='推荐人昵称/姓名/手机号'/>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 sx">
          <div class='input-group'>
            <button class="btn btn-default"><i class="fa fa-search"></i>搜索</button>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <?php if(cv('commission.agent.export')) { ?>
            <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button>
            <?php  } ?> </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">总数：<?php  echo $total;?></div>
  <style>
        @media (max-width: 767px){
            .table-responsive>.table{width:auto;}
        }
    </style>
  <div class=" table-responsive">
    <table class="table table-hover table-bordered">
      <thead class="navbar-inner" >
        <tr>
          <th style='width:5%;'>会员ID</th>
          <th style=''>推荐人</th>
          <th style=''>粉丝</th>
          <th style=''>姓名<br/>
            手机号码</th>
          <th style=''>分销等级</th>
          <th style=''>点击数</th>
          <th style=''>累计佣金<br/>
            打款佣金</th>
          <th style=''>下级分销商</th>
          <th style=''>状态<?php if(cv('commission.agent.check')) { ?><br/>
            （点击审核)<?php  } ?></th>
          <th style='width: 13%;'>时间</th>
          <th style=''>关注</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      
      <?php  if(is_array($list)) { foreach($list as $row) { ?>
      <tr>
        <td><?php  echo $row['id'];?></td>
          <td  <?php  if(!empty($row['agentid'])) { ?>title='ID: <?php  echo $row['agentid'];?>'<?php  } ?>>
        <?php  if(empty($row['agentid'])) { ?>
        <?php  if($row['isagent']==1) { ?>
        <label class='label label-primary'>总店</label>
        <?php  } else { ?>
        <label class='label label-default'>暂无</label>
        <?php  } ?>
        <?php  } else { ?> <img src='<?php  echo $row['parentavatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' /> <?php  echo $row['parentname'];?>
        <?php  } ?>
          </td>
        <td> <?php  if(!empty($row['avatar'])) { ?> <a style="color: #376fd5;" href="<?php  echo $this->createWebUrl('member',array('op'=>'detail', 'id' => $row['id']));?>" title='会员信息' style="color: #376fd5;"> <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' /> <?php  } ?>
          <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?> </a></td>
        <td><?php  echo $row['realname'];?> <br/>
          <?php  echo $row['mobile'];?></td>
        <td><?php  if(empty($row['levelname'])) { ?> <?php echo empty($this->set['levelname'])?'普通等级':$this->set['levelname']?><?php  } else { ?><?php  echo $row['levelname'];?><?php  } ?></td>
        <td><?php  echo $row['clickcount'];?></td>
        <td><?php  echo $row['commission_total'];?><br/>
          <?php  echo $row['commission_pay'];?></td>
        <td> 总计：<?php  echo $row['levelcount'];?> 人
          <?php  if($level>=1 && $row['level1']>0) { ?><br/>
          一级：<?php  echo $row['level1'];?> 人<?php  } ?>
          <?php  if($level>=2  && $row['level2']>0) { ?><br/>
          二级：<?php  echo $row['level2'];?> 人<?php  } ?>
          <?php  if($level>=3  && $row['level3']>0) { ?><br/>
          三级：<?php  echo $row['level3'];?> 人<?php  } ?></td>
        <td> <?php  if($row['status']==0) { ?>
          <?php  if($row['agentblack']==1) { ?> <span class="label label-default" style='color:#fff;background:black'>黑名单</span> <?php  } else { ?>
          
          <?php if(cv('commission.agent.check')) { ?> <a class="label label-default" href="<?php  echo $this->createPluginWebUrl('commission/agent',array('id' => $row['id'],'op'=>'check'))?>" onclick="return confirm('确认要审核此分销商吗?')">未审核</a> <?php  } else { ?> <span class="label label-default">未审核</span> <?php  } ?>
          <?php  } ?>
          <?php  } else { ?> <span class="label label-success">已审核</span> <?php  } ?> </td>
        <td>注册时间：<?php  echo date('Y-m-d H:i',$row['createtime'])?><br/>
          代理时间：<?php  if(!empty($row['agenttime'])) { ?><?php  echo date('Y-m-d H:i',$row['agenttime'])?><?php  } ?> </td>
        <td> <?php  if(empty($row['followed'])) { ?>
          <?php  if(empty($row['uid'])) { ?>
          <label class='label label-default'>未关注</label>
          <?php  } else { ?>
          <label class='label label-warning'>取消关注</label>
          <?php  } ?>
          <?php  } else { ?>
          <label class='label label-success'>已关注</label>
          <?php  } ?></td>
        <td  style="overflow:visible;"><div class="btn-group btn-group-sm"> <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
              <?php if(cv('member.member.view')) { ?>
              <li><a href="<?php  echo $this->createWebUrl('member',array('op'=>'detail', 'id' => $row['id']));?>" title='会员信息'><i class='fa fa-user'></i> 会员信息</a></li>
              <?php  } ?>
              <?php if(cv('commission.agent.view')) { ?>
              <li><a href="<?php  echo $this->createPluginWebUrl('commission/agent/detail',array('id' => $row['id']));?>" title='详细信息'><i class='fa fa-edit'></i> 详细信息</a> </li>
              <?php  } ?>
              <?php if(cv('commission.agent.order')) { ?>
              <li><a  href="<?php  echo $this->createWebUrl('order',array('op'=>'display','agentid' => $row['id']));?>" title='推广订单'><i class='fa fa-list'></i> 推广订单</a></li>
              <?php  } ?>
              <?php if(cv('commission.agent.user')) { ?>
              <li><a  href="<?php  echo $this->createPluginWebUrl('commission/agent/user',array('id' => $row['id']));?>"  title='推广下线'><i class='fa fa-users'></i> 推广下线</a></li>
              <?php  } ?>
              <?php if(cv('commission.agent.agentblack')) { ?>
              <?php  if($row['agentblack']==1) { ?>
              <li><a href="<?php  echo $this->createPluginWebUrl('commission/agent/agentblack',array('id' => $row['id'],'black'=>0));?>" title='取消黑名单'><i class='fa fa-minus-square'></i> 取消黑名单</a></li>
              <?php  } else { ?>
              <li><a href="<?php  echo $this->createPluginWebUrl('commission/agent/agentblack',array('id' => $row['id'],'black'=>1));?>" title='设置黑名单'><i class='fa fa-minus-circle'></i> 设置黑名单</a></li>
              <?php  } ?>
              <?php  } ?>
              <?php if(cv('commission.agent.delete')) { ?>
              <li><a href="<?php  echo $this->createPluginWebUrl('commission/agent/delete',array('id' => $row['id']));?>" title="删除" onclick="return confirm('确定要删除该会员吗？');"><i class='fa fa-remove'></i> &nbsp;删除分销商</a></li>
              <?php  } ?>
            </ul>
          </div></td>
      </tr>
      <?php  } } ?>
        </tbody>
      
    </table>
    <?php  echo $pager;?> </div>
</div>
<?php  } else if($operation=='detail') { ?>
<form <?php if(cv('commission.agent.edit|commission.agent.check')) { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
  <input type="hidden" name="id" value="<?php  echo $member['id'];?>">
  <input type="hidden" name="op" value="detail">
  <input type="hidden" name="c" value="site" />
  <input type="hidden" name="a" value="entry" />
  <input type="hidden" name="m" value="sz_yi" />
  <input type="hidden" name="p" value="commission" />
  <input type="hidden" name="method" value="agent" />
  <input type="hidden" name="op" value="detail" />
  <div class='panel panel-default'>
    <div class='panel-heading'> 分销商详细信息 </div>
    <div class=''>
      <style>
            .mtable {
                table-layout: fixed;
            }
            .mtable {
                width: 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }
            .panel .mtable td, .panel .mtable th {
                padding: 6px 15px;
            }
        </style>
      <table class="mtable table-bordered" style="text-align: center;">
        <tbody>
          <tr style="background: #1E95C9;color:#fff;">
            <td style="width:10%;"><label class="control-label">粉丝</label></td>
            <td style="width:10%;"><label class="control-label">OPENID</label></td>
            <td style="width:10%;"><label class="control-label">真实姓名</label></td>
            <td style="width:12%;"><label class="control-label">手机号码</label></td>
            <td style="width:15%;"><label class="control-label">微信号</label></td>
            <td><label class="control-label">分销商等级</label></td>
            <td><label class="control-label">累计佣金</label></td>
            <td><label class="control-label">已打款佣金</label></td>
          </tr>
          <tr>
            <td><div class=""> <img src='<?php  echo $member['avatar'];?>' style='width:90%;padding:1px;border:1px solid #ccc' />
                <p><?php  echo $member['nickname'];?></p>
              </div></td>
            <td style=" word-wrap:break-word;text-align: left;"><div class="">
                <div class="form-control-static"><?php  echo $member['openid'];?></div>
              </div></td>
            <td><div class=""> <?php if(cv('commission.agent.edit')) { ?>
                <input type="text" name="data[realname]" class="form-control" value="<?php  echo $member['realname'];?>"  />
                <?php  } else { ?>
                <input type="hidden" name="data[realname]" class="form-control" value="<?php  echo $member['realname'];?>"  />
                <div class='form-control-static'><?php  echo $member['realname'];?></div>
                <?php  } ?> </div></td>
            <td><div class=""> <?php if(cv('commission.agent.edit')) { ?>
                <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $member['mobile'];?>"  />
                <?php  } else { ?>
                <input type="hidden" name="data[mobile]" class="form-control" value="<?php  echo $member['mobile'];?>"  />
                <div class='form-control-static'><?php  echo $member['mobile'];?></div>
                <?php  } ?> </div></td>
            <td><div class=""> <?php if(cv('commission.agent.edit')) { ?>
                <input type="text" name="data[weixin]" class="form-control" value="<?php  echo $member['weixin'];?>"  />
                <?php  } else { ?>
                <input type="hidden" name="data[weixin]" class="form-control" value="<?php  echo $member['weixin'];?>"  />
                <div class='form-control-static'><?php  echo $member['weixin'];?></div>
                <?php  } ?> </div></td>
            <td><div class=""> <?php if(cv('commission.agent.edit')) { ?>
                <select name='data[agentlevel]' class='form-control'>
                  <option value='0'><?php echo empty($this->set['levelname'])?'普通等级':$this->set['levelname']?></option>
                  
                        <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                        
                  <option value='<?php  echo $level['id'];?>' <?php  if($member['agentlevel']==$level['id']) { ?>selected<?php  } ?>>
                  <?php  echo $level['levelname'];?>
                  </option>
                  
                        <?php  } } ?>
                    
                </select>
                <?php  } else { ?>
                <input type="hidden" name="data[agentlevel]" class="form-control" value="<?php  echo $member['agentlevel'];?>"  />
                <?php  if(empty($member['agentlevel'])) { ?>
                <?php echo empty($this->set['levelname'])?'普通等级':$this->set['levelname']?>
                <?php  } else { ?>
                <?php  echo pdo_fetchcolumn('select levelname from '.tablename('sz_yi_commission_level').' where id=:id limit 1',array(':id'=>$member['agentlevel']))?>
                <?php  } ?>
                <?php  } ?></div></td>
            <td><div class="">
                <div class='form-control-static'> <?php  echo $member['commission_total'];?></div>
              </div></td>
            <td><div class="">
                <div class='form-control-static'> <?php  echo $member['commission_pay'];?></div>
              </div></td>
          </tr>
          <tr style="background: #1E95C9;color:#fff;">
            <td style="width:10%;"><label class="control-label">注册时间</label></td>
            <td><label class="control-label">成为代理时间</label></td>
            <td><label class="control-label">分销商权限</label></td>
            <td><label class="control-label">审核通过</label></td>
            <td><label class="control-label">强制不自动升级</label></td>
            <td><label class="control-label">自选商品</label></td>
            <td><label class="control-label">黑名单</label></td>
            <td><label class="control-label">备注</label></td>
          </tr>
          <tr>
            <td><div class="">
                <div class='form-control-static'><?php  if(!strexists('1970',$member['agenttime'])) { ?><?php  echo $member['agenttime'];?><?php  } else { ?>----------<?php  } ?></div>
              </div></td>
            <td><div class=""> <?php if(cv('commission.agent.check')) { ?>
                <label class="radio-inline">
                  <input type="radio" name="data[isagent]" value="1" <?php  if($member['isagent']==1) { ?>checked<?php  } ?>>
                  是</label>
                <label class="radio-inline" >
                  <input type="radio" name="data[isagent]" value="0" <?php  if($member['isagent']==0) { ?>checked<?php  } ?>>
                  否</label>
                <?php  } else { ?>
                <input type='hidden' name='data[isagent]' value='<?php  echo $member['isagent'];?>' />
                <div class='form-control-static'><?php  if($member['isagent']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                <?php  } ?> </div></td>
            <td><div class=""> <?php if(cv('commission.agent.check')) { ?>
                <label class="radio-inline">
                  <input type="radio" name="data[status]" value="1" <?php  if($member['status']==1) { ?>checked<?php  } ?>>
                  是</label>
                <label class="radio-inline" >
                  <input type="radio" name="data[status]" value="0" <?php  if($member['status']==0) { ?>checked<?php  } ?>>
                  否</label>
                <input type='hidden' name='oldstatus' value="<?php  echo $member['status'];?>" />
                <?php  } else { ?>
                <input type='hidden' name='data[status]' value='<?php  echo $member['status'];?>' />
                <div class='form-control-static'><?php  if($member['status']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                <?php  } ?> </div></td>
            <td style=" word-wrap:break-word;text-align: left;"><div class=""> <?php if(cv('commission.agent.edit')) { ?>
                <label class="radio-inline" >
                  <input type="radio" name="data[agentnotupgrade]" value="0" <?php  if($member['agentnotupgrade']==0) { ?>checked<?php  } ?>>
                  允许自动升级</label>
                <label class="radio-inline">
                  <input type="radio" name="data[agentnotupgrade]" value="1" <?php  if($member['agentnotupgrade']==1) { ?>checked<?php  } ?>>
                  强制不自动升级</label>
                <span class="help-block">如果强制不自动升级，满足任何条件，此分销商的级别也不会改变</span> <?php  } else { ?>
                <input type="hidden" name="data[agentnotupgrade]" class="form-control" value="<?php  echo $member['agentnotupgrade'];?>"  />
                <div class='form-control-static'><?php  if($member['agentnotupgrade']==1) { ?>强制不自动升级<?php  } else { ?>允许自动升级<?php  } ?></div>
                <?php  } ?> </div></td>
            <td style=" word-wrap:break-word;text-align: left;"><div class="" style="width: 100%; word-wrap:break-word;"> <?php if(cv('commission.agent.edit')) { ?>
                <label class="radio-inline" >
                  <input type="radio" name="data[agentselectgoods]" value="0" <?php  if($member['agentselectgoods']==0) { ?>checked<?php  } ?>>
                  系统设置</label>
                <label class="radio-inline">
                  <input type="radio" name="data[agentselectgoods]" value="1" <?php  if($member['agentselectgoods']==1) { ?>checked<?php  } ?>>
                  强制禁止</label>
                <label class="radio-inline">
                  <input type="radio" name="data[agentselectgoods]" value="2" <?php  if($member['agentselectgoods']==2) { ?>checked<?php  } ?>>
                  强制开启</label>
                <span class="help-block" style="width: 100%; word-wrap:break-word;">系统设置： 跟随系统设置，系统关闭自选则为禁止，系统开启自选则为允许</span> <span class="help-block">强制禁止： 无论系统自选商品是否关闭或开启，此分销商永不能自选商品</span> <span class="help-block">强制允许： 无论系统自选商品是否关闭或开启，此分销商永可以自选商品</span> <?php  } else { ?>
                <input type="hidden" name="data[agentselectgoods]" class="form-control" value="<?php  echo $member['agentselectgoods'];?>"  />
                <div class='form-control-static'><?php  if($member['agentnotselectgoods']==1) { ?>
                  强制禁止
                  <?php  } else if($member['agentselectgoods']==2) { ?>
                  强制允许
                  <?php  } else { ?>
                  <?php  if($this->set['select_goods']==1) { ?>系统允许<?php  } else { ?>系统禁止<?php  } ?>
                  <?php  } ?></div>
                <?php  } ?> </div></td>
            <td><div class="">
                <input type='hidden' name='oldagentblack' value="<?php  echo $member['agentblack'];?>" />
                <?php if(cv('commission.agent.agentblack')) { ?>
                <label class="radio-inline">
                  <input type="radio" name="data[agentblack]" value="1" <?php  if($member['agentblack']==1) { ?>checked<?php  } ?>>
                  是</label>
                <label class="radio-inline" >
                  <input type="radio" name="data[agentblack]" value="0" <?php  if($member['agentblack']==0) { ?>checked<?php  } ?>>
                  否</label>
                <?php  } else { ?>
                <input type='hidden' name='data[agentblack]' value='<?php  echo $member['agentblack'];?>' />
                <div class='form-control-static'><?php  if($member['agentblack']==1) { ?>是<?php  } else { ?>否<?php  } ?></div>
                <?php  } ?> </div></td>
            <td> <?php if(cv('commission.agent.edit')) { ?>
              <textarea name="content" class='form-control'><?php  echo $member['content'];?></textarea>
              <?php  } else { ?>
              <textarea name="content" class='form-control' style='display:none'><?php  echo $member['content'];?></textarea>
              <div class='form-control-static'><?php  echo $member['content'];?></div>
              <?php  } ?> </td>
            <td><div class="">
                <div class='form-control-static'><?php  echo date('Y-m-d H:i:s', $member['createtime']);?></div>
              </div></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php  if($diyform_flag == 1) { ?>
    <div class='panel-heading'> 自定义表单信息 </div>
    <div class='panel-body'> 
      <!--<span>diyform</span>--> 
      
      <?php  $datas = iunserializer($member['diycommissiondata'])?>
      <?php  if(is_array($fields)) { foreach($fields as $key => $value) { ?>
      <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  echo $value['tp_name']?></label>
        <div class="col-sm-9 col-xs-12">
          <div class="form-control-static"> <?php  if($value['data_type'] == 0 || $value['data_type'] == 1 || $value['data_type'] == 2 || $value['data_type'] == 6) { ?>
            {php echo str_replace("\n","<br/>
            ",$datas[$key])}
            
            <?php  } else if($value['data_type'] == 3) { ?>
            <?php  if(!empty($datas[$key])) { ?>
            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
            <?php  echo $v1?>
            <?php  } } ?>
            <?php  } ?>
            
            <?php  } else if($value['data_type'] == 5) { ?>
            <?php  if(!empty($datas[$key])) { ?>
            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?> <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a> <?php  } } ?>
            <?php  } ?>
            
            <?php  } else if($value['data_type'] == 7) { ?>
            <?php  echo $datas[$key]?>
            
            <?php  } else if($value['data_type'] == 8) { ?>
            <?php  if(!empty($datas[$key])) { ?>
            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
            <?php  echo $v1?>
            <?php  } } ?>
            <?php  } ?>
            
            <?php  } else if($value['data_type'] == 9) { ?>
            <?php echo $datas[$key]['province']!='请选择省份'?$datas[$key]['province']:''?>-<?php echo $datas[$key]['city']!='请选择城市'?$datas[$key]['city']:''?>
            <?php  } ?> </div>
        </div>
      </div>
      <?php  } } ?> </div>
    <?php  } ?>
    <div class='panel-body'>
      <div class="form-group"></div>
      <div class="form-group row">
        <div class="col-xs-offset-2 col-xs-9 col-md-offset-3 col-md-9 col-lg-offset-4 col-lg-8"> <?php if(cv('commission.agent.edit|commission.agent.check')) { ?>
          <input type="submit" name="submit" value="提交" class="btn btn-primary"  style="width: 11%;"/>
          <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
          <?php  } ?>
          <input type="button" name="back" onclick='history.back()' <?php if(cv('commission.agent.edit|commission.agent.check')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
        </div>
      </div>
    </div>
  </div>
</form>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>