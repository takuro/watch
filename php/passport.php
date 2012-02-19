<?php

require_once 'init.php';
require_once 'response.php';
require_once 'user.php';
require_once 'movie.php';
require_once 'session.php';
require_once 'show.php';

$response = new Response();
$session = new Session();
$self = $_SERVER['PHP_SELF'];

if (!empty($_GET['session'])){

  if ($_GET['session'] === 'logout') {
    // log out
    $session->logout();
    $response->call('go_to_login_form');
  } else if ($_GET['session'] === 'watchmovie') {
    // show movie
    $show = new Show();
    $response->call('json', $show->play($_GET['id']));
    return;
  } else if ($_GET['session'] === 'get_movie_info') {
    $movie = new Movie();
    $movie->get_meta_data($_GET['id']);
  } else {
    $response->call('go_to_login_form');
  }

} else if (!empty($_POST['login'])) {

  // from login form
  $user = new User();
  $user->login($_POST);

} else if (!empty($_POST['signup'])) {

  // from sign up form
  $user = new User();
  $user->signup($_POST);

} else if (!empty($_POST['edit'])) {

  if ($_POST['edit'] === 'movie_info') {
    // edit movie information
    $movie = new Movie();
    $update_value = $movie->set_meta_data($_POST);
    echo $update_value;
  }


} else if ($session->has_session()) {

  // This user authenticated.
  if ($self === '/index.php' || $self === '/') {
    // from index
  } else {
    $response->call('go_to_index');
  }

} else if ($self === '/login.php') {

  // from login page
} else {

  // undefined
  $response->call('go_to_login_form');

}

return;

?>