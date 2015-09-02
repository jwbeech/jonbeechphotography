angular.module("jonphoto").factory("GalleryService", ["$http", "$q", function($http, $q){

	var cachedResult 	= null;
	var pageSize		= 30;

	return {
		fetchPageImages : function(pageNumber){
			return $http.get("/api/?call=api_images&api_total_per_page=" + pageSize + "&api_page=" + pageNumber);
		},

		cachedRecentPage : function(){
			return cachedResult;
		}
	}
}]);