const mix = require('laravel-mix');
require('dotenv').config();
const app_url = process.env.APP_URL;
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
// Override mix internal webpack output configuration
mix.config.webpackConfig.output = {
    // chunkFilename: 'js/build_component/[name].[chunkhash:8].js',
    chunkFilename: 'js/build_component/[name].js?id=[chunkhash]',
    // publicPath: '/public/', //For server
    publicPath: app_url + 'public/',
    // publicPath: app_url,
};
// mix.setPublicPath('');
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .version();
// mix.extract(['vue', 'jquery']);