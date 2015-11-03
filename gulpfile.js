var elixir = require('laravel-elixir');

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

elixir(function(mix) {
	mix.styles(['bootstrap.min.css', 'font-awesome.min.css', 'select2.min.css', 'app.css']);

	mix.scripts(['jquery.min.js', 'bootstrap.min.js', 'bootbox.min.js', 'select2.min.js', 'intel_*.js']);
});
