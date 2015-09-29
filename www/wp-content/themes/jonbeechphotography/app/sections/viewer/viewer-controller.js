angular.module("jonphoto").controller("ViewerController", ["$rootScope", function($rootScope){

	$rootScope.$on("$routeChangeSuccess", function(){
		console.log("changed locations")
	});

}]);