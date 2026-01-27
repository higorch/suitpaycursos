let mix = require('laravel-mix');

mix.webpackConfig({
    watchOptions: {
        ignored: /node_modules|public|mix-manifest.json/,
    }
});

mix.copy('resources/assets', 'public/assets');
mix.js("resources/src/js/scripts.js", "public/assets/js/scripts.js").version();

mix.postCss("resources/src/css/styles.css", "public/assets/css/styles.css", [
    require("@tailwindcss/postcss"),
]).options({
    processCssUrls: false
}).version();