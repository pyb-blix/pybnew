const gulp = require('gulp');
const terser = require('gulp-terser');
var uglifycss = require('gulp-uglifycss');

gulp.task('default',es);



function es(){

    //Front End js
    gulp.src('public/js/*.js').pipe(terser()).pipe(gulp.dest('public/min_js'));
    gulp.src('public/js/i18n/*.js').pipe(terser()).pipe(gulp.dest('public/min_js/i18n'));
    //Back End js
    gulp.src('public/admin_assets/dist/js/*.js').pipe(terser()).pipe(gulp.dest('public/admin_assets/dist/min_js'));

    //Front End CSS
    gulp.src('public/css/*.css').pipe(uglifycss()).pipe(gulp.dest('public/min_css'));
    gulp.src('public/css/slider/*.css').pipe(uglifycss()).pipe(gulp.dest('public/min_css/slider'));
    //Back End CSS
    gulp.src('public/admin_assets/dist/css/*.css').pipe(uglifycss()).pipe(gulp.dest('public/admin_assets/dist/min_css'));
    gulp.src('public/admin_assets/dist/css/skins/*.css').pipe(uglifycss()).pipe(gulp.dest('public/admin_assets/dist/min_css/skins'));
  
}
