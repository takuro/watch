<?php

require_once 'init.php';
require_once 'response.php';
require_once 'validate.php';
require_once 'database.php';
require_once 'movie.php';

class Show {

  //private $db = null;
  private $movie = null;

  function __construct() {
    //$this->db = new Database();
    $this->movie = new Movie();
  }

  public function play($movie_id) {
    $movie_id = Validate::movies_id($movie_id);

    if ($movie_id === false) {
      Response::r403();
    }

    $movie = $this->movie->find_by_id($movie_id);
    $public_file_path = basename(CACHE).'/'.$movie['id'].'.'.$movie['ext'];
    //shell_exec('cp '.$movie['path'].' '.$public_file_path);
    //d('ffmpeg -i '.$movie['path'].' -vcodec libx264 '.$public_file_path);

    return $public_file_path;
  }

}

?>
