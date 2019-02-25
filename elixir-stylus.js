/**
 * Created by lbbniu on 16/9/5.
 */
var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    notify = require('gulp-notify'),
    stylus = require('gulp-stylus');

/*
 |----------------------------------------------------------------
 | Gulp Jade Wrapper
 |----------------------------------------------------------------
 |
 | This task will compile your Jade files into your views folder.
 | You can make use of Blade variables in your jade files as well.
 | Examples see README.md
 |
 */

var Task = elixir.Task;

elixir.extend('stylus', function (options)
{
    options = {
        baseDir: './resources',
        dest: './public/css/',
        pretty: false,
        search: '*.styl',
        src: '/assets/stylus/'
    };

    var gulpSrc = options.baseDir + options.src;
    var dest = options.dest;
    new Task('stylus', function()
    {
        return gulp.src([gulpSrc+'index.styl',gulpSrc+'admin.styl'])
            .pipe(stylus())
            .pipe(gulp.dest(dest))
            .pipe(notify({
                title: 'Laravel Elixir',
                message: 'Stylus css compiled'
            }));
    }).watch([ options.baseDir + options.src + options.search ]);
});
