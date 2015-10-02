angular.module("jonphoto").controller("GalleryController", ["ImageService", "$stateParams", "$location", function(ImageService, $stateParams, $location){
	var self		= this;
	self.imageData	= null;
	self.pageNumber	= 1;
	self.loading	= true;
	self.hasNewPage	= false;
	var category	= $stateParams.category;

	self.fetchPage = function(pageNumber){
		self.loading	= false;
		pageNumber		= Number(pageNumber);
		self.pageNumber	= pageNumber;
		self.imageData	= [];

		$location.path("/gallery/" + category + "/" + pageNumber);
	};
	self.fetchNextPage = function(){
		self.fetchPage(Number(self.pageNumber) + 1);
	};
	self.fetchPreviousPage = function(){
		self.fetchPage(Number(self.pageNumber) - 1);
	};

	if ($stateParams.pageNumber){
		self.pageNumber = $stateParams.pageNumber;
		ImageService.fetchPageImageAndSetPage(category, self.pageNumber)
			.then(function(response){
				self.imageData	= response.data.api_rows;
				self.hasNewPage	= response.data.api_page < response.data.api_total_pages;
				self.loading	= false;
			},
			function(){
				self.hasNewPage = false;
				self.loading	= false;
			});
	}

}]);