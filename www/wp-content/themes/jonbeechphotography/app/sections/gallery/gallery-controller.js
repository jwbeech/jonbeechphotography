angular.module("jonphoto").controller("GalleryController", ["ImageService", "$stateParams", "$location", function(ImageService, $stateParams, $location){
	var self		= this;
	self.imageData	= [];
	self.pageNumber	= 1;
	self.loading	= true;
	var category	= $stateParams.category;

	self.fetchPage = function(pageNumber){
		self.loading	= false;
		pageNumber		= Number(pageNumber);
		self.pageNumber	= pageNumber;
		self.imageData	= [];

		$location.path("/gallery/" + category + "/" + pageNumber);
	};
	self.fetchNextPage = function(){
		if (self.showNext){
			self.fetchPage(Number(self.pageNumber) + 1);
		}
	};
	self.fetchPreviousPage = function(){
		if (self.showPrev){
			self.fetchPage(Number(self.pageNumber) - 1);
		}
	};

	if ($stateParams.pageNumber){
		self.pageNumber = $stateParams.pageNumber;
		ImageService.fetchPageImageAndSetPage(category, self.pageNumber)
			.then(function(response){
				self.imageData	= response.data.api_rows;
				self.showPrev	= response.data.api_page > 1;
				self.showNext	= response.data.api_page < response.data.api_total_pages;
				self.loading	= false;
			},
			function(){
				self.hasNewPage = false;
				self.loading	= false;
			});
	}

}]);