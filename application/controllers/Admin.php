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

        $this->CheckSession();
    }

    public function index()
	{
        $this->view('adm/index');
	}

    public function Login()
    {
        $data = [];
        // cookie
        $data['admin_login_info'] = $this->crypto->get_cookie_data('admin_login_info');

        // session
        $admin_login_info = $this->crypto->get_session_data('admin_login_info');

        $data['admin_login_info'];
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
            $admin_login_info = array(
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
            $this->crypto->set_cookie_data('admin_login_info', $admin_login_info);
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
                        xmp($this->admin_login_info);
                    }
                    else {
                        redirect(base_url('Admin/Login'));
                    }
                }
            }
        }
    }

}
