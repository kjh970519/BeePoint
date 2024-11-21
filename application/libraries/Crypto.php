<?php
class Crypto
{
    public function __construct()
    {
        // CodeIgniter 인스턴스를 가져옴
        $this->CI = &get_instance();
        // 세션 라이브러리 로드
        $this->CI->load->library('session');

        $this->key_str = '2ad265d024a06e3039c3649213a834390412aa7097ea05eea4e0b44c88ecf7972ad265d024a06e3039c3649213a834390412aa7097ea05eea4e0b44c88ecf797';
        $this->key = $this->mysql_aes_key($this->key_str);
        $this->iv = openssl_random_pseudo_bytes(16);
        $this->mode = 'aes-128-ecb';
    }

    function mysql_aes_key($key)
    {
        $new_key = str_repeat(chr(0), 16);
        for ($i = 0, $len = strlen($key); $i < $len; $i++) {
            $new_key[$i % 16] = $new_key[$i % 16] ^ $key[$i];
        }
        return $new_key;
    }

    // 암호화
    public function enc($plainText) {
        $plainText = strtoupper(bin2hex(openssl_encrypt($plainText, $this->mode, $this->key, OPENSSL_RAW_DATA))) ;
        return $plainText ;
    }

    // 복호화
    public function dec($decryptText) {
        $decryptText = openssl_decrypt(hex2bin($decryptText), $this->mode, $this->key, true);
        return $decryptText ;
    }

    public function get_session_data($key) {
        if ($this->CI->session->userdata($this->enc($key))) {
            return @json_decode($this->dec($this->CI->session->userdata($this->enc($key))), true);
        }
        return null;
    }

    public function set_session_data($key, $dt) {
        $this->CI->session->set_userdata($this->enc($key), $this->enc(json_encode($dt)));
    }

    public function get_cookie_data($key) {
        return @json_decode($this->dec(get_cookie($this->enc($key))), true);
    }

    public function set_cookie_data($key, $dt) {
        set_cookie($this->enc($key), $this->enc(json_encode($dt)), 86400 * 365);
    }

    public function get_session_type() {
        return $this->get_session_data('admin_login_info')['type'];
    }

    public function get_session_idx() {
        return $this->get_session_data('admin_login_info')['idx'];
    }

    public function get_session_id() {
        return $this->get_session_data('admin_login_info')['id'];
    }
}
