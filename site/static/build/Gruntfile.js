// Docs: http://gruntjs.com/getting-started
module.exports = function(grunt) {

	// The web-root
	var webRoot			= "../";
	// The build directory relative to the web-root
	var build			= "build/";
	var release			= "release/";

	var bootstrap		= "bower_components/bootstrap/js/";
    var bootstrapConfig	= grunt.file.readJSON("../bower_components/bootstrap/grunt/configBridge.json", { encoding: "utf8" });
	var fileRegister	= grunt.file.readJSON("../fileregister.json");

	// Project configuration
	grunt.initConfig({
        // Bootstrap JS: Only include what you need. Everything included by default.
        jqueryCheck			: bootstrapConfig.config.jqueryCheck.join("\n"),
        jqueryVersionCheck	: bootstrapConfig.config.jqueryVersionCheck.join("\n"),
        concat: {
            bootstrap: {
                options: {
                    banner: "<%= jqueryCheck %>\n<%= jqueryVersionCheck %>",
                    stripBanners: false
                },
                src: [
					//bootstrap + "transition.js",
					//bootstrap + "alert.js",
					bootstrap + "button.js",
					//bootstrap + "carousel.js",
					//bootstrap + "collapse.js",
					bootstrap + "dropdown.js",
					//bootstrap + "modal.js",
					//bootstrap + "tooltip.js",
					//bootstrap + "popover.js",
					//bootstrap + "scrollspy.js",
					//bootstrap + "tab.js",
					//bootstrap + "affix.js",
                ],
                dest: "app/vendor/bootstrap.js"
            },

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
					cwd		: "scss/",
					src		: ["**/*.scss"],
					dest	: "css/",
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

		// Watch task for auto running less command on changed less files
		watch : {
			main : {
				files 	: ["scss/**/*.scss"],
				tasks	: ["sass"],
				options : {
					spawn: false
				}
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
	grunt.loadNpmTasks("grunt-contrib-concat");
	grunt.loadNpmTasks("grunt-contrib-cssmin");
	grunt.loadNpmTasks("grunt-contrib-uglify");
	grunt.loadNpmTasks("grunt-contrib-clean");
	grunt.loadNpmTasks("grunt-contrib-copy");
	grunt.loadNpmTasks("grunt-sass");
	grunt.loadNpmTasks("grunt-contrib-imagemin");
	grunt.loadNpmTasks("grunt-contrib-watch");
	grunt.loadNpmTasks("grunt-karma");
	grunt.loadNpmTasks("grunt-newer");

	// Set base
	grunt.file.setBase(webRoot);

	// Define tasks that can be run
	grunt.registerTask("devassets", ["sass", "concat:bootstrap"]);
	//grunt.registerTask("releasenotests", ["devassets", "clean", "concat:main", "uglify", "cssmin", "newer:imagemin", "copy"]);
	//grunt.registerTask("default", ["releasenotests", "karma"]);
	grunt.registerTask("test", ["karma"]);
};