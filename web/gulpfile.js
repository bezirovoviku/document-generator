var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.less('main.less');
    mix.scriptsIn('resources/assets/js', 'public/js/app.js');
});
