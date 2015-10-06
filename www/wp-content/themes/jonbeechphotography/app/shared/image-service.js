angular.module("jonphoto").factory("ImageService", ["$http", "$window", "$q", "Cache", function($http, $window, $q, Cache){

	var pageSize 		= 30;
	var currentCategory	= null;
	var currentPage		= null;
	var pageCache		= new Cache();

	var self			= {
		setPage: function(pageNumber){
			currentPage = pageNumber * 1;
			if ($window.localStorage){
				$window.localStorage["currentPage"] = currentPage;
			}
		},
		getPage: function(imageId, category){
			return $q(function(resolve, reject){
				if (currentPage != null){
					resolve(currentPage);
				}
				else if ($window.localStorage && $window.localStorage["currentPage"]){
					currentPage = $window.localStorage["currentPage"] * 1;
					resolve(currentPage);
				}
				else{
					self.call("api_page_number", {api_category:category, api_total_per_page:pageSize, api_image_id:imageId})
						.then(function(response){
							self.setPage(response.data.data.api_page);
							resolve(currentPage);
						}, reject);
				}
			});
		},

		fetchPageImageAndSetPage: function(category, pageNumber){
			self.setPage(pageNumber);
			return self.fetchPageImages(category, pageNumber);
		},

		fetchPageImages : function(category, pageNumber){
			return $q(function(resolve, reject){
				// only let cache live as long as the category is the same
				if (currentCategory != category){
					currentCategory = category;
					pageCache.flush();
				}

				if (pageCache.getValue(pageNumber)){
					resolve(pageCache.getValue(pageNumber));
				}
				else{
					self.call("api_images", {api_category:category, api_total_per_page:pageSize, api_page:pageNumber})
						.then(function(response){
							pageCache.setValue(pageNumber, response.data);
							resolve(response.data);
						}, reject);
				}
			});
		},


		fetchViewerImage: function(category, imageId, increment){
			// Sanity check to make sure the logic below works correctly
			if (increment < -pageSize || increment > pageSize){
				throw new Error("Fetch viewer image increment must be smaller than the page size: ", pageSize);
			}

			return $q(function(resolve, reject){
				var tempPage;

				/*
				Get the current page's images then cycle till we find the image id
				If not found recurse the function to the next page and keep looking
				When found, if there is no increment the use the current cycle page to set the current page for the app
				*/

				self.getPage(imageId, category).then(cyclePages);

				// TODO: Add full cycle check

				function cyclePages(currPageNumber){
					self.fetchPageImages(category, currPageNumber)
						.then(function(response){
							// found my location now find the image
							var rows	= response.data.api_rows;
							var imageIndex;
							for (var i = 0; i < rows.length; i++){
								if (rows[i].id == imageId){
									imageIndex = i;
									break;
								}
							}
							// Calculate the next page number
							var nextPageNumber	= (response.data.api_page < response.data.api_total_pages) ? currPageNumber + 1 : 1;
							var prevPageNumber	= (response.data.api_page == 1) ? response.data.api_total_pages : response.data.api_page - 1;

							// If we didn't find it cycle to the next page
							if (imageIndex == null){
								cyclePages(nextPageNumber);
							}

							// If we found the image we are looking for
							else{
								// Set the current page to narrow the researching next time and resolve
								if (increment == null){
									self.setPage(currPageNumber);
									resolve(rows[imageIndex]);
								}

								// Otherwise look for the incremented index image
								else{
									// Add the increment
									imageIndex += increment;

									// If inside the same set, resolve using the new index
									if (imageIndex >= 0 && imageIndex < rows.length){
										resolve(rows[imageIndex]);
									}
									// If outside the set
									else{
										currPageNumber	= (increment < 0) ? prevPageNumber : nextPageNumber;

										// Fetch the next or previous set and return the index
										self.fetchPageImages(category, currPageNumber)
											.then(function(response){
												var responsePageSize = response.data.api_rows.length;
												var newIndex;

												// Positive / Forward
												if (imageIndex >= rows.length){
													newIndex = imageIndex - rows.length;
												}
												// Negative / Backward
												else{
													newIndex = responsePageSize + imageIndex;
												}

												resolve(response.data.api_rows[newIndex]);
											});
									}
								}
							}
						})
				}

			});
		},

		call: function(call, params){
			var url = "/gallery-api/?call=" + call;
			if (params){
				for (var name in params){
					url += "&" + name + "=" + params[name];
				}
			}
			return $http.get(url);
		}
	};

	return self
}]);