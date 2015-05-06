/**
 * This snippet is mostly take from this page:
 * http://karma-runner.github.io/0.8/plus/RequireJS.html
 */
(function(){
    var tests   = [];

    console.log("Running through karma files to look for tests");

    for (var file in window.__karma__.files) {
        // We look for all files loaded that end in Spec.js
        if (new RegExp("Spec\.js$").test(file)){
            //console.log("Found test file to use: ", file);
            tests.push(file);
        }
    }

    requirejs.config({
        // Karma serves files from '/base'
        baseUrl: '/base/js',

        // underscore and jquery are included in the testing server in the grunt config.
        // All included files are placed into the base directory so we just load them relatively.
        // They are NOT loaded relative to this file but relative to the testing server
        paths: {
            underscore  : "vendor/underscore.1.8.2",
            jquery      : "vendor/jquery-2.1.3"
        },

        // ask Require.js to load these files (all our tests)
        deps: tests,

        // start test run, once Require.js is done
        callback: window.__karma__.start
    });

})();