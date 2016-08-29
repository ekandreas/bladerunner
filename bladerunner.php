<?php
/*
Plugin Name:        Bladerunner
Plugin URI:         http://bladerunner.elseif.se
Description:        Laravel Blade template engine for WordPress
Version:            1.5
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

require_once __DIR__.'/src/Cache.php';
require_once __DIR__.'/src/Init.php';
require_once __DIR__.'/src/Template.php';
require_once __DIR__.'/src/Blade.php';
require_once __DIR__.'/src/Globals.php';
require_once __DIR__.'/src/Extension.php';
require_once __DIR__.'/src/CompilerExtensions.php';
require_once __DIR__.'/src/Admin.php';
require_once __DIR__.'/src/WPCompiler.php';

add_action('save_post', 'Bladerunner\Cache::removeAllViews');
add_action('admin_init', 'Bladerunner\Init::checkWriteableUpload');

if (apply_filters('bladerunner/templates/handler', false)) {
    $bladerunner_templates = new Bladerunner\Templates();
    add_filter('template_include', [$bladerunner_templates, 'templateFilter'], 999);
    add_action('template_redirect', [$bladerunner_templates, 'addPageTemplateFilters']);
}

add_action('admin_menu', function () {
    add_submenu_page(
        'tools.php',
        'Bladerunner',
        'Bladerunner',
        'manage_options',
        'bladerunner-admin-page',
        'Bladerunner\Admin::view');
});
 
register_activation_hook(__FILE__, '\Bladerunner\init::createCacheDirectory');
register_deactivation_hook(__FILE__, '\Bladerunner\init::deleteCacheDirectory');
