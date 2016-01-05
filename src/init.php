<?php

namespace Bladerunner;

/**
 * Initialize the plugin inside WordPress wp-admin to check for errors.
 */
class init
{
    public function __construct()
    {
        add_action('admin_init', '\Bladerunner\Init::check_writeable_upload');
    }

    /**
     * Check if the cache folder exists and is writable and assigns admin notice if not.
     *
     * @return void
     */
    public static function check_writeable_upload()
    {
        $cache = template::cache();

        if (!file_exists($cache)) {
            add_action('admin_notices', '\Bladerunner\Init::notice_create_cache');
        } else {
            $is_writable = @file_put_contents($cache.'/.folder_writable', 'true');
            if (!$is_writable) {
                add_action('admin_notices', '\Bladerunner\Init::notice_writable_cache');
            }
        }
    }

    /**
     * Creates the public cache folder.
     */
    public static function create_cache_directory()
    {
        wp_mkdir_p(Template::cache());
    }

    /**
     * Deletes the public cache folder.
     */
    public static function delete_cache_directory()
    {
        self::delete_directory(Template::cache());
    }

    /**
     * Helper function
     * http://php.net/manual/en/function.rmdir.php#114183 - source.
     *
     * @param $dir
     *
     * @return bool
     */
    public static function delete_directory($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file") && !is_link($dir)) ? self::delete_directory("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    /**
     * Echo admin notice inside wp-admin if cache folder doesnt exist.
     *
     * @return void
     */
    public static function notice_create_cache()
    {
        $cache = Template::cache();
        echo '<div class="error"> ';
        echo '<p><strong>Cache folder missing</strong></p>';
        echo '<p>Bladerunner needs a .cache -folder in uploads. Please create the folder and make it writable!</p>';
        echo '<p>Eg, <i>mkdir '.$cache.'</i></p>';
        echo '</div>';
    }

    /**
     * Echo admin notice inside wp-admin if cache folder not writable.
     *
     * @return void
     */
    public static function notice_writable_cache()
    {
        $cache = Template::cache();
        echo '<div class="error"> ';
        echo '<p><strong>Cache not writable</strong></p>';
        echo '<p>Bladerunner cache folder .cache in uploads not writable. Please make the folder writable for your web server!</p>';
        echo '<p>Eg, <i>chmod -R 777 '.$cache.'</i></p>';
        echo '</div>';
    }
}
