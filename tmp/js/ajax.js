jQuery(function () {

	$(".dropdown-menu li a").on('click', function (e) {


		e.preventDefault();
		var href = $(this).attr('href');

		$.ajax({

			type: 'GET',
			url: './ajax/ajaxCenter.php',
			dataType: 'html',
			data: 'page=1&cat='+href+'&count=0&sort=0',
			success: function (data) {
				$(".content").replaceWith(data);
			}

		});

	});

	$("body").on('click', 'div.pagination ul li a', function (e) {

		e.preventDefault();
		var href = $(this).attr('href');
		var arr = href.split('/');
		var count = $("select.count option:selected").val();
		count = count.split('|');
		var sort = $("select.sort option:selected").val();

		$.ajax({

			type: 'GET',
			url: './ajax/ajaxCenter.php',
			datatype: 'html',
			data: 'page='+arr[1]+'&cat='+arr[0]+'&count='+count[1]+'&sort='+sort,
			success: function (data) {
				$(".span7 .content").replaceWith(data);	
			}
		});

	});


	$("body").on('click', 'form.form-inline button', function (e) {

		e.preventDefault();
		var count = $("select.count option:selected").val();
		count = count.split('|');
		var sort = $("select.sort option:selected").val();

		$.ajax({

			type: 'GET',
			url: './ajax/ajaxCenter.php',
			datatype: 'html',
			data: 'page=1&cat='+count[0]+'&count='+count[1]+'&sort='+sort,
			success: function (data) {
				$(".span7 .content").replaceWith(data);
			}

		});

	});


});