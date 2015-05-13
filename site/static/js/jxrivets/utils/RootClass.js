/**
 * VERSION: 0.0.1
 */
(function(){
	// Create local var for this
	var window		= this;
	// Setup the constructor and call initialize
	var RootClass	= function () {
		this.initialize.apply(this, arguments);
	};
	function extend(source, additional){
		if (source && additional) {
			for (var propName in additional) source[propName] = additional[propName];
		}
	}
	// Add extended calls
	extend(RootClass.prototype, {
		initialize : function() {},

		callSuper : function(superClass, methodName, methodArgs){
			return superClass.prototype[methodName].apply(this, methodArgs);
		}
	});
	// Setup extension prototype manipulation
	RootClass.extend = function(instanceProps, staticProps) {
		var parent	= this;
		var child;
		if (instanceProps && instanceProps.hasOwnProperty("constructor")) {
			child	= instanceProps.constructor;
		} else {
			child	= function(){ parent.apply(this, arguments); };
		}
		extend(child, parent);
		extend(child, staticProps);
		var Surrogate		= function(){ this.constructor = child; };
		Surrogate.prototype	= parent.prototype;
		child.prototype		= new Surrogate;

		if (instanceProps) extend(child.prototype, instanceProps);
		child.__super__		= parent.prototype;
		return child;
	};

	// Node module definition
	if (typeof module === "object" && module && typeof module.exports === "object") {
		module.exports = RootClass;
	}
	// AMD module definition
	else if (typeof define === "function" && define.amd){
		define(function () { return RootClass; } );
	}
	// Standard window definition
	else if (typeof window === "object" && typeof window.document === "object") {
		window.RootClass = RootClass;
	}
}).call(this);
