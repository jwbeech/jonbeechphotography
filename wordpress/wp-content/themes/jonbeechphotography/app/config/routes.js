angular.module("jonphoto").config(["$stateProvider", "$urlRouterProvider", function ($stateProvider, $urlRouterProvider) {

	$urlRouterProvider.otherwise("/gallery/1");

	$stateProvider.state("gallery", {
		url				: "/gallery/:pageNumber",
		templateUrl		: "/wp-content/themes/jonbeechphotography/app/sections/gallery/gallery.html",
		controller		: "GalleryController as ctrl"
	});
}]);