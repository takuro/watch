<?php

class Validate {

  function __construct() {
  }

  public static function uri($uri) {
    // empty check
    if (empty($uri)) { return null; }

    // cleanup
    $uri = trim($uri);

    // is uri?
    $check = filter_var($uri, FILTER_VALIDATE_URL,
                              FILTER_FLAG_SCHEME_REQUIRED,
                              FILTER_FLAG_PATH_REQUIRED);

    if ($check === false) { return null; }

    return $uri;
  }

  public static function login_data($data) {
    // empty check
    if (empty($data)) { return false; }
    if (empty($data['uname'])) { return false; }
    if (empty($data['password'])) { return false; }

    return true;
  }

  public static function signup_data($data) {
    // empty check
    if (empty($data)) { return false; }
    if (empty($data['new-uname'])) { return false; }
    if (empty($data['new-password'])) { return false; }

    return true;
  }

  public static function movies_id($movies_id) {
    // empty check
    if (empty($movies_id)) { return false; }

    // cleanup
    $movies_id = trim($movies_id);
    $movies_id = intval($movies_id);

    if ($movies_id < 1) { return false; }

    return $movies_id;
  }

  public static function meta_data($data) {
    // empty check
    if (empty($data['element_id'])) { return false; }
    if (empty($data['update_value'])) { return false; }

    // cleanup
    $element_id = trim($data['element_id']);
    $update_value = trim($data['update_value']);

    $element = explode('_', $element_id);

    $row = null;
    switch($element[0]) {
    case 'movie-title' : $row = 'title'; break;
    case 'movie-genre' : $row = 'genre'; break;
    case 'movie-series' : $row = 'series'; break;
    case 'movie-part-number' : $row = 'part_number'; break;
    case 'movie-year' : $row = 'year'; break;
    case 'movie-description' : $row = 'description'; break;
    default: return false;
    }

    $meta_data = array(
      'id' => intval($element[1]),
      'row' => $row,
      'update_value' => $update_value
    );

    return $meta_data;

  }

}

?>
