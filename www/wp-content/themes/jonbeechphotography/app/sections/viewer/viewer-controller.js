angular.module("jonphoto").controller("ViewerController",
	["$scope", "ImageService", "$stateParams", "$location", "KeyService", function($scope, ImageService, $stateParams, $location, KeyService){
		var self		= this;
		var category	= $stateParams.category;
		self.imageURL	= null;

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
					self.imageURL = response.meta.file;
					$scope.$apply();

					// Now cache the next 4 images
					cacheUnseen([1, 2, 3, 4]);
				});
			}, errorHandler);
		}



		$scope.$on("$destroy", function() {
			// Destroy preloads
			for (var name in preloads){
				preloads[name].src = "";
			}
			// Remove key bindings
			for (var i = 0; i < bindings.length; i++){
				bindings[i]();
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
					preloadImage(response.meta.file, function(){
						cacheUnseen(increments);
					});
				});
			}
		}

		// Key bindings
		var bindings = [];
		bindings.push(KeyService.addBinding(37, self.prevImage));
		bindings.push(KeyService.addBinding(39, self.nextImage));
		bindings.push(KeyService.addBinding(27, self.close));

	}]
);