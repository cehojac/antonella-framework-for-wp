const mix = require('laravel-mix');
mix.js('resources/js/app.js', 'assets/js')
    .postCss('resources/css/app.css', 'assets/css', [
        //
    ]);