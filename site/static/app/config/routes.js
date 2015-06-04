angular.module("jonphoto").config(["$stateProvider", "$urlRouterProvider", function ($stateProvider, $urlRouterProvider) {

	$urlRouterProvider.otherwise("/gallery/1");

	$stateProvider.state("gallery", {
		url				: "/gallery/:pageNumber",
		templateUrl		: "/static/app/sections/gallery/gallery.html",
		controller		: "GalleryController as ctrl"
	});
}]);