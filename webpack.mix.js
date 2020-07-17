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

mix .js('resources/js/main.js', 'public/build')
    .js('resources/js/programmer/edit.js', 'public/build/programmer')
    .version()
    .sass('resources/sass/main.scss', 'public/build')
    .sass('resources/sass/programmer/edit.scss', 'public/build/programmer')
    .sass('resources/sass/tutorial.scss', 'public/build')
    .copyDirectory('resources/webfonts', 'public/fonts')
    .sourceMaps(false);