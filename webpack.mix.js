let mix = require('laravel-mix')

mix.setPublicPath('dist')
    .js('resources/js/tool.js', 'js')
    .js('resources/js/field.js', 'js')
    .sass('resources/sass/tool.scss', 'css')
