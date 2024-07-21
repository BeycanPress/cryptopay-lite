import gulp from 'gulp';
import * as nodeSass from 'sass';
import gulpSass from 'gulp-sass';
import minifyCSS from 'gulp-csso';
import minifyJS from 'gulp-uglify';
import concat from 'gulp-concat';
import autoprefixer from 'gulp-autoprefixer';
import sourcemaps from 'gulp-sourcemaps';
import include from 'gulp-include';
const sass = gulpSass(nodeSass);

const tasks = [
    'js-main',
    'js-blocks',
    'js-checkout',
    'scss-admin',
    'scss-main',
];

const watchs = [];

for (let index = 0; index < tasks.length; index++) {
    let task = tasks[index];
    let type = task.split("-")[0];
    let file = task.split("-")[1];

    if ( type === 'js' ) {
        gulp.task(task, () => {
            return gulp.src('src/js/'+file+'.js')
            .pipe(include()).on('error', console.log)
            .pipe(sourcemaps.init())
            .pipe(concat(file+'.min.js'))
            .pipe(minifyJS({
                mangle: false
            }))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest('assets/js'));
        });
        watchs.push(gulp.watch('src/js/'+file+'.js', { usePolling: true, interval: 1000 }, gulp.series(task)));
    } else {
        gulp.task(task, () => {
            return gulp.src('src/scss/'+file+'.scss')
            .pipe(sourcemaps.init())
            .pipe(sass())
            .pipe(autoprefixer())
            .pipe(concat(''+file+'.min.css'))
            .pipe(minifyCSS())
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest('assets/css'));
        });
        watchs.push(gulp.watch('src/scss/'+file+'.scss', { usePolling: true, interval: 1000 }, gulp.series(task)));
    }
}

watchs.push(gulp.watch('src/js/*.js', { usePolling: true, interval: 1000 }, gulp.series(tasks[0])));
    
gulp.task('watch', () => {
    for (let index = 0; index < watchs.length; index++) {
        watchs[index];
    }
});
    
gulp.task('default', async () => {
    tasks.push("watch");
    gulp.series(tasks)();
});

