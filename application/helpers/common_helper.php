<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*************************************************************
 * common_helper
 * 전페이지에서 공통으로 사용하는 Helper
 ************************************************************
 *
 */

function xmp($vars) {
    echo "<xmp style='background-color: #6BB6BB'>";
    print_r($vars);
    echo "</xmp>";
}

function pre($vars) {
    echo "<pre style='background-color: lemonchiffon'>";
    print_r($vars);
    echo "</pre>";
}

?>
