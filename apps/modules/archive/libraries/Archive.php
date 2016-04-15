<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * abfcommon
 *
 * @package Archive
 * @copyright Copyright (c) 2010-2016, Orzm.net
 * @license http://opensource.org/licenses/GPL-3.0    GPL-3.0
 * @link http://orzm.net
 * @version 2016-04-16 23:42:45
 * @author Alex Liu<lxiangcn@gmail.com>
 */
class Archive {

    function __construct() {
    }

    public function __get($var) {
        return get_instance()->$var;
    }
    // 获取程序版本
    function _get_version() {
        $this->S['version']    = HUIBERCMS_NAME . ' ' . HUIBERCMS_VERSION . ' build ' . HUIBERCMS_BUILDTIME;
        $this->S['softname']   = 'Huiber企业建站系统';
        $this->S['updatetime'] = HUIBERCMS_BUILDTIME;
    }

    /**
     *
     * 新闻数据读取函数
     *
     * @param string $classid 分类ID 多分类可以用逗号隔开 如1,2
     * @param int $num 读取的新闻数量
     * @param int $isgood 推荐等级 数字1-9
     * @param bool $titlepic 是否只显示标题图片
     * @param string $order 排序语句
     */
    function _article($cat_id = NULL, $row = 10, $ishot = 0, $isrecent = 0, $istop = 0, $showimg = 0, $order = NULL) {
        $this->load->model('model_archive', 'archive');
        $where = ' 1=1 and ';

        if ($cat_id) {
            $where .= " category_id in ($cat_id) and ";
        }
        if ($ishot) {
            $where .= " hot = $ishot  and ";
        }
        if ($isrecent) {
            $where .= " recent = $isrecent  and ";
        }
        if ($istop) {
            $where .= " top = $istop  and ";
        }
        if ($showimg) {
            $where .= " image <> '' and    ";
        }
        $where .= ' published = 1 ';

        $result = $this->archive->get_all_article_rows($where, '*', $order, 0, $row);

        return $result;
    }

    /**
     *
     * 产品数据读取函数
     *
     * @param string $cat_id 分类ID 多分类可以用逗号隔开 如1,2
     * @param string $brandid 品牌ID 多品牌可以用逗号隔开 如1,2
     * @param int $num 读取的产品数量
     * @param int $isgood 推荐等级 数字1-9
     * @param bool $titlepic 是否只显示标题图片
     * @param string $order 排序语句
     */
    function _products($cat_id = NULL, $num = 10, $isgood = 0, $titlepic = 0, $order = NULL) {
        $this->load->model('model_product', 'product');
        if ($cat_id) {
            $where = " product_cat_id in ($cat_id) and ";
        }
        if ($isgood) {
            $where = " isgood = '$isgood'  and ";
        }
        if ($titlepic) {
            $where .= " showpic <> '' and    ";
        }
        $where .= ' 1=1 ';
        $result = $this->product->get_all_rows($where, '*', $order, 0, $num);
        return $result;
    }

    /**
     * 读取碎片代码
     *
     * @param string $name //碎片名称
     */
    function _chips($name) {
        $this->load->model('model_chips', 'chips');
        $where  = " `chipname` = '$name' ";
        $result = $this->chips->field($where, 'content', NULL, 0, 1);

        return $result;
    }

    /**
     * 读留言信息代码
     *
     * @param unknown_type $num 调用条数
     * @param unknown_type $isshow 1：调用审核后留言 0：调用全部留言
     * @param unknown_type $reply 1：调用已回复留言 0：调用全部留言
     * @param unknown_type $order 排序 字符串如： id desc 默认 id desc
     */
    function _message($num = 10, $isshow = 1, $reply = 1, $order = 'id desc') {
        $this->load->_model('Model_message', 'message');
        $where = '';
        if ($isshow) {
            $where = " `isshow` = $isshow and  ";
        }

        if ($reply) {
            $where .= " `reply` <>'' ";
        }

        $result = $this->message->find_all($where, '*', $order, 0, $num);

        return $result;
    }

    /**
     * 读广告信息代码
     *
     * @param unknown_type $classid 分类id
     * @param unknown_type $isshow 1：显示的广告 0：调用全部广告
     * @param unknown_type $order 排序 字符串如： id desc 默认 id desc
     */
    function _ad_list($classid, $num = 5, $status = 1, $order = 'order  asc,id desc') {
        $this->load->_model('Model_ad', 'ad');
        $where = '';

        $where = "`classid` = '$classid' and ";
        if ($status) {
            $where .= " `status` = $status   ";
        }

        $result = $this->ad->find_all($where, '*', $order, 0, $num);

        return $result;
    }

    /**
     * 根据id读广告信息代码
     *
     * @param unknown_type $id 广告id 逗号分开 ,
     */
    function _ad($id, $num = 10, $order = 'order asc') {
        $this->load->_model('Model_ad', 'ad');
        $where = '';

        $where = " `status` = 1 and   ";

        if (!is_numeric($id)) {
            $where .= " `id` in ($id)   ";
            $result = $this->ad->find_all($where, '*', $order, 0, $num);
        } else {
            $result = $this->ad->find($where . 'id  = ' . $id);
        }

        return $result;
    }

    /**
     *
     * 产品分类读取函数
     *
     * @param string $fid 父分类ID 多分类可以用逗号隔开 如1,2
     * @param int $num 读取的新闻数量
     * @param string $order 排序语句
     */
    function _productclass($fid = NULL, $num = 10, $order = NULL) {
        $this->load->model('model_products_cats', 'products_cats');
        // $where = " tbname='product' ";
        if ($fid) {
            $where .= " and parent_id in ($fid)  ";
        }
        $result = $this->products_cats->find_all($where, '*', $order, 0, $num);
        return $result;
    }

    /**
     * 获取所有分类
     *
     * @param type $tbname 分类表名称category
     * @return int
     */
    function _get_cats($tbname) {
        $where = array(
            "published" => 1,
        );
        $this->db->where($where);
        $query  = $this->db->get($tbname);
        $result = $query->result_array();
        if (isset($result)) {
            return $result;
        } else {
            return 0;
        }
    }

    /**
     *
     * 获取单条分类数据
     *
     * @param int $classid
     */
    public function _get_class_row($cat_id, $fild_name = "id", $tbname = null) {
        $this->db->where($fild_name, $cat_id);
        $query  = $this->db->get($tbname);
        $result = $query->row_array(); // 结果集
        if (isset($result)) {
            return $result; // 返回数组
        } else {
            return 0;
        }
    }

    /**
     * 获取导航
     */
    function _get_channel($type = 'topmenu', $row = 8) {
        $this->load->model('model_navigation_cats', 'navigations_cats');
        $where   = " cat_key = '$type' ";
        $cat_nav = $this->navigations_cats->find_all($where, '*', '');
        $this->load->model('model_navigations', 'navigations');
        $where  = " navigation_cat_id = '{$cat_nav[0]['id']}' and published = 1 ";
        $result = $this->navigations->find_all($where, '*', 'sort_order asc', 0, $row);
        foreach ($result as $key => $value) {
            if (strstr($result[$key]['link'], "http://") || $result[$key]['link'] == '/') { // 是网址
            } else {
                $result[$key]['link'] = site_url($result[$key]['link']);
            }
        }
        return $result;
    }

    /**
     * 获取导航
     */
    function _get_flink($rows = 8) {
        $this->load->model('model_links', 'links');
        $where  = " published = 1 ";
        $result = $this->links->find_all($where, '*', '', 0, $rows);
        return $result;
    }

}

// ----------------------自定义函数-------------------------//

/**
 * 获取友情链接数组
 * $type 数据类型
 */
function flink($rows = 8) {
    $com = &get_common();
    return $com->_get_flink($rows);
}

/**
 * 获取导航数组
 * $type 数据类型
 */
function channel($type = "topmenu", $row = '8') {
    $com = &get_common();
    return $com->_get_channel($type, $row);
}

/**
 * 获取分类数组
 * $tbname 数据类型
 */
function get_cats($tbname) {
    $com = &get_common();
    return $com->_get_cats($tbname);
}

/**
 * 获取单条分类数据
 * $classid 分类id
 */
function get_class_row($cat_id, $fild_name = "id", $tbname = null) {
    $com = &get_common();
    return $com->_get_class_row($cat_id, $fild_name, $tbname);
}

/**
 * 生成通用类对象
 */
function get_common() {
    return new Archive();
}

function version($f = FALSE) {
    $com = &get_common();
    $com->_get_version();
    if ($f) {
        return $com->ci->S['version'];
    } else {
        return '<a href = "http://www.huiber.cn" target = _blank >' . $com->ci->S['version'] . '</a>';
    }
}

function article($cat_id = NULL, $row = 10, $ishot = 0, $isrecent = 0, $istop = 0, $showimg = 0, $order = NULL) {
    $com = &get_common();
    return $com->_article($cat_id, $row, $ishot, $isrecent, $istop, $showimg, $order);
}

function products($cat_id = 0, $row = 10, $isgood = 0, $titlepic = 0, $order = '`id` desc') {
    $com = &get_common();
    return $com->_products($cat_id, $row, $isgood, $titlepic, $order);
}

function chips($name) {
    $com = &get_common();
    return $com->_chips($name);
}

function message($num = 10, $isshow = 1, $reply = 1, $order = 'id desc') {
    $com = &get_common();
    return $com->_message($num, $isshow, $reply, $order);
}

function ad_list($typeid, $num = 10, $status = 1, $order = 'id desc') {
    $com = &get_common();
    return $com->_ad_list($typeid, $num, $status, $order);
}

function ad($id) {
    $com = &get_common();
    return $com->_ad($id);
}

function productclass($fid = NULL, $num = 10, $order = '`id` asc') {
    $com = &get_common();
    return $com->_productclass($fid, $num, $order);
}

/*
 * Utf-8、gb2312都支持的汉字截取函数
 * cut_str(字符串, 截取长度, 开始长度, 编码);
 * 编码默认为 utf-8
 * 开始长度默认为 0
 */

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') {
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);

        if (count($t_string[0]) - $start > $sublen) {
            return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        }

        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start  = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';

        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129) {
                $i++;
            }

        }
        if (strlen($tmpstr) < $strlen) {
            $tmpstr .= "...";
        }

        return $tmpstr;
    }
}