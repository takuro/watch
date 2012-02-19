<?php

class Response {

  function __construct() {
  }

  function call($code, $msg=null) {
    switch($code) {
    case 'fatal_error':
      $this->fatal_error($msg); return;
    case 'error_with_msg':
      $this->error_with_msg($msg); return;
    case 'go_to_login_form':
      $this->go_to_login_form(); return;
    case 'go_to_index':
      $this->go_to_index(); return;
    case 'json':
      $this->json($msg); return;
    case 403: r403(); return;
    case 404: r404(); return;
    default:
      fatal_error(); return;
    }
  }

  public static function go_to_login_form() {
    header('Location: /login.php');
  }

  public static function go_to_index() {
    header('Location: /index.php');
  }

  public static function fatal_error($msg='oops!') {
    die('Fatal Error: '.$msg);
  }

  public static function error_with_msg($msg) {
    echo 'Error: '.$msg;
    return true;
  }

  public static function json($array) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($array);
    return;
  }

  public static function r403() {
    header('HTTP/1.0 403 Forbidden');
    die('403 Forbidden');
  }

  public static function r404() {
    header('HTTP/1.0 404 Not Found');
    die('404 Not Found');
  }

}

?>
