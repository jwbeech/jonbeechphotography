angular.module("jonphoto").config(["$httpProvider", function($httpProvider) {

	// Add request transform to change into jQuery style
	$httpProvider.defaults.transformRequest.push(function(data) {
		var requestStr;
		if (data) {
			data = JSON.parse(data);
			for (var key in data) {
				var value = data[key];
				if (angular.isObject(value)){
					value = JSON.stringify(value);
				}
				if (requestStr) {
					requestStr += "&" + key + "=" + value;
				}
				else {
					requestStr = key + "=" + value;
				}
			}
		}
		return requestStr;
	});
	// Set the content type to be FORM type for all post requests
	// This does not add it for GET requests.
	$httpProvider.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
}]);

