angular.module("jonphoto").directive("gallerySizer", ["$window", "$timeout", function($window, $timeout){
	return {
		templateUrl: "/static/app/sections/gallery/gallery-sizer/gallery-sizer.html",
		restrict: "A",
		scope: {
			imageData: "=",
			size: "@",
			sizeStyle: "@",
			formattedData: "@"
		},
		link: function($scope, $element, $attrs){
			var windowEle = angular.element($window);
			// When data changes make sure the size is not set and rerun
			$scope.$watch("imageData", function(newValue, oldValue){
				console.log("imageData Change Made");
				$scope.size = null;
				doResize();
			});
			windowEle.bind("resize", doResize);

			var sizes 	= [
				{min:1800},
				{min:1200, max:1800},
				{min:979, max:1200},
				{min:767, max:979},
				{min:480, max:767},
				{min:320, max:480},
				{max:320}
			];
			function doResize(){
				console.log("Checking resize");
				var width = windowEle.width();
				for (var i = 0; i < sizes.length; i++){
					var sizeObj = sizes[i];
					var first	= i == 0;
					var last	= i == sizes.length - 1;

					if (first && width > sizeObj.min){
						populateImages(sizeObj.min);
						break;
					}
					else if (last && width <= sizeObj.max){
						populateImages(300);
						break;
					}
					else if (width > sizeObj.min && width <= sizeObj.max){
						populateImages(sizeObj.min);
						break;
					}
				}
			}

			function populateImages(size){
				if ($scope.size != size){
					console.log("Populating size for ", size);
					$scope.size 		= size;
					$scope.sizeStyle	= {width:size + "px"};
					// Prune data
					/*var newData			= [];
					console.log($scope.imageData);
					if ($scope.imageData){
						var strSize				= String(size);
						console.log(strSize);
						for (var i = 0; i < $scope.imageData.length; i++){
							console.log($scope.imageData[i]);
							newData.push($scope.imageData[i][strSize]);
						}
						$scope.formattedData	= newData;
					}*/
					$timeout(function(){
						$scope.$apply();
					});
				}
			}

			doResize();
		}
	}
}]);