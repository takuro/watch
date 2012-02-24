<!DOCTYPE html>
<html lang='ja'>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
  require_once 'php/init.php';
  require_once 'php/passport.php';
  require_once 'php/movie.php';
  require_once 'php/show.php';
  $movie = new Movie();
  $movie->refresh();
?>
  <title><?php echo SITE_NAME; ?></title>
	<link href="styles/bootstrap.min.css" rel="stylesheet" />
	<link href="styles/bootstrap-responsive.min.css" rel="stylesheet" />
  <link href="vendor/MediaElement/build/mediaelementplayer.min.css" rel="stylesheet" />
	<link href="styles/user.css" rel="stylesheet" />
</head>
<body>
  <div class="container">

    <header id="page-header">
      <h1><?php echo SITE_NAME; ?></h1>
    </header>

    <section id="movies" class="row">
    <?php
      $count = 1;
      foreach ($movie->find_all() as $m) {
        echo '<div class="span2 movie">';
        echo '  <a class="movie-action" data-movie-id="'.h($m['id']).'"';
        echo '     data-movie-title="'.h($m['title']).' '.h($m['part_number']).'"';
        echo '     id="movie-'.h($m['id']).'">';
        echo      h($m['title']).' '.h($m['part_number']);
        echo '  </a>';
        echo '</div>';
      }
    ?>
    </section>

    <section id="movie-package">
      <div id="screen">
      <video width="<?php echo MOVIE_WIDTH; ?>" height="<?php echo MOVIE_HEIGHT; ?>" controls autobuffer preload="none">
          <source id="for-mp4-screen" type="video/mp4" src="" />
          <track kind="subtitles" src="" srclang="en" />
          <track kind="chapters" src="" srclang="en" /> 
          <object width="<?php echo MOVIE_WIDTH; ?>" height="<?php echo MOVIE_HEIGHT; ?>"
                  type="application/x-shockwave-flash" data="vendor/MediaElement/build/flashmediaelement.swf">
              <param name="movie" value="vendor/MediaElement/build/flashmediaelement.swf" />
              <param name="flashvars" value="controls=true&file=" />
          </object>
        </video>
      </div>
      <nav>
        <a href="index.php" id="return">Return</a>
      </nav>
    </section>

    <section class="modal fade" id="movie-info" data-movie-id="">
      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Movie Info</h3>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-condensed">
          <tr>
            <th>Title</th>
            <td id="movie-title" class="edittable modal-movie-title" data-movie-title=""></td>
          </tr>
          <tr>
            <th>Genre</th>
            <td id="movie-genre" class="edittable modal-movie-genre" data-movie-genre=""></td>
          </tr>
          <tr>
            <th>Series</th>
            <td id="movie-series" class="edittable modal-movie-series" data-movie-series=""></td>
          </tr>
          <tr>
            <th>Part Number</th>
            <td id="movie-part-number" class="edittable modal-movie-part-number" data-movie-part-number=""></td>
          </tr>
          <tr>
            <th>Year</th>
            <td id="movie-year" class="edittable modal-movie-year" data-movie-year=""></td>
          </tr>
          <tr>
            <th>Description</th>
            <td id="movie-description" class="edittable-textarea"></td>
          </tr>
        </table>
        <div id="view">
          <a class="btn btn-primary" id="watch-movie" data-dismiss="modal">Watch</a>
        </div>
      </div>
      <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
      </div>
    </section>
    
    <footer id="page-footer">
      <a href="/index.php?session=logout">Logout</a>
      <?php echo '&copy; '.date('Y').' '.SITE_NAME; ?>
    </footer>

  </div>
	
	<!-- JavaScript Area -->
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="vendor/MediaElement/build/mediaelement-and-player.min.js"></script>
	<script src="js/jquery.editinplace.js"></script>
	<script src="js/user.js"></script>
	<!-- JavaScript Area -->
  <!-- Do not write anything below this. -->
</body>
</html>
