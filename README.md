# viewmaker
A laravel package for generating view files via laravel artisan command


<h1>Features</h1>
<ul>
  <li>You can use it to generate view files</li>
  <li>You can use it to generate view files extending an already existing file</li>
  <li>You can use it to generate view files adding all the js and css in the public directory of your laravel app</li>
  <li>You can use it to generate multiple view files extending an already file</li>
  <li>You can use it to generate multiple view files adding all the js and css already found in the laravel public directory</li>
</ul>

<h1>Usage</h1>

<h4>composer require adyns/viewmaker</h4>

<p>Add service provider into your laravel providers array in config/app</p>
<b>Adyns\ViewMaker\ViewMakerServiceProvider::class</b>
<p> You are now ready to use the view maker commands</p>

<h1>Commands</h1>

<ul>
  <li>php artisan make:view [filename] - generate view file</li>
  <li>php artisan make:view [foldername].[filename] - generate folder then the view file</li>
  <li>php artisan make:view [filename] --extend=[filename]?[section to extend] - generate view file extending from an existing file</li>
  <li>php artisan make:view [filename] -css -js - generate view file adding all the js and css files in the public directory</li>
  <li>php artisan make:view [filename],[filename] - generate multiple view file</li>
</ul>


<h1>Examples</h1>
<ul>
  <li>php artisan make:view app.index</li>
  <li>php artisan make:view dashboard -js -css</li>
  <li>php artisan make:view app.index,sidenav,signup</li>
  <li>php artisan make:view app --extend=layouts.app?content</li>
</ul>


