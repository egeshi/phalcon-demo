/**
 * Created by takeshi on 27.02.2017.
 */

'use strict';

var gulp = require("gulp");
var concat = require("gulp-concat");
var util = require('gulp-util');
var sass = require('gulp-sass');


var config = {
	assetsDir: 'app/src/Demo/views/assets',
	production: !!util.env.production,
	pub: 'app/public',
	jsDest: "assets/js",
	cssDest: "assets/css",
	bowerDir: "vendor/bower_components"
};

/**
 * Concatenate vendor files
 */
gulp.task('concat:js:vendor', function () {

	var files = [
		config.bowerDir + '/jquery/dist/jquery.js',
		config.bowerDir + '/tether/dist/js/tether.js',
		config.bowerDir + '/bootstrap/dist/js/bootstrap.js'
	];

	return gulp.src(files)
			.pipe(concat('vendor.js'))
			.pipe(gulp.dest(config.pub + "/" + config.jsDest));

});

gulp.task('sass:vendor', function () {

	var styles = [
		config.assetsDir + '/bootstrap/scss/bootstrap.scss',
		config.bowerDir + '/font-awesome/scss/font-awesome.scss'
	];

	return gulp.src(styles)
			.pipe(sass().on('error', sass.logError))
			.pipe(concat('vendor.sass.css'))
			.pipe(gulp.dest(config.pub + "/" + config.cssDest));

});

/**
 * Contactenate vendor css
 */
gulp.task("concat:css:vendor", function () {

	var files = [
		config.bowerDir + '/tether/dist/css/tether.min.css',
		config.bowerDir + '/tether/dist/css/tether-theme-basic.min.css'
	];

	return gulp.src(files)
			.pipe(concat('vendor.css'))
			.pipe(gulp.dest(config.pub + "/" + config.cssDest));
});

/**
 * Copy font-awesome fonts folder to css compiled folder
 */
gulp.task('copy:icons:fa', function () {
	return gulp.src(config.bowerDir + '/font-awesome/fonts/*')
			.pipe(gulp.dest(config.pub + "/" + config.cssDest + "/fonts"));
});

/**
 * Copy images
 */
gulp.task('copy:images', function () {
	return gulp.src(config.assetsDir + '/images/*')
			.pipe(gulp.dest(config.pub + "/assets/img"));
});

/**
 * Concatenate .scss files
 */
gulp.task('sass:dev', function () {

	var appStyles = [config.assetsDir + "/sass/app.scss"];

	return gulp.src(appStyles)
			.pipe(sass().on('error', sass.logError))
			.pipe(concat('app.css'))
			.pipe(gulp.dest(config.pub + "/" + config.cssDest));
});

/**
 * Concatenate javascript
 */
gulp.task('js:dev', function () {

	var appJs = [
		config.assetsDir + "/js/xhr.js",
		config.assetsDir + "/js/app.js"
	];

	return gulp.src(appJs)
			.pipe(concat('app.js'))
			.pipe(gulp.dest(config.pub + "/" + config.jsDest));

});

/**
 * Default task
 */
gulp.task('default', function () {
	gulp.start('concat:js:vendor');
	gulp.start('concat:css:vendor');
	gulp.start('sass:vendor');
	gulp.start('sass:dev');
	gulp.start('js:dev');
	gulp.start('copy:icons:fa');
	gulp.start('copy:images');
});

gulp.watch( [
	config.assetsDir + '/sass/*',
	config.assetsDir + '/js/*'
] , function(event) {
	console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	gulp.start('sass:dev');
	gulp.start('js:dev');
});
