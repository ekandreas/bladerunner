<?php

namespace Bladerunner;

/**
 * Initialize the plugin inside WordPress wp-admin to check for errors.
 */
class Init
{
    /**
     * Check if the cache folder exists and is writable and assigns admin notice if not.
     *
     * @return void
     */
    public static function checkWriteableUpload()
    {
        $cache = Cache::path();

        if (!file_exists($cache)) {
            add_action('admin_notices', '\Bladerunner\Init::noticeCreateCache');
        } else {
            Cache::setPermissions();
            $is_writable = false;
            try {
                file_put_contents($cache.'/folder_writable.php', '<?php echo "folder writable!";');
                $is_writable = true;
            } catch (\Exception $ex) {
            }
            if (!$is_writable) {
                add_action('admin_notices', '\Bladerunner\Init::noticeWritableCache');
            }
        }
    }

    /**
     * Creates the public cache folder.
     */
    public static function createCacheDirectory()
    {
        wp_mkdir_p(Cache::path());
    }

    /**
     * Deletes the public cache folder.
     */
    public static function deleteCacheDirectory()
    {
        self::deleteDirectory(Cache::path());
    }

    /**
     * Helper function.
     * http://php.net/manual/en/function.rmdir.php#114183 - source.
     *
     * @param  string $dir
     *
     * @return bool
     */
    public static function deleteDirectory($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file") && !is_link($dir)) ? self::deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    /**
     * Echo admin notice inside wp-admin if cache folder don't exist.
     *
     * @return void
     */
    public static function noticeCreateCache()
    {
        $cache = Cache::path();
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
    public static function noticeWritableCache()
    {
        $cache = Cache::path();
        echo '<div class="error"> ';
        echo '<p><strong>Cache not writable</strong></p>';
        echo '<p>Bladerunner cache folder .cache in uploads not writable. Please make the folder writable for your web server!</p>';
        echo '<p>Eg, <i>chmod -R 777 '.$cache.'</i></p>';
        echo '</div>';
    }
}
