angular.module("jonphoto").controller("GalleryController", ["GalleryService", "$stateParams", function(GalleryService, $stateParams){
	var self		= this;
	self.imageData	= null;

	self.fetchPage = function(pageNumber){
		GalleryService.fetchPage(pageNumber).then(
			function(response){
				self.imageData = response.data;
			}
		)
	};

	self.sizeChange = function(size){
		console.log("Size change");
	};

	if ($stateParams.pageNumber){
		self.fetchPage($stateParams.pageNumber);
	}
}]);