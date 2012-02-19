$(function(){

  $('.edittable').editInPlace({
    url: "php/passport.php?test=test",
    params: "edit=movie_info",
    saving_image: "../images/ajax-loader.gif"
  });

  $('.edittable-textarea').editInPlace({
    url: "php/passport.php",
    field_type: "textarea",
    params: "edit=movie_info",
    saving_image: "../images/ajax-loader.gif"
  });

  // show screen
  $('.movie-action').on('click', function(){
    var movie_id = $(this).attr('data-movie-id');
    var movie_title = $(this).attr('data-movie-title');
    $('#movie-info h3').html(movie_title);

    $('#movie-title').attr('id', 'movie-title_'+movie_id);
    $('#movie-genre').attr('id', 'movie-genre_'+movie_id);
    $('#movie-series').attr('id', 'movie-series_'+movie_id);
    $('#movie-part-number').attr('id', 'movie-part-number_'+movie_id);
    $('#movie-year').attr('id', 'movie-year_'+movie_id);
    $('#movie-description').attr('id', 'movie-description_'+movie_id);
    get_movie_info(movie_id);

    $('#movie-info').modal('toggle');
    get_movie(movie_id);
  });

  $('#watch-movie').on('click', function(){
    $('#movies').hide('fast');
    $('#movie-package').show('fast');
  });

  function set_movie_info(movie_id, data) {
    $('#movie-title_'+movie_id).text(data.title);
    $('#movie-genre_'+movie_id).text(data.genre);
    $('#movie-series_'+movie_id).text(data.series);
    $('#movie-part-number_'+movie_id).text(data.part_number);
    $('#movie-year_'+movie_id).text(data.year);
    $('#movie-description_'+movie_id).text(data.description);
  }

  // communicate with server 

  function get_movie(movie_id) {
    $.ajax({ url: 'php/passport.php', data: 'session=watchmovie&id=' + movie_id,
      success: function(file_name){
        $('#for-mp4-screen').attr('src', 'http://10.0.0.9:8000/'+file_name);
        $('video,audio').mediaelementplayer(/* Options */);
      }
    });
  }

  function get_movie_info(movie_id) {
    $.ajax({ url: 'php/passport.php', data: 'session=get_movie_info&id=' + movie_id,
      success: function(data){
        set_movie_info(movie_id, data);
      }
    });
  }


});
