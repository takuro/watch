$(function(){

  var player = new MediaElementPlayer('video,audio');

  $('.edittable').editInPlace({
    url: "php/passport.php?test=test",
    params: "edit=movie_info",
    saving_image: "../images/ajax-loader.gif",
    success : function() {
      var movie_id = $('#movie-info').attr('data-movie-id', movie_id);
      var title = $('.modal-movie-title').text();
      var part_number = $('.modal-movie-part-number').text();
      $('#movie-info h3').html(title + ' ' + part_number);
      $('#movie-' + movie_id).html(title);
    }
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
    $('#movie-info h3').html(movie_title);

    $('#movie-info').attr('data-movie-id', movie_id);
    $('.modal-movie-title').attr('id', 'movie-title_'+movie_id);
    $('.modal-movie-genre').attr('id', 'movie-genre_'+movie_id);
    $('.modal-movie-series').attr('id', 'movie-series_'+movie_id);
    $('.modal-movie-part-number').attr('id', 'movie-part-number_'+movie_id);
    $('.modal-movie-year').attr('id', 'movie-year_'+movie_id);
    $('.modal-movie-description').attr('id', 'movie-description_'+movie_id);
    get_movie_info(movie_id);

    $('#movie-info').modal('toggle');
    get_movie(movie_id);
  });

  function get_movie(movie_id) {
    //$('#for-mp4-screen').attr('src', 'php/passport.php?session=watchmovie&id=' + movie_id);
    player.setSrc('php/passport.php?session=watchmovie&id=' + movie_id);
  }

  $('#watch-movie').on('click', function(){
    $('#movies').hide('fast');
    $('#movie-package').show('fast');
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

  // hide screen
  $('#return').on('click', function(){
    player.pause();
    $('#movie-package').hide('fast');
    $('#movies').show('fast');
  });

  $('#movie-info').on('hidden', function(){
    var movie_id = $('#movie-info').attr('data-movie-id');
    //set_movie_info_to_file(movie_id);
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
