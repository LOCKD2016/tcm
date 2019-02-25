var to_dir="./template/default/html/";
var current_dir="./public/";
var gulp = require('gulp'),
    jade = require('gulp-jade'),
    stylus = require('gulp-stylus');

gulp.task('ts_jade', function() {
    gulp.src('./build/jade/template/**/*.jade')
        .pipe(jade({pretty: true}))
        .pipe(gulp.dest(current_dir+'html/'))
});

gulp.task('ts_stylus', function() {
    gulp.src('./build/stylus/app*.styl')
        .pipe(stylus())
        .pipe(gulp.dest(current_dir+'css/'))
});

//
//gulp.task('default', function() {
//    gulp.start('ts_jade','ts_stylus','watch');
//});

gulp.task('dev', function() {
    gulp.watch('build/jade/**/**/*', ['ts_jade']);
    gulp.watch('build/stylus/**/*.styl', ['ts_stylus']);
});

// PC后台
var elixir = require('laravel-elixir');
elixir.config.js.browserify.watchify.options.poll = true;
elixir.config.js.browserify.watchify.enabled = true;
elixir.config.sourcemaps = false;
// elixir.config.js.browserify.transformers.push({
//     name: 'vueify'
// });

//词句替代上面注释的作用
require('laravel-elixir-vueify');
require('laravel-elixir-livereload');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

require('./elixir-jade');
require('./elixir-stylus');

elixir(function(mix) {
    //编译css
    mix.stylus();
    mix.jade({
        baseDir: './resources',
        dest: '/assets/js/components',
        pretty: true,
        search: '*.jade',
        src: '/assets/jade/template/',
        extension: '.vue'
    });
    mix.jade({
        baseDir: './resources',
        dest: '/assets/js/components/module',
        pretty: true,
        search: '*.jade',
        src: '/assets/jade/module/',
        extension: '.vue'
    });

    mix.jade({
        baseDir: './resources',
        dest: '/views/auth',
        pretty: true,
        search: '*.jade',
        src: '/assets/jade/php/',
        extension: '.php'
    });

    //应用程序入口
    mix.browserify('main.js');
    //mix.version(['public/js/main.js']);
    mix.livereload();
});