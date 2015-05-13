requirejs.config({
	baseUrl: (typeof window === "object" && typeof window.document === "object" ? window.jsPrefix : ""),
	urlArgs: "build=" + (typeof window === "object" && typeof window.document === "object" ? window.buildNumber : "notSet"),

	paths: {
		jquery		: "vendor/jquery-2.1.3",
		underscore	: "vendor/underscore.1.8.2",
		bootstrap	: "vendor/bootstrap",
		harvey		: "vendor/harvey"
	},

	shim : {
		jquery		: { exports:"$" },
		bootstrap	: { deps: ["jquery"] },
		underscore	: { exports: "_", deps: ["jquery"] },
		harvey		: { exports: "Harvey" }
	}
});