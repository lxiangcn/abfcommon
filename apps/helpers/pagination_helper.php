<?php
defined('BASEPATH') or die('No direct script access allowed');

/**
 * pagination link
 *
 * @param String $str String to get an excerpt from
 * @param Integer $startPos Position int string to start excerpt from
 * @param Integer $maxLength Maximum length the excerpt may be
 * @return String excerpt
 */
if (!function_exists('pagination_link')) {

    function pagination_link($uri, $total, $segment = 3, $page_no = 1, $limit = 10) {
        $CI = &get_instance();

        // hitung batas awal pengambilan data
        $offset = $limit * ($page_no - 1);

        // setting dasar
        $config['base_url']    = base_url($uri);
        $config['uri_segment'] = $segment;
        $config['total_rows']  = $total;
        $config['per_page']    = $limit;
        $config['num_links']   = 5;

        // set ini supaya linknya pake page number, bukan offset
        $config['use_page_numbers']      = TRUE;
        $config['use_global_url_suffix'] = TRUE;
        $config['reuse_query_string']    = TRUE;

        // layouting
        $config['anchor_class']    = '';
        $config['first_tag_open']  = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';
        $config['next_link']       = '下一页';
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '</li>';
        $config['prev_link']       = '上一页';
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '</li>';
        $config['last_tag_open']   = '<li>';
        $config['last_tag_close']  = '</li>';
        $config['cur_tag_open']    = '<li class="active"><a>';
        $config['cur_tag_close']   = '</a></li>';

        $CI->pagination->initialize($config);
        return array('link' => $CI->pagination->create_links(), 'limit' => $limit, 'offset' => $offset, 'total_page' => ceil($total / $limit));
    }
}

/* End of file excerpt_helper.php */
/* Location: ./system/helpers/excerpt_helper.php */