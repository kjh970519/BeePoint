<?
class Category_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getCategory()
    {
        $admin_login_info = $this->crypto->get_session_data('admin_login_info');
        $_where = array(
            "access_yn" => 1,
        );
        if ($admin_login_info['type'] !== "admin") {
            $_where['level'] = "normal";
        }
        $result['parents_categories'] = $this->db->where($_where)->where('parent_category_idx IS NULL')->get('tbl_admin_category')->result_array();
        $result['children_categories'] = $this->db->where($_where)->where('parent_category_idx IS NOT NULL')->get('tbl_admin_category')->result_array();

        return $result;
    }

}