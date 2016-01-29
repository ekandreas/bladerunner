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
        $blade = new \Bladerunner\Blade(get_stylesheet_directory(), \Bladerunner\Cache::path());
        echo $blade->view()->make($view, $data)->render();
    }
}
