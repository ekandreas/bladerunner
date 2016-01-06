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
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_filter('template_include', [$this, 'path'], 999);
        add_action('init', [$this, 'init']);
    }

    /**
     * Adding {$type}_template to WP
     */
    public static function init()
    {
        $this->addTemplateFilters();
    }


    /**
     * The hook for template_include to override blade templating.
     *
     * @param  string $template
     *
     * @throws \Exception
     *
     * @return string
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

        $cache = self::cache();
        if (!file_exists($cache)) {
            throw new \Exception('Bladerunner: Cache folder does not exist.');
            return $template;
        }

        $search = [$views, '/', '.blade', '.php'];
        $replace = ['', '.', '', ''];
        $file = str_replace($search, $replace, $template);
        $file = trim($file, '.');

        if (!file_exists(get_stylesheet_directory().'/'.$file.'.blade.php')) {
            throw new \Exception("Bladerunner: Template file {$file}.blade.php is missing.");
            return $template;
        }

        $blade = new Blade($views, $cache);

        $view = $blade->view()->make($file);

        $id = (int)get_the_ID();
        $id = $id ? '-'.$id : '';

        $pathToCompiled = $cache.'/'.md5($view->getPath()).$id.'.compiled.php';

        $wp_debug = defined('WP_DEBUG') && WP_DEBUG;

        $expired = $wp_debug || (!file_exists($pathToCompiled)) || $blade->getCompiler()->isExpired($view->getPath());

        if ($expired) {
            $content = $view->render();

            $compilation_stamp = apply_filters('bladerunner/compilation_stamp', "\n\n<!-- Bladerunner page compiled ".date('Y-m-d H:i:s')." -->\n\n");

            $content .= $compilation_stamp;

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
     * @return string
     */
    public static function cache()
    {
        $result = wp_upload_dir()['basedir'];
        $result .= '/.cache';

        return apply_filters('bladerunner/cache', $result);
    }

    /**
     * Add template filters.
     */
    private function addTemplateFilters()
    {
        $types = [
            'index'      => 'index.blade.php',
            'home'       => 'index.blade.php',
            'single'     => 'single.blade.php',
            'page'       => 'page.blade.php',
            '404'        => '404.blade.php',
            'archive'    => 'archive.blade.php',
            'author'     => 'author.blade.php',
            'category'   => 'category.blade.php',
            'tag'        => 'tag.blade.php',
            'taxonomy'   => 'taxonomy.blade.php',
            'date'       => 'date.blade.php',
            'front-page' => 'front-page.blade.php',
            'paged'      => 'paged.blade.php',
            'search'     => 'search.blade.php',
            'single'     => 'single.blade.php',
            'singular'   => 'singular.blade.php',
            'attachment' => 'attachment.blade.php',
        ];

        $types = apply_filters('bladerunner/template_types', $types);

        if( $types ) {
            foreach ($types as $key => $type) {
                add_filter($key.'_template', function ($original) use ($type) {
                    if( locate_template( $type, false ) ) {
                        return $type;
                    }
                    else {
                        return $original;
                    }
                }, 99);
            }
        }

    }
}
