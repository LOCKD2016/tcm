/**
 * Created by lbbniu on 16/9/5.
 */
var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    jade = require('gulp-jade'),
    rename = require('gulp-rename'),
    notify = require('gulp-notify'),
    cached = require('gulp-cached'),
    changed = require('gulp-changed'),
    _ = require('underscore');

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

elixir.extend('jade', function (options)
{
    options = _.extend({
        baseDir: './resources',
        dest: '/views/',
        pretty: true,
        search: '**/*.jade',
        src: '/jade/',
        extension: '.vue'
    }, options);

    var gulpSrc = options.baseDir + options.src + options.search;
    var gulpDest = options.baseDir + options.dest;
    //jade 编译配置选项
    var jadeOptions = _.pick(
        options,
        'filename',
        'doctype',
        'pretty',
        'self',
        'debug',
        'compileDebug',
        'compiler'
    );
    new Task('jade', function()
    {
        return gulp.src(gulpSrc)
            .pipe(jade(jadeOptions))
            .pipe(rename(function (path) {
                path.extname = options.extension;
            }))
            .pipe(cached('jade'))
            .pipe(changed(gulpDest))
            .pipe(gulp.dest(gulpDest))
            .pipe(notify({
                title: 'Laravel Elixir',
                message: 'Jade templates compiled'
            }));
    }).watch([ gulpSrc ]);
});
