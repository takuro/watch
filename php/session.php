<?php
require_once 'init.php';
require_once 'response.php';
require_once 'database.php';
require_once 'user.php';

class Session {

  private $db = null;

  function __construct() {
    $this->db = new Database();
  }

  // add session
  public function add($users_id) {
    $session_id = $this->create_session_id();

    $sql  = 'INSERT INTO sessions (users_id, session_id) VALUES';
    $sql .= "(".$users_id.",'".$session_id."');";
    $r = $this->db->query($sql);

    if ($r) {
      return $session_id;
    } else {
      Response::fatal_error("can't add session");
      return false;
    }
  }

  public function find_by_session_id($session_id) {
    $sql = "SELECT * FROM sessions WHERE session_id = '".$this->db->escape($session_id)."';";
    return $this->db->select($sql);
  }

  public function delete_by_session_id($session_id) {
    $sql = "DELETE FROM sessions WHERE session_id = '".$this->db->escape($session_id)."';";
    return $this->db->select($sql);
  }

  public function has_session() {
    if (empty($_COOKIE[SESSION_COOKIE])) {
      return false;
    }

    $session = $this->find_by_session_id($_COOKIE[SESSION_COOKIE]);
    if ($session) {
      $user = new User();
      if ($user->find_by_id($session[0]['users_id'])) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  // set session id to cookie
  public function authed($users_id) {
    $session_id = $this->add($users_id);
    setcookie(SESSION_COOKIE, $session_id);
    Response::go_to_index();
    return true;
  }

  public function logout() {
    if (!empty($_COOKIE[SESSION_COOKIE])) {
      $this->delete_by_session_id($_COOKIE[SESSION_COOKIE]);
    }

    setcookie(SESSION_COOKIE, '');
    return true;
  }

  // -- private --

  private function create_session_id() {
    $base = 'sess_'.mt_rand().microtime().'_id';
    return hash('sha512', $base);
  }

}
?>
