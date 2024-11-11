<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // 공통 기능 또는 라이브러리 로드 등 설정
    }

    // 뷰 렌더링 메서드
    protected function view($view, $data = []) {
        if ($this->yield) {
            $this->load->view('adm/layout/header', $data);
        }

        $this->load->view($view, $data);

        if ($this->yield) {
            $this->load->view('adm/layout/footer', $data);
        }
    }

}