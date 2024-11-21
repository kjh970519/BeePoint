<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();

        $this->yield = true;

        $this->load->model('admin/Sign_model', 'sign');
        $this->load->model('admin/User_model', 'user');
        $this->load->model('admin/Store_model', 'store');

        $this->CheckSession();
    }

    public function index()
	{
        $data = [];
        $this->view('adm/index', $data);
	}

    public function Login()
    {
        $data = [];
        // cookie
        $data['admin_login_info'] = $this->crypto->get_cookie_data('admin_login_info');

        // session
        $admin_login_info = $this->crypto->get_session_data('admin_login_info');

        if ($admin_login_info) {
            $r = $this->sign->checkToken($admin_login_info);
            if ($r) redirect(base_url('Admin/'));
        }

        $this->load->view('adm/login', $data);
    }

    public function checkAccount()
    {
        $dt;
        $dt['id'] = $this->input->get_post('id');
        $dt['pw'] = $this->input->get_post('pw');
        $dt['rememberId'] = ($this->input->get_post('rememberId'))? 1:0;
        $dt['autoLogin'] = ($this->input->get_post('autoLogin'))? 1:0;

        $finResult = array(
            "status" => "ok",
        );

        if (!$dt['id'] || !$dt['pw']) {
            $finResult['status'] = "fail";
            $finResult['msg'] = "아이디를 입력해주세요";
            if (!$dt['pw']) {
                $finResult['msg'] = "비밀번호를 입력해주세요";
            }
            echo json_encode($finResult);
            return;
        }

        // 계정 확인
        $r = $this->sign->checkAccount($dt);
        if ($r) {
            if (!$r['access_yn']) {
                $finResult["status"] = "fail";
                $finResult["msg"] = "승인되지 않은 계정입니다. 관리자에게 문의해주세요.";
                echo json_encode($finResult);
                return;
            }

            $admin_login_info = array(
                "idx" => $r['idx'],
                "id" => $dt['id'],
                "token" => bin2hex(random_bytes(16)),
                "rememberId" => $dt['rememberId'],
                "autoLogin" => $dt['autoLogin'],
            );

            // 기존 쿠키를 제거
            delete_cookie($this->crypto->enc('admin_login_info'));
            // 자동로그인이 체크된 경우
            if ($dt['autoLogin']) {
                $this->crypto->set_cookie_data('admin_login_info', $admin_login_info);
            }
            else {
                // 아이디를 기억하는 경우
                if ($dt['rememberId']) {
                    $this->crypto->set_cookie_data('admin_login_info', $admin_login_info);
                }
            }
            $admin_login_info['type'] = $r['type'];
            $this->setAdminLoginSession($admin_login_info);
        }
        else {
            $finResult['status'] = "fail";
            $finResult['msg'] = "아이디 또는 비밀번호를 확인해주세요";
        }
        echo json_encode($finResult);
    }

    public function setAdminLoginSession($dt)
    {
        $this->crypto->set_session_data('admin_login_info', $dt);
        $this->sign->login($dt);
    }

    public function Logout()
    {
        $this->session->sess_destroy();

        $admin_login_info = $this->crypto->get_cookie_data('admin_login_info');
        $dt = [];
        if ($admin_login_info['rememberId']) {
            $dt = Array(
                "id" => $admin_login_info['id'],
                "rememberId" => $admin_login_info['rememberId'],
            );
            delete_cookie($this->crypto->enc('admin_login_info'));
            $this->crypto->set_cookie_data('admin_login_info', $dt);
        }
        redirect(base_url('Admin/Login'));
    }

    public function CheckSession()
    {
        // session
        $admin_login_info = $this->crypto->get_session_data('admin_login_info');

        // cookie
        $this->admin_login_info = $this->crypto->get_cookie_data('admin_login_info');

        $exceptions = ['Login', 'checkAccount', 'Logout'];
        $last_segment = $this->uri->segment($this->uri->total_segments());

        // 만약 로그인 또는 로그아웃 페이지가 아닐경우 지속적으로 세션을 체크한다
        if (!in_array($last_segment, $exceptions)) {

            // 세션과 쿠키 모두 존재하지 않을 경우 로그인 페이지로 보낸다
            if (!$admin_login_info) {
                if (!$this->admin_login_info) {
                    redirect(base_url('Admin/Login'));
                }
                else {
                    if ($this->admin_login_info->autoLogin) {

                    }
                    else {
                        redirect(base_url('Admin/Login'));
                    }
                }
            }
        }
    }

    public function User($path=null)
    {
        $data = [];

        $pageNo = ($this->input->get_post('pageNo'))? $this->input->get_post('pageNo') : 1;
        $pageSize = ($this->input->get_post('pageSize'))? $this->input->get_post('pageSize') : 10;
        $offset = ($pageNo - 1) * $pageSize;
        $data['lists'] = $this->user->getAdminList($pageSize, $offset);

        $p_config = array(
            "base_url" => 'Admin/User/list',
            "total_rows" => $this->db->count_all('tbl_admin'),
            "page_size" => $pageSize
        );
        $data['links'] = $this->pagination->set_pagination($p_config);

        $this->view('adm/user/list', $data);
    }

    public function ModAdminAccess()
    {
        $finResult = array(
            "status" => "ok",
        );
        $type = $this->crypto->get_session_type();
        if ($type != "admin") {
            $finResult["status"] = "fail";
            echo json_encode($finResult);
            return;
        }

        $idx = $this->input->get_post('idx');
        $checked = $this->input->get_post('checked');

        if (!$idx || (!$checked && $checked != 0)) {
            $finResult["status"] = "fail";
            echo json_encode($finResult);
            return;
        }

        $where = array(
            "idx" => $idx,
        );
        $set = array(
            "access_yn" => $checked
        );
        $r = $this->user->modAdminAccess($where, $set);
        if (!$r) {
            $finResult["status"] = "fail";
            echo json_encode($finResult);
            return;
        }

        echo json_encode($finResult);
    }

    public function AddAdmin()
    {
        $finResult = array(
            "status" => "ok",
        );
        $type = $this->crypto->get_session_type();
        if ($type != "admin") {
            $finResult["status"] = "fail";
            $finResult["msg"] = "권한이 없습니다.";
            echo json_encode($finResult);
            return;
        }

        $type = $this->input->get_post('type');
        $id = $this->input->get_post('id');

        if (!$id) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "아이디를 입력해주세요.";
            echo json_encode($finResult);
            return;
        }

        $values = array(
            "type" => $type,
            "id" => $id,
            "pw" => $this->crypto->enc('0000'),
            "created_at" => date('YmdHis'),
        );
        $r = $this->user->addAdmin($values);

        if (!$r) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "등록에 실패했습니다.";
            echo json_encode($finResult);
            return;
        }
        echo json_encode($finResult);
    }

    public function DelAdmin()
    {
        $finResult = array(
            "status" => "ok",
        );
        $type = $this->crypto->get_session_type();
        if ($type != "admin") {
            $finResult["status"] = "fail";
            $finResult["msg"] = "권한이 없습니다.";
            echo json_encode($finResult);
            return;
        }

        $idx = $this->input->get_post('idx');

        if (!$idx || $idx == 1) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "삭제할 수 없는 값입니다.";
            echo json_encode($finResult);
            return;
        }

        $where = array(
            "idx" => $idx,
        );
        $r = $this->user->delAdmin($where);
        if (!$r) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "삭제 실패했습니다.";
            echo json_encode($finResult);
            return;
        }

        echo json_encode($finResult);
    }

    public function Store($path=null, $path2=null)
    {
        switch ($path)
        {
            case "list":
                $data = [];

                $pageNo = ($this->input->get_post('pageNo'))? $this->input->get_post('pageNo') : 1;
                $pageSize = ($this->input->get_post('pageSize'))? $this->input->get_post('pageSize') : 10;
                $offset = ($pageNo - 1) * $pageSize;
                $data['lists'] = $this->store->getStoreList($pageSize, $offset);

                $p_config = array(
                    "base_url" => 'Admin/Store/list',
                    "total_rows" => $this->db->count_all('tbl_store_info'),
                    "page_size" => $pageSize
                );
                $data['links'] = $this->pagination->set_pagination($p_config);

                $this->view('adm/store/list', $data);
            break;

            case "pointBank":
                $data = [];

                $pageNo = ($this->input->get_post('pageNo'))? $this->input->get_post('pageNo') : 1;
                $pageSize = ($this->input->get_post('pageSize'))? $this->input->get_post('pageSize') : 10;
                $offset = ($pageNo - 1) * $pageSize;
                $data['lists'] = $this->store->getPointBankList($pageSize, $offset);

                $p_config = array(
                    "base_url" => 'Admin/Store/list',
                    "total_rows" => $this->db->count_all('tbl_point_bank'),
                    "page_size" => $pageSize
                );
                $data['links'] = $this->pagination->set_pagination($p_config);

                $this->view('adm/store/point_bank/list', $data);
            break;
        }
    }

    public function ModStoreAccess()
    {
        $finResult = array(
            "status" => "ok",
        );

        $type = $this->crypto->get_session_type();
        $id = $this->crypto->get_session_id();
        $idx = $this->input->get_post('idx');
        $checked = $this->input->get_post('checked');

        if ($type != 'admin') {
            $r = $this->store->checkStoreAdmin($idx, $id);
            if (!$r) {
                $finResult["status"] = "fail";
                $finResult["msg"] = "소유자만 수정할 수 있습니다.";
                echo json_encode($finResult);
                return;
            }
        }

        if (!$idx || (!$checked && $checked != 0)) {
            $finResult["status"] = "fail";
            echo json_encode($finResult);
            return;
        }

        $where = array(
            "idx" => $idx,
        );
        $set = array(
            "access_yn" => $checked
        );
        $r = $this->store->modStoreAccess($where, $set);
        if (!$r) {
            $finResult["status"] = "fail";
            echo json_encode($finResult);
            return;
        }

        echo json_encode($finResult);
    }

    public function DelStore() {
        $finResult = array(
            "status" => "ok",
        );

        $type = $this->crypto->get_session_type();
        $id = $this->crypto->get_session_id();
        $idx = $this->input->get_post('idx');

        if ($type != 'admin') {
            $r = $this->store->checkStoreAdmin($idx, $id);
            if (!$r) {
                $finResult["status"] = "fail";
                $finResult["msg"] = "소유자만 삭제할 수 있습니다.";
                echo json_encode($finResult);
                return;
            }
        }

        if (!$idx) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "삭제할 수 없는 값입니다.";
            echo json_encode($finResult);
            return;
        }

        $where = array(
            "idx" => $idx,
        );
        $r = $this->store->delStore($where);
        if (!$r) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "삭제 실패했습니다.";
            echo json_encode($finResult);
            return;
        }

        echo json_encode($finResult);
    }

    public function AddPointBank() {
        $finResult = array(
            "status" => "ok",
        );

        $point_bank_nm = $this->input->get_post('point_bank_nm');
        $usage_point = $this->input->get_post('usage_point');
        $grades = $this->input->get_post('grades');
        $acc_rates = $this->input->get_post('acc_rates');
        $type = $this->input->get_post('type');
        if (!$type) exit;

        if (!$point_bank_nm) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "포인트 은행명을 입력해주세요.";
            echo json_encode($finResult);
            return;
        }
        if (!$usage_point) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "최소 사용 가능 포인트를 입력해주세요.";
            echo json_encode($finResult);
            return;
        }
        if (count($grades) == 0 || count($acc_rates) == 0) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "혜택은 최소 1개 이상 등록해야합니다.";
            echo json_encode($finResult);
            return;
        }
        $benefits = [];
        foreach($grades AS $idx => $grade) {
            if (!$grade || !$acc_rates[$idx]) {
                $finResult["status"] = "fail";
                $finResult["msg"] = "등급명 및 적립률을 모두 입력해주세요.";
                echo json_encode($finResult);
                return;
            }
            $benefits[] = array(
                "grade" => $grade,
                "acc_rate" => $acc_rates[$idx],
                "coupon" => []
            );
        };

        $dt = array(
            "point_bank_nm" => $point_bank_nm,
            "usage_point" => $usage_point,
            "benefits" => json_encode($benefits),
        );
        if ($type == 'add') {
            $dt['admin_idx'] =  $this->crypto->get_session_idx();
            $dt['created_at'] =  date('YmdHis');
            $this->store->addPointBank($dt);
        }
        else if ($type == 'mod') {
            $this->store->modPointBank($dt);
        }

        echo json_encode($finResult);
    }

    public function DelPointBank() {
        $finResult = array(
            "status" => "ok",
        );

        $type = $this->crypto->get_session_type();
        $id = $this->crypto->get_session_id();
        $idx = $this->input->get_post('idx');

        if ($type != 'admin') {
            $r = $this->store->checkStoreAdmin($idx, $id);
            if (!$r) {
                $finResult["status"] = "fail";
                $finResult["msg"] = "소유자만 삭제할 수 있습니다.";
                echo json_encode($finResult);
                return;
            }
        }

        if (!$idx) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "삭제할 수 없는 값입니다.";
            echo json_encode($finResult);
            return;
        }

        $where = array(
            "idx" => $idx,
        );
        $r = $this->store->delPointBank($where);
        if (!$r) {
            $finResult["status"] = "fail";
            $finResult["msg"] = "삭제 실패했습니다.";
            echo json_encode($finResult);
            return;
        }

        echo json_encode($finResult);
    }

}
