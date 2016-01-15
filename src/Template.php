<?php

namespace Bladerunner;

/**
 * Handles the template include for blade templates.
 */
class Template
{
    /**
     * Stores passed data.
     *
     * To pass data use the below example (make sure to run before "template_include" filter):
     * \Bladerunner\Template::$data['variableName'] = $value;
     *
     * @var array
     */
    public static $data = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        add_filter('template_include', [$this, 'path'], 999);
        add_action('template_redirect', [$this, 'init']);
        add_filter('get_search_form', [$this, 'getSearchForm'], 999);
    }

    /**
     * Adding {$type}_template to WP
     */
    public function init()
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
        $path = $template;

        if (!$template) {
            return $path;
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
        $view_file = trim($file, '.');

        $views = rtrim($views, '/');
        if (!strstr($template, $views)) {
            $template = $views.'/'.$template;
        }

        if (!file_exists($template)) {
            throw new \Exception("Bladerunner: Missing template file {$template}");
            return $template;
        }

        $path = $cache . '/' . $view_file . '.php';

        if (!file_exists($path)) {
            $content = "";
            $content .= "<?php\n";
            $content .= "/*\n";
            $content .= "   This file is rendered with love by Bladerunnner.\n";
            $content .= "   View file '$view_file', compiled at " . date('Y-m-d H:i:s') . "\n";
            $content .= "*/\n";
            $content .= "\$blade = new \Bladerunner\Blade('$views', '$cache');\n";
            $content .= "echo \$blade->view()->make('$view_file', json_decode('" . json_encode(self::$data) . "', true))->render();\n";
            Cache::storeTemplate($view_file, $content);
        }

        return $path;
    }

    /**
     * Get Blade tempalte for search form when using get_search_form()
     *
     * @param  string $searchform Default search form markup
     *
     * @return bool               Always returns false (to prevent any other output than the include)
     */
    public function getSearchForm($searchform)
    {
        if (!file_exists(get_template_directory() . '/searchform.blade.php')) {
            return $searchform;
        }

        $bladerunner = new self();
        $templatePath = $bladerunner->path('searchform.blade.php');
        include $templatePath;
        unset($bladerunner);

        return false;
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

        if ($types) {
            foreach ($types as $key => $type) {
                add_filter($key.'_template', function ($original) use ($type) {
                    if (locate_template([$type], false)) {
                        return $type;
                    } else {
                        return $original;
                    }
                });
            }
        }
    }
}
