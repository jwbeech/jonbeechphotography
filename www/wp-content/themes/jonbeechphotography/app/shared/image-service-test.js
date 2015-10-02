describe("Image service suite", function(){

	beforeEach(module("jonphoto"));

	afterEach(inject(function($window){
		delete $window.localStorage["currentPage"];
	}));

	it("Test set page", inject(function(ImageService, $window){
		ImageService.setPage(5);
		expect($window.localStorage["currentPage"] * 1).toBe(5);
	}));

	it("Test getPage localstorage", function(done){
		inject(function(ImageService, $window, $rootScope){
			$window.localStorage["currentPage"] = 3;

			ImageService.getPage()
				.then(function(page){
					expect(page).toBe(3);
					ImageService.setPage(50);
					return ImageService.getPage();
				})
				.then(function(page){
					expect(page).toBe(50);
					done();
				});

			// Have to run apply for the async promise to execute
			$rootScope.$apply();
		});
	});

	it("Test getPage service", function(done){
		inject(function(ImageService, $httpBackend){

			$httpBackend.expectGET("/gallery-api/?call=api_page_number&api_total_per_page=30&api_image_id=123")
				.respond(200, {data:{api_page:50}});


			ImageService.getPage(123)
				.then(function(page){
					expect(page).toBe(50);
					done();
				});
			$httpBackend.flush();
		});
	});

	it("Test fetchPageImageAndSetPage", inject(function(ImageService, $rootScope){
		spyOn(ImageService, "fetchPageImages");
		ImageService.fetchPageImageAndSetPage("all", 51);

		expect(ImageService.fetchPageImages).toHaveBeenCalled();

		ImageService.getPage().then(function(page){
			expect(page).toBe(51);
		});
		$rootScope.$apply();
	}));

});