angular.module("jonphoto").factory("KeyService", ["$window", function($window){

	var bindings	= {};

	var cnt			= 0;

	angular.element($window).on("keyup", function(e){
		//console.log(e.keyCode);
		// keyCode, ctrlKey, altKey, shiftKey
		for (var name in bindings){
			var bind 	= bindings[name];
			var code	= bind.keyCode == e.keyCode;
			var ctrl	= (bind.ctrlKey == null || (ctrlKey.ctrlKey == e.ctrlKey));
			var shift	= (bind.shiftKey == null || (ctrlKey.shiftKey == e.shiftKey));
			var alt		= (bind.altKey == null || (ctrlKey.altKey == e.altKey));
			if (code && ctrl && shift && alt){
				bind.callback();
			}
		}
	});

	return {
		// Left Arrow: keyCode 37
		// Right Arrow: keyCode 39
		// Escape: keyCode 27
		addBinding: function(keyCode, callback, ctrlKey, shiftKey, altKey){
			var name 		= "binding_" + cnt++;
			bindings[name]	= {
				keyCode:keyCode, callback:callback, ctrlKey:ctrlKey, shiftKey:shiftKey, altKey:altKey
			};

			// Return the function to remove the listener
			return function(){
				delete bindings[name];
			}
		}
	}
}]);