<?php

/**
 * Check if global helper function could be declared.
 */
if (!function_exists('bladerunner')) {
    /**
     * Global helper function to use in templates to render correct view
     * This will use the Illuminate Blade engine to render correct view in a standard WordPress template.
     *
     * @param string $view Name of the view with path, eg: views.pages.single
     * @param array  $data Any object data to use in the view template/file
     */
    function bladerunner($view, $data = [], $echo=true)
    {
        if (defined('WP_DEBUG') && true === WP_DEBUG) {
            $files = glob(\Bladerunner\Cache::path().'/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }

        $bladepath = apply_filters('bladerunner/template/bladepath', get_stylesheet_directory());
        $blade = new \Bladerunner\Blade($bladepath, \Bladerunner\Cache::path());

        $result = $blade->view()->make($view, $data)->render();

        if ($echo) {
            echo $result;
        }

        return $result;
    }
}

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
