<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point extends MY_Controller {

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

        

        echo json_encode($finResult);
    }
}
