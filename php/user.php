<?php

require_once 'init.php';
require_once 'response.php';
require_once 'validate.php';
require_once 'session.php';
require_once 'database.php';

class User {

  private $session = null;
  private $db = null;

  function __construct() {
    $this->session = new Session();
    $this->db = new Database();
  }

  public function signup($data) {
    if (!Validate::signup_data($data)) {
      Response::go_to_login_form();
    }

    // add user
    $salt = $this->create_salt();
    $password = $this->hashed($data['new-password'], $salt);
    $date = "datetime('now','localtime')";

    $sql = sprintf("INSERT INTO users (uname, password, salt, created_at) VALUES ('%s','%s','%s',".$date.")",
                    $this->db->escape($data['new-uname']), $password, $salt);
    $r = $this->db->query($sql);

    if ($r) {
      $r = $this->find_by_uname($data['new-uname']);
      $id = $r[0]['id'];
      $this->session->authed($id);
      return true;
    } else {
      Response::fatal_error("can't add user");
      return false;
    }
  }

  public function login($data) {

    if (!Validate::login_data($data)) {
      Response::go_to_login_form();
    }

    $r = $this->find_by_uname($data['uname']);
    if (!$r) {
      Response::go_to_login_form();
      return false;
    }

    $id = $r[0]['id'];
    $salt = $r[0]['salt'];
    $password = $r[0]['password'];
    $input_password = $this->hashed($data['password'], $salt);
    if (strcmp($password, $input_password) === 0) {
      $this->session->authed($id);
      return true;
    } else {
      Response::go_to_login_form();
      return false;
    }
  }

  public function find_by_uname($uname) {
    $sql = sprintf("SELECT * from users WHERE uname = '%s';", $this->db->escape($uname));
    return $this->db->select($sql);
  }

  public function find_by_id($id) {
    $sql = sprintf("SELECT * from users WHERE id=%d;", intval($id));
    $r = $this->db->select($sql);
    return $r[0];
  }

  public function now() {
    $session = $this->session->find_by_session_id($_COOKIE[SESSION_COOKIE]);
    return $this->find_by_id($session[0]['users_id']);
  }


  // -- private line --

  private function create_salt() {
    return 's_'.mt_rand().microtime().'_e';
  }

  private function hashed($password, $salt) {
    $to_hash = $password.$salt.BIG_SALT;
    return hash('sha512', $to_hash);
  }

}

?>
