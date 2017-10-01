var gulp = require('gulp');
var babel = require('gulp-babel');

gulp.task('babel', function() {
   gulp.src('scripts/thp_fields.js')
           .pipe(babel())
           .pipe(gulp.dest('test'));
});

gulp.task('default', function() {
    
});


