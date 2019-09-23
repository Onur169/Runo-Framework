const { dest, src, watch, parallel } = require('gulp');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
//const babel = require('gulp-babel');
const webpack = require('webpack-stream');

function jsify(cb) {

    return src('./js/main.js')
        /*
        .pipe(babel({
            presets: ['@babel/env']
        }))
        */
        .pipe(webpack({
            entry: {
                app: './js/main.js',
            },
            output: {
                filename: '[name].js',
            },
        }))
        .pipe(dest('./js/dist'));

    // body omitted
    cb();
}

function sassify(cb) {

    return src('./css/**/*.scss')
        .pipe(sass())
        .pipe(cleanCSS())
        .pipe(dest('./css/dist'));

    // body omitted
    cb();
}

exports.default = function () {

    // Or a composed task
    watch('./js/*.js', parallel(jsify));
    watch('./css/**/*.scss', parallel(sassify));

};