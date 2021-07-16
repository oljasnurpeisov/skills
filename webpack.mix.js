const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

mix
    .scripts(
        [
            'resources/js/assets/ajax-select.js',
        ], 'public/assets/js/ajax-select.js')

    .scripts(
        [
            'resources/js/assets/ajax-select-old.js',
        ], 'public/assets/js/ajax-select-old.js')

    .scripts(
        [
            'resources/js/assets/avatar-dropzone.js',
        ], 'public/assets/js/avatar-dropzone.js')

    .scripts(
        [
            'resources/js/assets/calculator.js',
        ], 'public/assets/js/calculator.js')

    .scripts(
        [
            'resources/js/assets/course-edit.js',
        ], 'public/assets/js/course-edit.js')

    .scripts(
        [
            'resources/js/assets/dropzone.js',
        ], 'public/assets/js/dropzone.js')

    .scripts(
        [
            'resources/js/assets/kalkan.js',
        ], 'public/assets/js/kalkan.js')

    .scripts(
        [
            'resources/js/assets/lesson-create.js',
        ], 'public/assets/js/lesson-create.js')

    .scripts(
        [
            'resources/js/assets/professions-group.js',
        ], 'public/assets/js/professions-group.js')

    .scripts(
        [
            'resources/js/assets/scripts.js',
        ], 'public/assets/js/scripts.js')

    .scripts(
        [
            'resources/js/assets/service.js',
        ], 'public/assets/js/service.js')

    .scripts(
        [
            'resources/js/assets/test-constructor.js',
        ], 'public/assets/js/test-constructor.js')

    .scripts(
        [
            'resources/js/assets/visually-impaired-tools.js',
        ], 'public/assets/js/visually-impaired-tools.js');
