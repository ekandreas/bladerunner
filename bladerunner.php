<?php
/*
Plugin Name:        Bladerunner
Plugin URI:         https://github.com/ekandreas/bladerunner
Description:        Blade template engine for WordPress
Version:            0.6.6
Author:             Andreas Ek
Author URI:         http://www.aekab.se/

License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

// Load Composer autoload if it exists.
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

require_once __DIR__.'/src/init.php';
require_once __DIR__.'/src/template.php';
require_once __DIR__.'/src/blade.php';

new Bladerunner\Init();
new Bladerunner\Template();

register_activation_hook(__FILE__, '\Bladerunner\init::create_cache_directory');
register_deactivation_hook(__FILE__, '\Bladerunner\init::delete_cache_directory');
