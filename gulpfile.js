/**
 * Created by takeshi on 27.02.2017.
 */

'use strict';

var gulp = require("gulp");
var concat = require("gulp-concat");
var util = require('gulp-util');
var sass = require('gulp-sass');
var concat = require('gulp-concat');


var config = {
    assetsDir: 'app/Demo/views/assets',
    sassPattern: 'sass/**/*.scss',
    production: !!util.env.production,
    pub: 'app/public',
    jsDest: "assets/js",
    cssDest: "assets/css"
};

/**
 * Concatenate vendor files
 */
gulp.task('concat:js:vendor', function(){

    var files = [
        'bower_components/tether/dist/js/tether.js',
        'bower_components/bootstrap/dist/js/bootstrap.js'
    ];

    return gulp.src(files)
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest(config.pub+"/"+config.jsDest));

});

/**
 * Contactenate vendor css
 */
gulp.task("concat:css:vendor", function(){

    var files = [
        'bower_components/tether/dist/css/tether.min.css',
        'bower_components/tether/dist/css/tether-theme-basic.min.css'
    ];

    return gulp.src(files)
        .pipe(concat('vendor.css'))
        .pipe(gulp.dest(config.pub+"/"+config.cssDest));
});

/**
 * Concatenate .scss files
 */
gulp.task('sass:dev', function () {

    var files = [
        'bower_components/bootstrap/scss/bootstrap.scss'
    ];

    return gulp.src(files)
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('vendor.sass.css'))
        .pipe(gulp.dest(config.pub+"/"+config.cssDest));
});

/**
 * Watch custom .sass files for changes
 */
gulp.task('sass:watch', function () {
    gulp.watch(config.assetsDir+'/'+config.sassPattern, ['sass']);
});

/**
 * Default task depending on Application environment variable
 */
gulp.task('default', function(){

    gulp.start('concat:js:vendor');
    gulp.start('concat:css:vendor');
    gulp.start('sass:dev');

    var watcher = gulp.watch(config.assetsDir+'/'+config.sassPattern, ['sass:watch']);
    
    watcher.on('change', function(event) {
        console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });
});
