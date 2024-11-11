<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign extends MY_Controller {

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
        $this->load->model('Sign_model', 'sign');
    }

    public function index()
    {
        $data['signin_info'] = json_decode($this->crypto->dec(get_cookie('signin_info')), true);
        $this->SignOut();
        $this->load->view('sign', $data);
    }

    public function SignIn()
	{
        $finResult = array(
            'status' => 'ok',
            'code' => 0,
        );

        $data = [];
        $data['id'] = $this->input->get_post('id');
        $data['password'] = $this->input->get_post('password');
        $data['check_auto'] = $this->input->get_post('check_auto');
        $data['check_save'] = $this->input->get_post('check_save');

        if (!$data['id'] || !$data['password']) {
            $finResult['status'] = 'fail';
            $finResult['code'] = 98;
            echo json_encode($finResult);
            return;
        }

        if ($this->sign->checkUser($data)) {
            $data['token'] = bin2hex(random_bytes(16));

            $this->session->set_userdata('user_id', $data['id']);
            $this->session->set_userdata('token', $data['token']);

            if ($data['check_auto'] || $data['check_save']) {
                $signin_info = array(
                    "user_id" => $data['id'],
                    "token" => $data['token'],
                    "check_auto" => $data['check_auto'],
                    "check_save" => $data['check_save'],
                );
                set_cookie('signin_info', $this->crypto->enc(json_encode($signin_info)), 86400 * 365);
                $this->sign->updateSignInfo($data);
            }
            else {
                delete_cookie('signin_info');
            }
            $this->sign->updateSignInfo($data);
        }
        else {
            $finResult['status'] = 'fail';
            $finResult['code'] = 99;
        }
        echo json_encode($finResult);
	}

    public function SignOut()
    {
        $this->sign->closeSession();
        redirect();
    }
}
