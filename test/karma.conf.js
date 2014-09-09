module.exports = function (config) {
    config.set({
        basePath: '..',
//        plugins: ['karma-jquery'],
        frameworks: ['jasmine'],

        files: [
            'common/web/bower_components/angular/angular.min.js',
            'common/web/bower_components/angular-mocks/angular-mocks.js',
            'common/web/bower_components/jquery/dist/jquery.min.js',
            'common/web/bower_components/jasmine-jquery/lib/jasmine-jquery.js',

            'common/web/js/streamboard/angular-app/**/*.js',
            'test/karma.init.js',
            'test/unit/**/*.js',

            // fixtures
            {pattern: 'test/fixtures/*.json', watched: true, served: true, included: false}
        ],

        proxies: {
        },

        // list of files to exclude
        exclude: [
        ],


        // preprocess matching files before serving them to the browser
        // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
        preprocessors: {
        },


        // test results reporter to use
        // possible values: 'dots', 'progress'
        // available reporters: https://npmjs.org/browse/keyword/karma-reporter
        reporters: ['progress'],


        // web server port
        port: 9876,


        // enable / disable colors in the output (reporters and logs)
        colors: true,


        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        logLevel: config.LOG_INFO,


        // enable / disable watching file and executing tests whenever any file changes
        autoWatch: true,


        // start these browsers
        // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
        browsers: ['Chrome'],


        // Continuous Integration mode
        // if true, Karma captures browsers, runs the tests and exits
        singleRun: false
    });
};
