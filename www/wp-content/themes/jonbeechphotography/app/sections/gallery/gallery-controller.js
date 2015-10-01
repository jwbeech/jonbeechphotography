angular.module("jonphoto").controller("GalleryController", ["ImageService", "$stateParams", "$location", function(ImageService, $stateParams, $location){
	var self		= this;
	self.imageData	= null;
	self.pageNumber	= 1;
	self.loading	= true;
	self.hasNewPage	= false;

	self.fetchPage = function(pageNumber){
		self.loading	= false;
		pageNumber		= Number(pageNumber);
		self.pageNumber	= pageNumber;
		self.imageData	= [];

		$location.path("/gallery/" + pageNumber);

		ImageService.fetchPageImages(pageNumber)
			.then(function(response){
				self.imageData	= response.data.data.api_rows;
				self.hasNewPage	= response.data.data.api_page < response.data.data.api_total_pages;
				self.loading	= false;
			},
			function(){
				self.hasNewPage = false;
				self.loading	= false;
			});
	};
	self.fetchNextPage = function(){
		self.fetchPage(Number(self.pageNumber) + 1);
	};
	self.fetchPreviousPage = function(){
		self.fetchPage(Number(self.pageNumber) - 1);
	};

	if ($stateParams.pageNumber){
		self.pageNumber = $stateParams.pageNumber
	}

	self.fetchPage(self.pageNumber);
}]);