angular.module("jonphoto").factory("ImageService", ["$http", function($http){

	var pageSize 		= 30;
	var currentCategory	= null;

	return {
		fetchPageImages : function(pageNumber){
			return $http.get("/gallery-api/?call=api_images&api_total_per_page=" + pageSize + "&api_page=" + pageNumber);
		},
		fetchViewerImage: function(category, imageId, increment){
			

		}
	}
}]);