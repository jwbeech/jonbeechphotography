angular.module("jonphoto").config(["$stateProvider", "$urlRouterProvider", function ($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise("/gallery/all/1");

    $stateProvider
        .state("gallery", {
            url: "/gallery/:category/:pageNumber",
            templateUrl: "/wp-content/themes/jonbeechphotography/app/sections/gallery/gallery.html"
        })
        .state("view", {
            url: "/viewer/:category/:imageid",
            templateUrl: "/wp-content/themes/jonbeechphotography/app/sections/viewer/viewer.html"
        })
        .state("about", {
            url: "/about",
            templateUrl: "/wp-content/themes/jonbeechphotography/app/sections/about/about.html"
        })
        .state("contact", {
            url: "/contact",
            templateUrl: "/wp-content/themes/jonbeechphotography/app/sections/contact/contact.html"
        })
    ;
}]);