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
    $this->output($movie['path']);
    return;
  }

  private function output($file_path) {

    // via. http://www.yu-ki-report.com/index.php/archives/74
    
    $file_size = filesize($file_path) ;
    header( "Accept-Ranges: bytes" ) ;

    $handle = fopen($file_path, 'rb');
    if ($handle === false) {
      return false;
    }

    if( isset( $_SERVER['HTTP_RANGE'] ) ) {
      list($toss, $range) = explode('=', $_SERVER['HTTP_RANGE']);
      list($range_start, $range_end) = explode('-', $range);
      $size = $file_size - 1;
      $length = $range_end - $range_start +1;
    
      //header('HTTP/1.1 206 Partial Content');
      header('HTTP/1.1 200 OK');
      header('Content-type: video/mp4');
      header('Content-Length: ' . $length);
      header('Content-Range: bytes ' . $range . '/' . $file_size);
      header("Etag: \"" . md5( $_SERVER["REQUEST_URI"] ) . $file_size . "\"" );
      header("Last-Modified: " . gmdate( "D, d M Y H:i:s", filemtime($file_path)) . " GMT");
      fseek($handle, $range_start);
     
    }else {
      // first request
      header('HTTP/1.1 200 OK');
      header('Content-type: video/mp4');
      header('Content-Length: ' . $file_size);
      header("Etag: \"" . md5( $_SERVER["REQUEST_URI"] ) . $file_size . "\"" );
      header("Last-Modified: " . gmdate( "D, d M Y H:i:s", filemtime($file_path)) . " GMT");
    }
          
    @ob_end_clean();
    while (!feof($handle) && connection_status() == 0 && !connection_aborted()) {
      set_time_limit(0);
      $buffer = fread($handle,8192);
      echo $buffer;
      @flush();
      @ob_flush();
    }
    fclose($handle);

    exit(0);
  }


}

?>
