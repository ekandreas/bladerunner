<?php
namespace Bladerunner;

/**
 * Initialize the plugin inside WordPress wp-admin to check for errors
 */
class Init {

	protected $cache;

	function __construct() {
		$this->cache = Template::cache();
		add_action( 'admin_init', [ $this, 'check_writeable_upload' ] );
	}

	/**
	 * Check if the cache folder exists and is writable and assigns admin notice if not
	 * @return void
	 */
	function check_writeable_upload() {

		if( !file_exists( $this->cache ) ) {
			add_action( 'admin_notices', [ $this, 'notice_create_cache' ] ); 
		}
		else {

			$is_writable = @file_put_contents( $this->cache . '/.folder_writable', "true" );
			if( !$is_writable ) {
				add_action( 'admin_notices', [ $this, 'notice_writable_cache' ] ); 
			}

		}

	}

	/**
	 * Echo admin notice inside wp-admin if cache folder doesnt exist.
	 * @return void
	 */
	function notice_create_cache() {
        echo '<div class="error"> ';
        echo '<p><strong>Cache folder missing</strong></p>';
        echo '<p>Bladerunner needs a .cache -folder in uploads. Please create the folder and make it writable!</p>';
        echo '<p>Eg, <i>mkdir ' . $this->cache . '</i></p>';
        echo '</div>'; 
    }

	/**
	 * Echo admin notice inside wp-admin if cache folder not writable.
	 * @return void
	 */
	function notice_writable_cache() {
        echo '<div class="error"> ';
        echo '<p><strong>Cache not writable</strong></p>';
        echo '<p>Bladerunner cache folder .cache in uploads not writable. Please make the folder writable for your web server!</p>';
        echo '<p>Eg, <i>chmod -R 777 ' . $this->cache . '</i></p>';
        echo '</div>'; 
    }

}
