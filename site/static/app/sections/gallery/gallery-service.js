angular.module("jonphoto").factory("GalleryService", ["$http", "$q", function($http, $q){
	return {
		fetchPage : function(pageNumber){
			return $http.get("/home/fetchPage/" + pageNumber);
		}
	}
}]);