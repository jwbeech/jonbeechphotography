angular.module("jonphoto").factory("GalleryService", ["$http", "$q", function($http, $q){
	return {
		pageSize: 30,

		fetchPageImages : function(pageNumber){
			return $http({
				url		: "/wp-json/media",
				method	: "GET",
				data	: {
					"filter[posts_per_page]": this.pageSize,
					"page": pageNumber
				}
			});
		},
		checkHasNextPage : function(pageNumber){
			return $http({
				url		: "/wp-json/media",
				method	: "GET",
				data	: {
					"filter[posts_per_page]": this.pageSize,
					"page": pageNumber + 1
				}
			})
				.then(function(response){
					if (response.data.length == 0){
						return $q.reject();
					}
					else{
						return response;
					}
				},
				function(response){
					$q.reject();
				})
		}
	}
}]);