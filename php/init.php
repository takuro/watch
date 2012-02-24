<?php

  // root directory
  define('APP_ROOT', get_app_root_dir());

  // locale
  define('CHARSET', 'UTF-8');
  setlocale(LC_ALL, 'ja_JP.'.CHARSET);

  /* ---------------------------------------- *
   *  Customizable values 
   * ---------------------------------------- */

  // host name
  define('HOST_NAME', '');

  // your site name
  define('SITE_NAME', 'Watch');

  // your time zone
  date_default_timezone_set('Asia/Tokyo');

  // allow new user?
  define('ALLOW_NEW_USER', true);

  // [WARNING] YOU MUST CHANGE THIS WORD.
  define('BIG_SALT', '46RP035bsX7D#sL48CM%ozsT');

  // cookie name for session (It's better to change this word.)
  define('SESSION_COOKIE', 'SESSION_ID');

  // session expires (day)
  define('SESSION_EXPIRE_DAY', 30);
  define('SESSION_EXPIRE', time() + 60 * 60 * 24 * SESSION_EXPIRE_DAY);

  function get_movies_dir() {

    // movies directories
    // [notice] write absolute path
    $_const_movies_dir = array(
      realpath(APP_ROOT.'/../movies'),
      realpath(APP_ROOT.'/../movies2')
    );

    return $_const_movies_dir;
  }

  // movie size (px)
  define('MOVIE_WIDTH', '720');
  define('MOVIE_HEIGHT', '480');

  // directory of database
  define('DB_DIR', APP_ROOT.'/db');

  /* ---------------------------------------- *
   *  Do not modify anything below this.
   * ---------------------------------------- */

  if (!file_exists(DB_DIR)) {
    die('Fatal error : '.DB_DIR.' is not exist.');
  }

  if (!is_writable(DB_DIR)) {
    die('Fatal error : Permission denied. ( '.DB_DIR.' )');
  }

  // directory of sql file
  define('MIGRATE_DIR', DB_DIR.'/migrate');

  // database file
  define('DB', DB_DIR.'/'.SITE_NAME.'.sqlite3');

  /* ---------------------------------------- *
   *  functions for all script
   * ---------------------------------------- */

  // for debug
  function d($obj) {
    echo '<p>';
    print_r($obj);
    echo '</p><p>';
    var_dump($obj);
    echo '</p>';
    die('dumped');
  }

  // return : app's absolute pathname
  function get_app_root_dir() {
    $path = pathinfo($_SERVER['SCRIPT_FILENAME']);
    $dirname = $path['dirname'];
    if (file_exists($dirname.'/index.php')) {
      return $dirname;
    } else {
      return realpath('../');
    }
  }

  function h($string) {
    return htmlentities($string, ENT_NOQUOTES, CHARSET);
  }

  /* ---------------------------------------- *
   *  app init
   * ---------------------------------------- */

  // database init
  require_once APP_ROOT.'/php/database.php';
  if (!file_exists(DB)) {
    $db = new Database();
    $db->init();
    $db->close();
  }

?>
