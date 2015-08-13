angular.module("jonphoto").controller("GalleryController", ["GalleryService", "$stateParams", function(GalleryService, $stateParams){
	var self		= this;
	self.imageData	= null;
	self.pageNumber	= 1;
	self.loading	= true;
	self.hasNewPage	= false;

	self.fetchPage = function(pageNumber){
		self.loading	= false;

		GalleryService.fetchPageImages(pageNumber)
			.then(function(response){
				console.log("response: ", response);
				self.imageData = response.data;
			})
			.then(function(){
				return GalleryService.checkHasNextPage(pageNumber)
			})
			.then(function(){
				self.hasNewPage = true;
				self.loading	= false;
			},
			function(){
				self.hasNewPage = false;
				self.loading	= false;
			});
	};

	if ($stateParams.pageNumber){
		self.pageNumber = $stateParams.pageNumber
	}

	self.fetchPage(self.pageNumber);
}]);