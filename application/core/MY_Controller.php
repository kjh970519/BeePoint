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

            $base_url = base_url();
            $current_url = current_url();
            $data['path'] = str_replace($base_url, "",$current_url);

            // 카테고리를 가져온다
            $this->load->model('admin/Category_model', 'category');
            $r = $this->category->getCategory();

            $categories;
            foreach ($r['parents_categories'] AS $idx => $pc) {
                $categories[] = $pc;
                foreach ($r['children_categories'] AS $cc) {
                    if ($pc['idx'] == $cc['parent_category_idx']) {
                        if ($data['path'] == $cc['path']) {
                            $data['path_nm'] = "{$pc['category_nm']} / {$cc['category_nm']}";
                        }
                        $categories[$idx]['sub_categories'][] = $cc;
                    }
                }
            }
            $data['categories'] = $categories;

            $this->load->view('adm/layout/header', $data);
        }
        $this->load->view($view, $data);

        if ($this->yield) {
            $this->load->view('adm/layout/footer', $data);
        }
    }

}