<?php

namespace Bladerunner;

/**
 * Handles the template include for blade templates.
 */
class Template
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

        if (!$template) {
            return $template;
        }

        $template = apply_filters('bladerunner/get_post_template', $template);

        $views = get_stylesheet_directory();

        $cache = Cache::path();
        if (!file_exists($cache)) {
            throw new \Exception('Bladerunner: Cache folder does not exist.');
        }

        $search = [$views, '/', '.blade', '.php'];
        $replace = ['', '.', '', ''];
        $file = str_replace($search, $replace, $template);
        $file = trim($file, '.');

        if (!file_exists(get_stylesheet_directory().'/'.$file.'.blade.php')) {
            return $template;
        }

        $blade = new Blade($views, $cache);

        $view = $blade->view()->make($file);

        $compiled_path = $cache.'/'.md5($view->getPath()).'.compiled.php';

        if (Cache::expired($blade, $view, $compiled_path)) {
            $content = $view->render();

            $compilation_stamp = apply_filters('bladerunner/compilation_stamp', "\n\n<!-- Bladerunner page compiled ".date('Y-m-d H:i:s')." -->\n\n");

            $content .= $compilation_stamp;

            ob_start();
            echo $content;
            $content = ob_get_contents();
            ob_end_clean();

            file_put_contents($compiled_path, $content);
        }

        $this->path = $compiled_path;

        return $this->path;
    }
}
