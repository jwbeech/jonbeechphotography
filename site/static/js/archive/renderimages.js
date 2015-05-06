$(function(){
	function renderImages(imageList){
		// Reorder for better layout
		imageList		= shuffle(imageList);

		console.log("Running render");

		// Define rows with the logical distribution
		// Some may be over some under the width
		var width		= 1800;
		var rows		= [];
		var gap			= 10;
		var magicHeight	= 400;

		while(imageList.length > 0){
			console.log("Outer row loop");

			var row			= {images:[], calcWidth:0};
			while(true){
				if (imageList.length == 0){
					row.incomplete = true;
					break;
				}
				var imgObj		= imageList[0];
				// Set the image to use a predefined start height
				imgObj			= sizeHeight(magicHeight, imgObj);
				// If the gap left to fill is greater than the half the image width
				// Add the current image
				var totalGap	= gap * row.images.length;
				if (imgObj.width / 2 < (width - totalGap) - row.calcWidth){
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
			var totalGap	= gap * (row.images.length - 1);
			var n 			= (width - totalGap) / row.calcWidth;
			_.each(row.images, function(imgObj){
				imgObj.width	= imgObj.width * n;
				imgObj.height	= imgObj.height * n;
			});
		});

		console.log("Calculations complete, rendering");

		// Lets just layout the rows so we can get a UI view
		var x			= gap;
		var y 			= gap;
		var $imageTile	= $("#imageTile");
		_.each(rows, function(row){
			for (var i = 0; i < row.images.length; i++){
				var imgObj 		= row.images[i];
				imgObj.width	= Math.floor(imgObj.width);
				imgObj.height	= Math.floor(imgObj.height);

				var $holder		= $('<div class="imgHolder"></div>');
				if (i > 0) $holder.addClass("spaceLeft10");
				var $img 		= $("<img />").attr({src:imgObj.url, width:imgObj.width, height:imgObj.height});
				$holder.append($img);
				$imageTile.append($holder);

				if (i == row.images.length - 1){
					x = gap;
					y += imgObj.height + gap;
				}
				else{
					x += imgObj.width + gap;
				}
			}
		});
		console.log("Render complete");
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