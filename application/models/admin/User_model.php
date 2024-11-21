<?
class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getAdminList($pageSize, $offset)
    {
        return $this->db->limit($pageSize, $offset)->get('tbl_admin')->result_array();
    }

    function modAdminAccess($where, $set)
    {
        return $this->db->where($where)->set($set)->update('tbl_admin');
    }

    function addAdmin($values)
    {
        $query = $this->db->where('id', $values['id'])->get('tbl_admin');
        if ($query->num_rows() > 0) {
            return false;
        }

        return $this->db->insert('tbl_admin', $values);
    }

    function delAdmin($where)
    {
        return $this->db->where($where)->delete('tbl_admin');
    }

}