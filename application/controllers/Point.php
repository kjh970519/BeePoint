<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $data['signin_info'] = json_decode($this->crypto->dec(get_cookie('signin_info')), true);
        $this->token = $data['signin_info']['token'];
    }

    public function RequestAddPoint()
	{
        $finResult = array(
            'status' => 'ok',
            'code' => 0,
        );

        $_mobile = $this->input->get_post('mobile');
        if (!$_mobile) {
            $finResult['status'] = 'fail';
            echo json_encode($finResult);
            return;
        }
        else if (count($_mobile) > 11) {
            $finResult['status'] = 'fail';
            echo json_encode($finResult);
            return;
        }
        $_mobile = implode("", $_mobile);

        $r = $this->sendDataToWebSocket($_mobile);
        if ($r['status'] == 'success') {

        }
        else {
            $finResult['status'] = 'fail';
        }

        echo json_encode($finResult);
    }

    public function sendDataToWebSocket($mobile)
    {
        $url = 'http://localhost:8080/send';
        $data = array('token' => $this->token,
                      'mobile' => $mobile);

        // cURL을 이용한 HTTP POST 요청
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
