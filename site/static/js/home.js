$(function(){
	function renderImages(imageList){
		// Reorder for better layout
		imageList	= shuffle(imageList);

		// Define rows with the logical distribution
		// Some may be over some under the width
		var width		= 1200;
		var rows		= [];
		while(imageList.length > 0){
			var row			= {images:[], calcWidth:0};
			while(true){
				if (imageList.length == 0){
					row.incomplete = true;
					break;
				}
				var imgObj	= imageList[0];
				// Set the image to use a predefined start height
				imgObj		= sizeHeight(300, imgObj);
				// If the gap left to fill is greater than the half the image width
				// Add the current image
				if (imgObj.width / 2 < width - row.calcWidth){
					imageList.shift();
					row.images.push(imgObj);
					row.calcWidth += imgObj.width;
				}
				else{
					if (row.images.length == 0) throw new Error("Failed to add even 1 image :(");
					break
				}
				if (row.calcWidth >= width){
					break
				}
			}
			if (!row.incomplete) rows.push(row);
		}

		// Now we run the fancy algorithm to get the multiplier for the image widths so they fit
		/*
		Original formula, 3 images
		an + bn + cn = d
		a, b, c are the 3 image widths n is the scale and unknown
		Resolved for n it is:
		n = d/(a + b + c)
		*/
		_.each(rows, function(row){
			var n = width / row.calcWidth;
			_.each(row.images, function(imgObj){
				imgObj.width	= imgObj.width * n;
				imgObj.height	= imgObj.height * n;
			});
		});

		// Lets just layout the rows so we can get a UI view
		var x = 0;
		var y = 0;
		var $imageTile	= $("#imageTile");
		_.each(rows, function(row){
			for (var i = 0; i < row.images.length; i++){
				var imgObj 	= row.images[i];
				imgObj.width	= Math.floor(imgObj.width);
				imgObj.height	= Math.floor(imgObj.height);

				var $img 	= $("<img />")
					.attr({src:imgObj.url, width:imgObj.width, height:imgObj.height})
					.css({top:y, left:x});
				$imageTile.append($img);
				if (i == row.images.length - 1){
					x = 0;
					y += imgObj.height;
				}
				else{
					x += imgObj.width;
				}
			}
		});


		/*
		Original formula
		adn + bdn + cdn = d
		a, b, c are the 3 image widths n is the scale and unknown
		Resolved for n it is:
		n = d/(ad + bd + cd)
		*/
		/*
		var d 			= 800;
		var $imageTile	= $("#imageTile");

		while(imageList.length > 0){
			var size 	= Math.min(3, imageList.length);
			var $row	= $('<div class="imageRow"></div>');

			for (var i = 0; i < size; i++){
				var imgObj		= imageList.shift();
				var imgTag		= {src:imgObj.url, height:300};
				imgTag.width	= imgObj.width * (imgTag.height / imgObj.height);
				$row.append(buildTag('<img', imgTag, '/>'));
			}

			$imageTile.append($row);
			console.log("working ")
		}
		*/
	}

	function buildTag(prefix, props, suffix){
		var str = prefix + " ";
		for (var name in props){
			str += name + '="' + props[name] + '" '
		}
		str += suffix;
		return str;
	}

	/**
	 * reorder images into 1 landscape 1 portrait
	 */
	function shuffle(imageList){
		var landscape	= [];
		var portrait	= [];
		_.each(imageList, function(img){
			if (img.width > img.height){
				landscape.push(img);
			}
			else{
				portrait.push(img);
			}
		});
		imageList = [];
		while (landscape.length > 0 || portrait.length > 0){
			if (landscape.length > 0) imageList.push(landscape.shift());
			if (portrait.length > 0) imageList.push(portrait.shift());
		}
		return imageList;
	}

	function sizeHeight(magicHeight, imgObj){
		imgObj.width	= imgObj.originalWidth * (magicHeight / imgObj.originalHeight);
		imgObj.height	= magicHeight;
		return imgObj
	}
	function sizeWidth(magicWidth, imgObj){
		imgObj.height	= imgObj.originalHeight * (magicWidth / imgObj.originalWidth);
		imgObj.width	= magicWidth;
		return imgObj
	}

	renderImages(images);
});