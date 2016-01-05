<?php

namespace Bladerunner;

/**
 * Handles the template include for blade templates.
 */
class template
{
    /**
     * Saves the path in case of double object instance.
     *
     * @var [type]
     */
    protected $path;

    /**
     * [__construct description].
     */
    public function __construct()
    {
        add_filter('template_include', [$this, 'path']);
        add_filter('index_template', function () { return 'index.blade.php'; });
        //add_filter( 'page_template', [ $model, 'getPath' ] );
        //add_filter( 'bp_template_include', [ $model, 'getPath' ] );
    }

    /**
     * The hook for template_include to override blade templating.
     *
     * @param [type] $template [description]
     *
     * @return [type] [description]
     */
    public function path($template)
    {
        if ($this->path) {
            return $this->path;
        }

        $template = apply_filters('bladerunner/get_post_template', $template);

        $views = get_stylesheet_directory();

        $cache = self::cache();
        if (!file_exists($cache))
        {
            throw new \Exception('Bladerunner: Cache folder does not exist.');
        }

        $search = [$views, '/', '.blade', '.php'];
        $replace = ['', '.', '', ''];
        $file = str_replace($search, $replace, $template);
        $file = trim($file, '.');

        if (!file_exists(get_stylesheet_directory().'/'.$file.'.blade.php')) {
            return $template;
        }

        $blade = new blade($views, $cache);

        $view = $blade->view()->make($file);

        $pathToCompiled = $cache.'/'.md5($view->getPath()).'.compiled.php';

        $wp_debug = defined('WP_DEBUG') && WP_DEBUG;

        $expired = $wp_debug || (!file_exists($pathToCompiled)) || $blade->getCompiler()->isExpired($view->getPath());

        if ( $expired ) {
            $content = $view->render();
            ob_start();
            echo $content;
            $content = ob_get_contents();
            ob_end_clean();

            file_put_contents($pathToCompiled, $content);
        }

        $this->path = $pathToCompiled;

        return $this->path;
    }

    /**
     * Gets the cache folder for Bladerunner.
     *
     * @return [type] [description]
     */
    public static function cache()
    {
        $result = wp_upload_dir()['basedir'];
        $result .= '/.cache';

        return apply_filters('bladerunner/cache', $result);
    }
}
