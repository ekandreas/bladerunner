<?php

namespace Bladerunner;

/**
 * Handles cache functionalities.
 */
class Cache
{
    /**
     * Add WordPress hooks.
     */
    public function __construct()
    {
        add_action('save_post', '\Bladerunner\Cache::removeAllViews');
    }

    /**
     * Return true if compilation expired.
     *
     * @param \Bladerunner\Blade       $blade
     * @param \Illuminate\View\Factory $view
     * @param string                   $path
     */
    public static function expired($blade, $view, $path)
    {
        $result = false;

        $wp_debug = defined('WP_DEBUG') && WP_DEBUG;

        $result = $wp_debug || $result;

        $result = (! file_exists($path)) || $result;

        $result = $blade->getCompiler()->isExpired($view->getPath()) || $result;

        return $result;
    }

    /**
     * Gets the cache folder for Bladerunner.
     */
    public static function path()
    {
        $result = wp_upload_dir()['basedir'];
        $result .= '/.cache';
        $result = realpath($result);
        return apply_filters('bladerunner/cache/path', $result);
    }

    /**
     * Remove all views in cache folder.
     */
    public static function removeAllViews()
    {
        $dir   = Cache::path();

        Cache::setPermissions();

        array_map('unlink', glob($dir."/*.php"));
    }

    /**
     * Setting cache folder to 775
     */
    public static function setPermissions()
    {
        $dir   = Cache::path();
        $permission = apply_filters('bladerunner/cache/permission', 777);
        try {
            chmod($dir, octdec($permission));
        } catch (\Exception $ex) {
        }
    }

    public static function storeTemplate($view, $content)
    {
        $dir = Cache::path();
        try {
            if (!file_put_contents($dir.'/'.$view.'.php', $content)) {
                throw new \Exception("Bladerunner: Can't write to cache folder $dir when creating Blade template ($view)");
            }
        } catch (\Exception $ex) {
            throw new \Exception("Bladerunner: Can't write to cache folder $dir when creating Blade template ($view). " . $ex->getMessage());
        }
    }
}
