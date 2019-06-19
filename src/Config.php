<?php
namespace Bladerunner;

class Config
{
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string $key
     * @param mixed $default
     * @return mixed|\Bladerunner\Config
     * @copyright Taylor Otwell
     * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
     */
    public static function repo($key = null, $default = null)
    {
        if (is_null($key)) {
            return Container::current('config');
        }
        if (is_array($key)) {
            return Container::current('config')->set($key);
        }
        return Container::current('config')->get($key, $default);
    }

    /**
     * Gets all valid paths to views and templates through the installation
     * @return array
     */
    public static function viewPaths()
    {
        $bladePaths = apply_filters('bladerunner/template/bladepath', get_stylesheet_directory());
        if (!is_array($bladePaths)) {
            $bladePaths = [$bladePaths];
        }

        $stylesheetPath = get_stylesheet_directory();
        $templatePath = get_template_directory();

        $bladePaths[] = $stylesheetPath . DIRECTORY_SEPARATOR . 'views';
        $bladePaths[] = $stylesheetPath . DIRECTORY_SEPARATOR . 'views';

        $themePaths = [
            $stylesheetPath,
            $stylesheetPath . DIRECTORY_SEPARATOR . 'templates',
            $templatePath,
            $templatePath . DIRECTORY_SEPARATOR . 'templates',
            STYLESHEETPATH, // compability
            STYLESHEETPATH . DIRECTORY_SEPARATOR . 'templates',
            TEMPLATEPATH, // compability
            TEMPLATEPATH . DIRECTORY_SEPARATOR . 'templates',
        ];

        $bladePaths = array_merge($bladePaths, $themePaths);

        $viewPaths = array_values(
            collect($bladePaths, preg_replace('%[\/]?(templates)?[\/.]*?$%', '', $bladePaths))->unique()->toArray()
        );

        return $viewPaths;
    }
}
