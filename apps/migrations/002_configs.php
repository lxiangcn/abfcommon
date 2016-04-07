<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_configs extends CI_Migration {

	public function up() {
		// THIS IS JUST A PLACEHOLDER!!!... PUT IN YOUR OWN CODE HERE
		$configssql="DROP TABLE IF EXISTS `abf_configs`;
CREATE TABLE `abf_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(40) NOT NULL COMMENT '记标',
  `value` varchar(255) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL COMMENT '备注',
  `type` varchar(15) NOT NULL DEFAULT 'text',
  `group` int(11) DEFAULT '1',
  `ranges` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='';
INSERT INTO `abf_configs` VALUES ('3', 'allowed_types', '', '附件类型 | 隔开', 'text', '2', null);
INSERT INTO `abf_configs` VALUES ('4', 'cat_level', '', '分类允许最大级别数', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('5', 'cdn_url', '', 'CDN域名', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('6', 'description', '', '网站描述', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('7', 'isrewrite', '', '是否伪静态', 'radio', '1', '0,1');
INSERT INTO `abf_configs` VALUES ('8', 'is_cdn', '', '是否启用静态资源CDN加速', 'radio', '1', '0,1');
INSERT INTO `abf_configs` VALUES ('9', 'keywords', '', '关键词', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('10', 'news_comment_status', '', '新闻评论设置：0，不开启；1，开启且需要审核；2，开启且不需要审核；默认为0', 'text', '1', '0,1');
INSERT INTO `abf_configs` VALUES ('11', 'rewritetype', '', '设置伪静态后缀名', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('12', 'site_close', '', '是否关闭网站', 'radio', '1', 'on,off');
INSERT INTO `abf_configs` VALUES ('13', 'site_close_tip', '', '关闭网站提示', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('14', 'site_icp', '', '备案编号', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('15', 'site_name', '', '网站标题', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('16', 'site_url', '', '网站网址', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('17', 'theme', '', '风格模板', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('18', 'upload_encrypt_name', '', '是否重新命名上传的文件名称', 'text', '2', null);
INSERT INTO `abf_configs` VALUES ('19', 'upload_max_size', '', '上传文件最大允许大小', 'text', '2', null);
INSERT INTO `abf_configs` VALUES ('20', 'upload_path', '', '附件目录', 'text', '2', null);
INSERT INTO `abf_configs` VALUES ('21', 'upload_path_format', '', '目录格式', 'text', '2', null);
INSERT INTO `abf_configs` VALUES ('22', 'user_audit', '', '注册用户是否需要审核，0：需要，1：不需要', 'radio', '1', '0,1');
INSERT INTO `abf_configs` VALUES ('23', 'wap_theme', '', 'wap风格模板', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('1', 'admin_email', '', '管理员邮箱', 'text', '1', null);
INSERT INTO `abf_configs` VALUES ('2', 'admin_folder', '', '后台入口', 'text', '1', null);
";
$this->db->query($configssql);	
}

	public function down() {

	}

}
