<?php

use Illuminate\Contracts\Container\Container as ContainerContract;

/**
 * Setup Bladerunner options
 */
add_action('after_setup_theme', function () {
    /**
     * Bladerunner config
     */
    $paths = [
        'dir.stylesheet' => get_stylesheet_directory(),
        'dir.template' => get_template_directory(),
        'dir.upload' => wp_upload_dir()['basedir'],
        'uri.stylesheet' => get_stylesheet_directory_uri(),
        'uri.template' => get_template_directory_uri(),
    ];

    \Bladerunner\Config::repo([
            'view.compiled' => apply_filters('bladerunner/cache/path', "{$paths['dir.upload']}/.cache"),
            'view.paths' => \Bladerunner\Config::viewPaths(),
        ] + $paths);

    /**
     * Add Blade to Bladerunner container
     */
    Bladerunner\Container::current()->singleton('bladerunner.blade', function (ContainerContract $app) {
        $cachePath = Bladerunner\Config::repo('view.compiled');

        $makePath = apply_filters('bladerunner/cache/make', true);
        if ($makePath && !file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }

        (new \Bladerunner\BladeProvider($app))->register();
        return new \Bladerunner\Blade($app['view'], $app);
    });

    \Bladerunner\Container::current('blade')->compiler()->directive('controller', function () {
        return '<?php (new \Bladerunner\ControllerDebug(get_defined_vars())); ?>';
    });

    $extensions = apply_filters('bladerunner/extend', []);
    if ($extensions && is_array($extensions)) {
        foreach ($extensions as $extension) {
            if (is_callable($extension)) {
                \Bladerunner\Container::current('blade')->compiler()->extend($extension);
            }
        }
    }
});

/**
 * Init config
 */
Bladerunner\Container::current()->bindIf('config', Bladerunner\Repository::class, true);
