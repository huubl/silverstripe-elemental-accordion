/*
* If you don't have node.js installed, INSTALL NODE.JS
*
* If you don't have gulp installed:
* sudo npm install --global gulp
* sudo npm install --save-dev gulp
*
* Make sure gulpfile.js and package.json are in the same directory.
* Navigate to directory of gulpfile.js and package.json file and type `npm install`.
*
* In the gulp file, update all paths to location of current theme directory.
* Also update the browserSync proxy to the current project local URL
*
* RECOMMENDED: run 'gulp build' for initial setup and first time builds. run 'gulp watch' every time after that.
*
* Run 'gulp build' if you want to just run necessary tasks for building the theme
* Run 'gulp watch' to watch for any file changes while in development
* Run 'gulp' streamlined to run sass, lint and watch tasks
*
* NOTE: Browser Sync will run when running any task associated with the 'watch' task (including the default task). This will open a new tab at
* http://localhost:3000. In the command line you can find the links for local and external. There are also links for the UI to adjust options.
*
*/

var gulp = require('gulp');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var pump = require('pump');
var concat = require('gulp-concat');

var paths = {
    css: './src/css',
    cssany: ['./src/css/*.css'],
    sass: './src/css/scss',
    sassany: ['./src/css/scss/**/*.scss'],
    sassdist: './dist/css',
    jsany: ['./client/src/*.js'],
    jssrc: './src/javascript',
    jsdist: './client/dist',
    jsdistany: './dist/javascript/**/*.js'
};

// lint all scripts, then run 'scripts' task
// NOTE: add any files you want ignored to the .jshintignore file
gulp.task('lint', ['scripts'], function() {
    return gulp.src(paths.jsany)
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// combine all scripts to one file, then uglify
gulp.task('scripts', function (cb) {
    pump([
            gulp.src(paths.jsany),
            concat('accordion.init.min.js'),
            gulp.dest(paths.jsdist),
            uglify(),
            gulp.dest(paths.jsdist)
        ],
        cb
    );
});
gulp.task('watch', function() {
    gulp.watch(paths.jsany, ['lint']);
});

gulp.task('build', ['lint']);
gulp.task('default', ['lint']);

