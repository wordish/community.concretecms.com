// Javascript dependencies are compiled with Laravel Mix https://laravel.com/docs/5.5/mix
let mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        symlinks: false
    },
    externals: {
        jquery: 'jQuery',
        bootstrap: true,
        vue: 'Vue',
        moment: 'moment'
    },
    module: {
        rules: [
            { test: /\.html$/, loader: "underscore-template-loader" },
            {
                test: /\.jsx?$/,
                exclude: /(bower_components|node_modules\/v-calendar)/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: Config.babel()
                    }
                ]
            },
        ]
    }
});

mix.setResourceRoot('../');
mix.setPublicPath('../');

mix
    .js('assets/showcase_items/js/view.js', '../blocks/showcase_items/view.js')
    .js('assets/members_search/js/view.js', '../blocks/members_search/view.js')
    .sass('assets/members_search/scss/view.scss', '../blocks/members_search/view.css')
    .js('assets/karma/js/main.js', '../js/karma.js')
    .js('assets/teams/js/main.js', '../js/teams.js')
