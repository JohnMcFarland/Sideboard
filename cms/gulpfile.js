var gulp         = require( 'gulp' );
var autoprefixer = require( 'gulp-autoprefixer' );
var browserify   = require( 'gulp-browserify' );
var concat       = require( 'gulp-concat' );
var csslint      = require( 'gulp-csslint' );
var cssmin       = require( 'gulp-cssmin' );
var jscs         = require( 'gulp-jscs' );
var jshint       = require( 'gulp-jshint' );
var rename       = require( 'gulp-rename' );
var sass         = require( 'gulp-sass' );
var uglify       = require( 'gulp-uglify' );

gulp.task( 'default', ['scripts', 'stylesheets', 'vendors'] );

gulp.task( 'fonts', function( done ) {
	gulp.src( 'vendors/font-awesome/fonts/*' )
	    .pipe( gulp.dest( 'fonts' ) )
	    .on( 'end', done );
});

gulp.task( 'scripts', function( done ) {
	gulp.src( 'js/src/sideboard.js' )
	    .pipe( jshint() )
	    .pipe( jshint.reporter( 'jshint-stylish' ) )
	    .pipe( jscs() )
	    .pipe( browserify() )
	    .pipe( uglify() )
	    .pipe( rename({ suffix: '.min' }) )
	    .pipe( gulp.dest( 'js' ) )
	    .on( 'end', done );
});

gulp.task( 'stylesheets', function( done ) {
	gulp.src( 'style.css' )
	    .pipe( csslint() )
	    .pipe( csslint.reporter() )
	    .pipe( autoprefixer() )
	    .pipe( cssmin({ 'keepSpecialComments': false }) )
	    .pipe( rename({ suffix: '.min' }) )
	    .pipe( gulp.dest( '.' ) )
	    .on( 'end', done );
});

gulp.task( 'vendors', function( done ) {
	gulp.src([
		'vendors/css/bootstrap.min.css',
		'vendors/css/font-awesome.min.css',
		'vendors/css/cssshake.min.css',
		'vendors/css/hover-min.css',
		'vendors/css/jquery-ui.min.css',
		'vendors/css/animate.css',
	]).pipe( concat( 'vendors.css' ) )
	  .pipe( rename({ suffix: '.min' }) )
	  .pipe( gulp.dest( 'css' ) );

	gulp.src([
		'vendors/js/jquery-2.1.4.min.js',
		'vendors/js/bootstrap.min.js',
		'vendors/js/jquery.flip.min.js',
		'vendors/js/jquery-ui.min.js',
		'vendors/js/moment.js'
	]).pipe( concat( 'vendors.js' ) )
	  .pipe( rename({ suffix: '.min' }) )
	  .pipe( gulp.dest( 'js' ) );
});

gulp.task( 'watch', function() {
	gulp.watch( 'js/src/**/**/*.js', ['scripts'] );
	gulp.watch( 'style.css', ['stylesheets'] );
});
