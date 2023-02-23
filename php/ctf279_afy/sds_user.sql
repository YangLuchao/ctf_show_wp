/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : sds

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-12-16 20:06:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sds_user
-- ----------------------------
DROP TABLE IF EXISTS `sds_user`;
CREATE TABLE `sds_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sds_username` varchar(255) DEFAULT NULL,
  `sds_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sds_user
-- ----------------------------

