<?php
  
require_once 'init.php';
require_once 'response.php';

class Database {

  private $db = null;

  function __construct() {
    $this->db = new SQLite3(DB);
  }

  public function init() {
    $this->close();
    $this->delete_db();

    $this->db = new SQLite3(DB);
    $this->create_tables();
  }

  public function close() {
    $this->db->close();
  }

  public function escape($string) {
    return $this->db->escapeString($string);
  }

  public function exec($sql) {
    return $this->db->exec($sql);
  }

  public function query($query, $with_commit=true) {
    if ($with_commit) {
      $this->db->exec("BEGIN DEFERRED;");
    }

    if (!$this->db->exec($query)) {
      $this->db->exec("ROLLBACK;");
      return false;
    }

    if ($with_commit) {
      $this->db->exec("COMMIT;");
    }

    return true;
  }

  public function select($query) {
    $results = $this->db->query($query);

    if (!$results) {
      return false;
    }

    $data = array();
    $count = 0;
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
      foreach ($row as $column => $r) {
        $data[$count][$column] = $r;
      }
      $count++;
    }

    return $data;
  }

  // -- private line --

  private function delete_db() {
    if (file_exists(DB)) {
      $cmd = 'rm -f '.DB;
      shell_exec($cmd);
    }

    return true;
  }

  private function create_tables() {
    $d = dir(MIGRATE_DIR);
    $sql_files = array();
    while (false !== ($entry = $d->read())) {
      if ($entry !== '.') {
        if ($entry !== '..') {
          $sql_files[] = $entry;
        }
      }
    }

    $this->db->exec("BEGIN DEFERRED;");

    foreach ($sql_files as $sql_file) {
      $sql = trim(file_get_contents(MIGRATE_DIR.'/'.$sql_file));

      if (!$this->query($sql, false)) {
        Response::fatal_error($sql);
      }
    }

    $this->db->exec("COMMIT;");

    return true;
  }

}
?>
