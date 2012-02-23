<?php

require_once 'init.php';
require_once 'response.php';
require_once 'validate.php';
require_once 'database.php';
require_once APP_ROOT.'/vendor/getdirtree/get_dir_tree.php';

class Movie {

  private $db = null;
  private $movies_dir = null;

  function __construct() {
    $this->db = new Database();
    $this->movies_dir = get_movies_dir();
  }

  public function refresh() {
    $movie_path = $this->get_exists_movies_path();

    $columns = 'path,ext,title,is_exist,created_at';
    foreach ($movie_path as $movie) {
      if (!$this->find_by_path($movie)) {
        $path = pathinfo($movie);
        $title = $path['basename'];
        $ext = $path['extension'];
        $date = "datetime('now','localtime')";
        $sql  = "INSERT INTO movies (".$columns.") VALUES";
        $sql .= "('".$movie."','".$ext."','".$title."',1,".$date.");";
        $this->db->query($sql);
      }
    }

    $this->check_movie_exists();

    return true;
  }

  public function find_by_path($path) {
    $sql = "SELECT * from movies WHERE path = '".$this->db->escape($path)."';";
    $r = $this->db->select($sql);
    return $r[0];
  }

  public function find_by_id($id) {
    $sql = "SELECT * from movies WHERE id = ".intval($id).";";
    $r = $this->db->select($sql);
    return $r[0];
  }

  public function find_all($exist=true) {
    if ($exist) {
      $sql = "SELECT * from movies WHERE is_exist=1;";
    } else {
      $sql = "SELECT * from movies;";
    }
    return $this->db->select($sql);
  }

  public function set_meta_data($data) {
    $meta_data = Validate::meta_data($data);
    $sql  = "UPDATE movies SET ".$meta_data['row'];
    $sql .= "='".$this->db->escape($meta_data['update_value'])."' ";
    $sql .= "WHERE id=".$meta_data['id'].';';
    $r = $this->db->query($sql, false);
    if (!$r) { Response::r403(); }
    return $meta_data['update_value'];
  }

  public function get_meta_data($movies_id) {
    $movies_id = Validate::movies_id($movies_id);
    if (!$movies_id) { Response::r403(); }

    $sql  = "SELECT title,genre,series,part_number,year,description ";
    $sql .= "FROM movies WHERE id=".$movies_id.";";
    $r = $this->db->select($sql);
    Response::json($r[0]);
    return;
  }

  public function set_meta_data_to_file($movies_id) {
    $movies_id = Validate::movies_id($movies_id);
    if (!$movies_id) { Response::r403(); }

    //$movie = $this->find_by_id($movies_id);
    //$this->set_meta_data_use_ffmpeg_cmd($movie);
    return true;
  }

  // -- private line --

  private function check_movie_exists() {
    $movies = $this->find_all(false);

    if (empty($movies)) {
      return true;
    }

    $this->db->exec("BEGIN DEFERRED;");
    foreach ($movies as $movie) {
      $sql = '';
      if (file_exists($movie['path'])) {
        $sql .= "UPDATE movies SET is_exist=1 WHERE id=".$movie['id'];
      } else {
        $sql .= "UPDATE movies SET is_exist=0 WHERE id=".$movie['id'];
      }
      $r = $this->db->query($sql, false);
      if (!$r) {
        Response::fatal_error($sql);
      }
    }
    $this->db->exec("COMMIT;");

    return true;
  }

  private function get_exists_movies_path() {
    $movie_path = array();
    $dir_tree = new DirTree();

    foreach ($this->movies_dir as $movies_dir) {
      $dir_tree->set_root($movies_dir);
      $tmp_movie_path = $dir_tree->get();
      foreach ($tmp_movie_path as $path) {
        $movie_path[] = $path;
      }
    }

    return $movie_path;
  }

  private function set_meta_data_use_ffmpeg_cmd($d) {
    $cmd  = 'ffmpeg -y -i "'.$d['path'].'" ';
    $cmd .= '-metadata title="'.$d['title'].'" ';
    $cmd .= '-metadata comment="'.$d['description'].'" ';
    $cmd .= '-metadata description="'.$d['description'].'" ';
    $cmd .= '-metadata album="'.$d['series'].'" ';
    $cmd .= '-metadata year="'.$d['year'].'" ';
    $cmd .= '-metadata track="'.$d['part_number'].'" ';
    $cmd .= '-metadata part_number="'.$d['part_number'].'" ';
    $cmd .= '-metadata genre="'.$d['genre'].'" ';
    $cmd .= '-metadata date_written="'.date('r').'" ';
    shell_exec($cmd);
    return true;
  }

}
?>
