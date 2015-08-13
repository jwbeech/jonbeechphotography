angular.module("jonphoto").controller("RenderController", ["$http", "$timeout", function($http, $timeout){
	var self		= this;
	self.busy		= true;
	self.percent	= 0;

	self.renderImages = function(){
		console.log("rendering");
		self.busy = true;
		$http.get("admin/doThumbRendering");
		$timeout(checkStatus, 300);
	};

	function checkStatus(){
		$http.get("admin/thumbStatus").then(
			function(response){
				self.busy 		= response.data.busy === true;
				self.percent	= response.data.percent ? response.data.percent : 0;
				if (self.busy) $timeout(checkStatus, 300);
			}
		)
	}
	checkStatus();

}]);