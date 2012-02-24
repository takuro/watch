$(function(){

  $('.edittable').editInPlace({
    url: "php/passport.php",
    params: "edit=movie_info",
    saving_image: "../images/ajax-loader.gif",
  });

  $('.edittable-textarea').editInPlace({
    url: "php/passport.php",
    field_type: "textarea",
    params: "edit=movie_info",
    saving_image: "../images/ajax-loader.gif"
  });

  // show movie action
  $('.movie-action').on('click', function(){
    var movie_id = $(this).attr('data-movie-id');
    var movie_title = $(this).attr('data-movie-title');
    //$('#movie-info h3').html(movie_title);

    $('#movie-info').attr('data-movie-id', movie_id);
    $('.modal-movie-title').attr('id', 'movie-title_'+movie_id);
    $('.modal-movie-genre').attr('id', 'movie-genre_'+movie_id);
    $('.modal-movie-series').attr('id', 'movie-series_'+movie_id);
    $('.modal-movie-part-number').attr('id', 'movie-part-number_'+movie_id);
    $('.modal-movie-year').attr('id', 'movie-year_'+movie_id);
    $('.modal-movie-description').attr('id', 'movie-description_'+movie_id);

    get_movie_info(movie_id);
    $('#movie-info').modal('toggle');
  });

  function set_movie_info(movie_id, data) {
    if (!data.title) { data.title = '';}
    $('#movie-title_'+movie_id).text(data.title);
    //$('#movie-title_'+movie_id).attr('data-movie-title', data.title);

    if (!data.genre) { data.genre = '';}
    $('#movie-genre_'+movie_id).text(data.genre);
    //$('#movie-genre_'+movie_id).attr('data-movie-genre', data.genre);

    if (!data.series) { data.series = '';}
    $('#movie-series_'+movie_id).text(data.series);
    //$('#movie-series_'+movie_id).attr('data-movie-series', data.series);

    if (!data.part_number) { data.part_number = '';}
    $('#movie-part-number_'+movie_id).text(data.part_number);
    //$('#movie-part-number_'+movie_id).attr('data-movie-part-number', data.part_number);

    if (!data.year) { data.year = '';}
    $('#movie-year_'+movie_id).text(data.year);
    //$('#movie-year_'+movie_id).attr('data-movie-year', data.year);

    if (!data.description) { data.description = '';}
    $('#movie-description_'+movie_id).text(data.description);
  }

  $('#movie-info').on('hidden', function(){
    var movie_id = $('#movie-info').attr('data-movie-id');
    //set_movie_info_to_file(movie_id);
  });

  function get_movie(movie_id) {
    $('#for-mp4-screen').attr('src', 'php/passport.php?session=watchmovie&id=' + movie_id);
    $('#flashvars').attr('value', 'controls=true&file=php/passport.php?session=watchmovie&id=' + movie_id);
    $('#screen video').mediaelementplayer();
  }

  // show screen
  $('#watch-movie').on('click', function(){
    var movie_id = $('#movie-info').attr('data-movie-id');
    get_movie(movie_id);
    $('#movies').hide('fast');
    $('#movie-package').show('fast');
  });

  // hide screen
  $('#return').on('click', function(){
    $('video').each(function(){$(this)[0].player.pause();});
    //$('#watch-movie').off('click');
    //mejs.players[0].setSrc('a');

    //$('#movie-package').hide('fast');
    //$('#movies').show('fast');
  });

  // communicate with server 

  function get_movie_info(movie_id) {
    $.ajax({ url: 'php/passport.php', data: 'session=get_movie_info&id=' + movie_id,
      success: function(data){
        set_movie_info(movie_id, data);
      }
    });
  }

  function set_movie_info_to_file(movie_id) {
    $.ajax({ url: 'php/passport.php', data: 'session=set_meta_data_to_file&id=' + movie_id,
      success: function(data){ }
    });
  }


});
