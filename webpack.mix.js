// Javascript dependencies are compiled with Laravel Mix https://laravel.com/docs/5.5/mix
// See also: https://github.com/JeffreyWay/laravel-mix/tree/master/docs
let mix = require('laravel-mix');

mix
    // Make sure the public path is declared
    .setPublicPath('public')

    // Simple ES6 JavaScript
    .js('resources/js/hosting.js', 'public/application/js/hosting.js').vue()

// Other options:
// mix.sass, mix.js, mix.scripts, mix.stylus, mix.styles, mix.react, mix.webpackConfig, mix.copy, mix.copyDirectory,
// mix.browserSync, mix.disableNotifications
//
// Optional modifiers
// mix.js(...).version(), mix.js(...).extract(...)
//
// Accessing Info
// mix.inProduction()
