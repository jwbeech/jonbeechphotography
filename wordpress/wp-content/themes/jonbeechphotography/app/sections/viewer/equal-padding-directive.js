angular.module("jonphoto").directive("equalPadding", ["$window", "utils", function($window, utils){
	return {
		restrict: "A",
		link: function ($scope, $element, $attrs) {
			var current;

			$scope.resizeListener = function(){
				var height		= $element.outerHeight();
				var $img		= $element.find("img");
				var padding		= 0;

				// Reset to get correct reading
				if (current != 0) {
					current = 0;
					$element.css("padding-top", 0);
				}

				if ($img.length > 0){
					var imgHeight	= $img.height();
					padding			= height > imgHeight ? Math.round((height - imgHeight) / 2) : 0;
				}
				if (padding != current){
					current = padding;
					$element.css("padding-top", padding);
				}
			};

			$scope.debounced = utils.debounce($scope.resizeListener, 100);
			angular.element($window).on("resize", $scope.resizeListener);

			$element.find("img").on("load", $scope.resizeListener).each(function() {
				if (this.complete) $(this).load();
			});

			$scope.resizeListener();

			$scope.on("$destroy", function(){
				angular.element($window).off("resize", $scope.resizeListener);
			});
		}
	}
}]);