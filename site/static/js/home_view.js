define(["./ImageViewer"], function(ImageViewer){
	$(function(){
		var $imageTile	= $("#imageTile");
		var currentLoad	= null;
		var imgCache	= {};
		var currentSize	= null;
		var viewer		= new ImageViewer();

		function layoutImages(imageList){
			console.log("Laying out images");
			$imageTile.html("");
			_.each(imageList, function(row){
				for (var i = 0; i < row.length; i++){
					var imgObj		= row[i];
					var $wrapper	= $('<div class="imgHolder"></div>');
					if (i > 0) $wrapper.addClass("middleImg");

					$wrapper.css({
						width				: imgObj.width,
						height				: imgObj.height,
						"background-image"	: "url('" + imgObj.src + "')"
					});
					$wrapper.append("<a href='#' data-id='' class='imgLink animated1s fadeInUp'></a>");
					$imageTile.append($wrapper);
				}
			});
		}

		function loadImages(size){
			if (currentSize == size) return;
			currentSize = size;

			console.log("Loading new image set: ", size);
			if (currentLoad != null) currentLoad.abort();

			$imageTile.html("Loading...");

			if (imgCache[size] != null){
				layoutImages(imgCache[size]);
			}
			else{
				currentLoad = $.ajax({
					url		: "/home/fetchThumbs",
					data	: {size:size},
					success	: function(json){
						currentLoad 	= null;
						imgCache[size] 	= json;
						layoutImages(json);
					}
				});
			}
		}

		$("a.imgLink").click(function(e){
			e.preventDefault();
		});



		// Check the sizes on resize
		// To adhere you must be greater than min and (less than or equal to max)
		// These sizes are also in the css
		var $window	= $(window);
		var sizes 	= [
			{min:1800},
			{min:1200, max:1800},
			{min:979, max:1200},
			{min:767, max:979},
			{min:480, max:767},
			{min:320, max:480},
			{max:320}
		];
		$window.resize(checkSize);

		function checkSize(){
			var width = $window.width();
			for (var i = 0; i < sizes.length; i++){
				var sizeObj = sizes[i];
				var first	= i == 0;
				var last	= i == sizes.length - 1;

				if (first && width > sizeObj.min){
					loadImages(sizeObj.min);
					break;
				}
				else if (last && width <= sizeObj.max){
					loadImages(300);
					break;
				}
				else if (width > sizeObj.min && width <= sizeObj.max){
					loadImages(sizeObj.min);
					break;
				}
			}
		}

		checkSize();

	});
});