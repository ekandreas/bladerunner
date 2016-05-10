<?php
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
        echo $blade->view()->make($view, $data)->render();
    }
}
