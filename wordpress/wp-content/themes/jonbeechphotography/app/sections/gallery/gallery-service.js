angular.module("jonphoto").factory("GalleryService", ["$http", "$q", function($http, $q){
	return {
		pageSize: 30,

		fetchPageImages : function(pageNumber){
			return $http.get("/wp-json/media?filter[posts_per_page]=" + this.pageSize + "&page=" + pageNumber)
		},
		checkHasNextPage : function(pageNumber){
			var url = "/wp-json/media";
			url		+= "?filter[posts_per_page]=" + this.pageSize;
			url		+= "&page=" + (pageNumber + 1);

			return $http.get(url)
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