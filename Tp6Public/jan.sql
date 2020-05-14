/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : jan

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-05-14 10:24:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for jan_feng_fe_sign_set
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_fe_sign_set`;
CREATE TABLE `jan_feng_fe_sign_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(60) DEFAULT NULL,
  `openid` varchar(60) DEFAULT NULL,
  `reward_default_first` varchar(60) DEFAULT NULL COMMENT '首次奖励',
  `reward_default_day` varchar(60) DEFAULT NULL COMMENT '日常奖励',
  `reword_order` varchar(60) DEFAULT NULL COMMENT '连签奖励规则',
  `reword_sum` varchar(60) DEFAULT NULL COMMENT '总签奖励规则',
  `isopen` tinyint(1) DEFAULT NULL COMMENT '是否开启签到',
  `reword_special` varchar(60) DEFAULT NULL COMMENT '特殊奖励规则',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT=' 签到设置表';

-- ----------------------------
-- Records of jan_feng_fe_sign_set
-- ----------------------------

-- ----------------------------
-- Table structure for jan_feng_ye_admin
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_admin`;
CREATE TABLE `jan_feng_ye_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) DEFAULT NULL,
  `psd` varchar(60) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `regTime` int(10) DEFAULT NULL,
  `regip` varchar(60) DEFAULT NULL,
  `loginTime` int(60) DEFAULT NULL,
  `loginip` varchar(60) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_admin
-- ----------------------------
INSERT INTO `jan_feng_ye_admin` VALUES ('6', 'admin', '123456', '1530215484@qq.com', '1589016121', null, null, null, '1');
INSERT INTO `jan_feng_ye_admin` VALUES ('7', '王彬', '123456', '1530215484@qq.com', '1589356738', null, null, null, '1');
INSERT INTO `jan_feng_ye_admin` VALUES ('8', '	王彬', '123456', '1530215484@qq.com', '1589356781', null, null, null, '0');

-- ----------------------------
-- Table structure for jan_feng_ye_author
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_author`;
CREATE TABLE `jan_feng_ye_author` (
  `id` int(10) NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` int(60) DEFAULT NULL,
  `authorid` int(10) DEFAULT NULL COMMENT '坐着id',
  `authorstatus` tinyint(1) DEFAULT NULL COMMENT '作者状态 1 正常 2 审核 0异常',
  `authortime` int(10) DEFAULT NULL COMMENT '成为作者时间',
  `authorblack` tinyint(1) DEFAULT '0' COMMENT '是否黑名单0否，1是',
  `authorlevel` tinyint(3) DEFAULT NULL COMMENT '作者等级',
  `authornotupgrade` tinyint(1) DEFAULT '0' COMMENT '作者不可升级 0可升级1 不可升级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='作者信息表';

-- ----------------------------
-- Records of jan_feng_ye_author
-- ----------------------------

-- ----------------------------
-- Table structure for jan_feng_ye_auth_cate
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_auth_cate`;
CREATE TABLE `jan_feng_ye_auth_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catename` varchar(60) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_auth_cate
-- ----------------------------
INSERT INTO `jan_feng_ye_auth_cate` VALUES ('1', '会员管理', '1');
INSERT INTO `jan_feng_ye_auth_cate` VALUES ('8', '管理员管理', '1');

-- ----------------------------
-- Table structure for jan_feng_ye_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_auth_group`;
CREATE TABLE `jan_feng_ye_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_auth_group
-- ----------------------------
INSERT INTO `jan_feng_ye_auth_group` VALUES ('4', '超级管理员', '1', '0,13,15,12,14,16,17', '超级管理员 ');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('5', '管理员', '1', '0,13,15', '一般管理员，一般权限 ');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('6', '小角色', '0', '13', '小角色     ');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('8', '测试', '0', '13,12,14', '测试');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('9', '测试2', '0', '13,12,14', '参数2');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('10', 'ces333', '0', '13,12,14', 'ces3');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('11', 'ces444', '0', '12,14', '4');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('12', '1', '0', '13,12,14', '22121');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('13', '2', '0', '12,14', '2');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('14', '1', '0', '12,14', '1');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('15', '122', '0', '12,14', '2121');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('16', '2121', '0', '12,14', '1212');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('17', '大大大', '0', '12,14', '打打');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('18', '1', '0', '12,14', '1111');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('19', '1111111111', '0', '12,14', '111111111');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('20', '打打', '0', '13,12,14', '打打');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('21', '大大大', '0', '13,12,14', '打打');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('22', '大5555555555', '0', '13,12,14', '大5555555555');
INSERT INTO `jan_feng_ye_auth_group` VALUES ('23', '哒哒哒哒哒哒多多多多多多多多', '0', '13,12,14', '哒哒哒哒哒哒多多多多多多多');

-- ----------------------------
-- Table structure for jan_feng_ye_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_auth_group_access`;
CREATE TABLE `jan_feng_ye_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_auth_group_access
-- ----------------------------
INSERT INTO `jan_feng_ye_auth_group_access` VALUES ('6', '4');
INSERT INTO `jan_feng_ye_auth_group_access` VALUES ('7', '5');
INSERT INTO `jan_feng_ye_auth_group_access` VALUES ('8', '6');

-- ----------------------------
-- Table structure for jan_feng_ye_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_auth_rule`;
CREATE TABLE `jan_feng_ye_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_auth_rule
-- ----------------------------
INSERT INTO `jan_feng_ye_auth_rule` VALUES ('12', 'admin/adminrule', '权限列表', '1', '1', '', '8');
INSERT INTO `jan_feng_ye_auth_rule` VALUES ('13', 'Index/Memberstatic', '会员列表', '1', '1', '', '1');
INSERT INTO `jan_feng_ye_auth_rule` VALUES ('14', 'Admin/Admincate', '权限分类', '1', '1', '', '8');
INSERT INTO `jan_feng_ye_auth_rule` VALUES ('15', 'Index/Statistics', '统计页面', '1', '1', '', '1');
INSERT INTO `jan_feng_ye_auth_rule` VALUES ('16', 'Admin/Adminlist', '管理员列表', '1', '1', '', '8');
INSERT INTO `jan_feng_ye_auth_rule` VALUES ('17', 'Admin/Adminrole', '角色管理', '1', '1', '', '8');

-- ----------------------------
-- Table structure for jan_feng_ye_member
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_member`;
CREATE TABLE `jan_feng_ye_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `unionid` varchar(60) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '对应mc_member的uid',
  `groupid` int(10) DEFAULT NULL COMMENT '用户组id',
  `level` tinyint(3) DEFAULT NULL COMMENT '用户级别 用户等级表里的 id 值',
  `agentid` int(10) DEFAULT NULL COMMENT '上级ID',
  `openid` varchar(60) DEFAULT NULL,
  `realname` varchar(60) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `pwd` varchar(60) DEFAULT NULL,
  `isblack` tinyint(1) DEFAULT NULL COMMENT '是否是黑名单0，不是1，是',
  `weixin` varchar(60) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0 没有审核 1 已经审核',
  `clickcount` int(11) DEFAULT NULL COMMENT '	分享链接点击次数',
  `nickname` varchar(60) DEFAULT NULL COMMENT '昵称',
  `credit1` int(11) DEFAULT NULL COMMENT '积分',
  `credit2` double(11,0) DEFAULT NULL COMMENT '余额',
  `birthyear` int(4) DEFAULT NULL,
  `birthmonth` int(2) DEFAULT NULL,
  `birthday` int(2) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '0' COMMENT '性别，1男，2女，0未设置',
  `avatar` varchar(300) DEFAULT NULL COMMENT '头像',
  `province` varchar(60) DEFAULT NULL COMMENT '省',
  `city` varchar(60) DEFAULT NULL COMMENT '市',
  `area` varchar(60) DEFAULT NULL COMMENT '区',
  `salt` varchar(60) DEFAULT NULL COMMENT '密码加盐',
  `mobileverify` tinyint(1) DEFAULT '0' COMMENT '是否绑定手机0否1是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of jan_feng_ye_member
-- ----------------------------
INSERT INTO `jan_feng_ye_member` VALUES ('2', 'oU5YytzlKYvpJu9j9sqqqRwgg-es', null, null, null, null, 'oRrdQt6nZrm3II3-hKnXr6qMTPAM', null, '18171266309', null, null, null, null, null, '0', null, '你是真的菜呀', null, null, null, null, null, '0', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83er9d4iaIgRvdT8BLUtX6Ayp9XaWkghdB85OB2bVzmRCykFZ5uEB6Xs8pqAXtD6AEqJiaJvZDktMJTEA/132', 'Hubei', 'China', null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('3', null, null, null, null, null, null, null, '15926290378', null, null, null, null, null, '1', null, '啦啦啦', null, null, null, null, null, '0', 'http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83er9d4iaIgRvdT8BLUtX6Ayp9XaWkghdB85OB2bVzmRCykFZ5uEB6Xs8pqAXtD6AEqJiaJvZDktMJTEA/132', 'Hubei', 'China', null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('4', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, 'hhhhhh', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('5', null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, 'eeeeeeeeeeee', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('6', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, 'fffffffffffffffffff', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('7', null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, '99999', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('8', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '1111111111111', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('9', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '33333333333333', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('10', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '44444444444444', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('11', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '5555555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('12', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '12121212', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('13', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '22222222', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('14', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '3333333333333', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('15', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '444444444444', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('16', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '5555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('17', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '55555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('18', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '55555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('19', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '5555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('20', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '555555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('21', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '55555555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('22', null, null, null, null, null, null, null, null, null, null, null, null, null, '1', null, '555555555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('23', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '55555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('24', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', null, '555555555555555', null, null, null, null, null, '0', null, null, null, null, null, '0');
INSERT INTO `jan_feng_ye_member` VALUES ('25', null, null, null, null, null, null, null, null, null, null, null, null, '1597571200', '0', null, null, null, null, null, null, null, '0', null, null, null, null, null, '0');

-- ----------------------------
-- Table structure for jan_feng_ye_member_favorite
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_member_favorite`;
CREATE TABLE `jan_feng_ye_member_favorite` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(60) DEFAULT NULL,
  `articlesid` int(10) DEFAULT NULL COMMENT '文章id',
  `openid` varchar(60) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL COMMENT '加入时间',
  `deltime` int(10) DEFAULT NULL COMMENT '删除时间',
  `authorid` int(10) DEFAULT NULL COMMENT '文章作者',
  `type` int(2) DEFAULT NULL COMMENT '收藏类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_member_favorite
-- ----------------------------

-- ----------------------------
-- Table structure for jan_feng_ye_member_group
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_member_group`;
CREATE TABLE `jan_feng_ye_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `groupname` varchar(255) NOT NULL COMMENT '用户组明',
  PRIMARY KEY (`id`,`groupname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_member_group
-- ----------------------------

-- ----------------------------
-- Table structure for jan_feng_ye_member_history
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_member_history`;
CREATE TABLE `jan_feng_ye_member_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(60) DEFAULT NULL,
  `articleid` int(10) DEFAULT NULL COMMENT '文章id',
  `openid` varchar(60) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0' COMMENT '是否删除 0 未删除 1 删除',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `deltime` int(10) DEFAULT NULL COMMENT '删除时间',
  `authorid` int(10) DEFAULT NULL COMMENT '作者id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户浏览足迹表';

-- ----------------------------
-- Records of jan_feng_ye_member_history
-- ----------------------------

-- ----------------------------
-- Table structure for jan_feng_ye_sign_records
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_sign_records`;
CREATE TABLE `jan_feng_ye_sign_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(60) DEFAULT NULL,
  `openid` varchar(60) DEFAULT NULL,
  `time` int(10) DEFAULT NULL COMMENT '签到时间',
  `credit` varchar(255) DEFAULT NULL COMMENT '签到奖励',
  `log` varchar(255) DEFAULT NULL COMMENT '日志',
  `type` tinyint(1) DEFAULT '0' COMMENT '奖励类型 0 日常 1 连续 2 总签到',
  `day` tinyint(6) DEFAULT NULL COMMENT '对应上面字段的签到天数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='签到日志表';

-- ----------------------------
-- Records of jan_feng_ye_sign_records
-- ----------------------------

-- ----------------------------
-- Table structure for jan_feng_ye_token
-- ----------------------------
DROP TABLE IF EXISTS `jan_feng_ye_token`;
CREATE TABLE `jan_feng_ye_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(60) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jan_feng_ye_token
-- ----------------------------
INSERT INTO `jan_feng_ye_token` VALUES ('1', '5e99571d237ef', '1587107613');

-- ----------------------------
-- Table structure for jia_feng_ye_sign_user
-- ----------------------------
DROP TABLE IF EXISTS `jia_feng_ye_sign_user`;
CREATE TABLE `jia_feng_ye_sign_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(60) DEFAULT NULL,
  `openid` varchar(60) DEFAULT NULL,
  `order` int(10) DEFAULT NULL COMMENT '最长连续签到数',
  `orderday` int(10) DEFAULT NULL COMMENT '当前连续签到天数',
  `sum` int(10) DEFAULT NULL COMMENT '总签到天数',
  `signdate` int(10) DEFAULT NULL COMMENT '签到自然月',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='签到用户表';

-- ----------------------------
-- Records of jia_feng_ye_sign_user
-- ----------------------------
