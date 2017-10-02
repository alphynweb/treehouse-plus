var gulp = require('gulp');
var babel = require('gulp-babel');
var concat = require('gulp-concat');

gulp.task('babel', function() {
   gulp.src('./scripts/thp_fields.js')
           .pipe(babel())
           .pipe(concat('main.js'))
           .pipe(gulp.dest('./scripts'));
});

gulp.task('watch', function() {
    gulp.watch('./scripts/**/*.js', ['babel']);
});

gulp.task('default', function() {
    
});


