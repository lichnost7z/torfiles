jQuery(function () {

	
	$('#up').click(function() {  
    $('body,html').animate({scrollTop:0},500);  
    return false;  
  	});

  	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('#up').fadeIn();
		} else {
			$('#up').fadeOut();
		}
	});

  $("a.thumbnail").click(function (e) {
    e.preventDefault();
  });

	/*$('img#mini').hover(function () {
		$(this).css("z-index", 1);
            $(this).animate({
               height   : "250",
               width    : "250",
               left    : "-=50",
               top     : "-=50",
            }, 1000);
	},
	function () {
		$(this).css("z-index", 0);
            $(this).animate({
               height   : "150",
               width    : "150",
               left     : "+=50",
               top      : "+=50"
            }, 1000);
	});*/

  $("#search").on('click', function () {

    $("#search").animate({
      width: "500"}, 1000, function() {
        var search = "<form class='form-search'><input type='text' class='input-medium search-query'><button type='submit' class='btn'>Поиск</button></form>";
      $(".icon-search").replaceWith(search);
    });

  });
  $("#search").on('focusout', '.form-search', function(event) {

    var li = "<i class='icon-search'></i>";
    $("#search").animate({width:"14"},500,
      function () {
        $(".form-search").replaceWith(li);
      });

  });

});