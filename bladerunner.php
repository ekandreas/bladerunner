<?php
/*
Plugin Name:        Bladerunner
Plugin URI:         http://bladerunner.aekab.se
Description:        Laravel Blade template engine for WordPress
Version:            1.0.9
Author:             Andreas Ek
Author URI:         http://www.aekab.se/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

require_once __DIR__.'/src/Cache.php';
require_once __DIR__.'/src/Init.php';
require_once __DIR__.'/src/Template.php';
require_once __DIR__.'/src/Blade.php';

new Bladerunner\Cache();
new Bladerunner\Init();
new Bladerunner\Template();

register_activation_hook(__FILE__, '\Bladerunner\init::createCacheDirectory');
register_deactivation_hook(__FILE__, '\Bladerunner\init::deleteCacheDirectory');

/**
 * Check if global helper function could be declared
 */
if (!function_exists('bladerunner')) {
    /**
     * Global helper function to use in templates to render correct view
     * This will use the Illuminate Blade engine to render correct view in a standard WordPress template
     * @param  string $view Name of the view with path, eg: views.pages.single
     * @param  array  $data Any object data to use in the view template/file
     * @return void
     */
    function bladerunner($view, $data = [])
    {
        $blade = new \Bladerunner\Blade(get_stylesheet_directory(), \Bladerunner\Cache::path());
        echo $blade->view()->make($view, $data)->render();
    }
}
