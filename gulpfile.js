// TODO: refactor for Tyros

var gulp = require('gulp');
var cleancss = require('gulp-clean-css'); // used for css
var uglify = require('gulp-uglify'); // used for js
var concat = require('gulp-concat'); // used for css and js
var sourcemaps = require('gulp-sourcemaps'); // used in dev only

//
// CSS constants
//

const cssDestination = 'css/min/';

const cssViewSource = 'css/view/**';
const cssGlobalSource = 'css/global/**';
const cssThirdPartySource = 'css/third-party/**';

const cssDevViewFilename = 'dev-view.css';
const cssProdViewFilename = 'prod-view.css';

const cssDevGlobalFilename = 'dev-global.css';
const cssProdGlobalFilename = 'prod-global.css';

const cssDevThirdPartyFilename = 'dev-third-party.css';
const cssProdThirdPartyFilename = 'prod-third-party.css';

//
// JS constants
//

const jsDestination = 'js/min/';

const jsAppSource = ['js/app/**'];
const jsThirdPartySource = [
	'js/third-party/moment.js',
	'js/third-party/hammer.min.js',
	'js/third-party/hammer-time.min.js',
	'js/third-party/bootstrap-datepicker.js',
	'js/third-party/bootstrap-datetimepicker.js',
	'js/third-party/bootstrap-switch.min.js',
	'js/third-party/bootstrap-toggle.min.js',
	'js/third-party/jquery.fancybox.js',
	'js/third-party/jquery.fancybox-buttons.js',
	'js/third-party/jquery.fancybox-media.js',
	'js/third-party/jquery.fancybox-thumbs.js',
	'js/third-party/jquery.flexslider.js'
];
const jsSource = jsAppSource.concat(jsThirdPartySource);

const jsDevAppFilename = 'dev-app.js';
const jsProdAppFilename = 'prod-app.js';
const jsDevThirdPartyFilename = 'dev-third-party.js';
const jsProdThirdPartyFilename = 'prod-third-party.js';

gulp.task('watch', function(){
	console.log('watch');
	// build
	buildCSS();
	buildJS();
	
	gulp.watch(['gulpfile.js'], function(){
		console.log('gulpfile.js changed');
		buildCSS();
		buildJS();
	});
	
	// and watch to build again
	gulp.watch([cssViewSource, cssGlobalSource, cssThirdPartySource], function(e){
		console.log('some css changed', e);
		buildCSS();
	});
	
	gulp.watch(jsSource, function(e){
		console.log('some js changed', e);
		buildJS();
	});
});

//
// CSS methods
//

var buildCSS = function (){
	buildDevGlobalCSS();
	buildProdGlobalCSS();
	buildDevViewCSS();
	buildProdViewCSS();
	buildDevThirdPartyCSS();
	buildProdThirdPartyCSS();
	notify();
};

var buildDevGlobalCSS = function(){
	console.log('buildDevGlobalCSS');
	return gulp.src(cssGlobalSource)
	
	// init sourcemaps
	.pipe( sourcemaps.init() )
	
	// concatenate files (and save)
	.pipe( concat(cssDevGlobalFilename) )
	.pipe( gulp.dest(cssDestination) )
	
	// minify file
	.pipe( cleancss() )
	
	// write sourcemaps
	.pipe( sourcemaps.write() )
	
	// save
	.pipe( gulp.dest(cssDestination) );
};

var buildProdGlobalCSS = function(){
	console.log('buildProdGlobalCSS');
	return gulp.src(cssGlobalSource)
	
	// concatenate files (and save)
	.pipe( concat(cssProdGlobalFilename) )
	.pipe( gulp.dest(cssDestination) )
	
	// minify file
	.pipe( cleancss() )
	
	// save
	.pipe( gulp.dest(cssDestination) );
};

var buildDevViewCSS = function(){
	console.log('buildDevViewCSS');
	return gulp.src(cssViewSource)
	
	// init sourcemaps
	.pipe( sourcemaps.init() )
	
	// concatenate files (and save)
	.pipe( concat(cssDevViewFilename) )
	.pipe( gulp.dest(cssDestination) )
	
	// minify file
	.pipe( cleancss() )
	
	// write sourcemaps
	.pipe( sourcemaps.write() )
	
	// save
	.pipe( gulp.dest(cssDestination) );
};

var buildProdViewCSS = function(){
	console.log('buildProdViewCSS');
	return gulp.src(cssViewSource)
	
	// concatenate files (and save)
	.pipe( concat(cssProdViewFilename) )
	.pipe( gulp.dest(cssDestination) )
	
	// minify file
	.pipe( cleancss() )
	
	// save
	.pipe( gulp.dest(cssDestination) );
};

var buildDevThirdPartyCSS = function(){
	console.log('buildDevThirdPartyCSS');
	return gulp.src(cssThirdPartySource)
	
	// init sourcemaps
	.pipe( sourcemaps.init() )
	
	// concatenate files (and save)
	.pipe( concat(cssDevThirdPartyFilename) )
	.pipe( gulp.dest(cssDestination) )
	
	// minify file
	.pipe( cleancss() )
	
	// write sourcemaps
	.pipe( sourcemaps.write() )
	
	// save
	.pipe( gulp.dest(cssDestination) );
};

var buildProdThirdPartyCSS = function(){
	console.log('buildProdThirdPartyCSS');
	return gulp.src(cssThirdPartySource)
	
	// concatenate files (and save)
	.pipe( concat(cssProdThirdPartyFilename) )
	.pipe( gulp.dest(cssDestination) )
	
	// minify file
	.pipe( cleancss() )
	
	// save
	.pipe( gulp.dest(cssDestination) );
};


//
// JS methods
//

var buildJS = function(){
	buildDevAppJS();
	buildProdAppJS();
	buildDevThirdPartyJS();
	buildProdThirdPartyJS();
	notify();
};

var buildDevAppJS = function(){
	console.log('buildDevAppJS');
	return gulp.src(jsAppSource)
	
	// init sourcemaps
	.pipe( sourcemaps.init() )
	
	// concatenate files (and save)
	.pipe( concat(jsDevAppFilename) )
	.pipe( gulp.dest(jsDestination) )
	
	// minify file
	.pipe( uglify() )
		
	// write sourcemaps
	.pipe( sourcemaps.write() )
		
	// save
	.pipe( gulp.dest(jsDestination) );
};

var buildProdAppJS = function(){
	console.log('buildProdAppJS');
	return gulp.src(jsAppSource)
	
	// concatenate files (and save)
	.pipe( concat(jsProdAppFilename) )
	.pipe( gulp.dest(jsDestination) )
	
	// minify file
	.pipe( uglify() )
	
	// save
	.pipe( gulp.dest(jsDestination) );
};

var buildDevThirdPartyJS = function(){
	console.log('buildDevThirdPartyJS');
	return gulp.src(jsThirdPartySource)
	
	// init sourcemaps
	.pipe( sourcemaps.init() )
	
	// concatenate files (and save)
	.pipe( concat(jsDevThirdPartyFilename) )
	.pipe( gulp.dest(jsDestination) )
	
	// minify file
	.pipe( uglify() )
	
	// write sourcemaps
	.pipe( sourcemaps.write() )
	
	// save
	.pipe( gulp.dest(jsDestination) );
};

var buildProdThirdPartyJS = function(){
	console.log('buildProdThirdPartyJS');

	return gulp.src(jsThirdPartySource)
	
	// concatenate files (and save)
	.pipe( concat(jsProdThirdPartyFilename) )
	.pipe( gulp.dest(jsDestination) )
	
	// minify file
	.pipe( uglify() )
	
	// save
	.pipe( gulp.dest(jsDestination) );
};

var notify = function(){
	console.log('==============================');
	console.log(new Date());
	console.log('==============================');
};


