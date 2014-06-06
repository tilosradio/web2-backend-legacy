var distDir = 'dist';
var gulp =    require('gulp');
var clean =   require('gulp-clean');
var sass =    require('gulp-ruby-sass');
var concat =  require('gulp-concat');
var jshint  = require('gulp-jshint');
var connect  = require('gulp-connect');
var shell = require('gulp-shell');

gulp.task('views', function() {
  gulp.src('app/*')
  .pipe(gulp.dest(distDir + '/www'));  

  gulp.src('./app/views/**/*')
  .pipe(gulp.dest(distDir + '/www/views/'));
});


gulp.task('scripts', function() {
   gulp.src(["app/scripts/**/*.js"])
   .pipe(jshint('../.jshintrc'))
   .pipe(jshint.reporter('default'))
   .pipe(concat('tilos.js'))
   .pipe(gulp.dest(distDir + "/www/scripts"));

    gulp.src([
	"app/bower_components/angular/angular.js",
	"app/bower_components/angular-route/angular-route.js",
    "app/bower_components/angular-cookies/angular-cookies.js",
	"app/bower_components/angular-resource/angular-resource.js",
	"app/bower_components/angular-sanitize/angular-sanitize.js",
	"app/bower_components/textAngular/textAngular.js"
	])
   .pipe(concat('angular.js'))
   .pipe(gulp.dest(distDir + "/www/scripts"));
});


gulp.task('assets', function() {
  gulp.src([
     'app/template/**/*',
     'app/images/**/*',
     'app/styles/fonts/**',
     'app/jplayer/**/*'],
        {base: 'app'})
  .pipe(gulp.dest(distDir + '/www'));    
});

gulp.task('chat', function() {
  gulp.src(['chat/**/*'],{base : '.'})
  .pipe(gulp.dest(distDir + '/www'));    
});


gulp.task('php', function() {
  gulp.src([
     '../backend/init_autoloader.php',
     '../backend/config/**/*', 
     '../backend/data/**/*', 
     '../backend/module/Radio/**/*', 
     '../backend/module/RadioAdmin/**/*', 
     '../backend/module/RadioCommon/**/*', 
     '../backend/vendor/**/*'],{base: '../backend'})
  .pipe(gulp.dest(distDir + '/'));

  gulp.src([
     '../backend/www/admin.php'])
  .pipe(gulp.dest(distDir + '/www'));    

     
});


gulp.task('bower_components', function() {
  gulp.src(['app/bower_components/**/*'],{base: 'app'})
  .pipe(gulp.dest(distDir + '/www'));    
});


gulp.task('clean', function() {
  return gulp.src([distDir], {read: false})
    .pipe(clean());
});


gulp.task('build', ['clean'], function() {
    gulp.start('default');
});

gulp.task('default', function() {
    gulp.start('sass', 'scripts', 'assets','chat', 'php', 'bower_components','views', 'cachedir');
});


gulp.task('sass', function() {
  return gulp.src('app/styles/main.scss')
    .pipe(sass({ style: 'compressed',loadPath:'app/bower_components'}))
    //.pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest(distDir + '/www/styles'))
});

gulp.task('watch', function(){
   gulp.watch([distDir + "/www/**/*"], function(event) {
        return gulp.src(event.path)
            .pipe(connect.reload());
   });
 
  gulp.watch(["app/**/*"], ['default']);
  gulp.watch(["../backend/**/*"], ['php']);
});

gulp.task('connect', function() {
   connect.server({
    root: [distDir + '/www'],
    port: 9000,
    livereload: true
   });
});

gulp.task('cachedir', shell.task([
    'mkdir dist/data/DoctrineORMModule/Proxy',
    'chmod o+w dist/data/DoctrineORMModule/Proxy'
]))

gulp.task('server', ['connect','watch'], function() {});
