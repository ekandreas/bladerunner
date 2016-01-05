<?php
/*
Plugin Name:        Bladerunner
Plugin URI:         http://bladerunner.aekab.se
Description:        Laravel Blade template engine for WordPress
Version:            0.7.1
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
