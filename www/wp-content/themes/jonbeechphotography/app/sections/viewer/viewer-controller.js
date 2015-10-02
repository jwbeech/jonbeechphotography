angular.module("jonphoto").controller("ViewerController", ["$scope", "ImageService", "$stateParams", "$location", function($scope, ImageService, $stateParams, $location){
	var self		= this;
	var category	= $stateParams.category;
	self.imageURL	= null;
	console.log("-------------------");

	self.nextImage = function(){
		changeImageUrl(+1);
	};

	self.prevImage = function(){
		changeImageUrl(-1);
	};

	function changeImageUrl(direction){
		ImageService.fetchViewerImage(category, $stateParams.imageid, direction).then(function(response){
			setPageURL("viewer", category, response.id);
		}, errorHandler);
	}

	self.close = function(){
		ImageService.getPage($stateParams.imageid)
			.then(function(pageNumber){
				setPageURL("gallery", category, pageNumber);
			}, errorHandler);
	};

	if ($stateParams.imageid){
		// Load the main image
		ImageService.fetchViewerImage(category, $stateParams.imageid).then(function(response){
			preloadImage(response.meta.file, function(){
				console.log("Loading image: ", response.meta.file);
				self.imageURL = response.meta.file;
				$scope.$apply();

				// Now cache the next 4 images
				cacheUnseen([1, 2, 3, 4]);
			});
		}, errorHandler);
	}

	$scope.$on("$destroy", function() {
		for (var name in preloads){
			preloads[name].src = "";
		}
	});


	function errorHandler(){
		console.log("Error: ", arguments);
	}

	function setPageURL(section, category, number){
		$location.path("/" + section + "/" + category + "/" + number);
	}

	var preloads	= {};
	var cnt			= 0;
	function preloadImage(url, callback){
		var img			= new Image();
		var id			= "image_" + cnt++;
		img.onload	= function(){
			if (callback != null) callback();
			delete preloads[id];
		};
		img.src			= url;
		preloads[id]	= img;
	}

	function cacheUnseen(increments){
		if (increments && increments.length > 0){
			var increment = increments.shift();
			ImageService.fetchViewerImage(category, $stateParams.imageid, increment).then(function(response){
				console.log("Caching image: ", response.meta.file);
				preloadImage(response.meta.file, function(){
					console.log("Cache complete");
					cacheUnseen(increments);
				});
			});
		}
	}

}]);