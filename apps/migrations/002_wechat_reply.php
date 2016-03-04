<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_wechat_reply extends CI_Migration {

    public function up() {
        $sql = "alter table abf_wechat_reply add reply_type varchar(20) default NUll ";
        $this->db->query($sql);
    }

    public function down() {

    }
}