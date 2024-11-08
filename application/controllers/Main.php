<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

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
	public function index()
	{
        $data['signin_info'] = json_decode($this->crypto->dec(get_cookie('signin_info')), true);
        $this->load->model('User_model', 'user');

        if ($this->session->userdata('user_id')) {
            // 자동로그인, 아이디저장 쿠키가 있을때
            if ($data['signin_info']['token']) {
                // 토큰값이 다를 경우
                if (!$this->user->autoSignIn($data['signin_info']['user_id'], $data['signin_info']['token'])) {
                    $this->user->closeSession();
                    redirect();
                }
                else {
                    if ($data['signin_info']['check_auto']) {
                        $this->session->set_userdata('user_id', $data['signin_info']['user_id']);
                    }
                }
            }
            else {
                // 토큰값이 다를 경우
                if (!$this->user->autoSignIn($this->session->userdata('user_id'), $this->session->userdata('token'))) {
                    $this->user->closeSession();
                    redirect();
                }
            }
        }
        else {
            if ($data['signin_info']['check_auto']) {
                if ($this->user->autoSignIn($data['signin_info']['user_id'], $data['signin_info']['token'])) {
                    $this->session->set_userdata('user_id', $data['signin_info']['user_id']);
                    redirect();
                }
            }
        }

		$this->load->view('user/main', $data);
	}
}
