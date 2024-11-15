<?
class Sign_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function checkAccount($dt)
    {
        $result = $this->db->where('id', $dt['id'])->where('pw', $this->crypto->enc($dt['pw']))->get('tbl_admin')->result_array();
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    function login($dt)
    {
        $update_dt = array(
            "token" => $dt['token'],
            "last_login_at" => date('YmdHis'),
            "last_login_ip" => $this->input->ip_address(),
        );
        $this->db->set($update_dt)->update('tbl_admin');
    }

    function checkToken($dt)
    {
        return $this->db->where('id', $dt['id'])->where('token', $dt['token'])->get('tbl_admin')->num_rows();
    }

}