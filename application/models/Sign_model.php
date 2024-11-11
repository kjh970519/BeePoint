<?
class Sign_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function checkUser($data)
    {
        return $this->db->where('id', $data['id'])->where('password', $this->crypto->enc($data['password']))->get('tbl_account_info')->num_rows();
    }

    function updateSignInfo($data)
    {
        $now = date('YmdHis');
        $this->db->where('id', $data['id'])->where('password', $this->crypto->enc($data['password']))->set('token', $data['token'])->set('last_signin_at', $now)->update('tbl_account_info');
    }

    function autoSignIn($id, $token)
    {
        return $this->db->where('id', $id)->where('token', $token)->get('tbl_account_info')->num_rows();
    }

    function closeSession()
    {
        $this->session->sess_destroy();
    }
}