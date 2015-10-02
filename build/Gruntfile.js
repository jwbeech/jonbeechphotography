module.exports = function(grunt) {

	// The web-root
	var webRoot			= "../www/wp-content/themes/jonbeechphotography/";
	// The build directory relative to the web-root
	var build			= "../../../../build/";
	var release			= "../../../../release/";

	var fileRegister	= grunt.file.readJSON(webRoot + "fileregister.json");
	var lists			= [fileRegister.development.css, fileRegister.development.js, fileRegister.production.css, fileRegister.production.js];
	lists.forEach(function(list){
		list.forEach(function(path, index){
			list[index] = path.replace("/wp-content/themes/jonbeechphotography/", "");
		});
	});


	// Project configuration
	grunt.initConfig({
		// SCSS config builds all the less files into the css folder, overwrites existing files so only changes to less will persist
		sass : {
			main : {
				options: {
					outputStyle	: "expanded",
					precision	: 5,
					indentType	: "tab",
					indentWidth	: 1
				},
				files: [{
					expand	: true,
					cwd		: "stylesheets/",
					src		: ["**/*.scss"],
					dest	: "stylesheets/",
					ext		: ".css"
				}]
			}
		},

		// Used to compress the main js files once built
		uglify: {
			main: {
				options: {
					mangle: false
				},
				files: [{
					src: fileRegister.development.js,
					dest: fileRegister.production.js[0]
				}]
			}
		},

		// CSS minification, this will likely need updates as new css files are added
		cssmin : {
			main: {
				files: [{
					src: fileRegister.development.css,
					dest: fileRegister.production.css[0]
				}]
			}
		},

		// Karma config used to run karma build test runner harness in the grunt environment
		karma : {
			unit : {
				options :{
					// Build the list of files from the fileRegister and add some test files
					files: fileRegister.development.js.concat([
						"bower_components/angular-mocks/angular-mocks.js",
						"app/**/*test.js",
						{pattern:"app/**/*.tpl", included:false},
						{pattern:"app/**/*.html", included:false}
					]),

					frameworks	: ["jasmine-jquery", "jasmine"],
					reporters	: ["spec"],
					logLevel	: "INFO",
					autoWatch	: true,
					browsers	: ["PhantomJS"],
					singleRun	: true
				}
			}
		}
	});


	// Load the plugins
	require("load-grunt-tasks")(grunt);

	// Set base
	grunt.file.setBase(webRoot);

	// Tasks
	grunt.registerTask("build", ["sass", "uglify", "cssmin"]);
	grunt.registerTask("default", ["karma"]);
};