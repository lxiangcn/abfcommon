<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_install extends CI_Migration {

    public function up() {
        // THIS IS JUST A PLACEHOLDER!!!... PUT IN YOUR OWN CODE HERE
        $file_path = FCPATH . 'data/install/abfcommon_schema.sql';
        $this->db->load_sql($file_path);
    }

    public function down() {

    }
}