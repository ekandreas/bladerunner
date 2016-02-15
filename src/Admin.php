<?php

namespace Bladerunner;

/**
 * Handles cache functionalities.
 */
class Admin
{
    public static function view()
    {
        echo '<div class="wrap">';
        echo '<div id="icon-tools" class="icon32"></div>';
        echo '<h2>Bladerunner</h2>';

        Admin::actions();

        $cache_files = scandir(Cache::path());
        $count = sizeof($cache_files)-3;
        if ($count<0) {
            $count=0;
        }
        echo '<p>';
        echo $count . ' files in cache.';
        echo '<form method="POST">';
        echo '<input type="submit" class="button-primary" value="Empty cache" />';
        echo '<input type="hidden" name="action" value="empty_cache" />';
        echo '<input type="hidden" name="nonce" value="' . wp_create_nonce('bladerunner') . '" />';
        echo '</form>';
        echo '</p>';

        echo '</div>';
    }

    public static function actions()
    {
        $nonce = isset($_REQUEST['nonce']) ? wp_verify_nonce($_REQUEST['nonce'], 'bladerunner') : null;
        $action = isset($_REQUEST['action']) ? esc_attr($_REQUEST['action']) : null;
        if ($nonce&&$action) {
            if ($action=='empty_cache') {
                Cache::removeAllViews();
                echo '<div class="updated notice">';
                echo '<p>Cache is cleared.</p>';
                echo '</div>';
            }
        }
    }
}
