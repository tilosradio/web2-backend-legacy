var distDir = 'frontend-php';
var gulp =    require('gulp');
var clean =   require('gulp-clean');
var concat =  require('gulp-concat');
var exec = require('child_process');


gulp.task('php-frontend', function() {
  gulp.src([
     'init_autoloader.php',
     'config/**/*', 
     'data/**/*', 
     'module/Radio/**/*', 
     'module/RadioCommon/**/*', 
     'vendor/**/*'],{base: '.'})
  .pipe(gulp.dest('frontend-php/'));

  gulp.src([
     'www/backend.php'])
  .pipe(gulp.dest('frontend-php/www'));    

     
});

gulp.task('php-admin', function() {
  gulp.src([
     'init_autoloader.php',
     'config/**/*', 
     'data/**/*', 
     'module/Radio/**/*', 
     'module/RadioCommon/**/*',
     'module/RadioAdmin/**/*', 
     'vendor/**/*'],{base: '.'})
  .pipe(gulp.dest('admin-php/'));
  gulp.src([
     'www/admin.php'])
  .pipe(gulp.dest('admin-php/www'));    

     
});

gulp.task('clean', function() {
    return gulp.src(['frontend-php.zip','admin-php.zip', 'frontend-php','admin-php'], {read: false})
    .pipe(clean());
});

gulp.task('build', ['php-admin','php-frontend'], function() {
    gulp.start('admin-zip');
    gulp.start('frontend-zip');
});

gulp.task('frontend-zip', function (cb) {
  exec.exec('zip -q -r frontend-php.zip frontend-php', function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

gulp.task('admin-zip', function(cb){
  exec.exec('zip -q -r admin-php.zip admin-php', function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

