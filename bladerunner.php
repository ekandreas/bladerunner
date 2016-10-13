<?php
/*
Plugin Name:        Bladerunner
Plugin URI:         http://bladerunner.elseif.se
Description:        Laravel Blade template engine for WordPress
Version:            1.5.1
Author:             Andreas Ek
Author URI:         https://www.elseif.se/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
} elseif (defined('WP_CONTENT_DIR') && file_exists(WP_CONTENT_DIR . '/vendor/autoload.php')) {
    require_once WP_CONTENT_DIR . '/vendor/autoload.php';
}

include_once 'src/Autoloader.php';

$loader = new Bladerunner\Autoloader();
$loader->addNamespace('Bladerunner', dirname( __FILE__ ) . '/src' );
$loader->register();

include_once 'plugin_globals.php';

register_activation_hook(__FILE__, '\Bladerunner\init::createCacheDirectory');
register_deactivation_hook(__FILE__, '\Bladerunner\init::deleteCacheDirectory');
