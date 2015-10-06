angular.module("jonphoto").directive("imageGrid", ["$location", "$stateParams", function($location, $stateParams){
	return {
		templateUrl: "/wp-content/themes/jonbeechphotography/app/sections/gallery/image-grid/image-grid.html",
		restrict: "AE",
		scope: {
			imageData: "="
		},
		link: function ($scope, $element, $attrs) {

			// 20 pixels between images on a width of 1920
			var gap				= 20;
			$scope.gapRatio		= gap / 1920;
			$scope.gapPercent	= $scope.gapRatio * 100;

			$scope.$watch("imageData", function(newValue, oldValue){
				var rows = [];
				if ($scope.imageData){
					var fullSet = $scope.imageData.concat();
					while(fullSet.length > 0){
						rows.push(processRow(fullSet));
					}
				}
				$scope.templateRows	= rows;
			});

			$scope.loadImage = function(id){
				var newPath		= "/viewer/" + $stateParams.category + "/" + id;
				console.log("Setting new path to: ", newPath);
				$location.path(newPath);
			};

			function processRow(fullSet){
				var images		= [];
				var i			= 3;
				var cnt			= 0;
				while (fullSet.length > 0 && cnt < i){
					images.push(fullSet.shift());
					cnt++;
				}


				// find min height
				var minHeight	= 999999999999999;
				angular.forEach(images, function(image, i) {
					minHeight	= Math.min(minHeight, image.meta.width);
				});

				// Convert all sizes to the same height, get full width
				var fullWidth	= 0;
				angular.forEach(images, function(image, i) {
					var ratio		= minHeight / image.meta.height;
					image.height	= image.meta.height * ratio;
					image.width		= image.meta.width * ratio;
					fullWidth		+= image.width;
				});

				// Add the gap needed
				var gapSize		= fullWidth * $scope.gapRatio;
				fullWidth		+= (images.length - 1) * gapSize;
				var percentLeft	= 100;

				// Setup the width percentages to use
				angular.forEach(images, function(image, i) {
					image.widthPercent	= image.width / fullWidth * 100;
					percentLeft			-= image.widthPercent;
				});


				// Add dividers based on the size that's left
				var newImages	= [];
				var gapPercent	= percentLeft / (images.length - 1);

				angular.forEach(images, function(image, i) {
					newImages.push(image);
					// Add divider
					if (i < images.length - 1){
						newImages.push({
							type:"divider",
							widthPercent:gapPercent
						})
					}
				});

				var total = 0;
				angular.forEach(newImages, function(image, i) {
					total += image.widthPercent;
				});
				//console.log(total);

				return newImages;
			}
		}
	}
}]);

/*

private function generateThumbMemoryData($pageWidths, $magicHeight, $gap){
		$pageWidth		= $pageWidths[0];

		// Get the source images
		$imageResult	= $this->db->get('source_image');
		$imageList		= $imageResult->result();

		log_message("info", "Generating thumbs | \$width: $pageWidth, \$gap: $gap, \$magicHeight: $magicHeight");

		// Shuffle the list into portrait and landscape alternating
		log_message("info", "Shuffling");
		$landscape	= array();
		$portrait	= array();
		foreach ($imageList as $imgObj){
			if ($imgObj->width > $imgObj->height){
				$landscape[] = $imgObj;
			}
			else{
				$portrait[] = $imgObj;
			}
		}

		$imageList = array();
		while (count($landscape) > 0 || count($portrait) > 0){
			if (count($landscape) > 0) $imageList[] = array_shift($landscape);
			if (count($portrait) > 0) $imageList[] = array_shift($portrait);
		}

		// Define rows with the logical distribution
		// Some may be over some under the width
		log_message("info", "Breaking into rows");
		$rows			= array();
		$cnt			= 0;
		$max			= count($imageList) * 2;
		$totalImages	= 0;
		while(count($imageList) > 0){
			$row 				= new stdClass();
			$row->images		= array();
			$row->calcWidth		= 0;
			$row->incomplete	= false;

			while(true){
				if (count($imageList) == 0){
					$row->incomplete = true;
					break;
				}
				$imgObj		= $imageList[0];

				// Set the image to use a predefined start height
				$imgObj->width	= $imgObj->width * ($magicHeight / $imgObj->height);
				$imgObj->height	= $magicHeight;

				// If the gap left to fill is greater than the half the image width
				// Add the current image
				$totalGap	= $gap * count($row->images);
				if ($imgObj->width / 2 < ($pageWidth - $totalGap) - $row->calcWidth){
					$totalImages++;
					array_shift($imageList);
					$row->images[]	= $imgObj;
					$row->calcWidth	+= $imgObj->width;
				}
				else{
					if (count($row->images) == 0) throw new Exception("Failed to add even 1 image :(");
					break;
				}
				if ($row->calcWidth >= $pageWidth){
					break;
				}

				$cnt++;
				if ($cnt > $max) throw new Exception("Too much looping");
			}

			if (!$row->incomplete) $rows[] = $row;
		}

		// Fix the image widths
		log_message("info", "Fixing widths");
		foreach ($rows as $row){
			$totalGap	= $gap * (count($row->images) - 1);
			$n 			= ($pageWidth - $totalGap) / $row->calcWidth;
			foreach ($row->images as $imgObj){
				$imgObj->width	= $imgObj->width * $n;
				$imgObj->height	= $imgObj->height * $n;
			}
		}

		$magicRatio		= $magicHeight / $pageWidth;

		// Lets create an image set for every page size
		for ($r = 0; $r < count($rows); $r++) {
			$row				= $rows[$r];

			// Move the current width into its own set
			$row->$pageWidth	= $row->images;

			// Now run a loop for each other width
			for ($w = 1; $w < count($pageWidths); $w++){
				$currPageWidth		= $pageWidths[$w];
				$currArr			= array();
				$currHeight			= $currPageWidth * $magicRatio;

				// Now run through the image list an generate new rows for this size
				for ($i = 0; $i < count($row->images); $i++) {
					$imgObj 		= $row->images[$i];
					$newImg			= new stdClass();
					$newImg->id		= $imgObj->id;
					$newImg->url	= $imgObj->url;
					// Set the height then scale the width proportionally
					$newImg->height	= $currHeight;
					$newImg->width	= $imgObj->width * ($currHeight / $imgObj->height);
					$currArr[]		= $newImg;
				}

				// Assign to its own property
				$row->$currPageWidth	= $currArr;
			}
		}

		return $rows;
	}
*/