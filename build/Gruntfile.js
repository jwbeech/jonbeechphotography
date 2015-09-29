// Docs: http://gruntjs.com/getting-started
module.exports = function(grunt) {

	// The web-root
	var webRoot			= "../wp-content/themes/jonbeechphotography/";
	// The build directory relative to the web-root
	var build			= "../../../build/";
	var release			= "../../../release/";


	// Project configuration
	grunt.initConfig({
        concat: {
			main: {
				src: fileRegister.development.js,
				dest: release + fileRegister.production.js[0]
			}
        },

		// Cleans the release directory
		clean: {
			main: {
				src: [release + "**/**"]
			}
		},


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
					src: fileRegister.production.js[0],
					dest: fileRegister.production.js[0]
				}]
			}
		},

		// CSS minification, this will likely need updates as new css files are added
		cssmin : {
			main: {
				files: [{
					src: fileRegister.development.css,
					dest: release + fileRegister.production.css
				}]
			}
		},

		copy: {
			main: {
				files: [{
					expand	: true,
					src		: [
						"app/**/*.tpl",
						"app/**/*.html",
						"fonts/**/*.*",
						".htaccess",
						"404.html",
						"api.php",
						"fileregister.json"
					],
					dest	: release
				}]
			},
			config: {
				options: {
					process: function (content, srcpath) {
						return content.replace(/\$env *?\t*= *"development"/g, '$env = "production"');
					},
				},
				files: [{expand: true, src: ["index.php"], dest: release}]
			}
		},

		// Image compression systems
		imagemin: {
			main : {
				options:{
					// PNG optimization level
					optimizationLevel: 7,
					// jpg progressive transformation
					progressive: false,
					// gif interlaced transformation
					interlaced: false
					// SVG options supported, see docs: https://github.com/gruntjs/grunt-contrib-imagemin
				},
				files: [{
					expand	: true,
					src		: ["images/**/*.{png,jpg,gif}"],
					dest	: release
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

					// base path that will be used to resolve all patterns (eg. files, exclude)
					basePath: "",

					// frameworks to use
					frameworks: ["jasmine-jquery", "jasmine"],

					// Both of reporters are custom plugins:
					// junit - used to generare an XML report that can be used by jenkins
					// karma-spec-reporter - used to generare a friendly output in the console
					reporters: ["junit", "spec"],
					junitReporter: {
						outputFile: "reports/js-unit-test-results.xml"
					},

					port: 9876,

					colors: true,

					// level of logging
					// possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
					logLevel: "INFO",

					// enable / disable watching file and executing tests whenever any file changes
					autoWatch: true,

					// start these browsers. available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
					browsers: ["PhantomJS"],

					// Continuous Integration mode if true, Karma captures browsers, runs the tests and exits
					singleRun: true
				}
			}
		}
	});


	// Load the plugins
	require("load-grunt-tasks")(grunt);

	// Set base
	grunt.file.setBase(webRoot);

	// Define tasks that can be run
	grunt.registerTask("devassets", ["sass", "concat:bootstrap"]);
	//grunt.registerTask("releasenotests", ["devassets", "clean", "concat:main", "uglify", "cssmin", "newer:imagemin", "copy"]);
	grunt.registerTask("test", ["karma"]);
};