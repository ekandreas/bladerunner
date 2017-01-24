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
}
