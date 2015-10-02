angular.module("jonphoto").factory("Cache", ["RootClass", function(RootClass){


	return RootClass.extend({
		initialize: function(){
			this.cache 	= {};
			this.prefix	= "cache_";
		},

		setValue: function(key, value){
			this.cache[this.prefix + key] = value;
		},

		getValue: function(key, value){
			return this.cache[this.prefix + key];
		},

		invalidate: function(key){
			delete this.cache[this.prefix + key];
		},

		flush: function(){
			this.cache = {};
		}
	});
}]);