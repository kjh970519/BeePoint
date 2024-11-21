<?
class Store_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getStoreList($pageSize, $offset)
    {
        return $this->db->limit($pageSize, $offset)->get('tbl_store_info')->result_array();
    }

    function checkStoreAdmin($idx, $id)
    {
        return $this->db->where("idx", $idx)->where("admin_idx = (SELECT idx FROM tbl_admin WHERE id = '{$id}')")->get('tbl_store_info')->num_rows();
    }

    function modStoreAccess($where, $set)
    {
        return $this->db->where($where)->set($set)->update('tbl_store_info');
    }

    function delStore($where)
    {
        return $this->db->where($where)->delete('tbl_store_info');
    }

    function getPointBankList($pageSize, $offset)
    {
        $session_idx = $this->crypto->get_session_idx();
        $session_type = $this->crypto->get_session_type();
        $where = "1 = 1";
        if ($session_type != 'admin') {
            $where = array(
                "admin_idx" => $session_idx,
            );
        }

        $select = array(
            "idx",
            "point_bank_nm",
            "(SELECT id FROM tbl_admin WHERE idx = tbl_point_bank.admin_idx) AS admin_id",
            "usage_point",
            "benefits",
            "created_at",
        );
        return $this->db->limit($pageSize, $offset)->where($where)->select($select)->get('tbl_point_bank')->result_array();
    }

    function addPointBank($dt)
    {
        return $this->db->insert("tbl_point_bank", $dt);
    }

    function delPointBank($where)
    {
        return $this->db->where($where)->delete('tbl_point_bank');
    }
}