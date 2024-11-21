<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination {

    // 커스텀 create_links 메서드
    public function create_links_custom($custom_query) {
        // 기본 링크 생성
        $links = $this->create_links();

        // 커스텀 쿼리 문자열 추가
        if ($links) {
            $links = preg_replace_callback('/href="([^"]*)"/', function ($matches) use ($custom_query) {
                return 'href="' . $matches[1] . '&' . $custom_query . '"';
            }, $links);
        }

        return $links;
    }

    public function set_pagination($p_config) {
        // 페이지네이션 설정
        $config['base_url'] = site_url($p_config['base_url']);
        $config['total_rows'] = $p_config['total_rows']; // 데이터 총 개수
        $config['per_page'] = $p_config['page_size'];    // 한 페이지에 표시할 데이터 수
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'pageNo';
        $config['suffix'] = "&pageSize={$p_config['page_size']}";
        $config['reuse_query_string'] = FALSE;
        $config['use_page_numbers'] = TRUE; // 페이지 번호 사용 (기본: FALSE)
        $config['full_tag_open']= '<div class="pages">' ;
        $config['full_tag_close'] = '</div>' ;
        $config['cur_tag_open'] = '<span class="current">' ;
        $config['cur_tag_close'] = '</span>' ;

        $this->initialize($config);
        return $this->create_links();
    }
}